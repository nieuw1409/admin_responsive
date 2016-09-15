<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
	'icon' => BOX_HEADING_CATALOG_ICON,  
    'heading' => BOX_HEADING_CATALOG,
    'apps' => array(
      array(
        'code' => FILENAME_CATEGORIES,
        'title' => BOX_CATALOG_CATEGORIES_PRODUCTS,
        'link' => tep_href_link(FILENAME_CATEGORIES)
      ),
      array(
        'code' => FILENAME_PRODUCTS_ATTRIBUTES,
        'title' => BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES,
        'link' => tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES )
      ),
      array(
        'code' => FILENAME_MANUFACTURERS,
        'title' => BOX_CATALOG_MANUFACTURERS,
        'link' => tep_href_link(FILENAME_MANUFACTURERS)
      ),
      array(
        'code' => FILENAME_REVIEWS,
        'title' => BOX_CATALOG_REVIEWS,
        'link' => tep_href_link(FILENAME_REVIEWS)
      ),  
      array(
        'code' => FILENAME_SPECIALS,
        'title' => BOX_CATALOG_SPECIALS,
        'link' => tep_href_link(FILENAME_SPECIALS)
      ),
      array(
        'code' => FILENAME_SPECIALS_BY_CATEGORIES,
        'title' => BOX_CATALOG_SPECIALS_BY_CATEGORIES,
        'link' => tep_href_link(FILENAME_SPECIALS_BY_CATEGORIES)
      ),	  
      array(
        'code' => FILENAME_DISCOUNT_CODES,
        'title' => BOX_CATALOG_DISCOUNT_CODE,
        'link' => tep_href_link(FILENAME_DISCOUNT_CODES)
      ),	  	  
      array(
        'code' => FILENAME_CATEDISCOUNT,
        'title' =>  BOX_CATALOG_DISCOUNT_BY_CATEGORIES,
        'link' => tep_href_link(FILENAME_CATEDISCOUNT)
      ),		  
      array(
        'code' => FILENAME_CATEMANUDISCOUNT,
        'title' =>  BOX_CATALOG_DISCOUNT_BY_CATEGORIES_MANUFACTURER,
        'link' => tep_href_link(FILENAME_CATEMANUDISCOUNT)
      ),		  
      array(
        'code' => FILENAME_MANUDISCOUNT,
        'title' =>  BOX_CATALOG_DISCOUNT_BY_MANUFACTURER,
        'link' => tep_href_link(FILENAME_MANUDISCOUNT)
      ),	      array(
        'code' => FILENAME_PRODUCTS_EXPECTED,
        'title' => BOX_CATALOG_PRODUCTS_EXPECTED,
        'link' => tep_href_link(FILENAME_PRODUCTS_EXPECTED)
      ),
//	array(
//        'code' => FILENAME_QUICK_INVENTORY,
//        'title' => BOX_CATALOG_QUICK_INVENTORY,
//        'link' => tep_href_link(FILENAME_QUICK_INVENTORY)
//      ),	  
      array(
        'code' => FILENAME_XSELL_PRODUCTS,
        'title' => BOX_CATALOG_XSELL_PRODUCTS,
        'link' => tep_href_link(FILENAME_XSELL_PRODUCTS)
      ),	  	  
      array(
        'code' => FILENAME_EASY_POPULATE,
        'title' => BOX_CATALOG_EASYPOPULATE,
        'link' => tep_href_link(FILENAME_EASY_POPULATE)
      ),	  
     array(
        'code' => FILENAME_GOOGLE_TAXONOMY,
        'title' => BOX_CATALOG_GOOGLE_TAXONOMY,
        'link' => tep_href_link(FILENAME_GOOGLE_TAXONOMY)
      ),		  
      array(
        'code' => FILENAME_PRODUCTS_AVAILABILITY,
        'title' => BOX_CATALOG_AVAILABILITY,
        'link' => tep_href_link(FILENAME_PRODUCTS_AVAILABILITY)
      )	  	  
    )
  );
?>
