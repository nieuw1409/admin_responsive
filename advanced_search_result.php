<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);

  $error = false;

  if ( (isset($HTTP_GET_VARS['keywords']) && empty($HTTP_GET_VARS['keywords'])) &&
       (isset($HTTP_GET_VARS['dfrom']) && (empty($HTTP_GET_VARS['dfrom']) || ($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING))) &&
       (isset($HTTP_GET_VARS['dto']) && (empty($HTTP_GET_VARS['dto']) || ($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING))) &&
       (isset($HTTP_GET_VARS['pfrom']) && !is_numeric($HTTP_GET_VARS['pfrom'])) &&
       (isset($HTTP_GET_VARS['pto']) && !is_numeric($HTTP_GET_VARS['pto'])) ) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  } else {
    $dfrom = '';
    $dto = '';
    $pfrom = '';
    $pto = '';
    $keywords = '';

    if (isset($HTTP_GET_VARS['dfrom'])) {
      $dfrom = (($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dfrom']);
    }

    if (isset($HTTP_GET_VARS['dto'])) {
      $dto = (($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dto']);
    }

    if (isset($HTTP_GET_VARS['pfrom'])) {
      $pfrom = $HTTP_GET_VARS['pfrom'];
    }

    if (isset($HTTP_GET_VARS['pto'])) {
      $pto = $HTTP_GET_VARS['pto'];
    }

    if (isset($HTTP_GET_VARS['keywords'])) {
      $keywords = tep_db_prepare_input($HTTP_GET_VARS['keywords']);
    }

    $date_check_error = false;
    if (tep_not_null($dfrom)) {
      if (!tep_checkdate($dfrom, DOB_FORMAT_STRING, $dfrom_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_FROM_DATE);
      }
    }

    if (tep_not_null($dto)) {
      if (!tep_checkdate($dto, DOB_FORMAT_STRING, $dto_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_TO_DATE);
      }
    }

    if (($date_check_error == false) && tep_not_null($dfrom) && tep_not_null($dto)) {
      if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
        $error = true;

        $messageStack->add_session('search', ERROR_TO_DATE_LESS_THAN_FROM_DATE);
      }
    }

    $price_check_error = false;
    if (tep_not_null($pfrom)) {
      if (!settype($pfrom, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);
      }
    }

    if (tep_not_null($pto)) {
      if (!settype($pto, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);
      }
    }

    if (($price_check_error == false) && is_float($pfrom) && is_float($pto)) {
      if ($pfrom >= $pto) {
        $error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);
      }
    }

    if (tep_not_null($keywords)) {
      if (!tep_parse_search_string($keywords, $search_keywords)) {
        $error = true;

        $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
      }
    }
  }

  if (empty($dfrom) && empty($dto) && empty($pfrom) && empty($pto) && empty($keywords)) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  }

  if ($error == true) {
    tep_redirect(tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'NONSSL', true, false));
  }
  /*** Begin Header Tags SEO ***/
  if (HEADER_TAGS_STORE_KEYWORDS == 'true') {
      require(DIR_WS_MODULES . 'header_tags_keywords.php');
  }
  /*** End Header Tags SEO ***/
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(), 'NONSSL', true, false));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<div class="page-header">
  <h1><?php echo HEADING_TITLE_2; ?></h1>
</div>

<div class="contentContainer">

<?php
// create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($key, $value) = each($define_list)) {
    if ($value > 0) $column_list[] = $key;
  }
// BOF Separate Pricing Per Customer
// bof multi stores
//  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
  if (isset($_SESSION['sppc_customer_group_id']) ) {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  } else {
// $customer_group_id = '0';
    $customer_group_id = _SYS_STORE_CUSTOMER_GROUP ;
// eof multi stores	
  }
// EOF Separate Pricing Per Customer

  $select_column_list = '';

  for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
    switch ($column_list[$i]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model, ';
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
    }
  }
   // BOF Separate Pricing Per Customer
   $status_tmp_product_prices_table = false;
   $status_tmp_special_prices_table = false;
   $status_need_to_get_prices = false;
   // find out if sorting by price has been requested
