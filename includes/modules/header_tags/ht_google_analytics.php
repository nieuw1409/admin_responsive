<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License

  Google Analytics universal modification 2014/05/12 by:
  @raiwa (www.sarplataygemas.com)
*/

  class ht_google_analytics {
    var $code = 'ht_google_analytics';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages = SYS_DISPLAY_ALL_PAGES ;	

    function ht_google_analytics() {
      $this->title = MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_TITLE;
      $this->description = MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_STATUS == 'True');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $customer_id;

      if (tep_not_null(MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_ID) && tep_not_null(MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_AFFILIATION)) {
        if (MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_JS_PLACEMENT != 'Header') {
          $this->group = 'footer_scripts';
        }

        $header = '<script>;' . "\n";
        $header .= "  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');" . "\n";
  
        $header .= "  ga('create', '" . tep_output_string(MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_ID) . "', '" . STORE_NAME . "');
  ga('send', 'pageview');" . "\n";

        if ( (MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_EC_TRACKING == 'True') && (basename($PHP_SELF) == FILENAME_CHECKOUT_SUCCESS) && tep_session_is_registered('customer_id') ) {
          $order_query = tep_db_query("select orders_id, billing_city, billing_state, billing_country from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");

          if (tep_db_num_rows($order_query) == 1) {
            $order = tep_db_fetch_array($order_query);

            $totals = array();

            $order_totals_query = tep_db_query("select value, class from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order['orders_id'] . "'");
            while ($order_totals = tep_db_fetch_array($order_totals_query)) {
              $totals[$order_totals['class']] = $order_totals['value'];
            }

            $header .= "  ga('require', 'ecommerce', 'ecommerce.js');" . "\n";
            $header .= "  ga('ecommerce:addTransaction',{
    'id': '" . (int)$order['orders_id'] . "',
    'affiliation': '" . STORE_NAME . "',
    'revenue': '" . (isset($totals['ot_total']) ? $this->format_raw($totals['ot_total'], DEFAULT_CURRENCY) : 0) . "',
    'shipping': '" . (isset($totals['ot_shipping']) ? $this->format_raw($totals['ot_shipping'], DEFAULT_CURRENCY) : 0) . "', 
    'tax': '" . (isset($totals['ot_tax']) ? $this->format_raw($totals['ot_tax'], DEFAULT_CURRENCY) : 0) . "'
  });" . "\n";

            $order_products_query = tep_db_query("select op.products_id, pd.products_name, op.final_price, op.products_quantity from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_LANGUAGES . " l where op.orders_id = '" . (int)$order['orders_id'] . "' and op.products_id = pd.products_id and l.code = '" . tep_db_input(DEFAULT_LANGUAGE) . "' and l.languages_id = pd.language_id");
            while ($order_products = tep_db_fetch_array($order_products_query)) {
              $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_LANGUAGES . " l where p2c.products_id = '" . (int)$order_products['products_id'] . "' and p2c.categories_id = cd.categories_id and l.code = '" . tep_db_input(DEFAULT_LANGUAGE) . "' and l.languages_id = cd.language_id limit 1");
              $category = tep_db_fetch_array($category_query);

              $header .= "  ga('ecommerce:addItem', {
    'id': '" . (int)$order['orders_id'] . "',
    'sku': '" . (int)$order_products['products_id'] . "',
    'name': '" . tep_output_string($order_products['products_name']) . "',
    'category': '" . tep_output_string($category['categories_name']) . "',
    'price': '" . $this->format_raw($order_products['final_price']) . "',
    'quantity': '" . (int)$order_products['products_quantity'] . "'
  });" . "\n";
            }

            $header .= "  ga('ecommerce:send');" . "\n";
          }
        }

        $header .= '</script>' . "\n";

        $oscTemplate->addBlock($header, $this->group);
      }
    }

    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code) || !$currencies->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Google Analytics Module', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_STATUS', 'True', 'Do you want to add Google Analytics to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Google Analytics ID', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_ID', '', 'The Google Analytics profile ID to track.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('E-Commerce Tracking', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_EC_TRACKING', 'True', 'Do you want to enable e-commerce tracking? (E-Commerce tracking must also be enabled in your Google Analytics profile settings)', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Javascript Placement', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_JS_PLACEMENT', 'Header', 'Should the Google Analytics javascript be loaded in the header or footer?', '6', '1', 'tep_cfg_select_option(array(\'Header\', \'Footer\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_STATUS', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_ID', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_EC_TRACKING', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_JS_PLACEMENT', 'MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_SORT_ORDER');
    }
  }
?>
