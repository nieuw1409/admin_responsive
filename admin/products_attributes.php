<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();
  
 
  $tab_values  = '' ;
  
// BOF SPPC, attributes groups modification
  $customers_groups_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . "  order by customers_group_id");

  while ($_customers_groups = tep_db_fetch_array($customers_groups_query)) {
      $customers_groups[] = $_customers_groups;
  }
// EOF SPPC, attributes groups modification    

  $price_prefix_array = array(array('id' => '+', 'text' => '+'),
                              array('id' => '-', 'text' => '-'));

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $option_page = (($HTTP_GET_VARS['option_page']) && is_numeric($HTTP_GET_VARS['option_page'])) ? $HTTP_GET_VARS['option_page'] : 1;
  $value_page = (($HTTP_GET_VARS['value_page']) && is_numeric($HTTP_GET_VARS['value_page'])) ? $HTTP_GET_VARS['value_page'] : 1;
  $attribute_page = (($HTTP_GET_VARS['attribute_page']) && is_numeric($HTTP_GET_VARS['attribute_page'])) ? $HTTP_GET_VARS['attribute_page'] : 1;

  $page_info = 'option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page;
  
  //BOF - Zappo - Option Types v2 - Check if the option_value TEXT_UPLOAD_NAME is in place, and insert if not found
  $textoptions_query = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . OPTIONS_VALUES_TEXT_ID . "' and language_id = '" . $languages_id . "'");
  $textoptions = tep_db_fetch_array($textoptions_query);
  if (empty($textoptions['products_options_values_name']) || $textoptions['products_options_values_name'] != TEXT_UPLOAD_NAME) {
    tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . OPTIONS_VALUES_TEXT_ID . "'");
    for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
      tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . OPTIONS_VALUES_TEXT_ID . "', '" . (int)$languages[$i]['id'] . "', '" . TEXT_UPLOAD_NAME . "')");
    }
  }	
  //EOF - Zappo - Option Types v2 - Check if the option_value TEXT_UPLOAD_NAME is in place, and insert if not found
 
  if (tep_not_null($action)) {
    switch ($action) {
      case 'add_product_options':
        $products_options_id = tep_db_prepare_input($HTTP_POST_VARS['products_options_id']);
        $option_name_array = $HTTP_POST_VARS['option_name'];
		$option_cmmnt_array = $HTTP_POST_VARS['option_comment'];
        $option_type = $HTTP_POST_VARS['option_type'];
        $option_length = $HTTP_POST_VARS['option_length'];
        $option_order = $HTTP_POST_VARS['option_order'];
		if ( $option_type != 0 ) $track_stock = 0 ; // only stock for attributes if attrbute is select not for textarea etc
		
        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);
          $option_comment = tep_db_prepare_input($option_cmmnt_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id, products_options_type, products_options_length, 
		                                                            products_options_comment, products_options_sort_order,products_options_track_stock) 
										              values ('" . (int)$products_options_id . "', '" . tep_db_input($option_name) . "', '" . (int)$languages[$i]['id'] . "', '" . $option_type . "', '" . $option_length 
													    . "', '" . tep_db_input($option_comment) . "', '" . $option_order ."', '" . (int)$track_stock . "' )");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_option_values':
        $value_name_array = $HTTP_POST_VARS['value_name'];
        $value_id = tep_db_prepare_input($HTTP_POST_VARS['value_id']);
        $option_id = tep_db_prepare_input($HTTP_POST_VARS['option_id']);

        //BOF - Zappo - Option Types v2 - For TEXT and FILE option types, No need to add anything...
        // Let's Check for OptionType first...
        $optionType_query = tep_db_query("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "' and language_id = '" . $languages_id . "'");
        $optionType = tep_db_fetch_array($optionType_query);
        switch ($optionType['products_options_type']) {
          case OPTIONS_TYPE_TEXT:
          case OPTIONS_TYPE_TEXTAREA:
          case OPTIONS_TYPE_FILE:
            // Do Nothing...
          break;
          default:
        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$value_id . "', '" . (int)$languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
        }

        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$option_id . "', '" . (int)$value_id . "')");

          break;
        }
