<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_button_buy {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_button_buy() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_TITLE;
      $this->description = MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_DESCRIPTION;

      if ( defined('MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $_GET, $languages_id, $pf, $currencies, $currency, $product_info ;  
      
      $content_width = (int)MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_CONTENT_WIDTH;
	  
	  $placement = MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_ALIGN_TEXT;
      switch($placement) {
			case "Left" :
				$align_text = 'text-left';
				break;
			case "Right" :
				$align_text = 'text-right';
				break;
			case "Center" :
				$align_text = 'text-center';
				break;					
	  }		

      if ($product_info['products_purchase'] == '1') { 
	  
         $product_button_buy  = '<div class="form-group" id="mydiv">'. PHP_EOL ;  
         $product_button_buy .= '         <tr>'. PHP_EOL ;		 
         $product_button_buy .= '		      <td>'. PHP_EOL ;			  
         $product_button_buy .=                   tep_bootstrap_form_input_field('cart_quantity',                       // name input
                                                                                $pf->adjustQty(1),                 // value
										                                        'id_cart_quantity',                    // id
										                                        '' ,          // initial text
										                                        TEXT_ENTER_QUANTITY,                 // label
										                                        'col-xs-4 btn btn-primary',         // columns label
										                                        'col-xs-4 ',               // columns input
										                                        '6') ; 
         $product_button_buy .=                   tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_button(IMAGE_BUTTON_IN_CART, 'shopping-cart', null, 'primary', null, "btn-success"); 
         $product_button_buy .= '			  </td>'. PHP_EOL ;
         $product_button_buy .= '           </tr>'. PHP_EOL ;  
         $product_button_buy .= '	 </div>'. PHP_EOL ;
      }	

	  
	  ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/button_buy.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Button Buy Module', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS', 'True', 'Should the Product Button Buy be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Align Text Product Button Buy', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_ALIGN_TEXT', 'Center', 'Align the Text for this module ?', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Center\', \'Right\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_CONTENT_WIDTH', 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_SORT_ORDER');
//, 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_ALIGN_TEXT'
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_PRODUCT_BUTTON_BUY_STATUS == 'False');

    }		
  }
?>