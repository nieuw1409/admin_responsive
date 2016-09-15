 <div class="panel panel-primary">
	<div class="panel-heading"><?php echo HEADING_TITLE_POSTCODE ; ?></div>
    <div class="panel-body">	
        <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo BOX_TITLE_POST; ?></th>
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
				  
				  if ( (isset($HTTP_GET_VARS['postcode_prefix'])) && ($HTTP_GET_VARS['postcode_prefix'] != '') ) {
					  $prefix = '"' . $HTTP_GET_VARS['postcode_prefix'] . '"';
				// multi stores	  $postcode_query = tep_db_query("SELECT count(*) AS count, customers_postcode FROM " . TABLE_ORDERS . " WHERE substring(customers_postcode,1,3) = $prefix and customers_postcode IS NOT NULL and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' GROUP BY customers_postcode ORDER BY customers_postcode DESC LIMIT 50");	  
					  $postcode_query = tep_db_query("SELECT count(*) AS count, customers_postcode FROM " . TABLE_ORDERS . " WHERE substring(customers_postcode,1,3) = $prefix and customers_postcode IS NOT NULL and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' and orders.billing_stores_id= '" . $stores_active . "' GROUP BY customers_postcode  ORDER BY customers_postcode DESC LIMIT 50");	  
					  
					  
					  while ($customers_location = tep_db_fetch_array($postcode_query)) {
							$location_contents .= '<tr class="dataTableRow"><td class="dataTableContent">' . $customers_location['customers_postcode'] . '</font>';
							if ( is_numeric($customers_location['customers_postcode']) ) {
								$location_postcode .= '&nbsp;&nbsp;<a href="' . ZIP_URL . $customers_location['customers_postcode'] . '" target="_blank">' . POST_CODE_LOOKUP . '</a>';
							}
							$location_postcode .= '</td><td class="dataTableContent">' . $customers_location['count'] . '</td></tr>';
						}
				  } else {  
				     $location_query = tep_db_query("SELECT count(*) as count, substring(customers_postcode,1,3) as customers_postcode from " . TABLE_ORDERS . " WHERE customers_postcode IS NOT NULL and date_purchased >= '$year-$startmonth-01 00:00:00' and date_purchased <= '$year-$endmonth-31 11:59:59' and orders.billing_stores_id= '" . $stores_active . "'  group by customers_postcode ORDER BY customers_postcode ASC");     
				     while ($customers_location = tep_db_fetch_array($location_query)) {
							$location_postcode .= '<tr>' . PHP_EOL ;
							$location_postcode .= '	  <td><a href="' . tep_href_link(FILENAME_STATS_ORDERS_TRACKING . '?postcode_prefix=' . $customers_location['customers_postcode'] . '&year=' . $year . '&month='. $month ) . '"><b>' . $customers_location['customers_postcode'] . 'xxx</b></a></td>' . PHP_EOL ;
							$location_postcode .= '	  <td>' . $customers_location['count'] . '</td>' . PHP_EOL ;
							$location_postcode .= '</tr>' . PHP_EOL ;
					 }
				  }          
				echo $location_postcode;
?>
			  <tbody>
		</table>
    </div> <!-- end panel body -->		
  </div> <!-- end panel -- >	