//EOF - Zappo - Option Types v2 - For TEXT and FILE option types, No need to add anything...

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
     	 case 'add_product_attributes':
	    $tab_options = '' ;	  
	    $tab_values  = '' ;	 			 
//BOF - Zappo - Option Types v2 - For TEXT and FILE option types, Lock the value and always use OPTIONS_VALUES_TEXT.
        $products_options_query = tep_db_query("select products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $HTTP_POST_VARS['options_id'] . "'");
        $products_options_array = tep_db_fetch_array($products_options_query);
        $values_id = tep_db_prepare_input((($products_options_array['products_options_type'] == OPTIONS_TYPE_TEXT) || ($products_options_array['products_options_type'] == OPTIONS_TYPE_TEXTAREA) || ($products_options_array['products_options_type'] == OPTIONS_TYPE_FILE)) ? OPTIONS_VALUE_TEXT_ID : $HTTP_POST_VARS['values_id']);
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $options_id = tep_db_prepare_input($HTTP_POST_VARS['options_id']);
//        $values_id = tep_db_prepare_input($HTTP_POST_VARS['values_id']);
//EOF - Zappo - Option Types v2 - For TEXT and FILE option types, Lock the value and always use OPTIONS_VALUES_TEXT.
        $value_price = tep_db_prepare_input($HTTP_POST_VARS['value_price']);
        $price_prefix = tep_db_prepare_input($HTTP_POST_VARS['price_prefix']);

        $value_order = tep_db_prepare_input($HTTP_POST_VARS['value_order']);
        $_hide = array_keys( $HTTP_POST_VARS['hide'] ) ;  
        $attributes_hide_from_groups = '@,';
        if ( $_hide ) { // if any of the checkboxes are checked
           foreach($_hide as $val) {	
//		        foreach($HTTP_POST_VARS['hide'] as $val) {
//		        $attributes_hide_from_groups .= (int)$val . ','; 
		        $attributes_hide_from_groups .= tep_db_prepare_input($val) . ','; 
		   } // end foreach
	    }
		
        $attributes_hide_from_groups = substr($attributes_hide_from_groups,0,strlen($attributes_hide_from_groups)-1); // remove last comma		

// BOF change for SPPC attributes hide attributes from groups (added @ as value) 
//       tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values (null, '" . (int)$products_id . "', '" . (int)$options_id . "', '" . (int)$values_id . "', '" . (float)tep_db_input($value_price) . "', '" . tep_db_input($price_prefix) . "', '" . (int)$value_order . "')");

//         tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values (null, '" . (int)$products_id . "', '" . (int)$options_id . "', '" . (int)$values_id . "', '" . (float)tep_db_input($value_price) . "', '" . tep_db_input($price_prefix) . "', '" . (int)$value_order . "', '@')");
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . (int)$products_id . "', options_id = '" . (int)$options_id . "', options_values_id = '" . (int)$values_id . "', options_values_price = '" . (float)tep_db_input($value_price) . "', price_prefix = '" . tep_db_input($price_prefix) . "', products_options_sort_order = '" . tep_db_input($value_order) . "', attributes_hide_from_groups = '" . $attributes_hide_from_groups . "'");
		 
// EOF change for SPPC attributes hide attributes from groups
        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_id = tep_db_insert_id();

          $products_attributes_filename = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($HTTP_POST_VARS['products_attributes_maxcount']);

          if (tep_not_null($products_attributes_filename)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . (int)$products_attributes_id . ", '" . tep_db_input($products_attributes_filename) . "', '" . tep_db_input($products_attributes_maxdays) . "', '" . tep_db_input($products_attributes_maxcount) . "')");
          }
        }
		
