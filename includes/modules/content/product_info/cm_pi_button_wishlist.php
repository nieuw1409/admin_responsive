<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_button_wishlist {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_button_wishlist() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_PI_BUTTON_WISHLIST_TITLE;
      $this->description = MODULE_CONTENT_PI_BUTTON_WISHLIST_DESCRIPTION;

      if ( defined('MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_PI_BUTTON_WISHLIST_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $_GET, $languages_id, $pf, $currencies, $currency, $product_info, $reviews ;	  
      
      $content_width = (int)MODULE_CONTENT_PI_BUTTON_WISHLIST_CONTENT_WIDTH;
	  
	  $placement = MODULE_CONTENT_PI_BUTTON_WISHLIST_ALIGN_TEXT;
      switch($placement) {
			case "Left" :
				$align_text = '';
				break;
			case "Right" :
				$align_text = '';
				break;
			case "Center" :
				$align_text = '';
				break;					
	  }	

      if (MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS == 'True') {   	  

         $product_button_wishlist  = '<div>'. PHP_EOL ;  

         $product_button_wishlist .=                   tep_draw_button(TEXT_ADD_WISHLIST, 
		                                                               'list', 
																	   null, 
																	   'primary', 
																	   array('params' => 'onclick=\'var input = document.createElement("input"); input.setAttribute("type", "hidden"); input.setAttribute("name", "wishlist"); input.setAttribute("value", "wishlist"); document.getElementById("mydiv").appendChild(input);\''),
																	   'btn-primary' ) . PHP_EOL ;	

         $product_button_wishlist .= '	 </div>'. PHP_EOL ;	  
      }
		  
	  ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/button_wishlist.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Wishlist Button Module', 'MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS', 'True', 'Should the Product Wishlist Button be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_BUTTON_WISHLIST_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Align Text Product Availability', 'MODULE_CONTENT_PI_BUTTON_WISHLIST_ALIGN_TEXT', 'Center', 'Align the Text for this module ?', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Center\', \'Right\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_BUTTON_WISHLIST_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS', 'MODULE_CONTENT_PI_BUTTON_WISHLIST_CONTENT_WIDTH', 'MODULE_CONTENT_PI_BUTTON_WISHLIST_SORT_ORDER');
//, 'MODULE_CONTENT_PI_BUTTON_WISHLIST_ALIGN_TEXT'	  
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_BUTTON_WISHLIST_STATUS == 'False');

    }		
  }
?>