<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  function osc_db_connect($server, $username, $password, $link = 'db_link') {
    global $$link, $db_error;

    $db_error = false;

    if (!$server) {
      $db_error = 'No Server selected.';
      return false;
    }

    $$link = @mysqli_connect($server, $username, $password);

    if ( !mysqli_connect_errno() ) {
      mysqli_set_charset($$link, 'utf8');
    } else {
      $db_error = mysqli_connect_error();
    }

    return $$link;
  }

  function osc_db_select_db($database, $link = 'db_link') {
    global $$link, $db_error;

    if ( empty($database) ) {
      $db_error = 'No Database selected.';
      return false;
    }

    if ( !@mysqli_select_db($$link, $database) ) {
      $db_error = 'Could not open database "' . $database . '".';
      return false;
    }

    return true;
  }

  function osc_db_query($query, $link = 'db_link') {
    global $$link;

    return mysqli_query($$link, $query);
  }

  function osc_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function osc_db_install($database, $sql_file, $link = 'db_link') {
    global $$link, $db_error;

    $db_error = false;

    if (!@osc_db_select_db($database)) {
      if (@osc_db_query('create database ' . $database)) {
        osc_db_select_db($database);
      } else {
        $db_error = mysqli_error($$link);
      }
    }

    if (!$db_error) {
      if (file_exists($sql_file)) {
        $fd = fopen($sql_file, 'rb');
        $restore_query = fread($fd, filesize($sql_file));
        fclose($fd);
      } else {
        $db_error = 'SQL file does not exist: ' . $sql_file;
        return false;
      }

      $sql_array = array();
      $sql_length = strlen($restore_query);
      $pos = strpos($restore_query, ';');
      for ($i=$pos; $i<$sql_length; $i++) {
        if ($restore_query[0] == '#') {
          $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
          $sql_length = strlen($restore_query);
          $i = strpos($restore_query, ';')-1;
          continue;
        }
        if ($restore_query[($i+1)] == "\n") {
          for ($j=($i+2); $j<$sql_length; $j++) {
            if (trim($restore_query[$j]) != '') {
              $next = substr($restore_query, $j, 6);
              if ($next[0] == '#') {
// find out where the break position is so we can remove this line (#comment line)
                for ($k=$j; $k<$sql_length; $k++) {
                  if ($restore_query[$k] == "\n") break;
                }
                $query = substr($restore_query, 0, $i+1);
                $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
                $restore_query = $query . $restore_query;
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
                continue 2;
              }
              break;
            }
          }
          if ($next == '') { // get the last insert query
            $next = 'insert';
          }
          if ( (preg_match('/create/i', $next)) || (preg_match('/insert/i', $next)) || (preg_match('/drop t/i', $next)) ) {
            $next = '';
            $sql_array[] = substr($restore_query, 0, $i);
            $restore_query = ltrim(substr($restore_query, $i+1));
            $sql_length = strlen($restore_query);
            $i = strpos($restore_query, ';')-1;
          }
        }
      }

      for ($i=0; $i<sizeof($sql_array); $i++) {
        if (!osc_db_query($sql_array[$i])) {
          $db_error = mysqli_error($$link);

          return false;
        }
      }
    } else {
      return false;
    }
  }
  
// bof multi store
  function osc_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
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
            $query .= '\'' . osc_db_input($value) . '\', ';
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
            $query .= $columns . ' = \'' . osc_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return osc_db_query($query, $link);
  }
  
  function osc_db_insert_id($link = 'db_link') {
    global $$link;

    return mysqli_insert_id($$link);
  } 

  function osc_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }  
  
  function osc_db_input($string, $link = 'db_link') {
    global $$link;

    return mysqli_real_escape_string($$link, $string);
  }  
// eof multi store

  if ( !function_exists('mysqli_connect') ) {
  
    define('MYSQLI_ASSOC', MYSQL_ASSOC);  
    function mysqli_connect_errno($link = null) {
      if ( is_null($link) ) {
        return mysql_errno();
      }

      return mysql_errno($link);
    }

    function mysqli_connect_error($link = null) {
      if ( is_null($link) ) {
        return mysql_error();
      }

      return mysql_error($link);
    }

    function mysqli_connect($server, $username, $password) {
      if ( substr($server, 0, 2) == 'p:' ) {
        $link = mysql_pconnect(substr($server, 2), $username, $password);
      } else {
        $link = mysql_connect($server, $username, $password);
      }

      return $link;
    }

    function mysqli_set_charset($link, $charset) {
      if ( function_exists('mysql_set_charset') ) {
        return mysql_set_charset($charset, $link);
      }
    }

    function mysqli_select_db($link, $database) {
      return mysql_select_db($database, $link);
    }

    function mysqli_query($link, $query) {
      return mysql_query($query, $link);
    }

    function mysqli_error($link = null) {
      if ( is_null($link) ) {
        return mysql_error();
      }

      return mysql_error($link);
    }

    function mysqli_num_rows($query) {
      return mysql_num_rows($query);
    }
	
    function mysqli_insert_id($link) {
      return mysql_insert_id($link);
    }
	
    function mysqli_fetch_array($query, $type) {
      return mysql_fetch_array($query, $type);
    }

    function mysqli_real_escape_string($link, $string) {
      if ( function_exists('mysql_real_escape_string') ) {
        return mysql_real_escape_string($string, $link);
      } elseif ( function_exists('mysql_escape_string') ) {
        return mysql_escape_string($string);
      }

      return addslashes($string);
    }	
  }
?>
