<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>



<div class="bc_header">
	<form id="items_formupdate">
					
		<label for="mid">아이디</label>
		<input type="text" name="mid" id="mid" value="<?php echo $str['mid']?>">
		
		<label for="mname">이름</label>
		<input type="mname" name="mname" id="mname" value="<?php echo $str['mname']?>">

		<label for="level">권한</label>
		<select name="level">
			<option value="">전체</option>
		<?php for($i=1; $i<=10; $i++){ ?>
			<option value="<?php echo $i?>" <?php echo ($str['level'] == $i)?"selected":"";?>><?php echo $i?></option>
		<?php } ?>
		</select>
		
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	<span class="btn btn_right add_member">신규등록</span>
</div>


<div class="bc_cont">
	<div class="cont_header">사용자리스트</div>
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>아이디</th>
						<th>권한</th>
						<th>이름</th>
						<th>결혼여부</th>
						<th>주소</th>
						<th>휴대폰</th>
						<th>이메일</th>
						<th>상태</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($userList as $i=>$row){ ?>
				<tr>
					<td><?php echo $row->ID; ?></td>
					<td class="cen"><?php echo $row->LEVEL; ?></td>
					<td class="cen"><?php echo $row->NAME; ?></td>
					<td class="cen"><?php echo ($row->MARRY == "Y") ? "기혼" : "미혼"; ?></td>
					<td class="cen"><?php echo $row->ADDR2; ?></td>
					<td class="cen"><?php echo $row->HP; ?></td>
					<td><?php echo $row->EMAIL; ?></td>
					<td class="cen"><?php echo ($row->STATE == 1) ? "사용" : "중지"; ?></td>
					<td class="cen">
						<span class="btn register_update" data-idx="<?php echo $row->IDX;?>">수정</span>
					</td>
				</tr>
		

				<?php
				}
				if(empty($userList)){
				?>

					<tr>
						<td colspan="15" class="list_none">회원정보가 없습니다.</td>
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


$(".add_member").on("click",function(){

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url:"<?php echo base_url('SYS/ajax_set_memberinfo')?>",
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
		url:"<?php echo base_url('SYS/ajax_set_memberinfo')?>",
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