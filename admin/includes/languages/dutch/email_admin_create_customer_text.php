<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE',                 'Admin Nieuwe Klant Email text');
define('INFO',                          'U kunt hier de Tekst van de Email met de gegevens van de Nieuwe Klant invoeren, de tekst kan ingevoerd worden in gewone tekst of in HTML formaat afhankelijk van de configuratie van de webshop. Om in HTML formaat te Emailen, moet U onder  "Configuration/E-Mail Opties" de optie "Gebruik MIME HTML" veranderen naar "true".  U kunt de instelling hier wijzigen <a href="configuration.php?gID= ');
define('INFO2',                         '">>> Hier <<</a>.');
define('VAR_LIST_POPUP',                'Vaste Gegevens lijst' ); 
define('TEXT_EMAIL_ORDER_TEXT',         'Nieuwe Klant Email Tekst' ) ;
define('TEXT_EMAIL_SUBJECT',            'Tekst Email Onderwerp' ) ;

define('POPUP_LIST_EXPLAIN', 			'De volgende codes kunnen gebruikt worden in de Nieuwe Klant Email Tekst');

define('POPUP_CA_STORENAME', 		'Naam van de Webshop') ;
define('POPUP_CA_ID',			    'KlantNummer') ;
define('POPUP_CA_GENDER',			'Geslacht') ;
define('POPUP_CA_FIRSTNAME',	    'VoorNaam') ;
define('POPUP_CA_LASTNAME',         'AchterNaam') ;
define('POPUP_CA_STREET', 			'StraatNaam') ;
define('POPUP_CA_POSTCODE', 		'Postcode') ;
define('POPUP_CA_CITY', 			'PlaatsNaam') ;
define('POPUP_CA_COUNTRY', 			'Land') ;
define('POPUP_CA_EMAILADDRESS', 	'EmailAdres') ;
define('POPUP_CA_TELEPHONE', 		'Telefoonnummer') ;
define('POPUP_CA_FAX', 		        'Fax Nummer') ;
define('POPUP_CA_BIRTHDATE', 	    'GeboorteDatum') ;
define('POPUP_CA_COMPANY', 	        'Bedrijfsnaam') ;
define('POPUP_CA_SUBURB', 	        'Wijk') ;
define('POPUP_CA_STATE', 	        'Provincie') ;
define('POPUP_CA_NEWSLETTER', 	    'Geabonneerd Nieuwsbrief') ;
?>
