<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class oscTemplate {
    var $_title;
    var $_blocks = array();
    var $_content = array();	
    var $_grid_container_width = 12;
    var $_grid_content_width = 8;
    var $_grid_column_width = 2;
    var $_data = array();	

    function oscTemplate() {
      $this->_title = TITLE;
    }

    function setGridContainerWidth($width) {
      $this->_grid_container_width = $width;
    }

    function getGridContainerWidth() {
      return $this->_grid_container_width;
    }

    function setGridContentWidth($width) {
      $this->_grid_content_width = $width;
    }

    function getGridContentWidth() {
      return $this->_grid_content_width;
    }

    function setGridColumnWidth($width) {
      $this->_grid_column_width = $width;
    }

    function getGridColumnWidth() {
      return $this->_grid_column_width;
    }

    function setTitle($title) {
      $this->_title = $title;
    }

    function getTitle() {
      return $this->_title;
    }

    function addBlock($block, $group) {
      $this->_blocks[$group][] = $block;
    }

    function hasBlocks($group) {
      return (isset($this->_blocks[$group]) && !empty($this->_blocks[$group]));
    }

function getBlocks($group) {
	  
       require_once(DIR_WS_CLASSES . '/MinifyCode.php');	  
       $minifyCode = new MinifyCode();	   
	   
	   $place = $this->hasBlocks($group);
	   switch ($place) {
		   case "boxes_column_left" :				
		   case "boxes_column_right" :
//              return $minifyCode->minifyHtml( implode("\n", $this->_blocks[$group]) ); 	   
			  return implode("\n", $this->_blocks[$group]);
		  	  break;
			 
		   case "boxes_column_bread" :		     	
//              return $minifyCode->minifyHtml( implode(" ", $this->_blocks[$group]) ); 		   
			  return implode(" ", $this->_blocks[$group]);
		  	  break;
			  
		   case "boxes_column_head" :
//		      return $minifyCode->minifyHtml( implode(" ", $this->_blocks[$group]) ); 
			  return implode(" ", $this->_blocks[$group]);
		  	  break;
			  
		   case "boxes_column_foot" :
//              return $minifyCode->minifyHtml( implode(" ", $this->_blocks[$group]) ); 		   
			  return implode(" ", $this->_blocks[$group]);
		  	  break;		
	   }
	}

    function buildBlocks() {
      global $language, $PHP_SELF;

      if ( defined('TEMPLATE_BLOCK_GROUPS') && tep_not_null(TEMPLATE_BLOCK_GROUPS) ) {
        $tbgroups_array = explode(';', TEMPLATE_BLOCK_GROUPS);

        foreach ($tbgroups_array as $group) {
          $module_key = 'MODULE_' . strtoupper($group) . '_INSTALLED';

          if ( defined($module_key) && tep_not_null(constant($module_key)) ) {
            $modules_array = explode(';', constant($module_key));
 
            foreach ( $modules_array as $module ) {
              $class = substr($module, 0, strrpos($module, '.'));

              if ( !class_exists($class) ) {
// bof 2.3.3.2			  
//                include(DIR_WS_LANGUAGES . $language . '/modules/' . $group . '/' . $module);
//                include(DIR_WS_MODULES . $group . '/' . $class . '.php');
               if ( file_exists(DIR_WS_LANGUAGES . $language . '/modules/' . $group . '/' . $module) ) {
                  include(DIR_WS_LANGUAGES . $language . '/modules/' . $group . '/' . $module);
               }
               if ( file_exists(DIR_WS_MODULES . $group . '/' . $class . '.php') ) {
                  include(DIR_WS_MODULES . $group . '/' . $class . '.php');
               }
              }  

			  if ( class_exists($class) ) {
// eof 2.3.3.2			  
                $mb = new $class();
                // bof Dynamic Template System
			    if ( $mb->isEnabled() ) {
                  if(($mb->pages === 'all' && $mb->pages !== 'null') || in_array($PHP_SELF , explode(';' , $mb->pages))){
                     $mb->execute();
                  }
		        }		
                // eof Dynamic Template System
			  } // 2.3.3.2
            }
          }
        }
      }
    }
	
// bof 2.3.4	
    function addContent($content, $group) {
      $this->_content[$group][] = $content;
    }

    function hasContent($group) {
      return (isset($this->_content[$group]) && !empty($this->_content[$group]));
    }

    function getContent($group) {
      global $language;

      if ( !class_exists('tp_' . $group) && file_exists(DIR_WS_MODULES . 'pages/tp_' . $group . '.php') ) {
        include(DIR_WS_MODULES . 'pages/tp_' . $group . '.php');
		echo 'pages/tp_' . $group . '.php' ;
      }

      if ( class_exists('tp_' . $group) ) {
        $template_page_class = 'tp_' . $group;
        $template_page = new $template_page_class();
        $template_page->prepare();
      }

      foreach ( $this->getContentModules($group) as $module ) {
        if ( !class_exists($module) ) {
          if ( file_exists(DIR_WS_MODULES . 'content/' . $group . '/' . $module . '.php') ) {
            if ( file_exists(DIR_WS_LANGUAGES . $language . '/modules/content/' . $group . '/' . $module . '.php') ) {
              include(DIR_WS_LANGUAGES . $language . '/modules/content/' . $group . '/' . $module . '.php');
            }

            include(DIR_WS_MODULES . 'content/' . $group . '/' . $module . '.php');
          }
        }

        if ( class_exists($module) ) {
          $mb = new $module();

          if ( $mb->isEnabled() ) {
            $mb->execute();
          }
        }
      }

      if ( class_exists('tp_' . $group) ) {
        $template_page->build();
      }

      if ($this->hasContent($group)) {
        return implode("\n", $this->_content[$group]);
//          require_once(DIR_WS_CLASSES . '/MinifyCode.php');

//          $minifyCode = new MinifyCode();
//          return $minifyCode->minifyHtml( implode("\n", $this->_content[$group]) );
      }
    }

    function getContentModules($group) {
      $result = array();

      foreach ( explode(';', MODULE_CONTENT_INSTALLED) as $m ) {
        $module = explode('/', $m, 2);

        if ( $module[0] == $group ) {
          $result[] = $module[1];
        }
      }

      return $result;
    }
// eof 2.3.4
  }
?>