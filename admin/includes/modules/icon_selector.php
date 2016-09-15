<?php
/*
  $Id: icon_selector.php 20110212 Kymation $
  $Loc: catalog/admin/includes/functions/modules/ $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2011 osCommerce

  Released under the GNU General Public License
*/


  ////
  // Generate a pulldown menu of the available jquery icons
  //   Requires a text file containing a list of the icons, one per line
  //   at: admin/includes/functions/modules/boxes/icons.txt
  if (!function_exists('tep_cfg_pull_down_icon')) {
    function tep_cfg_pull_down_icon($icon, $key = '') {
      $icons_array = array ();

      $file = DIR_WS_MODULES . 'icons.txt';
      if (file_exists($file) && is_file($file)) {
        $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
        $file_contents = @file($file);

        foreach ($file_contents as $icon_name) {
          $icon_name = trim($icon_name);

          if (strlen($icon_name) > 0) {
            $icon_name = str_replace('ui-icon-', '', $icon_name);

            $icons_array[] = array (
              'id' => $icon_name,
              'text' => $icon_name
            );

          } // if (strlen
        } // foreach ($file_contents
      } // if( file_exists

      return tep_draw_pull_down_menu($name, $icons_array, $icon);
    } // function tep_cfg_pull_down_icon
  } // if (!function_exists

?>