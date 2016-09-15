<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
  
  ORDER TRACKING for osCommerce v2.3.1
  Originally Created by: Kieth Waldorf (14 Sep 2003)
  
Updated by: Eric Covert
- Added repeat customer
Updated by: Alan
- Added Localization work for English and Brazilian Portugese
Updated by: Robert Hellemans (v2.2)
- Added v2.2
Updated by: Jared Call (v2.1, v2.3, v2.4, v2.5, v2.6)
- Added tracking by current order status (Admin >> Reports >> Status Tracking).
- This shows current database $$ totals (not just for this year) for orders in each order status.  For example, if you currently have 20 orders in Pending status, for $20 each, status_tracking.php will show Pending :: 20 :: $400.
- Added MySQL indexes for better performance
- Apostrophes are now stripped in Zone names.  This means that orders_tracking_zones.php now works for countries like M'Sila, Côte d'Ivoire, etc.
Updated by: Jared Call (v2.6a)
- Fixed typo in MySQL command (step 5)
Updated by: Kornie (v2.6b)
- Fixed only the select queries in orders_tracking.php, so FROM orders became FROM " . TABLE_ORDERS . " and included the german language files.
Updated by: Jared Call (v2.6c)
- Added Spanish translation
- Added fix from form (Thanks, Young!) where stats would not show up for months with only one order.
Updated by: Jared Call (v2.7)
- Added Orders Tracking by Postal Code (with US zip lookup)
- Thanks to Luqi for sponsoring this feature
- Simplified date code (old code still there, just commented)
Updated by: Jared Call (v2.7a)
- Included the correct orders_tracking_zones.php file
Updated by: David Radford (v2.8)
- Changed the way the store currency is determined
- Added orders_tracking_countries.php
- Incorporated the simplified database query suggested in the forums  
Updated by: Steel Shadow (v2.8b)
- Fixed date error causing "Yesterday" info not to work
- Changed default profit rate from 30% to 60%
Updated by: Keith W (v2.9) May 15, 2007
- Fixed repeat customers per year
- Readded estimated inventory value
- Changed % profit back to closer to national retail average (20%)
Updated by: Keith W (v2.9a) Oct 18, 2007
- Fixed todays and yesterdays sales. Previous version took the total # of orders yesterday and subtracted that from the current orders_id #. This caused it to incorrectly list todays and yesterdays sales # as oscommerce by default creates and deletes blank orders if a transaction fails hence the total # of orders may be 50 but the actual last order # from yesterday was 60 orders later because oscommerce deleted some of those orders. Now this is fixed.
Updated by: Tomcat (v2.9b) Sept 29, 2008
- Fix to orders_tracking_countries.php
Updated by: Keith W (v2.9c) Sept 29, 2008
- Two significant new features added including monthly & yearly ship charge totals and monthly repeat visitor totals.
Updated by: Andy Nguyen (andyn@microheli.com) Jan 17-2011 (v3.0)
- Fixed error: 1054 - Unknown column 'p.products_cost' in 'field list'
- Fixed Profit % not was updated
- Fixed Estimated Inventory Value was not updated
- Added Total Value of Inventory
- Added Total Items in Inventory
- Highlighted clickable items  
- Joined all Order Tracking by Country, by Zone, by Postal and by Status in 1 page
- Updated Tables format
- Cleaned up codes
- Updated order_tracking.php file to be compatible with osCommerce V2.3.1 format.
(English version only. Needs to update more languages)	
*/

  require('includes/application_top.php');

// get main currency symbol for this store
  $currency_query = tep_db_query("select symbol_left from " . TABLE_CURRENCIES . " where  code = '" . DEFAULT_CURRENCY . "' ");
  $currency_symbol_results = tep_db_fetch_array($currency_query);
  $store_currency_symbol = tep_db_output($currency_symbol_results['symbol_left']);

setlocale(LC_MONETARY, 'en_US');

function get_month($mo, $yr, $store = 0 ) {
// BOF multi store 
//    $query = "SELECT * FROM " . TABLE_ORDERS. " WHERE date_purchased LIKE \"$yr-$mo%\" and orders.billing_stores_id= '" . $store . "' ";
    $query = "SELECT * FROM " . TABLE_ORDERS. " WHERE date_purchased LIKE \"$yr-$mo%\"";

    if ( $store != 0 ) { // only 1 store
      $query .= " and billing_stores_id= '" . $store . "'" ;
	}	  	 
// EOF multi stores	
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $month=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
                $month++;
    }
    tep_db_free_result($result);
    return $month;
}

