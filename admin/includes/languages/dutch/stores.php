<?php
/*
  $Id: stores.php,v 1.0 2004/08/23 01:58:58 rmh Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Winkels');

define('TABLE_HEADING_STORES', 'Winkels');
define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_HEADING_NEW_STORE', 'Nieuwe Winkel');
define('TEXT_HEADING_EDIT_STORE', 'Wijzig Winkel');
define('TEXT_HEADING_DELETE_STORE', 'Verwijder Winkel');

define('TEXT_STORES', 'Winkels:');
define('TEXT_DATE_ADDED', 'Datum Toegevoegd:');
define('TEXT_LAST_MODIFIED', 'Datum Aangepast:');
define('TEXT_PRODUCTS', 'Producten:');
define('TEXT_IMAGE_NONEXISTENT', 'AFBEELDING BESTAAT NIET');

define('TEXT_NEW_INTRO', 'Gelieve de volgende informatie in te vullen voor de nieuwe Winkel');
define('TEXT_EDIT_INTRO', 'Gelieve de nodige aanpassingen door te voeren');

define('TEXT_STORES_NAME', 'Naam Winkel :');
define('TEXT_STORES_IMAGE', 'Afbeelding Winkel:');
define('TEXT_STORES_URL', 'URL voor de Winkel catalog: (i.e. http://www.yourdomain.com/catalog)');
define('TEXT_STORES_ABSOLUTE', 'Absolute pad voor de Winkel catalog: (i.e. /home/user/public_html/catalog)');
define('TEXT_STORES_CONFIG_TABLE', 'BestandsNaam Configuratie Winkel:  Bij voorkeur de naam config_???? ');
define('TEXT_STORES_CUSTOMERS_GROUP_NAME', 'Standaard KlantGroep voor deze Winkel');
define('TEXT_STORES_ADMIN_COLOR', 'Het kleurenschema voor de administratie omgeving');

define('TEXT_DELETE_INTRO', 'Wilt U deze Winkel verwijderen ?');
define('TEXT_DELETE_IMAGE', 'Verwijder de afbeeldingen van deze winkel ?');
define('TEXT_INSERT_TABLE', 'Voeg Standaard Configuration gegevens toe aan bestand?');
define('TEXT_DELETE_TABLE', 'Verwijder configuration bestand?');
define('TEXT_DELETE_WARNING_PRODUCTS', 'LET OP: Er zijn %s producten aan deze winkel gelinked!');
define('TEXT_UPDATE_WARNING_CONFIG', 'LET OP: Configuration bestand hernoemd! Update uw database_tables.php bestand');
define('ERROR_DEFAULT_STORE', 'FOUT: U kunt niet de standaard winkel verwijderen !!');
define('ERROR_STORES_NAME_CONFIG', 'FOUT: U moet een Naam van de Winkel en een Configuratie bestandsnaam invoeren !!');
define('ERROR_STORES_CONFIG_TABLE_EXISTS', 'FOUT: Configuration bestand bestaat reeds bij een andere winkel');
?>