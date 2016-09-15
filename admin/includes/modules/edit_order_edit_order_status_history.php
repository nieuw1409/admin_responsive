<?php
	  $contents_edit_orders_edit_order_status    .= '<!-- begin order status history -->' . PHP_EOL;
	  
	  $contents_edit_orders_change_order_status    = '' ;
      include( DIR_WS_MODULES . 'edit_order_edit_order_status.php' ) ;	

 
	  $contents_edit_orders_history_order_status    = '' ;
      include( DIR_WS_MODULES . 'edit_order_history_order_status.php' ) ;	  					  
 
	  $contents_edit_orders_edit_order_status .= '                            <div class="col-md-12">' . PHP_EOL;
      $contents_edit_orders_edit_order_status .= $contents_edit_orders_change_order_status  ;	

	  $contents_edit_orders_edit_order_status .= $contents_edit_orders_history_order_status ;

	  $contents_edit_orders_edit_order_status .= '                            </div>' . PHP_EOL;	  
	  $contents_edit_orders_edit_order_status .= '                            <br /><br />' . PHP_EOL; 	  

	  $contents_edit_orders_edit_order_status .= '                            <div class="col-md-12">' . PHP_EOL;	  
	  $contents_edit_orders_edit_order_status .= '                           ' .  tep_draw_bs_button(IMAGE_SAVE, "ok", null) . '&nbsp;&nbsp;' . PHP_EOL;
	  $contents_edit_orders_edit_order_status .= '                           ' .  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $HTTP_GET_VARS['oID'])). PHP_EOL;
	  $contents_edit_orders_edit_order_status .= '                            </div>' . PHP_EOL;	  
	  $contents_edit_orders_edit_order_status .= '                            <br /><br />' . PHP_EOL;  
	
	  $contents_edit_orders_edit_order_status .=   $contents_edit_orders_order_status_hist_footer . PHP_EOL ;

	  
	  $contents_edit_orders_edit_order_status    .= '<!-- end order status history -->' . PHP_EOL;	  	  			 
?>