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

<div class="searchBox">
	<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
	<div>
		<form id="items_formupdate">
			
			<label for="">자재코드</label>
			<input type="text" name="component" value="<?php echo $str['component']?>" size="15" />					
			<label for="">자재명</label>
			<input type="text" name="comp_name" value="<?php echo $str['comp_name']?>" size="15" />
			<label for="">공정구분</label>
			<?php
			if(!empty($GJ_GB)){
			?>
				<select name="gjcode" style="padding:4px 10px; border:1px solid #ddd;">
					<option value="">ALL</option>
				<?php
				foreach($GJ_GB as $row){
					$selected8 = ($str['gjcode'] == $row->D_CODE)?"selected":"";
				?>
					<option value="<?php echo $row->D_CODE?>" <?php echo $selected8;?>><?php echo $row->D_NAME;?></option>
				<?php
				}
				?>
				</select>
			<?php
			}
			?>
			<label for="">사용유무</label>
			<input type="checkbox" name="use" value="Y" <?php echo ($str['use'] == "Y")?"checked":"";?> />
			
			<button class="search_submit"><i class="material-icons">search</i></button>

			<span class="btn_right add add_material"><i class="material-icons">add</i>신규등록</span>
		
		</form>
	</div>
	<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
</div>

<div class="bdcont_60">
	<div class="bc__box">
		
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>자재코드</th>
						<th>자재명</th>
						<th>단위</th>
						<th>REEL단위</th>
						<th>공정구분</th>
						<th>사용유무</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($materialList as $i=>$row){
					$num = $pageNum+$i+1;
					$useYn = ($row->USE_YN == "Y")?"사용":"미사용";
				?>

					<tr id="poc_<?php echo $row->IDX;?>" class="pocbox">
						<td class="cen"><?php echo $num;?></td>
						<td><span class="mod_stock_ajax mlink" data-idx="<?php echo $row->IDX;?>"><?php echo $row->COMPONENT; ?></span></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td class="cen"><?php echo $row->UNIT; ?></td>
						<td class="right"><?php echo number_format($row->REEL_CNT); ?></td>
						<td class="cen"><?php echo $row->GJ_GB; ?></td>
						<td class="cen"><?php echo $useYn; ?></td>
						<!--td class="cen"><button type="button" class="mod mod_stock" data-idx="<?php echo $row->IDX;?>">수정</button></td-->
					</tr>

				<?php
				}
				if(empty($materialList)){
				?>

					<tr>
						<td colspan="9" class="list_none">제품정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>

		<div class="pagination">
			<?php echo $this->data['pagenation'];?>
			<div class="limitset">
				<select name="per_page">
					<option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
					<option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
					<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
					<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
				</select>
			</div>
		</div>

	</div>
</div>
<div class="bdcont_40">
	<div class="bc__box">

		
		<div id="ajax_container"></div>
		
		<!--form name="materialform" id="materialform" method="post" action="<?php echo base_url('bom/materialUpdate');?>" onsubmit="return materialformchk(this)">
		<input type="hidden" name="midx" value="<?php echo (!empty($materialInfo)?$materialInfo->IDX:"");?>" />
		<div class="tbl-write01">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				
				<tbody>
				
					<tr>
						<th class="w120">부품코드</th>
						<td colspan="3"><input type="text" name="COMPONENT" value="<?php echo (!empty($materialInfo)?$materialInfo->COMPONENT:"");?>" class="form_input input_100" /></td>
					</tr>
					<tr>
						<th class="w120">품명</th>
						<td colspan="3"><input type="text" name="COMPONENT_NM" value="<?php echo (!empty($materialInfo)?$materialInfo->COMPONENT_NM:"");?>" class="form_input input_100" /></td>
					</tr>
					<tr>
						<th class="w120">규격</th>
						<td colspan="3"><input type="text" name="SPEC" value="<?php echo (!empty($materialInfo)?$materialInfo->SPEC:"");?>" class="form_input input_100" /></td>
					</tr>
					<tr>
						<th class="w120">REEL단위</th>
						<td><input type="text" name="REEL_CNT" value="<?php echo (!empty($materialInfo)?$materialInfo->REEL_CNT:"");?>" class="form_input input_100" /></td>
						<th class="w120">여유율</th>
						<td><input type="text" name="WORK_ALLO" value="<?php echo (!empty($materialInfo)?$materialInfo->WORK_ALLO:"");?>" class="form_input input_100" /></td>
					</tr>
					<tr>
						<th class="w120">PㆍT</th>
						<td><input type="text" name="PT" value="<?php echo (!empty($materialInfo)?$materialInfo->PT:"");?>" class="form_input input_100" /></td>
						<th class="w120">단가</th>
						<td><input type="text" name="PRICE" value="<?php echo (!empty($materialInfo)?$materialInfo->PRICE:"");?>" class="form_input input_100" /></td>
					</tr>
					<tr>
						<th class="w120">입고일</th>
						<td colspan="3"><input type="text" name="INTO_DATE" id="INTO_DATE" value="<?php echo (!empty($materialInfo)?$materialInfo->INTO_DATE:"");?>" class="form_input input_100" /></td>
					</tr>
					<tr>
						<th class="w120">사용갱신일</th>
						<td colspan="3"><input type="text" name="REPL_DATE" id="REPL_DATE" value="<?php echo (!empty($materialInfo)?$materialInfo->REPL_DATE:"");?>" class="form_input input_100" /></td>
					</tr>
					<tr>
						<th class="w120">GJ_GB</th>
						<td colspan="3">
						<?php
						if(!empty($GJ_GB)){
						?>
							<select name="GJ_GB" class="form_input select_call" style="width:100%;">
							<?php
							foreach($GJ_GB as $row){
								$selected5 = (!empty($materialInfo) && $materialInfo->GJ_GB == $row->D_CODE)?"selected":"";
							?>
								<option value="<?php echo $row->D_CODE?>" <?php echo $selected5;?>><?php echo $row->D_NAME;?> (<?php echo $row->D_CODE?>)</option>
							<?php
							}
							?>
							</select>
						<?php
						}else{
							echo "<a href='".base_url('main')."' class='none_code'>공통코드 GJ_GB를 등록하세요</a>";
						}
						?>
						</td>
					</tr>
					<tr>
						<th class="w120">비고</th>
						<td colspan="3">
							<textarea name="REMARK" id="REMARK" class="form_input input_100">
								<?php echo (!empty($materialInfo))?$materialInfo->REMARK:"";?>
							</textarea>
						</td>
					</tr>
					
					<tr>
						<td colspan="4" style="text-align:center; padding:15px 0;">
							<button class="btn blue_btn">저장</button>
							<?php if(!empty($materialInfo)){ ?>
							<button type="button" data-idx="<?php echo $materialInfo->IDX; ?>" class="btn blue_btn del_mater">삭제</button>
							<?php } ?>
						</td>
					</tr>

				
				</tbody>
			</table>
		</div>

		</form-->

	</div>
</div>




<script>

var IDX = "<?php echo $idx?>";
$(function(){

	if(IDX != ""){
		var offset = $("#poc_"+IDX).offset();
		/*$(".tbl-content").animate({
			scrollTop:offset.top
		},100);*/
		console.log(offset);
		$(".tbl-content").scrollTop(offset.top - 200);
	}

	ajax_container(0);
});

$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('bom/stock/')?>"+qstr+"&perpage="+$(this).val();
	
});