function get_order_total($mo, $yr, $store = 0 ) {
// BOF multi store 
//    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"  and orders.billing_stores_id= '" . $store . "'  ORDER by orders_id";
    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\" ";

    if ( $store != 0 ) { // only 1 store
      $query .= " and billing_stores_id= '" . $store . "'" ;
	}
    $query .= " ORDER by orders_id" ;	
// EOF multi stores	

    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $i=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           if ( $i == 0 ) {
                $first=$col_value;
                $last=$col_value;
                $i++;
           } else {
                $last=$col_value;
           }
        }
    }
    tep_db_free_result($result);

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\" and class = \"ot_total\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
                $total=$col_value;
        }
    }
    tep_db_free_result($result);
    return $total;
}

# Function get shipping charges
function get_ship_total($mo, $yr, $store = 0 ) {
// BOF multi store 
//    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"  and orders.billing_stores_id= '" . $store . "'  ORDER by orders_id";
        $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"";

    if ( $store != 0 ) { // only 1 store
      $query .= " and billing_stores_id= '" . $store . "'" ;
	}
    $query .= "  ORDER by orders_id" ;	
// EOF multi stores	

    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $i=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           if ( $i == 0 ) {
                $first=$col_value;
                $last=$col_value;
                $i++;
           } else {
                $last=$col_value;
           }
        }
    }
    tep_db_free_result($result);

    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\" and class = \"ot_shipping\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
                $total=$col_value;
        }
    }
    tep_db_free_result($result);
    return $total;
}

# Function count repeat customers
function get_repeats($mo, $yr, $store = 0 ) {
// BOF multi store 
//    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"  and orders.billing_stores_id= '" . $store . "'  ORDER by orders_id";
    $query = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yr-$mo%\"";

    if ( $store != 0 ) { // only 1 store
      $query .= " and billing_stores_id= '" . $store . "'" ;
	}
    $query .= "  ORDER by orders_id" ;	
// EOF multi stores	

    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $i=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           if ( $i == 0 ) {
                $first=$col_value;
                $last=$col_value;
                $i++;
           } else {
                $last=$col_value;
           }
        }
    }
    tep_db_free_result($result);

// BOF multi store 
//    $query = "SELECT COUNT(orders_id) as order_count, customers_id FROM " . TABLE_ORDERS . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\"  and orders.billing_stores_id= '" . $store . "'  GROUP BY customers_id";
    $query = "SELECT COUNT(orders_id) as order_count, customers_id FROM " . TABLE_ORDERS . " WHERE orders_id >= \"$first\" and  orders_id <= \"$last\"";


    if ( $store != 0 ) { // only 1 store
      $query .= " and billing_stores_id= '" . $store . "'" ;
	}
    $query .= "  GROUP BY customers_id" ;
// EOF multi stores		
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $repeats = 0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		if ($line['order_count']>1)
		{
			$total++;
		}
    }
    tep_db_free_result($result);
    return $total;
}

function get_status($type, $store = 0) {
// BOF multi store 
//    $query = "SELECT orders_status FROM " . TABLE_ORDERS . " WHERE orders_status = \"$type\" and orders.billing_stores_id= '" . $store . "' ";
    $query = "SELECT orders_status FROM " . TABLE_ORDERS . " WHERE orders_status = \"$type\"";

    if ( $store != 0 ) { // only 1 store
      $query .= " and billing_stores_id= '" . $store . "'" ;
	}
// EOF multi stores		

    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $orders_this_status=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
           $orders_this_status++;
  	}
    }
    tep_db_free_result($result);
    return $orders_this_status;
}

// BOF multi stores
    if ( isset($_GET['stores_id'] ) &&  $_GET['stores_id'] != '' ) {
       $stores_active = $_GET['stores_id'] ;
    }
// EOF multi stores	
   
# Get total value of inventory 
	$query = "SELECT products_quantity, products_price FROM " . TABLE_PRODUCTS . " WHERE products_quantity > '0'";
	$result = tep_db_query($query) or die("Query failed : " . tep_db_error());
	$inventory_total=0;    
	while ($col = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$inventory_product = ($col['products_price'] * $col['products_quantity']);
		$inventory_total = $inventory_product + $inventory_total;      		
	}		
		$inventory_total = number_format($inventory_total,2,'.',',');   
	tep_db_free_result($result);
	
