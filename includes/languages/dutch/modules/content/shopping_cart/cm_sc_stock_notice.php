<?php
/*
  $Id$
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2016 osCommerce
 
  Released under the GNU General Public License
*/
  
  define('MODULE_CONTENT_SC_STOCK_NOTICE_TITLE', 'Tekst Geen Voorraad Product');
  define('MODULE_CONTENT_SC_STOCK_NOTICE_DESCRIPTION', 'Plaats de Tekst Geen Voorraad Product in de Winkelwagen box op het scherm in de Winkelwagen pagina');
  
  define('OUT_OF_STOCK_CANT_CHECKOUT', 'Artikelen gemarkeerd met ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' zijn niet voorradig in de opgegeven hoeveelheid.<br />Gelieve de hoeveelheid aan te passen van de artikelen gemarkeerd met (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), dank u');
  define('OUT_OF_STOCK_CAN_CHECKOUT', 'Artikelen gemarkeerd met ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' zijn niet voorradig in de opgegeven hoeveelheid.<br />U kan ze toch aankopen en kijken hoeveel er onmiddellijk geleverd kunnen worden tijdens het afrekenen.');

  define('TITLE_MODULE_CONTENT_SC_STOCK_NOTICE_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_CONTENT_SC_STOCK_NOTICE_TITLE ) ;
  define('DESCRIPTION_MODULE_CONTENT_SC_STOCK_NOTICE_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_CONTENT_SC_STOCK_NOTICE_CONTENT_WIDTH', _MODULES_SORT_ORDER . MODULE_CONTENT_SC_STOCK_NOTICE_TITLE ) ;
  define('DESCRIPTION_MODULE_CONTENT_SC_STOCK_NOTICE_CONTENT_WIDTH', _MODULES_SORT_ORDER_DESCRIPTION ) ;    

  define('TITLE_MODULE_CONTENT_SC_STOCK_NOTICE_SORT_ORDER', _MODULES_QNT_COLUMN_BOOTSTRAP ) ;
  define('DESCRIPTION_MODULE_CONTENT_SC_STOCK_NOTICE_SORT_ORDER', _MODULES_QNT_COLUMN_BOOTSTRAP_DESCRIPTION ) ;        

?>