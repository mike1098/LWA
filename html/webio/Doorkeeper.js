///////////////////////////////////////////////////////////////////////////////////////////////
//Copyright by Michael Frank, Michael.Frank@mikefrank.de
//The content and scripts of this web page is intellectual property of Michael Frank.
//Copy, Modification and re-engeneering is prohibited by law. michael.frank@mikefrank.de
//////////////////////////////////////////////////////////////////////////////////////////////

var now = new Date();

function inputChanged( iDevice, iNr, iVal ){
	
	if ( iVal == true) {
		
		document.getElementById('pic'+iDevice+iNr).src = 'led_0.png';
		document.getElementById('pic'+iDevice+iNr).alt = 'Auf';
	}else{
		document.getElementById('pic'+iDevice+iNr).src = 'led_1.png';
		document.getElementById('pic'+iDevice+iNr).alt = 'Zu';
		}
}


    function setPassword()
    {	document.applets["dio3"].setPassword( '');
		document.applets["dio4"].setPassword( '');
    }
