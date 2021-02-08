<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">

<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>


<h2>
	클레임 등록
	<span class="material-icons close">clear</span>
</h2>



<div class="formContainer">
	
	<form name="insertclaim" id="insertclaim">
        <input type="hidden" name="idx" value="<?php echo $itemInfo->IDX;?>">
        <input type="hidden" name="upd" value="<?php echo !empty($itemInfo->CIDX)?"1":"" ;?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table class="nhover">
					<tbody>
                        <tr>
							<th>품명</th>
							<td colspan="3">
								 <?php echo $itemInfo->ITEM_NM;?>
                            </td>
						</tr>
                        <tr>
							<th>색상</th>
							<td colspan="3">
								 <?php echo $itemInfo->COLOR;?>
                            </td>
						</tr>
                        <tr>
							<th>출고수량</th>
							<td colspan="3">
								 <?php echo number_format($itemInfo->OUT_QTY);?> 개
                            </td>
						</tr>
                        <tr>
                            <th>클레임사유</th>
                            <td colspan="3">
                                <textarea name="remark" style="resize: none; " cols="70" rows="7"><?php echo $itemInfo->CLAIM;?></textarea>
                            </td>
                        </tr>
					</tbody>
				</table>
			</fieldset>
			
			<div class="bcont">
				<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif');?>' width="100"></span>
				
				<button type="button" class="submitBtn blue_btn">입력</button>
				
			</div>
			
		</div>

	</form>

</div>


<script>
$(document).on("click","h2 > span.close",function(){

//$(".ajaxContent").html('');
$("#pop_container").fadeOut();
$(".info_content").css("top","-50%");

});

$(".submitBtn").on("click", function() {
var claim = $('textarea').val();
var upd = $('input[name="upd"]').val();

if(claim == "" && upd != 1){
    alert("클레임 사유를 입력하세요.");
    $('textarea').focus();
    return false;
}

var formData = new FormData($("#insertclaim")[0]);
var $this = $(this);

$.ajax({
    url: "<?php echo base_url('AMT/insert_claim')?>",
    type: "POST",
    data: formData,
    //asynsc : true,
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function() {
        $this.hide();
        $("#loading").show();
    },
    success: function(data) {
        setTimeout(function() {
            alert('클레임이 등록되었습니다.');
            $(".ajaxContent").html('');
            $("#pop_container").fadeOut();
            $(".info_content").css("top", "-50%");
            $("#loading").hide();
            location.reload();
            chkHeadCode = false;
        },1000)
        
    },
    error: function(xhr, textStatus, errorThrown) {
        alert(xhr);
        alert(textStatus);
        alert(errorThrown);
    }
});
});
</script>