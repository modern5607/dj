<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<div class="body_cont_float2">
    <ul>
        <li style="width:430px">

            <div id="" class="bc_search">
                <form>
                    <input type='hidden' name='n' value='1' />
                    <input type='hidden' name='v1' value='<?= $str['v1'] ?>' />
                    <input type='hidden' name='component_nm' value='<?= $str['component_nm'] ?>' />

                    <label for="sdate">작업지시일</label>
                    <input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "") ? $str['sdate'] : date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y"))); ?>" size="12" /> ~

                    <input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "") ? $str['edate'] : date("Y-m-d"); ?>" size="12" />

                    <button class="search_submit"><i class="material-icons">search</i></button>
                </form>
            </div>

            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>작업지시일</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($List)) {
                            foreach ($List as $i => $row) {
                                $no = $pageNum + $i + 1;
                        ?>

                                <tr <?php echo ($NDATE == $row->TRANS_DATE) ? "class='over'" : ""; ?>>
                                    <td class="cen"><?php echo $no; ?></td>
                                    <td class="cen"><a href='<?php echo base_url('ORD/o4/') . $row->TRANS_DATE . "?sdate=" . $str['sdate'] . "&" . "edate=" . $str['edate']
                                                                ?>'><?php echo $row->TRANS_DATE; ?></a></td>
                                </tr>

                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" style='color:#999; padding:40px 0;'>등록된 작업지시가 없습니다.</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                </table>

            </div>
            <div class="pagination">
                <?php echo $this->data['pagenation']; ?>
                <?php
                if ($this->data['cnt'] > 20) {
                ?>
                    <div class="limitset">
                        <select name="per_page">
                            <option value="20" <?php echo ($perpage == 20) ? "selected" : ""; ?>>20</option>
                            <option value="50" <?php echo ($perpage == 50) ? "selected" : ""; ?>>50</option>
                            <option value="80" <?php echo ($perpage == 80) ? "selected" : ""; ?>>80</option>
                            <option value="100" <?php echo ($perpage == 100) ? "selected" : ""; ?>>100</option>
                        </select>
                    </div>
                <?php
                }
                ?>
            </div>


        </li>

        <li style="width:calc(100% - 430px);">

            <div id="" class="bc_search gsflexst" style="background:#f8f8f8;">
                <form>
                    <input type='hidden' name='sdate' value='<?= $str['sdate'] ?>' />
                    <input type='hidden' name='edate' value='<?= $str['edate'] ?>' />

                    <label for="v1">시리즈</label>
                    <select name="v1">
                        <option value="">전체</option>
                        <?php
                        foreach ($SERIES as $row) {
                            $selected = (!empty($str['v1']) && $row->IDX == $str['v1']) ? "selected" : "";
                        ?>
                            <option value="<?php echo $row->IDX; ?>" <?php echo $selected; ?>><?php echo $row->SERIES_NM; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>

                    <label for="component_nm">품명</label>
                    <input type="text" autocomplete="off" name="component_nm" id="component_nm" value="<?php echo $str['component_nm'] ?>">

                    <button class="search_submit"><i class="material-icons">search</i></button>

                </form>
                <div class="gsflexst">
                    <span class="btn_right">
                        <p style="font-size: 20px; padding-right:20px; color:#194bff;"><?= empty($NDATE) ? "" : $NDATE ?></p>
                    </span>
                    <span class="btni btn_right add_itemnum" style="max-height:34px;" data-type="<?php echo $this->data['subpos']; ?>"><span class="material-icons">add</span></span>
                </div>



            </div>

            <div class="tbl-content">

                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>시리즈</th>
                            <th>품명</th>
                            <th>색상</th>
                            <th>지시수량</th>
                            <th>비고</th>
                            <th></th>
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
                                        <td><?php echo $row->ITEM_NAME; ?></td>
                                        <td><?php echo $row->COLOR; ?></td>
                                        <td class="right"><?php echo number_format($row->ORDER_QTY); ?></td>
                                        <td><?php echo $row->REMARK; ?></td>
                                        <td><span class="btn del_items" data-idx="<?= $row->TRANS_IDX; ?>">삭제</span></td>
                                    </tr>

                            <?php }
                            }
                        }
                        if (empty($RList)) {
                            ?>
                            <tr>
                                <td colspan="15" style='color:#999; padding:40px 0;'>등록된 작업지시가 없습니다.</td>
                            </tr>
                        <?php } else {
                        ?>
                            <tr style="background:#f3f8fd;" class="nhover">
                                <td colspan="2" style="text-align:right"><strong>총 수량</strong></td>
                                <td style="text-align:right"><?php echo number_format($count); ?></td>
                                <td colspan="1" style="text-align:right"><strong>총 합계</strong></td>
                                <td style="text-align:right"><?php echo number_format($totalQty); ?></td>
                                <td colspan="2"></td>
                            </tr>
                        <?php
                        } ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>


            </div>

        </li>

    </ul>


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
            url: "<?php echo base_url('ORD/ajax_invenNum_form') ?>",
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