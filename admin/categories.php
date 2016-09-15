<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
//  $stores_to_cats = $HTTP_POST_VARS['stores_cat'] ;
  
//++++ QT Pro: Begin Added code
	//Create the product investigation for this product that are used in this page.
	$product_investigation = qtpro_doctor_investigate_product($HTTP_GET_VARS['pID']);
//++++ QT Pro: End Added code

// multi stores
  // check if there more then 1 store active
  $stores_query = tep_db_query( "select stores_id from " . TABLE_STORES ) ;
  if ( tep_db_num_rows( $stores_query ) > 1 ) $stores_multi_present = 'true' ;

  require(DIR_WS_CLASSES . 'class.get.image.php');
  
  require(DIR_WS_CLASSES . 'tax.php');  

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// eric optimize include CountProductsAdmin object for use on the admin side
  require(DIR_FS_ADMIN. DIR_WS_CLASSES . 'CountProductsAdmin.php');
  $countproducts = new CountProductsAdmin();  
  
// BOF qpbpp
  // include the price formatter for the price breaks contribution
  require(DIR_WS_CLASSES . 'PriceFormatter.php');
  $pf = new PriceFormatter;
// EOF qpbpp  

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  
  $action_attribute = (isset($HTTP_GET_VARS['action_attribute']) ? $HTTP_GET_VARS['action_attribute'] : '');
  $option_page = (($HTTP_GET_VARS['option_page']) && is_numeric($HTTP_GET_VARS['option_page'])) ? $HTTP_GET_VARS['option_page'] : 1;
  $value_page = (($HTTP_GET_VARS['value_page']) && is_numeric($HTTP_GET_VARS['value_page'])) ? $HTTP_GET_VARS['value_page'] : 1;
  $attribute_page = (($HTTP_GET_VARS['attribute_page']) && is_numeric($HTTP_GET_VARS['attribute_page'])) ? $HTTP_GET_VARS['attribute_page'] : 1;
  
  $action_attribute_stock = (isset($HTTP_GET_VARS['action_attribute_stock']) ? $HTTP_GET_VARS['action_attribute_stock'] : '');  
  $attribute_stock_page = (($HTTP_GET_VARS['attribute_stock_page']) && is_numeric($HTTP_GET_VARS['attribute_stock_page'])) ? $HTTP_GET_VARS['attribute_stock_page'] : 1;  

  $page_info = 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page . '&attribute_stock_page=' . $attribute_stock_page;
  
// INDIV_PM BEGIN
  if(!empty($_POST)){
	if(!empty($_POST['payment_methods'])){
		$payment_methods = implode(';',$_POST['payment_methods']);
  	}else{
		$payment_methods = NULL;
	}
  }
// INDIV_PM END
// BOF SPPC, attributes groups modification
  $customers_groups_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . "  order by customers_group_id");

  while ($_customers_groups = tep_db_fetch_array($customers_groups_query)) {
     $customers_groups_attributes[] = $_customers_groups;
  }
// EOF SPPC, attributes groups modification 
  $price_prefix_array = array(array('id' => '+', 'text' => '+'),
                              array('id' => '-', 'text' => '-'));	 
							  
  if (tep_not_null($action_attribute_stock)) {
    switch ($action_attribute_stock) {
      case 'delete_attribute_stock':
	    $products_stock_id = tep_db_prepare_input($HTTP_GET_VARS['products_stock_id']);
		tep_db_query("delete from " . TABLE_PRODUCTS_STOCK . " where products_stock_id='" . $products_stock_id . "'");
		
	    $query_sum = tep_db_query("select sum(products_stock_quantity) as summa from " . TABLE_PRODUCTS_STOCK . " where products_id=" . (int)$HTTP_GET_VARS['pID'] . " and products_stock_quantity>0");
        
		$list = tep_db_fetch_array($query_sum);
            
		$summa = (empty($list['summa'])) ? 0 : $list['summa'];
		
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity=" . $summa . " where products_id=" . (int)$HTTP_GET_VARS['pID']);
            
		if (($summa<1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
             tep_db_query("update " . TABLE_PRODUCTS . " set products_status='0' where products_id=" . (int)$HTTP_GET_VARS['pID']);
        }		
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_stock'. '&' . $page_info));		
	  break;
      case 'update_attribute_stock':
	    $products_stock_id       = tep_db_prepare_input($HTTP_GET_VARS['products_stock_id']);
	    $products_stock_quantity = tep_db_prepare_input($HTTP_POST_VARS['products_stock_quantity']);		
		tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set products_stock_quantity=" . (int)$products_stock_quantity . " where products_stock_id='" . $products_stock_id . "'");
		
	    $query_sum = tep_db_query("select sum(products_stock_quantity) as summa from " . TABLE_PRODUCTS_STOCK . " where products_id=" . (int)$HTTP_GET_VARS['pID'] . " and products_stock_quantity>0");
        
		$list = tep_db_fetch_array($query_sum);
            
		$summa = (empty($list['summa'])) ? 0 : $list['summa'];
		
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity=" . $summa . " where products_id=" . (int)$HTTP_GET_VARS['pID']);
            
		if (($summa<1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
             tep_db_query("update " . TABLE_PRODUCTS . " set products_status='0' where products_id=" . (int)$HTTP_GET_VARS['pID']);
        }		
		
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_stock' . '&' . $page_info));		
	  break;
      case 'insert_attribute_stock':
	    $products_stock_quantity = tep_db_prepare_input($HTTP_POST_VARS['products_stock_quantity']);		  
    
	    while(list($v1,$v2)=each($HTTP_POST_VARS)) {
         if (preg_match("/^option(\d+)$/",$v1,$m1)) {
            if (is_numeric($v2) and ($v2==(int)$v2)) $val_array[]=$m1[1]."-".$v2;
  
         }
        }
//        foreach ($HTTP_POST_VARS as $value) {
//           $product_stock_id .= $value[ 0 ] . ',' ;
//        }
//        $product_stock_id = substr($product_stock_id,0,strlen($product_stock_id)-1); // remove last comma	 

        sort($val_array, SORT_NUMERIC);
        $product_stock_id=join(",",$val_array);
 
        $query_stock_present=tep_db_query("select products_stock_id from " . TABLE_PRODUCTS_STOCK . " where products_id=" . (int)$HTTP_GET_VARS['pID'] . " and products_stock_attributes='" . $product_stock_id . "' order by products_stock_attributes");
			 
        if (tep_db_num_rows($query_stock_present) > 0) {  // ok selected serie is already present update or delete it
                
		    $stock_item = tep_db_fetch_array($query_stock_present);
		
            $stock_id   = $stock_item['products_stock_id'];
        
		    if ($products_stock_quantity=intval($products_stock_quantity)) {
               tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set products_stock_quantity=" . (int)$HTTP_POST_VARS['products_stock_quantity'] . " where products_stock_id='" . $stock_id . "'");
//                } else {
//                   tep_db_query("delete from " . TABLE_PRODUCTS_STOCK . " where products_stock_id='" . $stock_id . "'");
                }
        } else { // new enry
               tep_db_query("insert into " . TABLE_PRODUCTS_STOCK . " values (0," . (int)$HTTP_GET_VARS['pID'] . ",'" . $product_stock_id . "','" . (int)$HTTP_POST_VARS['products_stock_quantity'] . "')");
        }
       
	    $query_sum = tep_db_query("select sum(products_stock_quantity) as summa from " . TABLE_PRODUCTS_STOCK . " where products_id=" . (int)$HTTP_GET_VARS['pID'] . " and products_stock_quantity>0");
        
		$list = tep_db_fetch_array($query_sum);
            
		$summa = (empty($list['summa'])) ? 0 : $list['summa'];
		
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity=" . $summa . " where products_id=" . (int)$HTTP_GET_VARS['pID']);
            
		if (($summa<1) && (STOCK_ALLOW_CHECKOUT == 'false')) {
             tep_db_query("update " . TABLE_PRODUCTS . " set products_status='0' where products_id=" . (int)$HTTP_GET_VARS['pID']);
        }
           
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_stock' . '&' . $page_info . '&test=' . $product_stock_id));	
	  break;

    }
  }	
  if (tep_not_null($action_attribute)) {
    switch ($action_attribute) {
      case 'update_product_attribute':

	    $tab_options = '' ;	  
	    $tab_values  = '' ;	 		  
//BOF - Zappo - Option Types v2 - Enforce rule that TEXT and FILE Options use value OPTIONS_VALUE_TEXT_ID
        $products_options_query = tep_db_query("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $HTTP_POST_VARS['options_id'] . "'");
        $products_options_array = tep_db_fetch_array($products_options_query);
        switch ($products_options_array['products_options_type']) {
          case OPTIONS_TYPE_TEXT:
          case OPTIONS_TYPE_TEXTAREA:
          case OPTIONS_TYPE_FILE:
            $values_id = OPTIONS_VALUE_TEXT_ID;
          break;
          default: 
          $values_id = tep_db_prepare_input($HTTP_POST_VARS['values_id']);
        }
        $products_id = tep_db_prepare_input($_GET['pID']);		
//        $products_id = $HTTP_POST_VARS['products_id'];
        $options_id = tep_db_prepare_input($HTTP_POST_VARS['options_id']);
//        $values_id = tep_db_prepare_input($HTTP_POST_VARS['values_id']);
//EOF - Zappo - Option Types v2 - Enforce rule that TEXT and FILE Options use value OPTIONS_VALUE_TEXT_ID
        $value_price = tep_db_prepare_input($HTTP_POST_VARS['value_price']);
        $price_prefix = tep_db_prepare_input($HTTP_POST_VARS['price_prefix']);
        $value_order = tep_db_prepare_input($HTTP_POST_VARS['value_order']);
        $attribute_id = tep_db_prepare_input($HTTP_POST_VARS['attribute_id']);
		
//        $_hide = array_keys( $HTTP_POST_VARS['hide'] ) ;  
		if ( isset($HTTP_POST_VARS['hide'] ) ) {
		   $_hide = array_keys( $HTTP_POST_VARS['hide'] ) ;  
		} else {
		   $_hide = array() ;
		}
        $attributes_hide_from_groups = '@,';
        if ( $_hide ) { // if any of the checkboxes are checked
           foreach($_hide as $val) {	
//		        foreach($HTTP_POST_VARS['hide'] as $val) {
//		        $attributes_hide_from_groups .= (int)$val . ','; 
		        $attributes_hide_from_groups .= tep_db_prepare_input($val) . ','; 
		   } // end foreach
	    }
		
        $attributes_hide_from_groups = substr($attributes_hide_from_groups,0,strlen($attributes_hide_from_groups)-1); // remove last comma		

        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . (int)$products_id . "', options_id = '" . (int)$options_id . "', options_values_id = '" . (int)$values_id . "', options_values_price = '" . (float)tep_db_input($value_price) . "', price_prefix = '" . tep_db_input($price_prefix) . "', products_options_sort_order = '" . tep_db_input($value_order) . "', attributes_hide_from_groups = '" . $attributes_hide_from_groups . "' where products_attributes_id = '" . (int)$attribute_id . "'");

        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_filename = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxcount']);

          if (tep_not_null($products_attributes_filename)) {
		    // update if filename is filled
            tep_db_query("replace into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " set products_attributes_id = '" . (int)$attribute_id . "', products_attributes_filename = '" . tep_db_input($products_attributes_filename) . "', products_attributes_maxdays = '" . tep_db_input($products_attributes_maxdays) . "', products_attributes_maxcount = '" . tep_db_input($products_attributes_maxcount) . "'");
          } else {
            // delete if filename is empty
            tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . (int)$attribute_id . "'");
          }
        }
		
// eric begin
		
		// continue with prices for customer groups other than retail
 
	    $no_of_customer_groups = count( $customers_groups_attributes);
	    for ($x = 0; $x < $no_of_customer_groups; $x++) {
		$input_group = $customers_groups_attributes[$x];
	    $test =  ' group = ' . $input_group[ 'customers_group_name'] ;
		$_array = $HTTP_POST_VARS[ $input_group[ 'customers_group_id'] . '_' . $input_group[ 'customers_group_name'] ] ;
		//= array_keys( $input_group[ 'customers_group_name'] )  ;
	    $test .= 'prijs = ' .$HTTP_POST_VARS[ $input_group[ 'customers_group_name'] ]['price'] ;
		   //$input_group = array_keys( $HTTP_POST_VARS( $name_group )  );
		   if ( $x != 0 ) {										
			   // from hidden field, 1 if already in products_attributes_groups, 0 if not
			   if ($_array['in_db'] == '1' && isset($_array['del'])) {
				  // delete customer group price
				  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id = '" . (int)$attribute_id . "' and customers_group_id = '" . (int)$input_group[ 'customers_group_id'] . "'");
			
			   } 
			   
			   if ($_array['in_db'] == '1' && !isset($_array['del'])) {
				  // update customer group price
				  if (isset($sql_data_array)) { unset($sql_data_array); }
				      $sql_data_array = array('options_values_price' => tep_db_prepare_input($_array['price']),
			                                  'price_prefix' => tep_db_prepare_input($_array['price_prefix']),				 
								              'products_id' => (int)$products_id ) ;										
										
				  tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES_GROUPS, $sql_data_array, 'update', "products_attributes_id = '" . (int)$attribute_id . "' and customers_group_id = '" . (int)$input_group[ 'customers_group_id'] . "'");
			   } // end elseif ($data_array['in_db'] == '1')
			
			   if ($_array['in_db'] == '0' && isset($_array['insert'])) {
				  // insert new row in products_attributes_groups
				  if (isset($sql_data_array)) { unset($sql_data_array); }
				    $sql_data_array = array('products_attributes_id' =>       (int)$attribute_id,
							     	        'customers_group_id' =>           (int)$input_group[ 'customers_group_id'] ,
						     		        'options_values_price' =>         tep_db_prepare_input($_array['price']),
			                                'price_prefix' =>                 tep_db_prepare_input($_array['price_prefix']),				 
				    				        'products_id' =>                 (int)$products_id ) ;	
				 tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES_GROUPS, $sql_data_array);
			   } // end elseif ($data_array['in_db'] == '0' && isset($data_array['insert']))
		   }
	    } // end for ($x = 0; $x < $no_of_customer_groups; $x++)		

// eric end		

//        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info . $test));
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_attributes'));
        break;
      case 'delete_attribute':
	    $tab_options = '' ;	  
	    $tab_values  = '' ;	 		
        $attribute_id = tep_db_prepare_input($_GET['attribute_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$attribute_id . "'");
// BOF SPPC attributes for groups
		tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id = '" . (int)$attribute_id . "'");
// EOF SPPC attributes for groups


// added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . (int)$attribute_id . "'");

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_attributes'));
        break;	
      case 'add_product_attributes':
	    $tab_options = '' ;	  
	    $tab_values  = '' ;	 			 
//BOF - Zappo - Option Types v2 - For TEXT and FILE option types, Lock the value and always use OPTIONS_VALUES_TEXT.
        $products_options_query = tep_db_query("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $HTTP_POST_VARS['options_id'] . "'");
        $products_options_array = tep_db_fetch_array($products_options_query);
        $values_id = tep_db_prepare_input((($products_options_array['products_options_type'] == OPTIONS_TYPE_TEXT) || ($products_options_array['products_options_type'] == OPTIONS_TYPE_TEXTAREA) || ($products_options_array['products_options_type'] == OPTIONS_TYPE_FILE)) ? OPTIONS_VALUE_TEXT_ID : $HTTP_POST_VARS['values_id']);
//        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID'] ) ;
        $options_id = tep_db_prepare_input($HTTP_POST_VARS['options_id']);
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . (int)$products_id . "', options_id = '" . (int)$options_id . "'");
		$option_attribute_id = tep_db_insert_id();
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $products_id . '&action=options_attributes&action_attribute=update_option_attribute&attribute_id='.$option_attribute_id . '&' . $page_info));
        break;		
    }	 
  }
  if (tep_not_null($action)) {
    // ULTIMATE Seo Urls 5 PRO by FWR Media
    // If the action will affect the cache entries
    if ( $action == 'insert' || $action == 'update' || $action == 'setflag' ) {
      tep_reset_cache_data_usu5( 'reset' );
    }
  if ( $action == 'insert' || $action == 'update' || $action == 'setflag1' ) {
      tep_reset_cache_data_usu5( 'reset' );
    }	
	// eof ULTIMATE Seo Urls 5 PRO by FWR Media
// bof multi stores	
// multi stores get DIR_FS_CATALOG_IMAGES from default store this is used for copying the images to the main images directory
   $stores_query_imgage_dir = tep_db_query("select stores_config_table from " . TABLE_STORES . "  where stores_id = 1");
   $config_table_default_store = tep_db_fetch_array($stores_query_imgage_dir) ;
   $config_table_img_prod = $config_table_default_store[ 'stores_config_table' ] ; 
   $get_config_image_directory_query = tep_db_query("select configuration_value from " . $config_table_img_prod . " where configuration_key = 'DIR_FS_CATALOG_IMAGES'"); // get location images
   $config_image_directory_query_prod= tep_db_fetch_array($get_config_image_directory_query); 	   
   $default_store_images_directory = $config_image_directory_query_prod[ 'configuration_value' ] ;
// eof multi stores

    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
            tep_reset_cache_block('xsell_products'); // XSell    			
          }
		  // eric optimize
          if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
          }		  
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']));
        break;
