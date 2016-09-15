<?php

	  $contents_edit_orders_tab3    .= '<div class="panel panel-primary">' . PHP_EOL; 
	  $contents_edit_orders_tab3    .= '	<div class="panel-body">' . PHP_EOL;

	  $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab3    .=            tep_draw_bs_input_field('update_billing_name', stripslashes($edit_address_order->billing['name']), ENTRY_NAME, 'input_invoice_adress_First_Name' , 'control-label col-xs-3', 'col-xs-9', 'left'	)  . PHP_EOL;
	  $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;
	  
      if (ACCOUNT_COMPANY == 'true') {	  
	     $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab3    .=            tep_draw_bs_input_field('update_billing_company', stripslashes($edit_address_order->billing['company']), ENTRY_COMPANY,    'input_invoice_adress_Company' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;
	  }

	  $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab3    .=            tep_draw_bs_input_field('update_billing_street_address', stripslashes($edit_address_order->billing['street_address']), ENTRY_STREET_ADDRESS,    'input_invoice_adress_street' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;

      if (ACCOUNT_SUBURB == 'true') {	  
	     $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab3    .=            tep_draw_bs_input_field('update_billing_suburb', stripslashes($edit_address_order->billing['suburb']), ENTRY_SUBURB,    'input_invoice_adress_suburb' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;
	  }

	  $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab3    .=            tep_draw_bs_input_field('update_billing_city', stripslashes($edit_address_order->billing['city']), ENTRY_CITY_STATE,    'input_invoice_adress_City' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
//	  $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;

//	  $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab3    .=            tep_draw_bs_pull_down_menu('update_billing_zone_id', tep_get_country_zones($edit_address_order->billing['country_id']), $edit_address_order->billing['zone_id'], null, 'input_invoice_adress_Zone', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left') . PHP_EOL;
	  $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;
	  
	  if (ACCOUNT_STATE == 'true') {	  
	     $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab3    .=            tep_draw_bs_input_field('update_billing_state', stripslashes($edit_address_order->billing['state']), null,    'input_invoice_adress_state' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;
      }	  

	  $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab3    .=            tep_draw_bs_input_field('update_billing_postcode', stripslashes($edit_address_order->billing['postcode']), ENTRY_POST_CODE,    'input_invoice_adress_postcode' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;

	  $contents_edit_orders_tab3    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab3    .=            tep_draw_bs_pull_down_menu('update_billing_country_id', tep_get_countries(),$edit_address_order->billing['country_id'], ENTRY_COUNTRY, 'input_shiping_adress_country', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left') . PHP_EOL;
	  $contents_edit_orders_tab3    .= '	  </div>' . PHP_EOL;

	  $contents_edit_orders_tab3    .= '	</div>' . PHP_EOL;
	  $contents_edit_orders_tab3    .= '</div>' . PHP_EOL;
?>	  