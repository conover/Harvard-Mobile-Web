<?php
/**
 * @author Jared Lang
 **/
class NewsModule extends UCFModule{
	protected $id    = 'news';
	protected $feeds = array();
	
	function getFeed(){
		$slug = $this->getSlugFromURL();
		if (array_key_exists($slug, $this->feeds)){
			$feed = $this->feeds[$slug];
		}else{
			$feed = current($this->feeds);
			$this->redirectTo($this->sluggify($feed->get_title()));
		}
		$feed->handle_content_type();
		$this->feed = $feed;
	}
	
	function getFeeds(){
		foreach($this->options['NEWS_FEEDS'] as $url){
			$feed = new SimplePie();
			$feed->set_feed_url($url);
			$feed->set_cache_location(CACHE_DIR);
			$feed->init();
			$slug = $this->sluggify($feed->get_title());
			$this->feeds[$slug] = $feed;
		}
	}
	
	function initialize(){
		parent::initialize();
		$this->getFeeds();
		$this->getFeed();
	}
	
	function indexPage(){
		$page  = $this->getArg('page', 0);
		$limit = $this->options['NEWS_ITEMS_PER_PAGE'];
		$start = $page * $limit;
		
		$feed     = $this->feed;
		$total    = $feed->get_item_quantity();
		$articles = $feed->get_items($start, $limit);
		
		$page = array(
			'hasNext' => ($start + $limit < $total),
			'hasPrev' => ($start > 0),
			'current' => $page,
		);
		
		foreach($articles as $index=>$article){
			$article->url = $this->buildURL('article', array(
				'id'  => $index,
			));
			$article->image   = $article->get_enclosure();
			$articles[$index] = $article;
		}
		
		
		$this->assign('page', $page);
		$this->assign('articles', $articles);
		$this->assign('feed', $feed);
		$this->page = 'index';
		$this->setPageTitle($feed->get_title());
		return;
	}
	
	function feedsPage(){
		$feeds = $this->feeds;
		$cfeed = $this->feed;
		
		$this->assign('feeds', $feeds);
		$this->assign('cfeed', $cfeed);
		$this->setPageTitle('Categories');
		return;
	}
	
	function articlePage(){
		$feed    = $this->feed;
		$id      = $this->getArg('id', null);
		$article = $feed->get_item($id);
		
		$this->assign('feed', $feed);
		$this->assign('article', $article);
		$this->setPageTitle($article->get_title());
		return;
	}
	
	function initializeForPage(){
		switch($this->page){
			case 'feeds':
				$this->feedsPage();
				break;
			case 'article':
				$this->articlePage();
				break;
			default:
				#List stories of feed, if feed is not defined, redirect to default
				$this->indexPage();
		}
	}
} // END class


// Strip Image Dimensions
// Helper function called in template.  
// When width and height are set with html attributes, 
// it becomes difficult to style with CSS and breaks layout
function strip_img_dimensions($str=""){
	$str = preg_replace('/<img([^>]+)width="[^"]+"([^>]*)>/i',  '<img$1$2>', $str);
	$str = preg_replace('/<img([^>]+)height="[^"]+"([^>]*)>/i', '<img$1$2>', $str);
	return $str;
}
