<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>
<style>
    
</style>
<h2 class="tableth2">
    <?php echo $title; ?>
    <span class="material-icons close" style="font-size:50px">clear</span>
</h2>
<?php date_default_timezone_set('Asia/Seoul'); ?>

<div class="register_form_5">
<div class="form_5">


    <?php
    foreach ($RList as $i => $row) {
        $num = $i + 1;
        if ($row->SERIES_NM != "합계") {
    ?>
            <form method="post" name="menuUpdateForm" id="menuUpdateForm" action="<?php echo base_url('tablet/cu_update_insert'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="IDX" value="<?= $row->TRANS_IDX ?>">
                <div class="none_padding">
                    <table class="nhover">
                        <tbody>
                            <tr>
                                <th>시리즈</th>
                                <td colspan="3"><input type="text" name="series" class="form_input5 input_5 cupop" disabled value="<?= $row->SERIES_NM ?>"></td>
                            </tr>
                            <tr>
                                <th>품명</th>
                                <td colspan="3"><input type="text" name="name" class="form_input5 input_5 cupop" disabled value="<?= $row->ITEM_NAME ?>"></td>
                            </tr>
                            <tr>
                                <th>색상</th>
                                <td colspan="3"><input type="text" name="color" class="form_input5 input_5 cupop" disabled value="<?= $row->COLOR ?>"></td>
                            </tr>
                            <tr>
                                <th>완료수량</th>
                                <td colspan="3"><input type="text" name="QTY" class="form_input5 input_5 cupop right" value="<?php echo $row->ORDER_QTY; ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
    <?php }
    } ?>


    <span class="btni_5 btn_right add_form form_submit" data-hidx="3">입력</span>

</div>
</div>
<script>
    $(".add_form").on("click", function() {
        $("#menuUpdateForm").submit();
    });
</script>