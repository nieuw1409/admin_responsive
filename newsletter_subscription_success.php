<?php
/*
  $Id: newsletter_subscription_success.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTER_SUBSCRIPTION);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSLETTER_SUBSCRIPTION));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<!-- 
<div class="page-header">
  <h1><?php echo HEADING_TITLE; ?></h1>
</div>

<div class="contentContainer">
  <div class="contentText">
    <?php printf( TEXT_INFORMATION, $HTTP_GET_VARS[ 'subscribe'] ); ?>
	<br />
	<br />
    <?php echo tep_draw_button(UNSUBSCRIBE_TEXT, 'remove', tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE, 'iID=' . $HTTP_GET_VARS[ 'iID'], 'NONSSL')); ?>	
  </div>

  <div class="buttonSet">
    <div class="text-right"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?></div>
  </div>
</div>
-->
<div class="panel panel-success">
    <div class="panel-heading"><?php echo HEADING_TITLE; ?></div>
    <div class="panel-body">
       <p><?php printf( TEXT_INFORMATION, $HTTP_GET_VARS[ 'subscribe'] ) ; ?></p>
       <br />
       <br />
       <p><?php echo tep_draw_button(IMAGE_BUTTON_NEWSLETTER_UNSUBSCRIBE, 'remove', tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE, 'iID=' . $HTTP_GET_VARS[ 'iID'] . '&emailunsubscribe='. $HTTP_GET_VARS[ 'unsubscribe'], 'NONSSL')) ; ?></p>

    </div>
</div>
<div class="buttonSet">
      <div class="text-right"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?></div>
</div>	

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>