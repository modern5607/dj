<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

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
			<input type="text" name="mscode" value="<?php echo $str['mscode']?>" size="6" />
			<label for="">생산라인</label>
			<input type="text" name="mline" value="<?php echo $str['mline']?>" size="6" />
		
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
		
		
			<?php if(!empty($idx)){ ?>
			<span class="btn_right add add_bom" data-idx="<?php echo $idx;?>"><i class="material-icons">add</i>등록/제거</span>
			<?php } ?>
			
		</form>
		
	</div>

	
	<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
	<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
</div> 

<div class="bdcont_40">
	
	
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
						<th>하위자재</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($bomList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr id="poc_<?php echo $row->IDX;?>" class="<?php echo ($idx == $row->IDX)?"over":""; ?>">
						<td class="cen"><?php echo $num;?></td>
						<td style="max-width:140px;"><span class="mod_items mlink" data-idx="<?php echo $row->IDX;?>"><?php echo $row->BL_NO; ?></span></td>
						<td style="max-width:140px;"><?php echo $row->ITEM_NAME; ?></td>
						<td class="cen"><?php echo $row->MSAB; ?></td>
						<td><?php echo $row->M_LINE; ?></td>
						<td class="cen"><?php echo ($row->C_COUNT > 0)?"<strong>".$row->C_COUNT."</strong>":"<span class='gray'>-</span>";?><!--button type="button" class="mod mod_items" data-idx="<?php echo $row->IDX;?>">선택</button--></td>
					</tr>

				<?php
				}
				if(empty($bomList)){
				?>

					<tr>
						<td colspan="7" class="list_none">제품정보가 없습니다.</td>
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
						<th>재료비</th>
						<th>REEL단위</th>
						<th>POINT</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($insertBomList as $i=>$row){
				?>

					<tr>
						<td class="cen"><?php echo $i+1;?></td>
						<td><?php echo $row->COMPONENT; ?></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td class="cen"><?php echo $row->UNIT; ?></td>
						<td class="right"><?php echo number_format($row->PRICE); ?></td>
						<td class="right"><?php echo number_format($row->REEL_CNT); ?></td>
						<td><input type="text" name="POINT" class="form_input" size="6" value="<?php echo $row->POINT; ?>"/></td>
						<td><button type="button" class="mod mod_bom" data-idx="<?php echo $row->BIDX;?>">수정</button></td>
					</tr>

				<?php
				}
				if(empty($insertBomList)){
				?>

					<tr>
						<td colspan="13" class="list_none">제품정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>
		
		

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
	var offset = $("#poc_"+IDX).offset();
	/*$(".tbl-content").animate({
		scrollTop:offset.top
	},100);*/
	$(".tbl-content").scrollTop(offset.top - 200);
});
	
$(".mod_items").on("click",function(){
	var idx = $(this).data("idx");

	var qstr = "<?php echo $qstr ?>";
	
	var pp = $("select[name='per_page']").val();
	var perpage = (pp != "")?"&perpage="+pp:"";

	location.href="<?php echo base_url('bom/insert/')?>"+idx+qstr+perpage;
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('bom/insert/')?>"+qstr+"&perpage="+$(this).val();
	
});



$(".search_submit").on("click",function(){

	var seq = $("select[name='seq']").val();
	var set = $("input[name='set']").val();
	
	if(set == ""){
		//alert("검색어를 입력하세요");
		//$("input[name='set']").focus();
		//return false;
		seq = "all";
	}

	location.href="<?php echo base_url('bom/insert/?seq=')?>"+seq+"&set="+set;


	
});



$(".mod_bom").on("click",function(){
	var idx   = $(this).data("idx");
	var work  = $(this).parents("tr").find("input[name='WORK_ALLO']").val();
	var pt    = $(this).parents("tr").find("input[name='PT']").val();
	var point = $(this).parents("tr").find("input[name='POINT']").val();
	var reel  = $(this).parents("tr").find("input[name='REEL_CNT']").val();
	
	$.post("<?php echo base_url('bom/ajax_bomlist_update')?>",{idx:idx,work:work,pt:pt,point:point,reel:reel},function(data){
		if(data > 0){
			alert('변경되었습니다.');
			location.reload();
		}
	});

});



$(".add_bom").on("click",function(){
		
	var idx = $(this).data("idx");

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('bom/ajax_bomWriteform')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"add",idx:idx},
		success  : function(data){
			$(".ajaxContent").html(data);
		},
		error    : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	})

});

$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});


</script>