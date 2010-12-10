<?php
/**
 * UCF Mobile FAQ
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class FaqModule extends UCFModule {
	
	protected $id         = 'faq';
	protected $categories = array();
	protected $category   = null;
	protected $default_cat= array(
		'id'         => 0,
		'name'       => 'All Categories',
		'slug'       => 'all-categories',
		'_long_id'   => '',
		'_long_name' => '',
	);
	
	function getFeed($url){
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_cache_location(CACHE_DIR);
		$feed->init();
		return $feed;
		
	}
	
	function getCategory(){
		$slug = $this->getSlugFromURL();
		
		if ($slug != null and array_key_exists($slug, $this->categories)){
			$this->category = $this->categories[$slug];
		}else{
			$this->category = null;
		}
		$this->assign('category', $this->category);
	}
	
	function initialize(){
		$this->options = $GLOBALS['siteConfig']->getSection($this->id);
		$this->getCategories();
		$this->getCategory();
	}
	
	function getCategories(){
		$url     = $this->options['FAQ_URL'].$this->options['FAQ_CATS'];
		$content = $this->fromCache($url);
		
		$glue_re      = "[,\s]+";
		$id_regex     = "(?P<id>[\d]+)";
		$name_re      = "['\"](?P<name>[^'\"]+)['\"]";
		$full_id_re   = "['\"](?P<long_id>[^'\"]+)['\"]";
		$full_name_re = "['\"](?P<long_name>[^'\"]+)['\"]";
		$category_re  = "_do_search\([\s]*{$id_regex}{$glue_re}{$full_id_re}{$glue_re}{$full_name_re}{$glue_re}{$name_re}[\s]*\)";
		$found        = preg_match_all("/{$category_re}/", $content, $matches);
		
		if (!$found){return;}
		$categories = array($this->default_cat['slug'] => $this->default_cat);
		foreach ($matches[0] as $key=>$match){
			$slug = $this->sluggify($matches['name'][$key]);
			$categories[$slug] = array(
				'id'         => $matches['id'][$key],
				'name'       => $matches['name'][$key],
				'slug'       => trim($slug),
				'_long_id'   => $matches['long_id'][$key],
				'_long_name' => $matches['long_name'][$key],
				
			);
		}
		$categories = array_filter($categories, create_function('$c', '
			return count(explode(",", $c["_long_id"])) < 2;
		'));
		$this->categories = $categories;
	}
	
	function search($q){
		$url     = $this->options['FAQ_URL'].$this->options['FAQ_SEARCH'];
		$qstring = str_replace('%q', urlencode($q), $this->options['FAQ_SEARCH_ARG']);
		
		if (
			$this->category != null and
			array_key_exists($this->category['slug'], $this->categories) and
			$this->category != $this->default_cat
			){
			$id       = $this->category['id'];
			$qstring .= '&'.str_replace('%category', $id, $this->options['FAQ_CAT_ARG']);
		}
		
		$url     = $url.'?'.$qstring;
		$feed    = $this->getFeed($url);
		return $feed->get_items();
	}
	
	function indexPage(){
		if ($this->category == null){
			$this->redirectTo($this->default_cat['slug']);
		}
		$this->page = 'index';
		$q          = $this->getArg('q', '');
		$items      = $this->search($q);
		
		if ($q === ''){
			$items = array_slice($items, 0, 10);
		}
		$this->assign('items', $items);
		$this->assign('q', $q);
	}
	
	function categoryPage(){
		$this->assign('categories', $this->categories);
	}
	
	function answerPage(){
		$o_url = $this->getArg('url', '');
		$q     = $this->getArg('q', '');
		$url   = str_replace('std_adp.php', 'prnt_adp.php', $o_url);
		$page  = $this->fromCache($url);

		$quote = "['\"]"; #Double or single quotes
		
		#Find question
		$open  = "<td[\s]+class={$quote}textcell{$quote}[\s]+id={$quote}desc{$quote}>";
		$close = "<\/td>";
		$found = preg_match("/{$open}(.*){$close}/isU", $page, $match);
		if ($found){
			$question = $match[1];
		}
		
		#Find answer
		$open  = "<td[\s]+class={$quote}textcell{$quote}[\s]+id={$quote}soln{$quote}>";
		$close = "<\/td>";
		$found = preg_match("/{$open}(.*){$close}/is", $page, $match);
		if ($found){
			$answer = $match[1];
		}
		
		if (!$answer and !$question){
			$question = 'Oops!';
			$answer = 'We were unable to pull the information for this answer, but here is the <a href="'.$o_url.'">link to the original</a>.';
		}
		
		$this->assign('url', $url);
		$this->assign('q', $q);
		$this->assign('question', $question);
		$this->assign('answer', $answer);
		
	}
	
	function initializeForPage(){
		switch($this->page){
			case 'categories':
				$this->categoryPage();
				break;
			case 'answer':
				$this->answerPage();
				break;
			default:
				$this->indexPage();
				break;
		}
		
		
	}
}