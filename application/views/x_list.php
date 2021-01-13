<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<div style="float:left;">
				<form id="items_formupdate">
					
					<label for="date">수신일</label>
					<input type="text" class="calendar" name="actdate" id="actdate" value="<?php echo ($str['actdate']!="")?$str['actdate']:date("Y-m-d",time())?>" />
					<button class="search_submit"><i class="material-icons">search</i></button>
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
						<th>LOT NO</th>
						<th>BL NO</th>
						<th>품명</th>
						<th>생산라인</th>
						<th>MSAB</th>
						<th>구분</th>
						<th>수신일</th>
						<th>비고</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($xList as $i=>$row){
					$num = $pageNum+$i+1;
				?>

					<tr>
						<td class="cen"><?php echo $num;?></td>
						<td><strong><?php echo $row->LOT_NO; ?></strong></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td><?php echo $row->ITEM_NAME; ?></td>
						<td class="cen"><?php echo $row->M_LINE; ?></td>
						<td class="cen"><?php echo substr($row->ACT_DATE,0,10); ?></td>
						<td><?php echo $row->ACT_REMARK; ?></td>
					</tr>

				<?php
				}
				if(empty($xList)){
				?>

					<tr>
						<td colspan="12" class="list_none">제품정보가 없습니다.</td>
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
$(".limitset select").on("change",function(){
	var qstr = "<?php echo $qstr ?>";	
	location.href="<?php echo base_url('bom/stocklist/')?>"+qstr+"&perpage="+$(this).val();
	
});

$("#actdate").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});
</script>