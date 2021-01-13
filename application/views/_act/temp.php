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
						<th>NO</th>
						<th>LOT NO</th>
						<th>부번</th>
						<th>생산예정일</th>
						<th>생산수량</th>
						<th>단위</th>
						<th>상태</th>
						<th>사시즈NO</th>
						<th>지시수</th>
						<th>공정코드</th>
						<th>공정명</th>
						<th>공정지시수</th>
						<th>완료예정일</th>
						<th>계획배포일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($list as $i=>$row){
					
				?>

					<tr>
						<td class="cen"><?php echo $i+1; ?></td>
						<td><?php echo $row->LOT_NO; ?></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td><?php echo $row->ST_DATE; ?></td>
						<td><?php echo $row->QTY; ?></td>
						<td><?php echo $row->UNIT; ?></td>
						<td><?php echo $row->STATE; ?></td>

						<td><?php echo $row->SASIZ; ?></td>
						<td><?php echo $row->PL_QTY; ?></td>
						<td><?php echo $row->GJ_CODE; ?></td>
						<td><?php echo $row->GJ_NAME; ?></td>
						<td><?php echo $row->GJ_QTY; ?></td>
						<td><?php echo $row->ACT_DATE; ?></td>
						<td><?php echo $row->PLN_DATE; ?></td>
					</tr>

				<?php
				}
				if(empty($list)){
				?>

					<tr>
						<td colspan="17" class="list_none">제품정보가 없습니다.</td>
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
		$.post("<?php echo base_url('act/temp_update')?>",{mode:'update'},function(data){
			location.href="<?php echo base_url('act')?>";
		});
	}
});
</script>