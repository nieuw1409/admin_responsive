<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// bof image products  
  define(ORDERS_IMAGE_HEIGHT, 50);
  define(ORDERS_IMAGE_WIDTH, 50);
  

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($HTTP_POST_VARS['payment'])) $payment = $HTTP_POST_VARS['payment'];

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  // 2.3.3
  //if (tep_not_null($HTTP_POST_VARS['comments'])) {
  if (isset($HTTP_POST_VARS['comments']) && tep_not_null($HTTP_POST_VARS['comments'])) {
  // eof 2.3.3
    $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
  }

// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

  if ( ($payment_modules->selected_module != $payment) || ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }
// CHECKOUT START
  if (CHECKOUT_ENABLED == 'True') tep_redirect(tep_href_link(FILENAME_CHECKOUT, '', 'SSL'));
// CHECKOUT END
// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->process();

// bof qtpro 461  
// Stock Check
//  $any_out_of_stock = false;  
//  if (STOCK_CHECK == 'true') {
//    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
//      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
//        $any_out_of_stock = true;
//      }
//    }
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
//++++ QT Pro: Begin Changed code
    $check_stock='';
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes'])) {
        $attributes=array();
        foreach ($order->products[$i]['attributes'] as $attribute) {
          $attributes[$attribute['option_id']]=$attribute['value_id'];
        }
        $check_stock[$i] = tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'], $attributes);
      } else {
        $check_stock[$i] = tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
      }
      if ($check_stock[$i]) {
        $any_out_of_stock = true;
      }
//++++ QT Pro: End Changed Code
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }
//-----   BEGINNING OF ADDITION: MATC   -----// 
	if (tep_db_prepare_input($HTTP_POST_VARS['TermsAgree']) != 'true' and MATC_AT_CHECKOUT != 'false') {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'matcerror=true', 'SSL'));
    }
//-----   END OF ADDITION: MATC   -----//
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));  
//  $breadcrumb->add(NAVBAR_TITLE_2);

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div class="page-header">
  <h1><?php echo HEADING_TITLE; ?></h1>
</div>

<?php
// bof 2.3.4
  if ($messageStack->size('checkout_confirmation') > 0) {
    echo $messageStack->output('checkout_confirmation');
  }
// eof 2.3.4
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
?>

<div class="contentContainer">
  <div class="contentText">

    <div class="panel panel-default">
      <div class="panel-heading"><?php echo '<strong>' . HEADING_PRODUCTS . '</strong>' . tep_draw_button(TEXT_EDIT, 'edit', tep_href_link(FILENAME_SHOPPING_CART), NULL, NULL, 'pull-right btn-info btn-xs' ); ?></div>
      <div class="panel-body">
    <table width="100%" class="table table-hover order_confirmation">
     <tbody>

<?php
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    
    $orders_products_pic_query = tep_db_query("select products_model, products_image from " . TABLE_PRODUCTS . " where products_id = '".(int)$order->products[$i]['id']."' ");
    $orders_products_pic = tep_db_fetch_array($orders_products_pic_query);
	    	  	  	
    echo '      <tr>' . "\n" ;
    echo '         <td class="main" valign="top" align="right">' . tep_image(DIR_WS_IMAGES . $orders_products_pic['products_image'], $order->products[$i]['name'], ORDERS_IMAGE_WIDTH,  ORDERS_IMAGE_HEIGHT) . '</td>' . "\n" ;
    echo '         <td align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x&nbsp;</td>' . "\n" .
         '         <td valign="top">' . $order->products[$i]['name'];

// bof qtpro 461 		 
//    if (STOCK_CHECK == 'true') {
//      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
//    }
    if (STOCK_CHECK == 'true') {
      echo $check_stock[$i];
    }
// eof qtpro 461

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
// bof 231 option type		  
//        echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': '    . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
	      echo '<br>        <small>&nbsp;    - ' . $order->products[$i]['attributes'][$j]['option'] . ': <i>' . $order->products[$i]['attributes'][$j]['value'] . '</i>';
	 
        if ($order->products[$i]['attributes'][$j]['price'] != '0') {
          echo ' - (' . $order->products[$i]['attributes'][$j]['price_prefix'] . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($order->products[$i]['tax'])) . ')';
        }
        echo '</small>';
// eof 231 option type		
      }
    }

    echo '</td>' . "\n";

    if (sizeof($order->info['tax_groups']) > 1) echo '            <td valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";

    echo '            <td align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>


        </tbody>
      </table>
	  <hr>

      <table width="100%" class="pull-right">

