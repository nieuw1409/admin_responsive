<?php
/*
   for Separate Pricing Per Customer v4.2.1 2007/11/04
*/
  
define('HEADING_TITLE', 'Klanten Groepen');
define('HEADING_TITLE_SEARCH', 'Zoek:');

define('TABLE_HEADING_NAME',                'Naam');
define('TABLE_HEADING_ACTION',              'Actie');
define('TABLE_HEADING_DISCOUNT',            'Korting'); // totalb2b

define('HEADING_TITLE_MODULES_PAYMENT',     'Actieve Betaling voor Klanten Groep' ) ;
define('HEADING_TITLE_MODULES_SHIPPING',    'Actieve verzending voor Klanten Groep' ) ;
define('HEADING_TITLE_MODULES_ORDER_TOTAL', 'Actieve Order Totaal voor Klanten Groep' ) ;

define('ENTRY_GROUPS_NAME', 'Group&#160;Naam:');
define('ENTRY_DEFAULT_DISCOUNT', 'KortingPercentage') ; // totalb2b
define('ENTRY_GROUP_NAME_MAX_LENGTH', '&#160;&#160;Maximale lengte: 32 characters');
define('ENTRY_GROUP_SHOW_TAX', 'Laat&#160;prijzen&#160;met/zonder&#160;BTW:');
define('ENTRY_GROUP_SHOW_TAX_EXPLAIN_1', '&#160;&#160;Deze instelling werkt alleen als \'Vertoon Prijzen met BTW\'');
define('ENTRY_GROUP_SHOW_TAX_EXPLAIN_2', 'is ingesteld op true in de Configuratie van de winkel en BTW uitsluiting (zie onder) op \'No\'.');
define('ENTRY_GROUP_SHOW_TAX_YES', 'Vertoon prijzen met BTW');
define('ENTRY_GROUP_SHOW_TAX_NO', 'Vertoon prijzen zonder BTW');

define('ENTRY_GROUP_TAX_EXEMPT', 'BTW uitsluiting:'); 
define('ENTRY_GROUP_TAX_EXEMPT_YES', 'Ja'); 
define('ENTRY_GROUP_TAX_EXEMPT_NO', 'Nee'); 

define('ENTRY_GROUP_PAYMENT_SET', 'Betalings Methode voor de klanten groep');
define('ENTRY_GROUP_PAYMENT_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_PAYMENT_SET_EXPLAIN', 'Als U de <b><i>' . ENTRY_GROUP_PAYMENT_SET . '</i></b> activeert maar geen Betalingsmethode activeert, worden de standaard Betalingsmethodes gebruikt.');

define('ENTRY_GROUP_SHIPPING_SET', 'Verzend Methodes voor deze Klanten Groep');
define('ENTRY_GROUP_SHIPPING_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_SHIPPING_SET_EXPLAIN', 'Als U <b><i>'. ENTRY_GROUP_SHIPPING_SET. '</i></b> activeert  maar geen Verzendings methode activeert, worden de standaard Verzendings methodes gebruikt.');

define('ENTRY_GROUP_ORDER_TOTAL_SET', 'Order totaal modules voor de Klanten Groep');
define('ENTRY_GROUP_ORDER_TOTAL_DEFAULT', 'Gebruik instelling van de Configuratie');
define('ENTRY_ORDER_TOTAL_SET_EXPLAIN', 'Als U de  <b><i>' . ENTRY_GROUP_ORDER_TOTAL_SET. '</i></b> activeert  maar geen Order Totaal Modules activeert, worden de standaard Order Totaal Modules gebruikt..');

define('TEXT_DELETE_INTRO', 'Wilt U deze groep verwijderen?');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_GROUPS', 'Vertoon <b>%d</b> tot <b>%d</b> (van <b>%d</b> Klanten Groep)');
define('TEXT_INFO_HEADING_DELETE_GROUP', 'Verwijder Groep');

define('ERROR_CUSTOMERS_GROUP_NAME', 'Voer een Klanten Groep Naam in');

define('HEADING_TITLE_GROUP_TAX_RATES_EXEMPT', 'Klanten Groep Uitsluiting van Bepaalde BTW groepen');
define('ENTRY_GROUP_TAX_RATES_EXEMPT', 'Maak geen gebruik BTW percentage van de Klanten Groep');
define('ENTRY_GROUP_TAX_RATES_DEFAULT', 'Gebruik instelling van de Configuratie(zone based)');
define('ENTRY_TAX_RATES_EXEMPT_EXPLAIN', 'Als U de <b><i>' . ENTRY_GROUP_TAX_RATES_EXEMPT . '</i></b> activeert  maar geen Order Totaal Modules activeert, worden de standaard BTW percentages (zone based) gebruikt.<br />
                                          Als U voor deze groep BTW uitsluiting op ' . ENTRY_GROUP_TAX_EXEMPT_YES . ' heeft ingesteld , worden geen van bovenstaande instellingen actief.');										  
?>