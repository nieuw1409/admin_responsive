<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2014 osCommerce
  Released under the GNU General Public License
*/

  class cm_index_products_product_listing {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_index_products_product_listing() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_TITLE;
      $this->description = MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_DESCRIPTION;

      if ( defined('MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer_id, $category, $htc, $listing_sql, $messageStack, $column_list, $HTTP_GET_VARS, $_GET, $PHP_SELF, $pf, $languages_id, $currency;
      global $cPath ;
	  
      $content_width = (int)MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_CONTENT_WIDTH;   
    
      ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/products_product_listing.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Index Products Product Listing Module', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS', 'True', 'Do you want to enable the Index Products Product Listing  content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " .  $multi_stores_config   . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_CONTENT_WIDTH', 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_SORT_ORDER');
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_PRODUCT_LISTING_STATUS == 'False');

    }			
  }
?>