///////////////////////////////////////////////////////////////////////////////////////////////
//Copyright by Michael Frank, Michael.Frank@mikefrank.de
//The content and scripts of this web page is intellectual property of Michael Frank.
//Copy, Modification and re-engeneering is prohibited by law. michael.frank@mikefrank.de
// New Version with AJAX and PHP
// Created 16. August 2015
//////////////////////////////////////////////////////////////////////////////////////////////

var MAXIO=12;
var SendString;
var dioList= ["192.168.1.251"];
var dioIP= "192.168.1.251";
var dioPort="80";
var dioPassword = "";
var intervaltime=500;
var OutPortList = [ 1 , 2 , 4 , 8 , 10 , 20 , 40 , 80, 100 , 200 , 400 , 800];

//Poll the Values frequently
window.setInterval("Polling()", intervaltime);

// Translate the Hex Return String form DIO to Integer
function HexToInt(HexStr)
{	var TempVal;
	var HexVal=0;
	for( i=0; i<HexStr.length;i++)
	{	if (HexStr.charCodeAt(i) > 57)
	    {   TempVal = HexStr.charCodeAt(i) - 55;
		}
		else
		{   TempVal = HexStr.charCodeAt(i) - 48;
		}
		HexVal=HexVal+TempVal*Math.pow(16, HexStr.length-i-1);
	}
	return HexVal;
}


// This is the main function communicating with the DIO
// The function Data Received is called with the results
function DataRequest(SendString)
{	var xmlHttp;
	try
    {	// Internet Explorer
    	if( window.ActiveXObject )
    	{	xmlHttp = new ActiveXObject( "Microsoft.XMLHTTP" );
    	}
    	// Mozilla, Opera und Safari
    	else if( window.XMLHttpRequest )
    	{	xmlHttp = new XMLHttpRequest();
    	}
    }
    // loading of xmlhttp object failed
    catch( excNotLoadable )
    {	xmlHttp = false;
    	alert("Only Internet Explorer, Mozilla (Firefox), Opera or Safari is supported");
    }
	if 	(xmlHttp)
	{	xmlHttp.onreadystatechange = DataReceived;
		xmlHttp.open("GET", SendString, true);
		xmlHttp.setRequestHeader("Cache-Control", "no-store, no-cache, must-revalidate");
		xmlHttp.setRequestHeader("Expires", "Sat, 05 Nov 2005 00:00:00 GMT");
		xmlHttp.setRequestHeader("Pragma","no-cache");
		xmlHttp.send(null);
	}
//} //added
//This is the function called from DataRequest to process the results
//and change the HTML elements
// This function is part of function DataRequest because var xmlHttp is private to DataRequest
// Possibly make xmlHttp public

	function DataReceived()
	{ 	var HexVal;
		var iDevice = dioList.length;
		var ReceiveStr; //Contain the results from DataRequest
		if (xmlHttp.readyState == 4)
		{	if (xmlHttp.status == 200)
			{	ReceiveStr = xmlHttp.responseText; //Copy the results to ReceiveStr
			    status=ReceiveStr;
				if (ReceiveStr.substring(0,5)=='input')
				{	HexVal=HexToInt(ReceiveStr.substring(6,10));
					for (iNr=0;iNr<MAXIO;iNr++)
					{	if ((HexVal & Math.pow(2,iNr)) == Math.pow(2,iNr))
						{	//ioform.cb_input[i].checked = true;
							document.getElementById('pic'+iDevice+iNr).src = 'nolight.png';
							document.getElementById('pic'+iDevice+iNr).alt = 'Licht ist aus';
						}
						else
						{	//ioform.cb_input[i].checked = false;
							document.getElementById('pic'+iDevice+iNr).src = 'light.png';
							document.getElementById('pic'+iDevice+iNr).alt = 'Licht ist an';
						}
					}
				}
				if (ReceiveStr.substring(0,6)=='output')
				{	HexVal=HexToInt(ReceiveStr.substring(7,11));
					for (iNr=0;iNr<MAXIO;iNr++)
					{	if ((HexVal & Math.pow(2,iNr)) == Math.pow(2,iNr))
						{	//ioform.cb_output[iNr].checked = true;
							
						}
						else
						{	//ioform.cb_output[iNr].checked = false;
							
						}
					}
				}
				if (ReceiveStr.substring(0,7)=='counter')
				{	var slength=ReceiveStr.length;
					if (ReceiveStr.substring(7,8)==';')
					{	countervalue=ReceiveStr.split(';');
						for (i=0;i<MAXIO;i++)
						{	//document.getElementById('counter'+i).innerHTML = '<a>'+countervalue[i+1]+'<\/a>';
						}
					}
					else
					{	if (ReceiveStr.substring(9,10)==';')
						{	i=(ReceiveStr.substring(7,9));
							//document.getElementById('counter'+i).innerHTML = '<a>'+ReceiveStr.substring(10,slength)+'<\/a>';
						}
						else
						{	i=(ReceiveStr.substring(7,8));
							//document.getElementById('counter'+i).innerHTML = '<a>'+ReceiveStr.substring(9,slength)+'<\/a>';
						}
					}
				}
			}
		}
		//xmlHttp=null;
	}

}

// This function set the output on for 1000 ms
function setOutput(dioNr,OutputNr)
{
	dioIP = dioList[dioNr-1];       // Apply the corect IP from the List of DIO´s
	OPort = OutPortList[OutputNr];  // Set Output Nr. to correct Bit Pattern In Hex
    // Switch Output On
	SendString='webiorequest.php?IP='+dioIP
				+'&PORT='+dioPort
				+'&COMMAND=outputaccess&PW='+dioPassword
				+'&MASK='+OPort
				+'&STATE='+OPort+'&';
	//alert( "setOutput: " + dioIP + " OutPutNr: " + OutputNr + " SendStr: " + SendString);
	DataRequest(SendString);
	
	pause(1000); //Wait 1000ms
	// Switch Output off again to protect relais
	SendString='webiorequest.php?IP='+dioIP
				+'&PORT='+dioPort
				+'&COMMAND=outputaccess&PW='+dioPassword
				+'&MASK='+OPort
				+'&STATE=0&';
	//alert( "setOutput: " + dioIP + " OutPutNr: " + OutputNr + " SendStr: " + SendString);
	DataRequest(SendString);	
}

// This function get the output status
// Until now we dont need this
// ChangeMe: Change to process more then one DIO
function getOutputs()
{	DataRequest('webiorequest.php?IP='+dioIP+'&PORT='+dioPort
	+'&COMMAND=output&PW='+dioPassword+'&');
}

// This function get the input status
// Until now we dont use counters
// ChangeMe: Change to process more then one DIO
function getInputs()
{	DataRequest('webiorequest.php?IP='+dioIP+'&PORT='+dioPort
	+'&COMMAND=input&PW='+dioPassword+'&');
}


// This function frequently poll all Input and Output states
// From all DIO´s in dioList
function Polling()
{	
	for (dioIP in dioList)
	{
		DataRequest('webiorequest.php?IP='+dioList[dioIP]
		+'&PORT='+dioPort
		+'&COMMAND=input&PW='+dioPassword+'&');
	}
}


function pause(millis)
{
	var date = new Date();
	var curDate = null;

	do { curDate = new Date(); }
	while(curDate-date < millis);
} 


//Old Stuff
//ChangeMe
function changeImage( picId )
{
	//document.getElementById(picId).src = 'biglight.png';
	//document.images[picId].src= "led_1.gif";
return true;
}

function changeImageBack(picId)
{
 //document.images[].src = "led_0.gif";
 return true;
}



