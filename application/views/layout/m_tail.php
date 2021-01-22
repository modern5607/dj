
</div>


<script>
	$("#mback_close").on("click",function(){
		$("#m_menu").animate({
			right:"-200px"
		},function(){
			$("#m_back").fadeOut();	
		});
	});
	
	
	$(".mheader .right").on("click",function(){
		$("#m_back").fadeIn();	
		$("#m_menu").animate({
			right:"0px"
		});
	});

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

</script>


</body>
</html>