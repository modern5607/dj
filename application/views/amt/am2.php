<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
	<form id="items_formupdate">

		<label for="sdate">자재입고일</label>
		<input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));?>" size="12" /> ~ 
		
		<input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>" size="12" />
					
		<!-- <label for="component">자재코드</label>
		<input type="text" autocomplete="off" name="component" id="component" value="<?php echo $str['component']?>"> -->

		<label for="customer">거래처</label>
		<input type="text" autocomplete="off" name="customer" id="customer" value="<?php echo $str['customer']?>">

		<label for="component_nm">자재명</label>
		<input type="text" autocomplete="off" name="component_nm" id="component_nm" value="<?php echo $str['component_nm']?>">
	
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	<!-- <span class="btn btn_right add_items">신규등록</span> -->
</div>


<div class="bc_cont">
	<div class="cont_header"><?php echo $title;?></div>
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>일자</th>
						<th>거래처</th>
						<th>자재명</th>
						<th>입고량</th>
						<th>단위</th>
						<th>비고</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				if(!empty($List)){
				$count=0;
				$remark=0;
				foreach($List as $i=>$row){ 
					$no = $i+1; 
					if($row->CUST_NM == "합계"){
						$count += $row->IN_QTY;
						$remark += $row->TRANS_DATE;
					}else{
				?>
				<tr>
					<td class="cen"><?php echo $no; ?></td>
					<td class="cen"><?php echo $row->TRANS_DATE; ?></td>
					<td><?php echo $row->CUST_NM; ?></td>
					<td><?php echo $row->COMPONENT_NM; ?></td>
					<td class="right"><?php echo number_format($row->IN_QTY); ?></td>
					<td class="cen"><?php echo $row->UNIT; ?></td>
					<td class="cen"><?php echo $row->REMARK; ?></td>
				</tr>
				<?php
					}
				}
				?>
				<tr style="background:#f3f8fd;" class="nhover">
					<td colspan="2" style="text-align:right;"><strong>건수</strong></td>
					<td class="right"><strong><?php echo number_format($remark); ?></strong></td>
					<td colspan="1" style="text-align:right;"><strong>합계</strong></td>
					<!-- <td class="cen"><?php echo $row->COMPONENT_NM; ?></td> -->
					<td class="right"><strong><?php echo number_format($count); ?></strong></td>
					<td class="cen">KG</td>
					<td colspan="2"><?php echo $row->REMARK; ?></td>
				</tr>
				<?php
				}else{
				?>

					<tr>
						<td colspan="15" class="list_none">자재입고정보가 없습니다.</td>
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