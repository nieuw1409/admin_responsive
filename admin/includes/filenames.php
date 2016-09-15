<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// define the filenames used in the project
  define('FILENAME_ACTION_RECORDER', 'action_recorder.php');
  define('FILENAME_ADMINISTRATORS', 'administrators.php');
  define('FILENAME_BACKUP', 'backup.php');
  define('FILENAME_BANNER_MANAGER', 'banner_manager.php');
  define('FILENAME_BANNER_STATISTICS', 'banner_statistics.php');
  define('FILENAME_CACHE', 'cache.php');
  define('FILENAME_CATALOG_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_CATEGORIES', 'categories.php');
  define('FILENAME_CONFIGURATION', 'configuration.php');
  define('FILENAME_COUNTRIES', 'countries.php');
  define('FILENAME_CURRENCIES', 'currencies.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  define('FILENAME_DEFAULT', 'index.php');
  define('FILENAME_DEFINE_LANGUAGE', 'define_language.php');
  define('FILENAME_GEO_ZONES', 'geo_zones.php');
  define('FILENAME_LANGUAGES', 'languages.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_MAIL', 'mail.php');
  define('FILENAME_MANUFACTURERS', 'manufacturers.php');
  define('FILENAME_MODULES', 'modules.php');
  define('FILENAME_NEWSLETTERS', 'newsletters.php');
  define('FILENAME_ORDERS', 'orders.php');
  define('FILENAME_ORDERS_INVOICE', 'invoice.php');
  define('FILENAME_ORDERS_PACKINGSLIP', 'packingslip.php');
  define('FILENAME_ORDERS_STATUS', 'orders_status.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRODUCTS_ATTRIBUTES', 'products_attributes.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SEC_DIR_PERMISSIONS', 'sec_dir_permissions.php');
  define('FILENAME_SERVER_INFO', 'server_info.php');
  define('FILENAME_SHIPPING_MODULES', 'shipping_modules.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_STATS_PRODUCTS_VIEWED', 'stats_products_viewed.php');
  define('FILENAME_STORE_LOGO', 'store_logo.php');
  define('FILENAME_TAX_CLASSES', 'tax_classes.php');
  define('FILENAME_TAX_RATES', 'tax_rates.php');
  define('FILENAME_VERSION_CHECK', 'version_check.php');
  define('FILENAME_WHOS_ONLINE', 'whos_online.php');
  define('FILENAME_ZONES', 'zones.php');
  define('FILENAME_HEADER_TAGS_SEO',                           'header_tags_seo.php');  /*** Begin Header Tags SEO ***/
  define('FILENAME_HEADER_TAGS_FILL_TAGS',                     'header_tags_fill_tags.php'); /*** Begin Header Tags SEO ***/
  define('FILENAME_HEADER_TAGS_SILO',                          'header_tags_seo_silo.php'); /*** Begin Header Tags SEO ***/
  define('FILENAME_HEADER_TAGS_TEST',                          'header_tags_test.php');  /*** Begin Header Tags SEO ***/
  define('FILENAME_HEADER_TAGS_KEYWORDS',                      'header_tags_seo_keywords.php');  /*** Begin Header Tags SEO ***/
  define('FILENAME_HEADER_TAGS_SOCIAL',                        'header_tags_seo_social.php');   /*** Begin Header Tags SEO 331 ***/
  define('FILENAME_HEADER_TAGS_SEO_PAGES',                     'header_tags_seo_pages.php');   /*** Begin Header Tags SEO 331 ***/  
  define('FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT',             'header_tags_seo_pages_default.php');   /*** Begin Header Tags SEO 331 ***/    
  define('FILENAME_DISCOUNT_CODES',                            'discount_codes.php'); // Discount Code
  define('FILENAME_LOCATION_MAP',                              'location_map.php'); // easy maps
  define('FILENAME_GOOGLE_XMLMAPS',                            'usu5_sitemaps/index.php' ); // bof google xml maps ultimate seu urls fwr 7704
  define('FILENAME_CRON_SIMULATOR',                            'cron_simulator.php'); /**** BEGIN CRON SIMULATOR ****/  
  define('FILENAME_FAQ',                                       'faq.php');  //FAQDesk 2.3
  define('FILENAME_TAGS',                                      'tags.php'); // tags cloud
  define('FILENAME_STATS_ORDERS_TRACKING',                     'stats_orders_tracking.php'); // order tracking v30
  define('FILENAME_DATABASE_OPTIMIZER',                        'database_optimizer.php'); // database optimizer  
  define('FILENAME_GOOGLE_SITEMAP',                            'googlesitemap.php');// Google SiteMap BOF
  define('FILENAME_ORDERS_EDIT',                               'edit_orders.php'); // order editor
  define('FILENAME_ORDERS_EDIT_ADDRESS_BOOK',                  'edit_orders_address_book.php');  
  define('FILENAME_ORDERS_EDIT_STATUS_HISTORY',                'edit_orders_status_history.php');   
  define('FILENAME_ORDERS_EDIT_ADD_PRODUCT',                   'edit_orders_add_product.php');
  define('FILENAME_ORDERS_EDIT_AJAX',                          'edit_orders_ajax.php');  // end order editor  
  define('FILENAME_CREATE_ORDER_PROCESS',                      'create_order_process.php'); // manual order maker
  define('FILENAME_CREATE_ORDER',                              'create_order.php'); // manual order maker
  define('FILENAME_CREATE_ORDER_DETAILS',                      'create_order_details.php'); // manual order maker  
  define('FILENAME_STATS_LOW_STOCK',                           'stats_low_stock.php');   // low stock report
  define('FILENAME_PDF_INVOICE',                               'pdf_invoice.php'); // BOF ADMIN pdf invoice 1.6
  define('FILENAME_PDF_PACKINGSLIP',                           'pdf_packingslip.php');  // BOF ADMIN pdf invoice 1.6
  define('FILENAME_ORDERS_LABEL',                              'pdf_label.php');  // BOF ADMIN pdf invoice 1.6  
  define('FILENAME_XSELL_PRODUCTS',                            'xsell.php');  // XSell
  define('FILENAME_PRODUCTS_SORTER',                           'products_sorter.php');  // BOF Product Sort
  define('FILENAME_CREATE_ACCOUNT',                            'create_account.php'); // BOF Admin Create Account 1.0// Create customers
  define('FILENAME_CREATE_ACCOUNT_PROCESS',                    'create_account_process.php'); // BOF Admin Create Account 1.0// Create customers
  define('FILENAME_CREATE_ACCOUNT_SUCCESS',                    'create_account_success.php');  // BOF Admin Create Account 1.0// Create customers
  define('FILENAME_EMAIL_ORDER_TEXT',                          'email_order_text.php');  // BOF email texts  
  define('FILENAME_ADMIN_EMAIL_ORDER_TEXT',                    'email_admin_order_text.php');  // BOF email texts    
  define('FILENAME_EMAIL_CREATE_CUST_TEXT',                    'email_create_customer_text.php');  // BOF email texts    
  define('FILENAME_ADMIN_EMAIL_CREATE_CUST_TEXT',              'email_admin_create_customer_text.php');  // BOF email texts    
  define('FILENAME_ADMIN_EMAIL_UPD_ORDER_TEXT',                'email_admin_update_order_text.php');  // BOF email texts  
  define('FILENAME_EMAIL_PASSWORD_FORGOTTEN_TEXT',             'email_password_forgotten_text.php');  // BOF email texts    
  define('FILENAME_EMAIL_CUSTOMER_REVIEW_TEXT',                'email_customer_review_text.php');  // BOF email texts     
  define('FILENAME_EMAIL_WISHLIST_TEXT',                       'email_wishlist_text.php');  // BOF email texts   
  define('FILENAME_EMAIL_NEWSLETTER_SUBSCRIBE_TEXT',           'email_newsletter_subscribe_text.php');  // BOF email texts   
  define('FILENAME_EMAIL_NEWSLETTER_UNSUBSCRIBE_TEXT',         'email_newsletter_unsubscribe_text.php');  // BOF email texts     
  define('FILENAME_EMAIL_ADMIN_WISHLIST_TEXT',                 'email_admin_wishlist_text.php');  // BOF email texts   
  define('FILENAME_EMAIL_ADMIN_NEWSLETTER_SUBSCRIBE_TEXT',     'email_admin_newsletter_subscribe_text.php');  // BOF email texts   
  define('FILENAME_EMAIL_ADMIN_NEWSLETTER_UNSUBSCRIBE_TEXT',   'email_admin_newsletter_unsubscribe_text.php');  // BOF email texts     
  define('FILENAME_PRODUCTS_MULTI',                            'products_multi.php');    // bof products multi copy etc
  define('FILENAME_QUICK_UPDATES',                             'quick_updates.php');    // quick price update 
  define('FILENAME_SUPERTRACKER',                              'supertracker.php');   // super tracker 3.5
  define('FILENAME_STATS_PRODUCTS_KEYWORDS',                   'stats_keywords.php');   // report search keywords
  define('FILENAME_EASY_POPULATE',                             'easypopulate.php');   // report search keywords  
  define('FILENAME_INFORMATION_MANAGER',                       'information_manager.php');  // BOF: Information Pages Unlimited
  define('FILENAME_BANNED_IP_ADDRESS',                         '_banned.php');  // BOF: Information Pages       Unlimited 
  define('FILENAME_CHANGE_PASSWORD',                           'change_password.php');  //       Change Password
  define('FILENAME_SITEMONITOR_ADMIN',                         'sitemonitor_admin.php');  // sitemonitor
  define('FILENAME_SITEMONITOR_CONFIG_SETUP',                  'sitemonitor_configure_setup.php'); // sitemonitor
  define('FILENAME_SITEMONITOR_CONFIGURE',                     'sitemonitor_configure.php');  // sitemonitor
  define('FILENAME_REPORTS_GOOGLEMAP',                         'report_googlemap.php');  // google maps + directions
  define('FILENAME_GOOGLE_MAP',                                'google_map.php');  // google maps + directions
  define('FILENAME_MARGIN_REPORT_PRODUCTS',                    'stats_margin_report_products.php'); // bof product cost price
  define('FILENAME_MARGIN_REPORT_ORDERS',                      'stats_margin_report_orders.php');   // bof product cost price
  define('FILENAME_DEFINE_MAINPAGE',                           'define_mainpage.php');  // define_mainpage
  define('FILENAME_DEFINE_PRIVACY',                            'define_privacy.php');  // define_mainpage 
  define('FILENAME_DEFINE_CONDITIONS',                         'define_conditions.php');  // define_mainpage 
  define('FILENAME_DEFINE_SHIPPING',                           'define_shipping.php');  // define_mainpage 
  define('FILENAME_MOBILE_DEFINE_MAINPAGE',                    'define_mobile_mainpage.php');  // define_mainpage MOBILE
  define('FILENAME_MOBILE_DEFINE_PRIVACY',                     'define_mobile_privacy.php');  // define_mainpage MOBILE
  define('FILENAME_MOBILE_DEFINE_CONDITIONS',                  'define_mobile_conditions.php');  // define_mainpage MOBILE
  define('FILENAME_MOBILE_DEFINE_SHIPPING',                    'define_mobile_shipping.php');  // define_mainpage MOBILE 
  define('FILENAME_CUSTOMERS_GROUPS',                          'customers_groups.php'); // BOF Separate Pricing Per Customer
  define('FILENAME_ATTRIBUTES_GROUPS',                         'attributes_groups.php'); // BOF Separate Pricing Per Customer
  define('FILENAME_DISCOUNT_CATEGORIES',                       'discount_categories.php'); // BOF QPBPP for SPPC
  define('FILENAME_DISCOUNT_CATEGORIES_GROUPS_PP',             'discount_categories_groups_pp.php'); // BOF QPBPP for SPPC
  define('FILENAME_SPECIALS_BY_CATEGORIES',                    'specialsbycategory.php'); // BOF SPPC   716
  define('FILENAME_MANUDISCOUNT',                              'manudiscount.php'); //totalB2B start
  define('FILENAME_CATEMANUDISCOUNT',                          'catemanudiscount.php'); //totalB2B start
  define('FILENAME_CATEDISCOUNT',                              'catediscount.php');  //totalB2B start
  define('FILENAME_STATS_MONTHLY_SALES',                       'stats_monthly_sales.php');  // monthly reports
  define('FILENAME_PRODUCTS_AVAILABILITY',                     'products_availability.php'); // availability options  
  define('FILENAME_CONFIGURATION_CACHE',                       'cache/cachefile.inc.php') ;  //cache configuration data  multi stores
  define('FILENAME_CONFIGURATION_MULTI_STORES',                '/includes/multi_stores.inc.php') ;  //cache configuration data  multi stores  
  define('FILENAME_STATS_LOW_STOCK_ATTRIB',                    'stats_low_stock_attrib.php'); //++++ QT Pro: Begin Changed code
  define('FILENAME_STOCK',                                     'stock.php'); //++++ QT Pro: Begin Changed code
  define('FILENAME_QTPRODOCTOR',                               'qtprodoctor.php');  //++++ QT Pro: Begin Changed code
  define('FILENAME_QUICK_INVENTORY',                           'quick_inventory.php');  // quick inventory 8672
  define('FILENAME_REMOVE_IMAGES',                             'remove_unused_images.php');  // remove unused images 8157
  define('FILENAME_DASH_WHOS_ONLINE',                          'index.php');  // whos online dashboard module 8717
  define('FILENAME_REMOVE_IMAGES',                             'remove_unused_images.php');  // remove unused images 8157  
  define('FILENAME_STATS_WISHLISTS',                           'stats_wishlists.php');     // Wish List
  define('FILENAME_POPUP_MANAGER',                             'popup_manager.php');  // popup window
  define('FILENAME_EMAIL_CONTACT_US',                          'email_contact_us.php');  // BOF email texts    
  define('FILENAME_STORES',                                    'stores.php' ) ;          // multi stores  
  define('FILENAME_SPPC_GROUP_LIST',                           'sppc_group_list.php'); // BOF SPPC Group List
  define('FILENAME_SPPC_GROUP_LIST_GROUP',                     'sppc_group_list_group.php');  // BOF SPPC Group List
  define('FILENAME_ORDERS',                                    'orders.php');
  define('FILENAME_ORDERS_HANDLER',                            'order_handler.php');
  define('FILENAME_GOOGLE_FEEDERS',                            'feeders.php');  
  define('FILENAME_GOOGLE_TAXONOMY',                           'products_google_taxonomy.php');   
// BOF Visitor's Newsletter Contribution by brouillard s'embrouille
  define('FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER',             'newsletter_subscriber_manager.php');
  define('FILENAME_NEWSLETTER_UNSUBSCRIBE',                    'newsletter_unsubscribe.php');
  define('FILENAME_NEWSLETTER_VIEW',                           'newsletter_view.php'); // EOF Visitor's Newsletter Contribution by brouillard s'embrouille  
  define('FILENAME_MODULE_STATS_ORDERS_TRACK_SALES_SUM',       'stats_orders_tracking_sales_summary.php');  
  define('FILENAME_MODULE_STATS_ORDERS_TRACK_COUNTRY',         'stats_orders_tracking_sales_country.php');   
  define('FILENAME_MODULE_STATS_ORDERS_TRACK_ZONE',            'stats_orders_tracking_sales_zone.php');   
  define('FILENAME_MODULE_STATS_ORDERS_TRACK_POSTCODE',        'stats_orders_tracking_sales_postcode.php');     
?>