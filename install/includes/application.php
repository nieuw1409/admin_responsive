<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// set default timezone if none exists (PHP 5.3 throws an E_WARNING)
  if ((strlen(ini_get('date.timezone')) < 1) && function_exists('date_default_timezone_set')) {
    date_default_timezone_set(@date_default_timezone_get());
  }
  
  if (isset($_GET['language'])) {
    setcookie('osC_Language', $_GET['language']);

    $language = $_GET['language'];
  } elseif (isset($_COOKIE['osC_Language'])) {
    $language = $_COOKIE['osC_Language'];
  } else {
    $language = 'english';
  }
  

  require('includes/functions/general.php');
  require('includes/functions/database.php');
  require('includes/functions/html_output.php');
?>
