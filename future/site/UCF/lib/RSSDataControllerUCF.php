<?php
/**
 * undocumented class
 *
 * @package default
 * @author Jared Lang
 **/
class RSSDataControllerUCF extends RSSDataController{
	public static function factory($args){
		$controller = parent::factory($args);
		if (isset($args['TITLE'])){
			$controller->title = $args['TITLE'];
		}
		if (isset($args['SLUG'])){
			$controller->slug = $args['SLUG'];
		}
		return $controller;
	}
} // END class 
?>