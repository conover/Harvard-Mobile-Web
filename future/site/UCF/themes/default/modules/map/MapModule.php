<?php
/**
* UCF Mobile Map
* Web Communications - Fall 2010
*/

class MapModule extends UCFModule {
	protected $id = 'map';
	protected $location   = false;
	protected $locate_me  = false;
	protected $directions = false;
	
	/****************************************************************************\
		Constructor
			- sets the correct page
			- initializes private vars based on URL
	\****************************************************************************/
	function __construct($page='index', $args=array()){
		parent::__construct($page, $args);
		
		// accept building numbers
		$url = $_SERVER["REQUEST_URI"];
		$matches = array();
		if(preg_match('/location\/([\w-_]+)$/', $url, $matches)){
			$this->location = $matches[1];
		}
		if($this->location !== false && stripos($url, 'me+location')){
			$this->directions = true;
		}
		
		// tell map to ask for locaiton (append /me to url)
		if(preg_match('/\/me$/', $url)){
			$this->locate_me = true;
		}
		
		$map_pages = array(
			'options',
			'search',
			'directions',
			'traffic'
		);
		
		if(!in_array($this->page, $map_pages)){
			$this->page = 'index';
		}
	}
	
	
	/****************************************************************************\
		Manditory, abstract method of Module.php
	\****************************************************************************/
	protected function initializeForPage() {
		
		// will always want the Campus_Map object, should be
		// the only variable exposed to the window
		$this->addInlineJavascript('var Campus_Map = { };');
		
		switch ($this->page) {
			
			case 'index':
				$this->map();
				break;
			
			case 'traffic':
				$this->assign('traffic', true);
				$this->page = 'index';
				$this->map();
				break;
			
			case 'search':
				$this->search();
				break;
			
			case 'directions':
				$this->assign('directions', true);
				$this->search();
				break;
				
		}
	}
	
	
	/****************************************************************************\
		Search - passes query to the Campus Map
	\****************************************************************************/
	protected function search() {
		$this->page = 'options';
		
		$results = array();
		$this->assignByRef('results', $results);
		
		if(!isset($_GET['q'])) return;
		
		$map_api = $this->options['MAP_SERVICE_SEARCH'];
		
		$query = trim(stripslashes(strip_tags($_GET['q'])));
		if(empty($query)) return;
		$this->assign('search_q', $query);
		$url = sprintf($map_api, urlencode($query));
		
		$contents = $this->fromCache($url);
		$contents = utf8_encode($contents);
		$results = json_decode($contents)->results;
		
	}
	
	
	/****************************************************************************\
		Map - pulls all data and tiles from the Campus Map
	\****************************************************************************/
	protected function map() {
		
		// add script to header
		// rest of the js is in theme/map/index template
		$this->addExternalJavascript('http://maps.google.com/maps/api/js?sensor=false');
		$this->addExternalJavascript('http://code.google.com/apis/gears/gears_init.js');
		$this->addExternalJavascript('/media/-/geo.js');
		
		$url = URL_PREFIX . 'map/options/';
		$this->assign('options_url', $url);
		
		if($this->location){
			$map_api = $this->options['MAP_SERVICE_LOCATION'];
			$url = sprintf($map_api, urlencode($this->location));
			$contents = $this->fromCache($url);
			$loc = utf8_encode($contents);
			//remove poly coord
			$loc = preg_replace( '/"poly_coords":.*]]],/is', '', $loc);
			$this->assign('location', $loc);
			$this->assign('location_id', $this->location);
			if($this->directions) $this->assign('directions', true);
		}
		
		$url = URL_PREFIX . 'map/directions/';
		$this->assign('directions_url', $url);
		if($this->locate_me){
			$this->assign('locate_me', true);
		}
		
	}
	
	
} /* MapModule */