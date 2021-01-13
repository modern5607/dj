<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<h2>
	프린트
	<span class="material-icons close">clear</span>
	<span class="btn printxx">print</span>
</h2>

<div class="formContainer" style="height:100%; overflow-y:auto;">
	<div class="bdcont_100">
		<div class="bc__box100">
			
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-bottom:1px solid #ddd; margin-bottom:10px; background:#fff;">
					<thead>
					<tr>
						<td style="line-height:100px; text-align:center; font-size:25px; font-weight:600; letter-spacing:-1px; position:relative;" rowspan="2">
							<?php echo date("Y",time())?>년 <?php echo date("m",time())?>월 <?php echo date("d",time())?>일 작업지시서
							<span style="position:absolute; bottom:5px; right:10px; line-height:14px; font-size:12px; color:#666;">작업지시일 : <?php echo date("Y-m-d",time());?></span>
						</td>
						<th rowspan="2" width="60">결제</th>
						<th width="108" height="40">작성</th>
						<th width="108">승인</th>
					</tr>
					<tr>
						<th></th>
						<th></th>
					</tr>
					</thead>
				</table>



				<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>품명</th>
						<th>색상</th>
						<th>수주수량</th>
						<th>시유수량</th>
						<th>완료수량</th>
						<th>1급</th>
						<th>2급</th>
						<th>파손</th>
						<th>시유</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($List)){
				foreach($List as $i=>$row){ 
					$no = $i+1; 
					if($row->ITEM_NM == "합계"){
				?>
				<tr style="background:#f3f8fd;">
					<td colspan="2"><strong><?php echo $row->ITEM_NM; ?> - <?php echo $row->COLOR; ?></strong></td>
					<td class="right"><?php echo number_format($row->QTY); ?></td>
					<td class="right"><strong><?php echo number_format($row->IN_QTY); ?></strong></td>
					<td class="right"><?php echo number_format($row->QTY1); ?></td>
					<td class="right"><?php echo number_format($row->QTY1); ?></td>
					<td class="right"><?php echo number_format($row->QTY2); ?></td>
					<td class="right"><?php echo number_format($row->QTY3); ?></td>
					<td class="right"><?php echo number_format($row->QTY4); ?></td>
				</tr>
				<?php
					}else{
				?>
				<tr>
					<td><?php echo $row->ITEM_NM; ?></td>
					<td><?php echo $row->COLOR; ?></td>
					<td class="right"><?php echo number_format($row->QTY); ?></td>
					<td class="right"><?php echo number_format($row->IN_QTY); ?></td>
					<td class="right"><?php echo number_format($row->QTY1); ?></td>
					<td class="right"><?php echo number_format($row->QTY1); ?></td>
					<td class="right"><?php echo number_format($row->QTY2); ?></td>
					<td class="right"><?php echo number_format($row->QTY3); ?></td>
					<td class="right"><?php echo number_format($row->QTY4); ?></td>
					
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

			<div style="margin-bottom:30px;">
			<?php
			foreach($IMAGE as $img){
			?>
				<div>
					<h2><?php echo $img->ITEM_NAME;?></h2>
					<?php
					
						if($img->IMG_LINK1 != ""){
							$imgurl = $img->IMG_LINK1;
							echo "<img src='".base_url('uploads/').$img->IMG_LINK1."' width='150'>";
						}
						if($img->IMG_LINK3 != ""){
							$imgurl = $img->IMG_LINK2;
							echo "<img src='".base_url('uploads/').$img->IMG_LINK2."' width='150'>";
						}
						if($img->IMG_LINK3 != ""){
							$imgurl = $img->IMG_LINK3;
							echo "<img src='".base_url('uploads/').$img->IMG_LINK3."' width='150'>";
						}
						
						//echo "<img src='".base_url('uploads/').$img->$img->IMG_LINK.$i."'>";
					
					?>
				</div>

			<?php } ?>
			</div>

		</div>
	</div>
</div>