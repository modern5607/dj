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
	<div>
		<form id="items_formupdate">
		
			<label for="">BL_NO</label>
			<input type="text" name="bno" value="<?php echo $str['bno']?>" size="15" />					
			<label for="">품명</label>
			<input type="text" name="iname" value="<?php echo $str['iname']?>" size="15" />
			<label for="">MSAB</label>
			<input type="text" name="mscode" value="<?php echo $str['mscode']?>" size="6" onkeypress="press(this.form)" />
			<label for="">생산라인</label>
			<?php
			if(!empty($M_LINE)){
			?>
				<select name="mline" style="padding:4px 10px; border:1px solid #ddd;">
					<option value="">ALL</option>
				<?php
				foreach($M_LINE as $row){
					$selected1 = ($str['mline'] == $row->D_CODE)?"selected":"";
				?>
					<option value="<?php echo $row->D_CODE?>" <?php echo $selected1;?>><?php echo $row->D_NAME;?></option>
				<?php
				}
				?>
				</select>
			<?php
			}
			?>
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
			<!--input type="text" name="gjcode" value="<?php echo $str['gjcode']?>" size="6" onkeypress="press(this.form)" /-->
			<label for="">사용유무</label>

			<input type="checkbox" name="use" value="Y" <?php echo ($str['use'] == "Y")?"checked":"";?> />
			<button class="search_submit"><i class="material-icons">search</i></button>
		
			<span class="btn_right add add_items"><i class="material-icons">add</i>신규등록</span>
			
			
		</form>
	</div>
</div>

<div class="bdcont_60">
	<div class="bc__box">

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>B/L NO</th>
						<th>품명</th>
						<th>MSAB</th>
						<th>생산라인</th>
						<th>공정구분</th>
						<th>사용유무</th>
						<!--th></th-->
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($bomList as $i=>$row){
					$num = $pageNum+$i+1;
					$useYn = ($row->USE_YN == "Y")?"사용":"미사용";
				?>

					<tr id="poc_<?php echo $row->IDX;?>" class="pocbox">
						<td class="cen"><?php echo $num;?></td>
						<td><span class="mod_items_ajax mlink" data-idx="<?php echo $row->IDX;?>"><?php echo $row->BL_NO; ?></span></td>
						<td><?php echo $row->ITEM_NAME; ?></td>
						<td><?php echo $row->MSAB; ?></td>
						<td><?php echo $row->M_LINE; ?></td>
						<td><?php echo $row->GJ_GB; ?></td>
						<td class="cen"><?php echo $useYn; ?></td>
						<!--td class="cen"><button type="button" class="mod mod_items" data-idx="<?php echo $row->IDX;?>">수정</button></td-->
					</tr>

				<?php
				}
				if(empty($bomList)){
				?>

					<tr>
						<td colspan="8" class="list_none">제품정보가 없습니다.</td>
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

<div class="bdcont_40">
	
	<div class="bc__box">
		
		<div id="ajax_container"></div>
		
		

	</div>

</div>










<div id="pop_container">
	
	<div class="info_content">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
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


$('#items_formupdate input').keypress(function (e) {
  if (e.which == 13) {
    $('#items_formupdate').submit();
    return false;    //<---- Add this line
  }
});




$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('bom/index/')?>"+qstr+"&perpage="+$(this).val();
	
});


function ajax_container(idx){
	
	$.ajax({
		url : "<?php echo base_url('bom/index_ajax/')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$("#ajax_container").html(data);
		}
	});

}


$(".mod_items").on("click",function(){
	var idx = $(this).data("idx");
	var seq = "<?php echo $seq ?>";
	var set = "<?php echo $set ?>";
	var qstr = "<?php echo $qstr ?>";

	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";

	

	location.href="<?php echo base_url('bom/index/')?>"+idx+qstr+perpage;
});


$(document).on("click",".mod_items_ajax",function(){
	var idx = $(this).data("idx");

	$(".pocbox").removeClass("over");
	$("#poc_"+idx).addClass("over");

	ajax_container(idx);
});



$(".add_items").on("click",function(){
	var qstr = "<?php echo $qstr ?>";

	location.href="<?php echo base_url('bom')?>"+qstr;
});


/*자재정보삭제*/
$(".del_items").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('bom/ajax_delete_items/')?>",{idx:idx},function(data){
		alert(data.text);
		if(data.set == 1) location.reload();
	},"JSON");
});







function itemsformchk(f){
	
	var BLNO     = $("input[name='BL_NO']");
	var ITEMNAME = $("input[name='ITEM_NAME']");
	var ITEMSPEC = $("input[name='ITEM_SPEC']");
	var MSAB     = $("select[name='MSAB']");
	var MLINE    = $("select[name='M_LINE']");
	var GJGB     = $("select[name='GJ_GB']");
	var USEYN    = ($("select[name='USE_YN']").val()=="Y")?"사용":"미사용";


	var midx = $("input[name='midx']").val();



	if(BLNO.val() == ""){
		alert('B/L NO를 작성하세요.');
		BLNO.focus();
		return false;
	}

	if(ITEMNAME.val() == ""){
		alert('품명를 작성하세요.');
		ITEMNAME.focus();
		return false;
	}

	if(ITEMSPEC.val() == ""){
		alert('규격를 작성하세요.');
		ITEMSPEC.focus();
		return false;
	}


	
	var line1 = $("#LINE1");
	var line2 = $("#LINE2");
	var line3 = $("#LINE3");

	
	

	if(((line1.val() == line2.val() && line2.val() != "")) || ((line1.val() == line3.val() && line3.val() != "")) || ((line2.val() == line3.val()) && line2.val() != "" && line3.val() != "")){
		alert('생산라인은 중복할 수 없습니다.');
		return false;
	}

	if((line1.val() != "") && $("input[name='P_T']").val() == ""){
		alert("1번 생산라인의 소요시간을 입력하세요");
		$("input[name='P_T']").focus();
		return false;
	}

	if((line2.val() != "") && $("input[name='2ND_P_T']").val() == ""){
		alert("2번 생산라인의 소요시간을 입력하세요");
		$("input[name='2ND_P_T']").focus();
		return false;
	}

	if((line3.val() != "") && $("input[name='3ND_P_T']").val() == ""){
		alert("3번 생산라인의 소요시간을 입력하세요");
		$("input[name='3ND_P_T']").focus();
		return false;
	}

	
	//ajax 형태로 변경
	var formData = new FormData($("#itemsform")[0]);
	
	$.ajax({
		url  : "<?php echo base_url('bom/itemsUpdate')?>",
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
				
				parent.eq(1).html('<span class="mod_items_ajax mlink" data-idx="'+midx+'">'+BLNO.val()+'</span>');
				parent.eq(2).html(ITEMNAME.val());
				parent.eq(3).html(MSAB.val());
				parent.eq(4).html(MLINE.val());
				parent.eq(5).html(GJGB.val());
				parent.eq(6).html(USEYN);

			}else{
				location.reload();
			}
			
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