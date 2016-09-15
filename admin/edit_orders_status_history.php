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
  require_once(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

 
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
	  

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'edit');

  if (isset($action)) {
    switch ($action) {
    
 	
	case 'update_comments':	
	   // UPDATE STATUS HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####

       $check_status_query = tep_db_query("
	                      SELECT customers_name, customers_email_address, orders_status, date_purchased 
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . (int)$oID . "'");
						  
       $check_status = tep_db_fetch_array($check_status_query); 
	
       if (($check_status['orders_status'] != $HTTP_POST_VARS['status']) || (tep_not_null($HTTP_POST_VARS['comments']))) {

            tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = '" . tep_db_input($HTTP_POST_VARS['status']) . "', 
                      last_modified = now() 
                      WHERE orders_id = '" . (int)$oID . "'");
		
		    // Notify Customer ?
            $customer_notified = '0';
			if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
			  $notify_comments = '';
			  if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {
			    $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $HTTP_POST_VARS['comments']) . "\n\n";
			  }
			  if (EMAIL_USE_HTML == 'true'){
                $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_ORDER_UPDATE . "' and language_id = '" . (int)$languages_id  . "' and stores_id='" . SYS_STORES_ID . "'");	
              } else{
                $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWORDER . "' and language_id = '" . (int)$languages_id  . "' and stores_id='" . SYS_STORES_ID . "'");
              }
              $get_text = tep_db_fetch_array($text_query);
              $text = $get_text["eorder_text_one"];
			  $subject = $get_text['eorder_title'];
				
              $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME,                                                    $text);
              $text = preg_replace('/<-SYS_UPDATE_STATUS->/',          tep_get_orders_status_name( $HTTP_POST_VARS['status'], $languages_id ),  $text);
              $text = preg_replace('/<-SYS_UPDATE_COMMENTS->/',        $notify_comments,                                              $text);			
              $text = preg_replace('/<-SYS_UPDATE_INVOICE_ID->/',      $HTTP_GET_VARS['oID'],                                                  $text);			
              $text = preg_replace('/<-SYS_UPDATE_CUSTOMER_NAME->/',   $check_status['customers_name'],                               $text);
              $text = preg_replace('/<-SYS_UPDATE_CUSTOMER_EMAIL->/',  $check_status['customers_email_address'],                      $text);			
			  $text = preg_replace('/<-SYS_UPDATE_ORDER_PURCHASED->/', $check_status['date_purchased'],                               $text);
              //SADESA ORDER TRACKING			  
			  $text = preg_replace('/<-SYS_UPDATE_TRATRA_URL->/',      '<a href="' . SYS_TRACK_TRACE_URLTONR . nl2br(tep_output_string_protected(nl2br(tep_db_output($track_num)))) . SYS_TRACK_TRACE_URLTOPC . nl2br(tep_db_output($track_pcode)) .'" target="_blank">' . nl2br(tep_output_string_protected(nl2br(tep_db_output($track_num)))) . '</a>&nbsp;&nbsp;'
		                                                                       ,                                                      $text);	
              $text = preg_replace('/<-SYS_UPDATE_TRATRA_PCODE->/',    $track_pcode,                                                  $text); 			  
              $text = preg_replace('/<-SYS_UPDATE_TRATRA_NUMBER->/',   $track_num,                                                    $text); 				  
               // //SADESA ORDER TRACKING
              // picture mode
              $email_text = tep_add_base_ref($text); 

              tep_mail($check_status['customers_name'], $check_status['customers_email_address'], $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 
			  
			  $customer_notified = '1';
			}			  

			tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, track_num, track_pcode, comments) values 
			('" .       tep_db_input($HTTP_GET_VARS['oID'])       . "', 	'" . tep_db_input($HTTP_POST_VARS['status']) . "', now(), " . tep_db_input($customer_notified) . ",   '" . 
			tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['track_num']))  . "',   '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['track_pcode']))  . "',   '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['comments']))  . "')");

			//SADESA ORDER TRACKING// bof 5_0_8 Google Maps

			if (FILENAME_GOOGLE_MAP     !== 'FILENAME_GOOGLE_MAP'     ) {

			  if ($status == GOOGLE_MAP_ORDER_STATUS ) {    // wenn "Versendet"
            
			     //require(DIR_WS_LANGUAGES . $language . '/report_googlemap.php');

				 $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
          
		         $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
                 $order_exists = true;
				 if (!tep_db_num_rows($orders_query)) {
						$order_exists = false;
						$messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
				 }

				 include(DIR_WS_CLASSES . 'order.php');
				 $order = new order($oID);
				 
                 $url  = "file_get_contents('http://maps.google.com/maps/api/geocode/json?address="  .PHP_EOL ;
				 $url .= $order->delivery['street_address'] . "," . $order->delivery['postcode'] . "," . $order->delivery['city'] . "," . $order->delivery['country'] . PHP_EOL ;
				 $url .= '"&sensor=false")';
				 $url = str_replace (" ", "%20", $url);          // Leerzeichen -> %20				 
				 
				 
                 $response = json_decode($url);

                 $lat = $response->results[0]->geometry->location->lat;
                 $long = $response->results[0]->geometry->location->lng; 				 


				 $latlng_query_raw = "insert into orders_to_latlng (orders_id, lat, lng) values ('$oID','$lat','$lng')";
				 $latlng_query = tep_db_query($latlng_query_raw);
				 
		      } // endif versendet
		    } // endif check voor contribution google maps
		// eof 5_0_8 Google Maps 				
					}
		    if (isset($HTTP_POST_VARS['update_comments'])) {
		          $_update_comments = array_keys( $HTTP_POST_VARS['update_comments'] ) ;  				  
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
		if ( FILENAME_EMAIL_ORDER_TEXT !== �FILENAME_EMAIL_ORDER_TEXT� ){	
			// only use if email order text is installed 
  		    if (EMAIL_USE_HTML == 'true'){
  				$products_ordered .= $order_totals_table_end;
			}
 			if (EMAIL_USE_HTML == 'true'){
  				$text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWORDER . "' and language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");	
			} else{
  				$text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWORDER . "' and language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");
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
//   	 } else {
   	 	// the contribution Email HTML is not installed so we must use the standaard text email
   	 	$standaard_email = 'true' ;
   	    }

   	 	
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
		
        tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
		  
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

