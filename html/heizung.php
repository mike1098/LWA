<!DOCTYPE HTML>
<html>

<head>
  <title>Mikes Home Keeper - Heizungsübersicht</title
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <style type="text/css"></style>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
  <link rel="stylesheet" type="text/css" href="./styles.css" />
  <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
  <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->

<script>
$(document).ready(function(){
  $("#p1").mouseleave(function(){
    alert("Bye! You now leave p1!");
  });
});

$(function() {
  $( document ).tooltip();	
    });

</script>

<style>
    label {
        display: inline-block;
        width: 5em;
    }
</style>

</head>
<body>
<?php

$data = array();
$count = 0;

$db = new SQLite3("./db/HouseKeeper.db",SQLITE3_OPEN_READONLY);
//echo "DB open" . "<br />";

$res = $db->query("select S_Value,Sensor_ID from S_Values where Date_Time>=DATETIME('NOW','localtime','-3 minutes')") or die ("Error in query: <span style='color:red;'>$db->lastErrorMsg()</span>"); 



if (!$res) {throw new Exception( $db->lastErrorMsg() );}


while($dsatz = $res->fetchArray(SQLITE3_ASSOC)) //Could be SQLITE3_ASSOC or SQLITE3_NUM or both
   {
      $data[$dsatz["Sensor_ID"]] = $dsatz["S_Value"]/10;
	  $count++;
   }
$db->close();

//var_dump($data);

?>
</body>
<div id=main> 
<div id="navi">
	<ul class="navi">
		<li class="navi"><a class="navi" href="./index.php">Home</a></li>
		<li class="navi"><a class="navi" href="./traffic.html">Verkehr</a></li>
		<li class="navi"><a class="navi" href="./dwd.html">Wetter</a></li>
		<li class="navi"><a class="navi" href="./tvtv.html">TV</a></li>
		<li class="navi"><a class="navi" href="./version.php">Version</a></li>
	</ul>
</div>
  <img src="./LWA203.png" alt="LWA203">
  <a class="temp" id="aussenTemp" title="Aussentemperatur"style="position:absolute;left:660px;top:100px"> 
      <?php print($data[1]-2) ?>&deg;C Außentemperatur
  </a>
  <a class="temp" id="solKoll" title="Kollektor Temperatur" href="./solar.php" style="position:absolute;left:150px;top:95px">
     <?php print($data[2]) ?>&deg;C
  </a>
  <a class="temp" id="raumIst" title="Raumtemperatur" href="./raumtemp.php" style="position:absolute;left:660px;top:660px">
    <?php print($data[3]-2) ?>&deg;C Raumtemperatur ist
  </a>
  <a class="temp" id="raumSoll" style="position:absolute;left:660px;top:695px">
    <?php print($data[4]) ?>&deg;C Raumtemperatur soll
  </a>
  <a class="temp" id="speicherU" title="Speicher unten" style="position:absolute;left:540px;top:760px">
    <?php print($data[7]) ?>&deg;C Speicher unten
  </a>
  <a class="temp" id="verdampfer" title="Verdampfer" href="./verdampfer.php" style="position:absolute;left:460px;top:430px">
    <?php print($data[8]) ?>&deg;C
  </a>
  <a class="temp" id="vorl" href="./vorlauf.php" title="Vorlauf soll/ist" style="position:absolute;left:660px;top:630px">
    <?php print($data[12]) ?>&deg;C/<?php print($data[10]) ?>&deg;C Vorlauf Soll/Ist
  </a>
  <a class="temp" id="warmWasser" title="Warmwasser soll/ist" style="position:absolute;left:550px;top:565px"><?php print($data[14]) ?>&deg;C/<?php print($data[13]) ?>&deg;C</a>
</div>
</html>
