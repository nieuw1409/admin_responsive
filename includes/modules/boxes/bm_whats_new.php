<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_whats_new {
    var $code = 'bm_whats_new';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_whats_new() {
      $this->title = MODULE_BOXES_WHATS_NEW_TITLE;
      $this->description = MODULE_BOXES_WHATS_NEW_DESCRIPTION;
	  $this->pages = MODULE_BOXES_WHATS_NEW_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_WHATS_NEW_STATUS') ) {
        $this->sort_order = MODULE_BOXES_WHATS_NEW_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_WHATS_NEW_STATUS == 'True');

   //     $this->group = ((MODULE_BOXES_WHATS_NEW_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
     $placement = MODULE_BOXES_WHATS_NEW_CONTENT_PLACEMENT;
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
      global $currencies, $oscTemplate;

      $customer_group_id = tep_get_cust_group_id() ;
      if ($random_product = tep_random_select("select distinct p.products_id, p.products_image, p.products_tax_class_id, p.products_price from " 
             . TABLE_PRODUCTS . " p, " 
			 . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " 
			 . TABLE_CATEGORIES . " c 
			     where p.products_status = '1' 
				   and p.products_id = p2c.products_id 
				   and c.categories_id = p2c.categories_id 
				   and c.categories_status=1 
				   and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 
				   and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
				   and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
				   order by products_date_added desc limit " . MAX_RANDOM_SELECT_NEW)) {
 // EOF Hide products and categories from groups  
  // EOF Enable & Disable Categories	  
        $random_product['products_name'] = tep_get_products_name($random_product['products_id']);
        $random_product['specials_new_products_price'] = tep_get_products_special_price($random_product['products_id']);

        if (tep_not_null($random_product['specials_new_products_price'])) {
          $whats_new_price = '<del>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</del><br />';
          $whats_new_price .= '<span class="productSpecialPrice">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
        } else {
          $whats_new_price = $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
        }
		$title = True ;
/*		
		$placement = MODULE_BOXES_WHATS_NEW_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Foot Column" :
			case "Head Column" :				
  			  $data = '<div class="panel panel-default panel-primary">' .
                      '  <div class="panel-heading"><a class="label label-info" href="' . tep_href_link(FILENAME_PRODUCTS_NEW) . '">' . MODULE_BOXES_WHATS_NEW_BOX_TITLE . '</a></div>' .
                      '  <div class="panel-body" style="text-align: center;">' .
				      '       <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'. 
				      '       <br />' . 
				      '       <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a>'. 
				      '       <br />' . $whats_new_price . 
                      '   </div>' .
					  '</div>';
				break;
           case 'Left Header' : 
            case 'Center Header' : 
            case 'Right Header' :           
            case 'Left Footer' : 
            case 'Center Footer' :
            case 'Right Footer' :
            case 'Header Line' :			
            case 'Footer Line' : 			
			case "Bread Column" :
  			  $data = '<div class="panel panel-default panel-primary">' .
                      '  <div class="panel-body" style="text-align: center;">' .
				      '       <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'. 
				      '       <br />' . 
				      '       <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a>'. 
				      '       <br />' . $whats_new_price . 
                      '   </div>' .
					  '</div>';
				break;
	   }
*/	
       $whats_new_content = '  <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product["products_id"]) . '">' . 
			                 '' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, null, null,null, false) . '</a><br />' .
							 '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br />' .
							 '<span class="label label-default"><del>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</del></span>' .
							 '<span class="label label-success">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';
        ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/whats_new.php');
        $data = ob_get_clean();     
        $oscTemplate->addBlock($data, $this->group);
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_WHATS_NEW_STATUS');
    }

    function install() {
	  global $multi_stores_config;	  
       $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
        }	
	  // standaard usage
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable What\'s New Module', 'MODULE_BOXES_WHATS_NEW_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_WHATS_NEW_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_WHATS_NEW_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_WHATS_NEW_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '20','tep_cfg_select_pages(' , now())");	       
      }

    function remove() {
	  global $multi_stores_config;	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_WHATS_NEW_STATUS', 'MODULE_BOXES_WHATS_NEW_CONTENT_PLACEMENT', 'MODULE_BOXES_WHATS_NEW_SORT_ORDER', 'MODULE_BOXES_WHATS_NEW_DISPLAY_PAGES');
    }
  }
?>