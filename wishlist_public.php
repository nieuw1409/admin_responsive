<?php
/*
  $Id: wishlist_public.php,v 3.0  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST);

if(!isset($_GET['public_id'])) {
  	tep_redirect(tep_href_link(FILENAME_DEFAULT));
}

  $public_id = $_GET['public_id'];

/*******************************************************************
****************** QUERY CUSTOMER INFO FROM ID *********************
*******************************************************************/

 	$customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$public_id . "'");
	$customer = tep_db_fetch_array($customer_query);

/*******************************************************************
****************** ADD PRODUCT TO SHOPPING CART ********************
*******************************************************************/

  if (isset($_POST['add_wishprod'])) {
		foreach ($_POST['add_wishprod'] as $value) {
			$product_id = tep_get_prid($value);
			$cart->add_cart($product_id, $cart->get_quantity(tep_get_uprid($product_id, $_POST['id'][$value]))+1, $_POST['id'][$value]); 
		}
  	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }


 $breadcrumb->add(NAVBAR_TITLE_WISHLIST, tep_href_link(FILENAME_WISHLIST, '', 'SSL'));
 
 require(DIR_WS_INCLUDES . 'template_top.php');
?>

<h1><?php echo $customer['customers_firstname'] . ' ' . $customer['customers_lastname'] .  HEADING_TITLE2; ?></h1>
<?php echo tep_draw_form('wishlist_form', $_PHP_SELF); ?>
<?php
  if ($messageStack->size('wishlist') > 0) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $messageStack->output('wishlist'); ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
</table>
<?php
  }

/*******************************************************************
****** QUERY THE DATABASE FOR THE CUSTOMERS WISHLIST PRODUCTS ******
*******************************************************************/

  $wishlist_query_raw = "select * from " . TABLE_WISHLIST . " where customers_id = '" . (int)$public_id . "'";
  $wishlist_split = new splitPageResults($wishlist_query_raw, MAX_DISPLAY_WISHLIST_PRODUCTS);
  $wishlist_query = tep_db_query($wishlist_split->sql_query);

