<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

////
// Class to handle currencies
// TABLES: currencies
  class currencies {
    var $currencies;

// class constructor
    function currencies() {
//      $this->currencies = array();
//      $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
//      while ($currencies = tep_db_fetch_array($currencies_query)) {
//        $this->currencies[$currencies['code']] = array('title' => $currencies['title'],
//                                                       'symbol_left' => $currencies['symbol_left'],
//                                                       'symbol_right' => $currencies['symbol_right'],
//                                                       'decimal_point' => $currencies['decimal_point'],
//                                                       'thousands_point' => $currencies['thousands_point'],
//                                                       'decimal_places' => $currencies['decimal_places'],
//                                                       'value' => $currencies['value']);
//      }
	  global $cache;
      $this->currencies = array();
	  if (USE_CACHE == 'true') { 
	    $cache_name = 'currencies';
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
	  
		  $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES . " where find_in_set('" . SYS_STORES_ID . "', currencies_to_stores) != 0" );
		  while ($currencies = tep_db_fetch_array($currencies_query)) {
			$this->currencies[$currencies['code']] = array('title' => $currencies['title'],
														   'symbol_left' => $currencies['symbol_left'],
														   'symbol_right' => $currencies['symbol_right'],
														   'decimal_point' => $currencies['decimal_point'],
														   'thousands_point' => $currencies['thousands_point'],
														   'decimal_places' => (int)$currencies['decimal_places'], // 2.3.3.3
														   'value' => $currencies['value'], 
														   'code'  => $currencies['code']);														   
		  }
		  $cache->save_cache($cache_name, $this->currencies, 'ARRAY', 0, 0, '30/days');
	    } else {
	  	  $this->currencies = $cache->get_cache($cache_name, 'ARRAY');
	    }
	  } else {
		  $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES . " where find_in_set('" . SYS_STORES_ID . "', currencies_to_stores) != 0");
		  while ($currencies = tep_db_fetch_array($currencies_query)) {
			$this->currencies[$currencies['code']] = array('title' => $currencies['title'],
														   'symbol_left' => $currencies['symbol_left'],
														   'symbol_right' => $currencies['symbol_right'],
														   'decimal_point' => $currencies['decimal_point'],
														   'thousands_point' => $currencies['thousands_point'],
														   'decimal_places' => $currencies['decimal_places'],
														   'value' => $currencies['value'], 
														   'code'  => $currencies['code']);
          }														   
	  }
    }

// class methods
    function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
      global $currency;

      if (empty($currency_type)) $currency_type = $currency;

      if ($calculate_currency_value == true) {
        $rate = (tep_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
      } else {
        $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
      }

// BOF: WebMakers.com Added: Down for Maintenance
      if (DOWN_FOR_MAINTENANCE=='true' and DOWN_FOR_MAINTENANCE_PRICES_OFF=='true') {
        $format_string= '';
      }
// BOF: WebMakers.com Added: Down for Maintenance

        return $format_string;
    }

    function calculate_price($products_price, $products_tax, $quantity = 1) {
      global $currency;

      return tep_round(tep_add_tax($products_price, $products_tax), $this->currencies[$currency]['decimal_places']) * $quantity;
    }

    function is_set($code) {
      if (isset($this->currencies[$code]) && tep_not_null($this->currencies[$code])) {
        return true;
      } else {
        return false;
      }
    }

    function get_value($code) {
      return $this->currencies[$code]['value'];
    }

    function get_decimal_places($code) {
      return $this->currencies[$code]['decimal_places'];
    }

    function display_price($products_price, $products_tax, $quantity = 1) {
      return $this->format($this->calculate_price($products_price, $products_tax, $quantity));
    }
  }
?>
