<?php
/* $Id$ 
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 
Copyright (c) 2002 osCommerce 

Released under the GNU General Public License 
xsell.php
Original Idea From Isaac Mualem im@imwebdesigning.com <mailto:im@imwebdesigning.com> 
Complete Recoding From Stephen Walker admin@snjcomputers.com
*/ 

define('CROSS_SELL_SUCCESS', 'Cross Sell Items Successfully Update For Cross Sell Product #'.$_GET['add_related_product_ID']);
define('SORT_CROSS_SELL_SUCCESS', 'Sort Order Successfully Update For Cross Sell Product #'.$_GET['add_related_product_ID']);
define('HEADING_TITLE', 'Cross-Sell (X-Sell) Admin');
define('TABLE_HEADING_PRODUCT_ID', 'Product Id');
define('TABLE_HEADING_PRODUCT_MODEL', 'Product Model');
define('TABLE_HEADING_PRODUCT_NAME', 'Product Name');
define('TABLE_HEADING_CURRENT_SELLS', 'Current Cross-Sells');
define('TABLE_HEADING_UPDATE_SELLS', 'Update Cross-Sells');
define('TABLE_HEADING_PRODUCT_IMAGE', 'Product Image');
define('TABLE_HEADING_PRODUCT_PRICE', 'Product Price');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_RECIPROCAL_LINK', 'Reciprocal Link');
define('TABLE_HEADING_CROSS_SELL_THIS', 'Cross-Sell This?');
define('TEXT_EDIT_SELLS', 'Edit');
define('TEXT_SORT', 'Prioritize');
define('TEXT_SETTING_SELLS', 'Setting Cross-Sells For');
define('TEXT_PRODUCT_ID', 'Product Id');
define('TEXT_MODEL', 'Model');
define('TABLE_HEADING_PRODUCT_SORT', 'Sort Order');
define('TEXT_NO_IMAGE', 'No Image');
define('TEXT_CROSS_SELL', 'Cross-Sell');
define('HEADING_TITLE_SEARCH', 'Search : ');
define('TEXT_RECIPROCAL_LINK', 'Add reciprocal link?');

// version 2_7_3
define('HEADING_TITLE_XSELL', 'Cross Sells');
define('HEADING_TITLE_NEW_XSELL', 'Add Cross Sells');
define('TEXT_REMOVE_XSELLS', 'Remove reciprocal link ?' ) ;
define('HEADING_TITLE_EDIT_XSELL', 'Setting Cross Sells');

define('TEXT_ADD_XSELLS', 'Add Cross Sell');
define('TEXT_EDIT_XSELLS', 'Edit Cross Sell');
define('TEXT_SEARCH_MODEL' , 'Search Model Number');
define('IMAGE_WITH_XSELLS','Show products with Cross sells attatched to them');
define('IMAGE_WITHOUT_XSELLS','Show products without Cross sells attatched to them');

define('HEADING_TITLE_NEW_XSELL', 'Add Cross Sell Produkts' ) ;
define('HEADING_TITLE_EDIT_XSELL', 'Setting Cross Sell Products ');
define('TEXT_SEARCH_IN_RESULTS', 'Zoeken Products' ) ; // xsell
define('TEXT_FILTER_XSELL', 'Filter Products' ) ; // xsell
define('TEXT_CATEGORY_XSELL', 'Search Category' ) ; // xsell
define('TEXT_SEARCH_XSELL', 'Search Products' ) ; // xsell
define('TEXT_ALL_PRODUCTS', 'All Products' ) ; // xsell
define('TEXT_PRODUCTS_WITHOUT', 'WithOut Cross Sell Products' ) ; // xsell
define('TEXT_PRODUCTS_WITH', 'With Cross Sell Products' ) ; // xsell
define('TEXT_SEARCH_IN_RESULT', 'Search Products' ) ; // xsell
define('TEXT_NO_RESULTS_FOUND', 'NO PRODUCTS PRESENT' ) ;// XSELL
define('TEXT_INFO_HEADING_NEW_XSELL_PRODUCT' , 'Add Cross Sell Products' ) ;
define('TEXT_INFO_HEADING_EDIT_XSELL_PRODUCT', 'Cross Sells from ' ) ;

define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this Cross Sell Product?');
define('TEXT_INFO_HEADING_DELETE_CROSS_SELL', 'Delete Cross Sell Product');
?>