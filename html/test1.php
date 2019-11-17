<?php

$sensor = array("Aussentemperatur", 
                 "Kollektortemperatur" , 
				 "RaumtemperaturIst", 
				 "RaumtemperaturSoll", 
				 "RestWaermerSpeicher", 
				 "Ruecklauf",
				 "SpeichertemperaturUnten",
				 "Verdampfertemperatur",
				 "Vorlauftemperatur",
				 "VorlauftemperaturHK1",
				 "VorlauftemperaturSoll",
				 "VorlauftemperaturSollHK1",
				 "Wassertemperatur",
				 "WassertemperaturSoll");

$db = new SQLite3("./db/HouseKeeper.db",SQLITE3_OPEN_READONLY);

$count=0;

$result = $db->query("select Date_Time,S_Value,Sensor_ID from S_Values 
                      where Date_Time>=DATETIME('NOW','localtime','-3 minutes') and (Sensor_ID=1 or Sensor_ID=2 )");

if (!$result) {throw new Exception( $db->lastErrorMsg() );}
					  
while($dsatz = $result->fetchArray(SQLITE3_ASSOC)){ //Could be SQLITE3_ASSOC or SQLITE3_NUM or both
  //print_r($dsatz);
  //var_dump($dsatz);
  //$data[$dsatz["Sensor_ID"]] = $dsatz["S_Value"]/10;
  $data[$count] = $dsatz;
  print_r($sensor[$dsatz["Sensor_ID"]]);  
  $count++;
}			
//print_r($data);
	  
$db->close();
//var_dump($data);
?>

<html>
<head>
	
<script type="text/javascript">
var chart;
var chartData = [];

var chartData = <?php echo json_encode($data); ?>;


window.onload = function () {
     
}


</script>
</head> 
<body>


<div id=main>

</div>
</body>
</html>
