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
					
					
					<label for="mline">생산LINE</label>
					<select name="mline" id="mline" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="">ALL</option>
						<?php 
						if(!empty($M_LINE)){ 
							foreach($M_LINE as $mline){
								$selected = ($str['mline'] == $mline->D_CODE)?"selected":"";
						?>
						<option value="<?php echo $mline->D_CODE; ?>" <?php echo $selected?>><?php echo $mline->D_NAME; ?></option>
						<?php 
							}
						} 
						?>
					</select>
					
					
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<span class="btn print print_actpln"><i class="material-icons">get_app</i>출력하기</span>
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>LOT NO</th>
						<th>BL NO</th>
						<th>MSAB</th>
						<th>작업일자</th>
						<th>공정코드</th>
						<th>공정명</th>
						<th>수량</th>
						<th>P.T</th>
						<th>생산LINE</th>
						<th>거래처</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($actList as $i=>$row){
					$num = $pageNum+$i+1;
					$finishBtn = ($row->FINISH == "Y")?"xn":"yn finish_btn";
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td class="cen"><?php echo $row->MSAB; ?></td>
						<td class="cen"><?php echo substr($row->ST_DATE,0,10); ?></td>
						<td class="cen"><?php echo $row->GJ_CODE; ?></td>
						<td><?php echo $row->NAME; ?></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="right"><?php echo number_format($row->PT); ?></td>
						<td class="cen"><?php echo $row->M_LINE; ?></td>
						<td style="width:100px"><?php echo $row->CUSTOMER; ?></td>
						<td><span class="mod finish_btn <?php echo $finishBtn;?>" data-idx="<?php echo $row->IDX;?>">작업완료</span></td>
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
	
	<div id="info_content" class="info_content">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>



<script>


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('smt/s_print/')?>"+qstr+"&perpage="+$(this).val();
	
});

$(".print_actpln").on("click",function(){

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	var qstr = "<?php echo $qstr?>";
	var pageNum = "<?php echo $pageNum?>";
	var perpage = "<?php echo $perpage?>";

	$.ajax({
		url:"<?php echo base_url('smt/print_actpln')?>"+qstr+"&pageNum="+pageNum+"&perpage="+perpage,
		type : "get",
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
			//document.getElementById("info_content").print();
		}
		
	});


});


$(".finish_btn").on("click",function(){
	var idx = $(this).data("idx");
	$.ajax({
		url:"<?php echo base_url('smt/finish_actpln')?>",
		type : "post",
		data : {idx:idx},
		dataType : "json",
		success : function(data){
			console.log(data);
			if(data.error){
				alert('잘못된 작업지시입니다.\n관리자에게 문의하세요');
			}else{
				alert(data.msg);
				location.reload();
			}
			
		}
		
	});

});



 
$(document).on('click','.printxx',function() {
	var g_oBeforeBody = $('#info_content .ajaxContent .formContainer').html();
	// 프린트를 보이는 그대로 나오기위한 셋팅
	window.onbeforeprint = function (ev) {
		
		$("body").html(g_oBeforeBody);
		$("body").css("background","#fff");
		$(".tbl-content").css("overflow-y","unset");
		$(".formContainer").css("overflow-y","unset");
	};

	window.print();
	location.reload();
});



$('#items_formupdate input').keypress(function (e) {
  if (e.which == 13) {
    $('#items_formupdate').submit();
    return false;    //<---- Add this line
  }
});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});


$("input[name='st1'],input[name='st2']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

</script>