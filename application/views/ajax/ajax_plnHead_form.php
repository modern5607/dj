<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<h2>
	<?php echo $title;?>
	<span class="material-icons close">clear</span>
</h2>



<div class="formContainer">
	
	<form name="ajaxform" id="ajaxform">
		<input type="hidden" name="mod" value="<?php echo $mode?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="ACT_DATE"><span class="re"></span>수주일자</label></th>
							<td>
								<input type="text" name="ACT_DATE" id="ACT_DATE" value="<?php echo (!empty($data->ACT_DATE))?$data->ACT_DATE:"";?>" class="form_input input_100 calendar">

							</td>
						</tr>
						<tr>
							<th><label class="CUST"><span class="re"></span>거래처</label></th>
							<td>
								<?php
								if(!empty($CUST)){
								?>
								<select name="CUST" class="form_input select_call">
									<option value="">:::선택:::</option>
								<?php
									foreach($CUST as $cust){
										$select = ($data->BIZ_IDX == $cust->IDX)?"selected":"";
								?>
									<option value="<?php echo $cust->IDX;?>" <?php echo $select;?>><?php echo $cust->CUST_NM;?></option>
								<?php
								}
								?>
								</select>
								<?php
								}
								?>
							</td>
						</tr>
						<tr>
							<th><label class="ACT_NAME"><span class="re"></span>수주명</label></th>
							<td>
								<input type="text" name="ACT_NAME" id="ACT_NAME" value="<?php echo (!empty($data->ACT_NAME))?$data->ACT_NAME:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="DEL_DATE">납품일자</label></th>
							<td>
								<input type="text" name="DEL_DATE" id="DEL_DATE" value="<?php echo (!empty($data->DEL_DATE))?$data->DEL_DATE:"";?>" class="form_input input_100 calendar">
							</td>
						</tr>
						<tr>
							<th><label class="REMARK">기타세부사항</label></th>
							<td>
								<input type="text" name="REMARK" id="REMARK" value="<?php echo (!empty($data->REMARK))?$data->REMARK:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="ORD_TEXT">특이사항</label></th>
							<td>
								<input type="text" name="ORD_TEXT" id="ORD_TEXT" value="<?php echo (!empty($data->ORD_TEXT))?$data->ORD_TEXT:"";?>" class="form_input input_100">
							</td>
						</tr>
						
						
						
					</tbody>
				</table>
			</fieldset>
			
			<div class="bcont">
				<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif');?>' width="100"></span>
				<?php
				if(isset($data)){ //수정인경우
				?>
				<button type="button" class="modBtn blue_btn">수정</button>
				<input type="hidden" name="IDX" value="<?php echo $data->IDX; ?>">
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




<script type="text/javascript">
<!--





$(".submitBtn").on("click",function(){

	var formData = new FormData($("#ajaxform")[0]);
	var $this = $(this);

	

	if($("input[name='ACT_DATE']").val() == ""){
		alert("수주일자를 입력하세요");
		$("input[name='ACT_DATE']").focus();
		return false;
	}

	if($("select[name='CUST']").val() == ""){
		alert("거래처를 입력하세요");
		$("select[name='CUST']").focus();
		return false;
	}

	if($("input[name='ACT_NAME']").val() == ""){
		alert("수주명을 입력하세요");
		$("input[name='ACT_NAME']").focus();
		return false;
	}

	

	$.ajax({
		url  : "<?php echo base_url('PLN/ajax_plnHead_insert')?>",
		type : "POST",
		data : formData,
		//asynsc : true,
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

				chkHeadCode = false;

			}
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});
});





$("input[name='ACT_DATE'],#DEL_DATE").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});





//-->
</script>