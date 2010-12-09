<?php
/**
 * @author Jared Lang
 **/
class NewsModule extends UCFModule{
	protected $id    = 'news';
	protected $feeds = array();
	
	function getFeedBySlug($slug){
		foreach ($this->feeds as $index => $feed){
			if ($feed['SLUG'] == $slug){
				return $this->getFeed($index);
			}
		}
		return null;
	}
	
	function getFeed($index){
		if (isset($this->feeds[$index])) {
			$feedData = $this->feeds[$index];
			$controller = RSSDataControllerUCF::factory($feedData);
			$controller->setDebugMode($GLOBALS['siteConfig']->getVar('DATA_DEBUG'));
			return $controller;
		} else {
			throw new Exception("Error getting news feed for index $index");
		}
	}
	
	function initialize(){
		parent::initialize();
		$this->feeds      = $this->loadFeedData();
		$this->maxPerPage = $GLOBALS['siteConfig']->getVar('NEWS_MAX_RESULTS');
		
		$this->feedIndex = $this->getArg('section', 0);
		if (!isset($this->feeds[$this->feedIndex])) {
		  $this->feedIndex = 0;
		}
		
		$this->feed = $this->getFeed($this->feedIndex);
	}
	
	function indexPage(){
		$slug = $this->getSlugFromURL();
		if (!$slug){
			$slug = $GLOBALS['siteConfig']->getVar('NEWS_DEFAULT_FEED');
			$this->redirectTo($slug);
		}
		
		$page  = $this->getArg('page', 0);
		$limit = $GLOBALS['siteConfig']->getVar('NEWS_ITEMS_PER_PAGE');
		$start = $page * $limit;
		
		$feed     = $this->getFeedBySlug($slug);
		if(!$feed){
			#raise 404
			$this->redirectToModule('error', array('code'=>'notfound', 'url'=>$_SERVER['REQUEST_URI']));
			return;
		}
		$articles = $feed->items($start, $limit, $total);
		
		$page = array(
			'hasNext' => ($start + $limit < $total),
			'hasPrev' => ($start > 0),
			'current' => $page,
		);
		
		foreach($articles as $index=>$article){
			$article->url = $this->buildURL('article', array(
				'id'  => $article->getGUID(),
			));
			$article->image   = $article->getImage();
			$articles[$index] = $article;
		}
		
		
		$this->assign('page', $page);
		$this->assign('articles', $articles);
		$this->assign('feed', $feed);
		$this->page = 'index';
		$this->setPageTitle($feed->title);
		return;
	}
	
	function getSlugFromURL(){
		$suburl  = $GLOBALS['parts'][1];
		$matched = preg_match('/([^\/]+)\//i', $suburl, $matches);
		if ($matched){
			$slug = $matches[1];
			return $slug;
		}else{
			error_log("Couldn't parse feed slug from url: '$suburl'");
			return null;
		}
	}
	
	function feedsPage(){
		$slug  = $this->getSlugFromURL();
		$cfeed = $this->getFeedBySlug($slug);
		$feeds = array();
		foreach($this->feeds as $index => $feed){
			$feeds[$index] = array(
				'title' => $feed['TITLE'],
				'feed'  => $feed['BASE_URL'],
				'slug'  => $feed['SLUG'],
				'url'   => $this->buildURL($feed['SLUG']),
			);
		}
		
		$this->assign('feeds', $feeds);
		$this->assign('cfeed', $cfeed);
		$this->setPageTitle('Categories');
		return;
	}
	
	function articlePage(){
		$slug = $this->getSlugFromURL();
		$feed = $this->getFeedBySlug($slug);
		
		$article = $this->getArg('id', null);
		$article = $feed->getItem($article);
		
		$this->assign('feed', $feed);
		$this->assign('article', $article);
		$this->setPageTitle($article->getTitle());
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
