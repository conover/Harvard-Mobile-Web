<?php

require_once realpath(LIB_DIR.'/Module.php');
require_once realpath(LIB_DIR.'/feeds/LibrariesInfo.php');

define('LIBRARY_LIBRARIES_COOKIE', 'libraries');
define('LIBRARY_ARCHIVES_COOKIE', 'archives');
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
  
  private function locationAndHoursURL($type, $id, $name) {
    return $this->buildBreadcrumbURL('locationAndHours', array(
      'type' => $type,
      'id'   => $id,
      'name' => $name,
    ));
  }
  
  private function fullHoursURL($type, $id, $name) {
    return $this->buildBreadcrumbURL('fullHours', array(
      'type' => $type,
      'id'   => $id,
      'name' => $name,
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
            $section['items'][] = array(
              'title' => $title,
              'url'   => $config['urls'][$i],
            );
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
          //error_log(print_r($data, true));
          
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
          //error_log(print_r($data, true));
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
        //error_log(print_r($data, true));
        
        $libraries = array();
        foreach ($data as $entry) {
          $libraries[] = array(
            'title' => $entry['name'],
            'url' => $this->locationAndHoursURL('library', $entry['id'], $entry['name']),
          );
        }
        
        $this->assign('libraries', $libraries);
        break;
        
      case 'archives':
        $data = Libraries::getAllArchives();
        //error_log(print_r($data, true));
        
        $archives = array();
        foreach ($data as $entry) {
          $archives[] = array(
            'title' => $entry['name'],
            'url' => $this->locationAndHoursURL('archive', $entry['id'], $entry['name']),
          );
        }
        
        $this->assign('archives', $archives);
        break;
        
      case 'locationAndHours':
        $type = $this->getArg('type');
        $id = $this->getArg('id');
        $name = $this->getArg('name');
        
        if ($type == 'library') {
          $data = Libraries::getLibraryDetails($id, $name);
          //error_log(print_r($data, true));
          
        } else if ($type == 'archive') {
          $data = Libraries::getArchiveDetails($id, $name);
          //error_log(print_r($data, true));
        
        } else {
          $this->redirectTo('index');
        }
        
        $info['hours'] = array();
        if (count($data['weeklyHours'])) {
          $now = new DateTime();
          $today = intval($now->format('Ymd'));
          
          foreach ($data['weeklyHours'] as $entry) {
            if (intval($entry['date']) >= $today && count($info['hours']) < 3) {
              $info['hours'][] = array(
                'label' => $entry['day'],
                'title' => $entry['hours'],
              );
            }
          }
          $info['hours'][] = array(
            'label' => '',
            'title' => "Full week's schedule",
            'url'   => $this->fullHoursURL($type, $id, $name),            
          );
        }
        if (!count($info['hours'])) {
          unset($info['hours']);
        }
        
        $info['directions'] = array();
        if ($data['address']) {
          $info['directions'][] = array(
            'label' => 'Location',
            'title' => $data['address'],
            'url'   => '/map/search.php?'.http_build_query(array(
              'filter' => $data['address'],
            )),
            'class' => 'map',
          );
        }
        if ($data['directions']) {
          $info['directions'][] = array(
              'label' => 'Directions',
              'title' => $data['directions'],
          );
        }
        if (!count($info['directions'])) {
          unset($info['directions']);
        }
        
        $info['contact'] = array();
        if ($data['website']) {
          $info['contact'][] = array(
            'label' => 'Website',
            'title' => $data['website'],
            'url' => $data['website'],
          );
        }
        if ($data['email']) {
          $info['contact'][] = array(
            'label' => 'Email',
            'title' => str_replace('@', '@&shy;', $data['email']),
            'url'   => "mailto:{$data['email']}",
            'class' => 'email',
          );
        }
        foreach ($data['phone'] as $phone) {
          $info['contact'][] = array(
            'label' => $phone['description'] ? $phone['description'] : 'Phone',
            'title' => str_replace('-', '-&shy;', $phone['number']),
            'url'   => 'tel:'.strtr($phone['number'], '-', ''),
            'class' => 'phone',
          );
        }
        if (!count($info['contact'])) {
          unset($info['contact']);
        }
        
        $item = array(
          'name' => $name,
          'cookie' => ($type == 'library') ? LIBRARY_LIBRARIES_COOKIE : LIBRARY_ARCHIVES_COOKIE,
          'infoSections' => $info,
        );
        
        $this->assign('item', $item);
        break;
        
      case 'links':
        break;
        
      case 'contact':
        break;
    }
  }
}
