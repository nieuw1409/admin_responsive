<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class cm_sc_no_products {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SC_NO_PRODUCTS_TITLE;
      $this->description = MODULE_CONTENT_SC_NO_PRODUCTS_DESCRIPTION;

      if ( defined('MODULE_CONTENT_SC_NO_PRODUCTS_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SC_NO_PRODUCTS_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SC_NO_PRODUCTS_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $cart;
	  
	  $content_width = (int)MODULE_CONTENT_SC_NO_PRODUCTS_CONTENT_WIDTH;

      if ($cart->count_contents() === 0) {
 
        ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/no_products.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);

      }
    }

    function  isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SC_NO_PRODUCTS_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart No Products Message', 'MODULE_CONTENT_SC_NO_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");	
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_NO_PRODUCTS_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_NO_PRODUCTS_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SC_NO_PRODUCTS_STATUS', 'MODULE_CONTENT_SC_NO_PRODUCTS_CONTENT_WIDTH', 'MODULE_CONTENT_SC_NO_PRODUCTS_SORT_ORDER');
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_SC_NO_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_SC_NO_PRODUCTS_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_SC_NO_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_SC_NO_PRODUCTS_STATUS == 'True');

    }		
  }
?>