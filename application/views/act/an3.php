<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
	<form id="items_formupdate">

		
				
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	<span class="btni btn_right add_xprint"><span class="material-icons">add</span></span>
</div>


<div class="bc_cont">
	<div class="cont_header"><?php echo $title;?></div>
	<div class="cont_body">
		<div class="tbl-content">
			
		</div>
		
		<!--div class="pagination">
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
		</div-->

	</div>
</div>


<div id="pop_container">
	
	<div id="info_content" class="info_content">
		
		<div class="ajaxContent" style="height:100%; overflow:hidden"></div>
		
	</div>

</div>



<script type="text/javascript">
<!--

$("select[name='recycle']").on("change",function(){
	var vx = $(this).val();
	var nn = $(this).data("idx");
	if(vx == "Y"){
		if(confirm('2급수량을 재고로 변경 하시겠습니까?') !== false){
			$.post("<?php echo base_url('ACT/ajax_an3_listupdate')?>",{idx:nn,vx:vx},function(data){
				if(data > 0){
					location.reload();
				}
			});
		}
	}
});



$(".add_xprint").on("click",function(){

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
	
	var stDate = $("input[name='st_date']").val();
	var url = "<?php echo base_url('ACT/print_actpln')?>";
	

	$.ajax({
		url:url,
		type : "post",
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
			//document.getElementById("info_content").print();
		}
		
	});


});


$(document).on("click","h2 > span.close",function(){

	//$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});

//-->
</script>