//   if ( (isset($HTTP_GET_VARS['sort'])) && (ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) && (substr($HTTP_GET_VARS['sort'], 0, 1) <= sizeof($column_list)) ){
   if ( (!isset($_GET['sort'])) || (!preg_match('/^[1-8][ad]$/', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > sizeof($column_list)) ) {
 	   
    $_sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
//    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
//      $status_need_to_get_prices = true;
//      }
   }

   $status_need_to_get_prices = true;  // need to get the prices all the time even after request of different sort of products
   
   if ((tep_not_null($pfrom) || tep_not_null($pto) || $status_need_to_get_prices == true) && $customer_group_id != '0') { 
     $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES.$customer_group_id;
     // the table with product prices for a particular customer group is re-built only a number of times per hour
     // (setting in /includes/database_tables.php called MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE, in minutes)
     // to trigger the update the next function is called (new function that should have been
     // added to includes/functions/database.php)
     tep_db_check_age_products_group_prices_cg_table($customer_group_id);
     $status_tmp_product_prices_table = true;   
   } 
   if ((tep_not_null($pfrom) || tep_not_null($pto) || $status_need_to_get_prices == true) && $customer_group_id == '0') {
     // to be able to sort on retail prices we *need* to get the special prices instead of leaving them
     // NULL and do product_listing the job of getting the special price
     // first make sure that table exists and needs no updating
     tep_db_check_age_specials_retail_table();	 
     $status_tmp_special_prices_table = true;
   } // end elseif ((tep_not_null($pfrom) || (tep_not_null($pfrom)) && .... 
   
   if ($status_tmp_product_prices_table == true) {
     $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, tmp_pp.products_price, p.products_tax_class_id, if(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price, IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price ";
   } elseif ($status_tmp_special_prices_table == true) {
     $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, p.products_purchase,      p.products_price, p.products_tax_class_id, if(s.status, s.specials_new_products_price, NULL)           as specials_new_products_price, if(     s.status,      s.specials_new_products_price,      p.products_price) as final_price ";	
   } else {
     $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, p.products_purchase,      p.products_price, p.products_tax_class_id,                                                        NULL as specials_new_products_price,                                                                         NULL as final_price ";	
   }
   // next line original select query
   // $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";


  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
  }
  /*** Begin Header Tags SEO ***/
  if (HEADER_TAGS_SEARCH_KEYWORDS == 'true') {
    $select_str .= ", hts.keyword ";
  }
  /*** End Header Tags SEO ***/  
  if ($status_tmp_product_prices_table == true) {
    $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id) left join " . $product_prices_table . " as tmp_pp using(products_id)";
  } elseif ($status_tmp_special_prices_table == true) {
    $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id) left join " . TABLE_SPECIALS_RETAIL_PRICES . " s on p.products_id = s.products_id ";
  } else {
    $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id) ";
  }
  
  /*** Begin Header Tags SEO ***/
  if (HEADER_TAGS_SEARCH_KEYWORDS == 'true') {
     $from_str .= " left join " . TABLE_HEADERTAGS_SEARCH . " hts on p.products_id = hts.product_id ";
  }
  /*** END Header Tags SEO ***/  
//  qpbpp
//  $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, p.products_price,                        p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";
//  $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_qty_blocks, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";
// EOF qpbpp
//
//  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
//    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
//  }
//
//  $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id";
// EOF Separate Pricing Per Customer
  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    if (!tep_session_is_registered('customer_country_id')) {
      $customer_country_id = STORE_COUNTRY;
      $customer_zone_id = STORE_ZONE;
    }
    $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";
  }

  $from_str .= ", " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";

  $where_str = " where p.products_status = '1'               and c.categories_status = '1' 
                   and p.products_id = pd.products_id        and pd.language_id = '" . (int)$languages_id . "' 
				   and p.products_id = p2c.products_id       and p2c.categories_id = c.categories_id ";

  if (isset($HTTP_GET_VARS['categories_id']) && tep_not_null($HTTP_GET_VARS['categories_id'])) {
    if (isset($HTTP_GET_VARS['inc_subcat']) && ($HTTP_GET_VARS['inc_subcat'] == '1')) {
      $subcategories_array = array();
      tep_get_subcategories($subcategories_array, $HTTP_GET_VARS['categories_id']);

      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";

      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
      }

      $where_str .= ")";
    } else {
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";
    }
  }

  if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
    $where_str .= " and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'  and find_in_set('" . SYS_STORES_ID . "',      m.manufacturers_to_stores) != 0 ";
  }

  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
    $where_str .= " and (";
    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
      switch ($search_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str .= " " . $search_keywords[$i] . " ";
          break;
        default:
          $keyword = tep_db_prepare_input($search_keywords[$i]);
          /*** Begin Header Tags SEO ***/
//          $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%'";
         /*** Begin Header Tags SEO ***/
          if (HEADER_TAGS_SEARCH_KEYWORDS == 'true') {
             $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%' or hts.keyword like '%" . tep_db_input($keyword) . "%'";
          } else {
             $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%'";
          }           
          /*** End Header Tags SEO ***/		  
          if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1')) $where_str .= " or pd.products_description like '%" . tep_db_input($keyword) . "%'";
            $where_str .= ')';
          break;
      }
    }
    $where_str .= " )";
  }

  if (tep_not_null($dfrom)) {
    $where_str .= " and p.products_date_added >= '" . tep_date_raw($dfrom) . "'";
  }

  if (tep_not_null($dto)) {
    $where_str .= " and p.products_date_added <= '" . tep_date_raw($dto) . "'";
  }

  if (tep_not_null($pfrom)) {
    if ($currencies->is_set($currency)) {
      $rate = $currencies->get_value($currency);

      $pfrom = $pfrom / $rate;
    }
  }

  if (tep_not_null($pto)) {
    if (isset($rate)) {
      $pto = $pto / $rate;
    }
  }
