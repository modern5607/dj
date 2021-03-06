<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
	<form id="items_formupdate">

		<label for="sdate">선별일자</label>
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

		<label for="v2">품목</label>
		<input type="text"autocomplete="off" name="v2" id="v2" value="<?php echo $str['v2']?>">

		<label for="v2">색상</label>
		<input type="text"autocomplete="off" name="v3" id="v3" value="<?php echo $str['v3']?>">
		
		
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	<!--span class="btn btn_right add_items">신규등록</span-->
</div>


<div class="bc_cont">
	<div class="cont_header"><?php echo $title;?></div>
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>선별일자</th>
						<th>품명</th>
						<th>색상</th>
						<th>수주수량</th>
						<th>시유수량</th>
						<th>완료수량</th>
						<th>1급</th>
						<th>2급</th>
						<th>파손</th>
						<th>시유</th>
						<!-- <th>후처리</th> -->
					</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($List)){
					$count=0;
					$incount=0;
					$count1=0;
					$count2=0;
					$count3=0;
					$count4=0;
					$count5=0;
				foreach($List as $i=>$row){ 
					$no = $pageNum+$i+1;
					if($row->ITEM_NM == "합계"){
						$count += $row->QTY;
						$incount += $row->IN_QTY;
						$count1 += $row->QTY1;
						$count2 += $row->QTY2;
						$count3 += $row->QTY3;
						$count4 += $row->QTY4;
						$count5 += $row->QTY5;
					}else{
				
				?>
				<tr>
					<td class="cen"><?php echo $no; ?></td>
					<td class="cen"><?php echo $row->SB_DATE; ?></td>
					<td><?php echo $row->ITEM_NM; ?></td>
					<td><?php echo $row->COLOR; ?></td>
					<td class="right"><?php echo number_format($row->QTY); ?></td>
					<td class="right"><?php echo number_format($row->IN_QTY); ?></td>
					<td class="right"><?php echo number_format($row->QTY1); ?></td>
					<td class="right"><?php echo number_format($row->QTY1); ?></td>
					<td class="right"><?php echo number_format($row->QTY2); ?></td>
					<td class="right"><?php echo number_format($row->QTY3); ?></td>
					<td style="text-align:right"><?php echo number_format($row->QTY4); ?></td>
					<!-- <td style="text-align:right"><?php echo number_format($row->QTY5); ?></td> -->
					
				</tr>
				<?php
					}
				?>
		

				<?php
				}
				?>
				<tr style="background:#f3f8fd;" class="nhover">
					<td colspan="4" class="right"><strong>합계</strong></td>
					<td class="right"><?php echo number_format($count); ?></td>
					<td class="right"><?php echo number_format($incount); ?></td>
					<td class="right"><strong><?php echo number_format($count1); ?></strong></td>
					<td class="right"><?php echo number_format($count1); ?></td>
					<td class="right"><?php echo number_format($count2); ?></td>
					<td class="right"><?php echo number_format($count3); ?></td>
					<td style="text-align:right"><?php echo number_format($count4); ?></td>
					<!-- <td style="text-align:right"><?php echo number_format($count5); ?></td> -->
				</tr>
				<?php
				}else{
				
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