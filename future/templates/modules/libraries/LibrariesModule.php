<?php

require_once realpath(LIB_DIR.'/Module.php');
require_once realpath(LIB_DIR.'/feeds/LibrariesInfo.php');

define('LIBRARY_LIBRARIES_COOKIE', 'libraries');
define('LIBRARY_ARCHIVES_COOKIE', 'archives');
define('LIBRARY_LIBRARY_ITEMS_COOKIE', 'libraryItems');


class LibrariesModule extends Module {
  protected $id = 'libraries';
  private static $typeToCookie = array(
    'item'    => LIBRARY_LIBRARY_ITEMS_COOKIE,
    'library' => LIBRARY_LIBRARIES_COOKIE,
    'archive' => LIBRARY_ARCHIVES_COOKIE,
  );
  private $bookmarks = array(
    'item'    => null,
    'library' => null,
    'archive' => null,
  );
    
  private function getBookmarks($type) {
    if (isset(self::$typeToCookie[$type])) {
      if (!isset($this->bookmarks[$type])) {
        $this->bookmarks[$type] = array();
        if (isset($_COOKIE[self::$typeToCookie[$type]])) {
          $this->bookmarks[$type] = array_unique(explode(',', $_COOKIE[self::$typeToCookie[$type]]));
        }
      }
      return $this->bookmarks[$type];
    }
    error_log(__FUNCTION__."(): Warning unknown cookie type '$type'");
    return array();
  }
  
  private function setBookmarks($type, $bookmarks) {
    if (isset(self::$typeToCookie[$type])) {
      setcookie(self::$typeToCookie[$type], implode(',', $bookmarks), 0, COOKIE_PATH);
      $this->bookmarks[$type] = $bookmarks;
    } else {
      error_log(__FUNCTION__."(): Warning unknown cookie type '$type'");
    }
  }
  
  private function checkToggleBookmark($type, $id) {
    if ($this->getArg('toggleBookmark')) {
      if (isset(self::$typeToCookie[$type])) {
        $bookmarks = array_fill_keys($this->getBookmarks($type), true);
    
        if (isset($bookmarks[$id])) {
          unset($bookmarks[$id]);
        } else {
          $bookmarks[$id] = true;
        }
        $this->setBookmarks($type, array_keys($bookmarks));
        
        $args = $this->args;
        unset($args['toggleBookmark']);
        $this->redirectTo($this->page, $args);
      }
    }
  }
  
  private function translateFormat($formatDetail) {
    $format = strtolower($formatDetail);
    
    switch ($format) {
      case 'book':
        return 'book';
        
      case 'recording':
        return 'audio';
        
      case 'image':
        return 'image';
        
      case 'map':
        return 'map';
        
      case 'archives / manuscripts':
      case 'journal / serial':
        return 'journal';
        
      case 'sheet music':
        return 'score';

      case 'movie':
        return 'video';
        
      default:
        return 'book';
    }
    return $format;
  }
  
  private function getItemDetails($data) {
    return array(
      'id'         => $data['itemId'],
      'title'      => self::argVal($data, 'title', 'Unknown title'),
      'creator'    => self::argVal($data, 'creator', ''),
      'date'       => self::argVal($data, 'date', ''),
      'format'     => $this->translateFormat(self::argVal($data['format'], 'formatDetail', 'Book')),
      'formatDesc' => self::argVal($data['format'], 'formatDetail', 'Book'),
      'type'       => self::argVal($data['format'], 'typeDetail', ''),
      'url'        => $this->detailURL($data['itemId']),
    );
  }
  
