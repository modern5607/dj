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
		<input type="text"autocomplete="off" name="v3" id="v3" value="<?php echo $str['v3']?>">

		<label for="v4">색상</label>
		<input type="text"autocomplete="off" name="v4" id="v4" value="<?php echo $str['v4']?>">

				
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
						<th>시리즈</th>
						<th>품명</th>
						<th>색상</th>					
						<th>재고량</th>
						<th>조정수량</th>
						<th>조정사유</th>
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
					<td class="right">
					<input type="hidden" name="QTY[]" class="form_input1" value="<?php echo number_format($row->QTY); ?>">
					<?php echo number_format($row->QTY); ?></td>
					<td class="cen"><input type="text" name="C_QTY[]" class="form_input1" size="6" value="" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"></td>
					<td class="cen"><input type="text" name="C_CONT[]" class="form_input1" size="30" value=""></td>
					<td class="cen"><span class="btn mod_stock" data-item="<?php echo $row->ITEM_IDX;?>"  data-seriesd="<?php echo $row->SERIESD_IDX;?>">수정</span></td>
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


$(".mod_stock").on("click",function(){
	var item = $(this).data("item");
	var seriesd = $(this).data("seriesd");
	var qty = $(this).parents("tr").find("input[name^='QTY']").val()*1;
	var cqty = $(this).parents("tr").find("input[name^='C_QTY']").val()*1;
	var cont = $(this).parents("tr").find("input[name^='C_CONT']").val();


	if(qty == cqty){
		alert('현 재고량입니다.');
		return false;
	}

	if(qty < cqty){
		var kind = "INM";
		var qtygb = "IN_QTY";
		var stock = cqty - qty;
	}else{
		var kind = "OTM";
		var qtygb = "OUT_QTY";
		var stock = qty - cqty;
	}

if(stock == 0 || stock == ""){
		alert('수량을 입력하세요');
		return false;
	}

	$.post("<?php echo base_url('ACT/ajax_an4_listupdate')?>",
		{item:item, seriesd:seriesd, stock:stock, cont:cont, kind:kind, qtygb:qtygb},
		function(data){
			if(data > 0){
				alert("수정되었습니다.");
				location.reload();
			}
		}
	);

});

//-->
</script>