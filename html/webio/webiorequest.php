<?php
	// Diese Seite dient als Proxi zu verschiedenen Web-IO
	// folgende Parameter können/müssen in der URL mitgegebben werden
	// IP -> IP-Adresse des Web-IO
	// PORT -> Port Nr des Web-IO (typisch = 80)
	// PW -> PAsswort des Web-IO
	// COMMAND -> input, output, counter, single, outputaccess
	// MASK -> bestimmt welche Bits gesetzt werden sollen (optional und nur bei outputaccess)
	// STATE -> Bestimmt wie die Bits gesetzt werden sollen (nur bei outputaccess)
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Datum aus Vergangenheit
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // immer geÃ¤ndert
	header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                          // HTTP/1.0

	parse_str($_SERVER['QUERY_STRING']);
	$fp=fsockopen($IP, $PORT, $errno, $error, 5);
	if (!$fp)
	{   printf("ERROR");  }
	else
	{	if ($COMMAND == "outputaccess")
		{	IF ($MASK == "") {$MASK="0FFF";}
			fputs($fp, "GET /".$COMMAND."?PW=".$PW."&Mask=".$MASK."&State=".$STATE."&");
		}
		else
		{	fputs($fp, "GET /".$COMMAND."?PW=".$PW."&");
		}
		do
		{	$char=fgetc($fp);
			if($char!=chr(0))
			{	echo $char;
			}
		}
		while($char!=chr(0));
		fclose($fp);
	}
?>