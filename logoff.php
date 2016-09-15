<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
// BOF Separate Pricing Per Customer
  tep_session_unregister('sppc_customer_group_id');
  tep_session_unregister('sppc_customer_group_show_tax');
  tep_session_unregister('sppc_customer_group_tax_exempt');
  if (tep_session_is_registered('sppc_customer_specific_taxes_exempt')) { tep_session_unregister('sppc_customer_specific_taxes_exempt');
  }
// EOF Separate Pricing Per Customer  
// bof contact form
  tep_session_unregister('customer_email_ref');	
// eof contact form  
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
// bof 2.3.4  
//  tep_session_unregister('comments');
  if ( tep_session_is_registered('sendto') ) {
    tep_session_unregister('sendto');
  }

  if ( tep_session_is_registered('billto') ) {
    tep_session_unregister('billto');
  }

  if ( tep_session_is_registered('shipping') ) {
    tep_session_unregister('shipping');
  }

  if ( tep_session_is_registered('payment') ) {
    tep_session_unregister('payment');
  }
 
  if ( tep_session_is_registered('comments') ) {
    tep_session_unregister('comments');
  }  
// eof 2.3.4
  // Discount Code 2.7 - start
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true' && tep_session_is_registered('sess_discount_code')) {
    tep_session_unregister('sess_discount_code');
  }
  // Discount Code 2.7 - end  

  $cart->reset();
  $wishList->reset(); // wishlist

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div class="page-header">
  <h1><?php echo HEADING_TITLE; ?></h1>
</div>

<div class="contentContainer">
  <div class="contentText">
    <div class="alert alert-danger">
      <?php echo TEXT_MAIN; ?>
    </div>
  </div>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?></span>
  </div>
</div>

<?php

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