// START Added for the purchase feature option

		case 'setflag1':
        if ( ($HTTP_GET_VARS['flag1'] == '0') || ($HTTP_GET_VARS['flag1'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_purchase($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag1']);
          }

         if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
            tep_reset_cache_block('xsell_products'); // XSell    			
          }
		  // eric optimize
          if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
          }		  
        }
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']));
        break;		
// END Added for the purchase feature option		
     // BOF Enable & Disable Categories
      case 'setflag_cat':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['cID'])) {
            tep_set_categories_status($HTTP_GET_VARS['cID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
		  // eric optimize
		  if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&cID=' . $HTTP_GET_VARS['cID']));
        break;
      // EOF Enable & Disable Categories
		
      case 'insert_category':
      case 'update_category':
        if (isset($HTTP_POST_VARS['categories_id'])) $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
// BOF Separate Pricing Per Customer, hide categories from groups
//        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        // BOF Enable & Disable Categories
        //$sql_data_array = array('sort_order' => (int)$sort_order);
//        $categories_status = tep_db_prepare_input($HTTP_POST_VARS['categories_status']);
//        $sql_data_array = array('sort_order' => (int)$sort_order, 'categories_status' => $categories_status);
        // EOF Enable & Disable Categories		

        $hide_cats_from_these_groups = '@,';
          if ( $HTTP_POST_VARS['hide_cat'] ) { // if any of the checkboxes are checked
              foreach($HTTP_POST_VARS['hide_cat'] as $val) {
              $hide_cats_from_these_groups .= tep_db_prepare_input($val).','; 
              } // end foreach
           }
           $hide_cats_from_these_groups = substr($hide_cats_from_these_groups,0,strlen($hide_cats_from_these_groups)-1); // remove last comma		   

// bof multi stores
        $cats_to_stores = '@,';
          if ( $HTTP_POST_VARS['stores_cat'] ) { // if any of the checkboxes are checked
              foreach($HTTP_POST_VARS['stores_cat'] as $val) {
              $cats_to_stores .= tep_db_prepare_input($val).','; 
              } // end foreach
           }
           $cats_to_stores = substr($cats_to_stores,0,strlen($cats_to_stores)-1); // remove last comma
           if ( $stores_multi_present != 'true' ) $cats_to_stores = '@,1' ; // 1 store automatic active 
// eof multi stores		   
        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);
// BOF Enable & Disable Categories
        $categories_status = tep_db_prepare_input($HTTP_POST_VARS['categories_status']);
// EOF Enable & Disable Categories
        $sql_data_array = array('sort_order' => $sort_order,
                                'categories_hide_from_groups' => $hide_cats_from_these_groups, 
                                'categories_to_stores' => $cats_to_stores, 								// multi stores
								'categories_status' => $categories_status);
// EOF Separate Pricing Per Customer, hide categories from groups		

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

          $categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge((array)$sql_data_array, (array)$update_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $categories_name_array = $HTTP_POST_VARS['categories_name'];
          /*** Begin Header Tags SEO ***/
          $categories_htc_title_array = $HTTP_POST_VARS['categories_htc_title_tag'];
          $categories_htc_title_alt_array = $HTTP_POST_VARS['categories_htc_title_tag_alt'];
          $categories_htc_title_url_array = $HTTP_POST_VARS['categories_htc_title_tag_url'];
          $categories_htc_desc_array = $HTTP_POST_VARS['categories_htc_desc_tag'];
          $categories_htc_keywords_array = $HTTP_POST_VARS['categories_htc_keywords_tag'];
          $categories_htc_breadcrumb_array = $HTTP_POST_VARS['categories_htc_breadcrumb_text'];
          $categories_htc_description_array = $HTTP_POST_VARS['categories_htc_description'];

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
           'categories_htc_title_tag' => (tep_not_null($categories_htc_title_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_title_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_array[$language_id]))),
           'categories_htc_title_tag_alt' => (tep_not_null($categories_htc_title_alt_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_title_alt_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_alt_array[$language_id]))),
           'categories_htc_title_tag_url' => (tep_not_null($categories_htc_title_url_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_title_url_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_url_array[$language_id]))),
           'categories_htc_desc_tag' => (tep_not_null($categories_htc_desc_array[$language_id]) ? tep_db_prepare_input($categories_htc_desc_array[$language_id]) :  tep_db_prepare_input($categories_name_array[$language_id])),
           'categories_htc_keywords_tag' => (tep_not_null($categories_htc_keywords_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_keywords_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_array[$language_id]))),
           'categories_htc_breadcrumb_text' => (tep_not_null($categories_htc_breadcrumb_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_breadcrumb_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_array[$language_id]))),
           'categories_htc_description' => tep_db_prepare_input($categories_htc_description_array[$language_id]));
          /*** End Header Tags SEO ***/
          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }

        $categories_image = new upload('categories_image');
// bof multi stores         $categories_image->set_destination(DIR_FS_CATALOG_IMAGES );
        $categories_image->set_destination($default_store_images_directory . DIR_FS_CATEGORIES_IMAGES); 	

        if ($categories_image->parse() && $categories_image->save()) {
           tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . DIR_FS_CATEGORIES_IMAGES . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
		}
		
		$stores_categories_image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
		$stores_image_categorie = tep_db_fetch_array($stores_categories_image) ;
        tep_multi_stores_images( $stores_image_categorie[ 'categories_image' ], $HTTP_POST_VARS['stores_cat'], $default_store_images_directory, '', DIR_FS_CATEGORIES_IMAGES )    ; 

        tep_set_categories_to_stores( $categories_id, $cats_to_stores ) ;		
// eof multi stores		

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products'); // XSell    		  
        }
		// eric optimize
		if (USE_PRODUCTS_COUNT_CACHE == 'true') {
           tep_reset_cache_block('products_count');
        }
        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'c_' . $categories_id);
        }
        /*** End Header Tags SEO ***/

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        break;
      case 'delete_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $categories = tep_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

            while ($product_ids = tep_db_fetch_array($product_ids_query)) {
              $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
            }
          }

//          reset($products);
//          while (list($key, $value) = each($products)) {
          foreach ( $products as $key => $value ) {	
            $category_ids = '';

            for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

// removing categories can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            tep_remove_category($categories[$i]['id']);
          }

//          reset($products_delete);
//          while (list($key) = each($products_delete)) {
          foreach  ( array_keys ($products_delete) as $key ) {	
            tep_remove_product($key);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products'); // XSell    		  
        }
		// eric optimize
		if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
        }		

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'c_' . $categories_id);
        }
        /*** End Header Tags SEO ***/
        
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['product_categories']) && is_array($HTTP_POST_VARS['product_categories'])) {
          $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $product_categories = $HTTP_POST_VARS['product_categories'];

          for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
          }
// BOF Separate Pricing Per Customer
          tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . tep_db_input($product_id) . "' ");
// EOF Separate Pricing Per Customer		  
// BOF qpbpp
          tep_db_query("delete from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id = '" . (int)$product_id . "'");
// EOF qpbpp

          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
          $product_categories = tep_db_fetch_array($product_categories_query);

          if ($product_categories['total'] == '0') {
            tep_remove_product($product_id);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
		  tep_reset_cache_block('xsell_products'); // XSell    
        }
		// eric optimize
		if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
        }		

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('product_info.php', 'p_' . $product_id);
        }        
        /*** End Header Tags SEO ***/

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

          $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

          if (in_array($categories_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
          } else {
            tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('categories');
              tep_reset_cache_block('also_purchased');
              tep_reset_cache_block('xsell_products'); // XSell    			  
            }
		    // eric optimize
		    if (USE_PRODUCTS_COUNT_CACHE == 'true') {
              tep_reset_cache_block('products_count');
            }
            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
          }
        }

        break;
      case 'move_product_confirm':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products'); // XSell    		  
        }
		// eric optimize
		if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
        }		

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
      case 'insert_product':
      case 'update_product':
        if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($_GET['pID']);
        $products_date_available = tep_db_prepare_input( tep_date_raw( $_POST['products_date_available']) );
		
        $products_date_available = (date('Y-m-d') <= $products_date_available) ? $products_date_available : 'null';  
	
// BOF Separate Pricing Per Customer, hide products and categories from groups
        $hide_from_these_groups = '@,';
        if ( $HTTP_POST_VARS['hide'] ) { // if any of the checkboxes are checked
           foreach($HTTP_POST_VARS['hide'] as $val) {
              $hide_from_these_groups .= tep_db_prepare_input($val).','; 
           } // end foreach
        }
        $hide_from_these_groups = substr($hide_from_these_groups,0,strlen($hide_from_these_groups)-1); // remove last comma
// EOF Separate Pricing Per Customer, hide products and categories from groups		
// BOF multi stores
        $products_to_stores = '@,';
		$products_to_stores_images = '' ;
        if ( $HTTP_POST_VARS['stores_products'] ) { // if any of the checkboxes are checked
           foreach($HTTP_POST_VARS['stores_products'] as $val) {
              $products_to_stores .= tep_db_prepare_input($val).','; 
			  $products_to_stores_images = $val ;
           } // end foreach
        }
        $products_to_stores = substr($products_to_stores,0,strlen($products_to_stores)-1); // remove last comma
        if ( $stores_multi_present != 'true' ) $products_to_stores = '@,1' ; // 1 store automatic active		
// EOF multi stores

        $sql_data_array = array('products_quantity' => (int)tep_db_prepare_input($HTTP_POST_VARS['products_quantity']),
                                'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
                                'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),
// bof product price cost
                                 'products_cost' => tep_db_prepare_input($HTTP_POST_VARS['products_cost']),
// bof product price cost								
// BOF qpbpp
//                                'products_qty_blocks' => (($i=tep_db_prepare_input($HTTP_POST_VARS['products_qty_blocks'])) < 1) ? 1 : $i,
// EOF qpbpp					
// START Added for the purchase feature option
                                  'products_purchase' => tep_db_prepare_input($HTTP_POST_VARS['products_purchase']),
// END Added for the purchase feature option
// BOF QPBPP for SPPC
                                  'products_qty_blocks' => (($i = (int)tep_db_prepare_input($_POST['products_qty_blocks'][0])) < 1) ? 1 : $i,
                                  'products_min_order_qty' => (($min_i = (int)tep_db_prepare_input($_POST['products_min_order_qty'][0])) < 1) ? 1 : $min_i,
// EOF QPBPP for SPPC
                                'products_date_available' => $products_date_available,
                                'products_weight' => (float)tep_db_prepare_input($HTTP_POST_VARS['products_weight']),
                                'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),
                                'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
// INDIV_PM BEGIN
								'payment_methods' => tep_db_prepare_input($payment_methods),
// INDIV_PM END								
// BOF product sort	  
								'products_sort_order' => tep_db_prepare_input($HTTP_POST_VARS['products_sort_order']),
// EOF product sort								
// BOF Separate Price Per Customer, hide for these groups modification
                                'products_hide_from_groups' => $hide_from_these_groups,
// EOF Separate Price Per Customer, hide for these groups modification
// BOF multi stores
                                'products_to_stores' => $products_to_stores,
// EOF multi stores
                                'manufacturers_id' => (int)tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']),
// availability
// google feeder
                                'google_product_category' => (int)tep_db_prepare_input($HTTP_POST_VARS['google_taxonomy_code']),
                                'products_instock_id' => tep_db_prepare_input($HTTP_POST_VARS['products_instock_id']),
                                'products_nostock_id' => tep_db_prepare_input($HTTP_POST_VARS['products_nostock_id'])	);
								
		//++++ QT Pro: Begin Added code
			if($product_investigation['has_tracked_options'] or $product_investigation['stock_entries_count'] > 0){
				//Do not modify the stock from this page if the product has database entries or has tracked options
				unset($sql_data_array['products_quantity']);
			}
		//++++ QT Pro: End Added code								
						
        $products_image = new upload('products_image');
		
// bof multi stores         $products_image->set_destination(DIR_FS_CATALOG_IMAGES );
        $products_image->set_destination( $default_store_images_directory . DIR_FS_PRODUCTS_IMAGES  ); //

        if ($products_image->parse() && $products_image->save()) {
		   $sql_data_array['products_image'] = DIR_FS_PRODUCTS_IMAGES . tep_db_prepare_input( $products_image->filename);
//		 $sql_data_array['products_image'] = tep_db_prepare_input( $products_image->filename);
		}	

//		tep_multi_stores_images( $sql_data_array['products_image'], $HTTP_POST_VARS['stores_products'], $default_store_images_directory, DIR_FS_PRODUCTS_IMAGES, DIR_FS_PRODUCTS_IMAGES )    ;   
		
// eof multi stores	
		
        if ($action == 'insert_product') {
          $insert_sql_data = array('products_date_added' => 'now()');

          $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

          tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
          $products_id = tep_db_insert_id();

          tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
        } elseif ($action == 'update_product') {
          $update_sql_data = array('products_last_modified' => 'now()');

          $sql_data_array = array_merge((array)$sql_data_array, (array)$update_sql_data);

          tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
        }
/** AJAX Attribute Manager  **/ 
//        require_once('attributeManager/includes/attributeManagerUpdateAtomic.inc.php'); 
/** AJAX Attribute Manager  end **/		
// BOF qpbpp
//          tep_db_query("delete from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id = '" . (int)$products_id . "'");
//          for ($count = 0; $count <= (PRICE_BREAK_NOF_LEVELS - 1); $count++) {
//            if(isset($HTTP_POST_VARS['products_price' . $count]) && tep_not_null($HTTP_POST_VARS['products_price' . $count]) &&
//               isset($HTTP_POST_VARS['products_qty' . $count]) && tep_not_null($HTTP_POST_VARS['products_qty' . $count]) &&
//               !(isset($HTTP_POST_VARS['products_delete' . $count]) && tep_not_null($HTTP_POST_VARS['products_delete' . $count]))) {
//              $sql_price_break_data_array = array(
//                'products_id' => (int)$products_id,
//                'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price' . $count]),
//                'products_qty' => tep_db_prepare_input($HTTP_POST_VARS['products_qty' . $count]));
//              tep_db_perform(TABLE_PRODUCTS_PRICE_BREAK, $sql_price_break_data_array);
//            }
//          }
//          
//          $current_discount_category = (int)$_POST['current_discount_cat_id'];
//          $new_discount_category = (int)$_POST['discount_categories_id'];
//          $discount_category_result = qpbpp_insert_update_discount_cats($products_id, $current_discount_category, $new_discount_category);
//          if ($discount_category_result == false) {
//            $messageStack->add_session(ERROR_UPDATE_INSERT_DISCOUNT_CATEGORY, 'error');
//          }
// EOF qpbpp
// BOF QPBPP for SPPC
// BOF entries in products_groups
 $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id != '0' order by customers_group_id");
while ($customers_group = tep_db_fetch_array($customers_group_query)) // Gets all of the customers groups
  {
  $attributes_query = tep_db_query("select customers_group_id, customers_group_price, products_qty_blocks, products_min_order_qty from " . TABLE_PRODUCTS_GROUPS . " where ((products_id = '" . $products_id . "') && (customers_group_id = " . $customers_group['customers_group_id'] . ")) order by customers_group_id");
  $attributes = tep_db_fetch_array($attributes_query);
// set default values for quantity blocks and min order quantity  
  $pg_products_qty_blocks = 1;
  $pg_products_min_order_qty = 1;
  $delete_row_from_pg = false;

  if (isset($_POST['products_qty_blocks'][$customers_group['customers_group_id']]) && (int)$_POST['products_qty_blocks'][$customers_group['customers_group_id']] > 1) {
     $pg_products_qty_blocks = (int)$_POST['products_qty_blocks'][$customers_group['customers_group_id']];
  }
  if (isset($_POST['products_min_order_qty'][$customers_group['customers_group_id']]) && (int)$_POST['products_min_order_qty'][$customers_group['customers_group_id']] > 1) {
     $pg_products_min_order_qty = (int)$_POST['products_min_order_qty'][$customers_group['customers_group_id']];
  }
  if ($_POST['sppcprice'][$customers_group['customers_group_id']] == '' && $pg_products_qty_blocks == 1 && $pg_products_min_order_qty == 1) {
    $delete_row_from_pg = true; // no need to have default values for qty blocks and min order qty in the table
  }
  if ($_POST['sppcprice'][$customers_group['customers_group_id']] == '') {
    $pg_cg_group_price = 'null';
  } else {
    $pg_cg_group_price = "'" . (float)$_POST['sppcprice'][$customers_group['customers_group_id']] . "'";
  }

  if (tep_db_num_rows($attributes_query) > 0 && $delete_row_from_pg == false) {
// there is already a row inserted in products_groups, update instead of insert  
    if ($_POST['sppcoption'][$customers_group['customers_group_id']]) { // this is checking if the check box is checked
        tep_db_query("update " . TABLE_PRODUCTS_GROUPS . " set customers_group_price = " . $pg_cg_group_price . ", products_qty_blocks = " . $pg_products_qty_blocks . ", products_min_order_qty = " . $pg_products_min_order_qty . " where customers_group_id = '" . $attributes['customers_group_id'] . "' and products_id = '" . $products_id . "'");
    }
    else {
      tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $customers_group['customers_group_id'] . "' and products_id = '" . $products_id . "'");
    }
  } elseif (tep_db_num_rows($attributes_query) > 0 && $delete_row_from_pg == true) {
      tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $customers_group['customers_group_id'] . "' and products_id = '" . $products_id . "'");
  } elseif (($_POST['sppcoption'][$customers_group['customers_group_id']]) && $delete_row_from_pg == false) {
    tep_db_query("insert into " . TABLE_PRODUCTS_GROUPS . " (products_id, customers_group_id, customers_group_price, products_qty_blocks, products_min_order_qty) values ('" . $products_id . "', '" . $customers_group['customers_group_id'] . "', " . $pg_cg_group_price . ", " . $pg_products_qty_blocks . ", " . $pg_products_min_order_qty . ")");
  }
} // end while ($customers_group = tep_db_fetch_array($customers_group_query))
// EOF entries in products_groups

