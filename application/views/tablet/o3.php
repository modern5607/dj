<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<div class="body_cont_float2">

    <div class="tbl-content">

        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>시리즈</th>
                    <th>품명</th>
                    <th>색상</th>
                    <th>지시수량</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($RList)) { ?>
                    <?php
                    $totalQty = 0;
                    $count = 0;
                    foreach ($RList as $i => $row) {
                        $num = $i + 1;
                        if ($row->SERIES_NM == "합계") {
                            $totalQty += $row->ORDER_QTY;
                            $count += $row->TRANS_IDX;
                        } else {
                    ?>

                            <tr>
                                <td class="cen"><?php echo $num; ?></td>
                                <td><?php echo $row->SERIES_NM; ?></td>
                                <td><a class="btni btn_right add_itemnum" data-type="<?php echo $this->data['subpos']; ?>" href="#"><?php echo $row->ITEM_NAME; ?></a></td>
                                <td><?php echo $row->COLOR; ?></td>
                                <td class="right"><?php echo number_format($row->ORDER_QTY); ?></td>
                            </tr>

                        <?php }
                    }
                    if ($count != 0) {
                        ?>
                        <tr style="background:#f3f8fd;" class="nhover">
                            <td colspan="2" style="text-align:right"><strong>건수</strong></td>
                            <td style="text-align:right"><?php echo number_format($count); ?></td>
                            <td colspan="1" style="text-align:right"><strong>합계</strong></td>
                            <td style="text-align:right"><?php echo number_format($totalQty); ?></td>
                        </tr>
                    <?php
                    }
                }
                if (empty($RList) || $count == 0) {
                    ?>
                    <tr>
                        <td colspan="10" style='color:#999; padding:40px 0;'>등록된 작업지시가 없습니다.</td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
            </tfoot>
        </table>


    </div>


</div>




<div id="pop_container">

    <div class="info_content" style="height:auto;">
        <div class="ajaxContent">

            <!-- 데이터 -->

        </div>
    </div>

</div>



<script type="text/javascript">
    $(".add_itemnum").on("click", function() {

        var type = $(this).data("type");
        var selectedDate = "<?= empty($NDATE) ? "" : $NDATE; ?>";
        $(".ajaxContent").html('');

        $("#pop_container").fadeIn();
        $(".info_content").animate({
            top: "50%"
        }, 500);

        $.ajax({
            url: "<?php echo base_url('TABLET/ajax_invenNum_form') ?>",
            type: "POST",
            dataType: "HTML",
            data: {
                mode: "add",
                type: type,
                date: selectedDate
            },
            success: function(data) {
                $(".ajaxContent").html(data);
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        })

    });


    $(".del_items").on("click", function() {
        var idx = $(this).data("idx");

        if (confirm('삭제하시겠습니까?') !== false) {

            $.get("<?php echo base_url('ORD/ajax_del_inven_order') ?>", {
                idx: idx
            }, function(data) {
                if (data.status != "") {
                    alert(data.msg);
                    location.reload();
                }
            }, "JSON");
        }
    });


    $("input[name='sdate'],input[name='edate']").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });


    $(document).on("click", "h2 > span.close", function() {

        $(".ajaxContent").html('');
        $("#pop_container").fadeOut();
        $(".info_content").css("top", "-50%");

    });
    //-->
</script>