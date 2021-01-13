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
					
					<label for="component">자재코드</label>
					<input type="text" name="component" id="component" value="<?php echo $str['component']?>" size="15" />

					<label for="comp_name">자재명</label>
					<input type="text" name="comp_name" id="comp_name" value="<?php echo $str['comp_name']?>" size="15" />

					<label for="spec">규격</label>
					<input type="text" name="spec" id="spec" value="<?php echo $str['spec']?>" size="15" />

					
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>자재코드</th>
						<th>자재명</th>
						<th>규격</th>
						<th>재고량</th>
						<th>단위</th>
						<th>입고일</th>
						<th>갱신일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($materialList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><strong><?php echo $row->COMPONENT; ?></strong></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td><?php echo $row->SPEC; ?></td>
						<td class="right"><?php echo number_format($row->STOCK); ?></td>
						<td class="cen"><?php echo $row->UNIT; ?></td>
						<td class="cen"><?php echo substr($row->INTO_DATE,0,10); ?></td>
						<td class="cen"><?php echo substr($row->REPL_DATE,0,10); ?></td>
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
});

$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('bom/stocklist/')?>"+qstr+"&perpage="+$(this).val();
	
});

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


/*자재정보삭제*/
$(".del_mater").on("click",function(){
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('bom/ajax_delete_materials/')?>",{idx:idx},function(data){
		alert(data.text);
		if(data.set == 1) location.reload();
	},"JSON");
});

/* 검색 */
$(".search_submit").on("click",function(){

	var seq = $("select[name='seq']").val();
	var set = $("input[name='set']").val();
	
	if(set == ""){
		//alert("검색어를 입력하세요");
		//$("input[name='set']").focus();
		//return false;
		seq = "all";
	}

	location.href="<?php echo base_url('bom/stocklist/?seq=')?>"+seq+"&set="+set;


	
});


$("#INTO_DATE,#REPL_DATE").datetimepicker({
	format:'Y-m-d H:i:00',
	lang:'ko-KR'
});


$(".mod_material").on("click",function(){
	var idx = $(this).data("idx");
	location.href="<?php echo base_url('bom/materials/')?>"+idx;
});


$(".add_material").on("click",function(){
	location.href="<?php echo base_url('bom/materials')?>";
});


function materialformchk(f){
	
	var component   = $("input[name='COMPONENT']");
	var componentNM = $("input[name='COMPONENT_NM']");
	var spec        = $("input[name='SPEC']");

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
	

	return;


}

</script>