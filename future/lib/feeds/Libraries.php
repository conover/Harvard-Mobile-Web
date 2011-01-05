<?php

require_once realpath(LIB_DIR.'/feeds/html2text.php');
require_once realpath(LIB_DIR.'/DiskCache.php');

define('HOURS_UNAVAILABLE_STRING', '');

class Libraries {
  private static $cache = null;
  private static $daysOfWeek = array(
    'Monday'    => 1,
    'Mon'       => 1,
    'Tuesday'   => 2,
    'Tue'       => 2,
    'Tues'      => 2,
    'Wednesday' => 3,
    'Weds'      => 3,
    'Thursday'  => 4,
    'Thu'       => 4,
    'Thurs'     => 4,
    'Friday'    => 5,
    'Fri'       => 5,
    'Saturday'  => 6,
    'Sat'       => 6,
    'Sunday'    => 7,
    'Sun'       => 7,
  );
  private static $timezone = null;
  
  private static function getTimezone() {
    if (!isset(self::$timezone)) {
      self::$timezone = new DateTimeZone($GLOBALS['siteConfig']->getVar('LOCAL_TIMEZONE'));
    }
    return self::$timezone;
  }

  private static function getLibraryCache() {
    if (!isset(self::$cache)) {
      self::$cache = new DiskCache(
        $GLOBALS['siteConfig']->getVar('LIB_CACHE_DIR'), 
        $GLOBALS['siteConfig']->getVar('LIB_DIR_CACHE_TIMEOUT'), TRUE);
      self::$cache->preserveFormat();
      self::$cache->setSuffix('.xml');
    }
    
    return self::$cache;
  }

  private static function parseXML($contents) {
    return simplexml_load_string($contents, "SimpleXMLElement", LIBXML_NOCDATA);
  }

  private static function query($cacheName, $urlPrefix, $urlSuffix='') {
    $cache = self::getLibraryCache();
    $xml = null;
    
    if ($cache->isFresh($cacheName)) {
      $xml = self::parseXML($cache->read($cacheName));
      
    } else {
      $url = $GLOBALS['siteConfig']->getVar($urlPrefix).$urlSuffix;
    
      $contents = file_get_contents($url);
      error_log("LIBRARIES DEBUG: " . $url);
      if ($contents == "") {
        error_log("Failed to read contents from $url, reading expired cache");
        $xml = self::parseXML($cache->read($cacheName));
        
      } else {
        $xml = self::parseXML($contents, "SimpleXMLElement", LIBXML_NOCDATA);
        if (!$xml) {
          error_log("Failed to parse contents from $url, reading expired cache");
          $xml = self::parseXML($cache->read($cacheName));
        
        } else {
          $cache->write($contents, $cacheName);
        }
      }
    }
    return $xml;
  }
  
  private static function stripHTML($value) {
    if (!is_string($value)) { 
      error_log(__FUNCTION__."(): field value is not a string: ".print_r($value, true));
      return '';
    }
    
    if (strpos($value, 'http') !== FALSE && 
        preg_match(';href="([^"]+)";', $value, $matches)) {
      $value = $matches[1]; // grab the first url
    } else {
      preg_replace(';<br\s./>;', " \n", $value);
    }
    return trim(HTML2TEXT($value));
  }
  
