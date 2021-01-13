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
					
										
					<label for="mline">생산LINE</label>
					<select name="mline" id="mline" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="">ALL</option>
						<?php 
						if(!empty($M_LINE)){ 
							foreach($M_LINE as $mline){
								$selected = ($str['mline'] == $mline->D_CODE)?"selected":"";
						?>
						<option value="<?php echo $mline->D_CODE; ?>" <?php echo $selected?>><?php echo $mline->D_NAME; ?></option>
						<?php 
							}
						} 
						?>
					</select>
					
					
					<button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<span class="btn print print_actpln"><i class="material-icons">get_app</i>출력하기</span>
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>LOT NO</th>
						<th>BL NO</th>
						<th>수량</th>
						<th>생산LINE</th>
						<th>공정코드</th>
						<th>공정명</th>
						<th>바코드발행</th>
						<th>바코드</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($actList as $i=>$row){
					$num = $pageNum+$i+1;
					$finishBtn = ($row->FINISH == "Y")?"xn":"yn finish_btn";
					
					$barcodeFile = FCPATH.'_static/barcode/barcode_'.$row->IDX.'.gif';
					
					if(file_exists($barcodeFile)){
						$barcodeImg = "<img src='".base_url('_static/barcode/barcode_'.$row->IDX.'.gif')."'>";
						$bText = "코드재발행";
					}else{
						$barcodeImg = "";
						$bText = "코드발행";
					}
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="cen"><?php echo $row->M_LINE; ?></td>
						<td class="cen"><?php echo $row->GJ_CODE; ?></td>
						<td><?php echo $row->NAME; ?></td>
						<td class="cen"><span class="mod barcode_btn" data-idx="<?php echo $row->IDX;?>"><?php echo $bText;?></td>
						<td class="imgtd">
							<?php echo $barcodeImg;?>
						</td>
					</tr>

				<?php
				}
				if(empty($actList)){
				?>

					<tr>
						<td colspan="9" class="list_none">제품정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>

		<div class="pagination">
			<?php echo $this->data['pagenation'];?>
			<?
			if($this->data['cnt'] > 10){
			?>
			<div class="limitset">
				<select name="per_page">
					<option value="10" <?php echo ($perpage == 10)?"selected":"";?>>10</option>
					<option value="30" <?php echo ($perpage == 30)?"selected":"";?>>30</option>
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



<script>
$(".barcode_btn").on("click",function(){
	var $this = $(this);
	var idx = $this.data("idx");
	
	$.post("<?php echo base_url('smt/creat_barcode')?>",{idx:idx},function(data){
		console.log(data);
		$this.parents("tr").find("td.imgtd").html("<img src='"+data+"'>");
	});
});
</script>