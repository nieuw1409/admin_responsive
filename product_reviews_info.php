<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
// BOF Separate Pricing per Customer
// bof multi stores
//  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
  if (isset($_SESSION['sppc_customer_group_id']) ) {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  } else {
// $customer_group_id = '0';
    $customer_group_id = _SYS_STORE_CUSTOMER_GROUP ;
// eof multi stores	
  }
// EOF Separate Pricing per Customer
  
// BOF hid product from group sppc
//  if (isset($HTTP_GET_VARS['reviews_id']) && tep_not_null($HTTP_GET_VARS['reviews_id']) && isset($HTTP_GET_VARS['products_id']) && tep_not_null($HTTP_GET_VARS['products_id'])) {
//    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.reviews_status = 1");
 if (isset($HTTP_GET_VARS['reviews_id']) && tep_not_null($HTTP_GET_VARS['reviews_id']) && isset($HTTP_GET_VARS['products_id']) && tep_not_null($HTTP_GET_VARS['products_id'])) {
  $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . TABLE_CATEGORIES . " c using(categories_id) 
       where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "'              and r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' 
	     and r.products_id = p.products_id and r.reviews_id = rd.reviews_id          and rd.languages_id = '" . (int)$languages_id . "' 
		 and find_in_set('".$customer_group_id."', p.products_hide_from_groups) = 0 and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0
		 and find_in_set('" . SYS_STORES_ID . "', c.categories_to_stores) != 0 
         and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0");
// EOF Separate Pricing Per Customer, Hide products and categories from groups
    $review_check = tep_db_fetch_array($review_check_query);

    if ($review_check['total'] < 1) {
      tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
    }
  } else {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
  }

  tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "'");

  $review_query = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.customers_name, r.date_added, r.reviews_read, p.products_id, p.products_price, p.products_purchase, p.products_tax_class_id, p.products_image, p.products_model, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.products_id = p.products_id and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '". (int)$languages_id . "'");
  $review = tep_db_fetch_array($review_query);
/*
// BOF Separate Pricing Per Customer
      if ($customer_group_id !='0') {
// BOF qbppc for sppc	  
//        $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $review['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
      $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $review['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "' and customers_group_price != null");
// EOF qbppc for sppc		
        if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
	     $review['products_price'] = $customer_group_price['customers_group_price'];
        }
      }
// EOF Separate Pricing Per Customer

  if ($new_price = tep_get_products_special_price($review['products_id'])) {
    $products_price = '<del>' . $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($review['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id']));
  }
// EOF Separate Pricing Per Customer */
  
  $pf->loadProduct((int)$review['products_id'], (int)$languages_id);
  $products_price= '        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			      '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			       $pf->getPriceStringShort( )
				   . '</font></span><br />'  ;	
  

/*** Begin Header Tags SEO 331 ***/  
//  if (tep_not_null($review['products_model'])) {
//    $products_name = $review['products_name'] . '<br /><span class="smallText">[' . $review['products_model'] . ']</span>';
//  } else {
//    $products_name = $review['products_name'];
//  }
/*** End Header Tags SEO 331 ***/

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

/*** Begin Header Tags SEO 331 ***/  
//  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
/*** End Header Tags SEO 331 ***/

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div>
  <h1 style="float: right;"><?php echo $products_price; ?></h1>
  <h1><?php echo $products_name; ?></h1>
</div>

<div class="contentContainer">

<?php
  if (tep_not_null($review['products_image'])) {
?>

  <div style="float: right; width: <?php echo SMALL_IMAGE_WIDTH+20; ?>px; text-align: center;">
    <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $review['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $review['products_image'], addslashes($review['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '</a>'; ?>
<?php
// START Added for the purchase feature option
    if ($review['products_purchase'] == '1')  { 
?>			
       <br />
       <p><?php echo tep_draw_button(IMAGE_BUTTON_IN_CART, 'shopping-cart', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now')); ?></p>
<?php
    }	   
// END Added for the purchase feature option    }		
//    <p><?php echo tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now')); </p>
?>
  </div>

<?php
  }
?>

  <div>
    <span style="float: right;"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($review['date_added'])); ?></span>
    <h2><?php echo sprintf(TEXT_REVIEW_BY, tep_output_string_protected($review['customers_name'])); ?></h2>
  </div>

  <div class="contentText">
    <?php echo tep_break_string(nl2br(tep_output_string_protected($review['reviews_text'])), 60, '-<br />') . '<br /><br /><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $review['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $review['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $review['reviews_rating'])) . '</i>'; ?>
  </div>

  <br />

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_WRITE_REVIEW, 'comment', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params(array('reviews_id'))), 'primary'); ?></span>

    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'chevron-left', tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id')))); ?>
  </div>
       <?php /*** Begin Header Tags SEO ***/
      $product_info_query = tep_db_query("select products_head_sub_text from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id=" . (int)$review['products_id']);
      if (tep_db_num_rows($product_info_query)) {
          $product_info = tep_db_fetch_array($product_info_query);
          echo '<tr><td><div class="hts_sub_text" style="padding:10px 0;">' . $product_info['products_head_sub_text'] . '</div></td></tr>';
      }

      if (HEADER_TAGS_DISPLAY_CURRENTLY_VIEWING == 'true') {
          echo '<tr><td>';
          echo '<div id="hts_viewing">' .TEXT_VIEWING;
          $header_tags_array['title'] = ($header_tags_array['title'] ?: $review['products_name']);
          echo '&nbsp;<a title="' . $header_tags_array['title'] . '" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $review['products_id'], 'NONSSL') . '">' . $header_tags_array['title'] . '</a>';
          echo '</div>';
          echo '</td></tr>';
      }
      /*** End Header Tags SEO ***/
      ?>


      <?php
       if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') {
           echo '<tr><td>';
           include(DIR_WS_MODULES . 'header_tags_social_bookmarks.php');
           echo '</td></tr>';
       }
      ?>

      <!--- END Header Tags SEO Social Bookmarks -->
</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>