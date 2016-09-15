<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
  $avail_stores = tep_get_multi_stores();
  $multi_stores_array = array();
  $multi_stores_selected = $multi_stores_id  ;
  for ($i = 0, $n = sizeof($avail_stores); $i < $n; $i++) {
    $multi_stores_array[] = array('id' => $avail_stores[$i]['id'],
                                  'text' => $avail_stores[$i]['name']);
    if ($avail_stores[$i]['id'] == $multi_stores_id) {
      $multi_stores_selected = $avail_stores[$i]['id'];
    }
  }  

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
          <div id="dashboard"> 
<?php
  if ( defined('MODULE_ADMIN_DASHBOARD_INSTALLED') && tep_not_null(MODULE_ADMIN_DASHBOARD_INSTALLED) ) {
    $adm_array = explode(';', MODULE_ADMIN_DASHBOARD_INSTALLED);

    $col = 0;

    for ( $i=0, $n=sizeof($adm_array); $i<$n; $i++ ) {
      $adm = $adm_array[$i];

      $class = substr($adm, 0, strrpos($adm, '.'));

      if ( !class_exists($class) ) {
        include(DIR_WS_LANGUAGES . $language . '/modules/dashboard/' . $adm);
        include(DIR_WS_MODULES . 'dashboard/' . $class . '.php');
      }

      $ad = new $class();

      if ( $ad->isEnabled() ) {
        if ($col < 1) {
          echo '            <div class="row" style="padding-bottom:8px;">' . "\n";
        }

        $col++;

        if ($col <= 2) {
          echo '              <div class="col-md-6">' . "\n";
        }

        echo $ad->getOutput();

        if ($col <= 2) {
          echo '              </div>' . "\n";
        }

        if ( !isset($adm_array[$i+1]) || ($col == 2) ) {
          if ( !isset($adm_array[$i+1]) && ($col == 1) ) {
            echo '              <div class="clearfix"></div>' . "\n";
          }

          $col = 0;

          echo '            </div>' . "\n";
        }
      }
    }
  }
?>
          </div><!--#dashboard-->
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>