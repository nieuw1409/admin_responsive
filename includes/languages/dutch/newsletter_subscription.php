<?php
/*
  $Id: newsletter_subscription.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Nieuwsbrief');
define('HEADING_TITLE', 'Aanmelden Nieuwsbrief');

define('TEXT_INFORMATION', '<strong>Bedankt voor het aanmelden voor onze nieuwsbrief.</strong><br />Een Email met uw gegevens is verstuurd naar email adres : %s .');

define('EMAIL_WELCOME_SUBJECT', 'Welkom op ' . STORE_NAME);

define('EMAIL_WELCOME', 'Hartelijk bedankt voor het aanmelden op onze niewsbrief van ' . STORE_NAME . ' .<br /><br />Deze email is verstuurd voor het succesvol aanmelden op onze nieuwsbrief. <br /><br />Als het U niet bekend is dat u zich aangemeld voor onze nieuwsbrief, kunt u middels onderstaande link dit email adress verwijderen uit onze nieuwsbrief lijst.');

define('TEXT_PRIVACY_EMAIL', '<br /><br />Your information is confidential and will never be retransmitted, please visit our privacy policy : ' . '<a href="' . tep_href_link(FILENAME_PRIVACY, '', 'NONSSL') . '">' . tep_href_link(FILENAME_PRIVACY, '', 'NONSSL') . '</a>');

define('NL_UNSUBSCRIBE_LINK', 'Afmelden Nieuwsbrief volg deze link:<a href="' . tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE, '%s', 'NONSSL') . '">' . 'Afmelden' . '</a>');

define('UNSUBSCRIBE_TEXT', 'Afmelden Nieuwsbrief') ;

define('TEXT_EMAIL_HTML', 'HTML');
define('TEXT_EMAIL_TXT', 'Text');

define('PREVENIR_EMAIL_NEW_INSCRIT_NL', 'oui');
define('EMAIL_NEW_INSCRIT_NL', 'A new subscribe to the Newsletter');

//mis en page html-----------------------------------------------
define('EMAIL_START_HTML', '<html><head> </head><body>');
define('EMAIL_STOP_HTML', '<br /></body></html>');
define('EMAIL_SPAN_START_STYLE', '<span style="font-family:Verdana, Arial, sans-serif; font-size:12px;">');
define('EMAIL_SPAN_STOP_STYLE', '</span>');
//fin mis en page html-----------------------------------------------
?>