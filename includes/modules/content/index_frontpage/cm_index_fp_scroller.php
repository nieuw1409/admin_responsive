<?php
/*  $Id: scroller.php v1.0 20101109 Kymation $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_fp_scroller {
    var $code ;
    var $group ;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $count;
	var $pages ;
    // Set the number in the following line to the number of featured products desired.
   // var $featured_products = 10;

    function cm_index_fp_scroller() {
      global $PHP_SELF;

      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));		  
      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_DESCRIPTION;

      if( defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS' ) ) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
        $this->count = MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_MAX_DISPLAY + 1;
      }

      // Include the function that is used to add products in the Admin
      if( $PHP_SELF == 'modules.php' ) {
        include_once( DIR_WS_FUNCTIONS . 'modules/front_page/featured.php');
      }
    }
	
function getData() {
      global $PHP_SELF, $oscTemplate, $cPath, $languages_id;
	  $text_scroller = '' ;
      $customer_group_id = tep_get_cust_group_id()  ;

// BOF Separate Pricing Per Customer	  
       // Start the scroller code to display on the front page
        // Select the sort order of the products
        switch( MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_ORDER ) {
        	case 'date added':
            $order_sql = ' and s.customers_group_id = ' . (int)$customer_group_id . 'products_date_added DESC';
            break;

        	case 'last modified':
            $order_sql = ' and s.customers_group_id = ' . (int)$customer_group_id . 'products_last_modified DESC';
            break;

        	case 'random':
            $order_sql = 'rand()';
            break;
        } // switch( MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_ORDER

        switch( MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_TYPE ) {
        	case 'specials':
            $join_sql = "join " . TABLE_SPECIALS . " s
              on s.products_id = p.products_id";
            $where_sql = "and s.status = '1' and s.customers_group_id = '" . (int)$customer_group_id . "'";
            break;

        	case 'new':
            $join_sql = '';
            $where_sql = '';
            $order_sql = 'products_date_added DESC';
            break;

        	case 'featured':
        	  $products_ids = '';
            for( $id=1;  $id <= $this->featured_products; $id++ ) {
            	$pid = constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCT_' . $id );
            	if( $pid != 0 ) {
            	  $products_ids .= $pid . ', ';
            	}
            }
        	  $products_ids = rtrim( $products_ids, ', ' );
            $join_sql = '';
            $where_sql = ' and p.products_id in (' . $products_ids . ') and s.customers_group_id = ' . (int)$customer_group_id . '';
            break;

          case 'all':
          default:
            $join_sql = '';
            $where_sql = '';
            break;
        } // switch( MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_TYPE


        $where_sql .= "and find_in_set('" . SYS_STORES_ID . "',    c.categories_to_stores)     != 0 and find_in_set('" . SYS_STORES_ID . "',      p.products_to_stores) != 0" ; // multi stores
        $where_sql .= "	and find_in_set('". $customer_group_id."', p.products_hide_from_groups) = 0 and find_in_set('" . $customer_group_id . "', c.categories_hide_from_groups) = 0 " ; // sppc
 
        $products_query_raw = "
          select
            p.products_id,
            p.products_image,
            pd.products_name
          from
            " . TABLE_PRODUCTS . " p
            join " . TABLE_PRODUCTS_DESCRIPTION . " pd
              on pd.products_id = p.products_id
            left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
			  on p.products_id = p2c.products_id      
            left join " . TABLE_CATEGORIES . " c 
			  on p2c.categories_id = c.categories_id
            " . $join_sql . "
          where
            p.products_status = '1' 
			and c.categories_status = '1'
            and pd.language_id = '" . ( int )$languages_id . "'
            " . $where_sql . "
          order by
            " . $order_sql . "
          limit
            " . MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_MAX_DISPLAY . "
        ";
        // print 'Products Query: ' . $products_query_raw . '<br />';
        $products_query = tep_db_query( $products_query_raw );

		$total_pics = 0 ;
        if( tep_db_num_rows( $products_query ) > 0 ) {
		  $class_column = 12 / MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_QNTY_PRODUCTS ;
          // Set the text to display on the front page
          $text_scroller = '<!-- Scroller BOF -->' . PHP_EOL ;		  
          $text_scroller .= '            <div id="carousel-scroller" class="carousel slide" 
		                                  data-ride="carousel" data-interval="' . MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SCROLL_INTERVAL . '"> '. PHP_EOL ;
                
          $text_scroller .= '                <!-- Carousel items -->'. PHP_EOL ;
          $text_scroller .= '                <div class="carousel-inner">' . PHP_EOL ;
		  
          $text_scroller .= '                    <div class="item active">'. PHP_EOL ;		  
		  
		  $items = 1 ;
          while( $products_data = tep_db_fetch_array( $products_query ) ) {
            $text_scroller .= '                           <div class="col-sm-' . $class_column . '">' .
	 	                                                  '<a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $products_data['products_id'] ) . '">' .
	 													      tep_image(DIR_WS_IMAGES . $products_data['products_image'], $products_data['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, ' style="padding: 0;" width="100%" itemprop="image"', 'false')  .
	 													  '</a>'. PHP_EOL ;
           $text_scroller .= '                                <br />' ;														  
           $text_scroller .= '                           </div>' ;
		   
		   $total_pics++ ;
	   
		   if ( ++$items > MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_QNTY_PRODUCTS ) {
		     if ( $total_pics < MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_MAX_DISPLAY ) { 
		        $items = 1 ;
                $text_scroller .= '                 </div>' . PHP_EOL ;	// end div for class item
		        $text_scroller .= '                 <div class="item">'. PHP_EOL ;	// begin div for next items group
             }				
		   }
			 
          } // while( $products_data
          
		  $text_scroller .= '                    </div>'. PHP_EOL ;	// end div for class item		  
		  $text_scroller .= '                 </div>'. PHP_EOL ;	// end div for class carousel-inner				  
		  $text_scroller .= '               </div>'. PHP_EOL ;	    // end div for class  carousel-scroller
        }
	return $text_scroller ;
}
	

    function execute() {
      global $PHP_SELF, $oscTemplate, $cPath, $languages_id, $language, $cache ;

      $customer_group_id = tep_get_cust_group_id()  ;

      if( $PHP_SELF == 'index.php' && $cPath == '' ) {

        if ((USE_CACHE == 'true') && ( MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_USE_CACHE == 'True' ) ) {

	      $cache_name = 'frontpage_scroller-' . $language . '-cg' . $customer_group_id . '.cache'  ;
	      $cache->is_cached($cache_name, $is_cached, $is_expired);
          if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
  		    $text_scroller = $this->getData();
		    $cache->save_cache($cache_name, $text_scroller, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	      } else {
	  	    $text_scroller = $cache->get_cache($cache_name, 'RETURN');	  
	      }  		
        } else {
          $text_scroller = $this->getData();
        }
        $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_CONTENT_WIDTH;

        ob_start();
          include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/scroller.php');
        $template = ob_get_clean();	 		

        $oscTemplate->addContent( $template, $this->group );
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS' );
    }

    function install() {
	  global $multi_stores_config ;	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Scroller', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS', 'True', 'Do you want to show the scroller?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");    
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '1', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('How much products in scroller', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_QNTY_PRODUCTS', '4', 'The amount of products shown per scroll.', '6', '3', 'tep_cfg_select_option(array(\'4\', \'6\', \'12\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Manual Scroll Interval', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SCROLL_INTERVAL', '2000', 'The time between each manual scroll step (milliseconds).', '6', '12', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Products Shown', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_TYPE', 'all', 'What products do you want to show?', '6', '15', 'tep_cfg_select_option(array(\'all\', \'specials\', \'new\', \'featured\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Products Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_ORDER', 'random', 'In what order do you want your products to show?', '6', '16', 'tep_cfg_select_option(array(\'random\', \'date added\', \'last modified\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache for Products Scroller', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_USE_CACHE', 'True', 'If th shop cache is activated. Activate the cache for the Products Scroller', '6', '20', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_MAX_DISPLAY', '20', 'The maximum number of products to display in the scroller.', '6', '14', now())");  
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");	  
	  

      for( $id=1;  $id <= $this->featured_products; $id++ ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Scroller Product #" . $id . "', 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCT_" . $id . "', '', 'Select product #" . $id . " to show', '6', '" . ($id + 15) . "', 'tep_cfg_pull_down_products(', now())" );
      }
    }

    function remove() {
	  global $multi_stores_config ;	
      tep_db_query( "delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')" );
    }

    function keys() {
      $keys = array();
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SORT_ORDER';	  
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_CONTENT_WIDTH';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_QNTY_PRODUCTS';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_SCROLL_INTERVAL';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_TYPE';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCTS_ORDER';
	  $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_USE_CACHE';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_MAX_DISPLAY'; 

      for( $id=1;  $id <= $this->featured_products; $id++ ) {
        $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_PRODUCT_' . $id;
      }

      return $keys;
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_SCROLLER_STATUS == 'False');

    }			
  } // End class
?>