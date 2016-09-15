<?php
/*
  $Id: database_optimizer_cron.php,v 1.0 2011/02/02
  database_optimizer_cron.php Originally Created by: Jack_mcs - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2011 oscommerce-solution.com

  Released under the GNU General Public License
*/

function Get_DB_Size($database) {
    $result = tep_db_query("SHOW TABLE STATUS FROM `" . $database . "`");
    $dbsize = 0;
    while( $row = tep_db_fetch_array( $result ) ) {
        $dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];
    }
    return $dbsize;
}