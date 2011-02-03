<?php
class HomeModule extends UCFModule {
  protected $id = 'home';
	 
  protected function initializeForPage() {
	switch ($this->page) {
		case 'help':
			break;
		
		case 'index':
			$this->loadWebAppConfigFile('libarry-index', 'library');
			break;
	}
  }
}
?>