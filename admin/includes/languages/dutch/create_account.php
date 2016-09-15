<?php
/*
  $Id: create_account.php,v 1.8 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('HEADING_TITLE_CREATE_ACCOUNT', 'Aanmaken Nieuwe Klant');
define('PULL_DOWN_DEFAULT', 'AUB selecteren');

define('HEADING_TITLE_CREATE_ACCOUNT_SUCCESS', 'Nieuwe klant succevol aangemaakt');

define('NAVBAR_TITLE', 'Nieuwe klant');
define('HEADING_TITLE', 'Uw klantgegevens');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>LET OP:</b></font></small> Als u al klant bent log dan in op de <a href="%s"><u>login pagina</u></a>.');

define('EMAIL_SUBJECT', 'Welkom bij ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Geachte heer %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Geachte mevrouw %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Beste %s' . "\n\n");
define('EMAIL_WELCOME', 'Wij verwelkomen u bij <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'U kunt nu gebruik maken van de <b>diverse diensten</b> die wij kunnen aanbieden. Een aantal van deze diensten zijn:' . "\n\n" . '<li><b>Permanente winkelwagen</b> - Ieder artikel dat u aan uw winkelwagen toevoegt blijft bewaard tot u deze weer verwijderd of bestelt.' . "\n" . '<li><b>Adresboek</b> - Wij kunnen nu uw bestelling leveren aan een ander adres als de uwe! Dit is ideaal om bijv. een verjaardagsgeschenk direct aan de jarige te sturen.' . "\n" . '<li><b>Bestellingen Overzicht</b> - Bekijk al uw vorige bestellingen die u bij ons heeft geplaatst.'. "\n\n");
define('EMAIL_CONTACT', 'Voor hulp bij onze diensten kunt u contact opnemen met: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>OPMERKING:</b> Indien u zich niet zelf als klant heeft aangemeld neemt u dan a.u.b. contact op met ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('TEXT_SELECT_STORES_TITLE','Winkel Keuze'); // MULTI STORES
define('TEXT_SELECT_STORES', 'Selecteer Winkel:'); // MULTI STORES
define('ENTRY_STORES','Winkel'); // MULTI STORES
define('TEXT_INFO_STORE_ACTIVATED_NAME', 'Account gemaakt in Winkel '); // multi stores
define('ENTRY_CUSTOMERS_STORES_NAME', 'Geactiveerd in Winkel' ) ; // multi stores
?>