<?php
/*
  $Id: newsletter_subscription.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

   require('includes/application_top.php');
   require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTER_SUBSCRIPTION);

  $email_subscription = tep_db_prepare_input(strtolower(str_replace("'", "", $_POST['emailsubscription'])));
  $name_subscription = tep_db_prepare_input(strtolower(str_replace("'", "", $_POST['namesubscription'])));  

  $inscrits_check_query = tep_db_query("select count(*) as checkinscrits from " . TABLE_NEWSLETTER_SUBSCRIPTION . " where subscription_email_address = '" . $email_subscription . "' ");
  $inscrits_check_values = tep_db_fetch_array($inscrits_check_query);

  if ( ($inscrits_check_values['checkinscrits']=='0') && ($email_subscription != '') ) {
	  
	$customer_group_id = tep_get_cust_group_id() ;

    $cust_group_query = tep_db_query("select customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" . $customer_group_id . "' ");
    $cust_group = tep_db_fetch_array($cust_group_query);
		
	$customer_group_name = $cust_group['customers_group_name'] ;
	
    $stores_query = tep_db_query("select stores_name from " . TABLE_STORES . " where stores_id = '" . SYS_STORES_ID . "' ");
    $stores = tep_db_fetch_array($stores_query);
		
	$stores_name = $stores['stores_name'] ;
	
    $sql_data_array = array('subscription_email_address' => $email_subscription,
                            'subscription_name' => $name_subscription,	
                            'subscription_date_creation' => 'now()',
                            'subscription_newsletter' => '1',
							'subscription_customers_group_id'=> $customer_group_id,
							'subscription_stores_id' => SYS_STORES_ID );
							
    tep_db_perform(TABLE_NEWSLETTER_SUBSCRIPTION, $sql_data_array);
    $insert_id = tep_db_insert_id();

    $email_unsubscribe = str_replace('@', '4r0b6s3', $email_subscription);

// build the message content

// bof order confirmation html email
	if (EMAIL_USE_HTML == 'true'){
		 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	} else{
		 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	}
	$get_text = tep_db_fetch_array($text_query);
	$text = tep_decode_specialchars( $get_text["eorder_text_one"] );
	$subject = $get_text["eorder_title"];
		
	$text = preg_replace('/<-SYS_STORE_NAME->/',                         STORE_NAME,                                                                                                                                                           $text);
	$text = preg_replace('/<-SYS_SUBSCRIBER_EMAIL_ADDRESS->/',           $email_subscription,                                                                                                                                                  $text);
	$text = preg_replace('/<-SYS_SUBSCRIBER_NAME->/',                    $name_subscription,                                                                                                                                                   $text);  
	$text = preg_replace('/<-SYS_SUBSCRIBER_UNSUBSCRIBE_LINK->/',        sprintf(NL_UNSUBSCRIBE_LINK, 'emailunsubscribe=' . $email_unsubscribe . '&iID='. $insert_id, 'emailunsubscribe=' . $email_unsubscribe . '&iID='. $insert_id),         $text);
	  
// picture mode
	$email_text = tep_add_base_ref($text);  

	tep_mail($name, $email_subscription, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 

	// send emails to admin
	if ( EMAIL_ADMIN_CREATE_CUSTOMER != '') {
		  if (EMAIL_USE_HTML == 'true'){
			 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
		  } else{
			 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
		  }
		  $get_text = tep_db_fetch_array($text_query);
		  $text = tep_decode_specialchars( $get_text["eorder_text_one"] );
		  $subject = $get_text["eorder_title"];
		  
		  $text = preg_replace('/<-SYS_STORE_NAME->/',                                   STORE_NAME,                                         $text);
		  $text = preg_replace('/<-SYS_SUBSCRIBER_ID->/',                                $insert_id,                                         $text);
		  $text = preg_replace('/<-SYS_SUBSCRIBER_NAME->/',                              $name_subscription,                                 $text);  
		  $text = preg_replace('/<-SYS_SUBSCRIBER_EMAIL_ADDRESS->/',                     $email_subscription,                                $text); 
		  $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_GROUP_ID->/',                $customer_group_id . ' ' . $customer_group_name,    $text); 			 
		  $text = preg_replace('/<-SYS_SUBSCRIBER_STORES_ID->/',                         SYS_STORES_ID . ' ' .      $stores_name,            $text); 
		  $text = preg_replace('/<-SYS_SUBSCRIBER_DATE_CREATION->/',                     date( DATE_TIME_FORMAT_2 ),                         $text); 	  	  
		  // picture mode
		  $email_text = tep_add_base_ref($text); 
		  //tep_mail('', EMAIL_ADMIN_CREATE_CUSTOMER, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		  tep_mail('', EMAIL_ADMIN_CREATE_CUSTOMER, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	  
	}	
   }

  tep_redirect(tep_href_link(FILENAME_NEWSLETTER_SUBSCRIPTION_SUCCESS, 'subscribe=' . $email_subscription . '&unsubscribe=' . $email_subscription . '&iID='. $insert_id, 'NONSSL'));
?>