<?php
/*$Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/

  class bm_order_history {
    var $code = 'bm_order_history';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_order_history() {
      $this->title = MODULE_BOXES_ORDER_HISTORY_TITLE;
      $this->description = MODULE_BOXES_ORDER_HISTORY_DESCRIPTION;
	  $this->pages = MODULE_BOXES_ORDER_HISTORY_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_ORDER_HISTORY_STATUS') ) {
        $this->sort_order = MODULE_BOXES_ORDER_HISTORY_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_ORDER_HISTORY_STATUS == 'True');

    //    $this->group = ((MODULE_BOXES_ORDER_HISTORY_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_ORDER_HISTORY_CONTENT_PLACEMENT;
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
      global $customer_id, $languages_id, $PHP_SELF, $oscTemplate;

      if (tep_session_is_registered('customer_id')) {
// retreive the last x products purchased
        $orders_query = tep_db_query("select distinct op.products_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = '1' group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
        if (tep_db_num_rows($orders_query)) {
          $product_ids = '';
          while ($orders = tep_db_fetch_array($orders_query)) {
            $product_ids .= (int)$orders['products_id'] . ',';
          }
          $product_ids = substr($product_ids, 0, -1);

          $customer_orders_string = '<ul class="list-unstyled">';
          $products_query = tep_db_query("select products_id, products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $product_ids . ") and language_id = '" . (int)$languages_id . "' order by products_name");
          while ($products = tep_db_fetch_array($products_query)) {
            $customer_orders_string .= '<li><span class="pull-right"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $products['products_id']) . '"><span class="glyphicon glyphicon-shopping-cart"></span></a></span><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a></li>';
          }
          $customer_orders_string .= '</ul>';
		  
		  $title = True ;
		  
          ob_start();
           include(DIR_WS_MODULES . 'boxes/templates/order_history.php');
          $data  = ob_get_clean();  
	  
	      $oscTemplate->addBlock($data, $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_ORDER_HISTORY_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Order History Module', 'MODULE_BOXES_ORDER_HISTORY_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_ORDER_HISTORY_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_ORDER_HISTORY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_ORDER_HISTORY_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_ORDER_HISTORY_STATUS', 'MODULE_BOXES_ORDER_HISTORY_CONTENT_PLACEMENT', 'MODULE_BOXES_ORDER_HISTORY_SORT_ORDER', 'MODULE_BOXES_ORDER_HISTORY_DISPLAY_PAGES');
    }
  }
?>