<?php

	  $contents_edit_orders_order_totals .= '<div class="row">' . PHP_EOL;		
	  $contents_edit_orders_order_totals .= '  <div class="col-md-6">' . PHP_EOL;	  

	  $contents_edit_orders_order_totals .= '   <div class="col-xs-12">' . PHP_EOL;	  
	  $contents_edit_orders_order_totals .= '' .  tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $_GET['oID'] . '&page='. $HTTP_GET_VARS['page'])). PHP_EOL;	  
	  $contents_edit_orders_order_totals .= '' .  tep_draw_bs_button(TEXT_ADD_NEW_PRODUCT, 'plus', tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID'] . '&step=1')) . PHP_EOL;
	  $contents_edit_orders_order_totals .= '' .  tep_draw_bs_button(IMAGE_UPDATE, "save", null) . '&nbsp;&nbsp;' . PHP_EOL;
	  $contents_edit_orders_order_totals .= '   </div>' . PHP_EOL;
	  $contents_edit_orders_order_totals .= '  </div>' . PHP_EOL; 	  
	  $contents_edit_orders_order_totals .= '  <div class="col-md-6">' . PHP_EOL;	 

	  $contents_edit_orders_order_totals .= '     <table class="table table-bordered">' . PHP_EOL;	
	  $contents_edit_orders_order_totals .= '       <thead>' . PHP_EOL;			  
	  $contents_edit_orders_order_totals .= '          <th class="text-center">' . TABLE_HEADING_OT_TOTALS . '</th>' . PHP_EOL;		  
	  $contents_edit_orders_order_totals .= '          <th class="text-center">' . TABLE_HEADING_OT_VALUES . '</th>' . PHP_EOL;		  
	  $contents_edit_orders_order_totals .= '       </thead>' . PHP_EOL;		  
	  $contents_edit_orders_order_totals .= '       <tbody>' . PHP_EOL;		  
 
      for ($i=0; $i<sizeof($order->totals); $i++) {  
           $id = $order->totals[$i]['class'];
	
	       if ($order->totals[$i]['class'] == 'ot_shipping') {
	          if (tep_not_null($order->info['shipping_id'])) {
	             $shipping_module_id = $order->info['shipping_id'];
		      } else {
		         //here we could create logic to attempt to determine the shipping module used if it's not in the database
		         $shipping_module_id = '';
		      }
	       } else {
	         $shipping_module_id = '';
	       } //end if ($order->totals[$i]['class'] == 'ot_shipping') {
	 
           if ( $order->totals[$i]['title'] != '' ) {
			 
			 $read_state = '' ;			   
             if ( ($order->totals[$i]['class'] == 'ot_total') || 
			      ($order->totals[$i]['class'] == 'ot_subtotal') || 
			      ($order->totals[$i]['class'] == 'ot_subtotal_ex') || 				  
				  ($order->totals[$i]['class'] == 'ot_tax') || 
				  ($order->totals[$i]['class'] == 'ot_loworderfee') 
				 ) {
				 $read_state = ' readonly ' ;
			 }
	
  	         $contents_edit_orders_order_totals  .= '                <tr>' . PHP_EOL;
	         $contents_edit_orders_order_totals  .= '                  <td>' . PHP_EOL;
	         $contents_edit_orders_order_totals  .= '	                  <div class="form-group">' . PHP_EOL;
	         $contents_edit_orders_order_totals  .=                           tep_draw_bs_input_field('update_totals['.$i.'][title]',  trim($order->totals[$i]['title']), '',   $id.$i.'[title]' , 'col-xs-3', 'col-xs-9', 'left', null, $read_state	) . PHP_EOL; 
	         $contents_edit_orders_order_totals  .= '	                  </div>' . PHP_EOL;	  
	         $contents_edit_orders_order_totals  .= '                  </td>' . PHP_EOL;	 
		   
	         $contents_edit_orders_order_totals  .= '                  <td>' . PHP_EOL;
	         $contents_edit_orders_order_totals  .= '	                  <div class="form-group">' . PHP_EOL;
	         $contents_edit_orders_order_totals  .=                           tep_draw_bs_input_field('update_totals['. $i.'][value]',  number_format((double)$order->totals[$i]['value'], 2, '.', ''), '',   $id.$i.'[value]' , 'col-xs-3', 'col-xs-9', 'left', null, $read_state	) . PHP_EOL; 
	         $contents_edit_orders_order_totals  .=                           tep_draw_hidden_field('update_totals['.$i.'][class]', $order->totals[$i]['class'] ,  '" id="' . $id . $i.'[class]"' ) ;
             $contents_edit_orders_order_totals  .=                           tep_draw_hidden_field( 'update_totals['.$i.'][id]',    $shipping_module_id,       '" id="' . $id . $i.'[id]"'  ) ;		   
             $contents_edit_orders_order_totals  .=                           tep_draw_hidden_field( 'update_totals['.$i.'][text]',   $order->totals[$i]['text'],       '" id="' . $id . $i.'[text]"'  ) ;				 
	         $contents_edit_orders_order_totals  .= '	                  </div>' . PHP_EOL;	  
	         $contents_edit_orders_order_totals  .= '                  </td>' . PHP_EOL;	 
		   
	         $contents_edit_orders_order_totals  .= '                </tr>' . PHP_EOL;		   
           }		   
      }
		   
      if ($order->info['currency'] != DEFAULT_CURRENCY) {
 		  $contents_edit_orders_order_totals .=  '                    <tr><td>' . $order->totals[$i]['text'] . '</td></tr>' . PHP_EOL ;
	  }
		
	  $contents_edit_orders_order_totals .= '       <tbody>' . PHP_EOL;		  
	  $contents_edit_orders_order_totals .= '     </table>' . PHP_EOL;		  
	  $contents_edit_orders_order_totals .= '  </div>' . PHP_EOL; 	  
	  $contents_edit_orders_order_totals .= '</div>' . PHP_EOL; 

      echo 	  $contents_edit_orders_order_totals ;

?>
