<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
	<ul>
		<li style="width:25%;">
			
			<div id="" class="bc_search">
			<form>
				<input type='hidden' name='n' value='1'/>
				<label for="sdate">자재입고일</label>
				<input type="text" name="sdate" autocomplete="off" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));?>" size="12" /> ~ 
				
				<input type="text" name="edate" autocomplete="off" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>" size="12" />
				
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
							<th>자재입고일</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($List as $i=>$row){
						$no = $i+1;
					?>

						<tr <?php echo ($NDATE == $row->TRANS_DATE)?"class='over'":"";?>>
							<td class="cen"><?php echo $no; ?></td>
							<td class="cen"><a href='<?php echo base_url($this->data['pos'].'/p1/').$row->TRANS_DATE?>'><?php echo $row->TRANS_DATE;?></a></td>
						</tr>

					<?php
					}
					?>
					</tbody>
				</table>
			</div>

		</li>
		<li style="width:75%;">
			
			<div id="items_formupdate" class="bc_search gsflexst">
				<form>
					<label for="component">자재코드</label>
						<input type="text"  autocomplete="off"name="component" id="component" value="<?php echo $str['component']?>">
					<label for="component_nm">자재명</label>
						<input type="text" autocomplete="off" name="component_nm" id="component_nm" value="<?php echo $str['component_nm']?>">
				
					<button class="search_submit"><i class="material-icons">search</i></button>

				</form>
				<div class="gsflexst">
					<div style="margin:0 10px;">
						<!-- <label for="component_stock">자재재고량</label> -->
						<input style="text-align: right" type="hidden" name="XQTY" id="XQTY" readonly value="<?php if(!empty($detail)){ echo round($detail['STOCK']); }?>">	
					</div>
					<span class="btn_right"><label style="font-size: 20px; padding-right:20px;"><?=$detpos?></label></span>
					<span class="btni btn_right add_compnum" style="max-height:34px;"><span class="material-icons">add</span></span>	
				</div>
			</div>

			<div class="tbl-content">
			
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>자재코드</th>
							<th>자재명</th>
							<th>입고량</th>
							<th>단위</th>
							<th></th>                            
						</tr>
					</thead>
					<tbody>
					<?php if(!empty($RList)){ ?>
					<?php

					foreach($RList as $i=>$row){
						$num = $pageNum+$i+1;
						if($row->COMPONENT == "합계"){
							$count = $row->IN_QTY;
						}else{
					?>

						<tr>
							<td class="cen"><?php echo $num; ?></td>
							<td><?php echo $row->COMPONENT; ?></td>
							<td><?php echo $row->COMPONENT_NM; ?></td>
							<td class="right inqty"><?php echo number_format(round($row->IN_QTY));?></td>
							<td class="cen"><?php echo $row->UNIT;?></td>
							<td><span class="btn mod_delete" data-idx="<?php echo $row->AIDX; //trans idx?>">삭제</span></td>
						</tr>

					<?php
					}}
					?>
						<tr style="background:#f3f8fd;">
							<td colspan="3" style="text-align:right;"><strong>총 합계</strong></td>
							<td class="right"><strong><?php echo number_format($count); ?></strong></td>
							<td class="cen">KG</td>
							<td class="cen"></td>
						</tr>
					<?php
					}else{
					?>
						<tr><td colspan="15" style='color:#999; padding:40px 0;'>등록된 자재수량정보가 없습니다.</td></tr>
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


$(".mod_delete").on("click",function(){
	var idx = $(this).data("idx");
	var qty = $("input[name='XQTY']").val();	//점토 총합계
	var inqty = $(this).parents("tr").find("td").eq(4).text();
	var comp = $(this).parents("tr").find("td").eq(1).text(); //자재코드
	inqty = parseInt(inqty.replace(/,/g,""));
	console.log(idx);
	var cQty = qty-inqty;
	console.log("cqty : "+ cQty);

	
	if(inqty>qty){
		alert("자재 입고변경 값이 현 자재 재고량 보다 큽니다.");
		return false;
	}

	if(comp != 'CLAY'){
		alert("점토만 변경 가능합니다.")
		return false;
	}

	if(confirm("삭제하시겠습니까?") == true)
	{
		$.post("<?php echo base_url('PO/delete_comp_trans')?>",{idx:idx,cQty:cQty},function(data)
		{
				location.reload();
			
		});
	}

});


$(".add_compnum").on("click",function(){
	$(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('AMT/ajax_componentNum_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"add"},
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


$(".add_detail").on("click",function(){
	
	var hidx = $(this).data("hidx");

	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('PLN/ajax_plnDetail_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"add",hidx:hidx},
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





$(".mod_head").on("click",function(){
	
	var idx = $(this).data("idx");
	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('PLN/ajax_plnHead_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"mod",IDX:idx},
		success  : function(data){
			$(".ajaxContent").html(data);
		},
		error    : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});

});



/*$(".mod_detail").on("click",function(){
	
	var idx = $(this).data("idx");
	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('AMT/ajax_componentNum_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"mod",idx:idx},
		success  : function(data){
			$(".ajaxContent").html(data);
		},
		error    : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});

});*/


function formcheck(f){
	//location.href = "<?php echo base_url('PLN/index').$qstr?>";
	//return false;
}



/*
$(".print_detail").on("click",function(){
	//var HIDX = "<?php echo $H_IDX?>";
	//var H_IDX = (HIDX != "")?"/"+HIDX:"";
	//location.href = "<?php echo base_url('mdm/excelDown')?>"+H_IDX;
});
*/

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