<?php
/**
 * UCF Mobile Emergency
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class EmergencyModule extends UCFModule {
	
	protected $id     = 'emergency';
	protected $status = False;
	protected $feed   = null;
	
	/**
	 * Pulls emergency status from status page and sets instance attribute to
	 * it's value.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function getEmergencyStatus()
	{
		$url          = $this->options['EMERGENCY_STATUS_URL'];
		$status       = $this->fetchHttp($url);
		$this->status = strtolower(trim($status)) === 'true';
	}
	
	/**
	 * Pulls emergency feed and sets instance attribute to feed object.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	function getEmergencyFeed()
	{
		$url  = $this->options['EMERGENCY_RSS_URL'];
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_cache_location(CACHE_DIR);
		$feed->init();
		$this->feed = $feed;
	}
	
	
	function initialize()
	{
		parent::initialize();
		$this->getEmergencyStatus();
		$this->getEmergencyFeed();
	}
	
	function initializeForPage()
	{
		if ($this->status === True){
			$emergency = $this->feed->get_item(0);
			$this->assign('emergency', $emergency);
		}
	}
}