# Get total items in inventory		
	$query = "SELECT sum(products_quantity) FROM " . TABLE_PRODUCTS . " WHERE products_quantity > '0'";
	$result = tep_db_query($query) or die("Query failed : " . tep_db_error());	    
	while ($col = tep_db_fetch_array($result, MYSQL_ASSOC)) {		
		foreach ($col as $col_value) {
		$items_total=$col_value;
		}
	}	
	tep_db_free_result($result);
	
# Get total number new customers for the month
	$mo = date('m');
    $year = date('Y');	
    $query = "SELECT customers_info_date_account_created FROM " . TABLE_CUSTOMERS_INFO . " WHERE customers_info_date_account_created like \"$year-$mo%\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $mnewcust=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$mnewcust++;
    }
    tep_db_free_result($result);	

# Get total dollars in orders
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_total\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $grand_total=$col_value;
        }
    }
    tep_db_free_result($result);

# Get total shipping charges
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_shipping\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $shipping=$col_value;
        }
    }
    tep_db_free_result($result);

# Get total number of customers
    $query = "SELECT * FROM " . TABLE_CUSTOMERS . "";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $customer_count=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$customer_count++;
    }
    tep_db_free_result($result);

# Get total number new customers
    $like = date('Y-m-d');
    $query = "SELECT customers_info_date_account_created FROM " . TABLE_CUSTOMERS_INFO . " WHERE customers_info_date_account_created like \"$like%\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $newcust=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$newcust++;
    }
    tep_db_free_result($result);

# Whos online
    $query = "SELECT * FROM " . TABLE_WHOS_ONLINE . "";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $whos_online=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$whos_online++;
    }
    tep_db_free_result($result);

# Whos online again
    $query = "SELECT * FROM " . TABLE_WHOS_ONLINE . " WHERE customer_id != \"0\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $who_again=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$who_again++;
    }
    tep_db_free_result($result);

# How many orders today total
    $date = date('Y-m-d'); #2003-09-07%
// BOF multi store 
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$date%\"";    
    if ( $stores_active != 0 ) { // only 1 store
      $query .= " and orders.billing_stores_id= '" . $stores_active . "'" ;
	}	  	
// EOF multi stores	
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $today_order_count=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$today_order_count++;
    }
    tep_db_free_result($result);

# How many orders yesterday
    $mo = date('m');
    $today = date('d');
    $year = date('Y');
    $last_month = $mo-1;
    if ( $last_month == 0) $last_month = 12; //if jan, then last month is dec (12th mo, not 0th month)
    $yesterday = date('d') - 1;
    if ($yesterday == "0") //today is the first day of the month, now "Thirty days hath November . . ." for the prev month
     { $first_day_of_month=1;
       if ( ($last_month == 1) OR ($last_month == 3) OR ($last_month == 5) OR ($last_month == 7) OR ($last_month == 8) OR ($last_month == 10) OR ($last_month == 12) )
          $yesterday = "31";
        elseif  ( ($last_month == 4) OR ($last_month == 6) OR ($last_month == 9) OR ($last_month == 11) )
          $yesterday = "30";

//calculate Feb end day, including leap year calculation from http://www.mitre.org/tech/cots/LEAPCALC.html
        else {
              if ( ($year % 4) != 0) $yesterday = "28";
               elseif ( ($year % 400) == 0) $yesterday = "29";
               elseif ( ($year % 100) == 0) $yesterday = "28";
               else $yesterday = "29";
              }
     }

// set $yesterday_month so that we can properly run stats for yesterday, not the first day of last month
    if ($first_day_of_month == 1)
       $yesterday_month = $last_month;
    else $yesterday_month = $mo;

// set $yesterday_year so that we can properly run stats for yesterday, not the first day of last year or this month last year
    if ( ($yesterday_month == 12) && ($first_day_of_month == 1) )
      $yesterday_year = $year - 1;
    else
      $yesterday_year = $year;

// next line to normalize $yesterday format to 2 digits
    if ($yesterday <10) {$yesterday = "0$yesterday";}
// BOF multi store 
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yesterday_year-$yesterday_month-$yesterday%\""; 
    if ( $stores_active != 0 ) { // only 1 store
      $query .= " and orders.billing_stores_id= '" . $stores_active . "'" ;
	}	  	
    $query .= " order by orders_id";
//    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yesterday_year-$yesterday_month-$yesterday%\"  and orders.billing_stores_id= '" . $store . "'  order by orders_id";
// EOF multi stores		
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $yesterday_order_count=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {

// Get first orders_id yesterday
    $yesterday_last_order_id = $line['orders_id'];
    $yesterday_order_count++;
    }
    tep_db_free_result($result);

