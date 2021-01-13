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
					
					<label for="gjgb">공정구분</label>
					<?php
					if(!empty($GJ_GB)){
					?>
						<select name="gjgb" style="padding:4px 10px; border:1px solid #ddd;">
							<option value="">ALL</option>
						<?php
						foreach($GJ_GB as $row){
							$selected8 = ($str['gjgb'] == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected8;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}
					?>
					
					<label for="component">자재코드</label>
					<input type="text" name="component" id="component" value="<?php echo $str['component']?>" size="15" />

					<label for="comp_name">자재명</label>
					<input type="text" name="comp_name" id="comp_name" value="<?php echo $str['comp_name']?>" size="15" />

					<button class="search_submit">검색</button>
				</form>
			</div>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span--> 
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>자재코드</th>
						<th>자재명</th>
						<th>재고량</th>
						<th>재고입고일</th>
						<th>안전재고수량</th>
						<th>안전재고등록일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($componentList as $i=>$row){
					$num = $pageNum+$i+1;
					//$pTime = ($row->PT*$row->QTY)/60;
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->COMPONENT; ?></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td class="cen"><?php echo number_format($row->STOCK); ?></td>
						<td class="cen"><?php echo substr($row->INTO_DATE,0,10); ?></td>
						<td class="cen"><input type="text" name="SAVE_QTY" class="row_input saveQty" data-idx="<?php echo $row->IDX;?>" size="10" value="<?php echo number_format($row->SAVE_QTY); ?>"/></td>
						<td class="cen"><?php echo substr($row->SAVE_DATE,0,10); ?></td>
					</tr>

				<?php
				}
				if(empty($componentList)){
				?>

					<tr>
						<td colspan="15" class="list_none">제품정보가 없습니다.</td>
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

	</div>
</div>

<script>
$(".saveQty").on("change",function(){
	var sqty = $(this).val();
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('mat/ajax_saveqty_update')?>",{sqty:sqty,idx:idx},function(data){
		if(data > 0){
			alert("안전재고수량이 변경되었습니다");
			location.reload();
		}
	});
});


$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";
	location.href="<?php echo base_url('mat/m1/')?>"+qstr+"&perpage="+$(this).val();
	
});
</script>