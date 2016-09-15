 <div class="panel panel-primary">
	<div class="panel-heading"><?php echo HEADING_TITLE_COUNTRY ; ?></div>
    <div class="panel-body">	
        <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo BOX_TITLE_COUNTRY; ?></th>
                   <th class="text-center"><?php echo BOX_TITLE_ORDER_COUNT; ?></th>				    			   
                   <th class="text-center"><?php echo BOX_TITLE_ORDER_VALUE; ?></th>				   
                   <th class="text-center" ><?php echo BOX_TITLE_PCT_VALUE; ?></th>	   				   
                </tr>
              </thead>
              <tbody>
			  
<?php
				// if no year has been selected, use current year
				  if (isset($HTTP_GET_VARS['year'])) {
					  $year=$HTTP_GET_VARS['year'];
				  }
				  else {
					  $year = date('Y'); #current year
				  }

				// if selected a month but not year, assume current year    
				if ($HTTP_GET_VARS['year'] == '') {
					  $year = date('Y'); #current year
				  }      

				// if a month has been selected, set the date range for just that month
				  if (isset($HTTP_GET_VARS['month'])) {
					  $startmonth=$HTTP_GET_VARS['month'];
					  $endmonth=$startmonth;
				  }

				// if no month has been selected, we want entire year of data
				  if ($HTTP_GET_VARS['month'] == '') {
					  $startmonth=01;
					  $endmonth=12;
				  } 
				// BOF multi stores
				// $total_query = tep_db_query("select sum(value) as amount, count(value) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status");
					if ( isset($_GET['stores_id'] ) &&  $_GET['stores_id'] != '' ) {
					   $get = $_GET['stores_id'] ;
					} else {
					  $get = '' ; 	
					}	
				   
				  $store_sql = "" ;
				  if ( $stores_active != 0 ) { // 1 particular store
					$store_sql = " and orders.billing_stores_id= '" . $stores_active . "'" ; 
				  }	
				 $total_query = tep_db_query("select sum(value) as amount, count(value) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status " . $store_sql . "");

				// EOF multi stores	 
				  while  ($thetotal = tep_db_fetch_array($total_query)) {
					if ( $thetotal['count'] != 0 ) //if there are orders for this period find the totals for calculating the percentages later
					{
					 $total_orders = $thetotal['count'];
					 $total_amount = $thetotal['amount'];
					   }
				  }
				  $pcttot=0;
				  
				// BOF multi stores  
				  $store_sql = "" ;
				  if ( $stores_active != 0 ) { // 1 particular store
					$store_sql = " and orders.billing_stores_id= '" . $stores_active . "'" ; 
				  }
				  $location_query = tep_db_query("select customers_country, currency, sum(value) as amount, count(*) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status " . $store_sql . " group by customers_country order by customers_country ");
				  
				// multi stores  $location_query = tep_db_query("select customers_country, currency, sum(value) as amount, count(*) as count from " . TABLE_ORDERS . " LEFT JOIN " . TABLE_ORDERS_TOTAL . " ON orders.orders_id = orders_total.orders_id  where class = \"ot_total\" AND date_purchased >= '$year-$startmonth-01 00:00:00' AND date_purchased <= '$year-$endmonth-31 11:59:59' AND orders_status >= $o_min_status group by customers_country order by customers_country");
				  while  ($location = tep_db_fetch_array($location_query)) {
					if ( $location['count'] != 0 ) { //if there are orders for this country, print the country,count, amount and percentage of total
					   $pct = $location['amount'] * 100 / $total_amount ;
					   $amount = number_format($location['amount'],2,'.',',');
					   $pcttot += $pct;
						 $pct = number_format($pct,1,'.',',');
						 
                           $location_contents .= '<tr>' . PHP_EOL ;
                           $location_contents .= '   <td>' .                     $location['customers_country'] . '</td>' . PHP_EOL ;
                           $location_contents .= '   <td class="text-center">' . $location['count']  . '</td>' . PHP_EOL ;
                           $location_contents .= '   <td class="text-center">' . $store_currency_symbol. $amount . '</td>' . PHP_EOL ;
                           $location_contents .= '   <td class="text-center">' . $pct . '%</td>' . PHP_EOL ;
                           $location_contents .= '</tr>';
   	                }
                  }
                  echo $location_contents;
				  
                  $total_amount = number_format($total_amount,2,'.',',');
                  $pcttot = number_format($pcttot,2,'.',',');
				  
                  echo '<tr>' . PHP_EOL ;
                  echo '	<td><b>' . BOX_TOTAL . '</b></td>' . PHP_EOL ;
                  echo '	<td class="text-center">' .  $total_orders. '</td>' . PHP_EOL ;
                  echo '	<td class="text-center">' .  $store_currency_symbol . $total_amount. '</td>' . PHP_EOL ;
                  echo '	<td class="text-center">' .  $pcttot. '%</td>' . PHP_EOL ;
                  echo '</tr>';
?>	  
			  <tbody>
		</table>
    </div> <!-- end panel body -->		
  </div> <!-- end panel -- >			  