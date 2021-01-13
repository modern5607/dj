<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
	<ul>
		<li style="width:400px;">
			
			<div id="" class="bc_search">
			<form>
				<input type='hidden' name='n' value='1'/>
				<label for="sdate">실적등록일</label>
				<input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:"";?>" size="10" /> ~ 
				
				<input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:"";?>" size="10" />
				
				<button class="search_submit"><i class="material-icons">search</i></button>
			</form>
			</div>
			
			<div class="bc_header none_padding">
				<a href="<?php echo base_url('ACT2/a10')?>"><span class="btni btn_right add_head">시유실적1</span></a>
			</div>

			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>실적등록일</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($List as $i=>$row){
						$no = $i+1;
					?>

						<tr <?php echo ($NDATE == $row->CU_DATE)?"class='over'":"";?>>
							<td class="cen"><?php echo $no; ?></td>
							<td class="cen"><a href='<?php echo base_url('ACT2/'.$this->data['subpos'].'/').$row->CU_DATE
							?>'><?php echo $row->CU_DATE;?></a></td>
						</tr>

					<?php
					}
					?>
					</tbody>
				</table>
			</div>

		</li>
		<li style="width:calc(100% - 400px);">
			
			<!--div id="" class="bc_search" style="background:#f8f8f8;">
			<form>
				<label for="component">제품코드</label>
				<input type="text" name="component" id="component" value="<?php echo $str['component']?>">

				<label for="component_nm">제품명</label>
				<input type="text" name="component_nm" id="component_nm" value="<?php echo $str['component_nm']?>">
				
				<button class="search_submit"><i class="material-icons">search</i></button>
			</form>
			</div-->

			<div class="bc_header none_padding">
			<?php if(!empty($RList)){ ?>
				<div class="ac_table">
					<table>
						<tbody>
						<tr>
							<th>작업일자</th>
							<td><?php echo $NDATE;?></td>
						</tr>						
						</tbody>
					</table>

				</div>
			<?php } ?>
				<!--span class="btni btn_right add_itemnum" data-type="<?php echo $this->data['subpos'];?>"><span class="material-icons">add</span></span-->	
			</div>

			<div class="tbl-content">
				<?php if(!empty($RList)){ ?>
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>수주일자</th>
							<th>품명</th>
							<th>색상</th>
							<th>수주수량</th>
							<th>시유수량</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					
					<?php

					foreach($RList as $i=>$row){
						$num = $i+1;
					?>

						<tr>
							<td class="cen"><?php echo $num; ?></td>
							<td class="cen"><?php echo $row->ACT_DATE; ?></td>
							<td><?php echo $row->ITEM_NM; ?></td>
							<td><?php echo $row->COLOR;?></td>
							<td class="right"><?php echo number_format($row->QTY);?></td>
							<td class="cen">
								<input type="text" name="QTY" data-idx="<?php echo $row->IDX;?>" data-org="<?php echo $row->IN_QTY;?>" style="text-align:right;" value="<?php echo $row->IN_QTY;?>"></td>
							<td>
								<span class="btn del_items" data-idx="<?php echo $row->IDX; //detail idx?>">삭제</span>
							</td>
						</tr>

					<?php
					}
					if(empty($RList)){
					?>
						<tr><td colspan="10" style='color:#999; padding:40px 0;'>등록된 실적정보가 없습니다.</td></tr>
					<?php } ?>
					</tbody>
				</table>
				<?php }else{ ?>
				<div style="border:1px solid #ddd; padding:100px 0; text-align:center;">등록일를 선택하세요</div>
				<?php } ?>
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
<!--


$("input[name^='QTY']").on("change",function(){
	
	var org = $(this).data("org");
	var qty = $(this).val()*1;
	var idx = $(this).data("idx");

	var xxx = $(this).parents("tr").find("td").eq(4).text()*1;
	if(xxx > qty){
		alert('수주수량보다 작을 수 없습니다.');
		$(this).val(org);
		$(this).focus();
		return false;
	}

	$.post("<?php echo base_url('ACT/ajax_a10_1_qty')?>",{qty:qty,org:org,idx:idx},function(data){
		if(data.status != ""){
			alert(data.msg);
			location.reload();
		}
	},"JSON");

});


$(".add_itemnum").on("click",function(){

	var type = $(this).data("type");

	$(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('ACT/ajax_itemNum_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"add",type:type},
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
	if(confirm('삭제하시겠습니까?') !== false){
	$.post("<?php echo base_url('ACT/ajax_del_a10_1')?>",{idx:idx},function(data){
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