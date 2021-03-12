<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>
<style>
    * {
        font-size: 24px;
    }
</style>
<h2 class="tableth2">
    <?php echo $title; ?>
    <span class="material-icons close" style="font-size:50px">clear</span>
</h2>
<?php date_default_timezone_set('Asia/Seoul'); ?>
<style>
    * {
        font-size: 32px;
    }
    
    th {
        width: 40%
    }
</style>

<div class="form_5">


    <?php
    foreach ($RList as $i => $row) {
        $num = $i + 1;
        if ($row->SERIES_NM != "합계") {
    ?>
            <form method="post" name="menuUpdateForm" id="menuUpdateForm" action="<?php echo base_url('tablet/cu_update_insert'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="IDX" value="<?= $row->TRANS_IDX ?>">
                <div class="bc_header none_padding">
                    <table class="nhover">
                        <tbody>
                            <tr>
                                <th>시리즈</th>
                                <td colspan="3"><input type="text" name="series" class="input_100" disabled value="<?= $row->SERIES_NM ?>"></td>
                            </tr>
                            <tr>
                                <th>품명</th>
                                <td colspan="3"><input type="text" name="name" class="input_100" disabled value="<?= $row->ITEM_NAME ?>"></td>
                            </tr>
                            <tr>
                                <th>색상</th>
                                <td colspan="3"><input type="text" name="color" class="input_100" disabled value="<?= $row->COLOR ?>"></td>
                            </tr>
                            <tr>
                                <th>완료수량</th>
                                <td colspan="3"><input type="text" name="QTY" class="input_100 right" value="<?php echo $row->ORDER_QTY; ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
    <?php }
    } ?>


    <!--a href="http://rainshop.ivyro.net/rain_code3/PLN/index" class="alink" style="float:left;">전체코드보기</a-->
    <span class="btni btn_right add_form form_submit" data-hidx="3">입력</span>

</div>
<script>
    $(".add_form").on("click", function() {
        $("#menuUpdateForm").submit();
    });
</script>