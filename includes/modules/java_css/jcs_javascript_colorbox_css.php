<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2013 osCommerce
  Released under the GNU General Public License
*/
  class jcs_javascript_colorbox_css {
    var $code = 'jcs_javascript_colorbox_css';
    var $group = 'javascript_css_bottom';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;
	
    function jcs_javascript_colorbox_css() {
      $this->title = MODULE_JAVA_CSS_COLORBOX_CSS_TITLE;
      $this->description = MODULE_JAVA_CSS_COLORBOX_CSS_DESCRIPTION;

      if ( defined('MODULE_JAVA_CSS_COLORBOX_CSS_STATUS') ) {
        $this->sort_order = MODULE_JAVA_CSS_COLORBOX_CSS_SORT_ORDER;
        $this->enabled = (MODULE_JAVA_CSS_COLORBOX_CSS_STATUS == 'True');
		$this->pages = MODULE_JAVA_CSS_COLORBOX_CSS_DISPLAY_PAGES;			
      }
    }
	
    function dataF() {
      if ( MODULE_JAVA_CSS_COLORBOX_CSS_LOCATION == 'Local' ) {
	    if ( MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_STATUS == 'True' ) {
           $data .= '<script>' . PHP_EOL ;	 
           $data .= 'loadCSS("ext/colorbox/' . MODULE_JAVA_CSS_COLORBOX_CSS_JS_NAME . '");' . PHP_EOL ;
         $data .= '</script>' . PHP_EOL ;

         $data .= '<noscript>' . PHP_EOL ;
         $data .= '<link href="ext/colorbox/' . MODULE_JAVA_CSS_COLORBOX_CSS_JS_NAME . '" rel="stylesheet">' . PHP_EOL ;
        $data .= '</noscript>	  ' . PHP_EOL ;		  
        } else {		  
          $data = '<link rel="stylesheet" type="text/css" href="ext/colorbox/' . MODULE_JAVA_CSS_COLORBOX_CSS_JS_NAME . '">' ;
		}
      } else {
         // use cdnjs server
	     $data = '<link rel="stylesheet" type="text/css" href="' . MODULE_JAVA_CSS_COLORBOX_CSS_JS_LOCTION_SERVER . MODULE_JAVA_CSS_COLORBOX_CSS_JS_NAME . '">' ;
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
      return defined('MODULE_JAVA_CSS_COLORBOX_CSS_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_JAVA_CSS_COLORBOX_CSS_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', '6', '10', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Location CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_LOCATION', 'Local', 'Get the Jquery ColorBox File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', '6', '20', 'tep_cfg_select_option(array(\'Local\', \'Cdnjs\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Name of CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_JS_NAME', 'colorbox.css', 'The name of the Javascript file located on your local or CDNJS server.', '6', '30', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Location of CSS ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_CSS_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', '6', '40', now())");	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_JAVA_CSS_COLORBOX_CSS_DISPLAY_PAGES', 'product_info.php;', 'select pages where this box should be displayed. ', '6', '100','tep_cfg_select_pages(' , now())");	  
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_JAVA_CSS_COLORBOX_CSS_STATUS', 'MODULE_JAVA_CSS_COLORBOX_CSS_SORT_ORDER', 'MODULE_JAVA_CSS_COLORBOX_CSS_LOCATION', 'MODULE_JAVA_CSS_COLORBOX_CSS_JS_NAME', 'MODULE_JAVA_CSS_COLORBOX_CSS_JS_LOCTION_SERVER', 'MODULE_JAVA_CSS_COLORBOX_CSS_DISPLAY_PAGES' );
    }
  }
?>