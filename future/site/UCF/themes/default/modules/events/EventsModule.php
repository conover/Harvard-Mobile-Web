<?php
/**
 * UCF Mobile Events
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class EventsModule extends UCFModule {
	protected $id = 'events';
	
	function initialize(){
		parent::initialize();
		$this->rss_arg = 'format=rss';
		$this->options = $GLOBALS['siteConfig']->getSection($this->id);
	}
	
	function getFeed($url){
		$feed = new SimplePie();
		$feed->set_item_class('UCFEventSimplePie_Item');
		$feed->set_feed_url($url);
		$feed->set_cache_location(CACHE_DIR);
		$feed->set_cache_duration($GLOBALS['siteConfig']->getVar('DEFAULT_CACHE_LIFESPAN'));
		$feed->init();
		return $feed;
	}
		
	function getNeighbors($stamp){
		$next  = $stamp + 86400;
		$prev  = $stamp - 86400;
		return array($next, $prev);
	}
	
	
	function dayPage($day){
		list($next, $prev) = $this->getNeighbors($day);
		$q_arg = strftime($this->options['EVENTS_DAY'], $day);
		$url   = $this->options["EVENTS_URL"].'?'.$q_arg.'&'.$this->rss_arg;
		$feed  = $this->getFeed($url);
		
		$this->assign('next', $next);
		$this->assign('prev', $prev);
		$this->assign('now', $day);
		$this->assign('events', array_reverse($feed->get_items()));
		$this->setPageTitle('Events for '.date('M j', $day));
	}
	
	function todayPage(){
		$day = time();
		$this->dayPage($day);
		$this->setPageTitle('Today\'s Events');
	}
	
	function tomorrowPage(){
		$day = 86400 + time();
		$this->dayPage($day);
		$this->setPageTitle('Tomorrow\'s Events');
	}
	
	function upcomingPage(){
		$q_str = $this->options['EVENTS_UPCOMING'];
		$url   = $this->options["EVENTS_URL"].'?'.$q_str.'&'.$this->rss_arg;
		$feed  = $this->getFeed($url);
		
		$this->assign('events', array_reverse($feed->get_items()));
		$this->setPageTitle('Upcoming Events');
	}
	
	function searchPage(){
		$q     = $this->getArg('q', '');
		$q_str = str_replace('%q', $q, $this->options['EVENTS_SEARCH']);
		$url   = $this->options['EVENTS_URL'].'?'.$q_str.'&'.$this->rss_arg;
		$feed  = $this->getFeed($url);
		
		$this->assign('events', array_reverse($feed->get_items()));
		$this->assign('search_q', $q);
		$this->setPageTitle('Search UCF Events');
	}
	
	function initializeForPage(){
		$list = $this->getArg('list');
		switch($list){
			default:
			case 'today':
				$this->todayPage();
				break;
			case 'tomorrow':
				$this->tomorrowPage();
				break;
			case 'upcoming':
				$this->upcomingPage();
				break;
			case 'day':
				if (!$day = intval($this->getArg('day', null))){
					todayPage();
				}else{
					$this->dayPage($day);
				}
				break;
			case 'search':
				$this->searchPage();
				break;
		}
		
	}
}