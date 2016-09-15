<?php
/*
  $Id: orders.php,v 1.25 2003/06/20 00:28:44 hpdl Exp $

  DUTCH TRANSLATION
  - V2.2 ms1: Author: Joost Billiet   Date: 06/18/2003   Mail: joost@jbpc.be
  - V2.2 ms2: Update: Martijn Loots   Date: 08/01/2003   Mail: oscommerce@cosix.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Bestellingen');
define('HEADING_TITLE_SEARCH', 'Bestelnummer:');
define('HEADING_TITLE_STATUS', 'Status:');

define('HEADING_SEARCH_CUSTOMER', 'Klant Naam') ;
define('HEADING_SEARCH_ORDER',    'Order Nummer') ;

define('TABLE_HEADING_COMMENTS', 'Commentaar');
define('TABLE_HEADING_CUSTOMERS', 'Klanten');
define('TABLE_HEADING_ORDER_TOTAL', 'Order totaal');
define('TABLE_HEADING_DATE_PURCHASED', 'Gekocht op');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_QUANTITY', 'Aantal');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Artikelen');
define('TABLE_HEADING_TAX', 'BTW');
define('TABLE_HEADING_TOTAL', 'Totaal');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Prijs (excl.)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Prijs (incl.)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Totaal (excl.)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Totaal (incl.)');
define('TABLE_HEADING_CUSTOMERS_GROUPS', 'KlantenGroep'); //sppc
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Klant ingelicht');
define('TABLE_HEADING_DATE_ADDED', 'Datum toegevoegd');

define('ENTRY_CUSTOMER', 'Klant:');
define('ENTRY_NAME', 'Naam:');
define('ENTRY_SOLD_TO', 'Verkocht aan:');
define('ENTRY_DELIVERY_TO', 'Leveren aan:');
define('ENTRY_SHIP_TO', 'Verzenden naar:');
define('ENTRY_SHIPPING_ADDRESS', 'Afleveradres:');
define('ENTRY_BILLING_ADDRESS', 'Factuuradres:');
define('ENTRY_PAYMENT_METHOD', 'Betaalwijze:');
define('ENTRY_CREDIT_CARD_TYPE', 'Creditcard type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Creditcard eigenaar:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Creditcard nummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Creditcard vervalt op:');
define('ENTRY_SUB_TOTAL', 'Subtotaal:');
define('ENTRY_TAX', 'BTW:');
define('ENTRY_SHIPPING', 'Verzendkosten:');
define('ENTRY_TOTAL', 'Totaal:');
define('ENTRY_DATE_PURCHASED', 'Datum gekocht:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Datum laatste update:');
define('ENTRY_NOTIFY_CUSTOMER', 'Licht klant in:');
define('ENTRY_NOTIFY_COMMENTS', 'Voeg commentaar toe:');
define('ENTRY_PRINTABLE', 'Print factuur');
define('ENTRY_CITY_STATE', 'Woonplaats');
define('ENTRY_CURRENCY_TYPE', 'Valuta');
define('ENTRY_CURRENCY_VALUE', 'Valuta Waarde');
define('ENTRY_STORES_ID', 'Geactiveerde in Winkel');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Verwijder bestelling');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze bestelling wil verwijderen?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Voorraad bijwerken');
define('TEXT_DATE_ORDER_CREATED', 'Datum aangemaakt:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Laatst aangepast:');
define('TEXT_INFO_PAYMENT_METHOD', 'Betaalwijze:');

define('TEXT_ALL_ORDERS', 'Alle bestellingen');
define('TEXT_NO_ORDER_HISTORY', 'Geen bestelgeschiedenis beschikbaar');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Wijziging');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestelnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Gedetailleerde factuur:');
define('EMAIL_TEXT_DATE_ORDERED', 'Besteldatum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Uw order is aangepast naar de volgende status.' . "\n\n" . 'Nieuwe status: %s' . "\n\n" . 'Reageer s.v.p. op deze email als u nog vragen heeft.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Het commentaar voor uw order is' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fout: bestelling bestaat niet.');
define('SUCCESS_ORDER_UPDATED', 'Succes: De bestelling is succesvol ge-update.');
define('WARNING_ORDER_NOT_UPDATED', 'Waarschuwing: Er valt niets aan te passen. Geen order update uitgevoerd.');

// SADESA ORDER TRACKING
define('TABLE_HEADING_TRACKING', 'Track en Trace');
define('TABLE_HEADING_TRACKNR', 'Nummer');
define('TABLE_HEADING_TRACKPC', 'Post Code');
define('URL_TO_TRACKNR', 'https://securepostplaza.tntpost.nl/TPGApps/tracktrace/findByBarcodeServlet?BARCODE=');
define('URL_TO_TRACKPC', '&ZIPCODE=');
define('ENTRY_NOTIFY_TRACKING', 'Tracking nr. meesturen:');
define('EMAIL_TEXT_TRACKING_NUMBER', 'Het TPG Post Tracktrace nummer van bovenstaande bestelling is aan Uw account toegevoegd.');
define('TABLE_HEADING_CUST_GROUP', 'KlantenGroep');
/*** BOF: Additional Orders Info ***/
define('TEXT_ADDITIONAL_WARNING_TEXT_PAYMENT', '<span class="ui-state-highlight">***Controleer Betalings Informatie***</span>');
define('TEXT_ADDITIONAL_WARNING_TEXT_SHIPPING', '<span class="smallText ui-state-highlight" >***Controleer Verzend Informatie***</span>');
define('TEXT_ADDITIONAL_COLOR_ADDRESS', '<span class="smallText ui-state-error">Adres Fout</span>');
define('TEXT_ADDITIONAL_COLOR_PAYMENT', '<span class="smallText ui-state-highlight" >monitored payment used</span>');
define('TEXT_ADDITIONAL_COLOR_BOTH_ADDRESS_PAYMENT', '<span class="smallText ui-state-highlight" >Beide Condities Zijn Aanwezig</span>');
define('TEXT_COLOR_SCHEME', 'color scheme:');
define('TEXT_INFO_ADDRESS_MISMATCH', '<span class="smallText ui-state-error" >Verzend / Betaal Adres zijn Verschillend</span>');
define('TEXT_INFO_SEE_OTHER_ORDERS', 'Klik voor gerelateerde Orders');
define('TEXT_INFO_SHIPPING_METHOD', 'Verzonden door:');
define('TEXT_INFO_OTHER_ORDERS', '<strong>Gerelateerde Orders:</strong> (%s)');
define('TEXT_INFO_OTHER_ORDERS_TOTALS', '&nbsp;<strong>Afgehandelde Orders:</strong> %s<br />&nbsp;<strong>Totaal:</strong> %s');
define('TEXT_INFO_PRODUCTS_ORDERED', 'Producten: ');
define('TEXT_PREVIOUS_ORDERS_YES', '<span class="ui-state-default smallText">Vorige Orders: <strong>Aanwezig</strong></span>');
define('TEXT_PREVIOUS_ORDERS_NO', '<span class="ui-state-default smallText">Vorige Orders: <strong>Niet Aanwezig</strong></span>');
define('TEXT_PRODUCTS_REMAINING', '<br />&nbsp;&nbsp;(Op Voorraad: %s)'); 
/*** EOF: Additional Orders Info ***/
define('TEXT_CUSTOMER_CODE', 'KlantNummer :' ) ;
define('TEXT_SHIPPING_METHOD', 'Verzenden Via :' ) ;
define('TEXT_ORDER_CODE', 'Order Nummer :' ) ;
define('TEXT_STORES_ID', 'Winkel Naam :' ) ; // MULTI STORES
define('TEXT_INFO_STORE_ACTIVATED_NAME', 'Winkel Naam ' ) ; // MULTI STORES
define('TEXT_ALL_STORES', 'Alle Winkels ' ) ; // MULTI STORES
define('HEADING_TITLE_STORES', 'Winkels:'); // MULTI STORES

define('BUTTON_INVOICE', 'Factuur' ) ;

define( 'TEXT_COLLAPSE_ADRESS', 'Klant Adres en betaalgegevens') ;
define( 'TEXT_COLLAPSE_ORDER_STATUS', 'Status + Geschiedenis Order Status') ;
define( 'TEXT_ORDER_PRODUCTS', 'Deze order bevat de volgende artikelen');
define( 'TABLE_HEADING_NEW_STATUS', 'Nieuwe Status');
define( 'TEXT_TABS_EDIT_ORDER_ORDER_HIST_02', 'Order Status Geschiedenis');
define( 'TABLE_HEADING_DELETE', 'Verwijderen?');

define('TEXT_TABS_EDIT_ORDER_01', 'Order Adres Klant');
define('TEXT_TABS_EDIT_ORDER_02', 'Order Verzend Adres');
define('TEXT_TABS_EDIT_ORDER_03', 'Order Factuur Adres');
define('TEXT_TABS_EDIT_ORDER_04', 'Order Betalings Methode');

define('TEXT_SEARCH_NAME', 'Zoek op Naam') ;
define('TEXT_SEARCH_ORDER', 'Zoek op Order') ;
?>