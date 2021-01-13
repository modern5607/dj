<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>

<h2>
	<?php echo $title;?>
	<span class="material-icons close">clear</span>
</h2>



<div class="formContainer">
	<div style="background:#eceff5; padding:20px;">
		<select name="s1" id="s1" class="form_input select_call" style="width:120px;">
			<option value="">시리즈</option>
			<?php foreach($SERIES as $s_h){ ?>
			<option value="<?php echo $s_h->IDX;?>"><?php echo $s_h->SERIES_NM;?></option>
			<?php } ?>
		</select>
		<!--select name="s2" id="s2" class="form_input select_call" style="width:120px;">
			<option value="">품명</option>
		</select>
		<select name="s3" id="s3" class="form_input select_call" style="width:120px;">
			<option value="">색상</option>
		</select-->
		<button class="sh_submit"><i class="material-icons">search</i></button>
	</div>
	<form name="ajaxform" id="ajaxform">
		<input type="hidden" name="mod" value="<?php echo $mode?>">
		<div class="register_form">
			
			
			
			<fieldset class="form_3">
				
				<table>
					<thead>
						<tr>
							<th>No</th>
							<th>품명</th>
							<th>수량</th>
							<th>비고</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="5" style="text-align:center; color:#999;"> 시리즈를 선택하세요 </td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			
		</div>
		<div class="bcont" style="padding:15px 0; text-align:center;">
			<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif');?>' width="100"></span>
			<?php
			if(isset($data)){ //수정인경우
			?>
			<button type="button" class="modBtn blue_btn">수정</button>
			<input type="hidden" name="IDX" value="<?php echo $data->IDX; ?>">
			<?php
			}else{	
			?>
			<button type="button" class="submitBtn blue_btn">입력</button>
			<?php
			}
			?>
		</div>

	</form>

</div>




<script type="text/javascript">
<!--
var HIDX = "<?php echo $HIDX ?>"; //수주idx
$("#s1").on("change",function(){
	var idx = $(this).val();
	$.post("<?php echo base_url('PLN/ajax_set_series_select')?>",{idx:idx},function(data){
		//if(data.items.length > 0){
			$("#s2 option.xxx").remove();
			$("#s3 option.xxx").remove();
			$.each(data.items, function(key, val){
				$("#s2").append("<option value='"+val.IDX+"' class='xxx'>"+val.ITEM_NAME+"</option>");
			});
			$.each(data.sdetail, function(key, val){
				$("#s3").append("<option value='"+val.IDX+"' class='xxx'>"+val.COLOR+"</option>");
			});
		//}
	},"JSON");
});


$(".sh_submit").on("click",function(){
	var s1 = $("#s1").val();
	
	$.ajax({
		url: "<?php echo base_url('ACT/ajax_itemsindex_pop')?>",
		type: "POST",
		datatype: "JSON",
		data:{s1:s1},
		success: function(data){
			
			var dataset = JSON.parse(data);
			var html = "";
			if(dataset.length > 0){
				$.each(dataset, function(index, info){
					
					html += "<tr>";
					html += "<td>"+(index+1)+"</td>";
					html += "<td>"+info.ITEM_NAME+"</td>";
					//html += "<td>"+info.COLOR+"</td>";
					html += "<td>";
					html += "	<input type='text' name='QTY[]' class='form_select' size='4' value='' />";
					html += "	<input type='hidden' name='ITEM_IDX[]' value='"+info.IDX+"' />";
					html += "	<input type='hidden' name='ITEM_NM[]' value='"+info.ITEM_NAME+"' />";
					//html += "	<input type='hidden' name='SERIESD_IDX[]' value='"+info.SERIESD_IDX+"' />";
					//html += "	<input type='hidden' name='H_IDX[]' value='"+HIDX+"' />";				
					html += "</td>";
					html += "<td><input type='text' name='REMARK[]' class='form_select' value='' /></td>";
					html += "</tr>";
					
				});
			}else{
				html += "<tr><td colspan='4' style='text-align:center; color:#999;'>품목이 없습니다.</td></tr>"
			}
			$(".form_3 table tbody").html(html);
		}

	});

});


$(".submitBtn").on("click",function(){

	var formData = new FormData($("#ajaxform")[0]);
	var $this = $(this);

	

	$.ajax({
		url  : "<?php echo base_url('ACT/ajax_act_items_trans_insert')?>",
		type : "POST",
		data : formData,
		//asynsc : true,
		cache  : false,
		contentType : false,
		processData : false,
		beforeSend  : function(){
			$this.hide();
			$("#loading").show();
		},
		success : function(data){

			var jsonData = JSON.parse(data);
			if(jsonData.status == "ok"){
			
				setTimeout(function(){
					alert(jsonData.msg);
					$(".ajaxContent").html('');
					$("#pop_container").fadeOut();
					$(".info_content").css("top","-50%");
					$("#loading").hide();
					location.reload();

				},1000);

				chkHeadCode = false;

			}
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});
});





$("input[name='ACT_DATE'],#DEL_DATE").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});





//-->
</script>