// Get last orders_id yesterday
// BOF multi store 
    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yesterday_year-$yesterday_month-$yesterday%\"";
    if ( $stores_active != 0 ) { // only 1 store
      $query .= " and orders.billing_stores_id= '" . $stores_active . "'" ;
	}	  	
    $query .= "  order by orders_id desc";	
//    $query = "SELECT * FROM " . TABLE_ORDERS . " WHERE date_purchased LIKE \"$yesterday_year-$yesterday_month-$yesterday%\"  and orders.billing_stores_id= '" . $store . "' order by orders_id desc";
// EOF multi stores	
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
    // Get last orders_id yesterday
    $twodaysago_last_order_id = $line['orders_id'];
    }
    tep_db_free_result($result);

# Get the last order_id
    $query = "SELECT orders_id FROM " . TABLE_ORDERS_TOTAL . " WHERE class = \"ot_total\" ORDER BY orders_id ASC";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
		$latest_order_id=$col_value;
	}
    }
    tep_db_free_result($result);

# Grab the sum of all orders greater than $yesterday_last_order_id
# In other words, how much have we done so far in sales today?
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id > \"$yesterday_last_order_id\" and class = \"ot_total\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $orders_today=$col_value;
        }
    }
    tep_db_free_result($result);

# Grab the sum of all orders greater than $twodaysago_last_order_id and less than yesterday_last_order_id
# In other words, how much did we do in sales yesterday?
    $query = "SELECT sum(value) FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id >= \"$twodaysago_last_order_id\" and orders_id <= \"$yesterday_last_order_id\" and class = \"ot_total\"";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
        foreach ($line as $col_value) {
	    $orders_yesterday=$col_value;
        }
    }
    tep_db_free_result($result);

# repeat customers
    if (isset($HTTP_GET_VARS['year']) && $HTTP_GET_VARS['year'] != '') $yearRepeat=$HTTP_GET_VARS['year'];
      else $yearRepeat = date('Y'); #current year

// create array of all customers who have repeat ordered
	$repeat_custs=array();
// BOF multi store 
	$query = "SELECT COUNT(orders_id) as order_count, customers_id FROM " . TABLE_ORDERS . " WHERE date_purchased BETWEEN '" . $yearRepeat. "-01-01 00:00:00' AND '" . $yearRepeat. "-12-31 23:59:59'";
    if ( $stores_active != 0 ) { // only 1 store
      $query .= " and orders.billing_stores_id= '" . $stores_active . "'" ;
	}	  	
    $query .= " GROUP BY customers_id";
//	$query = "SELECT COUNT(orders_id) as order_count, customers_id FROM " . TABLE_ORDERS . " WHERE date_purchased BETWEEN '" . $yearRepeat. "-01-01 00:00:00' AND '" . $yearRepeat. "-12-31 23:59:59' GROUP BY customers_id";
// EOF multi stores		
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		if ($line['order_count']>1)
		{
			$repeat_custs[]=$line['customers_id'];
		}
    }
    tep_db_free_result($result);

# total repeat customers
	$repeats=count($repeat_custs);

# How many repeat orders today total
    $date = date('Y-m-d');
	$repeat_custs_string=implode(",", $repeat_custs);
    $query = "SELECT COUNT(orders_id) AS repeat_count FROM " . TABLE_ORDERS. " WHERE date_purchased LIKE \"$date%\" AND customers_id IN (\"".$repeat_custs_string."\") and orders.billing_stores_id= '" . $store . "' ";
    $result = tep_db_query($query) or die("Query failed : " . tep_db_error());
    $repeat_orders=0;
    while ($line = tep_db_fetch_array($result, MYSQL_ASSOC)) {
		$repeat_orders=$line['repeat_count'];
    }
    tep_db_free_result($result);

// if a profit rate has been entered as part of the URL, use that profit rate, else 20%
    if (isset($HTTP_GET_VARS['profit_rate']) && $HTTP_GET_VARS['profit_rate'] != '') {
	    $profit_rate=$HTTP_GET_VARS['profit_rate'];
    }
    else {
	    $profit_rate="20";
    }
    if ($profit_rate=="") {
	    $profit_rate="20";
    }
    $profit_rate_display=$profit_rate;

// divide profit_rate by 100 to get correct multiplier value
    $profit_rate = $profit_rate / 100;

