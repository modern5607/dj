<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="body_cont_float2">
    <ul>
        <li style="width:430px">

            <div id="" class="bc_search">
                <form>
                    <input type='hidden' name='n' value='1' />
                    <label for="sdate">날짜</label>
                    <input type="text" name="sdate" class="sdate calendar"
                        value="<?php echo (!empty($str['sdate']) && $str['sdate'] != "") ? $str['sdate'] : date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y"))); ?>"
                        size="12" /> ~

                    <input type="text" name="edate" class="edate calendar"
                        value="<?php echo (!empty($str['edate']) && $str['edate'] != "") ? $str['edate'] : date("Y-m-d"); ?>"
                        size="12" />

                    <button class="search_submit"><i class="material-icons">search</i></button>
                </form>
            </div>

            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>날짜</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($daylist)) {
                            foreach ($daylist as $i => $row) {
                                $no = $pageNum + $i + 1;
                        ?>

                        <tr>
                            <td class="cen"><?php echo $no; ?></td>
                            <td class="cen mlink ajax_date" data-date="<?= date("Y-m-d",strtotime($row->DATE))?>">
                                <?= date("Y-m-d",strtotime($row->DATE))?></td>
                        </tr>

                        <?php
                            }
                        } else {
                            ?>
                        <tr>
                            <td colspan="6" style='color:#999; padding:40px 0;'>등록된 실적정보가 없습니다.</td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                </table>

            </div>
            <div class="pagination">
                <?php echo $this->data['pagenation']; ?>
                <?php
                if ($this->data['cnt'] > 20) {
                ?>
                <div class="limitset">
                    <select name="per_page">
                        <option value="20" <?php echo ($perpage == 20) ? "selected" : ""; ?>>20</option>
                        <option value="50" <?php echo ($perpage == 50) ? "selected" : ""; ?>>50</option>
                        <option value="80" <?php echo ($perpage == 80) ? "selected" : ""; ?>>80</option>
                        <option value="100" <?php echo ($perpage == 100) ? "selected" : ""; ?>>100</option>
                    </select>
                </div>
                <?php
                }
                ?>
            </div>

        </li>

        <li style="width:calc(100% - 430px);">

        <div id="ajax_env">
        </div>
        </li>

    </ul>
</div>


<script>
google.charts.load('current', {
    packages: ['corechart', 'line']
});

$(document).on("click", ".ajax_date", function() {
    var date = $(this).data("date");
    console.log(date);
    $(".over").removeClass("over");
    $(this).parent().addClass("over");

    ajax_container(date);
});

function ajax_container(date) {
    console.log("asdf");
    $.ajax({
        url: "<?php echo base_url('monitor/monitor_ajax_envs')?>",
        type: "post",
        data: {
            date: date
        },
        dataType: "html",
        success: function(data) {
            $("#ajax_env").html(data);
        }
    });

}
</script>