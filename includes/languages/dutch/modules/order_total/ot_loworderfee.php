<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Dutch translation for v2.3.1 by dfirefire
  http://www.dfirefire.be
  
  Copyright (c) 2003 osCommerce

  Released under de GNU General Public License
*/

  define('MODULE_ORDER_TOTAL_LOWORDERFEE_TITLE', 'Lage bestelling toeslag');
  define('MODULE_ORDER_TOTAL_LOWORDERFEE_DESCRIPTION', 'Toeslag bij een laag bestellingstotaal');
  
  
  define('TITLE_MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_ORDER_TOTAL_LOWORDERFEE_TITLE ) ;
  define('DESCRIPTION_MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_ORDER_TOTAL_LOWORDERFEE_TITLE ) ;
  define('DESCRIPTION_MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;
  
  
  define('TITLE_MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'Activeer Lage Order Toeslag' ) ; // Allow Low Order Fee
  define('DESCRIPTION_MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'Activeer Lage Order Toeslag' ) ;    // Do you want to allow low order fees?
  
  define('TITLE_MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', 'Prijs voor Orders Onder' ) ; // Order Fee For Orders Under
  define('DESCRIPTION_MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', 'Het totaal van de order waarbij de lage order toeslag moet worden berekend' ) ;    // Add the low order fee to orders under this amount.
  
  define('TITLE_MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', 'Lage Order Toeslag' ) ; // Order Fee
  define('DESCRIPTION_MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', 'De prijs van de toeslag indien een order lager is dan bovenstaande order bedrag' ) ;    // Low order fee.
  
  define('TITLE_MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'Toeslag berekenen bij Orders Naar' ) ; // Attach Low Order Fee On Orders Made
  define('DESCRIPTION_MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'De Toeslag berekenen bij Orders Naar<br />
                                                                     national = binnen nederland<br />
                                                                     international = buiten nederland<br />
                                                                     both = beide nationaal en internationaal<br /> ' ) ;    // Attach low order fee for orders sent to the set destination.
																	 
  define('TITLE_MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS', 'BTW code' ) ; // Tax Class
  define('DESCRIPTION_MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS', 'Gebruik het BTW percentage voor Lage Order Bedrag ' )  ;    // Use the following tax class for low order fee.
?>