<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class d_customers {
    var $code = 'd_customers';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function d_customers() {
      $this->title = MODULE_ADMIN_DASHBOARD_CUSTOMERS_TITLE;
      $this->description = MODULE_ADMIN_DASHBOARD_CUSTOMERS_DESCRIPTION;

      if ( defined('MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_CUSTOMERS_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS == 'True');
      }
    }

    function getOutput() {
      $output = '<div class="panel panel-default">' .
	            '  <table class="table table-hover">' .
                '    <tr class="heading-row">' .
                '      <th class="col-md-8">' . MODULE_ADMIN_DASHBOARD_CUSTOMERS_TITLE . '</th>' .
                '      <th class="col-md-4 text-right">' . MODULE_ADMIN_DASHBOARD_CUSTOMERS_DATE . '</th>' .
                '    </tr>';

      $customers_query = tep_db_query("select c.customers_id, c.customers_lastname, c.customers_firstname, ci.customers_info_date_account_created from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id order by ci.customers_info_date_account_created desc limit 6");
      while ($customers = tep_db_fetch_array($customers_query)) {
        $output .= '    <tr>' .
                   '      <td class="col-md-8"><a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . (int)$customers['customers_id'] . '&action=edit') . '">' . tep_output_string_protected($customers['customers_firstname'] . ' ' . $customers['customers_lastname']) . '</a></td>' .
                   '      <td class="col-md-4 text-right">' . tep_date_short($customers['customers_info_date_account_created']) . '</td>' .
                   '  </tr>';
      }

      $output .= '  </table>' .
	             '</div>';

      return $output;
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;		
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Customers Module', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS', 'True', 'Do you want to show the newest customers on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;		
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_SORT_ORDER');
    }
  }
?>
