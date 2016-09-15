<?php
/*$Id$ 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_manufacturers {
    var $code = 'bm_manufacturers';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_manufacturers() {
      $this->title = MODULE_BOXES_MANUFACTURERS_TITLE;
      $this->description = MODULE_BOXES_MANUFACTURERS_DESCRIPTION;
	  $this->pages = MODULE_BOXES_MANUFACTURERS_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_MANUFACTURERS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_MANUFACTURERS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_MANUFACTURERS_STATUS == 'True');

       // $this->group = ((MODULE_BOXES_MANUFACTURERS_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
     $placement = MODULE_BOXES_MANUFACTURERS_CONTENT_PLACEMENT;
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

    function getData() {
// 2.3.3.3      global $HTTP_GET_VARS, $oscTemplate;
      global $HTTP_GET_VARS, $request_type, $oscTemplate; // 2.3.3.3

      $data = '';

  // BOF Enable & Disable Categories
        $manufacturers_query = tep_db_query("select distinct m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m left join " . TABLE_PRODUCTS . " p on m.manufacturers_id = p.manufacturers_id left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id left join " . TABLE_CATEGORIES . " c on p2c.categories_id = c.categories_id 
		  where c.categories_status = '1' and p.products_status = '1'  and find_in_set('" . SYS_STORES_ID . "', m.manufacturers_to_stores) != 0  order by m.manufacturers_name");
  // EOF Enable & Disable Categories

      if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {
        if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
// Display a list
          $manufacturers_list = '<ul class="nav nav-list">';
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
            $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
            if (isset($HTTP_GET_VARS['manufacturers_id']) && ($HTTP_GET_VARS['manufacturers_id'] == $manufacturers['manufacturers_id'])) $manufacturers_name = '<strong>' . $manufacturers_name .'</strong>';
            $manufacturers_list .= '<li><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '">' . $manufacturers_name . '</a></li>';
          }

          $manufacturers_list .= '</ul>';

          $content = $manufacturers_list;
        } else {
// Display a drop-down
          $manufacturers_array = array();
          if (MAX_MANUFACTURERS_LIST < 2) {
            $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
          }

          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
            $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
            $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                           'text' => $manufacturers_name);
          }

          $content = tep_draw_form('manufacturers', tep_href_link(FILENAME_DEFAULT, '', $request_type, false), 'get') .
                     tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($HTTP_GET_VARS['manufacturers_id']) ? $HTTP_GET_VARS['manufacturers_id'] : ''), 'onchange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . tep_hide_session_id() .
                     '</form>';
        }
		
		$title = True ;
/*
		$placement = MODULE_BOXES_MANUFACTURERS_CONTENT_PLACEMENT;
        switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Foot Column" :
			case "Head Column" :			
        $data = '<div class="panel panel-default panel-primary">' .
                  '  <div class="panel-heading">' . MODULE_BOXES_MANUFACTURERS_BOX_TITLE . '</div>' .
                  '  <div class="panel-body">' . $content . '</div>' .
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
                  '  <div class="panel-body">' . $content . '</div>' .
                  '</div>';
				break;	
	    } 		
*/
        ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/manufacturers.php');
        $data = ob_get_clean();
		
      }
      return $data;
    }

    function execute() {
      global $SID, $oscTemplate, $cPath, $language, $cache;

// bof multi stores
    $customer_group_id = tep_get_cust_group_id();
// eof multi stores	  
     if ((USE_CACHE == 'true') && empty($SID)) {
	    $cache_name = 'bm_manufacturers-' . $language . '-cg' . $customer_group_id . '.cache' ;
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		  $output = $this->getData();
		  $cache->save_cache($cache_name, $output, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');		  
	    } else {		
	  	  $output = $cache->get_cache($cache_name, 'RETURN');	  
	    }  		
      } else {
        $output = $this->getData();
      }

      $oscTemplate->addBlock($output, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_MANUFACTURERS_STATUS');
    }

    function install() {
	  global $multi_stores_config;	
	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Manufacturers Module', 'MODULE_BOXES_MANUFACTURERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_MANUFACTURERS_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_MANUFACTURERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_MANUFACTURERS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_MANUFACTURERS_STATUS', 'MODULE_BOXES_MANUFACTURERS_CONTENT_PLACEMENT', 'MODULE_BOXES_MANUFACTURERS_SORT_ORDER', 'MODULE_BOXES_MANUFACTURERS_DISPLAY_PAGES');
    }
  }
?>