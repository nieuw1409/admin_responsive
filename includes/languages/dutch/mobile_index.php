<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', 'This is a default setup of osCommerce Online Merchant. Products shown are for demonstrational purposes. <b>Any products purchased will not be delivered nor will the customer be billed</b>. Any information seen on these products is to be treated as fictional.<br><br><table border="0" width="100%" cellspacing="5" cellpadding="2"><tr><td class="main" valign="top">' . tep_image(DIR_WS_IMAGES . 'default/1.gif') . '</td><td class="main" valign="top"><b>Error Messages</b><br><br>If there are any error or warning messages shown above, please correct them first before proceeding.<br><br>Error messages are displayed at the very top of the page with a complete <span class="messageStackError">background</span> color.<br><br>Several checks are performed to ensure a healthy setup of your online store - these checks can be disabled by editing the appropriate parameters at the bottom of the includes/application_top.php file.</td></tr><td class="main" valign="top">' . tep_image(DIR_WS_IMAGES . 'default/2.gif') . '</td><td class="main" valign="top"><b>Editing Page Texts</b><br><br>The text shown here can be modified in the following file, on each language basis:<br><br><nobr class="messageStackSuccess">[path to catalog]/includes/languages/' . $language . '/' . FILENAME_DEFAULT . '</nobr><br><br>That file can be edited manually, or via the Administration Tool with the <nobr class="messageStackSuccess">Languages->' . ucfirst($language) . '->Define</nobr> or <nobr class="messageStackSuccess">Tools->File Manager</nobr> modules.<br><br>The text is set in the following manner:<br><br><nobr>define(\'TEXT_MAIN\', \'<span class="messageStackSuccess">This is a default setup of the osCommerce project...</span>\');</nobr><br><br>The text highlighted in green may be modified - it is important to keep the define() of the TEXT_MAIN keyword. To remove the text for TEXT_MAIN completely, the following example is used where only two single quote characters exist:<br><br><nobr>define(\'TEXT_MAIN\', \'\');</nobr><br><br>More information concerning the PHP define() function can be read <a href="http://www.php.net/define" target="_blank"><u>here</u></a>.</td></tr><tr><td class="main" valign="top">' . tep_image(DIR_WS_IMAGES . 'default/3.gif') . '</td><td class="main" valign="top"><b>Online Documentation</b><br><br>Online documentation can be read at the <a href="http://www.oscommerce.info" target="_blank"><u>osCommerce Knowledge Base</u></a> site.<br><br>Support is available at the <a href="http://www.oscommerce.com/support" target="_blank"><u>osCommerce Support Site</u></a>.</td></tr></table><br>If you wish to download the solution powering this shop, or if you wish to contribute to the osCommerce project, please visit the <a href="http://www.oscommerce.com" target="_blank"><u>support site of osCommerce</u></a>. This shop is running on <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Nieuwe Producten Voor %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Verwachte Producten');
define('TABLE_HEADING_DATE_EXPECTED', 'Verwachte Datum');

if ( ($category_depth == 'products') || (isset($HTTP_GET_VARS['manufacturers_id'])) ) {
  define('HEADING_TITLE', '');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Model');
  define('TABLE_HEADING_PRODUCTS', 'Product Naam');
  define('TABLE_HEADING_MANUFACTURER', 'Fabrikant');
  define('TABLE_HEADING_QUANTITY', 'Aantal');
  define('TABLE_HEADING_PRICE', 'Prijs');
  define('TABLE_HEADING_WEIGHT', 'Gewicht');
  define('TABLE_HEADING_BUY_NOW', 'Koop Nu');
  define('TEXT_NO_PRODUCTS', 'De lijst van producten in deze categorie is leeg.');
  define('TEXT_NO_PRODUCTS2', 'De lijst van producten van deze fabrikanten is leeg.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Aantal Producten: ');
  define('TEXT_SHOW', '<b>Op Scherm:</b>');
  define('TEXT_BUY', 'Koop 1 \'');
  define('TEXT_NOW', '\' nu');
  define('TEXT_ALL_CATEGORIES', 'Alle Categorieen');
  define('TEXT_ALL_MANUFACTURERS', 'Alle Fabrikanten');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'Nieuwe Producten?');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Categorieen');
}
?>
