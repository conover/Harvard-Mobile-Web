<?php

abstract class UCFModule extends Module{
	/**
	 * Returns url slug for given string
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function sluggify($text)
	{
		$slug = $text;
		$slug = trim($slug);
		$slug = strtolower($slug);
		$slug = preg_replace('/[^A-Z1-9\s\-]/i', '', $slug);
		$slug = preg_replace('/[\s]+/', ' ', $slug);
		$slug = str_replace(array(' ', '.'), array('-', '-'), $slug);
		return $slug;
	}
	
	/**
	 * Returns the slug from the url, if found
	 *
	 * @return string, null on failure
	 * @author Jared Lang
	 **/
	function getSlugFromURL()
	{
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
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function initialize()
	{
		$this->options = $GLOBALS['siteConfig']->getSection($this->id);
		$this->cache   = new DiskCache(
			$this->cacheFolder(),
			$GLOBALS['siteConfig']->getVar('DEFAULT_CACHE_LIFESPAN'),
			TRUE
		);
	}
	
	/**
	 * Retreives data pointed to by URL.
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function fetchHTTP($url)
	{
		$timeout = $GLOBALS['siteConfig']->getVar('HTTP_TIMEOUT');
		$options = stream_context_create(array(
			'http' => array(
				'timeout' => (string)$timeout,
			),
		));
		$data = @file_get_contents($url, false, $options);
		return $data;
	}
	
	/**
	 * Return the contents of a given url using framework's caching system.
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function fromCache($url)
	{
		$key = $this->cacheKey($url);
		
		if (!$this->cache->isFresh($key)){
			$data = $this->fetchHttp($url);
			$this->setCache($key, $data);
		}
		return $this->getCache($key);
	}
	
	
	/**
	 * Define where this module's cache belongs.
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function cacheFolder()
	{
		return CACHE_DIR.'/'.urlencode($this->id);
	}
	
	
	/**
	 * Return a unique key for the given url.
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function cacheKey($url)
	{
		$key = md5($url);
		return $key;
	}
	
	
	function setCache($key, $data){
		$this->cache->write($data, $key);
	}
	
	/**
	 * Return contents of url, creating new version of cache if out of date or
	 * non-existent
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function getCache($key)
	{
		return $this->cache->read($key);
	}
}

?>