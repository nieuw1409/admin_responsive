<?php
/*
  $Id: header_tags_seo.php,v 3.0 2008/01/10 by Jack_mcs - http://www.oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/ 

require_once(DIR_WS_FUNCTIONS . 'header_tags.php'); 
require_once(DIR_WS_FUNCTIONS . 'clean_html_comments.php'); // Clean out HTML comments from ALT tags etc.

$cache_output = '';
$canonical_url = '';
$header_tags_array = array();
$sortOrder = array();
$tmpTags = array();

$defaultTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS_DEFAULT . " where language_id = '" . (int)$languages_id . "' and stores_id = '" . (int)SYS_STORES_ID . "'"); // multi stores
$defaultTags = tep_db_fetch_array($defaultTags_query);
$tmpTags['def_title']     =  (tep_not_null($defaultTags['default_title'])) ? $defaultTags['default_title'] : '';
$tmpTags['def_desc']      =  (tep_not_null($defaultTags['default_description'])) ? $defaultTags['default_description'] : '';
$tmpTags['def_keywords']  =  (tep_not_null($defaultTags['default_keywords'])) ? $defaultTags['default_keywords'] : '';
$tmpTags['def_logo_text'] =  (tep_not_null($defaultTags['default_logo_text'])) ? $defaultTags['default_logo_text'] : '';
$tmpTags['home_page_text'] =  (tep_not_null($defaultTags['home_page_text'])) ? $defaultTags['home_page_text'] : '';

// Define specific settings per page: 
// Define specific settings per page:
  $pos = strripos($_SERVER['PHP_SELF'], "/");


$parts = explode('/', $_SERVER["SCRIPT_NAME"]);
$file = $parts[count($parts) - 1];


  $page = $parts[count($parts) - 1];
  //($pos !== FALSE) ? basename(substr($_SERVER['PHP_SELF'], 0, $pos)) : $page;
switch (true) {
  // INDEX.PHP
  case ($page === FILENAME_DEFAULT):
    $id = ($current_category_id ? 'c_' . (int)$current_category_id : (($_GET['manufacturers_id'] ? 'm_' . (int)$_GET['manufacturers_id'] : '')));

    if (! ReadCacheHeaderTags($header_tags_array, $page,  $id)) { 
       $pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . FILENAME_DEFAULT . "' and language_id = '" . (int)$languages_id . "' and stores_id = '" . (int)SYS_STORES_ID . "'"); // multi stores
       $pageTags = tep_db_fetch_array($pageTags_query);

         $catStr = "select categories_htc_title_tag as htc_title_tag, categories_htc_desc_tag as htc_desc_tag, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "' limit 1";
         $manStr = '';
         
         if ($category_depth == 'top') {  //home page or manufacturer page
             if (isset($_GET['manufacturers_id'])) { //a manufacturer page
                 $manStr = "select mi.manufacturers_htc_title_tag as htc_title_tag, mi.manufacturers_htc_desc_tag as htc_desc_tag, mi.manufacturers_htc_keywords_tag as htc_keywords_tag from " . TABLE_MANUFACTURERS . " m LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and mi.languages_id = '" . (int)$languages_id . "' limit 1";
             } else {                                //the home page
                 $header_tags_array['home_page_text'] = (tep_not_null($tmpTags['home_page_text']) ? $tmpTags['home_page_text'] : '');             
             }
         }
         if ($pageTags['append_root'] || $category_depth == 'top' && ! isset($_GET['manufacturers_id']) ) {
             $sortOrder['title'][$pageTags['sortorder_root']] = $pageTags['page_title']; 
             $sortOrder['description'][$pageTags['sortorder_root']] = $pageTags['page_description']; 
             $sortOrder['keywords'][$pageTags['sortorder_root']] = $pageTags['page_keywords']; 
             $sortOrder['logo'][$pageTags['sortorder_root']] = $pageTags['page_logo'];
             $sortOrder['logo_1'][$pageTags['sortorder_root_1']] = $pageTags['page_logo_1'];
             $sortOrder['logo_2'][$pageTags['sortorder_root_2']] = $pageTags['page_logo_2'];
             $sortOrder['logo_3'][$pageTags['sortorder_root_3']] = $pageTags['page_logo_3'];
             $sortOrder['logo_4'][$pageTags['sortorder_root_4']] = $pageTags['page_logo_4'];
         }

       $sortOrder = GetCategoryAndManufacturer($sortOrder, $pageTags, $defaultTags, $catStr, $manStr);

       if ($pageTags['append_default_title'] && tep_not_null($tmpTags['def_title'])) $sortOrder['title'][$pageTags['sortorder_title']] = $tmpTags['def_title'];
       if ($pageTags['append_default_description'] && tep_not_null($tmpTags['def_desc'])) $sortOrder['description'][$pageTags['sortorder_description']] = $tmpTags['def_desc'];
       if ($pageTags['append_default_keywords'] && tep_not_null($tmpTags['def_keywords'])) $sortOrder['keywords'][$pageTags['sortorder_keywords']] = $tmpTags['def_keywords'];
       if ($pageTags['append_default_logo'] && tep_not_null($tmpTags['def_logo_text']))  $sortOrder['logo'][$pageTags['sortorder_logo']] = $tmpTags['def_logo_text'];

       FillHeaderTagsArray($header_tags_array, $sortOrder);  

         // Canonical URL add-on
         if (isset($cPath) && tep_not_null($cPath)) {
             $path = $cPath;
             if (HEADER_TAGS_CANONICAL_PATH == 'last') {
                 if (strpos($cPath, '_') !== FALSE) {
                     $path = end(explode('_',$cPath));
                 }
             }    
             $canonical_url = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $path, 'NONSSL', false);
         } elseif (isset($_GET['manufacturers_id']) && tep_not_null($_GET['manufacturers_id'])) {
              $canonical_url = tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . (int)$_GET['manufacturers_id'], 'NONSSL', false);
         }

       WriteCacheHeaderTags($header_tags_array, $page,  $id);
    }   
    break;

  // PRODUCT_INFO.PHP
  // PRODUCT_REVIEWS.PHP
  // PRODUCT_REVIEWS_INFO.PHP
  // PRODUCT_REVIEWS_WRITE.PHP
  case ($page === FILENAME_PRODUCT_INFO):
  case ($page === FILENAME_PRODUCT_REVIEWS):
  case ($page === FILENAME_PRODUCT_REVIEWS_INFO):
  case ($page === FILENAME_PRODUCT_REVIEWS_WRITE):

    switch (true)
    {
     case ($page === FILENAME_PRODUCT_INFO):          $filename = FILENAME_PRODUCT_INFO;          break;
     case ($page === FILENAME_PRODUCT_REVIEWS):       $filename = FILENAME_PRODUCT_REVIEWS;       break;
     case ($page === FILENAME_PRODUCT_REVIEWS_INFO):  $filename = FILENAME_PRODUCT_REVIEWS_INFO;  break;
     case ($page === FILENAME_PRODUCT_REVIEWS_WRITE): $filename = FILENAME_PRODUCT_REVIEWS_WRITE; break;
     default: $filename = FILENAME_PRODUCT_INFO;
    } 

    $id = ($_GET['products_id'] ? 'p_' . (int)$_GET['products_id'] : '');

    if (! ReadCacheHeaderTags($header_tags_array, $page, $id))
    { 
       $pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . $filename . "' and language_id = '" . (int)$languages_id . "' and stores_id = '" . (int)SYS_STORES_ID . "'"); // multi stores
       $pageTags = tep_db_fetch_array($pageTags_query);

       $the_product_info_query = tep_db_query("select p.products_id, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.manufacturers_id, p.products_model from " . TABLE_PRODUCTS . " p inner join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_id = '" . (int)$_GET['products_id'] . "' and pd.language_id ='" .  (int)$languages_id . "' limit 1");
       $the_product_info = tep_db_fetch_array($the_product_info_query);
       $header_tags_array['product'] = $the_product_info['products_head_title_tag'];  //save for use on the logo
       $tmpTags['prod_title'] = (tep_not_null($the_product_info['products_head_title_tag'])) ? strip_tags($the_product_info['products_head_title_tag']) : '';
       $header_tags_array['title_alt'] = (tep_not_null($the_product_info['products_head_title_tag_alt'])) ? strip_tags($the_product_info['products_head_title_tag_alt']) : (HEADER_TAGS_USE_PAGE_NAME == 'false' ? strip_tags($the_product_info['products_head_title_tag']) : strip_tags($the_product_info['products_name']));
       $tmpTags['prod_desc'] = (tep_not_null($the_product_info['products_head_desc_tag'])) ? strip_tags($the_product_info['products_head_desc_tag']) : '';
       $tmpTags['prod_keywords'] = (tep_not_null($the_product_info['products_head_keywords_tag'])) ? strip_tags($the_product_info['products_head_keywords_tag']) : '';
       $tmpTags['prod_model'] = (tep_not_null($the_product_info['products_model'])) ? $the_product_info['products_model'] : '';

       $catStr = "select c.categories_htc_title_tag as htc_title_tag, c.categories_htc_desc_tag as htc_desc_tag, c.categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where c.categories_id = p2c.categories_id and p2c.products_id = '" . (int)$the_product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'";
       $manStr = "select mi.manufacturers_htc_title_tag as htc_title_tag, mi.manufacturers_htc_desc_tag as htc_desc_tag, mi.manufacturers_htc_keywords_tag as htc_keywords_tag from " . TABLE_MANUFACTURERS . " m LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id  where m.manufacturers_id = '" . (int)$the_product_info['manufacturers_id'] . "' and mi.languages_id = '" . (int)$languages_id . "' LIMIT 1";

       if ($pageTags['append_root'])
       {
         $sortOrder['title'][$pageTags['sortorder_root']] = $pageTags['page_title'];
         $sortOrder['description'][$pageTags['sortorder_root']] = $pageTags['page_description']; 
         $sortOrder['keywords'][$pageTags['sortorder_root']] = $pageTags['page_keywords'];
         $sortOrder['logo'][$pageTags['sortorder_root']] = $pageTags['page_logo']; 
         $sortOrder['logo_1'][$pageTags['sortorder_root_1']] = $pageTags['page_logo_1'];
         $sortOrder['logo_2'][$pageTags['sortorder_root_2']] = $pageTags['page_logo_2'];
         $sortOrder['logo_3'][$pageTags['sortorder_root_3']] = $pageTags['page_logo_3'];
         $sortOrder['logo_4'][$pageTags['sortorder_root_4']] = $pageTags['page_logo_4'];      
       }

       if ($pageTags['append_product'])
       {    
         $sortOrder['title'][$pageTags['sortorder_product']] = $tmpTags['prod_title'];  //places the product title at the end of the list
         $sortOrder['description'][$pageTags['sortorder_product']] = $tmpTags['prod_desc'];
         $sortOrder['keywords'][$pageTags['sortorder_product']] = $tmpTags['prod_keywords']; 
         $sortOrder['logo'][$pageTags['sortorder_product']] = $tmpTags['prod_title'];
       }

       if ($pageTags['append_model'])
       {    
         $sortOrder['title'][$pageTags['sortorder_model']] = $tmpTags['prod_model'];  //places the product title at the end of the list
         $sortOrder['description'][$pageTags['sortorder_model']] = $tmpTags['prod_model'];
         $sortOrder['keywords'][$pageTags['sortorder_model']] = $tmpTags['prod_model']; 
         $sortOrder['logo'][$pageTags['sortorder_model']] = $tmpTags['prod_model'];
       }

       $sortOrder = GetCategoryAndManufacturer($sortOrder, $pageTags, $defaultTags, $catStr, $manStr, true);

       if ($pageTags['append_default_title'] && tep_not_null($tmpTags['def_title'])) $sortOrder['title'][$pageTags['sortorder_title']] = $tmpTags['def_title'];
       if ($pageTags['append_default_description'] && tep_not_null($tmpTags['def_desc'])) $sortOrder['description'][$pageTags['sortorder_description']] = $tmpTags['def_desc'];
       if ($pageTags['append_default_keywords'] && tep_not_null($tmpTags['def_keywords'])) $sortOrder['keywords'][$pageTags['sortorder_keywords']] = $tmpTags['def_keywords'];
       if ($pageTags['append_default_logo'] && tep_not_null($tmpTags['def_logo_text']))  $sortOrder['logo'][$pageTags['sortorder_logo']] = $tmpTags['def_logo_text'];

       FillHeaderTagsArray($header_tags_array, $sortOrder);  

       // Canonical URL add-on
       if ($_GET['products_id'] != '') {
          $canonical_url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$_GET['products_id'], 'NONSSL', false);
//          $args = tep_get_all_get_params(array('action','currency', tep_session_name(),'cPath','manufacturers_id','sort','page'));
//          $canonical_url = StripSID(tep_href_link($page, $args, 'NONSSL', false) );

       }    
       WriteCacheHeaderTags($header_tags_array, $page, $id);
    }

    break;


// products_new.php
  case ($page === FILENAME_PRODUCTS_NEW):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['SCRIPT_FILENAME']), '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_PRODUCTS_NEW);
      $canonical_url = tep_href_link(FILENAME_PRODUCTS_NEW, tep_get_all_get_params(array()), 'NONSSL', false);
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['SCRIPT_FILENAME']), '');
    }
  break;
  
  // SPECIALS.PHP
  case ($page === FILENAME_SPECIALS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, ''))
    {
       $pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . FILENAME_SPECIALS . "' and language_id = '" . (int)$languages_id . "' and stores_id = '" . (int)SYS_STORES_ID . "'"); // multi stores
       $pageTags = tep_db_fetch_array($pageTags_query);  

       // Build a list of ALL specials product names to put in keywords
       $new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC ");
       $row = 0;
       $the_specials='';
       while ($new_values = tep_db_fetch_array($new)) {
         $the_specials .= clean_html_comments($new_values['products_name']) . ', ';
       }

       if (strlen($the_specials) > 30000)                  //arbitrary number - may vary with server setting
        $the_specials = substr($the_specials, 0, 30000);   //adjust as needed

       if ($pageTags['append_root'])
       {
         $sortOrder['title'][$pageTags['sortorder_root']] = $pageTags['page_title']; 
         $sortOrder['description'][$pageTags['sortorder_root']] = $pageTags['page_description']; 
         $sortOrder['keywords'][$pageTags['sortorder_root']] = $pageTags['page_keywords']; 
         $sortOrder['logo'][$pageTags['sortorder_root']] = $pageTags['page_logo'];
         $sortOrder['logo_1'][$pageTags['sortorder_root']] = $pageTags['page_logo_1'];
         $sortOrder['logo_2'][$pageTags['sortorder_root']] = $pageTags['page_logo_2'];
         $sortOrder['logo_3'][$pageTags['sortorder_root']] = $pageTags['page_logo_3'];
         $sortOrder['logo_4'][$pageTags['sortorder_root']] = $pageTags['page_logo_4'];      
       }

       $sortOrder['keywords'][10] = $the_specials;; 

       if ($pageTags['append_default_title'] && tep_not_null($tmpTags['def_title'])) $sortOrder['title'][$pageTags['sortorder_title']] = $tmpTags['def_title'];
       if ($pageTags['append_default_description'] && tep_not_null($tmpTags['def_desc'])) $sortOrder['description'][$pageTags['sortorder_description']] = $tmpTags['def_desc'];
       if ($pageTags['append_default_keywords'] && tep_not_null($tmpTags['def_keywords'])) $sortOrder['keywords'][$pageTags['sortorder_keywords']] = $tmpTags['def_keywords'];
       if ($pageTags['append_default_logo'] && tep_not_null($tmpTags['def_logo_text']))  $sortOrder['logo'][$pageTags['sortorder_logo']] = $tmpTags['def_logo_text'];

       FillHeaderTagsArray($header_tags_array, $sortOrder);  
      WriteCacheHeaderTags($header_tags_array, $page,  '');
    }
  break;


// advanced_search.php
  case ($page === FILENAME_ADVANCED_SEARCH):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_ADVANCED_SEARCH);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// advanced_search_result.php
  case ($page === FILENAME_ADVANCED_SEARCH_RESULT):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_ADVANCED_SEARCH_RESULT);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// autocomplete.php
  case ($page === FILENAME_AUTOCOMPLETE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_AUTOCOMPLETE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// compare.php
  case ($page === FILENAME_COMPARE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_COMPARE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// conditions.php
  case ($page === FILENAME_CONDITIONS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_CONDITIONS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// contact_us.php
  case ($page === FILENAME_CONTACT_US):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_CONTACT_US);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// cookie_usage.php
  case ($page === FILENAME_COOKIE_USAGE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_COOKIE_USAGE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// discount_code.php
  case ($page === FILENAME_DISCOUNT_CODE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_DISCOUNT_CODE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// download.php
  case ($page === FILENAME_DOWNLOAD):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_DOWNLOAD);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// down_for_maintenance.php
  case ($page === FILENAME_DOWN_FOR_MAINTENANCE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_DOWN_FOR_MAINTENANCE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// faq.php
  case ($page === FILENAME_FAQ):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_FAQ);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// information.php
  case ($page === FILENAME_INFORMATION):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_INFORMATION);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// info_shopping_cart.php
  case ($page === FILENAME_INFO_SHOPPING_CART):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_INFO_SHOPPING_CART);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// links.php
  case ($page === FILENAME_LINKS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_LINKS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// locate-us.php
  case ($page === FILENAME_EASYMAP):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_EASYMAP);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_about.php
  case ($page === FILENAME_MOBILE_ABOUT):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_ABOUT);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_advanced_search_result.php
  case ($page === FILENAME_MOBILE_ADVANCED_SEARCH_RESULT):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_ADVANCED_SEARCH_RESULT);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_catalogue.php
  case ($page === FILENAME_MOBILE_CATALOGUE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_CATALOGUE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_conditions.php
  case ($page === FILENAME_MOBILE_CONDITIONS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_CONDITIONS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_contact_us.php
  case ($page === FILENAME_MOBILE_CONTACT_US):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_CONTACT_US);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_currencies.php
  case ($page === FILENAME_MOBILE_CURRENCIES):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_CURRENCIES);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_index.php
  case ($page === FILENAME_MOBILE_INDEX):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_INDEX);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_languages.php
  case ($page === FILENAME_MOBILE_LANGUAGES):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_LANGUAGES);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_privacy.php
  case ($page === FILENAME_MOBILE_PRIVACY):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_PRIVACY);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_products.php
  case ($page === FILENAME_MOBILE_PRODUCTS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_PRODUCTS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_product_info.php
  case ($page === FILENAME_MOBILE_PRODUCT_INFO):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_PRODUCT_INFO);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_product_reviews.php
  case ($page === FILENAME_MOBILE_PRODUCT_REVIEWS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_PRODUCT_REVIEWS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_product_reviews_write.php
  case ($page === FILENAME_MOBILE_PRODUCT_REVIEWS_WRITE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_PRODUCT_REVIEWS_WRITE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_product_thumb.php
  case ($page === FILENAME_MOBILE_PRODUCT_THUMB):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_PRODUCT_THUMB);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_search.php
  case ($page === FILENAME_MOBILE_SEARCH):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_SEARCH);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_shipping.php
  case ($page === FILENAME_MOBILE_SHIPPING):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_SHIPPING);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// mobile_shopping_cart.php
  case ($page === FILENAME_MOBILE_SHOPPING_CART):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_MOBILE_SHOPPING_CART);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// opensearch.php
  case ($page === FILENAME_OPENSEARCH):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_OPENSEARCH);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// password_reset.php
  case ($page === FILENAME_PASSWORD_RESET):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_PASSWORD_RESET);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// pdfinvoice.php
  case ($page === FILENAME_CUSTOMER_PDF):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_CUSTOMER_PDF);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// popup_image.php
  case ($page === FILENAME_POPUP_IMAGE):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_POPUP_IMAGE);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// privacy.php
  case ($page === FILENAME_PRIVACY):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_PRIVACY);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// products_new.php
  case ($page === FILENAME_PRODUCTS_NEW):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_PRODUCTS_NEW);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// redirect.php
  case ($page === FILENAME_REDIRECT):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_REDIRECT);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// reviews.php
  case ($page === FILENAME_REVIEWS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_REVIEWS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// shipping.php
  case ($page === FILENAME_SHIPPING):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_SHIPPING);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// shopping_cart.php
  case ($page === FILENAME_SHOPPING_CART):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_SHOPPING_CART);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// tag_products.php
  case ($page === FILENAME_TAG_PRODUCTS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_TAG_PRODUCTS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// tell_a_friend.php
  case ($page === FILENAME_TELL_A_FRIEND):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_TELL_A_FRIEND);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// test_divs.php
  case ($page === FILENAME_TEST_DIVS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_TEST_DIVS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// visitors_georss.php
  case ($page === FILENAME_VISITORS_GEORSS):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_VISITORS_GEORSS);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// wishlist.php
  case ($page === FILENAME_WISHLIST):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_WISHLIST);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// wishlist_help.php
  case ($page === FILENAME_WISHLIST_HELP):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_WISHLIST_HELP);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// wishlist_public.php
  case ($page === FILENAME_WISHLIST_PUBLIC):
    if (! ReadCacheHeaderTags($header_tags_array, $page, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_WISHLIST_PUBLIC);
      WriteCacheHeaderTags($header_tags_array, $page, '');
    }
  break;

// autocomplete_eric.php
  case (basename($_SERVER['PHP_SELF']) === FILENAME_AUTOCOMPLETE_ERIC):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_AUTOCOMPLETE_ERIC);
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), '');
    }
  break;

// ALL OTHER PAGES NOT DEFINED ABOVE

// article_info.php
  case (basename($_SERVER['SCRIPT_FILENAME']) === FILENAME_ARTICLE_INFO):
    $page = 'article_info.php?articles=';
    $parts = explode("?",$page);
    $getStr = substr($parts[1], 0, -1);
    $getID = isset($_GET[$getStr]) ? $_GET[$getStr] : '';
    $parts = explode("=", $parts[1]);
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['SCRIPT_FILENAME']), $getID)) {
      if (isset($parts[0])) {
       $found = false;
       $name = FILENAME_ARTICLE_INFO . "?" . $parts[0] . "=";
       $pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . tep_db_input($name) . "%' and language_id = '" . (int)$languages_id . "'\ and stores_id = '" . (int)$stores_id . "'");
        if (tep_db_num_rows($pageTags_query) > 0) {
          while($pageTags = tep_db_fetch_array($pageTags_query)) {
            if ($name . $_GET[$parts[0]] === $pageTags['page_name']) {
               $header_tags_array = tep_header_tag_page($pageTags['page_name']);
               WriteCacheHeaderTags($header_tags_array, basename($_SERVER['SCRIPT_FILENAME']), $getID);
               $found = true;
               break; 
        } } }
        if (! $found) {
           $found = true;
           $header_tags_array = tep_header_tag_page(FILENAME_ARTICLE_INFO);
           WriteCacheHeaderTags($header_tags_array, basename($_SERVER['SCRIPT_FILENAME']), $getID);
       } } else { 
        $header_tags_array = tep_header_tag_page(FILENAME_ARTICLE_INFO);
        WriteCacheHeaderTags($header_tags_array, basename($_SERVER['SCRIPT_FILENAME']), $getID);
      }  
    $header_tags_array['keywords'] = tep_db_prepare_input($defaultTags['default_keywords']);
    break;
  }    
}
echo ' <meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET  . '" />'.PHP_EOL ;
echo ' <title  itemprop="name">' . $header_tags_array['title'] . '</title>' . PHP_EOL ; // m
echo ' <meta name="description" content="' . $header_tags_array['desc'] . '" >' . PHP_EOL ;
echo ' <meta name="keywords" content="' . $header_tags_array['keywords'] . '" >' . PHP_EOL ;


$lang_query = tep_db_query( "select code from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$languages_id . "'");
$lang = tep_db_fetch_array($lang_query);

if ($defaultTags['meta_language'])  echo ' <meta http-equiv="Content-Language" content="' . $lang['code'] . '" >'.PHP_EOL ;
if ($defaultTags['meta_google'])    echo ' <meta name="googlebot" content="all" />' . PHP_EOL ;
if ($defaultTags['meta_noodp'])     echo ' <meta name="robots" content="noodp" />' . PHP_EOL ;
if ($defaultTags['meta_noydir'])    echo ' <meta name="slurp" content="noydir" />' . PHP_EOL ;
if ($defaultTags['meta_revisit'])   echo ' <meta name="revisit-after" content="1 days" />' . PHP_EOL ;
if ($defaultTags['meta_robots'])    echo ' <meta name="robots" content="index, follow" />' . PHP_EOL ;
if ($defaultTags['meta_unspam'])    echo ' <meta name="no-email-collection" value="' . HTTP_SERVER . '" />' . PHP_EOL ;
if ($defaultTags['meta_replyto'])   echo ' <meta name="reply-to" content="' . STORE_OWNER_EMAIL_ADDRESS . '" />' . PHP_EOL ;
if ($defaultTags['meta_canonical']) echo (tep_not_null($canonical_url) ? ' <link rel="canonical" href="'.$canonical_url.'" itemprop="url" />' . PHP_EOL  : ' <link rel="canonical" href="'.GetCanonicalURL().'" itemprop="url"  >' . PHP_EOL );

if ($defaultTags['meta_og'])        include(DIR_WS_MODULES . 'header_tags_opengraph.php');
echo '<!-- EOF: Header Tags SEO Generated Meta Tags by oscommerce-solution.com -->' . PHP_EOL ;
?>