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
		// $this->addExternalJavascript('http://maps.google.com/maps/api/js?sensor=false');
		// $this->addExternalJavascript('http://library.ucf.edu/Web/JS/mootools-1.2.5.js');
		// $this->addExternalJavascript('http://library.ucf.edu/Web/JS/Main.js');
		// $this->addExternalJavascript('http://library.ucf.edu/Web/JS/Maps.js');
		
		if($this->page == 'service'){
			$url = "http://library.ucf.edu/web/db.php?q=publicmaptypes";
			$content = $this->fetchHTTP($url);
			header('Content-type: application/json');
			exit($content);
		}
		
		$foo = "Hello World!";
		$this->assign('foo', $foo);
	}

}