// BOF entries in products_to_discount_categories
  foreach ($_POST['discount_categories_id'] as $dc_cg_id => $dc_id) {
    $current_discount_category = (int)$_POST['current_discount_cat_id'][$dc_cg_id];
    $new_discount_category = (int)$dc_id;
    $discount_category_result = qpbpp_insert_update_discount_cats($products_id, $current_discount_category, $new_discount_category, $dc_cg_id);
      if ($discount_category_result == false) {
          $messageStack->add_session(ERROR_UPDATE_INSERT_DISCOUNT_CATEGORY, 'error');
       }
  } // end foreach ($_POST['discount_categories_id'] as $dc_cg_id => $dc_id
// EOF entries in products_to_discount_categories

// BOF entries in products_price_break
  foreach ($_POST['products_price_break'] as $pbb_cg_id => $price_break_array) {
    foreach ($price_break_array as $key1 => $products_price) {
      $pb_action = 'insert'; // re-set default to insert
      $where_clause = '';
      if (isset($_POST['products_delete'][$pbb_cg_id][$key1]) && $_POST['products_delete'][$pbb_cg_id][$key1] == 'y' && isset($_POST['products_price_break_id'][$pbb_cg_id][$key1])) {
        $delete_from_ppb_array[] = (int)$_POST['products_price_break_id'][$pbb_cg_id][$key1];
        continue;
      }
      if (!tep_not_null($products_price)) {
        continue; // if price is empty this price break is unused
      } elseif (!tep_not_null($_POST['products_qty'][$pbb_cg_id][$key1])) {
        continue; // if qty is not entered we will not update or insert this in the table
      } else {
        $sql_price_break_data_array = array(
           'products_id' => (int)$products_id,
           'products_price' => (float)$products_price,
           'products_qty' => (int)$_POST['products_qty'][$pbb_cg_id][$key1],
           'customers_group_id' => $pbb_cg_id
           );
               
        if (isset($_POST['products_price_break_id'][$pbb_cg_id][$key1]) && (int)$_POST['products_price_break_id'][$pbb_cg_id][$key1] > 0) {
          $pb_action = 'update';
          $where_clause = " products_price_break_id = '" . (int)$_POST['products_price_break_id'][$pbb_cg_id][$key1] . "'";
        }
        tep_db_perform(TABLE_PRODUCTS_PRICE_BREAK, $sql_price_break_data_array, $pb_action, $where_clause);
      } // end if/else (!tep_not_null($products_price))
    } // end foreach ($price_break_array as $key1 => $products_price)
  } // end foreach ($_POST['products_price_break'] as $pbb_cg_id => $price_break_array)
  
// delete the unwanted price breaks using their products_price_break_id's
    if (isset($delete_from_ppb_array) && sizeof($delete_from_ppb_array > 0) && tep_not_null($delete_from_ppb_array[0])) {
      tep_db_query("delete from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_price_break_id in (" . implode(',', $delete_from_ppb_array) . ")");
    }
