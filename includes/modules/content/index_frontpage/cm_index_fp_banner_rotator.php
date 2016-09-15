<?php
/*  $Id: banner_rotator.php v1.0 20101109 Kymation $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_fp_banner_rotator {
    var $code ;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;

    function cm_index_fp_banner_rotator() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));
	  
      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_DESCRIPTION;

      if( defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS' ) ) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
      }
    }
	
function getData() {
        // Set the banner rotator code to display on the front page
        $banner_query_raw = "
                  select
                    banners_id,
                    banners_url,
                    banners_image,
                    banners_html_text
                  from
                    " . TABLE_BANNERS . "
                  where
                    banners_group = '" . MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_GROUP . "'
                  order by
                    " . MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_BANNER_ORDER . "
                  limit
                    " . (int)MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_MAX_DISPLAY;
	    $body_text = '' ;
        $banner_query = tep_db_query($banner_query_raw);
        if (tep_db_num_rows($banner_query) > 0) {
          $body_text      = '<!-- Banner Rotator BOF -->' . PHP_EOL;
          $body_text_ind  = '  <!-- Indicators --> ' . PHP_EOL;	  
	  
//          $body_text .= ' <div class="container"> ' . PHP_EOL;			  
          $body_text .= '<div id="index_fp_banner_rotater" class="carousel slide" data-ride="carousel"> ' . PHP_EOL;	
		  
		  $active       =  ' class="active" ' ;
		  $active_inner =  ' active ' ;		  
 
		  $body_text_ind    .= '  <ol class="carousel-indicators"> ' . PHP_EOL;
		  $body_text_banner .= '  <div class="carousel-inner" role="listbox">		' . PHP_EOL;
          $counter = 0 ;		  
          while ($banner = tep_db_fetch_array($banner_query)) {		 
		  
		   $body_text_ind     .= ' <li data-target="#index_fp_banner_rotater" data-slide-to="' . $counter++ . '" ' . $active . '></li> ' . PHP_EOL;	
		   $body_text_banner  .= '      <div class="item ' . $active_inner . '">' . PHP_EOL;			   
		   $active        =  ' ' ;
		   $active_inner  =  ' ' ;		   
		   

            if ($banner['banners_url'] != '') {
              $body_text_banner .= '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner['banners_id']) . '" target="_self">';
            }

           	$body_text_banner .= tep_image( DIR_WS_IMAGES . $banner['banners_image'], $banner['banners_html_text'], MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_WIDTH_BANNER, MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_HEIGHT_BANNER, 'itemprop="image"' );

            if( $banner['banners_url'] != '' ) {
              $body_text_banner .= '</a>' . PHP_EOL;	
            }
		   $body_text_banner  .= '      </div>' . PHP_EOL;	

          }
		  $body_text_banner  .= '      </div>' . PHP_EOL;  // end <div class="carousel-inner"
		  $body_text_ind  .= '  </ol> ' . PHP_EOL;	
		  $body_text      .=  $body_text_ind	 ;  // add indicator
		  $body_text      .=  $body_text_banner	 ;  // add links + images
		  
		  $body_text      .= '  </div>		' . PHP_EOL;  // end <div id="carousel-example-generic"		  
//		  $body_text      .= '  </div>		' . PHP_EOL;  // end  <div class="container-flui		  	  
 }
	return $body_text ; 
}

    function execute() {
      global $PHP_SELF, $oscTemplate, $cPath, $language, $cache;
	  
	  $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE_CONTENT_WIDTH;

      if ($PHP_SELF == 'index.php' && $cPath == '') {
        if ((USE_CACHE == 'true') && ( MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_USE_CACHE == 'True' ) ){
	      $cache_name = 'front_page_banner_rotator-' . $language . '.cache'  ;
	      $cache->is_cached($cache_name, $is_cached, $is_expired);
	      if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		    $body_text = $this->getData();
		    $cache->save_cache($cache_name, $body_text, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	      } else {
	  	    $body_text = $cache->get_cache($cache_name, 'RETURN');	  
	      }  		
        } else {
          $body_text = $this->getData();
        }
		
        ob_start();
             include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/banner_rotator.php');
        $template = ob_get_clean();

        $oscTemplate->addContent( $template, $this->group );
      }
      
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS' );
    }

    function install() {
	  global $multi_stores_config ;	     
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Banner Rotator', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS', 'True', 'Do you want to show the banner rotator?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fade Time', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_FADE_TIME', '500', 'The time it takes to fade from one banner to the next. 1000 = 1 second', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Hold Time', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_HOLD_TIME', '4000', 'The time each banner is shown. 1000 = 1 second', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Banner Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_BANNER_ORDER', 'banners_id', 'Order that the Banner Rotator uses to show the banners.', '6', '0', 'tep_cfg_select_option(array(\'banners_id\', \'rand()\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Banner Rotator Group', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_GROUP', 'rotator', 'Name of the banner group that the Banner Rotator uses to show the banners.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Banner Rotator Max Banners', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_MAX_DISPLAY', '4', 'Maximum number of banners that the Banner Rotator will show', '6', '0', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache for Banner Rotator', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_USE_CACHE', 'True', 'If the shop cache is activated. Activate the cache for the Banner Rotator', '6', '20', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");  
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Height for Banner Rotator', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_HEIGHT_BANNER', '600', 'The height of the banners in the Banner Rotater on the index frontpage', '6', '25', now())");  
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Width for Banner Rotator', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_WIDTH_BANNER', '600', 'The width of the banners in the Banner Rotater on the index frontpage', '6', '25', now())");  
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())"); 	  
    }

    function remove() {
	  global $multi_stores_config ;	
      tep_db_query( "delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')" );
    }

    function keys() {
      return array( 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_SORT_ORDER', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_FADE_TIME', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_HOLD_TIME', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_BANNER_ORDER', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_GROUP', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_MAX_DISPLAY', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_USE_CACHE', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_HEIGHT_BANNER', 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_WIDTH_BANNER', 'MODULE_CONTENT_INDEX_FRONTPAGE_CONTENT_WIDTH' );
    }    
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_BANNER_ROTATOR_STATUS == 'False');

    }			
  }
?>