# How many per month
// if a year has been entered as part of the URL, use that year instead
//  commented out and replaced by following line as per forum suggestion
//  if (isset($HTTP_GET_VARS['year'])) $year=$HTTP_GET_VARS['year'];

    if (isset($HTTP_GET_VARS['year']) && $HTTP_GET_VARS['year'] != '') $year=$HTTP_GET_VARS['year'];
	else $year = date('Y'); #current year
	
    if (isset($HTTP_GET_VARS['month']) && $HTTP_GET_VARS['month'] != '') $month=$HTTP_GET_VARS['month'];
	else $month = date('M'); #current month
	
	
    $dec = get_month("12", $year, $stores_active);
    $nov = get_month("11", $year, $stores_active);
    $oct = get_month("10", $year, $stores_active);
    $sep = get_month("09", $year, $stores_active);
    $aug = get_month("08", $year, $stores_active);
    $jul = get_month("07", $year, $stores_active);
    $jun = get_month("06", $year, $stores_active);
    $may = get_month("05", $year, $stores_active);
    $apr = get_month("04", $year, $stores_active);
    $mar = get_month("03", $year, $stores_active);
    $feb = get_month("02", $year, $stores_active);
    $jan = get_month("01", $year, $stores_active);
    $current_month = get_month($mo, $year, $stores_active);

# Only Process Month Info if Month has info to process
# Always tally totals, even if zero
# while ($i < 13)
# (
#   $month_avg = $month_total / $current_month;
#   $current_month_total = get_order_total($i, $year);
#   $order = $order + $current_month_total;
#   )
#   $i++;
$order = '';

$jan_total = get_order_total("01", $year, $stores_active);
if ($jan != 0)   $jan_avg = $jan_total / $jan;
$order = $order + $jan_total;
$jan_ship_total = get_ship_total("01", $year, $stores_active);
$year_ship_total += $jan_ship_total;
$jan_repeat_total = get_repeats("01", $year, $stores_active);

$feb_total = get_order_total("02", $year, $stores_active);
if ($feb != 0)  $feb_avg = $feb_total / $feb;
$order = $order + $feb_total;
$feb_ship_total = get_ship_total("02", $year, $stores_active);
$year_ship_total += $feb_ship_total;
$feb_repeat_total = get_repeats("02", $year, $stores_active);

$mar_total = get_order_total("03", $year, $stores_active);
if ($mar != 0)   $mar_avg = $mar_total / $mar;
$order = $order + $mar_total;
$mar_ship_total = get_ship_total("03", $year, $stores_active);
$year_ship_total += $mar_ship_total;
$mar_repeat_total = get_repeats("03", $year, $stores_active);

$apr_total = get_order_total("04", $year, $stores_active);
if ($apr != 0)   $apr_avg = $apr_total / $apr;
$order = $order + $apr_total;
$apr_ship_total = get_ship_total("04", $year, $stores_active);
$year_ship_total += $apr_ship_total;
$apr_repeat_total = get_repeats("04", $year, $stores_active);

$may_total = get_order_total("05", $year, $stores_active);
if ($may != 0)   $may_avg = $may_total / $may;
$order = $order + $may_total;
$may_ship_total = get_ship_total("05", $year, $stores_active);
$year_ship_total += $may_ship_total;
$may_repeat_total = get_repeats("05", $year, $stores_active);

$jun_total = get_order_total("06", $year, $stores_active);
if ($jun != 0)   $jun_avg = $jun_total / $jun;
$order = $order + $jun_total;
$jun_ship_total = get_ship_total("06", $year, $stores_active);
$year_ship_total += $jun_ship_total;
$jun_repeat_total = get_repeats("06", $year, $stores_active);

$jul_total = get_order_total("07", $year, $stores_active);
if ($jul != 0)   $jul_avg = $jul_total / $jul;
$order = $order + $jul_total;
$jul_ship_total = get_ship_total("07", $year, $stores_active);
$year_ship_total += $jul_ship_total;
$jul_repeat_total = get_repeats("07", $year, $stores_active);

$aug_total = get_order_total("08", $year, $stores_active);
if ($aug != 0)   $aug_avg = $aug_total / $aug;
$order = $order + $aug_total;
$aug_ship_total = get_ship_total("08", $year, $stores_active);
$year_ship_total += $aug_ship_total;
$aug_repeat_total = get_repeats("08", $year, $stores_active);

$sep_total = get_order_total("09", $year, $stores_active);
if ($sep != 0)   $sep_avg = $sep_total / $sep;
$order = $order + $sep_total;
$sep_ship_total = get_ship_total("09", $year, $stores_active);
$year_ship_total += $sep_ship_total;
$sep_repeat_total = get_repeats("09", $year, $stores_active);

