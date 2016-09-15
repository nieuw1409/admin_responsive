<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Admin Order confirmation: Email text');
define('INFO', 'You can enter your confirmation email here, it can be in plain text or HTML format depending on the configuration of your shop. To use HTML you must, under "Configuration/E-Mail Options" set "use MIME HTML" to "true".  You can check this setting <a href="configuration.php?gID=');
define('INFO2', '">>>Here<<</a>.');

define('VAR_LIST_POPUP', 'Variable List' ); 
define('TEXT_EMAIL_ORDER_TEXT', 'New Order Email Text' ) ;
define('TEXT_EMAIL_SUBJECT',            'Text Email Subject' ) ;

define('POPUP_LIST_EXPLAIN', 				'The Following Variables can be Used in the New Order Email Text');

define('POPUP_STORENAME', 				'Name of the Webshop') ;
define('POPUP_UPDATE_STATUS', 			'Current order status') ;
define('POPUP_ORDER_NUMBER',			'Invoice number of the Order') ;
define('POPUP_UPDATE_DATE', 			'Date status change') ;
define('POPUP_UPDATE_COMMENTS', 		'Status Comments') ;
define('POPUP_STORE_EMAIL_ADDRESS', 	'Email Asdress Webshop') ;
define('POPUP_CUST_EMAIL_ADDRESS', 		'Email Asdres Customer') ;
define('POPUP_CUST_NAME', 				'Name Customer') ;
define('POPUP_INVOICE_ID', 			    'Order Number') ;
define('POPUP_UPDATE_CUST_EMAIL', 		'Email Address Customer') ;
define('POPUP_UPDATE_CUST_NAME', 		'Name Customer') ;
define('POPUP_UPDATE_DATE_ORDER_BUY', 	'Date Invoice') ;
define('POPUP_UPDATE_TRATRA_NR', 	    'Track Trace Number') ;
define('POPUP_UPDATE_TRATRA_URL', 	    'Track Trace Url') ;
define('POPUP_UPDATE_TRATRA_PCODE', 	'Track Trace PostalCode') ;

define('POPUP_CONTRIBUTION',				'The following substitute symbols are only available <br>
                                     if you have installed the Admin Edit Invoice contribution by Infobroker <br>
                                     http://www.oscommerce.com/community/contributions,2729 : <br><br>' );
                                     
define('POPUP_STORENAME2', 		'The complete name of the Store');
define('POPUP_TAX_OFFICE', 		'The Tax Office');
define('POPUP_TAX_NUMBER', 		'The Tax Number');
define('POPUP_USTID', 				'The UST ID');
define('POPUP_BANKNAME', 			'The Name of The Bank');
define('POPUP_ACCOUNT_NAME', 	'The Full Name of Bank Account');
define('POPUP_BANK_ID', 			'The Code Number of the Bank');
define('POPUP_BANK_NR', 			'The Account Number');
define('POPUP_BANK_SWIFT', 		'The Swift Code of the Bank');
define('POPUP_BANK_IBAN', 	  'The IBAN Number of the Bank Account');

?>
