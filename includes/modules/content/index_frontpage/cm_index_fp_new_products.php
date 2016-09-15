<?php
/*  $Id: new_products.php v1.0 20101109 Kymation $
  Most of the execute() code is from the stock osCommerce New Products module
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_fp_new_products {
    var $code  ;
    var $group ;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;

    function cm_index_fp_new_products() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));			
      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_DESCRIPTION;

      if( defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS' ) ) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
      }
    }
	
function getData() {
      global $oscTemplate, $languages_id, $language, $currencies, $PHP_SELF, $cPath, $pf, $new_products_category_id ;
      if ($PHP_SELF == 'index.php' && $cPath == '') {	  
        $customer_group_id = tep_get_cust_group_id()  ;

      // Set the text to display on the front page
        $new_prods_content = '<!-- New Products BOF -->' . PHP_EOL;
//	   $new_prods_content .= '<div class="container-fluid">' . PHP_EOL;	
//	   $new_prods_content .= '<div class="panel panel-default panel-primary">' . PHP_EOL;
	   
        if( constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE_' . strtoupper( $language ) ) != '') {		
//          $new_prods_content .= '  <h2>' . sprintf( constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE_' . strtoupper( $language ) ), strftime('%B')) . '</h2>' . "\n";
//	      $new_prods_content .= '  <div class="panel-heading">' . PHP_EOL;
	      $new_prods_content_heading .= sprintf( constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE_' . strtoupper( $language ) ), strftime('%B')) ;
//	      $new_prods_content .= '  </div>' . PHP_EOL;						  
        }
//        $new_prods_content .= '  <div class="contentText">' . "\n";

        if ((!isset ($new_products_category_id)) || ($new_products_category_id == '0')) {
           $new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, s.status, s.specials_new_products_price, p.products_price, p.products_purchase from " 
	                                        . TABLE_PRODUCTS . " p left join " 
											. TABLE_SPECIALS . " s on p.products_id = s.products_id and '" . $customer_group_id . "' = s.customers_group_id, "
											. TABLE_PRODUCTS_DESCRIPTION . " pd, " 
											. TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " 
											. TABLE_CATEGORIES . " c 
											   where c.categories_status='1' 
											     and p.products_id = p2c.products_id
												 and p2c.categories_id = c.categories_id 
												 and p.products_status = '1' 
												 and p.products_id = pd.products_id 
												 and pd.language_id = '" . (int)$languages_id . "' 
												 and find_in_set('".$customer_group_id."', p.products_hide_from_groups) = 0 
												 and find_in_set('" . $customer_group_id . "', c.categories_hide_from_groups) = 0 
												 and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    
												 and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
												order by p.products_date_added desc limit " . MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_MAX_DISPLAY);
// EOF Enable & Disable Categories	
        } else {
// BOF Enable & Disable Categories  
           $new_products_query = tep_db_query("  select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, s.status, s.specials_new_products_price, p.products_price, p.products_purchase from " 
	           . TABLE_PRODUCTS . " p left join " 
			   . TABLE_SPECIALS . " s on p.products_id = s.products_id and '" . $customer_group_id . "' = s.customers_group_id, "
			   . TABLE_PRODUCTS_DESCRIPTION . " pd, " 
			   . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " 
			   . TABLE_CATEGORIES . " c 
			       where c.categories_status='1' 
				       and p.products_id = p2c.products_id 
					   and p2c.categories_id = c.categories_id 					   
					   and c.parent_id = '" . (int)$new_products_category_id . "' 
					   and p.products_status = '1' 
					   and p.products_id = pd.products_id 
					   and pd.language_id = '" . (int)$languages_id . "' 
					   and find_in_set('".$customer_group_id."', p.products_hide_from_groups) = 0 
					   and find_in_set('".$customer_group_id."', c.categories_hide_from_groups) = 0 
					   and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0    
					   and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
					order by rand() desc limit " . MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_MAX_DISPLAY);
        } 	
	
    // EOF Enable & Disable Categories	
//      $col = 0;

//      $new_prods_content .= '    <table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";
        while ($new_products = tep_db_fetch_array($new_products_query)) {
	    
		  $pf->loadProduct( (int)$new_products['products_id'],(int)$languages_id);		

		  $text_new_products .= '<div class="col-sm-6 col-md-4">';
		  $text_new_products .= '  <div class="thumbnail">';
		  $text_new_products .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . 
		                                               tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'itemprop="image"', false) . '</a>';
		  $text_new_products .= '    <div class="caption">';
		  $text_new_products .= '      <p class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a></p>';
		  $text_new_products .= '      <hr>';
		  $text_new_products .= '      <p class="text-center">' . '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
														   '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
														   $pf->getPriceStringShort( ) .
														   '</font></span><br />' . PHP_EOL . '</p>';
		  $text_new_products .= '      <div class="clearfix"></div>';													   
		  $text_new_products .= '      <div class="text-center">';
		  $text_new_products .= '        <div class="btn-group btn-inline">';
		  $text_new_products .= '      ' .  tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 
																			 'circle-arrow-right', 
																			 tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $new_products['products_id']), 
																			 NULL, 
																			 NULL, 
																			 'btn-sm btn-default');
		  
		  if ( $new_products['products_purchase'] == '1')  { 	
			$text_new_products .= '      ' . tep_draw_button(SMALL_IMAGE_BUTTON_BUY, 
																			 'shopping-cart', 
																			 tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products['products_id']), 
																			 NULL, 
																			 NULL, 
																			 'btn-success btn-sm');
		  
		  }		
		  $text_new_products .= '        </div>';
		  $text_new_products .= '      </div>';
		  $text_new_products .= '    </div>'; 
		  $text_new_products .= '  </div>';
		  $text_new_products .= '<div class="clearfix"></div>';		  
		  $text_new_products .= '</div>';   		

        }	
      }

//      $new_prods_content .= '    </table>' . PHP_EOL ;;
//      $new_prods_content .= '  </div>' . PHP_EOL;
//      $new_prods_content .= ' </div>' . PHP_EOL;
//      $new_prods_content .= '</div>' . PHP_EOL;	  
      $new_prods_content .= '<!-- New Products EOF -->' . PHP_EOL;
	  
      $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_CONTENT_WIDTH;

      ob_start();
         include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/new_products.php');
      $template = ob_get_clean();	 		
		
      return $template ;
}	

    function execute() {
      global $oscTemplate, $languages_id, $language, $currencies, $PHP_SELF, $cPath, $cache;
 
      $customer_group_id = tep_get_cust_group_id()  ;
      if ((USE_CACHE == 'true') && ( MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_USE_CACHE == 'True') ) {

	      $cache_name = 'frontpage_new_products-' . $language . '-cg' . $customer_group_id . '.cache'  ;
	      $cache->is_cached($cache_name, $is_cached, $is_expired);
          if ( !$is_cached || $is_expired ){ // must not be cached or is expired 
  		    $new_prods_content = $this->getData();
		    $cache->save_cache($cache_name, $new_prods_content, 'RETURN', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	  
	      } else {
	  	    $new_prods_content = $cache->get_cache($cache_name, 'RETURN');	  
	      }  		
      } else {
          $new_prods_content = $this->getData();
      }     
      $oscTemplate->addContent($new_prods_content, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS' );
    }

    function install() {
	  global $multi_stores_config ;	
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable New Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS', 'True', 'Do you want to show the New Products box on the front page?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '1', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max New Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_MAX_DISPLAY', '6', 'How many New Products do you want to show on the front page?', '6', '2', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Columns', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_COLUMNS', '3', 'Number of columns of products to show', '6', '3', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Cache for New Products', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_USE_CACHE', 'True', 'If th shop cache is activated. Activate the cache for the New Products Box', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");	    

      foreach( $this->languages_array as $language_name ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ( '" . ucwords( $language_name ) . " Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE_" . strtoupper( $language_name ) . "', 'Title %s', 'Enter the title that you want on your box in " . $language_name . " (%s inserts the current month).', '6', '14', now())" );
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

      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_SORT_ORDER';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_CONTENT_WIDTH';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_MAX_DISPLAY';
      $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_COLUMNS';
	  $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_USE_CACHE' ;

      foreach( $this->languages_array as $language_name ) {
        $keys[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_TITLE_' . strtoupper( $language_name );
      }

      return $keys;
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_NEW_PRODUCTS_STATUS == 'False');

    }			
  }
?>