$oct_total = get_order_total("10", $year, $stores_active);
if ($oct != 0)   $oct_avg = $oct_total / $oct;
$order = $order + $oct_total;
$oct_ship_total = get_ship_total("10", $year, $stores_active);
$year_ship_total += $oct_ship_total;
$oct_repeat_total = get_repeats("10", $year, $stores_active);

$nov_total = get_order_total("11", $year, $stores_active);
if ($nov != 0)   $nov_avg = $nov_total / $nov;
$order = $order + $nov_total;
$nov_ship_total = get_ship_total("11", $year, $stores_active);
$year_ship_total += $nov_ship_total;
$nov_repeat_total = get_repeats("11", $year, $stores_active);

$dec_total = get_order_total("12", $year, $stores_active);
if ($dec != 0)   $dec_avg = $dec_total / $dec;
$order = $order + $dec_total;
$dec_ship_total = get_ship_total("12", $year, $stores_active);
$year_ship_total += $dec_ship_total;
$dec_repeat_total = get_repeats("12", $year, $stores_active);

$current_month_total = get_order_total($mo, $year, $stores_active);
if ($current_month != 0)   $current_month_avg = $current_month_total / $current_month;

# Daily Averages
if ($today_order_count !=0 ) 	$today_avg = $orders_today / $today_order_count;
  else $today_avg = 0;
if ($yesterday_order_count != 0) $yesterday_avg = $orders_yesterday / $yesterday_order_count;
  else ($yesterday_avg = 0);

$daily = $current_month / $today;
$daily_total = $current_month_total / $today;

if ($daily) $daily_avg = $daily_total / $daily;
  else ($daily_avg = 0);

# Calculate days in this month for accurate sales projection
if ( ($mo == 1) OR ($mo == 3) OR ($mo == 5) OR ($mo == 7) OR ($mo == 8) OR ($mo == 10) OR ($mo == 12) )
      $days_this_month = "31";
  elseif ( ($mo == 4) OR ($mo == 6) OR ($mo == 9) OR ($mo == 11) )
           $days_this_month = "30";

//calculate Feb end day, including leap year calculation from http://www.mitre.org/tech/cots/LEAPCALC.html
    else {
          if ( ($year % 4) != 0) $days_this_month = "28";
          elseif ( ($year % 400) == 0) $days_this_month = "29";
          elseif ( ($year % 100) == 0) $days_this_month = "28";
              else $days_this_month = "29";
         }

# Projected Profits this month
$projected = $daily * $days_this_month;
$projected_total = $daily_total * $days_this_month;
$gross_profit = $grand_total * $profit_rate;
$year_profit = $order * $profit_rate;

If ($newcust != 0) $close_ratio = $today_order_count / $newcust;
  else $close_ratio = 0;

# format test into current
	$total_orders = $jan + $feb + $mar + $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dec;
