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
					<input type="text" name="cg_date" id="cg_date" class="calendar" value="<?php echo ($str['cg_date']!="")?$str['cg_date']:date("Y-m-d",time())?>" />

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
						<th>BL NO</th>
						<th>반품일</th>
						<th>수량</th>
						<th>반품수량</th>
						<th>거래처</th>
						<th>사유</th>
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
						<td><?php echo $row->BL_NO; ?></td>
						<td class="cen"><?php echo substr($reDate,0,10);  ?></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="right"><?php echo number_format($row->RE_QTY); ?></td>
						<td><?php echo $row->CUSTOMER; ?></td>
						<td><?php echo $row->REMARK; ?></td>
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
	
	<div class="info_content">
		<div class="ajaxContent">			
			
			<h2>
				엑셀업로드
				<span class="material-icons close">clear</span>
			</h2>
			<div class="formContainer">
				
				<form name="codeHead" id="codeHead" method="post" action="<?php echo base_url('excel/upload_act')?>" enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
					<input type="hidden" name="table" value="T_TEMP">
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


$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('rel/r4/')?>"+qstr+"&perpage="+$(this).val();
	
});



/* 검색 */
$(".search_submit").on("click",function(){

	var set = $("input[name='set']").val();
	var sdate = $("input[name='sdate']").val();
	var edate = $("input[name='edate']").val();
	
	if(set == ""){
		//alert("검색어를 입력하세요");
		//$("input[name='set']").focus();
		//return false;
		//seq = "all";
	}

	location.href="<?php echo base_url('rel/r4/?set=')?>"+set+"&sdate="+sdate+"&edate="+edate;
});


$("select[name='seq']").on("change",function(){
	var code = $(this).val();
	switch(code){
		case "COMPONENT":
			$("input[name='set']").hide();
			$(".setdate").show();
			break;
		case "COMPONENT_NM":
			$("input[name='set']").show();
			$(".setdate").hide();
			break;
	}
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