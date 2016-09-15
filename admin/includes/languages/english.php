<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
// 2.3.3.3 setlocale(LC_TIME, 'en_US.ISO_8859-1');
@setlocale(LC_ALL, array('en_US.UTF-8', 'en_US.UTF8', 'enu_usa'));
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('JQUERY_DATEPICKER_I18N_CODE', ''); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
define('JQUERY_DATEPICKER_FORMAT', 'mm/dd/yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="en"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TITLE', 'osCommerce Online Merchant Administration Tool');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT_SITE', 'Support Site');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');
define('HEADER_TITLE_APPLICATIONS',   'Applications');
define('HEADER_TITLE_LANGUAGES',      'Languages');
define('HEADER_TITLE_LOGOFF',         'Log Off');
define('HEADER_TITLE_STORES',         'Stores');
define('HEADER_NAV_TEXT_ADD_CUSTOMER',   'Add Customer');
define('HEADER_NAV_TEXT_ADD_ORDERS',     'Add Order');
define('HEADER_NAV_TEXT_PRODUCTS',       'Products');
define('HEADER_NAV_TEXT_ACTIVE_CUST',    'Active Customer');
define('HEADER_NAV_TEXT_ORDERS',         'Orders');
define('HEADER_NAV_TEXT_CUSTOMERS',      'Customers');
define('HEADER_NAV_TEXT_DIRECT_ACCESS',  'Go To');

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');
define('BOX_CONFIGURATION_ADMINISTRATORS', 'Administrators');
define('BOX_CONFIGURATION_STORE_LOGO', 'Store Logo');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Products Attributes');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
define('BOX_CATALOG_CATEGORIES_DISCOUNT_CATEGORIES',     'Discount Categories');// BOF qpbpp
define('BOX_CATALOG_DISCOUNT_CODE',                      'Discount Codes'); // Discount Code - start
define('BOX_CATALOG_XSELL_PRODUCTS',                     'Cross Sell Products'); // XSell (English)
define('HEADING_TITLE_SEARCH',                           'Search:');// XSell (English)
define('TEXT_CACHE_XSELL_PRODUCTS',                      'Cross Sell Products'); // XSell (English)
define('BOX_CATALOG_PRODUCTS_SORTER',                    'Sort Order Products');// BOF Product Sort
define('BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI',          'Multiple Products Manager');  // bof products multi copy etc
define('BOX_CATALOG_QUICK_PRICE_UPDATE',                 'Quick PriceUpdate');  // quick prices update
define('BOX_CATALOG_EASYPOPULATE',                       'Easy Populate');  // easy populate 276
define('BOX_CATALOG_SPECIALS_BY_CATEGORIES',             'Specials by Categories'); // sppc 716
define('BOX_CATALOG_DISCOUNT_BY_CATEGORIES',             'Discount By Categories');  //TotalB2B start
define('BOX_CATALOG_DISCOUNT_BY_CATEGORIES_MANUFACTURER','Discount By Categories/Manufacturer');  //TotalB2B start
define('BOX_CATALOG_DISCOUNT_BY_MANUFACTURER',           'Kortingen By Manufacturer');  //TotalB2B start
define('BOX_CATALOG_AVAILABILITY',                       'Product Availability');  // availability
define('BOX_CATALOG_QUICK_INVENTORY',                    'Quick-Inventory');  // quick inventory 8672
define('BOX_CATALOG_GOOGLE_TAXONOMY',                    'Google Taxonomy Code');  // quick inventory 8672
// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');
// 2.3.4 define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_ADMIN_CREATE_ACCOUNT',                       'Create new customer');// BOF Admin Create Account 1.0
define('BOX_CUSTOMERS_CHANGE_PASSWORD',                  'Change Password');// Change Password
define('BOX_CUSTOMERS_CUSTOMER_GROUPS',                  'Customer Groups');// SPPC customers groups
define('BOX_CUSTOMERS_ADD_ORDERS',                       'Add New Order');// manual add order

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations / Taxes');
define('BOX_TAXES_COUNTRIES', 'Countries');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders-Total');
define('BOX_REPORTS_ORDERS_TRACKING',                     'Order Tracking'); // order tracking v30
define('BOX_REPORTS_STOCK_LEVEL',                         'Low Stock Report'); // low stock report
define('BOX_REPORTS_SUPERTRACKER',                        'Supertracker');  //supertracker 3.5
define('BOX_REPORTS_PRODUCTS_KEYWORDS',                   'Search Keywords');  // search product keywords
define('BOX_REPORTS_MARGIN_REPORT_PRODUCTS', 			  'Marge Report Products');  // bof product cost price
define('BOX_REPORTS_MARGIN_REPORT_ORDERS', 			      'Marge Report Orders');  // bof product cost price
define('BOX_REPORTS_MONTHLY_SALES',                       'Monthly Report'); // monthly report
define('BOX_REPORTS_STATS_LOW_STOCK_ATTRIB',              'Stock Report'); //++++ QT Pro: Begin Changed code

