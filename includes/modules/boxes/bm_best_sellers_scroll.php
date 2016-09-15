<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License


SCROLL bm_best_sellers_scroll.php
created: O.F.Y. - osCommerce for You - webáruház készítés
   http://www.oscommerceforyou.hu
  email: info@oscommerceforyou.hu
*/

  class bm_best_sellers_scroll {
    var $code = 'bm_best_sellers_scroll';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;	

    function bm_best_sellers_scroll() {
      $this->title = MODULE_BOXES_BEST_SELLERS_SCROLL_TITLE;
      $this->description = MODULE_BOXES_BEST_SELLERS_SCROLL_DESCRIPTION;

      if ( defined('MODULE_BOXES_BEST_SELLERS_SCROLL_STATUS') ) {
        $this->sort_order = MODULE_BOXES_BEST_SELLERS_SCROLL_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_BEST_SELLERS_SCROLL_STATUS == 'True');
		$this->pages = MODULE_BOXES_BEST_SELLERS_SCROLL_DISPLAY_PAGES;

//        $this->group = ((MODULE_BOXES_BEST_SELLERS_SCROLL_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
		$placement = MODULE_BOXES_BEST_SELLERS_SCROLL_CONTENT_PLACEMENT;
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
   global $HTTP_GET_VARS, $current_category_id, $languages_id, $currencies, $oscTemplate;
// BOF Hide products and categories from groups 
// bof multi stores
// bof sppc     
     $customer_group_id = tep_get_cust_group_id() ;
// eof sppc
     
  if (isset($current_category_id) && ($current_category_id > 0)) {
    $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name,p.products_price, p.products_tax_class_id,
                          pd.products_description, p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c 
			where p.products_status = '1'               and c.categories_status = '1' 
			  and p.products_ordered > 0                and p.products_id = pd.products_id 
			  and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id 
			  and p2c.categories_id = c.categories_id           and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) 
			  and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
			  and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
			  order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } else {
   $best_sellers_query = tep_db_query("select distinct p.products_id, pd.products_name,p.products_price, p.products_tax_class_id,
                          pd.products_description, p.products_image from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . TABLE_CATEGORIES . " c using(categories_id) 
			where p.products_status = '1'                       and c.categories_status = '1' 
			  and p.products_ordered > 0                        and p.products_id = pd.products_id 
			  and pd.language_id = '" . (int)$languages_id . "' 
			  and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
			  and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
			  order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  }
 // EOF Hide products and categories from groups	        

        if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
          $carrousel_indcator = '<ol>';
		  $active_carrousel = " active" ;
          $number_carrousel = 0 ;		  
          while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
		  
		      $p_price = '<span class="label label-default">' . $currencies->display_price($best_sellers['products_price'], tep_get_tax_rate($best_sellers['products_tax_class_id'])) . '</span>';

	          if ( $customer_group_id != 0 ) {
                 $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" .  $best_sellers['products_id']  . "' and customers_group_id =  '" . $customer_group_id . "'");
      
                 if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {

                    if (tep_not_null($customer_group_price['customers_group_price'])) {
                       $p_price = '<span class="label label-default">' . $currencies->display_price($customer_group_price['customers_group_price'], tep_get_tax_rate($best_sellers['products_tax_class_id'])). '</span>';
                    } 
                 }
              } // end if ($this->cg_id != '0')
              // now get the specials price for this customer_group 
              $special_price_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = " .  $best_sellers['products_id']  . " and status = '1' and customers_group_id = '" . $customer_group_id . "'");
              if ($specials_price = tep_db_fetch_array($special_price_query)) {
                $p_price = '<s>' .  $p_price . '</s><br /><span class="label label-success">' . $currencies->display_price($specials_price['specials_new_products_price'], tep_get_tax_rate($best_sellers['products_tax_class_id'])) . '</span>'; 	  
              }	  	
              $p_price .= '' ;  
		  
            $carrousel_indcator .= '<li data-target="'. tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) .  
			                          '" data-slide-to="'. $number_carrousel . '" class="' . $active_carrousel . '"></li>' ;

            $product_image_query = tep_db_query("select image from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" .  $best_sellers['products_id']  .  "' limit 1");
			if (tep_db_num_rows($product_image_query) >  0) {
//			   $product_image       = tep_db_fetch_array($product_image_query) ;			  
//               $carrousel_item     .= '<div class="item' . $active_carrousel . '">' .
//                                      '   <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers["products_id"]) . '">' . 
//									                   tep_image(DIR_WS_IMAGES . $product_image['image'], $best_sellers['products_name'], 300, 300 ) . '</a><br />' .  
//                                      '</div>' ;

              $product_image_query = tep_db_query("select image from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" .  $best_sellers['products_id']  .  "' limit 1");
		      if (tep_db_num_rows($product_image_query) >  0) {
			     $product_image       = tep_db_fetch_array($product_image_query) ;			  
				 $scroll_image = $product_image['image'] ;
			  } else {
			     $scroll_image = $best_sellers['products_image'] ;
			  }
                 $carrousel_item .= '<div class="item ' . $active_carrousel . '">' .
                                        '   <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers["products_id"]) . '">' . 
					  				                     tep_image(DIR_WS_IMAGES . $scroll_image, $best_sellers['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT ) .  
										'   </a>' .														 
                                        '     ' . $best_sellers['products_name'] . '<br />' . 
							            '     <span class="label label-default"><del>' . $currencies->display_price($best_sellers['products_price'], tep_get_tax_rate($best_sellers['products_tax_class_id'])) . '</del></span>' .
							            '     <span class="label label-success">' . $currencies->display_price($best_sellers['specials_new_products_price'], tep_get_tax_rate($best_sellers['products_tax_class_id'])) . '</span>' .
                                        '</div>' ;									  
			}
			$active_carrousel = '' ;
			$number_carrousel++ ;
			$name .= '<>'. $best_sellers['products_name'] ;
		  } // while ($best_sellers = tep_db_fetch_ar
		   $carrousel_indcator .= '</ol>';
		  $title = True ;	
	       ob_start();
             include(DIR_WS_MODULES . 'boxes/templates/best_sellers_scroll.php') ;
           $data = ob_get_clean();		   
		 }
	return $data ; 
}	

 function execute() {
    global $HTTP_GET_VARS, $current_category_id, $languages_id, $currencies, $oscTemplate, $language, $cache;

    $customer_group_id = tep_get_cust_group_id() ;
   
    if (!isset($HTTP_GET_VARS['products_id'])) {  

//$data= $this->getData() ;
      if (( USE_CACHE == 'true') && ( MODULE_BOXES_BEST_SELLERS_SCROLL_USE_CACHE == 'True') )  {
	    $cache_name = 'boxes_best_sellers_scroll-' . $language . '-cg' . $customer_group_id . '.cache'  ;
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		  $data = $this->getData();
		  $cache->save_cache($cache_name, $data, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	    } else {
	  	  $data = $cache->get_cache($cache_name, 'RETURN');	  
	    }  		
      } else {
        $data = $this->getData();
      }
      $oscTemplate->addBlock($data, $this->group);     
    }
}  
	
    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_BEST_SELLERS_SCROLL_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Best sellers Module', 'MODULE_BOXES_BEST_SELLERS_SCROLL_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_BEST_SELLERS_SCROLL_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_BEST_SELLERS_SCROLL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_BEST_SELLERS_SCROLL_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache for Box Best Sellers Scrolling', 'MODULE_BOXES_BEST_SELLERS_SCROLL_USE_CACHE', 'True', 'If the shop cache is activated. Activate the cache for the Box Best Sellers Scrolling', '6', '8', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");	  
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_BEST_SELLERS_SCROLL_STATUS', 'MODULE_BOXES_BEST_SELLERS_SCROLL_CONTENT_PLACEMENT', 'MODULE_BOXES_BEST_SELLERS_SCROLL_SORT_ORDER', 'MODULE_BOXES_BEST_SELLERS_SCROLL_USE_CACHE', 'MODULE_BOXES_BEST_SELLERS_SCROLL_DISPLAY_PAGES');
    }
  }
?>