// EOF entries in products_price_break
// EOF QPBPP for SPPC
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
           /*** Begin Header Tags SEO 331 ***/
            $sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name'][$language_id]),
                                    'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description'][$language_id]),
                                    'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url'][$language_id]),
                                    'products_head_title_tag' => ((tep_not_null($HTTP_POST_VARS['products_head_title_tag'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_title_tag'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),
                                    'products_head_title_tag_alt' => ((tep_not_null($HTTP_POST_VARS['products_head_title_tag_alt'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_title_tag_alt'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),
                                    'products_head_title_tag_url' => ((tep_not_null($HTTP_POST_VARS['products_head_title_tag_url'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_title_tag_url'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),
                                    'products_head_desc_tag' => ((tep_not_null($HTTP_POST_VARS['products_head_desc_tag'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_desc_tag'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),
                                    'products_head_keywords_tag' => ((tep_not_null($HTTP_POST_VARS['products_head_keywords_tag'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_keywords_tag'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),
                                    'products_head_breadcrumb_text' => tep_db_prepare_input($HTTP_POST_VARS['products_head_breadcrumb_text'][$language_id]),
                                    'products_head_listing_text' => tep_db_prepare_input($HTTP_POST_VARS['products_head_listing_text'][$language_id]),
                                    'products_head_sub_text' => tep_db_prepare_input($HTTP_POST_VARS['products_head_sub_text'][$language_id]));
           /*** End Header Tags SEO 331 ***/		   
          if ($action == 'insert_product') {
            $insert_sql_data = array('products_id' => $products_id,
                                     'language_id' => $language_id);

            $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_product') {
            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
          }
        }

        $pi_sort_order = 0;
        $piArray = array(0);

        foreach ($HTTP_POST_FILES as $key => $value) {
// Update existing large product images
          if (preg_match('/^products_image_large_([0-9]+)$/', $key, $matches)) {
            $pi_sort_order++;

            $sql_data_array = array('htmlcontent' => tep_db_prepare_input($HTTP_POST_VARS['products_image_htmlcontent_' . $matches[1]]),
                                    'sort_order' => $pi_sort_order);

            $t = new upload($key);
// bof multi stores         $t->set_destination(DIR_FS_CATALOG_IMAGES);
            $t->set_destination( $default_store_images_directory . DIR_FS_PRODUCTS_IMAGES ); // . DIR_FS_PRODUCTS_IMAGES

            if ($t->parse() && $t->save()) {
              $sql_data_array['image'] = DIR_FS_PRODUCTS_IMAGES . tep_db_prepare_input( $t->filename);
//              $sql_data_array['image'] = tep_db_prepare_input( $t->filename);
            }		
// eof multi stores	
            tep_db_perform(TABLE_PRODUCTS_IMAGES, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and id = '" . (int)$matches[1] . "'");

            $piArray[] = (int)$matches[1];
          } elseif (preg_match('/^products_image_large_new_([0-9]+)$/', $key, $matches)) {
// Insert new large product images
            $sql_data_array = array('products_id' => (int)$products_id,
                                    'htmlcontent' => tep_db_prepare_input($HTTP_POST_VARS['products_image_htmlcontent_new_' . $matches[1]]));

            $t = new upload($key);
// bof multi stores                    $t->set_destination(DIR_FS_CATALOG_IMAGES);
//            if ($t->parse() && $t->save()) {
            $t->set_destination( $default_store_images_directory . DIR_FS_PRODUCTS_IMAGES );  // 

            if ($t->parse() && $t->save()) {		
              $pi_sort_order++;

              $sql_data_array['image'] = DIR_FS_PRODUCTS_IMAGES . tep_db_prepare_input($t->filename);
//              $sql_data_array['image'] = tep_db_prepare_input($t->filename);
              $sql_data_array['sort_order'] = $pi_sort_order;

              tep_db_perform(TABLE_PRODUCTS_IMAGES, $sql_data_array);

              $piArray[] = tep_db_insert_id();
            }
          }
        }
		
        $product_images_query = tep_db_query("select image from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_id . "' and id not in (" . implode(',', $piArray) . ")");
        if (tep_db_num_rows($product_images_query)) {
          while ($product_images = tep_db_fetch_array($product_images_query)) {	  
            $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_IMAGES . " where image = '"  .  tep_db_input($product_images['image']) . "'"); //. DIR_FS_PRODUCTS_IMAGES
            $duplicate_image = tep_db_fetch_array($duplicate_image_query);

            if ($duplicate_image['total'] < 2) {
// bof multi stores			
//              if (file_exists(DIR_FS_CATALOG_IMAGES . $product_images['image'])) {
//                @unlink(DIR_FS_CATALOG_IMAGES . $product_images['image']);
              if (file_exists($default_store_images_directory.  $product_images['image'])) { 
                @unlink($default_store_images_directory  . $product_images['image']);
                tep_multi_stores_images( $product_images['image'], '', $default_store_images_directory, '', '' )    ;  	
				
              }
            }
          }
          tep_db_query("delete from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_id . "' and id not in (" . implode(',', $piArray) . ")");
        }
// bof multi stores
         
        $product_small_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
		$product_small_image = tep_db_fetch_array($product_small_image_query) ; 
		tep_multi_stores_images( $product_small_image['products_image'], $HTTP_POST_VARS['stores_products'], $default_store_images_directory, '', DIR_FS_PRODUCTS_IMAGES )    ;   

        $product_images_query = tep_db_query("select image from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_id . "'");
        if (tep_db_num_rows($product_images_query)) {
          while ($product_images = tep_db_fetch_array($product_images_query)) {
// multi stores upload or delete the category image for the different stores
            tep_multi_stores_images( $product_images['image'], $HTTP_POST_VARS['stores_products'], $default_store_images_directory, '', DIR_FS_PRODUCTS_IMAGES )    ;  	// , DIR_FS_PRODUCTS_IMAGES, false
          }
        }
		
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
		  tep_reset_cache_block('xsell_products'); // XSell    
        }
		// eric optimize
		if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
        }		

          /*** Begin Header Tags SEO ***/
          if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
            require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
            ResetCache_HeaderTags('product_info.php', 'p_' . $products_id);
          }
          /*** End Header Tags SEO ***/
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        break;
      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['categories_id'])) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          if ($HTTP_POST_VARS['copy_as'] == 'link') {
            if ($categories_id != $current_category_id) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {

            $product_query = tep_db_query("select products_quantity, products_model, products_image, products_price, products_cost, products_qty_blocks, products_min_order_qty, products_date_available, products_weight, products_purchase, products_tax_class_id, manufacturers_id, products_instock_id, products_nostock_id, payment_methods, 
			                                      products_sort_order , products_to_stores, p.google_product_category, ptdc.discount_categories_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id where p.products_id = '" . (int)$products_id . "'");
												                         // multi stores
            $product = tep_db_fetch_array($product_query); 

            tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model, products_image, products_price, products_cost, products_date_added, products_date_available, products_weight, products_status, products_purchase, products_tax_class_id, manufacturers_id, 
			                                                  products_instock_id,products_nostock_id, payment_methods, products_qty_blocks, products_min_order_qty, products_to_stores,google_product_category, products_sort_order ) values ('" . 
															                                                                                                            // multi stores
              tep_db_input($product['products_quantity']) . "', '" . 
              tep_db_input($product['products_model']) . "', '" . 
              tep_db_input($product['products_image']) . "', '" . 
              tep_db_input($product['products_price']) . "', '" . 
              tep_db_input($product['products_cost']) . "'" .
              ",  now(), " .
              (empty($product['products_date_available']) ? "null" : "'" . tep_db_input($product['products_date_available']) . "'") . ", '" . 
              tep_db_input($product['products_weight']) . "', '0', '1', '" . 
              (int)$product['products_tax_class_id'] . "', '" . 
              (int)$product['manufacturers_id'] . "', '" . 
			  (int)$product['products_instock_id'] . "', '" . 
			  (int)$product['products_nostock_id'] . "', '" . 			  
              (int)$product['payment_methods'] . "', '" . 			  
              tep_db_input($product['products_qty_blocks']) . "', '" . 
              tep_db_input($product['products_min_order_qty']) . "', '" . 
              tep_db_input($product['products_to_stores']) . "', '" . 			   
              tep_db_input($product['google_product_category']) . "', '" . 				  
			  tep_db_input($product['products_sort_order']) . "')");  
// eof availability			  
// EOF QPBPP for SPPC: products_qty_blocks, products_min_order_qty added			  
// eof products price cost			  
// eof product order sorter				  
// EOF qpbpp
			
            $dup_products_id = tep_db_insert_id();
          /*** Begin Header Tags SEO 331 ***/
            $description_query = tep_db_query("select language_id, products_name, products_description, products_head_title_tag, products_head_title_tag_alt, products_head_title_tag_url, products_head_desc_tag, products_head_keywords_tag, products_head_breadcrumb_text, products_head_listing_text, products_head_sub_text, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_head_title_tag, products_head_title_tag_alt, products_head_title_tag_url, products_head_desc_tag, products_head_keywords_tag, products_head_breadcrumb_text, products_head_listing_text, products_head_sub_text, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_title_tag_alt']) . "', '" . tep_db_input($description['products_head_title_tag_url']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_head_breadcrumb_text']) . "', '" . tep_db_input($description['products_head_listing_text']) . "', '" . tep_db_input($description['products_head_sub_text']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
            }
           /*** End Header Tags SEO 331 ***/		   
            $product_images_query = tep_db_query("select image, htmlcontent, sort_order from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$products_id . "'");
            while ($product_images = tep_db_fetch_array($product_images_query)) {
              tep_db_query("insert into " . TABLE_PRODUCTS_IMAGES . " (products_id, image, htmlcontent, sort_order) values ('" . (int)$dup_products_id . "', '" . tep_db_input($product_images['image']) . "', '" . tep_db_input($product_images['htmlcontent']) . "', '" . tep_db_input($product_images['sort_order']) . "')");
            }

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");

// BOF Separate Pricing Per Customer adapted for QPBPP for SPPC
            $cg_price_query = tep_db_query("select customers_group_id, customers_group_price, products_qty_blocks, products_min_order_qty from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $products_id . "' order by customers_group_id");

// insert customer group prices in table products_groups when there are any for the copied product
// adapted for QPBPP for SPPC
            if (tep_db_num_rows($cg_price_query) > 0) {
              while ( $cg_prices = tep_db_fetch_array($cg_price_query)) {
                  tep_db_query("insert into " . TABLE_PRODUCTS_GROUPS . " (customers_group_id, customers_group_price, products_id, products_qty_blocks, products_min_order_qty) values ('" . (int)$cg_prices['customers_group_id'] . "', " . (empty($cg_prices['customers_group_price']) ? "null" : "'" . tep_db_input($cg_prices['customers_group_price']) . "'") . ", '" . (int)$dup_products_id . "', '" . (int)$cg_prices['products_qty_blocks'] . "', '" . (int)$cg_prices['products_min_order_qty'] . "')");
              } // end while ( $cg_prices = tep_db_fetch_array($cg_price_query))
            } // end if (tep_db_num_rows($cg_price_query) > 0)

            $price_breaks_query = tep_db_query("select products_price, products_qty, customers_group_id from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id = '" . (int)$products_id . "' order by customers_group_id, products_qty");
            while ($price_break = tep_db_fetch_array($price_breaks_query)) {
              $sql_price_break_data_array = array(
                'products_id' => (int)$dup_products_id,
                'products_price' => $price_break['products_price'],
                'products_qty' => $price_break['products_qty'],
                'customers_group_id' => $price_break['customers_group_id']);
              tep_db_perform(TABLE_PRODUCTS_PRICE_BREAK, $sql_price_break_data_array);
            }
            
            $current_dc_query = tep_db_query("select discount_categories_id, customers_group_id from " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " where products_id = '" . (int)$products_id . "' order by customers_group_id");
            if (tep_db_num_rows($current_dc_query) > 0) {
              // insert the new products_id in products_to_discount_categories only 
              // if the cloned product was already in it
              while ($current_dc = tep_db_fetch_array($current_dc_query)) {
                $discount_category_result = qpbpp_insert_update_discount_cats($dup_products_id, '0', $current_dc['discount_categories_id'], $current_dc['customers_group_id']);
                if ($discount_category_result == false) {
                  $messageStack->add_session(ERROR_UPDATE_INSERT_DISCOUNT_CATEGORY, 'error');
                }
              } // end while ($current_dc = ....
            } // end if (tep_db_num_rows($current_dc_query)
// EOF Separate Pricing Per Customer adapted for QPBPP for SPPC
            $products_id = $dup_products_id;
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
			tep_reset_cache_block('xsell_products'); // XSell    
          }
		  // eric optimize
		  if (USE_PRODUCTS_COUNT_CACHE == 'true') {
            tep_reset_cache_block('products_count');
          }		  
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;
    }
  }

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!tep_is_writable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
  
  //++++ QT Pro: Begin Changed code
  if($product_investigation['any_problems']){
  	$messageStack->add('<strong>Warning: </strong>'. qtpro_doctor_formulate_product_investigation($product_investigation, 'short_suggestion') ,'warning');
  }
  //++++ QT Pro: End Changed code  

  require(DIR_WS_INCLUDES . 'template_top.php');
  
/** AJAX Attribute Manager  **/ 
//  require_once('attributeManager/includes/attributeManagerUpdateAtomic.inc.php'); 
/** AJAX Attribute Manager  end **/  

  if ($action == 'new_product') {
    $parameters = array('products_name' => '',
                       'products_description' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_quantity' => '',
                       'products_model' => '',
                       'products_image' => '',
                       'products_larger_images' => array(),
                       'products_price' => '',
// bof product price cost
                      'products_cost' => '',			   
// BOF QPBPP for SPPC
                       'products_qty_blocks' => '1',
                       'products_min_order_qty' => '1',
					   'discount_categories_id' => '',
// EOF QPBPP for SPPC  
// BOF SPPC hide from groups mod
                       'products_hide_from_groups' => '',
// EOF SPPC hide from groups mod
// BOF MULTI STORES
                       'products_to_stores' => ( SYS_STORES_ID == 1 ? SYS_STORES_ID : ''),
// EOF multi stores
                       'products_weight' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
// INDIV_PM BEGIN
                       'payment_methods' => '',
// INDIV_PM END					   
                       'products_status' => '',
// start remove add cart
                       'products_purchase' => '', 					   
                       'products_tax_class_id' => DEFAULT_PRODUCT_TAX_CLASS,
// bof availability
                       'products_instock_id' => SYS_DEFAULT_AVAILABILITY_INSTOCK, 
                       'products_nostock_id' => SYS_DEFAULT_AVAILABILITY_NOSTOCK, 
// eof availability					   
// BOF Product Sort
                       'manufacturers_id' => '',
                       'google_product_category' => '',				   
                       'products_sort_order' => '' );
// EOF Product Sort

    $pInfo = new objectInfo($parameters);

// bof availability
 	$products_availability_array = array(array('id' => '', 'text' => TEXT_NONE));
    $products_availability_query = tep_db_query("select products_availability_id, products_availability_name from " . TABLE_PRODUCTS_AVAILABILITY . " where language_id = '" . (int)$languages_id . "' order by products_availability_name");
    while ($products_availability = tep_db_fetch_array($products_availability_query)) {
      $products_availability_array[] = array('id' => $products_availability['products_availability_id'],
                                     'text' => $products_availability['products_availability_name']);
    }
// eof availability		

    if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {
	 /** 327
     $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_head_title_tag,                                                                 pd.products_head_desc_tag, pd.products_head_keywords_tag,                                   pd.products_head_listing_text, pd.products_head_sub_text, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_cost, p.products_qty_blocks, p.products_min_order_qty, p.products_weight, p.products_date_added, p.products_last_modified, p.products_hide_from_groups, p.products_to_stores , date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_purchase, p.products_tax_class_id, p.manufacturers_id, p.products_instock_id, p.products_nostock_id,p.payment_methods,ptdc.discount_categories_id, p.products_sort_order  from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");	  

   /*** End Header Tags SEO 327 ***/ 
    /*** Begin Header Tags SEO 331 ***/
     $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_title_tag_alt, pd.products_head_title_tag_url, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_head_breadcrumb_text, pd.products_head_listing_text, pd.products_head_sub_text, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_cost, p.products_qty_blocks, p.products_min_order_qty, p.products_weight, p.products_date_added, p.products_last_modified, p.products_hide_from_groups, p.products_to_stores , date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_purchase, p.products_tax_class_id, p.manufacturers_id, p.products_instock_id, p.products_nostock_id,p.payment_methods,ptdc.discount_categories_id, p.products_sort_order, p.google_product_category from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
    /*** End Header Tags SEO 331 ***/   
      $product = tep_db_fetch_array($product_query);

      $pInfo->objectInfo($product);
	  
      unset($pInfo->products_qty_blocks);
      $pInfo->products_qty_blocks[0] = $product['products_qty_blocks'];
      unset($pInfo->products_min_order_qty);
      $pInfo->products_min_order_qty[0] = $product['products_min_order_qty'];
// next is getting the group prices, products_qty_blocks, and products_min_order_qty for groups
      $cg_prices_query = tep_db_query("select customers_group_id, customers_group_price, products_qty_blocks, products_min_order_qty from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $pInfo->products_id . "' order by customers_group_id");
      while ($cg_prices = tep_db_fetch_array($cg_prices_query)) {
// and adding them to $pInfo for later use
        if (tep_not_null($cg_prices['customers_group_price'])) {
        $pInfo->sppcprice[$cg_prices['customers_group_id']] = $cg_prices['customers_group_price'];
        }
        $pInfo->products_qty_blocks[$cg_prices['customers_group_id']] = $cg_prices['products_qty_blocks'];
        $pInfo->products_min_order_qty[$cg_prices['customers_group_id']] = $cg_prices['products_min_order_qty'];
      } // end while ($cg_prices = tep_db_fetch_array($cg_prices_query))
      
      $price_breaks_array = array();
      $price_breaks_query = tep_db_query("select products_price_break_id, products_price, products_qty, customers_group_id from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id = '" . tep_db_input($pInfo->products_id) . "' order by customers_group_id, products_qty");
      while ($price_break = tep_db_fetch_array($price_breaks_query)) {
        $pInfo->products_price_break[$price_break['customers_group_id']][] = $price_break['products_price'];
        $pInfo->products_qty[$price_break['customers_group_id']][] = $price_break['products_qty'];
        $pInfo->products_price_break_id[$price_break['customers_group_id']][] = $price_break['products_price_break_id'];
      }
      $product_discount_categories = array();
      $products_discount_query = tep_db_query("select customers_group_id, discount_categories_id from " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " where products_id = '" . tep_db_input($pInfo->products_id) . "' order by customers_group_id");
      while ($products_discount_results = tep_db_fetch_array($products_discount_query)) {
        $pInfo->discount_categories_id[$products_discount_results['customers_group_id']] = $products_discount_results['discount_categories_id'];
      }
// EOF QPBPP for SPPC
	  
// eric line 384 qpbpp toevoegen ??

      $product_images_query = tep_db_query("select id, image, htmlcontent, sort_order from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product['products_id'] . "' order by sort_order");
      while ($product_images = tep_db_fetch_array($product_images_query)) {
        $pInfo->products_larger_images[] = array('id' => $product_images['id'],
                                                 'image' => $product_images['image'],
                                                 'htmlcontent' => $product_images['htmlcontent'],
                                                 'sort_order' => $product_images['sort_order']);
      }
    }

    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }
	
    $google_taxonomy_codes_array = array(array('id' => '', 'text' => TEXT_NONE));
    $google_taxonomy_codes_query = tep_db_query("select google_taxonomy_id,  	google_taxonomy_number, google_taxonomy_name from " . TABLE_PRODUCTS_GOOGLE_TAXONOMY . " order by google_taxonomy_name");
    while ($google_taxonomy_codes = tep_db_fetch_array($google_taxonomy_codes_query)) {
      $google_taxonomy_codes_array[] = array('id'   => $google_taxonomy_codes['google_taxonomy_id'],
                                             'text' => $google_taxonomy_codes['google_taxonomy_number'] . '-' . $google_taxonomy_codes['google_taxonomy_name']);
    }
	

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }
    // BOF qpbpp
    $discount_categories_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $discount_categories_query = tep_db_query("select discount_categories_id, discount_categories_name from " . TABLE_DISCOUNT_CATEGORIES . " order by discount_categories_name");
    while ($discount_categories = tep_db_fetch_array($discount_categories_query)) {
      $discount_categories_array[] = array('id' => $discount_categories['discount_categories_id'],
                                           'text' => $discount_categories['discount_categories_name']);
    }
    // EOF qpbpp
	

    $languages = tep_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }

// START Added for the purchase feature option
	 if (!isset($pInfo->products_purchase)) $pInfo->products_purchase = '1';
    switch ($pInfo->products_purchase) {
      case '0': $in_status1 = false; $out_status1 = true; break;
      case '1':
      default: $in_status1 = true; $out_status1 = false;
    }
// END Added for the purchase feature option	

    $form_action = (isset($HTTP_GET_VARS['pID'])) ? 'update_product' : 'insert_product';

?>
<?php 
// BOF QPBPP for SPPC
// the query is changed to also get the results for group 0 (retail) so that the
// results of the query can be used for others mods (like hide products, QPBPP for SPPC) too
    $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id");
    $header = false;
    if (!tep_db_num_rows($customers_group_query) > 0) {
      $messageStack->add_session(ERROR_ALL_CUSTOMER_GROUPS_DELETED, 'error');
   } else {
// to avoid confusion and/or duplication of code we re-use some code originally used
// for the "hide products for customers groups for sppc" mod here so both can co-exist
     while ($customers_group = tep_db_fetch_array($customers_group_query)) {
       $_hide_customers_group[] = $customers_group;
     }
   }
// BOF QPBPP for SPPC   
// BOF multi stores
    $stores_group_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
    $header = false;
    if (!tep_db_num_rows($stores_group_query) > 0) {
      $messageStack->add_session(ERROR_ALL_STORES_DELETED, 'error');
   } else {
     while ($stores_stores = tep_db_fetch_array($stores_group_query)) {
       $_stores_name[] = $stores_stores;
     }
   }
// multi stores get DIR_FS_CATALOG_IMAGES from default store this is used for copying the images to the main images directory
   $stores_query_imgage_dir = tep_db_query("select stores_config_table from " . TABLE_STORES . "  where stores_id = 1");
   $config_table_default_store = tep_db_fetch_array($stores_query_imgage_dir) ;
   $config_table_img_prod = $config_table_default_store[ 'stores_config_table' ] ; 
   $get_config_image_directory_query = tep_db_query("select configuration_value from " . $config_table_img_prod . " where configuration_key = 'DIR_FS_CATALOG_IMAGES'"); // get location images
   $config_image_directory_query_prod= tep_db_fetch_array($get_config_image_directory_query); 	   
   $default_store_images_directory = $config_image_directory_query_prod[ 'configuration_value' ] ;
 
// BOF SPPC hide products and categories from groups
//    $hide_cat_from_groups_array = explode(',',$cInfo->categories_hide_from_groups);
//    $hide_cat_from_groups_array = array_slice($hide_cat_from_groups_array, 1); // remove "@" from the array
      $hide_product_from_groups_array = explode(',',$pInfo->products_hide_from_groups);
      $hide_product_from_groups_array = array_slice($hide_product_from_groups_array, 1); // remove "@" from the array
// EOF SPPC hide products and categories from groups
//    $cats_to_stores_array = explode(',', $cInfo->categories_to_stores); // multi stores
//    $cats_to_stores_array = array_slice($cats_to_stores_array, 1); // remove "@" from the array	 // multi stores
      $products_to_stores_array = explode(',', $pInfo->products_to_stores); // multi stores
      $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores	
	
//	  $contents_products_heading .= '    <table border="0" width="100%" cellpadding="2">' . PHP_EOL ;
	  $contents_products_heading .= '       <div class="panel panel-primary">' . PHP_EOL ;
	  $contents_products_heading .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_PRODUCT . '</div>' . PHP_EOL;
	  $contents_products_heading .= '          <div class="panel-body">' . PHP_EOL;			
	  $contents_products_heading .= '                      ' .  tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=' . $form_action, 'post', 'role="form" enctype="multipart/form-data"') . PHP_EOL;
	  $contents_products_heading .= '                          <h1>' . $pInfo->products_name . '</h1>' . PHP_EOL;	
   	  $contents_products_heading .= '                          <h3><p>' . sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)) . '</p></h3>' . PHP_EOL;
      $contents_products_heading .= '                        <div class="col-xs-12 col-sm-12 col-md-12">' . PHP_EOL;	   

	  $contents_edit_prod_tab1    = '' ;
      include( DIR_WS_MODULES . 'categories_edit_products_tab_1.php' ) ;	

	  $contents_edit_prod_tab2    = '' ;
      include( DIR_WS_MODULES . 'categories_edit_products_tab_2.php' ) ;	  
	  
	  $contents_edit_prod_tab3    = '' ;
      include( DIR_WS_MODULES . 'categories_edit_products_tab_3.php' ) ;	 	  
	  
	  $contents_edit_prod_tab4    = '' ;
      include( DIR_WS_MODULES . 'categories_edit_products_tab_4.php' ) ;	  
	  
	  $contents_edit_prod_tab5    = '' ;	  
      include( DIR_WS_MODULES . 'categories_edit_products_tab_5.php' ) ;

//	  $contents_edit_prod_tab6    = ''; 
//      include(  DIR_WS_MODULES . 'categories_edit_products_tab_6.php' ) ;
	  
	  $contents_edit_prod_tab7    = '' ;
      include( DIR_WS_MODULES . 'categories_edit_products_tab_7.php' ) ;	 
	  
	  $contents_edit_prod_tab8    = '' ;
      include( DIR_WS_MODULES . 'categories_edit_products_tab_8.php' ) ;	 
	  
	  $contents_edit_prod_tab9    = '' ;
      include( DIR_WS_MODULES . 'categories_edit_products_tab_9.php' ) ;	 	  

	  $contents_products_footer .= '            </div>' . PHP_EOL;	// class="col-xs-12 col

	  $contents_products_footer .= '                 <br />' . PHP_EOL;	 
	  
	  $contents_products_footer .= '           <div>' . PHP_EOL;
	  $contents_products_footer .= '              <div class="col-md-12">' . PHP_EOL;
         
	  $contents_products_footer .= '		          ' .  tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date("Y-m-d"))) . PHP_EOL;
	  $contents_products_footer .= '		          ' .  tep_draw_bs_button(IMAGE_SAVE, "ok", null) . '&nbsp;&nbsp;' . PHP_EOL;
	  $contents_products_footer .= '		          ' .  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : ''))). PHP_EOL;
	  $contents_products_footer .= '              </div>' . PHP_EOL;
	  $contents_products_footer .= '            </div>' . PHP_EOL; //<!--row-->	

	  $contents_products_footer .= '          </form>' . PHP_EOL;
	  $contents_products_footer .= '       </div>' . PHP_EOL; // end div 	panel body
	  $contents_products_footer .= '     </div>' . PHP_EOL; // end div 	panel
	  
	  
	  $contents_products_edit .=   $contents_products_heading . PHP_EOL ;	  
	  
      $contents_products_edit .=  '<div role="tabpanel" id="tab_edit_products">' . PHP_EOL;
      $contents_products_edit .=  '  <!-- Nav tabs edit products -->' . PHP_EOL ;
      $contents_products_edit .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_products">' . PHP_EOL ;
      $contents_products_edit .=  '    <li  id="tab_edit_products" class="active"><a href="#tab_edit_products_name"       aria-controls="tab_edit_prod_name"             role="tab" data-toggle="tab">' . TEXT_TABS_01 . '</a></li>' . PHP_EOL ;
      $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_price"      aria-controls="tab_edit_prod_price"            role="tab" data-toggle="tab">' . TEXT_TABS_02 . '</a></li>'  . PHP_EOL;	  
      $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_descrip"    aria-controls="tab_edit_prod_descrip"          role="tab" data-toggle="tab">' . TEXT_TABS_03 . '</a></li>'  . PHP_EOL;	  
      $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_image"      aria-controls="tab_edit_prod_image"            role="tab" data-toggle="tab">' . TEXT_TABS_04 . '</a></li>'  . PHP_EOL;
      $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_htc"        aria-controls="tab_edit_prod_htc"              role="tab" data-toggle="tab">' . TEXT_TABS_05 . '</a></li>'  . PHP_EOL;			 
//      $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_options"    aria-controls="tab_edit_prod_options"          role="tab" data-toggle="tab">' . TEXT_TABS_06 . '</a></li>'  . PHP_EOL;			
      $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_stock"      aria-controls="tab_edit_products_stock"        role="tab" data-toggle="tab">' . TEXT_TABS_07 . '</a></li>'  . PHP_EOL;			  
      $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_hide_cat"   aria-controls="tab_edit_products_hide_cat"     role="tab" data-toggle="tab">' . TEXT_TABS_08 . '</a></li>'  . PHP_EOL;
      if ( $stores_multi_present == 'true' ) { // 1 store automatic active  	  
         $contents_products_edit .=  '    <li  id="tab_edit_products">               <a href="#tab_edit_products_hide_store" aria-controls="tab_edit_products_hide_store"   role="tab" data-toggle="tab">' . TEXT_TABS_09 . '</a></li>'  . PHP_EOL;	  
      }		 
      $contents_products_edit .=  '  </ul>'  . PHP_EOL;

      $contents_products_edit .=  '  <!-- Tab panes -->' . PHP_EOL ;
      $contents_products_edit .=  '  <div  id="tab_edit_products" class="tab-content">'  . PHP_EOL;
      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane active" id="tab_edit_products_name">'           . $contents_edit_prod_tab1 . '</div>' . PHP_EOL ;
      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_price">'          . $contents_edit_prod_tab2 . '</div>' . PHP_EOL ;
      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_descrip">'        . $contents_edit_prod_tab3 . '</div>' . PHP_EOL ;			
      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_image">'          . $contents_edit_prod_tab4 . '</div>' . PHP_EOL ;
      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_htc">'            . $contents_edit_prod_tab5   . '</div>' . PHP_EOL ;
//      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_options">'        . $contents_edit_prod_tab6  . '</div>' . PHP_EOL ;
      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_stock">'          . $contents_edit_prod_tab7  . '</div>' . PHP_EOL ;	  
      $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_hide_cat">'       . $contents_edit_prod_tab8  . '</div>' . PHP_EOL ;	 
      if ( $stores_multi_present == 'true' ) { // 1 store automatic active  	  
        $contents_products_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_products_hide_store">'     . $contents_edit_prod_tab9  . '</div>' . PHP_EOL ;	 	  
      }		
      $contents_products_edit .=  '  </div>' . PHP_EOL ;
      $contents_products_edit .=  '</div>' . PHP_EOL ;	
	  $contents_products_edit .=   $contents_products_footer . PHP_EOL ;	
	
// end bootstrap products	
	
       echo '                 ' . PHP_EOL .
             '                   ' . PHP_EOL .
             '                     <div class="row' . $alertClass . '">' . PHP_EOL .
                                    $contents_products_edit . 
             '                    </div>' . PHP_EOL .
             '                  ' . PHP_EOL .
             '                ' . PHP_EOL .
			 '      ' . PHP_EOL ;
?>			 
<script type="text/javascript"><!--  
var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0 ;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;
/* BOF QPBPP for SPPC - auto-update Retail readonly price field */
  document.forms["new_product"].products_price_retail_net.value = document.forms["new_product"].products_price.value;
/* EOF QPBPP for SPPC - auto-update Retail readonly price field */
  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
/* BOF QPBPP for SPPC - auto-update Retail readonly price field */
  document.forms["new_product"].products_price_retail_net.value = document.forms["new_product"].products_price.value;
/* EOF QPBPP for SPPC - auto-update Retail readonly price field */   
}

updateGross() ;
--></script>
<script>
<script>
    $('#tab_edit_products a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function (e) {
        var id = $(e.target).attr("href").substr(1);
        window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');
</script>
</script>
<?php			 

			 
  } elseif ($action == 'new_product_preview') {
// header tags 331
      $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag,pd.products_head_title_tag_alt, pd.products_head_title_tag_url, pd.products_head_breadcrumb_text, pd.products_url, p.products_quantity, p.products_min_order_qty , p.products_model, p.products_image, p.products_price, p.products_qty_blocks, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_purchase, p.manufacturers_id, p.products_instock_id, p.products_nostock_id , p.payment_methods, ptdc.discount_categories_id, dc.discount_categories_name, p.products_sort_order, p.products_to_stores, p.google_product_category   
	     from " . TABLE_PRODUCTS . " p left join " . 
		          TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id 
    left join " . TABLE_DISCOUNT_CATEGORIES . " dc using(discount_categories_id), " . 
	              TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'");
// eof qvailability	  
// indiv_pm end	  
// eof product sort order	  
// EOF qpbpp

      /*** End Header Tags SEO ***/ 
    $product = tep_db_fetch_array($product_query);

    $pInfo = new objectInfo($product);
    $products_image_name = $pInfo->products_image;

   /*** Begin Header Tags SEO 331 ***/
    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

	  $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
      $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
      $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);  
      if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) { 
        $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_title_tag_alt = tep_db_prepare_input($products_head_title_tag_alt[$languages[$i]['id']]);
        $pInfo->products_head_title_tag_url = tep_db_prepare_input($products_head_title_tag_url[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
        $pInfo->products_head_breadcrumb_text = tep_db_prepare_input($products_head_breadcrumb_text[$languages[$i]['id']]);
        $pInfo->products_head_listing_text = tep_db_prepare_input($products_head_listing_text[$languages[$i]['id']]);
        $pInfo->products_head_sub_text = tep_db_prepare_input($products_head_sub_text[$languages[$i]['id']]);
        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
      } else {
        $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_title_tag_alt = tep_db_prepare_input($products_head_title_tag_alt[$languages[$i]['id']]);
        $pInfo->products_head_title_tag_url = tep_db_prepare_input($products_head_title_tag_url[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
        $pInfo->products_head_breadcrumb_text = tep_db_prepare_input($products_head_breadcrumb_text[$languages[$i]['id']]);
        $pInfo->products_head_listing_text = tep_db_prepare_input($products_head_listing_text[$languages[$i]['id']]);
        $pInfo->products_head_sub_text = tep_db_prepare_input($products_head_sub_text[$languages[$i]['id']]);
        $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
      }
      $navtabs .= '                      <li '. (($i == 0) ? 'class="active"' : '') .'>' . PHP_EOL ;
	  $navtabs .= '                        <a href="#tab' . $i . '" data-toggle="tab">' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], null, null, null, false) . '</a>' . PHP_EOL ;
	  $navtabs .= '                      </li>' . PHP_EOL ;
	  
	  
	  // now the content for each language
	  $pf->loadProduct($pInfo->products_id, $pInfo->products_price, $pInfo->products_tax_class_id, (int)$pInfo->products_qty_blocks[0], $price_breaks_array, (int)$pInfo->products_min_order_qty[0] );
	  $tabcontent .= '                      <div class="tab-pane fade'. (($i == 0) ? ' active in' : '') .'" id="tab' . $i . '">' . PHP_EOL ; 
	  $tabcontent .= '                        <h1>' . $pInfo->products_name . '<small class="pull-right">' .  $pf->getPriceString( ) .'</small></h1>' . PHP_EOL ;
	  $tabcontent .= '                        <figure class="pull-right">' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</figure>' . PHP_EOL ;
	  
	  
      $tabcontent .= '                        ' . $pInfo->products_description  . PHP_EOL ;
	  
	  if ($pInfo->products_url)  {
	    $tabcontent .= '                        <br><br>' . sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url) . PHP_EOL ;
	  }
	  
	  if ($pInfo->products_date_available > date('Y-m-d')){
	    $tabcontent .= '                        <br><br><span class="label label-info">' . sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)) . '</span>' . PHP_EOL ;
	  } else {
        $tabcontent .= '                        <br><br><span class="label label-info">' . sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)) . '</span>' . PHP_EOL ;
	  }
	  
	  $tabcontent .= '                      </div>' . PHP_EOL ; 
    }	  
	
    /*** End Header Tags SEO ***/	
?>
            <div class="row">
            
              <div class="col-md-12">
                <div class="panel tabbed-heading panel-default">
                  <div class="panel-heading">
                    <ul class="nav nav-tabs" role="tablist">
<?php                    
	echo $navtabs;
?>                              
                    </ul>
                  </div>
                  <div class="panel-body">
                    <div class="tab-content">
<?php
    echo $tabcontent;
?>                     
                    </div>
                  </div>
                </div>  
              </div>
              
<?php

    if (isset($HTTP_GET_VARS['origin'])) {
      $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
      if ($pos_params != false) {
        $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
        $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
      } else {
        $back_url = $HTTP_GET_VARS['origin'];
        $back_url_params = '';
      }
    } else {
      $back_url = FILENAME_CATEGORIES;
      $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
    }
?>
              <div class="col-md-12">             
			    <?php echo tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link($back_url, $back_url_params)); ?>
      
              </div>
              
            </div><!--row -->
<?php
  } else {
// categories	  
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">  
          <div class="page-header">
            <div class="col-xs-12 col-md-6"><h1><?php echo HEADING_TITLE; ?></h1></div>
            <div class="col-xs-12 col-md-6">
              <div class="row">              
<?php
  echo '                ' . tep_draw_form('search', FILENAME_CATEGORIES, '', 'get', 'class="col-sm-6 col-md-6"') . PHP_EOL  .
       '                  <label class="sr-only" for="search">' . HEADING_TITLE_SEARCH . '</label>' . PHP_EOL  .  
       '                  ' . tep_draw_input_field('search','', 'placeholder="' . HEADING_TITLE_SEARCH . '"') . tep_hide_session_id() . PHP_EOL  .
	   '                </form>' . PHP_EOL ;
	   
  echo '                ' . tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get', 'class="col-sm-6 col-md-6"') . PHP_EOL  .
       '                  <label class="sr-only" for="cPath">' . HEADING_TITLE_GOTO . '</label>' . PHP_EOL  .  
       '                  ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onchange="this.form.submit();"') . tep_hide_session_id() . PHP_EOL  .
	   '                </form>' . PHP_EOL ;
?>
              </div>
            </div>
            <div class="clearfix"></div>
          </div><!-- page-header-->
		  
            <table class="table table-condensed table-striped"> <!-- after page-header -->
              <thead>
                <tr class="heading-row">
                  <th><?php echo TABLE_HEADING_IMAGE; ?></th>
                  <th><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
                  <th><?php echo TABLE_HEADING_HIDE_CATEGORIES; ?></th>				  
                  <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>		
			      <th class="text-center"><?php echo TABLE_HEADING_PURCHASE; ?></th>				  
                  <th class="text-center"><?php echo TABLE_HEADING_PRODUCT_SORT; ?></th>				  
                  <th ><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                </tr>
              </thead>
              <tbody>		   
<?php
    $categories_count = 0;
    $rows = 0;
// BOF SPPC hide products and categories from groups
    $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id");
    while ($customer_groups = tep_db_fetch_array($customers_group_query)) {
      $customers_groups[] = array('id' => $customer_groups['customers_group_id'], 'text' => $customer_groups['customers_group_name']);  
    }	
// EOF SPPC hide products and categories from groups	
// BOF multi stores
    $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
    while ($stores_stores = tep_db_fetch_array($stores_query)) {
      $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
    }	
		
// EOF multi stores
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_hide_from_groups, c.categories_status, c.categories_hide_from_groups, c.categories_to_stores , cd.categories_htc_title_tag, cd.categories_htc_title_tag_alt, cd.categories_htc_title_tag_url, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_breadcrumb_text, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");	    
    } else { 
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status, c.categories_hide_from_groups, c.categories_to_stores, cd.categories_htc_title_tag, cd.categories_htc_title_tag_alt, cd.categories_htc_title_tag_url,cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_breadcrumb_text, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");	  
    }

    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge((array)$categories, (array)$category_childs, (array)$category_products);
        $cInfo = new objectInfo($cInfo_array);
      }
	
      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '                <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . PHP_EOL ;	
      } else {
        echo '                <tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . PHP_EOL ;
      }

