<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_cu_mail_us {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_cu_mail_us() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_CONTACT_US_MAIL_US_TITLE;
      $this->description = MODULE_CONTENT_CONTACT_US_MAIL_US_DESCRIPTION;

      if ( defined('MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_CONTACT_US_MAIL_US_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $_GET, $languages_id, $pf, $currencies, $currency, $product_info ;  
      
      $content_width = (int)MODULE_CONTENT_CONTACT_US_MAIL_US_CONTENT_WIDTH;
	  
	  $placement = MODULE_CONTENT_CONTACT_US_MAIL_US_ALIGN_TEXT;
      switch($placement) {
			case "Left" :
				$align_text = 'text-left';
				break;
			case "Right" :
				$align_text = 'text-right';
				break;
			case "Center" :
				$align_text = 'text-center';
				break;					
	  }		

//	  $mail_us_box
	  ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/mail_us.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Mail Us Module', 'MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS', 'True', 'Should the Mail module be shown on the contact us page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_CONTACT_US_MAIL_US_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Align Text Product Button Buy', 'MODULE_CONTENT_CONTACT_US_MAIL_US_ALIGN_TEXT', 'Center', 'Align the Text for this module ?', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Center\', \'Right\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CONTACT_US_MAIL_US_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS', 'MODULE_CONTENT_CONTACT_US_MAIL_US_CONTENT_WIDTH', 'MODULE_CONTENT_CONTACT_US_MAIL_US_SORT_ORDER');
//, 'MODULE_CONTENT_CONTACT_US_MAIL_US_ALIGN_TEXT'
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS'");
            $this->enabled = (MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS'");
            $this->enabled = (MODULE_CONTENT_CONTACT_US_MAIL_US_STATUS == 'FaLse');

    }		
  }
?>
