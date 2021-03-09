<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- 달력 및 에디터호출 -->
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>

<style type ="text/css">
	.kpimean{
		float:right;font-size:15px;  display:flex;
		border:3px solid #ddd
	}
	.kpimean>p:last-child{
		border-right:0;
	}
	.kpimean>p{
		margin:5px 0;
		padding:0 5px;
		border-right:1px solid #ccc;
		color:#333;
	}
</style>
<!-- 
<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div> -->

<div class="bdcont_100" style="margin:20px">
	<div class="bc__box100">
		<header class="bc_search gsflexst">
			<div style="float:left;">
				<form id="items_formupdate">
                    <label for="">계획일</label>
                    <input type="text" name="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']: date("Y-m-d", strtotime("-1 month", time()))?>" size="12" autocomplete="off">~
                    <input type="text" name="edate" value="<?php echo ($str['edate']!="")?$str['edate']:date("Y-m-d",time())?>" size="12" autocomplete="off">
                    <button class="search_submit"><i class="material-icons">search</i></button>
				</form>
			</div>
			<div class="kpimean">
				<p>목표		: <?= $MF[1]->D_NAME ?> H</p>
				<p>구축 전	: <?= $MF[0]->D_NAME ?> H</p>
				<p>구축 후	: <?= $MF[2]->D_NAME ?> H</p>
			</div>
		</header>

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>no</th>
						<th>호선</th>
						<th>POR_NO</th>
						<th>품명/규격</th>
						<th>SEQ</th>
						<th>수량</th>
						<th>중량</th>
						<th>용접 계획일</th>
						<th>용접 실적일</th>
						<th>납품 작업일</th>
						<th>수주 구분</th>
						<th>품목구분</th>
					</tr>
				</thead>
				<tbody>
			<?php
				foreach($List as $i=>$row){
					$num = $pageNum+$i+1;
			?>
					<tr>
						<td class="cen"><?= $num ?></td>
						<td class="cen"><?= $row->PJT_NO ?></td>	
						<td class=""><?= $row->POR_NO ?></td>	
						<td class=""><?= $row->MCCSDESC ?></td>	
						<td class="right"><?= $row->POR_SEQ ?></td>	
						<td class="right"><?= $row->PO_QTY ?></td>	
						<td class="right"><?= number_format($row->WEIGHT) ?></td>	
						<td class="cen"><?= $row->WELD_PLN ?></td>	
						<td class="cen"><?= $row->WELD_ACT ?></td>	
						<td class="right"><?= !empty($row->WELD_WOR)?$row->WELD_WOR.'일':"" ?></td>	
						<td class=""><?= $row->OUTB_GBN ?></td>	
						<td style="text-align:left"><?= $row->DESC_GBN ?></td>	
					</tr>

			<?php
			}
				if(empty($List)){
				?>
					<tr>
						<td colspan="15" class="list_none">제품정보가 없습니다.</td>
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
                <select name="per_page" data-idx=<?=$idx?>>
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


<script>

$("input[name='sdate'],input[name='edate']").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

</script>