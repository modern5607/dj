<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form name="itemsform" id="itemsform" method="post" action="<?php echo base_url('bom/itemsUpdate');?>" onsubmit="return itemsformchk(this)">
	<input type="hidden" name="midx" value="<?php echo (!empty($bomInfo)?$bomInfo->IDX:"");?>" />
	<div class="tbl-write01">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			
			<tbody>
			
				<tr>
					<th class="w120">B/L NO<span class="re"></span></th>
					<td colspan="3"><input type="text" name="BL_NO" value="<?php echo (!empty($bomInfo)?$bomInfo->BL_NO:"");?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">품명<span class="re"></span></th>
					<td colspan="3"><input type="text" name="ITEM_NAME" value="<?php echo (!empty($bomInfo)?$bomInfo->ITEM_NAME:"");?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">규격<span class="re"></span></th>
					<td colspan="3"><input type="text" name="ITEM_SPEC" value="<?php echo (!empty($bomInfo)?$bomInfo->ITEM_SPEC:"");?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">단위<span class="re"></span></th>
					<td>
					<?php
					if(!empty($UNIT)){
					?>
						<select name="UNIT" class="form_input select_call" style="width:100%;">
						<?php
						foreach($UNIT as $row){
							$selected5 = (!empty($bomInfo) && $bomInfo->UNIT == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected5;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 UNIT를 등록하세요</a>";
					}
					?>
					</td>
					<th class="w120">MSAB</th>
					<td>
					<?php
					if(!empty($MSAB)){
					?>
						<select name="MSAB" class="form_input select_call" style="width:100%;">
						<?php
						foreach($MSAB as $row){
							$selected1 = (!empty($bomInfo) && $bomInfo->MSAB == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected1;?>><?php echo $row->D_NAME;?></option>
						<?php
						}	
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 HSAB를 등록하세요</a>";
					}
					?>
					</td>
				</tr>
				<tr>
					<th class="w120">상태</th>
					<td colspan="3">
					<?php
					if(!empty($STATE)){
					?>
						<select name="STATE" class="form_input select_call" style="width:100%;">
						<?php
						foreach($STATE as $row){
							$selected4 = (!empty($bomInfo) && $bomInfo->STATE == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected4;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 STATE를 등록하세요</a>";
					}
					?>
					
					</td>
				</tr>
				
				
				<tr>
					<th class="w120">생산라인1<span class="re"></span></th>
					<td>
					<?php
					if(!empty($M_LINE)){
					?>
						<select name="M_LINE" id="LINE1" class="form_input select_call" style="width:100%;">
						<?php
						foreach($M_LINE as $row){
							$selected7_1 = (!empty($bomInfo) && $bomInfo->M_LINE == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected7_1;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 M_LINE를 등록하세요</a>";
					}
					?>
					</td>
					<th class="w120">소요시간<span class="re"></span></th>
					<td><input type="text" name="P_T" value="<?php echo (!empty($bomInfo) && $bomInfo->P_T != 0)?$bomInfo->P_T:"";?>" class="form_input input_100" /></td>
				</tr>
				

				<tr>
					<th class="w120">생산라인2</th>
					<td>
					<?php
					if(!empty($M_LINE)){
					?>
						<select name="2ND_LINE" id="LINE2" class="form_input select_call" style="width:100%;">
							<option value="" selected>해당없음</option>
						<?php
						foreach($M_LINE as $row){
							$selected7_2 = (!empty($bomInfo) && $bomInfo->LINE2 == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected7_2;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 M_LINE를 등록하세요</a>";
					}
					?>
					</td>
					<th class="w120">소요시간</th>
					<td><input type="text" name="2ND_P_T" value="<?php echo (!empty($bomInfo) && $bomInfo->PT2 != 0)?$bomInfo->PT2:"";?>" class="form_input input_100" /></td>
				</tr>


				<tr>
					<th class="w120">생산라인3</th>
					<td>
					<?php
					if(!empty($M_LINE)){
					?>
						<select name="3ND_LINE" id="LINE3" class="form_input select_call" style="width:100%;">
							<option value="" selected>해당없음</option>
						<?php
						foreach($M_LINE as $row){
							$selected7_3 = (!empty($bomInfo) && $bomInfo->LINE3 == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected7_3;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 M_LINE를 등록하세요</a>";
					}
					?>
					</td>
					<th class="w120">소요시간</th>
					<td><input type="text" name="3ND_P_T" value="<?php echo (!empty($bomInfo) && $bomInfo->PT3 != 0)?$bomInfo->PT3:"";?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">포장단위</th>
					<td><input type="text" name="PACKING" value="<?php echo (!empty($bomInfo)?$bomInfo->PACKING:"");?>" class="form_input input_100" /></td>
					<th class="w120">고객명</th>
					<td>
					<?php
					if(!empty($CUSTOMER)){
					?>
						<select name="CUSTOMER" class="form_input select_call" style="width:100%;">
						<?php
						foreach($CUSTOMER as $row){
							$selected6 = (!empty($bomInfo) && $bomInfo->CUSTOMER == $row->CUST_NM)?"selected":"";
						?>
							<option value="<?php echo $row->CUST_NM;?>" <?php echo $selected6;?>><?php echo $row->CUST_NM;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('biz')."' class='none_code'>업체를 등록하세요</a>";
					}
					?>
					</td>
				</tr>
				<tr>
					<th class="w120">제품단가</th>
					<td><input type="text" name="PRICE" value="<?php echo (!empty($bomInfo)?$bomInfo->PRICE:"");?>" class="form_input input_100" /></td>
					<th class="w120">공정구분</th>
					<td>
					<?php
					if(!empty($GJ_GB)){
					?>
						<select name="GJ_GB" class="form_input select_call" style="width:100%;">
						<?php
						foreach($GJ_GB as $row){
							$selected8 = (!empty($bomInfo) && $bomInfo->GJ_GB == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected8;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 GJ_GB를 등록하세요</a>";
					}
					?>
					</td>
				</tr>
				<tr>
					<th class="w120">대분류</th>
					<td>
					<?php
					if(!empty($stClass1)){
					?>
						<select name="1ST_CLASS" class="form_input select_call" style="width:100%;">
						<?php
						foreach($stClass1 as $row){
							$selected2 = (!empty($bomInfo) && $bomInfo->CLASS1 == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected2;?>><?php echo $row->D_NAME;?></option>
						<?php
						}	
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 1ST_CLASS를 등록하세요</a>";
					}
					?>
					</td>
					<th class="w120">소분류</th>
					<td>
					<?php
					if(!empty($stClass2)){
					?>
						<select name="2ND_CLASS" class="form_input select_call" style="width:100%;">
						<?php
						foreach($stClass2 as $row){
							$selected3 = (!empty($bomInfo) && $bomInfo->CLASS2 == $row->D_CODE)?"selected":"";
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo $selected3;?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}else{
						echo "<a href='".base_url('mdm')."' class='none_code'>공통코드 2ST_CLASS를 등록하세요</a>";
					}
					?>
					</td>
				</tr>
				<tr>
					<th class="w120">기종</th>
					<td><input type="text" name="MODEL" value="<?php echo (!empty($bomInfo)?$bomInfo->MODEL:"");?>" class="form_input input_100" /></td>
					<th class="w120">사용유무</th>
					<td>
						<select name="USE_YN" class="form_input select_call" style="width:100%;">
							<option value="Y" <?php echo (!empty($bomInfo) && $bomInfo->USE_YN == "Y")?"selected":"";?>>사용</option>
							<option value="N" <?php echo (!empty($bomInfo) && $bomInfo->USE_YN == "N")?"selected":"";?>>미사용</option>
						</select>
					</td>
				</tr>
				<tr>
					<th class="w120">등록일</th>
					<td><input type="text" name="INSERT_DATE" id="INSERT_DATE" value="<?php echo (!empty($bomInfo))?$bomInfo->INSERT_DATE:date("Y-m-d H:i:s",time()); ?>" class="form_input input_100" /></td>
					<th class="w120">등록자</th>
					<td><input type="text" name="INSERT_ID" value="<?php echo (!empty($bomInfo))?$bomInfo->INSERT_ID:$this->data['userName'];?>" class="form_input input_100" /></td>
				</tr>
				


				<tr>
					<th class="w120">비고</th>
					<td colspan="3">
						<textarea name="REMARK" id="REMARK" class="form_input input_100">
							<?php echo (!empty($bomInfo))?$bomInfo->REMARK:"";?>
						</textarea>
					</td>
				</tr>

				<tr>
					<td colspan="4" style="text-align:center; padding:15px 0;">
						<button class="btn blue_btn">저장</button>
						<?php if(!empty($bomInfo)){ ?>
						<button type="button" data-idx="<?php echo $bomInfo->IDX;?>" class="btn blue_btn del_items">삭제</button>
						<?php } ?>
					</td>
				</tr>

			
			</tbody>
		</table>
	</div>

</form>

<script>

$("#INSERT_DATE").datetimepicker({
	format:'Y-m-d H:i:00',
	lang:'ko-KR'
});


$("#LINE2").on("change",function(){
	if($(this).val() == ""){
		$("input[name='2ND_P_T']").val('');
	}
});

$("#LINE3").on("change",function(){
	if($(this).val() == ""){
		$("input[name='3ND_P_T']").val('');
	}
});





$('#REMARK').summernote({
    height:100,
    lang: 'ko-KR',
	toolbar:false,
	dialogsFade: true,
	disableDragAndDrop: true, //드래그앤드랍true:비활성
    callbacks: {
        onImageUpload : function (files, editor, welEditable) {
            console.log('SummerNote image upload : ', files);
            //sendFile(files, editor, welEditable, '#summernote');
        },
        onMediaDelete : function($target, editor, welEditable) {
            /*const path = $target.attr("src");
            console.log(path);
            $.post("<?php echo base_url('acon/delete_editor_image')?>",{path},function(data){
				if(data != "error"){
					alert(data);
				}
            });*/
        }
    }
});

</script>