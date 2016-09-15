<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_COD_TEXT_TITLE', 'Betaling bij aflevering ( Onder Rembours )');
  define('MODULE_PAYMENT_COD_TEXT_DESCRIPTION', 'Betaling bij aflevering ( Onder Rembours )');
  
  
  define('TITLE_MODULE_PAYMENT_COD_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_PAYMENT_COD_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_PAYMENT_COD_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_PAYMENT_COD_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_PAYMENT_COD_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_PAYMENT_COD_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ; 
  
  define('TITLE_MODULE_PAYMENT_COD_ZONE', 'Betalings Zone' ) ; // Payment Zone
  define('DESCRIPTION_MODULE_PAYMENT_COD_ZONE', 'Selecteer een zone, de betalingsmethode is dan alleen geldig voor deze zone. <br />
                                                 Selecteerd u geen zone dan zal deze betalingsmethode actief zijn in alle zones' ) ;    // If a zone is selected, only enable this payment method for that zone.

  define('TITLE_MODULE_PAYMENT_COD_ORDER_STATUS_ID', 'Order Status BetalingsMethode' ) ; // Set Order Status
  define('DESCRIPTION_MODULE_PAYMENT_COD_ORDER_STATUS_ID', 'Standaard Order Status als deze betalingsmethode wordt gekozen ' ) ;    // Set the status of orders made with this payment module to this value

  define('TITLE_MODULE_PAYMENT_COD_USE_PAYMENTS', 'Verzendmethode van deze Betaligs Methode' ) ; // Use Payment in combination with Shipping.
  define('DESCRIPTION_MODULE_PAYMENT_COD_USE_PAYMENTS', 'Selecteer bij welke verzendmethode deze betalings mogelijkheid actief is' ) ;    // select shipping method to be used with this payment option.
  
  //      return array('MODULE_PAYMENT_COD_STATUS', 'MODULE_PAYMENT_COD_ZONE', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', 'MODULE_PAYMENT_COD_SORT_ORDER','MODULE_PAYMENT_COD_USE_PAYMENTS');

?>