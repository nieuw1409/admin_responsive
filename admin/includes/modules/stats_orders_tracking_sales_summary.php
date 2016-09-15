 <div class="panel panel-primary">
	<div class="panel-heading"><?php echo HEADING_TITLE_SALES_SUMMARY ; ?></div>
    <div class="panel-body">	
        <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo BOX_TITLE_DESCRIPTION; ?></th>
                   <th class="text-center"><?php echo BOX_TITLE_ORDERS; ?></th>				    			   
                   <th class="text-center"><?php echo BOX_TITLE_INCOME; ?></th>				   
                   <th class="text-center" ><?php echo BOX_TITLE_AVERAGE; ?></th>	   
                   <th class="text-center" ><?php echo BOX_TITLE_SHIPPING; ?></th>				   
                </tr>
              </thead>
              <tbody>
			  
							<tr >
								<td                   ><a href="<?php echo tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=1') ; ?>"><b><?php echo BOX_TODAY; ?> <?php echo "$mo-$today"; ?></b></a></td>
								<td                   ><a href="<?php echo tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=1') ; ?>"><b><?php echo "$today_order_count ($repeat_orders)"; ?> *<b></a></td>
								<td class="text-center"><?php echo $store_currency_symbol . $orders_today ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $today_avg ?></td>
								<td class="text-center">&nbsp;</td>
							</tr>			  
							<tr>
								<td                   ><?php echo BOX_YESTERDAY; ?> <?php echo "$yesterday_month-$yesterday"; ?></td>
								<td                   ><?php echo $yesterday_order_count ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $orders_yesterday ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $yesterday_avg ?></td>
								<td class="text-center">&nbsp;</td>
							</tr>
							<tr>
								<td                  ><?php echo BOX_DAILY_AVERAGE; ?> <?php echo $month ?></td>
								<td                  ><?php echo $daily ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $daily_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $daily_avg ?></td>
								<td class="text-center">&nbsp;</td>
							</tr>
								<tr>
								<td                  ><?php echo BOX_PROJECTION; ?> <?php echo $month ?></td>
								<td                  ><?php echo $projected ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $projected_total ?></td>
								<td class="text-center">&nbsp;</td>
								<td class="text-center">&nbsp;</td>
							</tr>
							<tr>
								<td                   class="text-center bg-info" colspan="5"><br /><span class="text-info"><?php echo BOX_TITLE_MONTH_TOTAL; ?></span></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_1_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $jan . ' (' . $jan_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jan_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jan_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jan_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_2_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $feb . ' (' . $feb_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $feb_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $feb_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $feb_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_3_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $mar . ' (' . $mar_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $mar_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $mar_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $mar_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_4_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $apr . ' (' . $apr_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $apr_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $apr_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $apr_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_5_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $may . ' (' . $may_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $may_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $may_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $may_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_6_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $jun . ' (' . $jun_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jun_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jun_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jun_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_7_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $jul . ' (' . $jul_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jul_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jul_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $jul_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_8_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $aug . ' (' . $aug_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $aug_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $aug_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $aug_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_9_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $sep . ' (' . $sep_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $sep_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $sep_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $sep_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_10_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $oct . ' (' . $oct_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $oct_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $oct_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $oct_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_11_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $nov . ' (' . $nov_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $nov_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $nov_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $nov_ship_total ?></td>
							</tr>
							<tr>
								<td                   ><?php echo MONTH_12_TITLE; ?> <?php echo $year ?></td>
								<td                   ><?php echo $dec . ' (' . $dec_repeat_total . ')'?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $dec_total ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $dec_avg ?></td>
								<td class="text-center"><?php echo $store_currency_symbol . $dec_ship_total ?></td>
							</tr>
							<tr >
								<td                   nowrap><b><?php echo BOX_TOTAL; ?> <?php echo $year ?></b></td>
								<td                  ><b><?php echo "$total_orders / $repeats"; ?> *</b></td>
								<td class="text-center"><b><?php echo $store_currency_symbol . $order ?></b></td>
								<td class="text-center"><b><?php echo $store_currency_symbol . $total ?></b></td>
								<td class="text-center"><b><?php echo $store_currency_symbol . $year_ship_total ?></b></td>
							</tr>
							<tr >
								<td                  ><b><?php echo $year ?> <?php echo BOX_PROFIT; ?> <?php echo $profit_rate_display ?>%</b></td>
								<td                   colspan="2" align="right"><b><?php echo $store_currency_symbol . $year_profit ?></b></td>
								<td                   colspan="2" align="right">&nbsp;</td>
							</tr>
							<tr>
								<td                  class="text-center bg-info" colspan="5"><br /><span class="text-info"><?php echo BOX_TITLE_CUSTOMER_INFO; ?></span></td>
							</tr>
							<tr >
								<td                  ><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS) ; ?>"><b><?php echo BOX_CUSTOMER_TOTAL; ?></b></a></td>
								<td                  ><?php echo $customer_count ?></td>
								<td                  ><a href="<?php echo tep_href_link(FILENAME_WHOS_ONLINE) ; ?>"><b><?php echo BOX_CUSTOMERS_ONLINE; ?></b></a></td>
								<td                  ><?php echo "$whos_online / $who_again"; ?> *</td>
								<td                  >&nbsp;</td>
							</tr>
							<tr >
								<td                  ><?php echo BOX_NEW_CUSTOMERS_TODAY; ?></td>
								<td                  ><?php echo $newcust ?></td>
								<td                  ><?php echo BOX_CLOSE_RATIO; ?></td>
								<td                  ><?php echo $close_ratio ?>%</td>
								<td                  >&nbsp;</td>
							</tr>
							<tr >
								<td                  ><?php echo BOX_NEW_CUSTOMERS_THIS_MONTH; ?></td>
								<td                  ><?php echo $mnewcust ?></td>
								<td                  >&nbsp;</td>
								<td                  >&nbsp;</td>
								<td                  >&nbsp;</td>
							</tr>
							<tr>
								<td                   class="text-center bg-info" colspan="5"><br /><span class="text-info"><?php echo BOX_TITLE_ORDER_STATUS; ?></span></td>
							</tr>
							<tr>
								<td class="text-center"><?php echo BOX_TITLE_STATUS; ?></td>
								<td class="text-center"><?php echo BOX_TITLE_ORDERS; ?></td>
								<td class="text-center"><?php echo BOX_TITLE_TOTAL; ?></td>
								<td class="text-center" colspan="2">&nbsp;</td>
							</tr>	