?>
                  <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_CATALOG_IMAGES . $categories['categories_image'], $categories['categories_name'], 40, 40, NULL, true) . '</a>'; ?></td>
                  <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '"></a>&nbsp;' . $categories['categories_name']; ?></td>			  
                  <td>
<?php 

					$hide_cat_from_groups_array = explode(',', $categories['categories_hide_from_groups']);
					$hide_cat_from_groups_array = array_slice($hide_cat_from_groups_array, 1); // remove "@" from the array
					$cats_to_stores_array = explode(',', $categories['categories_to_stores']); // multi stores
					$cats_to_stores_array = array_slice($cats_to_stores_array, 1); // remove "@" from the array	 // multi stores
				   $category_hidden_from_string = '';
				   if (LAYOUT_HIDE_FROM == '1') {
						 for ($i = 0; $i < count($customers_groups); $i++) {
						   if (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) {
						   $category_hidden_from_string .= $customers_groups[$i]['text'] . ', '; 
						   }
						 } // end for ($i = 0; $i < count($customers_groups); $i++)
						 $category_hidden_from_string = rtrim($category_hidden_from_string); // remove space on the right
						 $category_hidden_from_string = substr($category_hidden_from_string,0,strlen($category_hidden_from_string) -1); // remove last comma
						 if (!tep_not_null($category_hidden_from_string)) { 
						 $category_hidden_from_string = TEXT_GROUPS_NONE; 
						 }
						 $category_hidden_from_string = TEXT_HIDDEN_FROM_GROUPS . $category_hidden_from_string;
						 // if the string in the database field is @, everything will work fine, however tep_not_null
						 // will not discover the associative array is empty therefore the second check on the value
					 if (tep_not_null($hide_cat_from_groups_array) && tep_not_null($hide_cat_from_groups_array[0])) {
						echo tep_image(DIR_WS_ICONS . 'tick_black.gif', $category_hidden_from_string);
					  } else {
						echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $category_hidden_from_string);
					 }
				   } else {
				// default layout: icons for all groups
					  for ($i = 0; $i < count($customers_groups); $i++) {
						if (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) {
						  echo tep_glyphicon('remove-sign', 'danger') . $customers_groups[$i]['text'] . '&nbsp;&nbsp;';
						} else {
						  //echo '&nbsp;&nbsp;';
						}
					  }
				   }
				   // EOF SPPC hide products and categories from groups 
?>
                  </td>
                  <td align="center">
<?php
                  if ($categories['categories_status'] == '1') {
                        echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . PHP_EOL ;
                    } else {
                        echo '                    <a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
                    }
?>
                </td>
                <!-- // EOF Enable & Disable Categories -->						   
                <td align="center"><?php echo '' ; ?></td>					
                <td align="center"><?php echo $categories[sort_order]; ?></td>				  
					
                  <td class="text-right">
                    <div class="btn-toolbar" role="toolbar">                  
<?php
      echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO, 'info-sign', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=info'), null, 'info') . '</div>' . PHP_EOL  .
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=edit_category'), null, 'warning') . '</div>' . PHP_EOL  .
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_MOVE, 'move', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=move_category'), null, 'muted') . '</div>' . PHP_EOL  .
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=delete_category'), null, 'danger') . '</div>' . PHP_EOL ; 
?>

                    </div> 
				  </td>										
              </tr>
