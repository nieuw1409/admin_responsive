<?php
/*
  $Id$ Author: Tóth Gergely http://oscom.hu v1.0

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2011 osCommerce

  Released under the GNU General Public License
*/

  class bm_breadcrumb {
    var $code = 'bm_breadcrumb';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;	

    function bm_breadcrumb() {
      $this->title = MODULE_BOXES_BREADCRUMB_TITLE;
      $this->description = MODULE_BOXES_BREADCRUMB_DESCRIPTION;

      if ( defined('MODULE_BOXES_BREADCRUMB_STATUS') ) {
        $this->sort_order = MODULE_BOXES_BREADCRUMB_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_BREADCRUMB_STATUS == 'True');

        $this->pages = MODULE_BOXES_BREADCRUMB_DISPLAY_PAGES;		
		$placement = MODULE_BOXES_BREADCRUMB_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :
				$this->group = 'boxes_column_left';
				break;
			case "Right Column" :
				$this->group = 'boxes_column_right';
				break;
			case "Bread Column" :
				$this->group = 'boxes_column_bread';
				break;
			case "Head Column" :
				$this->group = 'boxes_column_head';
				break;
			case "Foot Column" :
				$this->group = 'boxes_column_foot';
				break;		
            case 'Left Header' : 
			    $this->group = 'header_contents_left';
                break;
            case 'Center Header' : 
			    $this->group = 'header_contents_center';
                break;
            case 'Right Header' : 
			    $this->group = 'header_contents_right';
                break;
            case 'Header Line' :  
			    $this->group = 'header_line';
                break;
            case 'Left Footer' : 
			    $this->group = 'footer_contents_left';
                break;
            case 'Center Footer' : 
			    $this->group = 'footer_contents_center';
                break;
            case 'Right Footer' : 
			    $this->group = 'footer_contents_right';
                break;
            case 'Footer Line' : 
			    $this->group = 'footer_line';
                break;	 
		}		
      }
    }

    function execute() {
      global $HTTP_GET_VARS, $current_category_id, $languages_id, $oscTemplate, $breadcrumb ;
      
	  ob_start();
        include(DIR_WS_MODULES . 'boxes/templates/breadcrumb.php');
      $data =  ob_get_clean();
	  
	  //'<div class="clearfix"></div><div class="col-sm-12 nav navbar-nav breadcrumb-margin">' . $breadcrumb->trail(' &raquo; ') . '<br /></div><div class="clearfix"></div>';	  
      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_BREADCRUMB_STATUS');
    }

    function install() {
	  global $multi_stores_config;	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Languages Module', 'MODULE_BOXES_BREADCRUMB_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_BREADCRUMB_CONTENT_PLACEMENT', 'Bread Column', 'Should the module be loaded in the header or the footer position?', '6', '2', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_BREADCRUMB_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Module Version Number', 'MODULE_BOXES_BREADCRUMB_VERSION_NUMBER', 'v1.0', 'Version number of installed module', '6', '4', 'tep_sanitize_string(', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_BREADCRUMB_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '5','tep_cfg_select_pages(' , now())"); 	  
    }

    function remove() {
	  global $multi_stores_config;	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_BREADCRUMB_VERSION_NUMBER','MODULE_BOXES_BREADCRUMB_STATUS', 'MODULE_BOXES_BREADCRUMB_CONTENT_PLACEMENT', 'MODULE_BOXES_BREADCRUMB_SORT_ORDER', 'MODULE_BOXES_BREADCRUMB_DISPLAY_PAGES');
    }
  }
?>