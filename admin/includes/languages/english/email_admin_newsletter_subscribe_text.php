<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Newsletter Subscribe Email text Admin' );
define('INFO', 'You can enter your confirmation email here, it can be in plain text or HTML format depending on the configuration of your shop. To use HTML you must, under "Configuration/E-Mail Options" set "use MIME HTML" to "true".  You can check this setting <a href="configuration.php?gID=');
define('INFO2', '">>>Here<<</a>.');

define('VAR_LIST_POPUP',                         'Variable List' ); 
define('TEXT_EMAIL_ORDER_TEXT',                  'Newsletter Subscribe Email text Admin' ) ;
define('TEXT_EMAIL_SUBJECT',                     'Text Email Subject' ) ;

define('POPUP_LIST_EXPLAIN', 				     'The Following Variables can be Used in thePasword Forgotten Email Text');

define('POPUP_STORENAME', 					     'The Name of the Store ( see Configuration "my store" )') ;
define('POPUP_CUSTOMER_FIRST',			         'First Name Customer') ;
define('POPUP_CUSTOMER_LAST', 			         'Last Name Customer') ;
define('POPUP_EMAILADDRESS', 			         'Email Address Customer') ;

define('POPUP_SECURE_LINK',                      'Secure URL Link Customer'); // 232

define('POPUP_SUBSCRIBER_EMAIL_ADRESS',          'Newsletter Subscriber Email address') ; 
define('POPUP_SUBSCRIBER_NAME',                  'Newsletter Subscriber Name') ; 
define('POPUP_SUBSCRIBER_ID',                    'Newsletter Subscriber ID') ; 
define('POPUP_SUBSCRIPTION_CUSTOMERS_GROUP_ID',  'Newsletter Subscriber Customer Group') ;  
define('POPUP_SUBSCRIPTION_STORES_ID',           'Newsletter Subscriber Store') ;  
define('POPUP_SUBSCRIPTION_DATE_CREATION',       'Newsletter Subscriber Date/Time Subscribe') ;
?>