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
			height = document.documentElement.clientHeight - document.getElementById('header-crumbs').scrollHeight;
		} else {
			height = document.documentElement.clientHeight - document.getElementById('header-crumbs').clientHeight;
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
	//this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(controls);
	
	
	var search = document.createElement('div');
	search.id = "search";
	search.innerHTML = Campus_Map.html['search'];
	this.map.controls[google.maps.ControlPosition.TOP].push(search);
	
	var geo = document.createElement('div');
	geo.id = "geo";
	geo.innerHTML = Campus_Map.html['geo-btn'];
	this.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(geo);
	
	// preload images
	var img = new Array();
	img[0] = new Image(); img[0].src = "/media/img/geo-load.gif";
	img[1] = new Image(); img[1].src = "/media/img/geo-blue.png";
	img[2] = new Image(); img[2].src = "/media/img/geo-grey.png";

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
		mapTypeControl: false,
		//mapTypeId : google.maps.MapTypeId.ROADMAP,
		mapTypeControlOptions: {
			//mapTypeIds : ['UCF', google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.TERRAIN],
			//style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
		streetViewControl: true,
		streetViewControlOptions: {
			position: google.maps.ControlPosition.LEFT_TOP
		}
		
	}
	
	this.resize();
	this.map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
	
	var styledMap = new google.maps.StyledMapType({}, { name: "UCF"});
	this.map.mapTypes.set('UCF', styledMap);
	this.map.setMapTypeId('UCF');
	
	this.controls();
	
}


/******************************************************************************\
 Directions
	http://code.google.com/apis/maps/documentation/javascript/services.html#Directions
\******************************************************************************/
Campus_Map.destination = false;
Campus_Map.directions = function(){
	
	if(Campus_Map.win){ Campus_Map.win.close(); }
	
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
	
	if(typeof Campus_Map.meLocWin !== 'undefined'){
		Campus_Map.meLocWin.close();
	}
}


/******************************************************************************\
 Geolocation
	Fails on android (super lame):
		http://code.google.com/apis/maps/documentation/javascript/basics.html#DetectingUserLocation
	Using google gears: 
		http://code.google.com/p/geo-location-javascript/
	
	Sets Campus_Map.me then calls callback fuction
\******************************************************************************/
Campus_Map.me = false;
Campus_Map.win = false;
Campus_Map.geo_on = false;
Campus_Map.geoLocate = function(callback){
	
	if(!Campus_Map.win){
		Campus_Map.win = new google.maps.InfoWindow();
	}
	
	var button = function(className){
		var button = document.getElementById('geo-button');
		if(button && typeof button !== 'undefined'){
			button.className = className;
		}
		
	};
	button('load');
	
	var geo_success = function(p) {
		var map  = Campus_Map.map;
		var me = new google.maps.LatLng(p.coords.latitude, p.coords.longitude);
		map.panTo(me);
		Campus_Map.me = me;
		
		// place marker
		var image = new google.maps.MarkerImage('/media/img/geo-blue.png',
		      new google.maps.Size(22, 22),  //dimen
		      new google.maps.Point(0,0),    //origin
		      new google.maps.Point(11, 11)); //anchor
		var geoMaker = new google.maps.Marker({ 
			position  : me,
			map       : map,
			clickable : false,
			icon      : image
		});
		
		// do something with location
		if(callback && typeof Campus_Map[callback] !== 'undefined'){
			Campus_Map[callback]();
		} else {
			var html = Campus_Map.html['geo-win'];
			var win  = Campus_Map.win;
			win.setContent(html);
			win.setPosition(me);
			win.open(map);
		}
		
		button("on");
	}
	
	var geo_fail = function(e) {
		button('off');
		Campus_Map.me = false;
		var error = "Geolocation service failed"
		if(e) error += ": " + e.message;
		alert(error);
	}
	
	if(!Campus_Map.geo_on){
		// turn on geoloaction
		if(geo_position_js.init()){
			geo_position_js.getCurrentPosition(geo_success,geo_fail,{enableHighAccuracy:true,options:5000});
		} else{
			geo_fail({'message': 'Geolocation not available'});
		}
		Campus_Map.geo_on=true;
	} else {
		// turn off geoloaction
		Campus_Map.win.close();
		button('off');
		Campus_Map.geo_on = false;
	}
}
