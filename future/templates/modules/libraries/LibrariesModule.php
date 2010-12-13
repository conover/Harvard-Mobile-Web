<?php

require_once realpath(LIB_DIR.'/Module.php');
require_once realpath(LIB_DIR.'/feeds/LibrariesInfo.php');

define('LIBRARY_LIBRARIES_COOKIE', 'libraries');
define('LIBRARY_LIBRARY_ITEMS_COOKIE', 'libraryItems');


class LibrariesModule extends Module {
  protected $id = 'libraries';
  
  private function getBookmarks() {
    $bookmarks = array();
    
    return $bookmarks;
  }
  
  private function detailURL($item) {
    $details = array(
      'id'      => $item['itemId'],
      'title'   => $item['title'],
      'creator' => $item['creator'],
      'date'    => $item['date'],
      'edition' => $item['edition'],
      'format'  => $item['format']['formatDetail'],
      'type'    => $item['format']['typeDetail'],
      'online'  => $item['isOnline'] != 'NO',
    );
  
    return $this->buildBreadcrumbURL('detail', array(
      'item' => serialize($details),
    ));
  }
  
  protected function initializeForPage() {
    switch ($this->page) {
      case 'index':
        $indexConfig = $this->loadWebAppConfigFile('libraries-index', 'indexConfig');
        
        $searchPlaceholder = '';
        $sections = array();
        foreach ($indexConfig as $sectionName => $config) {
          if ($sectionName == 'search') {
            $searchPlaceholder = $config['searchPlaceholder'];
            continue;
          }
        
          $section = array();
          foreach ($config['titles'] as $i => $title) {
            $entry = array('title' => $title);
            
            $params = array();
            if (isset($config['bookmarkTypes']) && $config['bookmarkTypes'][$i]) {
              $section['bookmarks'] = true;
              $params['type'] = $config['bookmarkTypes'][$i];
            }
            $entry['url'] = $this->buildBreadcrumbURL($config['pages'][$i], $params);
            
            $section['items'][] = $entry;
          }
          $sections[] = $section;
        }
        
        $this->assign('searchPlaceholder', $searchPlaceholder);
        $this->assign('sections',          $sections);
        break;
      
      case 'detail':
        $item = unserialize($this->getArg('item'));
        if ($item && is_array($item)) {
          $data = Libraries::getFullAvailability($item['id']);
          error_log(print_r($data, true));
          
          $locations = array();
          /*foreach ($data as $entry) {
            $itemsAtLocation = array();
            foreach ($entry['itemsByStat'] as $statItems) {
              $statTypes = array(
                'availableItems', 'checkedOutItems', 'unavailableItems', 'collectionOnlyItems'
              );
              foreach ($statTypes as $statType) {
                if (isset($statItems[$statType]) && count($statItems[$statType])) {
                  foreach ($statItems[$statType] as $item) {
                    if ($statType == 'collectionOnlyItems') {
                      if (!isset($itemsAtLocation['collection'])) {
                        $itemsAtLocation['collection'] = array(
                          'status' => 'in-library use',
                          'count' => 0,
                        );
                      }
                      $itemsAtLocation['collection'][] = array(
                        'status' => 'collection only',
                      );
                    } else {
                      
                    }
                  }
                }
              }
            }
            
            $location = array(
              'type'  => $entry['type'],
              'name'  => $entry['name'],
              'items' => $itemsAtLocation,
            );
          }*/
          
          $item['cookie'] = LIBRARY_LIBRARY_ITEMS_COOKIE;
          $item['bookmarked'] = false;
          if (isset($_COOKIE[LIBRARY_LIBRARY_ITEMS_COOKIE])) {
            $bookmarks = explode(',', $_COOKIE[LIBRARY_LIBRARY_ITEMS_COOKIE]);
            $item['bookmarked'] = in_array($item['id'], $bookmarks);
          }
          
          $this->assign('locations', $locations);
          $this->assign('item', $item);
        } else {
          $this->redirectTo('index');
        }
        break;
        
      case 'search':
        $results = array();
        $searchTerms = trim($this->getArg('filter'));
        
        if ($this->args['filter']) {
          $data = Libraries::searchItems($searchTerms);
          error_log(print_r($data, true));
          $results = array();
          foreach ($data as $entry) {
            $results[] = array(
              'title'   => $entry['title'],
              'creator' => $entry['creator'],
              'date'    => $entry['date'],
              'format'  => strtolower($entry['format']['formatDetail']),
              'url'     => $this->detailURL($entry),
            );
          }
        
          $this->assign('searchTerms', $searchTerms);
          $this->assign('resultCount', count($results));
          $this->assign('results',     $results);

        } else {
          $this->redirectTo('index');
        }
        break;
        
      case 'advanced_search':
        break;
        
      case 'bookmarks':
        break;
        
      case 'libraries':
        $data = Libraries::getAllLibraries();
        error_log(print_r($data, true));
        
        $libraries = array();
        foreach ($data as $entry) {
          $libraries[] = array(
            'title' => $entry['name'],
            'url' => $this->libraryURL($entry),
          );
        }
        
        break;
        
      case 'archives':
        break;
        
      case 'locationAndHours':
        break;
        
      case 'links':
        break;
        
      case 'contact':
        break;
    }
  }
}
