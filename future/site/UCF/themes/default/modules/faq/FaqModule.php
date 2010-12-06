<?php
/**
 * UCF Mobile FAQ
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class FaqModule extends UCFModule {
	
	protected $id = 'faq';
	
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
		$this->options = $GLOBALS['siteConfig']->getSection($this->id);
	}
	
	function search($q){
		$url     = $this->options['FAQ_URL'];
		$qstring = str_replace('%q', urlencode($q), $this->options['FAQ_QUERY']);
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
		$page  = $this->fetchHTTP($url);

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