?>
<!-- customer_wishlist //-->
<?php

  if (tep_db_num_rows($wishlist_query)) {

	if ($wishlist_split > 0 && (PREV_NEXT_BAR_LOCATION == '1' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
<table class="table">
  <tr>
    <td class="smallText"><?php echo $wishlist_split->display_count(TEXT_DISPLAY_NUMBER_OF_WISHLIST); ?></td>
    <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php
  }
?>
 <table class="table">
  
     <thead>
        <tr>
          <th><?php echo BOX_TEXT_IMAGE; ?></th>
          <th><?php echo BOX_TEXT_PRODUCT; ?></th>
          <th><?php echo BOX_TEXT_PRICE; ?></th>
          <th><?php echo BOX_TEXT_SELECT; ?></th>
        </tr>
      </thead>  
	  <tbody>
  <?php 

/*******************************************************************
***** LOOP THROUGH EACH PRODUCT ID TO DISPLAY IN THE WISHLIST ******
*******************************************************************/
	$i = 0;
    while ($wishlist = tep_db_fetch_array($wishlist_query)) {
    	$wishlist_id = tep_get_prid($wishlist['products_id']);

      $products_query = tep_db_query("select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_status, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from ( " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd ) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where pd.products_id = '" . tep_db_input($wishlist_id) . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by products_name");
      $products = tep_db_fetch_array($products_query);



?>
  <tr>
    <td class="text-left">
<?php
           if ($products['products_status'] != 0) echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $wishlist['products_id'], 'NONSSL') . '">';
                   echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
     if ($products['products_status'] != 0) echo '</a>';
    ?></td>
    <td class="text-left">
    <strong>
    <?php if ($products['products_status'] != 0) echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $wishlist['products_id'], 'NONSSL') . '">';
     echo $products['products_name'];
     if ($products['products_status'] != 0) echo '</a>';
      ?></strong>
    <?php

/*******************************************************************
******** THIS IS THE WISHLIST CODE FOR PRODUCT ATTRIBUTES  *********
*******************************************************************/

                  $attributes_addon_price = 0;

                  // Now get and populate product attributes
                    $wishlist_products_attributes_query = tep_db_query("select products_options_id as po, products_options_value_id as pov from " . TABLE_WISHLIST_ATTRIBUTES . " where customers_id='" . (int)$public_id . "' and products_id = '" . tep_db_input($wishlist['products_id']) . "'");
                    while ($wishlist_products_attributes = tep_db_fetch_array($wishlist_products_attributes_query)) {
                      // We now populate $id[] hidden form field with product attributes
                      echo tep_draw_hidden_field('id['.$wishlist['products_id'].']['.$wishlist_products_attributes['po'].']', $wishlist_products_attributes['pov']);
                      // And Output the appropriate attribute name
                      $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$wishlist_id . "'
                                       and pa.options_id = '" . (int)$wishlist_products_attributes['po'] . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$wishlist_products_attributes['pov'] . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
							$attributes_values = tep_db_fetch_array($attributes);

                       		if ($attributes_values['price_prefix'] == '+') {
								$attributes_addon_price += $attributes_values['options_values_price'];
                       		} else if($attributes_values['price_prefix'] == '-') {
                         		$attributes_addon_price -= $attributes_values['options_values_price'];
							}

                            // OTF contrib begins
                            if ($value == OPTIONS_VALUE_TEXT_ID) {   
                               $wish_attr_name_sql_raw = 'SELECT po.products_options_name FROM ' .
                                          TABLE_PRODUCTS_OPTIONS . ' po, ' . TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
                                                ' pa.products_id="' . (int)$wishlist_id . '" AND ' .              
                                                ' pa.options_id=po.products_options_id AND ' .
                                                ' po.language_id="' . (int)$languages_id . '" ';
			  
                                $wish_attr_name_sql = tep_db_query($wish_attr_name_sql_raw);
                                if ($wish_arr = tep_db_fetch_array($wish_attr_name_sql)) {
                                  $attributes_values['products_options_name']  = $wish_arr['products_options_name'] ;
                                }            
                                $attributes_values['products_options_values_name'] = $value ;          
                            }
							
							$att_name .= " (" . $attributes_values['products_options_name'] . ": " . $attributes_values['products_options_values_name'] . ") ";
                       		echo '<br /><small><em> ' . $attributes_values['products_options_name'] . ': ' . $attributes_values['products_options_values_name'] . '</em></small>';
                    	} // end while attributes for product

                    if (tep_not_null($products['specials_new_products_price'])) {
                       $products_price = '<del>' . $currencies->display_price($products['products_price']+$attributes_addon_price, tep_get_tax_rate($products['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($products['specials_new_products_price']+$attributes_addon_price, tep_get_tax_rate($products['products_tax_class_id'])) . '</span>';
                    } else {
                       $products_price = $currencies->display_price($products['products_price']+$attributes_addon_price, tep_get_tax_rate($products['products_tax_class_id']));
                    }

/*******************************************************************
******* CHECK TO SEE IF PRODUCT HAS BEEN ADDED TO THEIR CART *******
*******************************************************************/

		if($cart->in_cart($wishlist[products_id])) {
			echo '<br /><strong style="color: red">Item in Cart</strong>';
		}

/*******************************************************************
********** CHECK TO SEE IF PRODUCT IS NO LONGER AVAILABLE **********
*******************************************************************/

		if($products['products_status'] == 0) {
			echo '<br /><strong style="color: red">' . TEXT_ITEM_NOT_AVAILABLE . '</strong>';
		}

	$i++;
?>
      </td>
      <td class="text-left" ><?php echo $products_price; ?></td>
      <td class="text-left"><?php

/*******************************************************************
* PREVENT THE ITEM FROM BEING ADDED TO CART IF NO LONGER AVAILABLE *
*******************************************************************/

			if($products['products_status'] != 0) {
				echo tep_draw_checkbox_field('add_wishprod[]',$wishlist_id);
			}
?>
       </td>
    </tr>
	</tbody>
<?php
		}
?>
</table>
</td>
</tr>
<tr>
  <td align="right"><br />
    <?php echo tep_draw_button(BUTTON_TEXT_ADD_CART, 'shopping-cart', null, 'primary'); ?></td>
</tr>
<tr>
  <td><?php
  if ($wishlist_split > 0 && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText"><?php echo $wishlist_split->display_count(TEXT_DISPLAY_NUMBER_OF_WISHLIST); ?></td>
        <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
      </tr>
    </table>
    </form>
    <?php
	}
?></td>
</tr>
</table>
<?php
} else { // Nothing in the customers wishlist

?>
<div class="contentContainer">
  <div class="panel panel-default panel-alert">
      <div class="panel-body">
	    <div class="well well-default">
 	       <?php echo BOX_TEXT_NO_ITEMS; ?>
        </div>
		
<!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
            <?php echo BUTTON_HELP; ?>
        </button>

<!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog">
              <div class="modal-content">
                 <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title" id="myModalLabel"><?php echo HELP_HEADING_TITLE; ?></h4>
                 </div>
                 <div class="modal-body">
                       <?php echo TEXT_INFORMATION; ?>
                 </div>
                 <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo IMAGE_BUTTON_CLOSE ; ?></button>
                 </div>
               </div>
            </div>
         </div>	<!-- end modal -->
	  </div>
  </div>	  
  <div class="buttonSet"> <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?></span> </div>
</div>
<?php
	}
?>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
