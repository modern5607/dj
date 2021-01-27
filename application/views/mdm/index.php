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

						<tr <?php echo ($H_IDX == $row->IDX)?"class='over'":"";?>>
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

		</li>
		<li>
			<div class="bc_header none_padding">
				<?php if($de_show_chk){ //hid값이 없는경우는 노출안됨 ?>
					<!-- <a href="<?php echo base_url('MDM');?>" class="alink" style="float:left;">전체코드보기</a> -->
					<div style="display:flex;flex-direction: row-reverse;align-items: flex-end;">
					<span class="btni btn_right add_detail" data-hidx="<?php echo $H_IDX;?>"><span class="material-icons">add</span></span>
					<span class=""><p style="font-size: 20px; padding-right:20px; color:#999"><?php echo $headList[$H_IDX-1]->NAME ?></p></span>
					</div>
					
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
					if(!empty($detailList)){
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
					}}else{
					?>
					<tr>
						<td colspan="15" class="list_none">공통코드가 없습니다.</td>
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