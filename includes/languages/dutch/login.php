<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Dutch translation for v2.3.1 by dfirefire
  http://www.dfirefire.be
  
  Copyright (c) 2003 osCommerce

  Released under de GNU General Public License
*/

define('NAVBAR_TITLE', 'Aanmelden');
define('HEADING_TITLE', 'Welkom, gelieve u aan te melden');

define('HEADING_NEW_CUSTOMER', 'Nieuwe klant');
define('TEXT_NEW_CUSTOMER', 'Ik ben een nieuwe klant.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Door een account aan te maken bij ' . STORE_NAME . ' kunt u sneller winkelen, wordt u op de hoogte gehouden van de status van de bestellingen en kunt u het overzicht houden over de door u geplaatste bestellingen.');

define('HEADING_RETURNING_CUSTOMER', 'Vaste klant');
define('TEXT_RETURNING_CUSTOMER', 'Ik ben een vaste klant.');

define('TEXT_PASSWORD_FORGOTTEN', 'Wachtwoord vergeten? Klik hier.');

define('TEXT_LOGIN_ERROR', 'Fout: Geen overeenkomst gevonden voor dit e-mail-adres ne/of paswoord.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><strong>Opmerking:</strong></font> De inhoud van uw &quot;Bezoekersmandje&quot; zal samengevoegd worden met dat van uw &quot;Ledenmandje&quot; eens u zich aangemeld hebt. <a href="javascript:session_win();">[Meer info]</a>');
define('TEXT_MAKE_NEW_CUSTOMER', 'Nieuwe Klant. Klik hier om een nieuwe account aan te maken' ) ; 
define('TEXT_LOGIN_ERROR_SUSPENDED',        'Error: Uw klanten account is op non actief gesteld . <a href="' . tep_href_link(FILENAME_CONTACT_US) . '"><strong>Neem Contact op </strong></a> voor meer information.');
define('TEXT_MOBILE_LOGIN_ERROR_SUSPENDED', 'Error: Uw klanten account is op non actief gesteld . <a href="' . tep_href_link(FILENAME_MOBILE_CONTACT_US) . '"><strong>Neem Contact op </strong></a> voor meer information.');
?>
