
<div id="pageTitle">
	<h1><?php echo $title;?></h1>
</div>
	
<div class="bdcont">
	
	<div class="bc__box">

		<header>
			<span class="btn add add_head"><i class="material-icons">add</i>추가</span>
			<span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span>
		</header> 

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
						<td><a href="<?php echo base_url('mdm/index/'.$row->IDX);?>" class="link_s1"><?php echo $row->NAME; ?></a></td>
						<td><?php echo $row->REMARK;?></td>
						<td><button type="button" class="mod mod_head" data-idx="<?php echo $row->IDX;?>">수정</button></td>
					</tr>

				<?php
				}
				?>
				</tbody>
			</table>
		</div>

	</div>
		

</div>

<div class="bdcont">
	<div class="bc__box">
		<header>
		<?php if($de_show_chk){ //hid값이 없는경우는 노출안됨 ?>
			<span class="btn add add_detail" data-hidx="<?php echo $H_IDX;?>"><i class="material-icons">add</i>추가</span>
			<a href="<?php echo base_url('main');?>" class="btn">전체</a>
		<?php } ?>
			<span class="btn print print_detail"><i class="material-icons">get_app</i>출력하기</span>
		</header>
		
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
						<td><button type="button" class="mod mod_detail" data-idx="<?php echo $row->IDX;?>">수정</button></td>
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
	
	<div class="info_content" style="height:unset;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>


<script>


	$(".add_head").on("click",function(){
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdHead_form')?>",
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
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdDetail_form')?>",
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

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdHead_form')?>",
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

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top : "50%"
		},500);

		$.ajax({
			url      : "<?php echo base_url('mdm/ajax_cocdDetail_form')?>",
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
		location.href = "<?php echo base_url('mdm/excelDown')?>"+H_IDX;
	});


	$(document).on("click","h2 > span.close",function(){

		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top","-50%");
		
	});

</script>