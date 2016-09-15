<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class product_notification {
    var $show_choose_audience, $title, $content, $send_to_customer_groups, $send_to_stores;

    function product_notification($title, $content, $send_to_customer_groups, $send_to_stores) {
      $this->show_choose_audience = true;
      $this->title = $title;
      $this->content = $content;
// BOF Separate Pricing Per Customer
      $this->send_to_customer_groups = $send_to_customer_groups;
// EOF Separate Pricing Per Customer	  
      $this->send_to_stores = $send_to_stores; // multi stores	  
    }

    function choose_audience() {
      global $HTTP_GET_VARS, $languages_id;

      $products_array = array();
      $products_query = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.language_id = '" . $languages_id . "' and pd.products_id = p.products_id and p.products_status = '1' order by pd.products_name");
      while ($products = tep_db_fetch_array($products_query)) {
        $products_array[] = array('id' => $products['products_id'],
                                  'text' => $products['products_name']);
      }

$choose_audience_string = '<script type="text/javascript"><!--
function mover(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements[\'chosen[]\'].options[x].text,document.notifications.elements[\'chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}

function selectAll(FormName, SelectBox) {
  temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
  Source = eval(temp);

  for (x=0; x<(Source.length); x++) {
    Source.options[x].selected = "true";
  }

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_PRODUCTS . '\');
    return false;
  } else {
    return true;
  }
}
//--></script>';

      $global_button = tep_draw_bs_button(BUTTON_GLOBAL, 'database', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm&global=true'), null, null, 'btn-warning') ;
	  //tep_draw_button(BUTTON_GLOBAL, 'circle-triangle-n', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm&global=true'), 'primary');

      $cancel_button =  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']), null, null, 'btn-danger') ;
	  //tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']));

      $choose_audience_string .= '<form name="notifications" action="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm') . '" method="post" onsubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
                                 '  <tr>' . "\n" .
                                 '    <td align="center" class="smallText"><strong>' . TEXT_PRODUCTS . '</strong><br />' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '    <td align="center" class="smallText">&nbsp;<br />' . $global_button . '<br /><br /><br />
								                                                              <input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br /><br />
								                                                              <input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br /><br /><br />' . 																							  
                                                                                              tep_draw_bs_button(IMAGE_SEND, 'send', null, null, null, 'btn-success') . '<br /><br />' . 																							  
																							  $cancel_button . '</td>' . "\n" .
																							  
                                 '    <td align="center" class="smallText"><strong>' . TEXT_SELECTED_PRODUCTS . '</strong><br />' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n" .
                                 '</table></form>';

      return $choose_audience_string;
    }

    function confirm() {
      global $HTTP_GET_VARS, $HTTP_POST_VARS, $stores_array, $stores_multi_present, $cg_array;
	  
      $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS );
	  // all customers
	  $mail = tep_db_fetch_array($mail_query);
	  $_all_customers = $mail['count'] ;

      $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
	  // all customers
	  $mail = tep_db_fetch_array($mail_query);
	  $_all_customers_prod_notification = $mail['count'] ;	

      if (tep_not_null($this->send_to_stores)) {
		 $where .= ' and customers_stores_id in (' . $this->send_to_stores . ')' ; 		 
		 $sending_stores = true ;		 
	  }	else {
		 if ( $stores_multi_present == true )	 {
		   $sending_stores = false ; 
		 } else {
		   $sending_stores = true ;				 
		 }
	  }	 
	  
      if (tep_not_null($this->send_to_customer_groups)) {
		 $where .= ' and c.customers_group_id  in (' . $this->send_to_customer_groups . ')' ;
		 $sending_cust_group = true ;
	  } else {
		 $sending_cust_group = false ; 
	  }	  
	  
	  if ( ( $sending_stores == true ) && ( $sending_cust_group == true ) ) {
         $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " ci LEFT JOIN " . TABLE_CUSTOMERS . " c on c.customers_id = ci.customers_info_id where ci.global_product_notifications = '1' " . $where);
         $mail = tep_db_fetch_array($mail_query);
   	     $_customers_newsletter_plus_stores = $mail['count'] ;	 	  
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
 	  

      $audience = array();

      if (isset($HTTP_GET_VARS['global']) && ($HTTP_GET_VARS['global'] == 'true')) {
        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS);
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select ci.customers_info_id from " . TABLE_CUSTOMERS_INFO . " ci LEFT JOIN " . TABLE_CUSTOMERS . " c on c.customers_id = ci.customers_info_id where ci.global_product_notifications = '1' " . $where);
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      } else {
        $chosen = $HTTP_POST_VARS['chosen'];

        $ids = implode(',', $chosen);

        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id in (" . $ids . ")");
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " ci LEFT JOIN " . TABLE_CUSTOMERS . " c on c.customers_id = ci.customers_info_id where global_product_notifications = '1'" . $where);
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      }
						
      $confirm_string  = '<div class="panel panel-info">'. PHP_EOL ;
 
