<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// define the filenames used in the project
  define('FILENAME_ACCOUNT', 'account.php');
  define('FILENAME_ACCOUNT_EDIT', 'account_edit.php');
  define('FILENAME_ACCOUNT_HISTORY', 'account_history.php');
  define('FILENAME_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_ACCOUNT_NEWSLETTERS', 'account_newsletters.php');
  define('FILENAME_ACCOUNT_NOTIFICATIONS', 'account_notifications.php');
  define('FILENAME_ACCOUNT_PASSWORD', 'account_password.php');
  define('FILENAME_ADDRESS_BOOK', 'address_book.php');
  define('FILENAME_ADDRESS_BOOK_PROCESS', 'address_book_process.php');
  define('FILENAME_ADVANCED_SEARCH', 'advanced_search.php');
  define('FILENAME_ADVANCED_SEARCH_RESULT', 'advanced_search_result.php');
  define('FILENAME_ALSO_PURCHASED_PRODUCTS', 'also_purchased_products.php');
  define('FILENAME_CHECKOUT_CONFIRMATION', 'checkout_confirmation.php');
  define('FILENAME_CHECKOUT_PAYMENT', 'checkout_payment.php');
  define('FILENAME_CHECKOUT_PAYMENT_ADDRESS', 'checkout_payment_address.php');
  define('FILENAME_CHECKOUT_PROCESS', 'checkout_process.php');
  define('FILENAME_CHECKOUT_SHIPPING', 'checkout_shipping.php');
  define('FILENAME_CHECKOUT_SHIPPING_ADDRESS', 'checkout_shipping_address.php');
  define('FILENAME_CHECKOUT_SUCCESS', 'checkout_success.php');
  define('FILENAME_CONTACT_US', 'contact_us.php');
  define('FILENAME_CONDITIONS', 'conditions.php');
  define('FILENAME_COOKIE_USAGE', 'cookie_usage.php');
  define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
  define('FILENAME_DEFAULT', 'index.php');
  define('FILENAME_DOWNLOAD', 'download.php');
  define('FILENAME_INFO_SHOPPING_CART', 'info_shopping_cart.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_NEW_PRODUCTS', 'new_products.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'password_forgotten.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_POPUP_SEARCH_HELP', 'popup_search_help.php');
  define('FILENAME_PRIVACY', 'privacy.php');
  define('FILENAME_PRODUCT_INFO', 'product_info.php');
  define('FILENAME_PRODUCT_LISTING', 'product_listing.php');
  define('FILENAME_PRODUCT_REVIEWS', 'product_reviews.php');
  define('FILENAME_PRODUCT_REVIEWS_INFO', 'product_reviews_info.php');
  define('FILENAME_PRODUCT_REVIEWS_WRITE', 'product_reviews_write.php');
  define('FILENAME_PRODUCTS_NEW', 'products_new.php');
  define('FILENAME_REDIRECT', 'redirect.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SHIPPING', 'shipping.php');
  define('FILENAME_SHOPPING_CART', 'shopping_cart.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_SSL_CHECK', 'ssl_check.php');
  define('FILENAME_TELL_A_FRIEND', 'tell_a_friend.php');
  define('FILENAME_UPCOMING_PRODUCTS', 'upcoming_products.php');
  define('FILENAME_EASYMAP',                         'locate-us.php'); // bof easy maps
  define('FILENAME_FAQ',                             'faq.php');  //FAQDesk 2.3
  define('FILENAME_TAG_PRODUCTS',                    'tag_products.php'); // tag clouds
  define('FILENAME_GOOGLE_XMLMAPS',                  'googlesitemap.php' ); // bof google xml maps ultimate seu urls fwr 7704  
  define('FILENAME_XSELL_PRODUCTS',                  'xsell_products.php');// XSell
  define('FILENAME_BROWSE_CATEGORIES',               'browse_categories.php');//Browse by Categories.  
  define('FILENAME_CUSTOMER_PDF',                    'pdfinvoice.php');  // added customer pdf invoice 1.3
  define('FILENAME_INFORMATION',                     'information.php');  // BOF: Information Pages Unlimited
  define('FILENAME_COMPARE',                         'compare.php');  // compare products
  define('FILENAME_DEFINE_MAINPAGE',                 'define_mainpage.php');  // define_mainpage
  define('FILENAME_DEFINE_PRIVACY',                  'define_privacy.php');  // define_mainpage 
  define('FILENAME_DEFINE_CONDITIONS',               'define_conditions.php');  // define_mainpage 
  define('FILENAME_DEFINE_SHIPPING',                 'define_shipping.php');  // define_mainpage 
  define('FILENAME_MOBILE_DEFINE_MAINPAGE',          'define_mobile_mainpage.php');  // define_mainpage MOBILE
  define('FILENAME_MOBILE_DEFINE_PRIVACY',           'define_mobile_privacy.php');  // define_mainpage MOBILE
  define('FILENAME_MOBILE_DEFINE_CONDITIONS',        'define_mobile_conditions.php');  // define_mainpage MOBILE
  define('FILENAME_MOBILE_DEFINE_SHIPPING',          'define_mobile_shipping.php');  // define_mainpage MOBILE 
  define('FILENAME_CONFIGURATION_CACHE',             'cache/cachefile.inc.php') ;  // cache configuration file
  define('FILENAME_PASSWORD_RESET',                  'password_reset.php');  // update 232
  define('FILENAME_WISHLIST',                        'wishlist.php');  // wishlist
  define('FILENAME_WISHLIST_HELP',                   'wishlist_help.php');  // wishlist
  define('FILENAME_WISHLIST_PUBLIC',                 'wishlist_public.php');  // wishlist
  define('FILENAME_CONFIGURATION_MULTI_STORES',      'includes/multi_stores.inc.php') ;  //cache configuration data  multi stores   
  define('FILENAME_HT_SOCIAL_BOOKMARKS',             'header_tags_social_bookmarks.php');  // header tags
  define('FILENAME_QT_STOCK_TABLE',                  'qtpro_stock_table.php');  // header tags
  define('FILENAME_MATC',                            'matc.php');  // must agree terms  
  define('FILENAME_OPTION_TYPES',                    'option_types.php');  // must agree terms  
  define('FILENAME_NEWSLETTER_SUBSCRIPTION',         'newsletter_subscription.php'); // Visitor's Newsletter Contribution by brouillard s'embrouille
  define('FILENAME_NEWSLETTER_SUBSCRIPTION_SUCCESS', 'newsletter_subscription_success.php'); // Visitor's Newsletter Contribution by brouillard s'embrouille
  define('FILENAME_NEWSLETTER_UNSUBSCRIBE',          'newsletter_unsubscribe.php'); // Visitor's Newsletter Contribution by brouillard s'embrouille
  define('FILENAME_NEWSLETTER_UNSUBSCRIBE_SUCCESS',  'newsletter_unsubscribe_success.php'); // Visitor's Newsletter Contribution by brouillard s'embrouille
  define('FILENAME_NEWSLETTER_VIEW',                 'newsletter_view.php');  // Visitor's Newsletter Contribution by brouillard s'embrouille
?>