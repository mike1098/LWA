<?php

$db = new PDO('sqlite:./db/HouseKeeper.db');

$val= array();
$dateTime= array();
$title = "Solarkollektor";
$count="";

//$result = $db->query("select S_Value,strftime('%s',Date_Time) AS Date_Time from S_Values 
//	where Date_Time>=DATETIME('NOW','localtime','-6 hours') 
//	and Sensor_ID=2");

$result = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-30 days') and Sensor_ID=2");

$result1 = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-30 days') and Sensor_ID=7");

//SQL Date is in form of: 2012-12-26 14:17:51
//      Javascript needs: Wed Dec 26 2012 15:56:56 GMT+0100 (CET) 

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
  
  var epoche= new Number;
  epoche = chartData[0].Date_Time*1000;
  document.write(epoche);
  var myDate=new Date(epoche);
  document.write(myDate);
    

//for (var x in chartData) {
//  document.write(chartData[x]);
//  document.write(';');
// }
    
  window.onload = function () {

      var chart = new AmCharts.AmSerialChart();
	chart.pathToImages = "http://www.amcharts.com/lib/images/";
        chart.dataProvider = chartData;
        chart.categoryField = "Date_Time";
        chart.marginTop = 30;
        chart.marginLeft = 55;
        chart.marginRight = 15;
        chart.marginBottom = 80;
        //chart.angle = 0;
        //chart.depth3D = 0;
        //chart.categoryField = "year";
        chart.zoomOutButton = {
            backgroundColor: '#FF0000',
            backgroundAlpha: 0.3
        };
//################# Axes #########################
//################# Category #####################
      var catAxis = chart.categoryAxis;
        catAxis.gridCount = chartData.length;
        catAxis.labelRotation = 90;
	//catAxis.parseDates = true;
	//catAxis.minPeriod = "YYYY";
        catAxis.dashLength = 1;
	chart.autoMarginOffset = 5;
	catAxis.axisColor = "#DADADA";

//############# Value ############################
    var valueAxis = new AmCharts.ValueAxis();
	valueAxis.axisAlpha = 0;
	valueAxis.dashLength = 1;
	valueAxis.inside = false;
	chart.addValueAxis(valueAxis);
//############# Graph ############################ 
      var graph = new AmCharts.AmGraph();
	graph.title = "red line";
	graph.lineColor = "#b5030d";
	graph.negativeLineColor = "#0352b5";
	graph.bullet = "round";
	graph.bulletSize = 5;
	graph.hideBulletsCount = 50;
	graph.connect = false;
         graph.balloonText = "[[Date_Time]]: [[S_Value]]";
         graph.valueField = "S_Value"
         //graph.type = "line";
         //graph.lineAlpha = 0;
	graph.lineThickness = 1;
         graph.fillAlphas = 0.2;

	var graph1  = new AmCharts.AmGraph();

//################ Scrollbar ########################
 // SCROLLBAR
    var chartScrollbar = new AmCharts.ChartScrollbar();
    chartScrollbar.graph = graph;
    chartScrollbar.scrollbarHeight = 40;
    chartScrollbar.color = "#b5030d";
     chartScrollbar.graphFillColor = "#b5030d";
     chartScrollbar.labelRotation = 90;	
    chartScrollbar.autoGridCount = true;
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
    legend.labelText = "Solar Panel";
    chart.addLegend(legend);
         
	chart.addGraph(graph);
	chart.write('chartContainer');
   }
</script>


</head>
<body>
<div id=main>
<div id="navi">
	<ul class="navi">
		<li class="navi"><a href="./index.php">Home</a></li>
		<li class="navi"><a href="./dwd.html">Wetter</a></li>
		<li class="navi"><a href="./solar.php">Heizung</a></li>
		<li class="navi"><a href="./about.html">About</a></li>
	</ul>
</div>
      <div id="chartContainer" style="width: 640px; height: 400px;"></div>
</div>
</body>


</html>

