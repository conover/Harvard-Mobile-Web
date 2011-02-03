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
		$count = 0;
		
		foreach($items as $item){

			// capture each piece of the description
			$matches = array();
			preg_match_all('/<td.*?>(.*?)<\/td>/is', $item->get_description(), $matches);
			
			//stip unwanted tags
			foreach($matches[1] as &$m){
				$m = preg_replace( '/<br.*?>/', '', $m);
				$m = preg_replace( '/[\n\r]/', '', $m);
			}
			
			$video = '';
			if(empty($matches[1]) || count($matches[1])<5 ){
				$video = "<!-- error parsing content -->";
			} else {
				$c = $matches[1];
				
				preg_match_all('/<div.*?>(.*?)<\/div>/is', $c[1], $matches);
				$title = $matches[1][0];
				$description = $matches[1][1];
				
				$video .= '<h3>' . $title . '</h3>';
				$video .= '<div class="icon">' . $c[0] . '</div>';
				$video .= '<div class="desc">' . $description . '</div>';
				$video .= '<div class="foot">' . $c[2] . $c[3] . $c[4] . '</div>';
				++$count;
			}
			$videos[] = $video;
		}
		
		if($count < 1){
			$videos = false;
		}
		
		$this->assign('videos', $videos);
	}
}