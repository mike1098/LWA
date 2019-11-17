<?php

$db = new PDO('sqlite:./db/HouseKeeper.db');
$val= array();
//$dateTime= array();
$count="";

//$fiveAgo = mktime(date("G"),date("i")-5,date("s"),date("m"),date("d"),date("Y"));
//echo(date("Y-m-d G:i",$fiveAgo) . "<br />");

$result = $db->query("select S_Value from S_Values where Date_Time>=DATETIME('NOW','localtime','-3 minutes') and (Sensor_ID=1 or Sensor_ID=3 or Sensor_ID=10)");

foreach ($result as $row)
{
  $val[] = $row["S_Value"]/10; 
  $count++;
  //echo ("Value: " . $row["S_Value"]/10 . "| ID: " . $row["Sensor_ID"] . "<br />" );
}

//echo ("Vorltemp.: " . $val[$count-1] . "<br />");
//echo ("Raumtemp.: " . $val[$count-2] . "<br />");
//echo ("Aussentemp.: " . $val[$count-3] . "<br />");

$db = NULL;

?>

<html>
<head>
	<title>Mikes Home Keeper</title>
	<script type="text/javascript" src="./lib/CoolClock/coolclock.js"></script> 
	<script type="text/javascript" src="./lib/CoolClock/moreskins.js"></script> 
	
	<style type="text/css"></style>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
	
	
	
<script>
    window.onload = function ()
    {   
        CoolClock.findAndCreateClocks();
    };
</script>
</head> 
<body>

<div id=main>


<div id="navi">
	<ul class="navi">
		<li class="navi"><a href="./traffic.html">Verkehr</a></li>
		<li class="navi"><a href="./dwd.html">Wetter</a></li>
		<li class="navi"><a href="./solar.php">Heizung</a></li>
		<li class="navi"><a href="./tvtv.html">TvTv</a></li>
		<li class="navi"><a href="./about.html">About</a></li>
		<li class="navi"><a></a></li>
	</ul>
</div>

        <canvas id="clock" class="CoolClock:myclock:80::"></canvas>


<div id="widget">
	<div style="font-size:12px;margin-bottom:2px; font-weight:bold;">
	  Das Wetter f&uuml;r<br>Dasing
	</div>
	<iframe  
	  marginheight="0" 
	  marginwidth="0" 
	  frameborder="0" 
	  scrolling="no" 
	  src="http://www.wetteronline.de/cgi-bin/hpweather?PLZ=86453&amp;FORMAT=long&amp;MENU=dropdown&amp;MAP=rainradar"  
	  width="158px" height="308px">
	 </iframe>
</div>

<ul class="values" >
  <li class="values"> Aussentemperatur: <?php print($val[$count-3]-2) ?>&deg;C</li>
  <li class="values"> Raumtemperatur: <?php print($val[$count-2]-2) ?>&deg;C</li>
  <li class="values"> Vorlauftemperatur: <?php print($val[$count-1]) ?>&deg;C</li>
</ul>


</body>
</html>