$('#items_formupdate input').keypress(function (e) {
  if (e.which == 13) {
    $('#items_formupdate').submit();
    return false;    //<---- Add this line
  }
});




/*자재정보삭제*/
$(".del_mater").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('bom/ajax_delete_materials/')?>",{idx:idx},function(data){
		alert(data.text);
		if(data.set == 1) location.reload();
	},"JSON");
});





$(".mod_stock").on("click",function(){
	var idx = $(this).data("idx");
	var qstr = "<?php echo $qstr ?>";

	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";


	location.href="<?php echo base_url('bom/stock/')?>"+idx+qstr+perpage;
});


function ajax_container(idx){
	
	$.ajax({
		url : "<?php echo base_url('bom/stock_ajax/')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$("#ajax_container").html(data);
		}
	});

}


$(document).on("click",".mod_stock_ajax",function(){
	var idx = $(this).data("idx");
	
	$(".pocbox").removeClass("over");
	$("#poc_"+idx).addClass("over");

	ajax_container(idx);
});


$(".add_material").on("click",function(){
	location.href="<?php echo base_url('bom/stock')?>";
});





function materialformchk(f){
	
	var component   = $("input[name='COMPONENT']");
	var componentNM = $("input[name='COMPONENT_NM']");
	var GJGB        = $("select[name='GJ_GB']");
	var REEL_CNT    = $("input[name='REEL_CNT']");
	var UNIT        = $("select[name='UNIT']");
	var USEYN       = ($("select[name='USE_YN']").val()=="Y")?"사용":"미사용";

	var midx = $("input[name='midx']").val();
	
	if(component.val() == ""){
		alert("자재코드를 입력하세요");
		component.focus();
		return false;
	}

	if(componentNM.val() == ""){
		alert("자재명을 입력하세요");
		componentNM.focus();
		return false;
	}

	
	//ajax 형태로 변경
	var formData = new FormData($("#materialform")[0]);
	
	$.ajax({
		url  : "<?php echo base_url('bom/materialUpdate')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		beforeSend  : function(){
			//$this.hide();
			//$("#loading").show();
			//$(".ajaxContent").html('');
		},
		success : function(data){
			
			alert(data);
			if(midx != ""){
				var parent = $("#poc_"+midx+" td");
				
				parent.eq(1).html('<span class="mod_stock_ajax mlink" data-idx="'+midx+'">'+component.val()+'</span>');
				parent.eq(2).html(componentNM.val());
				parent.eq(3).html(UNIT.val());
				parent.eq(4).html(number_format(REEL_CNT.val()));
				parent.eq(5).html(GJGB.val());
				parent.eq(6).html(USEYN);

			}else{
				location.reload();
			}
			/*
			if(jsonData.status == "ok"){
			
				setTimeout(function(){
					//alert(jsonData.msg);
					//$(".ajaxContent").html('');
					//$("#pop_container").fadeOut();
					//$(".info_content").css("top","-50%");
					//$("#loading").hide();
					//location.reload();

				},1000);

			}*/
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});


	

	return false;


}

</script>