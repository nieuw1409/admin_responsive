<?php
/* overtollig verwijderd
$Id: xsell_products.php, v1  2002/09/11
// adapted for Separate Pricing Per Customer v4 2005/02/24

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_XSELL_PRODUCTS);

function getData_xsell() {
    global $languages_id,$currencies,$HTTP_GET_VARS, $PHP_SELF, $pf ;
//    if ((USE_CACHE == 'true') && empty($SID)) {
//	  // include currencies class and create an instance
//	  require_once(DIR_WS_CLASSES . 'currencies.php');/
//	  $currencies = new currencies();
//    }
//
// BOF Hide products and categories from groups 
// bof multi stores
//  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
    $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	
// EOF Hide products and categories from groups  
$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_price, p.products_purchase, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
	where c.categories_status='1'                        and p.products_id = p2c.products_id 
	and p2c.categories_id = c.categories_id              and xp.products_id = '" . $HTTP_GET_VARS['products_id'] . "' 
	and xp.xsell_id = p.products_id                      and p.products_id = pd.products_id 
	and pd.language_id = '" . $languages_id . "'         and p.products_status = '1' 
    and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 
	and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
	order by xp.sort_order asc limit " . MAX_DISPLAY_XSELL); 

$num_products_xsell = tep_db_num_rows($xsell_query);
$text_xsell = '' ;
if ($num_products_xsell > 0) {
     $text_xsell  = '<div class="panel panel-default panel-info">';
	 $text_xsell .= '   <div class="panel-heading">' . TEXT_XSELL_PRODUCTS . '</div>' ;
	 $text_xsell .= '   <div class="panel-body">' ;	 
 

     while ($xsell = tep_db_fetch_array($xsell_query)) {
	 
      $pf->loadProduct( (int)$xsell['products_id'],(int)$languages_id);		

      $text_xsell .= '<div class="col-sm-6 col-md-4">';
      $text_xsell .= '  <div class="thumbnail">';
      $text_xsell .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
      $text_xsell .= '    <div class="caption">';
      $text_xsell .= '      <p class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] . '</a></p>';
      $text_xsell .= '      <hr>';
//      $text_xsell .= '      <p class="text-center">' . '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
//			                                           '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
//			                                           $pf->getPriceStringShort( '<span class="">', '<span class="mark" itemprop="price">'  ) .
//				                                       '</font></span><br />' . PHP_EOL . '</p>';
      $text_xsell .= '      <p class="text-center">' . '' . 
			                                           '<font size="'.PRODUCT_PRICE_SIZE.'">' . 
			                                           $pf->getPriceStringShort( ) .
				                                       '</font></span><br />' . PHP_EOL . '</p>';													   
	  $text_xsell .= '      <div class="clearfix"></div>';													   
      $text_xsell .= '      <div class="text-center">';
      $text_xsell .= '        <div class="btn-group btn-inline">';
      $text_xsell .= '      ' .  tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 
	                                                                     'circle-arrow-right', 
																		 tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $xsell['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-sm btn-default');
	  
//      $text_xsell .= '          <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $xsell['products_id']) . '" class="btn btn-default" role="button">' . SMALL_IMAGE_BUTTON_VIEW . '</a>';
      if ( $xsell['products_purchase'] == '1')  { 	
        $text_xsell .= '      ' . tep_draw_button(SMALL_IMAGE_BUTTON_BUY, 
	                                                                     'shopping-cart', 
																		 tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $xsell['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-success btn-sm');
	  
//        $text_xsell .= '          <a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $xsell['products_id']) . '" class="btn btn-success" role="button">' . SMALL_IMAGE_BUTTON_BUY . '</a>';
      }		
      $text_xsell .= '        </div>';
      $text_xsell .= '      </div>';
      $text_xsell .= '    </div>'; 
      $text_xsell .= '  </div>';
	  $text_xsell .= '<div class="clearfix"></div>';		  
      $text_xsell .= '</div>';  
 	  
     }
 
	 $text_xsell .= '   </div>' ;	 // end div panel body
     $text_xsell .= '</div>';  // end div panel
	 return $text_xsell ;
  } 
}// end function getData_xsell()


//global $cache, $HTTP_GET_VARS ;

  
if ($HTTP_GET_VARS['products_id']) {
// BOF Hide products and categories from groups 
// bof multi stores
    $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	

// EOF Hide products and categories from groups  
     if (USE_CACHE == 'true')  {
	    $cache_name = 'xsell_box-' . $language . '-cg' . $customer_group_id . '-prod' . $HTTP_GET_VARS['products_id'] . '.-cache' ;
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		  $text_xsell =  getData_xsell() ;
		  $cache->save_cache($cache_name, $text_xsell, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	    } else {
	  	  $text_xsell = $cache->get_cache($cache_name, 'RETURN');	  
	    }  		
      } else {
        $text_xsell =  getData_xsell() ;
      }
/*	  
//  $text_xsell =  getData_xsell( $customer_group_id ) ;
  if ( $text_xsell != '' ) {
?>
   <br />
 
    <?php echo $text_xsell; ?>


<?php	 
  }  
*/  
}
?>