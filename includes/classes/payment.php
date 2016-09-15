<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  class payment {
    var $modules, $selected_module;

// class constructor
    function payment($module = '') {
      global $payment, $language, $PHP_SELF;

      if (defined('MODULE_PAYMENT_INSTALLED') && tep_not_null(MODULE_PAYMENT_INSTALLED)) {
// BOF Separate Pricing Per Customer, next line original code
     //       $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
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
   $customer_payment_query = tep_db_query("select IF(c.customers_payment_allowed <> '', c.customers_payment_allowed, cg.group_payment_allowed) as payment_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $customer_id . "' and cg.customers_group_id =  '" . $customer_group_id . "'");
   if ($customer_payment = tep_db_fetch_array($customer_payment_query)  ) {
	   if (tep_not_null($customer_payment['payment_allowed'])) {
	  $temp_payment_array = explode(';', $customer_payment['payment_allowed']);
	  $installed_modules = explode(';', MODULE_PAYMENT_INSTALLED);
	  for ($n = 0; $n < sizeof($installed_modules) ; $n++) {
		  // check to see if a payment method is not de-installed
		  if ( in_array($installed_modules[$n], $temp_payment_array ) ) {
			  $payment_array[] = $installed_modules[$n];
		  }
	  } // end for loop
	  $this->modules = $payment_array;
   } else {
	   $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
   }
   } else { // default
	   $this->modules = explode(';', MODULE_PAYMENT_INSTALLED);
   }
// EOF Separate Pricing Per Customer

        $include_modules = array();

        if ( (tep_not_null($module)) && (in_array($module . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.')+1)), $this->modules)) ) {
          $this->selected_module = $module;

          $include_modules[] = array('class' => $module, 'file' => $module . '.php');
        } else {
          reset($this->modules);
          while (list(, $value) = each($this->modules)) {
            $class = substr($value, 0, strrpos($value, '.'));
            $include_modules[] = array('class' => $class, 'file' => $value);
          }
        }

        for ($i=0, $n=sizeof($include_modules); $i<$n; $i++) {
          include(DIR_WS_LANGUAGES . $language . '/modules/payment/' . $include_modules[$i]['file']);
          include(DIR_WS_MODULES . 'payment/' . $include_modules[$i]['file']);

          $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class'];
        }

// if there is only one payment method, select it as default because in
// checkout_confirmation.php the $payment variable is being assigned the
// $HTTP_POST_VARS['payment'] value which will be empty (no radio button selection possible)
        if ( (tep_count_payment_modules() == 1) && (!isset($GLOBALS[$payment]) || (isset($GLOBALS[$payment]) && !is_object($GLOBALS[$payment]))) ) {
          $payment = $include_modules[0]['class'];
        }

        if ( (tep_not_null($module)) && (in_array($module, $this->modules)) && (isset($GLOBALS[$module]->form_action_url)) ) {
          $this->form_action_url = $GLOBALS[$module]->form_action_url;
        }
      }
    }

// class methods
/* The following method is needed in the checkout_confirmation.php page
   due to a chicken and egg problem with the payment class and order class.
   The payment modules needs the order destination data for the dynamic status
   feature, and the order class needs the payment module title.
   The following method is a work-around to implementing the method in all
   payment modules available which would break the modules in the contributions
   section. This should be looked into again post 2.2.
*/   
    function update_status() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module])) {
// 2.3.3.		
//          if (function_exists('method_exists')) {
//            if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
//              $GLOBALS[$this->selected_module]->update_status();
//            }
//          } else { // PHP3 compatibility
//            @call_user_method('update_status', $GLOBALS[$this->selected_module]);
         if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
            $GLOBALS[$this->selected_module]->update_status();
// eof 2.3.3			
          }
        }
      }
    }

    function javascript_validation() {
      $js = '';
      if (is_array($this->modules)) {
        $js = '<script type="text/javascript"><!-- ' . "\n" .
              'function check_form() {' . "\n" .
              '  var error = 0;' . "\n" .
              '  var error_message = "' . JS_ERROR . '";' . "\n" .
              '  var payment_value = null;' . "\n" .
              '  if (document.checkout_payment.payment.length) {' . "\n" .
              '    for (var i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
              '      if (document.checkout_payment.payment[i].checked) {' . "\n" .
              '        payment_value = document.checkout_payment.payment[i].value;' . "\n" .
              '      }' . "\n" .
              '    }' . "\n" .
              '  } else if (document.checkout_payment.payment.checked) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  } else if (document.checkout_payment.payment.value) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  }' . "\n\n";

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $js .= $GLOBALS[$class]->javascript_validation();
          }
        }

        $js .= "\n" . '  if (payment_value == null) {' . "\n" .
               '    error_message = error_message + "' . JS_ERROR_NO_PAYMENT_MODULE_SELECTED . '";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n\n" .
               '  if (error == 1) {' . "\n" .
               '    alert(error_message);' . "\n" .
               '    return false;' . "\n" .
               '  } else {' . "\n" .
               '    return true;' . "\n" .
               '  }' . "\n" .
               '}' . "\n" .
               '//--></script>' . "\n";
      }

      return $js;
    }

    function checkout_initialization_method() {
      $initialize_array = array();

      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled && method_exists($GLOBALS[$class], 'checkout_initialization_method')) {
            $initialize_array[] = $GLOBALS[$class]->checkout_initialization_method();
          }
        }
      }

      return $initialize_array;
    }

    function selection() {
	// INDIV_PM BEGIN (this is messy i know)
	  global $cart;
	  // New to fix attributes bug
	  $cart_products = $cart->get_products();
	  $real_ids = array();
	  foreach($cart_products as $prod){
	  	$real_ids[] = tep_get_prid($prod['id']);
	  }
		$sql = "SELECT payment_methods FROM ".TABLE_PRODUCTS." WHERE products_id IN (".implode(',',$real_ids).") AND payment_methods IS NOT NULL AND payment_methods <> ''";
		//$query = mysql_query($sql);
		$query = tep_db_query($sql);		
		
	  // End new bug fix
		$allow_mod_array = array();
		//while($rec = mysql_fetch_array($query)){
		while($rec = tep_db_fetch_array($query)){		
			if(empty($allow_mod_array)) $startedempty = true;
			$methods_array = array();
			$methods_array = explode(';',$rec['payment_methods']);
			if(!empty($methods_array)){
				foreach($methods_array as $method){
					$allow_mod_array[] = $method;
				}
			}
			if($startedempty){
				$startedempty = false;
			}else{
				$temp_array = array();
				foreach($allow_mod_array as $val){
					$temp_array[$val]++;
				}
				$allow_mod_array = array();
				foreach($temp_array as $key => $val){
					if($val > 1){
						$allow_mod_array[] = $key;
					}
				}
			}
		}
	  // INDIV_PM END	
      $selection_array = array();

      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));

          // INDIV_PM BEGIN (changed second condition to account for non-purchaseable combos)  
//		  if ($GLOBALS[$class]->enabled) {
          if ($GLOBALS[$class]->enabled && (tep_db_num_rows($query)==0 || in_array($class,$allow_mod_array))) {
	    // INDIV_PM END		  
            $selection = $GLOBALS[$class]->selection();
            if (is_array($selection)) $selection_array[] = $selection;
          }
        }
      }

      return $selection_array;
    }

    function pre_confirmation_check() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          $GLOBALS[$this->selected_module]->pre_confirmation_check();
        }
      }
    }

    function confirmation() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->confirmation();
        }
      }
    }

    function process_button() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->process_button();
        }
      }
    }

    function before_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->before_process();
        }
      }
    }

    function after_process() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->after_process();
        }
      }
    }

    function get_error() {
      if (is_array($this->modules)) {
        if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled) ) {
          return $GLOBALS[$this->selected_module]->get_error();
        }
      }
    }
  }
?>
