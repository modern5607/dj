<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<style>
.claim_insert{
    cursor: pointer;
    color: #194bff;
}
</style>
<div class="bc_header">
    <form id="items_formupdate">
        <label for="sdate">일자</label>
            <input type="text" name="sdate" class="sdate calendar" value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));?>" size="12" /> ~ 
            
            <input type="text" name="edate" class="edate calendar" value="<?php echo (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>" size="12" />

        <label for="cust">거래처</label>
            <select name="cust" id="cust">
                <option value="">전체</option>
                <?php
                foreach($CUST as $row){
                    $selected = (!empty($str['cust']) && $row->IDX == $str['cust'])?"selected":"";
                ?>
                    <option value="<?php echo $row->IDX;?>" <?php echo $selected;?>><?php echo $row->CUST_NM;?></option>
                    <?php
                }
                ?>
            </select>

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
        <input type="text" autocomplete="off" name="v3" id="v3" value="<?php echo $str['v3']?>">
        
        <label for="claim">클레임</label>
            <select name="claim" id="claim">
             <?php $selected = ($str['claim'] == "1")?"selected":"";?>
                <option value="">전체</option>
                <option value="1"  <?php echo($str['claim'] == "1")?"selected":"";?>>클레임 품목</option>
                <option value="2"  <?php echo($str['claim'] == "2")?"selected":"";?>>클레임 제외</option>
            </select>

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
                        <th>출고일</th>
                        <th>거래처</th>
                        <th>시리즈</th>
                        <th>품명</th>
                        <th>색상</th>
                        <th>주문수량</th>
                        <th>출고수량</th>
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
                        <td class="cen"><span class="claim_insert" data-idx="<?php echo $row->ACT_IDX;?>"><?php echo $row->TRANS_DATE;?></span></td>
                        <td class="cen"><?php echo $row->CUST_NM;?></td>
                        <td class="cen"><?php echo $row->SERIES_NM;?></td>
                        <td><strong><?php echo $row->ITEM_NM; ?></strong></td>
                        <td><strong><?php echo $row->COLOR; ?></strong></td>
                        <td class="right"><?php echo number_format($row->QTY); ?></td>
                        <td style="text-align:right"><?php echo number_format($row->OUT_QTY); ?></td>

                    </tr>


                    <?php
				}
				}else{
				?>

                    <tr>
                        <td colspan="15" class="list_none"><?php echo($str['claim'] == "1")?"클레임":"제품";?> 내역이 없습니다.</td>
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



<div id="pop_container">

    <div id="info_content" class="info_content" style="height:auto;">

        <div class="ajaxContent"></div>

    </div>

</div>



<script type="text/javascript">

$(".calendar").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});


$(".claim_insert").on("click",function(){
	var idx = $(this).data("idx");

	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	modchk = true;

	$.ajax({
		url:"<?php echo base_url('amt/ajax_claim_insert')?>",
		type : "post",
		data : {idx:idx},
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
		
	});
});

</script>