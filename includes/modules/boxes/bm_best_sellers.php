<?php
/* $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_best_sellers {
    var $code = 'bm_best_sellers';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_best_sellers() {
      $this->title = MODULE_BOXES_BEST_SELLERS_TITLE;
      $this->description = MODULE_BOXES_BEST_SELLERS_DESCRIPTION;

      if ( defined('MODULE_BOXES_BEST_SELLERS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_BEST_SELLERS_SORT_ORDER;
 
        $this->enabled = (MODULE_BOXES_BEST_SELLERS_STATUS == 'True');

/* BOF*/$this->pages = MODULE_BOXES_BEST_SELLERS_DISPLAY_PAGES;
		$placement = MODULE_BOXES_BEST_SELLERS_CONTENT_PLACEMENT;
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
      global $HTTP_GET_VARS, $current_category_id, $languages_id, $oscTemplate;

      if (!isset($HTTP_GET_VARS['products_id'])) {
// bof multi stores
         $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	
      }
     
      if (isset($current_category_id) && ($current_category_id > 0)) {
        $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c 
	      where p.products_status = '1'                       and c.categories_status = '1' 
		    and p.products_ordered > 0                        and p.products_id = pd.products_id 
		    and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id 
		    and p2c.categories_id = c.categories_id           and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) 
		    and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0    and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
		    and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
      } else {
        $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . TABLE_CATEGORIES . " c using(categories_id) 
          where p.products_status = '1'                       and c.categories_status = '1' 
		    and p.products_ordered > 0                        and p.products_id = pd.products_id 
		    and pd.language_id = '" . (int)$languages_id . "' 
		    and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0      and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0  
		    and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
		    order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
      }

      if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
          $bestsellers_list = '<ol style="margin: 0; padding-left: 25px;">';
          while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
            $bestsellers_list .= '<li><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . $best_sellers['products_name'] . '</a></li>';
          }
          $bestsellers_list .= '</ol>';
		  $title = True ;
/*		  $placement = MODULE_BOXES_BEST_SELLERS_CONTENT_PLACEMENT;
          switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Head Column" :
			case "Foot Column" :	
                $title = True ;				
				break;
				
            case 'Left Header' :
            case 'Center Header' : 
            case 'Right Header' : 
            case 'Left Footer' : 
            case 'Center Footer' :
            case 'Right Footer' : 				
			case "Bread Column" :
            case 'Header Line' :			
            case 'Footer Line' : 
                $title = False ;
				break;	  					
	      }  
*/		  

	      ob_start();
             include(DIR_WS_MODULES . 'boxes/templates/best_sellers.php');
          $data = ob_get_clean();		  
          $oscTemplate->addBlock($data, $this->group);
        }
      //}
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_BEST_SELLERS_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Best Sellers Module', 'MODULE_BOXES_BEST_SELLERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_BEST_SELLERS_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_BEST_SELLERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_BEST_SELLERS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_BEST_SELLERS_STATUS', 'MODULE_BOXES_BEST_SELLERS_CONTENT_PLACEMENT', 'MODULE_BOXES_BEST_SELLERS_SORT_ORDER', 'MODULE_BOXES_BEST_SELLERS_DISPLAY_PAGES');
    }
  }
?>