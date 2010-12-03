<?php

/**
 * Module responsible for querying directory service and displaying results for
 * directory search service.
 *
 * @package default
 * @author Jared Lang
 **/
class PeopleModule extends Module{
	protected $id = 'people';
	
	/**
	 * Given a SimpleXMLElement representing a result from the people search,
	 * will return that object converted to an associative array.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	function resultToArray($result){
		$array = array();
		foreach($result->Field as $field){
			$key   = (string)$field['name'];
			$value = (string)$field;
			$array[$key] = $value;
		}
		return $array;
	}
	
	/**
	 * Retreives data pointed to by URL.
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function fetchHTTP($url){
		$timeout = $GLOBALS['siteConfig']->getVar('PEOPLE_SEARCH_TIMEOUT');
		$options = stream_context_create(array(
			'http' => array(
				'timeout' => (string)$timeout,
			),
		));
		$data = file_get_contents($url, false, $options);
		return $data;
	}
	
	/**
	 * Returns an array of objects resulting from a search against $query.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	function search($query){
		$service   = $GLOBALS['siteConfig']->getVar('PEOPLE_SERVICE_URL');
		$query_str = http_build_query(array(
			'query' => $query,
		));
		
		$url  = implode(array($service, '?', $query_str));
		$json = $this->fetchHTTP($url);
		
		$response = json_decode($json);
		if ($response == null){
			return array();
		}
		return $response->results;
	}
	
	function initializeForPage(){
		$queryName = 'q';
		$idName    = 'id';
		$this->assign('searchURL', $this->buildURL('search'));
		$this->assign('queryName', $queryName);
		$this->assign('idName', $idName);
		
		switch ($this->page){
			case 'index':
				$this->redirectTo('search');
				break;
			case 'search':
				$query = $this->getArg($queryName, '');
				$this->assign('query', $query);
				
				if (!empty($this->args[$queryName])){
					$this->assign('listing', $this->search($query));
				}else{
					$this->assign('listing', array());
				}
				break;
			case 'detail':
				$id = $this->getArg($idName);
				break;
			default:
				#raise 404
				header("HTTP/1.0 404 Not Found");
				exit();
				break;
		}
	}
} // END class 

?>