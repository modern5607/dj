<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2" style="height: 100vh;" >
    <ul>
        <li style="width:100%;">
        <div id="" class="bc_search gsflexst">
                <div style="margin-bottom: 7px;">

                    <span class="btn_right">
                        <p style="font-size: 30px; padding-left:20px;">
                            <?= empty($NDATE) ? "" : $NDATE ?></p>
                    </span>

                    <span class="btn_right">
                        <p style="font-size: 30px; padding-left:20px;">
                            <?= $title ?></p>
                    </span>


                </div>

                <span style="float: right;">
                    <p id="iTime" style="font-size: 20px; float: left; margin-top: 8px; padding-right:20px;"></p>
                    <span class="btni btn_right" style="float: right; margin-left:5px; padding: 10px;"
                        onclick="location.reload()"><span class="material-icons">refresh</span></span>
                </span>

            </div>

            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>시유일자</th>
                            <th>품명</th>
                            <th>색상</th>
                            <th>수주수량</th>
                            <th>시유수량</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($List as $i=>$row){
							$num = $i+1;
					?>
                        <tr>
                            <td class="cen"><?php echo $num;?></td>
                            <td class="cen"><?php echo substr($row->ACT_DATE,0,10);?></td>
                            <td><span data-idx='<?php echo $row->IDX;?>'
                                    class="link_s1 add_itemnum"><?php echo $row->ITEM_NM;?></span></td>
                            <td class="cen"><?php echo $row->COLOR;?></td>
                            <td class="right"><?php echo $row->QTY;?></td>
                            <td style="text-align:right"><?php echo $row->IN_QTY;?></td>

                        </tr>
                        <?php } ?>
                        <?php
					if(empty($List)){
					?>
                        <tr>
                            <td colspan="8" style='color:#999; padding:40px 0;'>실적정보가 없습니다.</td>
                        </tr>
                        <?php
					}
					?>
                    </tbody>
                </table>

            </div>
        </li>
    </ul>
</div>



<div id="pop_container">

    <div class="info_content" style="height:auto;">
        <div class="ajaxContent">

            <!-- 데이터 -->

        </div>
    </div>

</div>



<script type="text/javascript">
$(document).ready(function() {
    var iTime = 10; // 새로고침 반복 시간  ex) 2분 = 2 * 60
    var m;
    setInterval(function() {
        iTime--;
        if (iTime == 0)
            location.reload();
        m = iTime;

        $("#iTime").text(m + "초후 새로고침");
    }, 1000);
});


$(".add_itemnum").on("click", function() {

    var idx = $(this).data("idx");
    $(".ajaxContent").html('');

    $("#pop_container").fadeIn();
    $(".info_content").animate({
        top: "50%"
    }, 500);

    $.ajax({
        url: "<?php echo base_url('tablet/ajax_invenNum1_form')?>",
        type: "POST",
        dataType: "HTML",
        data: {
            idx: idx
        },
        success: function(data) {
            $(".ajaxContent").html(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            alert(xhr);
            alert(textStatus);
            alert(errorThrown);
        }
    })

});



$(document).on("click", "h2 > span.close", function() {

    $(".ajaxContent").html('');
    $("#pop_container").fadeOut();
    $(".info_content").css("top", "-50%");

});
//-->
</script>