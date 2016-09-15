<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Ideal Lite Payment module for oscommerce 2.3.1
 *
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3 of the GNU license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl-3.0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Payment Module
 * @package    Ideal Lite
 * @author     Michiel Rop <mrop@xs4all.nl>
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html 
 * @version    SVN: $Id: ideal_lite.php 11 2011-02-15 13:35:26Z michiel $
 * @since      File available since Release 1.0.0
 */

// class loading mechanism of oscommerce cannot deal with
// naming php naming convention, that's why this class starts with
// a low cast character
class ideal_lite
{
    // {{{properties
    /**
     * The title title of the payment module
     * @var string
     */
    var $title;
    /**
     * The description of the payment module
     * @var string
     */
    var $description;
    /**
     * The enabled status of this module
     * @var bool
     */
    var $enabled;
    // }}}
	var $payment ;


    // {{{ ideal_lite()
    /**
     * Initializes an Ideal_lite instance
     */
    function Ideal_lite(){
        global $order;
        $this->signature ='iDEAL Lite|iDEAL Lite|1.0|1.0';
        $this->api_version= "1.0.0";
        $this->code='ideal_lite';
        $this->title = MODULE_PAYMENT_IDEAL_LITE_TEXT_TITLE;
        $this->public_title = MODULE_PAYMENT_IDEAL_LITE_TEXT_PUBLIC_TITLE;
        $this->description = MODULE_PAYMENT_IDEAL_LITE_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_PAYMENT_IDEAL_LITE_SORT_ORDER;
        $this->enabled = ((MODULE_PAYMENT_IDEAL_LITE_STATUS == 'True') ? true:false);
        $this->payment = MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS;	  		


        if ((int)MODULE_PAYMENT_IDEAL_LITE_ORDER_STATUS_ID > 0) {          
            $this->order_status = MODULE_PAYMENT_IDEAL_LITE_ORDER_STATUS_ID;
        }

        if (is_object($order)){
            $this->update_status();
        }
        $this->form_action_url = MODULE_PAYMENT_IDEAL_LITE_URL;
    }
    //}}}


    // class methods
    //{{{ update_status
    function update_status(){
// START Shipping dependency: disable the module if there is a list of allowed shipping method
// and the choosen method is not in selected shipping method
      if ($this->enabled == true) {
	  global $shipping;
	    if (tep_not_null(MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS)) {
		  if ( MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS != 'all' ) {
	        $ship_method=split ("_",$shipping['id']);
		    $ship_allowed=split (";",MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS);
		    if (in_array($ship_method[0],$ship_allowed )==false) {
               $this->enabled = false;
            }
		  }  
		}
      }
// END Shipping dependency	 	
    }
    //}}}

    //{{{ javascript_validation
    function javascript_validation(){
        return false;
    }
    // }}}

    //{{{ selection()
    /**
      * Returns and hash with keys 'id' and 'module'
      */
    function selection(){
        return array('id' => $this->code,
                'module' => $this->public_title);
    }
    //}}}

    //{{{ pre_confirmation_check()
    function pre_confirmation_check(){
        global $cartID, $cart;

        if (empty($cart->cartID)){
            $cartID = $cart->cartID = $cart->generate_card_id();
        }

        if (!tep_session_is_registered('cartID')){
            tep_session_register('cartID');
        }
    }
    //}}}

    //{{{ confirmation
    function confirmation(){
        return false;
    }
    //}}}

