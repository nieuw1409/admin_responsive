<?php
/*  $Id$ Tóth Gergely http://oscom.hu
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_shortcut_buttons {
    var $code = 'bm_shortcut_buttons';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;		

    function bm_shortcut_buttons() {
      $this->title = MODULE_BOXES_SHORTCUT_BUTTONS_TITLE;
      $this->description = MODULE_BOXES_SHORTCUT_BUTTONS_DESCRIPTION;
      $this->pages = MODULE_BOXES_SHORTCUT_BUTTONS_DISPLAY_PAGES;	  	  

      if ( defined('MODULE_BOXES_SHORTCUT_BUTTONS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SHORTCUT_BUTTONS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SHORTCUT_BUTTONS_STATUS == 'True');

        switch (MODULE_BOXES_SHORTCUT_BUTTONS_CONTENT_PLACEMENT) {
		  case "Left Column" : $this->group = 'boxes_column_left';
			break;
		  case "Right Column" : $this->group = 'boxes_column_right';
			break;
		  case "Bread Column" : $this->group = 'boxes_column_bread';
			break;
		  case "Head Column" : $this->group = 'boxes_column_head';
			break;
		  case "Foot Column" : $this->group = 'boxes_column_foot';
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
        }

      }
    }

    function execute() {
      global $cart, $oscTemplate;
	  
	    $title = True ;

//        $data = '  <div class="panel panel-primary" id="button_set">' . PHP_EOL;
		if ( $cart->count_contents() > 0 ) {		
            $button_string .= tep_draw_button(HEADER_TITLE_CART_CONTENTS .  '(' . $cart->count_contents() . ')' , 'shopping-cart', tep_href_link(FILENAME_SHOPPING_CART)) .
                     tep_draw_button(HEADER_TITLE_CHECKOUT, 'credit-card', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) ;
		}
        if (tep_session_is_registered('customer_id')) {
 
           $button_string .= tep_draw_button(HEADER_TITLE_MY_ACCOUNT, 'user', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
        } else {
           $button_string .= tep_draw_button(HEADER_TITLE_LOGIN, 'log-in', tep_href_link(FILENAME_LOGIN, '', 'SSL'));
        }
        if (tep_session_is_registered('customer_id')) {
          $button_string .= tep_draw_button(HEADER_TITLE_LOGOFF, 'log-out', tep_href_link(FILENAME_LOGOFF, '', 'SSL'));
        }
//        $data .= '</div>' . PHP_EOL;

//        $data .= '<script type="text/javascript">' . PHP_EOL;
//        $data .= '  $("#button_set").buttonset();' . PHP_EOL;
//        $data .= '</script>';
        ob_start();
           include(DIR_WS_MODULES . 'boxes/templates/shortcut_buttons.php');
        $data = ob_get_clean();

        $oscTemplate->addBlock($data, $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SHORTCUT_BUTTONS_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Currencies Module', 'MODULE_BOXES_SHORTCUT_BUTTONS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SHORTCUT_BUTTONS_CONTENT_PLACEMENT', 'Header Line', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SHORTCUT_BUTTONS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SHORTCUT_BUTTONS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	  	  	   	  
    }

    function remove() {
	  global $multi_stores_config;
	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_SHORTCUT_BUTTONS_STATUS', 'MODULE_BOXES_SHORTCUT_BUTTONS_CONTENT_PLACEMENT', 'MODULE_BOXES_SHORTCUT_BUTTONS_SORT_ORDER', 'MODULE_BOXES_SHORTCUT_BUTTONS_DISPLAY_PAGES');
    }
  }
?>