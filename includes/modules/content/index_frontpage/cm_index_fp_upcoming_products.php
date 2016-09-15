<?php
/*
  $Id: upcoming_products.php v1.0.4 20120129 Kymation $
  Most of the execute() code is from the stock osCommerce Upcoming Products module

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class cm_index_fp_upcoming_products {
    var $code ;
    var $group ;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;

    function cm_index_fp_upcoming_products() {
     $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));			
      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_DESCRIPTION;

      if (defined('MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS')) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
      }
    }
	
function getData() {
      global $oscTemplate, $language, $languages_id, $PHP_SELF, $cPath;

      $customer_group_id = tep_get_cust_group_id()  ;

// EOF Separate Pricing Per Customer
      // Get the module contents to display on the front page
      $upcoming_query_raw = "
        select
          p.products_id,
          pd.products_name,
		  p.products_image,
          products_date_available as date_expected
        from
          " . TABLE_PRODUCTS . " p
          join " . TABLE_PRODUCTS_DESCRIPTION . " pd
            on pd.products_id = p.products_id
            left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
			  on p.products_id = p2c.products_id      
            left join " . TABLE_CATEGORIES . " c 
			  on p2c.categories_id = c.categories_id			
        where
          to_days(products_date_available) >= to_days(now())
		  and c.categories_status = '1'
          and pd.language_id = '" . ( int )$languages_id . "'
		  and find_in_set('".$customer_group_id."', p.products_hide_from_groups) = 0 
		  and find_in_set('" . $customer_group_id . "', c.categories_hide_from_groups) = 0 
          and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    
		  and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 		  
        order by
          " . MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_FIELD . "
          " . MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_SORT . "
        limit " . MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_MAX_DISPLAY
      ;

        $upcoming_query = tep_db_query($upcoming_query_raw);
//		echo tep_db_num_rows($upcoming_query) ;
        if (tep_db_num_rows($upcoming_query) > 0) {
          $upcoming_prods_content = '<!-- Upcoming Products BOF -->' . PHP_EOL;
//	    $upcoming_prods_content .= '<div class="container-fluid">' . PHP_EOL;	
//	    $upcoming_prods_content .= '<div class="panel panel-default panel-primary">' . PHP_EOL;		  

          if( constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_TITLE_' . strtoupper( $language ) ) != '') {
//            $upcoming_prods_content .= '  <h2>' . constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_TITLE_' . strtoupper( $language ) ) . '</h2>';
//	        $upcoming_prods_content .= '  <div class="panel-heading">' . PHP_EOL;
	        $upcoming_prods_content_heading .= constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_TITLE_' . strtoupper( $language ) ) ;
//	        $upcoming_prods_content .= '  </div>' . PHP_EOL;				
          }
		  
//          $upcoming_prods_content .= '<span class="ui-widget-header ui-corner-all" style="float: right;">' . TABLE_HEADING_DATE_EXPECTED . '</span>' . PHP_EOL;
//          $upcoming_prods_content .= '  <div class="contentText">' . PHP_EOL;

          // Start the table to display the product data
          $upcoming_prods_content .= '    <table class="table table-bordered">' . PHP_EOL;
	      $upcoming_prods_content .= '     <thead>'. PHP_EOL ;
	      $upcoming_prods_content .= '             <tr>'. PHP_EOL ;
	      $upcoming_prods_content .= '               <th></th>'. PHP_EOL ;
	      $upcoming_prods_content .= '               <th>'  . TABLE_HEADING_UPCOMING_PRODUCTS . '</th>'. PHP_EOL ;
	      $upcoming_prods_content .= '               <th>' . TABLE_HEADING_DATE_EXPECTED . '</th>'. PHP_EOL ;
	      $upcoming_prods_content .= '             </tr>'. PHP_EOL ;
	      $upcoming_prods_content .= '     </thead>'. PHP_EOL ;		  
	      $upcoming_prods_content .= '     <tbody>'. PHP_EOL ;	  

          while ($upcoming_products = tep_db_fetch_array( $upcoming_query ) ) {
            $upcoming_prods_content .= '        <tr>' . PHP_EOL;
			$upcoming_prods_content .= '          <td align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $upcoming_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $upcoming_products['products_image'], $upcoming_products['products_name'], 50, 50, 'itemprop="image"' ) . '</a></td>' . PHP_EOL;	
            $upcoming_prods_content .= '          <td><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $upcoming_products['products_id']) . '">' . $upcoming_products['products_name'] . '</a></td>' . PHP_EOL;
            $upcoming_prods_content .= '          <td  align="left">' . tep_date_short($upcoming_products['date_expected']) . '</td>' . PHP_EOL;
            $upcoming_prods_content .= '        </tr>' . PHP_EOL;
          }
	      $upcoming_prods_content .= '     </tbody>' . PHP_EOL; 
		  
          // Close the table
          $upcoming_prods_content .= '    </table>' . PHP_EOL;
//          $upcoming_prods_content .= '  </div>' . PHP_EOL;
//          $upcoming_prods_content .= ' </div>' . PHP_EOL;
//          $upcoming_prods_content .= '</div>' . PHP_EOL;		 
		  $upcoming_prods_content .= '<br /><br />' . PHP_EOL;
          $upcoming_prods_content .= '<!-- Upcoming Products EOF -->' . PHP_EOL;
        }
		
        $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_CONTENT_WIDTH;

		$template = '' ;
		if ( tep_not_null( $upcoming_prods_content ) ) {
          ob_start();
            include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/upcoming_products.php');
          $template = ob_get_clean();			
		}		  
	return  $template ;  	  
}

    function execute() {
 //     global $oscTemplate, $languages_id, $currencies;
      global $oscTemplate, $language, $languages_id, $PHP_SELF, $cPath, $cache;
	  
      $customer_group_id = tep_get_cust_group_id()  ;

    if ($PHP_SELF == 'index.php' && $cPath == '') {  
// $upcoming_prods_content = $this->getData() ;
        if ((USE_CACHE == 'true')  && ( MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_USE_CACHE == 'True' )) {

	      $cache_name = 'frontpage_upcoming_products-' . $language . '-cg' . $customer_group_id . '.cache'  ;
	      $cache->is_cached($cache_name, $is_cached, $is_expired);
          if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
  		    $upcoming_prods_content = $this->getData();
		    $cache->save_cache($cache_name, $upcoming_prods_content, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	      } else {
	  	    $upcoming_prods_content = $cache->get_cache($cache_name, 'RETURN');	  
	      }  		
        } else {
          $upcoming_prods_content = $this->getData();
        }     
 
        $oscTemplate->addContent($upcoming_prods_content, $this->group);
      } // endif index.php self
    }  // end execute

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS');
    }

    function install() {
	  global $multi_stores_config ;	
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Upcoming Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS', 'True', 'Do you want to show the Upcoming Products box on the front page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES('Expected Sort Field', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_FIELD', 'date_expected', 'The column to sort by in the expected products box.', '6', '3', 'tep_cfg_select_option(array(\'products_name\', \'date_expected\'), ', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Expected Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_SORT', 'desc', 'This is the sort order used in the expected products box.', '6', '4', 'tep_cfg_select_option(array(\'asc\', \'desc\'), ', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Products Expected', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_MAX_DISPLAY', '10', 'Maximum number of products expected to display', '6', '5', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache for Upcoming Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_USE_CACHE', 'True', 'If th shop cache is activated. Activate the cache for the Upcoming Products', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");	  
	  

    	foreach( $this->languages_array as $language_name ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ( '" . ucwords( $language_name ) . " Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_TITLE_" . strtoupper( $language_name ) . "', 'Title', 'Enter the title that you want on your box in " . $language_name . "', '6', '14', now())" );
      }
    }

    function remove() {
	  global $multi_stores_config ;	
      tep_db_query( "delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

      $keys = array ();

      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_SORT_ORDER';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_CONTENT_WIDTH';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_FIELD';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_SORT';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_MAX_DISPLAY';
	  $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_USE_CACHE';

    	foreach( $this->languages_array as $language_name ) {
    	  $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_TITLE_' . strtoupper( $language_name );
    	}

      return $keys;
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_UPCOMING_PRODUCTS_STATUS == 'False');

    }			
  }
?>