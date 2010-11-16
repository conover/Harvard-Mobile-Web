<?php
/**
* UCF Mobile Map
* Web Communications - Fall 2010
*/

require_once realpath(LIB_DIR.'/Module.php');

class MapModule extends Module {
  protected $id = 'map';
  protected function initializeForPage() {
    
    //deconstruct URL
    $url = $_SERVER["REQUEST_URI"];
    
    if(preg_match('/options\/$/', $url)){
      
      $this->options();
      
    } else {
      
      $this->map();
    }
    
    
  }
  
  protected function map() {
    $this->page = "map";
    
    $this->addExternalJavascript('http://maps.google.com/maps/api/js?sensor=false');
    
    $url = URL_PREFIX . 'map/options/';
    $js=<<<JS
    
      // the only variable exposed to the window should be Campus_Map
      var Campus_Map = { };
      Campus_Map.device = "$this->platform";
      Campus_Map.urls   = {
        "map-options" : "$url"
      }
JS;
    $this->addInlineJavascript($js);
    $this->addInlineJavascriptFooter("Campus_Map.gmap();");
    
  }
  
  
  protected function options() {
    $this->page = "options";
  }
  
  
}