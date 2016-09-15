<?php
/*  $Id: heading_title.php v1.0 20101109 Kymation $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class cm_index_fp_heading_title {
    var $code  ;
    var $group ;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages ;

    function cm_index_fp_heading_title() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));			
      $this->title = MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_TITLE;
      $this->description = MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_DESCRIPTION;

      if( defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS' ) ) {
        $this->sort_order = MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_SORT_ORDER;
        $this->enabled = ( MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS == 'True' );
		$this->pages= FILENAME_DEFAULT ; 
      }
    }

    function execute() {
      global $oscTemplate, $PHP_SELF, $cPath;

      if( $PHP_SELF == 'index.php' && $cPath == '' ) {
        // Set the text to display on the front page
      	$body_text = '<!-- Heading Title BOF -->' . PHP_EOL;
//        $body_text .= '<div class="page-header">' . PHP_EOL;			
        $body_text .=   HEADING_TITLE ;
//        $body_text .= '</div>' . PHP_EOL;			
        $body_text .= '<!-- Heading Title EOF -->' ;

        $content_width = (int)MODULE_CONTENT_INDEX_FRONTPAGE__CUSTOMER_GREETING_CONTENT_WIDTH;

        ob_start();
          include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/customer_greeting.php');
        $template = ob_get_clean();	 		

        $oscTemplate->addContent($template, $this->group);
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS' );
    }

    function install() {
	  global $multi_stores_config ;	
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())" );
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Heading Title', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS', 'True', 'Do you want to show the heading title?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())" );
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");	  
    }

    function remove() {
	  global $multi_stores_config ;	
      tep_db_query( "delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')" );
    }

    function keys() {
      return array( 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_SORT_ORDER', 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_CONTENT_WIDTH' );
    }
    function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_FRONTPAGE_HEADING_TITLE_STATUS == 'False');

    }			
  }
?>