<?php
/*
  $Id: margin_report2.php,v 3.00 2008/03/16  Exp $
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  percentage margin per order
  order status filter
  order number clickable to link to the order details
   - by mr_absinthe 2008/03/16 
   
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_INCLUDES . 'template_top.php');
//  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_MARGIN_REPORT);
  $languages = tep_get_languages();
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  $store_select = (isset($HTTP_GET_VARS['stores']) ? $HTTP_GET_VARS['stores'] : 'all');
  
  // get list of order status
	$orders_statuses = array(); 
    $orders_status_array = array();
    $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_id");
        
    $orders_statuses[] = array('id' => 'all', 'text' => TEXT_ALL_STATUS);	
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
      $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
    }
  
  $date = tep_db_query("SELECT curdate() as time");
  $d2 = tep_db_fetch_array($date);

  switch($HTTP_GET_VARS['report_id']) {
	case 'all':
	  $header = TEXT_REPORT_HEADER;
	  break;
	case 'daily':
	  $date = tep_db_query("SELECT curdate() as time");
	  $d = tep_db_fetch_array($date);
	  $header = TEXT_REPORT_HEADER_FROM_DAY;
	  break;
	case 'yesterday':
	  $date = tep_db_query("SELECT DATE_SUB(curdate(), INTERVAL 1 DAY) as time");
	  $d = tep_db_fetch_array($date);
	  $l = 1;
	  $header = TEXT_REPORT_HEADER_FROM_YESTERDAY;
	  break;
	case 'weekly':
	case 'lastweek':
	  if ($HTTP_GET_VARS['report_id'] == "lastweek") {
	// last week
	  	$adjust = 7;	
	  } else {	
	  	$adjust = 0;
	  }
	  $l = 7;  // seven day window length
	  $weekday_query = tep_db_query("SELECT weekday(now()) as weekday");
	  $weekday = tep_db_fetch_array($weekday_query);
	  $day = 6+($weekday['weekday'] - 6);
	//echo $day;
		switch($day) {
		  case '0':
			$date = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+1)." DAY as time");
			$date2 = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+1-7)." DAY as time");
			break;
		  case '1':
			$date = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+2)." DAY as time");
			$date2 = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+2-7)." DAY as time");
			break;
		  case '2':
			$date = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+3)." DAY as time");
			$date2 = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+3-7)." DAY as time");
			break;
		  case '3':
			$date = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+4)." DAY as time");
			$date2 = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+4-7)." DAY as time");
			break;
		  case '4':
			$date = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+5)." DAY as time");
			$date2 = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+5-7)." DAY as time");
			break;
		  case '5':
			$date = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+6)." DAY as time");
			$date2 = tep_db_query("SELECT curdate() - INTERVAL ".($adjust+6-7)." DAY as time");
			break;
		  case '6':
			$date = tep_db_query( "SELECT curdate() - INTERVAL ".$adjust." DAY as time");
			$date2 = tep_db_query("SELECT curdate() - INTERVAL ".($adjustd-7)." DAY as time");
			break;
		}
	  $d = tep_db_fetch_array($date);
	  $d2 = tep_db_fetch_array($date2);
	  $header = TEXT_REPORT_HEADER_FROM_WEEK;
	  break;
	case 'monthly':
	  $date = tep_db_query("SELECT FROM_UNIXTIME(" . strtotime(date("F 1, Y")) . ") as time");
	  $d = tep_db_fetch_array($date);
	  $date = tep_db_query("SELECT curdate() as time");
	  $d2 = tep_db_fetch_array($date);
	  $header = TEXT_REPORT_HEADER_FROM_MONTH;
	  break;
	case 'lastmonth':
	  $date = tep_db_query("SELECT FROM_UNIXTIME(" . strtotime(date("F 1, Y")) . ") - INTERVAL 1 MONTH as time");
	  $d = tep_db_fetch_array($date);
	  $date = tep_db_query("SELECT FROM_UNIXTIME(" . strtotime(date("F 1, Y")) . ") - INTERVAL 0 MONTH as time");
	  $d2 = tep_db_fetch_array($date);
	  $header = TEXT_REPORT_HEADER_FROM_LASTMONTH;
	  break;
	case 'quarterly':
	  $quarter_query = tep_db_query("SELECT QUARTER(now()) as quarter, year(now()) as year");
	  $quarter = tep_db_fetch_array($quarter_query);
		switch($quarter['quarter']) {
		  case '1':
			$d['time'] = $quarter['year'] . '-01-01';
			break;
		  case '2':
			$d['time'] = $quarter['year'] . '-04-01';
			break;
		  case '3':
			$d['time'] = $quarter['year'] . '-07-01';
			break;
		  case '4':
			$d['time'] = $quarter['year'] . '-10-01';
			break;
		}
	  $header = TEXT_REPORT_HEADER_FROM_QUARTER;
	  break;
	case 'semiannually':
	  $year_query = tep_db_query("SELECT year(now()) as year, month(now()) as month");
	  $year = tep_db_fetch_array($year_query);
	  if ($year['month'] >= '7') {
		$d['time'] = $year['year'] . '-07-01';
	  } else {
		$d['time'] = $year['year'] . '-01-01';
	  }
	  $header = TEXT_REPORT_HEADER_FROM_SEMIYEAR;
	  break;
	case 'annually':
	  $year_query = tep_db_query("SELECT year(now()) as year");
	  $year = tep_db_fetch_array($year_query);
	  $d['time'] = $year['year'] . '-01-01';
	  $header = TEXT_REPORT_HEADER_FROM_YEAR;
	  break;
  }
  if ( $HTTP_GET_VARS['action'] == 'remove_date' ) {
    $input_begin_date = null  ; 
    $input_end_date   = null  ;  	  
  } else {
    $input_begin_date = $HTTP_GET_VARS['sdate']  ; 
    $input_end_date   = $HTTP_GET_VARS['edate']  ;  
  }	
  
  $store_select_db = '';
  if ( $store_select != 'all' ) {
	  $store_select_db = ' and billing_stores_id=' . $store_select ;
  }
  // show orders with selected status
    if (isset($HTTP_GET_VARS['status']) && is_numeric($HTTP_GET_VARS['status']) && ($HTTP_GET_VARS['status'] > 0)){
    $status = tep_db_prepare_input($HTTP_GET_VARS['status']);	
	if (isset($HTTP_GET_VARS['date']) && ($HTTP_GET_VARS['date'] == 'yes')) {
	  $header = TEXT_REPORT_BETWEEN_DAYS . $input_begin_date . TEXT_AND . $input_end_date . ': ';
	  $date_debut = explode("/", $input_begin_date);
	  $dd1 = $date_debut[2] . '-' . $date_debut[1] . '-' . $date_debut[0];
	  $date_fin = explode("/", $input_end_date);
	  $df1 = $date_fin[2] . '-' . $date_fin[1] . '-' . $date_fin[0];

	  $order_query_raw = "select orders_id from orders where date_purchased > '" . $dd1 . "' and date_purchased < '" . $df1 . "' and orders_status = '" . (int)$status . "' " . $store_select_db . " order by orders_id asc";
//	  $order_query = tep_db_query("select orders_id from orders where date_purchased > '" . $dd1 . "' and date_purchased < '" . $df1 . "' and orders_status = '" . (int)$status . "' order by orders_id asc");
	  
	} else {
	  if ($HTTP_GET_VARS['report_id'] != '1') {
		$header_date_query = tep_db_query("SELECT DATE_FORMAT('" . $d['time'] . "', '%d/%m/%Y') as date");
		$header_date = tep_db_fetch_array($header_date_query);
		$header .= $header_date['date'];
		if (isset($d2)) {
			// have date range, use it
			$order_query_raw = "select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < '" . $d2['time'] . "' and orders_status = '" . (int)$status . "' " . $store_select_db . "  order by orders_id asc"; //date_purchased > '" . $d['time'] . "' and date_purchased < '" . $d2['time'] . "' and
//			$order_query = tep_db_query("select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < '" . $d2['time'] . "' and orders_status = '" . (int)$status . "' order by orders_id asc");
			
		} else {
			// don't have a d2, business as usual
			$order_query_raw = "select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < now() and orders_status = '" . (int)$status . "' " . $store_select_db . "  order by orders_id asc"; //date_purchased > '" . $d['time'] . "' and date_purchased < now() and 
//			$order_query = tep_db_query("select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < now() and orders_status = '" . (int)$status . "' order by orders_id asc");
			
	}		
   }
  }
 }
  // show all orders		
	  elseif (isset($HTTP_GET_VARS['date']) && ($HTTP_GET_VARS['date'] == 'yes')) {
	  $header = TEXT_REPORT_BETWEEN_DAYS . $input_begin_date . TEXT_AND . $input_end_date . ': ';
	  $date_debut = explode("/", $input_begin_date);
	  $dd1 = $date_debut[2] . '-' . $date_debut[1] . '-' . $date_debut[0];
	  $date_fin = explode("/", $input_end_date);
	  $df1 = $date_fin[2] . '-' . $date_fin[1] . '-' . $date_fin[0];
	  $order_query_raw = "select orders_id from orders where date_purchased > '" . $dd1 . "' and date_purchased < '" . $df1 . "' " . $store_select_db . "  order by orders_id asc" ;
//	  $order_query = tep_db_query("select orders_id from orders where date_purchased > '" . $dd1 . "' and date_purchased < '" . $df1 . "' order by orders_id asc");
	  
	} else {
	  if ($HTTP_GET_VARS['report_id'] != '1') {
		$header_date_query = tep_db_query("SELECT DATE_FORMAT('" . $d['time'] . "', '%d/%m/%Y') as date");
		$header_date = tep_db_fetch_array($header_date_query);
		$header .= $header_date['date'];
		if (isset($d2)) {
	// have date range, use it
			$order_query_raw = "select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < '" . $d2['time'] . "' " . $store_select_db . "  order by orders_id asc";
//			$order_query = tep_db_query("select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < '" . $d2['time'] . "' order by orders_id asc");			
		} else {
	// don't have a d2, business as usual
			$order_query_raw = "select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < now()  " . $store_select_db . " order by orders_id asc";
//			$order_query = tep_db_query("select orders_id from orders where date_purchased > '" . $d['time'] . "' and date_purchased < now() order by orders_id asc");
			
	  }
	  
	  } else {
		$order_query_raw = "select orders_id from orders  " . $store_select_db . " order by orders_id asc";
//		$order_query = tep_db_query("select orders_id from orders order by orders_id asc");		
		}
	}
	
	$options = array();
	$options[] = array('id' => 'all',              'text' => TEXT_SELECT_REPORT);
	$options[] = array('id' => 'daily',            'text' => TEXT_SELECT_REPORT_DAILY);
	$options[] = array('id' => 'yesterday',        'text' => TEXT_SELECT_REPORT_YESTERDAY);
	$options[] = array('id' => 'weekly',           'text' => TEXT_SELECT_REPORT_WEEKLY);
	$options[] = array('id' => 'lastweek',         'text' => TEXT_SELECT_REPORT_LASTWEEK);
	$options[] = array('id' => 'monthly',          'text' => TEXT_SELECT_REPORT_MONTHLY);
	$options[] = array('id' => 'lastmonth',        'text' => TEXT_SELECT_REPORT_LASTMONTH);
	$options[] = array('id' => 'quarterly',        'text' => TEXT_SELECT_REPORT_QUARTERLY);
	$options[] = array('id' => 'semiannually',     'text' => TEXT_SELECT_REPORT_SEMIANNUALLY);
	$options[] = array('id' => 'annually',         'text' => TEXT_SELECT_REPORT_ANNUALLY);
	
// BOF multi stores
//    $stores_sql= ;
    $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_name");
  
    $stores_present = false ;
    if (tep_db_num_rows($stores_query) > 1) {
		$stores_present = true ;
        $stores_orders_array[] = array('id' => 'all', 'text' => TEXT_ALL_STORES);

        while ($filterlist = tep_db_fetch_array($stores_query)) {
          $stores_orders_array[] = array('id' => $filterlist['stores_id'], 'text' => $filterlist['stores_name']);
        } 
    }
// EOF multi stores		
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE  ; ?></h1> 
            <div class="col-md-10 col-xs-12">
			  <div class="row">
<?php
	              echo '  <br />'. PHP_EOL ;
	              echo '  <br />'. PHP_EOL ; 				
	              echo '  <div>'. PHP_EOL ;
                  echo '' . tep_draw_bs_form('which_report', FILENAME_MARGIN_REPORT_ORDERS, '', 'get', 'role="form"', 'id_which_report' ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;
			      echo          tep_draw_bs_pull_down_menu( 'report_id', $options, (isset($HTTP_GET_VARS['report_id']) ? $HTTP_GET_VARS['report_id'] : '1'), TEXT_SELECT_REPORT, 'report_select_which_report', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
 				  echo '    </div>' . PHP_EOL;				  
 				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 
	              echo '  </div>'. PHP_EOL ;					
	              echo '  <br />'. PHP_EOL ;
	              echo '  <br />'. PHP_EOL ; 				
	              echo '  <div>'. PHP_EOL ;
 
                  echo '' . tep_draw_bs_form('order_status', FILENAME_MARGIN_REPORT_ORDERS, '', 'get', 'role="form"', 'id_order_status' ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;
			      echo          tep_draw_bs_pull_down_menu( 'status',  $orders_statuses,(isset($HTTP_GET_VARS['status']) ? $HTTP_GET_VARS['status'] : ''), TITLE_STATUS, 'report_select_status', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"', null, true ) . PHP_EOL;
 				  echo '    </div>' . PHP_EOL;				  
				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 

	              echo '    </div>' . PHP_EOL ; 
	              echo '    <br />' . PHP_EOL ; 
 
                  if ( $stores_present == true ) {	
	                 echo '  <br />' . PHP_EOL ;				  
			         echo '  <div>'. PHP_EOL ;
                     echo '' . tep_draw_bs_form('stores', FILENAME_MARGIN_REPORT_ORDERS, '', 'get', 'role="form"' ). PHP_EOL ; 
                     echo '    <div class="form-group">' . PHP_EOL;
			         echo          tep_draw_bs_pull_down_menu( 'stores',  $stores_orders_array, $store_select, HEADING_TITLE_STORES, 'order_select_status', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"', null, true ) . PHP_EOL;
 				     echo '    </div>' . PHP_EOL;				  
				     echo          tep_hide_session_id() ;
	                 echo '   </form>' . PHP_EOL ; 
	                 echo '  </div>'. PHP_EOL ;					
	                 echo '  <br />'. PHP_EOL ;
	                 echo '  <br />'. PHP_EOL ; 
                  }				  
                  echo '  <div>' . PHP_EOL ;
	              echo '    <br /><hr><br />' . PHP_EOL ; 	
				
                  echo '' . tep_draw_bs_form('date_begin_end', FILENAME_MARGIN_REPORT_ORDERS, '', 'get', 'role="form"', 'id_set_begin_end_date' ). PHP_EOL ;
				  echo '       <div class="form-group  has-feedback text-left">' . PHP_EOL;	
				  echo '           <label for="report_date_begin" class="col-xs-3">' . TEXT_QUERY_DATES . '</label>' . PHP_EOL;	 										 
				  echo '           <div class="col-xs-4">' . PHP_EOL ;                                              // name
				  echo                                tep_draw_bs_input_date('sdate', 
				                                                           $input_begin_date,           // value
				                                                           'id="report_date_begin"',            // parameters
				                                                           null,                                                // type
				                                                           null,                                              // reinsert value
				                                                           TEXT_START_DATE                             // placeholder
					                                                      ) ; 
				  echo '           </div>' . PHP_EOL;	
 
 				  echo '           <label for="report_date_end" class="col-xs-1 text-center">' . TEXT_TO_DATE . '</label>' . PHP_EOL;	 										 
				  echo '           <div class="col-xs-4">' . PHP_EOL ;                                              // name
				  echo                                tep_draw_bs_input_date('edate', 
				                                                           $input_end_date,           // value
				                                                           'id="report_date_end"',            // parameters
				                                                           null,                                                // type
				                                                           null,                                              // reinsert value
				                                                           TEXT_END_DATE                             // placeholder
					                                                      ) ; 
				  echo '           </div>' . PHP_EOL;	
				  echo '     </div>' . PHP_EOL;	
				  echo '    <br />' . PHP_EOL;				

                  echo '    <div class="form-group">' . PHP_EOL;				  
				  echo        tep_draw_hidden_field('action', 'export') . PHP_EOL ;
				  echo        tep_draw_hidden_field('date', 'yes') . PHP_EOL ;
				  echo '    </div>' . PHP_EOL;					  
			      echo      tep_hide_session_id() ;
				  echo      tep_draw_bs_button(IMAGE_SEARCH, 'ok', null). PHP_EOL;	
				
                  if ( ( tep_not_null($input_begin_date) ) or tep_not_null($input_end_date) )	{
					echo tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_MARGIN_REPORT_ORDERS, 'action=remove_date' ), null, null, 'btn-default text-danger') ;
					
				  }
				
	              echo '    </form>' . PHP_EOL ; 
	              echo '   </div>'. PHP_EOL ; 
?>
              </div>
		  
            </div>			
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo TEXT_ORDER_ID; ?></th>
                   <th class="text-center"><?php echo TEXT_ITEMS_SOLD; ?></th>				    			   
                   <th class="text-center"> <?php echo TEXT_SALES_AMOUNT; ?></th>				   
                   <th class="text-center" ><?php echo TEXT_COST; ?></th>	   
                   <th class="text-center" ><?php echo TEXT_GROSS_PROFIT; ?></th>	
                   <th class="text-center" ><?php echo TABLE_HEADING_MARGIN_PERCENTAGE; ?></th>					   
                </tr>
              </thead>
              <tbody>
<?php	
	             
                $order_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $order_query_raw, $orders_query_numrows);
                $order_query = tep_db_query($order_query_raw);		  
				$total_price = 0;
				$total_cost = 0;
				$total_items_sold = 0;				
				
				while ($order = tep_db_fetch_array($order_query)) {
				  					
				  $order_id = $order['orders_id'];			  
				  $p = array();

					$price = 0;
					$cost = 0;
					$items_sold = 0;
					$prods_query = tep_db_query("select products_id, products_price, products_quantity, products_cost from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
					while ($prods = tep_db_fetch_array($prods_query)) {
						$p[] = array($prods['products_id'], $prods['products_price'], $prods['products_cost'], $prods['products_quantity']);
						$price = $price + ($prods['products_price'] * $prods['products_quantity']);
						$cost = $cost + ($prods['products_cost'] * $prods['products_quantity']);
						$items_sold = $items_sold + $prods['products_quantity'];
						$total_price = $total_price + ($prods['products_price'] * $prods['products_quantity']);
						$total_cost = $total_cost + ($prods['products_cost'] * $prods['products_quantity']);
						$total_items_sold = $total_items_sold + $prods['products_quantity'];
					// the following two lines will give us per order margin as well as the total margin
						$margin = tep_round((((($prods['products_price'])-($prods['products_cost']))/($prods['products_price']))*100), 0);
						$total_margin = tep_round((((($total_price)-($total_cost))/($total_price))*100), 2);		
					}
                    echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, '&oID=' . $o[$i] . '&action=edit_order') . '\'">' . PHP_EOL ;					
					
					echo '<td>' .                     $order_id .                             '</td>'. PHP_EOL ;
					echo '<td class="text-center">' . $items_sold .                           '</td>'. PHP_EOL ;					
					echo '<td class="text-center">' . $currencies->format($price) .           '</td>'. PHP_EOL ;	
					echo '<td class="text-center">' . $currencies->format($cost) .            '</td>'. PHP_EOL ;	
					echo '<td class="text-center">' . $currencies->format(($price - $cost)) . '</td>'. PHP_EOL ;						
					echo '<td class="text-center">' . $margin . '%' .                         '</td>'. PHP_EOL ;						
				  
								
				}
?>			  
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $order_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $order_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>	  
    </table>
	
<?php
	$contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
	$contents .= '          <div class="panel-heading">' . $cInfo->countries_name . '</div>' . PHP_EOL;
	$contents .= '          <div class="panel-body">' . PHP_EOL;		
	$contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
	$contents .= '                        <ul class="list-group">' . PHP_EOL;
	$contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$contents .= '                              ' . TEXT_TOTAL_ITEMS_SOLD . ' : ' . '<span class="bg-info">' .  $total_items_sold . '</span>' . PHP_EOL;
	$contents .= '                          </li>' . PHP_EOL;
    $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
	$contents .= '                              ' . TEXT_SALES_AMOUNT . ' : ' . '<span class="bg-info">' . $currencies->format($total_price) . '</span>' . PHP_EOL;
	$contents .= '                          </li>' . PHP_EOL;					
    $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
	$contents .= '                              ' . TEXT_TOTAL_COST . ' : ' . '<span class="bg-info">' . $currencies->format($total_cost) . '</span>' . PHP_EOL;
	$contents .= '                          </li>' . PHP_EOL;						                          
    $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
	$contents .= '                              ' . TEXT_TOTAL_MARGIN . ' : ' . '<span class="bg-info">' . $total_margin . '%' . '</span>' . PHP_EOL;
	$contents .= '                          </li>' . PHP_EOL;	
    $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
	$contents .= '                              ' . TEXT_TOTAL_GROSS_PROFIT . ' : ' . '<span class="bg-info">' . $currencies->format(($total_price - $total_cost)) . '</span>' . PHP_EOL;
	$contents .= '                          </li>' . PHP_EOL;											
 	$contents .= '                        </ul>' . PHP_EOL;
	$contents .= '                      </div>' . PHP_EOL;	
	$contents .= '          </div>' . PHP_EOL;
    $contents .= '       </div>' . PHP_EOL;
	
	echo $contents ;
										
   require(DIR_WS_INCLUDES . 'template_bottom.php'); 
   require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>