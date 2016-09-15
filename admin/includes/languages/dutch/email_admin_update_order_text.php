<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE',                 'Admin Update Order Email text');
define('INFO',                          'U kunt hier de Tekst van de Email met de gegevens van de Order invoeren, de tekst kan ingevoerd worden in gewone tekst of in HTML formaat afhankelijk van de configuratie van de webshop. Om in HTML formaat te Emailen, moet U onder  "Configuration/E-Mail Opties" de optie "Gebruik MIME HTML" veranderen naar "true".  U kunt de instelling hier wijzigen <a href="configuration.php?gID= ');
define('INFO2',                         '">>> Hier <<</a>.');
define('VAR_LIST_POPUP',                'Vaste Gegevens lijst' ); 
define('TEXT_EMAIL_ORDER_TEXT',         'Update Order Email Tekst' ) ;
define('TEXT_EMAIL_SUBJECT',            'Tekst Email Onderwerp' ) ;

define('POPUP_LIST_EXPLAIN', 			'De volgende codes kunnen gebruikt worden in de Update Order Email Tekst');

define('POPUP_STORENAME', 				'De Naam van de Webshop') ;
define('POPUP_UPDATE_STATUS', 			'De huidige order status') ;
define('POPUP_ORDER_NUMBER',			'Het Factuur nummer van de Order') ;
define('POPUP_UPDATE_DATE', 			'Datum status wijziging') ;
define('POPUP_UPDATE_COMMENTS', 		'Status Commentaar') ;
define('POPUP_STORE_EMAIL_ADDRESS', 	'Email Adress Webshop') ;
define('POPUP_CUST_EMAIL_ADDRESS', 		'Email Adres Klant') ;
define('POPUP_CUST_NAME', 				'Naam Klant') ;
define('POPUP_INVOICE_ID', 			    'Order Nummer') ;
define('POPUP_UPDATE_CUST_EMAIL', 		'Email Adres Klant') ;
define('POPUP_UPDATE_CUST_NAME', 		'Naam Klant') ;
define('POPUP_UPDATE_DATE_ORDER_BUY', 	'Datum Factuur') ;
define('POPUP_UPDATE_TRATRA_NR', 	    'Track Trace Nummer') ;
define('POPUP_UPDATE_TRATRA_URL', 	    'Track Trace Url') ;
define('POPUP_UPDATE_TRATRA_PCODE', 	'Track Trace PostCode') ;
?>
