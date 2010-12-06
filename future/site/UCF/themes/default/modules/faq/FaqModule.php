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
		$this->options    = $GLOBALS['siteConfig']->getSection($this->id);
		$this->getCategories();
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
		$categories = array();
		foreach ($matches[0] as $key=>$match){
			$categories[] = array(
				'id'        => $matches['id'][$key],
				'name'      => $matches['name'][$key],
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
		$feed    = $this->getFeed($url.'?'.$qstring);
		return $feed->items();
	}
	
	function indexPage(){
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
	
	function answerPage(){
		$url   = $this->getArg('url', '');
		$q     = $this->getArg('q', '');
		$url   = str_replace('std_adp.php', 'prnt_adp.php', $url);
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
		
		$this->assign('url', $url);
		$this->assign('q', $q);
		$this->assign('question', $question);
		$this->assign('answer', $answer);
		
	}
	
	function initializeForPage(){
		switch($this->page){
			default:
			case 'index':
				$this->indexPage();
				break;
			case 'answer':
				$this->answerPage();
				break;
		}
		
		
	}
}