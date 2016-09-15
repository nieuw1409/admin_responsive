<?php
/*
  $Id: ot_subtotal_ex.php,v 1.7 2003/02/13 00:12:04 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_subtotal_ex {
    var $title, $output;

    function ot_subtotal_ex() {
      $this->code = 'ot_subtotal_ex';
      $this->title = MODULE_ORDER_TOTAL_SUBTOTAL_EX_TITLE;
      $this->description = MODULE_ORDER_TOTAL_SUBTOTAL_EX_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_SUBTOTAL_EX_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_SUBTOTAL_EX_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies;

        if (DISPLAY_PRICE_WITH_TAX == 'true')
		  {
		// Changed to added shipping cost to the total - added +$order->info['shipping_cost'] into both lines below  
		  $this->output[] = array('title' => $this->title . ':',
								  'text' => $currencies->format($order->info['subtotal']-$order->info['tax']+$order->info['shipping_cost'], true, $order->info['currency'], $order->info['currency_value']),
								  'value' => $order->info['subtotal']-$order->info['tax']+$order->info['shipping_cost']);
		  
		  }
		  else
		  {
	  // Changed to added shipping cost to the total - added +$order->info['shipping_cost'] into both lines below
		  $this->output[] = array('title' => $this->title . ':',
								  'text' => $currencies->format($order->info['subtotal']+$order->info['shipping_cost'], true, $order->info['currency'], $order->info['currency_value']),
								  'value' => $order->info['subtotal']-$order->info['tax']+$order->info['shipping_cost']);
		  
		  }
    }

    function check() {
	  GLOBAL $multi_stores_config ;		
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . $multi_stores_config  . " where configuration_key = 'MODULE_ORDER_TOTAL_SUBTOTAL_EX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_SUBTOTAL_EX_STATUS', 'MODULE_ORDER_TOTAL_SUBTOTAL_EX_SORT_ORDER');
    }

    function install() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Sub-Total Excl.', 'MODULE_ORDER_TOTAL_SUBTOTAL_EX_STATUS', 'true', 'Do you want to display the order sub-total cost Excl. ?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_EX_SORT_ORDER', '1', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;	
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