// tools text in includes/boxes/emails.php
define('BOX_HEADING_EMAILS',                              'Email Text');
define('BOX_EMAIL_TEXT_NEW_ORDER',                        'Text New Order Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEW_ORDER_ADMIN',                  'Text New Order Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEW_CUSTOMER',                     'Text New Customer Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEW_CUSTOMER_ADMIN',               'Text New Customer Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_UPD_ORDER_ADMIN',                  'Text Update Order Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_PASSWORD_FORGOTTEN',               'Text Password Forgotten Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_CUSTOMER_REVIEW',                  'Text New Review Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_CONTACT_US',                       'Text Contact Us Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_WISHLIST',                         'Text Wishlist Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_SUBSCRIBE',             'Text Newsletter Subscribe Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_UNSUBSCRIBE',           'Text Newsletter Unsubscribe Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_WISHLIST_ADMIN',                   'Text Wishlist Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_SUBSCRIBE_ADMIN',       'Text Newsletter Subscribe Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_UNSUBSCRIBE_ADMIN',     'Text Newsletter Unsubscribe Email Admin' );  // bof email text for different emails

// sitemonitor text in includes/boxes/sitemonitor.php
define('BOX_HEADING_SITEMONITOR', 'SiteMonitor');
define('BOX_SITEMONITOR_ADMIN', 'Admin');
define('BOX_SITEMONITOR_CONFIG_SETUP', 'Configure');


// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_TOOLS_ACTION_RECORDER', 'Action Recorder');
define('BOX_TOOLS_BACKUP', 'Database Backup');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_CACHE', 'Cache Control');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_TOOLS_SEC_DIR_PERMISSIONS', 'Security Directory Permissions');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_VERSION_CHECK', 'Version Checker');
define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');
define('BOX_TOOLS_GOOGLE_XMLMAPS',                    'Google XML SiteMaps' );  // bof google xml maps ultimate seu urls fwr 7704
define('BOX_TOOLS_DATABASE_OPTIMIZER',                'Database Optimizer');
define('BOX_TOOLS_BANNED_IP',                         'Banned IP Adress');
define('BOX_TOOLS_FILEMANAGER', 					  'Filemanager Webshop'); 
define('BOX_TOOLS_QTPRODOCTOR',                       'QTPro Doctor'); //++++ QT Pro: Begin Changed code
define('BOX_TOOLS_REMOVE_IMAGES',                     'Remove Unused Images'); // remove unused images 8157
define('BOX_TOOLS_FAQ',                               'FAQ Manager');//FAQDesk 2.3
define('BOX_TOOLS_POPUP_MANAGER',                     'Popup Manager'); // popup
define('BOX_TOOLS_SUBSCRIBER_MANAGER',                'Newsletter Users'); // newletter

define('BOX_REPORTS_GOOGLEMAP',                       'Google Map'); // google maps + direction
define('BOX_REPORTS_GOOGLE_FEEDS',                    'Google Feeds'); // google feeds voor shopping 

define('BOX_HEADING_INFORMATION',                     'Info manager');  // info manager
define('BOX_CATALOG_DEFINE_MAINPAGE',                 'Define Main Page');// define_mainpage MOBILE
define('BOX_CATALOG_DEFINE_PRIVACY',                  'Define Privacy Page');// define_mainpage
define('BOX_CATALOG_DEFINE_CONDITIONS',               'Define Condition Page');// define_mainpage
define('BOX_CATALOG_DEFINE_SHIPPING',                 'Define Shipping Page');// define_mainpage

