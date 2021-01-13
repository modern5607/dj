<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- 달력 및 에디터호출 -->
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<div style="float:left;">
				<form id="items_formupdate">
					
					<label for="gjgb">공정구분</label>
					<?php
					if(!empty($GJ_GB)){
					?>
						<select name="gjgb" id="gjgb" class="form_select">
							<option value="">all</option>
						<?php
						foreach($GJ_GB as $row){
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo ($str['gjgb'] == $row->D_CODE)?"selected":"";?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}
					?>
					<label for="cg_date">출고일</label>
					<input type="text" class="calendar" name="cg_date" id="cg_date" value="<?php echo ($str['cg_date']!="")?$str['cg_date']:date("Y-m-d",time())?>" />

					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>LOT NO</th>
						<th>BL NO</th>
						<th>수량</th>
						<th>작업완료일</th>
						<th>공정코드</th>
						<th>공정명</th>
						<th>출고여부</th>
						<th>출고완료일</th>
						<th>반품여부</th>
						<th>반품일</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					
				<?php
				
				foreach($relList as $i=>$row){
					$num = $pageNum+$i+1;

					$chkClass = $reDate = "";
					if($row->RE_YN == "Y"){
						$chkClass = "mod_craim link_s1";
						$reDate = substr($row->RE_DATE,0,10);
					}
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td><span class="<?php echo $chkClass;?>" data-idx="<?php echo $row->TIDX;?>" data-customer="<?php echo $row->CUSTOMER;?>"><?php echo $row->BL_NO; ?></span></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="cen"><?php //echo substr($row->END_DATE,0,10); ?></td>
						<td class="cen"><?php //echo $row->GJ_CODE; ?></td>
						<td><?php echo $row->NAME; ?></td>
						<td class="cen"><?php echo $row->CG_YN; ?></td>
						<td class="cen"><?php echo substr($row->CG_DATE,0,10); ?></td>
						<td class="cen"><?php echo $row->RE_YN; ?></td>
						<td class="cen"><?php echo $reDate;  ?></td>
						<td class="cen"><!--button type="button" class="mod mod_material" data-idx="<?php echo $row->IDX;?>">수정</button--></td>
					</tr>

				<?php
				}
				if(empty($relList)){
				?>

					<tr>
						<td colspan="13" class="list_none">출력정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>

		<div class="pagination">
			<?php echo $this->data['pagenation'];?>

			<?php
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
	
	<div class="info_content" style="height:unset;">
		<div class="ajaxContent">			
			
			<h2>
				클래임등록
				<span class="material-icons close">clear</span>
			</h2>
			<div class="formContainer">
				
				<form name="claimform" id="claimform" method="post" action="<?php echo base_url('rel/claimform_update')?>" enctype="multipart/form-data" onsubmit="return claimform_update(this)">
					<input type="hidden" name="idx" id="H_IDX" value="">
					<div class="register_form">
						<fieldset class="form_1">
							<legend>이용정보</legend>
							<table>
								<tbody>
									<tr>
										<th><label class="l_id">BL_NO</label></th>
										<td>
											<span id="blNo"></span>
										</td>
									</tr>
									<tr>
										<th><label class="l_id">거래처</label></th>
										<td>
											<span id="customer"></span>
										</td>
									</tr>
									<tr>
										<th><label class="l_id">사유</label></th>
										<td>
											<textarea name="REMARK" id="REMARK" class="form_input input_100">
												
											</textarea>
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
var IDX = "<?php echo $idx?>";

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


$(".mod_craim").on("click",function(){
	var idx = $(this).data("idx");
	var customer = $(this).data("customer");
	var blNo = $(this).text();

	$("#blNo").text(blNo);
	$("#customer").text(customer);
	$("#H_IDX").val(idx);

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
});






$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('rel/r4/')?>"+qstr+"&perpage="+$(this).val();
	
});







$("input[name='sdate'],input[name='edate']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


function xlsxupload(f){
	
	var file = $("#xfile").val();

	if(!file){
		alert("xlsx파일을 등록하세요");
		return false;
	}

	return;


}

</script>