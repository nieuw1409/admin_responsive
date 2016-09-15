<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_headertags_seo_silo {
    var $code = 'bm_headertags_seo_silo';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;

    function bm_headertags_seo_silo() {
      $this->title = MODULE_BOXES_HEADERTAGS_SEO_SILO_TITLE;
      $this->description = MODULE_BOXES_HEADERTAGS_SEO_SILO_DESCRIPTION;

      if ( defined('MODULE_BOXES_HEADERTAGS_SEO_SILO_STATUS') ) {
        $this->sort_order = MODULE_BOXES_HEADERTAGS_SEO_SILO_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_HEADERTAGS_SEO_SILO_STATUS == 'True' && HEADER_TAGS_DISPLAY_SILO_BOX == 'true');
        $this->pages = FILENAME_PRODUCT_INFO ;
//        $this->group = ((MODULE_BOXES_HEADERTAGS_SEO_SILO_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_HEADERTAGS_SEO_SILO_CONTENT_PLACEMENT;
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

    function get_list() {
        global $cPath, $languages_id;
        
        /************** GET THE CATEGORY ID ***************/
        $catID = $cPath;
        if (isset($cPath) && strpos($cPath, '_')) {
            $parts = explode("_", $cPath);
            $catID = $parts[count($parts) - 1];
        }
        
        /************** GET THE SETTINGS ***************/
        $silo = array();
        $silo_query = tep_db_query("select * from " . TABLE_HEADERTAGS_SILO . " 
		              where ( category_id = 0 or category_id = " . (int)$catID . " ) 
					      and language_id = " . (int)$languages_id . " 
						  and find_in_set('" . SYS_STORES_ID . "', stores_id ) != 0 
						  order by category_id DESC");


        while ($silo = tep_db_fetch_array($silo_query)) {
            if ($silo['is_disabled']) return;
//  echo 'rows '.tep_db_num_rows($silo_query).' - '.$silo['category_id'] .'<br>';       
            if ($silo['category_id'] == $catID || $silo['category_id'] == 0) {
                 switch ($silo['sorton']) {
                   case 0: //date
                     $orderProducts = ' p.products_last_modified ';
                     $orderCategory = ' c.last_modified ';
                   break;
                   
                   case 1: //name
                     $orderProducts = ' pd.products_head_title_tag ';
                     $orderCategory = ' cd.categories_htc_title_tag ';
                   break;
                   
                   case 2: //best sellers or new
                     $orderProducts = ' p.products_ordered ';
                     $orderCategory = ' c.last_modified ';
                   break;
                   
                   default:   
                     $orderProducts = ' p.products_ordered ';
                     $orderCategory = ' c.last_modified ';
                 }
                  
                 break; //exit the loop so this array is used
            }                
        }

        if (! tep_not_null($silo)) {
             $catname = tep_db_fetch_array(tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$catID . "' and language_id = '" . $languages_id . "' limit 1"));
             $silo['box_heading'] = $catname['categories_name'];
             $silo['max_links'] = 6; 
             $orderProducts = ' p.products_ordered ';
             $orderCategory = ' cd.categories_htc_title_tag '; 
        }
       
        /************** GET THE CATEGORY INFORMATION ***************/ 
        $linkList = '';

        $products_query = tep_db_query("select pd.products_head_title_tag, p.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd INNER JOIN " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on pd.products_id = p2c.products_id 
		               where p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$catID . "' 
					    order by " . $orderProducts . " Limit " . (int)$silo['max_links']);
        if (tep_db_num_rows($products_query) > 0) {
            while ($products = tep_db_fetch_array($products_query)) {
                $linkList .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '" title="' . $products['products_head_title_tag'] . '">' . $products['products_head_title_tag'] . '</a><br>';
            } 
        } else {
            $subArray = array();
            $subArray = tep_get_categories($subArray, $catID);
            $subCnt = count($subArray);
         
            if ($subCnt > 0) {
                $catStr = ' ( ';
                for ($i = 0; ($i < $subCnt && $i < $silo['max_links']); ++$i)  {
                    $catStr .= ' cd.categories_id = ' . $subArray[$i]['id'] . ' or ';
                }
                $catStr = substr($catStr, 0, -3);
                $catStr .= ' ) ';
             
                $subcategories_query = tep_db_query("select cd.categories_htc_title_tag, cd.categories_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd left join " . TABLE_CATEGORIES . " c on cd.categories_id = c.categories_id where " . $catStr . " and cd.language_id = " . (int)$languages_id . " order by " . $orderCategory);      
                while($subcategories = tep_db_fetch_array($subcategories_query)) {
                    $linkList .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $subcategories['categories_id']) . '" title="' . $subcategories['categories_htc_title_tag'] . '">' . $subcategories['categories_htc_title_tag'] . '</a><br>';
                }  
            }
        }
        
        return $linkList;    
    }
    
    function execute() {
      global $oscTemplate;
   
//      $data = '<div class="panel panel-default panel-primary">' .
      $header_title = MODULE_BOXES_HEADERTAGS_SEO_SILO_TITLE ; 
//              '  <div class="panel-body">' .
      $htss_description = $this->get_list() ;
//              '  </div>' .
//              '</div>';
	  ob_start();
        include(DIR_WS_MODULES . 'boxes/templates/headertags_seo_silo.php');
      $data =  ob_get_clean();
      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_HEADERTAGS_SEO_SILO_STATUS');
    }

    function install() {
	global $multi_stores_config;	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Information Module', 'MODULE_BOXES_HEADERTAGS_SEO_SILO_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_HEADERTAGS_SEO_SILO_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_HEADERTAGS_SEO_SILO_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	global $multi_stores_config;	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_HEADERTAGS_SEO_SILO_STATUS', 'MODULE_BOXES_HEADERTAGS_SEO_SILO_CONTENT_PLACEMENT', 'MODULE_BOXES_HEADERTAGS_SEO_SILO_SORT_ORDER');
    }
  }
?>
