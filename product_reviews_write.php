<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  
  // 2.3.3
  if (!isset($HTTP_GET_VARS['products_id'])) {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
  }
  // eof 2.3.3

// BOF Separate Pricing Per Customer 

//  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
/* // bof multi stores
//  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
  if (isset($_SESSION['sppc_customer_group_id']) ) {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  } else {
// $customer_group_id = '0';
    $customer_group_id = _SYS_STORE_CUSTOMER_GROUP ;
// eof multi stores	
  }
*/  
$customer_group_id = tep_get_cust_group_id() ;

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_purchase,  p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . TABLE_CATEGORIES . " c using(categories_id) where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0");
// EOF Separate Pricing Per Customer, Hide products and categories from groups 
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }
/*// BOF Separate Pricing per Customer
   if ($customer_group_id !='0') {
// BOF qbppc for sppc   
//     $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
      $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "' and customers_group_price != null");
// EOF qbppc for sppc
     if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
	    $product_info['products_price'] = $customer_group_price['customers_group_price'];
     }
   }
// EOF Separate Pricing Per Customer
*/

  $customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $customer = tep_db_fetch_array($customer_query);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $rating = tep_db_prepare_input($HTTP_POST_VARS['rating']);
    $review = tep_db_prepare_input($HTTP_POST_VARS['review']);

    $error = false;
    if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
      $error = true;

      $messageStack->add('review', JS_REVIEW_TEXT);
    }

    if (($rating < 1) || ($rating > 5)) {
      $error = true;

      $messageStack->add('review', JS_REVIEW_RATING);
    }

    if ($error == false) {
      tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
      $insert_id = tep_db_insert_id();

      tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");
 
      // bof email review made 
      if (EMAIL_USE_HTML == 'true'){
          $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_CREATE_REVIEW . "' and language_id = '" . (int)$languages_id  . "' and stores_id='" . SYS_STORES_ID . "'");	
      } else{
          $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_CREATE_REVIEW . "' and language_id = '" . (int)$languages_id  . "' and stores_id='" . SYS_STORES_ID . "'");
      }
      $get_text = tep_db_fetch_array($text_query);
      $text = $get_text["eorder_text_one"];
      $subject = $get_text["eorder_title"];
				
      $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME,                                                               $text);
      $text = preg_replace('/<-SYS_PRODUCT_ID->/',             $product_info['products_id'],                                             $text);
      $text = preg_replace('/<-SYS_PRODUCT_NAME->/',           $product_info['products_name'],                                           $text);	  
      $text = preg_replace('/<-SYS_PRODUCT_MODEL->/',          $product_info['products_model'],                                          $text);		  
      $text = preg_replace('/<-SYS_CUSTOMER_ID->/',            $customer_id,                                                             $text);
      $text = preg_replace('/<-SYS_CUSTOMER_NAME->/',          $customer['customers_firstname'] . ' ' . $customer['customers_lastname'], $text);	  
      $text = preg_replace('/<-SYS_REVIEW_ID->/',              $insert_id,                                                               $text);	 	  
      $text = preg_replace('/<-SYS_REVIEW_RATING->/',          $rating,                                                                  $text);	  
      $text = preg_replace('/<-SYS_REVIEW_LANGUAGE_ID->/',     $language,                                                                $text);	  
      $text = preg_replace('/<-SYS_REVIEW_TEXT->/',            $review,                                                                  $text);	 
	  
	  // picture mode
      $email_text = tep_add_base_ref($text); 

      if ( EMAIL_ADMIN_NEW_REVIEW != '' ) {
		  tep_mail( '', EMAIL_ADMIN_NEW_REVIEW, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
//($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $htm=false) {		
	  }
	  // email to customer
	  tep_mail( '', $customer['customers_email_address'], $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	  // eof email new review
	  
      $messageStack->add_session('product_reviews', TEXT_REVIEW_RECEIVED, 'success');
      tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
    }
  }

/* bof sppc  
  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<del>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }
eof sppc */  

  $pf->loadProduct((int)$HTTP_GET_VARS['products_id'], (int)$languages_id);
  $products_price= '        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			      '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			       $pf->getPriceStringShort( '<span class="">', '<span class="mark" itemprop="price">'  )
				   . '</font></span><br />'  ;	
  
  
  //getPriceString( '<span class="ui-state-default ui-corner-all">', '<span class="ui-state-highlight ui-corner-all" itemprop="price">' );

  
  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br /><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<script type="text/javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>

<div class="page-header">
  <h2 class="pull-right"><?php echo $products_price; ?></h2>
  <h1><?php echo $products_name; ?></h1>
</div>

<?php
  if ($messageStack->size('review') > 0) {
    echo $messageStack->output('review');
  }
?>

<?php echo tep_draw_form('product_reviews_write', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'class="form-horizontal" onsubmit="return checkForm();"', true); ?>

<div class="contentContainer">

<?php
  if (tep_not_null($product_info['products_image'])) {
?>

  <div class="pull-right text-center">
    <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '</a>'; ?>
    <br />
    <p><?php echo tep_draw_button(IMAGE_BUTTON_IN_CART, 'shopping-cart', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now')); ?></p>
  </div>

  <div class="clearfix"></div>

<?php
  }
?>

  <div class="contentText">
    <div class="row">
      <p class="col-xs-3 text-right"><strong><?php echo SUB_TITLE_FROM; ?></strong></p>
      <p class="col-xs-9"><?php echo tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']); ?></p>
    </div>
    <div class="form-group has-feedback">
      <label for="inputReview" class="control-label col-xs-3"><?php echo SUB_TITLE_REVIEW; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_textarea_field('review', 'soft', 60, 15, NULL, 'required aria-required="true" id="inputReview" placeholder="' . SUB_TITLE_REVIEW . '"');
        echo FORM_REQUIRED_INPUT;
        ?>
      </div>
    </div>
    <div class="form-group">     
      <label class="control-label col-xs-3"><?php echo SUB_TITLE_RATING; ?></label>
      <div class="col-xs-9">
        <label class="radio-inline">
           <?php echo tep_bs_radio_field('rating', '1', null, 'id_radio_review_write_1', false, 'radio radio-success radio-inline' ) ; ?>
        </label>
        <label class="radio-inline">
           <?php echo tep_bs_radio_field('rating', '2', null, 'id_radio_review_write_2', false, 'radio radio-success radio-inline' ) ; ?>
        </label>
        <label class="radio-inline">
           <?php echo tep_bs_radio_field('rating', '3', null, 'id_radio_review_write_3', false, 'radio radio-success radio-inline' ) ; ?>
        </label>
        <label class="radio-inline">
           <?php echo tep_bs_radio_field('rating', '4', null, 'id_radio_review_write_4', false, 'radio radio-success radio-inline' ) ; ?>
        </label>
        <label class="radio-inline">
           <?php echo tep_bs_radio_field('rating', '5', null, 'id_radio_review_write_5', false, 'radio radio-success radio-inline' ) ; ?>
        </label>
        <hr>
        <?php echo '<div class="help-block justify" style="width: 300px;">' . TEXT_BAD . '<p class="pull-right">' . TEXT_GOOD . '</p></div>'; ?>
      </div>
    </div>

  </div>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', null, 'primary'); ?></span>

    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'chevron-left', tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id', 'action')))); ?>
  </div>
</div>

</form>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
