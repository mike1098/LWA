<?php
$db = new PDO('sqlite:./db/HouseKeeper.db');
$val= array();
$dateTime= array();
$title = "Solarkollektor";
$count="";

$result = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-12 hours') and Sensor_ID=1");
$result1 = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-12 hours') and Sensor_ID=10");


foreach ($result as $row)
{ 

  $val[] = $row["S_Value"]/10-2;
  $dateTime[] = $row["Date_Time"]; 
  $count++;
}

foreach ($result1 as $row)
{
  $val1[] = $row["S_Value"]/10;
}

// For RGraph we need everthing in one string
$val_string = "[" . join(",", $val) . "]";
$val_string1 = "[" . join(",", $val1) . "]";
//echo $val_string ."". $val_string1;
$val_string = $val_string .",". $val_string1;
//$dateTime_string = "['" . join("','", $dateTime) . "']";
$dateTime_string = "['" .$dateTime[1]."','". $dateTime[$count/2]."','".array_pop($dateTime)."']";
//echo $dateTime_string;
//echo "'" .$dateTime[1]." - ".$dateTime[2]."'"
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

</head>
<body>
<div id=main>
<div id="navi">
	<uli class="navi">
		<li class="navi"><a class="navi" href="./index.php">Home</a></li>
		<li class="navi"><a class="navi" href="./dwd.html">Wetter</a></li>
		<li class="navi"><a class="navi" href="./heizung.php">Heizung</a></li>
		<li class="navi"><a class="navi" href="./about.html">About</a></li>
	</ul>
</div>

<canvas id="cvs" width="800" height="400" style="position:absolute;top:40px;left:20px;">[No canvas support]</canvas>
    <script>
        chart = new RGraph.Line('cvs', <?php print($val_string) ?>);
	chart.Set('chart.title', 'Aussentemperatur und Vorlauftemperatur');
	//chart.Set('chart.title.xaxis', 'Title X-Axis');
	chart.Set('chart.title.xaxis.pos', 0.1);
	//chart.Set('chart.title.yaxis', 'Aussentemperatur und Vorlauftemperatur');
	chart.Set('chart.title.yaxis.pos', 0);
	chart.Set('chart.title.vpos', 2);
	
	chart.Set('chart.key.position', 'graph');
	
	// If Values are negativ we need the axis in the center
	<?php
	  if ($isNeg == 1)
	  print("chart.Set('chart.xaxispos', 'center');"); 
	 ?>
	//chart.Set('chart.key.background', 'rgba(255,255,255,0.7)');
	//chart.Set('chart.key.halign', 'right');
	
	chart.Set('chart.numxticks', 10);	
	chart.Set('chart.tickdirection', -1);
	chart.Set('chart.zoom.factor', 1);
	//chart.Set('chart.variant', '3d');
	chart.Set('chart.ylabels.count', 10);
	chart.Set('chart.numxticks', 10);
	chart.Set('chart.units.post', ' C');
	chart.Set('chart.linewidth', 2);
	chart.Set('chart.shadow', true);
	chart.Set('chart.curvy', 1);
        chart.Set('chart.curvy.factor', 0.25);

	chart.Set('chart.filled', false);
        chart.Set('chart.background.grid.autofit', true);
        chart.Set('chart.gutter.left', 55);
        chart.Set('chart.gutter.right', 50);
	chart.Set('chart.gutter.top', 20);
	chart.Set('chart.gutter.bottom', 30);
        chart.Set('chart.hmargin', 10);
        chart.Set('chart.tickmarks', '');
        chart.Set('chart.labels', <?php print($dateTime_string) ?>);        	
        chart.Draw();
    </script>
</div>
</body>
</html>

