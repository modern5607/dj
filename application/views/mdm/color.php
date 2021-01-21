<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
	<form id="items_formupdate">
		<label for="v1">시리즈</label>
			<select name="v1">
				<option value="">전체</option>
			<?php
			foreach($SERIES as $row){
				$selected = (!empty($str['v1']) && $row->IDX == $str['v1'])?"selected":"";
			?>
				<option value="<?php echo $row->IDX;?>" <?php echo $selected;?>><?php echo $row->SERIES_NM;?></option>
			<?php
			}
			?>
			</select>
		<label for="v3">품목</label>
		<input type="text" autocomplete="off" name="v3" id="v3" value="<?php echo $str['v3']?>">
		<label for="v4">색상</label>
		<input type="text" autocomplete="off" name="v4" id="v4" value="<?php echo $str['v4']?>">
		
		<label for="v2" >사용유무</label>
		<select name="v2">
			<option value="">전체</option>		
			<option value="Y" <?php echo ($str['v2'] == "Y")?"selected":"";?>>사용</option>
			<option value="N" <?php echo ($str['v2'] == "N")?"selected":"";?>>미사용</option>		
		</select>	

		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
</div>


<div class="bc_cont">
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>시리즈</th>
						<th>품명</th>
						<th>색상</th>					
						<th>현재고량</th>
						<th>출고대기수량</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($List)){
				foreach($List as $i=>$row){ 
					$no = $pageNum+$i+1;
				?>
				<tr>
					<td class="cen"><?php echo $no;?></td>
					<td class="cen"><?php echo $row->SE_NAME;?></td>
					<td><strong><?php echo $row->ITEM_NAME; ?></strong></td>
					<td class="cen"><?php echo $row->COLOR; ?></td>
					<td class="right"><?php echo number_format($row->QTY); ?></td>
					<td class="right"><?php echo number_format($row->EQTY); ?></td>
					<td class="right">
						<select name="useyn" 
						data-seriesd="<?php echo $row->SERIESD_IDX ?>"
						data-item="<?php echo $row->ITEM_IDX ?>"
						data-qty='<?php echo $row->QTY; ?>' 
						data-eqty='<?php echo $row->EQTY; ?>'>
							<option value="Y">사용</option>
							<option value="N" <?php echo ($row->USE_YN == "N")?"selected":"";?>>미사용</option>
						</select>
					</td>
				</tr>
						

				<?php
				}
				}else{
				
				?>

					<tr>
						<td colspan="15" class="list_none">재고내역 정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>
		
		<div class="pagination">
			<?php echo $this->data['pagenation'];?>
			<?php
			if($this->data['cnt'] > 20){
			?>
			<div class="limitset">
				<select name="per_page">
					<option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
					<option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
					<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
					<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
				</select>
			</div>
			<?php
			}	
			?>
		</div>

	</div>
</div>




<script type="text/javascript">
$('select[name=useyn]').on('change',function(){

	if($(this).data('qty') > 0 ){
		alert('현재고량이 남아있습니다.');
		$(this).children().eq(0).prop("selected", true);
		return false;
	}
	if($(this).data('eqty') > 0 ){
		alert('출고대기량이 남아있습니다.');
		$(this).children().eq(0).prop("selected", true);
		return false;
	}

	
	var formData = new FormData();
	formData.append('seriesd', $(this).data('seriesd'));
	formData.append('item', $(this).data('item'));
	formData.append('use', $(this).val());


	$.ajax({
		url  : "<?php echo base_url('MDM/color_use_update')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		success : function(data){	
			location.reload();
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});























})
</script>