// eric begin
		
		// continue with prices for customer groups other than retail
        $attribute_id = tep_db_insert_id();
	    $no_of_customer_groups = count( $customers_groups);
	    for ($x = 0; $x < $no_of_customer_groups; $x++) {
		$input_group = $customers_groups[$x];
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
		

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_option_name':  
//BOF - Zappo - Option Types v2 - Update to add option values to products_option.
        $option_name_array = $HTTP_POST_VARS['option_name'];
        $option_cmmnt_array = $HTTP_POST_VARS['option_comment'];
        $option_type = $HTTP_POST_VARS['option_type'];
        $option_length = $HTTP_POST_VARS['option_length'];
        $option_order = $HTTP_POST_VARS['option_order'];
        $option_id = tep_db_prepare_input($HTTP_POST_VARS['option_id']);
		//$track_stock = $HTTP_POST_VARS['track_stock'];
        $track_stock=isset($HTTP_POST_VARS['track_stock'])?1:0;
		if ( $option_type != 0 ) $track_stock = 0 ; // only stock for attributes if attrbute is select not for textarea etc

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);
          $option_comment = tep_db_prepare_input($option_cmmnt_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set 
		              products_options_name = '" . tep_db_input($option_name) . "', 
					  products_options_comment = '" . tep_db_input($option_comment) . "', 
					  products_options_type = '" . $option_type . "', 
					  products_options_length = '" . $option_length . "', 
					  products_options_track_stock = '" . (int)$track_stock . "', 					  
					  products_options_sort_order = '" . $option_order . "' where products_options_id = '" . (int)$option_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        // - Zappo - Option Types v2 - Automate insertion, deletion or replacement of text option values
        switch ($option_type) {
          case OPTIONS_TYPE_TEXT:
          case OPTIONS_TYPE_TEXTAREA:
          case OPTIONS_TYPE_FILE:
            // Let's Check for pov2po value first... (IF AN OPTION'S TYPE IS CHANGED, ALL OPTION VAlUES ARE LOST!!!)
            $pov2po_query = tep_db_query("select products_options_values_to_products_options_id as id from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");
            $pov2po = tep_db_fetch_array($pov2po_query);
            if ($pov2po['id']) {
              // - Zappo - Option Types v2 - NEXT LINE DELETES ALL OPTION VALUES IF OPTION TYPE IS CHANGED!!!
              tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . $option_id . "'");
// BOF SPPC attributes for groups
			  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id = '" . (int)$attribute_id . "'");
// EOF SPPC attributes for groups			  
            }
            // Now Let's Check for and Update product_attribute values... (IF AN OPTION'S TYPE IS CHANGED, ALL ATTRIBUTES' OPTION VAlUES ARE LOST!!!)
            $done = false;
            $pattrib_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_id = '" . (int)$option_id . "' and options_values_id != '" . OPTIONS_VALUES_TEXT_ID . "'");
            while ($pattrib = tep_db_fetch_array($pattrib_query)) {
              // - Zappo - Option Types v2 - NEXT LINE UPDATES ALL OPTION VALUES IF OPTION TYPE IS CHANGED!!! (You'll probably have some double values!)
              if ($done == false) {
                tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_id = '" . OPTIONS_VALUES_TEXT_ID . "' where options_id = '" . $option_id . "'");
                $done = true;
              }
            }
          default:
            tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . OPTIONS_VALUES_TEXT_ID . "'");
          break;	
        }
