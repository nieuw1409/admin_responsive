<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');  

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_sort_order");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  
// bof multi stores
  $stores_orders_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
  while ($stores_order =  tep_db_fetch_array($stores_orders_query)) {
    $stores_orders_array[] = array("id" => $stores_order['stores_id'], "text" => "&#160;".$stores_order['stores_name']."&#160;");
  }
// eof multi stores  

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
//        $status_test = tep_db_prepare_input($HTTP_POST_VARS['status_history']);
  if (tep_not_null($action)) {
    switch ($action) {
		
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
 		
		$standaard_email = 'false' ; // only email via email text 
 
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
	    
	  	    $email_order = $text ;	
 
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
 

   // send emails to other people as necessary
       if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
          tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
       }
  
         //do the dirty
 		
		$messageStack->add_session(SUCCESS_EMAIL_SENT, 'success');
		
        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action','languages'))));
		  
	  break;		
		
      case 'update_address_order':
        $oID = $HTTP_GET_VARS['oID'] ;	  
        // Set this Session's variables
        if (isset($HTTP_POST_VARS['billing_same_as_customer'])) $_SESSION['billing_same_as_customer'] = $HTTP_POST_VARS['billing_same_as_customer'];
        if (isset($HTTP_POST_VARS['shipping_same_as_billing'])) $_SESSION['shipping_same_as_billing'] = $HTTP_POST_VARS['shipping_same_as_billing'];
		
        // Update Order Info  
		//figure out the new currency value
		$currency_value_query = tep_db_query("SELECT value 
		                                      FROM " . TABLE_CURRENCIES . " 
											  WHERE code = '" . $HTTP_POST_VARS['update_info_payment_currency'] . "'");
		$currency_value = tep_db_fetch_array($currency_value_query);

		//figure out the country, state
		$update_customer_state = tep_get_zone_name($HTTP_POST_VARS['update_customer_country_id'], $HTTP_POST_VARS['update_customer_zone_id'], $HTTP_POST_VARS['update_customer_state']);
        $update_customer_country = tep_get_country_name($HTTP_POST_VARS['update_customer_country_id']);
        $update_billing_state = tep_get_zone_name($HTTP_POST_VARS['update_billing_country_id'], $HTTP_POST_VARS['update_billing_zone_id'], $HTTP_POST_VARS['update_billing_state']);
        $update_billing_country = tep_get_country_name($HTTP_POST_VARS['update_billing_country_id']);
        $update_delivery_state = tep_get_zone_name($HTTP_POST_VARS['update_delivery_country_id'], $HTTP_POST_VARS['update_delivery_zone_id'], $HTTP_POST_VARS['update_delivery_state']);
        $update_delivery_country = tep_get_country_name($HTTP_POST_VARS['update_delivery_country_id']);
		
        $sql_data_array = array(
		'customers_name' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_name'])),
        'customers_company' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_company'])),
        'customers_street_address' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_street_address'])),
        'customers_suburb' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_suburb'])),
        'customers_city' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_city'])),
        'customers_state' => tep_db_input(tep_db_prepare_input($update_customer_state)),
        'customers_postcode' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_postcode'])),
        'customers_country' => tep_db_input(tep_db_prepare_input($update_customer_country)),
        'customers_telephone' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_telephone'])),
        'customers_email_address' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_email_address'])),
                                
		'billing_name' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_name'] : $HTTP_POST_VARS['update_billing_name']))),
        'billing_company' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_company'] : $HTTP_POST_VARS['update_billing_company']))),
        'billing_street_address' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_street_address'] : $HTTP_POST_VARS['update_billing_street_address']))),
        'billing_suburb' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_suburb'] : $HTTP_POST_VARS['update_billing_suburb']))),
        'billing_city' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_city'] : $HTTP_POST_VARS['update_billing_city']))),
        'billing_state' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $update_customer_state : $update_billing_state))),
        'billing_postcode' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_postcode'] : $HTTP_POST_VARS['update_billing_postcode']))),
        'billing_country' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['billing_same_as_customer']) && $HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $update_customer_country : $update_billing_country))),
        'billing_stores_id' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_customer_stores_id'])),								
								
	    'delivery_name' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_name'] : $HTTP_POST_VARS['update_billing_name']) : $HTTP_POST_VARS['update_delivery_name']))),
        'delivery_company' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_company'] : $HTTP_POST_VARS['update_billing_company']) : $HTTP_POST_VARS['update_delivery_company']))),
        'delivery_street_address' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_street_address'] : $HTTP_POST_VARS['update_billing_street_address']) : $HTTP_POST_VARS['update_delivery_street_address']))),
        'delivery_suburb' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_suburb'] : $HTTP_POST_VARS['update_billing_suburb']) : $HTTP_POST_VARS['update_delivery_suburb']))),
        'delivery_city' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_city'] : $HTTP_POST_VARS['update_billing_city']) : $HTTP_POST_VARS['update_delivery_city']))),
        'delivery_state' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $update_customer_state : $update_billing_state) : $update_delivery_state))),
        'delivery_postcode' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $HTTP_POST_VARS['update_customer_postcode'] : $HTTP_POST_VARS['update_billing_postcode']) : $HTTP_POST_VARS['update_delivery_postcode']))),
        'delivery_country' => tep_db_input(tep_db_prepare_input(((isset($HTTP_POST_VARS['shipping_same_as_billing']) && $HTTP_POST_VARS['shipping_same_as_billing'] == 'on') ? (($HTTP_POST_VARS['billing_same_as_customer'] == 'on') ? $update_customer_country : $update_billing_country) : $update_delivery_country))),
                                
	    'payment_method' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_info_payment_method'])),
        'currency' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_info_payment_currency'])),
        'currency_value' => tep_db_input(tep_db_prepare_input($currency_value['value'])),
        'cc_type' => tep_db_prepare_input($HTTP_POST_VARS['update_info_cc_type']),
        'cc_owner' => tep_db_prepare_input($HTTP_POST_VARS['update_info_cc_owner']),
	    'cc_number' => tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_info_cc_number'])),
        'cc_expires' => tep_db_prepare_input($HTTP_POST_VARS['update_info_cc_expires']),
        'last_modified' => 'now()',
		'billing_stores_id' => tep_db_prepare_input($HTTP_POST_VARS['update_customer_stores_id'] ) );

        tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = \'' . tep_db_input($oID) . '\'');
        $order_updated = true;
	  
        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action', 'languages'))));	  
	   break;
		
      case 'update_status':
	   // UPDATE STATUS HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####
       $oID = $HTTP_GET_VARS['oID'] ;
       $status_history = tep_db_prepare_input($HTTP_POST_VARS['status_history']);
        $comments = tep_db_prepare_input($HTTP_POST_VARS['comments_history']);
