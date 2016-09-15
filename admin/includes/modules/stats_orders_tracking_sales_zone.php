 <div class="panel panel-primary">
	<div class="panel-heading"><?php echo HEADING_TITLE_ZONE ; ?></div>
    <div class="panel-body">	
        <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo BOX_TITLE_ZONE; ?></th>
                   <th class="text-center"><?php echo BOX_TITLE_ORDER_COUNT; ?></th>				    			   				   
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
					  
				  $location_query = tep_db_query("select zone_name, zone_country_id from " . TABLE_ZONES . " order by zone_country_id DESC");    
				  while ($customers_location = tep_db_fetch_array($location_query)) {
				// multi stores    $location_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where customers_state = '". $customers_location['zone_name'] ." ' and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' ");
				// BOF multi stores  
					$store_sql = "" ;
					if ( $stores_active != 0 ) { // 1 particular store
					  $store_sql = " and orders.billing_stores_id= '" . $stores_active . "'" ; 
					}
					$location_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where customers_state = '". $customers_location['zone_name'] ." ' and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' " . $store_sql . "");
					
					$location_pending = tep_db_fetch_array($location_pending_query);
					if ( $location_pending['count'] != 0 ) //if there are orders in this zone, print the zone and the count 
					{
							$location_info .= '<tr>' . PHP_EOL ;
							$location_info .= '	  <td>' . $customers_location['zone_name'] . '</td>' . PHP_EOL ;
							$location_info .= '   <td>' . $location_pending['count'] . '</td>' . PHP_EOL ;
							$location_info .= '</tr>';
					}
				  }
				  echo $location_info;
?>
			  <tbody>
		</table>
    </div> <!-- end panel body -->		
  </div> <!-- end panel -- >	