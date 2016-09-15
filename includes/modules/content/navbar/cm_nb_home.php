<?php
/*
  $Id: cm_nb_home.php, v1.0.1 20160321 Kymation$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 James C Keebaugh

  Released under the GNU General Public License
*/

  class cm_nb_home {
    public $version = '1.0.1';
    public $code = '';
    public $group = '';
    public $title = '';
    public $description = '';
    public $sort_order = 0;
    public $enabled = false;
    public $side = 'left';

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_NAVBAR_HOME_TITLE;
      $this->description = MODULE_CONTENT_NAVBAR_HOME_DESCRIPTION;

      if ( defined('MODULE_CONTENT_NAVBAR_HOME_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_NAVBAR_HOME_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_NAVBAR_HOME_STATUS == 'True');
        $this->side = ((MODULE_CONTENT_NAVBAR_HOME_CONTENT_PLACEMENT === 'left') ? 'left' : 'right');
      }
    }

    public function getOutput() {
      ob_start();
      include DIR_WS_MODULES . 'content/navbar/templates/' . basename(__FILE__);
      $template = ob_get_clean();

      return $template;
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_CONTENT_NAVBAR_HOME_STATUS');
    }

    public function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Module Version', 'MODULE_CONTENT_NAVBAR_HOME_VERSION', '" . $this->version . "', 'The version of this module that you are running.', '6', '0', 'tep_cfg_disabled(', now() ) ");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Home Module', 'MODULE_CONTENT_NAVBAR_HOME_STATUS', 'True', 'Should the home link be shown in the navigation bar?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_NAVBAR_HOME_SORT_ORDER', '9100', 'Sort order of display. Lowest is displayed first.', '6', '2', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_CONTENT_NAVBAR_HOME_CONTENT_PLACEMENT', 'left', 'Should the module be loaded on the left or right side of the navbar?', '6', '3', 'tep_cfg_select_option(array(\'left\', \'right\'), ', now())");
    }

    public function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      $keys = array();
      $keys[] = 'MODULE_CONTENT_NAVBAR_HOME_VERSION';
      $keys[] = 'MODULE_CONTENT_NAVBAR_HOME_STATUS';
      $keys[] = 'MODULE_CONTENT_NAVBAR_HOME_SORT_ORDER';
      $keys[] = 'MODULE_CONTENT_NAVBAR_HOME_CONTENT_PLACEMENT';
       
      return $keys;
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_NAVBAR_HOME_STATUS'");
            $this->enabled = (MODULE_CONTENT_NAVBAR_HOME_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_NAVBAR_HOME_STATUS'");
            $this->enabled = (MODULE_CONTENT_NAVBAR_HOME_STATUS == 'False');

    }		
  } // End class


  ////////////////////////////////////////////////////////////////////////////
  //                                                                        //
  //  This is the end of the module class.                                  //
  //  Everything past this point is an independent function, not a method.  //
  //                                                                        //
  ////////////////////////////////////////////////////////////////////////////


  ////
  // Function to show a disabled entry (Value is shown but cannot be changed)
  if( !function_exists( 'tep_cfg_disabled' ) ) {
    function tep_cfg_disabled( $value ) {
      return tep_draw_input_field( 'configuration_value', $value, ' disabled' );
    }
  }