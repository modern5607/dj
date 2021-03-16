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

    <form method="post" name="menuUpdateForm" id="menuUpdateForm" action="<?php echo base_url('tablet/form_a11_update'); ?>">

        <input type="hidden" name="OIDX" value="<?php echo $OIDX; ?>">
        <input type="hidden" name="A1" value="<?php echo date("Y-m-d"); ?>">
        <input type="hidden" name="TABLET" value="TABLET">

        <div class="none_padding">

            <div class="">
                <table class="nhover">
                    <tbody>
                        <?php foreach ($List as $i => $row) {
                        ?>
                            <tr>
                                <th>품명</th>
                                <td colspan="3"><input type="text" name="name" class="form_input input_5 sbpop" disabled value="<?= $row->ITEM_NM ?>"></td>
                            </tr>
                            <tr>
                                <th>색상</th>
                                <td colspan="3"><input type="text" name="color" class="form_input input_5 sbpop" disabled value="<?= $row->COLOR ?>"></td>
                            </tr>
                            <tr>
                                <th>수주수량</th>
                                <td colspan="3"><input type="text" name="QTY" class="form_input input_5 sbpop right" disabled value="<?php echo $row->QTY; ?>"></td>
                            </tr>
                            <tr>
                                <th>1급</th>
                                <td colspan="3"><input type="text" name="A2" class="form_input input_5 sbpop right" style="border-bottom:1px solid #bbb;" readonly value="<?php echo $row->IN_QTY; ?>"></td>
                            </tr>
                            <tr>
                                <th>2급</th>
                                <td colspan="3"><input type="text" name="A3" class="form_input input_5 sbpop right" style="border-bottom:1px solid #bbb;" value=""></td>
                            </tr>
                            <tr>
                                <th>파손</th>
                                <td colspan="3"><input type="text" name="A4" size="6" class="form_input input_5 sbpop right" style="border-bottom:1px solid #bbb;" value=""></td>
                            </tr>
                            <tr>
                                <th>시유</th>
                                <td colspan="3"><input type="text" name="A5" size="6" class="form_input input_5 sbpop right" style="border-bottom:1px solid #bbb;" value=""></td>
                            </tr>
                            <tr>
                                <th>비고</th>
                                <td colspan="3"><input type="text" name="A6" class="form_input input_5 sbpop" style="border-bottom:1px solid #bbb;" value=""></td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>

            </div>

            <span style="margin-top:10px;" class="btni_5 btn_right add_form" data-hidx="3">입력</span>

        </div>
    </form>
</div>
</div>




<script type="text/javascript">
    $("input").attr("autocomplete", "off");

    $(".add_form").on("click", function() {
        $("#menuUpdateForm").submit();
    });


    var orgNum = $("input[name=A2]").val();
    $("input[name=A3],input[name=A4],input[name=A5]").on("change", function() {
        var a3 = $("input[name=A3]").val();
        var a4 = $("input[name=A4]").val();
        var a5 = $("input[name=A5]").val();

        var setNum = orgNum - a3 - a4 - a5;
        $("input[name=A2]").val(setNum);
    });

    $(document).on("click", ".del_file", function() {
        num = num - 1;
        $(this).parents("tr").remove();
    });
</script>