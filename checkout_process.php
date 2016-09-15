<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping') || !tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
 }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

  $payment_modules->update_status();

  if ( ($payment_modules->selected_module != $payment) || ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();

// load the before_process function from the payment modules
  $payment_modules->before_process();

  $sql_data_array = array('customers_id' => $customer_id,
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          'customers_company' => $order->customer['company'],
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'], 
                          'customers_state' => $order->customer['state'], 
                          'customers_country' => $order->customer['country']['title'], 
                          'customers_telephone' => $order->customer['telephone'], 
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'], 
                          'delivery_name' => trim($order->delivery['firstname'] . ' ' . $order->delivery['lastname']),
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'], 
                          'delivery_suburb' => $order->delivery['suburb'], 
                          'delivery_city' => $order->delivery['city'], 
                          'delivery_postcode' => $order->delivery['postcode'], 
                          'delivery_state' => $order->delivery['state'], 
                          'delivery_country' => $order->delivery['country']['title'], 
                          'delivery_address_format_id' => $order->delivery['format_id'], 
                          'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'], 
                          'billing_company' => $order->billing['company'],
                          'billing_street_address' => $order->billing['street_address'], 
                          'billing_suburb' => $order->billing['suburb'], 
                          'billing_city' => $order->billing['city'], 
                          'billing_postcode' => $order->billing['postcode'], 
                          'billing_state' => $order->billing['state'], 
                          'billing_country' => $order->billing['country']['title'], 
                          'billing_address_format_id' => $order->billing['format_id'], 
                          'payment_method' => $order->info['payment_method'], 
						  'shipping_module' => $shipping['id'], // order editor 509
                          'cc_type' => $order->info['cc_type'], 
                          'cc_owner' => $order->info['cc_owner'], 
                          'cc_number' => $order->info['cc_number'], 
                          'cc_expires' => $order->info['cc_expires'], 
                          'date_purchased' => 'now()', 
                          'orders_status' => $order->info['order_status'], 
                          'currency' => $order->info['currency'], 
                          'currency_value' => $order->info['currency_value'],
						  'billing_stores_id' => SYS_STORES_ID ); // multi stores
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'], 
                            'class' => $order_totals[$i]['code'], 
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id, 
                          'orders_status_id' => $order->info['order_status'], 
                          'date_added' => 'now()', 
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

