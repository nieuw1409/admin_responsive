<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// BOF Separate Pricing Per Customer
  $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id");
  $cg_array = array();
  while ($customers_group = tep_db_fetch_array($customers_group_query)) {
    $cg_array[$customers_group['customers_group_id']] = array('id' => $customers_group['customers_group_id'], 'customers_group_name' => $customers_group['customers_group_name']);
  }
// EOF Separate Pricing Per Customer
  
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if ( ($action == 'send_email_to_user') && isset($HTTP_POST_VARS['customers_email_address']) && !isset($HTTP_POST_VARS['back_x']) ) {
    switch ($HTTP_POST_VARS['customers_email_address']) {
// BOF Separate Pricing Per Customer	
//      case '***':
//        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
//        $mail_sent_to = TEXT_ALL_CUSTOMERS;
//        break;
//      case '**D':
//        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
//        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      case substr($HTTP_POST_VARS['customers_email_address'], 0, 3) == '***' :
          $email_all_array = explode('_', $HTTP_POST_VARS['customers_email_address']);
          if ($email_all_array[1] == '') { // all customers
             $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
             $mail_sent_to = TEXT_ALL_CUSTOMERS;
          } else {
	         $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_group_id = '" . (int)$email_all_array[2] . "'");
             $mail_sent_to = TEXT_FOR_ALL . " " .$cg_array[(int)$email_all_array[2]]['customers_group_name'] . " " . TEXT_FOR_CUSTOMERS ;
          }
        break;
      case substr($HTTP_POST_VARS['customers_email_address'], 0, 3) == '**D':
          $email_all_array = explode('_', $HTTP_POST_VARS['customers_email_address']);
          if ($email_all_array[1] == '') { // all newsletter subscribers
            $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
            $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
          } else {
	        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1' and customers_group_id = '" . (int)$email_all_array[2] . "'");
            $mail_sent_to = $cg_array[(int)$email_all_array[2]]['customers_group_name'] . " " . TEXT_FOR_CUSTOMERS . " " . TEXT_FOR_NEWSLETTER_SUBSCRIBERS ;
          }
// EOF Separate Pricing Per Customer
        break;
      default:
        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);

        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];
        break;
    }

    $from = tep_db_prepare_input($HTTP_POST_VARS['from']);
    $from_name = tep_db_prepare_input($HTTP_POST_VARS['from_name']);	
    $subject = tep_db_prepare_input($HTTP_POST_VARS['subject']); 
    $messageraw = tep_db_prepare_input($HTTP_POST_VARS['message']);
    $message = tep_add_base_ref($messageraw);


    while ($mail = tep_db_fetch_array($mail_query)) {
// bof email html + pics	
//      $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
      tep_mail($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], $subject, $message, $from_name, $from, false);
	  $counter += 1 ;
// eof email html + pics	  
    }

    tep_redirect(tep_href_link(FILENAME_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to) . '&counter=' . $counter ));
  }

  if ( ($action == 'preview') && !isset($HTTP_POST_VARS['customers_email_address']) ) {    // NO ADDRESS
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
		tep_draw_hidden_field('back_x', '');		
  }
