<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class cfgm_java_css {
    var $code = 'java_css';
    var $directory;
    var $language_directory = DIR_FS_CATALOG_LANGUAGES;
    var $key = 'MODULE_JAVA_CSS_INSTALLED';
    var $title;
    var $template_integration = true;

    function cfgm_java_css() {
      $this->directory = DIR_FS_CATALOG_MODULES . 'java_css/';
      $this->title = MODULE_CFG_MODULE_JAVA_CSS_TITLE;
    }
  }
?>