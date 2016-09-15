<?php
/*
  $Id$
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_manufacturer_info {
    var $code = 'bm_manufacturer_info';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_manufacturer_info() {
      $this->title = MODULE_BOXES_MANUFACTURER_INFO_TITLE;
      $this->description = MODULE_BOXES_MANUFACTURER_INFO_DESCRIPTION;
	  $this->pages = MODULE_BOXES_MANUFACTURER_INFO_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_MANUFACTURER_INFO_STATUS') ) {
        $this->sort_order = MODULE_BOXES_MANUFACTURER_INFO_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_MANUFACTURER_INFO_STATUS == 'True');

       // $this->group = ((MODULE_BOXES_MANUFACTURER_INFO_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_MANUFACTURER_INFO_CONTENT_PLACEMENT;
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
      global $HTTP_GET_VARS, $languages_id, $oscTemplate, $cache ;

      if (isset($HTTP_GET_VARS['products_id'])) {
        $manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  
		     where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.manufacturers_id = m.manufacturers_id and find_in_set('" . SYS_STORES_ID . "', m.manufacturers_to_stores) != 0 ");
              if (tep_db_num_rows($manufacturer_query)) {
          $manufacturer = tep_db_fetch_array($manufacturer_query);

          $manufacturer_info_string = NULL;
          if (tep_not_null($manufacturer['manufacturers_image'])) $manufacturer_info_string .= '<div>' . tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '</div>';
          if (tep_not_null($manufacturer['manufacturers_url'])) $manufacturer_info_string .= '<div class="text-center"><a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(MODULE_BOXES_MANUFACTURER_INFO_BOX_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></div>';

          $data= NULL;
		  $title = True ;
/*          
          if ($this->group == 'boxes_product_page') $data .= '<div class="col-sm-4 product_box hidden-xs">';
        
		  $placement = MODULE_BOXES_MANUFACTURER_INFO_CONTENT_PLACEMENT;
          switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Head Column" :	
			case "Foot Column" :          
               $data .= '<div class="panel panel-default panel-primary">' .
                        '  <div class="panel-heading">' . MODULE_BOXES_MANUFACTURER_INFO_BOX_TITLE . '</div>' .
                        '  <div class="panel-body">' . $manufacturer_info_string . '</div>';
               $data .= '  <div class="panel-footer clearfix"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">' . MODULE_BOXES_MANUFACTURER_INFO_BOX_OTHER_PRODUCTS . '</a></div>';
               $data .= '</div>';
			  break ;
            case 'Left Header' : 
            case 'Center Header' : 
            case 'Right Header' :           
            case 'Left Footer' : 
            case 'Center Footer' :
            case 'Right Footer' :
            case 'Header Line' :			
            case 'Footer Line' : 			
			case "Bread Column" :
               $data .= '<div class="panel panel-default panel-primary">' .
                        '  <div class="panel-body">' . $manufacturer_info_string . '</div>';
               $data .= '  <div class="panel-footer clearfix"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">' . MODULE_BOXES_MANUFACTURER_INFO_BOX_OTHER_PRODUCTS . '</a></div>';
               $data .= '</div>';	
              break ;
		  }
			  
          if ($this->group == 'boxes_product_page') $data.= '</div>';
*/
          $manufacturer_info_heading = MODULE_BOXES_MANUFACTURER_INFO_BOX_TITLE ;
          ob_start();
             include(DIR_WS_MODULES . 'boxes/templates/manufacturer_info.php');
          $data = ob_get_clean();		  

          $oscTemplate->addBlock($data, $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_MANUFACTURER_INFO_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Manufacturer Info Module', 'MODULE_BOXES_MANUFACTURER_INFO_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_MANUFACTURER_INFO_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_MANUFACTURER_INFO_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_MANUFACTURER_INFO_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_MANUFACTURER_INFO_STATUS', 'MODULE_BOXES_MANUFACTURER_INFO_CONTENT_PLACEMENT', 'MODULE_BOXES_MANUFACTURER_INFO_SORT_ORDER', 'MODULE_BOXES_MANUFACTURER_INFO_DISPLAY_PAGES');
    }
  }
?>