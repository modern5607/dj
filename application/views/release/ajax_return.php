<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<h2>
	<?php echo $title?>
	<span class="material-icons close">clear</span>
</h2>
<div class="formContainer">
	<div class="bdcont_100">
		<div class="bc__box100">
			
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>no</th>
							<th>BL NO</th>
							<th>출고일</th>
							<th>출고수량</th>
							<th>반품수량</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						
					<?php
					
					foreach($list as $i=>$row){
						$num = $i+1;
						$total_rcount = $row->OUT_QTY - $row->RE_QTY;
						
						$btnText = "반품";
						$btnCss = "yn btn_return";

						if($total_rcount < 1){
							$btnCss = "xn";
						}

					?>

						<tr>
							<td class="cen"><?php echo $num;?></td>
							<td><?php echo $row->BL_NO; ?></td>
							<td class="cen"><?php echo substr($row->TRANS_DATE,0,10); ?></td>
							<td class="right"><?php echo number_format($row->OUT_QTY); ?></td>
							<td class="right">
								<input type="hidden" name="out" id="out" value="<?php echo $row->OUT_QTY; ?>" />
								<input type="text" name="qty" id="qty" size="5" class="row_input" value="<?php echo $total_rcount; ?>" />
							</td>
							<td class="cen"><span class="mod <?php echo $btnCss; ?>" data-idx="<?php echo $row->IDX;?>"><?php echo $btnText; ?></span></td>
						</tr>

					<?php
					}
					if(empty($list)){
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

				
			</div>

		</div>
	</div>
</div>

<script>



</script>