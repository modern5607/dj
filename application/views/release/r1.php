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

					<label for="trans_date">일자</label>
					<input type="text" class="calendar" name="trans_date" id="trans_date" value="<?php echo ($str['trans_date']!="")?$str['trans_date']:date("Y-m-d",time())?>" />
					
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
						<th>생산라인</th>
						<th>수량</th>
						<th>제작수량</th>
						<th>출고수량</th>
						<th>작업완료일</th>
						<th>출고여부</th>
						<th>출고완료일</th>
						<th>반품여부</th>
						<th>반품완료일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($relList as $i=>$row){
					$num = $pageNum+$i+1;

					$btnText = "출고하기";
					$btnCss  = "yn trans_btn";

					$btnText_return = "반품처리";
					$btnCss_return  = "yn return_chk";

					if($row->QTY < 1){
						$btnText = "수량부족";
						$btnCss  = "xn";
					}else{

						if($row->XXX < 1){
							$btnText = "출고완료";
							$btnCss  = "xn";
						}
					}

					if($row->XNUM < 1 || (($row->RE_QTY == $row->QTY) && ($row->RE_QTY == $row->OUT_QTY))){
						$btnText_return = "반품불가";
						$btnCss_return  = "xn";
					}
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td class="cen"><?php echo $row->MLINE; ?></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="right"><?php echo number_format($row->OUT_QTY); ?></td>
						<td class="cen"><input type="text" name="outQty" size="5" class="row_input" value="<?php echo $row->XXX;?>" /></td>
						<td class="cen"><?php echo substr($row->FINISH_DATE,0,10); ?></td>
						<td class="cen"><span class="mod <?php echo $btnCss;?>" data-idx="<?php echo $row->IDX;?>"><?php echo $btnText?></span></td>
						<td class="cen"><?php echo substr($row->CG_DATE,0,10); ?></td>
						<td class="cen"><span class="mod <?php echo $btnCss_return;?>" data-idx="<?php echo $row->IDX;?>"><?php echo $btnText_return;?></span></td>
						<td class="cen"><?php echo substr($row->RE_DATE,0,10); ?></td>
					</tr>

				<?php
				}
				if(empty($relList)){
				?>

					<tr>
						<td colspan="12" class="list_none">출력정보가 없습니다.</td>
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
	
	<div id="info_content" class="info_content" style="height:unset;">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>


<script>
var IDX = "<?php echo $idx?>";

$(".trans_btn").on("click",function(){
	var idx = $(this).data("idx");
	var qty = $(this).parents("tr").find("input[name='outQty']").val();
	
	$.ajax({
		url : "<?php echo base_url('rel/ajax_trans_items')?>",
		type : "post",
		data : {idx:idx,qty:qty},
		dataType : "json",
		success : function(data){
			if(data.chk > 0){
				alert('출고되었습니다.');
				location.reload();
			}
		}
	});
});



$(".return_chk").on("click",function(){
	
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
	
	var idx = $(this).data("idx");
	
	$.ajax({
		url:"<?php echo base_url('rel/ajax_return_form')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);

			//document.getElementById("info_content").print();
		}
		
	});


});



$(document).on("click",".btn_return",function(){
	var idx = $(this).data("idx");
	var rNum = $(this).parents("tr").find("input[name='qty']");
	var ocount = $(this).parents("tr").find("input[name='out']");

	if(rNum.val() > ocount.val()){
		alert("반품수량은 출고수량보다 높을 수 없습니다.");
		rNum.focus();
		return false;
	}

	if(confirm("해당출고상품 "+rNum.val()+"개를 반품처리 하시겠습니까?") !== false){
		$.ajax({
			url : "<?php echo base_url('rel/ajax_returnNum_form')?>",
			data : {idx:idx,rNum:rNum.val()},
			type : "POST",
			dataType : "JSON",
			success : function(data){
				if(data.chk){
					alert("반품처리가 완료되었습니다.");
				}else{
					alert("반품처리 실패\n관리자에게 문의하세요.");
				}
				$(".ajaxContent").html('');
				$("#pop_container").fadeOut();
				$(".info_content").css("top","-50%");
				location.reload();

			}
		});
	}
});




$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	location.reload();
	
});


$("input[name='trans_date']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});



$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('rel/r1/')?>"+qstr+"&perpage="+$(this).val();
	
});



</script>