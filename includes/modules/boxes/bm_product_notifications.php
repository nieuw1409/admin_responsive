<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_product_notifications {
    var $code = 'bm_product_notifications';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_product_notifications() {
      $this->title = MODULE_BOXES_PRODUCT_NOTIFICATIONS_TITLE;
      $this->description = MODULE_BOXES_PRODUCT_NOTIFICATIONS_DESCRIPTION;
	  $this->pages = MODULE_BOXES_PRODUCT_NOTIFICATIONS_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_PRODUCT_NOTIFICATIONS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_PRODUCT_NOTIFICATIONS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_PRODUCT_NOTIFICATIONS_STATUS == 'True');

    //    $this->group = ((MODULE_BOXES_PRODUCT_NOTIFICATIONS_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_PRODUCT_NOTIFICATIONS_CONTENT_PLACEMENT;
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
      global $HTTP_GET_VARS, $customer_id, $PHP_SELF, $request_type, $oscTemplate;

      if (isset($HTTP_GET_VARS['products_id'])) {
        if (tep_session_is_registered('customer_id')) {
          $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
          $check = tep_db_fetch_array($check_query);

          $notification_exists = (($check['count'] > 0) ? true : false);
        } else {
          $notification_exists = false;
        }

        $notif_contents = '';

        if ($notification_exists == true) {
          $notif_contents = '<a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '"><span class="glyphicon glyphicon-remove"></span> ' . sprintf(MODULE_BOXES_PRODUCT_NOTIFICATIONS_BOX_NOTIFY_REMOVE, tep_get_products_name($HTTP_GET_VARS['products_id'])) .'</a>';
        } else {
          $notif_contents = '<a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '"><span class="glyphicon glyphicon-envelope"></span> ' . sprintf(MODULE_BOXES_PRODUCT_NOTIFICATIONS_BOX_NOTIFY, tep_get_products_name($HTTP_GET_VARS['products_id'])) .'</a>';
        }	

        $title = True ;		

        ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/product_notifications.php');
        $data  = ob_get_clean();  
	   
        $oscTemplate->addBlock($data, $this->group);
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_PRODUCT_NOTIFICATIONS_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Notifications Module', 'MODULE_BOXES_PRODUCT_NOTIFICATIONS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_PRODUCT_NOTIFICATIONS_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_PRODUCT_NOTIFICATIONS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_PRODUCT_NOTIFICATIONS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_PRODUCT_NOTIFICATIONS_STATUS', 'MODULE_BOXES_PRODUCT_NOTIFICATIONS_CONTENT_PLACEMENT', 'MODULE_BOXES_PRODUCT_NOTIFICATIONS_SORT_ORDER', 'MODULE_BOXES_PRODUCT_NOTIFICATIONS_DISPLAY_PAGES');
    }
  }
?>