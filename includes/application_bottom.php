<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// close session (store variables)
// BOF QPBPP for SPPC
  if (isset($_SESSION['min_order_qty_not_met'])) {
	  unset($_SESSION['min_order_qty_not_met']);
  }
  if (isset($_SESSION['qty_blocks_not_met'])) {
	  unset($_SESSION['qty_blocks_not_met']);
  }
// EOF QPBPP for SPPC
// bof page cache
if (!tep_session_is_registered('customer_id') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache')) {
	global $page_cache;
	$page_cache->end_page_cache();
}
// eof page cache
  tep_session_close();

  if (STORE_PAGE_PARSE_TIME == 'true') {
    $time_start = explode(' ', PAGE_PARSE_START_TIME);
    $time_end = explode(' ', microtime());
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
    error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);

    if (DISPLAY_PAGE_PARSE_TIME == 'true') {
      echo '<span class="smallText">Parse Time: ' . $parse_time . 's</span>';
    }
  }

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded == true) && ($ini_zlib_output_compression < 1) ) {
    if ( (PHP_VERSION < '4.0.4') && (PHP_VERSION >= '4') ) {
      tep_gzip_output(GZIP_LEVEL);
    }
  }
?>
