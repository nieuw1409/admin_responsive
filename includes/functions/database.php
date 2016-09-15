<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $server = 'p:' . $server;
    }

    $$link = mysqli_connect($server, $username, $password, $database);
// bof 2.3.3.3
    if ( !mysqli_connect_errno() ) {
      mysqli_set_charset($$link, 'utf8');
    } 
// eof 2.3.3.3	

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysqli_close($$link);
  }

  function tep_db_error($query, $errno, $error) { 
    die('<font color="#000000"><strong>' . $errno . ' - ' . $error . '<br /><br />' . $query . '<br /><br /><small><font color="#ff0000">[TEP STOP]</font></small><br /><br /></strong></font>');
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY:' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG); // 2.3.3.3
    }

    $result = mysqli_query($$link, $query) or tep_db_error($query, mysqli_errno($$link), mysqli_error($$link));

// bof 2.3.3.3    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
//       $result_error = mysqli_error($$link);
//       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
// eof 2.3.3.3    }

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }

  function tep_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysqli_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id($link = 'db_link') {
    global $$link;

    return mysqli_insert_id($$link);
  }

  function tep_db_free_result($db_query) {
    return mysqli_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysqli_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db_link') {
    global $$link;

    return mysqli_real_escape_string($$link, $string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
  
// bof 2.3.3.2
  function tep_db_affected_rows($link = 'db_link') {
    global $$link;

    return mysqli_affected_rows($$link);
  }

  function tep_db_get_server_info($link = 'db_link') {
    global $$link;

    return mysqli_get_server_info($$link);
  }

  if ( !function_exists('mysqli_connect') ) {
    define('MYSQLI_ASSOC', MYSQL_ASSOC);

    function mysqli_connect($server, $username, $password, $database) {
      if ( substr($server, 0, 2) == 'p:' ) {
        $link = mysql_pconnect(substr($server, 2), $username, $password);
      } else {
        $link = mysql_connect($server, $username, $password);
      }

      if ( $link ) {
        mysql_select_db($database, $link);
      }

      return $link;
    }
	
// bof 2.3.3.3
    function mysqli_connect_errno($link = null) {
// bof 2.3.3.4
     if ( is_null($link) ) {
        return mysql_errno();
      }
// eof 2.3.3.4		
      return mysql_errno($link);
    }
    function mysqli_connect_error($link = null) {
// bof 2.3.3.4
     if ( is_null($link) ) {
        return mysql_errno();
      }
// eof 2.3.3.4		
      return mysql_error($link);
    }
    function mysqli_set_charset($link, $charset) {
      if ( function_exists('mysql_set_charset') ) {
        return mysql_set_charset($charset, $link);
      }
    }
// eof 2.3.3.3	

    function mysqli_close($link) {
      return mysql_close($link);
    }

    function mysqli_query($link, $query) {
      return mysql_query($query, $link);
    }

    function mysqli_errno($link = null) {
// bof 2.3.3.4
     if ( is_null($link) ) {
        return mysql_errno();
      }
// eof 2.3.3.4		
      return mysql_errno($link);
    }

    function mysqli_error($link = null) {
// bof 2.3.3.4
     if ( is_null($link) ) {
        return mysql_errno();
      }
// eof 2.3.3.4		
      return mysql_error($link);
    }

    function mysqli_fetch_array($query, $type) {
      return mysql_fetch_array($query, $type);
    }

    function mysqli_num_rows($query) {
      return mysql_num_rows($query);
    }

    function mysqli_data_seek($query, $offset) {
      return mysql_data_seek($query, $offset);
    }

    function mysqli_insert_id($link) {
      return mysql_insert_id($link);
    }

    function mysqli_free_result($query) {
      return mysql_free_result($query);
    }

    function mysqli_fetch_field($query) {
      return mysql_fetch_field($query);
    }

    function mysqli_real_escape_string($link, $string) {
      if ( function_exists('mysql_real_escape_string') ) {
        return mysql_real_escape_string($string, $link);
      } elseif ( function_exists('mysql_escape_string') ) {
        return mysql_escape_string($string);
      }

      return addslashes($string);
    }

    function mysqli_affected_rows($link) {
      return mysql_affected_rows($link);
    }

    function mysqli_get_server_info($link) {
      return mysql_get_server_info($link);
    }
  }
// eor 2.3.3.2  
  
// BOF Separate Pricing Per Customer, adapted from sample code in user comments on
  // http://www.php.net/manual/en/function.mysql-list-tables.php
  // Wrap DB_DATABASE with Back Ticks, Fixes Hyphens in Database Name, code from
  // Jef Stumpf/Krumbsnatcher: http://forums.oscommerce.com/index.php?showtopic=53436&view=findpost&p=563454
  function tep_db_table_exists($table, $link = 'db_link') {
	  $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
	  while ($list_tables = tep_db_fetch_array($result)) {
	    if ($list_tables['Name'] == $table) {
		    return true;
	    }
	  }
	  return false;
  }

  function tep_db_check_age_specials_retail_table() {
	  $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
	  $last_update_table_specials = "2000-01-01 12:00:00";
	  $table_srp_exists = false;
	  while ($list_tables = tep_db_fetch_array($result)) {
	  if ($list_tables['Name'] == TABLE_SPECIALS_RETAIL_PRICES) {
	    $table_srp_exists = true;
	    $last_update_table_srp = $list_tables['Update_time'];
	  }
	    if ($list_tables['Name'] == TABLE_SPECIALS) {
	    //$last_update_table_specials = $list_tables['Update_time'];
	    }
	  } // end while

	  if(!$table_srp_exists || ($last_update_table_specials > $last_update_table_srp)) {
	     if ($table_srp_exists) {
		     $query1 = "truncate " . TABLE_SPECIALS_RETAIL_PRICES . "";
		     if (tep_db_query($query1)) {
		 $query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.stores_id, s.customers_group_id from " . TABLE_SPECIALS . " s where s.customers_group_id = '0'";
		 $result =  tep_db_query($query2);
		 }
	     } else { // table specials_retail_prices does not exist
		     $query1 = "create table " . TABLE_SPECIALS_RETAIL_PRICES . " (products_id int NOT NULL default '0', specials_new_products_price decimal(15,4) NOT NULL default '0.0000', status tinyint, customers_group_id smallint, primary key (products_id) )" ;
		     $query2 = "insert into " . TABLE_SPECIALS_RETAIL_PRICES . " select s.products_id, s.specials_new_products_price, s.status, s.stores_id, s.customers_group_id from " . TABLE_SPECIALS . " s where s.customers_group_id = '0'";
		     if( tep_db_query($query1) && tep_db_query($query2) ) {
			; // execution succesfull
		    }
	     } // end else
	  } // end if(!$table_srp_exists || ($last_update_table_specials....
  }

  function tep_db_check_age_products_group_prices_cg_table($customer_group_id) {
	  $result = tep_db_query("show table status from `" . DB_DATABASE . "`");
	  $last_update_table_pgp = strtotime('2000-01-01 12:00:00');
	  $table_pgp_exists = false;
	  while ($list_tables = tep_db_fetch_array($result)) {
	  if ($list_tables['Name'] == TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id) {
	  $table_pgp_exists = true;
	  $last_update_table_pgp = strtotime($list_tables['Update_time']);
	  } elseif ($list_tables['Name'] == TABLE_SPECIALS ) {
	  $last_update_table_specials = strtotime($list_tables['Update_time']);
	  } elseif ($list_tables['Name'] == TABLE_PRODUCTS ) {
	  $last_update_table_products = strtotime($list_tables['Update_time']);
	  } elseif ($list_tables['Name'] == TABLE_PRODUCTS_GROUPS ) {
	  $last_update_table_products_groups = strtotime($list_tables['Update_time']);
	  }
	  } // end while

   if ($table_pgp_exists == false) {
      $create_table_sql = "create table " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . " (products_id int NOT NULL default '0', products_price decimal(15,4) NOT NULL default '0.0000', specials_new_products_price decimal(15,4) default NULL, status tinyint, primary key (products_id) )" ;
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($create_table_sql) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
	       return true;
              }
   } // end if ($table_pgp_exists == false)

   if ( ($last_update_table_pgp < $last_update_table_products && (time() - $last_update_table_products > (int)MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE * 60) ) || $last_update_table_specials > $last_update_table_pgp || $last_update_table_products_groups > $last_update_table_pgp ) { // then the table should be updated
      $empty_query = "truncate " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id . "";
      $fill_table_sql1 = "insert into " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." select p.products_id, p.products_price, NULL as specials_new_products_price, NULL as status FROM " . TABLE_PRODUCTS . " p";
      $update_table_sql1 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_PRODUCTS_GROUPS . " pg using(products_id) set ppt.products_price = pg.customers_group_price where ppt.products_id = pg.products_id and pg.customers_group_id ='" . $customer_group_id . "'";
      $update_table_sql2 = "update " . TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id ." ppt left join " . TABLE_SPECIALS . " s using(products_id) set ppt.specials_new_products_price = s.specials_new_products_price, ppt.status = s.status where ppt.products_id = s.products_id and s.customers_group_id = '" . $customer_group_id . "'";
      if ( tep_db_query($empty_query) && tep_db_query($fill_table_sql1) && tep_db_query($update_table_sql1) && tep_db_query($update_table_sql2) ) {
	       return true;
              }
   } else { // no need to update
	   return true;
   } // end checking for update

  }

// EOF Separate Pricing Per Customer    
?>