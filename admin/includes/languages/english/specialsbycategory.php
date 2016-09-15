<?php
/*
  original: $Id: specialsbycategory.php,v 1.0 2005/04/24 23:41:21 calimeross Exp $
  adapted for Separate Pricing Per Customer (SPPC) 2005/07/20 JanZ (version 1.0)
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/


define('HEADING_TITLE', 'Specials by Category for Separate Pricing Per Customer');
define('TEXT_SELECT_CAT', 'Select Category First');
define('TEXT_SELECT_CUST_GROUP', 'Customer Group');
define('TEXT_SELECT_MAN', 'and/or Manufacturer (optional)');
define('TEXT_ENTER_DISCOUNT','Enter Your Discount');
define('TEXT_PCT_AND','% and');
define('TEXT_BUTTON_SUBMIT', 'Submit');

define('TEXT_INSTRUCT_1','Leaving discount blank will show all products in selected category and/or manufacturer.');
define('TEXT_INSTRUCT_2','Entering 0 discount will remove all specials in selected category and/or manufacturer and customer group.');            
define('TEXT_INSTRUCT_3','Entering a number followed by a <b> % </b> in the Special Price input box<br>will deduct the  percentage from Products Price as the new Special Price');
define('TEXT_INSTRUCT_4','Entering a price followed by an <b> i </b> in the Special Price input box<br>will mean this price is given as <i>inclusive</i> with tax');
define('TEXT_INSTRUCT_5','Group Price is Y when a group price is used, N if the regular Products Price is used');
define('TEXT_INSTRUCT_6', 'Checking Full Price will remove the special price for that product');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');

define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Products<br>Price');
define('TABLE_HEADING_SPECIAL_PRICE', 'Special<br>Price');
define('TABLE_HEADING_GROUP_PRICE', '<acronym title="Yes or No">Group<br>Price</acronym>');
define('TABLE_HEADING_PCT_OFF', '&#160;%&#160;Off&#160;');
define('TABLE_HEADING_FULL_PRICE', 'Full<br>Price');
define('TABLE_HEADING_ACTION', 'Action') ;

define('TEXT_BUTTON_UPDATE', 'Update');
define('TEXT_NO_PRODUCTS_SPEC_BY_CAT', 'No Products in this Selection') ;

define('TEXT_HEADING_SELECT',  'Select Products');
define('TEXT_HEADING_OPTIONS', 'Optionele Keuzes');
?>