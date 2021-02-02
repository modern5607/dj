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
					$count=0;
					$incount=0;
					$count1=0;
					$count2=0;
					$countx=0;
					$glazing=0;
				foreach($List as $i=>$row){ 
					$no = $pageNum+$i+1;
					if($row->ITEM_NM == "합계"){
						$count += $row->QTY;
						$incount += $row->IN_QTY;
						$count1 += $row->QTY1;
						$count2 += $row->QTY2;
						$countx += $row->QTY3;
						$glazing += $row->QTY4;
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
				?>
				<tr style="background:#f3f8fd;" class="nhover">
					<td colspan="2"><strong>총 합계</strong></td>
					<td class="right"><?php echo number_format($count); ?></td>
					<td class="right"><?php echo number_format($incount); ?></td>
					<td class="right"><strong><?php echo number_format($count1); ?></strong></td>
					<td class="right"><?php echo number_format($count1); ?></td>
					<td class="right"><?php echo number_format($count2); ?></td>
					<td class="right"><?php echo number_format($countx); ?></td>
					<td class="right"><?php echo number_format($glazing); ?></td>
				</tr>
				<?php
				}else{
				
				?>

					<tr>
						<td colspan="15" class="list_none" style="text-align: center;">실적정보가 없습니다.</td>
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