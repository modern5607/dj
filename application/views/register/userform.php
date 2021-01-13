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
	<!--span class="btn btn_right add_member">신규등록</span-->
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
						<th>부서</th>
						<th>직급</th>
						<th>전화</th>
						<th>휴대폰</th>
						<th>이메일</th>
						<th>입사일</th>
						<th>상태</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($memberList as $i=>$row){ ?>
				<tr>
					<td><?php echo $row->ID; ?></td>
					<td class="cen"><?php echo $row->LEVEL; ?></td>
					<td class="cen"><?php echo $row->NAME; ?></td>
					<td class="cen"><?php echo $row->PART; ?></td>
					<td class="cen"><?php echo $row->GRADE; ?></td>
					<td class="cen"><?php echo $row->TEL; ?></td>
					<td class="cen"><?php echo $row->HP; ?></td>
					<td><?php echo $row->EMAIL; ?></td>
					<td class="cen"><?php echo $row->FIRSTDAY; ?></td>
					<td class="cen"><?php echo ($row->STATE == 1) ? "사용" : "사용안함"; ?></td>
					<td class="cen">
						<span class="btn register_update" data-idx="<?php echo $row->IDX;?>">수정</span>
					</td>
				</tr>
		

				<?php
				}
				if(empty($memberList)){
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

		<div class="pagination">
			<?php echo $this->data['pagenation'];?>
			<?
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








<script>

$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});






$(".register_update").on("click",function(){
	var idx = $(this).data("idx");

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	modchk = true;

	$.ajax({
		url:"<?php echo base_url('register/ajax_set_memberinfo')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
		
	});
});


</script>