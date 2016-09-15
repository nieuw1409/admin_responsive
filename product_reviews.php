<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // 2.3.3
  //if (!isset($HTTP_GET_VARS['products_id']) || !is_numeric($HTTP_GET_VARS['products_id'])) {
  if (!isset($HTTP_GET_VARS['products_id']) ) {  
  // eof 2.3.3  
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  }
// BOF Separate Pricing Per Customer, Hide products and categories from groups
/*
// global variable (session) $sppc_customer_group_id -> local variable customer_group_id
// bof multi stores
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

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_purchase, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . TABLE_CATEGORIES . " c using(categories_id) 
              where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' 
			    and p.products_id = pd.products_id                               and pd.language_id = '" . (int)$languages_id . "' 
				and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
				and find_in_set('" . SYS_STORES_ID . "', c.categories_to_stores) != 0 
                and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 " ) ;
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }
 
/* bof sppc
// EOF Separate Pricing Per Customer, Hide products and categories from groups 
    if ($customer_group_id !='0') {
// BOF qbppc for sppc	
//      $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
      $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $review['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "' and customers_group_price != null");
// EOF qbppc for sppc	  
        if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
          $product_info['products_price'] = $customer_group_price['customers_group_price'];
        }
    }

  

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<del>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }
// EOF Separate Pricing Per Customer */

  $pf->loadProduct((int)$product_info['products_id'], (int)$languages_id);
  $products_price= '        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			      '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			       $pf->getPriceStringShort(   )
				   . '</font></span><br />'  ;	


  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br /><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);
  
//  echo DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS ;

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<?php
  if ($messageStack->size('product_reviews') > 0) {
    echo $messageStack->output('product_reviews');
  }
?>

<div itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
<div class="page-header">
  <h1 class="pull-right"><?php echo $products_price; ?></h1>
  <h2 itemprop="itemreviewed"><?php echo $products_name; ?></h2>
</div>

<div class="contentContainer">

<?php
$average_query = tep_db_query("select AVG(r.reviews_rating) as average, COUNT(r.reviews_rating) as count from " . TABLE_REVIEWS . " r where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_status = 1");
$average = tep_db_fetch_array($average_query);
//echo '<div class="col-sm-8 text-center alert alert-success">' . sprintf(REVIEWS_TEXT_AVERAGE, tep_output_string_protected($average['count']), tep_draw_stars(tep_output_string_protected(round($average['average'])))) . '</div>';
echo '<div class="col-sm-8 text-center alert alert-success" itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating"><meta itemprop="average" content="' . (int)round($average['average']) . '" /><meta itemprop="best" content="5" />' . sprintf(REVIEWS_TEXT_AVERAGE, tep_output_string_protected($average['count']), tep_draw_stars(tep_output_string_protected(round($average['average'])), true)) . '</div>';
  if (tep_not_null($product_info['products_image'])) {
?>

  <div class="col-sm-4 text-center">
    <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '</a>'; ?>
<?php
// START Added for the purchase feature option
    if ($product_info['products_purchase'] == '1')  { 
?>
    <br />
    <p><?php echo tep_draw_button(IMAGE_BUTTON_IN_CART, 'shopping-cart', tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now'), null, '', 'btn-success'); ?></p>
<?php
   }
?>

  </div>
  
  <div class="clearfix"></div>

  <hr>
  
  <div class="clearfix"></div>

<?php
  }

//  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating,      r.date_added,                r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.reviews_status = 1 order by r.reviews_id desc";
  $reviews_query_raw = "select r.reviews_id, rd.reviews_text,                            r.reviews_rating, date(r.date_added) as date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.reviews_status = 1 order by r.reviews_rating desc";
  
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>
<div class="row">
  <div class="col-sm-6 pagenumber hidden-xs">
    <?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>
  </div>
  <div class="col-sm-6">
    <span class="pull-right pagenav"><ul class="pagination"><?php echo $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></ul></span>
    <span class="pull-right"><?php echo TEXT_RESULT_PAGE; ?></span>
  </div>
</div>
<?php
    }
?>

    <div class="reviews">
<?php
    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
      $review_name = tep_output_string_protected($reviews['customers_name']);
?>
<!--      <blockquote class="col-sm-6">
        <p><?php echo tep_output_string_protected($reviews['reviews_text']); ?></p>
        <footer><?php echo sprintf(REVIEWS_TEXT_RATED, tep_draw_stars($reviews['reviews_rating']), $review_name, $review_name); ?></footer>
-->
      <blockquote class="col-sm-6" itemscope itemtype="http://data-vocabulary.org/Review">
         <p itemprop="description"><?php echo tep_output_string_protected($reviews['reviews_text']); ?></p>
         <div class="hidden" itemprop="dtreviewed" datetime="<?php echo $reviews['date_added']; ?>"><?php echo $reviews['date_added']; ?></div>
         <div class="hidden" itemprop="itemreviewed"><?php echo $product_info['products_name']; ?></div>
         <footer><?php echo sprintf(REVIEWS_TEXT_RATED, tep_draw_stars($reviews['reviews_rating'], true), $review_name, $review_name); ?></footer>		
      </blockquote>
<?php
    }
?>
	</div>
    <div class="clearfix"></div>
<?php
  } else {
?>

<!--  <div class="contentText"> -->
    <div class="alert alert-info">
      <?php echo TEXT_NO_REVIEWS; ?>
    </div>
<!--   </div> -->

<?php
  }

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
<div class="row">
  <div class="col-sm-6 pagenumber hidden-xs">
    <?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>
  </div>
  <div class="col-sm-6">
    <span class="pull-right pagenav"><ul class="pagination"><?php echo $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></ul></span>
    <span class="pull-right"><?php echo TEXT_RESULT_PAGE; ?></span>
  </div>
</div>
<?php
  }
?>

  <br />

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_WRITE_REVIEW, 'comment', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()), 'primary', NULL, 'btn-success'); ?></span>

    <?php
    $back = sizeof($navigation->path)-2;
    if (isset($navigation->path[$back])) {
      echo tep_draw_button(IMAGE_BUTTON_BACK, 'chevron-left', tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']));
    }
    ?>
  </div>
</div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
