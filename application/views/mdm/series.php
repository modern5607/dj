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
							<th>No</th>
							<th>시리즈</th>
							<th>시리즈명</th>
							<th>사용여부</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($series_headList as $i=>$row){
						$no = $i+1;
					?>

						<tr>
							<td class="cen"><?php echo $no; ?></td>
							<td><a href="<?php echo base_url('MDM/series/'.$row->IDX); //series idx?>" class="link_s1"><?php echo $row->SERIES; ?></a></td>
							<td><?php echo $row->SERIES_NM;?></td>
							<td class="cen"><?php echo ($row->USE_YN == "Y")?"사용":"미사용";?></td>
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
					<a href="<?php echo base_url('MDM/series/');?>" class="alink" style="float:left;">전체코드보기</a>
					<span class="btni btn_right add_detail" data-hidx="<?php echo $H_IDX; //series idx?>"><span class="material-icons">add</span></span>
					
				<?php } ?>
			</div>
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>색상코드</th>
							<th>색상명</th>
							<th>사용여부</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($series_detailList as $i=>$row){
						$num = $i+1;
					?>

						<tr>
							<td class="cen"><?php echo $num; ?></td>
							<td><?php echo $row->COLOR_CD; ?></td>
							<td><?php echo $row->COLOR; ?></td>
							<td class="cen"><?php echo ($row->USE_YN == "Y")?"사용":"미사용";?></td>
							<td><span class="btn mod_detail" data-idx="<?php echo $row->IDX; //detail idx?>">수정</span></td>
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
		url      : "<?php echo base_url('MDM/ajax_seriesHead_form')?>",
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
		url      : "<?php echo base_url('MDM/ajax_seriesDetail_form')?>",
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
		url      : "<?php echo base_url('MDM/ajax_seriesHead_form')?>",
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
		url      : "<?php echo base_url('MDM/ajax_seriesDetail_form')?>",
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

/*
$(".print_detail").on("click",function(){
	//var HIDX = "<?php echo $H_IDX?>";
	//var H_IDX = (HIDX != "")?"/"+HIDX:"";
	//location.href = "<?php echo base_url('mdm/excelDown')?>"+H_IDX;
});
*/

$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});
//-->
</script>