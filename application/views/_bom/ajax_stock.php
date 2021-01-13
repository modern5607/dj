<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<form name="materialform" id="materialform" method="post" action="<?php echo base_url('bom/materialUpdate');?>" onsubmit="return materialformchk(this)">
	<input type="hidden" name="midx" value="<?php echo (!empty($materialInfo)?$materialInfo->IDX:"");?>" />
	<div class="tbl-write01">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			
			<tbody>
			
				<tr>
					<th class="w120">자재코드<span class="re"></span></th>
					<td colspan="3"><input type="text" name="COMPONENT" value="<?php echo (!empty($materialInfo)?$materialInfo->COMPONENT:"");?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">자재명<span class="re"></span></th>
					<td colspan="3"><input type="text" name="COMPONENT_NM" value="<?php echo (!empty($materialInfo)?$materialInfo->COMPONENT_NM:"");?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">규격</th>
					<td colspan="3"><input type="text" name="SPEC" value="<?php echo (!empty($materialInfo)?$materialInfo->SPEC:"");?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">REEL단위</th>
					<td><input type="text" name="REEL_CNT" value="<?php echo (!empty($materialInfo)?$materialInfo->REEL_CNT:"");?>" class="form_input input_100" /></td>
					<th class="w120">단가</th>
					<td><input type="text" name="PRICE" value="<?php echo (!empty($materialInfo)?$materialInfo->PRICE:"");?>" class="form_input input_100" /></td>
				</tr>
				<tr>
					<th class="w120">공정구분<span class="re"></span></th>
					<td>
						<?php
						if(!empty($GJ_GB)){
						?>
							<select name="GJ_GB" class="form_input select_call">
							<?php
							foreach($GJ_GB as $row){
								$selected5 = (!empty($materialInfo) && $materialInfo->GJ_GB == $row->D_CODE)?"selected":"";
							?>
								<option value="<?php echo $row->D_CODE?>" <?php echo $selected5;?>><?php echo $row->D_NAME;?></option>
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
					<th class="w120">사용유무<span class="re"></span></th>
					<td>
						<select name="USE_YN" class="form_input select_call">
							<option value="Y" <?php echo (!empty($materialInfo) && $materialInfo->USE_YN == "Y")?"selected":"";?>>사용</option>
							<option value="N" <?php echo (!empty($materialInfo) && $materialInfo->USE_YN == "N")?"selected":"";?>>미사용</option>
						</select>
					</td>
				</tr>
				<tr>
					<th class="w120">단위</th>
					<td colspan="3">
						<?php
						if(!empty($UNIT)){
						?>
							<select name="UNIT" class="form_input select_call">
							<?php
							foreach($UNIT as $row){
								$selected5 = (!empty($materialInfo) && $materialInfo->UNIT == $row->D_CODE)?"selected":"";
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
				</tr>
				
				<tr>
					<th class="w120">비고</th>
					<td colspan="3">
						<textarea name="REMARK" id="REMARK" class="form_input input_100">
							<?php echo (!empty($materialInfo))?$materialInfo->REMARK:"";?>
						</textarea>
					</td>
				</tr>
				
				<tr>
					<td colspan="4" style="text-align:center; padding:15px 0;">
						<button class="btn blue_btn">저장</button>
						<?php if(!empty($materialInfo)){ ?>
						<button type="button" data-idx="<?php echo $materialInfo->IDX; ?>" class="btn blue_btn del_mater">삭제</button>
						<?php } ?>
					</td>
				</tr>

			
			</tbody>
		</table>
	</div>

</form>

<script>

$("#INTO_DATE,#REPL_DATE").datetimepicker({
	format:'Y-m-d H:i:00',
	lang:'ko-KR'
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
