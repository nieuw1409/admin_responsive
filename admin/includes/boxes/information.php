<?php
/*
  Module: Information Pages Unlimited
          File date: 2007/02/17
          Based on the FAQ script of adgrafics
          Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands
          Modified by SLiCK_303@hotmail.com for OSC v2.3.1

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
	'icon' => BOX_HEADING_INFORMATION_ICON,  
    'heading' => BOX_HEADING_INFORMATION,
    'apps' => array(
      array(
        'code' => FILENAME_DEFINE_MAINPAGE,
        'title' => BOX_CATALOG_DEFINE_MAINPAGE,
        'link' => tep_href_link(FILENAME_DEFINE_MAINPAGE)
      ),
      array(
        'code' => FILENAME_DEFINE_PRIVACY,
        'title' => BOX_CATALOG_DEFINE_PRIVACY,
        'link' => tep_href_link(FILENAME_DEFINE_PRIVACY)
      ),
      array(
        'code' => FILENAME_DEFINE_CONDITIONS,
        'title' => BOX_CATALOG_DEFINE_CONDITIONS,
        'link' => tep_href_link(FILENAME_DEFINE_CONDITIONS)
      ),	  
      array(
        'code' => FILENAME_DEFINE_SHIPPING,
        'title' => BOX_CATALOG_DEFINE_SHIPPING,
        'link' => tep_href_link(FILENAME_DEFINE_SHIPPING)
      )	  
	)
  );

  $information_groups_query = tep_db_query("SELECT information_group_id AS igID, information_group_title AS igTitle FROM " . TABLE_INFORMATION_GROUP . " WHERE visible='1' ORDER BY sort_order");
  while ($information_groups = tep_db_fetch_array($information_groups_query)) {
    $cl_box_groups[sizeof($cl_box_groups)-1]['apps'][] = array(
      'code' => FILENAME_INFORMATION_MANAGER,
      'title' => $information_groups['igTitle'],
      'link' => tep_href_link(FILENAME_INFORMATION_MANAGER, 'gID=' . $information_groups['igID'])
    );
  }
?>
