<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE',                 'Nieuwe Beoordeling Email tekst');
define('INFO',                          'U kunt hier de Tekst van de Email met de gegevens van de nieuwe Beoordeling invoeren, de tekst kan ingevoerd worden in gewone tekst of in HTML formaat afhankelijk van de configuratie van de webshop. Om in HTML formaat te Emailen, moet U onder  "Configuration/E-Mail Opties" de optie "Gebruik MIME HTML" veranderen naar "true".  U kunt de instelling hier wijzigen <a href="configuration.php?gID= ');
define('INFO2',                         '">>> Hier <<</a>.');
define('VAR_LIST_POPUP',                'Vaste Gegevens lijst' ); 
define('TEXT_EMAIL_ORDER_TEXT',         'Nieuwe Beoordeling Email tekst' ) ;
define('TEXT_EMAIL_SUBJECT',            'Tekst Email Onderwerp' ) ;

define('POPUP_LIST_EXPLAIN', 			'De volgende codes kunnen gebruikt worden in de Email Tekst');

define('POPUP_STORENAME', 			'De Naam van de Webshop') ;
define('POPUP_PRODUCT_ID',			'Product Nummer') ;
define('POPUP_PRODUCT_MODEL', 		'Product Model') ;
define('POPUP_PRODUCT_NAME', 		'Product Naam') ;
define('POPUP_CUSTOMER_ID', 		'Klant Nummer') ;
define('POPUP_CUSTOMER_NAME', 		'Klant Naam') ;
define('POPUP_REVIEW_ID', 			'Nummer Beoordeling') ;
define('POPUP_REVIEW_RATING', 		'Beoordeling schaal 1->5') ;
define('POPUP_REVIEW_LANG_ID', 		'Taal Beoordelings') ;
define('POPUP_REVIEW_TEXT', 		'Beoordelings Tekst') ;
?>