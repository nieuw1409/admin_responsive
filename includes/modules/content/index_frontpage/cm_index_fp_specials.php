<?php
/*  $Id: specials.php v1.0 20101109 Kymation $
  Most of the execute() code is from the stock osCommerce New Products module
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_fp_specials {
    var $code  ;
    var $group ;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;

    function cm_index_fp_specials() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));			
      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_DESCRIPTION;

      if( defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS' ) ) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
      }
    }
	
function getData() {
      global $oscTemplate, $language, $languages_id, $currencies, $PHP_SELF, $cPath, $pf ;

      $specials_content = '' ;
      $customer_group_id = tep_get_cust_group_id()  ;
      $specials_products_query_raw = "
        select
          p.products_id,
          pd.products_name,
          p.products_price,
          p.products_tax_class_id,
          p.products_image,
		  p.products_purchase,
          s.specials_new_products_price
        from
          " . TABLE_PRODUCTS . " p
          join " . TABLE_PRODUCTS_DESCRIPTION . " pd
            on pd.products_id = p.products_id
          join " . TABLE_SPECIALS . " s
            on s.products_id = p.products_id
            left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
			  on p.products_id = p2c.products_id      
            left join " . TABLE_CATEGORIES . " c 
			  on p2c.categories_id = c.categories_id			
        where
          p.products_status = '1'
      	  and c.categories_status = '1'
          and pd.language_id = '" . ( int )$languages_id . "'
          and s.status = '1'
		  and s.customers_group_id = '" . (int)$customer_group_id . "' 
		  and find_in_set('".$customer_group_id."', p.products_hide_from_groups) = 0 
		  and find_in_set('" . $customer_group_id . "', c.categories_hide_from_groups) = 0 		  
		  and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    
		  and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
        order by
          RAND()
        limit
          " . MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_MAX_DISPLAY . "
      ";	  

// EOF Separate Pricing Per Customer

      // print 'Specials Query: ' . $specials_products_query_raw . '<br />';
      $specials_products_query = tep_db_query( $specials_products_query_raw );

      if( tep_db_num_rows( $specials_products_query ) > 0 ) {
        // Set the text to display on the front page
	      $specials_content = '<!-- Specials BOF -->' . PHP_EOL;
//	      $specials_content .= '<div class="container-fluid">' . PHP_EOL;	
//	      $specials_content .= '<div class="panel panel-default panel-primary">' . PHP_EOL;		  
          if( constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_FRONT_TITLE_' . strtoupper( $language ) ) != '') {
//	        $specials_content_heading .= '  <div class="panel-heading">' . PHP_EOL;
          if( MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_LINK == 'True' ) {
              $specials_content_heading .= '<a href="' . tep_href_link( FILENAME_SPECIALS ) . '">';
          }		  
	      $specials_content_heading .= constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_FRONT_TITLE_' . strtoupper( $language ) ) ;
		  
          if( MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_LINK == 'True' ) {
              $specials_content_heading .= '</a>';
          }		  
		  
//	      $specials_content .= '  </div>' . PHP_EOL;			
          }

//        $specials_content .= '  <div class="contentText">' . "\n";
//        $specials_content .= '    <table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";

        $col = 0;
        while( $specials_products = tep_db_fetch_array( $specials_products_query ) ) {
/*        // Format the price for the correct currency
          $products_price = '<del>' . $currencies->display_price( $specials_products['products_price'], tep_get_tax_rate( $specials_products['products_tax_class_id'] ) ) . '</del><br />';
 
	      if ( $customer_group_id != 0 ) {
             $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" .  $specials_products['products_id']  . "' and customers_group_id =  '" . $customer_group_id . "'");
      
             if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
               if (tep_not_null($customer_group_price['customers_group_price'])) {
                  $products_price =  '<del>' . $currencies->display_price($customer_group_price['customers_group_price'], tep_get_tax_rate($specials_products['products_tax_class_id'])) . '</del>' .($last ? '<br />' : '&nbsp;&nbsp;');	
        } 
      }
      // now get the specials price for this customer_group 
	  
    } // end if ($this->cg_id != '0')

	  $products_price .= '<span class="productSpecialPrice">' . $currencies->display_price( $specials_products['specials_new_products_price'], tep_get_tax_rate( $specials_products['products_tax_class_id'] ) ) . '</span>';

          if( $col == 0 ) {
            $specials_content .= '    <tr>' . "\n";
          }

          $width = ( floor (100 / MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_COLUMNS ) );

          $specials_content .= '        <td width="' . $width . '%" align="center" valign="top">' . "\n";
          $specials_content .= '<a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $specials_products['products_id'] ) . '">' . tep_image(DIR_WS_IMAGES . $specials_products['products_image'], $specials_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_products['products_id']) . '">' . $specials_products['products_name'] . '</a><br />' . $products_price;
          $specials_content .= '</td>' . "\n";

          $col ++;

        if( $col > ( MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_COLUMNS - 1 ) ) {
            $specials_content .= '    </tr>' . "\n";
            $col = 0;
          }
*/
		  $pf->loadProduct( (int)$specials_products['products_id'],(int)$languages_id);		

		  $specials_content .= '<div class="col-sm-6 col-md-4">';
		  $specials_content .= '  <div class="thumbnail">';
		  $specials_content .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_products['products_id']) . '">' . 
		                                               tep_image(DIR_WS_IMAGES . $specials_products['products_image'], $specials_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'itemprop="image"', false) . '</a>';
		  $specials_content .= '    <div class="caption">';
		  $specials_content .= '      <p class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_products['products_id']) . '">' . $specials_products['products_name'] . '</a></p>';
		  $specials_content .= '      <hr>';
		  $specials_content .= '      <p class="text-center">' . '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
														   '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
														   $pf->getPriceStringShort( ) .
														   '</font></span><br />' . PHP_EOL . '</p>';
		  $specials_content .= '      <div class="clearfix"></div>';													   
		  $specials_content .= '      <div class="text-center">';
		  $specials_content .= '        <div class="btn-group btn-inline">';
		  $specials_content .= '      ' .  tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 
																			 'circle-arrow-right', 
																			 tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $specials_products['products_id']), 
																			 NULL, 
																			 NULL, 
																			 'btn-sm btn-default');
		  
		  if ( $specials_products['products_purchase'] == '1')  { 	
			$specials_content .= '      ' . tep_draw_button(SMALL_IMAGE_BUTTON_BUY, 
																			 'shopping-cart', 
																			 tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $specials_products['products_id']), 
																			 NULL, 
																			 NULL, 
																			 'btn-success btn-sm');
		  
		  }		
		  $specials_content .= '        </div>';
		  $specials_content .= '      </div>';
		  $specials_content .= '    </div>'; 
		  $specials_content .= '  </div>';
		  $specials_content .= '<div class="clearfix"></div>';		  
		  $specials_content .= '</div>';   	
		  
        } // while( $specials_products

