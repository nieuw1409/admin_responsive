<?php

	  $contents_edit_orders_tab2    .= '<div class="panel panel-primary">' . PHP_EOL; 
	  $contents_edit_orders_tab2    .= '	<div class="panel-body">' . PHP_EOL;

	  $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab2    .=            tep_draw_bs_input_field('update_delivery_name', stripslashes($edit_address_order->delivery['name']), ENTRY_NAME, 'input_shipping_adress_First_Name' , 'control-label col-xs-3', 'col-xs-9', 'left'	)  . PHP_EOL;
	  $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;
	  
      if (ACCOUNT_COMPANY == 'true') {	  
	     $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab2    .=            tep_draw_bs_input_field('update_delivery_company', stripslashes($edit_address_order->delivery['company']), ENTRY_COMPANY,    'input_shipping_adress_Company' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;
	  }		 

	  $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab2    .=            tep_draw_bs_input_field('update_delivery_street_address', stripslashes($edit_address_order->delivery['street_address']), ENTRY_STREET_ADDRESS,    'input_shipping_adress_street' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;

      if (ACCOUNT_SUBURB == 'true') {	  
	     $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab2    .=            tep_draw_bs_input_field('update_delivery_suburb', stripslashes($edit_address_order->delivery['suburb']), ENTRY_SUBURB,    'input_shipping_adress_suburb' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;
	  }

	  $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab2    .=            tep_draw_bs_input_field('update_delivery_city', stripslashes($edit_address_order->delivery['city']), ENTRY_CITY_STATE,    'input_shipping_adress_City' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
//	  $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;

//	  $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab2    .=            tep_draw_bs_pull_down_menu('update_delivery_zone_id', tep_get_country_zones($edit_address_order->delivery['country_id']), $edit_address_order->delivery['zone_id'], null, 'input_shipping_adress_Zone', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left') . PHP_EOL;
	  $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;
	  
	  if (ACCOUNT_STATE == 'true') {	  
	     $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	     $contents_edit_orders_tab2    .=            tep_draw_bs_input_field('update_delivery_state', stripslashes($edit_address_order->delivery['state']), null,    'input_shipping_adress_state' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	     $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;
	  }

	  $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab2    .=            tep_draw_bs_input_field('update_delivery_postcode', stripslashes($edit_address_order->delivery['postcode']), ENTRY_POST_CODE,    'input_shipping_adress_postcode' , 'control-label col-xs-3', 'col-xs-9', 'left'	) . PHP_EOL;
	  $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;

	  $contents_edit_orders_tab2    .= '	  <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_tab2    .=            tep_draw_bs_pull_down_menu('update_delivery_country_id', tep_get_countries(), $edit_address_order->delivery['country_id'], ENTRY_COUNTRY, 'input_shiping_adress_country', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left') . PHP_EOL;
	  $contents_edit_orders_tab2    .= '	  </div>' . PHP_EOL;	  
	  
	  $contents_edit_orders_tab2    .= '	</div>' . PHP_EOL;
	  $contents_edit_orders_tab2    .= '</div>' . PHP_EOL;
?>	  