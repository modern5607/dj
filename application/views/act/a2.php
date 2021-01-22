<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="bc_header">
    <form id="items_formupdate">

        

        <label for="v3">품목</label>
        <input type="text" autocomplete="off" name="v3" id="v3" value="<?php echo $str['v3']?>">


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
                        <th>일자</th>
                        <th>시리즈</th>
                        <th>품목</th>
                        <th>수량</th>
                        <th>비고</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
				if(!empty($List)){
				$count = 0;
				$remark = 0;
				foreach($List as $i=>$row){ 
                    if($row->ITEM_NAME != "합계"){
					$no = $pageNum+$i+1;
					//$sumqty = $row->QT2+$row->QT3+$row->QT4;
				?>
                    <tr>
                        <td class="cen"><?php echo $no;?></td>
                        <td class="cen"><?php echo $row->TRANS_DATE;?></td>
                        <td><strong><?php echo $row->SERIES_NM; ?></strong></td>
                        <td><strong><?php echo $row->ITEM_NAME; ?></strong></td>
                        <td class="right"><?php echo number_format($row->IN_QTY); ?></td>
                        <td class=""><?php echo $row->REMARK; ?></td>
                    </tr>
                    <?php
                }else{
                $count = $row->IN_QTY;
                $remark = $row->REMARK;
                }
                }?>
				    <tr style="background:#f3f8fd;">
                        <td colspan="2" style="text-align:right;"><strong>총 수량</strong></td>
                        <td class="right"><?php echo $remark; ?></td>
                        <td colspan="" style="text-align:right;"><strong>총 합계</strong></td>
                        <td class="right"><?php echo $count; ?></td>
                        <td class="cen"></td>
                    </tr>
				<?php
			}
			else{?>
				<tr>
					<td colspan="15" class="list_none">정보가 없습니다.</td>
				</tr>
				<?php
			}?>
            </tbody>
			<tfoot>
            
                
                
            
			</tfoot>
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
var modchk = false;

function memberformChk(f) {

    var pwd = $("input[name='PWD']").val();
    var chkP = $("input[name='PWD_CHK']").val();
    var id = $("input[name='ID']").val();

    if (id == "") {
        alert("아이디를 입력하세요");
        $("input[name='ID']").focus();
        return false;
    }


    if (pwd == "" && !modchk) {
        alert("비밀번호를 입력하세요");
        $("input[name='PWD']").focus();
        return false;
    }

    if (pwd != chkP) {
        alert("비밀번호를 확인해주세요");
        $("input[name='PWD']").focus();
        return false;
    }

    modchk = false;
    return

}


$(".add_items").on("click", function() {

    $("#pop_container").fadeIn();
    $(".info_content").animate({
        top: "50%"
    }, 500);

    $.ajax({
        url: "<?php echo base_url('MDM/ajax_set_component')?>",
        type: "post",
        dataType: "html",
        success: function(data) {
            $(".ajaxContent").html(data);
        }

    });

});

$(".comp_update").on("click", function() {
    var idx = $(this).data("idx");

    $("#pop_container").fadeIn();
    $(".info_content").animate({
        top: "50%"
    }, 500);

    modchk = true;

    $.ajax({
        url: "<?php echo base_url('MDM/ajax_set_component')?>",
        type: "post",
        data: {
            idx: idx
        },
        dataType: "html",
        success: function(data) {
            $(".ajaxContent").html(data);
        }

    });
});

$("input[name='sdate'],input[name='edate']").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});


$(document).on("click", "h2 > span.close", function() {

    $(".ajaxContent").html('');
    $("#pop_container").fadeOut();
    $(".info_content").css("top", "-50%");
    location.reload();

});
//-->
</script>