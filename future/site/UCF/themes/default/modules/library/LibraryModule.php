<?php
/**
 * UCF Mobile - Library
 * 
 * @author UCF Web Communications
 * @author Douglas Beck
 * @author Jared Lang
 */
class LibraryModule extends UCFModule {
	
	protected $id = 'library';
	
	function initializeForPage(){
		$foo = "Hello World!";
		$this->assign('foo', $foo);
	}

}