<?php
/*  $Id: text_main.php v1.0 20101109 Kymation $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_fp_text_main {
    var $code ;
    var $group ;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $languages_array = array();
	var $pages ;

    function cm_index_fp_text_main() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));			

      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_DESCRIPTION;

      if( defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS' ) ) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
      }
    } // function text_main

    function execute() {
      global $oscTemplate, $language, $PHP_SELF, $cPath;

      if ($PHP_SELF == 'index.php' && $cPath == '') {
        // Set the text to display on the front page
        $body_text = '<!-- Text Main BOF -->' . PHP_EOL;
//        $body_text .= '<div class="jumbotron">' . PHP_EOL;		
//        $body_text .= '  <div class="container-fluid">' . PHP_EOL;
        $body_text .= constant( 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_' . strtoupper( $language ) ) . PHP_EOL;
//        $body_text .= '  </div>' . PHP_EOL;
//        $body_text .= '</div>' . PHP_EOL;		
        $body_text .= '<!-- Text Main EOF -->' . PHP_EOL;
		
        $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_CONTENT_WIDTH;

        ob_start();
          include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/text_main.php');
        $template = ob_get_clean();	 		

        $oscTemplate->addContent($template, $this->group);
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS' );
    }

    function install() {
	  global $multi_stores_config ;	
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Text Main', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS', 'True', 'Do you want to show the main text block on the front page?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())" );
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '1', now())" );
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");	  
	  
    	foreach( $this->languages_array as $language_id => $language_name ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ( '" . ucwords( $language_name ) . " Text', 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_" . strtoupper( $language_name ) . "', 'Quid ergo hunc aliud moliri, quid optare censetis aut quam omnino causam esse belli?', 'Enter the text that you want to show on the front page in " . $language_name . "', '6', '2', 
		'tep_draw_textarea_ckeditor(\'configuration[MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_" . strtoupper( $language_name ) . "]\', false, 115, 100, tep_get_config_value( MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_" . strtoupper( $language_name ) . " ),  tep_get_text_class() , ' , now())" );
     }
	  
    }

    function remove() {
	  global $multi_stores_config ;	
      tep_db_query( "delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')" );
    }

    function keys() {
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

      $keys_array = array ();

      $keys_array[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS';
      $keys_array[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_SORT_ORDER';
	  $keys_array[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_CONTENT_WIDTH' ;

    	foreach( $this->languages_array as $language_name ) {
    	  $keys_array[] = 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_' . strtoupper( $language_name );
    	}

      return $keys_array;
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_TEXT_MAIN_STATUS == 'False');

    }			
  }
?>