if ($total_orders != 0)   $total = $order / $total_orders;
	$total = number_format($total,2,'.',',');
	$order = number_format($order,2,'.',',');
	$grand_total = number_format($grand_total,2,'.',',');
	$gross_profit = number_format($gross_profit,2,'.',',');
	$year_profit = number_format($year_profit,2,'.',',');
	$projected = number_format($projected,0,'.',',');
	$projected_total = number_format($projected_total,2,'.',',');
	$close_ratio = number_format($close_ratio,2,'.',',');
	$yesterday_avg = number_format($yesterday_avg,2,'.',',');

	$dec_total = number_format($dec_total,2,'.',',');
	$nov_total = number_format($nov_total,2,'.',',');
	$oct_total = number_format($oct_total,2,'.',',');
	$sep_total = number_format($sep_total,2,'.',',');
	$aug_total = number_format($aug_total,2,'.',',');
	$jul_total = number_format($jul_total,2,'.',',');
	$jun_total = number_format($jun_total,2,'.',',');
	$may_total = number_format($may_total,2,'.',',');
	$apr_total = number_format($apr_total,2,'.',',');
	$mar_total = number_format($mar_total,2,'.',',');
	$feb_total = number_format($feb_total,2,'.',',');
	$jan_total = number_format($jan_total,2,'.',',');

	$dec_ship_total = number_format($dec_ship_total,2,'.',',');
	$nov_ship_total = number_format($nov_ship_total,2,'.',',');
	$oct_ship_total = number_format($oct_ship_total,2,'.',',');
	$sep_ship_total = number_format($sep_ship_total,2,'.',',');
	$aug_ship_total = number_format($aug_ship_total,2,'.',',');
	$jul_ship_total = number_format($jul_ship_total,2,'.',',');
	$jun_ship_total = number_format($jun_ship_total,2,'.',',');
	$may_ship_total = number_format($may_ship_total,2,'.',',');
	$apr_ship_total = number_format($apr_ship_total,2,'.',',');
	$mar_ship_total = number_format($mar_ship_total,2,'.',',');
	$feb_ship_total = number_format($feb_ship_total,2,'.',',');
	$jan_ship_total = number_format($jan_ship_total,2,'.',',');	
	$year_ship_total = number_format($year_ship_total,2,'.',',');

	$orders_today = number_format($orders_today,2,'.',',');
	$orders_yesterday = number_format($orders_yesterday,2,'.',',');

	$dec_avg = number_format($dec_avg,2,'.',',');
	$nov_avg = number_format($nov_avg,2,'.',',');
	$oct_avg = number_format($oct_avg,2,'.',',');
	$sep_avg = number_format($sep_avg,2,'.',',');
	$aug_avg = number_format($aug_avg,2,'.',',');
	$jul_avg = number_format($jul_avg,2,'.',',');
	$jun_avg = number_format($jun_avg,2,'.',',');
	$may_avg = number_format($may_avg,2,'.',',');
	$apr_avg = number_format($apr_avg,2,'.',',');
	$mar_avg = number_format($mar_avg,2,'.',',');
	$feb_avg = number_format($feb_avg,2,'.',',');
	$jan_avg = number_format($jan_avg,2,'.',',');
	$today_avg = number_format($today_avg,2,'.',',');

if ($total_orders !=0) $shipping_avg = $shipping / $total_orders;
	else $shipping_avg = 0;

	$shipping_avg = number_format($shipping_avg,2,'.',',');
	$shipping = number_format($shipping,2,'.',',');

	$daily = number_format($daily,2,'.',',');
	$daily_total = number_format($daily_total,2,'.',',');
	$daily_avg = number_format($daily_avg,2,'.',',');
	
# Order Tracking by Country	
 // Note: all orders assumed to be in the default currency
  $o_min_status =1; //schoose minimum order status

// get default currency symbol for this store
  $currency_query = tep_db_query("select symbol_left from " . TABLE_CURRENCIES . " where  code = '" . DEFAULT_CURRENCY . "' ");
  $currency_symbol_results = tep_db_fetch_array($currency_query);
  $store_currency_symbol = tep_db_output($currency_symbol_results['symbol_left']);
# Order Tracking by Country

  $array_month[] = array('id' => 'Jan', 'text' => TEXT_MONTH_JANUARY );
  $array_month[] = array('id' => 'Feb', 'text' => TEXT_MONTH_FEBRUARY );
  $array_month[] = array('id' => 'Mar', 'text' => TEXT_MONTH_MARCH );
  $array_month[] = array('id' => 'Apr', 'text' => TEXT_MONTH_APRIL );
  $array_month[] = array('id' => 'May', 'text' => TEXT_MONTH_MAY );
  $array_month[] = array('id' => 'Jun', 'text' => TEXT_MONTH_JUNE );
  $array_month[] = array('id' => 'Jul', 'text' => TEXT_MONTH_JULY );
  $array_month[] = array('id' => 'Aug', 'text' => TEXT_MONTH_AUGUST );
  $array_month[] = array('id' => 'Sep', 'text' => TEXT_MONTH_SEPTEMBER );
  $array_month[] = array('id' => 'Okt', 'text' => TEXT_MONTH_OKTOBER );
  $array_month[] = array('id' => 'Nov', 'text' => TEXT_MONTH_NOVEMBER );
  $array_month[] = array('id' => 'Dec', 'text' => TEXT_MONTH_DECEMBER );


  require(DIR_WS_INCLUDES . 'template_top.php');  	
?>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1  class="col-xs-12 col-md-4"><?php echo HEADING_TITLE; ?></h1> 
            <div class="col-xs-12 col-md-8 ">
			  <div class="row">  
			    <div>
