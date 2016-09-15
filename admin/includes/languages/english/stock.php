<?php
/*
      QT Pro Version 4.0
  
      stock.php language file
  
      Contribution extension to:
        osCommerce, Open Source E-Commerce Solutions
        http://www.oscommerce.com
     
      Copyright (c) 2004 Ralph Day
      Released under the GNU General Public License
  
      Based on prior works released under the GNU General Public License:
        QT Pro prior versions
          Ralph Day, October 2004
          Tom Wojcik aka TomThumb 2004/07/03 based on work by Michael Coffman aka coffman
          FREEZEHELL - 08/11/2003 freezehell@hotmail.com Copyright (c) 2003 IBWO
          Joseph Shain, January 2003
        osCommerce MS2
          Copyright (c) 2003 osCommerce
          
      Modifications made:
        11/2004 - none in this version
  
*******************************************************************************************
  
  
*/
define('PRODUCTS_STOCK',"Products stock");
define('TABLE_TITLE_ATTRIBUTES',"Attributes");

define('TABLE_HEADING_QUANTITY',"In Stock");

define('HEADING_TITLE', 'Stock Attributes');

define('HEADING_TITLE_PRODUCTS',    'Stock Product');
define('HEADING_TITLE_OPTIONS',     'Stock Attributes');

define('TEXT_BUTTON_EDIT_PROD',                      'Change this product' ) ;
define('TEXT_BUTTON_LOW_STOCK',                      'Low Stock Report' ) ;
define('TEXT_BUTTON_GOTO_PROD',                      'Go to this product in' ) ;

define('TEXT_WARNING_PROD_NOT_FOUND',                'Warning! This product does not seem to exist in any category. Your customers can not find it' ) ;

define('TEXT_SHORT_DESCRIPTION_006',                 'Warning: There are ' ) ;
define('TEXT_SHORT_DESCRIPTION_007',                 'sick products in the database. Please visit ' ) ;
define('TEXT_SHORT_DESCRIPTION_008',                 'the QTPro doctor' ) ;

define('TEXT_SHORT_DESCRIPTION_005',                 'Product ID : ' ) ;

define('TEXT_SHORT_DESCRIPTION_010',                 'The database entries for this products stock is messy and the summary stock calculation is wrong. Please take a look at this' ) ;
define('TEXT_SHORT_DESCRIPTION_012',                 'Stock Products' ) ;
define('TEXT_SHORT_DESCRIPTION_020',                 'The summary stock calculation is wrong. Please take a look at this' ) ;
define('TEXT_SHORT_DESCRIPTION_030',                 'The database entries for this products stock is messy. Please take a look at this' ) ;
define('TEXT_SHORT_DESCRIPTION_040',                 'This product is all ok.' ) ;


define('TEXT_INFO_STOCK_DET_010',                    'The stock quantity summary is ok' ) ;
define('TEXT_INFO_STOCK_DET_011',                    'This means that the current summary of this products quantity, which is in the database, is the value we get if we calculates it from scratch right now' ) ;
define('TEXT_INFO_STOCK_DET_012',                    'The Summary stock is: ') ;

define('TEXT_INFO_STOCK_DET_020',                    'The stock quantity summary is NOT ok' ) ;
define('TEXT_INFO_STOCK_DET_021',                    'This means that the current summary of this products quantity, which is in the database, isn\'t the value we get if we calculates it from scratch right now.') ;
define('TEXT_INFO_STOCK_DET_022',                    'The current summary stock is: ' ) ;
define('TEXT_INFO_STOCK_DET_023',                    'If we calculates it we get:') ;

define('TEXT_INFO_STOCK_DET_030',                    'The options stock is ok' ) ;
define('TEXT_INFO_STOCK_DET_032',                    'This means that the database entries for this product looks the way they should. No options are missing in any row. No option exist in any row where it should not.' ) ;
define('TEXT_INFO_STOCK_DET_034',                    'Total number of stock entries this product has:' ) ;
define('TEXT_INFO_STOCK_DET_036',                    'Number of messy entries:' ) ;

define('TEXT_INFO_STOCK_DET_040',                    'The options stock is NOT ok' ) ;
define('TEXT_INFO_STOCK_DET_042',                    'This means that at least one of the database entries for this product is messed up. Either options are missing in rows or options exist in rows they should not' ) ;
define('TEXT_INFO_STOCK_DET_044',                    'Total number of stock entries this product has:' ) ;
define('TEXT_INFO_STOCK_DET_046',                    'Number of messy entries: ' ) ;

define('TEXT_INFO_STOCK_DET_050',                    'These options were missing in row(s):' ) ;
define('TEXT_INFO_STOCK_DET_052',                    'Possible solutions: </span>Delete the corresponding row(s) from the database or stop tracking the stock for that option.' ) ;

define('TEXT_INFO_STOCK_DET_060',                    'These options exists in row(s) although they should not:' ) ;
define('TEXT_INFO_STOCK_DET_062',                    'Possible solutions: </span>Delete the corresponding row(s) from the database or start tracking the stock for that option.' ) ;

define('TEXT_ANY_PROBLEMS_010',                      'Automatic Solutions Avaliable:' ) ;
define('TEXT_ANY_PROBLEMS_020',                      'Amputation (Deletes all messy rows)' ) ;
define('TEXT_ANY_PROBLEMS_030',                      'Set the summary stock to: ' ) ;

?>