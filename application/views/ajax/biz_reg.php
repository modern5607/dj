<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<h2>
	<?php echo $title;?>
	<span class="material-icons close">clear</span>
</h2>



<div class="formContainer">
	
	<form name="bizRegForm" id="bizRegForm">
		<input type="hidden" name="mod" value="<?php echo $mod;?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id">업체명</label></th>
							<td>
								<input type="text" name="CUST_NM" value="<?php echo isset($data->CUST_NM)?$data->CUST_NM:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">주소</label></th>
							<td>
								<input type="text" name="ADDRESS" value="<?php echo isset($data->ADDRESS)?$data->ADDRESS:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">연락처</label></th>
							<td>
								<input type="text" name="TEL" value="<?php echo isset($data->TEL)?$data->TEL:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">담당자</label></th>
							<td>
								<input type="text" name="CUST_NAME" value="<?php echo isset($data->CUST_NAME)?$data->CUST_NAME:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">주거래품목</label></th>
							<td>
								<input type="text" name="ITEM" value="<?php echo isset($data->ITEM)?$data->ITEM:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">비고</label></th>
							<td>
								<textarea name="REMARK" class="form_input input_100">
									<?php echo isset($data->REMARK)?$data->REMARK:"";?>
								</textarea>
							</td>
						</tr>
						<?php
						if(isset($data)){ //수정인경우
						?>
						<tr>
							<th><label class="l_pw">생성ID</label></th>
							<td>
								<?php echo $data->INSERT_ID;?>
								<input type="hidden" name="IDX" value="<?php echo $data->IDX;?>">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">등록일</label></th>
							<td>
								<?php echo $data->INSERT_DATE;?>
							</td>
						</tr>
						<?php if($data->UPDATE_ID != ""){ ?>
						<tr>
							<th><label class="l_pw">수정ID</label></th>
							<td>
								<?php echo $data->UPDATE_ID;?>
							</td>
						</tr>
						<?php } ?>
						<?php if($data->UPDATE_DATE != ""){ ?>
						<tr>
							<th><label class="l_pw">수정일</label></th>
							<td>
								<?php echo $data->UPDATE_DATE;?>
							</td>
						</tr>
						<?php } ?>
						<?php
						}	
						?>
						
					</tbody>
				</table>
			</fieldset>
			
			<div class="bcont">
				<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif');?>' width="100"></span>
				<?php
				if(isset($data)){ //수정인경우
				?>
				<button type="button" class="modBtn blue_btn">수정</button>
				<button type="button" data-idx="<?php echo $data->IDX;?>" class="delBtn blue_btn">삭제</button>
				<?php
				}else{	
				?>
				<button type="button" class="submitBtn blue_btn">입력</button>
				<?php
				}
				?>
			</div>
			
		</div>

	</form>

</div>







<script>

$(".submitBtn").on("click",function(){

	var formData = new FormData($("#bizRegForm")[0]);
	var $this = $(this);

	if($("input[name='wr_1']").val() == ""){
		alert("업체명을 입력하세요");
		$("input[name='wr_1']").focus();
		return false;
	}

	if($("input[name='wr_2']").val() == ""){
		alert("연락처를 입력하세요");
		$("input[name='wr_2']").focus();
		return false;
	}

	$.ajax({
		url  : "<?php echo base_url('/biz/set_bizRegUpdate')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		beforeSend  : function(){
			$this.hide();
			$("#loading").show();
		},
		success : function(data){

			var jsonData = JSON.parse(data);
			if(jsonData.status == "ok"){
			
				setTimeout(function(){
					alert(jsonData.msg);
					$(".ajaxContent").html('');
					$("#pop_container").fadeOut();
					$(".info_content").css("top","-50%");
					$("#loading").hide();
					location.reload();

				},1000);

			}
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});
});


$(".modBtn").on("click",function(){
	var formData = new FormData($("#bizRegForm")[0]);
	var $this = $(this);

	$.ajax({
		url  : "<?php echo base_url('/biz/set_bizRegUpdate')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		beforeSend  : function(){
			$this.hide();
			$("#loading").show();
		},
		success : function(data){

			var jsonData = JSON.parse(data);
			if(jsonData.status == "ok"){
			
				setTimeout(function(){
					alert(jsonData.msg);
					$(".ajaxContent").html('');
					$("#pop_container").fadeOut();
					$(".info_content").css("top","-50%");
					$("#loading").hide();
					location.reload();

				},1000);

			}
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});

});




$(".delBtn").on("click",function(){
	if(confirm("해당 업체를 삭제하시겠습니까?") !== false){
		var idx = $(this).data("idx");
		$.post("<?php echo base_url('/biz/set_bizReg_delete')?>",{idx:idx},function(data){
			if(data > 0){
				alert("삭제처리가 완료되었습니다.");
			}
			$(".ajaxContent").html('');
			$("#pop_container").fadeOut();
			$(".info_content").css("top","-50%");
			$("#loading").hide();
			location.reload();
		});

		return false;
	}
});

</script>