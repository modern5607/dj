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
		<header style="height:unset;">
			<div style="float:left;">
				<ul id="left_sbox">
				<?php
				foreach($leftData as $left){
				?>
					<li>
						<h4><?php echo $left->NAME?></h4>
						<input type="text" name="ACT_TIME" size="5" data-mline="<?php echo $left->M_LINE?>" value="<?php echo $left->ACT_TIME;?>"/>
					</li>
				<?php
				}
				?>
				</ul>
			</div>
			<div style="float:right; margin-bottom:10px;">
				<div style="border:2px solid #f00; font-size:14px; color:#f00; padding:10px 30px;">일일작업진행시간 : <?php echo $toDate?></div>
			</div>
			
		</header>
		<div class="tbl-content3">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>생산라인</th>
						<th>금일생산시간</th>
						<th>생산실적</th>
						<th>달성률(%)</th>
						<th>생산효율(%)</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($viewList as $i=>$row){
				?>

					<tr>
						<td class="cen"><?php echo $row->NAME;?></td>
						<td class="cen"><strong><?php echo $row->ACT_TIME; ?></strong></td>
						<td class="cen"><?php echo $row->SUM_PT; ?></td>
						<td class="cen"><?php echo ($row->ACT1 != "")?$row->ACT1:"-"; ?></td>
						<td class="cen"><?php echo ($row->VAL != "")?$row->VAL:"-"; ?></td>
					</tr>

				<?php
				}
				if(empty($viewList)){
				?>

					<tr>
						<td colspan="12" class="list_none">제품정보가 없습니다.</td>
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
	
$("input[name='ACT_TIME']").on("change",function(){
	var time = $(this).val();
	var mline = $(this).data("mline");
	$.post("<?php echo base_url('rel/ajax_acttime_update')?>",{time:time, mline:mline}, function(data){
		if(data > 0){
			alert('변경되었습니다');
			location.reload();
		}
	});
});



setInterval(function(){
	location.reload();
},600000);



</script>