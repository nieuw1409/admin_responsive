<?php
/*
  $Id: bm_generic.php $
  $Loc: catalog/includes/languages/english/modules/boxes/ $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2011 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_BOXES_TEXT_TITLE', 'Tekst Box');
  define('MODULE_BOXES_TEXT_DESCRIPTION', 'Plaats de info box met tekst in een kolom.');

  define('TITLE_MODULE_BOXES_TEXT_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_BOXES_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_BOXES_TEXT_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_BOXES_TEXT_CONTENT_PLACEMENT', _MODULES_CONTENT_PLACEMENT_1 . MODULE_BOXES_TEXT_TITLE . _MODULES_CONTENT_PLACEMENT_2 ) ;
  define('DESCRIPTION_MODULE_BOXES_TEXT_CONTENT_PLACEMENT', _MODULES_CONTENT_PLACEMENT_DESCRIPTION ) ;  

  define('TITLE_MODULE_BOXES_TEXT_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_BOXES_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_BOXES_TEXT_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    

  define('TITLE_MODULE_BOXES_TEXT_DISPLAY_PAGES', _MODULES_DISPLAY_PAGES_1 . MODULE_BOXES_TEXT_TITLE . _MODULES_DISPLAY_PAGES_2 ) ;
  define('DESCRIPTION_MODULE_BOXES_TEXT_DISPLAY_PAGES', _MODULES_DISPLAY_PAGES_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_BOXES_TEXT_TITLE_LINK', 'Titel Link naar '   ) ; // Title Link
  define('DESCRIPTION_MODULE_BOXES_TEXT_TITLE_LINK', 'Naar welke pagina wordt de Titel naar gelink als men de Titel van deze info Box aan klikt met de muis' ) ;   // Link the title of the box to a page (Leave empty for no link.)
  
  define('TITLE_MODULE_BOXES_TEXT_CONTENT_LINK', 'Tekst van Box Link naar '   ) ; // Content Link
  define('DESCRIPTION_MODULE_BOXES_TEXT_CONTENT_LINK', 'Naar welke pagina wordt de Tekst van infoBox gelink als men op de Tekst van deze info Box aan klikt met de muis' ) ;    // Link the contents of the box to a page (Leave empty for no link.)
  
  define('TITLE_MODULE_BOXES_TEXT_CONTENT_ALIGNMENT', 'De Tekst wordt Uitgelijnd op  '  ) ; // Content Alignment
  define('DESCRIPTION_MODULE_BOXES_TEXT_CONTENT_ALIGNMENT',  'De Tekst wordt Uitgelijnd op : <br /> Left = De Tekst wordt links uitgelijnd<br /> Center = De tekst wordt gecentreerd uitgelijnd<br /> Right = De Teskst wordt rechts uitgelijnd' ) ;   // Align the module contents to the left, center, or right?
  
  define('TITLE_MODULE_BOXES_TEXT_TITLE_DUTCH', _MODULE_BOXES_TEXT_TITLE_DUTCH  ) ;
  define('DESCRIPTION_MODULE_BOXES_TEXT_TITLE_DUTCH', _MODULE_BOXES_TEXT_TITLE_DUTCH_DESCRIPTION ) ;    

  
//   	$keys[] = 'MODULE_BOXES_TEXT_STATUS';
//    	$keys[] = 'MODULE_BOXES_TEXT_SORT_ORDER';
//    	$keys[] = 'MODULE_BOXES_TEXT_TITLE_LINK';
//    	$keys[] = 'MODULE_BOXES_TEXT_CONTENT_LINK';
//    	$keys[] = 'MODULE_BOXES_TEXT_CONTENT_PLACEMENT';
//    	$keys[] = 'MODULE_BOXES_TEXT_CONTENT_ALIGNMENT';
//		$keys[] = 'MODULE_BOXES_TEXT_CONTENT_DISPLAY_PAGES' ;
//    	foreach( $this->languages_array as $language_name ) {
//    	  $keys[] = 'MODULE_BOXES_TEXT_TITLE_' . strtoupper( $language_name );
//    	  $keys[] = 'MODULE_BOXES_TEXT_CONTENT_' . strtoupper( $language_name );
//    	}
?>