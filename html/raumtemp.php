<?php
$db = new PDO('sqlite:./db/HouseKeeper.db');
$val= array();
$dateTime= array();
$title = "Solarkollektor";
$count="";
$isNeg=0;

// Fetch the values and dates from database
$result = $db->query("select S_Value,strftime('%H:%M', Date_Time) from S_Values where Date_Time>=DATETIME('NOW','localtime','-12 hours') and Sensor_ID=3");
$result1 = $db->query("select S_Value,strftime('%H:%M', Date_Time) from S_Values where Date_Time>=DATETIME('NOW','localtime','-12 hours') and Sensor_ID=1");

foreach ($result as $row)
{
  if ($row["S_Value"]/10-2 < 0) $isNeg=1;
  $val[] = $row["S_Value"]/10-2;
  $dateTime[] = $row["strftime('%H:%M', Date_Time)"] . " Uhr"; 
  $count++;
}

foreach ($result1 as $row)
{
  if ($row["S_Value"]/10-2 < 0) $isNeg=1;
  $val1[] = $row["S_Value"]/10-2;
}

// For RGraph we need everthing in one string
$val_string = "[" . join(",", $val) . "]";
$val_string1 = "[" . join(",", $val1) . "]";
//echo $val_string ."". $val_string1;
$val_string = $val_string .",". $val_string1;
//$dateTime_string = "['" . join("','", $dateTime) . "']";
$dateTime_string = "['" .$dateTime[1]."','". $dateTime[$count/2]."','".array_pop($dateTime)."']";
//echo $dateTime_string;
//echo "'" .$dateTime[1]." - ".$dateTime[2]."'";

//Close the DB connection
$db = NULL;

?>

<html>
<head>
	
	<style type="text/css"></style>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
    <!-- Don't forget to update these paths -->

<script src="libraries/RGraph.common.core.js" ></script>
<script src="libraries/RGraph.line.js" ></script>
<script src="libraries/RGraph.common.adjusting.js"></script> <!-- Just needed for adjusting -->
<script src="libraries/RGraph.common.annotate.js"></script>  <!-- Just needed for annotating -->
<script src="libraries/RGraph.common.context.js"></script>   <!-- Just needed for context menus -->
<script src="libraries/RGraph.common.effects.js"></script>   <!-- Just needed for visual effects -->
<script src="libraries/RGraph.common.resizing.js"></script>  <!-- Just needed for resizing -->
<script src="libraries/RGraph.common.tooltips.js"></script>  <!-- Just needed for tooltips -->
<script src="libraries/RGraph.common.zoom.js"></script>      <!-- Just needed for zoom -->
<script src="libraries/RGraph.common.dynamic.js"></script>   <!-- Just needed for ?? -->
<script src="libraries/RGraph.common.key.js"></script>       <!-- Just needed to show keys -->


</head>
<body>
<div id=main>
<div id="navi">
	<ul class="navi">
		<li class="navi"><a class="navi" href="./index.php">Home</a></li>
		<li class="navi"><a class="navi" href="./verkehr.html">Verkehr</a></li>
		<li class="navi"><a class="navi" href="./dwd.html">Wetter</a></li>
		<li class="navi"><a class="navi" href="./heizung.php">Heizung</a></li>
		<li class="navi"><a class="navi" href="./tvtv.html">TV</a></li>
		<li class="navi"><a class="navi" href="./about.html">About</a></li>
	</ul>
</div>

<canvas id="cvs" width="1000" height="400" style="position:absolute;top:100px;left:20px;">[No canvas support]</canvas>
    <script>
        chart = new RGraph.Line('cvs', <?php print($val_string) ?>);
	//Set the text Size for ALL fonts in the graph
	chart.Set('chart.text.size', 10);
	chart.Set('chart.text.angle', 0);
	
	// Set Chart Title
	chart.Set('chart.title.size', 12);
	//chart.Set('chart.title', 'Temperatur Solar Panel u. Speicher unten');
	
	// Set the legend
	chart.Set('chart.key', ['Raumtemperatur','Au\u00dfentemperatur']);
	chart.Set('chart.key.position', 'gutter');
	chart.Set('chart.key.halign', 'left');
	chart.Set('chart.key.interactive', 'false');

	//Set the title for the x axis
	chart.Set('chart.title.xaxis', 'Datum Uhrzeit');
	chart.Set('chart.title.xaxis.pos', 0.3);
	//Set the title for the x axis
	//chart.Set('chart.title.yaxis', '\u00b0C');
	chart.Set('chart.title.yaxis.pos', 0.1);

	// If Values are negativ we need the axis in the center
	<?php
	  if ($isNeg == 1)
	  print("chart.Set('chart.xaxispos', 'center');"); 
	 ?>
	
	chart.Set('chart.numxticks', 10);
	chart.Set('chart.numyticks', 10);
	chart.Set('chart.tickdirection', 1);

	chart.Set('chart.ylabels.count', 10);
	chart.Set('chart.units.post', '\u00b0C');
	chart.Set('chart.linewidth', 1.5);

	chart.Set('chart.curvy', 1);
        chart.Set('chart.curvy.factor', 0.25);

	chart.Set('chart.filled', false);
        //chart.Set('chart.background.grid.autofit', true);
	
        //gutter is where the labels and titles are
	chart.Set('chart.gutter.left', 55);
        chart.Set('chart.gutter.right', 50);
	chart.Set('chart.gutter.top', 25);
	chart.Set('chart.gutter.bottom', 50);
	//The size of the horizontal margin. This is on the inside of the axes.
        chart.Set('chart.hmargin', 0);
        chart.Set('chart.tickmarks', 'F');
        chart.Set('chart.labels', <?php echo $dateTime_string ?>);

	////////////////////////// Experimental //////////////////////////////////////////
	
	//chart.Set('chart.key.interactive', true); //Need to learn how to clear the lines
	//chart.Set('chart.tickmarks', 'endcircle');
	// Show tooltips with values from labels (maybe to much values)
	//chart.Set('chart.tooltips', chart.Get('chart.labels'));       	
        chart.Draw();
    </script>
</div>
</body>
</html>

