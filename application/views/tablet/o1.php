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

    .tbl-content-t table {
        background: #fff;
        border-collapse: collapse;
        border-radius: 1em;
        overflow: hidden;
        text-align: left;
        line-height: 1.5;
        margin: 50px 10px;
    }

    .tbl-content-t table thead th {
        width: 150px;
        padding: 20px;
        font-size: 20px;
        font-weight: bold;
        vertical-align: top;
        color: #fff;
        background: #3b4d73;
        margin: 20px 10px;
        text-align: center;
    }

    .tbl-content-t table tbody th {
        width: 150px;
        padding: 10px;
    }


    .tbl-content-t table td {
        width: 350px;
        padding: 16px;
        font-size: 20px;
        vertical-align: top;
        text-align: center;
        border-bottom: 1px solid #f7f7f7;
    }

    .even {
        background: #f3f8ff;
    }
    
</style>

<div class="body_cont_float2" style="height: 100vh;">
    <ul>
        <li style="width:100%; background:#f7f7f7; padding:50px 80px 50px 80px;">

            <div id="" class="bc_search gsflexst" style="border: 1px solid #f7f7f7; display:flex; justify-content:space-between; position:relative">

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
                        <?= ($timer[0]->REMARK != '') ? $timer[0]->REMARK . '초후 새로고침' : ''; ?></p>
                    <span class="btni btn_right" style="float: right; margin-left:5px; padding: 10px;" onclick="location.reload()"><span class="material-icons">refresh</span></span>
                </span>

            </div>

            <div class="tbl-content-t">
                <table class="t_table" cellpadding="0" cellspacing="0" border="0" width="100%">
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
                                    <td style="widtj:50px;" class="cen"><?php echo $num; ?></td>
                                    <td><?php echo $row->SERIES_NM; ?></td>
                                    <td class="<?= $mlink ?> cen" style="text-align:left;" data-idx="<?= $row->TRANS_IDX ?>">
                                        <?php echo $row->ITEM_NAME; ?></td>
                                    <td class="right"><?= number_format($row->ORDER_QTY); ?></td>
                                    <td class="right"><?= number_format($row->PROD_QTY) ?></td>
                                </tr>

                            <?php }
                        } else {
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
    $('tr:even').addClass('even');
    var timerControl = '';
    var iTime = $("input[name='timer']").val();


    $(document).ready(function() {
        if (iTime == '') {
            return false;
        }
        var m;
        timerControl = setInterval(function() {
            iTime--;
            if (iTime <= 0)
                location.reload();
            m = iTime;

            $("#iTime").text(m + "초후 새로고침");
        }, 1000);
    });



    $(".add_act").on("click", function() {
        clearInterval(timerControl)
        var idx = $(this).data('idx');
        var date = "<?= $NDATE ?>";
        console.log(date);
        $(".ajaxContent").html('');

        $("#pop_container").fadeIn();
        $(".info_content").animate({
            top: "50%"
        }, 500);

        $.ajax({
            url: "<?php echo base_url('Tablet/ajax_add_sh') ?>",
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



    $("input[name='sdate'],input[name='edate']").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });


    $(document).on("click", "h2 > span.close", function() {
        iTime = $("input[name='timer']").val();
        timerControl = setInterval(function() {
            if (iTime == '') {
            return false;
            }
            iTime--;
            if (iTime == 0)
                location.reload();
            m = iTime;

            $("#iTime").text(m + "초후 새로고침");
        }, 1000);

        $(".ajaxContent").html('');
        $("#pop_container").fadeOut();
        $(".info_content").css("top", "-50%");

    });

</script>