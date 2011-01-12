<?php
class HomeModule extends UCFModule {
  protected $id = 'home';
	 
  protected function initializeForPage() {
	switch ($this->page) {
		case 'help':
			break;
		
		case 'index':
			$this->loadWebAppConfigFile('home-index', 'home');
			$whatsNewCount = 0;
			$modules = array();
			$secondaryModules = array();
		
			foreach ($this->getHomeScreenModules() as $id => $info) {
				if (!$info['disabled']) {
					$module = array(
						'title' => $info['title'],
						'fancy' => $info['fancy'],
						'description' => $info['description'],
						'opt' => ''.@$info['opt'],
						'url' => isset($info['url']) ? $info['url'] : "/$id/",
						'img' => isset($info['img']) ? $info['img'] : "/modules/{$this->id}/images/$id.png",
					);
					if ($id == 'events'){
						$day           = date('j');
						$module['img'] = "/modules/{$this->id}/images/dates/date-{$day}.png";
					}
					
					if ($id == 'about' && $whatsNewCount > 0) {
						$module['badge'] = $whatsNewCount;
					}
					if ($info['primary']) {
						$modules[] = $module;
					} else {
						$module['class'] = 'utility';
						$secondaryModules[] = $module;
					}
				}
			}
			if (count($modules) && count($secondaryModules)) {
				$modules[] = array('separator' => true);
			}
			$modules = array_merge($modules, $secondaryModules);
			
			$this->assign('modules', $modules);
			$this->assign('topItem', null);
			break;
	}
  }
}
?>