define('BOX_CATALOG_DEFINE_MOBILE_MAINPAGE',          'Define Mobile Main Page');// define_mainpage MOBILE
define('BOX_CATALOG_DEFINE_MOBILE_PRIVACY',           'Define Mobile Privacy Page');// define_mainpage
define('BOX_CATALOG_DEFINE_MOBILE_CONDITIONS',        'Define Mobile Condition Page');// define_mainpage
define('BOX_CATALOG_DEFINE_MOBILE_SHIPPING',          'Define Mobile Shipping Page');// define_mainpage

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');

// localizaion box text in includes/boxes/stores.php
define('BOX_HEADING_STORES', 'Websites/Stores');
define('BOX_STORES_STORES', 'Stores');

// orders box text in includes/boxes/orders.php
define('BOX_HEADING_ORDERS', 'Orders');
define('BOX_ORDERS_ORDERS', 'Orders');

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' amd \'Confirmation\' entries must match amd have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');

define('CATEGORY_PERSONAL', 'Personal');
define('CATEGORY_ADDRESS', 'Address');
define('CATEGORY_CONTACT', 'Contact');
define('CATEGORY_COMPANY', 'Company');
define('CATEGORY_OPTIONS', 'Options');

define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The email address doesn\'t appear to be valid!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">This email address already exists!</span>');
define('ENTRY_COMPANY', 'Company name:');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</span>');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</span>');
define('ENTRY_STATE', 'State:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_SUSPENDED_YES', 'Suspended'); // suspend customer
define('ENTRY_SUSPENDED_NO', 'Active'); // suspend customer
define('ENTRY_ACCOUNT_STATUS', 'Account Status:'); // suspend customer
define('ENTRY_CUSTOMERS_DISCOUNT', 'Discount Percentage'); // total b2b
// BOF Separate Pricing Per Customer
define('ENTRY_CUSTOMERS_GROUP_NAME', 'Customer Group:');
define('ENTRY_COMPANY_TAX_ID', 'Company\'s tax id number:');
define('ENTRY_COMPANY_TAX_ID_ERROR', '');
define('ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION', 'Switch off alert for authentication:');
define('ENTRY_CUSTOMERS_GROUP_RA_NO', 'Alert off');
define('ENTRY_CUSTOMERS_GROUP_RA_YES', 'Alert on');
define('ENTRY_CUSTOMERS_GROUP_RA_ERROR', '');
define('HEADING_TITLE_MODULES_PAYMENT', 'Payment Modules');
define('HEADING_TITLE_MODULES_SHIPPING', 'Shipping Modules');
define('HEADING_TITLE_MODULES_ORDER_TOTAL', 'Order Total Modules');
// EOF Separate Pricing Per Customer

// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', 'Edit');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_EXPORT', 'Export');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MODULE_INSTALL', 'Install Module');
define('IMAGE_MODULE_REMOVE', 'Remove Module');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SELECT', 'Select');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');
define('IMAGE_EDIT_ORDER',              'Edit Order' ); // edit order 509
define('IMAGE_NEW_DISCOUNT_CODE',       'Add Discount Code');// discount codes 2.62
define('IMAGE_ORDERS_LABEL',            'Adress Label');// label pdf
define('IMAGE_CROSS_SELL',              'Cross Sell');// XSell
define('IMAGE_WITHOUT_XSELLS',          'With out Cross Sell');// XSell
define('IMAGE_WITH_XSELLS',             'With Cross Sell');// XSell
define('IMAGE_BUTTON_CONTINUE',         'Continue');// create account
define('IMAGE_SPEC_ACTIVE_ALL',         'Active Specials Selection');// specials enhanced
define('IMAGE_SPEC_DEACTIVE_ALL',       'Delete Specials Selection');// specials enhanced
define('IMAGE_SPEC_REMOVE_ALL',         'Delete All Specials');// specials enhanced
define('IMAGE_NEW_PAGE',                'Add New Information Page');// info manager 
define('IMAGE_CHANGE_PASSWORD',         'Wijzig Wachtwoord');// change password customer
define('IMAGE_EXCLUDE',                 'Exclude');  // sitemonitor
define('IMAGE_GOOGLE_DIRECTIONS',       'Google Delivery Directions'); // google maps + directions
define('IMAGE_EXPORT_MARGIN',           'Export To File'); // product price cost
define('IMAGE_SHOW_PRODUCTS',           'Show Products'); // BOF QPBPP for SPPC
define('IMAGE_QTSTOCK',                 'Stock'); //++++ QT Pro: Begin Changed code
define('IMAGE_ORDERS_INVOICE_PDF',      'Invoice PDF'); // BOF ADMIN pdf invoice 1.6
define('IMAGE_ORDERS_PACKINGSLIP_PDF',  'Packing Slip PDF'); // BOF ADMIN pdf invoice 1.6
define('IMAGE_REMOVE_CACHE',            'Remove Cache Categories'); // 
define('IMAGE_NEW_POPUP',               'Add New Popup Window'); //  popup
define('IMAGE_MULTI_STORES',            'Active Stores'); //  multi stores
define('IMAGE_SPPC_PROD_LIST',          'SPPC Product List');// BOF SPPC Group List
define('IMAGE_SPPC_GROUP_LIST',         'SPPC Group List');// BOF SPPC Group List
define('IMAGE_NEW_ORDER',               'New Order');// BOF SPPC Group List
define('IMAGE_NEW_LOCATION_MAP',        'New Location Map'); 
define('IMAGE_NEW_CUSTOMER_GROUP',      'New Customer Group'); 
define('IMAGE_NEW_SEO_PAGE',            'Check for New SEO Page'); 
define('IMAGE_NEW_SEO_PAGE_DEFAULT',    'Check for New Default SEO Pagina'); 
define('IMAGE_NEW_SEO_PSEUDO_PAGE',     'New Pseudo SEO Page'); 
define('IMAGE_NEW_ATTRIBUTE_NEW_OPTION','New Product Option'); 
define('IMAGE_NEW_ATTRIBUTE_NEW_VALUE', 'New Product Value');
define('IMAGE_OPTIONS_ATTRIBUTES',      'Options + Values'); 
define('IMAGE_ORDERS_ADDRESS',          'Address Order'); 
define('IMAGE_NEW_ORDER_EMAIL',         'New Order Email'); 
define('IMAGE_ORDERS_STATUS',           'Status Order'); 
define('IMAGE_ORDERS_PRODUCTS',         'Products Attributes Order'); 
define('IMAGE_NEW_GOOGLE_TAXECO_CODE',  'New Google Eco Code'); 
define('IMAGE_CLOSE',                   'Close') ;
define('IMAGE_INSTALL',                 'Istall') ;
define('IMAGE_UNINSTALL',               'REmove Installed') ;
define('IMAGE_DISABLE',                 'Disabled') ;
define('IMAGE_ENABLE',                  'Enabled') ;
define('IMAGE_NEW_SUBSCRIBER',          'New Subscriber ') ;
define('IMAGE_NEW_SPECIALS',            'New Special Price') ;
define('IMAGE_NEW_XSELL_PRODUCTS',      'New Cross Sell') ;
define('IMAGE_NEW_PRODUCT_AVAILABILITY','New Product Availability Status') ;
define('IMAGE_NEW_DISCOUNT',            'New Discount') ;
define('IMAGE_NEW_REVIEW',              'New Review') ;
define('IMAGE_ICON_DOWNLOAD',           'Download File' ) ;
define('IMAGE_NEW_BANNED_IP',           'New Banned IP Adress' ) ;
define('IMAGE_NEW_ATTRIBUTE_STOCK',     'New Stock Attributes' ) ;
define('IMAGE_EXPORT',                  'Export Data' ) ;
define('IMAGE_NEW_FQA',                 'New Question and Answer' ) ;
define('IMAGE_OPTIONS_STOCK',           'Product Stock Options' ) ;

define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

