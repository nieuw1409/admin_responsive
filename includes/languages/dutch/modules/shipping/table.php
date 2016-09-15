<?php
/*
  $Id: table.php,v 1.5 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_SHIPPING_TABLE_TEXT_TITLE', 'Verzendig Per Post');
define('MODULE_SHIPPING_TABLE_TEXT_DESCRIPTION', 'Verzending per Post');
define('MODULE_SHIPPING_TABLE_TEXT_WAY', 'Verzendig per Post');
define('MODULE_SHIPPING_TABLE_TEXT_WEIGHT', 'Gewicht');
define('MODULE_SHIPPING_TABLE_TEXT_AMOUNT', 'Aantal');


  define('TITLE_MODULE_SHIPPING_TABLE_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_SHIPPING_TABLE_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_TABLE_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_SHIPPING_TABLE_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_SHIPPING_TABLE_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_TABLE_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    
  
  define('TITLE_MODULE_SHIPPING_TABLE_COST', 'Verzendkosten ' . MODULE_SHIPPING_TABLE_TEXT_TITLE ) ; // Shipping Table
  define('DESCRIPTION_MODULE_SHIPPING_TABLE_COST', 'De verzendkosten worden berekend op basis van de Totale OrderTotaal of Totale Gewicht<br />
                                                    Bijvoorbeeld 25:8.50,50:5.50,etc. <br />
                                                    Tot 25 bereken 8.5 tot 50 bereken 5.5 etc.' ) ;    //The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc

  define('TITLE_MODULE_SHIPPING_TABLE_MODE', 'Methode Berekening Kosten ' ) ; // Table Method
  define('DESCRIPTION_MODULE_SHIPPING_TABLE_MODE', 'De kosten worden berekend over het Totale :<br />
                                                    weight = gewicht<br />
                                                    price  = ordertotaal ') ;    // The shipping cost is based on the order total or the total weight of the items ordered.

  define('TITLE_MODULE_SHIPPING_TABLE_HANDLING', 'BehandelingsKosten ' . MODULE_SHIPPING_TABLE_TEXT_TITLE ) ; // Handling Fee
  define('DESCRIPTION_MODULE_SHIPPING_TABLE_HANDLING', 'De BehandelingsKosten van deze verzendmethode' ) ;    // Handling fee for this shipping method.
  
  define('TITLE_MODULE_SHIPPING_TABLE_ZONE', 'Verzendigs Zone' ) ; // Shipping Zone
  define('DESCRIPTION_MODULE_SHIPPING_TABLE_ZONE', 'Selecteer een zone, Verzenden is dan alleen geldig voor deze zone. <br />
                                                 Selecteerd u geen zone dan zal deze Verzend methode actief zijn in alle zones' ) ;    // If a zone is selected, only enable this payment method for that zone.

  define('TITLE_MODULE_SHIPPING_TABLE_TAX_CLASS', 'BTW tarief' ) ; // Tax Class
  define('DESCRIPTION_MODULE_SHIPPING_TABLE_TAX_CLASS', 'Het BTW tarief voor de verzendmethode ' . MODULE_SHIPPING_TABLE_TEXT_TITLE ) ;    // 	Use the following tax class on the shipping fee.
  
//       return array('MODULE_SHIPPING_TABLE_STATUS', 'MODULE_SHIPPING_TABLE_COST', 'MODULE_SHIPPING_TABLE_MODE', 'MODULE_SHIPPING_TABLE_HANDLING', 'MODULE_SHIPPING_TABLE_TAX_CLASS', 'MODULE_SHIPPING_TABLE_ZONE', 'MODULE_SHIPPING_TABLE_SORT_ORDER');


?>