  private function formatItemAvailabilityInfo($entry) {
    $items = array();
    
    foreach ($entry['itemsByStat'] as $statItems) {
      $item = array(
        'type'        => self::argVal($statItems, 'statMain', 'collection'),
        'typeName'    => self::argVal($statItems, 'statMain', 'collection'),
        'callNumber'  => self::argVal($statItems, 'callNumber', null),
        'available'   => 0,
        'requestable' => 0,
        'total'       => 0,
        'types' => array(),
      );
      
      $countMapping = array(
        'available'   => 'availCount',
        'requestable' => 'checkedOutCount',
        'unavailable' => 'unavailCount',
        'collection'  => 'collectionOnlyCount',
      );
      
      $typeMapping = array(
        'available'   => 'availableItems',
        'requestable' => 'checkedOutItems',
        'unavailable' => 'unavailableItems',
        'collection'  => 'collectionOnlyItems',
      );
      
      foreach ($typeMapping as $typeKey => $statKey) {
        $count = count($statItems[$statKey]);
        
        if ($count > 0) {
          $item['total'] += $count;
          if ($typeKey == 'available') {
            $item['available'] += $count;
          }
          if ($typeKey == 'requestable') {
            $item['requestable'] += $count;
          }
          
          $itemType = array(
            'count' => $count,
            'status' => $typeKey,
          );
          foreach ($statItems[$statKey] as $statItem) {
            if (self::argVal($statItem, 'requestUrl')) {
              $itemType['url'] = $statItem['requestUrl'];
            }
            if (self::argVal($statItem, 'statSecondary')) {
              $itemType['status'] = $statItem['statSecondary'];
            }
            if (self::argVal($statItem, 'callNumber')) {
              $itemType['callNumber'] = $statItem['callNumber'];
            }
            if (self::argVal($statItem, 'collectionCallNumber')) {
              $itemType['callNumber'] = $statItem['collectionCallNumber'];
            }
            if ($item['type'] == 'collection' && self::argVal($statItem, 'collectionName')) {
              $item['typeName'] = $statItem['collectionName'];
            }
          }
          $item['types'][$typeKey] = $itemType;
        }
      }
      $items[] = $item;
    }

    return $items;
  }

  private function detailURL($id, $toggleBookmark=false) {
    $args = array(
      'id' => $id,
    );
    if ($toggleBookmark) {
      $args['toggleBookmark'] = 1;
    }
    return $this->buildBreadcrumbURL('detail', $args, !$toggleBookmark);
  }
  
  private function availabilityURL($itemID, $type, $id) {
    return $this->buildBreadcrumbURL('availability', array(
      'itemID' => $itemID,
      'type'   => $type,
      'id'     => $id,
    ));
  }
  
