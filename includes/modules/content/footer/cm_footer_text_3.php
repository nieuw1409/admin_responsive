<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class cm_footer_text_3 {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_footer_text_3() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_FOOTER_TEXT_3_TITLE;
      $this->description = MODULE_CONTENT_FOOTER_TEXT_3_DESCRIPTION;

      if ( defined('MODULE_CONTENT_FOOTER_TEXT_3_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_FOOTER_TEXT_3_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_FOOTER_TEXT_3_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $languages_description, $language ;
      
      $content_width = (int)MODULE_CONTENT_FOOTER_TEXT_3_CONTENT_WIDTH;
	  
	  $footer_text_text  = constant( 'MODULE_CONTENT_FOOTER_TEXT_3_TEXT_' . strtoupper( $language ) );
	  $footer_text_title = constant( 'MODULE_CONTENT_FOOTER_TEXT_3_TITLE_' . strtoupper( $language ) );
      
      ob_start();
      include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/text.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_FOOTER_TEXT_3_STATUS');
    }

    function install() {
	  global $multi_stores_config;		

      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }	  
	  
	  
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Generic Text Footer Module', 'MODULE_CONTENT_FOOTER_TEXT_3_STATUS', 'True', 'Do you want to enable the Generic Text content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_FOOTER_TEXT_3_CONTENT_WIDTH', '3', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_TEXT_3_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");

   	foreach( $this->languages_array as $language_name ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ( '" . ucwords( $language_name ) . " Title', 'MODULE_CONTENT_FOOTER_TEXT_3_TITLE_" . strtoupper( $language_name ) . "', 'Generic Title', 'Enter the title that you want on your box in " . $language_name . "', '6', '10', now())" );
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ( '" . ucwords( $language_name ) . " Contents', 'MODULE_CONTENT_FOOTER_TEXT_3_TEXT_" . strtoupper( $language_name ) . "', 'Generic Contents', 'Enter the contents that you want in your box in " . $language_name . "', '6', '20', 
		'tep_draw_textarea_ckeditor(\'configuration[MODULE_CONTENT_FOOTER_TEXT_3_TEXT_" . strtoupper( $language_name ) . "]\', false, 115, 100, tep_get_config_value( MODULE_CONTENT_FOOTER_TEXT_3_TEXT_" . strtoupper( $language_name ) . " ),  tep_get_text_class() , ' , now())" );
		
      }
	  
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " .  $multi_stores_config   . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }
	  
    	$keys = array();

    	$keys[] = 'MODULE_CONTENT_FOOTER_TEXT_3_STATUS';
    	$keys[] = 'MODULE_CONTENT_FOOTER_TEXT_3_CONTENT_WIDTH';
    	$keys[] = 'MODULE_CONTENT_FOOTER_TEXT_3_SORT_ORDER';
  
		
    	foreach( $this->languages_array as $language_name ) {
    	  $keys[] = 'MODULE_CONTENT_FOOTER_TEXT_3_TITLE_' . strtoupper( $language_name );
    	  $keys[] = 'MODULE_CONTENT_FOOTER_TEXT_3_TEXT_' . strtoupper( $language_name );
    	}
 
        return $keys ; 
 //     return array('MODULE_CONTENT_FOOTER_TEXT_3_STATUS', 'MODULE_CONTENT_FOOTER_TEXT_3_CONTENT_WIDTH', 'MODULE_CONTENT_FOOTER_TEXT_3_SORT_ORDER');
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_FOOTER_TEXT_3_STATUS'");
            $this->enabled = (MODULE_CONTENT_FOOTER_TEXT_3_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_FOOTER_TEXT_3_STATUS'");
            $this->enabled = (MODULE_CONTENT_FOOTER_TEXT_3_STATUS == 'False');

    }		
  }