<style>
.tab-pane {
    height: 500px;
}
</style>
<?php 

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) && isset($HTTP_GET_VARS['action'])) { 
	    $alertClass = '';
        switch ($action) {
		  case 'edit_category':
            if (!isset($cInfo->categories_status)) $cInfo->categories_status = '1';
            switch ($cInfo->categories_status) {
             case '0': $in_status2 = false; $out_status2 = true; break;
             case '1':
             default: $in_status2 = true; $out_status2 = false;
           }
// BOF SPPC hide products and categories from groups
           $hide_cat_from_groups_array = explode(',',$cInfo->categories_hide_from_groups);
           $hide_cat_from_groups_array = array_slice($hide_cat_from_groups_array, 1); // remove "@" from the array
//    $hide_product_from_groups_array = explode(',',$pInfo->products_hide_from_groups);
//    $hide_product_from_groups_array = array_slice($hide_product_from_groups_array, 1); // remove "@" from the array
// EOF SPPC hide products and categories from groups
           $cats_to_stores_array = explode(',', $cInfo->categories_to_stores); // multi stores
           $cats_to_stores_array = array_slice($cats_to_stores_array, 1); // remove "@" from the array	 // multi stores
//    $products_to_stores_array = explode(',', $pInfo->products_to_stores); // multi stores
//    $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores		   
// eof enable disable categories 			  
			$contents_heading .= '       <div class="panel panel-primary">' . PHP_EOL ;
			$contents_heading .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</div>' . PHP_EOL;
			$contents_heading .= '          <div class="panel-body">' . PHP_EOL;			
			$contents_heading .= '                      ' . tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'class="form-horizontal" role="form" enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id) . PHP_EOL;		
			$contents_heading .= '                          <p>' . TEXT_EDIT_INTRO . '</p>' . PHP_EOL;			
            $contents_heading .= '                        <div class="col-xs-12 col-sm-10 col-md-10">' . PHP_EOL;

            $category_inputs_string = '';
            $languages = tep_get_languages();
            for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
              $category_inputs_string .= '                                <div >' . PHP_EOL .
                                         '                                  <div class="input-group">' . PHP_EOL .
										 '                                    <div class="input-group-addon">' . PHP_EOL . 
										 '                                      ' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'],24,15, null, false) . PHP_EOL .
										 
										 '                                    </div>' . PHP_EOL .
										 '                                    ' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id'])) . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;
            }
			$contents_tab1 .= '                          <br />' . PHP_EOL;				
			$contents_tab1 .= '                          <div class="row">' . PHP_EOL;
			$contents_tab1 .= '                            <div class="col-md-12">' . PHP_EOL;
			$contents_tab1 .= '                              <label>' . TEXT_CATEGORIES_NAME . '</label>' . PHP_EOL;
			$contents_tab1 .=                                    $category_inputs_string;
			$contents_tab1 .= '                            </div>' . PHP_EOL;
			$contents_tab1 .= '                          </div>' . PHP_EOL;
			$contents_tab1 .= '                          <br />' . PHP_EOL;			

            $contents_tab1 .= '                              <label>' . TEXT_EDIT_SORT_ORDER . '</label>' . PHP_EOL;
            $contents_tab1 .= '                              ' . tep_draw_input_field('sort_order', $cInfo->sort_order) . '<br />' . PHP_EOL;

		    $contents_tab1 .= '                              <div class="btn-group" data-toggle="buttons">' . PHP_EOL; 
		    $contents_tab1 .= '                               <label>' . TEXT_EDIT_STATUS . '</label><br />' . PHP_EOL;
		    $contents_tab1 .= '                                 <label class="btn btn-default' . ( $in_status2 ===  True ? " active " : "" ) . '">' . TEXT_CATEGORIES_ONLINE  . PHP_EOL;
		    $contents_tab1 .= '                               ' . tep_draw_radio_field("categories_status", "1", $in_status2) . PHP_EOL;
		    $contents_tab1 .= '                                 </label>' . PHP_EOL;
 
		    $contents_tab1 .= '                                 <label class="btn btn-default ' . ( $out_status2 === True ? " active " : "" ) . '">'. TEXT_CATEGORIES_OFFLINE .  PHP_EOL;
		    $contents_tab1 .= '                                   ' . tep_draw_radio_field("categories_status", "0", $out_status2) . PHP_EOL;
		    $contents_tab1 .= '                                 </label>' . PHP_EOL;
			$contents_tab1 .= '                               </div>' . PHP_EOL;			
            
			$contents_tab2 .= '                          <div class="row">' . PHP_EOL;
			$contents_tab2 .= '                            <div class="col-md-12">' . PHP_EOL;			
            $contents_tab2 .= '                          <label>' . TEXT_EDIT_CATEGORIES_IMAGE . '</label>' . PHP_EOL;
            $contents_tab2 .= '                          <p>'. tep_draw_file_field('categories_image') . '</p>' . PHP_EOL;
            $contents_tab2 .= '                          <br>' . PHP_EOL;			
            $contents_tab2 .= '                          <figure>' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<figcaption><small>' . DIR_WS_CATALOG_IMAGES . '<strong>' . $cInfo->categories_image . '</strong></small></figcaption></figure>' . PHP_EOL;
			$contents_tab2 .= '                            </div>' . PHP_EOL;
			$contents_tab2 .= '                          </div>' . PHP_EOL;

            $contents_tab3       .=  PHP_EOL . '  <!-- Nav tabs Meta Tags -->' . PHP_EOL ;						
            $contents_tab3       .=  '<div role="tabpanel" id="tab_htc">'. PHP_EOL  ;			
			
            $contents_tab3_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_htc">'. PHP_EOL  ;
            
			$contents_tab3_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_tab3_tabs .=  '  <div class="tab-content" id="tab_htc">'. PHP_EOL  ;	

            $contents_tab4       .=  PHP_EOL . '  <!-- Nav tabs Htc Description Index page -->' . PHP_EOL ;						
            $contents_tab4       .=  '<div role="tabpanel" id="tab_htc_oms_idx_page">'. PHP_EOL  ;			
			
            $contents_tab4_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_htc_oms_idx_page">'. PHP_EOL  ;

            $contents_tab4_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_tab4_tabs .=  '  <div class="tab-content" id="tab_htc_oms_idx_page">'. PHP_EOL  ;			
				
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
              $contents_tab3_links .=  '    <li class="'. $active_tab . '"><a href="#htc_meta_tags_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_htc">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

			  $contents_tab4_links .=  '    <li class="'. $active_tab . '"><a href="#htc_desc_idx_page_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_htc_oms_idx_page">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             
             
            			
			  $contents_tab3_content   = '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="cat_htc_title' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">'  . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_title($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_title' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' .
										 '                                </div><br />' . PHP_EOL;										 
              $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL . 
										 '                                    <label for="cat_htc_title_tag_alt' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE_ALT . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .												 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_title_tag_alt[' . $languages[$i]['id'] . ']', tep_get_category_htc_title_alt($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_title_tag_alt' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
              $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="cat_htc_title_tag_url' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE_URL . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_title_tag_url[' . $languages[$i]['id'] . ']', tep_get_category_htc_title_url($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_title_tag_url' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
              $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="cat_htc_desc_tag' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_DESCRIP_CATOGORIES . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
//										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_desc($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_desc_tag' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    <div class="col-sm-10">' . tep_draw_textarea_ckeditor('categories_htc_desc_tag[' . $languages[$i]['id'] . ']', 'soft', 100, 50, tep_get_category_htc_desc($cInfo->categories_id, $languages[$i]['id']), 'id = "cat_htc_desc_tag' . $languages[$i]['name'] . '"') . PHP_EOL .
										 
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;											 
             $contents_tab3_content   .= '                                <div class="form-group">' . PHP_EOL .
 										 '                                    <label for="cat_htc_keywords_tag' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_KEYWORD . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_keywords($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_keywords_tag' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
	          $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL .
 										 '                                    <label for="cat_htc_breadcrumb_text' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_BREADCRUMB . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_breadcrumb_text[' . $languages[$i]['id'] . ']', tep_get_category_htc_breadcrumb($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_breadcrumb_text' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
										 
	          $contents_tab4_content   = '                                <div class="form-group">' . PHP_EOL .
// 										 '                                    <label for="cat_htc_description' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_DESCRIPTION . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-xs-12">' . tep_draw_textarea_ckeditor('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 100, 50, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']), 'id = "cat_htc_description' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;			
										 
              $contents_tab3_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="htc_meta_tags_'     . $languages[$i]['name'] . '">' . $contents_tab3_content .'</div>'. PHP_EOL  ;										 
              $contents_tab4_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="htc_desc_idx_page_' . $languages[$i]['name'] . '">' . $contents_tab4_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

            $contents_tab3_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_tab3_tabs  .=  '  </div>'. PHP_EOL  ; 
			
            $contents_tab4_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_tab4_tabs  .=  '  </div>'. PHP_EOL  ; 			
 
			
			$contents_tab3       .=	$contents_tab3_links . PHP_EOL . $contents_tab3_tabs . PHP_EOL ;
            $contents_tab3       .=  '</div>'. PHP_EOL  ;			
            $contents_tab3       .=  '<!-- end nav meta tags -->'. PHP_EOL  ;	
			
			
			$contents_tab4       .=	 '<p>' . TEXT_HEADER_CAT_HEADER_DESCRIPTION . '</p>'. PHP_EOL ;
			$contents_tab4       .=	 			$contents_tab4_links . PHP_EOL . $contents_tab4_tabs . PHP_EOL ;
            $contents_tab4       .=  '</div>'. PHP_EOL  ;			
            $contents_tab4       .=  '<!-- end nav Htc Description Index page  -->'. PHP_EOL  ;		
			
		    $contents_tab5 .= '<div class="well well-info">' . sprintf( TEXT_HIDE_CATEGORIES_FROM_GROUPS, $cInfo->categories_name ) . '</div>' ;
            for ($i = 0; $i < count($customers_groups); $i++) {
	           $contents_tab5 .= '   <div class="form-group">' . PHP_EOL ;
               $contents_tab5 .= '       <div class="checkbox checkbox-danger">'. PHP_EOL  ;	
               $contents_tab5 .= '          ' .  tep_draw_checkbox_field('hide_cat[' . $customers_groups[$i]['id'] . ']',  $customers_groups[$i]['id'] , (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) ? 1: 0) . PHP_EOL  ;	
               $contents_tab5 .= '               <label for="' . $customers_groups[$i]['id']  . '">'. PHP_EOL  ;	
               $contents_tab5 .= '                       ' . $customers_groups[$i]['text']  .  PHP_EOL  ;	
               $contents_tab5 .= '               </label>'. PHP_EOL  ;	
               $contents_tab5 .= '       </div>'. PHP_EOL  ;	
               $contents_tab5 .= '   </div>'. PHP_EOL  ;					
            }	
			
		    $contents_tab6 .= '<div class="well danger">' . sprintf( TEXT_CATEGORIES_TO_STORE, $cInfo->categories_name ) . '</div>' ;
            for ($i = 0; $i < count($stores_array); $i++) {
	           $contents_tab6 .= '   <div class="form-group">' . PHP_EOL ;
               $contents_tab6 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;	
               $contents_tab6 .= '          ' .  tep_draw_checkbox_field('stores_cat[' . $stores_array[$i]['id'] . ']',  $stores_array[$i]['id'] , (in_array($stores_array[$i]['id'], $cats_to_stores_array)) ? 1: 0) . PHP_EOL  ;	
               $contents_tab6 .= '               <label for="' . $stores_array[$i]['id']  . '">'. PHP_EOL  ;	
               $contents_tab6 .= '                       ' . $stores_array[$i]['text']  .  PHP_EOL  ;	
               $contents_tab6 .= '               </label>'. PHP_EOL  ;	
               $contents_tab6 .= '       </div>'. PHP_EOL  ;	
               $contents_tab6 .= '   </div>'. PHP_EOL  ;					
            }			

		    $contents_footer .= '                      </div>' . PHP_EOL;	
            $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id), null, null, 'btn-default text-danger') . PHP_EOL;
			
		    $contents_footer .= '                      </form>' . PHP_EOL;
		    $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		    $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
			
			$contents  =   $contents_heading ;
            $contents .=  '<div role="tabpanel" id="tab_category">' . PHP_EOL;

            $contents .=  '  <!-- Nav tabs Category -->' . PHP_EOL ;
            $contents .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_category">' . PHP_EOL ;
            $contents .=  '    <li  id="tab_category" class="active"><a href="#tab_name"     aria-controls="tab_name"      role="tab" data-toggle="tab">' . TEXT_TABS_CAT_1 . '</a></li>' . PHP_EOL ;
            $contents .=  '    <li  id="tab_category">              <a href="#tab_image"     aria-controls="tab_image"     role="tab" data-toggle="tab">' . TEXT_TABS_CAT_2 . '</a></li>'  . PHP_EOL;
            $contents .=  '    <li  id="tab_category">              <a href="#tab_htc"       aria-controls="tab_htc"       role="tab" data-toggle="tab">' . TEXT_TABS_CAT_3 . '</a></li>'  . PHP_EOL;			
            $contents .=  '    <li  id="tab_category">              <a href="#tab_descrip"   aria-controls="tab_descrip"   role="tab" data-toggle="tab">' . TEXT_TABS_CAT_4 . '</a></li>'  . PHP_EOL;
            $contents .=  '    <li  id="tab_category">              <a href="#tab_cust_gr"   aria-controls="tab_cust_gr"   role="tab" data-toggle="tab">' . TEXT_TABS_CAT_5 . '</a></li>'  . PHP_EOL;

			if ( count( $stores_array ) > 1 ) {
                $contents .=  '    <li  id="tab_category">          <a href="#tab_stores"    aria-controls="tab_stores"    role="tab" data-toggle="tab">' . TEXT_TABS_CAT_6 . '</a></li>'  . PHP_EOL;			
		    }
            $contents .=  '  </ul>'  . PHP_EOL;

            $contents .=  '  <!-- Tab panes -->' . PHP_EOL ;
            $contents .=  '  <div  id="tab_category" class="tab-content">'  . PHP_EOL;
            $contents .=  '    <div role="tabpanel" class="tab-pane active" id="tab_name">' . $contents_tab1 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_image">'       . $contents_tab2 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_htc">'         . $contents_tab3 . '</div>' . PHP_EOL ;			
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_descrip">'     . $contents_tab4 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_cust_gr">'     . $contents_tab5 . '</div>' . PHP_EOL ;
			
			if ( count( $stores_array ) > 1 ) {
               $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_stores">'     . $contents_tab6 . '</div>' . PHP_EOL ;			
            }
			
            $contents .=  '  </div>' . PHP_EOL ;

            $contents .=  '</div>' . PHP_EOL ;
			$contents .=  $contents_sv_cncl  . PHP_EOL;
            $contents .=  $contents_footer  . PHP_EOL;	

		  break;
        
		  case 'delete_category':
		    $alertClass .= ' alert alert-danger';
		    $contents .= '                      ' . tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id) . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_DELETE_CATEGORY . ': ' . $cInfo->categories_name. '</h4>' . PHP_EOL;
            $contents .= '                          <p>' . TEXT_DELETE_CATEGORY_INTRO . '</p>' . PHP_EOL;
            if ($cInfo->childs_count > 0) $contents .= '                          ' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count) . PHP_EOL;
            if ($cInfo->products_count > 0) $contents .= '                          <br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count) . PHP_EOL;
            $contents .= '                        </div>' . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id), null, null, 'btn-default text-danger') . PHP_EOL;
            $contents .= '                        </div>' . PHP_EOL;
		    $contents .= '                      </form>' . PHP_EOL;
          break;
		  
          case 'move_category':
		    $contents .= '                      ' . tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id) . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</h4>' . PHP_EOL;
            $contents .= '                          <p>' . sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name) . '</p>' . PHP_EOL;
            $contents .= '                          <p>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . '</p>' . PHP_EOL;
			$contents .= '                        </div>' . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_MOVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id), null, null, 'btn-default text-danger') . PHP_EOL;
            $contents .= '                        </div>' . PHP_EOL;
		    $contents .= '                      </form>' . PHP_EOL;
          break;
		
		  default:
            $contents .= '                      <div class="col-xs-12 col-sm-2 col-md-4">' . PHP_EOL;
			$contents .= '                        <strong>' . $cInfo->categories_name . '</strong>' . PHP_EOL;
            $contents .= '                        <figure>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<figcaption>' . $cInfo->categories_image.'</figcaption></figure>' . PHP_EOL;
            $contents .= '                      </div>' . PHP_EOL;
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL;
			$contents .= '                        <ul class="list-group">' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                            <span class="badge badge-info">' . $cInfo->childs_count . '</span>' . PHP_EOL;			
			$contents .= '                              ' . TEXT_SUBCATEGORIES . PHP_EOL;
			$contents .= '                          </li>' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                            <span class="badge badge-info">' . $cInfo->products_count . '</span>' . PHP_EOL;			
			$contents .= '                              ' . TEXT_PRODUCTS . PHP_EOL;
			$contents .= '                          </li>' . PHP_EOL;
			$contents .= '                        </ul>' . PHP_EOL;
			$contents .= '                      </div>' . PHP_EOL;
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL;
			$contents .= '                        <ul class="list-group">' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                            <span class="badge badge-info">' . tep_date_short($cInfo->date_added) . '</span>' . PHP_EOL;			
			$contents .= '                              ' . TEXT_DATE_ADDED . PHP_EOL;
			$contents .= '                          </li>' . PHP_EOL;
            if (tep_not_null($cInfo->last_modified)) {
		      $contents .= '                          <li class="list-group-item">' . PHP_EOL;
			  $contents .= '                            <span class="badge badge-info">' . tep_date_short($cInfo->last_modified) . '</span>' . PHP_EOL;			
			  $contents .= '                              ' . TEXT_LAST_MODIFIED . PHP_EOL;
			  $contents .= '                          </li>' . PHP_EOL;					
			}
			$contents .= '                        </ul>' . PHP_EOL;
			$contents .= '                      </div>' . PHP_EOL;
          break;
        }

        echo '                <tr class="content-row">' . PHP_EOL .
             '                  <td colspan="7">' . PHP_EOL .
             '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                    $contents . 
             '                    </div>' . PHP_EOL .
             '                  </td>' . PHP_EOL .
             '                </tr>' . PHP_EOL;
      }			  
    }

// products on screen
    $products_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_cost, p.products_qty_blocks, p.products_min_order_qty, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_purchase, p.products_to_stores, p.products_hide_from_groups, p2c.categories_id, ptdc.discount_categories_id, dc.discount_categories_name, p.products_sort_order, p.google_product_category  from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id left join " . TABLE_DISCOUNT_CATEGORIES . " dc using(discount_categories_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by p.products_sort_order,pd.products_name");
    } else {
     $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_cost, p.products_qty_blocks, p.products_min_order_qty, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_purchase, p.products_to_stores, p.products_hide_from_groups, ptdc.discount_categories_id, dc.discount_categories_name, p.products_sort_order, p.google_product_category  from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id left join " . TABLE_DISCOUNT_CATEGORIES . " dc using(discount_categories_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by p.products_sort_order,pd.products_name");
    }
	
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $products['categories_id'];

      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge((array)$products, (array)$reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

     if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview') . '\'">' . PHP_EOL ;
      } else {
        echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . PHP_EOL ;
      }
?>
<!-- Begin Mini Images v2.0//-->
                 <td ><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products['products_image'], $products['products_name'], 50, 50, null, true); ?></td>
                 <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview') . '">' . tep_glyphicon('screenshot', 'info') . '</a>&nbsp;' . $products['products_name']  ?></td>
			  
