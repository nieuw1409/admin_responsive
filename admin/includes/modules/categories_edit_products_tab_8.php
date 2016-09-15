<?php

	  $contents_edit_prod_tab8  .= '<div class="well well-info">' . sprintf( TEXT_HIDE_CATEGORIES_FROM_GROUPS, $pInfo->products_name ) . '</div>' ;
	  
      $hide_from_groups_array = explode(',',$pInfo->products_hide_from_groups);
      $hide_from_groups_array = array_slice($hide_from_groups_array, 1); // remove "@" from the array
      foreach ($_hide_customers_group as $key => $hide_customers_group) {
	     $contents_edit_prod_tab8 .= '   <div class="form-group">' . PHP_EOL ;
         $contents_edit_prod_tab8 .= '       <div class="checkbox checkbox-danger">'. PHP_EOL  ;
         if (isset($pInfo->hide)) {
            $contents_edit_prod_tab8 .= tep_draw_checkbox_field('hide[' . $hide_customers_group['customers_group_id'] . ']',  $hide_customers_group['customers_group_id'] , (isset($pInfo->hide[ $hide_customers_group['customers_group_id']])) ? 1: 0);
         } else {
	        $contents_edit_prod_tab8 .= tep_draw_checkbox_field('hide[' . $hide_customers_group['customers_group_id'] . ']',  $hide_customers_group['customers_group_id'] , (in_array($hide_customers_group['customers_group_id'], $hide_from_groups_array)) ? 1: 0);
         }
			   
//               $contents_tab5 .= '          ' .  tep_draw_checkbox_field('hide_cat[' . $customers_groups[$i]['id'] . ']',  $customers_groups[$i]['id'] , (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) ? 1: 0) . PHP_EOL  ;	
         $contents_edit_prod_tab8 .= '               <label for="' . $hide_customers_group['customers_group_id']  . '">'. PHP_EOL  ;	
         $contents_edit_prod_tab8 .= '                       ' . $hide_customers_group['customers_group_name']  .  PHP_EOL  ;	
         $contents_edit_prod_tab8 .= '               </label>'. PHP_EOL  ;	
         $contents_edit_prod_tab8 .= '       </div>'. PHP_EOL  ;	
         $contents_edit_prod_tab8 .= '   </div>'. PHP_EOL  ;					
      }		  
?>