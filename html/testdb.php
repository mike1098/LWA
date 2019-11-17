<?php


$db = new PDO('sqlite:./db/HouseKeeper.db');
$val= array();
$dateTime= array();
$title = "Aussentemperatur";
$count="";

//$fiveAgo = mktime(date("G"),date("i")-60,date("s"),date("m"),date("d"),date("Y"));
//$t=date("Y-m-d G:i",$fiveAgo);
//echo(date("Y-m-d G:i",$fiveAgo) . "<br />");
//echo ($t . "<br />");

$result = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-6 hours') and Sensor_ID=1");
$result1 = $db->query("select S_Value,Date_Time from S_Values where Date_Time>=DATETIME('NOW','localtime','-6 hours') and Sensor_ID=2");

//2012-02-19 14:00

foreach ($result as $row)
{
  $val[] = $row["S_Value"]/10;
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
//echo "'" .$dateTime[1]." - ".$dateTime[2]."'";
$db = NULL;

?>

<html>
<head>

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
    
    <canvas id="cvs" width="600" height="250">[No canvas support]</canvas>
    <script>
        chart = new RGraph.Line('cvs', <?php print($val_string) ?>);
	//chart.Set('chart.title', 'Aussentemperatur');
	//chart.Set('chart.title.xaxis', 'Title X-Axis');
	chart.Set('chart.title.xaxis.pos', 0.1);
	chart.Set('chart.title.yaxis', 'Title Y-Axis');
	chart.Set('chart.title.yaxis.pos', 0);
	chart.Set('chart.title.vpos', 2);
	chart.Set('chart.key', 'A,B,C');
	chart.Set('chart.key.background', 'rgba(255,255,255,0.7)');
	chart.Set('chart.key.halign', 'right');
	chart.Set('chart.key.interactive', true);
	//chart.Set('chart.key.position',); 
	chart.Set('chart.contextmenu', 'Menu A,Menu B');
	chart.Set('chart.adjustable', true);
	chart.Set('chart.resizable', true);
	chart.Set('chart.numxticks', 10);	
	chart.Set('chart.tickdirection', -1);
	chart.Set('chart.zoom.factor', 1);
	chart.Set('chart.variant', '3d');
	chart.Set('chart.ylabels.count', 10);
	chart.Set('chart.numxticks', 10);
	chart.Set('chart.units.post', ' C');
	chart.Set('chart.linewidth', 2);
	chart.Set('chart.shadow', true);
	//chart.Set('chart.curvy', 1);
        //chart.Set('chart.curvy.factor', 0.25);

	chart.Set('chart.filled', false);
        chart.Set('chart.background.grid.autofit', true);
        chart.Set('chart.gutter.left', 55);
        chart.Set('chart.gutter.right', 50);
	chart.Set('chart.gutter.top', 20);
	chart.Set('chart.gutter.bottom', 30);
        chart.Set('chart.hmargin', 10);
        chart.Set('chart.tickmarks', '');
        chart.Set('chart.labels', <?php print($dateTime_string) ?>);        
//	chart.Set('chart.labels', 'A,B,C,D,E,F');	
        chart.Draw();
    </script>

</body>
</html>