// initialized for the email confirmation
// bof html email order confimation
//  $products_ordered = '';
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
// eof html email order confimation

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
// Stock Update - Joao Correia
// bof qtpro 461
//    if (STOCK_LIMITED == 'true') {
//      if (DOWNLOAD_ENABLED == 'true') {
    $products_stock_attributes=null;
    if (STOCK_LIMITED == 'true') {
        //$products_attributes = $order->products[$i]['attributes'];
		$products_attributes = (isset($order->products[$i]['attributes'])) ? $order->products[$i]['attributes'] : ''; // 2.3.3
//      if (DOWNLOAD_ENABLED == 'true') {
// eof qtpro 461
        $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename 
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
// bof qtpro 461
//        $products_attributes = $order->products[$i]['attributes'];
// eof qtpro 461
        if (is_array($products_attributes)) {
// 2.3.3		
//          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
          $stock_query_raw .= " AND pa.options_id = '" . (int)$products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . (int)$products_attributes[0]['value_id'] . "'";
// eof 2.3.3
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      }
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
// bof qtpro 461
//        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
//          $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
//        } else {
//          $stock_left = $stock_values['products_quantity'];
//        }
//        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
//        if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
//          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        $actual_stock_bought = $order->products[$i]['qty'];
        $download_selected = false;
        if ((DOWNLOAD_ENABLED == 'true') && isset($stock_values['products_attributes_filename']) && tep_not_null($stock_values['products_attributes_filename'])) {
          $download_selected = true;
          $products_stock_attributes='$$DOWNLOAD$$';
        }
// If not downloadable and attributes present, adjust attribute stock
        if (!$download_selected && is_array($products_attributes)) {
          $all_nonstocked = true;
          $products_stock_attributes_array = array();
          foreach ($products_attributes as $attribute) {
            if ($attribute['track_stock'] == 1) {
              $products_stock_attributes_array[] = $attribute['option_id'] . "-" . $attribute['value_id'];
              $all_nonstocked = false;
            }
          } 
          if ($all_nonstocked) {
            $actual_stock_bought = $order->products[$i]['qty'];
          }  else {
            asort($products_stock_attributes_array, SORT_NUMERIC);
            $products_stock_attributes = implode(",", $products_stock_attributes_array);
            $attributes_stock_query = tep_db_query("select products_stock_quantity from " . TABLE_PRODUCTS_STOCK . " where products_stock_attributes = '$products_stock_attributes' AND products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            if (tep_db_num_rows($attributes_stock_query) > 0) {
              $attributes_stock_values = tep_db_fetch_array($attributes_stock_query);
              $attributes_stock_left = $attributes_stock_values['products_stock_quantity'] - $order->products[$i]['qty'];
              tep_db_query("update " . TABLE_PRODUCTS_STOCK . " set products_stock_quantity = '" . $attributes_stock_left . "' where products_stock_attributes = '$products_stock_attributes' AND products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
              $actual_stock_bought = ($attributes_stock_left < 1) ? $attributes_stock_values['products_stock_quantity'] : $order->products[$i]['qty'];
            } else {
              $attributes_stock_left = 0 - $order->products[$i]['qty'];
              tep_db_query("insert into " . TABLE_PRODUCTS_STOCK . " (products_id, products_stock_attributes, products_stock_quantity) values ('" . tep_get_prid($order->products[$i]['id']) . "', '" . $products_stock_attributes . "', '" . $attributes_stock_left . "')");
              $actual_stock_bought = 0;
            }
          }
        }
//        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
//      }
//      if (tep_db_num_rows($stock_query) > 0) {
//        $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
        if (!$download_selected) {
          $stock_left = $stock_values['products_quantity'] - $actual_stock_bought;
          tep_db_query("UPDATE " . TABLE_PRODUCTS . " 
                        SET products_quantity = products_quantity - '" . $actual_stock_bought . "' 
                        WHERE products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
//++++ QT Pro: End Changed Code
// eof qtpro 461
        }
      }
   // }

// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

// bof qtpro 461
//    $sql_data_array = array('orders_id' => $insert_id, 
//                            'products_id' => tep_get_prid($order->products[$i]['id']), 
//                            'products_model' => $order->products[$i]['model'], 
//                            'products_name' => $order->products[$i]['name'], 
//                            'products_price' => $order->products[$i]['price'],
// bof product cost price
//                            'products_cost' => $order->products[$i]['cost'],
// eof product cost price							
//                            'final_price' => $order->products[$i]['final_price'], 
//                            'products_tax' => $order->products[$i]['tax'], 
//                            'products_quantity' => $order->products[$i]['qty']);
//++++ QT Pro: Begin Changed code
    if (!isset($products_stock_attributes)) $products_stock_attributes=null;
    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => tep_get_prid($order->products[$i]['id']), 
                            'products_model' => $order->products[$i]['model'], 
                            'products_name' => $order->products[$i]['name'], 
                            'products_price' => $order->products[$i]['price'], 
// bof product cost price
                            'products_cost' => $order->products[$i]['cost'],
// eof product cost price														
                            'final_price' => $order->products[$i]['final_price'], 
                            'products_tax' => $order->products[$i]['tax'], 
                            'products_quantity' => $order->products[$i]['qty'],
                            'products_stock_attributes' => $products_stock_attributes);
//++++ QT Pro: End Changed Code
// eof qtpro 461
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();

//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
//        if (DOWNLOAD_ENABLED == 'true') {
//          $attributes_query = "select pad.products_attributes_id,popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
//                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
//                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
//                                on pa.products_attributes_id=pad.products_attributes_id
//                                  where pa.products_id = '" . (int)$order->products[$i]['id'] . "' 
//                                    and pa.options_id = '" . (int)$order->products[$i]['attributes'][$j]['option_id'] . "' 
//                                    and pa.options_id = popt.products_options_id 	
///                                    and pa.options_values_id = '" . (int)$order->products[$i]['attributes '][$j]['value_id'] . "' 
//                                    and pa.options_values_id = poval.products_options_values_id 									
//                                    and popt.language_id = '" . (int)$languages_id . "' 
//                                    and poval.language_id = '" . (int)$languages_id . "'";									
// 2.3.3								
//                               where pa.products_id = '" . $order->products[$i]['id'] . "' 
//                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
//                                and pa.options_id = popt.products_options_id 
//                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
//                                and pa.options_values_id = poval.products_options_values_id 
//                                and popt.language_id = '" . $languages_id . "' 
//                                and poval.language_id = '" . $languages_id . "'";
//          $attributes = tep_db_query($attributes_query);
//        } else {
// 2.3.3		
//          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" .      $order->products[$i]['id'] . "' and pa.options_id = '" .      $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" .      $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" .      $languages_id . "' and poval.language_id = '" . $languages_id . "'");
            $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_id from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . (int)$order->products[$i]['id'] . "' and pa.options_id = '" . (int)$order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$languages_id . "' and poval.language_id = '" . (int)$languages_id . "'");