//	  $confirm_string .= '          <div class="panel-heading">' . $this->title . '</div>' . PHP_EOL;
	  $confirm_string .= '          <div class="panel-body">' . PHP_EOL;		
	  $confirm_string .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
	  $confirm_string .= '                        <ul class="list-group">' . PHP_EOL;
	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_CUSTOMERS .       ' : ' . $_all_customers . "select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id in (" . $ids . ")" . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;
	  
	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_CUST_PROD_NOTI . ' : ' . $_all_customers_prod_notification  . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;

	  $confirm_string .= '                          <li class="list-group-item">' . PHP_EOL; 		
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_TOTAL_CUST_RECEIVE_PN .    ' : ' . $_customers_newsletter_plus_stores . PHP_EOL;
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
	  $confirm_string .= '                              ' . TEXT_NEWSLETTER_CONTENT . '<br />' . nl2br($this->content) . PHP_EOL;
	  $confirm_string .= '                          </li>' . PHP_EOL;
  
	  $confirm_string .= '                        </ul>' . PHP_EOL;
	  $confirm_string .= '                      </div>' . PHP_EOL;	 	 
  	  
	  $confirm_string .= '                      <div class="row">' . PHP_EOL;	  
	  $confirm_string .= '                      </div>' . PHP_EOL;	
	  
	  $confirm_string .= '          </div>' . PHP_EOL;	   // end panel body						
	  
	  $confirm_string .=     tep_draw_bs_form('confirm', FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm_send', 'post', 'role=form', 'id_formconfirm') . PHP_EOL ;
//	  tep_draw_form('confirm', FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm_send')	  

	  
      if (sizeof($audience) > 0) {
        if (isset($HTTP_GET_VARS['global']) && ($HTTP_GET_VARS['global'] == 'true')) {
          $confirm_string .= ' <div class="form-group">' . PHP_EOL ;				
          $confirm_string .=     tep_draw_hidden_field('global', 'true');
          $confirm_string .= ' </div>' . PHP_EOL ;		  
        } else {
          for ($i = 0, $n = sizeof($chosen); $i < $n; $i++) {
            $confirm_string .= ' <div class="form-group">' . PHP_EOL ;		  
            $confirm_string .=     tep_draw_hidden_field('chosen[]', $chosen[$i]);
            $confirm_string .= ' </div>' . PHP_EOL ;	
          }
        }
   	  }
	  if ( $_customers_newsletter_plus_stores > 0 ) {
        $confirm_string .= tep_draw_bs_button(IMAGE_SEND, 'send', null, 'primary');
      }	  
	  $confirm_string .= '      ' . tep_draw_bs_button(IMAGE_CANCEL, 'remove',   tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']),                          null, null, 'btn-default text-default') . PHP_EOL;			
	  $confirm_string .= '</form>' . PHP_EOL ;
	  
     
	  $confirm_string .= '</div>' . PHP_EOL;	  // end panel
      return $confirm_string;
    }


    function send($newsletter_id) {
      global $HTTP_POST_VARS;
	  
      if (tep_not_null($this->send_to_customer_groups)) {
		 $where_cust_group = ' and c.customers_group_id  in (' . $this->send_to_customer_groups . ')' ;
	  }
	  
      if (tep_not_null($this->send_to_stores)) {
		 $where_stores = ' and c.customers_stores_id in (' . $this->send_to_stores . ')'   ; 
	  }
	  

      $audience = array();

      if (isset($HTTP_POST_VARS['global']) && ($HTTP_POST_VARS['global'] == 'true')) {
	      // BOF Separate Pricing Per Customer
//	    if (tep_not_null($send_to_customer_groups)) {
        $products_query = tep_db_query("select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn 
		                   where c.customers_id = pn.customers_id " . $where_stores . $where_cust_group );

 	    // EOF Separate Pricing Per Customer
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }
 	      // BOF Separate Pricing Per Customer
//	    if (tep_not_null($send_to_customer_groups)) {
        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci 
		               where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1' " . $where_stores . $where_cust_group );

	    // EOF Separate Pricing Per Customer
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
       } else {
        $chosen = $HTTP_POST_VARS['chosen'];

        $ids = implode(',', $chosen);
	      // BOF Separate Pricing Per Customer
        $products_query = tep_db_query("select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn 
		          where c.customers_id = pn.customers_id and pn.products_id in (" . $ids . ") " . $where_stores . $where_cust_group );
	    // EOF Separate Pricing Per Customer
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }
	      // BOF Separate Pricing Per Customer
          $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci 
		        where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1' " . $where_stores . $where_cust_group );
//	    }
	    // EOF Separate Pricing Per Customer

// EOF sppc
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
      }

      $mimemessage = new email(array('X-Mailer: osCommerce'));

      // Build the text version
      //$text = strip_tags($this->content);
// $text = tep_add_base_ref($this->content);
	  $text = $this->content;	  
      if (EMAIL_USE_HTML == 'true') {
        //$mimemessage->add_html($this->content, $text);
		$mimemessage->add_html($this->content, $text, '', 'false');
      } else {
        $mimemessage->add_text($text);
      }

      $mimemessage->build_message();

	  $counter = 0 ;
//      reset($audience);
//      while (list($key, $value) = each ($audience)) {
      foreach ( $audience as $key => $value ) {	
        $mimemessage->send($value['firstname'] . ' ' . $value['lastname'],                   $value['email_address'],          STORE_OWNER,              STORE_OWNER_EMAIL_ADDRESS, $this->title);
        $counter++ ;		
      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");

      return $counter ;   	  
    }
  }  
?>