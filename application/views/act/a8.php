<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
	<ul>
		<li style="width:430px">
			
			<div id="" class="bc_search">
			<form>
				<input type='hidden' name='n' value='1'/>
				<label for="sdate">생산 실적일</label>
				<input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));?>" size="12" /> ~ 
				
				<input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>" size="12" />
				
				<button class="search_submit"><i class="material-icons">search</i></button>
			</form>
			</div>
			
			<!--div class="bc_header none_padding">
				<span class="btni btn_right add_head"><span class="material-icons">add</span></span>	
			</div-->

			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>생산 실적일</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($List as $i=>$row){
						$no = $pageNum+$i+1;
					?>

						<tr <?php echo ($NDATE == $row->TRANS_DATE)?"class='over'":"";?>>
							<td class="cen"><?php echo $no; ?></td>
							<td class="cen"><a href='<?php echo base_url('ACT2/a8/').$row->TRANS_DATE
							?>'><?php echo $row->TRANS_DATE;?></a></td>
						</tr>

					<?php
					}
					?>
					</tbody>
				</table>
			</div>

		</li>
		<li style="width:calc(100% - 430px);">
			
			<div id="" class="bc_search gsflexst" style="background:#f8f8f8;">
			<form>
				<label for="v1">시리즈</label>
					<select name="v1">
						<option value="">전체</option>
					<?php
					foreach($SERIES as $row){
						$selected = (!empty($str['v1']) && $row->IDX == $str['v1'])?"selected":"";
					?>
						<option value="<?php echo $row->IDX;?>" <?php echo $selected;?>><?php echo $row->SERIES_NM;?></option>
					<?php
					}
					?>
					</select>

				<label for="component_nm">품명</label>
				<input type="text" autocomplete="off"  name="component_nm" id="component_nm" value="<?php echo $str['component_nm']?>">
				
				<button class="search_submit"><i class="material-icons">search</i></button>
					
			</form>
			<span class="btn_right"><label style="font-size: 20px;"><?=empty($NDATE)?"":$NDATE?></label></span>
			
				<span class="btni btn_right add_itemnum" style="max-height:34px;" data-type="<?php echo $this->data['subpos'];?>"><span class="material-icons">add</span></span>
				
			</div>

			<div class="tbl-content">
			
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>시리즈</th>
							<th>품명</th>
							<th>수량</th>
							<th>비고</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php $totalQty = 0;?>
					<?php if(!empty($RList)){ ?>
					<?php
					$totalQty = 0;
					foreach($RList as $i=>$row){
						$num = $i+1;
						$totalQty += $row->IN_QTY;
					?>
					

						<tr>
							<td class="cen"><?php echo $num; ?></td>
							<td><?php echo $row->SERIES_NM; ?></td>
							<td><?php echo $row->ITEM_NAME; ?></td>
							<td class="right"><?php echo $row->IN_QTY; ?></td>
							<td><?php echo $row->REMARK;?></td>
							<td><span class="btn del_items" 
							data-idx="<?php echo $row->TRANS_IDX; //detail idx?>" 
							data-inqty="<?php echo $row->IN_QTY; ?>"
							data-shqty="<?php echo $row->SH_QTY; ?>">삭제</span></td>
						</tr>

					<?php
					}
					}
					if(empty($RList)){
					?>
						<tr><td colspan="6" style='color:#999; padding:40px 0;'>등록된 실적정보가 없습니다.</td></tr>
					<?php }else{
						?>
						<tr style="background:#f3f8fd;">
							<td colspan="3" style="text-align:right"><strong>총 합계</strong></td>
							<td style="text-align:right"><?php echo number_format($totalQty);?></td>
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

$(".add_itemnum").on("click",function(){

	var type = $(this).data("type");
	var selectedDate = "<?= empty($NDATE)?"":$NDATE;?>";
	$(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('ACT/ajax_itemNum_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"add",
					type:type,
					date:selectedDate},
		success  : function(data){
			$(".ajaxContent").html(data);
		},
		error    : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	})

});


$(".del_items").on("click",function(){
	var idx = $(this).data("idx");
	var inqty = $(this).data("inqty");
	var shqty = $(this).data("shqty");
console.log(inqty,shqty)
	if(inqty > shqty){
		alert(`삭제할 수 없습니다.재고가 ${shqty}개 남았습니다.`);
		return false;
	}
	if(confirm('삭제하시겠습니까?') !== false){
	$.post("<?php echo base_url('ACT/ajax_del_items_trans')?>",{idx:idx},function(data){
		if(data.statu != "")
		{
			alert(data.msg);
			location.reload();
		}
	},"JSON");
	}
});


$("input[name='sdate'],input[name='edate']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});
//-->
</script>