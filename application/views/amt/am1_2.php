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

        <label for="v2">품목</label>
        <input type="text" autocomplete="off" name="v2" id="v2" value="<?php echo $str['v2']?>">

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
                        <th>품목</th>
                        <th>색상</th>
                        <th>재고</th>
                    </tr>
                </thead>
                <tbody>
                <?php
				if(!empty($List))
				{
                    $count=0;
					foreach($List as $i=>$row){
						$no = $pageNum+$i+1;
                        ?>
							<tr>
								<td class="cen"><?php echo $no; ?></td>
								<td><?php echo $row->SERIES_NM; ?></td>
								<td><?php echo $row->ITEM_NM; ?></td>
								<td><?php echo $row->COLOR; ?></td>
								<td><?php echo number_format($row->IN_QTY); ?></td>
							</tr>
					<?php 
					}
				}
				if(empty($List))
				{?>
				<tr>
					<td colspan="15" class="list_none">실적정보가 없습니다.</td>
				</tr>
			<?php }?>
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



<div id="pop_container">

    <div id="info_content" class="info_content" style="height:auto;">

        <div class="ajaxContent"></div>

    </div>

</div>



<script type="text/javascript">

</script>