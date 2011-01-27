{include file="findInclude:common/header.tpl"}

{foreach $externalJavascriptURLs as $url}
<script src="{$url}" type="text/javascript"></script>
{/foreach}
<script src="{$minify['js']}" type="text/javascript"></script>
<div id="map-canvas"></div>

<script type="text/javascript">
/******************************************************************************\
  Variables and HTML
\******************************************************************************/
	Campus_Map.device = "{$platform}";
	Campus_Map.urls = {
		'map-options' : '{$options_url}'
	}
	Campus_Map.html = {
		'geo-btn': '{strip}
					<a onclick="Campus_Map.geoLocate(\'showGeoLoc\'); return false;" {if $locate_me}class="on"{/if} id="geo-button">Where am I?</a>
					{/strip}',
		'geo-win': '{strip}
					<div class="locate">
						<h3>You Are Here.</h3>
						<span>
							&raquo; <a onclick="Campus_Map.destination=false;Campus_Map.directions()">Directions to campus</a><br>
							&raquo; Directions to location:
							<form method="get" action="{$directions_url}">
								<input name="q" type="search"><input type="submit" value="search">
							</form>
						</span>
					</div>
					{/strip}',
		'search': '{strip}
					<form action="search/" method="get">
						<div><input type="search" placeholder="Search" results="0" name="q"></div>
						<input type="submit" value="">
					</form>
					{/strip}'
	}		
	Campus_Map.gmap();

/******************************************************************************\
  Display's User's positions and offer directions
\******************************************************************************/
{if $locate_me}
Campus_Map.geoLocate();
{/if}


{if $location}
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
	
	var latlng = new google.maps.LatLng( loc.googlemap_point[0] , loc.googlemap_point[1] );
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
{/if}

{if $traffic}
	var trafficLayer = new google.maps.TrafficLayer();
	trafficLayer.setMap(Campus_Map.map);
	Campus_Map.map.setZoom(14);
{/if}
</script>

{if $platform=="computer"}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="/media/-/jquery.browser.min.js"></script>
<script>
	jQuery(function(){
		$('body').addClass(jQuery.browser.name);
		$('body').addClass(jQuery.browser.name + '' + jQuery.browser.versionX);
	});
</script>
{/if}
</body>
</html>