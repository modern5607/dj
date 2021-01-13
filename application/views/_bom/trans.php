<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<!-- 달력 및 에디터호출 -->
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>

<div class="bdcont_100">
	<div class="bc__box100">
		<header>
			<!--span class="btn add add_items"><i class="material-icons">add</i>신규등록</span-->
			<div style="float:left;">
				<form id="items_formupdate">
					<p>
					<label for="gjgb">공정구분</label>
					<input type="text" name="gjgb" value="<?php echo $str['gjgb']?>"/>	
					<button class="search_submit"><i class="material-icons">search</i></button>
				</p>
				</form>
			</div>
			<span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>순번</th>
						<th>BL_NO</th>
						<th>제품</th>
						<th>출고구분</th>
						<th>자재코드</th>
						<th>자재명</th>
						<th>출고량</th>
						<th>출고일</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($transList as $i=>$row){
					$num = $pageNum+$i+1;
					$useYn = ($row->USE_YN == "Y")?"사용":"미사용";
				?>

					<tr id="poc_<?php echo $row->TIDX;?>" class="pocbox">
						<td class="cen"><?php echo $num;?></td>
						<td><?php echo $row->BL_NO; ?></td>
						<td><?php echo $row->ITEM_NAME; ?></td>
						<td class="cen"><?php echo $row->KIND; ?></td>
						<td><?php echo $row->COMPONENT; ?></td>
						<td><?php echo $row->COMPONENT_NM; ?></td>
						<td class="right"><?php echo number_format($row->OUT_QTY); ?></td>
						<td class="cen"><?php echo substr($row->TRANS_DATE,0,10); ?></td>
						<!--td class="cen"><button type="button" class="mod mod_stock" data-idx="<?php echo $row->IDX;?>">수정</button></td-->
					</tr>

				<?php
				}
				if(empty($transList)){
				?>

					<tr>
						<td colspan="9" class="list_none">제품정보가 없습니다.</td>
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