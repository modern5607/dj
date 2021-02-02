<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>


<h2>
	회원정보
	<span class="material-icons close">clear</span>
</h2>

<form name="memberform" id="memberform" method="post" onsubmit="return memberformChk(this)" action="<?php echo base_url('SYS/member_formUpdate');?>">
<input type="hidden" name="mod" value="<?php echo isset($memInfo)?1:0;?>">
<input type="hidden" name="IDX" value="<?php echo isset($memInfo)?$memInfo->IDX:"";?>">


<div class="formContainer" style="padding:10px;">
	

	<div class="tbl-content" style="padding-top:10px">
		<table class="none_border nhover" cellpadding="0" cellspacing="0" border="0" width="100%">
			<tbody>
			<tr><th colspan="4" style="font-size:15px; border-bottom:1px solid red;">개인정보</th></tr>
				<tr>
					<th>회원아이디</th>
					<td colspan="5">
						<input type="text" name="ID" id="ID" value="<?php echo isset($memInfo)?$memInfo->ID:"";?>" <?php echo isset($memInfo)?"readonly":"";?> class="form_input">
						<p class="chk_msg"></p>
					</td>
				</tr>
				<tr>
					<th>권한</th>
					<td colspan="3">
						<select name="LEVEL" id="LEVEL" style="padding:5px 10px; border:1px solid #ddd;">
						<?php for($i=1; $i<=10; $i++){ ?>
							<option value="<?php echo $i?>" <?php echo (isset($memInfo) && $memInfo->LEVEL == $i)?"selected":"";?>><?php echo $i?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>비밀번호</th>
					<td><input type="password" name="PWD" id="PWD" value="" class="form_input"></td>
					<th>비밀번호확인</th>
					<td><input type="password" name="PWD_CHK" id="PWD_CHK" value="" class="form_input"></td>
				</tr>
				<tr>
					<th>이름</th>
					<td><input type="text" name="NAME" id="NAME" value="<?php echo isset($memInfo)?$memInfo->NAME:"";?>" class="form_input"></td>
					<th>상태</th>
					<td>
						<label>사용 : 
						<input type="radio" name="STATE" id="STATE" <?php echo ((isset($memInfo) && $memInfo->STATE == 1) || empty($memInfo))?"checked":"";?> value="1">
						</label>
						<label>미사용 : 
						<input type="radio" name="STATE" id="STATE" <?php echo (isset($memInfo) && $memInfo->STATE == 0)?"checked":"";?> value="0">
						</label>
					</td>
				</tr>
				<tr>
					<th>이메일</th>
					<td colspan="3"><input type="text" name="EMAIL" id="EMAIL" value="<?php echo isset($memInfo)?$memInfo->EMAIL:"";?>" class="form_input input_100"></td>
				</tr>
				<tr>
					<th>주소</th>
					<td colspan="3">
						<input type="text" name="ADDR2" id="ADDR2" value="<?php echo isset($memInfo)?$memInfo->ADDR2:"";?>" class="form_input input_100">
					</td>
				</tr>
				<tr>
					<th>연락처</th>
					<td><input type="text" name="TEL" id="TEL" value="<?php echo isset($memInfo)?$memInfo->TEL:"";?>" class="form_input"></td>
					<th>휴대폰</th>
					<td><input type="text" name="HP" id="HP" value="<?php echo isset($memInfo)?$memInfo->HP:"";?>" class="form_input"></td>
				</tr>
				<tr>
					<th>주민등록번호</th>
					<td><input type="text" name="JNUMBER" id="JNUMBER" value="<?php echo isset($memInfo)?$memInfo->JNUMBER:"";?>" class="form_input"></td>
					<th>혈액형</th>
					<td><input type="text" name="BLOOD" id="BLOOD" value="<?php echo isset($memInfo)?$memInfo->BLOOD:"";?>" class="form_input"></td>
				</tr>
				
			</tbody>
		</table>
	</div>
			

	<div class="tbl-content">
		
		<table class="none_border nhover" cellpadding="0" cellspacing="0" border="0" width="100%">
			<tbody>
			<tr><th colspan="4" style="font-size:15px; border-bottom:1px solid red;">추가정보</th></tr>
				<tr>
					<th>학력</th>
					<td>
						<input type="text" name="SCHOOL" id="SCHOOL" value="<?php echo isset($memInfo)?$memInfo->SCHOOL:"";?>" class="form_input">
					</td>
					<th>가족사항</th>
					<td><input type="text" name="FAMILY" id="FAMILY" value="<?php echo isset($memInfo)?$memInfo->FAMILY:"";?>" class="form_input"></td>
				</tr>
				<tr>
					<th>경력</th>
					<td>
						<input type="text" name="EXPERIENCE" id="EXPERIENCE" value="<?php echo isset($memInfo)?$memInfo->EXPERIENCE:"";?>" class="form_input">
					</td>
					<th>면허</th>
					<td><input type="text" name="LICENSE" id="LICENSE" value="<?php echo isset($memInfo)?$memInfo->LICENSE:"";?>" class="form_input"></td>
				</tr>
				<tr>
					<th>병역</th>
					<td>
						<input type="text" name="ARMY" id="ARMY" value="<?php echo isset($memInfo)?$memInfo->ARMY:"";?>" class="form_input">
					</td>
					<th>아이피</th>
					<td><input type="text" name="IP" id="IP" value="<?php echo isset($memInfo)?$memInfo->IP:"";?>" class="form_input"></td>
				</tr>
				<tr>
					<th>등록일</th>
					<td>
						<input type="text" name="REGDATE" id="REGDATE" value="<?php echo isset($memInfo)?substr($memInfo->REGDATE,0,10):"";?>" class="form_input">
					</td>
					<th>결혼유무</th>
					<td>
						<input type="radio" name="MARRY" value="Y" <?php echo (isset($memInfo) && $memInfo->MARRY == "Y")?"checked":"";?> class="form_input"> 기혼
						<input type="radio" name="MARRY" value="N" <?php echo (isset($memInfo) && $memInfo->MARRY == "N")?"checked":"";?> class="form_input"> 미혼
					</td>
				</tr>
				
				
			</tbody>
		</table>
	</div>

	<input type="submit" class="mod blue_btn" value="<?php echo isset($memInfo)?"회원수정":"회원등록";?>" style="margin:0 auto; display:block;">


</div>

</form>

<script>
$("input[name='REGDATE']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

</script>