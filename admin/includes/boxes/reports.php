<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
	'icon' => BOX_HEADING_REPORTS_ICON,  
    'heading' => BOX_HEADING_REPORTS,
    'apps' => array(
      array(
        'code' => FILENAME_STATS_PRODUCTS_VIEWED,
        'title' => BOX_REPORTS_PRODUCTS_VIEWED,
        'link' => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED)
      ),
      array(
        'code' => FILENAME_STATS_PRODUCTS_PURCHASED,
        'title' => BOX_REPORTS_PRODUCTS_PURCHASED,
        'link' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED)
      ),
      array(
        'code' => FILENAME_STATS_CUSTOMERS,
        'title' => BOX_REPORTS_ORDERS_TOTAL,
        'link' => tep_href_link(FILENAME_STATS_CUSTOMERS)
      ),
      array(
		'code' => FILENAME_MARGIN_REPORT_PRODUCTS,
		'title' => BOX_REPORTS_MARGIN_REPORT_PRODUCTS,
		'link' => tep_href_link(FILENAME_MARGIN_REPORT_PRODUCTS)
	  ),	  
      array(
		'code' => FILENAME_MARGIN_REPORT_ORDERS,
		'title' => BOX_REPORTS_MARGIN_REPORT_ORDERS,
		'link' => tep_href_link(FILENAME_MARGIN_REPORT_ORDERS)
	  ),		  
      array(
        'code' => FILENAME_SUPERTRACKER,
        'title' => BOX_REPORTS_SUPERTRACKER,
        'link' => tep_href_link(FILENAME_SUPERTRACKER)
      ),	  
      array(
        'code' => FILENAME_STATS_ORDERS_TRACKING,
        'title' => BOX_REPORTS_ORDERS_TRACKING,
        'link' => tep_href_link(FILENAME_STATS_ORDERS_TRACKING)
      ),
     array(
        'code' => FILENAME_STATS_PRODUCTS_KEYWORDS,
        'title' => BOX_REPORTS_PRODUCTS_KEYWORDS,
        'link' => tep_href_link(FILENAME_STATS_PRODUCTS_KEYWORDS)
      ),	  
     array(
        'code' => FILENAME_REPORTS_GOOGLEMAP,
        'title' => BOX_REPORTS_GOOGLEMAP,
        'link' => tep_href_link(FILENAME_REPORTS_GOOGLEMAP)
      ),
//	  array(
//        'code' => FILENAME_STATS_MONTHLY_SALES,
//        'title' => BOX_REPORTS_MONTHLY_SALES,
//        'link' => tep_href_link(FILENAME_STATS_MONTHLY_SALES)
//      ),	
	  array(
        'code' => FILENAME_STATS_LOW_STOCK_ATTRIB,
        'title' => BOX_REPORTS_STATS_LOW_STOCK_ATTRIB,
        'link' => tep_href_link(FILENAME_STATS_LOW_STOCK_ATTRIB)
      ),	  
      array(
        'code' => FILENAME_STATS_LOW_STOCK,
        'title' => BOX_REPORTS_STOCK_LEVEL,
        'link' => tep_href_link(FILENAME_STATS_LOW_STOCK)
      ),
	  array(
        'code' => FILENAME_STATS_WISHLISTS,
        'title' => BOX_REPORTS_WISHLISTS,
        'link' => tep_href_link(FILENAME_STATS_WISHLISTS)
      )	  
    )
  );
?>
