<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<div class="body_cont_float2">
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
                            <td><span data-idx='<?php echo $row->IDX;?>' class="link_s1 add_itemnum"><?php echo $row->ITEM_NM;?></span></td>
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


</div>




<div id="pop_container">

    <div class="info_content" style="height:auto;">
        <div class="ajaxContent">

            <!-- 데이터 -->

        </div>
    </div>

</div>



<script type="text/javascript">
$(".add_itemnum").on("click", function() {

    var idx = $(this).data("idx");
    $(".ajaxContent").html('');

    $("#pop_container").fadeIn();
    $(".info_content").animate({
        top: "50%"
    }, 500);

    $.ajax({
        url: "<?php echo base_url('tablet/ajax_invenNum_form')?>",
        type: "POST",
        dataType: "HTML",
        data: {
            idx:idx
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