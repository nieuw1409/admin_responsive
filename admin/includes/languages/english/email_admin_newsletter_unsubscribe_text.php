<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Newsletter UNSubscribe Email text Admin' );
define('INFO', 'You can enter your confirmation email here, it can be in plain text or HTML format depending on the configuration of your shop. To use HTML you must, under "Configuration/E-Mail Options" set "use MIME HTML" to "true".  You can check this setting <a href="configuration.php?gID=');
define('INFO2', '">>>Here<<</a>.');

define('VAR_LIST_POPUP', 'Variable List' ); 
define('TEXT_EMAIL_ORDER_TEXT', 'Newsletter UNSubscribe Email text Admin' ) ;
define('TEXT_EMAIL_SUBJECT',            'Text Email Subject' ) ;

define('POPUP_LIST_EXPLAIN', 				'The Following Variables can be Used in thePasword Forgotten Email Text');

define('POPUP_STORENAME', 					'The Name of the Store ( see Configuration "my store" )') ;
define('POPUP_CUSTOMER_FIRST',			'First Name Customer') ;
define('POPUP_CUSTOMER_LAST', 			'Last Name Customer') ;
define('POPUP_EMAILADDRESS', 			'Email Address Customer') ;
define('POPUP_PASSWORD', 				'New Password ') ;
define('POPUP_SECURE_LINK',             'Secure URL Link Customer'); // 232
define('POPUP_SUBSCRIBER_CUSTOMERS_ID',               'Newsletter Subscriber / Customer ID'); // 232
define('POPUP_SUBSCRIBER_CUSTOMERS_NAME',             'Newsletter Subscriber / Customer Name'); // 232
define('POPUP_SUBSCRIBER_CUSTOMERS_EMAIL_ADDRESS',    'Newsletter Subscriber / Customer Email Address'); // 232
define('POPUP_SUBSCRIBER_CUSTOMERS_CUSTOMER_GROUP',   'Newsletter Subscriber / Customer Customer Group'); // 232
define('POPUP_SUBSCRIBER_CUSTOMERS_STORE',            'Newsletter Subscriber / Customer Store Active'); // 232
define('POPUP_SUBSCRIBER_CUSTOMERS_DATE_CREATION',    'Newsletter Subscriber / Customer Subscription Date'); // 232
define('POPUP_SUBSCRIBER_CUSTOMERS_TYPE',             'Newsletter Subscriber or Customer '); // 232
?>