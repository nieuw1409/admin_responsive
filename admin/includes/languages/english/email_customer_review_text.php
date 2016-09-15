<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'New Review Email text');
define('INFO', 'You can enter your confirmation email here, it can be in plain text or HTML format depending on the configuration of your shop. To use HTML you must, under "Configuration/E-Mail Options" set "use MIME HTML" to "true".  You can check this setting <a href="configuration.php?gID=');
define('INFO2', '">>>Here<<</a>.');

define('VAR_LIST_POPUP', 'Variable List' ); 
define('TEXT_EMAIL_ORDER_TEXT', 'New Review Email Text' ) ;
define('TEXT_EMAIL_SUBJECT',            'Text Email Subject' ) ;

define('POPUP_LIST_EXPLAIN', 				'The Following Variables can be Used in the New Review Email Text');

define('POPUP_CA_STORENAME', 		'Name of the Webshop') ;
define('POPUP_PRODUCT_ID',			'Product Number') ;
define('POPUP_PRODUCT_MODEL', 		'Product Model') ;
define('POPUP_PRODUCT_NAME', 		'Product Name') ;
define('POPUP_CUSTOMER_ID', 		'Customer Number') ;
define('POPUP_CUSTOMER_NAME', 		'Customer Naam') ;
define('POPUP_REVIEW_ID', 			'Number Review') ;
define('POPUP_REVIEW_RATING', 		'Review scale 1->5') ;
define('POPUP_REVIEW_LANG_ID', 		'Language Review') ;
define('POPUP_REVIEW_TEXT', 		'Review Text') ;
?>
