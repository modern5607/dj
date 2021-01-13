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
	<div style="background:#eceff5; padding:20px 15px;">
		
		
	</div>
	<form name="ajaxform" id="ajaxform">

			
		<input type="hidden" name="mod" value="<?php //echo $mode?>">
		<div class="register_form">
			
			<fieldset class="form_3">
				
				<table>
					<tbody>
						<tr>
							<th>자재<span class="re"></span></th>
							<td>
								<?php
								if(!empty($COMP)){
									echo "<select name='component' id='component' class='form_input select_call'>";
									echo "	<option value=''>::자재선택::</option>";
									foreach($COMP as $co){
										$selected = (!empty($INFO) && $INFO->COMP_IDX == $co->IDX)?"selected":"";
								?>
										<option value="<?php echo $co->IDX?>" <?php echo $selected;?>><?php echo $co->COMPONENT_NM?></option>
								<?php
									}
									echo "</select>";
								}else{
								?>

								<?php
								}
								?>
							</td>
						</tr>
						<tr>
							<th>거래처<span class="re"></span></th>
							<td>
								<select name="BIZ_IDX" class="form_input select_call">
									<option value=''>::거래처선택::</option>
								<?php
								foreach($CUST as $cust){
									$selected = (!empty($INFO) && $INFO->BIZ_IDX == $cust->IDX)?"selected":"";

									echo '<option value="'.$cust->IDX.'" '.$selected.'>'.$cust->CUST_NM.'</option>';
								}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<th>입고량</th>
							<td><input type="text" name="IN_QTY" class="form_input input_100" value="<?php echo (!empty($INFO))?$INFO->IN_QTY:""; ?>" style="padding:6px 10px;"></td>
						</tr>
						<tr>
							<th>입고일자<span class="re"></span></th>
							<td>
								<input type="text" name="TRANS_DATE" class="form_input input_100 calendar" style="padding:6px 10px;" value="<?php echo (!empty($INFO))?$INFO->TRANS_DATE:date("Y-m-d",time())?>">
							</td>
						</tr>
						<tr>
							<th>비고</th>
							<td><input type="text" name="REMARK" class="form_input input_100" style="padding:6px 10px;" value="<?php echo (!empty($INFO))?$INFO->REMARK:date("Y-m-d",time())?>"></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			
		</div>
		<div class="bcont" style="padding:15px 0; text-align:center;">
			<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif');?>' width="100"></span>
			<?php
			if(isset($INFO)){ //수정인경우
			?>
			<button type="button" class="submitBtn blue_btn">수정</button>
			<input type="hidden" name="IDX" value="<?php echo $INFO->IDX; ?>">
			<?php
			}else{	
			?>
			<button type="button" class="submitBtn blue_btn">입력</button>
			<?php
			}
			?>
		</div>

	</form>

</div>




<script type="text/javascript">
<!--


$(".submitBtn").on("click",function(){

	var formData = new FormData($("#ajaxform")[0]);
	var $this = $(this);

		

	$.ajax({
		url  : "<?php echo base_url('AMT/ajax_component_set_qty')?>",
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









$("input[name='TRANS_DATE']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});





//-->
</script>