{include file="findInclude:common/header.tpl"}
<div id="map-canvas"></div>

<script type="text/javascript">
	Campus_Map.device = "{$platform}";
	Campus_Map.urls		= {
		"map-options" : "{$options_url}"
	}
	Campus_Map.gmap();
</script>


{if $locate_me}
<script type="text/javascript">
/******************************************************************************\
  Display's User's positions and offer directions
\******************************************************************************/
Campus_Map.showGeoLoc = function(){
	var map = Campus_Map.map;
	var html = '{strip}
		<div class="locate">
			<h3>Your location.</h3>
			<span>
				&raquo; <a onclick="Campus_Map.directions()">Directions to campus</a><br>
				&raquo; Directions to location:
				<form method="get" action="{$directions_url}">
					<input name="q" type="search"><input type="submit" value="search">
				</form>
			</span>
		</div>{/strip}';
	Campus_Map.meLocWin = new google.maps.InfoWindow({
		content: html,
		position: Campus_Map.me
	});
	map.panTo(Campus_Map.me);
	Campus_Map.meLocWin.open(map);
};
Campus_Map.geoLocate('showGeoLoc');
</script>
{/if}



{if $location}
<script type="text/javascript">
/******************************************************************************\
  Location - display directions and/or info window
\******************************************************************************/
(function(){
	var map = Campus_Map.map;
	var loc = {$location};
	if(typeof loc.info === 'undefined'){
		alert("Location ({$location_id}) not found");
		return;
	}
	var latlng = new google.maps.LatLng( loc.coord_x , loc.coord_y );
	map.panTo(latlng);
	map.panBy(0, -100);
	
	// also opens on geoLocation failure
	Campus_Map.dirInfoWin = new google.maps.InfoWindow({
		content: loc.info,
		position: latlng
	});
	
	if({$directions|default:'false'}){
		Campus_Map.destination = latlng;
		Campus_Map.geoLocate('directions');
	} else {
		Campus_Map.dirInfoWin.open(map);
	}
	
})();
</script>
{/if}


</body>
</html>