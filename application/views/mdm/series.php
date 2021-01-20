<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="body_cont_float1">
	<ul>
		<li>
			<div id="items_formupdate" class="bc_search gsflexst">
				<form>
					<label for="v1">시리즈</label>
					<select name="v1">
							<option value="">전체</option>
						<?php
						foreach($SERIES as $row){
							$selected = (!empty($str['v1']) && $row->IDX == $str['v1'])?"selected":"";
						?>
							<option value="<?php echo $row->IDX;?>" <?php echo $selected;?>><?php echo $row->SERIES_NM;?></option>
						<?php
						}
						?>
					</select>

					<label for="v2">사용여부</label>
					<select name="v2">
						<option value="">전체</option>		
						<option value="Y" <?php echo ($str['v2'] == "Y")?"selected":"";?>>사용</option>
						<option value="N" <?php echo ($str['v2'] == "N")?"selected":"";?>>미사용</option>		
					</select>	
					<span style="display: inline-block;width: 10px;"></span>
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>

					<span class="btni btn_right add_head" style="max-height:34px;"><span class="material-icons">add</span></span>
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
					$pageNum=0;
					foreach($series_headList as $i=>$row){
						$no = $pageNum+$i+1;
					?>

						<tr <?php echo ($H_IDX == $row->IDX)?"class='over'":"";?>>
							<td class="cen"><?php echo $no; ?></td>
							<td><a href="<?php echo base_url('MDM/series/'.$row->IDX.$qstr); //series idx?>" class="link_s1"><?php echo $row->SERIES; ?></a></td>
							<td data-snm="<?php echo $row->SERIES_NM;?>"><?php echo $row->SERIES_NM;?></td>
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
			<div id="items_formupdate" class="bc_search gsflexst" style="min-height:76px;">
		<?php if($de_show_chk){?>
				<div class="gsflexst">
				<p style="font-size:20px; line-height:36px; padding:0 10px;">
					<?php if($series_detailList){
						  echo $series_detailList[0]->SERIES_NM; 
					 }else{
						 echo $series_headList[$detailpos-1]->SERIES_NM ;
					 }?>
				</p>

					<form>
						<label for="colorcode">색상코드</label>
							<input type="text" name="ccd" size="12" value="<?php echo $str['ccd']?>">
						<label for="colorname">색상명</label>
							<input type="text" name="cnm" size="12" value="<?php echo $str['cnm']?>">
						<label for="duse">사용여부</label>
							<select name="dv2">
								<option value="">전체</option>		
								<option value="Y" <?php echo ($str['dv2'] == "Y")?"selected":"";?>>사용</option>
								<option value="N" <?php echo ($str['dv2'] == "N")?"selected":"";?>>미사용</option>		
							</select>	
						<button class="search_submit"><i class="material-icons">search</i></button>
					</form>
				</div>
				
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
			if($de_show_chk){
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
			}else{ 
			?>
					<tr>
						<td colspan="5" class="list_none">헤드 시리지를 선택해주세요.</td>
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

//헤드 수정
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
//디테일 수정
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