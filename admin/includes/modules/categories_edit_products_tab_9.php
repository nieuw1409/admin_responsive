<?php 
      if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
         $products_to_stores_array = explode(',',$pInfo->products_to_stores);
         $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array
		  
         foreach ($_stores_name as $key => $products_stores) { //hide_customers_group
	       $contents_edit_prod_tab9 .= '   <div class="form-group">' . PHP_EOL ;
           $contents_edit_prod_tab9 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;
           if (isset($pInfo->stores_products)) {
               $contents_edit_prod_tab9 .= tep_draw_checkbox_field('stores_products[' . $products_stores['stores_id'] . ']',  $products_stores['stores_id'] , (isset($pInfo->stores_products[ $products_stores['stores_id']])) ? 1: 0);
           } else {
               $contents_edit_prod_tab9 .= tep_draw_checkbox_field('stores_products[' . $products_stores['stores_id'] . ']',  $products_stores['stores_id'] , (in_array($products_stores['stores_id'], $products_to_stores_array)) ? 1: 0);
           }
			   
//               $contents_tab5 .= '          ' .  tep_draw_checkbox_field('hide_cat[' . $customers_groups[$i]['id'] . ']',  $customers_groups[$i]['id'] , (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) ? 1: 0) . PHP_EOL  ;	
           $contents_edit_prod_tab9 .= '               <label for="' . $products_stores['stores_id']  . '">'. PHP_EOL  ;	
           $contents_edit_prod_tab9 .= '                       ' . $products_stores['stores_name']  .  PHP_EOL  ;	
           $contents_edit_prod_tab9 .= '               </label>'. PHP_EOL  ;	
           $contents_edit_prod_tab9 .= '       </div>'. PHP_EOL  ;	
           $contents_edit_prod_tab9 .= '   </div>'. PHP_EOL  ;					
         }
	  }	  
?>