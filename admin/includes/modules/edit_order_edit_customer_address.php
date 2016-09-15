<?php

	  $contents_edit_orders_tab1    .= '<div class="panel panel-primary">' . PHP_EOL; 
	  $contents_edit_orders_tab1    .= '	<div class="panel-body">' . PHP_EOL;

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_name', stripslashes($edit_address_order->customer['name']), ENTRY_NAME, 'input_Cust_adress_First_Name' , 'control-label col-xs-3', 'col-xs-9', 'left'	)  . PHP_EOL;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;
      if (ACCOUNT_COMPANY == 'true') {	  
	     $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_company', stripslashes($edit_address_order->customer['company']), ENTRY_COMPANY,    'input_Cust_adress_Company' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;
      }		 

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_street_address', stripslashes($edit_address_order->customer['street_address']), ENTRY_STREET_ADDRESS,    'input_Cust_adress_street' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;

      if (ACCOUNT_SUBURB == 'true') {	  
	     $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_suburb', stripslashes($edit_address_order->customer['suburb']), ENTRY_SUBURB,    'input_Cust_adress_suburb' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;
      }		 

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_city', stripslashes($edit_address_order->customer['city']), ENTRY_CITY_STATE,    'input_Cust_adress_City' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
//	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;

//	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_pull_down_menu('update_customer_zone_id', tep_get_country_zones($edit_address_order->customer['country_id']), $edit_address_order->customer['zone_id'], null, 'input_Cust_adress_Zone', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left') . PHP_EOL;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;

	  if (ACCOUNT_STATE == 'true') {
	     $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_state', stripslashes($edit_address_order->customer['state']), ENTRY_POST_CODE,    'input_Cust_adress_state' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;
      }		 

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_postcode', stripslashes($edit_address_order->customer['postcode']), ENTRY_POST_CODE,    'input_Cust_adress_postcode' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;
	  

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_pull_down_menu('update_customer_country_id', tep_get_countries(), $edit_address_order->customer['country_id'], ENTRY_COUNTRY, 'input_Cust_adress_country', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left') . PHP_EOL;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;	  
	  
	  $contents_edit_orders_tab1    .= '	  <div class="clearfix"></div><hr>' . PHP_EOL;	

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_telephone', stripslashes($edit_address_order->customer['telephone']), ENTRY_TELEPHONE_NUMBER,    'input_Cust_adress_Company' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=            tep_draw_bs_input_field('update_customer_email_address', stripslashes($edit_address_order->customer['email_address']), ENTRY_EMAIL_ADDRESS,    'input_Cust_adress_Company' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;

	  $contents_edit_orders_tab1    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab1    .=              tep_draw_bs_pull_down_menu('update_customer_stores_id', $stores_orders_array, $edit_address_order->info['stores_id'], ENTRY_STORES_ID,'input_Cust_Store', 'col-md-9', 'selectpicker show-tick', 'control-label col-md-3') ;
	  $contents_edit_orders_tab1    .= '	  </div>' . PHP_EOL;	  	  
	
	  $contents_edit_orders_tab1    .= '	</div>' . PHP_EOL;
	  $contents_edit_orders_tab1    .= '</div>' . PHP_EOL;
?> 