<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce
  Portions Copyright 2011 oscommerce-solution.com

  Released under the GNU General Public License
*/

  class bm_header_tags {
    var $code = 'bm_header_tags';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;

    function bm_header_tags() {
      $this->title = MODULE_BOXES_HEADER_TAGS_TITLE;
      $this->description = MODULE_BOXES_HEADER_TAGS_DESCRIPTION;

      if ( defined('MODULE_BOXES_HEADER_TAGS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_HEADER_TAGS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_HEADER_TAGS_STATUS == 'True' && HEADER_TAGS_DISPLAY_COLUMN_BOX == 'true' && basename($_SERVER['PHP_SELF']) == FILENAME_PRODUCT_INFO);
        $this->pages = FILENAME_PRODUCT_INFO ;
 //       $this->group = ((MODULE_BOXES_HEADER_TAGS_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_HEADER_TAGS_CONTENT_PLACEMENT;
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
      global $oscTemplate, $languages_id;

      $product_info_query = tep_db_query("select pd.products_name, pd.products_description from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
	     where p.products_status = '1'                                              and p.products_id = '" . (int)$_GET['products_id'] . "' 
		  and pd.products_id = p.products_id                                        and pd.language_id = '" . (int)$languages_id . "' 
		  and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0  and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0");
      $product_info = tep_db_fetch_array($product_info_query);
      
	  $title = True ;
//      $header_title = substr(trim(strip_tags($product_info['products_name'])), 0, 20);
      if (tep_not_null($header_tags_array['title_alt'])) {
          $header_title = $header_tags_array['title_alt'];
      } else if (tep_not_null($header_tags_array['title'])) {
          $header_title = $header_tags_array['title'];
      } else {
          $header_title = $product_info['products_name'];
      }    
//      $data = '<div class="panel panel-default panel-primary">' .
      $header_title =  '<a class="label label-info" style="text-decoration:none;" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['products_id']).'"  >' . 
	                        substr(trim(strip_tags($product_info['products_name'])), 0, 20) . '</a>' ;
//              '  <div class="panel-body">' .
      $ht_description = strip_tags(substr($product_info['products_description'], 0, 100)) . 
              '    <a style="color: red;" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['products_id']).'"  >  (...' . TEXT_SEE_MORE . ')</a>' ;
//              '  </div>' .
//              '</div>';

	  ob_start();
        include(DIR_WS_MODULES . 'boxes/templates/header_tags.php');
      $data =  ob_get_clean();

      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_HEADER_TAGS_STATUS');
    }

    function install() {
	global $multi_stores_config;	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Information Module', 'MODULE_BOXES_HEADER_TAGS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_HEADER_TAGS_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_HEADER_TAGS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	global $multi_stores_config;	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_HEADER_TAGS_STATUS', 'MODULE_BOXES_HEADER_TAGS_CONTENT_PLACEMENT', 'MODULE_BOXES_HEADER_TAGS_SORT_ORDER');
    }
  }
?>
