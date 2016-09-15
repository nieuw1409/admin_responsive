<?php
/*
  $Id: wishlist.php
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

/*******************************************************************
****** QUERY THE DATABASE FOR THE CUSTOMERS WISHLIST PRODUCTS ******
*******************************************************************/

class bm_wishlist {
    var $code = 'bm_wishlist';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;	

    function bm_wishlist() {
      $this->title = MODULE_BOXES_WISHLIST_TITLE;
      $this->description = MODULE_BOXES_WISHLIST_DESCRIPTION;

      if ( defined('MODULE_BOXES_WISHLIST_STATUS') ) {
        $this->sort_order = MODULE_BOXES_WISHLIST_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_WISHLIST_STATUS == 'True');
		$this->pages = MODULE_BOXES_WISHLIST_DISPLAY_PAGES;			

//        $this->group = ((MODULE_BOXES_WISHLIST_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_WISHLIST_CONTENT_PLACEMENT;
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
      global $wishList, $currencies, $languages_id, $oscTemplate;

      if (is_array($wishList->wishID) && !empty($wishList->wishID)) {
		    reset($wishList->wishID);
		    $counter = count($wishList->wishID);
		    $wishlist_box = '';
		    if ($counter <= MAX_DISPLAY_WISHLIST_BOX) {
			    $wishlist_box = '<ul style="list-style:inside disc; padding: 0px;">';
	
	/*******************************************************************
	*** LOOP THROUGH EACH PRODUCT ID TO DISPLAY IN THE WISHLIST BOX ****
	*******************************************************************/
	
			    while (list($wishlist_id, ) = each($wishList->wishID)) {
			      $wishlist_id = tep_get_prid($wishlist_id);
			      $products_query = tep_db_query("select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd ) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id 
				     where pd.products_id = '" . (int)$wishlist_id . "'                       and p.products_id = pd.products_id 
					   and pd.language_id = '" . (int)$languages_id . "' order by products_name");
			      $products = tep_db_fetch_array($products_query);
			      $wishlist_box .= '<li><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id'], 'NONSSL') . '">' . $products['products_name'] . '</a></li>';
			    }
		      $wishlist_box .= '</ul>';
		    }
	     	$wishlist_box .= sprintf(TEXT_WISHLIST_COUNT, $counter);
	    } else {
		    $wishlist_box = MODULE_BOXES_WISHLIST_BOX_CART_EMPTY;
	    }
	  
	    $data = '<div class="panel panel-default panel-primary">' .
                '  <div class="panel-heading"><a class="label label-info" href="' . tep_href_link(FILENAME_WISHLIST) . '">' . MODULE_BOXES_WISHLIST_BOX_TITLE . '</a></div>' .
                '  <div class="panel-body">' . $wishlist_box .
                '  </div>'.
				'</div>';

      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_WISHLIST_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Module', 'MODULE_BOXES_WISHLIST_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_WISHLIST_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_WISHLIST_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_WISHLIST_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	   	  
    }

    function remove() {
	  global $multi_stores_config;
	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_WISHLIST_STATUS', 'MODULE_BOXES_WISHLIST_CONTENT_PLACEMENT', 'MODULE_BOXES_WISHLIST_SORT_ORDER', 'MODULE_BOXES_WISHLIST_DISPLAY_PAGES');
    }
  }
?>
