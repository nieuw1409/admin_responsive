<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  browser stores detection logic Copyright phpMyAdmin (select_lang.lib.php3 v1.24 04/19/2002)
                                   Copyright Stephane Garin <sgarin@sgarin.com> (detect_language.php v0.1 04/02/2002)
*/

  class multi_stores {
    var $stores, $available_stores, $browser_stores, $default_store, $default_number ;

    function multi_stores($strs = '') {
      $this->default_number == 0 ;
      $this->available_stores = array();
      $stores_query = tep_db_query("select stores_id, stores_name, stores_image, stores_config_table, stores_status, stores_url, stores_absolute, stores_std_cust_group, stores_admin_color from " . TABLE_STORES . " order by stores_id");
      while ($stores = tep_db_fetch_array($stores_query)) {
	    if ( $this->default_number == 0 ) {
		  $this->default_number = $stores['stores_id'] ; // set first store as default
		}
        $this->available_stores[$stores['stores_id']] = array('id'             => $stores['stores_id'],
                                                              'name'           => $stores['stores_name'],
                                                              'image'          => $stores['stores_image'],
                                                              'url'            => $stores['stores_url'],															  
                                                              'absolute'       => $stores['stores_absolute'],																  
                                                              'configuration'  => $stores['stores_config_table'],
															  'status'         => $stores['stores_status'],
                                                              'std_cust_group' => $stores['stores_std_cust_group'],
                                                              'admin_color'    => $stores['stores_admin_color'] );
      }

      $this->browser_stores = '';
      $this->default_store = '';
      $this->set_multi_stores($this->default_number);
    }

    function set_multi_stores($stores) {
      if ( (tep_not_null($stores)) && (isset($this->available_stores[$stores])) ) {
        $this->default_store = $this->available_stores[$stores];
      } else {
        $this->default_store = $this->available_stores[$this->default_number];
      }
    }

    function get_browser_language() {
      $this->browser_stores = explode(',', getenv('HTTP_ACCEPT_LANGUAGE'));

      for ($i=0, $n=sizeof($this->browser_stores); $i<$n; $i++) {
        reset($this->stores);
        while (list($key, $value) = each($this->stores)) {
          if (preg_match('/^(' . $value . ')(;q=[0-9]\\.[0-9])?$/i', $this->browser_stores[$i]) && isset($this->available_stores[$key])) {
            $this->default_store = $this->available_stores[$key];
            break 2;
          }
        }
      }
    }
  }
?>
