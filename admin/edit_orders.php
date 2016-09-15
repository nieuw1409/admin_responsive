<?php
/*
  $Id: edit_orders.php v5.0.5 08/27/2007 djmonkey1 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License http://www.gnu.org/licenses/
  
    Order Editor is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
  
  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
  The original Order Editor contribution was written by Jonathan Hilgeman of SiteCreative.com
  
  Much of Order Editor 5.x is based on the order editing file found within the MOECTOE Suite Public Betas written by Josh DeChant
  
  Many, many people have contributed to Order Editor in many, many ways.  Thanks go to all- it is truly a community project.  
  
*/

  require('includes/application_top.php');
 

  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
   
  // Include currencies class
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// bof multi stores
  $stores_orders_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
  while ($stores_order =  tep_db_fetch_array($stores_orders_query)) {
    $stores_orders_array[] = array("id" => $stores_order['stores_id'], "text" => "&#160;".$stores_order['stores_name']."&#160;");
  }
// eof multi stores   
 
 //orders status
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("SELECT orders_status_id, orders_status_name 
                                       FROM " . TABLE_ORDERS_STATUS . " 
									   WHERE language_id = '" . (int)$languages_id . "' order by orders_status_name");
									   
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  
  $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
  $result = $stores_customers_query ;
  if (tep_db_num_rows($result) > 0){
 	  $SelectStoreBox = array(array('id' => '', 'text' => TEXT_SELECT_STORES));		
      while($db_Row = tep_db_fetch_array($result)){		  
         $SelectStoreBox[] = array('id'   => $db_Row["stores_id"] ,
                                   'text' => $db_Row["stores_name"] );
      }
  }	
	  
  $price_prefix_array = array(array('id' => '+', 'text' => '+'),
                              array('id' => '-', 'text' => '-'));	
	  
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'edit');

  if (isset($action)) {
    switch ($action) {
    
    ////
    // Update Order
      case 'update_order':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
        $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
        
        
        // Update Products
        if (is_array($HTTP_POST_VARS['update_products'])) {
          foreach($HTTP_POST_VARS['update_products'] as $orders_products_id => $products_details) {
		  
		  	//  Update Inventory Quantity
			$order_query = tep_db_query("
			SELECT products_id, products_quantity 
			FROM " . TABLE_ORDERS_PRODUCTS . " 
			WHERE orders_id = '" . (int)$oID . "'
			AND orders_products_id = '" . (int)$orders_products_id . "'");
			$order_products = tep_db_fetch_array($order_query);
			
			// First we do a stock check 
			
			if ($products_details['qty'] != $order_products['products_quantity']){
			$quantity_difference = ($products_details['qty'] - $order_products['products_quantity']);
				if (STOCK_LIMITED == 'true'){
					tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity - " . $quantity_difference . ",
					products_ordered = products_ordered + " . $quantity_difference . " 
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
// QT Pro Addon BOF	
					if (ORDER_EDITOR_USE_QTPRO == 'true') { 
					$attrib_q = tep_db_query("select distinct op.products_id, po.products_options_id, pov.products_options_values_id
						                        from products_options po, products_options_values pov, products_options_values_to_products_options po2pov, orders_products_attributes opa, orders_products op
						                        where op.orders_id = '" . $oID . "'
															      and op.orders_products_id = '" . $orders_products_id . "'
															      and products_options_values_name = opa.products_options_values
						                        and pov.products_options_values_id = po2pov.products_options_values_id
						                        and po.products_options_id = po2pov.products_options_id
						                        and products_options_name = opa.products_options");
					while($attrib_set = tep_db_fetch_array($attrib_q)) {
						// corresponding to each option find the attribute ids ( opts and values id )
						$products_stock_attributes[] = $attrib_set['products_options_id'].'-'.$attrib_set['products_options_values_id'];
					}
					sort($products_stock_attributes, SORT_NUMERIC); // Same sort as QT Pro stock
					$products_stock_attributes = implode($products_stock_attributes, ',');
					 // update the stock
					 tep_db_query("update ".TABLE_PRODUCTS_STOCK." set products_stock_quantity = products_stock_quantity - ".$quantity_difference . " where products_id= '" . $order_products['products_id'] . "' and products_stock_attributes='".$products_stock_attributes."'");				 
					}
// QT Pro Addon EOF
				 } else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered + " . $quantity_difference . "
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
				}
			}

		 
		   if ( (isset($products_details['delete'])) && ($products_details['delete'] == 'on') ) {
		     //check first to see if product should be deleted
		   
		   			 //update quantities first
			       if (STOCK_LIMITED == 'true'){
				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity + " . $products_details["qty"] . ",
					products_ordered = products_ordered - " . $products_details["qty"] . " 
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
// QT Pro Addon BOF	
					if (ORDER_EDITOR_USE_QTPRO == 'true') { 
					$attrib_q = tep_db_query("select distinct op.products_id, po.products_options_id, pov.products_options_values_id
						                        from products_options po, products_options_values pov, products_options_values_to_products_options po2pov, orders_products_attributes opa, orders_products op
						                        where op.orders_id = '" . $oID . "'
															      and op.orders_products_id = '" . $orders_products_id . "'
															      and products_options_values_name = opa.products_options_values
						                        and pov.products_options_values_id = po2pov.products_options_values_id
						                        and po.products_options_id = po2pov.products_options_id
						                        and products_options_name = opa.products_options");
					while($attrib_set = tep_db_fetch_array($attrib_q)) {
						// corresponding to each option find the attribute ids ( opts and values id )
						$products_stock_attributes[] = $attrib_set['products_options_id'].'-'.$attrib_set['products_options_values_id'];
					}
					sort($products_stock_attributes, SORT_NUMERIC); // Same sort as QT Pro stock
					$products_stock_attributes = implode($products_stock_attributes, ',');
					 // update the stock
					 tep_db_query("update ".TABLE_PRODUCTS_STOCK." set products_stock_quantity = products_stock_quantity + ".$products_details["qty"] . " where products_id= '" . $order_products['products_id'] . "' and products_stock_attributes='".$products_stock_attributes."'");
					 }
// QT Pro Addon EOF
				} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered - " . $products_details["qty"] . "
					WHERE products_id = '" . (int)$order_products['products_id'] . "'");
				}
		   
                    tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS . "  
	                              WHERE orders_id = '" . (int)$oID . "'
					              AND orders_products_id = '" . (int)$orders_products_id . "'");
      
	                tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
	                              WHERE orders_id = '" . (int)$oID . "'
                                  AND orders_products_id = '" . (int)$orders_products_id . "'");
	                
					tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "
	                              WHERE orders_id = '" . (int)$oID . "'
                                  AND orders_products_id = '" . (int)$orders_products_id . "'");
           
		   } else {
		     //not deleted=> updated
		   
            // Update orders_products Table
             	$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS . " SET
					products_model = '" . $products_details["model"] . "',
					products_name = '" . oe_html_quotes($products_details["name"]) . "',
					products_price = '" . $products_details["price"] . "',
					final_price = '" . $products_details["final_price"] . "',
					products_tax = '" . $products_details["tax"] . "',
					products_quantity = '" . $products_details["qty"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_id = '$orders_products_id';";
				tep_db_query($Query);
          
              // Update Any Attributes
				// Update Any Attributes
				if(isset($products_details['attributes'])) { 
				  foreach($products_details['attributes'] as $orders_products_attributes_id => $attributes_details) {
					$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
						products_options = '" . $attributes_details["option"] . "',
						products_options_values = '" . $attributes_details["value"] . "',
						options_values_price ='" . $attributes_details["price"] . "',
						price_prefix ='" . $attributes_details["prefix"] . "'
						where orders_products_attributes_id = '$orders_products_attributes_id';";
						tep_db_query($Query);
					}//end of foreach($products_details["attributes"]
				}// end of if(isset($products_details[attributes]))

            } //end if/else product details delete= on
          } //end foreach post update products
        }//end if is-array update products
		
	
	  //update any downloads that may exist
      if (is_array($HTTP_POST_VARS['update_downloads'])) {
	  foreach($HTTP_POST_VARS['update_downloads'] as $orders_products_download_id => $download_details) {
		$Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET
					orders_products_filename = '" . $download_details["filename"] . "',
					download_maxdays = '" . $download_details["maxdays"] . "',
					download_count = '" . $download_details["maxcount"] . "'
					WHERE orders_id = '" . (int)$oID . "'
					AND orders_products_download_id = '$orders_products_download_id';";
					tep_db_query($Query);
			}
		}	//end downloads
		
						
				//delete or update comments
/* eric       $_hide = array_keys( $HTTP_POST_VARS['hide'] ) ;  
		if ( isset($HTTP_POST_VARS['hide'] ) ) {
		   $_hide = array_keys( $HTTP_POST_VARS['hide'] ) ;  
		} else {
		   $_hide = array() ;
		}				
*/		

        $shipping = array();
      
        if (is_array($HTTP_POST_VARS['update_totals'])) {
          foreach($HTTP_POST_VARS['update_totals'] as $total_index => $total_details) {
            extract($total_details, EXTR_PREFIX_ALL, "ot");
            if ($ot_class == "ot_shipping") {
           
               $shipping['cost'] = $ot_value;
               $shipping['title'] = $ot_title;
               $shipping['id'] = $ot_id;
			
		    } // end if ($ot_class == "ot_shipping")
          } //end foreach
	    } //end if is_array

        if (tep_not_null($shipping['id'])) {
           tep_db_query("UPDATE " . TABLE_ORDERS . " SET shipping_module = '" . $shipping['id'] . "' WHERE orders_id = '" . (int)$oID . "'");
        }

        $order = new manualOrder($oID);
        $order->adjust_zones();

        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

        // Get the shipping quotes- if we don't have shipping quotes shipping tax calculation can't happen
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();

		if (DISPLAY_PRICE_WITH_TAX == 'true') {//extract the base shipping cost or the ot_shipping module will add tax to it again
		   $module = substr($GLOBALS['shipping']['id'], 0, strpos($GLOBALS['shipping']['id'], '_'));
		   $tax = tep_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		   $order->info['total'] -= ( $order->info['shipping_cost'] - ($order->info['shipping_cost'] / (1 + ($tax /100))) );
           $order->info['shipping_cost'] = ($order->info['shipping_cost'] / (1 + ($tax /100)));
		}

		//this is where we call the order total modules
		require( 'order_editor/order_total.php');
		$order_total_modules = new order_total();
        $order_totals = $order_total_modules->process();  

        $current_ot_totals_array = array();
		$current_ot_titles_array = array();
        $current_ot_totals_query = tep_db_query("select class, title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
        while ($current_ot_totals = tep_db_fetch_array($current_ot_totals_query)) {
          $current_ot_totals_array[] = $current_ot_totals['class'];
		  $current_ot_titles_array[] = $current_ot_totals['title'];
        }

		tep_db_query("DELETE FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . (int)$oID . "'");

        $j=1; //giving something a sort order of 0 ain't my bag baby
		$new_order_totals = array();
	

	    if (is_array($HTTP_POST_VARS['update_totals'])) { //1
          foreach($HTTP_POST_VARS['update_totals'] as $total_index => $total_details) { //2
            extract($total_details, EXTR_PREFIX_ALL, "ot");
            if (!strstr($ot_class, 'ot_custom')) { //3
             for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //4

			  if ($order_totals[$i]['code'] == 'ot_tax') { //5
			  $new_ot_total = ((in_array($order_totals[$i]['title'], $current_ot_titles_array)) ? false : true);
			  } else { //within 5
			  $new_ot_total = ((in_array($order_totals[$i]['code'], $current_ot_totals_array)) ? false : true);
			  }  //end 5 if ($order_totals[$i]['code'] == 'ot_tax')
 
			  if ( ( ($order_totals[$i]['code'] == 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) && ($order_totals[$i]['title'] == $ot_title) ) || ( ($order_totals[$i]['code'] != 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) ) ) { //6
			  //only good for components that show up in the $order_totals array

				if ($ot_title != '') { //7
                  $new_order_totals[] = array('title' => $ot_title,
                                              'text' => (($ot_class != 'ot_total') ? $order_totals[$i]['text'] : '<strong>' . $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']) . '</strong>'),
                                              'value' => (($order_totals[$i]['code'] != 'ot_total') ? $order_totals[$i]['value'] : $order->info['total']),
                                              'code' => $order_totals[$i]['code'],
                                              'sort_order' => $j);
                $written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
				$j++;
                } else { //within 7

				  $order->info['total'] += ($ot_value*(-1)); 
				  $written_ot_totals_array[] = $ot_class;
				  $written_ot_titles_array[] = $ot_title; 

                } //end 7

			  } elseif ( ($new_ot_total) && (!in_array($order_totals[$i]['title'], $current_ot_titles_array)) ) { //within 6

                $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$current_ot_titles_array[] = $order_totals[$i]['title'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
                //echo $order_totals[$i]['code'] . "<br>"; for debugging- use of this results in errors

			  } elseif ($new_ot_total) { //also within 6
                $order->info['total'] += ($order_totals[$i]['value']*(-1));
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
              }//end 6
           }//end 4
         } elseif ( (tep_not_null($ot_value)) && (tep_not_null($ot_title)) ) { // this modifies if (!strstr($ot_class, 'ot_custom')) { //3
            $new_order_totals[] = array('title' => $ot_title,
                                        'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                                        'value' => $ot_value,
                                        'code' => 'ot_custom_' . $j,
                                        'sort_order' => $j);
            $order->info['total'] += $ot_value;
			$written_ot_totals_array[] = $ot_class;
		    $written_ot_titles_array[] = $ot_title;
            $j++;
          } //end 3
		  
		    //save ot_skippy from certain annihilation
			 if ( (!in_array($ot_class, $written_ot_totals_array)) && (!in_array($ot_title, $written_ot_titles_array)) && (tep_not_null($ot_value)) && (tep_not_null($ot_title)) && ($ot_class != 'ot_tax') && ($ot_class != 'ot_loworderfee') ) { //7
			//this is supposed to catch the oddball components that don't show up in $order_totals
				 
				    $new_order_totals[] = array(
					        'title' => $ot_title,
                            'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                            'value' => $ot_value,
                            'code' => $ot_class,
                            'sort_order' => $j);
               //$current_ot_totals_array[] = $order_totals[$i]['code'];
				//$current_ot_titles_array[] = $order_totals[$i]['title'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
				 
				 } //end 7
        } //end 2
	  } else {//within 1
	  // $HTTP_POST_VARS['update_totals'] is not an array => write in all order total components that have been generated by the sundry modules
	   for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //8
	                  $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $j++;
				
			} //end 8
				
		} //end if (is_array($HTTP_POST_VARS['update_totals'])) { //1
	  
		for ($i=0, $n=sizeof($new_order_totals); $i<$n; $i++) {
          $sql_data_array = array('orders_id' => $oID,
                                  'title' => $new_order_totals[$i]['title'],
                                  'text' => $new_order_totals[$i]['text'],
                                  'value' => $new_order_totals[$i]['value'], 
                                  'class' => $new_order_totals[$i]['code'], 
                                  'sort_order' => $new_order_totals[$i]['sort_order']);
          tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
        }
		
        
//        if (isset($_POST['subaction'])) {
//          switch($_POST['subaction']) {
//            case 'add_product':
//              tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit#products'));
//              break;
              
//          }
//        }
        
		// 1.5 SUCCESS MESSAGE #####
		
		
	// CHECK FOR NEW EMAIL CONFIRMATION

 
	 
	 if ($order_updated)	{
		//	$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}

		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
		

		
	break;
		
	// 3. NEW ORDER EMAIL ###############################################################################################
	case 'email':
          
		$oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
		$order = new manualOrder($oID);
		
// bof order editor 5 0 9
		$order_totals_table_beginn = '<table border="0" cellpadding="5" cellspacing="0">';
		$order_totals_zelle_beginn = '<tr><td width="280" style="font-size: 12px">';
		$order_totals_zelle_mitte = '</td><td style="font-size: 12px" align="right">';
		$order_totals_zelle_end = '</td></tr>';
		$order_totals_table_end = '</table>';


		// initialized for the email confirmation
		if (EMAIL_USE_HTML == 'true'){
  		$products_ordered = $order_totals_table_beginn;
		} else{
  		$products_ordered = '';
		}

  	    $subtotal = 0;
  	    $total_tax = 0;

// eof order editor 5 0 9		
		
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	  	//loop all the products in the order
		 	$products_ordered_attributes = '';
	  	  if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
	    	  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
					$products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . $order->products[$i]['attributes'][$j]['value'];
      	      }
    	  }
	
// bof order editor 5 0 9
//	   	$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . $products_model . ' = ' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . $products_ordered_attributes . "\n";
//		}
		
		  $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    	  $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    	  $total_cost += $total_products_price;
		
          if (EMAIL_USE_HTML == 'true'){
            if ($order->products[$i]['model']) {
          	  $products_ordered .= $order_totals_zelle_beginn . $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $order_totals_zelle_mitte . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . $order_totals_zelle_end;
	  		} else {
          	  $products_ordered .= $order_totals_zelle_beginn . $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' = ' . $order_totals_zelle_mitte . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . $order_totals_zelle_end;
	  		}	
          } else {
            if ($order->products[$i]['model']) { 
					$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";	
			} else {
					$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";	
			}
   		  }
 		} 

    	$Text_Billing_Adress= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
    			             		 EMAIL_SEPARATOR . "\n" .
				     				 $order->billing['name'] . "\n";
									 
		if ($order->billing['company']) {
		  	$Text_Billing_Adress .= $order->billing['company'] . "\n";
	    }
		
		$Text_Billing_Adress .= $order->billing['street_address'] . "\n";
		
		if ($order->billing['suburb']) {
				$Text_Billing_Adress .= $order->billing['suburb'] . "\n";
	    }
		
		$Text_Billing_Adress .= $order->billing['city'] . "\n";
	    if ($order->billing['state']) {
		  	$Text_Billing_Adress .= $order->billing['state'] . "\n";
		}
		
		$Text_Billing_Adress .= $order->billing['postcode'] . "\n" .
								$order->billing['country'] . "\n\n";
															
	 	$Text_Delivery_Address = "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
        		            			EMAIL_SEPARATOR . "\n" .
										$order->delivery['name'] . "\n";
										
		if ($order->delivery['company']) {
		  	$Text_Delivery_Address .= $order->delivery['company'] . "\n";
	    }
		
		$Text_Delivery_Address .= $order->delivery['street_address'] . "\n";

		if ($order->delivery['suburb']) {
		  	$Text_Delivery_Address .= $order->delivery['suburb'] . "\n";
	    }
		
		$Text_Delivery_Address .= $order->delivery['city'] . "\n";

		if ($order->delivery['state']) {
		  	$Text_Delivery_Address .= $order->delivery['state'] . "\n";
	    }

		$Text_Delivery_Address .= $order->delivery['postcode'] . "\n" .	$order->delivery['country'] . "\n";
 		
		$standaard_email = 'false' ;
//		if ( FILENAME_EMAIL_ORDER_TEXT !== �FILENAME_EMAIL_ORDER_TEXT� ){	
			// only use if email order text is installed 
  		    if (EMAIL_USE_HTML == 'true'){
  				$products_ordered .= $order_totals_table_end;
			}
 			if (EMAIL_USE_HTML == 'true'){
  				$text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWORDER . "' and language_id = '" . $languages_id . "' and stores_id='" . $order->info['stores_id'] . "'");	
			} else{
  				$text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWORDER . "' and language_id = '" . $languages_id . "' and stores_id='" . $order->info['stores_id'] . "'");
			}
      
            $werte = tep_db_fetch_array($text_query);
            $text = $werte["eorder_text_one"];
	        $subject = $werte['eorder_title'];
			$text = preg_replace('/<-SYS_STORE_NAME->/', STORE_NAME, $text);
			$text = preg_replace('/<-SYS_INVOICE_ID->/', $oID, $text);
			$text = preg_replace('/<-SYS_INVOICE_URL->/', tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL', false), $text);
			$text = preg_replace('/<-SYS_DATE_ORDERED->/', tep_date_long( $order->info[ 'date_purchased' ] ), $text ) ;
			if ($order->info['comments']) {
				$text = preg_replace('/<-SYS_CUSTOMER_COMMENTS->/', tep_db_output($order->info['comments']), $text);
	  	    } else{
	  		    $text = preg_replace('/<-SYS_CUSTOMER_COMMENTS->/', '', $text);
	  	    }  
			$text = preg_replace('/<-SYS_PRODUCTS_LIST->/', $products_ordered, $text);
			if (EMAIL_USE_HTML == 'true'){	
	    	   $list_total = $order_totals_table_beginn;
	    	   for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
					$list_total .= $order_totals_zelle_beginn . strip_tags($order->totals[$i]['title']) . $order_totals_zelle_mitte . strip_tags($order->totals[$i]['text']) . $order_totals_zelle_end;	
			   }
	    	   $list_total .= $order_totals_table_end;
			} else{
	    	   for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
					$list_total .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";
			   }
			}	
			$text = preg_replace('/<-SYS_TOTAL_LIST->/', $list_total, $text);			
			if ($order->content_type != 'virtual') {
				$text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', $Text_Delivery_Address , $text);				
			}
			elseif($order->content_type == 'virtual') {	
					if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
		  			$text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', EMAIL_TEXT_DOWNLOAD_SHIPPING . "\n" . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL', false), $text);
					} else{
		  			$text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', '', $text);
					}	
			} else{
	  		$text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', '', $text);
			}
			$text = preg_replace('/<-SYS_INVOICE_ADDRESS->/', $Text_Billing_Adress, $text); 
			$text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT->/', $order->info['payment_method'], $text);
	    $text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT_FOOTER->/', EMAIL_TEXT_FOOTER, $text);	  	
	    
	  	$email_order = $text;
