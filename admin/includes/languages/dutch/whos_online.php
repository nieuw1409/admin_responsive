<?php
/*
  $Id: whos_online.php,v 3.5.4 2008/7/8 SteveDallas Exp $
  
  2008 Jul 08 v3.5.4 Glen Hoag aka SteveDallas Modified TEXT_NUMBER_OF_CUSTOMERS for formatting change
  2008 Jun 13 v3.5   Glen Hoag aka SteveDallas Moved version number out of language files
                                               Added string TEXT_ACTIVE_CUSTOMERS
                                               Added string TEXT_SHOW_BOTS

  updated version number because of version number jumble and provide installation instructions.
  corection french by azer
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// added for version 1.9 - to be translated to the right language BOF ******
define('AZER_WHOSONLINE_WHOIS_URL', 'http://www.dnsstuff.com/tools/whois.ch?ip='); //for version 2.9 by azer - whois ip
define('TEXT_NOT_AVAILABLE', '   <b>Note:</b> N/A = IP niet beschikbaar'); //for version 2.9 by azer was missing
define('TEXT_LAST_REFRESH', 'Voor het laatst ververst om'); //for version 2.9 by azer was missing
define('TEXT_EMPTY', 'Leeg'); //for version 2.8 by azer was missing
define('TEXT_MY_IP_ADDRESS', 'Uw IP adres '); //for version 2.8 by azer was missing
define('TABLE_HEADING_COUNTRY', 'Land'); // azerc : 25oct05 for contrib whos_online with country and flag
// added for version 1.9 EOF *************************************************

define('HEADING_TITLE', 'Who\'s Online');  // Version update to 3.2 because of multiple 1.x and 2.x jumble.  apr-07 by nerbonne
define('TABLE_HEADING_ONLINE', 'Online');
define('TABLE_HEADING_CUSTOMER_ID', 'ID');
define('TABLE_HEADING_FULL_NAME', 'Naam');
define('TABLE_HEADING_IP_ADDRESS', 'IP Adres');
define('TABLE_HEADING_ENTRY_TIME', 'Binnenkomst');
define('TABLE_HEADING_LAST_CLICK', 'Laatste klik');
define('TABLE_HEADING_LAST_PAGE_URL', 'Laatste URL');
define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_SHOPPING_CART', 'Winkelwagen');
define('TEXT_SHOPPING_CART_SUBTOTAL', 'Subtotal');
//define('TEXT_NUMBER_OF_CUSTOMERS', 'bezoekers online (Inactief na 5 min. Verwijderd na 15 min.)');
define('TEXT_NUMBER_OF_CUSTOMER', 'bezoeker online waarvan:');
define('TEXT_NUMBER_OF_CUSTOMERS', '%s bezoekers online waarvan:');
define('TABLE_HEADING_HTTP_REFERER', 'Komt van');
define('TEXT_HTTP_REFERER_URL', 'Afkomstig van');
define('TEXT_HTTP_REFERER_FOUND', 'Ja');
define('TEXT_HTTP_REFERER_NOT_FOUND', 'Nee');
define('TEXT_STATUS_ACTIVE_CART', 'Actief met WW');
define('TEXT_STATUS_ACTIVE_NOCART', 'Actief zonder WW');
define('TEXT_STATUS_INACTIVE_CART', 'Inactief met WW');
define('TEXT_STATUS_INACTIVE_NOCART', 'Inactief zonder WW');
define('TEXT_STATUS_NO_SESSION_BOT', 'Inactieve Bot'); //Azer !!! check if right description
define('TEXT_STATUS_INACTIVE_BOT', 'Inactieve Bot'); //Azer !!! check if right description
define('TEXT_STATUS_ACTIVE_BOT', 'Actieve Bot'); //Azer !!! check if right description
define('TABLE_HEADING_COUNTRY', 'Land');
define('TABLE_HEADING_USER_SESSION', 'Sessie');
define('TEXT_IN_SESSION', 'Ja');
define('TEXT_NO_SESSION', 'Nee');

define('TEXT_OSCID', 'osCsid');
define('TEXT_PROFILE_DISPLAY', 'Meer info van');
define('TEXT_USER_AGENT', 'User Agent');
define('TEXT_ERROR', 'Error!');
define('TEXT_ADMIN', 'Admin');
define('TEXT_DUPLICATE_IP', 'Dubbele IP adres');
define('TEXT_DUPLICATE_IPS', 'Dubbele IP adressen');
define('TEXT_BOT', 'Bot/Crawler');
define('TEXT_BOTS', 'Bots/Crawlers');
define('TEXT_ME', 'Beheerder(s)');
define('TEXT_ALL', 'Allen');
define('TEXT_REAL_CUSTOMER', 'Klant');
define('TEXT_REAL_CUSTOMERS', 'Klanten');
define('TEXT_ACTIVE_CUSTOMER', ' is actief.');
define('TEXT_ACTIVE_CUSTOMERS', ' waarvan %s actief.');

define('TEXT_YOUR_IP_ADDRESS', 'Uw IP Adres');
define('TEXT_SET_REFRESH_RATE', 'Vernieuwings tijd');
define('TEXT_NONE_', 'Niet');
define('TEXT_CUSTOMERS', 'Klanten');
define('TEXT_SHOW_BOTS', 'Toon Bots');
define('TEXT_SHOW_MAP', 'Toon kaart');
define('TEXT_COUNTRY', 'Country');
define('TEXT_REGION', 'Regio');
define('TEXT_CITY', 'Stad');
?>
