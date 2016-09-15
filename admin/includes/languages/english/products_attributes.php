<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE_OPT', 'Product Options');
define('HEADING_TITLE_VAL', 'Option Values');
define('HEADING_TITLE_ATRIB', 'Products Attributes');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_PRODUCT', 'Product Name');
define('TABLE_HEADING_OPT_NAME', 'Option Name');
define('TABLE_HEADING_OPT_VALUE', 'Option Value');
define('TABLE_HEADING_OPT_PRICE', 'Value Price');
define('TABLE_HEADING_OPT_PRICE_PREFIX', 'Prefix');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_DOWNLOAD', 'Downloadable products:');

define('TABLE_HEADING_GROUP_NAME', 'Group Name');
define('TABLE_HEADING_DELETE', 'Delete');
define('TABLE_HEADING_INSERT', 'Insert');

define('TABLE_TEXT_FILENAME', 'Filename:');
define('TABLE_TEXT_MAX_DAYS', 'Expiry days:');
define('TABLE_TEXT_MAX_COUNT', 'Maximum download count:');

define('MAX_ROW_LISTS_OPTIONS', 10);

define('TEXT_WARNING_OF_DELETE', 'This option has products and values linked to it - it is not safe to delete it.');
define('TEXT_OK_TO_DELETE', 'This option has no products and values linked to it - it is safe to delete it.');
define('TEXT_OPTION_ID', 'Option ID');
define('TEXT_OPTION_NAME', 'Option Name');
//BOF - Zappo - Option Types v2 - Add defines for Option Type, Comment, Length, Sort Order
define('TABLE_HEADING_OPT_TYPE', 'Option Type');
define('TABLE_HEADING_OPT_LENGTH', 'Value Length');
define('TABLE_HEADING_OPT_ORDER', 'Sort Order');
define('TABLE_HEADING_OPT_COMMENT', 'Comment');
define('TABLE_HEADING_OPT_STOCK', 'Stock');
define('TABLE_HEADING_OPT_HELP', 'Notice: For Text and Textarea \'Option type\' the maximum length is 32. Also the \'Option value\' is fixed to TEXT with ID 0. Do not add an \'Option value\' yourself.'); //PBOR added help for text options
//EOF - Zappo - Option Types v2 - Add defines for Option Type, Comment, Length, Sort Order
// BOF Separate Pricing Per Customer
define('TABLE_HEADING_HIDDEN', 'Hidden');
define('TEXT_HIDDEN_FROM_GROUPS', 'Hidden from groups: ');
define('TEXT_GROUPS_NONE', 'none');
// 0: Icons for all groups for which the category or product is hidden, mouse-over the icons to see what group
// 1: Only one icon and only if the category or product is hidden for a group, mouse-over the icon to what groups
define('LAYOUT_HIDE_FROM', '0'); 
define('NAME_WINDOW_ATTRIBUTES_GROUPS_POPUP', 'AttributeGroupPrices');
define('TEXT_GROUP_PRICES', 'Group Prices');
define('TEXT_MOUSE_OVER_GROUP_PRICES', 'Edit customer group prices for this attribute in a pop-up window');
// EOF Separate Pricing Per Customer
define('TABLE_HEADING_TRACK_STOCK',                'Stock') ; // qtpro 461
define('TEXT_STOCK_YES',                           'Yes') ; // qtpro 461
define('TEXT_STOCK_NO',                            'No') ; // qtpro 461 
define('TEXT_TAB_OPTIONS',                         'Product Options'); // TABBED ATTRIBUTES
define('TEXT_TAB_VALUES',                          'Option Values'); // TABBED ATTRIBUTES
define('TEXT_TAB_ATTRIBUTES',                      'Products Attributes'); // TABBED ATTRIBUTES

define('TEXT_INFO_HEADING_NEW_OPTION',             'Add New Product Option') ;
define('TEXT_INFO_HEADING_EDIT_OPTION',            'Edit Product Option') ;
define('TEXT_STOCK_ACTIVE',                        'Active');  
define('TEXT_STOCK_NOT_ACTIVE',                    'Not Active'); 

define('TEXT_INFO_HEADING_NEW_VALUE',              'Add New Product Value') ;
define('TEXT_INFO_HEADING_EDIT_VALUE',             'Edit Product Value') ;

define( 'TEXT_INFO_HEADING_DELETE_OPTION_ATTRIBUTE', 'Delete Product Atribute' ) ;
define( 'TEXT_INFO_DELETE_INTRO',                    'Delete Product Atribute ?' ) ;
define('TEXT_INFO_HEADING_NEW_OPTION_ATTRIBUTE',     'Add New Product Option Attribute') ;
?>