// SADESA ORDER TRACKING
       $track_num    = tep_db_prepare_input($HTTP_POST_VARS['track_num_history']);
       $track_pcode  = tep_db_prepare_input($HTTP_POST_VARS['track_pcode_history']);

// added paulm trim spaces 2004/06/03
       $track_num   = str_replace(' ','',$track_num);
       $track_pcode = str_replace(' ','',$track_pcode);
// end added paulm trim spaces 2004/06/03    	   
       $check_status_query = tep_db_query("
	                      SELECT customers_name, customers_email_address, orders_status, date_purchased, billing_stores_id,
						         customers_street_address, customers_postcode, customers_city, customers_country
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
       $check_status = tep_db_fetch_array($check_status_query); 
	
       if ($check_status['orders_status'] != $status_history) {

            tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = '" . tep_db_input($status_history) . "', 
                      last_modified = now() 
					  
                      WHERE orders_id = '" . (int)$oID . "'");
	
		    // Notify Customer ?
            $customer_notified = '0';
			if (isset($HTTP_POST_VARS['notify_history']) && ($HTTP_POST_VARS['notify_history'] == 'on')) {
			  $notify_comments = '';
			  if (isset($HTTP_POST_VARS['notify_comments_history']) && ($HTTP_POST_VARS['notify_comments_history'] == 'on')) {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $HTTP_POST_VARS['comments_history']) . "\n\n";
			  }
			  if (EMAIL_USE_HTML == 'true'){
                $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_ORDER_UPDATE . "' and language_id = '" . (int)$languages_id  . "' and stores_id='" . $check_status['billing_stores_id'] . "'");	
              } else{
                $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWORDER . "' and language_id = '" . (int)$languages_id  . "' and stores_id='" . $check_status['billing_stores_id'] . "'");
              }
              $get_text = tep_db_fetch_array($text_query);
              $text = $get_text["eorder_text_one"];
			  $subject = $get_text['eorder_title'];
				
              $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME,                                                    $text);
              $text = preg_replace('/<-SYS_UPDATE_STATUS->/',          tep_get_orders_status_name( $HTTP_POST_VARS['status_history'], $languages_id ),  $text);
              $text = preg_replace('/<-SYS_UPDATE_COMMENTS->/',        $notify_comments,                                              $text);			
              $text = preg_replace('/<-SYS_UPDATE_INVOICE_ID->/',      $oID,                                                          $text);			
              $text = preg_replace('/<-SYS_UPDATE_CUSTOMER_NAME->/',   $check_status['customers_name'],                               $text);
              $text = preg_replace('/<-SYS_UPDATE_CUSTOMER_EMAIL->/',  $check_status['customers_email_address'],                      $text);			
			  $text = preg_replace('/<-SYS_UPDATE_ORDER_PURCHASED->/', $check_status['date_purchased'],                               $text);
              //SADESA ORDER TRACKING			  
			  $text = preg_replace('/<-SYS_UPDATE_TRATRA_URL->/',      '<a href="' . SYS_TRACK_TRACE_URLTONR . nl2br(tep_output_string_protected(nl2br(tep_db_output($track_num)))) . SYS_TRACK_TRACE_URLTOPC . nl2br(tep_db_output($track_pcode)) .'" target="_blank">' . 
			                                                               nl2br(tep_output_string_protected(nl2br(tep_db_output($track_num)))) . '</a>&nbsp;&nbsp;'
		                                                                       ,                                                      $text);	
              $text = preg_replace('/<-SYS_UPDATE_TRATRA_PCODE->/',    $track_pcode,                                                  $text); 			  
              $text = preg_replace('/<-SYS_UPDATE_TRATRA_NUMBER->/',   $track_num,                                                    $text); 				  
               // //SADESA ORDER TRACKING
              // picture mode
              $email_text = tep_add_base_ref($text); 

              tep_mail($check_status['customers_name'], $check_status['customers_email_address'], $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 
			  
			  $customer_notified = '1';
			}			  

            tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, track_num, track_pcode) values ('" . (int)$oID . "', '" . tep_db_input($status_history) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "', '" . tep_db_input($track_num)  . "', '" . tep_db_input($track_pcode) . "')");


			//SADESA ORDER TRACKING// bof 5_0_8 Google Maps

