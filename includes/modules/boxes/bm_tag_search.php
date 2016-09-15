<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
 */
 
 	class tagcloud {
		// tag-styles: from very tiny to very big
	//	var $a_tag_styles = array('font-size:xx-small', 'font-size:x-small', 'font-size:small', 'font-size:medium', 'font-size:large', 'font-size:x-large', 'font-size:xx-large');

	//	var $a_tag_styles = array('font-size:9px', 'font-size:6px', 'font-size:7px', 'font-size:8px', 'font-size:9px', 'font-size:10px','font-size:11px', 'font-size:12px','font-size:13px','font-size:17px', 'font-size:20px');
/*    var $a_tag_styles = array('font-size:9px;  color:green', 
	                          'font-size:6px;  color:orange', 
							  'font-size:7px;  color:red', 
							  'font-size:8px;  color:black', 
							  'font-size:9px;  color:green', 
							  'font-size:10px; color:orange',
							  'font-size:11px; color:red', 
							  'font-size:12px; color:black',
							  'font-size:13px; color:green',
							  'font-size:17px; color:orange', 
							  'font-size:20px; color:red');	
*/							  
    var $a_tag_styles = array('font-size:10px;', 
	                          'font-size:10px;', 
							  'font-size:12px;', 
							  'font-size:12px; ', 
							  'font-size:12px;  ', 
							  'font-size:14px; ',
							  'font-size:14px; ', 
							  'font-size:14px; ',
							  'font-size:16px; ',
							  'font-size:16px; ', 
							  'font-size:16px; ');	
							  
    var $a_tag_class  = array('btn btn-default', 
	                          'btn btn-primary', 
							  'btn btn-primary', 
							  'btn btn-success', 
							  'btn btn-success', 
							  'btn btn-info',
							  'btn btn-info', 
							  'btn btn-warning',
							  'btn btn-warning', 
							  'btn btn-danger', 
							  'btn btn-danger');								  


  	// how many tags do we want to display?
		var $max_shown_tags;
		// the tags
		var $a_tc_data;

		/**
		 * Construct
		 */
		function tagcloud($max_shown_tags = MODULE_BOXES_TAG_SEARCH_MAX_SHOWN_TAGS) {
			$this->max_shown_tags = $max_shown_tags;
		}

		/**
		 * Saves the date for the tagcloud
		 *
		 * @param	array	$a_tc_data	An array with data. The keys are the actual tags, the values are how often they occure.
		 *								eg.: array('tag1' => 3, 'tag2' => 1, 'tag3' => 7);
		 * @return	bool				Always returns true
		 */
		function set_tagcloud_data($a_tc_data = array() ) {
			$this->a_tc_data = $a_tc_data;
			arsort($this->a_tc_data);

			// since we only want a specified number of tags, we strip all the tags, that do not often occure.
			$a_tags = array();
			reset($this->a_tc_data);
			$tag_count = count($this->a_tc_data);
			$i = 1;
			while ($i <= $tag_count && $i <= $this->max_shown_tags) {
				$a_tags[key($this->a_tc_data)] = current($this->a_tc_data);
				next($this->a_tc_data);
				$i++;
			}
			$this->a_tc_data = $a_tags;

			return true;
		}

		/**
		 * Create the Tagcloud
		 *
		 * @return	string				Returns the HTML code for the tagcloud
		 */
		function get_tagcloud() {
			if (count($this->a_tc_data) <= 0) {
				// no tags
				return '';
			}

			// calculate the range that lies between the the different tag sizes
			reset($this->a_tc_data);
			$count_high = current($this->a_tc_data);
			$count_low = end($this->a_tc_data);

			$range = ($count_high - $count_low) / (count($this->a_tag_styles) - 1);

			// sort the tags alphabetically
			//sort the array by keyword (not casesensitive)
			uksort($this->a_tc_data, "strnatcasecmp");


			// generate the html for the cloud
			if ($range > 0) {
				$b_first = true;
				$prev_search = '';


				foreach ($this->a_tc_data as $tag => $tagcount) {
				$tag1 = str_replace(" ","%20",$tag);
					if ($b_first) {
						$html_cloud = '<div> <a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $tag1 . '&search_in_description=1') .'" title="'. $tag1 .'">' .
						                 '<span style="' . $this->a_tag_styles[round(($tagcount - $count_low) / $range, 0)] . '" ' . 
										       'class="' . $this->a_tag_class[round(($tagcount - $count_low) / $range, 0)] . '" role="button">' . $tag . ' </span></a>';
						$b_first = false;
					} else {
					 $test = round(($tagcount - $count_low) / $range, 0) ;
						$html_cloud .= '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $tag1 . '&search_in_description=1') .'" title="'. $tag1 .'">'.
						                   '<span style="' . $this->a_tag_styles[round(($tagcount - $count_low) / $range, 0)] . '" ' . 
										         'class="' . $this->a_tag_class[round(($tagcount - $count_low) / $range, 0)] . '" role="button">' . $tag . ' </span></a>';
					}
				}
				$html_cloud .= '</div>';
			} else {
				// all tags are the same size
				$b_first = true;
				foreach ($this->a_tc_data as $tag => $tagcount) {
					$tag1 = str_replace(" ","%20",$tag);

					if ($b_first) {
						$html_cloud = '<span class="' . $this->a_tag_class[0] . '"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $tag1 . '&search_in_description=1') .'" title="'. $tag1 .'" role="button">' . $tag . '</a></span>';
						$b_first = false;
					} else {
						$html_cloud .= ' <span class="' . $this->a_tag_class[0] . '"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . $tag1 . '&search_in_description=1') .'" title="'. $tag1 .'" role="button">' . $tag . '</a></span>';
					}
				}
			}

			return $html_cloud;
		}
	}

  class bm_tag_search {


    var $code = 'bm_tag_search';
    var $group = 'boxes';
    var $title='tag';
    var $description='show tags';
    var $sort_order='5080';
    var $enabled = false;
	var $pages;

    function bm_tag_search() {
      $this->title = MODULE_BOXES_TAG_SEARCH_TITLE;
      $this->description = MODULE_BOXES_TAG_SEARCH_DESCRIPTION;

      if ( defined('MODULE_BOXES_TAG_SEARCH_STATUS') ) {
           
        $this->sort_order = MODULE_BOXES_TAG_SEARCH_SORT_ORDER;
        $this->enabled = ( MODULE_BOXES_TAG_SEARCH_STATUS == 'True') ;
        $this->pages = MODULE_BOXES_TAG_SEARCHS_CLOUD_DISPLAY_PAGES;
		$placement = MODULE_BOXES_TAG_SEARCH_CONTENT_PLACEMENT;
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
      global $HTTP_GET_VARS, $languages_id, $currencies ;
//    $products_query_raw = "select search, freq from customers_searches where language_id = " .$languages_id." order by search DESC";
      $products_query_raw = "select search, freq from " . TABLE_SEARCH_CUSTOMERS . " where  stores_id = '" . SYS_STORES_ID . "' and language_id = " .$languages_id." order by search DESC";
      $products_query1 = tep_db_query($products_query_raw);
   
	  $tc_a_tags = array();

      while ($tcproducts = tep_db_fetch_array($products_query1)) {
	      $tc_word = preg_replace('/(<(?:[^"\']|"(?:[^"]|\\\")*?"|\'(?:[^\']|\\\')*?\')*?' . '>)/si', ' ',$tcproducts['search']);
	      $tc_freq =  $tcproducts['freq'];
	      $tc_a_tags[$tc_word] = $tc_freq;
     }
     if ( !tep_not_null( $tc_a_tags ) ) {
          $tc_a_tags = array( '' ) ;
	 }
     return $tc_a_tags ;
}

    function execute() {
      global $HTTP_GET_VARS, $languages_id, $currencies, $oscTemplate, $cache,$language ;
      if (USE_CACHE == 'true')  {
	    $cache_name = 'bm_search_tag_box-' . $language . '.cache'  ;
	    $cache->is_cached($cache_name, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		  $tc_a_tags = $this->getData();
		  $cache->save_cache($cache_name, $tc_a_tags, 'ARRAY', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	    } else {
	  	  $tc_a_tags = $cache->get_cache($cache_name, 'ARRAY');	  
	    }  		
      } else {
        $tc_a_tags = $this->getData();
      }

	$tc_tch = new tagcloud();
	// hand over the tags to the class
	$tc_tch->set_tagcloud_data($tc_a_tags);
	// request the tagcloud
	$tc_tagcloud = $tc_tch->get_tagcloud();
	
	$title = True ;
/*
      $placement = MODULE_BOXES_TAG_SEARCH_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Head Column" :
			case "Foot Column" :
				 $data = '<div class="panel panel-default panel-primary">' .
                  '  <div class="panel-heading">' . MODULE_BOXES_TAG_SEARCH_TITLE . '</div>' .
                  '  <div class="panel-body">' . 
				      $tc_tagcloud . 
				  '  </div>' .
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
                  '  <div class="panel-body">' . 
				      $tc_tagcloud . 
				  '  </div>' .
                  '</div>';				break;
	   } 
*/
	   ob_start();
         include(DIR_WS_MODULES . 'boxes/templates/tag_search.php') ;
       $data = ob_get_clean();		   
       $oscTemplate->addBlock($data, $this->group);      
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TAG_SEARCH_STATUS');
    }

    function install() {
	  global $multi_stores_config;	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable TAG Cloud Module', 'MODULE_BOXES_TAG_SEARCH_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TAG_SEARCH_CONTENT_PLACEMENT', 'Right Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TAG_SEARCH_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '5', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Box-width', 'MODULE_BOXES_TAG_SEARCH_WIDTH', '150px', 'if customers search for very long word, it could destroy your design, so this is the max width', '6', '10', now())");	    
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max. shown Tags', 'MODULE_BOXES_TAG_SEARCH_MAX_SHOWN_TAGS', '10', 'How much Tags should be shown?', '6', '10', now())");	    
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_TAG_SEARCHS_CLOUD_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '20','tep_cfg_select_pages(' , now())");	    
	      }

    function remove() {
	  global $multi_stores_config;	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_TAG_SEARCH_STATUS', 'MODULE_BOXES_TAG_SEARCH_CONTENT_PLACEMENT', 'MODULE_BOXES_TAG_SEARCH_SORT_ORDER', 'MODULE_BOXES_TAG_SEARCH_WIDTH', 'MODULE_BOXES_TAG_SEARCH_MAX_SHOWN_TAGS', 'MODULE_BOXES_TAG_SEARCHS_CLOUD_DISPLAY_PAGES' );
    }
  }
?>