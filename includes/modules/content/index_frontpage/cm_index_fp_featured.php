<?php
/*  $Id: featured.php v1.1.3 20101118 Kymation $
  Most of the execute() code is from the stock osCommerce New Products module
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_fp_featured {
    var $code  ;
    var $group ;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $count;
	var $pages ;
    // Set the number in the following line to the number of featured products desired.
    var $featured_products = 10;

    function cm_index_fp_featured() {
      global $PHP_SELF;	  
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));	
      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_DESCRIPTION;

      if( defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS' ) ) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
        $this->count = MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_MAX_DISPLAY + 1;
      }

      // Include the function that is used to add products in the Admin
      if( $PHP_SELF == 'modules_content.php' ) {
        include_once( DIR_WS_FUNCTIONS . 'modules/front_page/featured.php');
      }

    	if( defined( 'MAX_DISPLAY_FEATURED_PRODUCTS' ) ) {
        $this->featured_products = MAX_DISPLAY_FEATURED_PRODUCTS;
    	}
    }

function getData() {
     global $oscTemplate, $languages_id, $language, $currencies, $PHP_SELF, $cPath, $pf;

     $customer_group_id = tep_get_cust_group_id()  ;

      // Set the text to display on the front page
	 $text_featured_products = '<!-- Featured Products BOF -->' . PHP_EOL;
     if( constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_FRONT_TITLE_' . strtoupper( $language ) ) != '') {
	      $featured__content_heading .= constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_FRONT_TITLE_' . strtoupper( $language ) ) ;
     }
        
     for( $id = 1;  $id <= $this->count; $id++ ) {
      	$products_id = @constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_' . $id );
      	if( $products_id > 0 ) {
         $featured_products_query_raw = "
            select
              p.products_id,
              pd.products_name,
              p.products_price,
              p.products_tax_class_id,
              p.products_image,
			  p.products_purchase,
              s.specials_new_products_price,
              s.status
            from
              " . TABLE_PRODUCTS . " p
              join " . TABLE_PRODUCTS_DESCRIPTION . " pd
                on pd.products_id = p.products_id
              left join " . TABLE_SPECIALS . " s
                on s.products_id = p.products_id
           left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
			  on p.products_id = p2c.products_id      
            left join " . TABLE_CATEGORIES . " c 
			  on p2c.categories_id = c.categories_id					
            where
			  c.categories_status = '1'
              and p.products_id = '" . $products_id . "'              	
		      and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 
		      and find_in_set('" . $customer_group_id . "', c.categories_hide_from_groups) = 0 
              and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    
			  and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 			  
              and pd.language_id = '" . ( int )$languages_id . "'
          ";
          // print 'Featured Query: ' . $featured_products_query_raw . '<br />';
          $featured_products_query = tep_db_query( $featured_products_query_raw );
          $featured_products = tep_db_fetch_array( $featured_products_query );
		  
		  $pf->loadProduct( (int)$products_id,(int)$languages_id);		

		  $text_featured_products .= '<div class="col-sm-6 col-md-4">';
		  $text_featured_products .= '  <div class="thumbnail">';
		  $text_featured_products .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . 
		                                               tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'itemprop="image"', false) . '</a>';
		  $text_featured_products .= '    <div class="caption">';
		  $text_featured_products .= '      <p class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a></p>';
		  $text_featured_products .= '      <hr>';
		  $text_featured_products .= '      <p class="text-center">' . '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
														   '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
														   $pf->getPriceStringShort() .
														   '</font></span><br />' . PHP_EOL . '</p>';
		  $text_featured_products .= '      <div class="clearfix"></div>';													   
		  $text_featured_products .= '      <div class="text-center">';
		  $text_featured_products .= '        <div class="btn-group btn-inline">';
		  $text_featured_products .= '      ' .  tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 
																			 'circle-arrow-right', 
																			 tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $featured_products['products_id']), 
																			 NULL, 
																			 NULL, 
																			 'btn-sm btn-default');
		  
		  if ( $featured_products['products_purchase'] == '1')  { 	
			$text_featured_products .= '      ' . tep_draw_button(SMALL_IMAGE_BUTTON_BUY, 
																			 'shopping-cart', 
																			 tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products['products_id']), 
																			 NULL, 
																			 NULL, 
																			 'btn-success btn-sm');
		  
		  }		
		  $text_featured_products .= '        </div>';
		  $text_featured_products .= '      </div>';
		  $text_featured_products .= '    </div>'; 
		  $text_featured_products .= '  </div>';
		  $text_featured_products .= '<div class="clearfix"></div>';		  
		  $text_featured_products .= '</div>';    

 
          }
        } // for( $id=1;
        $text_featured_products .= '<!-- Featured Products EOF -->' . PHP_EOL;
		
        $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_CONTENT_WIDTH;

        ob_start();
         include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/featured.php');
        $template = ob_get_clean();	 		
		
        return $template ;
}

    function execute() {
      global $oscTemplate, $languages_id, $language, $currencies, $PHP_SELF, $cPath, $cache ;
      $customer_group_id = tep_get_cust_group_id()  ;

      if ($PHP_SELF == 'index.php' && $cPath == '') {
         if ((USE_CACHE == 'true') && ( MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_USE_CACHE == 'True') ) {
	       $cache_name = 'front_page_featured-' . $language . '-cg' . $customer_group_id . '.cache'  ;
	       $cache->is_cached($cache_name, $is_cached, $is_expired);
	       if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
		     $featured__content = $this->getData();
		     $cache->save_cache($cache_name, $featured__content, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	       } else {
	  	     $featured__content= $cache->get_cache($cache_name, 'RETURN');	  
	       }  		
         } else {
           $featured__content= $this->getData();
         }
         $oscTemplate->addContent( $featured__content, $this->group );
	  } // end index.php
    } // function execute

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS' );
    }

    function install() {
      global $multi_stores_config ;		
      if( !defined( 'MAX_DISPLAY_FEATURED_PRODUCTS' ) ) {
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max Featured Products', 'MAX_DISPLAY_FEATURED_PRODUCTS', '10', 'Set the maximum number of featured products to allow.', '6', '222', now())");
      }

      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS', 'True', 'Do you want to show the Featured box on the front page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_MAX_DISPLAY', '6', 'How many featured products do you want to show?', '6', '3', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Columns', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_COLUMNS', '3', 'Number of columns of products to show', '6', '4', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache for Featured Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_USE_CACHE', 'True', 'If th shop cache is activated. Activate the cache for the Featured Products', '6', '20', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");	  

      foreach( $this->languages_array as $language_name ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ( '" . ucwords( $language_name ) . " Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_FRONT_TITLE_" . strtoupper( $language_name ) . "', 'Title', 'Enter the title that you want on your box in " . $language_name . "', '6', '14', now())" );
      }
      
      for ($id = 1; $id <= $this->featured_products; $id++) {
        tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Featured Product #" . $id . "', 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_" . $id . "', '', 'Select featured product #" . $id . " to show', '6', '99', 'tep_cfg_pull_down_products(', now())");
      }
    }

    function remove() {
	  global $multi_stores_config ;	
      tep_db_query( "delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')" );
    }

    function keys() {
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

    	$keys = array ();

    $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_SORT_ORDER';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_CONTENT_WIDTH' ;
//      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_FRONT_TITLE';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_MAX_DISPLAY';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_COLUMNS';
	  $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_USE_CACHE' ;

      foreach( $this->languages_array as $language_name ) {
        $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_FRONT_TITLE_' . strtoupper( $language_name );
      }
      
      for ($id = 1; $id <= $this->featured_products; $id++) {
        $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_PRODUCT_' . $id;
      }

      return $keys;
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_FEATURED_STATUS == 'False');

    }		
  }
?>