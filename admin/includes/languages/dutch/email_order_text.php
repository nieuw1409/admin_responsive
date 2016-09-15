<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE',                 'Nieuwe Order Email text');
define('INFO',                          'U kunt hier de Tekst van de Email met de gegevens van de Order invoeren, de tekst kan ingevoerd worden in gewone tekst of in HTML formaat afhankelijk van de configuratie van de webshop. Om in HTML formaat te Emailen, moet U onder  "Configuration/E-Mail Opties" de optie "Gebruik MIME HTML" veranderen naar "true".  U kunt de instelling hier wijzigen <a href="configuration.php?gID= ');
define('INFO2',                         '">>> Hier <<</a>.');
define('VAR_LIST_POPUP',                'Vaste Gegevens lijst' ); 
define('TEXT_EMAIL_ORDER_TEXT',         'Nieuwe Order Email Tekst' ) ;
define('TEXT_EMAIL_SUBJECT',            'Tekst Email Onderwerp' ) ;

define('POPUP_LIST_EXPLAIN', 			'De volgende codes kunnen gebruikt worden in de Nieuwe Order Email Tekst');

define('POPUP_STORENAME', 				'De Naam van de Webshop') ;
define('POPUP_INV_ID',					'Het Factuur nummer van de Order') ;
define('POPUP_INV_URL', 				'Link naar de Factuur Historie') ;
define('POPUP_DATEORDERED', 			'Factuur Datum') ;
define('POPUP_ITEMLIST', 				'De bestelde  Product(en) ') ;
define('POPUP_TOTALLIST', 				'Het Factuur Totaal') ;
define('POPUP_BILLADRESS', 				'Het Factuur Adres') ;
define('POPUP_DELIVERYADRESS', 			'Het Aflever Adres') ;
define('POPUP_CUSTOMERCOMMENTS', 		'Het commentaar van de Klant(en)') ;
define('POPUP_PAYMENTMODULTEXT', 		'De tekst van de betaalmethode') ;
define('POPUP_PAYMENTMODULFOOTER', 	    'De voettekst van de betalingsmethode') ;
?>