//			if (FILENAME_GOOGLE_MAP     !== 'FILENAME_GOOGLE_MAP'     ) {

			  if ($status_history == GOOGLE_MAP_ORDER_STATUS ) {    // wenn "Versendet"
            
			     //require(DIR_WS_LANGUAGES . $language . '/report_googlemap.php');

//				 $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
          
		         $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
                 $order_exists = true;
				 if (!tep_db_num_rows($orders_query)) {
						$order_exists = false;
						$messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
				 }

				 include(DIR_WS_CLASSES . 'order.php');
				 $order = new order($oID);
				 
				 $address = stripslashes($check_status['customers_street_address']) . ',' . 
				 		    stripslashes($check_status['customers_postcode'])       . ',' . 
						    stripslashes($check_status['customers_city'])           ;
						   
				 $address = str_replace(" ", "+", $address);
				 $region =  stripslashes(tep_get_country_name( $check_status['customers_country'] ) ) ;

				 $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . $address . "&sensor=false&region=" . $region );
				 $response = json_decode($json);

				//$lat = $response->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
				//$long = $response->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

				$lat = $response->results[0]->geometry->location->lat;
				$long = $response->results[0]->geometry->location->lng;
				 
			    $latlng_query_raw = "insert into orders_to_latlng (orders_id, lat, lng) values ('$oID','$lat','$long')";
				$latlng_query = tep_db_query($latlng_query_raw);
				 
		      } // endif versendet
