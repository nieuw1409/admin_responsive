<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2013 osCommerce
  Released under the GNU General Public License
*/
  class jcs_javascript_load_css_asynchr_file {
    var $code = 'jcs_javascript_load_css_asynchr_file';
    var $group = 'javascript_css_top';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;
	
    function jcs_javascript_load_css_asynchr_file() {
      $this->title = MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_TITLE;
      $this->description = MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_DESCRIPTION;

      if ( defined('MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_STATUS') ) {
        $this->sort_order = MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_SORT_ORDER;
        $this->enabled = (MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_STATUS == 'True');
		$this->pages = MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_DISPLAY_PAGES;			
      }
    }

    function dataF() {
//      if ( MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_LOCATION == 'Local' ) {
         $data = '<script>
                      /*!
                      loadCSS: load a CSS file asynchronously.
                      [c]2014 @scottjehl, Filament Group, Inc.
                      Licensed MIT
                      */
                      function loadCSS( href, before, media ){
                        "use strict";
                       
					    // Arguments explained:
                        // `href` is the URL for your CSS file.
                        // `before` optionally defines the element we will use as a reference for injecting our <link>
                        // By default, `before` uses the first <script> element in the page.
                        // However, since the order in which stylesheets are referenced matters, you might need a more specific location in your document.
                        // If so, pass a different reference element to the `before` argument and it will insert before that instead
                        // note: `insertBefore` is used instead of `appendChild`, for safety re: http://www.paulirish.com/2011/surefire-dom-element-insertion/
                        var ss = window.document.createElement( "link" );
                        var ref = before || window.document.getElementsByTagName( "script" )[ 0 ];
                        var sheets = window.document.styleSheets;
                        ss.rel = "stylesheet";
                        ss.href = href;
                        // temporarily, set media to something non-matching to ensure it will fetch without blocking render
                        ss.media = "only x";
                        // inject link
                        ref.parentNode.insertBefore( ss, ref );
                        // This function sets the link s media back to `all` so that the stylesheet applies once it loads
                        // It is designed to poll until document.styleSheets includes the new sheet.
                        function toggleMedia(){
                            var defined;
                            for( var i = 0; i < sheets.length; i++ ){
                               if( sheets[ i ].href && sheets[ i ].href.indexOf( href ) > -1 ){
                                  defined = true;
                               }
                            }
                            if( defined ){
                               ss.media = media || "all";
                            } else {
                              setTimeout( toggleMedia );
                            }
                        }
                        toggleMedia();
                        return ss;
                      }
                 </script>' ;
//      } else {
//         // use cdnjs server
//	     $data = '<script type="text/javascript" src="' . MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_JS_LOCTION_SERVER . MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_JS_NAME . '"></script>' ;
//      }	   
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
      return defined('MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Java Load CSS Asynchro File', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_STATUS', 'True', 'Activate this JavaScript Load CSS Asynchro  Module if you want to speed up the load time of the main page an product listing of your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_SORT_ORDER', '5', 'Sort order of display. Lowest is displayed first.', '6', '10', now())");
//      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Location Java PhotoGrid File', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_LOCATION', 'Local', 'Get the Jquery Load CSS Asynchro  File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', '6', '20', 'tep_cfg_select_option(array(\'Local\', \'Cdnjs\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Name of Java Load CSS Asynchro  File', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_JS_NAME', '', 'The name of the Javascript file located on your local or CDNJS server.', '6', '30', now())");
//      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Location of Java Load CSS Asynchro  File', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_JS_LOCTION_SERVER', '', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', '6', '40', now())");	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '100','tep_cfg_select_pages(' , now())");	  
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_STATUS', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_SORT_ORDER', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_DISPLAY_PAGES' );
	  //, 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_LOCATION', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_JS_NAME', 'MODULE_JAVA_CSS_LOAD_CSS_ASYNCH_FILE_JS_LOCTION_SERVER'
    }
  }
?>