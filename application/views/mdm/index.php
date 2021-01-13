<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="body_cont_float1">
	<ul>
		<li>
			
			<div class="bc_header none_padding">
				<span class="btni btn_right add_head"><span class="material-icons">add</span></span>				
			</div>

			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>code</th>
							<th>name</th>
							<th>비고</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($headList as $i=>$row){
					?>

						<tr>
							<td><?php echo $row->CODE; ?></td>
							<td><a href="<?php echo base_url('MDM/index/'.$row->IDX);?>" class="link_s1"><?php echo $row->NAME; ?></a></td>
							<td><?php echo $row->REMARK;?></td>
							<td><span class="btn mod_head" data-idx="<?php echo $row->IDX;?>">수정</span></td>
						</tr>

					<?php
					}
					?>
					</tbody>
				</table>
			</div>

		</li>
		<li>
			<div class="bc_header none_padding">
				<?php if($de_show_chk){ //hid값이 없는경우는 노출안됨 ?>
					<a href="<?php echo base_url('MDM');?>" class="alink" style="float:left;">전체코드보기</a>
					<span class="btni btn_right add_detail" data-hidx="<?php echo $H_IDX;?>"><span class="material-icons">add</span></span>
					
				<?php } ?>
			</div>
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>head-code</th>
							<th>code</th>
							<th>name</th>
							<th>비고</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($detailList as $i=>$row){
					?>

						<tr>
							<td><?php echo $row->H_CODE; ?></td>
							<td><?php echo $row->CODE; ?></td>
							<td><?php echo $row->NAME; ?></td>
							<td><?php echo $row->REMARK; ?></td>
							<td><span class="btn mod_detail" data-idx="<?php echo $row->IDX;?>">수정</span></td>
						</tr>

					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</li>
	</ul>
</div>

<div id="pop_container">
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>



<script type="text/javascript">
<!--
$(".add_head").on("click",function(){

	$(".ajaxContent").html('');

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('MDM/ajax_cocdHead_form')?>",
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
	})

});


$(".add_detail").on("click",function(){
	
	var hidx = $(this).data("hidx");

	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('MDM/ajax_cocdDetail_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"add",hidx:hidx},
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


$(".mod_head").on("click",function(){
	
	var idx = $(this).data("idx");
	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('MDM/ajax_cocdHead_form')?>",
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

$(".mod_detail").on("click",function(){
	
	var idx = $(this).data("idx");
	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url      : "<?php echo base_url('MDM/ajax_cocdDetail_form')?>",
		type     : "POST",
		dataType : "HTML",
		data     : {mode:"mod",idx:idx},
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


$(".print_detail").on("click",function(){
	var HIDX = "<?php echo $H_IDX?>";
	var H_IDX = (HIDX != "")?"/"+HIDX:"";
	location.href = "<?php echo base_url('MDM/excelDown')?>"+H_IDX;
});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});
//-->
</script>