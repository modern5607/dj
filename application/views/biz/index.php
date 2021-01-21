<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>





<div class="bc_header">
	<form id="items_formupdate">
		<label for="a1">업체명</label>
		<input type="text" name="a1" id="a1" value="<?php echo $str['a1']?>">

		<label for="a2">주소</label>
		<input type="text" name="a2" id="a2" value="<?php echo $str['a2']?>">
		
		<label for="a3">당담자</label>
		<input type="text" name="a3" id="a3" value="<?php echo $str['a3']?>">

		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>

	<span class="btn btn_right add_biz">업체추가</span>
</div>



<div class="bc_cont">
	<div class="cont_header">거래처리스트</div>
	<div class="cont_body">

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>순번</th>
						<th>업체명</th>
						<th>주소</th>
						<th>연락처</th>
						<th>담당자</th>
						<th>주거래품목</th>
						<th>비고</th>
						<th>사용유무</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($bizList as $i=>$row){

				?>

					<tr>
						<td class="cen"><?php echo $row->IDX;?></td>
						<td class="cen"><span class="mod_biz  link_s1" data-idx="<?php echo $row->IDX;?>"><?php echo $row->CUST_NM; ?></span></td>
						<td><?php echo $row->ADDRESS;?></td>
						<td class="cen"><?php echo $row->TEL;?></td>
						<td><?php echo $row->CUST_NAME;?></td>
						<td><?php echo $row->ITEM;?></td>
						<td class="cen"><?php echo $row->REMARK;?></td>
						<td><?php echo $row->CUST_USE;?></td>
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
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>


<script>
$(".add_biz").on("click",function(){
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('biz/ajax_bizReg_form')?>",
			type     : "POST",
			dataType : "HTML",
			data     : {mode:"add"},
			success  : function(data){
				$(".ajaxContent").html(data);
			},
			error    : function(xhr,textStatus,errorThrown){
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});

});


$(".mod_biz").on("click",function(){
		
	var idx = $(this).data("idx");

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('biz/ajax_bizReg_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"mod",IDX:idx},
		success  : function(data){
			$(".ajaxContent").html(data);
		},
		error    : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});

});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});
</script>