/*		
//   	 } else {
   	 	// the contribution Email HTML is not installed so we must use the standaard text email
//   	 	$standaard_email = 'true' ;
//   	    }

   	 	
   	    if ( $standaard_email == 'true' ) {
			//Build the standaard email
	  	  $email_order = 	STORE_NAME . "\n" . 
      	             	    EMAIL_SEPARATOR . "\n" . 
							EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$oID . "\n" .
  							EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$oID, 'SSL') . "\n" .
            	    	    EMAIL_TEXT_DATE_MODIFIED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

	  	  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
    		           	  EMAIL_SEPARATOR . "\n" . 
        		          $products_ordered . 
          	  	          EMAIL_SEPARATOR . "\n";

	  	  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
             $email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";
    	  }

	  	  if ($order->content_type != 'virtual') {
    		$email_order .= $Text_Delivery_Address   ; 		
	  	  }

    	  $email_order .= $Text_Billing_Adress ;
    	
	  	  $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
    		              EMAIL_SEPARATOR . "\n";
	      $email_order .= $order->info['payment_method'] . "\n\n";
		
		        
		//	if ( ($order->info['payment_method'] == ORDER_EDITOR_SEND_INFO_PAYMENT_METHOD) && (EMAIL_TEXT_PAYMENT_INFO) ) { 
		//     $email_order .= EMAIL_TEXT_PAYMENT_INFO . "\n\n";
		//   }
		//I'm not entirely sure what the purpose of this is so it is being shelved for now
	}
// eof order editor 5 0 9	
*/
		 if (EMAIL_TEXT_FOOTER) {
			$email_order .= EMAIL_TEXT_FOOTER . "\n\n";
	     }

    //code for plain text emails which changes the � sign to EUR, otherwise the email will show ? instead of �
         $email_order = str_replace("�","EUR",$email_order);
	     $email_order = str_replace("&nbsp;"," ",$email_order);

	  //code which replaces the <br> tags within EMAIL_TEXT_PAYMENT_INFO and EMAIL_TEXT_FOOTER with the proper \n
	     $email_order = str_replace("<br>","\n",$email_order);
	  
	  // picture mode
	     $email_order = tep_add_base_ref($email_order);

	  //send the email to the customer
