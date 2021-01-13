<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<h2>
	생산LINE
	<span class="material-icons close">clear</span>
	<span class="btn printxx">변경</span>
</h2>

<div class="formContainer">
	<div class="bdcont_100">
		<div class="bc__box100">
			
			<div class="tbl-content">
				
				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #ddd; border-bottom:1px solid #ddd; font-size:12px; letter-spacing:-.9px;">
					
					<tbody>
						
						<tr>
							<th>생산LINE</th>
							<td>
								<input type="hidden" name="IDX" value="<?php echo $AIDX;?>">
								<select name="M_LINE" style="padding:3px 10px; border:1px solid #ddd;">
									<option value="<?php echo $mline->M_LINE."_".$mline->P_T;?>"><?php echo $mline->M_LINE?></option>
									<?php if(!empty($mline->M_LINE2)){ ?>
									<option value="<?php echo $mline->M_LINE2."_".$mline->P_T2;?>"><?php echo $mline->M_LINE2?></option>
									<?php } ?>
									<?php if(!empty($mline->M_LINE3)){ ?>
									<option value="<?php echo $mline->M_LINE3."_".$mline->P_T3;?>"><?php echo $mline->M_LINE3?></option>
									<?php } ?>
								</select>
							</td>
						</tr>

					</tbody>
					
				</table>

				
			</div>

		</div>
	</div>
</div>