//EOF - Zappo - Option Types v2 - Update to add option values to products_option.

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_value':
        $value_name_array = $HTTP_POST_VARS['value_name'];
        $value_id = tep_db_prepare_input($HTTP_POST_VARS['value_id']);
        $option_id = tep_db_prepare_input($HTTP_POST_VARS['option_id']);

        //BOF - Zappo - Option Types v2 - For TEXT and FILE option types, automatically add OPTIONS_VALUE_TEXT and TEXT_UPLOAD_NAME
        // Let's Check for OptionType first...
        $optionType_query = tep_db_query("select distinct products_options_type from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "' and language_id = '" . $languages_id . "'");
        $optionType = tep_db_fetch_array($optionType_query);
        switch ($optionType['products_options_type']) {
          case OPTIONS_TYPE_TEXT:
          case OPTIONS_TYPE_TEXTAREA:
          case OPTIONS_TYPE_FILE:
            // Let's Check for pov2po value first... (IF AN OPTION'S TYPE IS CHANGED, ALL OPTION VAlUES ARE LOST!!!)
            $pov2po_query = tep_db_query("select distinct products_options_values_to_products_options_id as id from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");
            $pov2po = tep_db_fetch_array($optionType_query);
            if ($pov2po['id']) {
              // - Zappo - Option Types v2 - NEXT LINES DELETE ALL OPTION VALUES IF OPTION TYPE IS CHANGED!!!
              tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . $option_id . "'");
            }
            tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $value_id . "'");
          break;
          default:
            for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
              $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

              tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value_name) . "' where products_options_values_id = '" . tep_db_input($value_id) . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
            }
            tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . (int)$option_id . "'  where products_options_values_id = '" . (int)$value_id . "'");
          break;
        }
