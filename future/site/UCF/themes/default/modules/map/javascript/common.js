/*	
	CAMPUS MAP
	UCF Web Communcations
	Summer 2010

*/

/******************************************************************************\
 Resize
	sets map to 100% height
	attaches function to window resize
\******************************************************************************/
Campus_Map.resize = function(){
	var height;
	var resize = function(){
		// window - header - header border
		if(Campus_Map.ie){
			height = document.documentElement.clientHeight - document.getElementById('Header').scrollHeight;
		} else {
			height = document.documentElement.clientHeight - document.getElementById('Header').clientHeight - 2;
		}
		
		document.getElementById('map-canvas').style.height = height + "px";
		
		// iphone, hide url bar
		if(Campus_Map.device === "webkit"){
			height += 58;
			document.getElementById('map-canvas').style.height = height + "px";
			window.scrollTo(0, 1);
		}
	};
	
	resize();
	
	if(Campus_Map.device == 'computer')
		window.onresize = resize;
}


/******************************************************************************\
  Custom Controls
	creates controls for the map
	appends to the google map
\******************************************************************************/
Campus_Map.controls = function() {
	// Create the DIV to hold the control and call the HomeControl() constructor
	// passing in this DIV.
	var controls = document.createElement('div');
	controls.id = "map-options";
	
	var options = document.createElement('a');
	options.title = 'Click to set the view map options';
	options.innerHTML = "Search";
	options.href = Campus_Map.urls['map-options'];
	controls.appendChild(options);
  	
	// place controls on map
	controls.index = 1;
	this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(controls);

}



/******************************************************************************\
 Create Google Map
	stores in Campus_Map.map
\******************************************************************************/
Campus_Map.gmap = function(){
	//center map at Student Union
	var myLatlng = new google.maps.LatLng(28.601584019049238,-81.20095419304656);
	
	var myOptions = {
		zoom: 16,
		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		}
	}
	
	this.resize();
	this.map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
	this.controls();
	
}


/******************************************************************************\
 Directions
	http://code.google.com/apis/maps/documentation/javascript/services.html#Directions
\******************************************************************************/
Campus_Map.destination = false;
Campus_Map.directions = function(){
	
	if(!Campus_Map.me){
		// error in geoloaction, display campus location if set
		if(typeof Campus_Map.dirInfoWin !== 'undefined')
			Campus_Map.dirInfoWin.open(Campus_Map.map);
	}
	
	if(!Campus_Map.destination){
		// set to Welcome Center
		Campus_Map.destination = new google.maps.LatLng(28.597707,-81.203122);
	}
	
	var directionsDisplay = new google.maps.DirectionsRenderer();
	directionsDisplay.setMap(Campus_Map.map);
	
	//todo: use response.legs.distance to choose travel mode
	var directionsService = new google.maps.DirectionsService();
	var request = {
		origin:Campus_Map.me, 
		destination:Campus_Map.destination,
		travelMode: google.maps.DirectionsTravelMode.DRIVING
	};
	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			/* didn't work out so well
			// update response with location information
			if(
				typeof response.routes !== 'undefined' && response.routes.length > 0 &&
				typeof response.routes[0].legs !== 'undefined' && response.routes[0].legs.length > 0
			){
				if(typeof Campus_Map.dirInfoWin !== 'undefined'){
					response.routes[0].legs[0].end_address = Campus_Map.dirInfoWin.content;
				} else {
					response.routes[0].legs[0].end_address = "Welcome Center<br>" + response.routes[0].legs[0].end_address;
				}
			}
			*/
			directionsDisplay.setDirections(response);
		}
	});
	
	if(typeof Campus_Map.meLocWin !== 'undefined')
		Campus_Map.meLocWin.close();
}


/******************************************************************************\
 Geolocation
	http://code.google.com/apis/maps/documentation/javascript/basics.html#DetectingUserLocation
\******************************************************************************/
Campus_Map.me = false;
Campus_Map.geoLocate = function(callback){
	// Note that using Google Gears requires loading the Javascript
	// at http://code.google.com/apis/gears/gears_init.js
	var handleNoGeolocation = function(errorFlag) {
		Campus_Map.me = false;
		if (errorFlag == true) {
			alert("Geolocation service failed.");
		} else {
			alert("Your browser doesn't support geolocation.");
		}
	}
	var browserSupportFlag = true;
	// Try W3C Geolocation (Preferred)
	if(navigator.geolocation) {
		Campus_Map.geoContinue = true;
		navigator.geolocation.getCurrentPosition(function(position) {
			// for some reason, occassionally gets called twice, fixed with 'geoContinue'
			if(Campus_Map.geoContinue){
				Campus_Map.me = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
				Campus_Map[callback]();
				Campus_Map.geoContinue = false;
			}
		}, function() {
			handleNoGeolocation(browserSupportFlag);
		});
	// Try Google Gears Geolocation
	} else if (google.gears) {
		browserSupportFlag = true;
		var geo = google.gears.factory.create('beta.geolocation');
		geo.getCurrentPosition(function(position) {
			Campus_Map.me = new google.maps.LatLng(position.latitude,position.longitude);
			Campus_Map[callback]();
		}, function() {
			handleNoGeoLocation(browserSupportFlag); 
		});
	// Browser doesn't support Geolocation
	} else {
		browserSupportFlag = false;
		handleNoGeolocation(browserSupportFlag);
	}
}