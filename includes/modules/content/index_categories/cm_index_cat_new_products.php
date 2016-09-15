<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2014 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_cat_new_products{
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_index_cat_new_products() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_TITLE;
      $this->description = MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_DESCRIPTION;

      if ( defined('MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS == 'True');
      }
    }
	
	function getData() {
      global $oscTemplate, $customer_id, $category, $new_products_category_id, $currency, $PHP_SELF;
	  global $cPath, $categories, $languages_id, $current_category_id, $cPath_array, $pf, $cache ;	 		
      if ( SYS_USE_NEW_PROD_INDEX == 'True' ) { 
        ob_start();
          include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); 
        $_string_new_products = ob_get_clean();	  
//        $new_products_string = require(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); 
      }	
      return $_string_new_products ;	  
	}

    function execute() {
      global $oscTemplate, $customer_id, $category, $new_products_category_id, $currency, $PHP_SELF;
	  global $cPath, $categories, $languages_id, $current_category_id, $cPath_array, $pf, $cache ;	  
      
      $content_width = (int)MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_CONTENT_WIDTH;	  
      $customer_group_id = tep_get_cust_group_id() ;

      if (USE_CACHE == 'true') {
	    $cache_name = 'cm_idx_cat_nw_prod-' . $languages_id . '-cg' . $customer_group_id . '.cache' . $cPath ;
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		  $new_products_string = $this->getData();
		  $cache->save_cache($cache_name, $new_products_string, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	    } else {
	  	  $new_products_string = $cache->get_cache($cache_name, 'RETURN');	  
	    }  		
      } else {
        $new_products_string = $this->getData();
      }
      
      ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/categories_new_products.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Index Categories New Products Module', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS', 'True', 'Do you want to enable the Index Categories New Products  content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " .  $multi_stores_config   . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_CONTENT_WIDTH', 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_SORT_ORDER');
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_NEW_PRODUCTS_STATUS == 'False');

    }		
  }
?>