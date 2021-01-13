<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>



<div id="pageTitle">
<h1>임시데이터 확인</h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		
		<header>
			total : <?php echo $num;?>
			<span class="btn print excel_update"><i class="material-icons">get_app</i>업데이트</span> 
		</header>
		
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>자재코드</th>
						<th>랭크</th>
						<th>일련번호</th>
						<th>LotNo</th>
						<th>수량</th>
						<th>입고일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($list as $i=>$row){
					
				?>

					<tr>
						<td><?php echo $row->COMPONENTNO; ?></td>
						<td><?php echo $row->RANK; ?></td>
						<td><?php echo $row->NO; ?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td class="right"><?php echo number_format($row->QTY); ?></td>
						<td class="cen"><?php echo substr($row->IPGO_DATE,0,10); ?></td>
					</tr>

				<?php
				}
				if(empty($list)){
				?>

					<tr>
						<td colspan="17" class="list_none cen">제품정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>

		

	</div>
</div>



<script>
$(".excel_update").on("click",function(){
	if(confirm("임시데이터를 등록하시겠습니까?\n등록 후 임시데이터는 삭제됩니다.") !== false){
		$.post("<?php echo base_url('mat/matform_temp_update')?>",{mode:'update'},function(data){
			location.href="<?php echo base_url('mat/matform')?>";
		});
	}
});
</script>