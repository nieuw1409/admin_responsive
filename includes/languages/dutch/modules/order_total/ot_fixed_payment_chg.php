<?php
/*
  $Id: ot_fixed_payment_chg.php,v 1.1 2002/08/30 19:28:33 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_FIXED_PAYMENT_CHG_TITLE', 'De kosten van Betaling Bij Afleveren');
  define('MODULE_FIXED_PAYMENT_CHG_DESCRIPTION', 'De kosten van Betaling Bij Afleveren');
  
  define('TITLE_MODULE_FIXED_PAYMENT_CHG_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_FIXED_PAYMENT_CHG_TITLE ) ;
  define('DESCRIPTION_MODULE_FIXED_PAYMENT_CHG_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_FIXED_PAYMENT_CHG_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_FIXED_PAYMENT_CHG_TITLE ) ;
  define('DESCRIPTION_MODULE_FIXED_PAYMENT_CHG_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ; 
  
  define('TITLE_MODULE_FIXED_PAYMENT_CHG_TYPE', 'Prijs van de BetalingsMethode' ) ; // Fee for payment type
  define('DESCRIPTION_MODULE_FIXED_PAYMENT_CHG_TYPE', 'Prijs van de BetalingsMethode voer de verschillende prijzen voor de betaalmethodes als volgt in<br />
                                                       [cod:xx:0.yy,paypal_ipn:xx:0.yy] ' )  ;    // Payment methods with minimal fee (any) and normal fee (0 to 1, 1 is 100%) all splitted by colons, enter like this: [cod:xx:0.yy,paypal_ipn:xx:0.yy]
  
  define('TITLE_MODULE_FIXED_PAYMENT_CHG_TAX_CLASS', 'BTW code' ) ; // Tax Class
  define('DESCRIPTION_MODULE_FIXED_PAYMENT_CHG_TAX_CLASS', 'Gebruik het BTW percentage voor onder Rembours Levering ' )  ;    // Use the following tax class .
  
  
?>