  private function locationAndHoursURL($type, $id, $name, $toggleBookmark=false) {
    $args = array(
      'type' => $type,
      'id'   => $id,
      'name' => $name,
    );
    if ($toggleBookmark) {
      $args['toggleBookmark'] = 1;
    }
    return $this->buildBreadcrumbURL('locationAndHours', $args, !$toggleBookmark);
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
        
        $hasItemBookmarks = count($this->getBookmarks('item')) > 0;
        $hasLibraryBookmarks = 
          (count($this->getBookmarks('library')) + count($this->getBookmarks('archive'))) > 0;
        $hasBookmarks = $hasItemBookmarks || $hasLibraryBookmarks;
        
        $searchPlaceholder = '';
        $sections = array();
        foreach ($indexConfig as $sectionName => $config) {
          if ($sectionName == 'search') {
            $searchPlaceholder = $config['searchPlaceholder'];
            continue;
          }
          
          if ($sectionName == 'bookmarks' && !$hasBookmarks) {
            continue;
          }
        
          $section = array();
          foreach ($config['titles'] as $i => $title) {
            if ($sectionName == 'bookmarks') {
              if ($config['bookmarkType'][$i] == 'item' && !$hasItemBookmarks) {
                continue;
              } else if ($config['bookmarkType'][$i] == 'library' && !$hasLibraryBookmarks) {
                continue;
              }
            }
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
        $id = $this->getArg('id');
        if (!$id) {
          $this->redirectTo('index');
        }
        
        $this->checkToggleBookmark('item', $id);

        $data = Libraries::getItemRecord($id);
        $data['itemId'] = $id;
        
        $item = $this->getItemDetails($data);

        $data = Libraries::getFullAvailability($id);
        //error_log(print_r($data, true));
        
        $locations = array();
        $locationCoords = array();
        foreach ($data as $entry) {          
          $locations[] = array(
            'type'  => $entry['type'],
            'name'  => $entry['name'],
            'lat'   => $entry['details']['latitude'],
            'lon'   => $entry['details']['longitude'],
            'items' => $this->formatItemAvailabilityInfo($entry),
            'url'   => $this->availabilityURL($id, $entry['type'], $entry['id']),
          );
          $locationCoords[] = array(
            'lat' => floatVal($entry['details']['latitude']),
            'lon' => floatVal($entry['details']['longitude']),
          );
        }
        
        $item['bookmarked'] = in_array($id, $this->getBookmarks('item'));
         
        $this->addOnLoad('var locationCoords = '.json_encode($locationCoords).
          '; setLocationDistances(locationCoords);');
        //error_log(print_r($item, true));
        $this->assign('locations', $locations);
        $this->assign('item',      $item);
        $this->assign('bookmarkURL', $this->detailURL($id, true));
        break;
      
      case 'availability':
        $itemID = $this->getArg('itemID');
        $type   = $this->getArg('type');
        $id     = $this->getArg('id');
          
        if (!$itemID || !$id) {
          $this->redirectTo('index');
        }
        
        $items = array();
        $name = '';
        
        $data = Libraries::getFullAvailability($itemID);
        //error_log(print_r($data, true));
        foreach ($data as $entry) {
          if ($entry['id'] != $id) { continue; }
          
          $name = $entry['details']['name'];
          $items = $this->formatItemAvailabilityInfo($entry);

          break; // assume libraries are not listed multiple times
        }

        if (!$name) {
          $this->redirectTo('index');
        }

        if ($type == 'library') {
          $data = Libraries::getLibraryDetails($id, $name);
          //error_log(print_r($data, true));
          
        } else if ($type == 'archive') {
          $data = Libraries::getArchiveDetails($id, $name);
          //error_log(print_r($data, true));
        
        } else {
          $this->redirectTo('index');
        }
        
        $location = array(
          'type'  => $data['type'],
          'name'  => $data['name'],
          'hours' => self::argVal($data, 'hrsOpenToday', ''),
          'items' => $items,
        );
      
        $this->assign('infoURL', $this->locationAndHoursURL($type, $id, $name));
        $this->assign('location', $location);
        break;
      
      case 'search':
        $results = array();
        $searchTerms = trim($this->getArg('filter'));
        
        if (!$this->args['filter']) {
          $this->redirectTo('index');
        }

        $data = Libraries::searchItems($searchTerms);
        //error_log(print_r($data, true));
        $results = array();
        foreach ($data as $entry) {
          $results[] = $this->getItemDetails($entry);
        }
      
        $this->assign('searchTerms', $searchTerms);
        $this->assign('resultCount', count($results));
        $this->assign('results',     $results);
        break;
        
      case 'bookmarks':
        $types = explode(':', $this->getArg('type'));
        
        $libraries = null;
        $archives = null;
        
        $results = array();
        foreach ($types as $type) {
          $bookmarks = $this->getBookmarks($type);
          foreach ($bookmarks as $id) {
            if ($type == 'library') {
              if (!isset($libraries)) {
                $libraries = Libraries::getAllLibraries();
              }
              if (!isset($archives)) {
                $archives = Libraries::getAllArchives();
              }
              
              foreach ($libraries as $entry) {
                if ($entry['id'] == $id) {
                  $results[] = array(
                    'title' => $entry['name'],
                    'url' => $this->locationAndHoursURL('library', $entry['id'], $entry['name']),
                  );
                }
              }
              foreach ($archives as $entry) {
                if ($entry['id'] == $id) {
                  $results[] = array(
                    'title' => $entry['name'],
                    'url' => $this->locationAndHoursURL('archive', $entry['id'], $entry['name']),
                  );
                }
              }

            } else {
              $data = Libraries::getItemRecord($id);
              $data['itemId'] = $id;
              $results[] = $this->getItemDetails($data);
            }
          }
        }
        $this->assign('bookmarkType', $type);
        $this->assign('results', $results);
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
        $id   = $this->getArg('id');
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

        $this->checkToggleBookmark($type, $id);

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
            'label' => null,
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
          'id'     => $id,
          'name'   => $name,
          'type'   => $type,
          'bookmarked' => in_array($id, $this->getBookmarks($type)),
          'cookie' => ($type == 'library') ? LIBRARY_LIBRARIES_COOKIE : LIBRARY_ARCHIVES_COOKIE,
          'infoSections' => $info,
        );
        
        $this->assign('item', $item);
        $this->assign('bookmarkURL',  $this->locationAndHoursURL($type, $id, $name, true));
        break;
      
      case 'fullHours':
        $type = $this->getArg('type');
        $id   = $this->getArg('id');
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
        
        $this->checkToggleBookmark($type, $id);

        $hours = array();
        if (count($data['weeklyHours'])) {
          foreach ($data['weeklyHours'] as $entry) {
            $hours[] = array(
              'label' => $entry['day'],
              'title' => $entry['hours'],
            );
          }
        }
        if (!count($hours)) {
          $this->redirectTo('locationAndHours', array(
            'type' => $type,
            'id'   => $id,
            'name' => $name,
          ));
        }
        
        $item = array(
          'name' => $name,
          'bookmarked' => in_array($id, $this->getBookmarks($type)),
          'cookie' => ($type == 'library') ? LIBRARY_LIBRARIES_COOKIE : LIBRARY_ARCHIVES_COOKIE,
          'hours' => $hours,
        );
        
        $this->assign('item', $item);
        break;
      
      case 'advanced_search':
        break;

      case 'links':
        break;
    }
  }
}
