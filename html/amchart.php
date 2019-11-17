<?php

$db = new PDO('sqlite:./db/HouseKeeper.db');

$val= array();
$dateTime= array();
$title = "Solarkollektor";
$count="";

//$result = $db->query("select S_Value,strftime('%s',Date_Time) AS Date_Time from S_Values 
//	where Date_Time>=DATETIME('NOW','localtime','-6 hours') 
//	and Sensor_ID=2");

$result = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-6 hours') and Sensor_ID=1");

$result1 = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-6 hours') and Sensor_ID=7");

//SQL Date is in form of: 2012-12-26 14:17:51
//      Javascript needs: Wed Dec 26 2012 15:56:56 GMT+0100 (CET) 

foreach ($result as $row)
{
	$row["S_Value"] = $row["S_Value"]/10;
	$row["S_Value"] = $row["S_Value"]-2;
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
//################### Define Chart #############################
    var chart = new AmCharts.AmSerialChart();
	  chart.pathToImages = "http://www.amcharts.com/lib/images/";
      chart.dataProvider = chartData;
      chart.categoryField = "Date_Time";
      chart.marginTop = 20;
        
      chart.zoomOutButton = {
        backgroundColor: '#000000',
        backgroundAlpha: 0.15
      };
//################# Axes #########################
//################# Category #####################
    var catAxis = chart.categoryAxis;
      catAxis.gridCount = chartData.length;
      catAxis.labelRotation = 90;
      catAxis.dashLength = 2;
	  chart.autoMarginOffset = 5;
	  catAxis.axisColor = "#DADADA";

//############# Value ############################
    var valueAxis1 = new AmCharts.ValueAxis();
	  valueAxis1.axisColor = "#FF6600";
	  valueAxis1.axisThickness = 2;
      valueAxis1.gridAlpha = 0;
    chart.addValueAxis(valueAxis1);
	
//############# Graph 1 ############################ 
    var graph1 = new AmCharts.AmGraph();
	  graph1.title = "red line";
	  graph1.lineColor = "#FF6600";
	  graph1.negativeLineColor = "#efcc26";
	  graph1.bullet = "round";
	  graph1.bulletSize = 5;
	  graph1.hideBulletsCount = 50;
	  graph1.connect = false;
      graph1.balloonText = "Date: [[Date_Time]]: Temperature: [[S_Value]]";
      graph1.valueField = "S_Value"   
	  graph1.lineThickness = 2;
	  chart.addGraph(graph1);
	
//############# Graph 2 ############################ 
    var graph2 = new AmCharts.AmGraph();
	  graph2.title = "red line";
	  graph2.lineColor = "#FF6600";
	  graph2.negativeLineColor = "#efcc26";
	  graph2.bullet = "round";
	  graph2.bulletSize = 5;
	  graph2.hideBulletsCount = 50;
	  graph2.connect = false;
      graph2.balloonText = "Date: [[Date_Time]]: Vorlauf: [[Vorlauf]]";
      graph2.valueField = "Vorlauf"   
	  graph2.lineThickness = 2;
	  chart.addGraph(graph2);
	
	
//################ Scrollbar ########################
 // SCROLLBAR
    var chartScrollbar = new AmCharts.ChartScrollbar();
      chartScrollbar.graph = graph1;
      chartScrollbar.scrollbarHeight = 40;
      chart.addChartScrollbar(chartScrollbar);

//################ Cursor ###########################
    var chartCursor = new AmCharts.ChartCursor();
	  chartCursor.cursorAlpha = 0;
	  chartCursor.cursorPosition = "mouse";
	  chartCursor.categoryBalloonDateFormat = "YYYY";
	  chart.addChartCursor(chartCursor);

//################ LEGEND #############################
    var legend = new AmCharts.AmLegend();
      legend.marginLeft = 110;
      legend.labelText = "Aussentemperatur";
      chart.addLegend(legend);
	
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
      <div id="chartContainer" style="width: 100%; height: 600px;"></div>
</div>
</body>


</html>

