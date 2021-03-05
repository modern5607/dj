<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<h2>
    <?php echo $title; ?>
    <span class="material-icons close">clear</span>
</h2>
<?php date_default_timezone_set('Asia/Seoul'); ?>


<div class="formContainer">
    <div style="background:#eceff5; padding:20px;" class="gsflexst">
        <div>
            <label for="s1">시리즈</label>
            <select name="s1" id="s1" class="form_input select_call" style="width:100px;">
                <option value="">시리즈</option>
                <?php foreach ($SERIES as $s_h) { ?>
                    <option value="<?php echo $s_h->IDX; ?>"><?php echo $s_h->SERIES_NM; ?></option>
                <?php } ?>
            </select>
            <label for="s2">품명</label>
            <input type="text" name="s2" id="s2" class="form_input" autocomplete="off" style="width:120px;">
            <!--select name="s2" id="s2" class="form_input select_call" style="width:120px;">
				<option value="">품명</option>
			</select>
			<select name="s3" id="s3" class="form_input select_call" style="width:120px;">
				<option value="">색상</option>
			</select-->
            <button class="sh_submit"><i class="material-icons">search</i></button>
        </div>
        <lavel>등록일
            <input type="text" name="transdate" class="calendar" value="<?= (empty($NDATE) || $NDATE == "") ? date("Y-m-d") : $NDATE ?>" size="15" autocomplete="off" style="border: 1px solid #ddd; padding: 5px 7px;" />
        </lavel>
    </div>

    <form name="ajaxform" id="ajaxform">
        <input type="hidden" name="mod" value="<?php echo $mode ?>">
        <div class="register_form">



            <fieldset class="form_3">

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>품명</th>
                            <th>수량</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody style="text-align:center;">
                        <tr>
                            <td colspan="5" style="text-align:center; color:#999;"> 시리즈를 선택하세요 </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>

        </div>
        <div class="bcont" style="padding:15px 0; text-align:center;">
            <span id="loading"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></span>
            <?php
            if (isset($data)) { //수정인경우
            ?>
                <button type="button" class="modBtn blue_btn">수정</button>
                <input type="hidden" name="IDX" value="<?php echo $data->IDX; ?>">
            <?php
            } else {
            ?>
                <button type="button" class="submitBtn blue_btn">입력</button>
            <?php
            }
            ?>
        </div>

    </form>

</div>




<script type="text/javascript">
    $("input").attr("autocomplete", "off");

    var HIDX = "<?php echo $HIDX ?>"; //수주idx
    $("#s1").on("change", function() {
        var idx = $(this).val();
        $.post("<?php echo base_url('PLN/ajax_set_series_select') ?>", {
            idx: idx
        }, function(data) {
            //if(data.items.length > 0){
            $("#s2 option.xxx").remove();
            $("#s3 option.xxx").remove();
            $.each(data.items, function(key, val) {
                $("#s2").append("<option value='" + val.IDX + "' class='xxx'>" + val.ITEM_NAME +
                    "</option>");
            });
            $.each(data.sdetail, function(key, val) {
                $("#s3").append("<option value='" + val.IDX + "' class='xxx'>" + val.COLOR +
                    "</option>");
            });
            //}
        }, "JSON");
    });


    $(".sh_submit").on("click", function() {
        var s1 = $("#s1").val();
        var s2 = $("#s2").val();
        var type = 'SH';

        $.ajax({
            url: "<?php echo base_url('ACT/ajax_itemsindex_pop') ?>",
            type: "POST",
            datatype: "JSON",
            data: {
                s1: s1,
                s2: s2,
                type: type
            },
            success: function(data) {

                var dataset = JSON.parse(data);
                var html = "";
                if (dataset.length > 0) {
                    $.each(dataset, function(index, info) {

                        html += "<tr>";
                        html += "<td>" + (index + 1) + "</td>";
                        html += "<td style='text-align:left'>" + info.ITEM_NAME + "</td>";
                        //html += "<td>"+info.COLOR+"</td>";
                        html += "<td>";
                        html +=
                            "	<input type='text' autocomplete='off' name='QTY[]' class='form_select' size='4' value='' />";
                        html += "	<input type='hidden' name='ITEM_IDX[]' value='" + info.IDX +
                            "' />";
                        html += "	<input type='hidden' name='ITEM_NM[]' value='" + info
                            .ITEM_NAME + "' />";
                        html += "	<input type='hidden' name='JT_QTY[]' value='" + info.JT_QTY + "' />";
                        //html += "	<input type='hidden' name='H_IDX[]' value='"+HIDX+"' />";				
                        html += "</td>";
                        html +=
                            "<td><input type='text' autocomplete='off' name='REMARK[]' class='form_select' value='' /></td>";
                        html += "</tr>";

                    });
                    html +=
                        "<script type='text/javascript'>$('.calendar').datetimepicker({format:'Y-m-d',timepicker:false,lang:'ko-KR'});<\/script>";
                } else {
                    html +=
                        "<tr><td colspan='4' style='text-align:center; color:#999;'>품목이 없습니다.</td></tr>"
                }
                $(".form_3 table tbody").html(html);
            }

        });

    });


    $(".submitBtn").on("click", function() {
        var trc = $('.form_3 tbody tr').length;
        var count = 0;
        var max = <?php echo ($component[0]->STOCK) ?>;

        for (var i = 0; i < trc; i++) {
            var stock = $("input[name^='QTY']").eq(i).val();

            if (stock === '0') {
                alert('빈칸으로 남겨 놓으시거나 1개 이상의 수량을 입력해 주세요');
                $("input[name^='QTY']").eq(i).focus();
                return;
            }

            var acc = $("input[name^='JT_QTY']").eq(i).val()
            count += (stock * acc)
        };
        if (max < count) {
            alert('자재가 부족합니다.')
            return false;
        }
        if (count == 0) {
            alert('입력하지 않았습니다');
            return;
        }

        var formData = new FormData($("#ajaxform")[0]);
        var $this = $(this);
        formData.append('transdate', $("input[name='transdate']").val());

        $.ajax({
            url: "<?php echo base_url('ACT/ajax_act_items_trans_insert') ?>",
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





    $("input[name='ACT_DATE'],#DEL_DATE,.calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });
</script>