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
		
		
		#todo, build out error module so it can send an email if issue encountered
		
		
		$content = $this->fromCache('http://m.youtube.com/ucf');
		$matches = Array();
		preg_match('/(Videos \([0-9]+\))[^>]+>([\s\S]+<\/div>)[\s\S]+<span[^>]+>[^>]+>Next page/', $content, $matches);
		$content = $matches[2];
		$content = preg_replace('/<hr[^>]*>/', '', $content);
		$content = preg_replace('/style="[^"]+"/', '', $content);
		$content = preg_replace('/width="[^"]+"/', '', $content);
		$content = preg_replace('/height="[^"]+"/', '', $content);
		$content = preg_replace('/valign="[^"]+"/', '', $content);
		$content = preg_replace('/href="\//', 'href="http://m.youtube.com/', $content);
		$content = str_replace("w=40&amp;h=30", "w=160&amp;h=120", $content);
		$content = str_replace("<table", '<div class="block"><table', $content);
		$content = str_replace("table>", 'table></div>', $content);
		
		$this->assign('count',  $matches[1]);
		$this->assign('content', $content);
	}
}