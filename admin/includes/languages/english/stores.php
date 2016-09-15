<?php
/*
  $Id: stores.php,v 1.0 2004/08/23 01:58:58 rmh Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Stores');

define('TABLE_HEADING_STORES', 'Stores');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_HEADING_NEW_STORE', 'New Store');
define('TEXT_HEADING_EDIT_STORE', 'Edit Store');
define('TEXT_HEADING_DELETE_STORE', 'Delete Store');

define('TEXT_STORES', 'Stores:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('TEXT_NEW_INTRO', 'Please fill out the following information for the new store');
define('TEXT_EDIT_INTRO', 'Please make any necessary changes');

define('TEXT_STORES_NAME', 'Stores Name:');
define('TEXT_STORES_IMAGE', 'Stores Image:');
define('TEXT_STORES_URL', 'URL to Stores catalog: <br>(i.e. http://www.yourdomain.com/catalog)');
define('TEXT_STORES_ABSOLUTE', 'Absolute path for the Store catalog: <br>(i.e. /home/user/public_html/catalog)');
define('TEXT_STORES_CONFIG_TABLE', 'Stores Config Table:<br /> Preferred config_????');
define('TEXT_STORES_CUSTOMERS_GROUP_NAME', 'Standard CustomerGroup for this Store <br /> ');
define('TEXT_STORES_ADMIN_COLOR', 'The colors for administration');

define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this store?');
define('TEXT_DELETE_IMAGE', 'Delete stores image?');
define('TEXT_INSERT_TABLE', 'Insert default configuration table?');
define('TEXT_DELETE_TABLE', 'Delete configuration table?');
define('TEXT_DELETE_WARNING_PRODUCTS', 'Warning: There are %s products still linked to this store!');
define('TEXT_UPDATE_WARNING_CONFIG', 'Warning: Configuration table renamed! Make sure to update your catalog database_tables.php file');
define('ERROR_DEFAULT_STORE', 'Error: You cannot delete default store');
define('ERROR_STORES_NAME_CONFIG', 'Error: You must enter Store Name & Configuration Table');
define('ERROR_STORES_CONFIG_TABLE_EXISTS', 'Error: Configuration Table already Exists');
?>