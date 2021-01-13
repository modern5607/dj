<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>



<div class="bc_header">
	<form id="items_formupdate">
					
		<label for="series">시리즈</label>
		<select name="series" id="series" style="min-width:150px;">
			<option value="">전체</option>
		<?php 
			foreach($seriesList as $ser){ 
				$selected = ($ser->IDX == $str['series'])?"selected":"";
		?>
			<option value="<?php echo $ser->IDX;?>" <?php echo $selected;?>><?php echo $ser->SERIES_NM;?></option>
		<?php } ?>
		</select>
		
		<label for="itemno">품번</label>
		<input type="itemno" name="itemno" id="itemno" value="<?php echo $str['itemno']?>">

		<label for="itemname">권한</label>
		<input type="itemname" name="itemname" id="itemname" value="<?php echo $str['itemname']?>">

		<label for="use">사용유무</label>
		<select name="useyn" id="use">
			<option value="Y">사용</option>
			<option value="N">미사용</option>
		</select>	
		
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	<span class="btn btn_right add_items">신규등록</span>
</div>


<div class="bc_cont">
	<div class="cont_header"><?php echo $title;?></div>
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>품번</th>
						<th>품명</th>
						<th>규격</th>
						<th>사용유무</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($itemsList as $i=>$row){ $no = $i+1; ?>
				<tr>
					<td class="cen"><?php echo $no; ?></td>
					<td class="cen"><?php echo $row->ITEM_NO; ?></td>
					<td><?php echo $row->ITEM_NAME; ?></td>
					<td><?php echo $row->SPEC; ?></td>
					<td class="cen"><?php echo ($row->USE_YN == "Y") ? "사용" : "미사용"; ?></td>
					<td class="cen">
						<span class="btn register_update" data-idx="<?php echo $row->IDX;?>">수정</span>
					</td>
				</tr>
		

				<?php
				}
				if(empty($itemsList)){
				?>

					<tr>
						<td colspan="15" class="list_none">품목정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>



<div id="pop_container">
	
	<div id="info_content" class="info_content" style="height:auto;">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>



<script type="text/javascript">
<!--


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
		url:"<?php echo base_url('MDM/ajax_set_items')?>",
		type : "post",
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);			
		}
		
	});

});

$(".register_update").on("click",function(){
	var idx = $(this).data("idx");

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	modchk = true;

	$.ajax({
		url:"<?php echo base_url('MDM/ajax_set_items')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
		
	});
});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});
//-->
</script>