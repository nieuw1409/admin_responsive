<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2013 osCommerce
  Released under the GNU General Public License
*/
  class jcs_javascript_colorbox_file {
    var $code = 'jcs_javascript_colorbox_file';
    var $group = 'javascript_css_bottom';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;
	
    function jcs_javascript_colorbox_file() {
      $this->title = MODULE_JAVA_CSS_COLORBOX_FILE_TITLE;
      $this->description = MODULE_JAVA_CSS_COLORBOX_FILE_DESCRIPTION;

      if ( defined('MODULE_JAVA_CSS_COLORBOX_FILE_STATUS') ) {
        $this->sort_order = MODULE_JAVA_CSS_COLORBOX_FILE_SORT_ORDER;
        $this->enabled = (MODULE_JAVA_CSS_COLORBOX_FILE_STATUS == 'True');
		$this->pages = MODULE_JAVA_CSS_COLORBOX_FILE_DISPLAY_PAGES;			
      }
    }

    function dataF() {
      if ( MODULE_JAVA_CSS_COLORBOX_FILE_LOCATION == 'Local' ) {
         $data = '<script  type="text/javascript" src="ext/colorbox/' . MODULE_JAVA_CSS_COLORBOX_FILE_JS_NAME . '"></script>' ;
      } else {
         // use cdnjs server
	     $data = '<script type="text/javascript" src="' . MODULE_JAVA_CSS_COLORBOX_FILE_JS_LOCTION_SERVER . MODULE_JAVA_CSS_COLORBOX_FILE_JS_NAME . '"></script>' ;
      }	   
      return $data ;
    }
 
    function execute() {
       global $oscTemplate, $photoset_layout; 
       $oscTemplate->addBlock($this->dataF(), $this->group  );
       $oscTemplate->addBlock('<script  type="text/javascript">var ImgCount = $("#piGal").data("imgcount");$(function() {
  $("#piGal").css({
    visibility: "hidden"
  });

  $("#piGal").photosetGrid({
    layout: "13",
    width: "100%",
    highresLinks: true,
	gutter: "5px", 
    rel: "pigallery",
    onComplete: function() {
      $("#piGal").css({ "visibility": "visible"});

      $("#piGal a").colorbox({
        maxHeight: "100%",
        maxWidth: "100%",
        rel: "pigallery"
      });

      $("#piGal img").each(function() {
        var imgid = $(this).attr("id").substring(9);

        if ( $("#piGalDiv_" + imgid).length ) {
          $(this).parent().colorbox({ inline: true, href: "#piGalDiv_" + imgid });
        }
      });
    }
  });
});</script>', 'javascript_css_top');
	   
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_JAVA_CSS_COLORBOX_FILE_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_STATUS', 'True', 'Activate this JavaScript ColorBox Module if you want to speed up the load time of the main page an product listing of your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_JAVA_CSS_COLORBOX_FILE_SORT_ORDER', '160', 'Sort order of display. Lowest is displayed first.', '6', '10', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Location Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_LOCATION', 'Local', 'Get the Jquery ColorBox File from your Local Website ( Local ) or from a CDN file server ( Cdnjs ) ?', '6', '20', 'tep_cfg_select_option(array(\'Local\', \'Cdnjs\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Name of Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_JS_NAME', 'jquery.colorbox-min.js', 'The name of the Javascript file located on your local or CDNJS server.', '6', '30', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Location of Java ColorBox File', 'MODULE_JAVA_CSS_COLORBOX_FILE_JS_LOCTION_SERVER', '//cdnjs.cloudflare.com/ajax/libs/jquery.colorbox/1.4.33/', 'The location of the Javascript file located on the CDNJS server. With trailing backslash. <br />If you have chosen for the Local use on your own fileserver the file must be located in ext/jquery/', '6', '40', now())");	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_JAVA_CSS_COLORBOX_FILE_DISPLAY_PAGES', 'product_info.php;', 'select pages where this box should be displayed. ', '6', '100','tep_cfg_select_pages(' , now())");	  
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_JAVA_CSS_COLORBOX_FILE_STATUS', 'MODULE_JAVA_CSS_COLORBOX_FILE_SORT_ORDER', 'MODULE_JAVA_CSS_COLORBOX_FILE_LOCATION', 'MODULE_JAVA_CSS_COLORBOX_FILE_JS_NAME', 'MODULE_JAVA_CSS_COLORBOX_FILE_JS_LOCTION_SERVER', 'MODULE_JAVA_CSS_COLORBOX_FILE_DISPLAY_PAGES' );
    }
  }
?>