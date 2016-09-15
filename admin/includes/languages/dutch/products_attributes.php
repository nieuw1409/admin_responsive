<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE_OPT', 'Product Opties');
define('HEADING_TITLE_VAL', 'Optie Waardes');
define('HEADING_TITLE_ATRIB', 'Product Attributen');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_PRODUCT', 'Product Naam');
define('TABLE_HEADING_OPT_NAME', 'Optie');
define('TABLE_HEADING_OPT_VALUE', 'Optie Waarde');
define('TABLE_HEADING_OPT_PRICE', 'Waarde Prijs');
define('TABLE_HEADING_OPT_PRICE_PREFIX', 'Prefix');
define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_DOWNLOAD', 'Downloadbare producten:');

define('TABLE_HEADING_GROUP_NAME', 'GroepsNaam');
define('TABLE_HEADING_DELETE', 'Verwijder');
define('TABLE_HEADING_INSERT', 'Toevoegen');

define('TABLE_TEXT_FILENAME', 'Bestandsnaam:');
define('TABLE_TEXT_MAX_DAYS', 'Verloop Dagen:');
define('TABLE_TEXT_MAX_COUNT', 'Maximale aantal downloads:');

define('MAX_ROW_LISTS_OPTIONS', 10);

define('TEXT_WARNING_OF_DELETE', 'Aan deze optie zijn producten en waardes gekoppeld - het is niet veilig deze te verwijderen.');
define('TEXT_OK_TO_DELETE', 'Aan deze optie zijn geen producten en waardes gekoppeld - het is veilig deze te verwijderen.');
define('TEXT_OPTION_ID', 'Optie ID');
define('TEXT_OPTION_NAME', 'Optie Naam');
//BOF - Zappo - Option Types v2 - Add defines for Option Type, Comment, Length, Sort Order
define('TABLE_HEADING_OPT_TYPE', 'Type');
define('TABLE_HEADING_OPT_LENGTH', 'Lengte');
define('TABLE_HEADING_OPT_ORDER', 'Sorteer');
define('TABLE_HEADING_OPT_COMMENT', 'Commentaar');
define('TABLE_HEADING_OPT_STOCK', 'Vooraad');
define('TABLE_HEADING_OPT_HELP', 'LET OP!!: Voor Tekst and Tekstarea \'Option type\' the maximale lengte is 32. De \'Optie Waarde\' is vastgesteld op TEXT met ID 0. Voeg geen \'Optie Waarde\' met nummer 0 toe.'); //PBOR added help for text options
//EOF - Zappo - Option Types v2 - Add defines for Option Type, Comment, Length, Sort Order

define('TABLE_HEADING_HIDDEN',                     'Verborgen');  // BOF Separate Pricing Per Customer
define('TEXT_HIDDEN_FROM_GROUPS',                  'Verborgen voor Groep(en): '); // BOF Separate Pricing Per Customer
define('TEXT_GROUPS_NONE',                         'Geen'); // BOF Separate Pricing Per Customer
// 0: Icons for all groups for which the category or product is hidden, mouse-over the icons to see what group
// 1: Only one icon and only if the category or product is hidden for a group, mouse-over the icon to what groups
define('LAYOUT_HIDE_FROM',                         '0');  // BOF Separate Pricing Per Customer
define('NAME_WINDOW_ATTRIBUTES_GROUPS_POPUP',      'Attribute Groep Prijzen');  // BOF Separate Pricing Per Customer
define('TEXT_GROUP_PRICES',                        'Groep Prijzen');  // BOF Separate Pricing Per Customer
define('TEXT_MOUSE_OVER_GROUP_PRICES',             'Wijzig Klanten Groep voor deze attribute in een pop-up window');  // BOF Separate Pricing Per Customer
define('TABLE_HEADING_TRACK_STOCK',                'Voorraad') ; // qtpro 461
define('TEXT_STOCK_YES',                           'Ja') ; // qtpro 461
define('TEXT_STOCK_NO',                            'Nee') ; // qtpro 461 
define('TEXT_TAB_OPTIONS',                         'Product Opties'); // TABBED ATTRIBUTES
define('TEXT_TAB_VALUES',                          'Optie Waardes'); // TABBED ATTRIBUTES
define('TEXT_TAB_ATTRIBUTES',                      'Product Attributen'); // TABBED ATTRIBUTES
define('TEXT_INFO_HEADING_NEW_OPTION',             'Toevoegen Nieuwe Product Optie') ;
define('TEXT_INFO_HEADING_EDIT_OPTION',            'Wijzigen Product Optie') ;
define('TEXT_STOCK_ACTIVE',                        'Actief');  
define('TEXT_STOCK_NOT_ACTIVE',                    'Niet Actief'); 

define('TEXT_INFO_HEADING_NEW_VALUE',              'Toevoegen Nieuwe Product Optie Waarde') ;
define('TEXT_INFO_HEADING_EDIT_VALUE',             'Wijzigen Product  Optie Waarde') ;

define( 'TEXT_INFO_HEADING_DELETE_OPTION_ATTRIBUTE', 'Verwijderen Product Atribute' ) ;
define( 'TEXT_INFO_DELETE_INTRO',                    'Verwijder Product Atribute ?' ) ;
define('TEXT_INFO_HEADING_NEW_OPTION_ATTRIBUTE',     'Toevoegen Nieuwe Product Option Attribute') ;
?>