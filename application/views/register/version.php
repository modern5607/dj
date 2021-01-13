<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>


<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<span class="btn print add_verform"><i class="material-icons">add</i>신규등록</span>
			<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>버전</th>
						<th>비고</th>
						<th>등록일</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php 
				foreach($verList as $i=>$row){
					$num = $pageNum+$i+1;
					
				?>
				<tr>
					<td class="cen"><?php echo $num;?></td>
					<td class="cen"><?php echo $row->VER_NO; ?></td>
					<td class="cen"><?php echo $row->VER_REMARK; ?></td>
					<td class="cen"><?php echo $row->INSERT_DATE; ?></td>
					<td><span class="mod modi_Btn" data-idx="<?php echo $row->IDX;?>">수정</span> <span class="mod deleteBtn" data-idx="<?php echo $row->IDX;?>">삭제</span></td>
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
	
	<div id="info_content" class="info_content" style="height:unset;">
		
		<div class="ajaxContent">
			
			<h2>
				버전정보입력
				<span class="material-icons close">clear</span>
			</h2>
			<div class="formContainer">
				
				<form name="codeHead" id="codeHead" method="post" action="<?php echo base_url('register/upload_ver_form')?>" enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
					<input type="hidden" name="MIDX" value="">
					<div class="register_form">
						<fieldset class="form_1">
							<legend>이용정보</legend>
							<table>
								<tbody>
									<tr>
										<th><label class="l_id">버전</label></th>
										<td>
											<input type="text" name="VER_NO" class="form_input input_100" id="VER_NO" value="">
										</td>
									</tr>
									<tr>
										<th><label class="l_id">비고</label></th>
										<td>
											<textarea name="VER_REMARK" id="VER_REMARK" class="form_input input_100"></textarea>
										</td>
									</tr>
									
								</tbody>
							</table>
						</fieldset>
						
						<div class="bcont">
							<input type="submit" class="submitBtn blue_btn" value="입력"/>
						</div>
						
					</div>

				</form>

			</div>

		</div>
		
	</div>

</div>




<script>





$(".deleteBtn").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('register/delete_ver_form')?>",{IDX:idx},function(data){
		if(data > 0){
			alert('삭제를 완료했습니다');
			location.reload();
		}
	});
});



$(".modi_Btn").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('register/modified_ver_form')?>",{IDX:idx},function(data){

		if(data.IDX != ""){
			$("#pop_container").fadeIn();
			$(".info_content").animate({
				top : "50%"
			},500);
			
			$("input[name='MIDX']").val(idx);
			$("#VER_NO").val(data.VER_NO);
			$("#VER_REMARK").html(data.VER_REMARK);
			//$('#VER_REMARK').summernote('reset');
			$('#VER_REMARK').summernote({
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
					}
				}
			});


		}
	},"JSON");
});




$(".add_verform").on("click",function(){
	
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$('#VER_REMARK').summernote({
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
			}
		}
	});

});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});
</script>