<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
// boostrap design replace all grid_?? with col-md-??
*/

// BOF QPBPP for SPPC
// query names of products for which the min order quantity was not met or 
// didn't match the quantity blocks
  $moq_pids = array();
  $qtb_pids = array();
  if (isset($_SESSION['min_order_qty_not_met']) && count($_SESSION['min_order_qty_not_met']) > 0) {
    foreach ($_SESSION['min_order_qty_not_met'] as $moq_key => $moq_pid) {
      if ((int)$moq_pid > 0) {
        $moq_pids[] = (int)$moq_pid;
      }
    }
  } // end if (isset($_SESSION['min_order_qty_not_met']) && ...

  if (isset($_SESSION['qty_blocks_not_met']) && count($_SESSION['qty_blocks_not_met']) > 0) {
    foreach ($_SESSION['qty_blocks_not_met'] as $qtb_key => $qtb_pid) {
      if ((int)$qtb_pid > 0) {
        $qtb_pids[] = (int)$qtb_pid;
      }
    }
   } // end if (isset($_SESSION['qty_blocks_not_met']) &&
   $moq_qtb_pids = array_merge($moq_pids, $qtb_pids);
   $moq_qtb_pids = array_unique($moq_qtb_pids);

    if (count($moq_qtb_pids) > 0  && tep_not_null($moq_qtb_pids[0])) {
// bof multi stores
        $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	
        if ($customer_group_id == '0') {
          $product_names_query = tep_db_query("select p.products_id, pd.products_name, p.products_min_order_qty, p.products_qty_blocks from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id in (" . implode(',', $moq_qtb_pids) . ") and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
        } else {
          $product_names_query = tep_db_query("select pd.products_id, pd.products_name, pg.products_min_order_qty, pg.products_qty_blocks from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join (select products_id, products_min_order_qty, products_qty_blocks from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $customer_group_id . "' and products_id in (" . implode(',', $moq_qtb_pids) . ")) pg on pd.products_id = pg.products_id where pd.products_id in (" . implode(',', $moq_qtb_pids) . ") and pd.language_id = '" . (int)$languages_id . "'");
        }
      while ($_product_names = tep_db_fetch_array($product_names_query)) {
        if (in_array($_product_names['products_id'], $moq_pids)) {
          $messageStack->add('cart_notice', sprintf(MINIMUM_ORDER_NOTICE, $_product_names['products_name'], $_product_names['products_min_order_qty']), 'warning');
        }
        if (in_array($_product_names['products_id'], $qtb_pids)) {
          $messageStack->add('cart_notice', sprintf(QUANTITY_BLOCKS_NOTICE, $_product_names['products_name'], $_product_names['products_qty_blocks']), 'warning');
        }
      }      
    } // end if (count($moq_qtb_pids) > 0))
// EOF QPBPP for SPPC
// bof bootstrap design  if ($messageStack->size('header') > 0) {
//    echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
//  }
  if ($messageStack->size('header') > 0) {
    echo '<div class="col-md-12">' . $messageStack->output('header') . '</div>';
  }  
// eof bootstrap design
  
// BOF QPBPP for SPPC
// show messages in header if the page we are at is not catalog/shopping_cart.php
  if (basename($_SERVER['PHP_SELF']) != FILENAME_SHOPPING_CART && $messageStack->size('cart_notice') > 0) {
    echo $messageStack->output('cart_notice');
  }
// EOF QPBPP for SPPC
/*** Begin Header Tags SEO ***/
if (HEADER_TAGS_DISPLAY_PAGE_TOP_TITLE == 'true') {
?>
 <div class="col-md-12 label label-info" style="text-align:center;"><?php echo $header_tags_array['title']; ?></div>
<?php
}
/*** End Header Tags SEO ***/  
?>  
  <div id="headerShortcuts" class="col-md-12">
<?php
  if ($oscTemplate->hasBlocks('boxes_column_head')) {	
  	echo  $oscTemplate->getBlocks('boxes_column_head');	
  }
// give the visitors a message that the website will be down at ... time
  if ((WARN_BEFORE_DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE == 'false')) {
    $messageStack->add('header', TEXT_BEFORE_DOWN_FOR_MAINTENANCE . PERIOD_BEFORE_DOWN_FOR_MAINTENANCE, 'warning');
  }
// this will let the admin know that the website is DOWN FOR MAINTENANCE to the public
  if ((DOWN_FOR_MAINTENANCE == 'true') && (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE == getenv('REMOTE_ADDR'))) {
    $messageStack->add('header', TEXT_ADMIN_DOWN_FOR_MAINTENANCE, 'warning');
  }
  if ($messageStack->size('header') > 0) echo $messageStack->output('header');	  
?>
</div>
<div class="col-md-12">
  <?php echo $oscTemplate->getContent('header'); ?>
</div>

   <div class="col-md-12">
<?php 
	   if ($oscTemplate->hasBlocks('header_line')) {
	      echo $oscTemplate->getBlocks('header_line'); 
	   }
?>	   
  </div>

 <div id="headerContent" class="col-md-12">
  <div class="col-md-4">&nbsp;
<?php
     if ($oscTemplate->hasBlocks('header_contents_left')) {
       echo $oscTemplate->getBlocks('header_contents_left');
     }
?>
  </div>

  <div class="col-md-4">&nbsp;
<?php
  if ($oscTemplate->hasBlocks('header_contents_center')) {
    echo $oscTemplate->getBlocks('header_contents_center');
  }
?>
  </div>

  <div class="col-md-4">&nbsp;
<?php
  if ($oscTemplate->hasBlocks('header_contents_right')) {
    echo $oscTemplate->getBlocks('header_contents_right');
  }
?>
  </div>
  </div>
 
<?php

if ( SYS_USE_BREADCRUMB == 'true' ) {
?> 
  <div class="col-md-12 infoBoxContainer">
    <div class="infoBoxHeading">
<?php
    if ($oscTemplate->hasBlocks('boxes_column_bread')) {	
       echo  $oscTemplate->getBlocks('boxes_column_bread'); 
    }
?>
    </div>
  </div>
<?php
} // end of use bread_crumb
 
// bof bootstrap design zie class="close glyphicon glyphicon-remove
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<div class="clearfix"></div>
<div class="col-xs-12">
  <div class="alert alert-danger">
    <a href="#" class="close <?php echo glyphicon_icon_to_fontawesome( "remove" ) ; ?>" data-dismiss="alert"></a>
    <?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?>
  </div>
</div>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<div class="clearfix"></div>
<div class="col-xs-12">
  <div class="alert alert-info">
    <a href="#" class="close <?php echo glyphicon_icon_to_fontawesome( "remove" ) ; ?>" data-dismiss="alert"></a>
    <?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?>
  </div>
</div>
<?php
// eof bootstrap design 
  }   
?>