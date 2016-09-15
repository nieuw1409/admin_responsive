<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

////
//! Write out serialized data.
//  write_cache uses serialize() to store $var in $filename.
//  $var      -  The variable to be written out.
//  $filename -  The name of the file to write to.
  function write_cache(&$var, $filename) {
    $filename = DIR_FS_CACHE . $filename;
    $success = false;

// try to open the file
    if ($fp = @fopen($filename, 'w')) {
// obtain a file lock to stop corruptions occuring
      flock($fp, 2); // LOCK_EX
// write serialized data
      fputs($fp, serialize($var));
// release the file lock
      flock($fp, 3); // LOCK_UN
      fclose($fp);
      $success = true;
    }

    return $success;
  }

////
//! Read in seralized data.
//  read_cache reads the serialized data in $filename and
//  fills $var using unserialize().
//  $var      -  The variable to be filled.
//  $filename -  The name of the file to read.
  function read_cache(&$var, $filename, $auto_expire = false){
    $filename = DIR_FS_CACHE . $filename;
    $success = false;

    if (($auto_expire == true) && file_exists($filename)) {
      $now = time();
      $filetime = filemtime($filename);
      $difference = $now - $filetime;

      if ($difference >= $auto_expire) {
        return false;
      }
    }

// try to open file
    if ($fp = @fopen($filename, 'r')) {
// read in serialized data
      $szdata = fread($fp, filesize($filename));
      fclose($fp);
// unserialze the data
      $var = unserialize($szdata);

      $success = true;
    }

    return $success;
  }

////
//! Get data from the cache or the database.
//  get_db_cache checks the cache for cached SQL data in $filename
//  or retreives it from the database is the cache is not present.
//  $SQL      -  The SQL query to exectue if needed.
//  $filename -  The name of the cache file.
//  $var      -  The variable to be filled.
//  $refresh  -  Optional.  If true, do not read from the cache.
  function get_db_cache($sql, &$var, $filename, $refresh = false){
    $var = array();

// check for the refresh flag and try to the data
    if (($refresh == true)|| !read_cache($var, $filename)) {
// Didn' get cache so go to the database.
//      $conn = mysql_connect("localhost", "apachecon", "apachecon");
      $res = tep_db_query($sql);
//      if ($err = mysql_error()) trigger_error($err, E_USER_ERROR);
// loop through the results and add them to an array
      while ($rec = tep_db_fetch_array($res)) {
        $var[] = $rec;
      }
// write the data to the file
      write_cache($var, $filename);
    }
  }

////
//! Cache the categories box
// Cache the categories box
  function tep_cache_categories_box($auto_expire = false, $refresh = false) {
    global $cPath, $language;

	// new
// bof multi stores
//  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
  if (isset($_SESSION['sppc_customer_group_id']) ) {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  } else {
// $customer_group_id = '0';
    $customer_group_id = _SYS_STORE_CUSTOMER_GROUP ;
// eof multi stores	
    }
    $cache_output = '';

//    if (($refresh == true) || !read_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath, $auto_expire)) {
    if (($refresh == true) || !read_cache($cache_output, 'categories_box-' . $language . '-cg' . $customer_group_id . '.cache' . $cPath, $auto_expire)) {	
      if (!class_exists('bm_categories')) {
        include(DIR_WS_MODULES . 'boxes/bm_categories.php');
      }

      $bm_categories = new bm_categories();
      $cache_output = $bm_categories->getData();

//      write_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath);
      write_cache($cache_output, 'categories_box-' . $language . '-cg' . $customer_group_id . '.cache' . $cPath);	  
    }

    return $cache_output;
  }

////
//! Cache the manufacturers box
// Cache the manufacturers box
  function tep_cache_manufacturers_box($auto_expire = false, $refresh = false) {
    global $HTTP_GET_VARS, $language;

    $cache_output = '';

    $manufacturers_id = '';
    if (isset($HTTP_GET_VARS['manufacturers_id']) && is_numeric($HTTP_GET_VARS['manufacturers_id'])) {
      $manufacturers_id = $HTTP_GET_VARS['manufacturers_id'];
    }

    if (($refresh == true) || !read_cache($cache_output, 'manufacturers_box-' . $language . '.cache' . $manufacturers_id, $auto_expire)) {
      if (!class_exists('bm_manufacturers')) {
        include(DIR_WS_MODULES . 'boxes/bm_manufacturers.php');
      }

      $bm_manufacturers = new bm_manufacturers();
      $cache_output = $bm_manufacturers->getData();

      write_cache($cache_output, 'manufacturers_box-' . $language . '.cache' . $manufacturers_id);
    }

    return $cache_output;
  }