//        $specials_content .= '    </table>' . PHP_EOL;;
//        $specials_content .= '  </div>' . PHP_EOL;
//        $specials_content .= ' </div>' . PHP_EOL;
//        $specials_content .= '</div>' . PHP_EOL;		
        $specials_content .= '<!-- Specials EOF -->' . PHP_EOL;


      } // if( tep_db_num_rows
	  
        $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_CONTENT_WIDTH;

        ob_start();
          include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/specials.php');
        $template = ob_get_clean();	 		  
		
	  return $template ;
}

    function execute() {
      global $oscTemplate, $language, $languages_id, $currencies, $PHP_SELF, $cPath, $cache;

      $customer_group_id = tep_get_cust_group_id()  ;

      if ($PHP_SELF == 'index.php' && $cPath == '') {

        if ((USE_CACHE == 'true') && ( MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_USE_CACHE == 'True' ) ) {

	      $cache_name = 'frontpage_specials-' . $language . '-cg' . $customer_group_id . '.cache'  ;
	      $cache->is_cached($cache_name, $is_cached, $is_expired);
          if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
  		    $specials_content = $this->getData();
		    $cache->save_cache($cache_name, $specials_content, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	      } else {
	  	    $specials_content = $cache->get_cache($cache_name, 'RETURN');	  
	      }  		
        } else {
          $specials_content = $this->getData();
        }     

 	    $oscTemplate->addContent( $specials_content, $this->group );
	  } // if index.php
    } // function execute

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS' );
    }

    function install() {
	  global $multi_stores_config ;	
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Specials', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS', 'True', 'Do you want to show the Specials box on the front page?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '1', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max Specials', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_MAX_DISPLAY', '6', 'How many Specials do you want to show on the front page?', '6', '3', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Columns', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_COLUMNS', '3', 'Number of columns of specials to show', '6', '4', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Link to Specials Page', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_LINK', 'True', 'Do you want the header to link to the specials page?', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache for Specials', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_USE_CACHE', 'True', 'If th shop cache is activated. Activate the cache for the Specials box', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");	  
	 

    	foreach( $this->languages_array as $language_name ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ( '" . ucwords( $language_name ) . " Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_FRONT_TITLE_" . strtoupper( $language_name ) . "', 'Title', 'Enter the title that you want on your box in " . $language_name . "', '6', '14', now())" );
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

      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_SORT_ORDER';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_CONTENT_WIDTH' ;
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_MAX_DISPLAY';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_COLUMNS';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_LINK';
	  $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_USE_CACHE' ;

    	foreach( $this->languages_array as $language_name ) {
    	  $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_FRONT_TITLE_' . strtoupper( $language_name );
    	}

      return $keys;
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_SPECIALS_STATUS == 'False');

    }			
  }
?>