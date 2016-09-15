<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_product_images {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_product_images() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_PI_IMAGES_TITLE;
      $this->description = MODULE_CONTENT_PI_IMAGES_DESCRIPTION;

      if ( defined('MODULE_CONTENT_PI_IMAGES_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_PI_IMAGES_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PI_IMAGES_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $_GET, $languages_id, $pf, $currencies, $currency, $product_info ;
	  
	  
	  
      
      $content_width = (int)MODULE_CONTENT_PI_IMAGES_CONTENT_WIDTH;
	  
	  $placement = MODULE_CONTENT_PI_IMAGES_ALIGN_TEXT;
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

      if (tep_not_null($product_info['products_image'])) {
        $photoset_layout = '1';

        $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");
        $pi_total = tep_db_num_rows($pi_query);

        if ($pi_total > 0) {
          $pi_sub = $pi_total-1;

          while ($pi_sub > 5) {
            $photoset_layout .= 5;
            $pi_sub = $pi_sub-5;
          }

          if ($pi_sub > 0) {
            $photoset_layout .= ($pi_total > 5) ? 5 : $pi_sub;
          }


          $product_images = '    <div id="piGal" data-imgcount="' . $photoset_layout . '">' .PHP_EOL ;

          $pi_counter = 0;
          $pi_html = array();

          while ($pi = tep_db_fetch_array($pi_query)) {
            $pi_counter++;

            if (tep_not_null($pi['htmlcontent'])) {
              $pi_html[] = '<div id="piGalDiv_' . $pi_counter . '">' . $pi['htmlcontent'] . '</div>';
            }

//            $product_images .= tep_image(DIR_WS_IMAGES . $pi['image'], '', '', '', 'id="piGalImg_' . $pi_counter . '"');
            $product_images .= tep_image(DIR_WS_IMAGES . $pi['image'], addslashes($product_info['products_name']), '', '', 'id="piGalImg_' . $pi_counter . '" itemprop="image"');
          }

          $product_images .=  '</div>' . PHP_EOL ;

          if ( !empty($pi_html) ) {
            $product_images .= '    <div style="display: none;">' . implode('', $pi_html) . '</div>' . PHP_EOL ;
          }
        } else {  // if ($pi_total > 0) 

          $product_images .= '<div id="piGal">'. PHP_EOL ;
//          $product_images .= tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name'])) . PHP_EOL ; 
          $product_images .= tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), 300, 200, 'itemprop="image"') . PHP_EOL ; 
          $product_images .= '</div>' . PHP_EOL ;
        } // if ($pi_total > 0) 
      } // if (tep_not_null($product_info['prod
   

	  ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/product_images.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PI_IMAGES_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Images Module', 'MODULE_CONTENT_PI_IMAGES_STATUS', 'True', 'Should the Product Images block be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_IMAGES_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Align Text Product Availability', 'MODULE_CONTENT_PI_IMAGES_ALIGN_TEXT', 'Center', 'Align the Text for this module ?', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Center\', \'Right\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_IMAGES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_PI_IMAGES_STATUS', 'MODULE_CONTENT_PI_IMAGES_CONTENT_WIDTH', 'MODULE_CONTENT_PI_IMAGES_SORT_ORDER');
	  // , 'MODULE_CONTENT_PI_IMAGES_ALIGN_TEXT'
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_PI_IMAGES_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_IMAGES_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_PI_IMAGES_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_IMAGES_STATUS == 'False');

    }		
  }
?>