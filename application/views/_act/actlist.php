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
					
					<label for="date">기간별</label>
					<input type="text" class="calendar" name="insert1" value="<?php echo ($str['insert1']!="")?$str['insert1']:date("Y-m-d",time())?>" />-<input type="text" class="calendar" name="insert2" value="<?php echo ($str['insert2']!="")?$str['insert2']:date("Y-m-d",strtotime('+1 week'))?>" /> 
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span>
			<span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>작업지시번호</th>
						<th>부번</th>
						<th>생산예정일</th>
						<th>생산수량</th>
						<th>사시즈</th>
						<th>공정명</th>
						<th>완료예정일</th>
						<th>계획배포일</th>
						<th>등록일</th>
						<th>등록자</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($actList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><strong><?php echo $row->PLN_NO; ?></strong></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td class="cen"><?php echo substr($row->ST_DATE,0,10); ?></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="right"><?php echo $row->SIZ_NO; ?></td>
						<td><?php echo $row->NAME; ?></td>
						<td class="cen"><?php echo substr($row->ACT_DATE,0,10); ?></td>
						<td class="cen"><?php echo substr($row->PLN_DATE,0,10); ?></td>
						<td class="cen"><?php echo substr($row->INSERT_DATE,0,10); ?></td>
						<td class="cen"><?php echo $row->INSERT_ID; ?></td>
					</tr>

				<?php
				}
				if(empty($actList)){
				?>

					<tr>
						<td colspan="12" class="list_none">제품정보가 없습니다.</td>
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
	
	<div class="info_content" style="height:unset;">
		<div class="ajaxContent">			
			
			<h2>
				엑셀업로드
				<span class="material-icons close">clear</span>
			</h2>
			<div class="formContainer">
				
				<form name="codeHead" id="codeHead" method="post" action="<?php echo base_url('excel/upload_act')?>" enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
					<input type="hidden" name="table" value="T_ACTPLN_EX">
					<div class="register_form">
						<fieldset class="form_1">
							<legend>이용정보</legend>
							<table>
								<tbody>
									<tr>
										<th><label class="l_id">코드</label></th>
										<td>
											<input type="file" name="xfile" id="xfile" value=""  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
										</td>
									</tr>
									<tr>
										<th><label class="l_id">공정구분</label></th>
										<td>
										<?php
										if(!empty($GJ_GB)){
										?>
											<select name="GJ_GB" class="form_input select_call" style="width:100%;">
											<?php
											foreach($GJ_GB as $row){
											?>
												<option value="<?php echo $row->D_CODE?>"><?php echo $row->D_NAME;?></option>
											<?php
											}
											?>
											</select>
										<?php
										}else{
											echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 GJ_GB를 등록하세요</a>";
										}
										?>
										</td>
									</tr>
									<tr>
										<th><label class="l_id">시작행선택</label></th>
										<td>
											<input type="text" name="rownum" id="rownum" value="" class="form_input" size="5" />
										</td>
									</tr>
									<tr>
										<td colspan="2">

											<p>확장자(.xlsx)만 등록가능합니다.</p>
											<p>데이터 시작열을 입력해주세요</p>

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



$(".write_xlsx").on("click",function(){
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
});


$('#items_formupdate input').keypress(function (e) {
  if (e.which == 13) {
    $('#items_formupdate').submit();
    return false;    //<---- Add this line
  }
});


$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('act/index/')?>"+qstr+"&perpage="+$(this).val();
	
});



/*자재정보삭제*/
$(".del_mater").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('bom/ajax_delete_materials/')?>",{idx:idx},function(data){
		alert(data.text);
		if(data.set == 1) location.reload();
	},"JSON");
});




$("input[name='insert1'],input[name='insert2']").datetimepicker({
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