// BOF Separate Pricing Per Customer
//  if (DISPLAY_PRICE_WITH_TAX == 'true') {
//    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
//    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
//  } else {
//    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";
//    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";
//  }

   if ($status_tmp_product_prices_table == true) {
  if (DISPLAY_PRICE_WITH_TAX == 'true') {
    if ($pfrom > 0) $where_str .= " and (IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
  } else {
    if ($pfrom > 0) $where_str .= " and (IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) <= " . (double)$pto . ")";
  }
   } else { // $status_tmp_product_prices_table is not true: uses p.products_price instead of cg_products_price
	   // because in the where clause for the case $status_tmp_special_prices is true, the table
	   // specials_retail_prices is abbreviated with "s" also we can use the same code for "true" and for "false"
	     if (DISPLAY_PRICE_WITH_TAX == 'true') {
    if ($pfrom > 0) $where_str .= " and (IF(s.status AND s.customers_group_id = '" . $customer_group_id . "', s.specials_new_products_price, p.products_purchase, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status AND s.customers_group_id = '" . $customer_group_id . "', s.specials_new_products_price, p.products_purchase, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
  } else {
    if ($pfrom > 0) $where_str .= " and (IF(s.status AND s.customers_group_id = '" . $customer_group_id . "', s.specials_new_products_price, p.products_purchase, p.products_price) >= " . (double)$pfrom . ")";
    if ($pto > 0) $where_str .= " and (IF(s.status AND s.customers_group_id = '" . $customer_group_id . "', s.specials_new_products_price, p.products_purchase, p.products_price) <= " . (double)$pto . ")";
   }
   }
// EOF Separate Pricing Per Customer
// BOF Separate Pricing Per Customer BOF Hide products and categories from groups
//  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
//    $where_str .= " group by p.products_id, tr.tax_priority";
//  }

 
 $where_str .= " and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 ";
 $where_str .= " and find_in_set('".$customer_group_id."', categories_hide_from_groups) = 0 ";
 $where_str .= " and find_in_set('" . SYS_STORES_ID . "',      c.categories_to_stores) != 0 " ; // multi stores
 $where_str .= " and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 " ; // multi stores 
 
 /*** Begin Header Tags SEO ***/
 if (HEADER_TAGS_SEARCH_KEYWORDS == 'true') {
     $from_str .= " and '" . SYS_STORES_ID . "' = hts.stores_id "; // multi stores
 }
 /*** END Header Tags SEO ***/   

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $where_str .= " group by p.products_id, tr.tax_priority";
  }  
 // EOF hide products and categories from group
  $quotes = (defined('QUOTES_CATEGORY_NAME')) ? " and p.customers_email_address = '' and p.quotes_email_address = '' " : ''; // headertags 3.3.0
  $where_str .=  $quotes;  // headertags 3.3.0

  if ( (!isset($HTTP_GET_VARS['sort'])) || (!preg_match('/^[1-8][ad]$/', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
        $HTTP_GET_VARS['sort'] = $i+1 . 'a';
        $order_str = " order by pd.products_name";
        break;
      }
    }
  } else {
    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    $sort_order = substr($HTTP_GET_VARS['sort'], 1);

    switch ($column_list[$sort_col-1]) {
      case 'PRODUCT_LIST_MODEL':
        $order_str = " order by p.products_sort_order, p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_NAME':
        $order_str = " order by p.products_sort_order,pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $order_str = " order by m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $order_str = " order by p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_IMAGE':
        $order_str = " order by p.products_sort_order,pd.products_name";
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $order_str = " order by p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;
      case 'PRODUCT_LIST_PRICE':
        $order_str = " order by final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
        break;
    }
  }

  $listing_sql = $select_str . $from_str . $where_str . $order_str;
  
//  $list_thumb_view_or_default = PRODUCT_THUMBNAIL_VIEW ;

  require(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);
// stats_keywords.php_bof
  $keyword_lookup = tep_db_query("select search_text, search_date from " . TABLE_SEARCH_QUERIES . " where search_text = '" . $HTTP_GET_VARS['keywords'] . "' and stores_id = '" . SYS_STORES_ID . "'"); // multi stores
  //$keyword = tep_db_fetch_array($keyword_lookup);
  
  if (tep_db_num_rows($keyword_lookup) > 0) {
  
  tep_db_query("update " . TABLE_SEARCH_QUERIES . " set search_count = search_count+1, search_date = now() where search_text = '" . $HTTP_GET_VARS['keywords'] . "' and stores_id = '" . SYS_STORES_ID . "'"); // multi stores
  
  } else {
  
  tep_db_query("insert into " . TABLE_SEARCH_QUERIES . " (search_text, search_date, stores_id) values ('" . $HTTP_GET_VARS['keywords'] . "', now(), '" . SYS_STORES_ID . "')"); // multi stores
  
  }
  
// stats_keywords.php_eof    
?>

  <br />

  <div class="buttonSet">
    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'chevron-left', tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(array('sort', 'page')), 'NONSSL', true, false)); ?>
  </div>
</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
