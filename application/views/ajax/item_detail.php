<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">

<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<h2>
	<?php echo $title;?>
	<span class="material-icons close">clear</span>
</h2>



<div class="formContainer">
	
	<form name="cocdDetail" id="cocdDetail">
		<input type="hidden" name="mod" value="<?php echo $mod;?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id">HEAD CODE</label></th>
							<td>
								<select name="H_IDX" class="form_input select_call" style="width:100%;">
									<option value="">Head code 선택</option>
								<?php
								
								foreach($headList as $opt){ //공통코드 HEAD
									
									if($mod == 1){ //수정인경우
										
										$selected = ($data->H_IDX == $opt->IDX)?"selected":"";

									}else{

										$selected = ($hidx == $opt->IDX)?"selected":"";

									}

									

								?>
                                    <option value="<?php echo $opt->IDX;?>" <?php echo $selected;?>><?php echo $opt->ITEM_CODE." - ".$opt->ITEM_NAME;?></option>
								<?php
								}	
								?>
                                </select>
							</td>
						</tr>
						<tr>
							<th><label class="l_id">출력순서</label></th>
							<td>
								<input type="number" name="S_NO" min="0" value="<?php echo isset($data->S_NO)?$data->S_NO:0;?>" class="form_input">
							</td>
						</tr>
						<tr>
							<th><label class="l_id">코드</label></th>
							<td>
								<input type="text" name="CODE" value="<?php echo isset($data->D_ITEM_CODE)?$data->D_ITEM_CODE:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">품목명</label></th>
							<td>
								<input type="text" name="NAME" value="<?php echo isset($data->D_ITEM_NAME)?$data->D_ITEM_NAME:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">품목속성</label></th>
							<td>
								<input type="text" name="ITEM_ATT" value="<?php echo isset($data->ITEM_ATT)?$data->ITEM_ATT:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">규격</label></th>
							<td>
								<input type="text" name="PRODUCT" value="<?php echo isset($data->PRODUCT)?$data->PRODUCT:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">단위</label></th>
							<td>
								<input type="text" name="UNIT" value="<?php echo isset($data->UNIT)?$data->UNIT:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">용도</label></th>
							<td>
								<input type="text" name="ITEM_USE" value="<?php echo isset($data->ITEM_USE)?$data->ITEM_USE:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">품목구분</label></th>
							<td>
								<input type="text" name="ITEM_GB" value="<?php echo isset($data->ITEM_GB)?$data->ITEM_GB:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">단가</label></th>
							<td>
								<input type="text" name="PRICE" value="<?php echo isset($data->PRICE)?$data->PRICE:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">조달구분</label></th>
							<td>
								<input type="text" name="JD_GB" value="<?php echo isset($data->JD_GB)?$data->JD_GB:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">주거래처</label></th>
							<td>
								<input type="text" name="CUSTOMER" value="<?php echo isset($data->CUSTOMER)?$data->CUSTOMER:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">사용유무</label></th>
							<td>
								<input type="radio" name="USE_YN" value="Y" <?php echo ($data->USE_YN == "Y")?"checked":"";?> id="enable" class="form_input"><label for="enable">사용</label>
								<input type="radio" name="USE_YN" value="N" <?php echo ($data->USE_YN == "N")?"checked":"";?> id="unable" class="form_input"><label for="unable">사용안함</label>
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">비고</label></th>
							<td>
								<textarea name="REMARK" id="REMARK" class="form_input input_100">
									<?php echo isset($data->REMARK)?nl2br($data->REMARK):"";?>
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
				<button type="button" class="mod modBtn blue_btn">수정</button>
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



$('#REMARK').summernote({
    height:100,
    lang: 'ko-KR',
	toolbar:false,
	dialogsFade: true,
	disableDragAndDrop: true, //드래그앤드랍true:비활성
    callbacks: {
        onImageUpload : function (files, editor, welEditable) {
            console.log('SummerNote image upload : ', files);
            //sendFile(files, editor, welEditable, '#summernote');
        },
        onMediaDelete : function($target, editor, welEditable) {
            /*const path = $target.attr("src");
            console.log(path);
            $.post("<?php echo base_url('acon/delete_editor_image')?>",{path},function(data){
				if(data != "error"){
					alert(data);
				}
            });*/
        }
    }
});




$(".submitBtn").on("click",function(){

	var formData = new FormData($("#cocdDetail")[0]);
	var $this = $(this);

	if($("input[name='CODE']").val() == ""){
		alert("코드를 입력하세요");
		$("input[name='CODE']").focus();
		return false;
	}

	if($("input[name='NAME']").val() == ""){
		alert("코드명를 입력하세요");
		$("input[name='NAME']").focus();
		return false;
	}

	$.ajax({
		url  : "<?php echo base_url('/item/set_item_DetailUpdate')?>",
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
	var formData = new FormData($("#cocdDetail")[0]);
	var $this = $(this);

	$.ajax({
		url  : "<?php echo base_url('/item/set_item_DetailUpdate')?>",
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
	if(confirm("해당 코드를 삭제하시겠습니까?") !== false){
		var idx = $(this).data("idx");
		$.post("<?php echo base_url('/item/set_itemDetail_delete')?>",{idx:idx},function(data){
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