<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
// bof multi stores
    $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
// BOF Enable & Disable Categories
// BOF Separate Pricing Per Customer
    $new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, p.products_price, pd.products_name, p.products_purchase from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c where c.categories_status='1' and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id 
		  and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0       and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
		  and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, p.products_price, pd.products_name, p.products_purchase from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on pd.products_id = p2c.products_id left join " . TABLE_CATEGORIES . " c using(categories_id) where c.parent_id = '" . (int)$new_products_category_id . "' and c.categories_status='1' and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id 
		  and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0       and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
		  and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }


  $num_new_products = tep_db_num_rows($new_products_query);

  // 2.3.3
  //if ($new_products_query > 0) {
  if ($num_new_products > 0) {  
  // eof 2.3.3
    $new_prods_content = NULL ;

   while ($new_products = tep_db_fetch_array($new_products_query)) {
//   for ($x = 0; $x < $no_of_new_products; $x++) {
      $pf->loadProduct( (int)$new_products['products_id'],(int)$languages_id);
      $new_prods_content .= '<div class="col-sm-6 col-md-4">';
      $new_prods_content .= '  <div class="thumbnail">';
      $new_prods_content .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
      $new_prods_content .= '    <div class="caption">';
      $new_prods_content .= '      <p class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a></p>';
      $new_prods_content .= '      <hr>';
      $new_prods_content .= '      <p class="text-center">' . '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			                                                  '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			                                                  $pf->getPriceStringShort(  ) .
				                                              '</font></span><br />' . PHP_EOL . '</p>';
      $new_prods_content .= '      <div class="text-center">';
      $new_prods_content .= '        <div class="btn-group">';
	  
//      $new_prods_content .= '          <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $new_products[$x]['products_id']) . '" class="btn btn-default" role="button">' . SMALL_IMAGE_BUTTON_VIEW . '</a>';
      $new_prods_content .=                 tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 
	                                                                     'circle-arrow-right', 
																		 tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $new_products['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-default btn-sm'); 
      if ( $new_products['products_purchase'] == '1')  { 																		 
         $new_prods_content .=                 tep_draw_button(IMAGE_BUTTON_BUY_NOW, 
	                                                                     'shopping-cart', 
																		 tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-success btn-sm');																		 
      }		
	  
//      $new_prods_content .= '          <a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products[$x]['products_id']) . '" class="btn btn-success" role="button">' . SMALL_IMAGE_BUTTON_BUY . '</a>';
      $new_prods_content .= '        </div>';
      $new_prods_content .= '      </div>';
      $new_prods_content .= '    </div>';
      $new_prods_content .= '  </div>';
      $new_prods_content .= '</div>';
    }
?>

  <h3><?php echo sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')); ?></h3>

  <div class="row">
    <?php echo $new_prods_content; ?>
  </div>

<?php
  }
?>