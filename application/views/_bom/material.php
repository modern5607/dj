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

<div class="bdcont_60">
	<div class="bc__box">
		<header>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<div style="float:left;">
				<form id="items_formupdate">
					<select name="seq">
						<option value="COMPONENT" <?php echo ($seq == "COMPONENT")?"selected":"";?>>부품코드</option>
						<option value="COMPONENT_NM" <?php echo ($seq == "COMPONENT_NM")?"selected":"";?>>품명</option>
						<option value="SPEC" <?php echo ($seq == "SPEC")?"selected":"";?>>규격</option>
					</select>
					<input type="text" name="set" value="<?php echo $set?>" />
					<button type="button" class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>부품코드</th>
						<th>품명</th>
						<th>규격</th>
						<th>여유율</th>
						<th>REEL단위</th>
						<th>입고일</th>
						<th>갱신일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($materialList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr id="poc_<?php echo $row->IDX;?>" class="pocbox">
						<td class="cen"><?php echo $num;?></td>
						<td><span class="mod_material_ajax mlink" data-idx="<?php echo $row->IDX;?>"><?php echo $row->COMPONENT; ?></span></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td><?php echo $row->SPEC; ?></td>
						<td><?php echo $row->WORK_ALLO; ?></td>
						<td><?php echo $row->REEL_CNT; ?></td>
						<td><?php echo $row->INTO_DATE; ?></td>
						<td><?php echo $row->REPL_DATE; ?></td>
						<!--td class="cen"><button type="button" class="mod mod_material" data-idx="<?php echo $row->IDX;?>">수정</button></td-->
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

		<header>
			<span class="btn add add_material"><i class="material-icons">add</i>신규등록</span>
			<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
		</header>

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
		$(".tbl-content").scrollTop(offset.top - 200);
	}

	ajax_container(0);
});





$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('bom/materials/')?>"+qstr+"&perpage="+$(this).val();
	
});



/* 검색
* 검색어가 새롭게 들어가면 qstr reset 
*/
$(".search_submit").on("click",function(){
	var qstr = "<?php echo $qstr ?>";	
	var seq = $("select[name='seq']").val();
	var set = $("input[name='set']").val();
	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";
	
	if(set == ""){
		//alert("검색어를 입력하세요");
		//$("input[name='set']").focus();
		//return false;
		seq = "all";
	}

	location.href="<?php echo base_url('bom/materials/')?>?seq="+seq+"&set="+set;


	
});


/*자재정보삭제*/
$(".del_mater").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('bom/ajax_delete_materials/')?>",{idx:idx},function(data){
		alert(data.text);
		if(data.set == 1) location.reload();
	},"JSON");
});



$(".mod_material").on("click",function(){
	var idx = $(this).data("idx");
	var qstr = "<?php echo $qstr ?>";
	
	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";
	

	location.href="<?php echo base_url('bom/materials/')?>"+idx+qstr+perpage;
});

function ajax_container(idx){

	$.ajax({
		url : "<?php echo base_url('bom/materials_ajax/')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$("#ajax_container").html(data);
		}
	});

}


$(document).on("click",".mod_material_ajax",function(){
	var idx = $(this).data("idx");

	$(".pocbox").removeClass("over");
	$("#poc_"+idx).addClass("over");

	ajax_container(idx);
});


$(".mod_material").on("click",function(){
	var idx = $(this).data("idx");
	var qstr = "<?php echo $qstr ?>";
	
	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";
	

	location.href="<?php echo base_url('bom/materials/')?>"+idx+qstr+perpage;
});





$(".add_material").on("click",function(){
	location.href="<?php echo base_url('bom/materials')?>";
});


function materialformchk(f){
	
	var component   = $("input[name='COMPONENT']");
	var componentNM = $("input[name='COMPONENT_NM']");
	var spec        = $("input[name='SPEC']");
	var REEL_CNT    = $("input[name='REEL_CNT']");
	var WORK_ALLO   = $("input[name='WORK_ALLO']");

	var midx = $("input[name='midx']").val();

	if(component.val() == ""){
		alert("부품명을 입력하세요");
		component.focus();
		return false;
	}

	if(componentNM.val() == ""){
		alert("품명을 입력하세요");
		componentNM.focus();
		return false;
	}

	if(spec.val() == ""){
		alert("규격을 입력하세요");
		spec.focus();
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
			
			
			//$(".ajaxContent").html(data);
			alert(data);
			if(midx != ""){

				var parent = $("#poc_"+midx+" td");
				
				parent.eq(1).html('<span class="mod_material_ajax mlink" data-idx="'+midx+'">'+component.val()+'</span>');
				parent.eq(2).html(componentNM.val());
				parent.eq(3).html(spec.val());
				parent.eq(5).html(number_format(REEL_CNT.val()));
				parent.eq(4).html(WORK_ALLO.val());

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