<!--
Copyright by Michael Frank, Michael.Frank@mikefrank.de
The content and scripts of this web page is intellectual property of Michael Frank.
Copy, Modification and re-engeneering is prohibited by law. michael.frank@mikefrank.de
//-->

<html>
<head>
	<title>Mikes Home Keeper - tvtv</title>
	<style type="text/css"></style>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
</head> 
<body>

<div id=main>


<div id="navi">
	<ul class="navi">
		<li class="navi"><a class="navi" href="./index.php">Home</a></li>
		<li class="navi"><a class="navi" href="./traffic.html">Verkehr</a></li>
		<li class="navi"><a class="navi" href="./dwd.html">Wetter</a></li>
		<li class="navi"><a class="navi" href="./heizung.php">Heizung</a></li>
		<li class="navi"><a class="navi" href="./about.html">About</a></li>
	</ul>
</div>
<div id="tvtv" style="position:relative;left:0px;top:100px">
<?php print file_get_contents("https://www.tvtv.de/programm")?>
</div>
</div>
</body>
</html>
