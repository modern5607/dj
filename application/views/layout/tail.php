
</div>
</div>

<script>
$(".mhide").on("click",function(){
	
	
	$(".menu_Content").animate({
		width:'0'
	},function(){
		$(".mhide").addClass('mshow');
		$(".mhide span").text("keyboard_arrow_right");
	});

	$(".body_").animate({
		marginLeft:'0px'
	},function(){
		$(".mControl_show").show();
	});

	$(".body_Header").animate({
		paddingLeft:'65px'
	});

	

});



$(".mshow").on("click",function(){
	
	

		$(".menu_Content").animate({
			width:'220px'
		},function(){
			$(".mhide").removeClass('mshow');
			$(".mhide span").text("keyboard_arrow_left");
		});
		$(".body_").animate({
		marginLeft:'220px'
		});


		$(".mControl_show").hide();
	

});

$(".menu01_li").on("click",function(){
	
	$(".menu01_li").find(".menu_a").removeClass("on");
	$(".menu01_li").find("ul").css("display","none");

	$(this).find(".menu_a").addClass("on");
	$(this).find("ul").css("display","block");
});

//input autocoamplete off
$("input").attr("autocomplete", "off");
//pageignation
$(".limitset select").on("change", function() {
    var qstr = "<?= empty($qstr)?"":$qstr; ?>";
    location.href = "<?php echo base_url($pos."/".$subpos)?>" + qstr + "&perpage=" + $(this).val();
});

$(".calendar").on("change", function() {
	var pln1 = $("input[name='pln1']").val();
	var pln2 = $("input[name='pln2']").val();
	var insert1 = $("input[name='insert1']").val();
	var insert2 = $("input[name='insert2']").val();
	var st1 = $("input[name='st1']").val();
	var st2 = $("input[name='st2']").val();
	var sdate = $("input[name='sdate']").val();
	var edate = $("input[name='edate']").val();

	if(pln1 > pln2 || st1 > st2 || sdate > edate || insert1 > insert2){
		$("input[name='pln1']").val(pln2);
		$("input[name='st1']").val(st2);
		$("input[name='sdate']").val(edate);
		alert("검색 시작일을 수정했습니다.")
	}
});
</script>


</body>
</html>