<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_specials {
    var $code = 'bm_specials';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_specials() {
      $this->title = MODULE_BOXES_SPECIALS_TITLE;
      $this->description = MODULE_BOXES_SPECIALS_DESCRIPTION;
	  $this->pages = MODULE_BOXES_SPECIALS_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_SPECIALS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SPECIALS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SPECIALS_STATUS == 'True');

      $placement = MODULE_BOXES_SPECIALS_CONTENT_PLACEMENT;
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
      global $HTTP_GET_VARS, $languages_id, $currencies, $oscTemplate;
      define(N_RANDOM_SELECT_SPECIALS, 3);	  

  if (!isset($HTTP_GET_VARS['products_id'])) {
// BOF Separate Pricing Per Customer
    $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	

  if ($random_product = tep_random_select("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  
                     where c.categories_status='1'                       and p.products_id = p2c.products_id 
					   and p2c.categories_id = c.categories_id           and p.products_status = '1' 
					   and p.products_id = s.products_id                 and pd.products_id = s.products_id 
					   and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' 
					   and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0  
					   and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0 and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
					   and s.customers_group_id= '".$customer_group_id."' order by s.specials_date_added desc limit " . N_RANDOM_SELECT_SPECIALS)) {
						   
       $title = true ;
       $specials_content = '  <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"]) . '">' . 
			                 '' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, null, null,null, false) . '</a><br />' .
							 '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br />' .
							 '<span class="label label-default"><del>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</del></span>' .
							 '<span class="label label-success">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
       ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/specials.php');
       $data = ob_get_clean();  	   
  	   $oscTemplate->addBlock($data, $this->group);
      }
     }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SPECIALS_STATUS');
    }

    function install() {
	  global $multi_stores_config;	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Specials Module', 'MODULE_BOXES_SPECIALS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SPECIALS_CONTENT_PLACEMENT', 'Right Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SPECIALS_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SPECIALS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SPECIALS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_SPECIALS_STATUS', 'MODULE_BOXES_SPECIALS_CONTENT_PLACEMENT', 'MODULE_BOXES_SPECIALS_SORT_ORDER', 'MODULE_BOXES_SPECIALS_DISPLAY_PAGES');
    }
  }
?>