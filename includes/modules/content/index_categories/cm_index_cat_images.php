<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2014 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_cat_images {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_index_cat_images() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_TITLE;
      $this->description = MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_DESCRIPTION;

      if ( defined('MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS == 'True');
      }
    }
	
	function getData() {
		global $cPath, $categories, $languages_id, $current_category_id, $cPath_array ; 
		
		$customer_group_id = tep_get_cust_group_id() ;
		
        if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category 
           $category_links = sizeof(array_reverse($cPath_array) );
           for($i=0, $n=$category_links; $i<$n; $i++) {
              $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd 
		            where  c.categories_status = '1' 
				    and c.parent_id = '" . (int)$category_links[$i] . "' 
				    and c.categories_id = cd.categories_id 
				    and cd.language_id = '" . (int)$languages_id . "' and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
				    and find_in_set('" . SYS_STORES_ID . "', categories_to_stores) != 0");
              $categories = tep_db_fetch_array($categories_query);
              if ($categories['total'] < 1) {
                  // do nothing, go through the loop
              } else {
                  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, IF(cd.categories_htc_description !='',cd.categories_htc_description,'') as hts_desc   from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd 
		                where  c.categories_status = '1'                                                   and c.parent_id = '" . (int)$category_links[$i] . "' 
					      and c.categories_id = cd.categories_id                                          and cd.language_id = '" . (int)$languages_id . "' 
						  and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0  and find_in_set('" . SYS_STORES_ID . "', categories_to_stores) != 0 
					    order by sort_order, cd.categories_name");
/*** End Header Tags SEO 331 ***/ 							
                  break; // we've found the deepest category the customer is in
              }
           }
        } else {
           $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, IF(cd.categories_htc_description !='',cd.categories_htc_description,'') as hts_desc  from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd 
	          where  c.categories_status = '1'                                                            and c.parent_id = '" . (int)$current_category_id . "' 
			     and c.categories_id = cd.categories_id                                                   and cd.language_id = '" . (int)$languages_id . "' 
				 and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0           and find_in_set('" . SYS_STORES_ID . "', categories_to_stores) != 0 
			 order by sort_order, cd.categories_name");	  
/*** End Header Tags SEO 331 ***/ 				 
        }

//        $number_of_categories = tep_db_num_rows($categories_query);

        $rows = 0;
        while ($categories = tep_db_fetch_array($categories_query)) {
// bof respnsive design	
//      $rows++;
           $cPath_new = tep_get_path($categories['categories_id']);
           $htsDesc = '';
           if (HEADER_TAGS_DISPLAY_CATEGORY_SHORT_DESCRIPTION !== 'Off' && tep_not_null($categories['hts_desc'])) {
              $lgth = (int)HEADER_TAGS_DISPLAY_CATEGORY_SHORT_DESCRIPTION;
              $htsDesc = '<br />';
              if (isset($categories['hts_desc'][$lgth])) {
                 $htsDesc .= substr($categories['hts_desc'], 0, ($lgth - 3)) . '<span class="ui-state-active">...</span>';
              } else {
                 $htsDesc .= $categories['hts_desc'];
              }
           }
           $data .= '<div class="col-xs-6 col-sm-4">' . PHP_EOL ;
           $data .= '  <div class="caption text-center">' . PHP_EOL ;
           $data .= '    <a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . 
	                         tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT, 'itemprop="image"') . 							                              
			        '    </a>' . PHP_EOL  ;
           $data .= '    <div class="text-center">' . PHP_EOL ;
           $data .= '      <h5><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . $categories['categories_name'] . $htsDesc . '</a></h5>' . PHP_EOL ;
           $data .= '    </div>' . PHP_EOL ;
           $data .= '  </div>' . PHP_EOL ;
           $data .= '</div>' . PHP_EOL ;
// eof responsive design	  
        }
	    return $data ;
	}

    function execute() {
      global $oscTemplate, $customer_id, $category;
      
      $content_width = (int)MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_CONTENT_WIDTH;
	  
	  $categories_images = $this->getData() ;
      
      ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/categories_images.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Index Categories Images Module', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS', 'True', 'Do you want to enable the Index Categories Images  content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " .  $multi_stores_config   . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_CONTENT_WIDTH', 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_SORT_ORDER');
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_CATEGORIES_IMAGES_STATUS == 'False');

    }		
  }
?>