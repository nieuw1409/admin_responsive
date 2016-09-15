<?php
/*
  $Id: cop.php,v 1.2 2007/06/03 05:51:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class cop {
    var $code, $title, $description, $enabled;
	var $payment ;
	
// class constructor
    function cop() {
      global $order;

      $this->code = 'cop';
      $this->title = MODULE_PAYMENT_COP_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_COP_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_COP_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_COP_STATUS == 'True') ? true : false);
      $this->payment = MODULE_PAYMENT_COP_USE_PAYMENTS;	  	  

      if ((int)MODULE_PAYMENT_COP_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_COP_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_COP_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_COP_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }

// disable the module if the order only contains virtual products
      if ($this->enabled == true) {
        if ($order->content_type == 'virtual') {
          $this->enabled = false;
        }
      }
// START Shipping dependency: disable the module if there is a list of allowed shipping method
// and the choosen method is not in selected shipping method
      if ($this->enabled == true) {
	  global $shipping;
	    if (tep_not_null(MODULE_PAYMENT_COP_USE_PAYMENTS)) {
		  if ( MODULE_PAYMENT_COP_USE_PAYMENTS != 'all' ) {
//	        $ship_method=split ("_",$shipping['id']); // 5.3			
//		    $ship_allowed=split (";",MODULE_PAYMENT_COP_USE_PAYMENTS); // 5.3
	        $ship_method= preg_split("/_/",$shipping['id']);
		    $ship_allowed= preg_split("/;/",MODULE_PAYMENT_COP_USE_PAYMENTS);			
		    if (in_array($ship_method[0],$ship_allowed )==false) {
               $this->enabled = false;
            }
		  }  
		}
      }
// END Shipping dependency	
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
	  GLOBAL $multi_stores_config ;
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . $multi_stores_config . " where configuration_key = 'MODULE_PAYMENT_COP_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Toestaan van Contant of Pinnen Module', 'MODULE_PAYMENT_COP_STATUS', 'True', 'Wilt U Contante Betaling in de Winkel activeren?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_COP_ZONE', '0', 'Als een zone is geselecteerd, activeer deze betalingsmethode voor deze zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sorteer order op het scherm.', 'MODULE_PAYMENT_COP_SORT_ORDER', '0', 'Sorteer order op het scherm. Laagste wordt het eerst op het scherm.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_COP_ORDER_STATUS_ID', '0', 'Zet de status van de orders gemaakt met deze betalingsmethode met deze gegeven', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
// Shipping dependency: line added
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Payment in combination with Shipping.', 'MODULE_PAYMENT_COP_USE_PAYMENTS', 'all', 'select shipping method to be used with this payment option. ', '6', '0','tep_cfg_select_payment(' , now())");
   }

    function remove() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
// Shipping dependency: key MODULE_PAYMENT_COP_SHIPPING added.
    function keys() {
      return array('MODULE_PAYMENT_COP_STATUS', 'MODULE_PAYMENT_COP_ZONE', 'MODULE_PAYMENT_COP_ORDER_STATUS_ID', 'MODULE_PAYMENT_COP_SORT_ORDER','MODULE_PAYMENT_COP_SHIPPING', 'MODULE_PAYMENT_COP_USE_PAYMENTS');
    }
  }
?>