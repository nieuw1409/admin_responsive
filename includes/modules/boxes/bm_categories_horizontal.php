<?php
/*
  $Id$ Tóth Gergely http://oscom.hu

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_categories_horizontal {
    var $code = 'bm_categories_horizontal';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;		

    function bm_categories_horizontal() {
      $this->title = MODULE_BOXES_CATEGORIES_HORIZONTAL_TITLE;
      $this->description = MODULE_BOXES_CATEGORIES_HORIZONTAL_DESCRIPTION;
      $this->pages = MODULE_BOXES_CATEGORIES_HORIZONTAL_DISPLAY_PAGES;	  	  

      if ( defined('MODULE_BOXES_CATEGORIES_HORIZONTAL_STATUS') ) {
        $this->sort_order = MODULE_BOXES_CATEGORIES_HORIZONTAL_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_CATEGORIES_HORIZONTAL_STATUS == 'True');

        switch (MODULE_BOXES_CATEGORIES_HORIZONTAL_CONTENT_PLACEMENT) {
          case 'Header Line'  : $this->group = 'header_line';
            break;
    	  case "Bread Column" : $this->group = 'boxes_column_bread';
			break;			
          case 'Footer Line'  : $this->group = 'footer_line';
            break;
        }
      }
    }

    function execute() {
      global $cart, $oscTemplate,$lng, $currencies, $language, $currency, $PHP_SELF, $request_type, $languages_description, $customer_first_name;
//	  global $cart ;
	  
	  $inverse = ( ( MODULE_BOXES_CATEGORIES_HORIZONTAL_INVERTED == 'True') ? 'navbar-inverse' : '' ) ;

      ob_start();
      include(DIR_WS_MODULES . 'boxes/templates/categories_horizontal.php');
      $data = ob_get_clean();

      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_CATEGORIES_HORIZONTAL_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Navigation Bar Module', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_CONTENT_PLACEMENT', 'Header Line', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Header Line\', \'Bread Column\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Inverted Navigation Bar', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_INVERTED', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	  	  	   	  
	  
    }

    function remove() {
	  global $multi_stores_config;
	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_CATEGORIES_HORIZONTAL_STATUS', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_CONTENT_PLACEMENT', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_SORT_ORDER', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_INVERTED', 'MODULE_BOXES_CATEGORIES_HORIZONTAL_DISPLAY_PAGES');
    }
  }
?>