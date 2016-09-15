<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Contact Us: Email text');
define('INFO', 'You can enter your Contact Us email here, it can be in plain text or HTML format depending on the configuration of your shop. To use HTML you must, under "Configuration/E-Mail Options" set "use MIME HTML" to "true".  You can check this setting <a href="configuration.php?gID=');
define('INFO2', '">>>Here<<</a>.');

define('VAR_LIST_POPUP', 'Variable List' ); 
define('TEXT_EMAIL_ORDER_TEXT', 'Contact Us Email Text' ) ;
define('TEXT_EMAIL_SUBJECT',    'Contact Us Email Subject' ) ;

define('POPUP_LIST_EXPLAIN', 				'The Following Variables can be Used in the Contact Us Email Text');

define('POPUP_STORENAME', 			 'The Name of the Store') ;
define('POPUP_NAME',				 'The Name of the Customer') ;
define('POPUP_EMAIL_ADDRESS', 	     'The Email Address of the Customer') ;
define('POPUP_TEXT', 		         'The Text of the Email Contact Us') ;
define('POPUP_ITEMLIST', 			 'List of Products/Items') ;
define('POPUP_TOTALLIST', 			 'Order Total') ;
define('POPUP_BILLADRESS', 			 'Billing Address') ;
define('POPUP_DELIVERYADRESS', 		 'Delivery Address') ;
define('POPUP_CUSTOMERCOMMENTS', 	 'Customer Comments') ;
define('POPUP_PAYMENTMODULTEXT', 	 'Text of Payment') ;
define('POPUP_PAYMENTMODULFOOTER', 	 'Footer Text of Payment') ;
?>
