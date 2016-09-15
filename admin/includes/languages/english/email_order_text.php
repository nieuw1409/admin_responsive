<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Order confirmation: Email text');
define('INFO', 'You can enter your confirmation email here, it can be in plain text or HTML format depending on the configuration of your shop. To use HTML you must, under "Configuration/E-Mail Options" set "use MIME HTML" to "true".  You can check this setting <a href="configuration.php?gID=');
define('INFO2', '">>>Here<<</a>.');

define('VAR_LIST_POPUP', 'Variable List' ); 
define('TEXT_EMAIL_ORDER_TEXT', 'New Order Email Text' ) ;
define('TEXT_EMAIL_SUBJECT',    'Text Email Subject' ) ;

define('POPUP_LIST_EXPLAIN', 				'The Following Variables can be Used in the New Order Email Text');

define('POPUP_STORENAME', 			 'The Name of the Store') ;
define('POPUP_INV_ID',				 'The number of the Order') ;
define('POPUP_INV_URL', 			 'View the order history') ;
define('POPUP_DATEORDERED', 		 'Order Date') ;
define('POPUP_ITEMLIST', 			 'List of Products/Items') ;
define('POPUP_TOTALLIST', 			 'Order Total') ;
define('POPUP_BILLADRESS', 			 'Billing Address') ;
define('POPUP_DELIVERYADRESS', 		 'Delivery Address') ;
define('POPUP_CUSTOMERCOMMENTS', 	 'Customer Comments') ;
define('POPUP_PAYMENTMODULTEXT', 	 'Text of Payment') ;
define('POPUP_PAYMENTMODULFOOTER', 	 'Footer Text of Payment') ;
?>
