
</div>
</div>

<script>


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