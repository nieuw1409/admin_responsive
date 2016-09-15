<?php
/*
  $Id: ht_theme_admin_switcher.php v1.0 20110610 lorem_ipsum $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/



////
// Get a list of the files or directories in a directory
 // if( !function_exists( 'tep_get_directory_list' ) ) {
    function tep_get_directory_list2( $directory, $file=true, $exclude=array() ) {
      $d = dir( $directory );
      $list = array();
      while( $entry = $d->read() ) {
        if( $file == true ) { // We want a list of files, not directories
          $parts_array = explode( '.', $entry );
          $extension = $parts_array[1];
          // Don't add files or directories that we don't want
          if( $entry != '.' && $entry != '..' && $entry != '.htaccess' && $extension != 'php'  && $extension != 'gif'  ) {
            if( !is_dir( $directory . "/" . $entry ) ) {
             $list[] = array( 'id' => $entry,
                               'text' => $entry
                             );
            }
          }
        } else { // We want the directories and not the files
          if( is_dir( $directory . "/" . $entry ) && $entry != '.' && $entry != '..' ) {  // && $entry != 'i18n'
            if( count( $exclude ) == 0 || !in_array ( $entry, $exclude ) ) {
              $list[] = array( 'id' => $entry,
                               'text' => $entry
                             );
            }
          }
        }
      }
      $d->close();
      return $list;
    }
//  }

////
// Generate a pulldown menu of the available themes
  function tep_cfg_pull_down_file_js( $theme_name, $key = '' ) {
  	$themes_array = array();
    $theme_directory = DIR_FS_CATALOG . 'ext/jquery';

    if( file_exists( $theme_directory ) && is_dir( $theme_directory ) ) {
      $name = ( ( $key ) ? 'configuration[' . $key . ']' : 'configuration_value' );

      $exclude = array( 'i18n' );
      $themes_array = tep_get_directory_list2( $theme_directory, true, $exclude );
    }

    return tep_draw_pull_down_menu( $name, $themes_array, $theme_name );
  }


?>