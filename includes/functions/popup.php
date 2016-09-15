<?php
/*
  $Id$: $Id$ catalog/includes/functions/popup.php v.1.00 2013/04/10
  
  by De Dokta


  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

////
// Sets the status of a popup
  function tep_set_popup_status($popups_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_POPUPS . " set status = '1', date_status_change = now() where popups_id = '" . (int)$popups_id . "'
	                                and find_in_set('" . SYS_STORES_ID . "',    stores_id)     != 0 ");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_POPUPS . " set status = '0', date_status_change = now() where popups_id = '" . (int)$popups_id . "'
	                                and find_in_set('" . SYS_STORES_ID . "',    stores_id)     != 0 ");
    } else {
      return -1;
    }
  }

////
// Auto activate popups
  function tep_activate_popups() {
    $popups_query = tep_db_query("select popups_id, date_scheduled from " . TABLE_POPUPS  . " where date_scheduled != ''
	                                and find_in_set('" . SYS_STORES_ID . "',    stores_id)     != 0 ");
    if (tep_db_num_rows($popups_query)) {
      while ($popups = tep_db_fetch_array($popups_query)) {
        if (date('Y-m-d H:i:s') >= $popups['date_scheduled']) {
          tep_set_popup_status($popups['popups_id'], '1');
        }
      }
    }
  }

////
// Auto expire popups
  function tep_expire_popups() {
    $popups_query = tep_db_query("select popups_id, expires_date from " . TABLE_POPUPS  . " where status = '1' 
	                                and find_in_set('" . SYS_STORES_ID . "',    stores_id)     != 0 ");
    if (tep_db_num_rows($popups_query)) {
      while ($popups = tep_db_fetch_array($popups_query)) {
        if (tep_not_null($popups['expires_date'])) {
          if (date('Y-m-d H:i:s') >= $popups['expires_date']) {
            tep_set_popup_status($popups['popups_id'], '0');
          }
        } 
      }
    }
  }
/*
// Display a popup from 
  function tep_display_popup() {   
    global $languages_id;  
//	$popup_query = tep_db_query("select popups_id, popups_title, popups_image, popups_html_text, stores_id                                              from " . TABLE_POPUPS . "                                          where status = '1' and find_in_set('" . SYS_STORES_ID . "', stores_id) != 0");
    $popup_query = tep_db_query("select po.popups_id, pod.popups_id, pod.language_id, po.popups_title, po.popups_image, stores_id, pod.popups_html_text from " . TABLE_POPUPS . " po, " . TABLE_POPUPS_DESCRIPTION . " pod where status = '1' and po.popups_id = pod.popups_id and pod.language_id = '" . (int)$languages_id . "' and find_in_set('" . SYS_STORES_ID . "', po.stores_id) != 0");
		
    $popup = tep_db_fetch_array($popup_query);

    if (tep_not_null($popup['popups_html_text'])) {
      $popup_string = $popup['popups_html_text'];
    } else {
      $popup_string = '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=popup&goto=' . $popup['popups_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $popup['popups_image'], $popup['popups_title']) . '</a>';
    }
    return $popup_string;
  }
*/

  function tep_popup_exists() {
      $popup_check_query = tep_db_query("select count(*) as count from " . TABLE_POPUPS . " where status = '1' 
	                                and find_in_set('" . SYS_STORES_ID . "',    stores_id)     != 0 ");
      $popup_exists = tep_db_fetch_array($popup_check_query);
      
      return $popup_exists['count'];
      }



?>
