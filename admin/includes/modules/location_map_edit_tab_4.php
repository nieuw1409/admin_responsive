<?php 
      if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
		  
//         foreach ($_stores_name as $key => $products_stores) { //hide_customers_group
         for ($i = 0; $i < count($stores_array); $i++) {
	       $contents_edit_location_tab4 .= '   <div class="form-group">' . PHP_EOL ;
           $contents_edit_location_tab4 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;
//           if (isset($mInfo->locationmap_id)) {
//               $contents_edit_location_tab4 .= tep_draw_checkbox_field('stores_location_map[' . $stores_array[$i]['id'] . ']',  $stores_array[$i]['id']  );
//           } else {
               $contents_edit_location_tab4 .= tep_draw_checkbox_field('stores_location_map[' . $stores_array[$i]['id'] . ']',  $stores_array[$i]['id'] , (in_array($stores_array[$i]['id'], $location_map_to_stores_array)) ? 1: 0);
//           }
			   
//               $contents_tab5 .= '          ' .  tep_draw_checkbox_field('hide_cat[' . $customers_groups[$i]['id'] . ']',  $customers_groups[$i]['id'] , (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) ? 1: 0) . PHP_EOL  ;	
           $contents_edit_location_tab4 .= '               <label for="' . $stores_array[$i]['id']  . '">'. PHP_EOL  ;	
           $contents_edit_location_tab4 .= '                       ' . $stores_array[$i]['text']  .  PHP_EOL  ;	
           $contents_edit_location_tab4 .= '               </label>'. PHP_EOL  ;	
           $contents_edit_location_tab4 .= '       </div>'. PHP_EOL  ;	
           $contents_edit_location_tab4 .= '   </div>'. PHP_EOL  ;					
         }
	  }	  
?>