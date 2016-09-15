<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_SHIPPING_ITEM_TEXT_TITLE', 'Per Item');
define('MODULE_SHIPPING_ITEM_TEXT_DESCRIPTION', 'Per Item');
define('MODULE_SHIPPING_ITEM_TEXT_WAY', 'Tarief');


  define('TITLE_MODULE_SHIPPING_ITEM_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_SHIPPING_ITEM_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_ITEM_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_SHIPPING_ITEM_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_SHIPPING_ITEM_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_ITEM_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    
  
  define('TITLE_MODULE_SHIPPING_ITEM_COST', 'Kosten ' . MODULE_SHIPPING_ITEM_TEXT_TITLE ) ; // Shipping Cost
  define('DESCRIPTION_MODULE_SHIPPING_ITEM_COST', 'De VerzendKosten worden vermenigvuldigd met het aantal producten per order' ) ;    // The shipping cost will be multiplied by the number of items in an order that uses this shipping method.

  define('TITLE_MODULE_SHIPPING_ITEM_HANDLING', 'BehandelingsKosten ' . MODULE_SHIPPING_ITEM_TEXT_TITLE ) ; // Handling Fee
  define('DESCRIPTION_MODULE_SHIPPING_ITEM_HANDLING', 'De BehandelingsKosten van deze verzendmethode' ) ;    // Handling fee for this shipping method.
  
  define('TITLE_MODULE_SHIPPING_ITEM_ZONE', 'Verzendigs Zone' ) ; // Shipping Zone
  define('DESCRIPTION_MODULE_SHIPPING_ITEM_ZONE', 'Selecteer een zone, Verzenden is dan alleen geldig voor deze zone. <br />
                                                 Selecteerd u geen zone dan zal deze Verzend methode actief zijn in alle zones' ) ;    // If a zone is selected, only enable this payment method for that zone.

  define('TITLE_MODULE_SHIPPING_ITEM_TAX_CLASS', 'BTW tarief' ) ; // Tax Class
  define('DESCRIPTION_MODULE_SHIPPING_ITEM_TAX_CLASS', 'Het BTW tarief voor de verzendmethode ' . MODULE_SHIPPING_ITEM_TEXT_TITLE ) ;    // 	Use the following tax class on the shipping fee.
  
//      return array('MODULE_SHIPPING_ITEM_STATUS', 'MODULE_SHIPPING_ITEM_COST', 'MODULE_SHIPPING_ITEM_HANDLING', 'MODULE_SHIPPING_ITEM_TAX_CLASS', 'MODULE_SHIPPING_ITEM_ZONE', 'MODULE_SHIPPING_ITEM_SORT_ORDER');
    
?>
