<?php
	  $contents_tab_sppc_cust_group_04 .= '<br />'; 
 	  $contents_tab_sppc_cust_group_04 .= '   <div class="form-group">' . PHP_EOL ;
 	  $contents_tab_sppc_cust_group_04 .= '      <div class="radio radio-success radio-inline">' . PHP_EOL ;
      $contents_tab_sppc_cust_group_04 .=  			   tep_bs_radio_field('group_tax_rate_exempt_settings', '1', ENTRY_GROUP_TAX_RATES_EXEMPT,  'input_Cust_Gr_tax_exempt_Sett',   (tep_not_null($cInfo->group_specific_taxes_exempt)? true : false ), 'radio radio-success radio-inline', '', '', 'right') ;
      $contents_tab_sppc_cust_group_04 .=  			   tep_bs_radio_field('group_tax_rate_exempt_settings', '0', ENTRY_GROUP_TAX_RATES_DEFAULT, 'input_Cust_Gr_tax_exempt_Sett_2', (tep_not_null($cInfo->group_specific_taxes_exempt)? false : true ), 'radio radio-success radio-inline', '', '', 'right') ;
      $contents_tab_sppc_cust_group_04 .= '      </div>'. PHP_EOL  ;	
      $contents_tab_sppc_cust_group_04 .= '   </div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_04 .= '<br />';	
	  
 	  $contents_tab_sppc_cust_group_04 .= '   <div class="form-group">' . PHP_EOL ;
	  
      if ( $new_cust_group == false ) {
//		  $customers_tax_ids_exempt = explode (",",$cInfo->group_specific_taxes_exempt);
	  }
      $tax_query = tep_db_query("select tax_rates_id, tax_rate, tax_description from " . TABLE_TAX_RATES . " order by tax_rates_id");
      while ($tax_rate = tep_db_fetch_array($tax_query)) { // end if (tep_class_exists($order_total_class))
				
 	        $contents_tab_sppc_cust_group_04 .= '<div class="form-group">' . PHP_EOL ;			
 	        $contents_tab_sppc_cust_group_04 .= '  <div class="checkbox checkbox-success">' . PHP_EOL ;				
			$contents_tab_sppc_cust_group_04 .=        tep_bs_checkbox_field('group_tax_rate_exempt_id[' . $tax_rate['tax_rates_id'] . ']', $tax_rate['tax_rates_id'], $tax_rate['tax_description'], $tax_rate['tax_rates_id'].'.tax', ((in_array ($i, $group_tax_ids_exempt)) ?  true : false), 'checkbox checkbox-success', '', '', 'right') ;
			$contents_tab_sppc_cust_group_04 .= '  </div>' . PHP_EOL ;			
			$contents_tab_sppc_cust_group_04 .= '</div>' . PHP_EOL ;				
			$contents_tab_sppc_cust_group_04 .= '<br />';			


      } // end while ($tax_rate = tep_db_fetch_array($tax_query)) 
	  
  
      $contents_tab_sppc_cust_group_04 .= '   </div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_04 .= '<br />';	  
	  
      $contents_tab_sppc_cust_group_04 .= '<div class="well mark">' . ENTRY_TAX_RATES_EXEMPT_EXPLAIN . '</div>'. PHP_EOL  ;	
	  $contents_tab_sppc_cust_group_04 .= '<br />';	  
 
?>