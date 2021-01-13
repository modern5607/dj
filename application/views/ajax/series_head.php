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
		<input type="hidden" name="mod" value="<?php echo $mod?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id">시리즈</label></th>
							<td>
								<input type="text" name="SERIES" id="SERIES" value="<?php echo isset($data->SERIES)?$data->SERIES:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">시리즈명</label></th>
							<td>
								<input type="text" name="SERIES_NM" value="<?php echo isset($data->SERIES_NM)?$data->SERIES_NM:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="USE_YN">사용여부</label></th>
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
				<input type="hidden" name="IDX" value="<?php echo $data->IDX?>">
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



//헤드코드-중복검사
var chkHeadCode = false;
$("#SERIES").change(function(){

	var $this = $(this);
    
	$.post("<?php echo base_url('MDM/ajax_seriesHaedchk');?>",{code:$this.val()},function(data){
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
		$.post("<?php echo base_url('MDM/set_cocdHead_delete')?>",{code:code},function(data){
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
		url  : "<?php echo base_url('MDM/set_series_HeadUpdate')?>",
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

	var formData = new FormData($("#codeHead")[0]);
	var $this = $(this);

	if($("input[name='SERIES']").val() == ""){
		alert("시리즈를 입력하세요");
		$("input[name='SERIES']").focus();
		return false;
	}

	if($("input[name='SERIES_NM']").val() == ""){
		alert("시리즈명를 입력하세요");
		$("input[name='SERIES_NM']").focus();
		return false;
	}

	if(chkHeadCode){
		alert("중복된 코드입니다.")
		$("#SERIES").focus();
		return false;
	}

	$.ajax({
		url  : "<?php echo base_url('MDM/set_series_HeadUpdate')?>",
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

</script>