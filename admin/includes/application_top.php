<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

// Start the clock for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// check support for register_globals
  if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3) ) {
    exit('Server require_oncement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');
  }

// load server configuration parameters
  if (file_exists('includes/local/configure.php')) { // for developers
    include('includes/local/configure.php');
  } else {
    include('includes/configure.php');
  }
  
// removed at request of hosting provider   require_once( DIR_FS_CATALOG . 'includes/osc_sec.php' );   // osc secureti 7834
  
// Define the project version --- obsolete, now retrieved with tep_get_version()
  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.3');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// kdm define special general functions
  require(DIR_WS_FUNCTIONS . 'gfc_general.php');  

// set php_self in the local scope
// 2.3.4  $PHP_SELF = (((strlen(ini_get('cgi.fix_pathinfo')) > 0) && ((bool)ini_get('cgi.fix_pathinfo') == false)) || !isset($HTTP_SERVER_VARS['SCRIPT_NAME'])) ? basename($HTTP_SERVER_VARS['PHP_SELF']) : basename($HTTP_SERVER_VARS['SCRIPT_NAME']);
  $req = parse_url($HTTP_SERVER_VARS['SCRIPT_NAME']);
  $PHP_SELF = substr($req['path'], ($request_type == 'SSL') ? strlen(DIR_WS_HTTPS_ADMIN) : strlen(DIR_WS_ADMIN));

// Used in the "Backup Manager" to compress backups
  define('LOCAL_EXE_GZIP', 'gzip');
  define('LOCAL_EXE_GUNZIP', 'gunzip');
  define('LOCAL_EXE_ZIP', 'zip');
  define('LOCAL_EXE_UNZIP', 'unzip');

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
  define('CURRENCY_SERVER_PRIMARY', 'oanda');
  define('CURRENCY_SERVER_BACKUP', 'xe');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
//  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
//  while ($configuration = tep_db_fetch_array($configuration_query)) {
//    define($configuration['cfgKey'], $configuration['cfgValue']);
//  }


