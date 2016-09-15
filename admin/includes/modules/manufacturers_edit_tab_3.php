<?php 
      if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
         $manufac_to_stores_array = explode(',', $mInfo->manufacturers_to_stores); // multi stores
         $manufac_to_stores_array = array_slice($manufac_to_stores_array, 1); // remove "@" from the array	 // multi stores	
	
         foreach ($_stores_name as $key => $manufac_stores) { //hide_customers_group
	       $contents_edit_manu_tab3 .= '   <div class="form-group">' . PHP_EOL ;
           $contents_edit_manu_tab3 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;
//           if (isset($mInfo->manufacturers_to_stores)) {
//               $contents_edit_manu_tab3 .= tep_draw_checkbox_field('stores_manufacturers[' . $manufac_stores['stores_id'] . ']',  $manufac_stores['stores_id'] , (isset($mInfo->manufacturers_to_stores[ $manufac_stores['stores_id']])) ? 1: 0);
//           } else {
               $contents_edit_manu_tab3 .= tep_draw_checkbox_field('stores_manufacturers[' . $manufac_stores['stores_id'] . ']',  $manufac_stores['stores_id'] , (in_array($manufac_stores['stores_id'], $manufac_to_stores_array)) ? 1: 0);
//           }
			   
//               $contents_tab5 .= '          ' .  tep_draw_checkbox_field('hide_cat[' . $customers_groups[$i]['id'] . ']',  $customers_groups[$i]['id'] , (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) ? 1: 0) . PHP_EOL  ;	
           $contents_edit_manu_tab3 .= '               <label for="' . $manufac_stores['stores_id']  . '">'. PHP_EOL  ;	
           $contents_edit_manu_tab3 .= '                       ' . $manufac_stores['stores_name']  .  PHP_EOL  ;	
           $contents_edit_manu_tab3 .= '               </label>'. PHP_EOL  ;	
           $contents_edit_manu_tab3 .= '       </div>'. PHP_EOL  ;	
           $contents_edit_manu_tab3 .= '   </div>'. PHP_EOL  ;					
         }
	  }	  
?>