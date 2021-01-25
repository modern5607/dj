
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
$("input"). attr("autocomplete", "off"); 

//pageignation
$(".limitset select").on("change", function() {
    var qstr = "<?php echo $qstr ?>";
    location.href = "<?php echo base_url($pos."/".$subpos)?>" + qstr + "&perpage=" + $(this).val();
});

</script>


</body>
</html>