    //{{{ process_button
    function process_button(){
        global $_POST, $customer_id, $order, $currencies;

        $validUntil = date("Y-m-d\TG:i:s\Z",strtotime ("+1 week"));

        // v1.3 Modification -  Jeroen de Grebber - start
        // original: $ideal_lite_orderID = $customer_id . date('YmdHis');
        // bij ING iDEAL is maximale lengte 16 tekens. Met YmdHis wordt dit 18 tekens:
        // ymdHis geeft 16 tekens, eerste 2 getallen van jaar zijn ook niet zo belangrijk

        $ideal_lite_orderID = $customer_id . date('ymdHis');
        // v1.3 Modification -  Jeroen de Grebber - end

        $totaal = $order->info['total'];
        $bedrag = str_replace(',', '', $totaal);
        $bedrag = round($bedrag,2);
        $ideal_lite_amount= $bedrag *100;

        $key=MODULE_PAYMENT_IDEAL_LITE_SHA_STRING;
        $merchantID=MODULE_PAYMENT_IDEAL_LITE_PSPID;
        $subID='0';
        $amount=$ideal_lite_amount;
        $orderNumber=$ideal_lite_orderID;
        $paymentType='ideal';
        $itemNumber1='1234';
        $itemDescription1='ARTIKEL';
        $itemQuantity1='1';
        $itemPrice1=$ideal_lite_amount;

        // ### bereken alvast een deel van de SHA string ###
        $partOfSha = $partOfSha . $itemNumber1 . $itemDescription1 . $itemQuantity1 . $itemPrice1;

        // ### bouw de String op waarover een SHA1 moet worden berekend ###
        $shastring = "$key" . "$merchantID" . "$subID"  . "$amount" . "$orderNumber" . "$paymentType" . "$validUntil" . $partOfSha ;

        // ###speciale HTML entiteiten verwijderen:
        $clean_shaString = HTML_entity_decode($shastring);

        // ### De tekens "\t", "\n", "\r", " " (spaties) mogen niet voorkomen in de string

        $not_allowed = array("\t", "\n", "\r", " ");
        $clean_shaString = str_replace($not_allowed, "",$clean_shaString);

        $shasign = sha1($clean_shaString);

        $process_button_string = 
            tep_draw_hidden_field('merchantID', MODULE_PAYMENT_IDEAL_LITE_PSPID) .
            tep_draw_hidden_field('subID','0') .
            tep_draw_hidden_field('amount', $ideal_lite_amount).
            tep_draw_hidden_field('purchaseID', $ideal_lite_orderID) .
            tep_draw_hidden_field('language', MODULE_PAYMENT_IDEAL_LITE_LANGUAGE) .
            tep_draw_hidden_field('currency', $order->info['currency']) .
            tep_draw_hidden_field('description','Order webwinkel') .
            tep_draw_hidden_field('hash',$shasign).
            tep_draw_hidden_field('paymentType','ideal') .
            tep_draw_hidden_field('validUntil',$validUntil).
            tep_draw_hidden_field('itemNumber1','1234') .
            tep_draw_hidden_field('itemDescription1','ARTIKEL') .
            tep_draw_hidden_field('itemQuantity1','1') .
            tep_draw_hidden_field('itemPrice1', $ideal_lite_amount).
            tep_draw_hidden_field('urlSuccess', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL')) .
            tep_draw_hidden_field('urlError', tep_href_link(FILENAME_SHOPPING_CART)) .
            tep_draw_hidden_field('exceptionurl', tep_href_link(FILENAME_SHOPPING_CART)) .
            tep_draw_hidden_field('urlCancel', tep_href_link(FILENAME_SHOPPING_CART)) .
            tep_draw_hidden_field('CN', $order->customer['firstname'] . ' ' . $order->customer['lastname']) .
            tep_draw_hidden_field('catalogurl', tep_href_link(FILENAME_DEFAULT)) .
            tep_draw_hidden_field('owneraddress', $order->delivery['street_address']) .
            tep_draw_hidden_field('ownerZIP', $order->delivery['postcode']) .
            tep_draw_hidden_field('COM', MODULE_PAYMENT_IDEAL_LITE_COM_DESCRIPTION . $customer_id) .
            tep_draw_hidden_field('email', $order->customer['email_address']);

        return $process_button_string;
    }
    //}}} 

    //{{{ before_process
    function before_process(){
        return false;
    }
    //}}}

    //{{{ after_process
    function after_process(){
        return false;
    }
    //}}}

    //{{{ output_error()
    function output_error(){
        return false;
    }
    //}}}

    //{{{ check()
    function check(){
		  GLOBAL $multi_stores_config ;
        if (!isset($this->check)) {
            $check_query = tep_db_query("select configuration_value from " . $multi_stores_config . " where configuration_key = 'MODULE_PAYMENT_IDEAL_LITE_STATUS'");
            $this->check = tep_db_num_rows($check_query);
        }
        return $this->check;
    }
    //}}}

    //{{{ install()
    /**
     *	Inserts rows to $multi_stores_config with data associated with this iDEAL Lite module
     *
     */
    function install(){
	  GLOBAL $multi_stores_config ;
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow iDEAL Payments', 'MODULE_PAYMENT_IDEAL_LITE_STATUS', 'True', 'Do you want to accept iDEAL payments?', '6', '20', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
       tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_IDEAL_LITE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");	
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('iDEAL Status Mode', 'MODULE_PAYMENT_IDEAL_LITE_MODE', 'test', 'Status mode for IDEAL payments? (test or prod)', '6', '21', 'tep_cfg_select_option(array(\'test\', \'prod\'), ', now())");
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iDEAL Merchant ID', 'MODULE_PAYMENT_IDEAL_LITE_PSPID', 'TESTSTD', 'Merchant ID', '6', '22', now())");
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iDEAL Client Language', 'MODULE_PAYMENT_IDEAL_LITE_LANGUAGE', 'nl', 'Client language', '6', '23', now())");
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iDEAL SHA String', 'MODULE_PAYMENT_IDEAL_LITE_SHA_STRING', '', 'SHA string used for the signature (set at the merchant administration page)', '6', '24', now())");
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iDEAL URL', 'MODULE_PAYMENT_IDEAL_LITE_URL', 'https://', 'Bank url', '6', '75', now())");
        // Minimum amount - start
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iDEAL Min amount', 'MODULE_PAYMENT_IDEAL_LITE_MIN_AMOUNT', '10', 'The minimum amount to make the iDEAL payment method available', '6', '80', now())");
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_IDEAL_LITE_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '90', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Payment in combination with Shipping.', 'MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS', 'all', 'select shipping method to be used with this payment option. ', '6', '100','tep_cfg_select_payment(' , now())");		
    }
    //}}}

    //{{{
    /**
     * Deletes all rows from the configuration table associated with this iDEAL Lite modele
     */
    function remove(){
	  GLOBAL $multi_stores_config ;
        tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
    //}}}


    //{{{ keys()
    /**
     * Returns an array of configuration_keys that are used in the table $multi_stores_config
     * @return array with configuration_key 's
     */
    function keys(){
        return array('MODULE_PAYMENT_IDEAL_LITE_STATUS'
                , 'MODULE_PAYMENT_IDEAL_LITE_MODE'
                , 'MODULE_PAYMENT_IDEAL_LITE_PSPID'
                , 'MODULE_PAYMENT_IDEAL_LITE_SORT_ORDER'
                , 'MODULE_PAYMENT_IDEAL_LITE_SHA_STRING'
                , 'MODULE_PAYMENT_IDEAL_LITE_URL'
                , 'MODULE_PAYMENT_IDEAL_LITE_LANGUAGE'
                , 'MODULE_PAYMENT_IDEAL_LITE_MIN_AMOUNT'
                , 'MODULE_PAYMENT_IDEAL_LITE_ORDER_STATUS_ID'
				, 'MODULE_PAYMENT_IDEAL_LITE_USE_PAYMENTS');
    }
    //}}}
}
?>