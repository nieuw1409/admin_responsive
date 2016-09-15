<?php
/*
  $Id: ot_cod_fee.php,v 1.00 2002/11/30 16:56:00 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_ORDER_TOTAL_COD_TITLE', 'Onder Rembours Tarief');
  define('MODULE_ORDER_TOTAL_COD_DESCRIPTION', 'Onder Rembours Tarief');
  
  define('TITLE_MODULE_ORDER_TOTAL_COD_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_ORDER_TOTAL_COD_TITLE ) ;
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_ORDER_TOTAL_COD_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_ORDER_TOTAL_COD_TITLE ) ;
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;  

  define( 'COD_DESCRIPTION' , 'LandCode:COD prijs, etc.<br />
                                                           Landcode 00 is van toepassing op alle leveringen, deze code moet als laatste zijn ingevoerd<br />
														   Als er geen code voor buitenland is ingevoerd ( = 00:9.99)  dan zijn buitenlandse leveringen niet mogelijk ' ) ;
//							<Country code>:<COD price>, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)
														   
  define('TITLE_MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'COD Prijs voor FLAT' ) ; // COD Fee for FLAT
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'FLAT: ' . COD_DESCRIPTION )  ;    // FLAT: 
  
  define('TITLE_MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'COD Prijs voor ITEM' ) ; // COD Fee for ITEM
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'ITEM: ' . COD_DESCRIPTION )  ;    // ITEM: 
  
  define('TITLE_MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'COD Prijs voor TABLE' ) ; // COD Fee for TABLE
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'TABLE: ' . COD_DESCRIPTION )  ;    // TABLE: 
  
  define('TITLE_MODULE_ORDER_TOTAL_COD_FEE_UPS', 'COD Prijs voor UPS' ) ; // COD Fee for UPS
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_FEE_UPS', 'UPS: ' . COD_DESCRIPTION )  ;    // USPS: 
  
  define('TITLE_MODULE_ORDER_TOTAL_COD_FEE_ZONES', 'COD Prijs voor ZONES' ) ; // COD Fee for ZONES
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_FEE_ZONES', 'ZONES: ' . COD_DESCRIPTION )  ;    // ZONES: 
  
  define('TITLE_MODULE_ORDER_TOTAL_COD_FEE_DHL', 'COD Prijs voor DHL' ) ; // COD Fee for DHL
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_FEE_DHL', 'DHL: ' . COD_DESCRIPTION )  ;    // DHL: 

   define('TITLE_MODULE_ORDER_TOTAL_COD_TAX_CLASS', 'BTW code' ) ; // Tax Class
  define('DESCRIPTION_MODULE_ORDER_TOTAL_COD_TAX_CLASS', 'Gebruik het BTW percentage voor onder Rembours Levering ' )  ;    // Use the following tax class on the COD fee.
  

?>