<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class d_reviews {
    var $code = 'd_reviews';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function d_reviews() {
      $this->title = MODULE_ADMIN_DASHBOARD_REVIEWS_TITLE;
      $this->description = MODULE_ADMIN_DASHBOARD_REVIEWS_DESCRIPTION;

      if ( defined('MODULE_ADMIN_DASHBOARD_REVIEWS_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_REVIEWS_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_REVIEWS_STATUS == 'True');
      }
    }

    function getOutput() {
      global $languages_id;

      $output = '<div class="panel panel-default table-responsive">' .
	            '  <table class="table table-hover">' .
                '    <tr class="heading-row">' .
                '      <th class="col-md-4">' . MODULE_ADMIN_DASHBOARD_REVIEWS_TITLE . '</th>' .
                '      <th class="col-md-1">' . MODULE_ADMIN_DASHBOARD_REVIEWS_DATE . '</th>' .
                '      <th class="col-md-3">' . MODULE_ADMIN_DASHBOARD_REVIEWS_REVIEWER . '</th>' .
                '      <th class="col-md-3 text-center">' . MODULE_ADMIN_DASHBOARD_REVIEWS_RATING . '</th>' .
                '      <th class="col-md-1 text-right">' . MODULE_ADMIN_DASHBOARD_REVIEWS_REVIEW_STATUS . '</th>' .
                '    </tr>';

      $reviews_query = tep_db_query("select r.reviews_id, r.date_added, pd.products_name, r.customers_name, r.reviews_rating, r.reviews_status from " . TABLE_REVIEWS . " r, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = r.products_id and pd.language_id = '" . (int)$languages_id . "' order by r.date_added desc limit 6");
      while ($reviews = tep_db_fetch_array($reviews_query)) {
        $status_icon = ($reviews['reviews_status'] == '1') ? tep_glyphicon('ok-sign', 'success') : tep_glyphicon('remove-sign', 'danger');
        $output .= '    <tr>' .
                   '      <td class="col-md-4"><a href="' . tep_href_link(FILENAME_REVIEWS, 'rID=' . (int)$reviews['reviews_id'] . '&action=edit') . '">' . $reviews['products_name'] . '</a></td>' .
                   '      <td class="col-md-1">' . tep_date_short($reviews['date_added']) . '</td>' .
                   '      <td class="col-md-3">' . tep_output_string_protected($reviews['customers_name']) . '</td>' .
                   '      <td class="col-md-3 text-center small">' . tep_draw_stars($reviews['reviews_rating']) . '</td>' .
                   '      <td class="col-md-1 text-right">' . $status_icon . '</td>' .
                   '    </tr>';
      }

      $output .= '  </table>' . 
	             '</div>';

      return $output;
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_DASHBOARD_REVIEWS_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;		
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Reviews Module', 'MODULE_ADMIN_DASHBOARD_REVIEWS_STATUS', 'True', 'Do you want to show the latest reviews on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_REVIEWS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;		
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ADMIN_DASHBOARD_REVIEWS_STATUS', 'MODULE_ADMIN_DASHBOARD_REVIEWS_SORT_ORDER');
    }
  }
?>