<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $error = false;

    $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
    $enquiry = tep_db_prepare_input($HTTP_POST_VARS['enquiry']);

    if (!tep_validate_email($email_address)) {
      $error = true;

      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $actionRecorder = new actionRecorder('ar_contact_us', (tep_session_is_registered('customer_id') ? $customer_id : null), $name);
    if (!$actionRecorder->canPerform()) {
      $error = true;

      $actionRecorder->record(false);

      $messageStack->add('contact', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES') ? (int)MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES : 15)));
    }

    if ($error == false) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);

      $actionRecorder->record();

// bof contact us confirmation html email
      if (EMAIL_USE_HTML == 'true'){
         $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_CONTACT_US . "' and language_id = '" . $languages_id . "' and stores_id='" . SYS_STORES_ID . "'");	
      } else{
         $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_CONTACT_US . "' and language_id = '" . $languages_id . "' and stores_id='" . SYS_STORES_ID . "'");
      }
      $get_text = tep_db_fetch_array($text_query);
      $text    = $get_text["eorder_text_one"];
      $subject = $get_text['eorder_title'];
	
      $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME, $text);
      $text = preg_replace('/<-SYS_NAME->/',                   $name, $text);
 
      $text = preg_replace('/<-SYS_EMAIL_ADDRESS->/',          $email_address, $text);
      $text = preg_replace('/<-SYS_TEXT->/',                   $enquiry, $text);
	  
      $email_text = $text;
  
      // picture mode
      $email_text = tep_add_base_ref($email_text);  
      tep_mail($name, $email_address, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
//      tep_mail($name, $email_address, $subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    
// eof contact us confirmation html email  
      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div class="page-header">
  <h1><?php echo HEADING_TITLE; ?></h1>
</div>

<?php
  if ($messageStack->size('contact') > 0) {
    echo $messageStack->output('contact');
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) {
?>

<div class="contentContainer">
  <div class="contentText">
    <div class="alert alert-info"><?php echo TEXT_SUCCESS; ?></div>
  </div>

  <div class="pull-right">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?>
  </div>
</div>

<?php
  } else {
?>
     <div class="row">
      <?php echo $oscTemplate->getContent('contact_us'); ?>
     </div>
  


<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>