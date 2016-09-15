<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class sb_google_plus {
    var $code = 'sb_google_plus';
    var $title;
    var $description;
    var $sort_order;
    var $icon = 'google_plus.png';
    var $enabled = false;
	var $pages = SYS_DISPLAY_ALL_PAGES ;	

    function sb_google_plus() {
      $this->title = MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_TITLE;
      $this->public_title = MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_PUBLIC_TITLE;
      $this->description = MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_DESCRIPTION;

      if ( defined('MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_STATUS') ) {
        $this->sort_order = MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_STATUS == 'True');
      }
    }

    function getOutput() {
      global $HTTP_GET_VARS;
      return '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
</script>
<g:plusone size="small" count="false"></g:plusone>';
    }
    function isEnabled() {
      return $this->enabled;
    }

    function getIcon() {
      return $this->icon;
    }

    function getPublicTitle() {
      return $this->public_title;
    }

    function check() {
      return defined('MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Google Plus Module', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_STATUS', 'True', 'Do you want to allow products to be shared through Google Plus?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_STATUS', 'MODULE_SOCIAL_BOOKMARKS_GOOGLE_PLUS_SORT_ORDER');
    }
  }
?>