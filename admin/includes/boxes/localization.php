<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
    'icon' => BOX_HEADING_LOCALIZATION_ICON,  
    'heading' => BOX_HEADING_LOCALIZATION,
    'apps' => array(
      array(
        'code' => FILENAME_CURRENCIES,
        'title' => BOX_LOCALIZATION_CURRENCIES,
        'link' => tep_href_link(FILENAME_CURRENCIES)
      ),
      array(
        'code' => FILENAME_LANGUAGES,
        'title' => BOX_LOCALIZATION_LANGUAGES,
        'link' => tep_href_link(FILENAME_LANGUAGES)
      ),
      array(
        'code' => FILENAME_ORDERS_STATUS,
        'title' => BOX_LOCALIZATION_ORDERS_STATUS,
        'link' => tep_href_link(FILENAME_ORDERS_STATUS)
      ),
      array(
        'code' => FILENAME_COUNTRIES,
        'title' => BOX_TAXES_COUNTRIES,
        'link' => tep_href_link(FILENAME_COUNTRIES)
      ),
      array(
        'code' => FILENAME_ZONES,
        'title' => BOX_TAXES_ZONES,
        'link' => tep_href_link(FILENAME_ZONES)
      ),
      array(
        'code' => FILENAME_GEO_ZONES,
        'title' => BOX_TAXES_GEO_ZONES,
        'link' => tep_href_link(FILENAME_GEO_ZONES)
      ),
      array(
        'code' => FILENAME_TAX_CLASSES,
        'title' => BOX_TAXES_TAX_CLASSES,
        'link' => tep_href_link(FILENAME_TAX_CLASSES)
      ),
      array(
        'code' => FILENAME_TAX_RATES,
        'title' => BOX_TAXES_TAX_RATES,
        'link' => tep_href_link(FILENAME_TAX_RATES)
      ),
      array(
        'code' => FILENAME_EASYMAP,
        'title' => BOX_EASYMAP,
        'link' => tep_href_link(FILENAME_LOCATION_MAP)
      )	  
    )
  );
?>
