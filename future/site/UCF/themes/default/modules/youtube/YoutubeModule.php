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
		$feed->set_timeout($GLOBALS['siteConfig']->getVar('HTTP_TIMEOUT'));
		$feed->set_feed_url('http://gdata.youtube.com/feeds/base/users/UCF/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile');
		$feed->set_cache_location(CACHE_DIR);
		$feed->init();
		$items = $feed->get_items();
		$videos = array();
		
		foreach($items as $item){
			
			$video = array();
			
			// capture each piece of the description
			$matches = array();
			preg_match_all('/<td.*?>(.*?)<\/td>/is', $item->get_description(), $matches);
			
			//stip unwanted tags
			foreach($matches[1] as &$m){
				$m = preg_replace( '/<br.*?>/', '', $m);
				$m = preg_replace( '/[\n\r]/', '', $m);
			}
			
			
			if(empty($matches[1]) || count($matches[1])<5 ){
				$video['error'] = true;
			} else {
				$c = $matches[1];
				$video['foot']  = $c[2] . $c[3] . $c[4];
				
				preg_match('/<img[^>]+?>/', $c[0], $matches);
				if(!empty($matches)){
					$video['icon']  = $matches[0];
				}
				
				preg_match_all('/<div.*?>(.*?)<\/div>/is', $c[1], $matches);
				$video['desc']  = $matches[1][1];
				$title = $matches[1][0];
				preg_match('/>([^<]+?)</', $title, $matches); // strip link
				if(!empty($matches)){
					$video['title'] = $matches[1];
				}
			}
			
			
			$link = $item->get_link();
			$video['link'] = $link;
			preg_match('/\?v=([^&]+)&/', $link, $matches);
			if(!empty($matches)){
				$video['id'] = $matches[1];
			}
			
			$videos[] = $video;
			
		}
		
		$this->assign('videos', $videos);
	}
}