////
//! Cache the also purchased module
// Cache the also purchased module
//  function tep_cache_also_purchased($auto_expire = false, $refresh = false) {
//    global $HTTP_GET_VARS, $language, $languages_id;
//
//    $cache_output = '';
//
//    if (isset($HTTP_GET_VARS['products_id']) && is_numeric($HTTP_GET_VARS['products_id'])) {
//      if (($refresh == true) || !read_cache($cache_output, 'also_purchased-' . $language . '.cache' . $HTTP_GET_VARS['products_id'], $auto_expire)) {
//        ob_start();
//       include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
//        $cache_output = ob_get_contents();
//        ob_end_clean();
//        write_cache($cache_output, 'also_purchased-' . $language . '.cache' . $HTTP_GET_VARS['products_id']);
//      }
//    }
//
//    return $cache_output;
//  }
  function tep_cache_also_purchased($auto_expire = false, $refresh = false) {
    global $HTTP_GET_VARS, $language, $languages_id;

    $cache_output = '';

// bof multi stores
//  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
  if (isset($_SESSION['sppc_customer_group_id']) ) {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  } else {
// $customer_group_id = '0';
    $customer_group_id = _SYS_STORE_CUSTOMER_GROUP ;
// eof multi stores	
  }

    if (isset($HTTP_GET_VARS['products_id']) && is_numeric($HTTP_GET_VARS['products_id'])) {
      if (($refresh == true) || !read_cache($cache_output, 'also_purchased-' . $language . '-cg' . $customer_group_id . '.cache' . $HTTP_GET_VARS['products_id'], $auto_expire)) {
        ob_start();
        include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
        $cache_output = ob_get_contents();
        ob_end_clean();
// in version 2.0.1 and earlier of the Hide products from customer groups for SPPC there is a bug in 
// the includes/modules/also_purchased_products.php. If you don't replace the code or establishing
// the customer group id with the one a few lines before, or add "global $sppc_customer_group_id"
// $customer_group_id is suddenly empty here!!
        write_cache($cache_output, 'also_purchased-' . $language . '-cg' . $customer_group_id. '.cache' . $HTTP_GET_VARS['products_id']);
      }
    }

    return $cache_output;
  }

  
////
// Phocea Cache XSELL module
// Cache the also Xell module
  function tep_cache_xsell_products($auto_expire = false, $refresh = false) {
    global $HTTP_GET_VARS, $language, $languages_id;

    $cache_output = '';

    if (isset($HTTP_GET_VARS['products_id']) && is_numeric($HTTP_GET_VARS['products_id'])) {
      if (($refresh == true) || !read_cache($cache_output, 'xsell_products-' . $language . '.cache' . $HTTP_GET_VARS['products_id'], $auto_expire)) {
        ob_start();
        include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
        $cache_output = ob_get_contents();
        ob_end_clean();
        write_cache($cache_output, 'xsell_products-' . $language . '.cache' . $HTTP_GET_VARS['products_id']);
      }
    }

    return $cache_output;
  }  

// Cache the categories accordion box
function tep_cache_categories_box2($auto_expire = false, $refresh = false) {
global $cPath, $language;

$cache_output = '';

if (($refresh == true) || !read_cache($cache_output, 'categories_box2-' . $language . '.cache' . $cPath, $auto_expire)) {
if (!class_exists('bm_categories_accordion')) {
include(DIR_WS_MODULES . 'boxes/bm_categories_accordion.php');
}

$bm_categories_accordion = new bm_categories_accordion();
$cache_output = $bm_categories_accordion->getData();

write_cache($cache_output, 'categories_box2-' . $language . '.cache' . $cPath);
}

return $cache_output;
} 
  function tep_cache_products_count($auto_expire = false, $refresh = false) {
    $cache_output = '';
    $customer_group_id = tep_get_cust_group_id() ;
    if (($refresh == true) || !read_cache($cache_output, 'products_count-cg' . $customer_group_id . '.cache', $auto_expire)) {
    $category_query = tep_db_query("select categories_id from categories where find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0");
    while ($_categories = tep_db_fetch_array($category_query)) {
      $categories[] = $_categories['categories_id'];
    }
        $products_query = tep_db_query("select count(*) as number_in_category, categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id in (" . implode(",", $categories) . ") and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 group by categories_id");
          while ($_prods_in_category = tep_db_fetch_array($products_query)) {
          $prods_in_category[$_prods_in_category['categories_id']] = $_prods_in_category['number_in_category'];
          }
        $cache_output = $prods_in_category;
        write_cache($cache_output, 'products_count-cg' . $customer_group_id . '.cache');
    }
    return $cache_output;
  }   
?>