<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2013 osCommerce
  Released under the GNU General Public License
*/
  class jcs_javascript_grid_list_view {
    var $code = 'jcs_javascript_grid_list_view';
    var $group = 'javascript_css_bottom';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;
	
    function jcs_javascript_grid_list_view() {
      $this->title = MODULE_JAVA_CSS_GRID_LIST_VIEW_TITLE;
      $this->description = MODULE_JAVA_CSS_GRID_LIST_VIEW_DESCRIPTION;

      if ( defined('MODULE_JAVA_CSS_GRID_LIST_VIEW_STATUS') ) {
        $this->sort_order = MODULE_JAVA_CSS_GRID_LIST_VIEW_SORT_ORDER;
        $this->enabled = (MODULE_JAVA_CSS_GRID_LIST_VIEW_STATUS == 'True');
		$this->pages = MODULE_JAVA_CSS_GRID_LIST_VIEW_DISPLAY_PAGES;			
      }
    }

    function dataF() {
	
      if ( MODULE_JAVA_CSS_GRID_LIST_VIEW_LOCATION == 'Local' ) {
//         ob_start();
//           include('ext/jquery/' . MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_NAME );
//         $data = '<script>' . ob_get_clean() . '</script>' . PHP_EOL ;		  
         $data = '<script  type="text/javascript" src="ext/jquery/' . MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_NAME . '"></script>' ;
      } else {
         // use cdnjs server
	     $data = '<script   type="text/javascript" src="' . MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_LOCTION_SERVER . MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_NAME . '"></script>' ;
      }	   
      return $data ;
    }
 
    function execute() {
       global $oscTemplate; 
 
       $oscTemplate->addBlock( $this->dataF(), $this->group); // js can in bottom but script in the top
	   
	   $oscTemplate->addBlock('<script>$(function() { var cc = $.cookie(\'list_grid\'); 
	                              if (cc == \'list\') { 
								     $(\'#product-listing .inline-span\').
									 removeClass(\''. MODULE_JAVA_CSS_GRID_LIST_VIEW_DEFAULT .'-across fluid-'. MODULE_JAVA_CSS_GRID_LIST_VIEW_MOBILE .'-across\').
									 addClass(\'one-across fluid-one-across\');						  						  
								  } else { 
								     $(\'#product-listing .inline-span\').
	                                 removeClass(\'one-across fluid-one-across\').
									 addClass(\''. MODULE_JAVA_CSS_GRID_LIST_VIEW_DEFAULT .'-across fluid-'. MODULE_JAVA_CSS_GRID_LIST_VIEW_MOBILE .'-across\'); 
								  } 									 
							      }); 
								  $(document).ready(function() { $(\'#list\').click(function(event){event.preventDefault();$(\'#product-listing .inline-span\').removeClass(\''. MODULE_JAVA_CSS_GRID_LIST_VIEW_DEFAULT .'-across fluid-'. MODULE_JAVA_CSS_GRID_LIST_VIEW_MOBILE .'-across\').addClass(\'one-across fluid-one-across\');$.cookie(\'list_grid\', \'list\');}); $(\'#grid\').
									 click(function(event){event.preventDefault();$(\'#product-listing .inline-span\').
									 removeClass(\'one-across fluid-one-across\').
									 addClass(\''. MODULE_JAVA_CSS_GRID_LIST_VIEW_DEFAULT .'-across fluid-'. MODULE_JAVA_CSS_GRID_LIST_VIEW_MOBILE .'-across\');$.cookie(\'list_grid\', \'grid\');});});</script>' . "\n", 'javascript_css_top');

//       $oscTemplate->addBlock($this->dataF(), 'javascript_css' );
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_JAVA_CSS_GRID_LIST_VIEW_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Java ColorBox File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_SORT_ORDER', '170', 'Sort order of display. Lowest is displayed first.', '6', '10', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_DISPLAY_PAGES', 'advanced_search_result.php;index.php;products_new.php;specials.php;', 'select pages where this box should be displayed. ', '6', '100','tep_cfg_select_pages(' , now())");	  
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Default Desktop View', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_DEFAULT', 'three', 'Default products per row in grid view', '6', '2', 'tep_cfg_select_option(array(\'two\',\'three\', \'four\'), ', now())");
	  tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Default Mobile View', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_MOBILE', 'two', 'Default products per row in mobile grid view', '6', '3', 'tep_cfg_select_option(array(\'one\',\'two\'), ', now())");

      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Location Jquery Cookie JS File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_LOCATION', 'Local', 'Get the Jquery Cookie JS File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', '6', '20', 'tep_cfg_select_option(array(\'Local\', \'Cdnjs\'), ', now())");  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Name of Jquery Cookie JS File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_NAME', 'jquery.cookie.js', 'The name of the Jquery Cookie JS  file located on your local or CDNJS server.', '6', '30', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Location of Jquery Cookie JS File', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/', 'The location of the Jquery Cookie JS  file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', '6', '40', now())");	  	  	  
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_JAVA_CSS_GRID_LIST_VIEW_STATUS', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_SORT_ORDER', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_DEFAULT', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_MOBILE', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_NAME', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_JS_LOCTION_SERVER', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_LOCATION', 'MODULE_JAVA_CSS_GRID_LIST_VIEW_DISPLAY_PAGES' );
    }
  }
?>