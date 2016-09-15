<?php
/* overtollig verwijderd
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2008 osCommerce
  Released under the GNU General Public License
*/
// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());
  define( 'SYS_DISPLAY_ALL_PAGES', 'all' ) ;  // display all pages for boxes and heater tags and social bookmarks  

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// check support for register_globals
  if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3) ) {
    exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');
  }

// load server configuration parameters
  if (file_exists('includes/local/configure.php')) { // for developers
    include('includes/local/configure.php');
  } else {
    include('includes/configure.php');
  }
  

  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
	  exit ;
    }
  }
  
// removed on request off hosting provider  require( DIR_FS_CATALOG . 'includes/osc_sec.php' );   // osc secureti 7834

// define the project version --- obsolete, now retrieved with tep_get_version()
  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.3');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  // bof fwr ultimate seo urls  $PHP_SELF = (((strlen(ini_get('cgi.fix_pathinfo')) > 0) && ((bool)ini_get('cgi.fix_pathinfo') == false)) || !isset($HTTP_SERVER_VARS['SCRIPT_NAME'])) ? basename($HTTP_SERVER_VARS['PHP_SELF']) : basename($HTTP_SERVER_VARS['SCRIPT_NAME']);
/**
  * ULTIMATE Seo Urls 5 PRO by FWR Media
  * function to return the base filename 
  */
  function usu5_base_filename() {
    // Probably won't get past SCRIPT_NAME unless this is reporting cgi location
    $base = new ArrayIterator( array( 'SCRIPT_NAME', 'PHP_SELF', 'REQUEST_URI', 'ORIG_PATH_INFO', 'HTTP_X_ORIGINAL_URL', 'HTTP_X_REWRITE_URL' ) );
    while ( $base->valid() ) {
      if ( array_key_exists(  $base->current(), $_SERVER ) && !empty(  $_SERVER[$base->current()] ) ) {
        if ( false !== strpos( $_SERVER[$base->current()], '.php' ) ) {
          preg_match( '@[a-z0-9_]+\.php@i', $_SERVER[$base->current()], $matches );
          if ( is_array( $matches ) && ( array_key_exists( 0, $matches ) )
                                    && ( substr( $matches[0], -4, 4 ) == '.php' )
                                    && ( is_readable( $matches[0] ) ) ) {
            return $matches[0];
          } 
        } 
      }
      $base->next();
    }
    // Some odd server set ups return / for SCRIPT_NAME and PHP_SELF when accessed as mysite.com (no index.php) where they usually return /index.php
    if ( ( $_SERVER['SCRIPT_NAME'] == '/' ) || ( $_SERVER['PHP_SELF'] == '/' ) ) {
      return 'index.php';
    }
    // Return the standard RC3 code 
    return ( ( ( strlen( ini_get( 'cgi.fix_pathinfo' ) ) > 0) && ( (bool)ini_get( 'cgi.fix_pathinfo' ) == false ) ) || !isset( $_SERVER['SCRIPT_NAME'] ) ) ? basename( $_SERVER['PHP_SELF'] ) : basename( $_SERVER['SCRIPT_NAME'] );
  } // End function
// set php_self in the local scope
  $PHP_SELF = usu5_base_filename();  

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
    // Security Pro by FWR Media
     include_once DIR_WS_MODULES . 'fwr_media_security_pro.php';
     $security_pro = new Fwr_Media_Security_Pro;
     // If you need to exclude a file from cleansing then you can add it like below
     //$security_pro->addExclusion( 'some_file.php' );
     $security_pro->cleanse( $PHP_SELF );
    // End - Security Pro by FWR Media	
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');
// bof optimize tep_get_parent ajout pour la gestion optimisée des catégories.
require(DIR_WS_CLASSES.'categorie.class.php');
require(DIR_WS_CLASSES.'categories.class.php');

$categoriesTree = Categories::getCategories();
// eof fin ajout pour la gestion optimisée

// set the application parameters
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

// if gzip_compression is enabled, start to buffer the output
// 2.3.3
//  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && !headers_sent() ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
//      if (PHP_VERSION >= '4.0.4') {
//        ob_start('ob_gzhandler');
//      } else {
//        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
//        ob_start();
//        ob_implicit_flush();
      if (PHP_VERSION < '5.4' || PHP_VERSION > '5.4.5') { // see PHP bug 55544
        if (PHP_VERSION >= '4.0.4') {
          ob_start('ob_gzhandler');
        } elseif (PHP_VERSION >= '4.0.1') {
          include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
          ob_start();
          ob_implicit_flush();
        }
      }
