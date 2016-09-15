<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_SHIPPING_FLAT_TEXT_TITLE', 'Vast tarief');
define('MODULE_SHIPPING_FLAT_TEXT_DESCRIPTION', 'Vast tarief');
define('MODULE_SHIPPING_FLAT_TEXT_WAY', 'Tarief');

  define('TITLE_MODULE_SHIPPING_FLAT_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_SHIPPING_FLAT_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_FLAT_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_SHIPPING_FLAT_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_SHIPPING_FLAT_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_FLAT_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    
  
  define('TITLE_MODULE_SHIPPING_FLAT_COST', 'Kosten ' . MODULE_SHIPPING_FLAT_TEXT_TITLE ) ; // Shipping Cost
  define('DESCRIPTION_MODULE_SHIPPING_FLAT_COST', 'De VerzendKosten voor ' . MODULE_SHIPPING_FLAT_TEXT_TITLE ) ;    // The shipping cost for all orders using this shipping method.
  
  define('TITLE_MODULE_SHIPPING_FLAT_ZONE', 'Verzendigs Zone' ) ; // Shipping Zone
  define('DESCRIPTION_MODULE_SHIPPING_FLAT_ZONE', 'Selecteer een zone, Verzenden is dan alleen geldig voor deze zone. <br />
                                                 Selecteerd u geen zone dan zal deze Verzend methode actief zijn in alle zones' ) ;    // If a zone is selected, only enable this payment method for that zone.

  define('TITLE_MODULE_SHIPPING_FLAT_TAX_CLASS', 'BTW tarief' ) ; // Tax Class
  define('DESCRIPTION_MODULE_SHIPPING_FLAT_TAX_CLASS', 'Het BTW tarief voor de verzendmethode ' . MODULE_SHIPPING_FLAT_TEXT_TITLE ) ;    // 	Use the following tax class on the shipping fee.
												 
//       return array('MODULE_SHIPPING_FLAT_STATUS', 'MODULE_SHIPPING_FLAT_COST', 'MODULE_SHIPPING_FLAT_TAX_CLASS', 'MODULE_SHIPPING_FLAT_ZONE', 'MODULE_SHIPPING_FLAT_SORT_ORDER');
												 
?>