//		    } // endif check voor contribution google maps
		// eof 5_0_8 Google Maps 				
		} // end if status = stauts
		
		if (isset($HTTP_POST_VARS['update_comments'])) {
			
		          $_update_comments = $HTTP_POST_VARS['update_comments'] ;  				  
	              foreach($_update_comments as $orders_status_history_id => $comments_details) {
	  
	                  if (isset($comments_details['delete'])){
		
			             $Query = "DELETE FROM " . TABLE_ORDERS_STATUS_HISTORY . " 
			                              WHERE orders_id = '" . (int)$oID . "' 
			                              AND orders_status_history_id = '$orders_status_history_id';";
				         tep_db_query($Query);
				
				        } else {

		                 $Query = "UPDATE " . TABLE_ORDERS_STATUS_HISTORY . " SET
					               comments = '" . $comments_details["comments"] . "'
					               WHERE orders_id = '" . (int)$oID . "'
					               AND orders_status_history_id = '$orders_status_history_id';";
					     tep_db_query($Query);
				        }
				    }	
		}//end comments update section		  
		
        tep_redirect(tep_href_link(FILENAME_ORDERS, 'oID=' . $HTTP_GET_VARS[ 'oID'] . '&page=' . $HTTP_GET_VARS['page'] ) );	   // . '&action=status_history'
	    break;

      case 'deleteconfirm':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        tep_remove_order($oID, $HTTP_POST_VARS['restock']);

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action', 'languages'))));
        break;
    }
  }

  if (($action == 'edit') && isset($HTTP_GET_VARS['oID'])) {
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
  
  
  /*** BOF: Additional Orders Info ***/
  // Look up things in orders
  if (! tep_not_null($oID) && isset($_GET['oID'])) {
      $oID = tep_db_prepare_input($_GET['oID']); 
  }
  
  if (tep_not_null($oID)) {  
     $the_extra_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
     $the_extra= tep_db_fetch_array($the_extra_query);
     $the_customers_id = $the_extra['customers_id'];
  } elseif (isset($_GET['cID'])) {  
     $orders_query_raw = tep_db_query("select o.customers_id, o.orders_id from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_STATUS . " s on ( o.orders_status = s.orders_status_id ) where s.language_id = '" . (int)$languages_id . "' and o.customers_id = '" . (int)$_GET['cID'] . "' order by o.orders_id DESC");
     $the_extra = tep_db_fetch_array($orders_query_raw);
     $the_customers_id = $the_extra['customers_id'];
     $oID = $the_extra['orders_id'];  
  } else { 
     $orders_query_raw = tep_db_query("select o.customers_id, o.orders_id from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by o.orders_id DESC");
     $the_extra = tep_db_fetch_array($orders_query_raw);
     $the_customers_id = $the_extra['customers_id'];
     $oID = $the_extra['orders_id'];
  } 
  
  // Check for other orders
  $numOtherCompletedOrders = 0;
  $numOtherOrders = 0;
  $ttlOtherOrders = 0; 

  $parts = explode(",", SHOW_ORDERS_STATUS_CHECK);
  $where = " where ( ";
  for ($i = 0; $i < count($parts); ++$i) {
    if ($where !== " where ( ") {
      $where .= " or "; 
    } 
    $where .= " orders_status_name LIKE '" . strtolower(trim($parts[$i])) . "'"; 
  }
  $where .= " ) ";
      
  $the_extra_query= tep_db_query("select o.orders_id, o.orders_status, ot.text  from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where o.orders_id != '" . tep_db_input($oID) . "' and o.customers_id = '" . $the_customers_id . "' and ot.class = 'ot_total'");
  while ($the_extra= tep_db_fetch_array($the_extra_query)) {
    $order_status_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS .  $where . " and language_id = '" . (int)$languages_id . "'");
    while ($orders_status = tep_db_fetch_array($order_status_query)) {
      if ($the_extra['orders_status'] == $orders_status['orders_status_id']) {
        $numOtherCompletedOrders++;
      }  
    }
      
    $numOtherOrders++;
    $ttlOtherOrders += GetNumber($the_extra['text']);
  }
  // Look up things in customers
  $the_extra_query= tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $the_customers_id . "'");
  $the_extra= tep_db_fetch_array($the_extra_query);
  $the_customers_fax= $the_extra['customers_fax'];
  $aiPartsPayment = explode(",", SHOW_ORDERS_HIGHLIGHT_PAYMENT);
  /*** EOF: Additional Orders Info ***/

  include(DIR_WS_CLASSES . 'order.php');

  require(DIR_WS_INCLUDES . 'template_top.php');
?>		
  
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <div class="page-header">
            <h1 class="col-xs-12 col-md-2"><?php echo HEADING_TITLE; ?></h1>
            <div class="col-md-10 col-xs-12">
			  <div class="row">
             
			    <div>
<?php
                  echo '' . tep_draw_bs_form('orders', FILENAME_ORDERS, '', 'get', 'role="form"'  ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;				  
                  echo          tep_draw_bs_input_field('oID', '',  HEADING_SEARCH_ORDER, 'order_select_order_numb' , 'control-label col-xs-3', 'col-xs-9', 'left', TEXT_SEARCH_ORDER 	).  PHP_EOL; 
 				  echo '    </div>' . PHP_EOL;					  
                  echo '    <div class="form-group">' . PHP_EOL;				  
				  echo          tep_draw_hidden_field('action', 'edit'). PHP_EOL;
				  echo '    </div>' . PHP_EOL;				  
				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 
?>
                </div> 
                <br />	
                <br />				
			    <div>
<?php
                  if (isset($HTTP_GET_VARS['custname']) && tep_not_null($HTTP_GET_VARS['custname'])) {
					  $col_input = ' col-xs-5 ' ;
				  } else {
					  $col_input = ' col-xs-9 ' ;					  
				  }
                  echo '' . tep_draw_bs_form('customer_search', FILENAME_ORDERS, '', 'get', 'role="form"' ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;				  
                  echo          tep_draw_bs_input_field('custname', '',  HEADING_SEARCH_CUSTOMER, 'order_select_cust_name' , 'control-label col-xs-3', $col_input, 'left', TEXT_SEARCH_NAME	).  PHP_EOL; 
 				  echo '    </div>' . PHP_EOL;		

                  if (isset($HTTP_GET_VARS['custname']) && tep_not_null($HTTP_GET_VARS['custname'])) {
			         echo '  <div class="col-xs-4">'. PHP_EOL ; 					  
		             echo       tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_ORDERS)); 
                     echo '    </div>' . PHP_EOL ;					 
                  }		   				   

                  echo '    <div class="form-group">' . PHP_EOL;				  