<?php  //BOF SPPC hide products and categories from groups ?>
			     <td align="center"><?php
					  $hide_prods_from_groups_array = explode(',', $products['products_hide_from_groups']);
					  $hide_prods_from_groups_array = array_slice($hide_prods_from_groups_array, 1); // remove "@" from the array
					  if (LAYOUT_HIDE_FROM == '1') {
						$product_hidden_from_string = '';
						 for ($i = 0; $i < count($customers_groups); $i++) {
						   if (in_array($customers_groups[$i]['id'], $hide_prods_from_groups_array)) {
						   $product_hidden_from_string .= $customers_groups[$i]['text'] . ', '; 
						   }
						 } // end for ($i = 0; $i < count($customers_groups); $i++)
						 $product_hidden_from_string = rtrim($product_hidden_from_string); // remove space on the right
						 $product_hidden_from_string = substr($product_hidden_from_string,0,strlen($product_hidden_from_string) -1); // remove last comma
				   if (tep_not_null($hide_prods_from_groups_array)&& tep_not_null($hide_prods_from_groups_array[0])) { 
						 $product_hidden_from_string = TEXT_GROUPS_NONE; 
						 }
						 $product_hidden_from_string = TEXT_HIDDEN_FROM_GROUPS . $product_hidden_from_string;
				   if (tep_not_null($hide_prods_from_groups_array)) {
						echo tep_image(DIR_WS_ICONS . 'tick_black.gif', $product_hidden_from_string, 20, 16);
					 } else {
						echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $product_hidden_from_string, 20, 16);
					 }
				   } else {
				// default layout: icons for all groups
					  for ($i = 0; $i < count($customers_groups); $i++) {
						if (in_array($customers_groups[$i]['id'], $hide_prods_from_groups_array)) {
		//				  echo tep_image(DIR_WS_ICONS . 'icon_tick.gif', $customers_groups[$i]['text'], 11, 11) . '&nbsp;&nbsp;';
						  echo tep_glyphicon('remove-sign', 'danger') . $customers_groups[$i]['text'] . '&nbsp;&nbsp;';				  
						} else {
						  echo ''; 
						  //tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', 11, 11) . '&nbsp;&nbsp;';
						}
					  }
				   } // end if/else (LAYOUT_HIDE_FROM == '1')
?>
                 </td> <?php // EOF SPPC hide products and categories from groups ?>				
                 <td align="center">
<?php
					  if ($products['products_status'] == '1') {
						 echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . PHP_EOL ;
					  } else {
						echo '                    <a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath)  . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
					  }
?>
                </td>
<!-- START Added for the purchase feature option -->
                <td align="center">
<?php
				  if ($products['products_purchase'] == '1') {
					 echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag1&flag1=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . PHP_EOL ;
				  } else {
					echo '                    <a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag1&flag1=1&pID=' . $products['products_id'] . '&cPath=' . $cPath)  . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
				  }
?>
                </td>
<!-- END Added for the purchase feature option -->
				<td  align="center"><?php echo $products['products_sort_order']; // Product Sort ?></td>
                <td class="text-right">
                    <div class="btn-toolbar" role="toolbar">
                      
<?php
      echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,          'info-sign',    tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=info'),                       null, 'info') .    '</div>' . PHP_EOL  . 
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,               'pencil',       tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product'),                null, 'warning') . '</div>' . PHP_EOL  . 
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_OPTIONS_ATTRIBUTES, 'list',         tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_attributes'),         null, 'warning').    '</div>' . PHP_EOL ;

//	  $product_investigation = qtpro_doctor_investigate_product( $products['products_id'] );		   
	  if($product_investigation['has_tracked_options'] or $product_investigation['stock_entries_count'] > 0) {		   
	    echo '                    <div class="btn-group">' . tep_glyphicon_button(IMAGE_OPTIONS_STOCK,      'align-left',   tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_stock'),              null, 'warning').    '</div>' . PHP_EOL ;
	  }
	  echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_COPY_TO,            'transfer',     tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=copy_to'),                    null, 'muted').    '</div>' . PHP_EOL  . 		   
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_MOVE,               'move',         tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=move_product'),               null, 'muted') .   '</div>' . PHP_EOL  . 
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,             'remove',       tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=delete_product'),             null, 'danger') .  '</div>' . PHP_EOL ; 
?>

                    </div>
				</td>              
			</tr>
<?php
      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) && isset($HTTP_GET_VARS['action'])) { 
	    $alertClass = '';
        switch ($action) {
			        
		  case 'delete_product':
		    $alertClass .= ' alert alert-danger';
		    $contents .= '                      ' . tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id) . PHP_EOL ;
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL ;
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_DELETE_PRODUCT . ': ' . $pInfo->products_name . '</h4>' . PHP_EOL ;
            $contents .= '                          <p>' . TEXT_DELETE_PRODUCT_INTRO . '</p>' . PHP_EOL ;
			
			
            $product_categories_string = '';
            $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
            for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
              $category_path = '';
              for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
                $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
              }
              $category_path = substr($category_path, 0, -16);
//              $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
                $product_categories_string .= tep_bs_checkbox_field('product_categories[]',$product_categories[$i][sizeof($product_categories[$i])-1]['id'], $category_path, 'prod_delete_'. $i . $j, true, 'checkbox checkbox-success', '', '', 'right') ;			  				
            }
           // $product_categories_string = substr($product_categories_string, 0, -4);
			
 	        $contents .= '   <div class="form-group">' . PHP_EOL ;
            $contents .= '                          ' . $product_categories_string .  "\n";
            $contents .= '   </div>'. PHP_EOL  ;			

			
            $contents .= '                        </div>' . PHP_EOL ;
			
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL ;
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id), null, null, 'btn-default text-danger') . PHP_EOL ;
            $contents .= '                        </div>' . PHP_EOL ;
			
		    $contents .= '                      </form>' . PHP_EOL ;
          break;
		  
          case 'move_product':
		    $contents .= '                      ' . tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id) . PHP_EOL ;
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL ;
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</h4>' . PHP_EOL ;
            $contents .= '                          <p>' . sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name) . '</p>' . PHP_EOL ;
			$contents .= '                          <p>' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><strong>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</strong>' . PHP_EOL ;
			
            $contents .= '                          <p>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . '</p>' . PHP_EOL ;
			$contents .= '                        </div>' . PHP_EOL ;
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL ;
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_MOVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id), null, null, 'btn-default text-danger') . PHP_EOL ;
            $contents .= '                        </div>' . PHP_EOL ;
		    $contents .= '                      </form>' . PHP_EOL ;
          break;
		  
          case 'copy_to':
		    $contents .= '                      ' . tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id) . PHP_EOL ;
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL ;
			$contents .= '                          <h4>' . TEXT_INFO_COPY_TO_INTRO . '</h4>' . PHP_EOL ;
            $contents .= '                          <p>' . sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name) . '</p>' . PHP_EOL ;
			$contents .= '                          <p>' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><strong>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</strong></p>' . PHP_EOL ;
			
            $contents .= '                          <p>' . TEXT_CATEGORIES . '<br />' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id) . '</p>' . PHP_EOL ;
			
			
			$contents .= '                        </div>' . PHP_EOL ;
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4">' . PHP_EOL ;
            $contents .= '                          <strong>' . TEXT_HOW_TO_COPY . '</strong>' . PHP_EOL ;
			
 	        $contents .= '                          <div class="form-group">' . PHP_EOL ;
            $contents .=  			                      tep_bs_radio_field('copy_as', 'link',      TEXT_COPY_AS_LINK,      'input_Edit_Prod_link',      false, 'radio radio-success radio-inline', '', '', 'right') ;
	        $contents .= '                                <br />';			
            $contents .=  			                      tep_bs_radio_field('copy_as', 'duplicate', TEXT_COPY_AS_DUPLICATE, 'input_Edit_Prod_duplicate', true , 'radio radio-success radio-inline', '', '', 'right') ;
            $contents .= '                          </div>'. PHP_EOL  ;	
	        $contents .= '                          <br />';							
			
            $contents .= '                          <div class="text-right">' . PHP_EOL ;
            $contents .= '                            ' . tep_draw_bs_button(IMAGE_COPY, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id), null, null, 'btn-default text-danger') . PHP_EOL ;
            $contents .= '                          </div>' . PHP_EOL ;
			
            $contents .= '                        </div>' . PHP_EOL ;
		    $contents .= '                      </form>' . PHP_EOL ;
          break;
		  
          case 'options_attributes':	
              $contents_edit_prod_options_attri = '' ;
              include(  DIR_WS_MODULES . 'categories_edit_products_options_attributes.php' ) ;
              $contents .= $contents_edit_prod_options_attri ;			  
		  break;
		  
          case 'options_stock':	
              $contents_edit_prod_options_stock = '' ;
              include(  DIR_WS_MODULES . 'categories_edit_products_options_stock.php' ) ;
              $contents .= $contents_edit_prod_options_stock ;			  
		  break;
		  
		
		  default:
            $contents .= '                      <div class="col-xs-12 col-sm-2 col-md-4">' . PHP_EOL ;
			$contents .= '                        <strong>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</strong>' . PHP_EOL ;
            $contents .= '                        <figure>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<figcaption>' . $pInfo->products_image.'</figcaption></figure>' . PHP_EOL ;
            $contents .= '                      </div>' . PHP_EOL ;
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL ;
			$contents .= '                        <ul class="list-group">' . PHP_EOL ;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL ;
			$contents .= '                            <span class="badge badge-info">' . tep_decode_specialchars( $currencies->format($pInfo->products_price) ) . '</span>' . PHP_EOL ;			
			$contents .= '                              ' . TEXT_PRODUCTS_PRICE_INFO . PHP_EOL ;
			$contents .= '                          </li>' . PHP_EOL ;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL ;
			$contents .= '                            <span class="badge badge-info">' . $pInfo->products_quantity . '</span>' . PHP_EOL ;			
			$contents .= '                              ' . TEXT_PRODUCTS_QUANTITY_INFO . PHP_EOL ;
			$contents .= '                          </li>' . PHP_EOL ;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL ;
			$contents .= '                            <span class="badge badge-info">' . number_format($pInfo->average_rating, 2) . '%' . '</span>' . PHP_EOL ;			
			$contents .= '                              ' . TEXT_PRODUCTS_AVERAGE_RATING . PHP_EOL ;
			$contents .= '                          </li>' . PHP_EOL ;
			$contents .= '                        </ul>' . PHP_EOL ;
			$contents .= '                      </div>' . PHP_EOL ;
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL ;
			$contents .= '                        <ul class="list-group">' . PHP_EOL ;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL ;
			$contents .= '                            <span class="badge badge-info">' . tep_date_short($pInfo->products_date_added) . '</span>' . PHP_EOL ;			
			$contents .= '                              ' . TEXT_DATE_ADDED . PHP_EOL ;
			$contents .= '                          </li>' . PHP_EOL ;
            if (tep_not_null($pInfo->products_last_modified)) {
		      $contents .= '                          <li class="list-group-item">' . PHP_EOL ;
			  $contents .= '                            <span class="badge badge-info">' . tep_date_short($pInfo->products_last_modified) . '</span>' . PHP_EOL ;			
			  $contents .= '                              ' . TEXT_LAST_MODIFIED . PHP_EOL ;
			  $contents .= '                          </li>' . PHP_EOL ;					
			}
            if (date('Y-m-d') < $pInfo->products_date_available) {
		      $contents .= '                          <li class="list-group-item">' . PHP_EOL ;
			  $contents .= '                            <span class="badge badge-info">' . tep_date_short($pInfo->products_date_available) . '</span>' . PHP_EOL ;			
			  $contents .= '                              ' . TEXT_DATE_AVAILABLE . PHP_EOL ;
			  $contents .= '                          </li>' . PHP_EOL ;					
			}
			$contents .= '                        </ul>' . PHP_EOL ;
			$contents .= '                      </div>' . PHP_EOL ;
          break;
        }
        echo '                <tr class="content-row">' . PHP_EOL  .
             '                  <td colspan="7">' . PHP_EOL  .
             '                    <div class="row' . $alertClass . '">' . PHP_EOL  .
                                    $contents . 
             '                    </div>' . PHP_EOL  .
             '                  </td>' . PHP_EOL  .
             '                </tr>' . PHP_EOL ;
      }
    }
	
?> 
           
              </tbody>
            </table>  <!-- end table after page-header -->
        </table>            
