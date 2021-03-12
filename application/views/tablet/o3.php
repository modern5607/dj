<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>


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
                            <th>시리즈</th>
                            <th>품명</th>
                            <th>색상</th>
                            <th>지시수량</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($RList)) { ?>
                        <?php
                    foreach ($RList as $i => $row) {
                        $num = $i + 1;
                    ?>

                        <tr>
                            <td class="cen"><?php echo $num; ?></td>
                            <td><?php echo $row->SERIES_NM; ?></td>
                            <td><a href="#" class="btni btn_right add_itemnum"
                                    data-idx="<?php echo $row->TRANS_IDX; ?>"><?php echo $row->ITEM_NAME; ?></a></td>
                            <td><?php echo $row->COLOR; ?></td>
                            <td class="right"><?php echo number_format($row->ORDER_QTY); ?></td>
                            <td><?php echo $row->REMARK; ?></td>
                        </tr>

                        <?php 
                    }
                }
                if (empty($RList) ) {
                    ?>
                        <tr>
                            <td colspan="10" style='color:#999; padding:40px 0;'>등록된 작업지시가 없습니다.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
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
        url: "<?php echo base_url('TABLET/ajax_invenNum_form') ?>",
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


$(".del_items").on("click", function() {
    var idx = $(this).data("idx");

    if (confirm('삭제하시겠습니까?') !== false) {

        $.get("<?php echo base_url('ORD/ajax_del_inven_order') ?>", {
            idx: idx
        }, function(data) {
            if (data.status != "") {
                alert(data.msg);
                location.reload();
            }
        }, "JSON");
    }
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

});
//-->
</script>