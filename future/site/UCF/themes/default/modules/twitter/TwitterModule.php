<?php
/**
 * UCF Mobile - Twitter
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */


class TwitterModule extends UCFModule {
	protected $id = 'twitter';
	
	public function initializeForPage(){
		#Pull in TWITTER_ options to current scope
		extract($this->options);
		
		$api_url   = '/statuses/friends_timeline.json';
		$cache_key = $this->cacheKey($api_url);
		$cache     = $this->getCache($cache_key);
		if ($cache){
			$tweets = json_decode($cache);
		}else{
			$twitter = new EpiTwitter(
				$TWITTER_CONSUMER_KEY,
				$TWITTER_CONSUMER_SECRET,
				$TWITTER_OAUTH_TOKEN,
				$TWITTER_OAUTH_SECRET
			);
			$response = $twitter->get($api_url);
			$json     = $response->responseText;
			$tweets   = json_decode($json);
			$this->setCache($cache_key, $json);
		}
		
		$this->assign('tweets', array_slice($tweets, 0, $TWITTER_MAX_TWEETS));
	}
}

function linkify($string){
	$words = explode(' ', $string);
	$words = array_map(create_function('$w','
		if (stripos($w, "http") !== False){
			$w = "<a href=\"{$w}\">{$w}</a>";
		}
		return $w;
	'), $words);
	$string = implode(' ', $words);
	return $string;
}

function textual_difference($then){
	$now  = time();
	$diff = $now - $then;
	
	if ($diff < 60){
		$text = 'less than a minute ago';
	}#Seconds
	else if ($diff < 3600){
		$n    = floor($diff/60);
		if ($n != 1){
			$text = "about {$n} minutes ago";
		}else{
			$text = "about {$n} minute ago";
		}
	}#Minutes
	else if ($diff < 86400){
		$n    = floor($diff/3600);
		if ($n != 1){
			$text = "about {$n} hours ago";
		}else{
			$text = "about {$n} hour ago";
		}
	}#Hours
	else{
		$d    = date("F j, Y", $now);
		$text = "on {$d}";
	}#Default
	return $text;
}