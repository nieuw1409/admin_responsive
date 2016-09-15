<?php
	  $contents_tab_sppc_cust_group_03 .= '<br />'; 
 	  $contents_tab_sppc_cust_group_03 .= '   <div class="form-group">' . PHP_EOL ;
 	  $contents_tab_sppc_cust_group_03 .= '      <div class="radio radio-success radio-inline">' . PHP_EOL ;
      $contents_tab_sppc_cust_group_03 .=  			   tep_bs_radio_field('group_order_total_settings', '1', ENTRY_GROUP_ORDER_TOTAL_SET,     'input_Cust_Gr_order_total_Sett',   (tep_not_null($cInfo->group_order_total_allowed)? true : false ), 'radio radio-success radio-inline', '', '', 'right') ;
      $contents_tab_sppc_cust_group_03 .=  			   tep_bs_radio_field('group_order_total_settings', '0', ENTRY_GROUP_ORDER_TOTAL_DEFAULT, 'input_Cust_Gr_order_total_Sett_2', (tep_not_null($cInfo->group_order_total_allowed)? false : true ), 'radio radio-success radio-inline', '', '', 'right') ;
      $contents_tab_sppc_cust_group_03 .= '      </div>'. PHP_EOL  ;	
      $contents_tab_sppc_cust_group_03 .= '   </div>'. PHP_EOL  ;	  
	  $contents_tab_sppc_cust_group_03 .= '<br />';	
	  
 	  $contents_tab_sppc_cust_group_03 .= '   <div class="form-group">' . PHP_EOL ;
	  
      if ( $new_cust_group == false ) $order_total_allowed = explode (";",$cInfo->group_order_total_allowed);
      $order_total_module_active = explode (";",MODULE_ORDER_TOTAL_INSTALLED);
      $installed_order_total_modules = array();
      for ($i = 0, $n = sizeof($order_total_directory_array); $i < $n; $i++) {
          $file = $order_total_directory_array[$i];
          if (in_array ($order_total_directory_array[$i], $order_total_module_active)) {
            include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/order_total/' . $file);
            include($order_total_module_directory . $file);

            $order_total_class = substr($file, 0, strrpos($file, '.'));
            if (tep_class_exists($order_total_class)) {
              $order_total_module = new $order_total_class;
              if ($order_total_module->check() > 0) {
                $installed_order_total_modules[] = $file;
              }
            } // end if (tep_class_exists($order_total_class))
				
 	        $contents_tab_sppc_cust_group_03 .= '<div class="form-group">' . PHP_EOL ;			
 	        $contents_tab_sppc_cust_group_03 .= '  <div class="checkbox checkbox-success">' . PHP_EOL ;				
			$contents_tab_sppc_cust_group_03 .=        tep_bs_checkbox_field('order_total_allowed[' . $order_total_module->code.".php" . ']', $order_total_module->code.".php", $order_total_module->title, $order_total_module->code.'.order', ((in_array ($order_total_module->code.".php", $order_total_allowed)) ?  true : false), 'checkbox checkbox-success', '', '', 'right') ;
			$contents_tab_sppc_cust_group_03 .= '  </div>' . PHP_EOL ;			
			$contents_tab_sppc_cust_group_03 .= '</div>' . PHP_EOL ;					
			$contents_tab_sppc_cust_group_03 .= '<br />';			


         } // end if (in_array ($directory_array[$i], $module_active))
      } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)	  
	  
  
      $contents_tab_sppc_cust_group_03 .= '   </div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_03 .= '<br />';	  
	  
      $contents_tab_sppc_cust_group_03 .= '<div class="well mark">' . ENTRY_ORDER_TOTAL_SET_EXPLAIN . '</div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_03 .= '<br />';	  
 
?>