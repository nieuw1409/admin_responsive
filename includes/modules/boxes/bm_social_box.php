<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_social_box {
    var $code = 'bm_social_box';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;	

    function bm_social_box() {
      $this->title = MODULE_BOXES_SOCIAL_TITLE;
      $this->description = MODULE_BOXES_SOCIAL_DESCRIPTION;
      $this->pages = MODULE_BOXES_SOCIAL_NETWORK_DISPLAY_PAGES;
	  
      if ( defined('MODULE_BOXES_SOCIAL_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SOCIAL_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SOCIAL_STATUS == 'True');	

//        $this->group = ((MODULE_BOXES_SOCIAL_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_SOCIAL_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :
				$this->group = 'boxes_column_left';
				break;
			case "Right Column" :
				$this->group = 'boxes_column_right';
				break;
			case "Bread Column" :
				$this->group = 'boxes_column_bread';
				break;
			case "Head Column" :
				$this->group = 'boxes_column_head';
				break;
			case "Foot Column" :
				$this->group = 'boxes_column_foot';
				break;		
            case 'Left Header' : 
			    $this->group = 'header_contents_left';
                break;
            case 'Center Header' : 
			    $this->group = 'header_contents_center';
                break;
            case 'Right Header' : 
			    $this->group = 'header_contents_right';
                break;
            case 'Header Line' :  
			    $this->group = 'header_line';
                break;
            case 'Left Footer' : 
			    $this->group = 'footer_contents_left';
                break;
            case 'Center Footer' : 
			    $this->group = 'footer_contents_center';
                break;
            case 'Right Footer' : 
			    $this->group = 'footer_contents_right';
                break;
            case 'Footer Line' : 
			    $this->group = 'footer_line';
                break;						
		}
      }
    }
	

    function execute() {
      global $oscTemplate;
	  
	  $title = True ;
/*	  
//      global $PHP_SELF, $lng, $request_type, $oscTemplate;
      $placement = MODULE_BOXES_SOCIAL_CONTENT_PLACEMENT;
      switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Foot Column" :
			case "Head Column" :
             $data = '<div class="panel panel-default panel-primary">' .
                '  <div class="panel-heading">' . MODULE_BOXES_SOCIAL_BOX_TITLE . '</div>' .
                '  <div class="panel-body text-center">' .
                '    <a href="http://www.new.facebook.com/profile.php?id=' . MODULE_BOXES_FACEBOOK_ACCOUNT_NAME . '" target="_new">' . tep_image(DIR_WS_IMAGES . 'follow-us-on-facebook.png', 'Follow us on Facebook') . '</a><br>' .
				'    <a href="http://twitter.com/' . MODULE_BOXES_TWITTER_ACCOUNT_NAME . '" target="_new">' . tep_image(DIR_WS_IMAGES . 'follow_us_twitter.png', 'Tweet Us!') . '</a>' .
                '  </div>' .
                '</div>';
				break;				
            case 'Left Header' : 
            case 'Center Header' : 
            case 'Right Header' :           
            case 'Left Footer' : 
            case 'Center Footer' :
            case 'Right Footer' :
            case 'Header Line' :			
            case 'Footer Line' : 			
			case "Bread Column" :
             $data = '<div class="panel panel-default panel-primary">' .                
                '  <div class="panel-body text-center">' .
                '  <a href="http://www.new.facebook.com/profile.php?id=' . MODULE_BOXES_FACEBOOK_ACCOUNT_NAME . '" target="_new">' . tep_image(DIR_WS_IMAGES . 'follow-us-on-facebook.png', 'Follow us on Facebook') . '</a><br>'.
				'  <a href="http://twitter.com/' . MODULE_BOXES_TWITTER_ACCOUNT_NAME . '" target="_new">' . tep_image(DIR_WS_IMAGES . 'follow_us_twitter.png', 'Tweet Us!') . '</a>' .
                '  </div>' .
                '</div>';
				break;

	   }				
*/
      $social_box_content =   '    <a href="http://www.new.facebook.com/profile.php?id=' . MODULE_BOXES_FACEBOOK_ACCOUNT_NAME . '" target="_new">' . tep_image(DIR_WS_IMAGES . 'follow-us-on-facebook.png', 'Follow us on Facebook') . '</a><br>' .
				              '    <a href="http://twitter.com/' . MODULE_BOXES_TWITTER_ACCOUNT_NAME . '" target="_new">' . tep_image(DIR_WS_IMAGES . 'follow_us_twitter.png', 'Tweet Us!') . '</a>' .
                
      ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/social_box.php');
      $data = ob_get_clean();  
      $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SOCIAL_STATUS');
    }

    function install() {
	  global $multi_stores_config;	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Social Box Module', 'MODULE_BOXES_SOCIAL_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SOCIAL_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SOCIAL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Facebook Account Name', 'MODULE_BOXES_FACEBOOK_ACCOUNT_NAME', '', 'Add your facebook account name here', '6', '1', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Twitter id', 'MODULE_BOXES_TWITTER_ACCOUNT_NAME', '', 'Add your twitter id here', '6', '1', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SOCIAL_NETWORK_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
	  
    }

    function remove() {
	  global $multi_stores_config;	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_SOCIAL_STATUS', 'MODULE_BOXES_SOCIAL_CONTENT_PLACEMENT', 'MODULE_BOXES_SOCIAL_SORT_ORDER', 'MODULE_BOXES_FACEBOOK_ACCOUNT_NAME', 'MODULE_BOXES_TWITTER_ACCOUNT_NAME', 'MODULE_BOXES_SOCIAL_NETWORK_DISPLAY_PAGES' );
    }
  }
?>