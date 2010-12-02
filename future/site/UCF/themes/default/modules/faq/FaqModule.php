<?php
/**
 * UCF Mobile FAQ
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class FaqModule extends Module {
	
	protected $id = 'faq';
	
	function getFeed($url){
		$dummy = array(
			'TITLE'            => "None",
			'SLUG'             => "none",
			'BASE_URL'         => $url,
			'CONTROLLER_CLASS' => "GazetteRSSController",
			'ITEM_CLASS'       => "GazetteRSSItem",
			'ENCLOSURE_CLASS'  => "GazetteRSSEnclosure",
			'MEDIAGROUP_CLASS' => "GazetteRSSMediaGroup",
		);
		$feed = RSSDataController::factory($dummy);
		return $feed;
		
	}
	
	function initialize(){
		$this->options = $GLOBALS['siteConfig']->getSection($this->id);
	}
	
	function search($q){
		$url     = $this->options['FAQ_URL'];
		$qstring = str_replace('%q', urlencode($q), $this->options['FAQ_QUERY']);
		$feed    = $this->getFeed($url.'?'.$qstring);
		return $feed->items();
	}
	
	function initializeForPage(){
		$q = $this->getArg('q', '');
		if ($q != ''){
			$items = $this->search($q);
			$this->assign('items', $items);
			$this->assign('q', $q);
		}else{
			$this->assign('items', array());
			$this->assign('q', null);
		}
		
		
	}
}