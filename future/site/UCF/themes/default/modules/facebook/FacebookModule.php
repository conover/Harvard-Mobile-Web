<?php
/**
 * UCF Mobile - Facebook
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class FacebookModule extends UCFModule {
	protected $id = 'facebook';
	
	public function initializeForPage(){
		header('Location:'.$this->options['FACEBOOK_PAGE']);
	}
}