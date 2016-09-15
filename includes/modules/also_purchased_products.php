<?php
/* overtollig verwijderd
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
function getData_alsoPurcased() {
    global $languages_id,$currencies,$HTTP_GET_VARS, $pf, $PHP_SELF ;
// bof multi stores
    $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	
    
    $orders_query = tep_db_query("select p.products_id, p.products_image, p.products_purchase, pd.products_name from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . TABLE_CATEGORIES . " c using(categories_id) 
	    where opa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'             and opa.orders_id = opb.orders_id 
		  and opb.products_id != '" . (int)$HTTP_GET_VARS['products_id'] . "'            and opb.products_id = p.products_id 
		  and opb.orders_id = o.orders_id and c.categories_status='1'                    and  p.products_status = '1' 
		  and pd.products_id = p.products_id
		  and pd.language_id = '". $languages_id . "' 
		  and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0       and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
		  and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
		  group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED); 
		  
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
      $also_pur_prods_content = NULL;
	  
	  $also_pur_prods_content  = '<div class="panel panel-default panel-info">';
	  $also_pur_prods_content .= '   <div class="panel-heading">' . TEXT_ALSO_PURCHASED_PRODUCTS . '</div>' ;
	  $also_pur_prods_content .= '   <div class="panel-body">' ;	 

      while ($orders = tep_db_fetch_array($orders_query)) {

         $pf->loadProduct( (int)$orders['products_id'],(int)$languages_id);		

         $also_pur_prods_content .= '<div class="col-sm-6 col-md-4">';
         $also_pur_prods_content .= '  <div class="thumbnail">';
         $also_pur_prods_content .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
         $also_pur_prods_content .= '    <div class="caption">';
         $also_pur_prods_content .= '      <p class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></p>';
         $also_pur_prods_content .= '      <hr>';
         $also_pur_prods_content .= '      <p class="text-center">' . '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			                                           '<font size="'.PRODUCT_PRICE_SIZE.'">" />' . 
			                                           $pf->getPriceStringShort() .
				                                       '</font></span><br />' . PHP_EOL . '</p>';
         $also_pur_prods_content .= '      <div class="text-center">';
         $also_pur_prods_content .= '        <div class="btn-group">';
//         $also_pur_prods_content .= '          <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $orders['products_id']) . '" class="btn btn-default" role="button">' . SMALL_IMAGE_BUTTON_VIEW . '</a>';
//         if ( $orders['products_purchase'] == '1')  { 	  
//            $also_pur_prods_content .= '          <a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $orders['products_id']) . '" class="btn btn-success" role="button">' . SMALL_IMAGE_BUTTON_BUY . '</a>';
//         }		
         $also_pur_prods_content .= '      ' .  tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 
	                                                                     'circle-arrow-right', 
																		 tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $orders['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-sm btn-default');
	  
         if ( $orders['products_purchase'] == '1')  { 	
           $also_pur_prods_content .= '      ' . tep_draw_button(SMALL_IMAGE_BUTTON_BUY, 
	                                                                     'shopping-cart', 
																		 tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $orders['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-success btn-sm');
	  
         }	
         $also_pur_prods_content .= '        </div>';
         $also_pur_prods_content .= '      </div>';
         $also_pur_prods_content .= '    </div>';
         $also_pur_prods_content .= '  </div>';
         $also_pur_prods_content .= '</div>';  
      } // end while ($orders = tep_db_fetch_
 
	 $also_pur_prods_content .= '   </div>' ;	 // end div panel body
     $also_pur_prods_content .= '</div>';  // end div panel	  
	  
	  
	  
/*  eric	  
        $also_pur_prods_content .= '<div class="col-sm-6 col-md-4">';
        $also_pur_prods_content .= '  <div class="thumbnail">';
        $also_pur_prods_content .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
        $also_pur_prods_content .= '    <div class="caption">';
        $also_pur_prods_content .= '      <h5 class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></h5>';
        $also_pur_prods_content .= '    </div>';
        $also_pur_prods_content .= '  </div>';
        $also_pur_prods_content .= '</div>';
		
      }  // end while ($orders = tep_db_fetch_
*/	  
	 return $also_pur_prods_content ;
    }	 
  	
} // end function getData_alsoPurcased()


global $cache ;

  if (isset($HTTP_GET_VARS['products_id'])) { 
// bof multi stores
    $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	

     if (USE_CACHE == 'true')  {
	    $cache_name = 'also_purchased_box-' . $language . '-cg' . $customer_group_id . '-prod' . $HTTP_GET_VARS['products_id'] . '.-cache' ;
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		  $also_pur_prods_content =  getData_alsoPurcased() ;
		  $cache->save_cache($cache_name, $also_pur_prods_content, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	    } else {
	  	  $also_pur_prods_content = $cache->get_cache($cache_name, 'RETURN');	  
	    }  		
      } else {
        $also_pur_prods_content =  getData_alsoPurcased( ) ;
      }
/*
  if ( $also_pur_prods_content != '' ) {  
?>

  <br />  
<?php 
  echo $also_pur_prods_content; 
    }
*/	
  }
  
?>