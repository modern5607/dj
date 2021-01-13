<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<h2>
	<?php echo $title;?>
	<span class="material-icons close">clear</span>
</h2>



<div class="formContainer">
	
	<form name="cocdDetail" id="cocdDetail">
		<input type="hidden" name="mod" value="<?php echo $mod;?>">
		<input type="hidden" name="D_IDX" value="<?php echo isset($data)?$data->IDX:"";?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id">시리즈</label></th>
							<td>
								<select name="H_IDX" class="form_input select_call" style="width:100%;">
									<option value="">Head code 선택</option>
								<?php
								
								foreach($headList as $opt){ //공통코드 HEAD
									
									if($mod == 1){ //수정인경우
										
										$selected = ($data->SERIES_IDX == $opt->IDX)?"selected":"";

									}else{

										$selected = ($hidx == $opt->IDX)?"selected":"";

									}
								?>
                                    <option value="<?php echo $opt->IDX;?>" <?php echo $selected;?>><?php echo $opt->SERIES." - ".$opt->SERIES_NM;?></option>
								<?php
								}	
								?>
                                </select>
							</td>
						</tr>
						<tr>
							<th><label class="l_id">색상코드</label></th>
							<td>
								<input type="text" name="COLOR_CD" value="<?php echo isset($data->COLOR_CD)?$data->COLOR_CD:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">색상명</label></th>
							<td>
								<input type="text" name="COLOR" id="COLOR" value="<?php echo isset($data->COLOR)?$data->COLOR:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">사용유무</label></th>
							<td>
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


$("input[name='SERIES']").change(function(){
	var $this = $(this);
    
	$.post("<?php echo base_url('MDM/ajax_seriesDetailchk');?>",{code:$this.val()},function(data){
        if(data.state == "N"){
			$this.focus();
			chkHeadCode = true;
			alert(data.msg);
        }else{
			chkHeadCode = false;
		}
    },"json").fail(function(data){
        console.log(data);
    });

});


$(".submitBtn").on("click",function(){

	var formData = new FormData($("#cocdDetail")[0]);
	var $this = $(this);

	if($("input[name='COLOR_CD']").val() == ""){
		alert("색상코드를 입력하세요");
		$("input[name='COLOR_CD']").focus();
		return false;
	}

	if($("input[name='COLOR']").val() == ""){
		alert("색상명를 입력하세요");
		$("input[name='COLOR']").focus();
		return false;
	}

	$.ajax({
		url  : "<?php echo base_url('MDM/set_series_DetailUpdate')?>",
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
			console.log(jsonData);

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
	var formData = new FormData($("#cocdDetail")[0]);
	var $this = $(this);

	$.ajax({
		url  : "<?php echo base_url('MDM/set_series_DetailUpdate')?>",
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


/* 삭제필요없음
$(".delBtn").on("click",function(){
	if(confirm("해당 코드를 삭제하시겠습니까?") !== false){
		var idx = $(this).data("idx");
		$.post("<?php echo base_url('mdm/set_cocdDetail_delete')?>",{idx:idx},function(data){
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
*/
</script>