<?php
							$orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");	
							while ($orders_status = tep_db_fetch_array($orders_status_query)) {
							
							  $orders_pending_query = "select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'";
						// BOF multi stores
							  if ( $stores_active != 0 ) { // only 1 store
								 $orders_pending_query .= " and billing_stores_id = '" . $stores_active . "'";
							  }
						// EOF multi stores			  
							  $orders_pending_query = tep_db_query(  $orders_pending_query )  ;
							  $orders_pending = tep_db_fetch_array($orders_pending_query);    
							
							  $current_status = $orders_status['orders_status_id'];
							
							  $orders_total_this_status_query_raw = "select sum(ot.value) as total FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND  ot.class = 'ot_total' AND o.orders_status = $current_status" ;
						// BOF multi stores
							  if ( $stores_active != 0 ) { // only 1 store
								 $orders_total_this_status_query_raw .= " and o.billing_stores_id = '" . $stores_active  . "'";
							  }
						// EOF multi stores		
							  $orders_total_this_status_query = tep_db_query($orders_total_this_status_query_raw);
							  $orders_total_this_status = tep_db_fetch_array($orders_total_this_status_query);  
	  
	                          $orders_contents .= '<tr>' . PHP_EOL ;
	                          $orders_contents .= '   <td><a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=orders&status=' . $orders_status['orders_status_id']) . '"><b>' . $orders_status['orders_status_name'] . '</b></a></td>' . PHP_EOL ;
	                          $orders_contents .= '   <td >' . $orders_pending['count'] . '</td>' . PHP_EOL ;
	                          $orders_contents .= '   <td class="text-center>' . $store_currency_symbol . number_format($orders_total_this_status['total'],2) . '</td>' . PHP_EOL ;
	                          $orders_contents .= '   <td class="text-center" colspan="2" align="right">&nbsp;</td>' . PHP_EOL ;
	                          $orders_contents .= '   </tr>' . PHP_EOL ;  
	                       }
                           echo $orders_contents;
?>
							<tr>
								<td class="text-center bg-info" colspan="5"><br /><span class="text-info"><?php echo BOX_TITLE_GRAND_TOTAL; ?></span></td>
							</tr>
							<tr>
								<td class="text-center">           <?php echo BOX_TITLE_DESCRIPTION; ?></td>
								<td class="text-right" colspan="2"><?php echo BOX_TITLE_TOTAL; ?></td>
								<td class="text-right" colspan="2"><?php echo BOX_TITLE_AVERAGE; ?></td>
							</tr>
							<tr >
								<td class="text-center">           <b><?php echo BOX_GRAND_TOTAL; ?></b></td>
								<td class="text-right" colspan="2"><b><?php echo $store_currency_symbol . $grand_total ?></b></td>
								<td class="text-right" colspan="2">&nbsp;</td>
							</tr>
							<tr >
								<td class="text-center">           <b><?php echo BOX_GROSS_PROFIT; ?> <?php echo $profit_rate_display ?>%</b></td>
								<td class="text-right" colspan="2"><b><?php echo $store_currency_symbol . $gross_profit ?></b></td>
								<td class="text-right" colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td class="text-center">           <b><?php echo BOX_SHIPPING_TOTAL; ?></b></td>
								<td class="text-right" colspan="2"><b><?php echo $store_currency_symbol . $shipping ?></b></td>
								<td class="text-right" colspan="2"><b><?php echo $store_currency_symbol . $shipping_avg ?></b></td>
							</tr>
							<tr >
								<td><b><?php echo BOX_INVENTORY_TOTAL; ?> <?php echo $items_total ?> <?php echo BOX_ITEMS; ?></b></td>
								<td class="text-right" colspan="2"><b><?php echo $store_currency_symbol . $inventory_total ?></b></td>
								<td class="text-right" colspan="2">&nbsp;</td>
							</tr>			  
			  <tbody>
		</table>
        <div class="well">
            <p><?php echo NOTE_1; ?></p><br />
			<p><?php echo NOTE_2; ?></p><br />
			<p><?php echo NOTE_3; ?></p><br />     							  
		</div>		
    </div> <!-- end panel body -->		
  </div> <!-- end panel -- >