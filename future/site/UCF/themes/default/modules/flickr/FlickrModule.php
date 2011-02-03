<?php
/**
 * UCF Mobile - Flickr
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class FlickrModule extends UCFModule {
	protected $id       = 'flickr';
	protected $feed_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=%s&lang=en-us&format=rss_200';
	
	public function initialize(){
		parent::initialize();
		$this->feed_url = sprintf($this->feed_url, $this->options['FLICKR_ID']);
	}
	
	public function initializeForPage(){
		$feed = new SimplePie();
		$feed->set_timeout($GLOBALS['siteConfig']->getVar('HTTP_TIMEOUT'));
		$feed->set_feed_url($this->feed_url);
		$feed->set_cache_location(CACHE_DIR);
		$feed->init();
		
		$items = $feed->get_items();
		$this->assign('items', $items);
	}
}