/*** Begin Header Tags SEO ***/
// header_tags_seo text in includes/boxes/header_tags_seo.php
define('BOX_HEADING_HEADER_TAGS_SEO',                  'Header Tags SEO');
define('BOX_HEADER_TAGS_ADD_A_PAGE',                   'SEO Page Control');
define('BOX_HEADER_TAGS_ADD_PAGE_DEFAULT', 			   'SEO Page Default');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_KEYWORDS', 					   'Keywords');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_FILL_TAGS',                    'Fill Tags');
define('BOX_HEADER_TAGS_SILO',                         'Silo Control');
define('BOX_HEADER_TAGS_TEST',                         'Test');
define('BOX_HEADER_TAGS_SOCIAL',                       'Social Bookmarks'); // 331
/*** End Header Tags SEO ***/

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_ENTRIES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> entries)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> zones)');
define('TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> discount codes)'); // Discount Code - start
define('TEXT_DISPLAY_NUMBER_OF_KEYWORDS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> keywords)');  // search keyword report
define('TEXT_DISPLAY_NUMBER_OF_DISCOUNT', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Discounts)');  // total b2b
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_AVAILABILITY', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Product Availabilities)');  // availability
define('TEXT_DISPLAY_NUMBER_OF_STORES','Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Stores)');  //  multi stores
define('TEXT_DISPLAY_NUMBER_OF_LOCATIONMAPS','Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Locations)');  //  google map locations
define('TEXT_DISPLAY_NUMBER_OF_SEO_PAGES', 'Displaying <b>%d</b> to <b>%d</b> ( of <b>%d</b> SEO Pages  )');  
define('TEXT_DISPLAY_NUMBER_OF_SEO_PAGES_DEFAULT', 'Displaying <b>%d</b> to <b>%d</b> ( of <b>%d</b> SEO Pages Default  )'); 
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_GOOGLE_TAXONOMY', 'Displaying <b>%d</b> to <b>%d</b> ( of <b>%d</b> Google Taxonomy Codes  )'); 
define('TEXT_DISPLAY_NUMBER_OF_SUBSCRIBERS', 'Display <b>%d</b> to <b>%d</b> (of <b>%d</b> subscribers)');
define('TEXT_DISPLAY_NUMBER_OF_CROSS_SELLS', 'Display <b>%d</b> to <b>%d</b> (of <b>%d</b> Cross Sells)');
define('TEXT_DISPLAY_NUMBER_OF_BANNED_IP', 'Display <b>%d</b> to <b>%d</b> (of <b>%d</b> Banned  Ip Adress(es))');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Set as default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');
define('TEXT_KEYWORDS','Keywords List Report'); // search keyword report

define('TEXT_CACHE_CATEGORIES', 'Categories Box');
define('TEXT_CACHE_CATEGORIES_ACCORDION', 'Categories Accordion Box');
define('TEXT_CACHE_MANUFACTURERS', 'Manufacturers Box');
define('TEXT_CACHE_ALSO_PURCHASED', 'Also Purchased Module');

define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');
define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully.');
define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');

