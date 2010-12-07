<?php

/**
 * Module responsible for querying directory service and displaying results for
 * directory search service.
 *
 * @package default
 * @author Jared Lang
 **/
class PeopleModule extends UCFModule{
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
	 * Returns an array of objects resulting from a search against $query.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	function search($query){
		$service   = $GLOBALS['siteConfig']->getVar('PEOPLE_SERVICE_URL');
		$query_str = http_build_query(array(
			'search' => $query,
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
		$query     = $this->getArg($queryName, '');
		$this->assign('searchURL', $this->buildURL('./search'));
		$this->assign('queryName', $queryName);
		$this->assign('idName', $idName);
		$this->assign('query', $query);
		
		switch ($this->page){
			case 'index':
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