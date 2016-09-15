<?php
/*
  $Id: newsletter_unsubscribe.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTER_UNSUBSCRIBE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE));

  $nlmail_search_chars = array('/4r0b6s3/', '/\'/');
  $nlmail_replace_chars = array('@', '');

  $unsubscribe_email  = $HTTP_GET_VARS['emailunsubscribe'];
  $unsubscribe_id     = $HTTP_GET_VARS['iID'];
  $customer_id        = $HTTP_GET_VARS['cID'];  

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<!--
<div class="page-header">
  <h1><?php echo HEADING_TITLE; ?></h1>
</div>

<div class="contentContainer">
  <div class="contentText">
    <?php echo UNSUBSCRIBE_TEXT_INFORMATION; ?>
  </div>

  <div class="buttonSet">
    <div class="text-right"><?php echo tep_draw_form('form_newsletterunsubscribe', tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE_SUCCESS, '', 'NONSSL'), 'post', '') . tep_draw_hidden_field('unsubscribeemail', $unsubscribe_email, '') . tep_draw_hidden_field('unsubscribeid', (int)$unsubscribe_id, '') . tep_draw_button(IMAGE_BUTTON_NEWSLETTER_UNSUBSCRIBE, 'glyphicon glyphicon-remove-sign', null, 'primary'); ?></div>
  </div>
</div>
-->
<div class="panel panel-success">
    <div class="panel-heading"><?php echo HEADING_TITLE; ?></div>
    <div class="panel-body">
       <p><?php echo UNSUBSCRIBE_TEXT_INFORMATION ; ?></p>
       <br />
       <br />
<?php 
           echo tep_draw_form('form_newsletterunsubscribe', tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE_SUCCESS, 'unsubscribeemail=' . $unsubscribe_email . '&unsubscribeid=' . $unsubscribe_id . '&customer_id=' . $customer_id , 'NONSSL'), 'post', '') . 
	                   tep_draw_hidden_field('unsubscribeemail', $unsubscribe_email, '') . 
					   tep_draw_hidden_field('unsubscribeid',    (int)$unsubscribe_id, '') . 
					   tep_draw_hidden_field('customer_id',      (int)$customer_id, '') .					   
				       tep_draw_button(IMAGE_BUTTON_NEWSLETTER_UNSUBSCRIBE, 'remove', null, 'primary'); 			
?>

    </div>
</div>
<div class="buttonSet">
      <div class="text-right"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?></div>
</div>	

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>