// bof order editor 5_0_8 	  
//	  tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	  //tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
// bof added for pdfinvoice email attachment:
//    if (FILENAME_PDF_INVOICE    !== 'FILENAME_PDF_INVOICE'    ) {
        $pdf_data = '' ;
    	if ( ORDER_EDITOR_ADD_PDF_INVOICE_EMAIL == 'true' ) {
          // All we do is set the order_id for pdfinvoice.php to pick up
          //$HTTP_GET_VARS['order_id'] = $insert_id;
          // set stream mode
          $stream = true;
          $oID= $HTTP_GET_VARS['oID'] ;
          $invoice_number = $HTTP_GET_VARS['oID'] ;
        
          $pdf_data = include(FILENAME_PDF_INVOICE );       
          $file_name = $HTTP_GET_VARS['oID'] .'.pdf' ;
          // add text to email informing customer a pdf invoice copy has been attached:        
		  $email_order .= EMAIL_TEXT_PDF_ATTACHED ."\n\n";
          $file_name = $HTTP_GET_VARS['oID'] .'.pdf' ;
          // send email with pdf invoice attached. Check to make sure pdfinvoice.php returns some data, else send standard email
          // note $order object reinstantiated by inclusion of pdfinvoice.php hence customer['name']
		}
        if (tep_not_null($pdf_data)) {
            tep_mail_string_attachment($order->customer['name'], $order->customer['email_address'],  $subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $pdf_data, $file_name);
        } else {
            tep_mail($order->customer['name'], $order->customer['email_address'],  $subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
//      } else {
//        // send vanilla e-mail - if email attachment option is false
//        tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
//      }
//    } else {
//        // send vanilla e-mail - if email attachment option is false
//        tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
//    }  
    
// eof added for pdfinvoice email attachment:

// eof order editor 5_0_8

   // send emails to other people as necessary
       if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
          tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
       }
  
         //do the dirty
 		
		$messageStack->add_session(SUCCESS_EMAIL_SENT, 'success');
		
        tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action','languages')) . 'action=edit'));
		  
	  break;

        
    ////
    // Edit Order
      case 'edit':
        if (!isset($HTTP_GET_VARS['oID'])) {
		$messageStack->add(ERROR_NO_ORDER_SELECTED, 'error');
          break;
		  }
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
        $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $order_exists = true;
        if (!tep_db_num_rows($orders_query)) {
        $order_exists = false;
          $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
          break;
        }
        
        $order = new manualOrder($oID);
        $shippingKey = $order->adjust_totals($oID);
        $order->adjust_zones();
        
        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

        // Get the shipping quotes
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();
 
     
        break;		
    }
  }

  // currecies drop-down array
  $currency_query = tep_db_query("select distinct title, code from " . TABLE_CURRENCIES . " order by code ASC");  
  $currency_array = array();
  while($currency = tep_db_fetch_array($currency_query)) {
    $currency_array[] = array('id' => $currency['code'],
                              'text' => $currency['code'] . ' - ' . $currency['title']);
  }
  require(DIR_WS_INCLUDES . 'template_top.php');

   include('order_editor/javascript.php');  
      //because if you haven't got your javascript, what have you got?
