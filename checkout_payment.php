<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    $any_out_of_stock = 0;
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
     if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
       $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity'], $products[$i]['attributes']);
     }
     else{
       $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
     }
     if ($stock_check) $any_out_of_stock = 1;
  	}
    if ($any_out_of_stock == 1) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
      break;
    }
 }
// eof qtpro 461

// if no billing destination address was selected, use the customers own address as default
  if (!tep_session_is_registered('billto')) {
    tep_session_register('billto');
    $billto = $customer_default_address_id;
  } else {
// verify the selected billing address
    if ( (is_array($billto) && empty($billto)) || is_numeric($billto) ) {
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] != '1') {
        $billto = $customer_default_address_id;
        if (tep_session_is_registered('payment')) tep_session_unregister('payment');
      }
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (isset($HTTP_POST_VARS['comments']) && tep_not_null($HTTP_POST_VARS['comments'])) {
    $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<script type="text/javascript"><!--
var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
<?php
  // Discount Code 3.1 - start
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') {
?>
$(document).ready(function() {
var a = 0;
discount_code_process();
$('#discount_code').blur(function() { if (a == 0) discount_code_process(); a = 0 });
$("#discount_code").keypress(function(event) { if (event.which == 13) { event.preventDefault(); a = 1; discount_code_process() } });
function discount_code_process() { if ($("#discount_code").val() != "") { $("#discount_code").attr("readonly", "readonly"); $("#discount_code_status").empty().append('<?php echo tep_image(DIR_WS_ICONS . 'dc_progress.gif'); ?>'); $.post("discount_code.php", { discount_code: $("#discount_code").val() }, function(data) { data == 1 ? $("#discount_code_status").empty().append('<?php echo tep_image(DIR_WS_ICONS . 'dc_success.gif'); ?>') : $("#discount_code_status").empty().append('<?php echo tep_image(DIR_WS_ICONS . 'dc_failed.gif'); ?>'); $("#discount_code").removeAttr("readonly") }); } }
});
<?php
  }
  // Discount Code 3.1 - end
?>
//--></script>
<?php echo $payment_modules->javascript_validation(); ?>

<div class="page-header">
  <h1><?php echo HEADING_TITLE . $_GET['matcerror'] ; ?></h1>
</div>

<?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'class="form-horizontal" onsubmit="return check_form();"', true); ?>

<div class="contentContainer">

<?php
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
?>

  <div class="contentText">
    <?php echo '<strong>' . tep_output_string_protected($error['title']) . '</strong>'; ?>

    <p class="messageStackError"><?php echo tep_output_string_protected($error['error']); ?></p>
  </div>

<?php
  }
?>
<?php //-----   BEGINNING OF ADDITION: MATC   -----// 
  if($_GET['matcerror'] = 'true'){
?>
	  <tr>
        <td><?php 
               $matc_error_box_contents = array();
               $matc_error_box_contents[] = array('text' => MATC_ERROR);
               new alertBlock($matc_error_box_contents);
			   
?>
       </td>
      </tr>
<?php 
   } //-----   END OF ADDITION: MATC   -----// ?>
   
  <h2><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></h2>

  <div class="contentText row">
    <div class="col-sm-8">
      <div class="alert alert-warning">
        <?php echo TEXT_SELECTED_BILLING_DESTINATION; ?>
        <div class="clearfix"></div>
        <div class="pull-right">
          <?php echo tep_draw_button(IMAGE_BUTTON_CHANGE_ADDRESS, 'user', tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL')); ?>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="panel panel-primary">
        <div class="panel-heading"><?php echo TITLE_BILLING_ADDRESS; ?></div>
        <div class="panel-body">
          <?php echo tep_address_label($customer_id, $billto, true, ' ', '<br />'); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>

  <h2><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h2>

<?php
  $selection = $payment_modules->selection();

  if (sizeof($selection) > 1) {
?>

  <div class="contentText">
    <div class="alert alert-warning">
      <div class="row">
        <div class="col-xs-8">
          <?php echo TEXT_SELECT_PAYMENT_METHOD; ?>
        </div>
        <div class="col-xs-4 text-right">
          <?php echo '<strong>' . TITLE_PLEASE_SELECT . '</strong>'; ?>
        </div>
      </div>
    </div>
  </div>

<?php
// 2.3.4    } elseif ($free_shipping == false) {
    } else {	
?>

  <div class="contentText">
    <div class="alert alert-info"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></div>
  </div>

<?php
    }
?>
  <div class="contentText">
    <table class="table table-striped table-condensed table-hover">
      <tbody>
<?php 
        echo '      <tr>' . "\n"; 
  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
    if (isset($quotes[$i]['error'])) {
?>
      <div class="contentText">
        <div class="alert alert-warning"><?php echo $selection[$i]['error']; ?></div>
      </div>

<?php
        } else {

?>
  <div class="contentText">
    <div class="form-group">
      <label for="payment" class="control-label col-xs-4"><strong><?php echo $selection[$i]['module']; ?></strong></label>
      <div class="col-xs-8 col-xs-pull-1 text-right">
        <label class="checkbox-inline">
          <?php
          if (sizeof($selection) > 1) {
//            echo tep_draw_radio_field('payment', $selection[$i]['id'], ($selection[$i]['id'] == $payment), false, 'id="payment" required aria-required="true"');
//tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') 
//tep_bs_radio_field($name, $value = '', $label = '', $id, $checked = false, $radio_class='', $label_class='', $input_class ='', $label_lft_rght = 'left', $parameters = '', $hidden = false)
?>
            <div class="col-xs-9">  
               <div class="form-group has-feedback">
                   <?php echo tep_bs_radio_field('payment', $selection[$i]['id'], '',     'payment',   ($selection[$i]['id'] == $payment),'radio radio-success radio-inline', '', '', 'right', 'required aria-required="true"'); ?>         
               </div>		
            </div>	
<?php 
          } else {
            echo tep_draw_hidden_field('payment', $selection[$i]['id'], false, 'id="payment" required aria-required="true"');
          }
          ?>
        </label>
      </div>
    </div>
  </div>
<?php
    $radio_buttons++;
  }
}		 
        echo '      </tr>' . "\n"; 
