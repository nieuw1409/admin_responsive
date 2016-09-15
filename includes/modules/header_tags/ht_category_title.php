<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class ht_category_title {
    var $code = 'ht_category_title';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages = SYS_DISPLAY_ALL_PAGES ;

    function ht_category_title() {
      $this->title = MODULE_HEADER_TAGS_CATEGORY_TITLE_TITLE;
      $this->description = MODULE_HEADER_TAGS_CATEGORY_TITLE_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_CATEGORY_TITLE_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS == 'True');	
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $categories, $current_category_id, $languages_id;

// bof header tags ($page === FILENAME_DEFAULT)
      $pos = strripos($_SERVER['PHP_SELF'], "/");
      $page = ($pos !== FALSE) ? basename(substr($_SERVER['PHP_SELF'], 0, $pos)) : $page;	  
//      if (basename($PHP_SELF) == FILENAME_DEFAULT) {
      if ($page === FILENAME_DEFAULT) {	  
// eof header tags	  
// $categories is set in application_top.php to add the category to the breadcrumb
        if (isset($categories) && (sizeof($categories) == 1) && isset($categories['categories_name'])) {
          $oscTemplate->setTitle($categories['categories_name'] . ', ' . $oscTemplate->getTitle());
        } else {
// $categories is not set so a database query is needed
          if ($current_category_id > 0) {
            $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "' limit 1");
            if (tep_db_num_rows($categories_query) > 0) {
              $categories = tep_db_fetch_array($categories_query);

              $oscTemplate->setTitle($categories['categories_name'] . ', ' . $oscTemplate->getTitle());
            }
          }
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Category Title Module', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS', 'True', 'Do you want to allow category titles to be added to the page title?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SORT_ORDER');
    }
  }
?>