// eof email html + pics
  
  if (isset($HTTP_GET_VARS['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $HTTP_GET_VARS['mail_sent_to']), 'success');
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->

            <div class="panel panel-primery">
<?php
               if ( ($action == 'preview') && isset($HTTP_POST_VARS['customers_email_address']) ) {	

                  switch ($HTTP_POST_VARS['customers_email_address']) {			   
                     case substr($HTTP_POST_VARS['customers_email_address'], 0, 3) == '***' :
                        $email_all_array = explode('_', $HTTP_POST_VARS['customers_email_address']);
                        if ($email_all_array[1] == '') { // all customers
                            $mail_sent_to = TEXT_ALL_CUSTOMERS;
                        } else {
                            $mail_sent_to = TEXT_FOR_ALL . " " . $cg_array[(int)$email_all_array[2]]['customers_group_name'] . " " . TEXT_FOR_CUSTOMERS ;
                        }
                        break;
            
			         case substr($HTTP_POST_VARS['customers_email_address'], 0, 3) == '**D':
                        $email_all_array = explode('_', $HTTP_POST_VARS['customers_email_address']);
                        if ($email_all_array[1] == '') { // all newsletter subscribers
                          $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
                        } else {
                          $mail_sent_to = $cg_array[(int)$email_all_array[2]]['customers_group_name'] . " " . TEXT_FOR_CUSTOMERS . " " . TEXT_FOR_NEWSLETTER_SUBSCRIBERS ;
                        }
// EOF Separate Pricing Per Customer
                        break;
                     default:
                        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];
                        break;
                  }
	              $content_input  .=     tep_draw_bs_form('mail', FILENAME_MAIL, 'action=send_email_to_user', 'post', 'role="form"', 'id_input_send_mail') . PHP_EOL ;
 
	              $content_input  .= '                        <ul class="list-group">' . PHP_EOL ;
	              $content_input  .= '                          <li class="list-group-item row">'. PHP_EOL ;
			  
	              $content_input  .= '                             <div class="col-xs-8">' . $mail_sent_to      . PHP_EOL ;
	              $content_input  .= '                             <div class="col-xs-4">' . TEXT_CUSTOMER      .    PHP_EOL ;					  
	              $content_input  .= '                          </li>' . PHP_EOL ;				  

	              $content_input  .= '                          <li class="list-group-item row">'. PHP_EOL ;
	              $content_input  .= '                             <div class="col-xs-4">' . TEXT_FROM        . PHP_EOL ;				  
	              $content_input  .= '                             <div class="col-xs-8">' . htmlspecialchars(stripslashes($HTTP_POST_VARS['from_name']) )      . PHP_EOL ;
	              $content_input  .= '                          </li>' . PHP_EOL ;					  
				  
	              $content_input  .= '                          <li class="list-group-item row">'. PHP_EOL ;
	              $content_input  .= '                             <div class="col-xs-4">' . TEXT_FROM_EMAIL        . PHP_EOL ;				  
	              $content_input  .= '                             <div class="col-xs-8">' . htmlspecialchars(stripslashes($HTTP_POST_VARS['from']))      . PHP_EOL ;
	              $content_input  .= '                          </li>' . PHP_EOL ;	

	              $content_input  .= '                          <li class="list-group-item row">'. PHP_EOL ;
	              $content_input  .= '                             <div class="col-xs-4">' . TEXT_SUBJECT        . PHP_EOL ;				  
	              $content_input  .= '                             <div class="col-xs-8">' . htmlspecialchars(stripslashes($HTTP_POST_VARS['subject']))      . PHP_EOL ;
	              $content_input  .= '                          </li>' . PHP_EOL ;	

	              $content_input  .= '                          <li class="list-group-item row">'. PHP_EOL ;
	              $content_input  .= '                             <div class="col-xs-4">' . TEXT_MESSAGE        . PHP_EOL ;				  
	              $content_input  .= '                             <div class="col-xs-8">' . stripslashes($HTTP_POST_VARS['message'])      . PHP_EOL ;	  
	              $content_input  .= '                          </li>' . PHP_EOL ;					  
			  
	              $content_input  .= '                        </ul>' . PHP_EOL ;
				  
                  
				  /* Re-Post all POST'ed variables */
                  reset($HTTP_POST_VARS);
                  while (list($key, $value) = each($HTTP_POST_VARS)) {
                     if (!is_array($HTTP_POST_VARS[$key])) {
                        $content_input .= '                       <div class="form-group">' . PHP_EOL ;										   
				        $content_input .= '                           ' . tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value))) . PHP_EOL;	
                        $content_input .= '                       </div>' . PHP_EOL ;							                           
                     }
                  }				  
				  
	              $content_input  .=                        tep_draw_bs_button(IMAGE_SEND_EMAIL, 'send', null, null, null, 'btn-default text-danger') . PHP_EOL;					 
	              $content_input  .=                        tep_draw_bs_button(IMAGE_CANCEL,     'close',       tep_href_link(FILENAME_MAIL, 'message='. $HTTP_POST_VARS['message'] . '&subject=' .  htmlspecialchars(stripslashes($HTTP_POST_VARS['subject'])) ), null, null, 'btn-default text-danger') . PHP_EOL;					  
 				 				 
                  $content_input .= '    </form>' . PHP_EOL ;
				 
                  $content_input .= '</div>' . PHP_EOL ;				 // end div panel body input
				 
				  echo $content_input ;				  
			   
			   } else {		
                 $customers = array();
                 $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
                 $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
// BOF Separate Pricing Per Customer
                 foreach ($cg_array as $id_and_name) {
		               $customers[] = array('id' => '***_cg_' . $id_and_name['id'], 'text' => TEXT_FOR_ALL . " " . $id_and_name['customers_group_name'] . " " . TEXT_FOR_CUSTOMERS);
                 } // end foreach $cg_array as $id_and_name
                 $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
                 foreach ($cg_array as $id_and_name) {
		               $customers[] = array('id' => '**D_cg_' . $id_and_name['id'], 'text' => TEXT_FOR_TO . " " . $id_and_name['customers_group_name'] . " " . TEXT_FOR_CUSTOMERS . " " . TEXT_FOR_NEWSLETTER_SUBSCRIBERS);
                 } // end foreach $cg_array as $id_and_name
// EOF Separate Pricing Per Customer

                 $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
                 while($customers_values = tep_db_fetch_array($mail_query)) {
                     $customers[] = array('id' => $customers_values['customers_email_address'],
                                          'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
                 }			   
                 $content_input .= '<div class="panel-body">' . PHP_EOL ;
                 $content_input .=      tep_draw_bs_form('mail', FILENAME_MAIL, 'action=preview', 'post', 'role="form"', 'id_input_mail') . PHP_EOL ;
                 
				 $content_input .= '                       <div class="form-group">' . PHP_EOL ;										   
				 $content_input .= '                           ' . tep_draw_bs_pull_down_menu('customers_email_address', $customers, (isset($HTTP_GET_VARS['customer']) ? $HTTP_GET_VARS['customer'] : ''), TEXT_CUSTOMER, 'inputCustomer_email', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;										   
                 $content_input .= '                       </div>' . PHP_EOL ;
				 
                 $content_input .= '                       <div class="form-group">' . PHP_EOL ;										   
				 $content_input .= '                           ' . tep_draw_bs_input_field('from_name',      STORE_NAME,                       TEXT_FROM,          'id_input_store_name' ,                  'col-xs-3', 'col-xs-9', 'left', TEXT_FROM,         '', true ) . PHP_EOL;	
                 $content_input .= '                       </div>' . PHP_EOL ;					 
				 
                 $content_input .= '                       <div class="form-group">' . PHP_EOL ;										   
				 $content_input .= '                           ' . tep_draw_bs_input_field('from',           STORE_OWNER_EMAIL_ADDRESS,        TEXT_FROM_EMAIL,    'id_input_store_owner_email_address' ,   'col-xs-3', 'col-xs-9', 'left', TEXT_FROM_EMAIL,   '', true ) . PHP_EOL;	
                 $content_input .= '                       </div>' . PHP_EOL ;					 

			     if (tep_not_null($HTTP_POST_VARS['subject'])) {
				    $subject = $HTTP_POST_VARS['subject']; 
			     } else {
				    $subject = '' ;
			     } 							 
				 
                 $content_input .= '                       <div class="form-group">' . PHP_EOL ;										   
				 $content_input .= '                           ' . tep_draw_bs_input_field('subject',         $subject,                               TEXT_SUBJECT,       'id_input_subject' ,                     'col-xs-3', 'col-xs-9', 'left', TEXT_SUBJECT,      '', true ) . PHP_EOL;	
                 $content_input .= '                       </div>' . PHP_EOL ;	

			     if (tep_not_null($HTTP_POST_VARS['message'])) {
				    $message = nl2br(stripslashes($HTTP_POST_VARS['message'])); 
			     } else {
				    $message = TEXT_DEFAULT_EMAIL;
			     } 				 
				 
	             $content_input  .= '                      <div class="form-group">' . PHP_EOL ;
	             $content_input  .= '                          <div class="col-xs-12">' . tep_draw_textarea_ckeditor('message', 'soft', '140', '40', $message, 'id = "id_input_message" class="ckeditor"') . PHP_EOL ;
	             $content_input  .= '                          </div>' . PHP_EOL ;
	             $content_input  .= '                      </div>' . PHP_EOL;						 
	             $content_input  .= '                      <div class="clearfix"></div>' . PHP_EOL;				 
	             $content_input  .= '                      <hr><br />' . PHP_EOL;				 

	             $content_input  .=                        tep_draw_bs_button(IMAGE_PREVIEW, 'send', null, null, null, 'btn-default text-danger') . PHP_EOL;					 
				 				 
                 $content_input .= '    </form>' . PHP_EOL ;
				 
                 $content_input .= '</div>' . PHP_EOL ;				 // end div panel body input
				 
				 echo $content_input ;
			   }
?>			   
			</div> <!-- end div panelinput and preview -->
   </table> <!-- end div table  -->
 

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
