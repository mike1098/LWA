<!DOCTYPE HTML>
<html>

<head>
<meta http-equiv="Content-Type"
content="text/html;charset=UTF-8">
</head>
<body>

<?php

$data = array();

$db = new SQLite3("test.db",SQLITE3_OPEN_READONLY);
//echo "DB open" . "<br />";

$res = $db->query("select ID,name from sensors") or die ("Error in query: <span style='color:red;'>$query</span>"); 
if (!$res) {throw new Exception( $db->lastErrorMsg() );}


//echo "Column Name: " . $res->columnName(0) . "<br />";
//echo "Column Type: " . $res->columnType(0) . "<br />";
//echo "Number of Cols:" . $res->numColumns() . "<br />";

//var_dump($res);

while($dsatz = $res->fetchArray(SQLITE3_ASSOC)) //Could be SQLITE3_ASSOC or SQLITE3_NUM or both
   {
      //$data["ID"] .= $dsatz["ID"];
      //$data["Name"] .= $dsatz["Name"];
      $data[] = $dsatz;
      //var_dump($dsatz);
   }
$db->close();

//foreach ($data as $name=>$value)
//{
//  echo $data["ID"] . ", "
//	. $data["name"]
//	. "<br />";
//}


$max = count($data);

echo "<form id= 'selection' action='pla.php' method='post' >";
echo "<h3>Verfügbare Werte</h3>";
for($i = 0; $i < $max;$i++)
{
echo "<p><input type='checkbox' name='sensor" . $data[$i]["ID"] . "' value=" . $data[$i]["ID"] . "/>"
     . $data[$i]["ID"] . " " . $data[$i]["Name"] . "</br>"; 

//echo "ID Number: " . $data[$i]["ID"] . ", "
//  . "Name : " . $data[$i]["Name"]
//  . "<br />";
}
echo "</form>";
//var_dump($data);
?>
</body>
<input id="time" type="time" placeholder="hh:mm" title="hh:mm"/>
<input id="date" type="date" placeholder="dd:mm:yy" title="dd:mm:yy"/>
</html>
