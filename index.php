<?php
/* overtollig verwijderd
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
global $cache;

// Include the Cache_Lite Class
// php lite   require_once( 'D:/usbwebserver8 clean/root/includes/functions/Lite.php' ) ;   // change "/web/htdocs/www.YOURSITE.com/home/" with the full path of your oscommerce installation
// php lite  require_once('/home/verfwebs/domains/verfwebshop.nl/public_html/includes/functions/Lite.php' ) ;   // change "/web/htdocs/www.YOURSITE.com/home/" with the full path of your oscommerce installation
 
// Create a unique ID for the page, in this case, the full URL. You
// should stick to a single convention here!

// php lite   $id = md5('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
 
// Set options and create a new instance of the class - self explantory!
// php lite   $options = array(
// php lite    'cacheDir' => "D:/usbwebserver8 clean/root/temp/",    //this is the folder were the file will be cached, change "/web/htdocs/www.YOURSITE.com/home/" with the full path of your oscommerce installation
// php lite  'cacheDir' => "/home/verfwebs/domains/verfwebshop.nl/public_html/temp/",           //this is the folder were the file will be cached, change "/web/htdocs/www.YOURSITE.com/home/" with the full path of your oscommerce installation
// php lite   'lifeTime' => 3600, // time in seconds of the cache file 
// php lite   'automaticSerialization' => true,
// php lite   );

// php lite  $cache_lite = new Cache_Lite($options);
 
// php lite   if ($data = $cache_lite->get($id)) { 
// php lite   } else { 
         // Use PHP output buffering to save the contents of the webpage to a variable,
         //for use in the cache file
// php lite      ob_start();
	
  require('includes/application_top.php');
  
// bof multi stores
   $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	

// the following cPath references come from application_top.php
/*
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
    $categories_products = tep_db_fetch_array($categories_products_query);
    if ($categories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
*/
  $category_depth = 'top';
  global $countproducts;
  $cache_name_1 = 'count_prod_in_cat_1';    // for if (isset($cPath) && tep_not_null($cPath)) {
  $cache_name_2 = 'count_prod_child_cat1';  // for if ($cateqories_products['total'] > 0) { etc
  $cache_name_3 = 'nested1';                // for if ($category_depth == 'nested') {
  
  if (isset($cPath) && tep_not_null($cPath)) {
    // $countproducts['prods_in_category'] is not set when SHOW_COUNTS is not true
    if (is_object($countproducts) && is_array($countproducts->prods_in_category)) {
      $categories_products['total'] = $countproducts->CountProductsInCategory((int)$current_category_id);
    } else {
	  if(USE_CACHE == 'true') {
	    //$cache_name_1 = 'count_prod_in_cat_1';
	    $cache->is_cached($cache_name_1, $is_cached, $is_expired);
	    if ( !$is_cached || $is_expired ){ // must not be cached or is expired   
          $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
          $cateqories_products = tep_db_fetch_array($categories_products_query);												   		  
		  $cache->save_cache($cache_name_1, $cateqories_products, 'ARRAY', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');	
          // if cache of this is renewed delete all other cache files to determine for the categories depth etc
		  $cache->remove_cache_file( $cache_name_2 ) ;
		  $cache->remove_cache_file( $cache_name_3 ) ;
		  
	    } else {
   	  	  $cateqories_products = $cache->get_cache($cache_name, 'ARRAY');	  
	    }  	
	  } else {	
       $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
       $cateqories_products = tep_db_fetch_array($categories_products_query);
	  }
    } // end if else (is_object($countproducts))
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      if (is_object($countproducts)) {
        $category_parent['total'] = $countproducts->countChildCategories((int)$current_category_id);
      } else {
	    if (USE_CACHE == 'true') {
	      // $cache_name_2 = 'count_prod_child_cat1';
	      $cache->is_cached($cache_name_2, $is_cached, $is_expired);
	      if ( !$is_cached || $is_expired ){ // must not be cached or is expired   
            $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
            $category_parent = tep_db_fetch_array($category_parent_query);
		    $cache->save_cache($cache_name_2, $category_parent, 'ARRAY', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');
            // if cache of this is renewed delete all other cache files to determine for the categories depth etc
		    $cache->remove_cache_file( $cache_name_3 ) ;			
	      } else {
	  	    $category_parent = $cache->get_cache($cache_name_2, 'ARRAY');	  
	      }  		  
		} else {
         $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
         $category_parent = tep_db_fetch_array($category_parent_query);
		}
      } // end if else (is_object($countproducts))
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  require(DIR_WS_INCLUDES . 'template_top.php');

  if ($category_depth == 'nested') {
//	if (USE_CACHE == 'true') { 	  
	  // $cache_name_3 = 'nested1';
//	  $cache->is_cached($cache_name_3, $is_cached, $is_expired);
//	  if ( !$is_cached || $is_expired ){ // must not be cached or is expired 	  
//        $category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_htc_title_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd 
//	                                            where c.categories_id = '" . (int)$current_category_id . "' 
//												   and cd.categories_id = '" . (int)$current_category_id . "' 
//												   and cd.language_id = '" . (int)$languages_id . "' 
//												   and find_in_set('".$customer_group_id."', categories_hide_from_groups) = 0 order by sort_order, cd.categories_name");
//        $category = tep_db_fetch_array($category_query);														   
//		  
//		  $cache->save_cache($cache_name_3, $category, 'ARRAY', 0, 0, PAGE_CACHE_LIFETIME . '/minutes');		  
//	  } else {
//	  	  $category = $cache->get_cache($cache_name_3, 'ARRAY');	  
//	  }  
//	} else {
/*** Begin Header Tags SEO 331 ***/
      $category_query = tep_db_query("select cd.categories_name, c.categories_image, IF(cd.categories_htc_title_tag_alt != '',cd.categories_htc_title_tag_alt,cd.categories_htc_title_tag) as categories_htc_title_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd 
	                                            where c.categories_id = '" . (int)$current_category_id . "' 
												   and cd.categories_id = '" . (int)$current_category_id . "' 
												   and cd.language_id = '" . (int)$languages_id . "' 
												   and find_in_set('".$customer_group_id."', categories_hide_from_groups) = 0 and find_in_set('" . SYS_STORES_ID . "', categories_to_stores) != 0 
												      order by sort_order, cd.categories_name");
 /*** end Header Tags SEO 331 ***/														  
      $category = tep_db_fetch_array($category_query);
//	}

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;

?>
<!-- htc title tag -->
<!-- htc category description  -->
          
<div class="contentContainer">
  <div class="contentText">
  
  <div class="row">
      <?php echo $oscTemplate->getContent('index_categories'); ?>
  </div>	  
  
<!-- categories images -->

<?php	
  // new products module
  // header tags social bookmarks
?>
<!--- END Header Tags SEO Social Bookmarks -->              
  </div>
</div>

<?php
  } elseif ($category_depth == 'products' || (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id']))) {
// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_DESCRIPTION' => PRODUCT_LIST_DESCRIPTION,						  // JQUERY PRODUCT LIST
                         'PRODUCT_COMPARE' => PRODUCT_COMPARE, 						 
 // BOF Product Sort
						 'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
						 'PRODUCT_SORT_ORDER' => PRODUCT_SORT_ORDER); 
// EOF Product Sort

    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }
// BOF Separate Pricing Per Customer
// this will build the table with specials prices for the retail group or update it if needed
// this function should have been added to includes/functions/database.php
   if ($customer_group_id == '0') {
      tep_db_check_age_specials_retail_table();
   }
   $status_product_prices_table = false;
   $status_need_to_get_prices = false;

   // find out if sorting by price has been requested
//   if ( (isset($HTTP_GET_VARS['sort'])) && (ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) && (substr($HTTP_GET_VARS['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0' ){// 5.3
   if ( (isset($HTTP_GET_VARS['sort'])) && (preg_match('/[1-8][ad]/', $HTTP_GET_VARS['sort'])) && (substr($HTTP_GET_VARS['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0' ){   
    $_sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
      $status_need_to_get_prices = true;
      }
   }
   if ($status_need_to_get_prices == true && $customer_group_id != '0') {
   $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id;
   // the table with product prices for a particular customer group is re-built only a number of times per hour
   // (setting in /includes/database_tables.php called MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE, in minutes)
   // to trigger the update the next function is called (new function that should have been
   // added to includes/functions/database.php)
   tep_db_check_age_products_group_prices_cg_table($customer_group_id);
   $status_product_prices_table = true;

   } // end if ($status_need_to_get_prices == true && $customer_group_id != '0')
// EOF Separate Pricing Per Customer
    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'PRODUCT_LIST_MODEL':
          $select_column_list .= 'p.products_model, ';
          break;
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'pd.products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
// BOF Product Sort
		case 'PRODUCT_SORT_ORDER':
          $select_column_list .= 'p.products_sort_order, ';
          break;
// EOF Product Sort		  
      }
    }
// show the products of a specified manufacturer
// BOF qpbpp
    // 2.3.3 if (isset($HTTP_GET_VARS['manufacturers_id'])) {
    if (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id'])) {	
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only a specific category
// BOF Separate Pricing Per Customer
	if ($status_product_prices_table == true) { // changed for SPPC hide categories -- ok in mysql 5
	$listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price 
	                                                 from " . TABLE_PRODUCTS . " p left join " . 
													          $product_prices_table . " as tmp_pp using(products_id), " . 
															  TABLE_PRODUCTS_DESCRIPTION . " pd left join " . 
															  TABLE_PRODUCTS_TO_CATEGORIES . " p2c using(products_id) left join " . 
															  TABLE_CATEGORIES . " c using(categories_id), " . 
															  TABLE_MANUFACTURERS . " m 
													 where  c.categories_status = '1'                              and p.products_status = '1' 
															     and p.manufacturers_id = m.manufacturers_id       and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' 
																 and p.products_id = p2c.products_id               and pd.products_id = p2c.products_id 
																 and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";		
	} else { // either retail or no need to get correct special prices -- changed for mysql 5 and 
// SPPC hide categories for groups
	$listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, 
	                                                 IF(s.status, s.specials_new_products_price, p.products_price) as final_price 
													 from " . TABLE_PRODUCTS . " p left join " . 
													          TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id, " . 
															  TABLE_PRODUCTS_DESCRIPTION . " pd, " . 
															  TABLE_MANUFACTURERS . " m, " . 
															  TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . 
															  TABLE_CATEGORIES . " c using(categories_id) 
												     where  c.categories_status = '1'                             and p.products_status = '1' 
													    and p.manufacturers_id = m.manufacturers_id               and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' 
														and p.products_id = p2c.products_id                       and pd.products_id = p2c.products_id 
														and pd.language_id = '" . (int)$languages_id . "'         and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";
	} // end else { // either retail...

      } else {
// We show them all
    if ($status_product_prices_table == true) {
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price 
		         from " . TABLE_PRODUCTS . " p 
		             left join " . $product_prices_table . " as tmp_pp using(products_id) 
					 left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id 
					 left join " . TABLE_CATEGORIES . " c using(categories_id), " . 
					               TABLE_PRODUCTS_DESCRIPTION . " pd, " . 
								   TABLE_MANUFACTURERS . " m 
					  where  c.categories_status = '1'              and  p.products_status = '1' 
					    and pd.products_id = p.products_id          and pd.language_id = '" . (int)$languages_id . "' 
						and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";	
	} else { // either retail or no need to get correct special prices -- changed for mysql 5 & SPPC hide categories
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price 
		         from " . TABLE_PRODUCTS . " p 
				     left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id 
					 left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id 
					 left join " . TABLE_CATEGORIES . " c using(categories_id), " . 
					               TABLE_PRODUCTS_DESCRIPTION . " pd, " . 
								   TABLE_MANUFACTURERS . " m 
					  where  c.categories_status = '1'              and p.products_status = '1' 
					    and pd.products_id = p.products_id          and pd.language_id = '" . (int)$languages_id . "' 
						and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";
	} // end else { // either retail...
		}
    } else {
// show the products in a given categorie
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only specific catgeory

        if ($status_product_prices_table == true) { // ok for mysql 5, SPPC hide categories for groups added
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_purchase, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price 
		            from " . TABLE_PRODUCTS . " p 
					   left join " . $product_prices_table . " as tmp_pp using(products_id), " . 
					                 TABLE_PRODUCTS_DESCRIPTION . " pd, " . 
									 TABLE_MANUFACTURERS . " m, " . 
									 TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
					   left join " . TABLE_CATEGORIES . " c using(categories_id) 
					     where c.categories_status = '1'                      and  p.products_status = '1' 
						   and p.manufacturers_id = m.manufacturers_id        and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' 
						   and p.products_id = p2c.products_id                and pd.products_id = p2c.products_id 
						   and pd.language_id = '" . (int)$languages_id . "'  and p2c.categories_id = '" . (int)$current_category_id . "'";	
        } else { // either retail or no need to get correct special prices -- ok for mysql 5
		// SPPC hide categories for groups added
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_purchase, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price 
		            from " . TABLE_PRODUCTS . " p, " . 
					                  TABLE_PRODUCTS_DESCRIPTION . " pd, " . 
							          TABLE_MANUFACTURERS . " m, " . 
							          TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . 
							          TABLE_SPECIALS_RETAIL_PRICES . " s using(products_id) 
			            left join " . TABLE_CATEGORIES . " c on p2c.categories_id = c.categories_id 
						  where c.categories_status = '1'                     and  p.products_status = '1' 
						    and p.manufacturers_id = m.manufacturers_id       and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' 
							and p.products_id = p2c.products_id               and pd.products_id = p2c.products_id 
							and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
        } // end else { // either retail...
// EOF Separate Pricing Per Customer		
		} else {
// We show them all
        if ($status_product_prices_table == true) { // ok in mysql 5
// SPPC hide categories for groups added
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_purchase, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . $product_prices_table . " as tmp_pp using(products_id), " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_CATEGORIES . " c using(categories_id) where c.categories_status = '1' and  p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
        } else { // either retail or no need to get correct special prices -- changed for mysql 5
// SPPC hide categories for groups added
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_purchase, p.products_qty_blocks, p.products_min_order_qty, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_CATEGORIES . " c using(categories_id) where c.categories_status = '1' and  p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
      } // end else { // either retail...
// EOF Separate Pricing per Customer		
      }
    }
// BOF SPPC Hide products and categories from groups
 $listing_sql .= " and find_in_set('".$customer_group_id."', p.products_hide_from_groups) = 0 ";
 $listing_sql .= " and find_in_set('" . $customer_group_id . "', c.categories_hide_from_groups) = 0 ";
 $listing_sql .= " and find_in_set('" . SYS_STORES_ID . "', c.categories_to_stores) != 0 "; // multi stores
 $listing_sql .= " and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 "; // multi stores 
 // EOF SPPC Hide products and categories from groups	
 
//EOF qpbpp


    if ( (!isset($HTTP_GET_VARS['sort'])) || (!preg_match('/^[1-8][ad]$/', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
//      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      foreach ($column_list as $column_variable) {

//        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
        if ($column_variable == 'PRODUCT_LIST_NAME') {
    	  $HTTP_GET_VARS['sort'] = 'products_sort_order';
	      $listing_sql .= " order by p.products_sort_order asc, pd.products_name";
// EOF Product Sort
          break;
        }
      }
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);

      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MODEL':
          $listing_sql .= " order by p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $listing_sql .= " order by pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $listing_sql .= " order by m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $listing_sql .= " order by p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          $listing_sql .= " order by pd.products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $listing_sql .= " order by p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          $listing_sql .= " order by final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
          break;
// BOF Product Sort	
		case 'PRODUCT_SORT_ORDER':
          $listing_sql .= "order by p.products_sort_order " . ($sort_order == 'd' ? "desc" : '') . ", pd.products_name";
          break;
// EOF Product Sort		  
      }
    }
    /*** Begin Header Tags SEO ***/
    // 2.3.3 if (isset($HTTP_GET_VARS['manufacturers_id'])) {
	if (isset($HTTP_GET_VARS['manufacturers_id']) && !empty($HTTP_GET_VARS['manufacturers_id'])) {
      $image = tep_db_query("select manufacturers_image, manufacturers_name as catname from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
// HT 331      $db_query = tep_db_query("select manufacturers_htc_title_tag as htc_title, manufacturers_htc_description as htc_description from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$languages_id . "' and manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
     $db_query = tep_db_query("select IF(manufacturers_htc_title_tag_alt != '', manufacturers_htc_title_tag_alt, manufacturers_htc_title_tag) as htc_title, manufacturers_htc_description as htc_description from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$languages_id . "' and manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    } elseif ($current_category_id) {
      $image = tep_db_query("select c.categories_image, cd.categories_name as catname from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
      $image = tep_db_fetch_array($image);
// HT 331    $db_query = tep_db_query("select categories_htc_title_tag as htc_title, categories_htc_description as htc_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");
      $db_query = tep_db_query("select IF(categories_htc_title_tag_alt != '', categories_htc_title_tag_alt,categories_htc_title_tag) as htc_title, categories_htc_description as htc_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");
    }
    $htc = tep_db_fetch_array($db_query);
	
?>


<!-- <h1><?php echo $htc['htc_title']; ?></h1> -->

<div class="contentContainer">
<div class="row">
<?php
// htc_description 
	echo $oscTemplate->getContent('index_products');
//    require(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); 	
?>
</div>
</div>

<?php

//END Header Tags SEO Social Bookmarks -->  

  } else { // default page

  // Start Modular Front Page
?>
<div class="contentContainer">
<div class="row">
<?php 
//	    echo $oscTemplate->getBlocks('front_page'); 	
    echo $oscTemplate->getContent('index_frontpage'); 	
?>
</div>
</div>
<?php
  // End Modular Front Page
  }
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');  
  
// php lite    $data = ob_get_contents(); 
  // Save the page to a cache file, using the ID created earlier
// php lite     $cache_lite->save($data, $id);
// php lite     ob_get_clean();
// php lite    }		
// php lite     echo $data; 
?>