<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_tax_ex {
    var $title, $output;

    function ot_tax_ex() {
      $this->code = 'ot_tax_ex';
      $this->title = MODULE_ORDER_TOTAL_TAX_EX_TITLE;
      $this->description = MODULE_ORDER_TOTAL_TAX_EX_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_TAX_EX_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_TAX_EX_SORT_ORDER;

      $this->output = array();
    }

    function process() {
//      global $order, $currencies;
//
//      $this->output[] = array('title' => $this->title . ':',
//                              'text' => $currencies->format($order->info['tax'], true, $order->info['currency'], $order->info['currency_value']),
//                              'value' => $order->info['tax']);
      global $order, $currencies;
      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        if ($value > 0) {
  //    function process() {
  //    global $order, $currencies, $tax_discount; // Discount Code 2.4
//
//      reset($order->info['tax_groups']);
//      while (list($key, $value) = each($order->info['tax_groups'])) {
//        if (!empty($tax_discount[$key])) $value -= $tax_discount[$key]; // Discount Code 2.4
//        if ($value > 0) {  
          $this->output[] = array('title' => $key . ':',
                                  'text' => $currencies->format($value, true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => $value);
        }
      }							  
    }

   function check() {
	  GLOBAL $multi_stores_config ;   
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . $multi_stores_config  . " where configuration_key = 'MODULE_ORDER_TOTAL_TAX_EX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
	
      return array('MODULE_ORDER_TOTAL_TAX_EX_STATUS', 'MODULE_ORDER_TOTAL_TAX_EX_SORT_ORDER');
    }

    function install() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Tax', 'MODULE_ORDER_TOTAL_TAX_EX_STATUS', 'true', 'Do you want to display the order tax value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_TAX_EX_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>