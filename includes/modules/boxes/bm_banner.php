<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  ******* banner box for Advertisement ******************************
  released under the GNU General Public License
*/
  class bm_banner {
    var $code = 'bm_banner';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;	

    function bm_banner() {
      $this->title = MODULE_BOXES_BANNER_TITLE;
      $this->description = MODULE_BOXES_BANNER_DESCRIPTION;

      if ( defined('MODULE_BOXES_BANNER_STATUS') ) {
        $this->sort_order = MODULE_BOXES_BANNER_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_BANNER_STATUS == 'True');
		$this->pages = MODULE_BOXES_BANNER_DISPLAY_PAGES;		

	    $placement = MODULE_BOXES_BANNER_CONTENT_PLACEMENT;
        switch($placement) {
			case "Left Column" :
				$this->group = 'boxes_column_left';
				break;
			case "Right Column" :
				$this->group = 'boxes_column_right';
				break;
			case "Bread Column" :
				$this->group = 'boxes_column_bread';
				break;
			case "Head Column" :
				$this->group = 'boxes_column_head';
				break;
			case "Foot Column" :
				$this->group = 'boxes_column_foot';
				break;	
            case 'Left Header' : 
			    $this->group = 'header_contents_left';
                break;
            case 'Center Header' : 
			    $this->group = 'header_contents_center';
                break;
            case 'Right Header' : 
			    $this->group = 'header_contents_right';
                break;
            case 'Header Line' :  
			    $this->group = 'header_line';
                break;
            case 'Left Footer' : 
			    $this->group = 'footer_contents_left';
                break;
            case 'Center Footer' : 
			    $this->group = 'footer_contents_center';
                break;
            case 'Right Footer' : 
			    $this->group = 'footer_contents_right';
                break;
            case 'Footer Line' : 
			    $this->group = 'footer_line';
                break;				
		}
      }
    }

function execute() {
      global $oscTemplate;
		if ($banner = tep_banner_exists('dynamic',  MODULE_BOXES_BANNER_GROUP )) {
           ob_start();
              include(DIR_WS_MODULES . 'boxes/templates/banner.php');
           $data = ob_get_clean();

		    $oscTemplate->addBlock($data, $this->group);
		}
}

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_BANNER_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Banner Module', 'MODULE_BOXES_BANNER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Box heading', 'MODULE_BOXES_BANNER_HEADING', 'True', 'Do you want to add the infobox heading to box?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Box contents', 'MODULE_BOXES_BANNER_CONTENTS', 'True', 'Do you want to add the infobox content to box?<br>Box heading = False This is only possible with False!', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_BANNER_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_BANNER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
	  tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Banner  Group', 'MODULE_BOXES_BANNER_GROUP', 'banner', 'Name of the banner group that the Banner box uses to show the banners.<br> Tools / banner manager to enter the banner group settings<br><strong>The image width can not be larger than the column width!<br> </strong>', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_BANNER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_BANNER_STATUS', 'MODULE_BOXES_BANNER_CONTENT_PLACEMENT', 'MODULE_BOXES_BANNER_SORT_ORDER', 'MODULE_BOXES_BANNER_HEADING',  'MODULE_BOXES_BANNER_CONTENTS','MODULE_BOXES_BANNER_GROUP', 'MODULE_BOXES_BANNER_DISPLAY_PAGES');
    }
  }
?>