<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
	<ul>
		<li style="width:400px">
			
			<div id="" class="bc_search">
			<form>
				<input type='hidden' name='n' value='1'/>
				<label for="sdate">실적완료일</label>
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
							<th>실적완료일</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($List as $i=>$row){
						$no = $i+1;
					?>

						<tr <?php echo ($NDATE == $row->SB_DATE)?"class='over'":"";?>>
							<td class="cen"><?php echo $no; ?></td>
							<td class="cen"><a href='<?php echo base_url($this->data['pos'].'/a11_1/').$row->SB_DATE
							?>'><?php echo $row->SB_DATE;?></a></td>
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
				작업일자 : <strong style='font-size:16px;'><?php echo $NDATE?></strong>
			</div>

			

			<div class="tbl-content">
			
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>수주일자</th>
							<th>품명</th>
							<th>색상</th>
							<th>수주수량</th>
							<th>시유수량</th>
							<th>완료수량</th>
							<th>1급</th>
							<th>2급</th>
							<th>파손</th>
							<th>시유</th>
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
							<td class="cen">
								<?php echo $num; ?>
								<input type="hidden" name="IDX" value="<?php echo $row->IDX;?>">
							</td>
							<td class="cen"><?php echo $row->ACT_DATE; ?></td>
							<td><?php echo $row->ITEM_NM; ?></td>
							<td class="cen"><?php echo $row->COLOR;?></td>
							<td class="right"><?php echo number_format($row->QTY);?></td>
							<td class="right"><?php echo number_format($row->IN_QTY);?></td>
							<td class="right"><?php echo number_format($row->QTY1);?></td>
							<td class="right" data-lastnum="<?php echo $row->QTY1;?>"><?php echo number_format($row->QTY1);?></td>
							<td class="cen"><input type="text" size="4" name="QTY2" data-lastnum="<?php echo $row->QTY2;?>" value="<?php echo $row->QTY2;?>"/></td>
							<td class="cen"><input type="text" size="4" name="QTY3" data-lastnum="<?php echo $row->QTY3;?>" value="<?php echo $row->QTY3;?>"/></td>
							<td class="cen"><input type="text" size="4" name="QTY4" data-lastnum="<?php echo $row->QTY4;?>" value="<?php echo $row->QTY4;?>"/></td>
							<td><span class="btn del_items" data-idx="<?php echo $row->IDX;?>">삭제</span></td>
						</tr>

					<?php
					}
					}
					if(empty($RList)){
					?>
						<tr><td colspan="15" style='color:#999; padding:40px 0;'>등록된 실적정보가 없습니다.</td></tr>
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



$("input[name='QTY2'],input[name='QTY3'],input[name='QTY4']").on("change",function(){

	var qty2 = $(this).parents("tr").find("input[name='QTY2']").val();
	var qty3 = $(this).parents("tr").find("input[name='QTY3']").val();
	var qty4 = $(this).parents("tr").find("input[name='QTY4']").val();
	
	var orgNum = $(this).parents("tr").find("td:eq(5)");
	var setNum = $(this).parents("tr").find("td:eq(5)").text()-qty2-qty3-qty4;

	var lastNum = $(this).data("lastnum");
	

	var IDX = $(this).parents("tr").find("input[name='IDX']").val();

	if(setNum < 0){
		alert('시유수량을 초과할 수 없습니다.');
		$(this).val(lastNum);
		return false;
	}



	
	$(this).parents("tr").find("td:eq(7)").text(setNum);

	$.post("<?php echo base_url('ACT/ajax_a11_1_update')?>",{idx:IDX,qty1:setNum,qty2:qty2,qty3:qty3,qty4:qty4},function(data){
		if(data == 1){
			//location.reload();
		}
	});

});






$(".del_items").on("click",function(){
	var idx = $(this).data("idx");
	if(confirm('삭제하시겠습니까?') !== false){
	$.post("<?php echo base_url('ACT/ajax_a11_1_delete')?>",{idx:idx},function(data){
		if(data > 0)
		{
			alert('실적이 삭제되었습니다.');
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