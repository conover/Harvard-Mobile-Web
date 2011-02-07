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
		$json = $this->fromCache($url);
		
		$response = json_decode($json);
		if ($response == null){
			return array();
		}
		return array_filter($response->results, create_function('$r', '
			return stripos($r->name, "FAX:") === False;
		'));
	}
	
	function initializeForPage(){
		$queryName = 'search';
		$query     = $this->getArg($queryName, '');
		$this->assign('queryName', $queryName);
		$this->assign('query', $query);
		
		switch ($this->page){
			case 'index':
				$this->assign('query', $query);
				if (!empty($this->args[$queryName])){
					$listing = $this->search($query);
					$listing = merge_duplicates($listing);
					$this->assign('listing', $listing);
				}else{
					$this->assign('listing', array());
				}
				break;
			default:
				#raise 404
				header("HTTP/1.0 404 Not Found");
				exit();
				break;
		}
	}
} // END class

/**
 * Combines duplicate people entries
 *
 * @return results
 * @author Jared Lang
 **/
function merge_duplicates($items){
	$null_key  = 'null@mail.ucf.edu-';
	$null_iter = 0;
	$by_email  = array();
	foreach($items as $item){
		$key = ($item->email) ? $item->email : $null_key . $null_iter++;
		if (!array_key_exists($key, $by_email)){
			$by_email[$key] = array();
		}
		$by_email[$key][] = $item;
	}
	return $by_email;
}

?>