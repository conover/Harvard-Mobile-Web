<?php
/**
* UCF Mobile Map
* Web Communications - Fall 2010
*/

class MapModule extends UCFModule {
	protected $id = 'map';
	protected $location   = false;
	protected $locate_me  = false;
	
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
		$url = $map_api . urlencode($query);
		
		$contents = $this->fromCache($url);
		$contents = utf8_encode($contents);
		$results = json_decode($contents);
		
	}
	
	
	/****************************************************************************\
		Map - pulls all data and tiles from the Campus Map
	\****************************************************************************/
	protected function map() {
		
		// add script to header
		// the only variable exposed to the window should be Campus_Map
		// rest of the js is in theme/map/index template
		$this->addExternalJavascript('http://maps.google.com/maps/api/js?sensor=false');
		$url = URL_PREFIX . 'map/options/';
		$this->assign('options_url', $url);
		$this->addInlineJavascript('var Campus_Map = { };');
		
		if($this->location){
			// TODO: move url to config/web/map.ini
			$map_api = $this->options['MAP_SERVICE_LOCATION'];
			$url = $map_api . urlencode($this->location);
			$contents = $this->fromCache($url);
			$loc = utf8_encode($contents);
			$this->assign('location', $loc);
			$this->assign('location_id', $this->location);
			if($this->directions) $this->assign('directions', true);
		}
		
		if($this->locate_me){
			$url = URL_PREFIX . 'map/directions/';
			$this->assign('directions_url', $url);
			$this->assign('locate_me', true);
		}
		
	}
	
	
} /* MapModule */