<?php
                  echo   tep_draw_bs_form('search', FILENAME_STATS_ORDERS_TRACKING, '', 'get', 'role="form"', 'id_orders_tracking'  ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;				  
                  echo          tep_draw_bs_input_field('year', $year,  HEADING_SELECT_YEAR, 'order_select_order_numb' , 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_SELECT_YEAR 	).  PHP_EOL; 
 				  echo '    </div>' . PHP_EOL;	
 				  echo '    <div class="clearfix"></div>' . PHP_EOL;					  
 				  echo '    <br/>' . PHP_EOL;					  
				  
//                  echo '    <div class="form-group">' . PHP_EOL;				  
//                  echo          tep_draw_bs_input_field('month', $month,  HEADING_SELECT_MONTH, 'order_select_order_numb' , 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_SELECT_MONTH 	).  PHP_EOL; 
// 				  echo '    </div>' . PHP_EOL;	
				  
                  echo '    <div class="form-group">' . PHP_EOL;
			      echo          tep_draw_bs_pull_down_menu( 'month', $array_month, $month, HEADING_SELECT_MONTH, 'order_select_month', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
 				  echo '    </div>' . PHP_EOL;	
 				  echo '    <div class="clearfix"></div>' . PHP_EOL;				  
 				  echo '    <br/>' . PHP_EOL;				  

                  echo '    <div class="form-group">' . PHP_EOL;				  
                  echo          tep_draw_bs_input_field('postcode_prefix', '',  HEADING_SELECT_POSTPREFIX, 'order_select_order_numb' , 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_SELECT_POSTPREFIX 	).  PHP_EOL; 
 				  echo '    </div>' . PHP_EOL;	
 				  echo '    <div class="clearfix"></div>' . PHP_EOL;
 				  echo '    <br/>' . PHP_EOL;					  

                  echo '    <div class="form-group">' . PHP_EOL;				  
                  echo          tep_draw_bs_input_field('profit_rate', $profit_rate,  HEADING_SELECT_PROFIT_RATE, 'order_select_order_numb' , 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_SELECT_PROFIT_RATE 	).  PHP_EOL; 
 				  echo '    </div>' . PHP_EOL;
 				  echo '    <div class="clearfix"></div>' . PHP_EOL;				  
 				  echo '    <br/>' . PHP_EOL;					  
				  
// BOF multi stores
                  $stores_sql= "select stores_id, stores_name from " . TABLE_STORES . " order by stores_name";
                  $stores_query = tep_db_query($stores_sql);
  
                  if (tep_db_num_rows($stores_query) > 1) {
                     echo tep_draw_hidden_field('cPath', $cPath);
                     $options = array(array('id' => '', 'text' => TEXT_ALL_STORES));

                     echo tep_draw_hidden_field('sort', $_GET['sort']);
                     while ($filterlist = tep_db_fetch_array($stores_query)) {
                        $options[] = array('id' => $filterlist['stores_id'], 'text' => $filterlist['stores_name']);
                     }
                     
					 echo '    <div class="form-group">' . PHP_EOL;
			         echo          tep_draw_bs_pull_down_menu( 'stores_id', $options, '', HEADING_SELECT_STORES, 'order_select_stores', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
 				     echo '    </div>' . PHP_EOL;					 
                     //echo tep_draw_pull_down_menu('stores_id', $options,  '', 'onchange="this.form.submit()"');

                 }
// EOF multi stores					  
			  
				  echo      tep_hide_session_id() ;
				  
				  echo      tep_draw_bs_button(HEADING_TITLE_RECALCULATE, 'ok', null) ;
	              echo ' </form>' . PHP_EOL ; 			  
?>
                </div>				  				
              </div>			
              <div class="clearfix"></div>
            </div><!-- page-header-->
          </div>
		  <div class="clearfix"></div>
	      <hr>
          <div class="table-responsive">		  
            <table class="table table-condensed table-striped">
 
              <tbody>
			    <tr>  
				   <td>
				      <?php       include( DIR_WS_MODULES . FILENAME_MODULE_STATS_ORDERS_TRACK_SALES_SUM ) ; ?><!-- begin total sales SUMMARY -->
				   </td>
				   <td>
				      <?php       include( DIR_WS_MODULES . FILENAME_MODULE_STATS_ORDERS_TRACK_COUNTRY ) ; ?><!-- begin total sales COUNTRY -->
				   </td>
				   <td>
				      <?php       include( DIR_WS_MODULES . FILENAME_MODULE_STATS_ORDERS_TRACK_ZONE ) ; ?><!-- begin total sales ZONE -->
				   </td>
				   <td>
				      <?php       include( DIR_WS_MODULES . FILENAME_MODULE_STATS_ORDERS_TRACK_POSTCODE ) ; ?><!-- begin total sales POSTCODE -->
				   </td>				   
				
				</tr>
              </tbody>
           </table>			  
		 </div>
</table>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>