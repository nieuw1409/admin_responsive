<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class ar_admin_login {
    var $code = 'ar_admin_login';
    var $title;
    var $description;
    var $sort_order = 0;
    var $minutes = 5;
    var $attempts = 3;
    var $identifier;
	var $pages = SYS_DISPLAY_ALL_PAGES ;	

    function ar_admin_login() {
      $this->title = MODULE_ACTION_RECORDER_ADMIN_LOGIN_TITLE;
      $this->description = MODULE_ACTION_RECORDER_ADMIN_LOGIN_DESCRIPTION;

      if ($this->check()) {
        $this->minutes = (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES;
        $this->attempts = (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS;
      }
    }

    function setIdentifier() {
      $this->identifier = tep_get_ip_address();
    }

    function canPerform($user_id, $user_name) {
      $check_query = tep_db_query("select id from " . TABLE_ACTION_RECORDER . " where module = '" . tep_db_input($this->code) . "' and (" . (!empty($user_name) ? "user_name = '" . tep_db_input($user_name) . "' or " : "") . " identifier = '" . tep_db_input($this->identifier) . "') and date_added >= date_sub(now(), interval " . (int)$this->minutes  . " minute) and success = 0 order by date_added desc limit " . (int)$this->attempts);
      if (tep_db_num_rows($check_query) == $this->attempts) {
        return false;
      } else {
        return true;
      }
    }

    function expireEntries() {
// 2.3.3.3      global $db_link;

      tep_db_query("delete from " . TABLE_ACTION_RECORDER . " where module = '" . $this->code . "' and date_added < date_sub(now(), interval " . (int)$this->minutes  . " minute)");

//      return mysql_affected_rows($db_link); // 2.3.3.2
// 2.3.3.3      return tep_db_affected_rows($db_link);
      return tep_db_affected_rows(); // 2.3.3.3
    }

    function check() {
      return defined('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES');
    }

    function install() {
	  global $multi_stores_config;
	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Minutes', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES', '5', 'Number of minutes to allow login attempts to occur.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Attempts', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS', '3', 'Number of login attempts to allow within the specified period.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS');
    }
  }
?>