//				  echo          tep_draw_hidden_field('action', 'cust_search') . PHP_EOL;
				  echo '    </div>' . PHP_EOL;					  
				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 
?>
                </div>	
                <br />	
                <br />					
			    <div>
<?php
                  echo '' . tep_draw_bs_form('status', FILENAME_ORDERS, '', 'get', 'role="form"' ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;
			      echo          tep_draw_bs_pull_down_menu( 'status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', HEADING_TITLE_STATUS, 'order_select_status', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
 				  echo '    </div>' . PHP_EOL;				  
                  //echo          tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onchange="this.form.submit();"'). PHP_EOL; 
				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 
?>
                </div>
                <br />	
                <br />					
			    <div>
<?php
                if ( sizeof( $stores_orders_array ) > 1 ) {
                  echo '' . tep_draw_bs_form('stores', FILENAME_ORDERS, '', 'get', 'role="form"' ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;
			      echo          tep_draw_bs_pull_down_menu( 'stores', array_merge(array(array('id' => '', 'text' => TEXT_ALL_STORES)), $stores_orders_array), '', HEADING_TITLE_STORES, 'order_select_status', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
 				  echo '    </div>' . PHP_EOL;				  
				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 
				}
?>
                </div>					

              </div>
			  
            </div>
            <div class="clearfix"></div>
        </div><!-- end page-header-->
		  
	</table> <!-- end table --> 
  
   <div class="table-responsive">
    <table class="table table-condensed table-striped table-responsive">
        <thead>
           <tr class="heading-row">
             <th>                    <?php echo 'ID #'; ?></th>
             <th>                    <?php echo TABLE_HEADING_CUSTOMERS; ?></th>
             <th class="hidden-xs hidden-sm"><?php echo TABLE_HEADING_CUSTOMERS_GROUPS; ?></th>
             <th class="text-right"> <?php echo TABLE_HEADING_ORDER_TOTAL; ?></th>
             <th class="text-center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></th>			 
             <th class="text-right"> <?php echo TABLE_HEADING_STATUS; ?></th>				 
             <th class="hidden-xs hidden-sm"><?php echo TABLE_HEADING_ACTION; ?></th>
           </tr>
        </thead>
        <tbody>
<?php

$custname = '' ; 
if (isset($HTTP_GET_VARS['custname']) && tep_not_null($HTTP_GET_VARS['custname'])) {
//if(isset($HTTP_GET_VARS['custname'])){
//	$custname = $HTTP_GET_VARS['custname'];
    $custname = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['custname']));
//	$orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.billing_stores_id, s.orders_status_name, o.orders_status, ot.text as order_total, cg.customers_group_name, cg.customers_group_id from " 
//	                         . TABLE_ORDERS . " o, " 
//							 . TABLE_ORDERS_TOTAL . " ot, " 
//							 . TABLE_ORDERS_STATUS . " s, " 
//							 . TABLE_CUSTOMERS_GROUPS . " cg where o.customers_id = cg.customers_group_id and o.customers_name LIKE '%".$custname."%' and ot.orders_id = o.orders_id and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
    if (isset($HTTP_GET_VARS['custname']) && tep_not_null($HTTP_GET_VARS['custname'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['custname']));
      $search = "where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' and o.customers_name like '%" . $keywords . "%' ";
    } else {
	  $search = "where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' ";  
    }							 
    $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total, o.customers_email_address from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s 
            ". $search ."  order by orders_id DESC" ;
							 
}else{
	
	if (isset($HTTP_GET_VARS['cID']) && tep_not_null($HTTP_GET_VARS['cID']) ) {
	      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);
          $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.billing_stores_id, o.orders_status, s.orders_status_name, ot.text as order_total, cg.customers_group_name from " . 
		                                   TABLE_ORDERS . " o left join " . 
										   TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) left join " . 
										   TABLE_CUSTOMERS . " c on c.customers_id = o.customers_id left join " . 
										   TABLE_CUSTOMERS_GROUPS . " cg using(customers_group_id), " . 
										   TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
		  
	    } elseif (isset($HTTP_GET_VARS['status']) && is_numeric($HTTP_GET_VARS['status']) && ($HTTP_GET_VARS['status'] > 0)) {
	      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
          $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.billing_stores_id, o.orders_status, s.orders_status_name, ot.text as order_total, cg.customers_group_name from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) left join " . TABLE_CUSTOMERS . " c on c.customers_id = o.customers_id left join " . TABLE_CUSTOMERS_GROUPS . " cg using(customers_group_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by o.orders_id DESC"; 
// bof multi stores		  
	    } elseif (isset($HTTP_GET_VARS['stores']) && is_numeric($HTTP_GET_VARS['stores']) && ($HTTP_GET_VARS['stores'] > 0)) {
	      $stores = tep_db_prepare_input($HTTP_GET_VARS['stores']);
          $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.billing_stores_id, o.orders_status, s.orders_status_name, ot.text as order_total, cg.customers_group_name from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) left join " . TABLE_CUSTOMERS . " c on c.customers_id = o.customers_id left join " . TABLE_CUSTOMERS_GROUPS . " cg using(customers_group_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and o.billing_stores_id = '" . (int)$stores . "' and ot.class = 'ot_total' order by o.orders_id DESC"; 
// eof multi stores		  
	    } else {
          $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.billing_stores_id, o.orders_status, s.orders_status_name, ot.text as order_total, cg.customers_group_name from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) left join " . TABLE_CUSTOMERS . " c on c.customers_id = o.customers_id left join " . TABLE_CUSTOMERS_GROUPS . " cg using(customers_group_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by o.orders_id DESC";
	    }
}
// #### HURL CUSTOMER ORDER SEARCH ####

    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
    if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }

      /*** BOF: Additional Orders Info ***/
      $rowcolor = '';
      $watchedPayment = GetAdditionalOrderInfo($aiPartsPayment, $orders['payment_method']);
      $addrMismatch = BillingShippingDontMatch($orders['orders_id']);

      if ($addrMismatch && tep_not_null($watchedPayment))
         $rowcolor = SHOW_ORDERS_COLOR_BOTH;
      else if ($addrMismatch)
         $rowcolor = SHOW_ORDERS_COLOR_ADDRESS;
      else if (tep_not_null($watchedPayment))
         $rowcolor = SHOW_ORDERS_COLOR_PAYMENT;

      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
       echo '              <tr class="active"  onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr                onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";
      }