  private static function formatField($field, $value) {
    switch ($field) {
      case 'hoursofoperation':
        $hours = array();
        $hoursStrings = array();
        foreach ($value as $entry) {
          if (isset($entry->dailyhours)) {
            foreach ($entry->dailyhours as $dayHours) {
              $date = $dayHours->date[0];
              $datetime = new DateTime(
                substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2), 
                self::getTimezone());

              $hours[] = array(
                'date'  => strval($date),
                'day'   => $datetime->format('l'),
                'hours' => strval($dayHours->hours[0]),
              );
            }            
          } else if (isset($entry[0]) && strval($entry[0])) {
            // a text description of the hours
            $hoursStrings[] = self::stripHTML(strval($entry[0]));
          }
        }
        
        if (count($hours)) {
          return $hours;
          
        } else if (count($hoursStrings)) {
          return $hoursStrings;
          
        } else {
          return array(HOURS_UNAVAILABLE_STRING);
        }
        break;
        
      case 'phonenumber':
        $value = trim($value);
        if (preg_match(';^[0-9]{3}-[0-9]{4}$;', $value)) {
          $value = $GLOBALS['siteConfig']->getVar('LOCAL_AREA_CODE').'-'.$value;
        }
        break;
    }
    return self::stripHTML($value);
  }
  
  private static function getField($obj, $fields, $default='') {
    $lastField = is_array($fields) ? end($fields) : $fields;
    $exists = true;
    $value = $obj;
    
    if (!is_array($fields)) {
      $fields = array($fields);
    }
    
    foreach ($fields as $field) {
      if (isset($value->$field)) {
        $value = $value->$field;
        
      } else if (isset($value[$field])) {
        $value = $value[$field];
        
      } else {
        $exists = false;
        break;
      }
    }
  
    switch ($lastField) {
      case 'hoursofoperation':
        if ($exists) {
          return self::formatField($lastField, $value);
        } else {
          return array(HOURS_UNAVAILABLE_STRING);
        }
        break;
      
      case 'phonenumber':
        $phoneNumbers = array();
        if ($exists) {
          foreach ($value as $phonenumber) {
            if (isset($phonenumber['mobiledisplay']) && $phonenumber['mobiledisplay'] == 'false') { 
              continue;
            }
            
            $phoneNumbers[] = array(
              'description' => self::formatField('description', (string)$phonenumber->description[0]),
              'number'      => self::formatField($field,        (string)$phonenumber->number[0]),
            );
          }
        }
        return $phoneNumbers;
      
      case 'url':
        if ($exists) {
          return self::formatField($lastField, (string)$value);
        }
        break;
      
      case 'format':
        $formats = array();
        if ($exists) {
          foreach ($value as $format) {
            $attributes = $format->attributes();
            if (isset($attributes['type'], $attributes['type'][0])) {
              $formats['type']       = strval($attributes['type'][0]);
              $formats['typeDetail'] = strval($format);
            } else {
              $formats['formatDetail'] = strval($format);
            }
          }
        }
        return $formats;
      
      case 'identifier':
        $availability = array();
        if ($exists) {
          foreach ($value as $identifier) {
            $entry = array(
              'type' => '',
              'typeDetail' => strval($identifier[0]),
            );
            $attributes = $identifier->attributes();
            if (isset($attributes['type'], $attributes['type'][0])) {
              $entry['type'] = strval($attributes['type'][0]);
            }
            $availability[] = $entry;
          }
        }
        return $availability;
      
      default: 
        if ($exists && isset($value[0])) {
          return self::formatField($lastField, (string)$value[0]);
        }
        break;
    }
    return $default;
  }
  
  
  private static function getDateTimeForTimeString($timeString) {
    if ($timeString == '12' || $timeString == 'noon') {
      $timeString = '12pm';
    }
    if ($timeString == 'midnight') {
      $timeString = '12am';
    }
    //error_log($timeString);
    return new DateTime($timeString, self::getTimezone());
  }
  
  private static function isOpen($hours) {
    $now = time();
    
    $todaysHours = self::hoursOpenToday($hours);
    foreach ($todaysHours as $hoursString) {
      if (preg_match_all(';([0-9:]+(am|pm)?|noon|midnight)\s*-\s*([0-9:]+(am|pm)?|noon|midnight);', $hoursString, $matches)) {
        foreach ($matches[0] as $index => $match) {
          $start = self::getDateTimeForTimeString($matches[1][$index]);
          $end   = self::getDateTimeForTimeString($matches[3][$index]);
          
          if (intval($start->format('U')) <= $now && $now <= intval($end->format('U'))) {
            return true;
          }          
        }
      }
    }
    return false;
  }
  
  private static function hoursOpenToday($hours) {
    $now = new DateTime('now', self::getTimezone());
    $today = $now->format('Ymd');
    $dayOfWeek = intval($now->format('N'));

    $hoursOpenToday = array();

    foreach ($hours as $dateHours) {
      if (is_array($dateHours)) {
        if ($dateHours['date'] == $today) {
          $hoursOpenToday[] = $dateHours['hours'];
        }
      } else {
        $re = ';([A-Za-z]+).?\s*-\s*([A-Za-z]+).?\s+,?([0-9:]+(am|pm)?\s*-\s*[0-9:]+(am|pm)?);';
        if (preg_match($re, $dateHours, $matches)) {
          if (isset($matches[1], $matches[2])) {
            $first = strval($matches[1]);
            $last  = strval($matches[2]);
            if (isset(self::$daysOfWeek[$first], self::$daysOfWeek[$last]) && 
                self::$daysOfWeek[$first] <= $dayOfWeek && $dayOfWeek <= self::$daysOfWeek[$last]) {
              $hoursOpenToday[] = $matches[3];
            }
          }
        }
      }
    }
    if (!count($hoursOpenToday)) {
      $hoursOpenToday[] = 'closed';
    }
    return $hoursOpenToday;
  }
  
  private static function hoursOpenTodayString($hours) {
    return implode(', ', self::hoursOpenToday($hours));
  }
  
  private static function getInstitutionsByType($type=null) {
    $xml = self::query('librariesInfo', 'URL_LIBRARIES_INFO');
      
    $institutions = array();
    
    foreach ($xml->institution as $institution) {
      if (isset($type) && $type != self::getField($institution, 'type')) { continue; }
      //error_log(print_r($institution, true));

      $hours = self::getField($institution, 'hoursofoperation');

      $institutions[] = array(
        'name'         => self::getField($institution, 'name'),
        'primaryname'  => self::getField($institution, 'primaryname'),
        'id'           => self::getField($institution, 'id'),
        'type'         => $type,
        'address'      => self::getField($institution, array('location', 'address')),
        'latitude'     => self::getField($institution, array('location', 'latitude')),
        'longitude'    => self::getField($institution, array('location', 'longitude')),
        'hrsOpenToday' => self::hoursOpenTodayString($hours),
        'isOpenNow'    => self::isOpen($hours),
      );
      //error_log(print_r(end($institutions), true));
    }
    return $institutions;
  }
  
  private static function getInstitutionShortInfo($type) {
    $xml = self::query('librariesInfo', 'URL_LIBRARIES_INFO');
      
    $institutions = array();
    
    foreach ($xml->institution as $institution) {
      if ($type != 'all' && $type != self::getField($institution, 'type')) { continue; }

      $institutions[] = array(
        'name'         => self::getField($institution, 'name'),
        'id'           => self::getField($institution, 'id'),
        'type'         => $type,
        'isOpenNow'    => self::isOpen(self::getField($institution, 'hoursofoperation')),
      );
    }
    return $institutions;    
  }
  
  private static function getInstitutionDetails($type, $id, $preferredName) {
    $urlBase = ($type == 'library') ? 'URL_LIB_DETAIL_BASE' : 'URL_ARCHIVE_DETAIL_BASE';
  
    $xml = self::query("$type-{$id}", $urlBase, $id);
    
    //error_log(print_r($institution, true));
    $primaryName = self::getField($xml, array('names', 'primaryname'));
    if ($preferredName != $primaryName) {
      // Make sure name is valid
      $foundName = false;
      if (isset($xml->names->alternatename)) {
        foreach ($xml->names->alternatename as $name) {
          if (strval($name[0]) == $preferredName) {
            $foundName = true;
          }
        }
      }      
      if (!$foundName) {
        $preferredName = $primaryName;
      }
    }
    
    $hours = self::getField($xml, 'hoursofoperation');
    $hoursIsArray = count($hours) && is_array($hours[0]);
    
    return array(
      'name'                   => $preferredName,
      'primaryname'            => $primaryName,
      'id'                     => self::getField($xml, 'id'),
      'type'                   => self::getField($xml, 'type'),
      'address'                => self::getField($xml, array('location', 'address')),
      'directions'             => self::getField($xml, array('location', 'directions')),
      'longitude'              => self::getField($xml, array('location', 'longitude')),
      'latitude'               => self::getField($xml, array('location', 'latitude')),
      'website'                => self::getField($xml, 'url'),
      'email'                  => self::getField($xml, array('emailaddresses', 'email', 'emailaddress')),
      'phone'                  => self::getField($xml, array('phonenumbers', 'phonenumber')),
      'weeklyHours'            => $hoursIsArray ? $hours : array(), 
      'isOpenNow'              => self::isOpen($hours),
      'hrsOpenToday'           => self::hoursOpenTodayString($hours),
      'hoursOfOperationString' => !$hoursIsArray ? implode('; ', $hours) : '',
    );
  }
  
  //
  // Public functions
  //
  
  public static function getLibrarySearchCodes() {
    $xml = self::query('librariesOpts', 'URL_LIBRARIES_OPTS');
    
    $codes = array();
    for ($i = 0; isset($xml->refinebylibrary->label[$i]); $i++) {
      $code = strval($xml->refinebylibrary->value[$i]);
      if ($code) {
        $codes[$code] = strval($xml->refinebylibrary->label[$i]);
      }
    }
    
    return $codes;
  }

  public static function getFormatSearchCodes() {
    $xml = self::query('librariesOpts', 'URL_LIBRARIES_OPTS');
    
    $codes = array();
    for ($i = 0; isset($xml->refinebyformat->label[$i]); $i++) {
      $code = strval($xml->refinebyformat->value[$i]);
      if ($code) {
        $codes[$code] = strval($xml->refinebyformat->label[$i]);
      }
    }
    
    return $codes;
  }
  
  public static function getLibraries() {
    return self::getInstitutionsByType('library');
  }

  public static function getArchives() {
    return self::getInstitutionsByType('archive');
  }

  public static function getOpenNow() {
    return self::getInstitutionShortInfo('library');
  }

  public static function getLibraryDetails($id, $preferredName) {
    return self::getInstitutionDetails('library', $id, $preferredName);
  }

  public static function getArchiveDetails($id, $preferredName) {
    return self::getInstitutionDetails('archive', $id, $preferredName);
  }

  public static function searchItems($queryTerms, $locationTerms='', $formatTerms='', $pubDateTerms='', $page=1) {
    $xml = self::query("search-{$queryTerms}-loc:{$locationTerms}-fmt:{$formatTerms}-date:{$pubDateTerms}-p:{$page}", 
      'URL_LIBRARIES_SEARCH_BASE', 
      http_build_query(array(
        'q'   => $queryTerms, 
        'lib' => $locationTerms, 
        'fmt' => $formatTerms,
      ))
    );
    
    $results = array(
      'total'    => self::getField($xml, 'totalResults', 0),
      'start'    => self::getField($xml, 'startIndex', 0),
      'end'      => 0,
      'pagesize' => self::getField($xml, 'itemsPerPage', 0),
      'items'    => array(),
    );
    
    if (isset($xml->resultSet, $xml->resultSet->item)) {
      foreach ($xml->resultSet->item as $item) {
        $namespaces = $item->getNameSpaces(true);
        $dc = $item->children($namespaces['dc']);

        $index = self::getField($item, 'position');
        if ($index > $results['end']) { $results['end'] = $index; }

        $results['items'][] = array(
          'index'        => $index,
          'itemId'       => self::getField($item, 'id'),
          'creator'      => self::getField($dc,   'creator'),
          'title'        => self::getField($dc,   'title'),
          'date'         => self::getField($dc,   'date'),
          'format'       => self::getField($dc,   'format'),
          'edition'      => self::getField($item, 'edition'),
        );
      }
    }
    
    return $results;
  }

  public static function getFullAvailability($id) {
    $xml = self::query("avail-{$id}", 'URL_LIBRARIES_AVAILABILITY_BASE', $id);
    
    // Get the full list of known institution IDs for later use below
    $institutionIDs = array();
    foreach (self::getInstitutionsByType() as $institution) {
      $institutionIDs[$institution['id']] = true;
    }
    
    $results = array();
    
    foreach ($xml->branch as $branch) {
      $library = array(
        'name' => strval($branch->repository->name[0]),
        'id'   => strval($branch->repository->id[0]),
        'type' => strval($branch->repository->type[0]), 
        'collection' => array(),
      );
      
      // Make sure library is in the list of institutions:
      if (!isset($institutionIDs[$library['id']])) { continue; }
      
      foreach ($branch->collection as $collection) {
        $collectionName = strval($collection->collectionname[0]);
        $collectionCallNumber = strval($collection->callnumber);
        
        $callNumbersMatch = true;
        $descriptionsMatch = true;
        $tempCallNumber = null;
        $tempDescription = null;

        $itemsByStat = array();
        $itemCount = 0;
        
        // Walk over the items if there are any
        if (isset($collection->items->itemrecord)) {
          foreach ($collection->items->itemrecord as $item) {
            $statString = strtolower($item->stat[0]);
            $stats = explode(' | ', $statString);
            $statMain = $stats[0];
            
            $checkedOutItem = 
              (strpos($statString, 'checked out') !== FALSE &&
               strpos($statString, 'not checked out') === FALSE) || 
              (strpos($statString, 'on hold') !== FALSE &&
               strpos($statString, 'not on hold') === FALSE);
            
            if (!isset($itemsByStat[$statMain])) {
              $itemsByStat[$statMain] = array(
                'statMain'            => $statMain,
                'availableItems'      => array(),
                'checkedOutItems'     => array(),
                'unavailableItems'    => array(),
                'collectionOnlyItems' => array(),
                'availCount'          => 0,
                'unavailCount'        => 0,
                'requestCount'        => 0,
                'checkedOutCount'     => 0,
                'scanAndDeliverCount' => 0,
                'collectionOnlyCount' => 0,
              );
            }
             
            $itemByStat = array(
              'callNumber'           => strval($item->call),
              'description'          => strval($item->desc),
              'collectionName'       => $collectionName,
              'collectionCallNumber' => $collectionCallNumber,
              'available'            => strcasecmp($item->isavail, 'Y') == 0,
              'unavailable'          => strcasecmp($item->isavail, 'Y') != 0,
              'checkedOutItem'       => $checkedOutItem,
              'canRequest'           => false,
              'requestUrl'           => '',
              'canScanAndDeliver'    => false,
              'scanAndDeliverUrl'    => '',
              'statMain'             => $statMain,
              'statSecondary'        => implode(' ', array_slice($stats, 1)),
            );
            
            if (!strlen($itemByStat['callNumber'])) {
              $itemByStat['callNumber'] = $collectionCallNumber;
            }
            
            if (isset($item->req)) {
              foreach($item->req as $req) {
                if (stripos($req, 'scan') !== FALSE) {
                  $itemByStat['scanAndDeliverUrl'] = strval($req['href']);
                  $itemByStat['canScanAndDeliver'] = true;
                } else {
                  $itemByStat['requestUrl'] = strval($req['href']);
                  $itemByStat['canRequest'] = true;
                }
              }
            }
            
            if ($itemByStat['available']        ) { $itemsByStat[$statMain]['availCount']++; }
            if ($itemByStat['unavailable']      ) { $itemsByStat[$statMain]['unavailCount']++; }
            if ($itemByStat['canRequest']       ) { $itemsByStat[$statMain]['requestCount']++; }
            if ($itemByStat['checkedOutItem']   ) { $itemsByStat[$statMain]['checkedOutCount']++; }
            if ($itemByStat['canScanAndDeliver']) { $itemsByStat[$statMain]['scanAndDeliverCount']++; }
            
            if ($itemByStat['available']) {
              $itemsByStat[$statMain]['availableItems'][] = $itemByStat;
              
            } else if ($itemByStat['checkedOutItem']) {
              $itemsByStat[$statMain]['checkedOutItems'][] = $itemByStat;
              
            } else {
              $itemsByStat[$statMain]['unavailableItems'][] = $itemByStat;
            }
            
            $itemCount++;
            
            // Remember whether or not all items have the same call number and/or description            
            if (!isset($tempCallNumber)) {
              $tempCallNumber = $itemByStat['callNumber'];
              
            } else if ($tempCallNumber != $itemByStat['callNumber']) {
              $callNumbersMatch = false;
            }
            if (!isset($tempDescription)) {
              $tempDescription = $itemByStat['description'];
              
            } else if ($tempDescription != $itemByStat['description']) {
              $descriptionsMatch = false;
            }
          }
        }
        
        if ($itemCount == 0) { // No items means it's a collection only item
          $collectionAvail = array();
          if (isset($collection->holdtag)) {
            foreach($collection->holdtag as $holdtag) {
              $collectionAvail[] = strval($holdtag->availval[0]);
            }
          }
          
          $itemsByStat['collection'] = array(
            'statMain'            => 'collection',
            'availableItems'      => array(),
            'checkedOutItems'     => array(),
            'unavailableItems'    => array(),
            'collectionOnlyItems' => array(
              array(
                'collectionName'       => $collectionName,
                'collectionCallNumber' => $collectionCallNumber,
                'collectionAvailVal'   => $collectionAvail,
                'noItemMessage'        => strval($branch->noitems[0]),
              ),
            ),
            'availCount'          => 0,
            'unavailCount'        => 0,
            'requestCount'        => 0,
            'checkedOutCount'     => 0,
            'scanAndDeliverCount' => 0,
            'collectionOnlyCount' => 1,
          );
        }

        // Categorize by type        
        if (count($itemsByStat) <= 1) {
          if (isset($itemsByStat['collection'])) {
            $displayType = 'V';   // type 5: collection-only display
            
          } else if ($callNumbersMatch && $descriptionsMatch) {
            $displayType = 'I';   // type 1: everything same
          } else {
            $displayType = 'III'; // type 3: only diff call numbers or desc
          }
        } else {
          if ($callNumbersMatch && $descriptionsMatch) {
            $displayType = 'II';  // type 2: only diff holding stats within same collection
          } else {
            $displayType = 'IV';  // type 4: everything diff
          }
        }
        
        // Add the collection if it is valid
        if (strlen($collectionName)) {
          $library['collection'][] = array(
            'collectionName'       => $collectionName,
            'collectionCallNumber' => $collectionCallNumber,
            'displayType'          => $displayType,
            'itemsByStat'          => array_values($itemsByStat),
          );
        }
      }
      
      $results[] = $library;
    }

    return $results;
  }
  
  public static function getItemRecord($id) {
    $item = self::query("item-{$id}", 'URL_LIBRARIES_ITEM_RECORD_BASE', $id);
  
    $namespaces = $item->getNameSpaces(true);
    $dc = $item->children($namespaces['dc']);
    
    return array(
      'itemId'         => $id,
      'title'          => self::getField($dc,   'title'),
      'creator'        => self::getField($dc,   'creator'),
      'publisher'      => self::getField($dc,   'publisher'),
      'date'           => self::getField($dc,   'date'),
      'format'         => self::getField($dc,   'format'),
      'edition'        => self::getField($item, 'edition'),
      'identifier'     => self::getField($dc,   'identifier'),
      'numberofimages' => self::getField($item, 'numberofimages', 0),
      'worktype'       => self::getField($item, 'workType'),
      'thumbnail'      => self::getField($item, 'thumbnail'),
      'cataloglink'    => self::getField($item, 'cataloglink'),
      'fullimagelink'  => self::getField($item, 'fullimage'),
    );
  }
  
  public static function getImageThumbnail($id) {
    return self::getItemRecord($id);
  }
}
