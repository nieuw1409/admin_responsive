<?php
/*
  $Id: customers.php,v 1.12 2002/01/12 18:46:27 hpdl Exp $

  DUTCH TRANSLATION
  - V2.2 ms1: Author: Joost Billiet   Date: 06/18/2003   Mail: joost@jbpc.be
  - V2.2 ms2: Update: Martijn Loots   Date: 08/01/2003   Mail: oscommerce@cosix.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Klanten');
define('HEADING_TITLE_SEARCH', 'Zoeken:');

define('TABLE_HEADING_FIRSTNAME', 'Voornaam');
define('TABLE_HEADING_LASTNAME', 'Achternaam');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account gemaakt');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_HEADING_PERSONAL', 'Algemeen');
define('TEXT_HEADING_ADDRESS_CONTACT', 'Adres/Contact');
define('TEXT_HEADING_OPTIONS', 'Opties');
define('TEXT_HEADING_SPPC', 'Opties Prijs Per Klant');

define('TEXT_DATE_ACCOUNT_CREATED', 'Account gemaakt:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Laatst gewijzigd:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Laatste login:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Aantal logins:');
define('TEXT_INFO_COUNTRY', 'Land:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Aantal recensies:');
define('TEXT_DELETE_INTRO', 'Weet u zeker dat u deze klant wil verwijderen?');
define('TEXT_DELETE_REVIEWS', 'Verwijder %s recensie(s)');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Verwijder klant');
define('TEXT_INFO_EDIT_INTRO', 'Wijzigen klant');

define('TYPE_BELOW', 'Typ hieronder');
define('PLEASE_SELECT', 'Selecteer');
// BOF Separate Pricing Per Customer
define('TABLE_HEADING_CUSTOMERS_GROUPS', 'Klanten Groep');
define('TABLE_HEADING_CUSTOMERS_GROUPS_QNT', 'Aantal') ;
define('TABLE_HEADING_REQUEST_AUTHENTICATION', 'RA');

define('ENTRY_CUSTOMERS_PAYMENT_SET', 'Betalings Methode voor deze klant');
define('ENTRY_CUSTOMERS_PAYMENT_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_CUSTOMERS_PAYMENT_SET_EXPLAIN', 'Als U de <b><i>' . ENTRY_CUSTOMERS_PAYMENT_SET . '</i></b> activeert maar geen Betalingsmethode activeert, worden de standaard Betalingsmethode gebruikt.');

define('ENTRY_CUSTOMERS_SHIPPING_SET',  'Verzend Methodes voor deze Klant');
define('ENTRY_CUSTOMERS_SHIPPING_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_CUSTOMERS_SHIPPING_SET_EXPLAIN', 'If you choose <b><i>'. ENTRY_CUSTOMERS_SHIPPING_SET. '</i></b> activeert  maar geen Verzendings methode activeert, worden de standaard Verzendings methode gebruikt.');

define('ENTRY_CUSTOMERS_ORDER_TOTAL_SET', 'Order totaal modules voor de Klanten Groep');
define('ENTRY_CUSTOMERS_ORDER_TOTAL_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_CUSTOMERS_ORDER_TOTAL_SET_EXPLAIN', 'Als U de  <b><i>' . ENTRY_CUSTOMERS_ORDER_TOTAL_SET. '</i></b> activeert  maar geen Order Totaal Modules activeert, worden de standaard Order Totaal Modules gebruikt..');


define('HEADING_TITLE_CUSTOMERS_TAX_RATES_EXEMPT', 'Vrijstelling per klant van een specifieke BTW tarief');

define('ENTRY_CUSTOMERS_TAX_RATES_EXEMPT', 'Vrijstelling BTW tarief van deze klant');
define('ENTRY_CUSTOMERS_TAX_RATES_DEFAULT', 'Gebruik de instelling van de groep of van de Configuration (zone based)');
define('ENTRY_CUSTOMERS_TAX_RATES_EXEMPT_EXPLAIN', 'Als U de <b><i>' . ENTRY_CUSTOMERS_TAX_RATES_EXEMPT . '</i></b> activeert  maar geen BTW optie activeert, worden de standaard BTW percentage (zone based) gebruikt.<br />');

//'If you choose <b><i>Exempt tax rates from the customer</i></b> but do not check any of the boxes, default settings (Group or zone based Configuration settings) will still be used.<br />If this customer is in a group that is "Tax Exempt", none of these settings will have any effect.');
// EOF Separate Pricing Per Customer
define('ENTRY_GROUP_PAYMENT_SET', '');
define('ENTRY_GROUP_PAYMENT_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_PAYMENT_SET_EXPLAIN', '');
define('ENTRY_GROUP_SHIPPING_SET', 'Verzend Methodes voor deze Klanten Groep');
define('ENTRY_GROUP_SHIPPING_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_SHIPPING_SET_EXPLAIN', 'If you choose <b><i>'. ENTRY_GROUP_SHIPPING_SET. '</i></b> activeert  maar geen Verzendings methode activeert, worden de standaard Verzendings methode gebruikt.');

define('ENTRY_GROUP_ORDER_TOTAL_SET', 'Order totaal modules voor de Klanten Groep');
define('ENTRY_GROUP_ORDER_TOTAL_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_ORDER_TOTAL_SET_EXPLAIN', 'Als U de  <b><i>' . ENTRY_GROUP_ORDER_TOTAL_SET. '</i></b> activeert  maar geen Order Totaal Modules activeert, worden de standaard Order Totaal Modules gebruikt..');

define('ENTRY_GROUP_TAX_RATES_EXEMPT', 'Maak geen gebruik BTW percentage van de Klanten Groep');
define('ENTRY_GROUP_TAX_RATES_DEFAULT', 'Gebruik instelling van de Configuratie(zone based)');
define('ENTRY_TAX_RATES_EXEMPT_EXPLAIN', 'Als U de <b><i>' . ENTRY_GROUP_TAX_RATES_EXEMPT . '</i></b> activeert  maar geen Order Totaal Modules activeert, worden de standaard BTW percentage (zone based) gebruikt.<br />
                                          Als U voor deze groep BTW uitsluiting op ' . ENTRY_GROUP_TAX_EXEMPT_YES . ' heeft ingesteld , worden geen van bovenstaande instellingen actief.');
define('SORT_BY_COMPANYNAME', 'Sorteer op BedrijfsNaam --> A-B-C ');
define('SORT_BY_COMPANYNAME_DESC', 'Sorteer op BedrijfsNaam --> Z-X-Y ');
define('SORT_BY_FIRSTNAME', 'Sorteer op VoorNaam Oplopend --> A-B-C ' );
define('SORT_BY_FIRSTNAME_DESC', 'Sorteer op VoorNaam Aflopend --> Z-X-Y ');
define('SORT_BY_LASTNAME', 'Sorteer op AchterNaam Oplopend --> A-B-C ');
define('SORT_BY_LASTNAME_DESC', 'Sorteer op AchterNaam Aflopend --> Z-Y-X ');
define('SORT_BY_CUSTOMER_GROUP', 'Sorteer op KlantGroep Oplopend --> A-B-C ');
define('SORT_BY_CUSTOMER_GROUP_DESC', 'Sorteer op KlantGroep Aflopend --> Z-X-Y ');
define('SORT_BY_ACCOUNT_CREATED', 'Sorteer op Datum Oplopend  --> 1-2-3 ');
define('SORT_BY_ACCOUNT_CREATED_DESC', 'Sorteer op Datum Aflopend  --> 3-2-1 ');
define('SORT_BY_RA', 'Sorteer op Authorizatie --> RA als eerste (to Top) ');
define('SORT_BY_RA_DESC', 'Sorteer op Authorizatie --> RA als Laatste (to Bottom) ');
define('TEXT_INFO_STORE_ACTIVATED_NAME', 'Account gemaakt in Winkel '); // multi stores
define('ENTRY_CUSTOMERS_STORES_NAME', 'Geactiveerd in Winkel' ) ; // multi stores
?>