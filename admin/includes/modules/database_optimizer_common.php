<?php
/*
  $Id: database_optimizer_common.php,v 1.0 2011/02/02
  database_optimizer_cron.php Originally Created by: Jack_mcs - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2011 oscommerce-solution.com

  Released under the GNU General Public License
*/
    if ($config['period'] < $daysLastRan) {
        $initialDBSize = Get_DB_Size(DB_DATABASE);

        /*********************** REMOVE FROM CUSTOMER TABLES - OLD SESSIONS *****************************/
        if (! empty($config['customers']) && $config['customers'] < $daysLastRan) {
            $dateCustomers = date("Ymd", time() - ($config['customers'] * 86400));
            $wasUpdated = true;
            $message .= "\r\n" . 'Customers tables were trimmed.';

            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " WHERE (`products_id`, `customers_id`) in (select `products_id`, `customers_id` from " . TABLE_CUSTOMERS_BASKET . " where customers_basket_date_added < '" . $dateCustomers . "')");
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_basket_date_added < '" . $dateCustomers . "'");     //clear the customers basket table of entries greater than one month old
            tep_db_query("update database_optimizer set customers_last_update = now()");
            
        }        

        /*********************** REMOVE OLD CUSTOMERS *****************************/
        if (! empty($config['customers_old']) && $config['customers_old'] < $daysLastRan) {
            $dateCustomers = date("Y-m-d", time() - ($config['customers_old'] * 86400));
            $wasUpdated = true;
            $message .= "\r\n" . 'Customers (old) tables were trimmed.';
            
            $id_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " WHERE customers_info_number_of_logons = 0 and customers_info_date_account_created < '" . $dateCustomers . "'" );

            if (tep_db_num_rows($id_query) > 0) {  
                $custStr = '';
                $custRemoved = 0;
                
                while ($cust = tep_db_fetch_array($id_query)) {
                    $orders_query = tep_db_query("select 1 from " . TABLE_ORDERS . " WHERE customers_id = " . (int)$cust['customers_info_id']);
                    if (tep_db_num_rows($orders_query) == 1) continue; //skip customers with orders
                    $custStr .= " customers_id = " . (int)$cust['customers_info_id'] . " or ";  //build a string for faster operation
                    $custRemoved++;
                }
                
                if ($custRemoved > 0) {
                    $text = ($custRemoved > 1 ? 'customers' : 'customer');
                    $message .= ' ( ' . $custRemoved . ' ' . $text . ' removed. )';
                    $custStr = " where ( " . substr($custStr, 0, -4) . " )";
                    
                    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . $custStr);
                    tep_db_query("delete from " . TABLE_CUSTOMERS . $custStr);
                    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . $custStr);
                    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . $custStr);
                    tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . $custStr);
                    $custStr = str_replace('customers_id', 'customers_info_id', $custStr);  //id has different name so change it
                    tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . $custStr);
                }
            }
            tep_db_query("update database_optimizer set customers_old_last_update = now()");
        }
        
        /********************** REMOVE FROM PRODUCTS_NOTIFICATIONS TABLE *************************/  
        if (! empty($config['notifications']) && $config['notifications'] < $daysLastRan) {        
            $dbWhereCust = '';
            $dbWhereProd = '';
            $delArrayCust = array();
            $delArrayProd = array();            
            $missingCust = 0;
            $missingProd = 0;
            $missingTTL  = 0;
            $wasUpdated = true;
            
            $db_query = tep_db_query("select customers_id as cid, products_id as pid from " . TABLE_PRODUCTS_NOTIFICATIONS);

            while ($db = tep_db_fetch_array($db_query)) {
                $cust_query = tep_db_query("select 1 from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$db['cid'] . "'");
                if (tep_db_num_rows($cust_query) > 0) { //customer is present
                    $product_query = tep_db_query("select 1 from " . TABLE_PRODUCTS . " where products_id = '" . (int)$db['pid'] . "'");
                    if (tep_db_num_rows($product_query) > 0) { //order is present
                        continue;
                    } else {
                        if (! in_array($db['pid'], $delArrayProd)) {
                            $delArrayProd[] = $db['pid'];
                            $missingProd++;
                            $missingTTL++;
                            $dbWhereProd .= (int)$db['pid'] . ',';
                        }
                    }      
                } else {
                    if (! in_array($db['cid'], $delArrayCust)) {
                        $delArrayCust[] = $db['cid'];      
                        $missingCust++;
                        $missingTTL++;
                        $dbWhereCust .= (int)$db['cid'] . ',';
                    }
                }      
            }
            
            if ($missingCust) {
                $dbWhereCust = substr($dbWhereCust, 0, -1);   
                tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id IN (" .$dbWhereCust . ")");
            }
            if ($missingProd) {
                $dbWhereProd = substr($dbWhereProd, 0, -1);
                tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id IN (" .$dbWhereProd . ")");
            }
      
            $message .= "\r\n" . 'Products Notifications table was cleaned.';
            
            if ($missingTTL) {
                $ctext = ($missingCust > 1 ? ' customers' : 'customer');
                $ptext = ($missingProd > 1 ? ' products' : 'product');
                $text = ($missingTTL > 1 ? 'records' : 'record');
                $message .= ' ( Removed ' . $missingTTL . ' ' . $text . ': ';
                $message .= ($missingCust ? $ctext . ': ' . $missingCust : '0');
                $message .= ($missingProd ? $ptext . ': ' . $missingProd : '0');
                $message .= ' )';
            }    
            tep_db_query("update database_optimizer set products_notifications_last_update = now()");
        }
        
        /********************** REMOVE CC DATA FROM ORDERS TABLE *************************/
        if (! empty($config['orders_cc']) && $config['orders_cc'] < $daysLastRan) {
            $dateOrder = date("Y-m-d", time() - ($config['orders_cc'] * 86400));
            $wasUpdated = true;
            $message .= "\r\n" . 'Credit Card data was removed from the orders table.';
            tep_db_query("update " . TABLE_ORDERS . " set cc_number = '' where date_purchased > '" . $dateOrder . "'");     //clear the sessions table of entries greater than one month old
            tep_db_query("update database_optimizer set orders_last_update = now()");
        }
        /********************** REMOVE ORPHANS FROM ADDRESS BOOK TABLE *************************/
        if (! empty($config['orphan_addr_book']) && $config['orphan_addr_book'] < $daysLastRan) {
            $dateOrder = date("Y-m-d", time() - ($config['orphan_addr_book'] * 86400));
            $wasUpdated = true;
            $message .= "\r\n" . 'Orphan entries were removed from the address book table.';
            tep_db_query("update " . TABLE_ORDERS . " set cc_number = '' where date_purchased > '" . $dateOrder . "'");     //clear the sessions table of entries greater than one month old
            tep_db_query("DELETE FROM " . TABLE_ADDRESS_BOOK . " where NOT EXISTS (select 1 FROM " . TABLE_CUSTOMERS . " where customers_id = " . TABLE_ADDRESS_BOOK . ".customers_id)");
            tep_db_query("update database_optimizer set orphan_addr_book_last_update = now()");
        }        

        /*********************** REMOVE FROM SESSION TABLES *****************************/
        if (! empty($config['sessions']) && $config['sessions'] < $daysLastRan) {
            $secondsSessions = $config['sessions'] * 86400;
            $wasUpdated = true;
            $message .= "\r\n" . 'Sessions table was trimmed.';
            tep_db_query("delete from " . TABLE_SESSIONS . " where expiry > '" . $secondsSessions . "'");     //clear the sessions table of entries greater than one month old
            tep_db_query("update database_optimizer set sessions_last_update = now()");
        }

        /*********************** REMOVE FROM USER TRACKING *****************************/
        if (! empty($config['usertracking']) && $config['usertracking'] < $daysLastRan) {
            $secondsUserTracking = $config['usertracking'] * 86400;
            $wasUpdated = true;
            $message .= "\r\n" . 'User Tracking table was trimmed.';
            tep_db_query("delete from " . TABLE_USER_TRACKING . " where time_entry > '" . (int)$secondsUserTracking . "'");     //clear the sessions table of entries greater than one month old
            tep_db_query("update database_optimizer set user_tracking_last_update = now()");
        }

        /**************************** OPTIMIZE ALL TABLES ******************************/
        if ($config['optimize'] || $config['analyze']) {
             $tbl_status = "SHOW TABLE STATUS FROM `" . DB_DATABASE . "`";        // Statement to select the tables in the currently looped database
             $tbl_result = tep_db_query($tbl_status);         // Query mySQL for the results

             if(tep_db_num_rows($tbl_result) > 0) {                         // Check to see if any tables exist within database
                 while ($tbl_row = tep_db_fetch_array($tbl_result)) {       // Loop through all the tables
                     if (! empty($config['analyze']) && $config['analyze'] < $daysLastRan) {
                         tep_db_query("ANALYZE TABLE `".$tbl_row['Name']."`") or die(mysql_error());  //adjust keys
                     }
                     if (! empty($config['optimize']) && $config['optimize'] < $daysLastRan) {
                         tep_db_query("OPTIMIZE TABLE `".$tbl_row['Name']."`") or die(mysql_error()); //adjust size
                     }
                 }
             }

             if (! empty($config['analyze']) && $config['analyze'] < $daysLastRan) {
                 $message .= "\r\n" . 'Database was analyzed.';
             }
             if (! empty($config['optimize']) && $config['optimize'] < $daysLastRan) {
                 $message .= "\r\n" . 'Database was optimized.';
             }

             $wasUpdated = true;
             tep_db_query("update database_optimizer set last_update = now()");
        }

        $message .= "\r\n" . 'Initial size was ' . $initialDBSize . '.';
        $message .= "\r\n" . 'Final, optimized, size is ' . Get_DB_Size(DB_DATABASE) . '.';

        if ($wasUpdated && ! empty($config['email_address'])) {
            $emails = explode(',', $config['email_address']);

            foreach ($emails as $emailAddress) {
                mail(trim($emailAddress), sprintf($subject, DB_DATABASE), $message, $config['email_address']);
            }
        }

        if ($verbose) {
            echo $message;
        }
    }
?>