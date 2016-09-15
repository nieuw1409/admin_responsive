<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_categories {
    var $code = 'bm_categories';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $pages;

    function bm_categories() {
      $this->title = MODULE_BOXES_CATEGORIES_TITLE;
      $this->description = MODULE_BOXES_CATEGORIES_DESCRIPTION;

      if ( defined('MODULE_BOXES_CATEGORIES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_CATEGORIES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_CATEGORIES_STATUS == 'True');
        $this->pages = MODULE_BOXES_CATEGORIES_DISPLAY_PAGES;
//        $this->group = ((MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
		$placement = MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT;
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


    function getData() {
      global $categories_string, $tree, $languages_id, $cPath, $cPath_array;


      global $oscTemplate, $cPath;

      $OSCOM_CategoryTree = new category_tree();
      $OSCOM_CategoryTree->setCategoryPath($cPath, '<strong>', '</strong>');
      $OSCOM_CategoryTree->setSpacerString('&nbsp;&nbsp;', 1);

      $OSCOM_CategoryTree->setParentGroupString('<ul class="nav nav-list">', '</ul>', true);
      $category_tree = $OSCOM_CategoryTree->getTree();
      ob_start();
      include(DIR_WS_MODULES . 'boxes/templates/categories.php');
      $data = ob_get_clean();

      return $data;
    }

    function execute() {
      global $SID, $oscTemplate, $cPath, $language, $cache;

// bof multi stores
// bof sppc     
     $customer_group_id = tep_get_cust_group_id() ;
// eof sppc

      if ((USE_CACHE == 'true') && empty($SID)) {
//        $output = tep_cache_categories_box();
	    $cache_name = 'categories_box-' . $language . '-cg' . $customer_group_id . '.cache' . $cPath ;
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		  $output = $this->getData();
		  $cache->save_cache($cache_name, $output, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	    } else {
	  	  $output = $cache->get_cache($cache_name, 'RETURN');	  
	    }  		
      } else {
        $output = $this->getData();
      }

      $oscTemplate->addBlock($output, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_CATEGORIES_STATUS');
    }

    function install() {
	  global $multi_stores_config ;		  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Categories Module', 'MODULE_BOXES_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'Left Column', 'Should the module be be placed in: ?', '6', '3', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_CATEGORIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '4', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_CATEGORIES_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '5','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config ;	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_CATEGORIES_STATUS', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'MODULE_BOXES_CATEGORIES_SORT_ORDER','MODULE_BOXES_CATEGORIES_DISPLAY_PAGES');
    }
  }
?>
