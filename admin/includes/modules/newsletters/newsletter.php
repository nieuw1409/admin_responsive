<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class newsletter {
    var $show_choose_audience, $title, $content, $send_to_customer_groups, $send_to_stores;

    function newsletter($title, $content, $send_to_customer_groups, $send_to_stores  ) {
      $this->show_choose_audience = false;
      $this->title = $title;
      $this->content = $content;
	  
      $this->text_unsubscribe = $text_unsubscribe ;
// BOF Separate Pricing Per Customer
      $this->send_to_customer_groups = $send_to_customer_groups;
// EOF Separate Pricing Per Customer	  
      $this->send_to_stores = $send_to_stores; // multi stores
    }

    function choose_audience() {
      return false;
    }

    function confirm() {
      global $HTTP_GET_VARS, $stores_array, $stores_multi_present, $cg_array;
// BOF Separate Pricing Per Customer

      $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS );
	  // all customers
	  $mail = tep_db_fetch_array($mail_query);
	  $_all_customers = $mail['count'] ;
	  
      $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
	  // all customers
	  $mail = tep_db_fetch_array($mail_query);
	  $_all_customers_newsletter = $mail['count'] ;	

      $mail_query = tep_db_query("select count(*) as count from " . TABLE_NEWSLETTER_SUBSCRIPTION );
	  // all customers
	  $mail = tep_db_fetch_array($mail_query);
	  $_all_subscribers = $mail['count'] ;
	  
      $mail_query = tep_db_query("select count(*) as count from " . TABLE_NEWSLETTER_SUBSCRIPTION . " where subscription_newsletter = '1'");
	  // all customers
	  $mail = tep_db_fetch_array($mail_query);
	  $_all_subscribers_newsletter = $mail['count'] ;		  
	  
	  $sending_emails = false ;	  
	  
      if (tep_not_null($this->send_to_stores)) {
		 $where     .= ' and customers_stores_id in (' . $this->send_to_stores . ')' ;
		 $where_sub .= ' and subscription_stores_id in (' . $this->send_to_stores . ')' ; 		 
		 $sending_stores = true ;		 
	  }	else {
		 if ( $stores_multi_present == true )	 {
		   $sending_stores = false ; 
		 } else {
		   $sending_stores = true ;				 
		 }
	  }	 	  
	  
      if (tep_not_null($this->send_to_customer_groups)) {
		 $where     .= ' and customers_group_id  in (' . $this->send_to_customer_groups . ')' ;
		 $where_sub .= ' and subscription_customers_group_id in (' . $this->send_to_customer_groups . ')' ;		 
		 $sending_cust_group = true ;
	  } else {
		 $sending_cust_group = false ; 
	  }	  
	  
	  if ( ( $sending_stores == true ) && ( $sending_cust_group == true ) ) {
         $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' " . $where );
         $mail = tep_db_fetch_array($mail_query);
   	     $_customers_newsletter_plus_stores = $mail['count'] ;	
         $mail_query = tep_db_query("select count(*) as count from " . TABLE_NEWSLETTER_SUBSCRIPTION . " where subscription_newsletter = '1' " . $where_sub );
         $mail = tep_db_fetch_array($mail_query);
   	     $_customers_newsletter_plus_stores += $mail['count'] ;			 
	  } else {
		 $_customers_newsletter_plus_stores = 0 ; // do not send if none of the stores or customer group are selected
	  }
	 
	  $stores_array_in_db = explode(',',$this->send_to_stores);
	  for ($y = 0; $y < sizeof($stores_array); $y++) { 	 
	    if ( in_array ($stores_array[$y]['id'], $stores_array_in_db) ) {
			$active_stores .= $stores_array[$y]['stores_name'] . ':' ;
		}
	  }
	  
      $cg_array_in_db = explode(',', $this->send_to_customer_groups);																	 
	  for ($z = 0; $z < sizeof($cg_array); $z++) { 	  
	    if ( in_array ($cg_array[$z]['id'], $cg_array_in_db ) ) {
			$active_customer_groups .= $cg_array[$z]['customers_group_name'] . ':' ;			
		}
	  }
	  
	  $active_stores = substr($active_stores,0,strlen($active_stores)-1);
	  $active_customer_groups = substr($active_customer_groups,0,strlen($active_customer_groups)-1);
						
      $confirm_string  = '<div class="panel panel-info">'. PHP_EOL ;
 
//	  $confirm_string .= '          <div class="panel-heading">' . $this->title . '</div>' . PHP_EOL;
	  $confirm_string .= '          <div class="panel-body">' . PHP_EOL;		
	  $confirm_string .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
	  $confirm_string .= '                        <ul class="list-group">' . PHP_EOL;
	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_CUSTOMERS .       ' : ' . $_all_customers . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;
	  
	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_SUBSCRIBERS .       ' : ' . $_all_subscribers . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;

	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_CUST_NEWSLETTER . ' : ' . $_all_customers_newsletter  . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;
	  
	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_SUBSR_NEWSLETTER . ' : ' . $_all_subscribers_newsletter  . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;	  

	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_CUST_RECEIVE .    ' : ' . $_customers_newsletter_plus_stores . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;
	  
	  if ( $stores_multi_present == true )	 {
	    $confirm_string .= '                        <li class="list-group-item">' . PHP_EOL; 		
	    $confirm_string .= '                              ' . TEXT_NEWSLETTER_ACTIVE_STORES .    ' : ' . $active_stores . PHP_EOL;
	    $confirm_string .= '                        </li>' . PHP_EOL;	  
	  }		

	  if ( tep_not_null($active_customer_groups) ) {
	    $confirm_string .= '                        <li class="list-group-item">' . PHP_EOL; 		
	    $confirm_string .= '                              ' . TEXT_NEWSLETTER_ACTIVE_CUST_GROUPS .    ' : ' . $active_customer_groups . PHP_EOL;
	    $confirm_string .= '                        </li>' . PHP_EOL;
      }		
	  
	  $confirm_string .= '                        </ul>' . PHP_EOL;
	  $confirm_string .= '                      </div>' . PHP_EOL;	  

	  $confirm_string .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
	  $confirm_string .= '                        <ul class="list-group">' . PHP_EOL;
	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TITLE . ' : ' . $this->title . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;
	  
	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_CONTENT . '<br />' . $this->content . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;
  
	  $confirm_string .= '                        </ul>' . PHP_EOL;
	  $confirm_string .= '                      </div>' . PHP_EOL;	 	 

	  $confirm_string .= '                      <div class="row">' . PHP_EOL;
	  $confirm_string .= '                      </div>' . PHP_EOL;	  
  	  
	  $confirm_string .= '          </div>' . PHP_EOL;	   // end panel body
	  if ( $_customers_newsletter_plus_stores > 0 ) {
          $confirm_string .= '  ' . tep_draw_bs_button(IMAGE_SEND,   'send',     tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm_send'), null, null, 'btn-default text-danger') .   PHP_EOL;	
   	  }
	  $confirm_string .= '      ' . tep_draw_bs_button(IMAGE_CANCEL, 'remove',   tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']),                          null, null, 'btn-default text-default') . PHP_EOL;			
	  	  
	  $confirm_string .= '</div>' . PHP_EOL;	  // end panel
	  
      return $confirm_string;
    }
// BOF Separate Pricing Per Customer
    function send($newsletter_id ) {
		
      if (tep_not_null($this->send_to_customer_groups)) {
		 $where_cust_group     = ' and c.customers_group_id  in (' . $this->send_to_customer_groups . ')' ;
		 $where_cust_group_sub = ' and nc.subscription_customers_group_id in (' . $this->send_to_customer_groups . ')' ;			 
	  }
	  
      if (tep_not_null($this->send_to_stores)) {
		 $where_stores     .= ' and c.customers_stores_id in (' . $this->send_to_stores . ')'   ; 
		 $where_stores_sub .= ' and nc.subscription_stores_id in (' . $this->send_to_stores . ')' ; 		 
	  }
	
	  $mail_query              = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, s.stores_name, s.stores_url, s.stores_config_table from " . 
	                                     TABLE_CUSTOMERS . " c left join " . TABLE_STORES . " s on s.stores_id = c.customers_stores_id 
	                                     where c.customers_newsletter = '1'" . $where_stores . $where_cust_group );

      $mail_query_subscription = tep_db_query("select nc.subscription_id, nc.subscription_name, nc.subscription_email_address, s.stores_name, s.stores_url, s.stores_config_table from " . 
	                                     TABLE_NEWSLETTER_SUBSCRIPTION . " nc left join " . TABLE_STORES . " s on s.stores_id = nc.subscription_stores_id 
										 where subscription_newsletter = '1' " . $where_cust_group_sub . $where_stores_sub );
	  
      $counter = 0 ;
 
      while ($mail = tep_db_fetch_array($mail_query)) { 
         // get store url	  
         $unsub_link = '<a href="' . $mail['stores_url'] . '/' . FILENAME_NEWSLETTER_UNSUBSCRIBE . '?' . 'cID=' . $mail['customers_id'] . '&emailunsubscribe='. $mail['customers_email_address'] . '&iID=' . '">' . TEXT_NEWSLETTER_SEND_UNSUBSCRIBE . '</a>';
		 // get store owner and email address
         $sql_temp=tep_db_query("SELECT * FROM " . $mail['stores_config_table'] . " where configuration_key = 'STORE_OWNER'");

         $configuration_db=tep_db_fetch_array($sql_temp);
         $store_owner = $configuration_db["configuration_value"];		 

         $sql_temp=tep_db_query("SELECT * FROM " . $mail['stores_config_table'] . " where configuration_key = 'STORE_OWNER_EMAIL_ADDRESS'");

         $configuration_db=tep_db_fetch_array($sql_temp);
         $store_owner_email_address = $configuration_db["configuration_value"];		 
		 
         $text_send = preg_replace('<-SYS_UNSUBSCRIBE_LINK->',                    $unsub_link,                    $this->content);	
		 $text_send = tep_add_base_ref($text_send);
		 
		 
         tep_mail($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], $this->title, $text_send, $store_owner, $store_owner_email_address, true ) ;
         $counter++ ;
      } 
	  
      while ($mail_subscription = tep_db_fetch_array($mail_query_subscription)) {
         $unsub_link = '<a href="' . $mail_subscription['stores_url'] . '/' . FILENAME_NEWSLETTER_UNSUBSCRIBE . '?' . 'cID=' . '' . '&emailunsubscribe='. $mail_subscription['subscription_email_address'] . '&iID=' . $mail_subscription['subscription_id'] . '">' . TEXT_NEWSLETTER_SEND_UNSUBSCRIBE . '</a>';

		 // get store owner and email address
         $sql_temp=tep_db_query("SELECT * FROM " . $mail_subscription['stores_config_table'] . " where configuration_key = 'STORE_OWNER'");

         $configuration_db=tep_db_fetch_array($sql_temp);
         $store_owner = $configuration_db["configuration_value"];		 

         $sql_temp=tep_db_query("SELECT * FROM " . $mail_subscription['stores_config_table'] . " where configuration_key = 'STORE_OWNER_EMAIL_ADDRESS'");

         $configuration_db=tep_db_fetch_array($sql_temp);
         $store_owner_email_address = $configuration_db["configuration_value"];	
         
		 $text_send = preg_replace('<-SYS_UNSUBSCRIBE_LINK->',                    $unsub_link,                    $this->content);
		 $text_send = tep_add_base_ref($text_send);		 
 
         tep_mail($mail_subscription['subscription_name'], $mail_subscription['subscription_email_address'], $this->title, $text_send, $store_owner, $store_owner_email_address, true ) ;
 			  
 //         $mimemessage->send($mail_subscription['subscription_name'],  $mail_subscription['subscription_email_address'], STORE_OWNER,     STORE_OWNER_EMAIL_ADDRESS, $this->title);
         $counter++ ;
      }	  

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
      	
      return $counter ;  
    }
  }
?>