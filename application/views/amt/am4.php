<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
	<form id="items_formupdate">

		
		<label for="v1">시리즈</label>
		<select name="v1">
			<option value="">::선택::</option>
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
		<input type="text" name="v3" id="v3" value="<?php echo $str['v3']?>">
		
						
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	
</div>


<div class="bc_cont">
	<div class="cont_header"><?php echo $title;?></div>
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>수주일자</th>
						<th>품명</th>
						<th>주문수량</th>
						<th>출고수량</th>
						<th>출고일</th>
						<th>비고</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if(!empty($List)){
				foreach($List as $i=>$row){ 
					$no = $i+1;
					
				?>
				<tr>
					<td class="cen"><?php echo $no;?></td>
					<td class="cen"><?php echo $row->ACT_DATE;?></td>
					<td><strong><?php echo $row->ITEM_NM; ?></strong></td>
					<td class="right"><?php echo $row->QTY; ?></td>
					<td class="cen">
						<input type="text" name="OUT_QTY[]" class="form_input1" value="" size="6">
						<input type="hidden" name="QTY[]" value="<?php echo $row->QTY; ?>">
					</td>
					<td class="cen"><input type="text" name="OUT_DATE[]" class="form_input1" value=""></td>
					<td class="cen"><span class="btn mod_stock" data-actidx="<?php echo $row->ACT_IDX;?>"  data-seriesd="<?php //echo $row->SERIESD_IDX;?>">수정</span></td>
				</tr>
						

				<?php
				}
				}else{
				
				?>

					<tr>
						<td colspan="15" class="list_none">실적정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>
		

		<div class="pagination">
			<?php echo $this->data['pagenation'];?>
			<?
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



<div id="pop_container">
	
	<div id="info_content" class="info_content" style="height:auto;">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>



<script type="text/javascript">
<!--

function leadingZeros(n, digits) {

    var zero = '';
    n = n.toString();

    if (n.length < digits) {
        for (i = 0; i < digits - n.length; i++)
            zero += '0';
    }
    return zero + n;
}


$("input[name^=OUT_QTY]").on("change",function(){
	if($(this).val() == ""){
		$(this).parents("tr").find("input[name^='OUT_DATE']").val('');
	}else{
		var d = new Date();
		var s =
        leadingZeros(d.getFullYear(), 4) + '-' +
        leadingZeros(d.getMonth() + 1, 2) + '-' +
        leadingZeros(d.getDate(), 2) +' '+
		leadingZeros(d.getHours(), 2) +':'+
		leadingZeros(d.getMinutes(), 2) +':'+
		leadingZeros(d.getSeconds(), 2);


		$(this).parents("tr").find("input[name^='OUT_DATE']").val(s);
	}
});


$(".mod_stock").on("click",function(){
	var actidx = $(this).data("actidx");
	var seriesd = "";
	var outqty = $(this).parents("tr").find("input[name^='OUT_QTY']").val();
	var qty = $(this).parents("tr").find("input[name^='QTY']").val();
	var xdate = $(this).parents("tr").find("input[name^='OUT_DATE']").val();

	$this = $(this);
	
	
	if(outqty == 0 || outqty == ""){
		alert('출고수량을 입력하세요');
		return false;
	}

	
	
	$.post("<?php echo base_url('AMT/ajax_am4_listupdate')?>",{actidx:actidx, seriesd:seriesd, outqty:outqty, xdate:xdate},function(data){
		if(data.status != ""){
			alert(data.msg);
			if(data.status == "Y"){
				location.reload();
			}else{
				$this.parents("tr").find("input[name^='OUT_QTY']").val('');
				$this.parents("tr").find("input[name^='OUT_DATE']").val('');
				$this.parents("tr").find("input[name^='OUT_QTY']").focus();
			}
		}
	},"JSON");

});




//-->
</script>