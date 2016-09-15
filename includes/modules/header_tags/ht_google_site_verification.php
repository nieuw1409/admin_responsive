<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class ht_google_site_verification {
    var $code = 'ht_google_site_verification';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages = SYS_DISPLAY_ALL_PAGES ;

    function ht_google_site_verification() {
      $this->title = MODULE_HEADER_TAGS_GOO_SITE_VERI_TITLE;
      $this->description = MODULE_HEADER_TAGS_GOO_SITE_VERI_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_GOO_SITE_VERI_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_GOO_SITE_VERI_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_GOO_SITE_VERI_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate;

      $oscTemplate->addBlock('<meta name="google-site-verification" content="' . tep_output_string(MODULE_HEADER_TAGS_GOO_SITE_VERI_ID) . '" />' . "\n", $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_GOO_SITE_VERI_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Google Site Verification Code', 'MODULE_HEADER_TAGS_GOO_SITE_VERI_STATUS', 'True', 'Type the code which is given to you by Google to verify that you are the owner of this Site', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('G+ Publisher Address', 'MODULE_HEADER_TAGS_GOO_SITE_VERI_ID', '', 'Your G+ URL.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_GOO_SITE_VERI_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_GOO_SITE_VERI_STATUS', 'MODULE_HEADER_TAGS_GOO_SITE_VERI_ID', 'MODULE_HEADER_TAGS_GOO_SITE_VERI_SORT_ORDER');
    }
  }
?>
