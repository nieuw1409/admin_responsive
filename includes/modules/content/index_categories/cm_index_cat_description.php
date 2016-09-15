<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2014 osCommerce
  Released under the GNU General Public License
*/

  class cm_index_cat_description {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_index_cat_description() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_TITLE;
      $this->description = MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_DESCRIPTION;

      if ( defined('MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer_id, $category;
      
      $content_width = (int)MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_CONTENT_WIDTH;
      
/*** Begin Header Tags SEO ***/ 
      if (tep_not_null($category['categories_htc_description'])) { 
              $categories_description = $category['categories_htc_description']  ;
			  //nl2br( $category['categories_htc_description']  ) ;
   
          ob_start();
          include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/categories_description.php');
          $template = ob_get_clean();

          $oscTemplate->addContent($template, $this->group);
      } 
/*** End Header Tags SEO ***/ 		  
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Index Categories Description Module', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS', 'True', 'Do you want to enable the Index Categories Description  content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " .  $multi_stores_config   . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_CONTENT_WIDTH', 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_SORT_ORDER');
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_DESCRIPTION_STATUS == 'False');

    }		
  }
?>