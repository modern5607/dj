<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>




<div class="bc_header">
	<form id="items_formupdate">
					
		<label for="mid">아이디</label>
		<input type="text" name="mid" id="mid" value="<?php echo $str['mid']?>">
		
		<label for="login">로그인 날짜</label>
		<input type="text" class="calendar" name="login" id="login" value="<?php echo ($str['login']!="")?$str['login']:date("Y-m-d",time())?>" />
		
		<button class="search_submit"><i class="material-icons">search</i></button>
	</form>
	
</div>



<div class="bc_cont">
	<div class="cont_body">
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>IP</th>
						<th>아이디</th>
						<th>접속 기록</th>
						<th>활동 기록</th>
						<th>상태</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($userlog as $i=>$row){ ?>
				<tr>
					<td class="cen"><?php echo $row->IP; ?></td>
					<td class="cen"><?php echo $row->MID; ?></td>
					<td class="cen"><?php echo $row->SDATE; ?></td>
					<td class="cen"><?php echo $row->EDATE; ?></td>
					<td class="cen"><?php echo $row->STATUS; ?></td>
				</tr>
		

				<?php
				}
				if(empty($userlog)){
				?>

					<tr>
						<td colspan="5" class="list_none">회원정보가 없습니다.</td>
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


<script>
    $(".calendar").datetimepicker({
        format:'Y-m-d',
        timepicker:false,
        lang:'ko-KR'
    });
    $(".limitset select").on("change",function(){
        $(window).unbind("beforeunload");
var qstr = "<?php echo $qstr ?>";
        location.href="<?php echo base_url('smt/s5/')?>"+qstr+"&perpage="+$(this).val();
        
    });
</script>