//EOF - Zappo - Option Types v2 - For TEXT and FILE option types, automatically add OPTIONS_VALUE_TEXT and TEXT_UPLOAD_NAME
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
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
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $options_id = tep_db_prepare_input($HTTP_POST_VARS['options_id']);
//        $values_id = tep_db_prepare_input($HTTP_POST_VARS['values_id']);
//EOF - Zappo - Option Types v2 - Enforce rule that TEXT and FILE Options use value OPTIONS_VALUE_TEXT_ID
        $value_price = tep_db_prepare_input($HTTP_POST_VARS['value_price']);
        $price_prefix = tep_db_prepare_input($HTTP_POST_VARS['price_prefix']);
        $value_order = tep_db_prepare_input($HTTP_POST_VARS['value_order']);
        $attribute_id = tep_db_prepare_input($HTTP_POST_VARS['attribute_id']);
		
        $_hide = array_keys( $HTTP_POST_VARS['hide'] ) ;  
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
 
	    $no_of_customer_groups = count( $customers_groups);
	    for ($x = 0; $x < $no_of_customer_groups; $x++) {
		$input_group = $customers_groups[$x];
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

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info . $test));
        break;
      case 'delete_option':
        $option_id = tep_db_prepare_input($_GET['option_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");
		tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_value':
        $value_id = tep_db_prepare_input($_GET['value_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
// 2.3.4        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
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

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
    }
  }
  //BOF - Zappo - Option Types v2 - Define Option Types List
  $products_options_types_list[OPTIONS_TYPE_SELECT] = OPTIONS_TYPE_SELECT_NAME;
  $products_options_types_list[OPTIONS_TYPE_TEXT] = OPTIONS_TYPE_TEXT_NAME;
  $products_options_types_list[OPTIONS_TYPE_TEXTAREA] = OPTIONS_TYPE_TEXTAREA_NAME;
  $products_options_types_list[OPTIONS_TYPE_RADIO] = OPTIONS_TYPE_RADIO_NAME;
  $products_options_types_list[OPTIONS_TYPE_CHECKBOX] = OPTIONS_TYPE_CHECKBOX_NAME;
  $products_options_types_list[OPTIONS_TYPE_FILE] = OPTIONS_TYPE_FILE_NAME;
  $products_options_types_list[OPTIONS_TYPE_IMAGE] = OPTIONS_TYPE_IMAGE_NAME;
  
// Draw a pulldown for Option Types
function draw_optiontype_pulldown($name, $default = '') {
  global $products_options_types_list;
  $values = array();
  foreach ($products_options_types_list as $id => $text) {
    $values[] = array('id' => $id, 'text' => $text);
  }
  return tep_draw_pull_down_menu($name, $values, $default);
}
function tep_optiontype_pulldown() {
  global $products_options_types_list;
  $values = array();
  foreach ($products_options_types_list as $id => $text) {
    $values[] = array('id' => $id, 'text' => $text);
  }
  return $values ;
}
// Translate option_type_values to english string
function translate_type_to_name($opt_type) {
  global $products_options_types_list;
  return isset($products_options_types_list[$opt_type]) ? $products_options_types_list[$opt_type] : 'Error ' . $opt_type;
}
//EOF - Zappo - Option Types v2 - Define Option Types List

  require(DIR_WS_INCLUDES . 'template_top.php');
  
  if( isset($_GET['action']) && ( $_GET['action'] != 'update_option_attribute' )  && ( $_GET['action'] != 'delete_option_attribute' ) ) {
	 $tab_options = ' in ' ;	  	  
	 $tab_values  = ' in ' ;	 
  }
?>

<!-- options and values//-->
          <div class="row">	
		  
		     <?php include( DIR_WS_MODULES . 'products_attributes_options.php' ) ; ?>
			 
		     <?php include( DIR_WS_MODULES . 'products_attributes_values.php' ) ; ?>			 
		  
		  
		 </div>
		 
<!-- prducts with attribues		 -->
         <div class="row">	
		  
		     <?php include( DIR_WS_MODULES . 'products_attributes_products.php' ) ; ?>			   
		  
		 </div>		  
<?php
function tep_get_hide_info($customers_groups, $attributes_hide_from_groups) {
  $hide_attr_from_groups_array = explode(',', $attributes_hide_from_groups);
  $hide_attr_from_groups_array = array_slice($hide_attr_from_groups_array, 1); // remove "@" from the array
  $attribute_hidden_from_string = '';
	$hide_info = '';
	if (LAYOUT_HIDE_FROM == '1') {
	      for ($i = 0; $i < count($customers_groups); $i++) {
		      if (in_array($customers_groups[$i]['customers_group_id'], $hide_attr_from_groups_array)) {
	        $attribute_hidden_from_string .= $customers_groups[$i]['customers_group_name'] . ', '; 
		      }
              } // end for ($i = 0; $i < count($customers_groups); $i++)
	      $attribute_hidden_from_string = rtrim($attribute_hidden_from_string); // remove space on the right
	      $attribute_hidden_from_string = substr($attribute_hidden_from_string,0,strlen($attribute_hidden_from_string) -1); // remove last comma
	      if (!tep_not_null($attribute_hidden_from_string)) { 
	      $attribute_hidden_from_string = TEXT_GROUPS_NONE; 
	      }
	      $attribute_hidden_from_string = TEXT_HIDDEN_FROM_GROUPS . $attribute_hidden_from_string;
				// if the string in the database field is @, everything will work fine, however tep_not_null
				// will not discover the associative array is empty therefore the second check on the value
	if (tep_not_null($hide_attr_from_groups_array) && tep_not_null($hide_attr_from_groups_array[0])) {
		  $hide_info = tep_image(DIR_WS_ICONS . 'tick_black.gif', $attribute_hidden_from_string, 20, 16);
		} else {
		  $hide_info = tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $attribute_hidden_from_string, 20, 16);
	  }
	} else {
/*		
		// default layout: icons for all groups
      for ($i = 0; $i < count($customers_groups); $i++) {
        if (in_array($customers_groups[$i]['customers_group_id'], $hide_attr_from_groups_array)) {
          $hide_info .= tep_image(DIR_WS_ICONS . 'icon_tick.gif', $customers_groups[$i]['customers_group_name'], 11, 11) . '&nbsp;&nbsp;';
        } else {
          $hide_info .= tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', 11, 11) . '&nbsp;&#160;';
        }
      }
*/	
		// default layout: icons for all groups
	  for ($i = 0; $i < count($customers_groups); $i++) {
        if (in_array($customers_groups[$i]['customers_group_id'], $hide_attr_from_groups_array)) {
			$hide_info .= tep_glyphicon('remove-sign', 'danger') . $customers_groups[$i]['customers_group_name'] . '&nbsp;&nbsp;';
		} else {
			$hide_info .=  tep_image(DIR_WS_IMAGES . 'pixel_trans.gif'  ) . '&nbsp;&nbsp;';
		}
	  }	  
	}
	return $hide_info;
}
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>