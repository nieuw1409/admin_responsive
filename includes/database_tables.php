<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// define the database table names used in the project
  define('TABLE_ACTION_RECORDER', 'action_recorder');
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_ADMINISTRATORS', 'administrators');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTER', 'counter');
  define('TABLE_COUNTER_HISTORY', 'counter_history');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_IMAGES', 'products_images');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');  
  define('TABLE_HEADERTAGS', 								'headertags');/*** Begin Header Tags SEO ***/
  define('TABLE_HEADERTAGS_CACHE', 							'headertags_cache');/*** Begin Header Tags SEO ***/
  define('TABLE_HEADERTAGS_DEFAULT', 						'headertags_default');/*** Begin Header Tags SEO ***/
  define('TABLE_HEADERTAGS_SEARCH',                         'headertags_search'); /*** Begin Header Tags SEO 327 ***/
  define('TABLE_HEADERTAGS_SILO', 							'headertags_silo');/*** Begin Header Tags SEO ***/  
  define('TABLE_HEADERTAGS_SOCIAL',                         'headertags_social');  /*** Begin Header Tags SEO 331 ***/  
  define('TABLE_HEADERTAGS_KEYWORDS',                       'headertags_keywords');  /*** Begin Header Tags SEO 327 ***/  
  define('TABLE_CUSTOMERS_TO_DISCOUNT_CODES', 				'customers_to_discount_codes');  // Discount Code - start
  define('TABLE_DISCOUNT_CODES', 							'discount_codes');    // Discount Code - start  
  define('TABLE_LOCATIONMAP',                               'location_map');// bof location maps  
  define('TABLE_LOCATIONMAP_INFO',                          'location_map_info');// bof location maps  
  define('TABLE_FAQ', 										'faq'); //FAQDesk 2.3
  define('TABLE_FAQ_DESCRIPTION', 							'faq_description');	  //FAQDesk 2.3
  define('TABLE_PRODUCTS_PRICE_BREAK', 						'products_price_break');// BOF qpbpp
  define('TABLE_DISCOUNT_CATEGORIES', 						'discount_categories');// BOF qpbpp
  define('TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES', 			'products_to_discount_categories');   // BOF qpbpp
  define('TABLE_PRODUCTS_XSELL', 							'products_xsell');// XSell  
  define('TABLE_EMAIL_ORDER_TEXT', 							'eorder_text');// EMAIL TEXT  
  define('TABLE_SEARCH_QUERIES', 							'search_queries');     // keyword search
  define('TABLE_SEARCH_CUSTOMERS', 							'customers_searches');     // keyword search  
  define('TABLE_INFORMATION', 								'information');  // BOF: Information Pages Unlimited
  define('TABLE_PRODUCTS_OPTIONS_TYPES', 			        'products_options_types');  //BOF Option Types v2.3.1 - ONE LINE - Option Types
  define('TABLE_FILES_UPLOADED',                            'files_uploaded' ) ;   //BOF Option Types v2.3.1 - ONE LINE - Option Types
  define('TABLE_PRODUCTS_GROUPS',                           'products_groups');  // BOF Separate Pricing per Customer
  define('TABLE_SPECIALS_RETAIL_PRICES',                    'specials_retail_prices');  // BOF Separate Pricing per Customer
  define('TABLE_PRODUCTS_GROUP_PRICES',                     'products_group_prices_cg_');  // BOF Separate Pricing per Customer
  define('TABLE_CUSTOMERS_GROUPS',                          'customers_groups');  // BOF Separate Pricing per Customer
  define('TABLE_PRODUCTS_ATTRIBUTES_GROUPS',                'products_attributes_groups'); // BOF Separate Pricing per Customer
// this will define the maximum time in minutes between updates of a products_group_prices_cg_# table
// changes in table specials will trigger an immediate update if a query needs this particular table
  define('MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE',            '10');
// EOF Separate Pricing per Customer
  define('TABLE_MANUDISCOUNT',                              'manudiscount'); //TotalB2B start
  define('TABLE_CATEMANUDISCOUNT',                          'catemanudiscount'); //TotalB2B start
  define('TABLE_CATEDISCOUNT',                              'catediscount');  //TotalB2B start
  define('TABLE_PRODUCTS_AVAILABILITY',                     'products_availability');  //availability   
  define('TABLE_PRODUCTS_STOCK',                            'products_stock');  //++++ QT Pro: Begin Changed code
  define('TABLE_WISHLIST',                                  'customers_wishlist');  // wishlist
  define('TABLE_WISHLIST_ATTRIBUTES',                       'customers_wishlist_attributes');  // wishlist  
  define('TABLE_POPUPS',                                    'popups');   // popup window
  define('TABLE_POPUPS_DESCRIPTION',                        'popups_description');   // popup window    
  define('TABLE_STORES',                                    'stores') ; // multi stores
  define('TABLE_CONFIGURATION_STD',                         'configuration_std');   // multi stores  
  define('TABLE_PRODUCTS_VIEWED',                           'products_viewed');   // multi stores   
  define('TABLE_PRODUCTS_GOOGLE_TAXONOMY',                  'products_google_taxonomy');   // multi stores   
  define('TABLE_NEWSLETTER_SUBSCRIPTION',                   'newsletter_subscription');
  define('TABLE_NEWSLETTERS',                               'newsletters');  
?>