?>		 
		 
	  </tbody>
	</table>
  </div>
  <hr>


      <!-- Discount Code 3.6 - start -->
<?php
  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'true') {
?>  
<script>
$(document).ready(function() {
var a = 0;
discount_code_process();
$('#discount_code').blur(function() { if (a == 0) discount_code_process(); a = 0 });
$("#discount_code").keypress(function(event) { if (event.which == 13) { event.preventDefault(); a = 1; discount_code_process() } });
function discount_code_process() { if ($("#discount_code").val() != "") { $("#discount_code").attr("readonly", "readonly"); $("#discount_code_status").empty().append('<i class="fa fa-cog fa-spin fa-lg">&nbsp;</i>'); $.post("discount_code.php", { discount_code: $("#discount_code").val() }, function(data) { data == 1 ? $("#discount_code_status").empty().append('<i class="fa fa-check fa-lg" style="color:#00b100;"></i>') : $("#discount_code_status").empty().append('<i class="fa fa-ban fa-lg" style="color:#ff2800;"></i>'); $("#discount_code").removeAttr("readonly") }); } }
});
</script>
  <div class="contentText">
    <div class="form-group  has-feedback">
      <label for="discount_code" class="control-label col-xs-4"><?php echo TEXT_DISCOUNT_CODE; ?></label>
      <div class="col-xs-8">
        <?php
        echo tep_draw_input_field('discount_code', $sess_discount_code, 'id="discount_code"', 'text',  true, TEXT_DISCOUNT_CODE );
        ?>
		<span class="form-control-feedback" id="discount_code_status" style="right:0;"></span>
      </div>	  

      <div class="clearfix"></div>
    </div>
  </div>


<!--  <div class="contentText">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="middle" height="25"><?php echo tep_draw_input_field('discount_code', $sess_discount_code, 'id="discount_code" size="10"'); ?></td>
        <td width="5"></td>
        <td valign="middle"><div id="discount_code_status"></div></td>
      </tr>
    </table>
  </div> -->
<?php
  }
?>
      <!-- Discount Code - end -->  

  <div class="contentText">
    <div class="form-group">
      <label for="inputComments" class="control-label col-xs-4"><?php echo TABLE_HEADING_COMMENTS; ?></label>
      <div class="col-xs-8">
        <?php
        echo tep_draw_textarea_field('comments', 'soft', 60, 5, $comments, 'id="inputComments" placeholder="' . TABLE_HEADING_COMMENTS . '"');
        ?>
      </div>
    </div>
  </div>
<!-- BEGINNING OF ADDITION: MATC -->
<?php
    if(MATC_AT_CHECKOUT != 'false'){
	  require(DIR_WS_MODULES . FILENAME_MATC );
    }
?>
  <div class="buttonSet">
     <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', null, 'primary'); ?></span>
  </div>
<!--   END OF ADDITION: MATC   --> 
  <div class="contentText">
    <div style="float: left; width: 80%; padding-top: 5px; padding-left: 15%;">
      <div id="coProgressBar" style="height: 5px;"></div>

         <div class="clearfix"></div>

         <div class="contentText">
           <div class="stepwizard">
             <div class="stepwizard-row">
              <div class="stepwizard-step">
                  <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><button type="button" class="btn btn-default btn-circle">1</button></a>
                  <p><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo CHECKOUT_BAR_DELIVERY; ?></a></p>
              </div>
              <div class="stepwizard-step">
                <button type="button" class="btn btn-primary btn-circle">2</button>
                   <p><?php echo CHECKOUT_BAR_PAYMENT; ?></p>
              </div>
              <div class="stepwizard-step">
                 <button type="button" class="btn btn-default btn-circle" disabled="disabled">3</button>
                 <p><?php echo CHECKOUT_BAR_CONFIRMATION; ?></p>
              </div>
             </div>
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
