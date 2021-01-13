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
			
			<!--div class="bc_header none_padding">
				<span class="btni btn_right add_head"><span class="material-icons">add</span></span>	
			</div-->

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

						<tr <?php echo ($NDATE == $row->TRANS_DATE)?"class='over'":"";?>>
							<td class="cen"><?php echo $no; ?></td>
							<td class="cen"><a href='<?php echo base_url($this->data['pos'].'/'.$this->data['subpos'].'/').$row->TRANS_DATE
							?>'><?php echo $row->TRANS_DATE;?></a></td>
						</tr>

					<?php
					}
					?>
					</tbody>
				</table>
			</div>

		</li>
		<li style="width:calc(100% - 400px);">
			
			<div id="" class="bc_search" style="background:#f8f8f8;">
			<form>
				<label for="component">제품코드</label>
				<input type="text" name="component" id="component" value="<?php echo $str['component']?>">

				<label for="component_nm">제품명</label>
				<input type="text" name="component_nm" id="component_nm" value="<?php echo $str['component_nm']?>">
				
				<button class="search_submit"><i class="material-icons">search</i></button>
			</form>
			</div>

			<div class="bc_header none_padding">
				<span class="btni btn_right add_itemnum" data-type="<?php echo $this->data['subpos'];?>"><span class="material-icons">add</span></span>	
			</div>

			<div class="tbl-content">
			
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>품명</th>
							<th>수량</th>
							<th>비고</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php if(!empty($RList)){ ?>
					<?php

					foreach($RList as $i=>$row){
						$num = $i+1;
					?>

						<tr>
							<td class="cen"><?php echo $num; ?></td>
							<td><?php echo $row->ITEM_NAME; ?></td>
							<td class="cen"><?php echo $row->IN_QTY; ?></td>
							<td><?php echo $row->REMARK;?></td>
							<td><span class="btn del_items" data-idx="<?php echo $row->TRANS_IDX; //detail idx?>">삭제</span></td>
						</tr>

					<?php
					}
					}
					if(empty($RList)){
					?>
						<tr><td colspan="6" style='color:#999; padding:40px 0;'>등록된 실적정보가 없습니다.</td></tr>
					<?php } ?>
					</tbody>
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
<!--
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
	$.post("<?php echo base_url('ACT/ajax_del_items_trans_a9')?>",{idx:idx},function(data){
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