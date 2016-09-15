<?php
/*
  $Id: faq.php,v 2.2 2010/01/18 01:48:08 dgw_ Exp $

  /catalog/admin/includes/languages/dutch/faq.php

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 Denkster

  Released under the GNU General Public License
*/

  $action = (!empty($HTTP_GET_VARS['actie']) ? $HTTP_GET_VARS['actie'] : '' );
	if ( $action == 'new_faq' ){
		  define('HEADING_TITLE', 'FAQDesk - Nieuwe vraag invoeren');
	} elseif ( $action == 'edit_faq' ){
		  define('HEADING_TITLE', 'FAQDesk - Vraag bewerken');
	} elseif ( $action == 'preview_faq' ){
		  define('HEADING_TITLE', 'FAQDesk - Voorbeeld tonen');
	} else {
		  define('HEADING_TITLE', 'FAQDesk - FAQ beheren');
	}
  define('TABLE_HEADING_FAQ_ID', 'ID');
  define('TABLE_HEADING_FAQ_QUESTION', 		'Vraag');
  define('TABLE_HEADING_FAQ_STATUS', 		'Status');
  define('TABLE_HEADING_FAQ_LAST_MODIFIED', 'Datum laatst bewerkt');
  define('TABLE_HEADING_ACTION',            'Actie');


  define('TEXT_HEADING_DELETE_INTRO', 		'FAQ item verwijderen?');
  define('TEXT_HEADING_DELETE_FAQ', 		'Koptekst voor Verwijderen');


  define('TITLE_ADD_QUESTION', 				'Nieuwe Vraag  :');
  define('TITLE_ADD_ANSWER', 				'Nieuw Antwoord:');
  define('TITLE_STATUS', 					'Status:');
  define('TITLE_SORT', 						'Volgorde:');

  define('TEXT_ON', 						'Aan');
  define('TEXT_OFF', 						'Uit');
  define('TEXT_QUESTION', 					'Vraag:');
  define('TEXT_ANSWER', 					'Antwoord:');
  define('TEXT_LAST_UPDATED', 				'Datum laatst bewerkt - ');
  define('TEXT_DISPLAY_NUMBER_OF_QUESTIONS','Vragen nummer <b>%d</b> t/m <b>%d</b> (van <b>%d</b> vragen) tonen');
  define('TEXT_CLICK_REVEAL', 				'Klik op een vraag om het antwoord te zien');
  define('ERROR_MISSING_QUESTION',          'FAQ Vraag kan niet leeg zijn! ');
  define('ERROR_MISSING_ANSWER',            'FAQ Antwoord kan niet leeg zijn! ');
  
  define('TEXT_TABS_01', 'Vraag' ) ;
  define('TEXT_TABS_02', 'Antwoord' ) ;
  
  define('TEXT_INFO_HEADING_NEW_QUESTION_ANSWER',    'Nieuwe Vraag en Antwoord');   
  define('TEXT_INFO_HEADING_DELETE_QUESTION_ANSWER', 'Verwijder Vraag en Antwoord');   
  define('TEXT_INFO_HEADING_EDIT_QUESTION_ANSWER',   'Wijzigen Vraag en Antwoord') ; 
  
  define('TEXT_FAQ_QUESTION',                     'Vraag Bewerken' ) ;
  define('TEXT_FAQ_ANSWER',                       'Antwoord Bewerken' ) ;  
?>