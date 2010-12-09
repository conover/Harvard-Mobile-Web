<?php

abstract class UCFModule extends Module{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function initialize()
	{
		$this->options = $GLOBALS['siteConfig']->getSection($this->id);
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
		$data = file_get_contents($url, false, $options);
		return $data;
	}
	
	/**
	 * Return the contents of a given url using framework's caching system.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function fromCache($url)
	{
		$contents = $this->getCache($url);
		return $contents;
	}
	
	
	/**
	 * Define where this module's cache belongs.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function cacheFolder()
	{
		return CACHE_DIR.'/'.urlencode($this->id);
	}
	
	
	/**
	 * Return a unique key for the given url.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function cacheKey($url)
	{
		$key = md5($url);
		return $key;
	}
	
	
	/**
	 * Write content to cache defined by key
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function setCache($key, $content)
	{
		$file     = $key;
		$lifespan = $GLOBALS['siteConfig']->getVar('DEFAULT_CACHE_LIFESPAN');
		$cache    = new DiskCache($this->cacheFolder(), $lifespan, TRUE);
		$cache->write($content, $file);
	}
	
	
	/**
	 * Return contents of url, creating new version of cache if out of date or
	 * non-existent
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function getCache($url)
	{
		$key      = $this->cacheKey($url);
		$lifespan = $GLOBALS['siteConfig']->getVar('DEFAULT_CACHE_LIFESPAN');
		$cache    = new DiskCache($this->cacheFolder(), $lifespan, TRUE);
		
		if (!$cache->isFresh($key)){
			$data = $this->fetchHttp($url);
			$cache->write($data, $key);
		}
		return $cache->read($key);
	}
}

?>