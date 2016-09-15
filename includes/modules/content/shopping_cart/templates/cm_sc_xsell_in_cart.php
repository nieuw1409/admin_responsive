<?php
/*
  $Id: cm_sc_xsell_in_cart.php, v1.0 20160405 Kymation$
  $Loc: catalog/includes/modules/content/shopping_cart/templates/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 James C Keebaugh

  Released under the GNU General Public License v2.0 or later
 */
?>

<!-- Start cm_sc_xsell_in_cart module -->
  <div id="cm_sc_xsell_in_cart" class="col-sm-<?php echo (int) MODULE_CONTENT_SHOPPING_CART_XSELL_CONTENT_WIDTH; ?>">
    <h3><?php echo MODULE_CONTENT_SHOPPING_CART_XSELL_TABLE_HEADING; ?></h3>

    <div class="row">
<?php
  foreach ($products_data as $product ) {
?>
      <div class="col-sm-6 col-md-<?php echo (int) MODULE_CONTENT_SHOPPING_CART_XSELL_PRODUCT_WIDTH; ?>">
        <div class="thumbnail equal-height">
          <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id']); ?>"><?php echo tep_image(DIR_WS_IMAGES . $product['products_image'], $product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></a>
          <div class="caption">
              <p class="text-center"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id']); ?>"><?php echo $product['products_name']; ?></a></p>
            <hr>
            <p class="text-center"><?php echo $currencies->display_price($product['products_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?></p>
            <div class="text-center">
              <div class="btn-group">
                <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $product['products_id']); ?>" class="btn btn-default" role="button"><?php echo SMALL_IMAGE_BUTTON_VIEW; ?></a>
                <a href="<?php echo tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $product['products_id']); ?>" class="btn btn-success" role="button"><?php echo SMALL_IMAGE_BUTTON_BUY; ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>
<?php
  }
?>
    </div>
  </div>
<!-- End cm_sc_xsell_in_cart module -->