<?php
/*
  $Id$ 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_reviews {
    var $code = 'bm_reviews';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_reviews() {
      $this->title = MODULE_BOXES_REVIEWS_TITLE;
      $this->description = MODULE_BOXES_REVIEWS_DESCRIPTION;
	  $this->pages = MODULE_BOXES_REVIEWS_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_REVIEWS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_REVIEWS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_REVIEWS_STATUS == 'True');

      $placement = MODULE_BOXES_REVIEWS_CONTENT_PLACEMENT;
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
      global $languages_id, $HTTP_GET_VARS, $currencies, $oscTemplate;

// bof multi stores
    $customer_group_id = tep_get_cust_group_id()  ;

  $random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . TABLE_CATEGORIES . " c using(categories_id) 
      where p.products_status = '1'                                and c.categories_status= '1' 
	     and p.products_id = r.products_id                         and r.reviews_id = rd.reviews_id 
		 and rd.languages_id = '" . (int)$languages_id . "'        and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'";
  if (isset($HTTP_GET_VARS['products_id'])) {
    $random_select .= " and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'";
  }
    $random_select .= " and find_in_set('".$customer_group_id."', p.products_hide_from_groups) = 0 ";
    $random_select .= " and find_in_set('" . $customer_group_id . "', c.categories_hide_from_groups) = 0 ";
    $random_select .= " and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0 " ; // multi stores
	$random_select .= " and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 " ; // multi stores
 // EOF Hide products and categories from groups	  
      $random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
      $random_product = tep_random_select($random_select);

      $reviews_box_contents = '';

      if ($random_product) {
// display random review box
        $rand_review_query = tep_db_query("select substring(reviews_text, 1, 60) as reviews_text from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$random_product['reviews_id'] . "' and languages_id = '" . (int)$languages_id . "'");
        $rand_review = tep_db_fetch_array($rand_review_query);

        $rand_review_text = tep_break_string(tep_output_string_protected($rand_review['reviews_text']), 15, '-<br />');	  
        $reviews_box_contents .= '<div class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><div><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . $random_product['products_id']) . '">' . $rand_review_text . '</a>...</div><div class="text-center" title="' .  sprintf(MODULE_BOXES_REVIEWS_BOX_TEXT_OF_5_STARS, $random_product['reviews_rating']) . '">' . tep_draw_stars($random_product['reviews_rating']) . '</div>';
      } elseif (isset($HTTP_GET_VARS['products_id'])) {
// display 'write a review' box
        $reviews_box_contents .= '<span class="' . glyphicon_icon_to_fontawesome( "thumbs-up" ) . 'eric"></span> <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . MODULE_BOXES_REVIEWS_BOX_WRITE_REVIEW .'</a>';
      } else { // glyphicon glyphicon-thumbs-up
// display 'no reviews' box
        $reviews_box_contents .= '<p>' . MODULE_BOXES_REVIEWS_BOX_NO_REVIEWS . '</p>';
      }

      $title = True ;	  

	  ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/reviews.php');
      $data =  ob_get_clean();	   
      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_REVIEWS_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Reviews Module', 'MODULE_BOXES_REVIEWS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_REVIEWS_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_REVIEWS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_REVIEWS_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_REVIEWS_STATUS', 'MODULE_BOXES_REVIEWS_CONTENT_PLACEMENT', 'MODULE_BOXES_REVIEWS_SORT_ORDER', 'MODULE_BOXES_REVIEWS_DISPLAY_PAGES');
    }
  }
?>