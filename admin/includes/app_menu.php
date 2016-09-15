<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  if (tep_session_is_registered('admin')) {
    $cl_box_groups = array();

    if ($dir = @dir(DIR_FS_ADMIN . 'includes/boxes')) {
      $files = array();

      while ($file = $dir->read()) {
        if (!is_dir($dir->path . '/' . $file)) {
          if (substr($file, strrpos($file, '.')) == '.php') {
            $files[] = $file;
          }
        }
      }

      $dir->close();

      natcasesort($files);

      foreach ( $files as $file ) {
        if ( file_exists(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file) ) {
          include(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file);
        }

        include($dir->path . '/' . $file);
      }
    }

    function tep_sort_admin_boxes($a, $b) {
      return strcasecmp($a['heading'], $b['heading']);
    }

    usort($cl_box_groups, 'tep_sort_admin_boxes');

    function tep_sort_admin_boxes_links($a, $b) {
      return strcasecmp($a['title'], $b['title']);
    }

    foreach ( $cl_box_groups as &$group ) {
      usort($group['apps'], 'tep_sort_admin_boxes_links');
    }

?>
        <li class="dropdown">
          <a id="drop1" role="navigation" class="dropdown-toggle" data-toggle="dropdown" role="button" href="#"><?php echo HEADER_TITLE_APPLICATIONS ; ?><b class="caret"></b></a>
          <ul class="dropdown-menu multi-level" aria-labelledby="drop1" role="menu">
<?php
    $n = 1;
    foreach ($cl_box_groups as $groups) {
      $n++;
      echo '            <li class="dropdown-submenu"><a class="submenu" id="drop' . $n . '" href="#">' . $groups['heading'] . '</a>' . "\n" . 
           '              <ul aria-labelledby="drop' . $n . '" class="dropdown-menu">' . "\n";

      foreach ($groups['apps'] as $app) {
        echo '                <li><a href="' . $app['link'] . '">' . $app['title'] . '</a></li>' . "\n";
      }

      echo '              </ul>' . "\n" . '            </li>' . "\n";
    }
?>
           </ul>
        </li>
<?php
  }
?>