// define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// initialize the logger class
  require(DIR_WS_CLASSES . 'logger.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// set the session name and save path
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

  @ini_set('session.use_only_cookies', (SESSION_FORCE_COOKIE_USE == 'True') ? 1 : 0);

// lets start our session
  tep_session_start();

  if ( (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

// set the language
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
      $lng->set_language($HTTP_GET_VARS['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
	$languages_code = $lng->language['code'] ;
  }

// set the current store
  if (!tep_session_is_registered('multi_stores') || isset($HTTP_GET_VARS['multi_stores'])) {
    if (!tep_session_is_registered('multi_stores')) {
      tep_session_register('multi_stores');
      tep_session_register('multi_stores_id');
      tep_session_register('multi_stores_config');	  
      tep_session_register('multi_stores_url');	  	  
      tep_session_register('multi_stores_absolute');	  	  
      tep_session_register('multi_stores_std_cust_group');	  
      tep_session_register('multi_stores_admin_color');	  
    }

    include(DIR_WS_CLASSES . 'multi_stores.php');
    $stores = new multi_stores();

    if (isset($HTTP_GET_VARS['multi_stores']) && tep_not_null($HTTP_GET_VARS['multi_stores'])) {
      $stores->set_multi_stores( $HTTP_GET_VARS['multi_stores']);
    } else {
      $stores->set_multi_stores( $stores->default_number );
   }
 
    $multi_stores = $stores->default_store['name']  ;
    $multi_stores_id = $stores->default_store['id'] ;
	$multi_stores_config = $stores->default_store['configuration'] ;
	$multi_stores_url = $stores->default_store['url']  ;		
	$multi_stores_absolute = $stores->default_store['absolute']  ;	
	$multi_stores_std_cust_group = $stores->default_store['std_cust_group']  ;	
	$multi_stores_admin_color = $stores->default_store['admin_color']  ;	
  }
  
// EOF MULTI STORES

// Configuration Cache modification start
  require ('includes/configuration_cache_read.php');
// Configuration Cache modification end

// bof multi stores	
// multi stores get the standard customer group to this store
   $stores_query_cust_group = tep_db_query("select stores_std_cust_group, stores_config_table from " . TABLE_STORES . "  where stores_id = '" . SYS_STORES_ID . "'");
   $store_customer_group = tep_db_fetch_array($stores_query_cust_group) ;
   $temp = strval( $store_customer_group[ 'stores_std_cust_group' ] ) ;
   $tmp_config = $store_customer_group[ 'stores_config_table' ] ;
   define( '_SYS_STORE_CUSTOMER_GROUP', $temp ) ;    
   define( '_SYS_STORE_CONFIG_TABLE', $tmp_config ) ;       
// eof multi stores

  
// redirect to login page if administrator is not yet logged in
  if (!tep_session_is_registered('admin')) {
    $redirect = false;

// 2.3.4    $current_page = basename($PHP_SELF);
    $current_page = $PHP_SELF;
// eof 2.3.4	

// if the first page request is to the login page, set the current page to the index page
// so the redirection on a successful login is not made to the login page again
    if ( ($current_page == FILENAME_LOGIN) && !tep_session_is_registered('redirect_origin') ) {
      $current_page = FILENAME_DEFAULT;
      $HTTP_GET_VARS = array();
    }

    if ($current_page != FILENAME_LOGIN) {
      if (!tep_session_is_registered('redirect_origin')) {
        tep_session_register('redirect_origin');

        $redirect_origin = array('page' => $current_page,
                                 'get' => $HTTP_GET_VARS);
      }

// try to automatically login with the HTTP Authentication values if it exists
      if (!tep_session_is_registered('auth_ignore')) {
        if (isset($HTTP_SERVER_VARS['PHP_AUTH_USER']) && !empty($HTTP_SERVER_VARS['PHP_AUTH_USER']) && isset($HTTP_SERVER_VARS['PHP_AUTH_PW']) && !empty($HTTP_SERVER_VARS['PHP_AUTH_PW'])) {
          $redirect_origin['auth_user'] = $HTTP_SERVER_VARS['PHP_AUTH_USER'];
          $redirect_origin['auth_pw'] = $HTTP_SERVER_VARS['PHP_AUTH_PW'];
        }
      }

      $redirect = true;
    }

    if (!isset($login_request) || isset($HTTP_GET_VARS['login_request']) || isset($HTTP_POST_VARS['login_request']) || isset($HTTP_COOKIE_VARS['login_request']) || isset($HTTP_SESSION_VARS['login_request']) || isset($HTTP_POST_FILES['login_request']) || isset($HTTP_SERVER_VARS['login_request'])) {
      $redirect = true;
    }

    if ($redirect == true) {
      tep_redirect(tep_href_link(FILENAME_LOGIN, (isset($redirect_origin['auth_user']) ? 'action=process' : '')));
    }

    unset($redirect);
  }

// include the language translations
  $_system_locale_numeric = setlocale(LC_NUMERIC, 0); // 2.3.3.4
  require(DIR_WS_LANGUAGES . $language . '.php');
  setlocale(LC_NUMERIC, $_system_locale_numeric); // Prevent LC_ALL from setting LC_NUMERIC to a locale with 1,0 float/decimal values instead of 1.0 (see bug #634) // 2.3.3.4
  
  $current_page = basename($PHP_SELF);
  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
    include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }
  // added stock.php 
  include(DIR_WS_LANGUAGES . $language . '/' . 'stock.php');

// define our localization functions
  require(DIR_WS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// setup our boxes
  require(DIR_WS_CLASSES . 'table_block.php');
  require(DIR_WS_CLASSES . 'box.php');

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'alertbox.php');

//bof enhanced specials compatability
//  require_once(DIR_WS_CLASSES . 'message_stack.php');
// override the admin message stack to use the store message stack
  if(false !== strpos($PHP_SELF, FILENAME_SPECIALS_MAINTENANCE)) {
     require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'boxes.php');
     require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'message_stack.php');
    } else {
// initialize the message stack for output messages
     require(DIR_WS_CLASSES . 'message_stack.php');
    }
//eof enhanced specials	
  $messageStack = new messageStack;

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// entry/item info classes
  require(DIR_WS_CLASSES . 'object_info.php');

// email classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// file uploading class
  require(DIR_WS_CLASSES . 'upload.php');

// action recorder
  require(DIR_WS_CLASSES . 'action_recorder.php');

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// initialize configuration modules
  require_once(DIR_WS_CLASSES . 'cfg_modules.php');
  $cfgModules = new cfg_modules();

// bos XSell  
// the following cache blocks are used in the Tools->Cache section
// ('language' in the filename is automatically replaced by available languages)
//  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
//                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
//                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true)
//                       );
// the following cache blocks are used in the Tools->Cache section
// ('language' in the filename is automatically replaced by available languages)
// ('currency' in the filename is automatically replaced by available currencies)
//  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
//                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
//                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true),
//                        array('title' => TEXT_CACHE_XSELL_PRODUCTS, 'code' => 'xsell_products', 'file' => 'xsell_products-language.cache', 'multiple' => true)
//                       );
// eof XSell	
// adapted for Hide products and categories from groups
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language-cg', 'multiple' => true),
// categories accordion
                        array('title' => TEXT_CACHE_CATEGORIES_ACCORDION, 'code' => 'categories_accordion', 'file' => 'categories_box2-language.cache', 'multiple' => true),  
// end categories accordion						
                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language-cg', 'multiple' => true),
                        array('title' => TEXT_CACHE_XSELL_PRODUCTS, 'code' => 'xsell_products', 'file' => 'xsell_products-language-cg', 'multiple' => true),
                        array('title' => TEXT_CACHE_PRODUCTS_COUNT, 'code' => 'products_count', 'file' => 'products_count-cg', 'multiple' => true)						
                       );				   
?>
