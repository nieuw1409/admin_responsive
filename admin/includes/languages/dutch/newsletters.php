<?php
/*
  $Id: newsletters.php,v 1.5 2002/03/08 22:10:08 hpdl Exp $

  DUTCH TRANSLATION
  - V2.2 ms1: Author: Joost Billiet   Date: 06/18/2003   Mail: joost@jbpc.be
  - V2.2 ms2: Update: Martijn Loots   Date: 08/01/2003   Mail: oscommerce@cosix.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Nieuwsbrief manager');

define('TABLE_HEADING_NEWSLETTERS', 'Nieuwsbrief');
define('TABLE_HEADING_SIZE', 'Grootte');
define('TABLE_HEADING_MODULE', 'Module');
define('TABLE_HEADING_SENT', 'Verstuur');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_NEWSLETTER_MODULE', 'Module:');
define('TEXT_NEWSLETTER_TITLE', 'Titel van nieuwsbrief:');
define('TEXT_NEWSLETTER_CONTENT', 'Inhoud:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'Toegevoegd op:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Verstuurd op:');

define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze nieuwsbrief wilt verwijderen?');

define('TEXT_PLEASE_WAIT', 'Gelieve te wachten...De emails worden verstuurd...<br><br>Gelieve dit proces niet te onderbreken!');
define('TEXT_FINISHED_SENDING_EMAILS', 'De emails zijn verzonden!');

define('ERROR_NEWSLETTER_TITLE', 'Fout: Nieuwsbrief titel vereist');
define('ERROR_NEWSLETTER_MODULE', 'Fout: Nieuwsbrief module vereist');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Fout: Blokkeer de nieuwsbrief alvorens deze te verwijderen.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Fout: Blokkeer de nieuwsbrief alvorens deze te wijzigen.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Fout: Blokkeer de nieuwsbrief alvorens deze te verzenden.');
// BOF Separate Pricing Per Customer
define('TABLE_HEADING_CUSTOMERS_GROUPS', 'KlantenGroepen');
define('TEXT_SEND_TO_CUSTOMERS_GROUPS', '<div align=\'center\' style=\'margin-top: 10px; margin-bottom: 5px; padding-top: 5px; font-weight: bold; border-top: 1px solid black;\'>Verzend naar deze Klanten Groep(en):</div>');
// EOF Separate Pricing Per Customer

define('TEXT_INFO_HEADING_DELETE_NEWSLETTER', 'Verwijder Nieuwsbrief') ;
define('TEXT_INFO_HEADING_EDIT_NEWSLETTER', 'Wijzig Nieuwsbrief') ;

define( 'TEXT_TABS_EDIT_NEWSLETTER_01', 'Winkels' ) ;
define( 'TEXT_TABS_EDIT_NEWSLETTER_02', 'Klanten Groep' ) ;

define( 'TEXT_NEWSLETTER_TOTAL_CUSTOMERS', 'Aantal Klanten') ;
define( 'TEXT_NEWSLETTER_TOTAL_CUST_NEWSLETTER', 'Klanten met Actieve Nieuwsbrieven') ; 
define( 'TEXT_NEWSLETTER_TOTAL_CUST_RECEIVE', 'Klanten die een Nieuwsbrief ontvangen') ;

define( 'TEXT_NEWSLETTER_TOTAL_CUST_PROD_NOTI', 'Klanten met Actieve Product Notificatie') ; 
define( 'TEXT_NEWSLETTER_TOTAL_CUST_RECEIVE_PN', 'Klanten die een Product Notificatie ontvangen') ; 

define( 'TEXT_INFO_HEADING_SEND_NEWSLETTER', 'Verzenden Niewsbrief') ;
define( 'TEXT_INFO_HEADING_SEND_PRODUCT_NOTI', 'Verzenden Product Notificatie') ;

define( 'TEXT_INFO_SEND_NEWSLETTERS', 'Totaal verzonden Nieuwsbrieven  :  %s') ;

define( 'TEXT_INFO_HEADING_SENDING_NEWSLETTER', 'Verzenden van Nieuwsbrieven' ) ;
define( 'TEXT_INFO_HEADING_SEND_NEWSLETTER', 'Verzonden Nieuwsbrieven' ) ;

define( 'TEXT_NEWSLETTER_ACTIVE_STORES', 'Verzenden naar Winkel(s)  ') ;
define( 'TEXT_NEWSLETTER_ACTIVE_CUST_GROUPS', 'Verzenden naar KlantenGroep(en)  ') ;

define('TEXT_INFO_CUST_GROUPS', 'Indien geen van de klanten groep(en) wordt gekozen zal de Nieuwesbrief naar iedereen met een actieve neiuwsbrief worden verzonden');

define('TEXT_NEWSLETTER_UNSUBSCRIBE' , 'Tekst Afmelden Nieuwsbrief:' ) ;
define('TEXT_NEWSLETTER_SEND_UNSUBSCRIBE' , 'Afmelden' ) ;
define('TEXT_INFO_HEADING_NEW_NEWSLETTER', 'Nieuwe Nieuwsbrief') ;
define('TEXT_NEWSLETTER_TOTAL_SUBSCRIBERS', 'Aantal Nieuwsbrief gebruikers:') ;
define('TEXT_NEWSLETTER_TOTAL_SUBSR_NEWSLETTER', 'Actieve Nieuwsbrief Gebruikers:' ) ;
?>