<!--          </div> -->
<?php		  
    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>

          <div class="row">
            <!--<div class="col-xs-8 col-sm-4 col-md-3">
              <span class="col-md-8">
			     <?php echo TEXT_CATEGORIES . '<span class="badge badge-info pull-right">' . $categories_count . '</span>'; ?>
              </span>
              <br />
              <span class="col-md-8">
			    <?php echo '<div class="label label-info">' . TEXT_PRODUCTS . '<span class="badge badge-info pull-right">' . $products_count . '</span></div>'; ?>
              </span>
            </div> !-->
            
            <div class="col-xs-12 col-md-7">
              <?php if (sizeof($cPath_array) > 0) echo tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id)) . '&nbsp;'; 
		      if (!isset($HTTP_GET_VARS['search'])) echo tep_draw_bs_button(IMAGE_NEW_CATEGORY, 'plus', null,'data-toggle="modal" data-target="#new_category"') . '&nbsp;' . tep_draw_bs_button(IMAGE_NEW_PRODUCT, 'plus', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product')); ?>
            </div>
            
            <div class="col-xs-12 col-md-5 text-right">
              <?php echo '<span class="btn btn-primary">' . TEXT_CATEGORIES . '&nbsp;<span class="badge">' . $categories_count . '</span>&nbsp;' . TEXT_PRODUCTS . '&nbsp;<span class="badge">' . $products_count . '</span></span>'; ?>
          </div>
            
          </div><!--row-->
            
<?php
    $heading = array();
    $contents = array();

?>
 
         <div class="modal fade"  id="new_category" role="dialog" aria-labelledby="new_category" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'); ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_CATEGORY; ?></h4>
                  </div>
                  <div class="modal-body">
                    <p><?php echo TEXT_NEW_CATEGORY_INTRO; ?></p>			   
<?php
            $out_status2 = True ;
            $category_inputs_string = '';
            $languages = tep_get_languages();
            for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
              $category_inputs_string .= '                                <div >' . PHP_EOL .
                                         '                                  <div class="input-group">' . PHP_EOL .
										 '                                    <div class="input-group-addon">' . PHP_EOL . 
										 '                                      ' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'],24,15) . PHP_EOL .
										 
										 '                                    </div>' . PHP_EOL .
										 '                                    ' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;
            }
			$contents_tab_new_cat1 .= '                          <br />' . PHP_EOL;				
			$contents_tab_new_cat1 .= '                          <div class="row">' . PHP_EOL;
			$contents_tab_new_cat1 .= '                            <div class="col-md-12">' . PHP_EOL;
			$contents_tab_new_cat1 .= '                              <label>' . TEXT_CATEGORIES_NAME . '</label>' . PHP_EOL;
			$contents_tab_new_cat1 .=                                    $category_inputs_string;
			$contents_tab_new_cat1 .= '                            </div>' . PHP_EOL;
			$contents_tab_new_cat1 .= '                          </div>' . PHP_EOL;
			$contents_tab_new_cat1 .= '                          <br />' . PHP_EOL;			

            $contents_tab_new_cat1 .= '                              <label>' . TEXT_EDIT_SORT_ORDER . '</label>' . PHP_EOL;
            $contents_tab_new_cat1 .= '                              ' . tep_draw_input_field('sort_order', 0 ) . '<br />' . PHP_EOL;

		    $contents_tab_new_cat1 .= '                              <div class="btn-group" data-toggle="buttons">' . PHP_EOL; 
		    $contents_tab_new_cat1 .= '                               <label>' . TEXT_EDIT_STATUS . '</label><br />' . PHP_EOL;
		    $contents_tab_new_cat1 .= '                                 <label class="btn btn-default' . ( $in_status2 ===  True ? " active " : "" ) . '">' . TEXT_CATEGORIES_ONLINE  . PHP_EOL;
		    $contents_tab_new_cat1 .= '                               ' . tep_bs_radio_field("categories_status", "1", $in_status2, 'input_radio_status_1') . PHP_EOL;
		    $contents_tab_new_cat1 .= '                                 </label>' . PHP_EOL;
 
		    $contents_tab_new_cat1 .= '                                 <label class="btn btn-default ' . ( $out_status2 === True ? " active " : "" ) . '">'. TEXT_CATEGORIES_OFFLINE .  PHP_EOL;
		    $contents_tab_new_cat1 .= '                                   ' . tep_bs_radio_field("categories_status", "0", $out_status2, 'input_radio_status_2') . PHP_EOL;
		    $contents_tab_new_cat1 .= '                                 </label>' . PHP_EOL;
			$contents_tab_new_cat1 .= '                               </div>' . PHP_EOL;	

			$contents_tab_new_cat2 .= '                          <div class="row">' . PHP_EOL;
			$contents_tab_new_cat2 .= '                            <div class="col-md-12">' . PHP_EOL;			
            $contents_tab_new_cat2 .= '                          <label>' . TEXT_EDIT_CATEGORIES_IMAGE . '</label>' . PHP_EOL;
            $contents_tab_new_cat2 .= '                          <p>'. tep_draw_file_field('categories_image') . '</p>' . PHP_EOL;
			$contents_tab_new_cat2 .= '                            </div>' . PHP_EOL;
			$contents_tab_new_cat2 .= '                          </div>' . PHP_EOL;		

            $contents_tab_new_cat3       .=  PHP_EOL . '  <!-- Nav tabs Meta Tags -->' . PHP_EOL ;						
            $contents_tab_new_cat3       .=  '<div role="tabpanel" id="tab_new_cat_htc">'. PHP_EOL  ;			
			
            $contents_tab_new_cat3_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_new_cat_htc">'. PHP_EOL  ;
            
			$contents_tab_new_cat3_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_tab_new_cat3_tabs .=  '  <div class="tab-content" id="tab_new_cat_htc">'. PHP_EOL  ;	

            $contents_tab_new_cat4       .=  PHP_EOL . '  <!-- Nav tabs Htc Description Index page -->' . PHP_EOL ;						
            $contents_tab_new_cat4       .=  '<div role="tabpanel" id="tab_new_cat_htc_oms_idx_page">'. PHP_EOL  ;			
			
            $contents_tab_new_cat4_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_new_cat_htc_oms_idx_page">'. PHP_EOL  ;

            $contents_tab_new_cat4_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_tab_new_cat4_tabs .=  '  <div class="tab-content" id="tab_new_cat_htc_oms_idx_page">'. PHP_EOL  ;			
				
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
              $contents_tab_new_cat3_links .=  '    <li class="'. $active_tab . '"><a href="#new_cat_htc_meta_tags_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_new_cat_htc">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

			  $contents_tab_new_cat4_links .=  '    <li class="'. $active_tab . '"><a href="#new_cat_htc_desc_idx_page_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_new_cat_htc_oms_idx_page">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             
             
            			
			  $contents_tab_new_cat3_content   = '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="new_cat_htc_title' . $languages[$i]['name'] . '" class="col-sm-4 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-8">'  . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']', "", 'id="new_cat_htc_title' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' .
										 '                                </div><br />' . PHP_EOL;										 
              $contents_tab_new_cat3_content  .= '                                <div class="form-group">' . PHP_EOL . 
										 '                                    <label for="new_cat_htc_title_tag_alt' . $languages[$i]['name'] . '" class="col-sm-4 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE_ALT . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .												 
										 '                                    <div class="col-sm-8">' . tep_draw_input_field('categories_htc_title_tag_alt[' . $languages[$i]['id'] . ']', "", 'id="new_cat_htc_title_tag_alt' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div><br />' . PHP_EOL;	
              $contents_tab_new_cat3_content  .= '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="new_cat_htc_title_tag_url' . $languages[$i]['name'] . '" class="col-sm-4 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE_URL . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-8">' . tep_draw_input_field('categories_htc_title_tag_url[' . $languages[$i]['id'] . ']', "", 'id="new_cat_htc_title_tag_url' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div><br />' . PHP_EOL;	
              $contents_tab_new_cat3_content  .= '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="new_cat_htc_desc_tag' . $languages[$i]['name'] . '" class="col-sm-4 control-label">' . TEXT_HEADER_CAT_HEADER_DESCRIP_CATOGORIES . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-8">' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']', "", 'id="new_cat_htc_desc_tag' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div><br />' . PHP_EOL;											 
             $contents_tab_new_cat3_content   .= '                                <div class="form-group">' . PHP_EOL .
 										 '                                    <label for="new_cat_htc_keywords_tag' . $languages[$i]['name'] . '" class="col-sm-4 control-label">' . TEXT_HEADER_CAT_HEADER_KEYWORD . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-8">' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']', "", 'id="new_cat_htc_keywords_tag' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div><br />' . PHP_EOL;	
	          $contents_tab_new_cat3_content  .= '                                <div class="form-group">' . PHP_EOL .
 										 '                                    <label for="new_cat_htc_breadcrumb_text' . $languages[$i]['name'] . '" class="col-sm-4 control-label">' . TEXT_HEADER_CAT_HEADER_BREADCRUMB . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-8">' . tep_draw_input_field('categories_htc_breadcrumb_text[' . $languages[$i]['id'] . ']', "", 'id="new_cat_htc_breadcrumb_text' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div><br />' . PHP_EOL;	
										 
	          $contents_tab_new_cat4_content   = '                                <div class="form-group">' . PHP_EOL .
// 										 '                                    <label for="new_cat_htc_description' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_DESCRIPTION . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-xs-12">' . tep_draw_textarea_ckeditor('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 100, 50, "", 'id = "new_cat_htc_description' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;			
										 
              $contents_tab_new_cat3_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="new_cat_htc_meta_tags_'     . $languages[$i]['name'] . '">' . $contents_tab_new_cat3_content .'</div>'. PHP_EOL  ;										 
              $contents_tab_new_cat4_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="new_cat_htc_desc_idx_page_' . $languages[$i]['name'] . '">' . $contents_tab_new_cat4_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

            $contents_tab_new_cat3_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_tab_new_cat3_tabs  .=  '  </div>'. PHP_EOL  ; 
			
            $contents_tab_new_cat4_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_tab_new_cat4_tabs  .=  '  </div>'. PHP_EOL  ; 			
 
			
			$contents_tab_new_cat3       .=	$contents_tab_new_cat3_links . PHP_EOL . $contents_tab_new_cat3_tabs . PHP_EOL ;
            $contents_tab_new_cat3       .=  '</div>'. PHP_EOL  ;			
            $contents_tab_new_cat3       .=  '<!-- end nav meta tags -->'. PHP_EOL  ;	
		 
			$contents_tab_new_cat4       .=	'<br /><p>' .	TEXT_HEADER_CAT_HEADER_DESCRIPTION . '</p>'. PHP_EOL ;
			$contents_tab_new_cat4       .=	$contents_tab_new_cat4_links . PHP_EOL . $contents_tab_new_cat4_tabs . PHP_EOL ;
            $contents_tab_new_cat4       .=  '</div>'. PHP_EOL  ;			
            $contents_tab_new_cat4       .=  '<!-- end nav Htc Description Index page  -->'. PHP_EOL  ;	

	        $contents_tab_new_cat5 .= '<div class="well well-info">' . sprintf( TEXT_HIDE_CATEGORIES_FROM_GROUPS, "" ) . '</div>' ;
            for ($i = 0; $i < count($customers_groups); $i++) {
	           $contents_tab_new_cat5 .= '   <div class="form-group">' . PHP_EOL ;
               $contents_tab_new_cat5 .= '       <div class="checkbox checkbox-danger">'. PHP_EOL  ;	
               $contents_tab_new_cat5 .= '          ' .  tep_draw_checkbox_field('hide_cat[' . $customers_groups[$i]['id'] . ']',  $customers_groups[$i]['id'] ) . PHP_EOL  ;	
               $contents_tab_new_cat5 .= '               <label for="' . $customers_groups[$i]['id']  . '">'. PHP_EOL  ;	
               $contents_tab_new_cat5 .= '                       ' . $customers_groups[$i]['text']  .  PHP_EOL  ;	
               $contents_tab_new_cat5 .= '               </label>'. PHP_EOL  ;	
               $contents_tab_new_cat5 .= '       </div>'. PHP_EOL  ;	
               $contents_tab_new_cat5 .= '   </div>'. PHP_EOL  ;					
            }	
			
		    $contents_tab_new_cat6 .= '<div class="well danger">' . sprintf( TEXT_CATEGORIES_TO_STORE, $cInfo->categories_name ) . '</div>' ;
            for ($i = 0; $i < count($stores_array); $i++) {
	           $contents_tab_new_cat6 .= '   <div class="form-group">' . PHP_EOL ;
               $contents_tab_new_cat6 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;	
               $contents_tab_new_cat6 .= '          ' .  tep_draw_checkbox_field('stores_cat[' . $stores_array[$i]['id'] . ']',  $stores_array[$i]['id'] ) . PHP_EOL  ;	
               $contents_tab_new_cat6 .= '               <label for="' . $stores_array[$i]['id']  . '">'. PHP_EOL  ;	
               $contents_tab_new_cat6 .= '                       ' . $stores_array[$i]['text']  .  PHP_EOL  ;	
               $contents_tab_new_cat6 .= '               </label>'. PHP_EOL  ;	
               $contents_tab_new_cat6 .= '       </div>'. PHP_EOL  ;	
               $contents_tab_new_cat6 .= '   </div>'. PHP_EOL  ;					
            }				

            $contents .=  '<div role="tabpanel" id="tab_new_category">' . PHP_EOL;

            $contents .=  '  <!-- Nav tabs Category -->' . PHP_EOL ;
            $contents .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_new_category">' . PHP_EOL ;
            $contents .=  '    <li  id="tab_new_category" class="active"><a href="#tab_new_cat_name"      aria-controls="tab_name"      role="tab" data-toggle="tab">' . TEXT_TABS_CAT_1 . '</a></li>' . PHP_EOL ;
            $contents .=  '    <li  id="tab_new_category">               <a href="#tab_new_cat_image"     aria-controls="tab_image"     role="tab" data-toggle="tab">' . TEXT_TABS_CAT_2 . '</a></li>'  . PHP_EOL;
            $contents .=  '    <li  id="tab_new_category">               <a href="#tab_new_cat_htc"       aria-controls="tab_htc"       role="tab" data-toggle="tab">' . TEXT_TABS_CAT_3 . '</a></li>'  . PHP_EOL;			
            $contents .=  '    <li  id="tab_new_category">               <a href="#tab_new_cat_descrip"   aria-controls="tab_descrip"   role="tab" data-toggle="tab">' . TEXT_TABS_CAT_4 . '</a></li>'  . PHP_EOL;
            $contents .=  '    <li  id="tab_new_category">               <a href="#tab_new_cat_cust_gr"  aria-controls="tab_cust_gr"   role="tab" data-toggle="tab">' . TEXT_TABS_CAT_5 . '</a></li>'  . PHP_EOL;

			if ( count( $stores_array ) > 1 ) {
                $contents .=  '    <li  id="tab_new_category">          <a href="#tab_new_cat_stores"    aria-controls="tab_new_cat_stores"    role="tab" data-toggle="tab">' . TEXT_TABS_CAT_6 . '</a></li>'  . PHP_EOL;			
		    }
            $contents .=  '  </ul>'  . PHP_EOL;

            $contents .=  '  <!-- Tab panes -->' . PHP_EOL ;
            $contents .=  '  <div  id="tab_new_category" class="tab-content">'  . PHP_EOL;
            $contents .=  '    <div role="tabpanel" class="tab-pane active" id="tab_new_cat_name">'        . $contents_tab_new_cat1 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane"        id="tab_new_cat_image">'       . $contents_tab_new_cat2 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane"        id="tab_new_cat_htc">'         . $contents_tab_new_cat3 . '</div>' . PHP_EOL ;			
            $contents .=  '    <div role="tabpanel" class="tab-pane"        id="tab_new_cat_descrip">'     . $contents_tab_new_cat4 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane"        id="tab_new_cat_cust_gr">'     . $contents_tab_new_cat5 . '</div>' . PHP_EOL ;
			
			if ( count( $stores_array ) > 1 ) {
               $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_new_cat_stores">'     . $contents_tab_new_cat6 . '</div>' . PHP_EOL ;			
            }
			
            $contents .=  '  </div>' . PHP_EOL ;

            $contents .=  '</div>' . PHP_EOL ;

	

?>		
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_category -->
		
		  
<?php
  }
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
        <div class="modal fade"  id="products_new_option_attribute" role="dialog" aria-labelledby="products_new_option_attribute" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_option_attribute', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_attributes&action_attribute=add_product_attributes' . '&' . $page_info , 'post', 'role="form"', 'id_attributes_new_option' ) ; 
				?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_OPTION_ATTRIBUTE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 						
									$select_options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
									while ($select_options_values = tep_db_fetch_array($select_options)) {
                                        switch ($options_values['products_options_type']) {
                                           case OPTIONS_TYPE_TEXT:
                                           case OPTIONS_TYPE_TEXTAREA:
                                           case OPTIONS_TYPE_FILE:
                                             // Exclude from dropdown
                                             break;
                                           default:										
										       $options_array[] = array('id' => $select_options_values['products_options_id'],
												       				  'text' => $select_options_values['products_options_name']);
										}
									}									
								  
 
			                        $contents_new_option_attrib  = '           <div class="panel panel-primary">' . PHP_EOL ;
			                        $contents_new_option_attrib .= '                 <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_OPTION_ATTRIBUTE . '</div>' . PHP_EOL;
			                        $contents_new_option_attrib .= '                 <div class="panel-body">' . PHP_EOL;	
									
								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL;	// tep_draw_pull_down_menu('options_id', $options_array, $attributes_values['options_id'])
								    $contents_new_option_attrib .= '                            ' . tep_draw_bs_pull_down_menu('options_id', $options_array, null, TABLE_HEADING_OPT_NAME, 'id_input_att_option_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;

									$contents_new_option_attrib_footer .= '</div>' . PHP_EOL; // end div 	panel body
		                            $contents_new_option_attrib_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?> 
                      <div class="full-iframe" width="100%"> 
                          <?php echo $contents_new_option_attrib . $contents_new_option_attrib_footer ; ?>
                      </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . 
				             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_attributes' . '&' . $page_info)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #products_new_option_attribute -->
		  
       <div class="modal fade"  id="products_new_option_stock" role="dialog" aria-labelledby="products_new_option_stock" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_option_stock', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_stock&action_attribute_stock=insert_attribute_stock' . '&' . $page_info , 'post', 'role="form"', 'id_attributes_new_option_stock' ) ; 
				?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_OPTION_STOCK; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 						
$options = array() ;
$option_names = array() ;
 $product_name = '';
									$q=tep_db_query("select products_name,products_options_name as _option,
									                        products_attributes.options_id as _option_id,
															products_options_values_name as _value,
															products_attributes.options_values_id as _value_id from ".
													  "products_description, products_attributes,products_options,products_options_values where ".
													  "products_attributes.products_id=products_description.products_id and ".
													  "products_attributes.products_id=" . (int)$pInfo->products_id . " and ".
													  "products_attributes.options_id=products_options.products_options_id and ".
													  "products_attributes.options_values_id=products_options_values.products_options_values_id and ".
													  "products_description.language_id=" . (int)$languages_id . " and ".
													  "products_options_values.language_id=" . (int)$languages_id . " and products_options.products_options_track_stock=1 and ".
													  "products_options.language_id=" . (int)$languages_id . " order by products_attributes.options_id, products_attributes.options_values_id, products_attributes.products_options_sort_order");
									 //list($product_name,$option_name,$option_id,$value,$value_id)
									if (tep_db_num_rows($q)>0) {
										$flag=1;
										$counter = -1 ;
										$key = -1 ;
										while($list=tep_db_fetch_array($q)) {
										  if ( $key != $list[_option_id] ) {
											 $counter += 1 ;
											 $key = $list[_option_id] ;
										  }
										  $options[ $list[_option_id]][]=array($list[_value],$list[_value_id]);
										  $options2[$counter][]=array($list[_option_id],$list[_value_id], $list[_value]);											  
										  $options3[]=array('id' =>$list[_option_id] . '-'. $list[_value_id], 'text' => $list[_value]);											  
										  $option_names[$list[_option_id]]=$list[_option];
										  $product_name=$list[products_name];
									  
//										  $temp[$list[_option_id]][]=array($key, $list[_value], $list[_value_id]) ;										   
										  
										}
									}
									
									
									
			                        $contents_new_option_attrib_stock  = '           <div class="panel panel-primary">' . PHP_EOL ;
			                        $contents_new_option_attrib_stock .= '                 <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_OPTION_STOCK . '</div>' . PHP_EOL;
			                        $contents_new_option_attrib_stock .= '                 <div class="panel-body">' . PHP_EOL;	
									
									$i=0;
				                    $option_temp = -1 ;
                                    while(list($k,$v)=each($options)) {
 
					                   if ( $option_temp != $k ) {
					                      $option_array = 	array() ;
					                      $option_temp = $k ;
					                   }
					 
					                   $option = 'option' . $k  ;
                                       foreach($v as $v1) {
                                          $option_array[] = array('id' => $v1[1], 'text' => $v1[0]  );	   
                                       }
					 
								       $contents_new_option_stock_select .= '                               <div class="form-inline">' . PHP_EOL;														 
									   $contents_new_option_stock_select .=                                      tep_draw_bs_pull_down_menu( $option, $option_array, null ) . PHP_EOL ;
								       $contents_new_option_stock_select .= '                               </div>' . PHP_EOL;
					 
                                       $i++;					 
                                    }
									
					                $contents_new_option_attrib_stock .= $contents_new_option_stock_select ;
									
									$contents_new_option_attrib_stock .= '                                <div class="form-group">' . PHP_EOL;			  
								    $contents_new_option_attrib_stock .=                                      tep_draw_bs_input_field( 'products_stock_quantity',  null, TEXT_HEADING_STOCK, 'id_products_stock_quantity', 'col-xs-3', 'col-xs-9', 'left', TEXT_HEADING_STOCK ) . PHP_EOL; 
								    $contents_new_option_attrib_stock .= '                                </div>' . PHP_EOL;	

									$contents_new_option_attrib_stock_footer .= '          </div>' . PHP_EOL; // end div 	panel body
		                            $contents_new_option_attrib_stock_footer .= '    </div>' . PHP_EOL; // end div 	panel		
?> 
                      <div class="full-iframe" width="100%"> 
                          <?php echo $contents_new_option_attrib_stock . $contents_new_option_attrib_stock_footer ; ?>
                      </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . 
				             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=options_stock' . '&' . $page_info)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #products_new_option_stock -->		  