<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
	<form id="items_formupdate">

		<label for="sdate">수주일자</label>
		<input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));?>" size="12" /> ~ 
		
		<input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>" size="12" />

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

		<label for="v3">품명</label>
		<input type="text"autocomplete="off" name="v3" id="v3" value="<?php echo $str['v3']?>">
		
		<label for="v20">진행내역</label>
		<select name="v2">
			<option value="">전체</option>
			<option value="1" <?php echo(!empty($str['v2']) && 1 == $str['v2'])?"selected":"";?>>시유</option>
			<option value="2" <?php echo(!empty($str['v2']) && 2 == $str['v2'])?"selected":"";?>>선별</option>
			<option value="3" <?php echo(!empty($str['v2']) && 3 == $str['v2'])?"selected":"";?>>출고대기</option>
		</select>
				
		<span class="nbsp"></span>
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	
</div>


<div class="bc_cont">
	<div class="cont_header"><?php echo $title;?></div>
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>수주일자</th>
						<th>품명</th>
						<th>색상</th>
						<th>지시수량</th>
						<th>시유수량</th>
						<th>불량수량</th>
						<th>납품예정일</th>
						<th>시유완료일</th>
						<th>선별완료일</th>
						<th>출고완료</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($List)){
					$count=0;
					$countin=0;
					$countsum=0;
					$remark=0;
				foreach($List as $i=>$row){ 
					$no = $pageNum+$i+1;
					$sumqty = $row->QT2+$row->QT3+$row->QT4;
					if($row->ACT_DATE == "합계"){
						$count = $row->QTY;
						$countin = $row->IN_QTY;
						$countsum = $sumqty;
						$remark = $row->DEL_DATE;
					}else{
				?>
				<tr>
					<td class="cen"><?php echo $no;?></td>
					<td class="cen"><?php echo $row->ACT_DATE;?></td>
					<td><strong><?php echo $row->ITEM_NM; ?></strong></td>
					<td class="cen"><?php echo $row->COLOR; ?></td>
					<td class="right"><?php echo number_format($row->QTY); ?></td>
					<td class="right"><?php echo number_format($row->IN_QTY); ?></td>
					<td class="right"><?php echo number_format($sumqty); ?></td>
					<td class="cen"><?php echo $row->DEL_DATE; ?></td>
					<td class="cen"><?php echo $row->CU_DATE; ?></td>
					<td class="cen"><?php echo $row->SB_DATE; ?></td>
					<td class="cen"><?php echo $row->CG_DATE; ?></td>
				</tr>
				<?php
				}}
				if($count != 0){
				?>
				<tr style="background:#f3f8fd;" class="nhover">
					<td colspan="2" style="text-align:right;"><strong>총 수량</strong></td>
					<td class="right"><strong><?php echo number_format($remark); ?></strong></td>
					<td colspan="" style="text-align:right;"><strong>총 합계</strong></td>
					<td class="right"><strong><?php echo number_format($count); ?></strong></td>
					<td class="right"><strong><?php echo number_format($countin); ?></strong></td>
					<td class="right"><strong><?php echo number_format($countsum); ?></strong></td>
					<td colspan="4" class="right"></td>
				</tr>
				<?php
				}}
				if(empty($List) || $count == 0){
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


	</div>
	
</div>



<div id="pop_container">
	
	<div id="info_content" class="info_content" style="height:auto;">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>



<script type="text/javascript">



var modchk = false;
function memberformChk(f){
	
	var pwd  = $("input[name='PWD']").val();
	var chkP = $("input[name='PWD_CHK']").val();
	var id   = $("input[name='ID']").val();

	if(id == ""){
		alert("아이디를 입력하세요");
		$("input[name='ID']").focus();
		return false;
	}
	

	if(pwd == "" && !modchk){
		alert("비밀번호를 입력하세요");
		$("input[name='PWD']").focus();
		return false;
	}

	if(pwd != chkP){
		alert("비밀번호를 확인해주세요");
		$("input[name='PWD']").focus();
		return false;
	}
	
	modchk = false;
	return
	
}


$(".add_items").on("click",function(){

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url:"<?php echo base_url('MDM/ajax_set_component')?>",
		type : "post",
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);			
		}
		
	});

});

$(".comp_update").on("click",function(){
	var idx = $(this).data("idx");

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	modchk = true;

	$.ajax({
		url:"<?php echo base_url('MDM/ajax_set_component')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
		
	});
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
	location.reload();
	
});
//-->
</script>