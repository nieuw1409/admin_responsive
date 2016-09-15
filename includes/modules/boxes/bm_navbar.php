<?php
/*$Id$ Tóth Gergely http://oscom.hu
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_navbar {
    var $code = 'bm_navbar';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;		

    function bm_navbar() {
      $this->title = MODULE_BOXES_NAVBAR_TITLE;
      $this->description = MODULE_BOXES_NAVBAR_DESCRIPTION;
      $this->pages = MODULE_BOXES_NAVBAR_DISPLAY_PAGES;	  	  

      if ( defined('MODULE_BOXES_NAVBAR_STATUS') ) {
        $this->sort_order = MODULE_BOXES_NAVBAR_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_NAVBAR_STATUS == 'True');

        switch (MODULE_BOXES_NAVBAR_CONTENT_PLACEMENT) {
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

	  
	  $inverse = ( ( MODULE_BOXES_NAVBAR_INVERTED == 'True') ? 'navbar-inverse' : '' ) ;
      if ( defined('MODULE_CONTENT_INSTALLED') && tep_not_null(MODULE_CONTENT_INSTALLED) ) {
        $navbar_array = explode(';', MODULE_CONTENT_INSTALLED);

        $navigation_left = array();
        $navigation_right = array();

        foreach ( $navbar_array as $navbar_element ) {
          $dir = substr($navbar_element, 0, 6);
          if ( $dir === 'navbar' ) {
            $class = substr($navbar_element, 7);

            if ( !class_exists( $class ) ) {
              include_once DIR_WS_LANGUAGES . $language . '/modules/content/navbar/' . $class . '.php';
              include_once DIR_WS_MODULES . 'content/navbar/' . $class . '.php';
            }

            $navbar_class = new $class();

            if ( $navbar_class->isEnabled() && $navbar_class->side === 'left' ) {
              $navigation_left[] = $navbar_class->getOutput();

            } elseif ( $navbar_class->isEnabled() && $navbar_class->side === 'right' ) {
              $navigation_right[] = $navbar_class->getOutput();

            }
          }
        }

        if ( !empty($navigation_left) || !empty($navigation_right) ) {

          ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/navbar.php');
          $template = ob_get_clean();

          $oscTemplate->addBlock($template, $this->group);
        }
      }
//    ob_start();
//        include(DIR_WS_MODULES . 'boxes/templates/navbar.php');
//    $data = ob_get_clean();  

//      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_NAVBAR_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Navigation Bar Module', 'MODULE_BOXES_NAVBAR_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_NAVBAR_CONTENT_PLACEMENT', 'Header Line', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Header Line\', \'Bread Column\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_NAVBAR_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Inverted Navigation Bar', 'MODULE_BOXES_NAVBAR_INVERTED', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_NAVBAR_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	  	  	   	  
	  
    }

    function remove() {
	  global $multi_stores_config;
	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_NAVBAR_STATUS', 'MODULE_BOXES_NAVBAR_CONTENT_PLACEMENT', 'MODULE_BOXES_NAVBAR_SORT_ORDER', 'MODULE_BOXES_NAVBAR_INVERTED', 'MODULE_BOXES_NAVBAR_DISPLAY_PAGES');
    }
  }
?>