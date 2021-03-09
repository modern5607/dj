<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- <div id="pageTitle">
<h1><?php echo $title;?></h1>
</div> -->


<!DOCTYPE html>
<html>
  <head>
	<title>Insert title here</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   
    <meta charset="utf-8">
    <title>Line_Controls_Chart</title>
    
	<style type ="text/css">
       #mid_content{
       margin:10px;
	   vertical-align: top;
       }

	   .kpimean{
			float:right; font-size:15px;  display:flex;
			border:3px solid #414350;
			background:#fff
		}
		.kpimean>p:last-child{
			border-right:0;
		}
		.kpimean>p{
			margin:5px 0;
			padding:0 5px;
			border-right:1px solid #aaa;
			color:#333;
		}
    </style>
    
 
    <!-- jQuery -->
		<script src="https://code.jquery.com/jquery.min.js"></script>
    <!-- google charts -->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
		<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
		<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
		<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
		<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>
		
  </head>
	<body>
	         <div id = "mid_content" style="border:2px solid gray;">
				<div style="width: 100%; background:#8C8C8C; color: #fff;">
					<div class="bc__box100">
						<header class="bc_search gsflexst">
							<div style="float:left">
								<form id="items_formupdate">
									<label style="color:#fff" for="">일자</label>
			<input type="text" name="sdate" id="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']:date("Y",time())?>" style="width:70px;" data-eweek="<?=$str['eweek']?>">
			<select name="sweek" id="sweek" style="width:70px; padding:5px 7px">
			<?php 
				for($i=1; $i<=12; $i=$i+1)
				{
					$selected = ($str['sweek'] == sprintf('%02d',$i))?"selected":"";
					echo $i."<option ".$selected." value='".sprintf('%02d',$i)."'>".$i."월</option> <br />";
				}
			?>
			</select>~
			<select name="eweek" id="eweek" style="width:70px; padding:5px 7px">
			<?php 
				for($i=1; $i<=12; $i=$i+1)
				{
					$selected = ($str['eweek'] == sprintf('%02d',$i))?"selected":"";
					echo $i."<option ".$selected." value='".sprintf('%02d',$i)."'>".$i."월</option> <br />";
				}
			?>
			</select>

									<button class="search_submit"><i class="material-icons">search</i></button>
								</form>
							</div>
							
							<div class="kpimean">
								<p>목표 	: <?= $PP[1]->D_NAME ?> %</p>
								<p>구축 전	: <?= $PP[0]->D_NAME ?> %</p>
								<p>구축 후 	: <?= $PP[2]->D_NAME ?> %</p>
							</div>
						</header>		 
					</div>
				</div>
                 <!-- 라인 차트 생성할 영역 -->
                     <div id="lineChartArea" style="height:80%; padding:20px;"></div>
            </div>

    	</body>
	<script>


		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawVisualization);

		function drawVisualization() { 

			 var data = new google.visualization.DataTable();
			 var chartDateformat     = 'MM-dd';
			 var chartLineCount    = <?php echo $this->data['cnt']; ?>;

			 
			  //그래프에 표시할 컬럼 추가
			  data.addColumn('string' 	, '날짜');
			  data.addColumn('number'   , '목표');
			  data.addColumn('number'   , '구축 전');
			  data.addColumn('number'   , '구축 후');

			  //그래프에 표시할 데이터
				
			  var dataRow  = [];
	 
				<?php
					foreach($List as $row){
				?>
					var year  = "<?php echo $row->YE; ?>";
					var month = "<?php echo $row->MO; ?>";
					var week  = "<?php echo $row->WE; ?>주";

					var mp 	= <?= $PP[1]->D_NAME ?>;
					var old = <?= $PP[0]->D_NAME ?>;
					var ac  = <?php echo round($row->AC_KPI,1); ?>;


					dataRow = [''+year+'-'+month+'-'+week+'', mp, old, ac];
					data.addRow(dataRow);
	
				<?php
					}
				?>

			var options = {
					//title : '스마트공장 KPI 제조리드타임',
					focusTarget   : 'category',
					width: '100%', height: 700, 
					chartArea : {'width': '75%','height' : '80%'},
					tooltip       : {textStyle : {fontSize:12}, showColorCode : true,trigger: 'both'},
					vAxis: {ticks:[1,2,3,4,5,6,7,8,9,10]},
					hAxis: {title: '스마트공장 KPI 전기에너지 절감율', 
							format: chartDateformat,
							// slantedText: true, slantedTextAngle: -90, 
							// gridlines:{count:chartLineCount}, 
							textStyle: {fontSize:12}
							},
					seriesType: 'bars',
					series: {0:{color: '#acf', visibleInLegend: true, type:'steppedArea'},
			 				 1:{color: 'gray', visibleInLegend: true, type:'steppedArea'},
            				 2:{color: 'red', visibleInLegend: true, type:'bar', pointSize:8}},
				};
			
			var chart = new google.visualization.ComboChart(document.getElementById('lineChartArea'));
			chart.draw(data, options);
		}

$(document).on("change","#sweek",function(){
	var sweek = $(this).val();
	$('#eweek option').prop('disabled',false);
		for(var i=0; i <= 11; i++){
			var eweek = $('#eweek option').eq(i).val();
			if(eweek < sweek){
				$('#eweek option').eq(i).prop('disabled',true);
			}
		}
	}
)

$(document).on("change","#eweek",function(){
	var eweek = $(this).val();
	$('#sweek option').prop('disabled',false);
		for(var i=0; i <= 11; i++){
			var sweek = $('#sweek option').eq(i).val();
			if(eweek < sweek){
				$('#sweek option').eq(i).prop('disabled',true);
			}
		}
	}
)

window.onload = function(){
	if($("#sdate").data('eweek') == ""){
		$('#eweek option').eq(1).attr("selected","selected");
	}else{

	}

	var eweek1 = $('#eweek option:selected').val();
	for(var i=0; i <= 11; i++){
		var sweek1 = $('#sweek option').eq(i).val();
		if(eweek1 < sweek1){
			$('#sweek option').eq(i).prop('disabled',true);
		}
	};

	var sweek2 = $('#sweek option:selected').val();
	for(var i=0; i <= 11; i++){
		var eweek2 = $('#eweek option').eq(i).val();
		if(eweek2 < sweek2){
			$('#eweek option').eq(i).prop('disabled',true);
		}
	};
}
</script>
</html>