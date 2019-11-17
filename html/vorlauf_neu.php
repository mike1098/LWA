<?php

$db = new PDO('sqlite:./db/HouseKeeper.db');

$val= array();
$dateTime= array();
$title = "Vorlauftemperatur";
$count="";

//$result = $db->query("select S_Value,strftime('%s',Date_Time) AS Date_Time from S_Values 
//where Date_Time>=DATETIME('NOW','localtime','-6 hours') 
//and Sensor_ID=2");

$result = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-1 days') and Sensor_ID=1");

$result1 = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-1 days') and Sensor_ID=10");

//SQL Date is in form of: 2012-12-26 14:17:51
//Javascript needs: Wed Dec 26 2012 15:56:56 GMT+0100 (CET) 

foreach ($result as $row)
{
	$row["S_Value"] = $row["S_Value"]/10;
	//$row["Date_Time"] =
	
	$val[] = $row;
}

foreach ($result1 as $row)
{
	$row["S_Value"] = $row["S_Value"]/10;
	//$row["Date_Time"] =
	$val1[] = $row;
}

$db = NULL;

?>

<html>
<head>
	
	<style type="text/css"></style>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
	<script src="./lib/amcharts/amcharts.js" type="text/javascript"></script>


<script type="text/javascript">
    
  var chartData = <?php echo json_encode($val); ?>;
  var chartData1 = <?php echo json_encode($val1); ?>;
    
  window.onload = function () {
//####################### Create Chart ####################################
    var chart = new AmCharts.AmSerialChart();
	  chart.pathToImages = "http://www.amcharts.com/lib/images/";
      chart.dataProvider = chartData1;
      chart.categoryField = "Date_Time";
      chart.marginTop = 0;
	  chart.autoMarginOffset = 5;
      chart.zoomOutButton = {
        backgroundColor: '#000000',
        backgroundAlpha: 0.15
        };
		
//################# Axes #########################
//################# Category #####################
    var catAxis = chart.categoryAxis;
      catAxis.gridCount = chartData1.length;
      catAxis.labelRotation = 90;
      catAxis.dashLength = 2;
	  categoryAxis.gridAlpha = 0.15;
	  catAxis.axisColor = "#DADADA";

//############# Value ############################
    var valueAxis1 = new AmCharts.ValueAxis();
	  valueAxis1.axisColor = "#FF6600";
	  valueAxis1.axisThickness = 2;
	  valueAxis1.gridAlpha = 0;
	  chart.addValueAxis(valueAxis1);
	  
//############# Graph Vorlauf Ist############################ 
    var graph1 = new AmCharts.AmGraph();
	  graph1.valueAxis = valueAxis1;
	  graph1.title = "Vorlauf ist";
	  graph1.bullet = "triangleUp"; // Could be round,square,triangleUp
	  graph1.hideBulletsCount = 500;
	  chart.addGraph(graph1);
	  
//################ Scrollbar ########################
    var chartScrollbar = new AmCharts.ChartScrollbar();
      chart.addChartScrollbar(chartScrollbar);

//################ Cursor ###########################
    var chartCursor = new AmCharts.ChartCursor();
	  chartCursor.cursorPosition = "mouse";
	  chart.addChartCursor(chartCursor);

//################ LEGEND #############################
    var legend = new AmCharts.AmLegend();
      legend.marginLeft = 110;
      chart.addLegend(legend);
	
//############## Write Chart ##########################
	chart.write('chartContainer');
}
</script>


</head>
<body>
<div id=main>
<div id="navi">
	<ul class="navi">
		<li class="navi"><a class="navi" href="./index.php">Home</a></li>
		<li class="navi"><a class="navi" href="./dwd.html">Wetter</a></li>
		<li class="navi"><a class="navi" href="./solar.php">Heizung</a></li>
		<li class="navi"><a class="navi" href="./about.html">About</a></li>
	</ul>
</div>
      <div id="chartContainer" style="width: 1600px; height: 800px;"></div>
</div>
</body>


</html>