?>

<div class="clearfix"></div>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
     <div class="page-header">
        <h1 class="col-xs-12 col-md-6"><?php echo sprintf(HEADING_TITLE, $oID, tep_datetime_short($order->info['date_purchased']))  ; ?></h1> 
        <div class="clearfix"></div>
     </div><!-- page-header-->	 
<?php 
     if (($action == 'edit') && ($order_exists == true)) {
        echo tep_draw_bs_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'oId=' . $HTTP_GET_VARS['oID'] . '&action=update_order', 'post', 'role=form" class="form-horizontal"'); 
		
		echo '<div class="btn-group">' . PHP_EOL;

	    echo tep_draw_bs_button(IMAGE_NEW_ORDER_EMAIL,         'send',       tep_href_link(FILENAME_ORDERS_EDIT,        'action=email&oID=' . $HTTP_GET_VARS['oID']), null, null, "btn-info") . PHP_EOL;
		
	    echo tep_draw_bs_button(IMAGE_ORDERS_INVOICE_PDF,     'th-list',     tep_href_link(FILENAME_PDF_INVOICE,        'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true), "btn-primary") . PHP_EOL;
		echo tep_draw_bs_button(IMAGE_ORDERS_PACKINGSLIP_PDF, 'inbox',       tep_href_link(FILENAME_PDF_PACKINGSLIP,    'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true), "btn-primary") . PHP_EOL;
		
	    echo tep_draw_bs_button(IMAGE_ORDERS_LABEL,           'tags',        tep_href_link(FILENAME_ORDERS_LABEL,       'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true), "btn-primary") . PHP_EOL;
		echo tep_draw_bs_button(IMAGE_GOOGLE_DIRECTIONS,      'map-marker',  tep_href_link(FILENAME_GOOGLE_MAP,         'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true), "btn-info") . PHP_EOL;
		
	    echo tep_draw_bs_button(IMAGE_ORDERS_INVOICE,         'th-list',     tep_href_link(FILENAME_ORDERS_INVOICE,     'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true), "btn-primary") . PHP_EOL;
		echo tep_draw_bs_button(IMAGE_ORDERS_PACKINGSLIP,     'inbox',       tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true), "btn-primary") . PHP_EOL;
		
		echo '</div>' . PHP_EOL;
		echo '<div class="clearfix"></div>' . PHP_EOL;		
		echo '<br />' . PHP_EOL;
	  
	    echo '<div class="row">' . PHP_EOL;
	    echo '   <div class="col-xs-12">' . PHP_EOL;	  
	    echo '' .  tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $_GET['oID'] . '&page='. $HTTP_GET_VARS['page'])). PHP_EOL;	  
	    echo '' .  tep_draw_bs_button(TEXT_ADD_NEW_PRODUCT, 'plus', tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID'] . '&step=1')) . PHP_EOL;
	    echo '' .  tep_draw_bs_button(IMAGE_UPDATE, "save", null) . '&nbsp;&nbsp;' . PHP_EOL;
	    echo '   </div>' . PHP_EOL; ;		
		echo '</div>' . PHP_EOL;		
		echo '<br />' . PHP_EOL;		
/*		
		// include the adress tabs 
		include( DIR_WS_MODULES . 'edit_order_adress_invoice_send_ship.php' )  ;
	    $contents_edit_orders_adresss .= '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">' . PHP_EOL;
	    $contents_edit_orders_adresss .= '  <div class="panel panel-primary">' . PHP_EOL;
	    $contents_edit_orders_adresss .= '    <div class="panel-heading" role="tab" id="headingOne">' . PHP_EOL;
	    $contents_edit_orders_adresss .= '      <h4 class="panel-title">' . PHP_EOL;
	    $contents_edit_orders_adresss .= '        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">' . PHP_EOL;
	    $contents_edit_orders_adresss .=              TEXT_COLLAPSE_ADRESS . PHP_EOL;
	    $contents_edit_orders_adresss .= '        </a>' . PHP_EOL;
	    $contents_edit_orders_adresss .= '      </h4>' . PHP_EOL;
	    $contents_edit_orders_adresss .= '    </div>' . PHP_EOL;
	    $contents_edit_orders_adresss .= '    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">' . PHP_EOL;
	    $contents_edit_orders_adresss .= '      <div class="panel-body">' . PHP_EOL;
	    $contents_edit_orders_adresss .=            $contents_edit_orders_edit     . PHP_EOL;
	    $contents_edit_orders_adresss .= '      </div>' . PHP_EOL;
	    $contents_edit_orders_adresss .= '    </div>' . PHP_EOL;
	    $contents_edit_orders_adresss .= '  </div>' . PHP_EOL;
	    $contents_edit_orders_adresss .= '</div>' . PHP_EOL;
		
		echo $contents_edit_orders_adresss ;	

		// include the order starus history 
        include( DIR_WS_MODULES . 'edit_order_edit_order_status_history.php' ) ;	
	    $contents_edit_orders_status_hist .= '<div class="panel-group" id="accordionTwo" role="tablist" aria-multiselectable="true">' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '  <div class="panel panel-primary">' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '    <div class="panel-heading" role="tab" id="headingTwo">' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '      <h4 class="panel-title">' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">' . PHP_EOL;
	    $contents_edit_orders_status_hist .=              TEXT_COLLAPSE_ORDER_STATUS . PHP_EOL;
	    $contents_edit_orders_status_hist .= '        </a>' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '      </h4>' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '    </div>' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '      <div class="panel-body">' . PHP_EOL;
	    $contents_edit_orders_status_hist .=            $contents_edit_orders_edit_order_status     . PHP_EOL;
	    $contents_edit_orders_status_hist .= '      </div>' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '    </div>' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '  </div>' . PHP_EOL;
	    $contents_edit_orders_status_hist .= '</div>' . PHP_EOL;
		
		echo $contents_edit_orders_status_hist ;			
//		echo '<div class="clearfix"></div>' . PHP_EOL;			
*/
		// include the products 
        include( DIR_WS_MODULES . 'edit_order_product_attributes.php' ) ;	
		
        include( DIR_WS_MODULES . 'edit_order_order_totals.php' ) ;		
		
		echo '<div class="clearfix"></div>' . PHP_EOL;		

	    $contents_edit_orders_buttons .= '    </form>' . PHP_EOL;	  // end tep_draw_bs_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_a
		
   	    echo $contents_edit_orders_buttons  ;  
     }  // if (($action == 'edit') && ($order_e
?>
</table> <!-- end first table -->

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>