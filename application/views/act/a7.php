<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<style>
.even{background:#fff !important;}
.odd{background:#eee !important;}
</style>

<div class="bc_header">
	<form id="items_formupdate">

		<label for="sdate">수주일자</label>
		<input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:"";?>" size="10" /> ~ 
		
		<input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:"";?>" size="10" />
				
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
</div>


<div class="bc_cont">
	<div class="cont_header"><?php echo $title;?></div>
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>수주일자</th>
						<th>품명</th>
						<th>색상구분</th>
						<th>수주수량</th>
						<th>지시수량</th>
						<th>불량수량</th>
						<th>불량코드</th>
						<th>불량내역</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($List)){
				
				
				
				foreach($List as $i=>$row){
					$chkn = 0;
					$no = $i+1;
					//$sumqty = $row->QT2+$row->QT3+$row->QT4;
					$yn = ($row->END_YN <> "Y")?"N":"Y";
					//$mQty = (($row->QT1-$row->QTY) > 0)?$row->QT1-$row->QTY:"0";//잉여재고
					
					//if($i==0){
					if($row->QT2 > 0) $chkn += 1;
					if($row->QT3 > 0) $chkn += 1;
					if($row->QT4 > 0) $chkn += 1;

					$trclass = ( $i % 2 == 0 )?"even":"odd";

					//echo $row->QT2."-".$row->QT3."-".$row->QT4."<br>";
					
				?>
				<tr class="<?php echo $trclass;?>">
					<td class="cen" rowspan="<?php echo $chkn;?>"><?php echo $no;?></td>
					<td class="cen" rowspan="<?php echo $chkn;?>"><?php echo $row->ACT_DATE;?></td>
					<td rowspan="<?php echo $chkn;?>"><strong><?php echo $row->ITEM_NM; ?></strong></td>
					<td rowspan="<?php echo $chkn;?>" class="cen"><?php echo $row->COLOR; ?></td>
					<td rowspan="<?php echo $chkn;?>" class="right"><?php echo number_format($row->QTY); ?></td>
					<td rowspan="<?php echo $chkn;?>" class="right"><?php echo number_format($row->IN_QTY); ?></td>
					<?php
					if($row->QT2 > 0){
					?>
					<td class="cen"><?php echo number_format($row->QT2); ?></td>
					<td class="cen">A-2</td>
					<td class="cen">2급</td>
				</tr>
					<?php
					}elseif($row->QT2 < 1 && $row->QT3 > 0){
						
					?>
					<td class="cen"><?php echo number_format($row->QT3); ?></td>
					<td class="cen">A-3</td>
					<td class="cen">파손</td>
				</tr>				
					<?php
					}elseif($row->QT2 < 1 && $row->QT3 < 1 && $row->QT4 > 0){ 
					?>
					<td class="cen"><?php echo number_format($row->QT4); ?></td>
					<td class="cen">A-4</td>
					<td class="cen">시유</td>
				</tr>
					<?php
					}
					?>

				<?php
					if($row->QT2 > 0 && $row->QT3 > 0 ){
				?>
				<tr class="<?php echo $trclass;?>">
					<td class="cen"><?php echo number_format($row->QT3); ?></td>
					<td class="cen">A-3</td>
					<td class="cen">파손</td>
				</tr>
				<?php
					}
				?>
				<?php
					if($row->QT4 > 0  && $row->QT3 > 0){
				?>
				<tr class="<?php echo $trclass;?>">
					<td class="cen"><?php echo number_format($row->QT4); ?></td>
					<td class="cen">A-4</td>
					<td class="cen">시유</td>
				</tr>
				<?php
					}
				?>

				

				<?php
				}
				}else{
				
				?>

					<tr>
						<td colspan="15" class="list_none">실적정보가 없습니다.</td>
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



<div id="pop_container">
	
	<div id="info_content" class="info_content" style="height:auto;">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>



<script type="text/javascript">
<!--



//-->
</script>