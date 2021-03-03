<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
	<form id="items_formupdate">

	<label for="sdate">후처리일자</label>
		<input type="text" name="sdate" class="sdate calendar"
			value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));?>"
			size="12" /> ~

		<input type="text" name="edate" class="edate calendar"
			value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>"
			size="12" />

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
						<th>후처리일자</th>
						<th>시리즈</th>
						<th>품명</th>
						<th>색상</th>					
						<th>재고량</th>
						<th>수주수량</th>
						<th>불량수량</th>
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
					<td class="cen"><?php echo $row->KS_DATE;?></td>
					<td class="cen"><?php echo $row->SE_NAME;?></td>
					<td><strong><?php echo $row->ITEM_NAME; ?></strong></td>
					<td class="cen"><?php echo $row->COLOR; ?></td>
					<td class="right"><?php echo number_format($row->QTY); ?></td>
					<td class="right"><?php echo number_format($row->IN_QTY); ?></td>
					<td class="right"><?php echo number_format($row->KSQTY); ?></td>
					<td class="cen"><span class="btn del_stock" data-idx="<?php echo $row->IDX;?>">삭제</span></td>
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


$(".del_stock").on("click",function(){
	var idx = $(this).data("idx");
	var stock = $(this).parents("tr").find("td:eq(7)").text()*(-1);
	var cont = "";
	var gjgb = "SB";
	var kind = "IN";

	$.post("<?php echo base_url('ACT/ajax_a12_ksupdate')?>",
		{idx:idx, stock:stock, cont:cont, kind:kind, gjgb:gjgb},
		function(data){
			if(data > 0){
				alert("수정되었습니다.");
				location.reload();
			}
		}
	);

});

$("input[name='sdate'],input[name='edate'],.calendar").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});
</script>