// eof 2.3.3 	

         //}
//        }
        $attributes_values = tep_db_fetch_array($attributes);

        // bof option type 		
        $attr_name = $attributes_values['products_options_name'];

        if ($attributes_values['products_options_id'] == OPTIONS_VALUE_TEXT_ID) {
          $attr_name_sql_raw = 'SELECT po.products_options_name FROM ' .
            TABLE_PRODUCTS_OPTIONS . ' po, ' .
            TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
            ' pa.products_id="' . tep_get_prid($order->products[$i]['id']) . '" AND ' .
            ' pa.options_id="' . $order->products[$i]['attributes'][$j]['option_id'] . '" AND ' .
            ' pa.options_id=po.products_options_id AND ' .
            ' po.language_id="' . $languages_id . '" ';
          $attr_name_sql = tep_db_query($attr_name_sql_raw);
          if ($arr = tep_db_fetch_array($attr_name_sql)) {
            $attr_name  = $arr['products_options_name'];
          }
        }		
		// eof option type

        $sql_data_array = array('orders_id' => $insert_id, 
                                'orders_products_id' => $order_products_id, 
                                // OTF contrib begins
                                //'products_options' => $attributes_values['products_options_name'],
                                //'products_options_values' => $attributes_values['products_options_values_name'], 
                                'products_options' => $attr_name,
                                'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
                                // OTF contrib ends
                                'options_values_price' => $attributes_values['options_values_price'], 
                                'price_prefix' => $attributes_values['price_prefix']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
		
// BOF get attribute if download is possible
        if ((DOWNLOAD_ENABLED == 'true') ) {
            $attributes_download = tep_db_query("select products_attributes_maxdays, products_attributes_maxcount , products_attributes_filename 
			                from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where 
							  products_attributes_id = '" . $attributes_values['products_attributes_id'] . "'"); 
  		    $_download_attributes_values = tep_db_fetch_array($attributes_download);

            if ((DOWNLOAD_ENABLED == 'true') && isset($_download_attributes_values['products_attributes_filename']) && tep_not_null($_download_attributes_values['products_attributes_filename'])) {
//        if ((DOWNLOAD_ENABLED == 'true') ) {
               $sql_data_array = array('orders_id' => $insert_id, 
                                       'orders_products_id' => $order_products_id, 
                                       'orders_products_filename' => $_download_attributes_values['products_attributes_filename'], 
                                       'download_maxdays' => $_download_attributes_values['products_attributes_maxdays'], 
                                       'download_count' => $_download_attributes_values['products_attributes_maxcount']);
               tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
			}
        }
// EOF get attribute if download is possible		
        // OTF contrib begins
        //$products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . tep_decode_specialchars($order->products[$i]['attributes'][$j]['value']);
        // OTF contrib ends
      }
    }
//------insert customer choosen option eof ----
// bof order confirmation html email
//    $products_ordered .=                                  $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
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
// eof order confirmation html email
  }

  // bof order confirmation html email
  if (EMAIL_USE_HTML == 'true'){
    $products_ordered .= $order_totals_table_end;
  }
// eof order confirmation html email


  // Discount Code 2.3 - start
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') {
    if (!empty($discount)) {
      $discount_codes_query = tep_db_query("select discount_codes_id from " . TABLE_DISCOUNT_CODES . " where discount_codes = '" . tep_db_input($sess_discount_code) . "'");
      $discount_codes = tep_db_fetch_array($discount_codes_query);
      tep_db_perform(TABLE_CUSTOMERS_TO_DISCOUNT_CODES, array('customers_id' => $customer_id, 'discount_codes_id' => $discount_codes['discount_codes_id']));
      tep_db_query("update " . TABLE_DISCOUNT_CODES . " set number_of_orders = number_of_orders + 1 where discount_codes_id = '" . (int)$discount_codes['discount_codes_id'] . "'");

      tep_session_unregister('sess_discount_code');
    }
  }
  // Discount Code 2.3 - end
