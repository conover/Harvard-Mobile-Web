<?php

require_once realpath(LIB_DIR.'/Module.php');
require_once realpath(LIB_DIR.'/feeds/Libraries.php');

define('LIBRARY_LIBRARIES_COOKIE', 'libraryLocations');
define('LIBRARY_ARCHIVES_COOKIE',  'libraryArchives');
define('LIBRARY_ITEMS_COOKIE',     'libraryItems');


class LibrariesModule extends Module {
  protected $id = 'libraries';
  private static $typeToCookie = array(
    'item'    => LIBRARY_ITEMS_COOKIE,
    'library' => LIBRARY_LIBRARIES_COOKIE,
    'archive' => LIBRARY_ARCHIVES_COOKIE,
  );
  private $bookmarks = array(
    'item'    => null,
    'library' => null,
    'archive' => null,
  );
  private static $statusMapping = array(
    'available'   => 'availableItems',
    'requestable' => 'checkedOutItems',
    'unavailable' => 'unavailableItems',
    'collection'  => 'collectionOnlyItems',
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
      setcookie(self::$typeToCookie[$type], implode(',', array_unique($bookmarks)), 0, COOKIE_PATH);
      $this->bookmarks[$type] = $bookmarks;
    } else {
      error_log(__FUNCTION__."(): Warning unknown cookie type '$type'");
    }
  }

  private function isBookmarked($type, $id) {
    return in_array($id, $this->getBookmarks($type));
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
  
  private function initLibraryDetails($type, $id, $name) {
    $data = array();
    
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
    
    return $data;
  }

  private function translateFormat($formatDetail) {
    switch (strtolower($formatDetail)) {
      case 'book':
        return 'book';
      
      case 'computer file':
        return 'computerfile';
        
      case 'recording':
        return 'soundrecording';
        
      case 'image':
        return 'image';
        
      case 'map':
        return 'map';
      
      case 'archives / manuscripts':
      case 'journal / serial':
        return 'journal';
        
      case 'sheet music':
        return 'musicscore';

      case 'movie':
        return 'video';
        
      case '':
        error_log("Warning empty library item format"); 
        return 'book';
        
      default:
        error_log("Warning unknown library item format '$format'"); 
        return $formatDetail;
    }
  }
  
  private function formatDetail($data, $key='', $default='') {
    if (is_array($data)) {
      $detail = self::argVal($data, $key, $default);
    } else {
      $detail = $data;
    }
    
    // restore newlines
    $detail = str_replace("\n", '<br/>', $detail);
    
    // no phone numbers in item details:
    $detail = str_replace('-', '-&shy;', $detail);
    
    // no email addresses
    $detail = str_replace('@', '@&shy;', $detail);
    
    return $detail;
  }
  
  private function getItemDetails($data) {
    // error_log(json_encode($data));
    $details = array(
      'id'              => $data['itemId'],
      'title'           => $this->formatDetail($data, 'title', 'Unknown title'),
      // Used for non-english versions of the title, creator, UTF-8 encoded:
      'nonLatinTitle'   => $this->formatDetail($data, 'nonLatinTitle'),
      'nonLatinCreator' => $this->formatDetail($data, 'nonLatinCreator'),
      'creator'         => $this->formatDetail($data, 'creator'),
      'publisher'       => $this->formatDetail($data, 'publisher'),
      'date'            => $this->formatDetail($data, 'date'),
      'format'          => $this->translateFormat(self::argVal($data['format'], 'formatDetail', '')),
      'formatDesc'      => $this->formatDetail($data['format'], 'formatDetail'),
      'type'            => $this->formatDetail($data['format'], 'typeDetail'),
      'url'             => $this->detailURL($data['itemId']),
      'bookmarked'      => $this->isBookmarked('item', $data['itemId']),
      'cookie'          => LIBRARY_ITEMS_COOKIE,
    );
    
    if ($details['creator']) {
      $creatorLink = self::argVal($data, 'creatorLink', '');
      if ($creatorLink) {
        $details['creatorURL'] = $this->buildBreadcrumbURL('search', array(
          'q' => $creatorLink,
        ));
      } else {
        $details['creatorURL'] = $this->buildBreadcrumbURL('search', array(
          'author' => self::argVal($data, 'creator', ''),
        ));
      }
    };

    if (isset($data['index'])) {
      $details['index'] = $data['index'];
    }
    if (isset($data['identifier'])) {
      foreach ($data['identifier'] as $identifier) {
        if (isset($identifier['type']) && strtolower($identifier['type']) == 'net') {
          $details['isOnline']  = true;
          $details['onlineUrl'] = $identifier['typeDetail'];
        }
      }
    }
    
    if (strtolower($details['format']) == 'image') {
      $imageInfo = Libraries::getImageThumbnail($data['itemId']);

      $details['isOnline']     = true;
      $details['onlineUrl']    = $imageInfo['cataloglink'];
      $details['thumbnail']    = $imageInfo['thumbnail'];
      $details['fullImageUrl'] = $imageInfo['fullimagelink'];
      $details['workType']     = $imageInfo['worktype'];
      $details['imageCount']   = $imageInfo['numberofimages'];
    }
    
    return $details;
  }
  
  private function formatBriefItemAvailability($entry) {
    $results = array();
    
    foreach ($entry['collection'] as $collection) {
      foreach ($collection['itemsByStat'] as $statItems) {
        $item = array(
          'type'        => self::argVal($statItems, 'statMain'),
          'collection'  => 0,
          'available'   => 0,
          'requestable' => 0,
          'total'       => 0,
        );
        
        foreach (self::$statusMapping as $status => $statusKey) {
          $count = count($statItems[$statusKey]);
          
          if ($count > 0) {
            $item['total'] += $count;
            if ($status == 'available') {
              $item['available'] += $count;
            }
            if ($status == 'requestable') {
              $item['requestable'] += $count;
            }
            if ($status == 'collection') {
              $item['collection'] += $count;
            }
          }
        }
        $results[] = $item;
      }
    }
    
    return $results;
  }
  
  private function formatItemAvailabilityInfo($entry) {
    $results = array();
    
    foreach ($entry['collection'] as $collection) {
      $types = array();
      
      $collectionName = $this->formatDetail($collection, 'collectionName');
      $collectionCallNumber = self::argVal($collection, 'collectionCallNumber');
      
      foreach ($collection['itemsByStat'] as $itemsByStat) {
        $items = array();
                
        foreach (self::$statusMapping as $status => $statKey) {
          foreach ($itemsByStat[$statKey] as $itemByStat) {
            $keyParams = array(
              'callNumber'  => 'callNumber', 
              'secondary'   => 'statSecondary', 
              'description' => 'description', 
              'requestUrl'  => 'requestUrl',
              'scanUrl'     => 'scanAndDeliverUrl',
            );
            $keys = array($status);
            foreach ($keyParams as $param => $paramKey) {
              if (self::argVal($itemByStat, $paramKey)) {
                $keys[] = self::argVal($itemByStat, $paramKey);
              }
            }
            
            if (self::argVal($itemByStat, 'callNumber')   ) { $keys[] = $itemByStat['callNumber']; }
            if (self::argVal($itemByStat, 'statSecondary')) { $keys[] = $itemByStat['statSecondary']; }
            if (self::argVal($itemByStat, 'description')  ) { $keys[] = $itemByStat['description']; }
            if (self::argVal($itemByStat, 'requestUrl')   ) { $keys[] = $itemByStat['requestUrl']; }
          
            $key = implode('|', $keys);
            
            if (!isset($items[$key])) {
              $items[$key] = array(
                'count'  => 0,
                'status' => $status,
              );
              foreach ($keyParams as $param => $paramKey) {
                $items[$key][$param] = self::argVal($itemByStat, $paramKey);
              }
              
              if ($status == 'collection') {
                $extraDescriptionLines = array();
                
                $itemMessage = self::argVal($itemByStat, 'noItemMessage');
                if ($itemMessage) {
                  $extraDescriptionLines[] = $itemMessage;
                }
                
                $availArray = self::argVal($itemByStat, 'collectionAvailVal');
                if ($availArray) {
                  $extraDescriptionLines = array_merge($extraDescriptionLines, $availArray);
                }
                
                if (!$itemMessage && !$availArray) {
                  $extraDescriptionLines[] = 'Contact the library for more information.';
                }
                
                if (isset($items[$key]['description'])) { 
                  $items[$key]['description'] .= '<br/>'; 
                }
                $items[$key]['description'] .= implode('<br/>', $extraDescriptionLines);
              }
            }
            $items[$key]['count']++;
          }
        }
        
        $types[] = array( 
          'type'  => $itemsByStat['statMain'],
          'items' => array_values($items),
        );
      }
      
      if (count($types)) {
        $results[] = array(
          'name'       => $collectionName,
          'callNumber' => $collectionCallNumber,
          'types'      => $types,
        );
      }
    }
    //error_log(print_r($results, true));
    
    return $results;
  }

  private function detailURL($id, $toggleBookmark=false, $addBreadcrumb=true) {
    $args = array(
      'id' => $id,
    );
    if ($toggleBookmark) {
      $args['toggleBookmark'] = 1;
    }
    return $this->buildBreadcrumbURL('detail', $args, !$toggleBookmark && $addBreadcrumb);
  }
  
  private function availabilityURL($itemId, $type, $id) {
    return $this->buildBreadcrumbURL('availability', array(
      'itemId' => $itemId,
      'type'   => $type,
      'id'     => $id,
    ));
  }
  
  private function locationAndHoursURL($type, $id, $name, $toggleBookmark=false, $dropBreadcrumbs=false) {
    $args = array(
      'type' => $type,
      'id'   => $id,
      'name' => $name,
    );
    if ($toggleBookmark) {
      $args['toggleBookmark'] = 1;
    }
    if ($dropBreadcrumbs) {
      return $this->buildURL('locationAndHours', $args);
    } else {
      return $this->buildBreadcrumbURL('locationAndHours', $args, !$toggleBookmark);
    }
  }
  
  private function mapURL($type, $id, $name) {
    return $this->buildBreadcrumbURL('map', array(
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

  private static function titleSort($a, $b) {
    return strcmp($a['title'], $b['title']);
  }
  
  protected function urlForFederatedSearch($searchTerms) {
    return $this->buildBreadcrumbURL("/{$this->id}/search", array(
      'keywords' => $searchTerms,
      'federated' => 1
    ), false);
  }

  public function federatedSearch($searchTerms, $maxCount, &$results) {
    $count = 0;
    $results = array();
  
    $data = array_values(Libraries::searchItems($searchTerms, '', ''));
    $count = count($data);

    if ($count) {
      $limit = min($maxCount, $count);
      
      for ($i = 0; $i < $limit; $i++) {
        $subtitle = trim(self::argVal($data[$i], 'date', ''));
        $creator = self::argVal($data[$i], 'creator', '');
        if ($creator) {
          if ($subtitle && $creator) { $subtitle .= ' | '; }
          $subtitle .= $creator;
        }
      
        $results[] = array(
          'title'    => self::argVal($data[$i], 'title', 'Unknown title'),
          'subtitle' => $subtitle ? $subtitle : null,
          'url'      => $this->buildBreadcrumbURL("/{$this->id}/detail", array(
            'id' => $data[$i]['itemId'],
          ), false),
        );
      }
    }
    return $count;
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
          
          $section = array(
            'items' => array(),
          );
          foreach ($config['titles'] as $i => $title) {
            if ($sectionName == 'bookmarks') {
              $types = explode('|', $config['bookmarkType'][$i]);

              $hasBookmarkOfTypes = false;
              foreach ($types as $type) {
                if (count($this->getBookmarks($type))) {
                  $hasBookmarkOfTypes = true;
                  break;
                }
              }
              if (!$hasBookmarkOfTypes) { continue; }
            }
            $section['items'][] = array(
              'title' => $title,
              'url'   => $config['urls'][$i],
              'class' => $config['classes'][$i],
            );
          }
          if (count($section['items'])) {
            $sections[] = $section;
          }
        }
        
        $this->assign('searchPlaceholder', $searchPlaceholder);
        $this->assign('sections',          $sections);
        break;

      case 'links':
        $indexConfig = $this->loadWebAppConfigFile('libraries-links', 'indexConfig');
        
        $sections = array();
        foreach ($indexConfig as $sectionName => $config) {
          $section = array(
            'heading' => self::argVal($config, 'heading'),
            'items'   => array(),
          );
          foreach ($config['titles'] as $i => $title) {
            $section['items'][] = array(
              'title'    => $title,
              'subtitle' => $config['subtitles'][$i],
              'url'      => $config['urls'][$i],
              'class'    => $config['classes'][$i],
            );
          }
          if (count($section)) {
            $sections[] = $section;
          }
        }
        
        $this->assign('sections', $sections);
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
        //error_log(print_r($data, true));
        
        $data = Libraries::getItemAvailabilitySummary($id);
        //error_log(print_r($data, true));
        
        $locations = $data['institutions'];
        $locationCoords = array();
        foreach ($locations as $index => $location) {
          $locations[$index]['name'] = $this->formatDetail($location, 'name');
          $locations[$index]['url'] = 
            $this->availabilityURL($id, $location['type'], $location['id']);
                    
          $libData = null;
          if ($location['type'] == 'library') {
            $libData = Libraries::getLibraryDetails($location['id'], $location['name']);
            
          } else if ($location['type'] == 'archive') {
            $libData = Libraries::getArchiveDetails($location['id'], $location['name']);
          }
          
          if ($libData) {
            $locationCoords[$location['id']] = array(
              'lat' => floatVal($libData['latitude']),
              'lon' => floatVal($libData['longitude']),
            );
          }
        }
        
        $this->addInlineJavascript('var locationCoords = '.json_encode($locationCoords).';');
        $this->addOnLoad('setLocationDistances(locationCoords);');
        //error_log(print_r($locations, true));
        
        $this->assign('locations',   $locations);
        $this->assign('item',        $item);
        $this->assign('bookmarkURL', $this->detailURL($id, true));
        break;
      
      case 'availability':
        $itemId = $this->getArg('itemId');
        $type   = $this->getArg('type');
        $id     = $this->getArg('id');
          
        if (!$itemId || !$id) {
          $this->redirectTo('index');
        }
        
        $itemData = Libraries::getItemRecord($itemId);

        $location = array();
        $name = '';
        
        $data = Libraries::getItemAvailability($itemId);

        foreach ($data['institutions'] as $entry) {
          if ($entry['id'] != $id) { continue; }
          //error_log(print_r($entry, true));
          
          $name = $entry['name'];

          if (!$name) {
            $this->redirectTo('index');
          }
  
          if ($type == 'library') {
            $libData = Libraries::getLibraryDetails($id, $name);
            //error_log(print_r($libData, true));
            
          } else if ($type == 'archive') {
            $libData = Libraries::getArchiveDetails($id, $name);
            //error_log(print_r($libData, true));
          
          } else {
            $this->redirectTo('index');
          }
          
          $collections = $entry['collections'];
          
          foreach ($collections as $col => $collection) {
            foreach ($collection['categories'] as $cat => $category) {
              foreach ($category['items'] as $i => $item) {
                if (self::argVal($item, 'requestURL') || self::argVal($item, 'scanAndDeliverURL')) {
                  $url = $this->buildBreadcrumbURL('request', array(
                    'libId'      => $id, 
                    'libType'    => $type, 
                    'libName'    => $name, 
                    'itemId'     => $itemId,
                    'cName'      => $collection['name'], 
                    'cNumber'    => $item['callNumber'], 
                    'hStatus'    => $category['holdingStatus'],
                    'state'      => $item['state'],
                    'sStatus'    => $item['secondaryStatus'],
                    'message'    => $item['message'],
                    'requestURL' => $item['requestURL'],
                    'scanURL'    => $item['scanAndDeliverURL'],
                  ));
                  
                  $collections[$col]['categories'][$cat]['items'][$i]['url'] = $url;
                }
              }
            }
          }
          
          $location = array(
            'type'        => $libData['type'],
            'name'        => $name,
            'primaryname' => $this->formatDetail($libData, 'primaryname'),
            'hours'       => $this->formatDetail($libData, 'hrsOpenToday'),
            'collections' => $collections,
          );
          break; // assume libraries are not listed multiple times
        }

        //error_log(print_r($location, true));
        
        $this->assign('infoURL',  $this->locationAndHoursURL($type, $id, $name, false, true));
        $this->assign('location', $location);
        $this->assign('title',    $this->formatDetail($itemData, 'title', 'Unknown title'));
        break;
        
      case 'request':
        $libType = $this->getArg('libType');
        $libId   = $this->getArg('libId');
        $libName = $this->getArg('libName');
          
        $itemId = $this->getArg('itemId');
        if (!$itemId) {
          $this->redirectTo('index');
        }
        $itemData = Libraries::getItemRecord($itemId);

        if ($libType == 'library') {
          $libData = Libraries::getLibraryDetails($libId, $libName);
          //error_log(print_r($libData, true));
          
        } else if ($libType == 'archive') {
          $libData = Libraries::getArchiveDetails($libId, $libName);
          //error_log(print_r($libData, true));
        
        } else {
          $this->redirectTo('index');
        }

        $this->assign('info', array(
          'name'            => $libName,
          'primaryname'     => $this->formatDetail($libData, 'primaryname'),
          'hours'           => $this->formatDetail($libData, 'hrsOpenToday'),
          'infoUrl'         => $this->locationAndHoursURL($libType, $libId, $libName, false, true),
          
          'title'           => $this->formatDetail($itemData, 'title', 'Unknown title'),
          'collectionName'  => $this->getArg('cName'),
          'callNumber'      => $this->getArg('cNumber'),
          'holdingStatus'   => $this->getArg('hStatus'),
          'state'           => $this->getArg('state'),
          'secondaryStatus' => $this->getArg('sStatus'),
          'message'         => $this->getArg('message'),
          'requestURL'      => $this->getArg('requestURL'),
          'scanURL'         => $this->getArg('scanURL'),
        ));
        break;
      
      case 'advanced':
        $searchConfig = $this->loadWebAppConfigFile('libraries-search', 'searchConfig');
        
        $locations = $searchConfig['locations'] + Libraries::getLibrarySearchCodes();
        $formats   = $searchConfig['formats']   + Libraries::getFormatSearchCodes();
        $pubDates  = $searchConfig['pubDates']  + Libraries::getPubDateSearchCodes();
        
        $this->assign('locations', $locations);
        $this->assign('formats',   $formats);
        $this->assign('pubDates',  $pubDates);
        break;

      case 'search':
        $searchConfig = $this->loadWebAppConfigFile('libraries-search', 'searchConfig');

        $locations = $searchConfig['locations'] + Libraries::getLibrarySearchCodes();
        $formats   = $searchConfig['formats']   + Libraries::getFormatSearchCodes();
        $pubDates  = $searchConfig['pubDates']  + Libraries::getPubDateSearchCodes();
        
        $q          = trim($this->getArg('q'));  // full query
        $keywords   = trim($this->getArg('keywords'));
        $title      = trim($this->getArg('title'));
        $author     = trim($this->getArg('author'));
        $location   = $this->getArg('location');
        $format     = $this->getArg('format');
        $pubDate    = $this->getArg('pubDate');
        $language   = $this->getArg('language');
        $pageNumber = $this->getArg('page', 1);
        
        // Sanity check arguments
        if ($pageNumber < 1) { $pageNumber = 1; }
        if ($location == 'any' || $location == 'all') { $location = ''; }
        if ($format   == 'any' || $format   == 'all') { $format   = ''; } 
        if ($pubDate  == 'any' || $pubDate  == 'all') { $pubDate  = ''; } 
        
        $firstIndex = 1;
        $lastIndex = 1;
        $totalCount = 0;
        $pageSize = 0;
        $pageCount = 0;
        $results = array();
        if ($q || $keywords || $title || $author) {
          $results = array();
          $data = Libraries::searchItems(array(
            'q'        => $q, 
            'keywords' => $keywords, 
            'title'    => $title,
            'author'   => $author,
            'location' => $location, 
            'format'   => $format, 
            'pubDate'  => $pubDate, 
            'language' => $language,
          ), $pageNumber);
          //error_log(print_r($data, true));
          
          if (count($data['items'])) {
            $totalCount = $data['total'];
            $firstIndex = $data['start'];
            $lastIndex  = $data['end'];
            $pageSize   = $data['pagesize'];            
            $pageCount  = ceil($totalCount / $pageSize);
          }
          foreach ($data['items'] as $item) {
            $results[] = $this->getItemDetails($item);
          }
        }

        $prevURL = '';
        $nextURL = '';
        if ($pageNumber > 1) {
          $args = $this->args;
          $args['page'] = $pageNumber-1;
          $prevURL = $this->buildBreadcrumbURL($this->page, $args, false);
        }
        if ($pageNumber < $pageCount) {
          $args = $this->args;
          $args['page'] = $pageNumber+1;
          $nextURL = $this->buildBreadcrumbURL($this->page, $args, false);
        }
        
        $this->assign('keywords',    implode(' ', array($keywords, $q)));
        $this->assign('title',       $title);
        $this->assign('author',      $author);
        $this->assign('location',    $location);
        $this->assign('format',      $format);
        $this->assign('pubDate',     $pubDate);
        $this->assign('language',    $language);
        
        $this->assign('locations',   $locations);
        $this->assign('formats',     $formats);
        $this->assign('pubDates',    $pubDates);
        
        $this->assign('results',     $results);
        $this->assign('firstIndex',  $firstIndex);
        $this->assign('lastIndex',   $lastIndex);
        $this->assign('totalCount',  $totalCount);
        
        $this->assign('pageNumber',  $pageNumber);
        $this->assign('pageCount',   $pageCount);
        $this->assign('pageSize',    $pageSize);
        $this->assign('prevURL',     $prevURL);
        $this->assign('nextURL',     $nextURL);
        break;

      case 'bookmarks':
        $types = explode('|', $this->getArg('type'));
        
        $libraries = null;
        $archives = null;
        
        $results = array();
        foreach ($types as $type) {
          $bookmarks = $this->getBookmarks($type);
          foreach ($bookmarks as $id) {
            if ($type == 'library') {
              if (!isset($libraries)) {
                $libraries = Libraries::getLibraries();
              }
              
              foreach ($libraries as $entry) {
                if ($entry['id'] == $id) {
                  $results[] = array(
                    'title' => $this->formatDetail($entry, 'primaryname'),
                    'url'   => $this->locationAndHoursURL('library', $entry['id'], $entry['primaryname']),
                  );
                  break;
                }
              }
            } else if ($type == 'archive') {
              if (!isset($archives)) {
                $archives = Libraries::getArchives();
              }
              
              foreach ($archives as $entry) {
                if ($entry['id'] == $id) {
                  $results[] = array(
                    'title' => $this->formatDetail($entry, 'primaryname'),
                    'url'   => $this->locationAndHoursURL('archive', $entry['id'], $entry['primaryname']),
                  );
                  break;
                }
              }

            } else {
              $data = Libraries::getItemRecord($id);
              $data['itemId'] = $id;
              $results[] = $this->getItemDetails($data);
            }
          }
        }
        //error_log(print_r($results, true));
        
        $this->assign('bookmarkType', $type);
        $this->assign('results', $results);
        break;
        
      case 'libraries':
        $openOnly = $this->getArg('openOnly', false) ? true : false;
        $openNowToggleURL = $this->buildBreadcrumbURL($this->page, 
          $openOnly ? array() : array('openOnly' => 'true'), false);// toggle
        
        $data = Libraries::getOpenNow();
        //error_log(print_r($data, true));
        
        $libraries = array();
        foreach ($data as $entry) {
          if (!isset($libraries[$entry['name']]) && (!$openOnly || $entry['isOpenNow'])) {
            $libraries[$entry['name']] = array(
              'title' => $this->formatDetail($entry, 'name'),
              'url'   => $this->locationAndHoursURL('library', $entry['id'], $entry['name']),
            );
          }
        }
        ksort($libraries);
        
        $this->assign('openOnly',         $openOnly);
        $this->assign('openNowToggleURL', $openNowToggleURL);
        $this->assign('libraries',        $libraries);
        break;
        
      case 'archives':
        $data = Libraries::getArchives();
        //error_log(print_r($data, true));
        
        $archives = array();
        foreach ($data as $entry) {
          if (!isset($archives[$entry['name']])) {
            $archives[$entry['name']] = array(
              'title' => $this->formatDetail($entry, 'name'),
              'url'   => $this->locationAndHoursURL('archive', $entry['id'], $entry['name']),
            );
          }
        }
        ksort($archives);

        $this->assign('archives', $archives);
        break;
        
      case 'locationAndHours':        
        $type = $this->getArg('type');
        $id   = $this->getArg('id');
        $name = $this->getArg('name');
        
        $data = $this->initLibraryDetails($type, $id, $name);
        //error_log(print_r($data, true));
        
        $info['hours'] = array();
        if (count($data['weeklyHours'])) {
          $now = new DateTime();
          $today = intval($now->format('Ymd'));
          
          foreach ($data['weeklyHours'] as $entry) {
            if (intval($entry['date']) >= $today && count($info['hours']) < 3) {
              $info['hours'][$entry['date']] = array(
                'label' => $entry['day'],
                'title' => $this->formatDetail($entry, 'hours'),
              );
            }
          }
          $info['hours'][] = array(
            'label' => null,
            'title' => "Full week's schedule",
            'url'   => $this->fullHoursURL($type, $id, $name),            
          );
          $info['hours'] = array_values($info['hours']);
          
        } else if (strlen($data['hoursOfOperationString'])) {
          if (strpos($data['hoursOfOperationString'], 'http') !== 0) {
            $info['hours'][] = array(
              'label' => 'Hours',
              'title' => $data['hoursOfOperationString'],
            );
          } else {
            $info['hours'][] = array(
              'label' => 'Hours',
              'title' => 'See website',
              'url'   => $data['hoursOfOperationString'],
              'class' => 'external',
            );
         }
        }
        if (!count($info['hours'])) {
          unset($info['hours']);
        }
        
        $info['directions'] = array();
        if ($data['address']) {
          $location = array(
            'label' => 'Location',
            'title' => $this->formatDetail($data, 'address'),
            'class' => 'map',
          );
          if ($data['latitude'] && $data['longitude']) {
            $location['url'] = $this->mapURL($type, $id, $name);
          }
          $info['directions'][] = $location;
        }
        if ($data['directions']) {
          $directions = trim($data['directions']);
          
          $entry = array(
            'label' => 'Directions',
          );
          if (strpos($directions, 'http') === 0) {
            $entry['title'] = 'See website';
            $entry['url'] = $directions;
            $entry['class'] = 'external';
          } else {
            $entry['title'] = $this->formatDetail($directions);
          }
          
          $info['directions'][] = $entry;
        }
        if (!count($info['directions'])) {
          unset($info['directions']);
        }
        
        $info['contact'] = array();
        if ($data['website']) {
          if (strpos($data['website'], 'http') === 0) {
            $info['contact'][] = array(
              'label' => 'Website',
              'title' => $data['website'],
              'url'   => $data['website'],
              'class' => 'external',
            );
          } else {
            $info['contact'][] = array(
              'label' => 'Website',
              'title' => $this->formatDetail($data, 'website'),
            );
          }
        }
        if ($data['email']) {
          $info['contact'][] = array(
            'label' => 'Email',
            'title' => $this->formatDetail($data, 'email'),
            'url'   => "mailto:{$data['email']}",
            'class' => 'email',
          );
        }
        foreach ($data['phone'] as $phone) {
          $info['contact'][] = array(
            'label' => $phone['description'] ? $this->formatDetail($phone, 'description') : 'Phone',
            'title' => $this->formatDetail($phone, 'number'),
            'url'   => 'tel:'.strtr($phone['number'], '-', ''),
            'class' => 'phone',
          );
        }
        if (!count($info['contact'])) {
          unset($info['contact']);
        }
        
        $item = array(
          'id'           => $id,
          'name'         => $this->formatDetail($name),
          'fullName'     => $this->formatDetail($data, 'primaryname'),
          'type'         => $type,
          'bookmarked'   => $this->isBookmarked($type, $id),
          'cookie'       => self::$typeToCookie[$type],
          'infoSections' => $info,
        );
        
        $this->assign('item', $item);
        $this->assign('bookmarkURL',  $this->locationAndHoursURL($type, $id, $name, true));
        break;

      case 'fullHours':
        $type = $this->getArg('type');
        $id   = $this->getArg('id');
        $name = $this->getArg('name');
        
        $data = $this->initLibraryDetails($type, $id, $name);

        $hours = array();
        if (count($data['weeklyHours'])) {
          foreach ($data['weeklyHours'] as $entry) {
            $hours[$entry['date']] = array(
              'label' => $this->formatDetail($entry, 'day'),
              'title' => $this->formatDetail($entry, 'hours'),
            );
          }
          $hours = array_values($hours);
        }
        if (!count($hours)) {
          $this->redirectTo('locationAndHours', array(
            'type' => $type,
            'id'   => $id,
            'name' => $name,
          ));
        }
        
        $item = array(
          'name'       => $this->formatDetail($name),
          'fullName'   => $this->formatDetail($data, 'primaryname'),
          'type'       => $type,
          'bookmarked' => $this->isBookmarked($type, $id),
          'cookie'     => self::$typeToCookie[$type],
          'hours'      => $hours,
        );
        
        $this->assign('item', $item);
        break;

      case 'map':
        $type = $this->getArg('type');
        $id   = $this->getArg('id');
        $name = $this->getArg('name');
        
        $data = $this->initLibraryDetails($type, $id, $name);
        
        switch ($this->pagetype) {
          case 'compliant':
            $imageWidth = 290; $imageHeight = 300;
            break;
          
          case 'basic':
            if ($GLOBALS['deviceClassifier']->getPlatform() == 'bbplus') {
              $imageWidth = 410; $imageHeight = 360;
            } else {
              $imageWidth = 200; $imageHeight = 200;
            }
            break;
        }
        
        $this->assign('imageWidth',  $imageWidth);
        $this->assign('imageHeight', $imageHeight);
        
        $imgSrc = $GLOBALS['siteConfig']->getVar('GOOGLE_STATIC_MAPS_URL').http_build_query(array(
          'zoom'    => 15,
          'sensor'  => 'false',
          'size'    => "{$imageWidth}x{$imageHeight}",
          'markers' => "color:0xb12727|{$data['latitude']},{$data['longitude']}",
        ));

        
        $item = array(
          'name'       => $this->formatDetail($name),
          'fullName'   => $this->formatDetail($data, 'primaryname'),
          'type'       => $type,
          'bookmarked' => $this->isBookmarked($type, $id),
          'cookie'     => self::$typeToCookie[$type],
          'imgSrc'     => $imgSrc,
        );
        
        $this->assign('item', $item);
        break;
    }
  }
}
