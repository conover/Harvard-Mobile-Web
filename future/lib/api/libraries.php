<?php

/****************************************************************
 *
 *  Copyright 2010 The President and Fellows of Harvard College
 *  Copyright 2010 Modo Labs Inc.
 *
 *****************************************************************/

function getArg($key, $default='') {
  return isset($_REQUEST[$key]) ? stripslashes(trim($_REQUEST[$key])) : $default;
}

require_once realpath(LIB_DIR.'/feeds/Libraries.php');
error_log("COMMAND {$_REQUEST['command']}");
switch ($_REQUEST['command']) {
  case 'libraries':
    $data = Libraries::getLibraries();
    break;

  case 'archives':
    $data = Libraries::getArchives();
    break;

  case 'opennow':
    $data = Libraries::getOpenNow();
    break;

  case 'libdetail':
    $libId = getArg('id');
    $libName = getArg('name');
    $data = Libraries::getLibraryDetails($libId, $libName);
    break;

  case 'archivedetail':
    $archiveId = getArg('id');
    $archiveName = getArg('name');
    $data = Libraries::getArchiveDetails($archiveId, $archiveName);
    break;

  case 'search':
    $data = Libraries::searchItems(array(
      'keywords' => getArg('keywords'), 
      'title'    => getArg('title'),
      'author'   => getArg('author'),
      'location' => getArg('location'),
      'format'   => getArg('format'),
      'pubDate'  => getArg('pubDate'),
      'language' => getArg('language'),
    ), getArg('page', '1'));
    break;

  case 'fullavailability':
    $itemid = getArg('itemId');
    $data = Libraries::getFullAvailability($itemid);
    break;

  case 'itemdetail':
    $itemid = getArg('itemId');
    $data = Libraries::getItemRecord($itemid);
    break;

  case 'imagethumbnail':
    $imageId = getArg('itemId');
    $data = Libraries::getImageThumbnail($imageId);
    break;
  
  case 'searchcodes':
    $data = array(
      'formats'   => Libraries::getFormatSearchCodes(),
      'locations' => Libraries::getLibrarySearchCodes(),
      'pubDates'  => Libraries::getPubDateSearchCodes(),
    );
    break;
}

echo json_encode($data);
