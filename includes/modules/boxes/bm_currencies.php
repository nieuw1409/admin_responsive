<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_currencies {
    var $code = 'bm_currencies';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_currencies() {
      $this->title = MODULE_BOXES_CURRENCIES_TITLE;
      $this->description = MODULE_BOXES_CURRENCIES_DESCRIPTION;
	  $this->pages = MODULE_BOXES_CURRENCIES_DISPLAY_PAGES;
	  
      if ( defined('MODULE_BOXES_CURRENCIES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_CURRENCIES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_CURRENCIES_STATUS == 'True');
		// $this->group = ((MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
		$placement = MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT;
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
        case 'Left Header' : $this->group = 'header_contents_left';
            break;
          case 'Center Header' : $this->group = 'header_contents_center';
            break;
          case 'Right Header' : $this->group = 'header_contents_right';
            break;
          case 'Header Line' : $this->group = 'header_line';
            break;
          case 'Left Footer' : $this->group = 'footer_contents_left';
            break;
          case 'Center Footer' : $this->group = 'footer_contents_center';
            break;
          case 'Right Footer' : $this->group = 'footer_contents_right';
            break;
          case 'Footer Line' : $this->group = 'footer_line';
            break;
			case "Head Column" :
				$this->group = 'boxes_column_head';
				break;
			case "Foot Column" :
				$this->group = 'boxes_column_foot';
				break;			  
		}
      }
    }

    function execute() {
      global $PHP_SELF, $currencies, $HTTP_GET_VARS, $request_type, $currency, $oscTemplate;

      if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
		 
        if (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1)) {
          reset($currencies->currencies);
          $currencies_array = array();
          while (list($key, $value) = each($currencies->currencies)) {
            $currencies_array[] = array('id' => $key, 'text' => $value['title']);
          }

          $hidden_get_variables = '';
          reset($HTTP_GET_VARS);
          while (list($key, $value) = each($HTTP_GET_VARS)) {
            if ( is_string($value) && ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y') ) {
              $hidden_get_variables .= tep_draw_hidden_field($key, $value);
            }
          }

          $form_output = tep_draw_form('currencies', tep_href_link($PHP_SELF, '', $request_type, false), 'get') . tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onchange="this.form.submit();" style="width: 100%"') . $hidden_get_variables . tep_hide_session_id() . '</form>';
		    
		  $title = True ;
/*		  
		   $placement = MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :				
			case "Right Column" :
		case "Head Column" :
		case "Foot Column" :
              $data = '<div class="panel panel-default panel-primary">' .
                      '  <div class="panel-heading">' . MODULE_BOXES_CURRENCIES_BOX_TITLE . '</div>' .
                      '    ' . tep_draw_form('currencies', tep_href_link($PHP_SELF, '', $request_type, false), 'get') .
                      '    ' . tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onchange="this.form.submit();" style="width: 100%"') . $hidden_get_variables . tep_hide_session_id() . '</form>' .
                      '</div>'; 						  
				break;
		case "Bread Column" :
          case 'Left Header' : 
          case 'Center Header' : 
          case 'Right Header' :           
          case 'Left Footer' : 
          case 'Center Footer' :
          case 'Right Footer' :
          case 'Header Line' :			
          case 'Footer Line' : 		
              $data = '<div class="panel panel-default panel-primary">' .
                      '    ' . tep_draw_form('currencies', tep_href_link($PHP_SELF, '', $request_type, false), 'get') .
                      '    ' . tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onchange="this.form.submit();" style="width: 100%"') . $hidden_get_variables . tep_hide_session_id() . '</form>' .
                      '</div>'; 			  
	
				  break;
			}
*/
	      ob_start();
            include(DIR_WS_MODULES . 'boxes/templates/currencies.php');
          $data =  ob_get_clean();			
          $oscTemplate->addBlock($data, $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_CURRENCIES_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_CURRENCIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT', 'Right Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\',\'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_CURRENCIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_CURRENCIES_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_CURRENCIES_STATUS', 'MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT', 'MODULE_BOXES_CURRENCIES_SORT_ORDER', 'MODULE_BOXES_CURRENCIES_DISPLAY_PAGES');
    }
  }
?>
