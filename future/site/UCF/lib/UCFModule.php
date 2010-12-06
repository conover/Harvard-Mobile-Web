<?php

abstract class UCFModule extends Module{
	/**
	 * Retreives data pointed to by URL.
	 *
	 * @return string
	 * @author Jared Lang
	 **/
	function fetchHTTP($url){
		$timeout = $GLOBALS['siteConfig']->getVar('HTTP_TIMEOUT');
		$options = stream_context_create(array(
			'http' => array(
				'timeout' => (string)$timeout,
			),
		));
		$data = file_get_contents($url, false, $options);
		return $data;
	}
}

?>