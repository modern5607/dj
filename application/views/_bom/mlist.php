<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div id="pageTitle">
	<h1><?php echo $title;?></h1>
</div>
<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<!--div style="float:left;">
				<form id="items_formupdate">
					<select name="seq">
						<option value="COMPONENT" <?php echo ($seq == "COMPONENT")?"selected":"";?>>부품코드</option>
						<option value="COMPONENT_NM" <?php echo ($seq == "COMPONENT_NM")?"selected":"";?>>품명</option>
						<option value="SPEC" <?php echo ($seq == "SPEC")?"selected":"";?>>규격</option>
					</select>
					<input type="text" name="set" value="<?php echo $set?>" />
					<button type="button" class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div-->
			<span class="btn print write_xlsx"><i class="material-icons">get_app</i>액셀업로드</span>
			<span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span>
			<span class="btn print setComponent"><i class="material-icons">get_app</i>일괄수정</span>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>자재코드</th>
						<th>자재명</th>
						<th>단위</th>
						<th>현재고량</th>
						<th>비교재고량</th>
						<th class="rl_board">차이</th>
						<th>입고일</th>
						<th>갱신일</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($materialList as $i=>$row){
					$num = $pageNum+$i+1;
					if(!empty($row->E_STOCK)){
						
						if($row->E_STOCK > $row->STOCK){
							$setStock = $row->STOCK - $row->E_STOCK;
						}elseif($row->E_STOCK < $row->STOCK){
							$setStock = $row->STOCK - $row->E_STOCK;
						}else{
							$setStock = $row->STOCK - $row->E_STOCK;
						}
						
					}else{
						$setStock = $row->STOCK;
						
					}
					
					$backbg = "";
					if($row->COMPONENT == NULL){
						$backbg = "style='background:#fbe6e6'";
					}
					


				?>

					<tr id="poc_<?php echo $row->IDX;?>" class="pocbox" <?php echo $backbg;?>>
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->E_COMPONENT; ?></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td class="cen"><?php echo $row->UNIT; ?></td>
						<td style="width:100px; text-align:right"><?php echo number_format($row->STOCK); ?></td>
						<td style="width:100px; text-align:right"><?php echo number_format($row->E_STOCK); ?></td>
						<td class="rl_board" style="width:100px; text-align:right"><strong><?php echo number_format($setStock); ?></strong></td>
						<td class="cen"><?php echo substr($row->INTO_DATE,0,10); ?></td>
						<td class="cen"><?php echo substr($row->REPL_DATE,0,10); ?></td>
						<td class="cen"><button type="button" class="mod mod_material" data-component="<?php echo $row->E_COMPONENT;?>">수정</button></td>
					</tr>

				<?php
				}
				if(empty($materialList)){
				?>

					<tr>
						<td colspan="10" class="list_none">자재정보가 없습니다.</td>
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
				엑셀업로드
				<span class="material-icons close">clear</span>
			</h2>
			<div class="formContainer">
				
				<form name="codeHead" id="codeHead" method="post" action="<?php echo base_url('excel/upload_mat')?>" enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
					<input type="hidden" name="table" value="T_COMPONENT_EX">
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



$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});

$(".write_xlsx").on("click",function(){
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('mat/materials/')?>"+qstr+"&perpage="+$(this).val();
	
});



$(".setComponent").on("click",function(){

	$.ajax({
		url : "<?php echo base_url('mat/ajax_setComponent')?>",
		type : "post",
		data : {mode:'all'},
		dataType : "json",
		success : function(data){
			alert(data.tcount+"건중 "+data.scount+"건의 처리가 완료되었습니다.");
		}
	});

});


$(".mod_material").on("click",function(){
	var comp = $(this).data("component");
	$.ajax({
		url : "<?php echo base_url('mat/ajax_setComponent')?>",
		type : "post",
		data : {mode:'one', component:comp},
		dataType : "json",
		success : function(data){
			alert(data.tcount+"건중 "+data.scount+"건의 처리가 완료되었습니다.");
			window.location.reload();
		}
	});

});



function xlsxupload(f){
	
	var file = $("#xfile").val();

	if(!file){
		alert("xlsx파일을 등록하세요");
		return false;
	}

	return;


}


/*if(nnum > 0){
	alert('자재데이터가 없는 자재('+nnum+')건이 있습니다.\n일괄수정시 자재데이터가 자동등록됩니다.');
}*/

</script>