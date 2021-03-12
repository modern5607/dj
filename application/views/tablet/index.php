<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<style>
    .tlink {
        display: flex;
        flex-direction: column;
        text-align: center;
        height: 190px;
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
        height: 190px;
        width: 210px;
        font-size: 24px;
        -webkit-box-shadow: 6px 10px 10px -8px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 6px 10px 10px -8px rgba(0, 0, 0, 0.75);
        box-shadow: 6px 10px 10px -8px rgba(0, 0, 0, 0.75);
    }

    .bdcont_100 {
        height: 100vh;
    }

    .bc__box100 {
        display: flex;
        height: 100%;
        padding: 40px;
    }

    .m_nav {
        display: flex;
        justify-content: space-around;
        width: 80%;
        margin-top: 10%;
    }

    .side_menu {
        background: white;
        width: 20%;
        display: flex;
        flex-direction: column;
        padding-top: 20px;
    }

    .side_menu a {
        border-bottom: 1px solid #f5f5f5;
        text-align: center;
        font-size: 32px;
        margin-top: 12px;
        padding-bottom: 12px;
    }
</style>

<div class="mbody">
    <div class="bdcont_100">
        <div class="bc__box100">
            <nav class="m_nav">
                <a style="color:#009687" class="tlink" href="<?php echo base_url('tablet/o1') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon1.png"); ?>" width="140">
                        <div>성형</div>
                    </div>
                </a>
                </a>
                <a style="color:#3a72dd" class="tlink" href="<?php echo base_url('tablet/o2') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon2.png"); ?>" width="140">
                        <div>정형</div>
                    </div>
                </a>
                </a>
                <a style="color:#bc845f" class="tlink" href="<?php echo base_url('tablet/o3') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon3.png"); ?>" width="140">
                        <div>시유</div>
                    </div>
                </a>
                </a>
                <a style="color:#f8554c" class="tlink" href="<?php echo base_url('tablet/o4') ?>">
                    <div class="tlink_column"><img src="<?php echo base_url("_static/img/djicon4.png"); ?>" width="140">
                        <div>선별</div>
                    </div>
                </a>
                </a>
            </nav>
            <div class="side_menu">
                <a style="color:black" href="tablet/o1">성형</a>
                <a style="color:black" href="tablet/o2">정형</a>
                <a style="color:black" href="tablet/o3">시유</a>
                <a style="color:black" href="tablet/o4">선별</a>
            </div>
        </div>
    </div>
</div>