?>

                <td>                            <?php echo '#' . $orders['orders_id']; ?></td>
                <td>                            <?php echo $orders['customers_name']; ?></td><!-- next td added for SPPC -->
		        <td class="hidden-xs hidden-sm"><?php echo $orders['customers_group_name']; ?></td>
                <td class="text-right">         <?php echo strip_tags(tep_decode_specialchars($orders['order_total'])); ?></td>
                <td class="text-center">        <?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                <td class="text-right">         <?php echo $orders['orders_status_name']; ?></td>
                <td  class="hidden-xs hidden-sm text-left">
                   <div class="btn-toolbar" role="toolbar">                  
<?php
                       echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,             'info-sign',   tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']. '&action=info'),              null, 'info')    . '</div>' . PHP_EOL .
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_PRODUCTS,       'pencil',      tep_href_link(FILENAME_ORDERS_EDIT,         tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']),                                                                               null, 'warning') . '</div>' . PHP_EOL .
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_STATUS,         'list-alt',    tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=status_history'),   null, 'warning')    . '</div>' . PHP_EOL .                                      						
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_ADDRESS,        'book',        tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']. '&action=edit_address_order'), null, 'warning')    . '</div>' . PHP_EOL .                                      
							
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,                'remove',      tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=confirm'),          null, 'danger')  . '</div>' . PHP_EOL . 
											
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_INVOICE_PDF,    'th-list',     tep_href_link(FILENAME_PDF_INVOICE,         'oID=' . $orders['orders_id']. '&action=nothing'),                                                            null, 'info', null, 'target="_blank"') . '</div>' . PHP_EOL .				
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_PACKINGSLIP_PDF,'inbox',       tep_href_link(FILENAME_PDF_PACKINGSLIP,     'oID=' . $orders['orders_id']. '&action=nothing'),                                                            null, 'info', null, 'target="_blank"' ). '</div>' . PHP_EOL .				
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_NEW_ORDER_EMAIL,       'send',        tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']. '&action=email'),              null, 'success')    . '</div>' . PHP_EOL .                                      						
							
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_LABEL,          'tags',        tep_href_link(FILENAME_ORDERS_LABEL,        tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']),                               null, 'info', null, 'target="_blank"' )   . '</div>' . PHP_EOL .
                            '' //                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_GOOGLE_DIRECTIONS,     'map-marker',  tep_href_link(FILENAME_GOOGLE_MAP,          tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']),                               null, 'info' )   . '</div>' . PHP_EOL 							
							; 
?>

                   </div> 
				</td>					
              </tr>
              <tr class="hidden-lg hidden-md">
               <td    colspan="5" class="text-left">
                   <div class="btn-toolbar" role="toolbar">                  
<?php
                       echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,             'info-sign',   tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']. '&action=info'),              null, 'info')    . '</div>' . PHP_EOL .
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_PRODUCTS,       'pencil',      tep_href_link(FILENAME_ORDERS_EDIT,         tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']),                                                                               null, 'warning') . '</div>' . PHP_EOL .
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_STATUS,         'list-alt',    tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=status_history'),   null, 'warning')    . '</div>' . PHP_EOL .                                      						
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_ADDRESS,        'book',        tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']. '&action=edit_address_order'), null, 'warning')    . '</div>' . PHP_EOL .                                      
							
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,                'remove',      tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=confirm'),          null, 'danger')  . '</div>' . PHP_EOL . 
											
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_INVOICE_PDF,    'th-list',     tep_href_link(FILENAME_PDF_INVOICE,         'oID=' . $orders['orders_id']. '&action=nothing'),                                                            null, 'info', null, 'target="_blank"') . '</div>' . PHP_EOL .				
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_PACKINGSLIP_PDF,'inbox',       tep_href_link(FILENAME_PDF_PACKINGSLIP,     'oID=' . $orders['orders_id']. '&action=nothing'),                                                            null, 'info', null, 'target="_blank"' ). '</div>' . PHP_EOL .				
		                    '<div class="btn-group">' . tep_glyphicon_button(IMAGE_NEW_ORDER_EMAIL,       'send',        tep_href_link(FILENAME_ORDERS,              tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']. '&action=email'),              null, 'success')    . '</div>' . PHP_EOL .                                      						
							
                            '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS_LABEL,          'tags',        tep_href_link(FILENAME_ORDERS_LABEL,        tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']),                               null, 'info', null, 'target="_blank"' )   . '</div>' . PHP_EOL .
                            '' //                            '<div class="btn-group btn-group-xs">' . tep_glyphicon_button(IMAGE_GOOGLE_DIRECTIONS,     'map-marker',  tep_href_link(FILENAME_GOOGLE_MAP,          tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']),                               null, 'info' )   . '</div>' . PHP_EOL 							
							; 
