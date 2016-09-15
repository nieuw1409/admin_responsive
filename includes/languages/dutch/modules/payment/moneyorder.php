<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Vooruitbetalen Per Bank of Giro');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Betaal aan:&nbsp;' . MODULE_PAYMENT_MONEYORDER_PAYTO . '<br /><br /><br />' . nl2br(STORE_NAME_ADDRESS) . '<br /><br />' . 'Uw bestelling wordt pas verzonden als uw betaling ontvangen is.');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Betaal aan: ". MODULE_PAYMENT_MONEYORDER_PAYTO . "\n\n\n" . STORE_NAME_ADDRESS . "\n\n" . 'Uw bestelling wordt pas verzonden als uw betaling ontvangen is.');
  
  define('TITLE_MODULE_PAYMENT_MONEYORDER_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_PAYMENT_MONEYORDER_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_PAYMENT_MONEYORDER_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_PAYMENT_MONEYORDER_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_PAYMENT_MONEYORDER_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_PAYMENT_MONEYORDER_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ; 
  
  define('TITLE_MODULE_PAYMENT_MONEYORDER_ZONE', 'Betalings Zone' ) ; // Payment Zone
  define('DESCRIPTION_MODULE_PAYMENT_MONEYORDER_ZONE', 'Selecteer een zone, de betalingsmethode is dan alleen geldig voor deze zone. <br />
                                                 Selecteerd u geen zone dan zal deze betalingsmethode actief zijn in alle zones' ) ;    // If a zone is selected, only enable this payment method for that zone.

  define('TITLE_MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', 'Order Status BetalingsMethode' ) ; // Set Order Status
  define('DESCRIPTION_MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', 'Standaard Order Status als deze betalingsmethode wordt gekozen ' ) ;    // Set the status of orders made with this payment module to this value

  define('TITLE_MODULE_PAYMENT_MONEYORDER_USE_PAYMENTS', 'Verzendmethode van deze Betaligs Methode' ) ; // Use Payment in combination with Shipping.
  define('DESCRIPTION_MODULE_PAYMENT_MONEYORDER_USE_PAYMENTS', 'Selecteer bij welke verzendmethode deze betalings mogelijkheid actief is' ) ;    // select shipping method to be used with this payment option.

  define('TITLE_MODULE_PAYMENT_MONEYORDER_PAYTO', 'Betalen Aan' ) ; // Make Payable to:
  define('DESCRIPTION_MODULE_PAYMENT_MONEYORDER_PAYTO', 'Geef hier de naam van uw Webshop of de naam van het RekeningNummer bij de bank' ) ;    // Who should payments be made payable to?
  
//      return array('MODULE_PAYMENT_MONEYORDER_STATUS', 'MODULE_PAYMENT_MONEYORDER_ZONE', 'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', 'MODULE_PAYMENT_MONEYORDER_SORT_ORDER',
//     'MODULE_PAYMENT_MONEYORDER_PAYTO', 'MODULE_PAYMENT_MONEYORDER_USE_PAYMENTS');

?>
