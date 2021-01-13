
</div>
</div>

<script>


$(".menu01_li").on("click",function(){
	
	$(".menu01_li").find(".menu_a").removeClass("on");
	$(".menu01_li").find("ul").css("display","none");

	$(this).find(".menu_a").addClass("on");
	$(this).find("ul").css("display","block");
});


</script>


</body>
</html>