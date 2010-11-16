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
	options.innerHTML = "options";
	options.href = Campus_Map.urls['map-options'];
	controls.appendChild(options);
  	
	// place controls on map
	controls.index = 1;
	this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(controls);

};



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
	
};