/*** Begin EasyMap ***/
// easymap box text in includes/boxes/easymap.php
define('BOX_HEADING_EASYMAP', 'EasyMap');
define('BOX_EASYMAP', 'EasyMap');
/*** End EasyMap ***/
/**** BEGIN CRON SIMULATOR ****/
define('BOX_HEADING_CRON_SIMULATOR', 'Cron Simulator');
define('BOX_CRON_SIMULATOR_CONTROL', 'Cron Simulator');
// Down for Maintenance Admin reminder
define ('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'The site is currently set Down for maintenance to the public.  Remember to bring it up when you are done!');

// tag clouds
define('BOX_CATALOG_TAGS', 'Tags');
// Start theme admin switcher
  define( 'MODULE_THEME_ADMIN_SWITCHER_SEARCH_PRODUCTS', 'Search product' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_SEARCH_CUSTOMERS', 'Search customer' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_OK', 'ok' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_ADD_PRODUCT', 'Products' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_NEWSLETTER', 'Newsletter' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_WHOISONLINE', 'Who Is Online' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_CUSTOMERS',                'Customers' );  
  define( 'MODULE_THEME_ADMIN_SWITCHER_ORDERS', 'Orders' );  
  define( 'MODULE_THEME_ADMIN_SWITCHER_INSTALL_ERROR', 'Go to modules/admin for turn on the theme admin switcher' );
  define( 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_DIALOG_TITLE', 'Message' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_ADD_ORDER',                  'Add Order' );  
  define( 'MODULE_THEME_ADMIN_SWITCHER_ADD_CUSTOMER',               'Add Customer' );  
// End theme admin switcher
  define( 'BOX_CONFIGURATION_PDFINVOICE_PACKINGSLIP', 'PDF Invoice / PDF PackingSlip') ; // BOF ADMIN pdf invoice 1.6
  define('BOXES_ALL_PAGES', 'all pages.'); // box placement 7691
  define('BOXES_ANY_PAGES', 'any page.'); // box placement 7691
  define('BOXES_ONE_BY_ONE', 'or select one by one :'); // box placement 7691
  
  define('SHIPPING_METHODS_ALL',               'all shipping methods.'); // PAYMENT DEPENDING ON SHIPPING  
  define('SHIPPING_METHODS_ONE_BY_ONE',        'or select 1 off the shipping methods') ; // PAYMENT DEPENDING ON SHIPPING 
  
  define('SUCCESS_EMAIL_ORDER_TEXT', 'Success: Order confirmation email updated successfully.' ); // text order confirmation text
  define('ERROR_EMAIL_ORDER_TEXT', 'Error: Order confirmation email update failed.');  // text order confirmation text

  define('BOX_REPORTS_WISHLISTS', 'Wish Lists');  // Wish List

  define('TEXT_MULTI_STORE_NAME', 'Store : '  ) ; // MULTI STORE
// separate shipping box text in includes/boxes/separate_rate.php
define('BOX_HEADING_SEPARATE_SHIPPING','Separate Shipping');
define('BOX_SEPARATE_SHIPPING_RATE', 'Shipping Rates');   

define('TEXT_IMAGE_NONEXISTENT','NO IMAGE');
// bootstrap helper
define('MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION', '<p>Content Width can be 12 or less per column per row.</p><p>12/12 = 100% width, 6/12 = 50% width, 4/12 = 33% width.</p><p>Total of all columns in any one row must equal 12 (eg:  3 boxes of 4 columns each, 1 box of 12 columns and so on).</p>');
define('TEXT_BROWSE', 'Select a File' ) ;
define('TEXT_BROWSE_DONE', 'File selected' ) ;

// navbar menu icons
define('BOX_HEADING_CATALOG_ICON', '<i class="fa fa-folder-open"></i> ');
define('BOX_HEADING_CONFIGURATION_ICON', '<i class="fa fa-cog"></i> ');
define('BOX_HEADING_CUSTOMERS_ICON', '<i class="fa fa-user"></i> ');
define('BOX_HEADING_LOCALIZATION_ICON', '<i class="fa fa-globe"></i> ');
define('BOX_HEADING_LOCATION_AND_TAXES_ICON', '<i class="fa fa-briefcase"></i> ');
define('BOX_HEADING_MODULES_ICON', '<i class="fa fa-tasks"></i> ');
define('BOX_HEADING_ORDERS_ICON', '<i class="fa fa-inbox"></i> ');
define('BOX_HEADING_REPORTS_ICON', '<i class="fa fa-bar-chart"></i> ');
define('BOX_HEADING_TOOLS_ICON', '<i class="fa fa-wrench"></i> ');
define('BOX_HEADING_EMAIL_ICON', '<i class="fa fa-paper-plane"></i> ');
define('BOX_HEADING_HEADER_TAGS_ICON', '<i class="fa fa-sitemap"></i> ');
define('BOX_HEADING_INFORMATION_ICON', '<i class="fa fa-info"></i> ');

define('TEXT_MONTH_JANUARY',     'January') ;
define('TEXT_MONTH_FEBRUARY',    'Ferbuary') ;
define('TEXT_MONTH_MARCH',       'March') ;
define('TEXT_MONTH_APRIL',       'April') ;
define('TEXT_MONTH_MAY',         'May') ;
define('TEXT_MONTH_JUNE',        'June') ;
define('TEXT_MONTH_JULY',        'July') ;
define('TEXT_MONTH_AUGUST',      'August') ;
define('TEXT_MONTH_SEPTEMBER',   'September') ;
define('TEXT_MONTH_OKTOBER',     'Oktober') ;
define('TEXT_MONTH_NOVEMBER',    'November') ;
define('TEXT_MONTH_DECEMBER',    'December') ;
?>