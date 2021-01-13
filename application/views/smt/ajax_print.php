<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<h2>
	프린트
	<span class="material-icons close">clear</span>
	<span class="btn printxx">print</span>
</h2>

<div class="formContainer">
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
				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #ddd; border-bottom:1px solid #ddd; font-size:12px; letter-spacing:-.9px;">
					<thead>
						<tr>
							<td colspan="6"></td>
							<th colspan="2">생산라인</th>
							<th colspan="2"><?php echo $M_TITLE?></th>
						</tr>
						<tr>
							<th>no</th>
							<th>고객사</th>
							<th>LOT NO</th>
							<th>BL NO</th>
							<th>MS</th>
							<th>작업일자</th>
							<!--th>공정코드</th>
							<th>공정명</th-->
							<th>수량</th>
							<th>P.T</th>
							<th>비고</th>
						</tr>
					</thead>
					<tbody>
						
					<?php
					$totalQty = $totalPt = 0;
					foreach($actList as $i=>$row){
						$num = $i+1;
					?>

						<tr>
							<td class="cen"><?php echo $num;?></td>
							<td><?php echo $row->CUSTOMER; ?></td>
							<td><?php echo $row->LOT_NO; ?></td>
							<td><?php echo $row->BL_NO; ?></td>
							<td><?php echo $row->MSAB; ?></td>
							<td class="cen"><?php echo substr($row->ST_DATE,0,10); ?></td>
							<!--td><?php echo $row->GJ_CODE; ?></td>
							<td><?php echo $row->NAME; ?></td-->
							<td class="right"><?php echo number_format($row->QTY); ?></td>
							<td class="right"><?php echo number_format($row->PT); ?></td>
							<td></td>
						</tr>

					<?php
						$totalQty += $row->QTY;
						$totalPt  += $row->PT; 
					}
					if(empty($actList)){
					?>

						<tr>
							<td colspan="12" class="list_none">제품정보가 없습니다.</td>
						</tr>

					<?php
					}	
					?>
					</tbody>
					<tfoot style="width:100%;">
						<tr>
							<td colspan="9">&nbsp;</td>
						</tr>
					</tfoot>
				</table>

				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #ddd; margin-top:10px;">
					<tbody>
						<tr>
							<td rowspan="3"></td>
							<td style="width:80px;">Total Qty</td>
							<td style="width:80px;">Total P.T</td>
						</tr>
						<tr>
							<td class="right"><?php echo $totalQty;?></td>
							<td class="right"><?php echo $totalPt;?></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>