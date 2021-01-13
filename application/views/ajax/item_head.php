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

	<form name="codeHead" id="codeHead">
		<input type="hidden" name="mod" value="<?php echo $mod;?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id">코드</label></th>
							<td>
								<input type="text" name="CODE" id="CODE" value="<?php echo isset($data->ITEM_CODE)?$data->ITEM_CODE:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">코드명</label></th>
							<td>
								<input type="text" name="NAME" value="<?php echo isset($data->ITEM_NAME)?$data->ITEM_NAME:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">비고</label></th>
							<td>
								<textarea name="REMARK" id="REMARK" class="form_input input_100">
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
								<input type="hidden" name="IDX" value="<?php echo $data->IDX?>">
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
				<button type="button" data-code="<?php echo $data->ITEM_CODE;?>" class="delBtn blue_btn">삭제</button>
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


//품목코드-중복검사
var chkHeadCode = false;
$("#CODE").change(function(){

	var $this = $(this);
    
	$.post("<?php echo base_url('/item/ajax_itemHaedchk');?>",{code:$this.val()},function(data){
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



$(".delBtn").on("click",function(){
	if(confirm("해당 코드를 삭제하시겠습니까?") !== false){
		var code = $(this).data("code");
		$.post("<?php echo base_url('/item/set_itemHead_delete');?>",{code:code},function(data){
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


$(".modBtn").on("click",function(){
	var formData = new FormData($("#codeHead")[0]);
	var $this = $(this);

	$.ajax({
		url  : "<?php echo base_url('/item/set_item_HeadUpdate')?>",
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


$(".submitBtn").on("click",function(){

	var formData = new FormData($("#codeHead")[0]);
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

	if(chkHeadCode){
		alert("중복된 코드입니다.")
		$("#CODE").focus();
		return false;
	}

	$.ajax({
		url  : "<?php echo base_url('/item/set_item_HeadUpdate')?>",
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

</script>