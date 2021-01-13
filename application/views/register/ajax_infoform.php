<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<h2>
	회원정보
	<span class="material-icons close">clear</span>
</h2>

<form name="memberform" id="memberform" method="post" onsubmit="return memberformChk(this)" action="<?php echo base_url('register/member_formUpdate_info');?>">
<input type="hidden" name="mod" value="<?php echo isset($memInfo)?1:0;?>">
<input type="hidden" name="IDX" value="<?php echo isset($memInfo)?$memInfo->IDX:"";?>">


<div class="formContainer">
	<div class="bdcont_100">
		<div class="bc__box100">


			<div class="tbl-content">
				<h3>사원정보</h3>
				<table class="none_border" cellpadding="0" cellspacing="0" border="0" width="100%">
					<tbody>
						<tr>
							<th>사원번호</th>
							<td>
								<input type="text" name="NO" id="NO" value="<?php echo isset($memInfo)?$memInfo->NO:"";?>" class="form_input">
							</td>
							<th>입사일</th>
							<td>
								<input type="text" name="FIRSTDAY" id="FIRSTDAY" value="<?php echo isset($memInfo)?$memInfo->FIRSTDAY:"";?>" class="form_input">
							</td>
						</tr>
						<tr>
							<th>부서</th>
							<td>
								<input type="text" name="PART" id="PART" value="<?php echo isset($memInfo)?$memInfo->PART:"";?>" class="form_input">
							</td>
							<th>직급</th>
							<td>
								<input type="text" name="GRADE" id="GRADE" value="<?php echo isset($memInfo)?$memInfo->GRADE:"";?>" class="form_input">
							</td>
						</tr>
						<tr>
							<th>취업형태</th>
							<td colspan="3">
								<input type="text" name="WORKKIND" id="WORKKIND" value="<?php echo isset($memInfo)?$memInfo->WORKKIND:"";?>" class="form_input">
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>

			<div class="tbl-content">
				<h3>수령계좌</h3>
				<table class="none_border" cellpadding="0" cellspacing="0" border="0" width="100%">
					<tbody>
						<tr>
							<th>은행명</th>
							<td>
								<input type="text" name="NO" id="NO" value="<?php echo isset($memInfo)?$memInfo->NO:"";?>" class="form_input">
							</td>
							<th>계좌번호</th>
							<td>
								<input type="text" name="FIRSTDAY" id="FIRSTDAY" value="<?php echo isset($memInfo)?$memInfo->FIRSTDAY:"";?>" class="form_input">
							</td>
						</tr>
						<tr>
							<th>예금주</th>
							<td colspan="3">
								<input type="text" name="WORKKIND" id="WORKKIND" value="<?php echo isset($memInfo)?$memInfo->WORKKIND:"";?>" class="form_input">
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>


		</div>
	</div>
</div>

<div style="margin:20px 0; text-align:center;">
	<input type="submit" class="mod blue_btn" value="<?php echo isset($memInfo)?"회원수정":"회원등록";?>">
</div>

</form>

