<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>
<style>
    /* 테블릿 스타일 */
    .home .material-icons {
        font-size: 40px;
    }

    .home a {
        color: black;
    }
</style>

<div class="body_cont_float2" style="height: 100vh;">
    <ul>
        <li style="width:100%;">

            <div id="" class="bc_search gsflexst" style="position:relative">
                <div class="home"><a href="<?php echo base_url('tablet/index') ?>"><span class="material-icons">
                            arrow_back
                        </span></a></div>
                <div style="margin-bottom: 7px; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);">

                    <span class="btn_right">
                        <p style="font-size: 30px; padding-left:20px;">
                            <?= empty($NDATE) ? "" : $NDATE ?></p>
                    </span>

                    <span class="btn_right">
                        <p style="font-size: 30px;">
                            <?= $title ?></p>
                    </span>


                </div>

                <span style="float: right;">
                <input type="hidden" name="timer" value="<?= $timer[0]->REMARK ?>">
                    <p id="iTime" style="font-size: 20px; float: left; margin-top: 8px; padding-right:20px;">
                    <?= ($timer[0]->REMARK != '')?$timer[0]->REMARK.'초후 새로고침':''; ?></p>
                    <span class="btni btn_right" style="float: right; margin-left:5px; padding: 10px;" onclick="location.reload()"><span class="material-icons">refresh</span></span>
                </span>

            </div>

            <div class="tbl-content">

                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>시리즈</th>
                            <th>품명</th>
                            <th>지시수량</th>
                            <th>완료수량</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($List)) { ?>
                            <?php foreach ($List as $i => $row) {
                                $num = $i + 1;
                                $mlink = ($row->END_YN != 'Y') ? "mlink add_act" : "";
                            ?>
                                <tr>
                                    <td class="cen"><?php echo $num; ?></td>
                                    <td><?php echo $row->SERIES_NM; ?></td>
                                    <td class="<?= $mlink ?> cen" data-idx="<?= $row->TRANS_IDX ?>"><?php echo $row->ITEM_NAME; ?></td>
                                    <td class="right"><?= number_format($row->ORDER_QTY); ?></td>
                                    <td class="right"><?= number_format($row->PROD_QTY) ?></td>
                                </tr>

                        <?php }
                        }else{
                        ?>
                                <tr>
                                    <td colspan="12" class="list_none">작업지시 내역이 없습니다.</td>
                                </tr>
                        <?php
                        } ?>

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
        var iTime = $("input[name='timer']").val(); // 새로고침 반복 시간  ex) 2분 = 2 * 60
        if(iTime == ''){
            return false;
        }
        var m;
        setInterval(function() {
            iTime--;
            if (iTime == 0)
                location.reload();
            m = iTime;

            $("#iTime").text(m + "초후 새로고침");
        }, 1000);
    });


    $(".add_act").on("click", function() {
        var idx = $(this).data('idx');
        var date = "<?= $NDATE ?>";
        console.log(date);
        $(".ajaxContent").html('');

        $("#pop_container").fadeIn();
        $(".info_content").animate({
            top: "50%"
        }, 500);

        $.ajax({
            url: "<?php echo base_url('Tablet/ajax_add_jh') ?>",
            type: "POST",
            dataType: "HTML",
            data: {
                idx: idx,
                date: date
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

            $.get("<?php echo base_url('ORD/ajax_del_items_order') ?>", {
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


    // setInterval(function() {
    //     location.reload();
    // }, 3000);
</script>