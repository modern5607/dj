<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>
<h2 class="tableth2" >
    <?php echo $title; ?>
    <span class="material-icons close" style="font-size:50px">clear</span>
</h2>
</style>

<div class="formContainer">

    <form name="ajaxform" id="ajaxform">
        <div class="register_form_5">
            <fieldset class="form_5">
                <legend>이용정보</legend>
                <table class="nhover">
                    <tbody>
                        <tr>
                            <input type="hidden" name="idx" id="idx" value="<?= $info->IDX ?>">
                            <th>시리즈</th>
                            <td>
                                <input type="text" name="SERIES" id="" value="<?= $info->SERIES_NM ?>" class="form_input5 input_5" disabled>
                            </td>
                        </tr>
                        <tr>
                            <th>품명</th>
                            <td>
                                <input type="text" value="<?= $info->ITEM_NAME ?>" class="form_input5 input_5" disabled>
                            </td>
                        </tr>
                        <tr>
                            <th>지시수량</th>
                            <td>
                                <input type="text" value="<?= $info->ORDER_QTY ?>" class="form_input5 input_5" disabled>
                            </td>
                        </tr>
                        <tr>
                            <th>남은 지시수량</th>
                            <td>
                                <input type="text" value="<?= $info->ORDER_QTY - $info->PROD_QTY ?>" class="form_input5 input_5" disabled>
                            </td>
                        </tr>
                        <tr>
                            <th>완료수량</th>
                            <td>
                                <input type="number" name="FNSH_QTY" value="<?= $info->ORDER_QTY - $info->PROD_QTY ?>" class="form_input5 input_5" style="border-bottom:1px solid #ccc;">
                            </td>
                        </tr>



                    </tbody>
                </table>
            </fieldset>

            <div class="bcont">
                <span id="loading"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></span>
                
                <button type="button" class="submitBtn t_blue_btn">입력</button>
                
            </div>

        </div>

    </form>

</div>


<script>
    $(".submitBtn").on("click", function() {

        var formData = new FormData($("#ajaxform")[0]);
        var $this = $(this);


        $.ajax({
            url: "<?php echo base_url('/Tablet/add_jh_order') ?>",
            type: "POST",
            data: formData,
            //asynsc : true,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $this.hide();
                $("#loading").show();
            },
            success: function(data) {

                var jsonData = JSON.parse(data);
                if (jsonData.status == "ok") {

                    setTimeout(function() {
                        alert(jsonData.msg);
                        $(".ajaxContent").html('');
                        $("#pop_container").fadeOut();
                        $(".info_content").css("top", "-50%");
                        $("#loading").hide();
                        location.reload();

                    }, 1000);

                    chkHeadCode = false;

                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });
    });
</script>