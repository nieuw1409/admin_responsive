<?php
/*
  $Id: newsletter_unsubscribe_success.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTER_UNSUBSCRIBE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE));

  $unsubscribe_email = tep_db_prepare_input($HTTP_POST_VARS['unsubscribeemail']);
  $unsubscribe_id    = tep_db_prepare_input($HTTP_POST_VARS['unsubscribeid']);
  $customer_id       = tep_db_prepare_input($HTTP_POST_VARS['customer_id']); 


  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div class="contentContainer">
<?php
    if (  $unsubscribe_id != 0 ) {
       $unsubscribe_query = tep_db_query("select subscription_id, subscription_email_address, subscription_name, subscription_date_creation, subscription_customers_group_id, subscription_stores_id from " . TABLE_NEWSLETTER_SUBSCRIPTION . " where subscription_email_address = '" . $unsubscribe_email . "' and subscription_id = '" . $unsubscribe_id . "'");
       $unsubscribe_values = tep_db_fetch_array($unsubscribe_query);
		  
       if ($unsubscribe_values['subscription_email_address'] != '') {
		  $id_subscription                  = $unsubscribe_values['subscription_id']  ;		   
		  $email_subscription               = $unsubscribe_values['subscription_email_address']  ;
		  $name_subscription                = $unsubscribe_values['subscription_name']  ;
		  $date_creation_subscription       = $unsubscribe_values['subscription_date_creation']  ;		  
		  $customers_group_id_subscription  = $unsubscribe_values['subscription_customers_group_id']  ;
		  $store_id_subscription            = $unsubscribe_values['subscription_stores_id']  ;		  
		  
          tep_db_query("delete from " . TABLE_NEWSLETTER_SUBSCRIPTION . " where subscription_email_address = '" . tep_db_input($unsubscribe_email) . "' and subscription_id = '" . tep_db_input($unsubscribe_id) . "'");

	      if (EMAIL_USE_HTML == 'true'){
		     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	      } else{
		     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	      }
		  
		  $get_text = tep_db_fetch_array($text_query);
		  $text = tep_decode_specialchars( $get_text["eorder_text_one"] );
		  $subject = $get_text["eorder_title"];
			 	
		  $text = preg_replace('/<-SYS_STORE_NAME->/',                         STORE_NAME,          $text);
		  $text = preg_replace('/<-SYS_SUBSCRIBER_ID->/',                      $id_subscription,    $text); 
		  $text = preg_replace('/<-SYS_SUBSCRIBER_NAME->/',                    $name_subscription,  $text);  
		  $text = preg_replace('/<-SYS_SUBSCRIBER_EMAIL->/',                   $email_subscription, $text); 		  
 		  
				  
				  
			   // picture mode
//			   $email_text = tep_add_base_ref($text);  
   
	      tep_mail($name, $email_subscription, $subject, $text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 

		  // send emails to admin
		  if ( EMAIL_ADMIN_CREATE_CUSTOMER != '') {
			  
			 $cust_group_query = tep_db_query("select customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" . $customers_group_id_subscription . "' ");
			 $cust_group = tep_db_fetch_array($cust_group_query);
				
			 $customer_group_name = $cust_group['customers_group_name'] ;
			
			 $stores_query = tep_db_query("select stores_name from " . TABLE_STORES . " where stores_id = '" . $store_id_subscription . "' ");
			 $stores = tep_db_fetch_array($stores_query);
				
			 $stores_name = $stores['stores_name'] ;			  
			 
			 if (EMAIL_USE_HTML == 'true'){
				$text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
			 } else{
			    $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
			 }
			 $get_text = tep_db_fetch_array($text_query);
			 $text = tep_decode_specialchars( $get_text["eorder_text_one"] );
			 $subject = $get_text["eorder_title"];
						
			 $text = preg_replace('/<-SYS_STORE_NAME->/',                                   STORE_NAME,                                                        $text);
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_ID->/',                      $id_subscription,                                                  $text);
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_NAME->/',                    $name_subscription,                                                $text);  
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_EMAIL_ADDRESS->/',           $email_subscription,                                               $text); 
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_CUSTOMERS_GROUP_ID->/',      $customers_group_id_subscription . ' ' . $customer_group_name,     $text); 			 
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_STORES_ID->/',               $store_id_subscription . ' ' . $stores_name,                       $text); 
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_DATE_CREATION->/',           $date_creation_subscription,                                       $text); 			 
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_TYPE->/',                    UNSUBSCRIBE_SUBSCRIBER,                                            $text);			 
			 
			 // picture mode 
			 //  $email_text = tep_add_base_ref($text);  
			 tep_mail('', EMAIL_ADMIN_CREATE_CUSTOMER, $subject, $text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	  
		  }			   
			   

		  echo '            <div class="panel panel-success">'. PHP_EOL ;
		  echo '            <div class="panel-heading">' . HEADING_TITLE . '</div>'. PHP_EOL ;
		  echo '               <div class="panel-body">'. PHP_EOL ;
		  echo '                 <p>' . sprintf(UNSUBSCRIBE_TEXT_OK, $unsubscribe_email) . '</p>'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '               </div>'. PHP_EOL ;
		  echo '            </div>'. PHP_EOL ;

       } else {

		  echo '            <div class="panel panel-success">'. PHP_EOL ;
		  echo '            <div class="panel-heading">' . HEADING_TITLE . '</div>'. PHP_EOL ;
		  echo '               <div class="panel-body">'. PHP_EOL ;
		  echo '                 <p>' . sprintf(UNSUBSCRIBE_TEXT_ERROR, $unsubscribe_email) . 'subscription</p>'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '               </div>'. PHP_EOL ;
		  echo '            </div>'. PHP_EOL ;
       }
    }
	
	// remove newsletter with customer
    if ( $customer_id != 0 ) {
       $customers_query = tep_db_query("select customers_id,  	customers_email_address,  	customers_firstname, customers_lastname, customers_group_id, customers_stores_id from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
       $customers_values = tep_db_fetch_array($customers_query);
		  
       if ($customers_values['customers_email_address'] != '') {
		  $id_subscription                  = $customers_values['customers_id']  ;		   
		  $email_subscription               = $customers_values['customers_email_address']  ;
		  $name_subscription                = $customers_values['customers_firstname'] . ' ' . $customers_values['customers_lastname']  ;		  
		  $customers_group_id_subscription  = $customers_values['customers_group_id']  ;
		  $store_id_subscription            = $customers_values['customers_stores_id']  ;		  
		  
 //         tep_db_query("update from " . TABLE_NEWSLETTER_SUBSCRIPTION . " where subscription_email_address = '" . tep_db_input($unsubscribe_email) . "' and subscription_id = '" . tep_db_input($unsubscribe_id) . "'");

		  tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '0' where customers_id = '" . $customer_id . "'") ;		  

	      if (EMAIL_USE_HTML == 'true'){
		     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	      } else{
		     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	      }
		  
		  $get_text = tep_db_fetch_array($text_query);
		  $text = tep_decode_specialchars( $get_text["eorder_text_one"] ); 
		  $subject = $get_text["eorder_title"];
			 	
		  $text = preg_replace('/<-SYS_STORE_NAME->/',                         STORE_NAME,          $text);
		  $text = preg_replace('/<-SYS_SUBSCRIBER_ID->/',                      $id_subscription,    $text); 
		  $text = preg_replace('/<-SYS_SUBSCRIBER_NAME->/',                    $name_subscription,  $text);  
		  $text = preg_replace('/<-SYS_SUBSCRIBER_EMAIL->/',                   $email_subscription, $text); 		  
				  
			   // picture mode
//			   $email_text = tep_add_base_ref($text);  
   
	      tep_mail($name, $email_subscription, $subject, $text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 

		  // send emails to admin
		  if ( EMAIL_ADMIN_CREATE_CUSTOMER != '') {
			  
			 $cust_group_query = tep_db_query("select customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" . $customers_group_id_subscription . "' ");
			 $cust_group = tep_db_fetch_array($cust_group_query);
				
			 $customer_group_name = $cust_group['customers_group_name'] ;
			
			 $stores_query = tep_db_query("select stores_name from " . TABLE_STORES . " where stores_id = '" . $store_id_subscription . "' ");
			 $stores = tep_db_fetch_array($stores_query);
				
			 $stores_name = $stores['stores_name'] ;	
			 
			 if (EMAIL_USE_HTML == 'true'){
				$text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
			 } else{
			    $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
			 }
			 $get_text = tep_db_fetch_array($text_query);
			 $text = tep_decode_specialchars( $get_text["eorder_text_one"] );
			 $subject = $get_text["eorder_title"];
			 		 
						
			 $text = preg_replace('/<-SYS_STORE_NAME->/',                                   STORE_NAME,                                                        $text);
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_ID->/',                      $id_subscription,                                                  $text);
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_NAME->/',                    $name_subscription,                                                $text);  
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_EMAIL_ADDRESS->/',           $email_subscription,                                               $text); 
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_CUSTOMERS_GROUP_ID->/',      $customers_group_id_subscription . ' ' . $customer_group_name,     $text); 			 
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_STORES_ID->/',               $store_id_subscription . ' ' . $stores_name,                       $text); 
//			 $text = preg_replace('/<-SYS_SUBSCRIPTION_CUSTOMERS_DATE_CREATION->/',           $date_creation_subscription,          $text); 
			 $text = preg_replace('/<-SYS_SUBSCRIBER_CUSTOMERS_TYPE->/',                    UNSUBSCRIBE_CUSTOMER,                                              $text);			 
			 
			 // picture mode 
			 //  $email_text = tep_add_base_ref($text);  
			 tep_mail('', EMAIL_ADMIN_CREATE_CUSTOMER, $subject, $text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	  
		  }			   
			   

		  echo '            <div class="panel panel-success">'. PHP_EOL ;
		  echo '            <div class="panel-heading">' . HEADING_TITLE . '</div>'. PHP_EOL ;
		  echo '               <div class="panel-body">'. PHP_EOL ;
		  echo '                 <p>' . sprintf(UNSUBSCRIBE_TEXT_OK, $unsubscribe_email) . '</p>'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '               </div>'. PHP_EOL ;
		  echo '            </div>'. PHP_EOL ;

       } else {

		  echo '            <div class="panel panel-success">'. PHP_EOL ;
		  echo '            <div class="panel-heading">' . HEADING_TITLE . '</div>'. PHP_EOL ;
		  echo '               <div class="panel-body">'. PHP_EOL ;
		  echo '                 <p>' . sprintf(UNSUBSCRIBE_TEXT_ERROR, $unsubscribe_email) . 'customers</p>'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '                 <br />'. PHP_EOL ;
		  echo '               </div>'. PHP_EOL ;
		  echo '            </div>'. PHP_EOL ;
       }
    }	
?>

  <div class="buttonSet">
    <div class="text-right"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?></div>
  </div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>