<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    echo $order_total_modules->output();
  }
?>

        </table>
            </div>
    </div>



  </div>

  <div class="clearfix"></div>

  <div class="row">
    <?php
    if ($sendto != false) {
      ?>
      <div class="col-sm-4">
        <div class="panel panel-info">
          <div class="panel-heading"><?php echo '<strong>' . HEADING_DELIVERY_ADDRESS . '</strong>' . tep_draw_button(TEXT_EDIT, 'edit', tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'), NULL, NULL, 'pull-right btn-info btn-xs' ); ?></div>
          <div class="panel-body">
            <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?>
          </div>
        </div>
      </div>
      <?php
    }
    ?>
    <div class="col-sm-4">
      <div class="panel panel-warning">
        <div class="panel-heading"><?php echo '<strong>' . HEADING_BILLING_ADDRESS . '</strong>' . tep_draw_button(TEXT_EDIT, 'edit', tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'), NULL, NULL, 'pull-right btn-info btn-xs' ); ?></div>
        <div class="panel-body">
          <?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <?php
      if ($order->info['shipping_method']) {
        ?>
        <div class="panel panel-info">
          <div class="panel-heading"><?php echo '<strong>' . HEADING_SHIPPING_METHOD . '</strong>' . tep_draw_button(TEXT_EDIT, 'edit', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'), NULL, NULL, 'pull-right btn-info btn-xs' ); ?></div>
          <div class="panel-body">
            <?php echo $order->info['shipping_method']; ?>
          </div>
        </div>
        <?php
      }
      ?>
      <div class="panel panel-warning">
        <div class="panel-heading"><?php echo '<strong>' . HEADING_PAYMENT_METHOD . '</strong>' . tep_draw_button(TEXT_EDIT, 'edit', tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'), NULL, NULL, 'pull-right btn-info btn-xs' ); ?></div>
        <div class="panel-body">
          <?php echo $order->info['payment_method']; ?>
        </div>
      </div>
    </div>


  </div>


<?php
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
  <hr>

  <h2><?php echo HEADING_PAYMENT_INFORMATION; ?></h2>

  <div class="contentText row">
<?php
    if (tep_not_null($confirmation['title'])) {
      echo '<div class="col-sm-6">';
      echo '  <div class="alert alert-danger">';
      echo $confirmation['title'];
      echo '  </div>';
      echo '</div>';
    }
?>
<?php
      if (isset($confirmation['fields'])) {
        echo '<div class="col-sm-6">';
        echo '  <div class="alert alert-info">';
        for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
          echo $confirmation['fields'][$i]['title'] . ' ' . $confirmation['fields'][$i]['field'];
        }
        echo '  </div>';
        echo '</div>';
      }
?>
  </div>
  <div class="clearfix"></div>

<?php
    }
  }

  if (tep_not_null($order->info['comments'])) {
?>
  <hr>

  <h2><?php echo '<strong>' . HEADING_ORDER_COMMENTS . '</strong>' . tep_draw_button(TEXT_EDIT, 'edit', tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'), NULL, NULL, 'pull-right btn-info btn-xs' ); ?></h2>

  <blockquote>
    <?php echo tep_output_string_protected($order->info['comments']) . tep_draw_hidden_field('comments', $order->info['comments']); ?> 
  </blockquote>

<?php
  } // nl2br
?>

  <div class="buttonSet">
    <span class="buttonAction">
      <?php
      if (is_array($payment_modules->modules)) {
        echo $payment_modules->process_button();
      }
      echo tep_draw_button(IMAGE_BUTTON_CONFIRM_ORDER, 'ok-sign', null, 'primary');
      ?>
    </span>
  </div>

  <div class="clearfix"></div>

  <div class="contentText">
    <div class="stepwizard">
      <div class="stepwizard-row">
        <div class="stepwizard-step">
          <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><button type="button" class="btn btn-default btn-circle">1</button></a>
          <p><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo CHECKOUT_BAR_DELIVERY; ?></a></p>
        </div>
        <div class="stepwizard-step">
          <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><button type="button" class="btn btn-default btn-circle">2</button></a>
          <p><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>"><?php echo CHECKOUT_BAR_PAYMENT; ?></a></p>
        </div>
        <div class="stepwizard-step">
          <button type="button" class="btn btn-primary btn-circle">3</button>
          <p><?php echo CHECKOUT_BAR_CONFIRMATION; ?></p>
        </div>
      </div>
    </div>
  </div>

</div>

</form>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>