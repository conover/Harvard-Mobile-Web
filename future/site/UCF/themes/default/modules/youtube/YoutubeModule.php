<?php
/**
 * UCF Mobile - Youtube
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class YoutubeModule extends UCFModule {
	
	protected $id = 'youtube';
	
	function initializeForPage(){
		
		
		$feed = new SimplePie();
		$feed->set_feed_url('http://gdata.youtube.com/feeds/base/users/UCF/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile');
		$feed->set_cache_location(CACHE_DIR);
		$feed->init();
		$this->assign('videos', $feed->get_items());
		

	}
}