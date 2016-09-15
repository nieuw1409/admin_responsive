<?php
/*
  $Id: bm_languages.php 189 2010-12-01 14:16:21Z Rob $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_languages {
    var $code = 'bm_languages';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
var $pages	 ;

    function bm_languages() {
      $this->title = MODULE_BOXES_LANGUAGES_TITLE;
      $this->description = MODULE_BOXES_LANGUAGES_DESCRIPTION;
	  $this->pages = MODULE_BOXES_LANGUAGES_DISPLAY_PAGES;	  

      if ( defined('MODULE_BOXES_LANGUAGES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_LANGUAGES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_LANGUAGES_STATUS == 'True');
        $this->pages = MODULE_BOXES_LANGUAGES_DISPLAY_PAGES;
//        $this->group = ((MODULE_BOXES_LANGUAGES_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_LANGUAGES_CONTENT_PLACEMENT;
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
      global $PHP_SELF, $lng, $request_type, $oscTemplate, $language;

// 2.3.4      if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
      if (substr($PHP_SELF, 0, 8) != 'checkout') {	  
        if (!isset($lng) || (isset($lng) && !is_object($lng))) {
          include_once(DIR_WS_CLASSES . 'language.php');
          $lng = new language;
        }

        if (count($lng->catalog_languages) > 1) {
          $languages_string = '';
          reset($lng->catalog_languages);
          foreach( $lng->catalog_languages as $key => $value ) {
            if ( $value['directory'] == $language ) {
              $current_lang_key = $key;
              break;
            }
          }
          reset($lng->catalog_languages);
//		 $languages_string = '<table class="table table-bordered table-hover"><tbody>'. PHP_EOL ;
         while (list($key, $value) = each($lng->catalog_languages)) { 
// 2.3.4              $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
            $languages_string .= '<tr><td> <a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> </td></tr>';
//            }
          }
//          $languages_string .= '</tbody></table>'. PHP_EOL ;
		  
		  $title = True ;
/*  
		$placement = MODULE_BOXES_LANGUAGES_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Head Column" :
			case "Foot Column" :			
				 $data = '<div class="panel panel-default panel-primary">' .
                  '  <div class="panel-heading">' . MODULE_BOXES_LANGUAGES_BOX_TITLE . '</div>' .
                  '  <div class="panel-body text-left">' . $languages_string . '</div>' .
                  '</div>';
				break;
            case 'Left Header' : 
            case 'Center Header' : 
            case 'Right Header' :           
            case 'Left Footer' : 
            case 'Center Footer' :
            case 'Right Footer' :
            case 'Header Line' :			
            case 'Footer Line' : 			
			case "Bread Column" :	
				 $data = '<div class="panel panel-default panel-primary">' .
                  '  <div class="panel-heading">' . MODULE_BOXES_LANGUAGES_BOX_TITLE . '</div>' .
                  '  <div class="panel-body text-left">' . $languages_string . '</div>' .
                  '</div>';
				break;
	   }
*/

          ob_start();
            include(DIR_WS_MODULES . 'boxes/templates/languages.php');
          $data = ob_get_clean();	   

          $oscTemplate->addBlock($data, $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_LANGUAGES_STATUS');
    }

     function install() {
	  global $multi_stores_config;
	 
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Languages Module', 'MODULE_BOXES_LANGUAGES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_LANGUAGES_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_LANGUAGES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_LANGUAGES_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_LANGUAGES_STATUS', 'MODULE_BOXES_LANGUAGES_CONTENT_PLACEMENT', 'MODULE_BOXES_LANGUAGES_SORT_ORDER', 'MODULE_BOXES_LANGUAGES_DISPLAY_PAGES');
    }
  }
?>