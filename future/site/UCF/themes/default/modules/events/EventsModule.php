<?php
/**
 * UCF Mobile Events
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class EventsModule extends Module {
	protected $id = 'events';
	
	function initialize(){
		parent::initialize();
		$this->rss_arg = 'format=rss';
		$this->options = $GLOBALS['siteConfig']->getSection($this->id);
	}
	
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
	
	function todayPage(){
		$q_str = date($this->options['EVENTS_DAY']);
		$url   = $this->options["EVENTS_URL"].'?'.$q_str.'&'.$this->rss_arg;
		$feed  = $this->getFeed($url);
		
		$this->assign('events', $feed->items());
		$this->setPageTitle('Today\'s Events');
	}
	
	function tomorrowPage(){
		$tomorrow = 86400 + time();
		$q_str = date($this->options['EVENTS_DAY'], $tomorrow);
		$url   = $this->options["EVENTS_URL"].'?'.$q_str.'&'.$this->rss_arg;
		$feed  = $this->getFeed($url);
		
		$this->assign('events', $feed->items());
		$this->setPageTitle('Tomorrow\'s Events');
	}
	
	function upcomingPage(){
		$q_str = date($this->options['EVENTS_UPCOMING']);
		$url   = $this->options["EVENTS_URL"].'?'.$q_str.'&'.$this->rss_arg;
		$feed  = $this->getFeed($url);
		
		$this->assign('events', $feed->items());
		$this->setPageTitle('Upcoming Events');
	}
	
	function initializeForPage(){
		$list = $this->getArg('list');
		switch($list){
			case 'index':
			case 'today':
				$this->todayPage();
				break;
			case 'tomorrow':
				$this->tomorrowPage();
				break;
			case 'upcoming':
				$this->upcomingPage();
				break;
		}
		
	}
}