?>

                   </div> 
				</td>
              </tr>				
			  
<?php
              if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id) && isset($HTTP_GET_VARS['action'])) { 
 	                             $alertClass = '';
                                 switch ($action) {
									 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_DELETE_INTRO . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;

                                       $contents .= '                          <p>' . TEXT_ORDER_CODE . ' ' . $orders['orders_id']  .  '<br />' . ENTRY_CUSTOMER . ' ' . $orders['customers_name'] . '</p>' . PHP_EOL;
 
  								       $contents .= '                          <br />' . PHP_EOL ;
									   $contents .= '                                <div class="form-group">' . 
									                                                      tep_bs_checkbox_field('restock', 'on', TEXT_INFO_RESTOCK_PRODUCT_QUANTITY, 'input_delete_stock', true, 'checkbox checkbox-success') . 
																					'</div>'  . PHP_EOL ;
									                   //tep_draw_checkbox_field('restock', 'on', true) . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY ; //tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY
 											
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
                                      break;

		                            case 'status_history':
										  $contents_edit_orders_change_order_status    = '' ;
										  include( DIR_WS_MODULES . 'edit_order_edit_order_status.php' ) ;	
									 
										  $contents_edit_orders_history_order_status    = '' ;
										  include( DIR_WS_MODULES . 'edit_order_history_order_status.php' ) ;	  					  
 
                                          $contents .= '                   <div class="panel panel-primary">' . PHP_EOL; // begin panel
	                                      $contents .= '                      <div class="panel-heading">' . TEXT_COLLAPSE_ORDER_STATUS . '</div>' . PHP_EOL; // panel heading
	                                      $contents .= '                      <div class="panel-body">' . PHP_EOL; // panel body
										  $contents .= '<!-- begin order status history -->' . PHP_EOL;	  
	                                      $contents .=                             tep_draw_bs_form('edit_order_status', FILENAME_ORDERS, 'oID=' . $HTTP_GET_VARS[ 'oID'] . '&page=' . $HTTP_GET_VARS['page'] . '&action=update_status', 'post', 'role="form" class="form-horizontal"'); 	  

										  $contents .= $contents_edit_orders_change_order_status  ;	

										  $contents .= $contents_edit_orders_history_order_status ;
	  
										  $contents .= '                              <br /><br />' . PHP_EOL;  
										
	                                      $contents .= '                              <div class="col-xs-12">' . PHP_EOL;									  
	                                      $contents .= '                           ' .  tep_draw_bs_button(IMAGE_SAVE, "ok", null) . '&nbsp;&nbsp;' . PHP_EOL;
	                                      $contents .= '                           ' .  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) ) ) . PHP_EOL;
	                                      $contents .= '                              </div>' . PHP_EOL;										  
										  $contents .= '                           </form>' . PHP_EOL;	
	                                      $contents .= '                      </div>' . PHP_EOL; // endpanel body
                                          $contents .= '                   </div>' . PHP_EOL;	  	  // end panel										  
										  $contents .= '<!-- end order status history -->' . PHP_EOL;										
                                      break ;									  
									  
		                            case 'edit_address_order':
 									 
										  $contents_edit_orders_edit     = '' ;
		                                  include( DIR_WS_MODULES . 'edit_order_address_invoice_send_ship.php' )  ;					  
 
                                          $contents .= '                   <div class="panel panel-primary">' . PHP_EOL; // begin panel
	                                      $contents .= '                      <div class="panel-heading">' . TEXT_COLLAPSE_ADRESS . '</div>' . PHP_EOL; // panel heading
	                                      $contents .= '                      <div class="panel-body">' . PHP_EOL; // panel body
										  $contents .= '<!-- begin order address shipping invoice payment -->' . PHP_EOL;	  
	                                      $contents .=                             tep_draw_bs_form('edit_order_status', FILENAME_ORDERS, 'oID=' . $oInfo->orders_id . '&page=' . $HTTP_GET_VARS['page'] . '&action=update_address_order', 'post', 'role="form" class="form-horizontal"'); 	  

										  $contents .= $contents_edit_orders_edit  ;
	  
										  $contents .= '                              <br /><br />' . PHP_EOL;  
										
	                                      $contents .= '                              <div class="col-xs-12">' . PHP_EOL;	  
	                                      $contents .= '                           ' .  tep_draw_bs_button(IMAGE_SAVE, "ok", null) . '&nbsp;&nbsp;' . PHP_EOL;
	                                      $contents .= '                           ' .  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) ) ) . PHP_EOL;
	                                      $contents .= '                              </div>' . PHP_EOL;										  
										  $contents .= '                           </form>' . PHP_EOL;	
	                                      $contents .= '                      </div>' . PHP_EOL; // endpanel body
                                          $contents .= '                   </div>' . PHP_EOL;	  	  // end panel										  
										  $contents .= '<!-- end order  address shipping invoice payment  -->' . PHP_EOL;										
                                      break ;


		                            default: 
