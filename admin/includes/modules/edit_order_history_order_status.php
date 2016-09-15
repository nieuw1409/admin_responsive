<?php

      $contents_edit_orders_history_order_status  .= '                <div class="col-xs-12 col-md-6">' . PHP_EOL;	
      $contents_edit_orders_history_order_status  .= '                   <div class="panel panel-primary">' . PHP_EOL; // begin panel
	  $contents_edit_orders_history_order_status  .= '                      <div class="panel-heading">' . TEXT_TABS_EDIT_ORDER_ORDER_HIST_02 . '</div>' . PHP_EOL; // panel heading
	  $contents_edit_orders_history_order_status  .= '                      <div class="panel-body">' . PHP_EOL; // panel body
	  
	  $contents_edit_orders_history_order_status  .=  '                          <div class="panel-group" id="accordion">' . PHP_EOL;	

      $orders_history_query = tep_db_query("SELECT orders_status_history_id, orders_status_id, date_added, customer_notified, comments, track_num, track_pcode  
                                            FROM " . TABLE_ORDERS_STATUS_HISTORY . " 
									        WHERE orders_id = '" . (int)$oID . "' 
									        ORDER BY date_added");
      if (tep_db_num_rows($orders_history_query)) {
         while ($orders_history = tep_db_fetch_array($orders_history_query)) {	 

	        $contents_edit_orders_history_order_status  .=  '                          <div class="panel panel-info">' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                              <div class="panel-heading">' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                                <h4 class="panel-title">' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                                  <a data-toggle="collapse" data-parent="#accordion" href="#'. $orders_history['orders_status_history_id'] . '">' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=			                              ( $orders_status_array[$orders_history['orders_status_id']] == '' ? TEXT_NO_ORDER_STATUS : $orders_status_array[$orders_history['orders_status_id']] ) ;
	        $contents_edit_orders_history_order_status  .=                                    '</a>' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                                </h4>' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                              </div>' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                              <div id="'. $orders_history['orders_status_history_id'] . '" class="panel-collapse collapse">' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                                <div class="panel-body">' . PHP_EOL;
			
	        $contents_edit_orders_history_order_status  .=  '                                    <div class="form-group">' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=	                                          tep_bs_checkbox_field('update_comments[' . $orders_history['orders_status_history_id'] . '][delete]',  null, TABLE_HEADING_DELETE, 'do_not_delete', false, 'checkbox checkbox-success', '', '', 'right') . PHP_EOL;	
	        $contents_edit_orders_history_order_status  .=  '                                    </div>' . PHP_EOL;
			
	        $contents_edit_orders_history_order_status  .=                                       TABLE_HEADING_STATUS . ' : ' . ( $orders_status_array[$orders_history['orders_status_id']] == '' ? TEXT_NO_ORDER_STATUS : $orders_status_array[$orders_history['orders_status_id']] ) . '<br />' . PHP_EOL;

	        $contents_edit_orders_history_order_status  .=                                       TABLE_HEADING_DATE_ADDED . ' : ' . tep_datetime_short($orders_history['date_added'])  . '<br />' . PHP_EOL;

	        if ($orders_history['customer_notified'] == '1') {
	          $contents_edit_orders_history_order_status  .=                                     TABLE_HEADING_CUSTOMER_NOTIFIED . ' : ' . tep_glyphicon('ok-sign', 'success') . '<br />' . PHP_EOL;
            } else {
	          $contents_edit_orders_history_order_status  .=                                     TABLE_HEADING_CUSTOMER_NOTIFIED . ' : ' . tep_glyphicon('remove-sign', 'danger') . '<br />' . PHP_EOL;
            }						
			
            if ( SYS_TRACK_TRACE_DUTCH == 'true' ) {
	           $contents_edit_orders_history_order_status  .=                                    TABLE_HEADING_TRACKING . ' : ' .  '<a href="' . SYS_TRACK_TRACE_URLTONR . nl2br(tep_output_string_protected( $orders_history['track_num'])) . 
																										                                         SYS_TRACK_TRACE_URLTOPC . nl2br(tep_db_output($orders_history['track_pcode'])) .'" target="_blank">' . nl2br(tep_output_string_protected(nl2br(tep_db_output($orders_history['track_num'])))) . '</a>&nbsp;&nbsp;<br />'. PHP_EOL;
            }						

	        $contents_edit_orders_history_order_status  .=                                       TABLE_HEADING_COMMENTS . ' : <br /><br />' . PHP_EOL;			
	        $contents_edit_orders_history_order_status  .=  '                                    <div class="form-group">' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=	                                           tep_draw_textarea_ckeditor("update_comments[" . $orders_history['orders_status_history_id'] . "][comments]", "soft", "40", "5", "" .	tep_db_output($orders_history['comments']) . "") . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                                    </div>' . PHP_EOL;
			
	        $contents_edit_orders_history_order_status  .=  '                                </div>' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                              </div>' . PHP_EOL;
	        $contents_edit_orders_history_order_status  .=  '                          </div>' . PHP_EOL;
		 
		 }
      } else {		  
	     $contents_edit_orders_history_order_status  .=	 '<div class="alert alert-info">' . TEXT_NO_ORDER_HISTORY . '</div>' . PHP_EOL; 
      }
	  
	  $contents_edit_orders_history_order_status  .=  '                          </div>' . PHP_EOL;  // end panel-group
	  
	  $contents_edit_orders_history_order_status  .= '                      </div>' . PHP_EOL; // endpanel body
      $contents_edit_orders_history_order_status  .= '                   </div>' . PHP_EOL;	  	  // end panel
	  $contents_edit_orders_history_order_status  .= '                </div>' . PHP_EOL;  // end div 
?>