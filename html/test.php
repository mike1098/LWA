<?php

$db = new PDO('sqlite:./db/HouseKeeper.db');
$val= array();
//$dateTime= array();
$count="";

//$fiveAgo = mktime(date("G"),date("i")-5,date("s"),date("m"),date("d"),date("Y"));
//echo(date("Y-m-d G:i",$fiveAgo) . "<br />");

$result = $db->query("select S_Value,Sensor_ID from S_Values where Date_Time>=DATETIME('NOW','localtime','-10 minutes') and (Sensor_ID=1)");

foreach ($result as $row)
{
  //$val[] = $row["S_Value"]/10; 
  $row["S_Value"] = $row["S_Value"]/10;
  //$row["Date_Time"] =
  $val[] = $row;
  echo ("   ID: " . $row["Sensor_ID"] . "Value: " . $row["S_Value"]/10 . "<br />" );
}

echo ("Vorltemp.: " . $val[$count-1] . "<br />");
echo ("Raumtemp.: " . $val[$count-2] . "<br />");
echo ("Aussentemp.: " . $val[$count-3] . "<br />");

$db = NULL;

?>

<html>
<head>
	<title>Mikes Home Keeper</title>
	<style type="text/css"></style>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
	<script src="./lib/amcharts/amcharts.js" type="text/javascript"></script>
	
<script type="text/javascript">

  var chartData = <?php echo json_encode($val); ?>;
  alert( chartData.toSource();
  alert('Hallo?');
  

  window.onload = function ()
    { 
     
    };
</script>
</head> 
<body>

<script>
var chartData = <?php echo json_encode($val); ?>;
  alert( chartData.toSource();
</script>

<div id=main>


  <div id="navi">
	<ul>
		<li><a href="./traffic.html">Verkehr</a></li>
		<li><a href="./weather.html">Wetter</a></li>
		<li><a href="./solar.php">Heizung</a></li>
		<li><a href="./about.html">About</a></li>
	</ul>
  </div>


</div>
</body>
</html>
