<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Ideal Lite Payment module for oscommerce 2.3.1
 *
 *
 * PHP version 5
 */
  define('MODULE_PAYMENT_IDEAL_LITE_TEXT_TITLE', 'Ideal Lite');
  define('MODULE_PAYMENT_IDEAL_LITE_TEXT_PUBLIC_TITLE','Ideal Lite');
  define('MODULE_PAYMENT_IDEAL_LITE_TEXT_DESCRIPTION', 'Ideal Lite');
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_PAYMENT_IDEAL_LITE_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_PAYMENT_IDEAL_LITE_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ; 
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_MODE', 'iDEAL Status Modus' ) ; // iDEAL Status Mode
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_MODE', 'Status modus voor IDEAL betalingen? (test or prod)' ) ;    // Status mode for IDEAL payments? (test or prod)
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_PSPID', 'iDeal Klant ID nummer' ) ; // iDEAL Merchant ID
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_PSPID', 'iDeal Klant ID nummer' ) ;    // Merchant ID
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_SHA_STRING', 'iDEAL SHA Aanmelding') ; // iDEAL SHA String
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_SHA_STRING', 'iDEAL SHA Aanmelding ( zie klant administratie pagina' ) ;    // SHA string used for the signature (set at the merchant administration page)
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_URL', 'iDEAL Website Adres' ) ; // iDEAL URL
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_URL', 'Het Website Adres van iDeal ( bij voorkeur https:// )' ) ;    // Bank url
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_LANGUAGE', 'iDeal Taal Klant' ) ; // iDEAL Client Language
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_LANGUAGE', 'De taal van de klant tijdens de iDela overboeking' ) ;    // Client language
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_MIN_AMOUNT', 'iDEAL Minimale Bedrag' ) ; // iDEAL Min amount
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_MIN_AMOUNT', 'Het minimale order bedrag waarbij de klant gebruik kan maken van iDeal ' ) ;    // The minimum amount to make the iDEAL payment method available
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_ORDER_STATUS_ID', 'Order Status BetalingsMethode' ) ; // Set Order Status
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_ORDER_STATUS_ID', 'Standaard Order Status als deze betalingsmethode wordt gekozen ' ) ;    // Set the status of orders made with this payment module to this value
  
  define('TITLE_MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS', 'Verzendmethode van deze Betaligs Methode' ) ; // Use Payment in combination with Shipping.
  define('DESCRIPTION_MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS', 'Selecteer bij welke verzendmethode deze betalings mogelijkheid actief is' ) ;    // select shipping method to be used with this payment option.
  
  
//       return array('MODULE_PAYMENT_IDEAL_LITE_STATUS'
//                //                , 'MODULE_PAYMENT_IDEAL_LITE_SORT_ORDER'
//                , 'MODULE_PAYMENT_IDEAL_LITE_MODE'
//                , 'MODULE_PAYMENT_IDEAL_LITE_PSPID'
//                , 'MODULE_PAYMENT_IDEAL_LITE_SHA_STRING'
//                , 'MODULE_PAYMENT_IDEAL_LITE_URL'
//                , 'MODULE_PAYMENT_IDEAL_LITE_LANGUAGE'
//                , 'MODULE_PAYMENT_IDEAL_LITE_MIN_AMOUNT'
 //               , 'MODULE_PAYMENT_IDEAL_LITE_ORDER_STATUS_ID'
//				, 'MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS');
?>
