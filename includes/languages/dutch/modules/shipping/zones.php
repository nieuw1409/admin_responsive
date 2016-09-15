<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_SHIPPING_ZONES_TEXT_TITLE', 'Zonetarieven');
define('MODULE_SHIPPING_ZONES_TEXT_DESCRIPTION', 'Tarieven gebaseerd op zones');
define('MODULE_SHIPPING_ZONES_TEXT_WAY', 'Verzending naar');
define('MODULE_SHIPPING_ZONES_TEXT_UNITS', 'kg');
define('MODULE_SHIPPING_ZONES_INVALID_ZONE', 'Naar deze zone is geen verzending mogelijk');
define('MODULE_SHIPPING_ZONES_UNDEFINED_RATE', 'Het verzendingstarief kan momenteel niet berekend worden');

  define('TITLE_MODULE_SHIPPING_ZONES_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_SHIPPING_ZONES_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_SHIPPING_ZONES_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_SHIPPING_ZONES_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    
  
  define('TITLE_MODULE_SHIPPING_ZONES_COUNTRIES_1', 'Zone 1 Landen ' ) ; // Zone 1 Countries
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_COUNTRIES_1', 'Voor Zone 1 gelden onderstaande landen. Deze landen moeten als volgt worden ingevoerd US,CA etc. ' ) ;    // Comma separated list of two character ISO country codes that are part of Zone 1.

  define('TITLE_MODULE_SHIPPING_ZONES_COUNTRIES_2', 'Zone 2 Landen ' ) ; // Zone 2 Countries
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_COUNTRIES_2', 'Voor Zone 2 gelden onderstaande landen. Deze landen moeten als volgt worden ingevoerd US,CA etc. ' ) ;    // Comma separated list of two character ISO country codes that are part of Zone 1.

  define('TITLE_MODULE_SHIPPING_ZONES_COUNTRIES_3', 'Zone 3 Landen ' ) ; // Zone 3 Countries
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_COUNTRIES_3', 'Voor Zone 3 gelden onderstaande landen. Deze landen moeten als volgt worden ingevoerd US,CA etc. ' ) ;    // Comma separated list of two character ISO country codes that are part of Zone 1.
  // add more if you have more countries zones
  
  define('TITLE_MODULE_SHIPPING_ZONES_COST_1', 'Kosten Verzending Zone 1 ' ) ; // Zone 1 Shipping Table
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_COST_1', 'De Verzendkosten voor Zone 1 gebaseerd op een groep van maximum order gewicht<br /.>
                                                      Bijvoorbeeld 25:8.50,50:5.50,etc. <br />
                                                      Tot 25 gewicht bereken 8.5 tot 50 gewicht bereken 5.5 etc.' ) ;    // Shipping rates to Zone 1 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations.

  define('TITLE_MODULE_SHIPPING_ZONES_COST_2', 'Kosten Verzending Zone 2 ' ) ; // Zone 2 Shipping Table
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_COST_2', 'De Verzendkosten voor Zone 2 gebaseerd op een groep van maximum order gewicht<br /.>
                                                      Bijvoorbeeld 25:8.50,50:5.50,etc. <br />
                                                      Tot 25 gewicht bereken 8.5 tot 50 gewicht bereken 5.5 etc.' ) ;    // Shipping rates to Zone 1 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations.

  define('TITLE_MODULE_SHIPPING_ZONES_COST_3', 'Kosten Verzending Zone 3 ' ) ; // Zone 3 Shipping Table
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_COST_3', 'De Verzendkosten voor Zone 3 gebaseerd op een groep van maximum order gewicht<br /.>
                                                      Bijvoorbeeld 25:8.50,50:5.50,etc. <br />
                                                      Tot 25 gewicht bereken 8.5 tot 50 gewicht bereken 5.5 etc.' ) ;    // Shipping rates to Zone 1 destinations based on a group of maximum order weights. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations. Example: 3:8.50,7:10.50,... Weights less than or equal to 3 would cost 8.50 for Zone 1 destinations.
  // add more if you have more countries zones
  
  define('TITLE_MODULE_SHIPPING_ZONES_HANDLING_1', 'BehandelingsKosten ' . MODULE_SHIPPING_ZONES_TEXT_TITLE ) ; // Handling Fee
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_HANDLING_1', 'De BehandelingsKosten van deze verzendmethode' ) ;    // Handling fee for this shipping method.
  
  define('TITLE_MODULE_SHIPPING_ZONES_HANDLING_2', 'BehandelingsKosten ' . MODULE_SHIPPING_ZONES_TEXT_TITLE ) ; // Handling Fee
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_HANDLING_2', 'De BehandelingsKosten van deze verzendmethode' ) ;    // Handling fee for this shipping method.
  
  define('TITLE_MODULE_SHIPPING_ZONES_HANDLING_3', 'BehandelingsKosten ' . MODULE_SHIPPING_ZONES_TEXT_TITLE ) ; // Handling Fee
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_HANDLING_3', 'De BehandelingsKosten van deze verzendmethode' ) ;    // Handling fee for this shipping method.
 // add more if you have more countries zones
 
  define('TITLE_MODULE_SHIPPING_ZONES_TAX_CLASS', 'BTW tarief' ) ; // Tax Class
  define('DESCRIPTION_MODULE_SHIPPING_ZONES_TAX_CLASS', 'Het BTW tarief voor de verzendmethode ' . MODULE_SHIPPING_ZONES_TEXT_TITLE ) ;    // 	Use the following tax class on the shipping fee.



//      $keys = array('MODULE_SHIPPING_ZONES_STATUS', 'MODULE_SHIPPING_ZONES_TAX_CLASS', 'MODULE_SHIPPING_ZONES_SORT_ORDER');
//       $keys[] = 'MODULE_SHIPPING_ZONES_COUNTRIES_' . $i;
//        $keys[] = 'MODULE_SHIPPING_ZONES_COST_' . $i;
//        $keys[] = 'MODULE_SHIPPING_ZONES_HANDLING_' . $i;
      
?>
