<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_footer_popup {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_footer_popup() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_FOOTER_POPUP_TITLE;
      $this->description = MODULE_CONTENT_FOOTER_POPUP_DESCRIPTION;

      if ( defined('MODULE_CONTENT_FOOTER_POPUP_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_FOOTER_POPUP_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_FOOTER_POPUP_STATUS == 'true');
      }
    }

    function execute() {
     global $oscTemplate, $languages_id, $popups_id, $_SERVER;

     if ( !tep_session_is_registered('popup')) {	 
       $popups_query = tep_db_query("select popups_id, popups_title, popups_image, stores_id, expires_date, date_scheduled from " . TABLE_POPUPS  . " where status = '1' and find_in_set('" . SYS_STORES_ID . "', stores_id) != 0");
       if (tep_db_num_rows($popups_query)) {
         while ($popups = tep_db_fetch_array($popups_query)) {
           if (tep_not_null($popups['expires_date'])) {
             if ((date('Y-m-d H:i:s') >= $popups['date_scheduled']) && (date('Y-m-d H:i:s') <= $popups['expires_date']) )  {
	            $popup_string =  tep_get_popups_text($popups['popups_id'] ,$languages_id)  ;
		        $popup_title  =  $popups['popups_title'] ;
		        $popup_id     =  $popups['popups_id'] ;
     
                ob_start();
                include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/popup.php');
                $template .= ob_get_clean();
		     }
		   }
	     }
		 $oscTemplate->addContent($template, $this->group); 	
	   }
       tep_session_register('popup');	   
	 }
	}

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_FOOTER_POPUP_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Message Popup Footer Module', 'MODULE_CONTENT_FOOTER_POPUP_STATUS', 'true', 'Do you want to enable the Message popup content module?', '6', '1', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_POPUP_SORT_ORDER', '999', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");

    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");  
    }

    function keys() {
      return array('MODULE_CONTENT_FOOTER_POPUP_STATUS', 'MODULE_CONTENT_FOOTER_POPUP_SORT_ORDER');
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_FOOTER_POPUP_STATUS'");
            $this->enabled = (MODULE_CONTENT_FOOTER_POPUP_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_FOOTER_POPUP_STATUS'");
            $this->enabled = (MODULE_CONTENT_FOOTER_POPUP_STATUS == 'False');

    }		
   }
?>