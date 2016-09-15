<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application.php');

  $page_contents = 'install.php';

  if (isset($_GET['step']) && is_numeric($_GET['step'])) {
    switch ($_GET['step']) {
      case '2':
        $page_contents = 'install_2.php';
        break;

      case '3':
        $page_contents = 'install_3.php';
        break;

      case '4':
        $page_contents = 'install_4.php';
        break;
      case '20':  // multi store install first page equal to templates/pages/install.php
        $page_contents = 'install_multi_store.php';
        break;		
      case '22':  // multi store install step 2 equal to templates/pages/install_2.php
        $page_contents = 'install_multi_store_2.php';
        break;				
      case '23':  // multi store install step 3 equal to templates/pages/install_3.php
        $page_contents = 'install_multi_store_3.php';
        break;					
      case '24':  // multi store install step 4 equal to templates/pages/install_4.php
        $page_contents = 'install_multi_store_4.php';
        break;				
    }
  }

  require('templates/main_page.php');
?>