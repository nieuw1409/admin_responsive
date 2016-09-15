<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_product_availability {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_pi_product_availability() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_PI_AVAILABILITY_TITLE;
      $this->description = MODULE_CONTENT_PI_AVAILABILITY_DESCRIPTION;

      if ( defined('MODULE_CONTENT_PI_AVAILABILITY_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_PI_AVAILABILITY_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_PI_AVAILABILITY_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $_GET, $languages_id, $pf, $currencies, $currency, $product_info ;
	  
	  
// bof availability 	
	  if ( $product_info['products_quantity'] > 0 ) {
	    // this product is in stock
        $products_availability = $product_info['products_instock_id'];
        $products_image_height = SYS_IMAGE_AVAILABILITY_INSTOCK_HEIGHT ;	  
        $products_image_width = SYS_IMAGE_AVAILABILITY_INSTOCK_WIDTH ;	 	  
	  } else {
	  // no stock for this product
        $products_availability = $product_info['products_nostock_id'];
        $products_image_height = SYS_IMAGE_AVAILABILITY_NOSTOCK_HEIGHT ;	  
        $products_image_width = SYS_IMAGE_AVAILABILITY_NOSTOCK_WIDTH ;		  
	  }
	  // availibility fot attributes
	  $attributes_availability_nostock = $product_info['products_nostock_id'];
	
      $products_availability_info_query = tep_db_query("select products_availability_name, products_availability_image from " . TABLE_PRODUCTS_AVAILABILITY . " where products_availability_id = '" . (int)$products_availability . "' and language_id = '" . (int)$languages_id . "'");

      $products_availability_info = tep_db_fetch_array($products_availability_info_query);
      $products_availability_name = $products_availability_info['products_availability_name'];	
      $products_availability_image= $products_availability_info['products_availability_image'];	
// eof availability 	  
      
      $content_width = (int)MODULE_CONTENT_PI_AVAILABILITY_CONTENT_WIDTH;
	  
	  $placement = MODULE_CONTENT_PI_AVAILABILITY_ALIGN_TEXT;
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
	   
       $avail_string  = '<!-- bof availability -->' . PHP_EOL ;
       $avail_string .= '<table >' . PHP_EOL ;
	   $avail_string .= '  <tr>' . PHP_EOL ;
       if ( SYS_DEFAULT_USE_AVAILABILITY == 'True') {
          if (tep_not_null($products_availability_info['products_availability_name'])) {
              
	          $avail_string .= '		<td class="label label-info" align="center">' . PHP_EOL ;
              $avail_string .=             TEXT_AVAILABILITY . ':&nbsp' . $products_availability_name ; 					  
  	          $avail_string .= '			</td>' . PHP_EOL ;		
          } // endif name is empty
		  if (tep_not_null($products_availability_info['products_availability_image'])) {
				   
	         $avail_string .= '			<td>' . PHP_EOL ;		    
	         $avail_string .=                tep_image(DIR_WS_IMAGES .$products_availability_info['products_availability_image'], $products_availability_info['products_availability_name'], $products_image_width, $products_image_height, 'itemprop="image"') ; 
	         $avail_string .= '			</td>' . PHP_EOL ;						 
          } //  endif empty image
	  } // endif use availability			 

	  $avail_string .= '	</tr>' . PHP_EOL ;
	  $avail_string .= '</table>	' . PHP_EOL ;
	  $avail_string .= '<!-- eof availability -->' . PHP_EOL ;
	  
	  ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/product_availability.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);

    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_PI_AVAILABILITY_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Availability Module', 'MODULE_CONTENT_PI_AVAILABILITY_STATUS', 'True', 'Should the Product Availability block be shown on the product info page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_PI_AVAILABILITY_CONTENT_WIDTH', '6', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Align Text Product Availability', 'MODULE_CONTENT_PI_AVAILABILITY_ALIGN_TEXT', 'Center', 'Align the Text for this module ?', '6', '1', 'tep_cfg_select_option(array(\'Left\', \'Center\', \'Right\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_AVAILABILITY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_PI_AVAILABILITY_STATUS', 'MODULE_CONTENT_PI_AVAILABILITY_CONTENT_WIDTH', 'MODULE_CONTENT_PI_AVAILABILITY_ALIGN_TEXT', 'MODULE_CONTENT_PI_AVAILABILITY_SORT_ORDER');
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_PI_AVAILABILITY_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_AVAILABILITY_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_PI_AVAILABILITY_STATUS'");
            $this->enabled = (MODULE_CONTENT_PI_AVAILABILITY_STATUS == 'False');

    }		
  }
?>