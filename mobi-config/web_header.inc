<?php

// per-installation constants.

define('WEBROOT', '/var/www/html');
define('LIBDIR', '/opt/mitmobile/mobi-lib');

// TODO: make this into an auto include
define('HELP_HEADER', '/var/www/html/page_builder/help.php');

/* path where cookies should be stored on the users' browser. */
define('HTTPROOT', '/');

date_default_timezone_set('America/New_York');

// fetch per-server settings.

$ini = parse_ini_file("web_constants_prod.ini");

require WEBROOT . '/page_builder/Page.inc';
require WEBROOT . '/page_builder/page_tools.inc';

$page = Page::factory();

class DataServerException extends Exception {
}

class DeviceNotSupported extends Exception {
}

if($ini['USE_PRODUCTION_ERROR_HANDLER']) {
  set_exception_handler('exception_handler');
}

function exception_handler($exception) {
  global $ini;

  if(is_a($exception, 'DataServerException')) {
    $error_query = 'code=data&url=' . urlencode($_SERVER['REQUEST_URI']);
  } else if(is_a($exception, 'DeviceNotSupported')) {
    $error_query = 'code=device_notsupported';
  } else {
    $error_query = 'code=internal';
  }
  $error_url = HTTPROOT . "error-page/?{$error_query}";

  // a text representation of the exception
  ob_start();
    var_dump($exception);
  $text = ob_get_contents();
  ob_end_clean();

  if(!Page::factory()->is_spider()) {
    mail(
      $ini['DEVELOPER_EMAIL'], 
      "mobile web page experiencing problems",
      "the following url is throwing exceptions: http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}\n" .
      "Exception:\n" . 
      "$text\n" .
      "The User-Agent: \"{$_SERVER['HTTP_USER_AGENT']}\"\n" .
      "The referer URL: \"{$_SERVER['HTTP_REFERER']}\""
    );
  }

  header("Location: {$error_url}");
  die(0);
}

?>