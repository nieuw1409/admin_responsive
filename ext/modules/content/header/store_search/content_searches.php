<?php
/*
  $Id$ version 1.1 for oscommerce 2.3.4BS

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  // get rid of the individual calls for files and replace it with the only one we need, application_top.php
  // from here all other files necessary are also included.
  chdir('../../../../../');

  require('includes/application_top.php');
  include(DIR_WS_LANGUAGES . $language . '/modules/content/header/cm_header_store_search.php') 

 
  //here we can replace certain phrases that people may search for that are wrong, i have left my examples below.
  //for example i have people add food or foods onto the end of search phrases, but food is rarely used in product names.
  //or for if people add spaces where there shouldnt be or remove spaces when there should be

  if ($language == 'english') {
    $query = str_replace(' food','',$query);
    $query = str_replace(' food','',$query);
    $query = str_replace('jameswellbeloved','james wellbeloved',$query);
    $query = str_replace('jameswell beloved','james wellbeloved',$query);
    $query = str_replace('ferrets','ferret',$query);
    $query = str_replace('sawdust','wood shavings',$query);
    $query = str_replace('saw dust','wood shavings',$query);
    $query = str_replace('naturesdiet','naturediet',$query);
    $query = str_replace('natures diet','naturediet',$query);
    $query = str_replace('drjohns','dr johns',$query);
  }

  //Explode This Query

  $query_exploded = array();
  $query_exploded = explode(' ', $query);
  $query_exploded = array_unique($query_exploded);

  //if a characters are only "b" or "B" do nothing!
  if (($key = array_search("b", $query_exploded)) !== false) { unset($query_exploded[$key]); }
  if (($key = array_search("B", $query_exploded)) !== false) { unset($query_exploded[$key]); }

  //for highlight rule
  arsort($query_exploded);
  $query_exploded_new = '';
  foreach ($query_exploded as $g) {
    // <b> is not search engine sensitive
    $query_exploded_new .= '<b>' . $g . '</b>' . "E_OF_L";
  }
  $query_exploded_new = substr($query_exploded_new, 0, -6);

  $query_exploded_highlight = explode("E_OF_L", $query_exploded_new);

  //Generate Like Statement for Each Word To Find Categories, Second Level, That Match

  foreach ($query_exploded as $g) {
    $like_statement .= " cd.categories_name LIKE '%" . tep_db_input($g) . "%' AND ";
  }

  $like_statement = substr($like_statement, 0, -4); //Remove That Last AND

  //Select categories, that are second level, and that match our query

  $sqlquery = tep_db_query("SELECT distinct(c.categories_id), cd.categories_name, c.parent_id FROM categories_description cd, categories c WHERE cd.categories_id = c.categories_id AND" . $like_statement . " and cd.language_id = '" . (int)$languages_id . "' limit 15");

  //For Each Category We Found

  $categories_found = '';

  if (tep_db_num_rows($sqlquery)) {

    while ($row = tep_db_fetch_array($sqlquery)) {
      $url_title = ucwords(strtolower($row['categories_name']));

      //highlight
      $url_title = str_ireplace($query_exploded, $query_exploded_highlight, $url_title);

      $array[] = array('icon'  => "sitemap",
                       'title' => $url_title,
                       'href'  => tep_href_link('index.php', 'cPath=' . $row['categories_id'], $request_type),
                       'price' =>null);
    }
  }

  $like_statement = '';

  //We Have All Suggested Categories

  //Find Suggested Products

  foreach ($query_exploded as $g) {
    //Prevent SQL Injection Attempts
    $g = str_replace(array("'", ";", "*", "(", ")"), '',$g);
    $like_statement .= " (pd.products_name LIKE '%" . tep_db_input($g) . "%' OR p.products_model LIKE '%" . tep_db_input($g) . "%') AND ";
  }

  //Remove the Last And

  $like_statement = substr($like_statement, 0, -4);

  $sqlquery = tep_db_query("SELECT distinct(p.products_id), pd.products_name, p.products_price, p.products_tax_class_id FROM products_description pd, products p WHERE" . $like_statement . " AND pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_status limit 6");


  $r = 0; //Set R
  if (tep_db_num_rows($sqlquery)) {
    while ($row = tep_db_fetch_array($sqlquery)) {
      $r++;
      $url_title = str_replace('’', '', $row['products_name']);

      //highlight
      $url_title = str_ireplace($query_exploded, $query_exploded_highlight, $url_title);

      if ($r > 5) {
        $array[] = array('icon'  => "plus-circle",
                         'title' => MODULE_CONTENT_HEADER_STORE_SEARCH_MORE_PRODUCT,
                         'href'  => tep_href_link('advanced_search_result.php', 'keywords=' . urlencode(str_replace(' ', '+', $query)) . '&search_in_description=' . (MODULE_CONTENT_HEADER_STORE_SEARCH_FUNCTIONS == 'Descriptions' ? 1 : 0), $request_type),
                         'price' =>null);
        break;
      } else {

        if ($new_price = tep_get_products_special_price($row['products_id'])) {
          $price = '<s>' . $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($row['products_tax_class_id'])) . '</span>';
        } else {
          $price = $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']));
        }

        $array[] = array('icon'  => "cart-plus",
                         'title' => $url_title,
                         'href'  => tep_href_link('product_info.php', 'products_id=' . $row['products_id'], $request_type),
                         'price' => $price);
      }
    }
  } else {

    $array[] = array('icon'  => "wrench",
                     'title' => MODULE_CONTENT_HEADER_STORE_SEARCH_NOT_FOUND,
                     'href'  => tep_href_link('advanced_search.php', 'keywords=' . urlencode(str_replace(' ', '+', $query)), $request_type),
                     'price' => null);
  }

  // start content searches in files

  if (tep_not_null(MODULE_CONTENT_HEADER_STORE_SEARCH_PAGES)) {
    $content_files = array();

    foreach (explode(';', MODULE_CONTENT_HEADER_STORE_SEARCH_PAGES) as $page) {
      $page = trim($page);

      if (!empty($page)) {
        $content_files[] = $page;
      }
    }

    foreach ($content_files as $file_name) {

      $file = DIR_WS_LANGUAGES . $language . '/' . $file_name;

      $lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

      if ($lines !== false) {
        $n = 0;
        foreach ($lines as $line) {
          $n++;
          // skip header
          if ( $n>8 ) { //empty rows shifted in @file!
            // Check if the line contains the string we're looking for, and add if it does
            foreach ($query_exploded as $q) {
              if (strpos(strtolower($line), strtolower($q)) !== false) {

                $array[] = array('icon'  => "file",
                                 'title' => sprintf( MODULE_CONTENT_HEADER_STORE_SEARCH_PAGE, substr(basename($file), 0, -4)),
                                 'href'  => tep_href_link($file_name, null, $request_type),
                                 'price' => null);
                break 2;
              }
            }
          }
        }
      }

    }
  }
  // build json
  echo json_encode($array);
?>