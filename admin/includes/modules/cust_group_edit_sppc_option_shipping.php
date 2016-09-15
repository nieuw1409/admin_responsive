<?php
	  $contents_tab_sppc_cust_group_02 .= '<br />'; 
 	  $contents_tab_sppc_cust_group_02 .= '   <div class="form-group">' . PHP_EOL ;
 	  $contents_tab_sppc_cust_group_02 .= '      <div class="radio radio-success radio-inline">' . PHP_EOL ;
      $contents_tab_sppc_cust_group_02 .=  			   tep_bs_radio_field('group_shipment_settings', '1', ENTRY_GROUP_SHIPPING_SET,     'input_Cust_Gr_shipping_Sett',   (tep_not_null($cInfo->group_shipment_allowed)? true : false ), 'radio radio-success radio-inline', '', '', 'right') ;
      $contents_tab_sppc_cust_group_02 .=  			   tep_bs_radio_field('group_shipment_settings', '0', ENTRY_GROUP_SHIPPING_DEFAULT, 'input_Cust_Gr_shipping_Sett_2', (tep_not_null($cInfo->group_shipment_allowed)? false : true ), 'radio radio-success radio-inline', '', '', 'right') ;
      $contents_tab_sppc_cust_group_02 .= '      </div>'. PHP_EOL  ;	
      $contents_tab_sppc_cust_group_02 .= '   </div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_02 .= '<br />';	
	  
 	  $contents_tab_sppc_cust_group_02 .= '   <div class="form-group">' . PHP_EOL ;
	  
//      if ( $new_cust_group == false ) $shipment_allowed = explode (";",$cInfo->group_shipment_allowed);
      $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
      $installed_shipping_modules = array();
      for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++) {
         $file = $ship_directory_array[$i];
         if (in_array ($ship_directory_array[$i], $ship_module_active)) {
            include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
            include($ship_module_directory . $file);

            $ship_class = substr($file, 0, strrpos($file, '.'));
            if (tep_class_exists($ship_class)) {
               $ship_module = new $ship_class;
               if ($ship_module->check() > 0) {
                  $installed_shipping_modules[] = $file;
               }
            } // end if (tep_class_exists($ship_class))
				
 	        $contents_tab_sppc_cust_group_02 .= '<div class="form-group">' . PHP_EOL ;			
 	        $contents_tab_sppc_cust_group_02 .= '  <div class="checkbox checkbox-success">' . PHP_EOL ;				
			$contents_tab_sppc_cust_group_02 .=        tep_bs_checkbox_field('shipping_allowed[' . $ship_module->code.".php" . ']', $ship_module->code.".php", $ship_module->title, $ship_module->code.'.ship', ((in_array ($ship_module->code.".php", $shipment_allowed)) ?  true : false), 'checkbox checkbox-success', '', '', 'right') ;
			$contents_tab_sppc_cust_group_02 .= '  </div>' . PHP_EOL ;			
			$contents_tab_sppc_cust_group_02 .= '</div>' . PHP_EOL ;				
			$contents_tab_sppc_cust_group_02 .= '<br />';			


         } // end if (in_array ($directory_array[$i], $module_active))
      } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)	  
	  
  
      $contents_tab_sppc_cust_group_02 .= '   </div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_02 .= '<br />';	  
	  
      $contents_tab_sppc_cust_group_02 .= '<div class="well mark">' . ENTRY_SHIPPING_SET_EXPLAIN . '</div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_02 .= '<br />';	  
 
?>