<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2013 osCommerce
  Released under the GNU General Public License
*/
  class jcs_javascript_bootstrap_datepicker_file {
    var $code = 'jcs_javascript_bootstrap_datepicker_file';
    var $group = 'javascript_css_top';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;
	
    function jcs_javascript_bootstrap_datepicker_file() {
      $this->title = MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_TITLE;
      $this->description = MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_DESCRIPTION;

      if ( defined('MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_STATUS') ) {
        $this->sort_order = MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_SORT_ORDER;
        $this->enabled = (MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_STATUS == 'True');
		$this->pages = MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_DISPLAY_PAGES;			
      }
    }

    function dataF() {
      if ( MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_LOCATION == 'Local' ) {
         $data = '<script   type="text/javascript" src="ext/bootstrap/js/' . MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_NAME . '"></script>' ;
      } else {
         // use cdnjs server
	     $data = '<script   type="text/javascript" src="' . MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_LOCTION_SERVER . MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_NAME . '"></script>' ;
      }	  
      return $data ;
    }
 
    function execute() {
       global $oscTemplate; 
       $oscTemplate->addBlock($this->dataF(), $this->group );
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Java DatePicker File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_STATUS', 'True', 'Activate this JavaScript DatePicker Module if you want to speed up the load time of the main page an product listing of your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '10', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Location Java DatePicker File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_LOCATION', 'Local', 'Get the Jquery DatePicker File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', '6', '20', 'tep_cfg_select_option(array(\'Local\', \'Cdnjs\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Name of Java DatePicker File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_NAME', 'bootstrap-datepicker.min.js', 'The name of the DatePicker Javascript file located on your local or CDNJS server.', '6', '30', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Location of Java DatePicker File', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/js/', 'The location of the DatePicker Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', '6', '40', now())");	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_DISPLAY_PAGES', 'account_edit;', 'select pages where this box should be displayed. ', '6', '100','tep_cfg_select_pages(' , now())");	  
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_STATUS', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_SORT_ORDER', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_LOCATION', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_NAME', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_JS_LOCTION_SERVER', 'MODULE_JAVA_CSS_BOOTSTRAP_DATEPICKER_FILE_DISPLAY_PAGES' );
    }
  }
?>