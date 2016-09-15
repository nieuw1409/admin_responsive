<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order_total {
    var $modules;

// class constructor
    function order_total() {
      global $language;

      if (defined('MODULE_ORDER_TOTAL_INSTALLED') && tep_not_null(MODULE_ORDER_TOTAL_INSTALLED)) {

// BOF Separate Pricing Per Customer, next line original code 
// $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED); 
        global $customer_id; 
// bof multi stores		
//  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
        if (isset($_SESSION['sppc_customer_group_id']) ) {
          $customer_group_id = $_SESSION['sppc_customer_group_id'];
        } else {
// $customer_group_id = '0';
           $customer_group_id = _SYS_STORE_CUSTOMER_GROUP ;
// eof multi stores	
		} 
		$customer_ot_query = tep_db_query("select IF(c.customers_order_total_allowed <> '', c.customers_order_total_allowed, cg.group_order_total_allowed) as order_total_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $customer_id . "' and cg.customers_group_id = '" . $customer_group_id . "'"); 
		
		if ($customer_ot = tep_db_fetch_array($customer_ot_query) ) {
    		if (tep_not_null($customer_ot['order_total_allowed']) ) { 
			   $temp_ot_array = explode(';', $customer_ot['order_total_allowed']); 
			   $installed_modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED); 
			   for ($n = 0; $n < sizeof($installed_modules) ; $n++) { // check to see if a order total module is not de-installed 
			      if ( in_array($installed_modules[$n], $temp_ot_array ) ) { 
				     $ot_array[] = $installed_modules[$n]; 
				  } 
			   } // end for loop 
			   $this->modules = $ot_array; 
			} else { 
			   $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED); 
			} 
		} else { // default 
		  $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED); 
		} // EOF Separate Pricing Per Customer 
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          include(DIR_WS_LANGUAGES . $language . '/modules/order_total/' . $value);
          include(DIR_WS_MODULES . 'order_total/' . $value);

          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
        }
      }
    }

    function process() {
      $order_total_array = array();
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $GLOBALS[$class]->output = array();
            $GLOBALS[$class]->process();

            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
              if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
                $order_total_array[] = array('code' => $GLOBALS[$class]->code,
                                             'title' => $GLOBALS[$class]->output[$i]['title'],
                                             'text' => $GLOBALS[$class]->output[$i]['text'],
                                             'value' => $GLOBALS[$class]->output[$i]['value'],
                                             'sort_order' => $GLOBALS[$class]->sort_order);
              }
            }
          }
        }
      }

      return $order_total_array;
    }

    function output() {
      $output_string = '';
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $size = sizeof($GLOBALS[$class]->output);
            for ($i=0; $i<$size; $i++) {
              $output_string .= '              <tr>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['title'] . '</td>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['text'] . '</td>' . "\n" .
                                '              </tr>';
            }
          }
        }
      }

      return $output_string;
    }
  }
?>