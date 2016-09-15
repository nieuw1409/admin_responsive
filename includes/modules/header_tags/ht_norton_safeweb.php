<?php
/*
  $Id$ ht_norton_safeweb.php by MarcelMedia.nl

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class ht_norton_safeweb {
    var $code = 'ht_norton_safeweb';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages = SYS_DISPLAY_ALL_PAGES ;	

    function ht_norton_safeweb() {
      $this->title = MODULE_HEADER_TAGS_NORTON_SAFEWEB_TITLE;
      $this->description = MODULE_HEADER_TAGS_NORTON_SAFEWEB_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_NORTON_SAFEWEB_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_NORTON_SAFEWEB_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_NORTON_SAFEWEB_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate;

      $oscTemplate->addBlock('<meta name="norton-safeweb-site-verification" content="' . tep_output_string(MODULE_HEADER_TAGS_NORTON_SAFEWEB_ID) . '" />' . "\n", $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_NORTON_SAFEWEB_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Norton Safeweb verification Header Tag?', 'MODULE_HEADER_TAGS_NORTON_SAFEWEB_STATUS', 'True', 'To verify your site ownership, you can either add a meta tag to your home page.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Code', 'MODULE_HEADER_TAGS_NORTON_SAFEWEB_ID', '', 'Your verification code.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_NORTON_SAFEWEB_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_NORTON_SAFEWEB_STATUS', 'MODULE_HEADER_TAGS_NORTON_SAFEWEB_ID', 'MODULE_HEADER_TAGS_NORTON_SAFEWEB_SORT_ORDER');
    }
  }
?>