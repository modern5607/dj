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

			
		<div class="register_form">
			
			<fieldset class="form_3">
				
				<table>
					<tbody>
						<tr>
							<th>날짜<span class="re"></span></th>
							<td>
								<?php echo $setDate;?>
								<input type="hidden" name="WOEK_DATE" value="<?php echo $setDate;?>">
							</td>
						</tr>
						<tr>
							<th>내용<span class="re"></span></th>
							<td>
								<input type="text" name="REMARK" class="form_input input_100" value="<?php echo (!empty($INFO))?$INFO->REMARK:""; ?>" style="padding:6px 10px;">
							</td>
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
		url  : "<?php echo base_url('PLN/ajax_p2_insert')?>",
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