<?php
/*
  $Id: cm_sc_product_listing.php
  $Loc: catalog/includes/modules/content/shopping_cart/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class cm_sc_product_listing {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SC_PRODUCT_LISTING_TITLE;
      $this->description = MODULE_CONTENT_SC_PRODUCT_LISTING_DESCRIPTION;

      if ( defined('MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SC_PRODUCT_LISTING_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $cart, $products, $currencies, $languages_id, $any_out_of_stock;
	  
	  $content_width = (int)MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH;
	  
	  if ($cart->count_contents() > 0) {
	  	
		$any_out_of_stock = 0;
		$products = $cart->get_products();
		for ($i=0, $n=sizeof($products); $i<$n; $i++) {
	// Push all attributes information in an array
		  if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
			while (list($option, $value) = each($products[$i]['attributes'])) {
			  $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, popt.products_options_track_stock, pa.options_values_price, pa.price_prefix, pa.products_attributes_id
										  from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
										  where pa.products_id = '" . (int)$products[$i]['id'] . "'
										   and pa.options_id = '" . (int)$option . "'
										   and pa.options_id = popt.products_options_id
										   and pa.options_values_id = '" . (int)$value . "'
										   and pa.options_values_id = poval.products_options_values_id
										   and popt.language_id = '" . (int)$languages_id . "'
										   and poval.language_id = '" . (int)$languages_id . "'");									   
			  $attributes_values = tep_db_fetch_array($attributes);

			  // OTF contrib begins
			  if ($value == OPTIONS_VALUE_TEXT_ID) {                       
				
				$attr_value = $products[$i]['attributes_values'][$option] . 
				  tep_draw_hidden_field('id[' . $products[$i]['id'] . '+++' .
				  $i . '][' . TEXT_PREFIX . $option . ']',  
				  $products[$i]['attributes_values'][$option]);
				  
				$attr_name_sql_raw = 'SELECT po.products_options_name FROM ' .
				  TABLE_PRODUCTS_OPTIONS . ' po, ' .
				  TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
				  ' pa.products_id="' . tep_get_prid($products[$i]['id']) . '" AND ' .
				  ' pa.options_id="' . $option . '" AND ' .
				  ' pa.options_id=po.products_options_id AND ' .
				  ' po.language_id="' . $languages_id . '" ';
				$attr_name_sql = tep_db_query($attr_name_sql_raw);
				if ($arr = tep_db_fetch_array($attr_name_sql)) {
				  $attr_name  = $arr['products_options_name'];
				}
				
			  } else {
				
				
				$attr_value = $attributes_values['products_options_values_name'] . 
				  tep_draw_hidden_field('id[' . $products[$i]['id'] . '+++' . 
				  $i. '][' . $option . ']', $value);
				$attr_name  = $attributes_values['products_options_name'];
				
			  }
			  // OTF contrib ends
	 
			  // OTF contrib begins
			  $products[$i][$option]['products_options_name'] = $attr_name;
			  $products[$i][$option]['options_values_id'] = $value; 
			  $products[$i][$option]['products_options_values_name'] = $attr_value ;
			  $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
			  $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
			  $products[$i][$option]['track_stock'] = $attributes_values['products_options_track_stock'];		  

			  // get attribute price for the group	   
			  $customer_group_id = $_SESSION['sppc_customer_group_id'];	  
			  if ( $customer_group_id != '0') { 
				$pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . 
										   TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " 
										  where products_id = '" . (int)$products[$i]['id'] . "' and 
												products_attributes_id = '" . $attributes_values['products_attributes_id']  . "'  and 
												customers_group_id = '" . $customer_group_id . "'");																
				$cg_attr_prices = tep_db_fetch_array($pag_query) ;
				if (tep_not_null($cg_attr_prices)) {
				  $products[$i][$option]['price_prefix']  = $cg_attr_prices['price_prefix'];
				  $products[$i][$option]['options_values_price']  = $cg_attr_prices['options_values_price'];		 		 
				}
			  }		  
			  
			}
		  }
		}	
 

        $products_name  = '<table class="table table-striped table-condensed">';
    	$products_name .= '  <tbody>';

      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
		  
      	$products_name .= '   <tr>';		  
		  
	    if (STOCK_CHECK == 'true') {
// bof qtpro 461	
//        $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
           if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
             $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity'], $products[$i]['attributes']); 
           }else{
             $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
           }
// eof qtpro 461
           if (tep_not_null($stock_check)) {
            $any_out_of_stock = 1;
           }
        } else {
          $stock_check = '';
        }	

        for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      	   $products_name .= '   <tr>';

      	   $products_name .= '      <td valign="top" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
                        	'      <td valign="top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><strong>' . $products[$i]['name'] . '</strong></a>';

           if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
             reset($products[$i]['attributes']);
                $products_name .= '<br />'. PHP_EOL ;			 
             while (list($option, $value) = each($products[$i]['attributes'])) {
//BOF Option Types v2.3.1 - Rearanged Product(s) cart-listing, added Options Column, Upload preview link, and added Prices to Attributes
                $imageDir = (tep_session_registered) ? UPL_DIR : TMP_DIR;
                $image_link1 = '';
                $image_link2 = '';
                if (file_exists($imageDir.$products[$i][$option]['products_options_values_name'])) {
                   $image_link1 = '<a href="' . $imageDir . $products[$i][$option]['products_options_values_name'] . '" target="_blank">';
                   $image_link2 = tep_image(DIR_WS_ICONS . 'view.gif') . '</a>';
                }
                $Option_Price = ($products[$i][$option]['options_values_price'] != '0') ? ' - (' . $products[$i][$option]['price_prefix'] . $currencies->display_price($products[$i][$option]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id'])) . ')' : '';
                $products_name .= '<small>- ' . $products[$i][$option]['products_options_name'] . ': <i>' . $image_link1 . $products[$i][$option]['products_options_values_name'] . $image_link2 . '</i>' . $Option_Price . '</small><br />';
             }
           }


        $products_name .= '<br>' . tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'style="width: 65px;" min="0"', 'number') . tep_draw_hidden_field('products_id[]', $products[$i]['id']) . ' ' . 
      									   tep_draw_button(NULL, 'refresh', NULL, NULL, NULL, 'btn-info btn-xs') . ' ' . tep_draw_button(NULL, 'trash', tep_href_link(FILENAME_SHOPPING_CART, 'products_id=' . $products[$i]['id'] . '&action=remove_product'), NULL, NULL, 'btn-danger btn-xs');
											
      									   $products_name .= '     </td>';

      									   $products_name .= '     <td class="text-right"><strong>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</strong></td>' .
      									   									 '   </tr>';
      }
      }

		$products_name .= ' </tbody>';
		$products_name .= '</table>';

		ob_start();
		include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/product_listing.php');
		$template = ob_get_clean();

		$oscTemplate->addContent($template, $this->group);
	  } // end if $cart->count_contents() > 0
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Product Listing', 'MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
	  tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_PRODUCT_LISTING_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
   }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS', 'MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH', 'MODULE_CONTENT_SC_PRODUCT_LISTING_SORT_ORDER');
    }
    
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS'");
            $this->enabled = (MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS'");
            $this->enabled = (MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS == 'False');

    }	
  }
?>