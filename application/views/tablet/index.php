<?php
defined('BASEPATH') or exit('No direct script access allowed');
$today = date('y-m-d');
?>
<style>
    .tlink {
        display: flex;
        flex-direction: column;
        text-align: center;
        height: 170px;
        border-radius: 20px;
    }

    .tlink:nth-child(1) {
        background: #e6f5f3;
    }

    .tlink:nth-child(2) {
        background: #d8e3f8;
    }

    .tlink:nth-child(3) {
        background: #f8f2ee;
    }

    .tlink:nth-child(4) {
        background: #fedddb;
    }

    .tlink_column {
        border-radius: 20px;
        height: 170px;
        width: 200px;
        font-size: 24px;
        -webkit-box-shadow: 6px 10px 10px -8px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 6px 10px 10px -8px rgba(0, 0, 0, 0.75);
        box-shadow: 6px 10px 10px -8px rgba(0, 0, 0, 0.75);
    }

    .bdcont_100 {
        height: 100vh;
    }

    .bc__box100 {
        background: #f8f8f8;
        display: flex;
        height: 100%;
        padding: 40px;
    }

    .m_nav {
        display: flex;
        justify-content: space-around;
        width: 70%;
        margin-top: 10%;
    }

    .side_menu {
        background: #f8f8f8;
        width: 30%;
        display: flex;
        flex-direction: column;
        padding-top: 20px;
        margin-left: 20px;
        position: reelative;
    }

    .side_menu a {
        border-bottom: 1px solid #f5f5f5;
        text-align: center;
        font-size: 32px;
        margin-top: 12px;
        padding-bottom: 12px;
    }

    .maker {
        background: white;
        border-radius: 20px;
        padding: 20px;
        height: 260px;
        margin-top: 32px;
    }

    .maker_detail {
        display: flex;
        justify-content: space-between;
    }

    .maker_detail_1 {
        flex: 1.5;
    }

    .maker_detail_2 {
        flex: 1;
    }

    .maker_name {
        font-size: 40px;
        margin-bottom: 50px;
    }

    .product {
        font-size: 24px;
        margin-bottom: 10px;
        margin-top: 24px;
    }

    .t_date {
        font-size: 20px;
    }


    .maker_detail_2 .material-icons {
        font-size: 120px;
        opacity: 0.6;
    }

    .timer{
        position: absolute;
        bottom:0; right:0;
        display:flex;
        font-size: 22px;
        margin:40px
    }
</style>

<div class="mbody">
    <div class="bdcont_100">
        <div class="bc__box100">
            <nav class="m_nav">
                <a style="color:#009687" class="tlink" href="<?php echo base_url('tablet/o1') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon1.png"); ?>" width="120">
                        <div>성형</div>
                    </div>
                </a>
                </a>
                <a style="color:#3a72dd" class="tlink" href="<?php echo base_url('tablet/o2') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon2.png"); ?>" width="120">
                        <div>정형</div>
                    </div>
                </a>
                </a>
                <a style="color:#bc845f" class="tlink" href="<?php echo base_url('tablet/o3') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon3.png"); ?>" width="120">
                        <div>시유</div>
                    </div>
                </a>
                </a>
                <a style="color:#f8554c" class="tlink" href="<?php echo base_url('tablet/o4') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon4.png"); ?>" width="120">
                        <div>선별</div>
                    </div>
                </a>
                </a>
            </nav>
            <div class="side_menu">
                <div class="maker">
                    <div class="maker_name">작업자</div>
                    <div class="maker_detail">
                        <div class="maker_detail_1">
                            <div class="product">생산관리</div>
                            <div class="t_date">20<?php echo $today ?></div>
                        </div>
                        <div class="maker_detail_2">
                            <span class="material-icons">
                                assignment_ind
                            </span>
                        </div>
                    </div>
                </div>
                <!-- <a style="color:black" href="tablet/o1">성형</a>
                <a style="color:black" href="tablet/o2">정형</a>
                <a style="color:black" href="tablet/o3">시유</a>
                <a style="color:black" href="tablet/o4">선별</a> -->
                <div class='timer'>
                <p style="padding-right:15px">새로고침</p>
                <select name="timer" id="timer" style="width:150px;">
                    <option value="" <?= ($timer[0]->REMARK == "")?"selected":"" ?>>사용안함</option>
                    <option value="10" <?= ($timer[0]->REMARK == "10")?"selected":"" ?>>10초</option>
                    <option value="20" <?= ($timer[0]->REMARK == "20")?"selected":"" ?>>20초</option>
                    <option value="30" <?= ($timer[0]->REMARK == "30")?"selected":"" ?>>30초</option>
                    <option value="40" <?= ($timer[0]->REMARK == "40")?"selected":"" ?>>40초</option>
                    <option value="50" <?= ($timer[0]->REMARK == "50")?"selected":"" ?>>50초</option>
                    <option value="60" <?= ($timer[0]->REMARK == "60")?"selected":"" ?>>60초</option>
                    <option value="120" <?= ($timer[0]->REMARK == "120")?"selected":"" ?>>2분</option>
                    <option value="180" <?= ($timer[0]->REMARK == "180")?"selected":"" ?>>3분</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("select[name='timer']").on("change",function(){
        console.log(123)
        var time = $(this).val();
        $.post("<?php echo base_url('tablet/timer_update')?>",{time:time});
    });
</script>