?>
<script language="javascript" src="includes/general.js"></script>

<?php  
//   include('order_editor/javascript.php');  
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
        echo tep_draw_bs_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'oId=' . $oID . '&action=update_order', 'post', 'role=form" class="form-horizontal"'); 
		
		echo '<div class="btn-group">' . PHP_EOL;

	    echo tep_draw_bs_button(IMAGE_NEW_ORDER_EMAIL,         'send',       tep_href_link(FILENAME_ORDERS_EDIT,        'action=email&oID=' . $HTTP_GET_VARS['oID']) ) . PHP_EOL;
		
	    echo tep_draw_bs_button(IMAGE_ORDERS_INVOICE_PDF,     'list-alt',    tep_href_link(FILENAME_PDF_INVOICE,        'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true)) . PHP_EOL;
		echo tep_draw_bs_button(IMAGE_ORDERS_PACKINGSLIP_PDF, 'inbox',       tep_href_link(FILENAME_PDF_PACKINGSLIP,    'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true)) . PHP_EOL;
		
	    echo tep_draw_bs_button(IMAGE_ORDERS_LABEL,           'tags',        tep_href_link(FILENAME_ORDERS_LABEL,       'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true)) . PHP_EOL;
		echo tep_draw_bs_button(IMAGE_GOOGLE_DIRECTIONS,      'hand-right',  tep_href_link(FILENAME_GOOGLE_MAP,         'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true)) . PHP_EOL;
		
	    echo tep_draw_bs_button(IMAGE_ORDERS_INVOICE,         'list-alt',    tep_href_link(FILENAME_ORDERS_INVOICE,     'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true)) . PHP_EOL;
		echo tep_draw_bs_button(IMAGE_ORDERS_PACKINGSLIP,     'inbox',       tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $HTTP_GET_VARS['oID']), null, array('newwindow' => true)) . PHP_EOL;
		
		echo '</div>' . PHP_EOL;
		echo '<div class="clearfix"></div>' . PHP_EOL;		
		echo '<br />' . PHP_EOL;	

        include( DIR_WS_MODULES . 'edit_order_edit_order_status_history.php' ) ;	
        echo  $contents_edit_orders_edit_order_status ;  
	
   	    echo $contents_edit_orders_buttons  ;  
		
     }  // if (($action == 'edit') && ($order_e
?>
</table> <!-- end first table -->

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>