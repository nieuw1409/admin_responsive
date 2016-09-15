<?php
/* $Id$ 
osCommerce, Open Source E-Commerce Solutions 
http://www.oscommerce.com 
Copyright (c) 2002 osCommerce 

Released under the GNU General Public License 
xsell.php
Original Idea From Isaac Mualem im@imwebdesigning.com <mailto:im@imwebdesigning.com> 
Complete Recoding From Stephen Walker admin@snjcomputers.com
*/ 

define('CROSS_SELL_SUCCESS', 'Cross Sell Gegevens Successvol opgeslagen voor Cross Sell Product #'.$_GET['add_related_product_ID']);
define('SORT_CROSS_SELL_SUCCESS', 'Sorteer Order Successvol opgeslagen voor Cross Sell Product #'.$_GET['add_related_product_ID']);
define('HEADING_TITLE', 'Gerelateerde Produkten (X-Sell) Admin');
define('TABLE_HEADING_PRODUCT_ID', 'Product Id');
define('TABLE_HEADING_PRODUCT_MODEL', 'Product Model');
define('TABLE_HEADING_PRODUCT_NAME', 'Product Naam');
define('TABLE_HEADING_CURRENT_SELLS', 'Huidige Gerelateerde Produkt');
define('TABLE_HEADING_UPDATE_SELLS', 'Bijwerken Gerelateerde Produkt');
define('TABLE_HEADING_PRODUCT_IMAGE', 'Product Afbeelding');
define('TABLE_HEADING_PRODUCT_PRICE', 'Product Prijs');
define('TABLE_HEADING_RECIPROCAL_LINK', 'Wederkerige Link');
define('TABLE_HEADING_CROSS_SELL_THIS', 'Gerelateerde Produkten ?');
define('TEXT_EDIT_SELLS', 'Wijzig');
define('TEXT_SORT', 'Prioriteit');
define('TEXT_SETTING_SELLS', 'Instelling Gerelateerde Produkten voor');
define('TEXT_PRODUCT_ID', 'Product Id');
define('TEXT_MODEL', 'Model');
define('TABLE_HEADING_PRODUCT_SORT', 'Sorteer Order');
define('TEXT_NO_IMAGE', 'Geen afbeelding');
define('TEXT_CROSS_SELL', 'Gerelateerde Produkten');
define('TEXT_SEARCH_MODEL', 'Zoek en Filter:');
define('TEXT_RECIPROCAL_LINK', 'Voeg Wederkerige link toe ?');
// version 2_7_3
define('TEXT_ADD_XSELLS', 'Voeg een gerelateerd produkt toe' ) ;
define('TEXT_REMOVE_XSELLS', 'Verwijder gerelateerde produkt ?' ) ;
define('TEXT_EDIT_XSELLS', 'Wijzig de gerelateerde produkten' ) ;

define('TEXT_EDITTING_SELLS', 'Gerelateerde Produkten voor : ');

define('HEADING_TITLE_NEW_XSELL', 'Voeg een Gerelateerde Produkt toe' ) ;
define('HEADING_TITLE_EDIT_XSELL', 'Instelling Gerelateerde Produkten ');
define('TEXT_SEARCH_IN_RESULTS', 'Zoeken Produkten' ) ; // xsell
define('TEXT_FILTER_XSELL', 'Filter Produkten' ) ; // xsell
define('TEXT_CATEGORY_XSELL', 'Zoeken Categorie' ) ; // xsell
define('TEXT_SEARCH_XSELL', 'Zoeken Produkten' ) ; // xsell
define('TEXT_ALL_PRODUCTS', 'Alle Produkten' ) ; // xsell
define('TEXT_PRODUCTS_WITHOUT', 'Zonder Gerelateerde Produkten' ) ; // xsell
define('TEXT_PRODUCTS_WITH', 'Met Gerelateerde Produkten' ) ; // xsell
define('TEXT_SEARCH_IN_RESULT', 'Zoeken Produkten' ) ; // xsell
define('TEXT_NO_RESULTS_FOUND', 'GEEN PRODUCTEN AANWEZIG' ) ;// XSELL

define('TABLE_HEADING_ACTION', 'Actie');
define('TEXT_INFO_HEADING_NEW_XSELL_PRODUCT' , 'Toevoegen Gerelateerd Product' ) ;
define('TEXT_INFO_HEADING_EDIT_XSELL_PRODUCT', 'Gerelateerde Producten van' ) ;
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze gerelateerde product wilt verwijderen?');
define('TEXT_INFO_HEADING_DELETE_CROSS_SELL', 'Verwijder gerelateerde Product' ) ;
?>