// bof multi stores		
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
//			                            $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 			
			                            $contents .= '                              ' . TEXT_INFO_PAYMENT_METHOD . ' '   . $oInfo->payment_method . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_SHIPPING_METHOD . ' ' . tep_get_orders_shipping_method($oInfo->orders_id) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
										
										$stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " where stores_id= '" . $oInfo->billing_stores_id . "'");
                                        $stores_customers =  tep_db_fetch_array($stores_customers_query) ;
		 									
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;									
										
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;

                                        $order = new order($oInfo->orders_id);
			                            
										$contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_PRODUCTS_ORDERED . ' ' . sizeof($order->products). PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;										
//                                        $contents[] = array('text' => TEXT_INFO_PRODUCTS_ORDERED . sizeof($order->products) );
        
		                                for ($i=0; $i<sizeof($order->products); $i++) {											
						                      $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                                  $contents .= '                              ' . $order->products[$i]['qty'] . '&nbsp;x&nbsp;' . $order->products[$i]['name'] . sprintf(TEXT_PRODUCTS_REMAINING, GetProductsOnHand($order->products[$i])) . '<br />' . PHP_EOL;                                          											  

//                                              $contents[] = array('text' => $order->products[$i]['qty'] . '&nbsp;x&nbsp;' . $order->products[$i]['name'] . sprintf(TEXT_PRODUCTS_REMAINING, GetProductsOnHand($order->products[$i])));
        
                                              if (sizeof($order->products[$i]['attributes']) > 0) {
                                                 for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
			                                         $contents .= '                        ' . '&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></nobr><br />' . PHP_EOL;   													 
//                                                     $contents[] = array('text' => '&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></nobr>' );
                                                 }
                                              }
			                                  $contents .= '                          </li>' . PHP_EOL;											  
                                        }										
										
//			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
//			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $info['number_of_logons'] . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//	/		                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                                    
						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) .'oID=' . $orders['orders_id']  ), null, null, 'btn-default text-danger') . PHP_EOL;										
                                    break;
                                 }
	
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="7">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
                              }	
    } // while ($orders = tep_db_fetch_array($orders_query)) {
?>		
		</tbody>

    </table> <!-- end div <table class="table table-condensed -->
	</div>
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText hidden-xs mark" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
            <td class="smallText mark" style="text-align: right;"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))) ; ?></td>	   
		  </tr>

    </table>	
<?php
 
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>