<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
	<ul>
		<li>
			
			<div id="" class="bc_search">
			<form>
				<input type='hidden' name='n' value='1'/>
				<label for="sdate">수주일</label>
				<input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:"";?>" size="10" /> ~ 
				
				<input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:"";?>" size="10" />
				<span class="nbsp"></span>
				<label for="custnm">거래처</label>
				<input type="text" name="custnm" class="custnm" value="<?php echo (!empty($str['custnm']) && $str['custnm'] != "")?$str['custnm']:"";?>" size="12" />
				<label for="actnm">수주명</label>
				<input type="text" name="actnm" class="actnm" value="<?php echo (!empty($str['actnm']) && $str['actnm'] != "")?$str['actnm']:"";?>" size="12" />
				<button class="search_submit"><i class="material-icons">search</i></button>
			</form>
			</div>
			
			<div class="bc_header none_padding">
				<span class="btni btn_right add_head"><span class="material-icons">add</span></span>				
			</div>

			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>수주명</th>
							<th>거래처</th>
							<th>수주일</th>
							<th>수정</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($contList as $i=>$row){
						$no = $i+1;
					?>

						<tr>
							<td class="cen"><?php echo $no; ?></td>
							<td><a href="<?php echo base_url('PLN/index/').$row->IDX.$qstr; ?>" class="link_s1"><?php echo $row->ACT_NAME; ?></a></td>
							<td><?php echo $row->CUST_NM;?></td>
							<td class="cen"><?php echo substr($row->ACT_DATE,0,10);?></td>
							<td class="cen"><span class="btn mod_head" data-idx="<?php echo $row->IDX;?>">수정</span></td>
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
				
				<?php if(!empty($headInfo)){ //hid값이 없는경우는 노출안됨 ?>
					
					<div class="ac_table">
						<table>
							<tr>
								<th>수주일자</th>
								<td><?php echo substr($headInfo->ACT_DATE,0,10); ?></td>
								<th>거래처</th>
								<td><?php echo $headInfo->CUST_NM; ?></td>
							</tr>
							<tr>
								<th>수주명</th>
								<td colspan="3"><?php echo $headInfo->ACT_NAME; ?></td>
							</tr>
							<tr>
								<th>납품일자</th>
								<td><b><?php echo substr($headInfo->DEL_DATE,0,10); ?></b></td>
								<th>납품현황</th>
								<td><?php echo ($headInfo->END_YN == "Y")?"납품완료":"준비중"; ?></td>
							</tr>
							<tr>
								<th>세부사항</th>
								<td colspan="3"><?php echo $headInfo->REMARK; ?></td>
							</tr>
							<tr>
								<th>특이사항</th>
								<td colspan="3"><?php echo $headInfo->ORD_TEXT; ?></td>
							</tr>
						</table>

					</div>

					<!--a href="<?php echo base_url('PLN/index');?>" class="alink" style="float:left;">전체코드보기</a-->
					<span class="btni btn_right add_detail" data-hidx="<?php echo $HIDX; // idx?>"><span class="material-icons">add</span></span>
					
				<?php } ?>
			</div>
			<div class="tbl-content">
			<?php if(!empty($detailList) || $HIDX){ ?>
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>상품명</th>
							<th>색상</th>
							<th>수량</th>
							<th>비고</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($detailList as $i=>$row){
						$num = $i+1;
					?>

						<tr>
							<td class="cen"><?php echo $num; ?></td>
							<td><?php echo $row->ITEM_NM; ?></td>
							<td><?php echo $row->COLOR; ?></td>
							<td class="cen"><?php echo $row->QTY;?></td>
							<td><?php echo $row->REMARK;?></td>
							<td><span class="btn mod_detail" data-idx="<?php echo $row->IDX; //detail idx?>">수정</span></td>
						</tr>

					<?php
					}
					if(empty($detailList)){
					?>
						<tr><td colspan="6" style='color:#999; padding:40px 0;'>수주항목이 없습니다.</td></tr>
					<?php } ?>
					</tbody>
				</table>
			<?php }else{ ?>
				<div style="border:1px solid #ddd; padding:100px 0; text-align:center;">수주정보를 선택하세요</div>
			<?php } ?>
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
		url      : "<?php echo base_url('PLN/ajax_plnHead_form')?>",
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
		url      : "<?php echo base_url('PLN/ajax_plnDetail_form')?>",
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
		url      : "<?php echo base_url('PLN/ajax_plnHead_form')?>",
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
		url      : "<?php echo base_url('PLN/ajax_seriesDetail_form')?>",
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


function formcheck(f){
	//location.href = "<?php echo base_url('PLN/index').$qstr?>";
	//return false;
}



/*
$(".print_detail").on("click",function(){
	//var HIDX = "<?php echo $H_IDX?>";
	//var H_IDX = (HIDX != "")?"/"+HIDX:"";
	//location.href = "<?php echo base_url('mdm/excelDown')?>"+H_IDX;
});
*/

$("input[name='sdate'],input[name='edate']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


$(document).on("click","h2 > span.close",function(){

	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
	
});
//-->
</script>