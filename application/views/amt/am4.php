<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<div class="bc_header">
    <form id="items_formupdate">
        <label for="sdate">일자</label>
        <input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "") ? $str['sdate'] : ""; ?>" size="12" /> ~

        <input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "") ? $str['edate'] : ""; ?>" size="12" />


        <label for="v1">시리즈</label>
        <select name="v1">
            <option value="">전체</option>
            <?php
            foreach ($SERIES as $row) {
                $selected = (!empty($str['v1']) && $row->IDX == $str['v1']) ? "selected" : "";
            ?>
                <option value="<?php echo $row->IDX; ?>" <?php echo $selected; ?>><?php echo $row->SERIES_NM; ?></option>
            <?php
            }
            ?>
        </select>

        <label for="v3">품목</label>
        <input type="text" autocomplete="off" name="v3" id="v3" value="<?php echo $str['v3'] ?>">

        <label for="cg">상태</label>
        <select name="cg" id="cg">
            <option value="" <?php echo ($str['cg'] == "w") ? "" : "selected"; ?>>출고 대기중</option>
            <option value="w" <?php echo ($str['cg'] == "w") ? "selected" : ""; ?>>제작 대기중</option>
        </select>

        <button class="search_submit"><i class="material-icons">search</i></button>
    </form>

</div>


<div class="bc_cont">
    <div class="cont_header"><?php echo $title; ?></div>
    <div class="cont_body">
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>수주일자</th>
                        <th>거래처</th>
                        <th>시리즈</th>
                        <th>품명</th>
                        <th>색상</th>
                        <th>주문수량</th>
                        <th>출고수량</th>
                        <th>출고일</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($List)) {
                        foreach ($List as $i => $row) {
                            $no = $pageNum + $i + 1;

                    ?>
                            <tr>
                                <td class="cen"><?php echo $no; ?></td>
                                <td class="cen"><?php echo $row->ACT_DATE; ?></td>
                                <td class="cen"><?php echo $row->CUST_NM; ?></td>
                                <td class="cen"><?php echo $row->SERIES_NM; ?></td>
                                <td><strong><?php echo $row->ITEM_NM; ?></strong></td>
                                <td><strong><?php echo $row->COLOR; ?></strong></td>
                                <td class="right"><?php echo number_format($row->QTY); ?></td>
                                <td class="cen">
                                    <input style="text-align:right; width:100px" type="number" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" name="OUT_QTY[]" class="form_input1" value="<?php echo $row->QTY; ?>" max="<?= $row->MAXQTY ?>" min="<?php echo $row->QTY; ?>">
                                    <input type="hidden" name="QTY[]" value="<?php echo $row->QTY; ?>">
                                </td>
                                <td class="cen"><input type="text" name="OUT_DATE[]" class="form_input1 calendar" value="<?= date("Y-m-d") ?>" /> </td>
                                <!-- <td class="cen"><input type="text" name="OUT_DATE[]" class="form_input1" value=""></td> -->
                                <td class="cen"><span class="btn mod_stock" data-actidx="<?php echo $row->ACT_IDX; ?>" data-maxqty="<?php echo $row->MAXQTY; ?>">출고</span></td>
                            </tr>


                        <?php
                        }
                    } else {

                        ?>

                        <tr>
                            <td colspan="15" class="list_none">실적정보가 없습니다.</td>
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


    </div>

</div>



<div id="pop_container">

    <div id="info_content" class="info_content" style="height:auto;">

        <div class="ajaxContent"></div>

    </div>

</div>



<script type="text/javascript">
    function leadingZeros(n, digits) {

        var zero = '';
        n = n.toString();

        if (n.length < digits) {
            for (i = 0; i < digits - n.length; i++)
                zero += '0';
        }
        return zero + n;
    }


    // $("input[name^=OUT_QTY]").on("change",function(){
    // 	if($(this).val() == ""){
    // 		$(this).parents("tr").find("input[name^='OUT_DATE']").val('');
    // 	}else{
    // 		var d = new Date();
    // 		var s =
    //         leadingZeros(d.getFullYear(), 4) + '-' +
    //         leadingZeros(d.getMonth() + 1, 2) + '-' +
    //         leadingZeros(d.getDate(), 2) +' '+
    // 		leadingZeros(d.getHours(), 2) +':'+
    // 		leadingZeros(d.getMinutes(), 2) +':'+
    // 		leadingZeros(d.getSeconds(), 2);
    // 		$(this).parents("tr").find("input[name^='OUT_DATE']").val(s);
    // 	}
    // });


    $(".mod_stock").on("click", function() {
        var actidx = $(this).data("actidx");
        var seriesd = "";
        var outqty = $(this).parents("tr").find("input[name^='OUT_QTY']").val(); //출고량
        var qty = $(this).parents("tr").find("input[name^='QTY']").val(); //수주량
        var xdate = $(this).parents("tr").find("input[name^='OUT_DATE']").val();
        var itemnm = $(this).parents("tr").find('strong').text();
        var maxqty = $(this).data('maxqty'); //재고량
        if (!maxqty) {
            maxqty = 0;
        }
        console.log(maxqty, +outqty, +qty)

        $this = $(this);

        if (xdate == 0 || xdate == "") {
            alert('출고일을 입력하세요');
            $this.parents("tr").find("input[name^='OUT_DATE']").focus();
            return false;
        }
        if (+outqty < +qty) {
            alert('출고수량이 주문수량보다 적습니다.');
            $this.parents("tr").find("input[name^='OUT_QTY']").focus();
            return false;
        }
        if (+outqty > maxqty) {
            alert('출고수량이 재고수량보다 많습니다.\n현 재고수량 : ' + maxqty);
            $this.parents("tr").find("input[name^='OUT_QTY']").focus();
            return false;
        }





        if (confirm(itemnm + "을(를) " + xdate + "에 출고하시겠습니까?") !== false) {
            if (outqty == 0 || outqty == "") {
                alert('출고수량을 입력하세요');
                $this.parents("tr").find("input[name^='OUT_QTY']").focus();
                return false;
            }



            $.post("<?php echo base_url('AMT/ajax_am4_listupdate') ?>", {
                    actidx: actidx,
                    seriesd: seriesd,
                    outqty: outqty,
                    xdate: xdate
                },
                function(data) {
                    if (data.status != "") {
                        alert(data.msg);
                        if (data.status == "Y") {
                            location.reload();
                        } else {
                            $this.parents("tr").find("input[name^='OUT_QTY']").val('');
                            $this.parents("tr").find("input[name^='OUT_DATE']").val('');
                        }
                    }
                }, "JSON"
            );
        }
    });


    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });

    //-->
</script>