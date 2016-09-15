<?php
/* $Id: CountProductsAdmin.php v 1.1 2007/09/02 JanZ
   an object to store the number of products in a category and which category_id has which parent_id for the admin side of Hide Products for Customer Groups for SPPC
	 
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

class CountProductsAdmin {
	
	function CountProductsAdmin() {
    if (SHOW_COUNTS == 'true') {
/*      if (USE_PRODUCTS_COUNT_CACHE == 'true') {
        $this->prods_in_category = tep_cache_products_count();
      } else { */
        $category_query = tep_db_query("select categories_id from categories");
          while ($_categories = tep_db_fetch_array($category_query)) {
          $categories[] = $_categories['categories_id'];
          }
      
          $products_query = tep_db_query("select count(*) as number_in_category, categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id in (" . implode(",", $categories) . ") group by categories_id");
          while ($_prods_in_category = tep_db_fetch_array($products_query)) {
          $this->prods_in_category[$_prods_in_category['categories_id']] = $_prods_in_category['number_in_category'];
          }
//        }
      }
    $this->category_tree = $this->buildCategoryTree();
	}

  function CountProductsInCategory($category_id) {
    if (isset($this->prods_in_category[$category_id])) {
      return $this->prods_in_category[$category_id];
    } else {
      return 0;
    }
  }
  
  function hasChildCategories($category_id) {
    foreach ($this->category_tree as $categories_id => $parent_id) {
      if ($parent_id == $category_id) {
        $ChildCategories[] = $categories_id;
      }
    } // end foreach ($this->category_tree as $categories_id => $parent_id)
    if (isset($ChildCategories)) {
      return $ChildCategories;
    } else {
      return false;
    }
  }
  
    function buildCategoryTree() {
    $category_query = tep_db_query("select c.categories_id, c.parent_id from " . TABLE_CATEGORIES . " c order by c.parent_id");

    while ($categories = tep_db_fetch_array($category_query)) {
	    $category_tree_array[$categories['categories_id']] = $categories['parent_id'];  
    } 
    return $category_tree_array;
  } 
  
  function getParentCategory($category_id) {
    foreach ($this->category_tree as $categories_id => $parent_id) {
      if ($categories_id == $category_id) {
        return $parent_id;
      }
    } // end foreach ($this->category_tree as $categories_id => $parent_id)
      return false;
    }

  function countChildCategories($category_id) {
    $count = 0;
    foreach ($this->category_tree as $categories_id => $parent_id) {
      if ($parent_id == $category_id) {
        $count += 1;
      }
    } // end foreach ($this->category_tree as $categories_id => $parent_id)
      return $count;
  }
}
?>