//    } else {
    } elseif (function_exists('ini_set')) {
// eof 2.3.3	
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
      $GET_array = array();
      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
      $vars = explode('/', substr(getenv('PATH_INFO'), 1));
      do_magic_quotes_gpc($vars);
      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {
        if (strpos($vars[$i], '[]')) {
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $HTTP_GET_VARS[$vars[$i]] = $vars[$i+1];
        }
        $i++;
      }

      if (sizeof($GET_array) > 0) {
        while (list($key, $value) = each($GET_array)) {
          $HTTP_GET_VARS[$key] = $value;
        }
      }
    }
  }

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');
  
// include wishlist class
  require(DIR_WS_CLASSES . 'wishlist.php');  

// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCsid');
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

// set the session ID if it exists
// bof 2.3.4
//   if (isset($HTTP_POST_VARS[tep_session_name()])) {
//     tep_session_id($HTTP_POST_VARS[tep_session_name()]);
//   } elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[tep_session_name()]) ) {
//     tep_session_id($HTTP_GET_VARS[tep_session_name()]);
//   }
   if ( SESSION_FORCE_COOKIE_USE == 'False' ) {
    if ( isset($HTTP_GET_VARS[tep_session_name()]) && (!isset($HTTP_COOKIE_VARS[tep_session_name()]) || ($HTTP_COOKIE_VARS[tep_session_name()] != $HTTP_GET_VARS[tep_session_name()])) ) {
      tep_session_id($HTTP_GET_VARS[tep_session_name()]);
    } elseif ( isset($HTTP_POST_VARS[tep_session_name()]) && (!isset($HTTP_COOKIE_VARS[tep_session_name()]) || ($HTTP_COOKIE_VARS[tep_session_name()] != $HTTP_POST_VARS[tep_session_name()])) ) {
      tep_session_id($HTTP_POST_VARS[tep_session_name()]);
    }
   }
// eof 2.3.4   

// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {
      tep_session_start();
      $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
