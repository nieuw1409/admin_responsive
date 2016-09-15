<?php
      $contents_edit_orders_change_order_status  .= '                <div class="col-xs-12 col-md-6">' . PHP_EOL;	
      $contents_edit_orders_change_order_status  .= '                   <div class="panel panel-primary">' . PHP_EOL; // begin panel
	  $contents_edit_orders_change_order_status  .= '                      <div class="panel-heading">' . TABLE_HEADING_NEW_STATUS . '</div>' . PHP_EOL; // panel heading
	  $contents_edit_orders_change_order_status  .= '                      <div class="panel-body">' . PHP_EOL; // panel body
	  
	  $contents_edit_orders_change_order_status  .= '	                        <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_change_order_status  .=                                   tep_draw_bs_pull_down_menu('status_history', $orders_statuses, $oInfo->orders_status, ENTRY_STATUS, 'id_status_history_status', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left' ) . PHP_EOL;
	  $contents_edit_orders_change_order_status  .= '	                        </div>' . PHP_EOL;	
	  
	  $contents_edit_orders_change_order_status  .= '	                        <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_change_order_status  .= 			                       tep_bs_checkbox_field('notify_history', '', ENTRY_NOTIFY_CUSTOMER, 'notify', true, 'checkbox checkbox-success', '', '', 'right') ;
	  $contents_edit_orders_change_order_status .= '                                 <br />';			
      $contents_edit_orders_change_order_status  .= '	                        </div>' . PHP_EOL;
	  
	  $contents_edit_orders_change_order_status  .= '	                        <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_change_order_status  .= 			                       tep_bs_checkbox_field('notify_comments_history', '', ENTRY_NOTIFY_COMMENTS, 'notify_comments', false, 'checkbox checkbox-success', '', '', 'right') ;
	  $contents_edit_orders_change_order_status  .= '	                        </div>' . PHP_EOL;
	  
	  $contents_edit_orders_change_order_status  .= '	                        <div class="form-group">' . PHP_EOL;
	  $contents_edit_orders_change_order_status  .=                                   tep_draw_textarea_ckeditor('comments_history', 'soft', '40', '5', '', 'id="comments"') . PHP_EOL;
	  $contents_edit_orders_change_order_status  .= '	                        </div>' . PHP_EOL;
	  
// SADESA ORDER Tracking	  
	  if ( SYS_TRACK_TRACE_DUTCH == 'true' ) {
	     $contents_edit_orders_change_order_status .= '                         <div class="form-group">' . PHP_EOL;			  
	     $contents_edit_orders_change_order_status .=                                 tep_draw_bs_input_field(track_num_history, null, TABLE_HEADING_TRACKNR, 'id_input_track_num' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_TRACKNR ) . PHP_EOL; 
	     $contents_edit_orders_change_order_status .= '                         </div>' . PHP_EOL;		
		 
	     $contents_edit_orders_change_order_status .= '                         <div class="form-group">' . PHP_EOL;			  
	     $contents_edit_orders_change_order_status .=                                 tep_draw_bs_input_field(track_pcode_history, null, TABLE_HEADING_TRACKPC, 'id_input_track_pcode' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_TRACKPC ) . PHP_EOL; 
	     $contents_edit_orders_change_order_status .= '                         </div>' . PHP_EOL;		
		 
	  }
									
	 

	  $contents_edit_orders_change_order_status  .= '                      </div>' . PHP_EOL; // endpanel body
      $contents_edit_orders_change_order_status  .= '                   </div>' . PHP_EOL;	  	  // end panel
	  $contents_edit_orders_change_order_status  .= '                </div>' . PHP_EOL;  // end div 
?>