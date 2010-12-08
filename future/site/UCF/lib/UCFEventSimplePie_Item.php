<?php
/**
 * Extends SimplePie_Item to include methods to retrieve ucfevent namespaced
 * elements in rss feeds.
 *
 * @package default
 * @author Jared Lang
 **/
class UCFEventSimplePie_Item extends SimplePie_Item{
	function get_type(){
		try{
			$type = $this->get_item_tags('http://events.ucf.edu', 'type');
			if (is_array($type)){
				$type = $type[0]['data'];
			}else{
				$type = '';
			}
			return $type;
		}
		catch(Exception $e){
			return '';
		}
	}
	
	
	function get_location_name(){
		try{
			$location = $this->get_item_tags('http://events.ucf.edu', 'location');
			if (is_array($location)){
				$name = $location[0]['child']['http://events.ucf.edu']['name'][0]['data'];
			}else{
				$name = '';
			}
			return $name;
		}
		catch(Exception $e){
			return '';
		}
	}
	
	
	function get_location_url(){
		try{
			$location = $this->get_item_tags('http://events.ucf.edu', 'location');
			if (is_array($location)){
				$url = $location[0]['child']['http://events.ucf.edu']['mapurl'][0]['data'];
			}else{
				$url = '';
			}
			return $url;
		}
		catch(Exception $e){
			return '';
		}
	}
	
	
	function get_startdate(){
		try{
			$startdate = $this->get_item_tags('http://events.ucf.edu', 'startdate');
			if (is_array($startdate)){
				$time = $startdate[0]['data'];
			}else{
				$time = '';
			}
			return $time;
		}
		catch(Exception $e){
			return '';
		}
	}
	
	
	function get_enddate(){
		try{
			$endtime = $this->get_item_tags('http://events.ucf.edu', 'enddate');
			if (is_array($endtime)){
				$time = $endtime[0]['data'];
			}else{
				$time = '';
			}
			return $time;
		}
		catch(Exception $e){
			return '';
		}
	}
}

?>