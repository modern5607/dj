<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>



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
							<th>순번</th>
							<th>품명</th>
							<th>품목Code</th>
                            <!-- <th>비고</th> -->
                            <th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($headList as $i=>$row){
					?>

						<tr>
							<td><?php echo $row->IDX; ?></td>
							<td><a href="<?php echo base_url('item/index/'.$row->IDX);?>" class="link_s1"><?php echo $row->ITEM_NAME; ?></a></td>
							<td><?php echo $row->ITEM_CODE; ?></td>
							<!--  <td><?php echo $row->REMARK;?></td> -->
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
				<a href="<?php echo base_url('item');?>" class="btn">전체</a>
			<?php } ?>
				<span class="btn print print_detail"><i class="material-icons">get_app</i>출력하기</span>
			</header>
			
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>head-code</th>
							<th>code</th>
							<th>품목명</th>
							<th>품목속성</th>
							<th>규격</th>
							<th>단위</th>
							<th>용도</th>
							<th>품목구분</th>
							<th>단가</th>
							<th>조달구분</th>
							<th>주거래처</th>
							<th>사용유무</th>
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
							<td><?php echo $row->D_ITEM_CODE; ?></td>
							<td><?php echo $row->D_ITEM_NAME; ?></td>
							<td><?php echo $row->ITEM_ATT ?></td>
							<td><?php echo $row->PRODUCT ?></td>
							<td><?php echo $row->UNIT ?></td>
							<td><?php echo $row->ITEM_USE ?></td>
							<td><?php echo $row->ITEM_GB ?></td>
							<td><?php echo $row->PRICE ?></td>
							<td><?php echo $row->JD_GB ?></td>
							<td><?php echo $row->CUSTOMER ?></td>
							<td><?php echo ($row->USE_YN == 'Y') ? '사용' : '사용안함' ?></td>
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
	
	<div class="info_content">
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
			url      : "<?php echo base_url('/item/ajax_itemHead_form')?>",
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
			url      : "<?php echo base_url('/item/ajax_itemDetail_form')?>",
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
			url      : "<?php echo base_url('/item/ajax_itemHead_form')?>",
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
			url      : "<?php echo base_url('/item/ajax_itemDetail_form')?>",
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

	$(document).on("click","h2 > span.close",function(){

		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top","-50%");
		
	});

</script>