<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
    <ul>
        <li style="width:calc(100% - 400px);">

            <div id="" class="bc_search">
                <form>
                    <label for="sdate">수주일</label>
                    <input type="text" name="sdate" class="sdate calendar" value="<?= $str['sdate'] ?>" size="12"
                        autocomplete="off" /> ~

                    <input type="text" name="edate" class="edate calendar" 
                        value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>" size="12"
                        autocomplete="off" />

                    <input type='hidden' name='n' value='1' />
                    <label for="v1">거래처</label>
                    <select name="v1">
                        <option value="">전체</option>
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
                    <label for="v2">시리즈</label>
                    <select name="v2">
                        <option value="">전체</option>
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

                    <label for="v3">품명</label>
                    <input type="text" autocomplete="off" name="v3" class="v3"
                        value="<?php echo (!empty($str['v3']) && $str['v3'] != "")?$str['v3']:"";?>" size="12" />

                    <!--label for="v4">CU만보기</label>
					<input type="checkbox" name="v4" id="v4" value="1" <?php echo (!empty($str['v4']) && $str['v4'] == 1)?"checked":"";?> /-->
                    <span class="nbsp"></span>
                    <button class="search_submit"><i class="material-icons">search</i></button>
                </form>
            </div>

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
                            <th>수주수량</th>
                            <th>시유수량</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($List as $i=>$row){
							$num = $i+1;
					?>
                        <tr <?php echo ($OIDX == $row->IDX)?"class='over'":"";?>>
                            <td class="cen"><?php echo $num;?></td>
                            <td class="cen"><?php echo substr($row->ACT_DATE,0,10);?></td>
                            <td class="cen"><?php echo $row->CUST_NM;?></td>
                            <td class="cen"><?php echo $row->SERIES_NM;?></td>
                            <td>
                                <a href="<?php echo base_url($this->data['pos'].'/a11/'.$row->IDX.$qstr);?>"
                                    class="link_s1">
                                    <?php echo $row->ITEM_NM;?>
                                </a>
                            </td>
                            <td class="cen"><?php echo $row->COLOR;?></td>
                            <td class="right"><?php echo $row->QTY;?></td>
                            <td style="text-align:right"><?php echo $row->IN_QTY;?></td>

                        </tr>
                        <?php } ?>
                        <?php
					if(empty($List)){
					?>
                        <tr>
                            <td colspan="8" style='color:#999; padding:40px 0;'>실적정보가 없습니다.</td>
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

        <li style="width:400px">

            <?php if(!empty($OIDX)){ ?>
            <form method="post" name="menuUpdateForm" id="menuUpdateForm"
                action="<?php echo base_url('ACT/form_a11_update');?>" enctype="multipart/form-data">
                <input type="hidden" name="OIDX" value="<?php echo $OIDX;?>">
                <input type="hidden" name="qstr" value="<?php echo $qstr;?>">
                <div class="bc_header none_padding">

                    <div class="ac_table">
                        <table class="nhover">
                            <tbody>
                                <tr>
                                    <th>품명</th>
                                    <td colspan="3"><input type="text" name="name" class="input_100" disabled
                                            value="<?=$XXX_QTY->ITEM_NAME?>"></td>
                                </tr>
                                <tr>
                                    <th>색상</th>
                                    <td colspan="3"><input type="text" name="color" class="input_100" disabled
                                            value="<?=$XXX_QTY->COLOR?>"></td>
                                </tr>
                                <tr>
                                    <th>작업일자</th>
                                    <td colspan="3"><input type="text" name="A1" class="input_100 calendar"
                                            value="<?php echo date('Y-m-d',time())?>"></td>
                                </tr>
                                <tr>
                                    <th>1급</th>
                                    <td colspan="3"><input type="text" name="A2" class="input_100 right" readonly
                                            value="<?php echo $XXX_QTY->IN_QTY;?>"></td>
                                </tr>
                                <tr>
                                    <th>2급</th>
                                    <td colspan="3"><input type="text" name="A3" class="input_100 right" value=""></td>
                                </tr>
                                <tr>
                                    <th>파손</th>
                                    <td colspan="3"><input type="text" name="A4" size="6" class="input_100 right"
                                            value=""></td>
                                </tr>
                                <tr>
                                    <th>시유</th>
                                    <td colspan="3"><input type="text" name="A5" size="6" class="input_100 right"
                                            value=""></td>
                                </tr>
                                <tr>
                                    <th>비고</th>
                                    <td colspan="3"><input type="text" name="A6" class="input_100" value=""></td>
                                </tr>
                                <tr>
                                    <th>파일1</th>
                                    <td colspan="3">
                                        <input type="file" name="setfile[]" style="width:200px" />
                                        <span class="btni btn_right add_file">추가</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!--a href="http://rainshop.ivyro.net/rain_code3/PLN/index" class="alink" style="float:left;">전체코드보기</a-->
                    <span class="btni btn_right add_form" data-hidx="3">저장</span>

                </div>
            </form>
            <?php }else{ ?>
            <div>none</div>
            <?php } ?>

        </li>
    </ul>
</div>


<script type="text/javascript">
var num = 1;
$(".add_form").on("click", function() {
    $("#menuUpdateForm").submit();
});

$(".add_file").on("click", function() {

    var html = "";
    num++;

    if (num > 3) {
        alert('파일은 3개까지만 등록가능합니다.');
        return false;
    }

    html += "<tr>";
    html += "	<th>파일" + num + "</th>";
    html += "	<td colspan='3'>";
    html += "		<input type='file' name='setfile[]' style='width:200px;'>";
    html += "		<span class='btni btn_right del_file'>삭제</span>";
    html += "	</td>";
    html += "</tr>";

    $(this).parents("tbody").find("tr").last().after(html);

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
    num--;
    $(this).parents("tr").remove();
});


$("input[name='sdate'],input[name='edate'],input[name='A1']").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});

//-->
</script>