// lets start with the email confirmation
// bof order confirmation html email
  if (EMAIL_USE_HTML == 'true'){
     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWORDER . "' and language_id = '" . $languages_id . "' and stores_id='" . SYS_STORES_ID . "'");	
  } else{
     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWORDER . "' and language_id = '" . $languages_id . "' and stores_id='" . SYS_STORES_ID . "'");
  }
  $get_text = tep_db_fetch_array($text_query);
  $text    = $get_text["eorder_text_one"];
  $subject = $get_text['eorder_title'];
	
  $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME, $text);
  $text = preg_replace('/<-SYS_INVOICE_ID->/',             $insert_id, $text);
 
//  $text = preg_replace('/<-SYS_INVOICE_URL->/',            tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false), $text);
  $text = preg_replace('/<-SYS_INVOICE_URL->/',            '<a href="' .tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) .'" target="_blank">' .  TEXT_INVOICE_URL . '</a>&nbsp;&nbsp;', $text);
  $text = preg_replace('/<-SYS_INVOICE_DATE_ORDERED->/',   strftime(DATE_FORMAT_LONG), $text);
  if ($order->info['comments']) {
	$text = preg_replace('/<-SYS_CUSTOMER_COMMENTS->/', tep_db_output($order->info['comments']), $text);
  } else{
	$text = preg_replace('/<-SYS_CUSTOMER_COMMENTS->/', '', $text);
  }  
  $text = preg_replace('/<-SYS_PRODUCTS_LIST->/', $products_ordered, $text);
  if (EMAIL_USE_HTML == 'true'){	
	$list_total = $order_totals_table_beginn;
	for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
	  $list_total .= $order_totals_zelle_beginn . strip_tags($order_totals[$i]['title']) . $order_totals_zelle_mitte . strip_tags($order_totals[$i]['text']) . $order_totals_zelle_end;
	}
	$list_total .= $order_totals_table_end;
  } else{
	for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
	  $list_total .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
	}
  }	
  $text = preg_replace('/<-SYS_TOTAL_LIST->/',             $list_total, $text);
  
  if ($order->content_type != 'virtual') {
	$text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', tep_address_label($customer_id, $sendto, 0, '', "\n"), $text);
  } elseif($order->content_type == 'virtual') {	
	if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
	  $text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', EMAIL_TEXT_DOWNLOAD_SHIPPING . "\n" . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false), $text);
	} else{
	  $text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', '', $text);
	}	
  } else{
	  $text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', '', $text);
  }
  $text = preg_replace('/<-SYS_INVOICE_ADDRESS->/',         tep_address_label($customer_id, $billto, 0, '', "\n"), $text);  
  if (is_object($$payment)) {
	$payment_class = $$payment;
	$text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT->/',   $payment_class->title, $text);
	if ($payment_class->email_footer) { 
	  $text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT_FOOTER->/', $payment_class->email_footer, $text);
	} else {
      $text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT_FOOTER->/', '', $text); //rg add to email conf mod
	}		  
  }
	  	  
  $email_order = $text;
  
  // picture mode
  $email_order = tep_add_base_ref($email_order);  

  //tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  if ( EMAIL_PDF_CREATE_ORDER == 'true') {
        // All we do is set the order_id for pdfinvoice.php to pick up
        //$HTTP_GET_VARS['order_id'] = $insert_id;
        // set stream mode
        $stream = true;
        $oID= $_GET['oID'] ;
        $invoice_number = $insert_id ;
        $pdf_data = '' ;
        $pdf_data = include(FILENAME_CUSTOMER_PDF );    //tep_href_link( FILENAME_CUSTOMER_PDF,        'order_id=' . $HTTP_GET_VARS['order_id'] , 'SSL')   
        $file_name = $insert_id .'.pdf' ;
        // add text to email informing customer a pdf invoice copy has been attached:
        $email_order .= EMAIL_TEXT_PDF_ATTACHED ."\n\n";
        //$file_name = $_GET['oID'] .'.pdf' ;
        // send email with pdf invoice attached. Check to make sure pdfinvoice.php returns some data, else send standard email
        // note $order object reinstantiated by inclusion of pdfinvoice.php hence customer['name']
  }
  if (tep_not_null($pdf_data)) {
    tep_mail_string_attachment($order->customer['name'], $order->customer['email_address'], $subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $pdf_data, $file_name);
  } else {
    tep_mail($order->customer['name'], $order->customer['email_address'], $subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }
  
// send emails to admin
  if ( EMAIL_ADMIN_CREATE_ORDER != '') {
	  if (EMAIL_USE_HTML == 'true'){
		 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWORDER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id='" . SYS_STORES_ID . "'");	
	  } else{
		 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWORDER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id='" . SYS_STORES_ID . "'");
	  }
	  $get_text = tep_db_fetch_array($text_query);
	  $text    = $get_text["eorder_text_one"];
	  $subject = $get_text['eorder_title'];
		
	  $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME, $text);
	  $text = preg_replace('/<-SYS_INVOICE_ID->/',             $insert_id, $text);
	  $text = preg_replace('/<-SYS_INVOICE_URL->/',            tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false), $text);
	  $text = preg_replace('/<-SYS_INVOICE_DATE_ORDERED->/',   strftime(DATE_FORMAT_LONG), $text);
	  if ($order->info['comments']) {
		$text = preg_replace('/<-SYS_CUSTOMER_COMMENTS->/', tep_db_output($order->info['comments']), $text);
	  } else{
		$text = preg_replace('/<-SYS_CUSTOMER_COMMENTS->/', '', $text);
	  }  
	  $text = preg_replace('/<-SYS_PRODUCTS_LIST->/', $products_ordered, $text);
	  if (EMAIL_USE_HTML == 'true'){	
		$list_total = $order_totals_table_beginn;
		for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
		  $list_total .= $order_totals_zelle_beginn . strip_tags($order_totals[$i]['title']) . $order_totals_zelle_mitte . strip_tags($order_totals[$i]['text']) . $order_totals_zelle_end;
		}
		$list_total .= $order_totals_table_end;
	  } else{
		for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
		  $list_total .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
		}
	  }	
	  $text = preg_replace('/<-SYS_TOTAL_LIST->/',             $list_total, $text);
	  
	  if ($order->content_type != 'virtual') {
		$text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', tep_address_label($customer_id, $sendto, 0, '', "\n"), $text);
	  } elseif($order->content_type == 'virtual') {	
		if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
		  $text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', EMAIL_TEXT_DOWNLOAD_SHIPPING . "\n" . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false), $text);
		} else{
		  $text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', '', $text);
		}	
	  } else{
		  $text = preg_replace('/<-SYS_DELIVERY_ADDRESS->/', '', $text);
	  }
	  $text = preg_replace('/<-SYS_INVOICE_ADDRESS->/',         tep_address_label($customer_id, $billto, 0, '', "\n"), $text);  
	  if (is_object($$payment)) {
		$payment_class = $$payment;
		$text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT->/',   $payment_class->title, $text);
		if ($payment_class->email_footer) { 
		  $text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT_FOOTER->/', $payment_class->email_footer, $text);
		} else {
		  $text = preg_replace('/<-SYS_PAYMENT_MODUL_TEXT_FOOTER->/', '', $text); //rg add to email conf mod
		}		  
	  }
			  
	  $email_order = $text;
	  
	  // picture mode
	  $email_order = tep_add_base_ref($email_order);   
      tep_mail('', EMAIL_ADMIN_CREATE_ORDER, $subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }
// eof order confirmation html email
// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

// load the after_process function from the payment modules
  $payment_modules->after_process();
// remove items from wishlist if customer purchased them
  $wishList->clear();
  
  $cart->reset(true);

// unregister session variables used during checkout
  tep_session_unregister('sendto');
  tep_session_unregister('billto');
  tep_session_unregister('shipping');
  tep_session_unregister('payment');
  tep_session_unregister('comments');
// CHECKOUT START
if ($guestCustomer == 'true')
	{
	tep_session_unregister('customer_id');
	tep_session_unregister('guestCustomer');
	$messageStack->add_session('header', TEXT_SUCCESS, 'success');
	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
	}
// CHECKOUT END
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>