//    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    
    $user_agent = '';
    
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    }

    $spider_flag = false;

    if (tep_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (tep_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      tep_session_start();
      $session_started = true;
    }
  } else {
    tep_session_start();
    $session_started = true;
  }

  if ( ($session_started == true) && (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

// initialize a session token
  if (!tep_session_is_registered('sessiontoken')) {
    $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());
    tep_session_register('sessiontoken');
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!tep_session_is_registered('SSL_SESSION_ID')) {
      $SESSION_SSL_ID = $ssl_session_id;
      tep_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!tep_session_is_registered('SESSION_USER_AGENT')) {
      $SESSION_USER_AGENT = $http_user_agent;
      tep_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = tep_get_ip_address();
    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {
      $SESSION_IP_ADDRESS = $ip_address;
      tep_session_register('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// create the shopping cart
  if (!tep_session_is_registered('cart') || !is_object($cart)) {
    tep_session_register('cart');
    $cart = new shoppingCart;
  }
  // include CountProductsStore object
  require(DIR_WS_CLASSES . 'CountProductsStore.php');
  $countproducts = new CountProductsStore();

  
// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');
  
  

// set the language
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
      tep_session_register('languages_total');	  
      tep_session_register('languages_description');		  
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
	$languages_total = count($lng->catalog_languages) ;
	$languages_description = $lng->language['name'] ;
	//$languages_number = 2 ;
  }
  /**
  * ULTIMATE Seo Urls 5 PRO by FWR Media
  */
  Usu_Main::i()->setVar( 'languages_id', $languages_id )
               ->setVar( 'request_type', $request_type ) 
               ->setVar( 'session_started', $session_started ) 
               ->setVar( 'sid', $SID ) 
               ->setVar( 'language', $language )
               ->setVar( 'filename', $PHP_SELF )
               ->initiate( ( isset( $lng ) && ( $lng instanceof language ) ) ? $lng : array(), $languages_id, $language );
// eof fwr ultimate seo urls
			   
// include the language translations
  $_system_locale_numeric = setlocale(LC_NUMERIC, 0); // 2.3.3.4
  require(DIR_WS_LANGUAGES . $language . '.php');
  setlocale(LC_NUMERIC, $_system_locale_numeric); // Prevent LC_ALL from setting LC_NUMERIC to a locale with 1,0 float/decimal values instead of 1.0 (see bug #634) // 2.3.3.4
  # include the cache class
    include(DIR_WS_CLASSES . 'cache.class.php');
    $cache = new cache($languages_id);
    # Get the cache - no parameters will get all GLOBAL cache entries for this language
    $cache->get_cache('GLOBAL');
  
// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// BOF qpbpp
  // include the price formatter classes for the price breaks contribution
  require(DIR_WS_CLASSES . 'PriceFormatter.php');
  $pf = new PriceFormatter;
  require(DIR_WS_CLASSES . 'PriceFormatterStore.php');
  $pfs = new PriceFormatterStore;
// EOF qpbpp
// currency
  if (!tep_session_is_registered('currency') || isset($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!tep_session_is_registered('currency')) tep_session_register('currency');

    if (isset($HTTP_GET_VARS['currency']) && $currencies->is_set($HTTP_GET_VARS['currency'])) {
      $currency = $HTTP_GET_VARS['currency'];
    } else {
      $currency = ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && $currencies->is_set(LANGUAGE_CURRENCY)) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }

/*
  // navigation history
  if (!tep_session_is_registered('navigation') || !is_object($navigation)) {
    tep_session_register('navigation');
    $navigation = new navigationHistory;
  }
  $navigation->add_current_page();
*/
// navigation history
if (isset($_SESSION['navigation'])) {
if (PHP_VERSION < 4) {
$broken_navigation = $navigation;
$navigation = new navigationHistory;
$navigation->unserialize($broken_navigation);
}
} else {
tep_session_register('navigation');
$navigation = new navigationHistory;
}
$navigation->add_current_page(); 

// bof 231 option type 
	// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'alertbox.php');  // bootstrap design
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;
// eof 231 option type 

// action recorder
  include('includes/classes/action_recorder.php');

  // BOF: Down for Maintenance except for admin ip
if (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE != getenv('REMOTE_ADDR')){
	if (DOWN_FOR_MAINTENANCE=='true' and !strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) { tep_redirect(tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME)); }
	}
// do not let people get to down for maintenance page if not turned on
if (DOWN_FOR_MAINTENANCE=='false' and strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}
// EOF: WebMakers.com Added: Down for Maintenance
// Shopping cart actions
// wishlist data
  if(!tep_session_is_registered('wishList')) {
  	tep_session_register('wishList');
  	$wishList = new wishlist;
  }

//Wishlist actions (must be before shopping cart actions)
  if(isset($HTTP_POST_VARS['wishlist'])) {
	if(isset($HTTP_POST_VARS['products_id'])) {
		if(isset($HTTP_POST_VARS['id'])) {
			$attributes_id = $HTTP_POST_VARS['id'];
			tep_session_register('attributes_id');
		}
		$wishlist_id = $HTTP_POST_VARS['products_id'];
		tep_session_register('wishlist_id');
	}
	tep_redirect(tep_href_link(FILENAME_WISHLIST));
  }
// eof wishlist
  if (isset($HTTP_GET_VARS['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
// 2.3.4      $goto = basename($PHP_SELF);
      $goto = $PHP_SELF;
      if ($HTTP_GET_VARS['action'] == 'buy_now') {
// BOE: XSell
        if (isset($HTTP_GET_VARS['product_to_buy_id'])) {
          $parameters = array('action', 'pid', 'products_to_but_id');
		} else {
          $parameters = array('action', 'pid', 'products_id');
		}
// EOE: XSell
      } else {
        $parameters = array('action', 'pid');
      }
    }
    switch ($HTTP_GET_VARS['action']) {
	  // clear the cart
	  case 'clear_cart'     : $cart->remove_all();
	                          tep_redirect( tep_href_link( FILENAME_SHOPPING_CART ) ) ;
							  break;
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {
                                if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {
                                  $cart->remove($HTTP_POST_VARS['products_id'][$i]);
                                } else {
                                  $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
                                  $cart->add_cart($HTTP_POST_VARS['products_id'][$i], $HTTP_POST_VARS['cart_quantity'][$i], $attributes, false);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // customer adds a product from the products page
	case 'add_product' :    if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id'])) {
                                //BOF Option Types v2.3.1 - File uploading: Purge the Temporary Upload Dir
                                $purgeDir = opendir(TMP_DIR) or die ('Could not open '.TMP_DIR);
                                while ($file = readdir($purgeDir)) {
                                  if ($file != ('.htaccess') && $file != ('.') && $file != ('..') && filemtime(TMP_DIR . $file) < strtotime(OPTIONS_TYPE_PURGETIME)) {
                                    unlink(TMP_DIR . $file);  // Delete file from server...
                                    tep_db_query("delete from " . TABLE_FILES_UPLOADED . " where files_uploaded_name = '" . $file . "'"); // Remove File's database entry....
                                  }
                                }
                                closedir($purgeDir);
                                //EOF Option Types v2.3.1 - File uploading: Purge the Temporary Upload Dir

                                //BOF Option Types v2.3.1 - ONE LINE - Set real_ids for processing
                                $real_ids = $HTTP_POST_VARS['id'];
                                //BOF Option Types v2.3.1 - File uploading: save uploaded files with unique file names, in the proper folder
                                if ($HTTP_POST_VARS['number_of_uploads'] > 0) {
                                  require(DIR_WS_CLASSES . 'upload.php');
                                  for ($i = 1; $i <= $HTTP_POST_VARS['number_of_uploads']; $i++) {
                                    $TEMP_FILE = $_FILES['id']['tmp_name'][TEXT_PREFIX . $HTTP_POST_VARS[UPLOAD_PREFIX . $i]];
                                    if (tep_not_null($TEMP_FILE) && $TEMP_FILE != 'none') {
                                      $products_options_file = new upload('id');
                                      //BOF Option Types v2.3.1 - Set Upload directory (Registered customers in Uploads, other in Temporary folder)
                                      if (tep_session_is_registered('customer_id')) {  // IF the customer is registered, use Upload Dir
                                        $products_options_file->set_destination(UPL_DIR);
                                      } else { // If the customer is not registered, use Temporary Dir
                                        $products_options_file->set_destination(TMP_DIR);
                                      }
                                      //EOF Option Types v2.3.1 - Set Upload directory (Registered customers in Uploads, other in Temporary folder)
                                      if ($products_options_file->parse(TEXT_PREFIX . $HTTP_POST_VARS[UPLOAD_PREFIX . $i])) {
                                        if (tep_session_is_registered('customer_id')) {
                                          tep_db_query("insert into " . TABLE_FILES_UPLOADED . " (sesskey, customers_id, files_uploaded_name, date) values('" . tep_session_id() . "', '" . $customer_id . "', '" . tep_db_input($products_options_file->filename) . "', '" . date("d-m-y") . "')");
                                        } else {
                                          tep_db_query("insert into " . TABLE_FILES_UPLOADED . " (sesskey, files_uploaded_name, date) values('" . tep_session_id() . "', '" . tep_db_input($products_options_file->filename) . "', '" . date("d-m-y") . "')");
                                        }
                                        //BOF Option Types v2.3.1 - Set File Prefix
                                        if (OPTIONS_TYPE_FILEPREFIX == 'Database') {  //  Database ID as File prefix
                                          $insert_id = tep_db_insert_id() . '_';
                                        } else {  //  Date, time or both as File prefix (Change date formatting here)
                                          if (OPTIONS_TYPE_FILEPREFIX == 'Date' || OPTIONS_TYPE_FILEPREFIX == 'DateTime') {
                                            $insert_id = 'D'.date("d-m-y_");
                                          }
                                          $insert_id .= (OPTIONS_TYPE_FILEPREFIX == 'DateTime' || OPTIONS_TYPE_FILEPREFIX == 'Time') ? 'T'.date("H-i_") : '';
                                        }
                                        //EOF Option Types v2.3.1 - Set File Prefix
                                        // Update filename in Database with correct prefix (For comparing database names with real files)
                                        tep_db_query("update " . TABLE_FILES_UPLOADED . " set files_uploaded_name = '" . tep_db_input($insert_id . $products_options_file->filename) . "' where sesskey = '" . tep_session_id() . "' and files_uploaded_name = '" . tep_db_input($products_options_file->filename) . "' and date = '" . date("d-m-y") . "'");
                                        $real_ids[TEXT_PREFIX . $HTTP_POST_VARS[UPLOAD_PREFIX . $i]] = $insert_id . $products_options_file->filename;
                                        $products_options_file->set_filename($insert_id . $products_options_file->filename);
                                        if (!($products_options_file->save())) {
                                          break 2;
                                        }
                                      } else {
                                        break 2;
                                      }
                                    } else { // No file uploaded -- use previously uploaded file (From Dropdown)
                                      $real_ids[TEXT_PREFIX . $HTTP_POST_VARS[UPLOAD_PREFIX . $i]] = $HTTP_POST_VARS[TEXT_PREFIX . UPLOAD_PREFIX . $i];
                                    }
                                  }
                                }
                                //EOF Option Types v2.3.1 - File uploading: save uploaded files with unique file names, in the proper folder
// qtpro add ??		

//++++ QT Pro: Begin Changed code
                                $attributes=array();
                                if (isset($HTTP_POST_VARS['attrcomb']) && (preg_match("/^\d{1,10}-\d{1,10}(,\d{1,10}-\d{1,10})*$/",$HTTP_POST_VARS['attrcomb']))) {
                                  $attrlist=explode(',',$HTTP_POST_VARS['attrcomb']);
                                  foreach ($attrlist as $attr) {
                                    list($oid, $oval)=explode('-',$attr);
                                    if (is_numeric($oid) && $oid==(int)$oid && is_numeric($oval) && $oval==(int)$oval)
                                      $attributes[$oid]=$oval;
                                  }
								  $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $attributes + $HTTP_POST_VARS['id'])) + $HTTP_POST_VARS['cart_quantity'], $attributes + $HTTP_POST_VARS['id']);
                                } else {
								  $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id'])) + $HTTP_POST_VARS['cart_quantity'], $HTTP_POST_VARS['id']);	
								}


//                                $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $attributes + $HTTP_POST_VARS['id'])) + $HTTP_POST_VARS['cart_quantity'], $attributes + $HTTP_POST_VARS['id']);
//++++ QT Pro: End Changed Code
                              						
                              //$cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id'])) + $HTTP_POST_VARS['cart_quantity'], $HTTP_POST_VARS['id']);	
								  
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // customer removes a product from their shopping cart
      case 'remove_product' : if (isset($HTTP_GET_VARS['products_id'])) {
                                $cart->remove($HTTP_GET_VARS['products_id']);
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // performed by the 'buy now' button in product listings and review page
//      case 'buy_now' :        if (isset($HTTP_GET_VARS['products_id'])) {
// BOF: XSell
      case 'buy_now' :        if (isset($HTTP_GET_VARS['product_to_buy_id'])) {
								if (tep_has_product_attributes($HTTP_GET_VARS['product_to_buy_id'])) {
								  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['product_to_buy_id']));
								} else {
								  $cart->add_cart($HTTP_GET_VARS['product_to_buy_id'], $cart->get_quantity($HTTP_GET_VARS['product_to_buy_id'])+1);
								}
                              } elseif (isset($HTTP_GET_VARS['products_id'])) {
// EOF: XSell
                                if (tep_has_product_attributes($HTTP_GET_VARS['products_id'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']));
                                } else {
                                  $cart->add_cart($HTTP_GET_VARS['products_id'], $cart->get_quantity($HTTP_GET_VARS['products_id'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      case 'notify' :         if (tep_session_is_registered('customer_id')) {
                                if (isset($HTTP_GET_VARS['products_id'])) {
                                  $notify = $HTTP_GET_VARS['products_id'];
                                } elseif (isset($HTTP_GET_VARS['notify'])) {
                                  $notify = $HTTP_GET_VARS['notify'];
                                } elseif (isset($HTTP_POST_VARS['notify'])) {
                                  $notify = $HTTP_POST_VARS['notify'];
                                } else {
// 2.3.4                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                                    tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action', 'notify')))); 
                                }
                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
// 2.3.3								
//                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $notify[$i] . "' and customers_id = '" . $customer_id . "'");
                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . (int)$customer_id . "'");
// eof 2.3.3								  
                                  $check = tep_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
// 2.3.3								  
//                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . $notify[$i] . "', '" . $customer_id . "', now())");
                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . (int)$customer_id . "', now())");
// eof 2.3.3									
                                  }
                                }
// 2.3.4                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                                tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action', 'notify')))); 
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['products_id'])) {
// 2.3.3	  
//                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
// eof 2.3.3								
                                $check = tep_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
// 2.3.3								
//                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
// eof 2.3.3								  
                                }
// 2.3.4                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));
                                tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')))); 
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['pid'])) {
                                if (tep_has_product_attributes($HTTP_GET_VARS['pid'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['pid']));
                                } else {
                                  $cart->add_cart($HTTP_GET_VARS['pid'], $cart->get_quantity($HTTP_GET_VARS['pid'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
    }
  }

// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  tep_update_whos_online();

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// auto activate and expire banners
  if ( SYS_USE_BANNERS == 'True' ) {
    require(DIR_WS_FUNCTIONS . 'banner.php');
    tep_activate_banners();
    tep_expire_banners();
   }	

// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');
  tep_expire_specials();

  require(DIR_WS_CLASSES . 'osc_template.php');
  $oscTemplate = new oscTemplate();

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];	
  } elseif (isset($HTTP_GET_VARS['products_id']) && !isset($HTTP_GET_VARS['manufacturers_id'])) {
    $cPath = tep_get_product_path($HTTP_GET_VARS['products_id']);
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
  
// bootstrap
// include category tree class
  require(DIR_WS_CLASSES . 'category_tree.php');

// include the breadcrumb class and start the breadcrumb trail
  require(DIR_WS_CLASSES . 'breadcrumb.php');
  $breadcrumb = new breadcrumb;

  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
  $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

/*** Begin Header Tags SEO ***/
// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
/*** Begin Header Tags SEO 331 ***/	
//     $categories_query = tep_db_query("select categories_htc_title_tag from " . TABLE_CATEGORIES_DESCRIPTION . " cd left join " . TABLE_CATEGORIES . " c on cd.categories_id = c.categories_id where c.categories_status = '1' and cd.categories_id = '" . (int)$cPath_array[$i] . "' and cd.language_id = '" . (int)$languages_id . "'");
     $categories_query = tep_db_query("select IF(categories_htc_breadcrumb_text !='', categories_htc_breadcrumb_text, categories_name) as title from " . TABLE_CATEGORIES_DESCRIPTION . " cd left join " . TABLE_CATEGORIES . " c on cd.categories_id = c.categories_id where c.categories_status = '1' and cd.categories_id = '" . (int)$cPath_array[$i] . "' and cd.language_id = '" . (int)$languages_id . "'");
/*** End Header Tags SEO 331 ***/	 
//     $categories_query = tep_db_query("select categories_htc_title_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "' LIMIT 1");
	 
	  if (tep_db_num_rows($categories_query) > 0) {
        $categories = tep_db_fetch_array($categories_query);
/*** Begin Header Tags SEO 331 ***/			
//        $breadcrumb->add($categories['categories_htc_title_tag'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
        $breadcrumb->add($categories['title'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
/*** End Header Tags SEO 331 ***/		
      } else {
        break;
	
      }
    }
  } elseif (isset($_GET['manufacturers_id'])) {
/*** Begin Header Tags SEO 331 ***/  
//    $manufacturers_query = tep_db_query("select manufacturers_htc_title_tag from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' AND languages_id = '" . (int)$languages_id . "' LIMIT 1");
    $manufacturers_query = tep_db_query("select IF(mi.manufacturers_htc_breadcrumb_text !='',mi.manufacturers_htc_breadcrumb_text, m.manufacturers_name) as title 
	             from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id=mi.manufacturers_id 
				 where m.manufacturers_id= " . (int)$_GET['manufacturers_id'] . " AND languages_id = " . (int)$languages_id	) ;

	//manufacturers_htc_title_tag from " .                                                                                                                            TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' AND languages_id = '" . (int)$languages_id . "' LIMIT 1");
/*** End Header Tags SEO 331 ***/	
    if (tep_db_num_rows($manufacturers_query)) {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
/*** Begin Header Tags SEO 331 ***/ 	  
//      $breadcrumb->add($manufacturers['manufacturers_htc_title_tag'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']));
      $breadcrumb->add($manufacturers['title'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']));
/*** End Header Tags SEO 331 ***/		  
    }
  }
/*** Begin Header Tags SEO 331 ***/
// add the products name to the breadcrumb trail
 if (isset($_GET['products_id'])) {
//  $products_query = tep_db_query("select IF(pd.products_head_breadcrumb_text !='', pd.products_head_breadcrumb_text,pd.products_name) as title from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_id = '" . (int)$_GET['products_id'] . "' and pd.language_id ='" .  (int)$languages_id . "' LIMIT 1");
     $products_query = tep_db_query("select IF(pd.products_head_breadcrumb_text !='', pd.products_head_breadcrumb_text,pd.products_name) as title, p.products_model as model from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_id = " . (int)$_GET['products_id'] . " and pd.language_id =" .  (int)$languages_id);
 
  if (tep_db_num_rows($products_query)) {
    $products = tep_db_fetch_array($products_query);
//    $breadcrumb->add($products['products_head_title_tag'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
    $breadcrumb->add($products['title'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
    }
  }  
 // add the products name to the breadcrumb trail
/* if (isset($_GET['products_id'])) {
     $parts = explode('/', $_SERVER["SCRIPT_NAME"]);
     $file = $parts[count($parts) - 1]; 
     $addModel = false;
     $db_query = tep_db_query("select 1 from " . TABLE_HEADERTAGS . " where page_name = '" . tep_db_input($file) . "' and append_model=1 and find_in_set('" . SYS_STORES_ID . "', stores_id ) != 0 ");
     if (tep_db_num_rows($db_query)) {
         $addModel = true;
     }

     $products_query = tep_db_query("select IF(pd.products_head_breadcrumb_text !='', pd.products_head_breadcrumb_text,pd.products_name) as title, p.products_model as model from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_id = " . (int)$_GET['products_id'] . " and pd.language_id =" .  (int)$languages_id);
     if (tep_db_num_rows($products_query)) {
         $products = tep_db_fetch_array($products_query);
         $title = ($addModel && tep_not_null($products['model']) ? $products['model'] . ' - ' . $products['title'] : $products['title']);
         $args = isset($_GET['reviews_id']) ? tep_get_all_get_params() : 'cPath=' . $cPath . '&products_id=' . $_GET['products_id'] ;
         $breadcrumb->add($title, tep_href_link(  $cPath . $file, tep_get_all_get_params()));
     }		 
  }
/*** End Header Tags SEO 331 ***/		 
  // Discount Code - start
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') {
    if (!tep_session_is_registered('sess_discount_code')) tep_session_register('sess_discount_code');
    if (!empty($HTTP_GET_VARS['discount_code'])) $sess_discount_code = tep_db_prepare_input($HTTP_GET_VARS['discount_code']);
    if (!empty($HTTP_POST_VARS['discount_code'])) $sess_discount_code = tep_db_prepare_input($HTTP_POST_VARS['discount_code']);
  }
  // Discount Code - end 
  // bof optimize tep get tax  
  // tax class
  require('includes/classes/tax.php');
  $osC_Tax = new osC_Tax;
  
// Supertracker
  if ( SYS_USE_SUPERTRACKER == 'True' ) {
    require(DIR_WS_CLASSES . 'supertracker.php');
	$tracker = new supertracker;
	$tracker->update(); 
  }

//Ban IP Addresses
    require(DIR_WS_INCLUDES . '_banned.php');
/*--------------------------------------------------------*\
#	Page cache contribution - by Chemo
#	Define the pages to be cached in the $cache_pages array
\*--------------------------------------------------------*/
$cache_pages = array('index.php'); //, 'product_info.php'
if (!tep_session_is_registered('customer_id') && ENABLE_PAGE_CACHE == 'true') {
	# Start the output buffer for the shopping cart
    if (!class_exists('bm_shopping_cart')) {
      include(DIR_WS_MODULES . 'boxes/bm_shopping_cart.php');
   }

   $bm_shopping_cart = new bm_shopping_cart();
   $cart_cache = $bm_shopping_cart->execute();	
//	ob_start();
//	require(DIR_WS_BOXES . 'shopping_cart.php');
//	$cart_cache = ob_get_clean();
	# End the output buffer for cart and save as $cart_cache string

	# Loop through the $cache_pages array and start caching if found
	foreach ($cache_pages as $index => $page){
		if ( strpos($_SERVER['PHP_SELF'], $page) ){
			include_once(DIR_WS_CLASSES . 'page_cache.php');
			$page_cache = new page_cache($cart_cache);
			# The cache timelife is set globally 
			# in the admin control panel settings
			# Example below overrides the setting to 60 minutes
			# Leave blank to use default setting
			# $page_cache->cache_this_page(60);
			$page_cache->cache_this_page();
		} # End if
	} # End foreach
} # End if	

// auto activate and expire popups
  require(DIR_WS_FUNCTIONS . 'popup.php');
  tep_activate_popups();
  tep_expire_popups();   
