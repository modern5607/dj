<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
    <ul>
        <li style="width:420px">

            <div id="" class="bc_search">
                <form>
                    <label for="sdate">수주일</label>
                    <input style="width:139px" type="text" name="sdate" class="sdate calendar" value=""size="12" /> ~
                    <input style="width:139px;" type="text" name="edate" class="edate calendar" value="" size="12" />
                    <div>
                        <label for="v2">거래처</label>
                        <select name="v1" style="width:295px;margin-top: 5px;">
                            <option value="">::거래처::</option>
                            <?php
							foreach($BIZ as $row){
								$selected = (!empty($str['v1']) && $row->IDX == $str['v1'])?"selected":"";
							?>
                            <option value="<?php echo $row->IDX;?>" <?php echo $selected;?>><?php echo $row->CUST_NM;?>
                            </option>
                            <?php
							}
							?>
                        </select>
                    </div>

                    <label for="v2">시리즈</label>
                    <select name="v2" style="width:295px;margin-top: 5px;">
                        <option value="">::시리즈::</option>
                        <?php
				foreach($SERIES as $row){
					$selected = (!empty($str['v2']) && $row->IDX == $str['v2'])?"selected":"";
				?>
                        <option value="<?php echo $row->IDX;?>" <?php echo $selected;?>><?php echo $row->SERIES_NM;?>
                        </option>
                        <?php
				}
				?>
                    </select>
                    <br>
                    <label>품목</label>
                    <input style="width:234px;margin-top: 5px;margin-left: 12px;" type="text" autocomplete="off"
                        name="v3" size="6" value="<?php echo (!empty($str['v3']))?$str['v3']:"";?>">
                    <button class="search_submit" style="margin-top: 5px;"><i class="material-icons">search</i></button>
                </form>
            </div>

            <div class="bc_header none_padding">
                <a href="<?php echo base_url($this->data['pos'].'/a10_1')?>"><span
                        class="btni btn_right add_head">시유실적2</span></a>
            </div>

            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>품명</th>
                            <th>수량</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
					foreach($List as $i=>$row){
						$no = $pageNum+$i+1;
					?>

                        <tr <?php echo ($CODE == $row->ITEMS_IDX)?"class='over'":"";?>>
                            <td class="cen"><?php echo $no; ?></td>
                            <td><a href="<?php echo base_url($this->data['pos'].'/a10/').$row->ITEMS_IDX.$qstr; ?>"
                                    class="link_s1"><?php echo $row->ITEM_NM; ?></a></td>
                            <td><?php echo number_format($row->SUM_QTY);?></td>
                        </tr>

                        <?php
					}
					?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <?php echo $this->data['pagenation'];?>
                <?php
			if($this->data['cnt'] > 20){
			?>
                <div class="limitset">
                    <select name="per_page">
                        <option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
                        <option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
                        <option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
                        <option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
                    </select>
                </div>
                <?php
			}	
			?>
            </div>


        </li>
        <li style="width:calc(100% - 420px);">
            <form name="ajaxform" id="ajaxform">

                <div class="bc_header none_padding">

                    <?php if(!empty($detail)){ ?>

                    <div class="ac_table">
                        <table>
                            <tr>
                                <th>작업일자</th>
                                <td><input type="text" name="XDATE" value="<?php echo date("Y-m-d",time());?>" autocomplet="off" class="calendar">
                                </td>
                                <th>품목</th>
                                <td><input type="text" name="XITEM" readonly
                                        value="<?php echo $detail['ITEM_NAME']?>" /></td>
                                <th>정형재고수량</th>
                                <td><input type="text" name="XQTY" readonly value="<?php echo $detail['JH_QTY']?>" />
                                </td>
                            </tr>


                        </table>

                    </div>

                    <!--a href="<?php echo base_url('PLN/index');?>" class="alink" style="float:left;">전체코드보기</a-->
                    <span class="btni btn_right form_submit" data-hidx="<?php //echo $HIDX; // idx?>">저장</span>

                    <?php } ?>
                </div>
                <div class="tbl-content">
                    <?php if(!empty($detail)){ ?>
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>수주일자</th>
                                <th>거래처</th>
                                <th>시리즈</th>
                                <th>품명</th>
                                <th>색상</th>
                                <th>수주수량</th>
                                <th>시유수량</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if(!empty($detail)){
                            foreach($detail['SLIST'] as $i=>$row){
                                $num = $i+1;
                                if($row->SE_NAME == "합계"){?>
                            <tr style="background:#f3f8fd;">
                                <td colspan="4" class="right"><strong>합계</strong></td>
                                <td></td>
                                <td></td>
                                <td class="right"><?=number_format($row->QTY)?></td>
                                <td></td>
                            </tr>
                            <?php
                                }
                                else
                                {?>
                            <tr>
                                <td class="cen"><?php echo $num; ?></td>
                                <td class="cen"><?php echo $row->ACT_DATE; ?></td>
                                <td class="cen"><?php echo $row->CUST_NM; ?></td>
                                <td class="cen"><?php echo $row->SE_NAME; ?></td>
                                <td><?php echo $row->ITEM_NM; ?></td>
                                <td class="cen"><?php echo $row->COLOR;?></td>
                                <td class="right"><?php echo number_format($row->QTY);?></td>
                                <td>
                                    <input type="text" name="IN_QTY[]" value="" autocomplete="off" data-qty="<?php echo $row->QTY;?>"
                                        style="text-align: right; border:1px solid #ddd; padding:3px 7px; margin: 3px 4px;" />
                                    <input type="hidden" name="AD_IDX[]" value="<?php echo $row->IDX;?>" />
                                    <input type="hidden" name="ACT_IDX[]" value="<?php echo $row->H_IDX;?>" />
                                    <input type="hidden" name="ITEMS_IDX[]" value="<?php echo $row->ITEMS_IDX?>" />
                                    <input type="hidden" name="SERIESD_IDX[]" value="<?php echo $row->SERIESD_IDX?>" />
                                </td>
                            </tr>

                            <?php
                            }
                        }
                            if(empty($detail)){
                            ?>
                            <tr>
                                <td colspan="7" style='color:#999; padding:40px 0;'>실적정보가 없습니다.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php }
                    }else{ ?>
                    <div style="border:1px solid #ddd; padding:100px 0; text-align:center;">품명를 선택하세요</div>
                    <?php } ?>
                </div>
            </form>
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
$("input[name^='IN_QTY']").on("change", function() {
    var SQTY = $(this).parents("tr").find("td").eq(4).text();
    if (SQTY > $(this).val() * 1) {
        alert('수주수량보다 작을수 없습니다.');
        $(this).val('');
        $(this).focus();
        return false;
    }
});


$(".form_submit").on("click", function() {

    var formData = new FormData($("#ajaxform")[0]);
    var $this = $(this);

    var qty = $("input[name='XQTY']").val();
    var tCount = 0;
    var sjqty = 0;
    var num = 0;
    var ererer = 0;
 

    $("input[name='IN_QTY[]']").each(function(idx) {
        tCount += $(this).val() * 1;
        if ($(this).val() != "") {
            num++;

            if($(this).val() < $(this).data('qty')){
            ererer = 1;
            }
        }
    });

  
    if (ererer == 1) {
            alert('시유수량이 수주수량보다 작을 수 없습니다.');
        ererer = 0;
        return false;
    }
    if (num == 0) {
        alert('시유수량을 입력하세요');
        $("input[name^=IN_QTY]").eq(0).focus();
        return false;
    }

    if (qty < tCount) {
        alert('시유수량이 정형재고수량보다 클 수 없습니다.');
        $("input[name^=IN_QTY]").val('');
        $("input[name^=IN_QTY]").eq(0).focus();
        return false;
    }


    $.ajax({
        url: "<?php echo base_url('ACT/ajax_act_a10_insert')?>",
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



$("input[name='sdate'],input[name='edate'],.calendar").datetimepicker({
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