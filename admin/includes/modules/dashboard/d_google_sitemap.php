<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class d_GOOGLE_SITEMAP {
    var $code = 'd_google_sitemap';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function d_google_sitemap() {
      $this->title = MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_TITLE;
      $this->description = MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_DESCRIPTION;

      if ( defined('MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_STATUS == 'True');
      }
    }

    function getOutput() {
	  global $PHP_SELF, $HTTP_GET_VARS ;
	  
      $datecheck_file = DIR_FS_CATALOG . 'sitemapIndex.xml';
      $date_last_checked = tep_datetime_short(date('Y-m-d H:i:s', filemtime($datecheck_file)));
	  $base_url = HTTP_SERVER . DIR_WS_CATALOG;
	  $url_gs_update = 'usu5_sitemaps/index.php?language=nl';
  	  $url_gs_ping = 'http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $base_url . 'sitemapindex.xml';
	  $url_gs_docs = $base_url . 'googlesitemap/index.html';
	  
	  $output .= '<div class="panel panel-primary">' . PHP_EOL ;
      $output .= '   <div class="panel-heading">' . MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_TITLE  . '</div>' . PHP_EOL ;		  
	  $output .= '   <div class="panel-body">' . PHP_EOL ;	  
	  
	  $output .= '   			     <ul class="list-group">' . PHP_EOL ;
	  $output .= '   			       <li class="list-group-item">' . PHP_EOL ;
	  $output .= '                          <p><strong>' . MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_DATE . '</strong>' . ' : ' .  $date_last_checked . '</p>' . PHP_EOL ;
	  $output .= '                      </li>' . PHP_EOL ;
	  $output .= '   			       <li class="list-group-item">' . PHP_EOL ;
	  $output .= '                         <p class="text-info">' . tep_draw_bs_button( MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_UPDATE_NOW, 'disk', tep_catalog_href_link( $url_gs_update . '&return=' . basename($PHP_SELF) . '&admin_id=' . $HTTP_GET_VARS['osCAdminID'] ), null, null, 'btn-primary ') . '</p>' . PHP_EOL ;
	  $output .= '                      </li>' . PHP_EOL ;
	  $output .= '   			       <li class="list-group-item">' . PHP_EOL ;
	  $output .= '                         <p class="text-info">' . tep_draw_bs_button( MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_PING,       'disk', $url_gs_ping, null, null, 'btn-primary ') . '</p>' . PHP_EOL ;
	  $output .= '                      </li>' . PHP_EOL ;
	  $output .= '                    </ul>' . PHP_EOL ;
				 
      $output .= '   </div>' . PHP_EOL ;					 
      $output .= '</div>' . PHP_EOL ;		  

      return $output;
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Google XML Sitemap Admin Module', 'MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_STATUS', 'True', 'Do you want to show the google xml sitemap module on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_SORT_ORDER', '1300', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_STATUS', 'MODULE_ADMIN_DASHBOARD_GOOGLE_SITEMAP_SORT_ORDER');
    }
  }
?>
