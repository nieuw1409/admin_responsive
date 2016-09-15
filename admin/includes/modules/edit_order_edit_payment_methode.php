<?php

	  $contents_edit_orders_tab4    .= '<div class="panel panel-primary">' . PHP_EOL; 
	  $contents_edit_orders_tab4    .= '	<div class="panel-body">' . PHP_EOL;
 
	  $contents_edit_orders_tab4    .= '	  <div class="clearfix"></div><hr>' . PHP_EOL; 
//START for payment dropdown menu use this by quick_fixer
  	  if (ORDER_EDITOR_PAYMENT_DROPDOWN == 'true') { 
		
		// Get list of all payment modules available
        $enabled_payment = array();
        $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
        $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

        if ($dir = @dir($module_directory)) {
           while ($file = $dir->read()) {
             if (!is_dir( $module_directory . $file)) {
               if (substr($file, strrpos($file, '.')) == $file_extension) {
                   $directory_array[] = $file;
               }
             }
           }
              sort($directory_array);
             $dir->close();
        }

        // For each available payment module, check if enabled
        for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
          $file = $directory_array[$i];

          include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
          include($module_directory . $file);

          $class = substr($file, 0, strrpos($file, '.'));
          if (tep_class_exists($class)) {
             $module = new $class;
             if ($module->check() > 0) {
              // If module enabled create array of titles
      	       $enabled_payment[] = array('id' => $module->title, 'text' => $module->title);
		
		      //if the payment method is the same as the payment module title then don't add it to dropdown menu
		      if ($module->title == $edit_address_order->info['payment_method']) {
			      $paymentMatchExists='true';	
		         }
              }
            }
        }
 		//just in case the payment method found in db is not the same as the payment module title then make it part of the dropdown array or else it cannot be the selected default value
		if ($paymentMatchExists !='true') {
			$enabled_payment[] = array('id' => $edit_address_order->info['payment_method'], 'text' => $edit_address_order->info['payment_method']);	
        }
        
		$enabled_payment[] = array('id' => 'Other', 'text' => 'Other');	
		//draw the dropdown menu for payment methods and default to the order value
 	    $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	    $contents_edit_orders_tab4    .=            tep_draw_bs_pull_down_menu('update_info_payment_method', $enabled_payment, $edit_address_order->info['payment_method'], ENTRY_PAYMENT_METHOD, 'update_info_payment_method', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left', 'onChange="init()"') . PHP_EOL;
	    $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;
	  }  else { //draw the input field for payment methods and default to the order value  
	    $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	    $contents_edit_orders_tab4    .=            tep_draw_bs_input_field('update_info_payment_method', stripslashes($edit_address_order->info['payment_method']), ENTRY_PAYMENT_METHOD,    'update_info_payment_method' , 'control-label col-xs-3', 'col-xs-9', 'left', 'onChange="init()"'	) . PHP_EOL;
	    $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;		  
		   
	  } //END for payment dropdown menu use this by quick_fixer 
	  
	  ///get the currency info
      reset($currencies->currencies);
      $currencies_array = array();
      while (list($key, $value) = each($currencies->currencies)) {
            $currencies_array[] = array('id' => $key, 'text' => $value['title']);
      }
	  $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab4    .=            tep_draw_bs_pull_down_menu('update_info_payment_currency', $currencies_array, $edit_address_order->info['currency'], ENTRY_CURRENCY_TYPE, 'update_info_payment_currency', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left', 'onChange="currency(this.value)"') . PHP_EOL;
	  $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;	
	  
	  $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab4    .=            tep_draw_bs_input_field('update_info_payment_currency_value', stripslashes($edit_address_order->info['currency_value']), ENTRY_CURRENCY_VALUE,    'update_info_payment_currency_value' , 'control-label col-xs-3', 'col-xs-9', 'left', '', 'readonly="readonly"'	) . PHP_EOL;
	  $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;	  
	  
	  $contents_edit_orders_tab4    .= '	  <div class="clearfix"></div><hr>' . PHP_EOL;	  
	  
	  $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab4    .=            tep_draw_bs_input_field('update_info_cc_type', stripslashes($edit_address_order->info['cc_type']), ENTRY_CREDIT_CARD_TYPE,    'update_info_payment_cc_type' , 'control-label col-xs-3', 'col-xs-9', 'left' ) . PHP_EOL;
	  $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;	 

	  $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab4    .=            tep_draw_bs_input_field('update_info_cc_owner', stripslashes($edit_address_order->info['cc_owner']), ENTRY_CREDIT_CARD_OWNER,    'update_info_payment_cc_owner' , 'control-label col-xs-3', 'col-xs-9', 'left' ) . PHP_EOL;
	  $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;	 

	  $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab4    .=            tep_draw_bs_input_field('update_info_cc_number', stripslashes($edit_address_order->info['cc_number']), ENTRY_CREDIT_CARD_NUMBER,    'update_info_payment_cc_number' , 'control-label col-xs-3', 'col-xs-9', 'left' ) . PHP_EOL;
	  $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;	 

	  $contents_edit_orders_tab4    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab4    .=            tep_draw_bs_input_field('update_info_cc_expires', stripslashes($edit_address_order->info['cc_expires']), ENTRY_CREDIT_CARD_EXPIRES,    'update_info_payment_cc_expires' , 'control-label col-xs-3', 'col-xs-9', 'left' ) . PHP_EOL;
	  $contents_edit_orders_tab4    .= '	  </div>' . PHP_EOL;	 	  

 	
	  $contents_edit_orders_tab4    .= '	</div>' . PHP_EOL;
	  $contents_edit_orders_tab4    .= '</div>' . PHP_EOL;
?>	  