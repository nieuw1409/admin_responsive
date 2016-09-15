<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_product_attributes {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_product_attributes() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_TITLE;
      $this->description = MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_DESCRIPTION;

      if ( defined('MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $_GET, $languages_id, $pf, $currencies, $currency, $product_info, $cart, $HTTP_GET_VARS ;
	  
	  
	  
      
      $content_width = (int)MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_CONTENT_WIDTH;
	  
	  $placement = MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_ALIGN_TEXT;
      switch($placement) {
			case "Left" :
				$align_text = 'text-left';
				break;
			case "Right" :
				$align_text = 'text-right';
				break;
			case "Center" :
				$align_text = 'text-center';
				break;					
	  }	

      $customer_group_id = tep_get_cust_group_id() ;
	   
// bof 231 option type
      $number_of_uploads = 0;
      $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . 
	                                                                               TABLE_PRODUCTS_ATTRIBUTES . " patrib 
	                      where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' 
	                        and find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0 order by patrib.products_options_sort_order");
	  
// EOF SPPC Hide attributes from customer groups	
      $products_attributes = tep_db_fetch_array($products_attributes_query);
      if ($products_attributes['total'] > 0) {

        $prod_attributes .= '<div class="form-group"> ' . PHP_EOL ;
        $prod_attributes .= '       <div class="col-sm-3 col-xs-6 label label-info text-right" role="label"><h5>' . TEXT_PRODUCT_OPTIONS . '</h5></div>' . PHP_EOL ;
        $prod_attributes .= '	</div>	' . PHP_EOL ;
        $prod_attributes .= '    <p>' . PHP_EOL ;

	    $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment, popt.products_options_track_stock
                                          from " . TABLE_PRODUCTS_OPTIONS . " popt, " . 
											       TABLE_PRODUCTS_ATTRIBUTES . " patrib 
										  where patrib.products_id='" . (int)$product_info['products_id'] . "' and 
											    patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' and 
											    find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0 order by patrib.products_options_sort_order" ) ;
											 
// are there are select attributes with stock 
	    $stock_attributes = 'false' ;										 
        while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {

// begin Option Types v2.3.1 - Include option_types.php - Contains all Option Types, other than the original Drowpdown...
          require(DIR_WS_MODULES . FILENAME_OPTION_TYPES );
// end Option Types v2.3.1 - Include option_types.php - Contains all Option Types, other than the original Drowpdown...
          if ( ($Default == true) && ( !$products_options_name[ 'products_options_track_stock' ] ) ) {  // Option Types v2.3.1 - Default action is (standard) dropdown list. If something is not correctly set, we should always fall back to the standard.
		
            $products_options_array = array();
            $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' and find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0 order by pa.products_options_sort_order");
		    $list_of_prdcts_attributes_id = '';
		    $products_options = array(); // makes sure this array is empty again
            while ($_products_options = tep_db_fetch_array($products_options_query)) {
		      $products_options[] = $_products_options;
		      $list_of_prdcts_attributes_id .= $_products_options['products_attributes_id'].",";
	        }

            if (tep_not_null($list_of_prdcts_attributes_id) && $customer_group_id != '0') { 
              $select_list_of_prdcts_attributes_ids = "(" . substr($list_of_prdcts_attributes_id, 0 , -1) . ")";
	          $pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . 
			                TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " 
							where products_attributes_id IN " . $select_list_of_prdcts_attributes_ids . " 
							AND customers_group_id = '" . $customer_group_id . "'");
	          while ($pag_array = tep_db_fetch_array($pag_query)) {
		        $cg_attr_prices[] = $pag_array;
	          }

	          // substitute options_values_price and prefix for those for the customer group (if available)
	          if ($customer_group_id != '0' && tep_not_null($cg_attr_prices)) {
                foreach ($products_options as $produc_options_variable) {		 
	              foreach ($cg_attr_prices as $attri_price_variable) {		
		            if ($attri_price_variable['products_attributes_id'] == $produc_options_variable['products_attributes_id']) {
				      $produc_options_variable['price_prefix'] = $attri_price_variable['price_prefix'];
				      $produc_options_variable['options_values_price'] = $attri_price_variable['options_values_price'];
			        }
		          } // end for ($i = 0; $i < count($cg_att_prices) ; $i++)
	            }
              } // end if ($customer_group_id != '0' && (tep_not_null($cg_attr_prices))
            } // end if (tep_not_null($list_of_prdcts_attributes_id) && $customer_group_id != '0')

            foreach ($products_options as $some_variable) {
              $products_options_array[] = array('id' => $some_variable['products_options_values_id'], 'text' => $some_variable['products_options_values_name']);
              if ($some_variable['options_values_price'] != '0') {
                $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $some_variable['price_prefix'] . $currencies->display_price($some_variable['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
              }  
            }
// EOF SPPC attributes mod
            if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
              $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
            } else {
              $selected_attribute = false;
            }

            $prod_attributes .= '          <div class="form-group"> '. PHP_EOL ;
            $prod_attributes .= '             <label for="id[' . $ProdOpt_ID . ']" class="col-sm-3 label label-info"><h6>' .  $ProdOpt_Name . ' :</h6></label>'. PHP_EOL ;
            $prod_attributes .= '		      <div class="col-sm-9">'. PHP_EOL ;
//            $prod_attributes .= '               ' . tep_draw_pull_down_menu('id[' . $ProdOpt_ID . ']', $products_options_array, $selected_attribute) . ' &nbsp; ' . $ProdOpt_Comment . PHP_EOL ;
            $prod_attributes .= '               ' . tep_bootstrap_pull_down_menu('id[' . $ProdOpt_ID . ']', $products_options_array, 'id'.$ProdOpt_ID, $selected_attribute ) . ' &nbsp; ' . $ProdOpt_Comment . $products_options[0]['options_values_price'] . PHP_EOL ;
            $prod_attributes .= '             </div>'. PHP_EOL ;
            $prod_attributes .= '          </div>		  '. PHP_EOL ;
            $prod_attributes .= '	       <br /><br />'. PHP_EOL ;

	      } elseif ( $products_options_name[ 'products_options_track_stock' ] )  {
   	        // well there are select attributes with stock 
		    $stock_attributes = 'true' ;
	      } // End if Default=true
        } // end while ($products_options_name = tep_db_fetch_arra
// eof 231 option types

// qt pro 461 show stock related select attributes
// well there are select attributes with stock 
        if ( $stock_attributes == 'true' ) {
           $products_id=(preg_match("/^\d{1,10}(\{\d{1,10}\}\d{1,10})*$/",$HTTP_GET_VARS['products_id']) ? $HTTP_GET_VARS['products_id'] : (int)$HTTP_GET_VARS['products_id']); 
           require(DIR_WS_CLASSES . 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN . '.php');
           $class = 'pad_' . PRODINFO_ATTRIBUTE_PLUGIN;
           $pad = new $class($products_id, $attributes_availability_nostock);
           $prod_attributes .= $pad->draw();
        } 
    

//        //Display a table with which attributecombinations is on stock to the customer?
//        if(PRODINFO_ATTRIBUTE_DISPLAY_STOCK_LIST == 'True') {
 //         require(DIR_WS_MODULES . FILENAME_QT_STOCK_TABLE ) ;
//        }

//++++ QT Pro: End Changed Code

        $prod_attributes .= '	    </p>   '. PHP_EOL ;

      } // endif if ($products_attributes['total'] > 0) {


	  ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/product_attributes.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Options Module', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS', 'True', 'Should the Product Options block be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Align Text Product Availability', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_ALIGN_TEXT', 'Center', 'Align the Text for this module ?', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Center\', \'Right\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_CONTENT_WIDTH', 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_SORT_ORDER');
// , 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_ALIGN_TEXT'	  
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_PRODUCT_ATTRIBUTES_STATUS == 'False');

    }		
  }
?>