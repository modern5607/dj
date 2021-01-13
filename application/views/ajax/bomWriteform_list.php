<?php
defined('BASEPATH') OR exit('No direct script access allowed');
foreach($materialList as $i=>$row){
?>
	<?php
	if($i==0){
	?>
	<tr class="page_text">
		<td colspan="8" style="text-align:center; color:#666;">			
			--- <?php echo $pageNum?> PAGE ---
		</td>
	</tr>
	<?php
	}	
	?>

	<tr class="addlist">
		<td class="cen"><input type="checkbox" class="setHidx" name="idx[]" <?php echo ($row->CHKBOM > 0)?"checked":"";?> value="<?php echo $row->IDX; ?>"></td>
		<td><?php echo $row->COMPONENT; ?></td>
		<td><?php echo $row->COMPONENT_NM; ?></td>
		<td><?php echo $row->UNIT; ?></td>
		<td><?php echo number_format($row->PRICE); ?></td>
		<td><?php echo $row->REEL_CNT; ?></td>
	</tr>

<?php
}
?>