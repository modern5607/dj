<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>



<h2>
	<?php echo $title;?>
	<span class="material-icons close">clear</span>
</h2>



<div class="formContainer">
	
	<form name="itemsform" id="itemsform">
		<input type="hidden" name="mod" value="<?php echo $mod?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id"><span class="re"></span>품번</label></th>
							<td>
								<input type="text" name="ITEM_NO" id="ITEM_NO" value="<?php echo isset($data->ITEM_NO)?$data->ITEM_NO:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_id"><span class="re"></span>품명</label></th>
							<td>
								<input type="text" name="ITEM_NAME" id="ITEM_NAME" value="<?php echo isset($data->ITEM_NAME)?$data->ITEM_NAME:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_id"><span class="re"></span>규격</label></th>
							<td>
								<input type="text" name="SPEC" id="SPEC" value="<?php echo isset($data->SPEC)?$data->SPEC:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_id"><span class="re"></span>시리즈</label></th>
							<td>
								<select name="SERIES" class="input_100" style="padding:7px 8px; border:1px solid #ddd;">
								<?php 
									foreach($seriesList as $ser){ 
										$selected = ($ser->IDX == $data->SERIES_IDX)?"selected":"";
								?>
									<option value="<?php echo $ser->IDX;?>" <?php echo $selected?>><?php echo $ser->SERIES_NM;?></option>
								<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><label class="l_id"><span class="re"></span>단위</label></th>
							<td style="line-height:32px;">
								<?php
								if(!empty($UNIT)){
								?>
									<select name="UNIT" class="form_input select_call" style="padding:7px 8px; border:1px solid #ddd;">
										<option value="">::선택::</option>
									<?php
									foreach($UNIT as $row){
										$selected5 = (!empty($data) && $data->UNIT == $row->D_CODE)?"selected":"";
									?>
										<option value="<?php echo $row->D_CODE?>" <?php echo $selected5;?>><?php echo $row->D_NAME;?></option>
									<?php
									}
									?>
									</select>
								<?php
								}else{
									echo "<a href='".base_url('MDM/index')."' class='none_code'>공통코드 UNIT를 등록하세요</a>";
								}
								?>
							</td>
						</tr>
						<tr>
							<th><label class="l_id"><span class="re"></span>BK여부</label></th>
							<td style="line-height:32px;">
								<input type="radio" name="BK_YN" value="Y" <?php echo (isset($data->BK_YN) && $data->BK_YN == "Y")?"checked":"";?>>사용
								<input type="radio" name="BK_YN" value="N" <?php echo (isset($data->BK_YN) && $data->BK_YN == "N")?"checked":"";?>>미사용
							</td>
						</tr>
						<tr>
							<th><label class="l_id"><span class="re"></span>점토중량</label></th>
							<td>
								<input type="text" name="JT_QTY" id="JT_QTY" value="<?php echo isset($data->JT_QTY)?$data->JT_QTY:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_id">주고객</label></th>
							<td style="line-height:32px;">
								<?php echo (isset($data->BIZ_IDX))?$data->BIZ_IDX:"";?>
							</td>
						</tr>
						<tr>
							<th><label class="l_id">사용유무</label></th>
							<td style="line-height:32px;">
								<input type="radio" name="USE_YN" value="Y" <?php echo (isset($data->USE_YN) && $data->USE_YN == "Y")?"checked":"";?>>사용
								<input type="radio" name="USE_YN" value="N" <?php echo (isset($data->USE_YN) && $data->USE_YN == "N")?"checked":"";?>>미사용
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


$(".modBtn").on("click",function(){
	var formData = new FormData($("#itemsform")[0]);
	var $this = $(this);

	$.ajax({
		url  : "<?php echo base_url('MDM/set_items_formUpdate')?>",
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

			}
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});

});


$(".submitBtn").on("click",function(){

	var formData = new FormData($("#itemsform")[0]);
	var $this = $(this);

	if($("input[name='ITEM_NO']").val() == ""){
		alert("품번를 입력하세요");
		$("input[name='ITEM_NO']").focus();
		return false;
	}

	if($("input[name='ITEM_NAME']").val() == ""){
		alert("품명를 입력하세요");
		$("input[name='ITEM_NAME']").focus();
		return false;
	}

	if($("input[name='SPEC']").val() == ""){
		alert("규격를 입력하세요");
		$("input[name='SPEC']").focus();
		return false;
	}

	if($("select[name='SERIES']").val() == ""){
		alert("시리즈를 선택하세요");
		
		return false;
	}
	

	
	/*
	if(chkHeadCode){
		alert("중복된 코드입니다.")
		$("#CODE").focus();
		return false;
	}*/

	$.ajax({
		url  : "<?php echo base_url('MDM/set_items_formUpdate')?>",
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
//-->
</script>