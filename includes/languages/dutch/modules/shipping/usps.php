<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_SHIPPING_USPS_TEXT_TITLE', 'United States Postal Service');
define('MODULE_SHIPPING_USPS_TEXT_DESCRIPTION', 'United States Postal Service<br /><br />U moet beschikken over een account bij USPS op http://www.uspsprioritymail.com/et_regcert.html om deze module te kunnen gebruiken<br /><br />USPS verwacht dat u pond als maateenheid van uw artikelen gebruikt.');
define('MODULE_SHIPPING_USPS_TEXT_OPT_PP', 'Parcel Post');
define('MODULE_SHIPPING_USPS_TEXT_OPT_PM', 'Priority Mail');
define('MODULE_SHIPPING_USPS_TEXT_OPT_EX', 'Express Mail');
define('MODULE_SHIPPING_USPS_TEXT_ERROR', 'Er is een fout opgetreden tijdens de berekeningen van de USPS verzending.<br />Als u USPS als uw verzendingsmethode wenst te gebruiken, gelieve de eigenaar te contacteren.');


  define('TITLE_MODULE_SHIPPING_USPS_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_SHIPPING_USPS_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_USPS_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_SHIPPING_USPS_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_SHIPPING_USPS_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_USPS_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    
  
  define('TITLE_MODULE_SHIPPING_USPS_USERID', 'ID Nummer voor USPS ' ) ; // Enter the USPS User ID
  define('DESCRIPTION_MODULE_SHIPPING_USPS_USERID', 'Het ID Nummer voor USPS ' ) ;    // Enter the USPS USERID assigned to you.

  define('TITLE_MODULE_SHIPPING_USPS_USERID', 'WachtWoord voor USPS ' ) ; // Enter the USPS Password
  define('DESCRIPTION_MODULE_SHIPPING_USPS_USERID', 'Het Wachtwoord voor het gebruik van USPS ' ) ;    // ee USERID, above.

  define('TITLE_MODULE_SHIPPING_USPS_HANDLING', 'BehandelingsKosten ' . MODULE_SHIPPING_USPS_TEXT_TITLE ) ; // Handling Fee
  define('DESCRIPTION_MODULE_SHIPPING_USPS_HANDLING', 'De BehandelingsKosten van deze verzendmethode' ) ;    // Handling fee for this shipping method.
  
  define('TITLE_MODULE_SHIPPING_USPS_ZONE', 'Verzendigs Zone' ) ; // Shipping Zone
  define('DESCRIPTION_MODULE_SHIPPING_USPS_ZONE', 'Selecteer een zone, Verzenden is dan alleen geldig voor deze zone. <br />
                                                 Selecteerd u geen zone dan zal deze Verzend methode actief zijn in alle zones' ) ;    // If a zone is selected, only enable this payment method for that zone.

  define('TITLE_MODULE_SHIPPING_USPS_TAX_CLASS', 'BTW tarief' ) ; // Tax Class
  define('DESCRIPTION_MODULE_SHIPPING_USPS_TAX_CLASS', 'Het BTW tarief voor de verzendmethode ' . MODULE_SHIPPING_USPS_TEXT_TITLE ) ;    // 	Use the following tax class on the shipping fee.
 
//      return array('MODULE_SHIPPING_USPS_STATUS', 'MODULE_SHIPPING_USPS_USERID', 'MODULE_SHIPPING_USPS_PASSWORD', 'MODULE_SHIPPING_USPS_HANDLING', 'MODULE_SHIPPING_USPS_TAX_CLASS', 'MODULE_SHIPPING_USPS_ZONE', 'MODULE_SHIPPING_USPS_SORT_ORDER');
 
?>
