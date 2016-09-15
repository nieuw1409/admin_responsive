<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2014 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_products_manufacturers{
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_index_products_manufacturers() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_TITLE;
      $this->description = MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_DESCRIPTION;

      if ( defined('MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS == 'True');
      }
    }
	
    function execute() {
      global $oscTemplate, $customer_id, $category, $new_products_category_id, $currency, $PHP_SELF, $_GET, $filterlist_query, $languages_id, $current_category_id, $HTTP_GET_VARS;
     
	  $customer_group_id = tep_get_cust_group_id() ; 
	  
      if (PRODUCT_LIST_FILTER > 0) {
      // 2.3.3 if (isset($HTTP_GET_VARS['manufacturers_id'])) {
	    if (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id'])) {
          $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name 
		     from " . TABLE_PRODUCTS . " p, " . 
			          TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . 
					  TABLE_CATEGORIES . " c, " . 
					  TABLE_CATEGORIES_DESCRIPTION . " cd 
			    where  c.categories_status = '1'                                                  and p.products_status = '1' 
				   and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0  
				   and find_in_set('" . SYS_STORES_ID . "', categories_to_stores) != 0            and find_in_set('" . SYS_STORES_ID . "',  products_to_stores) != 0      
				   and p.products_id = p2c.products_id                                            and p2c.categories_id = c.categories_id 
				   and p2c.categories_id = cd.categories_id                                       and cd.language_id = '" . (int)$languages_id . "' 
				   and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";
        } else {
          $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name 
		      from " . TABLE_PRODUCTS . " p, " . 
			           TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
		  left join " . TABLE_CATEGORIES . " c  using(categories_id), " . 
		               TABLE_MANUFACTURERS . " m 
				where c.categories_status = '1'                                                    and  p.products_status = '1' 
				  and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0   and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 
				  and find_in_set('" . SYS_STORES_ID . "', categories_to_stores) != 0              and find_in_set('" . SYS_STORES_ID . "',  products_to_stores) != 0  
				  and p.manufacturers_id = m.manufacturers_id                                      and p.products_id = p2c.products_id 
				  and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
       }
// EOF SPPC Hide products and categories from groups
       $filterlist_query = tep_db_query($filterlist_sql);
      
      if (tep_db_num_rows($filterlist_query) > 1) {
        $content_manufacturers = '<div>' . tep_draw_form('filter', tep_href_link( FILENAME_DEFAULT ), 'get') . '<p align="left">' . TEXT_SHOW . '&nbsp;';		
		
        // 2.3.3 if (isset($HTTP_GET_VARS['manufacturers_id'])) {
		if (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id'])) {
        $content_manufacturers .= tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
        $content_manufacturers .=  tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        $content_manufacturers .= tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
//        $content_manufacturers .= tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');
        $content_manufacturers .= tep_bootstrap_pull_down_menu('filter_id', $options, 'id_select_manufacturer', (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"', null, true);
		
        $content_manufacturers .= tep_hide_session_id() . '</p></form></div>' . "\n";
      }
      }
      
	  $content_width = (int)MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_CONTENT_WIDTH;		  
      ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/products_manufacturers.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Index Products Manufacturers Select Module', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS', 'True', 'Do you want to enable the Index Products Manufacturers Select  content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " .  $multi_stores_config   . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_CONTENT_WIDTH', 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_SORT_ORDER');
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_MANUFACTURERS_STATUS == 'False');

    }			
  }
?>