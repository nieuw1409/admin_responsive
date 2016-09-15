<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  ******* skype box  ******************************
  ******* created by:  O.F.Y. - http://www.oscommerceforyou.hu ******
  Released under the GNU General Public License
*/
  class bm_skype {
    var $code = 'bm_skype';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;	
	
    function bm_skype() {
      $this->title = MODULE_BOXES_SKYPE_TITLE;
      $this->description = MODULE_BOXES_SKYPE_DESCRIPTION;

      if ( defined('MODULE_BOXES_SKYPE_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SKYPE_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SKYPE_STATUS == 'True');
		$this->pages = MODULE_BOXES_SKYPE_DISPLAY_PAGES;
		
        $this->group = ((MODULE_BOXES_SKYPE_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
     $placement = MODULE_BOXES_SKYPE_CONTENT_PLACEMENT;
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

      $header = '<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>' ;
      $oscTemplate->addBlock( $header, 'header_tags' );	 

      if (MODULE_BOXES_SKYPE_CALL_ME =='True') {
          $skype_data_call= '<div class="text-center">' . MODULE_BOXES_SKYPE_BOX_CALL_ME_TEXT . '<br /><a href="skype:' . MODULE_BOXES_SKYPE_NAME . '?call"><img src="http://mystatus.skype.com/smallclassic/' . MODULE_BOXES_SKYPE_NAME . '" style="border: none;" width="114" height="20"  title="' . MODULE_BOXES_SKYPE_BOX_CALL_ME_TEXT . '" alt="' . MODULE_BOXES_SKYPE_BOX_CALL_ME_TEXT . '" /></a> </div>';
                                         }

       if (MODULE_BOXES_SKYPE_CHAT_ME =='True') {
           $skype_data_chat= '<div class="text-center">' . MODULE_BOXES_SKYPE_BOX_CHAT_ME_TEXT . '<br /><a href="skype:' . MODULE_BOXES_SKYPE_NAME . '?chat"><img src="http://mystatus.skype.com/smallclassic/' . MODULE_BOXES_SKYPE_NAME . '" style="border: none;" width="114" height="20" title="' . MODULE_BOXES_SKYPE_BOX_CHAT_ME_TEXT . '" alt="' . MODULE_BOXES_SKYPE_BOX_CHAT_ME_TEXT . '" /></a></div>' ;
                                         }

      ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/skype.php');
      $data = ob_get_clean();  
      $oscTemplate->addBlock($data, $this->group);   
}

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SKYPE_STATUS');
    }


    function install() {
	  global $multi_stores_config;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable SKYPE Module', 'MODULE_BOXES_SKYPE_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable SKYPE call me funktion?', 'MODULE_BOXES_SKYPE_CALL_ME', 'True', 'Do you want to add the SKYPE call me funktion?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable SKYPE chat me funktion?', 'MODULE_BOXES_SKYPE_CHAT_ME', 'True', 'Do you want to add the chat me funktion?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Box heading', 'MODULE_BOXES_SKYPE_HEADING', 'True', 'Do you want to add the infobox heading to box?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Box contents', 'MODULE_BOXES_SKYPE_CONTENTS', 'True', 'Do you want to add the infobox content to box?<br>Box heading = False This is only possible with False!', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SKYPE_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SKYPE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
	  tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Skype name', 'MODULE_BOXES_SKYPE_NAME', 'yourskypename', 'Your Name on the SKYPE.</strong>', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SKYPE_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
    }



    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_SKYPE_STATUS', 'MODULE_BOXES_SKYPE_CONTENT_PLACEMENT', 'MODULE_BOXES_SKYPE_SORT_ORDER', 'MODULE_BOXES_SKYPE_CALL_ME', 'MODULE_BOXES_SKYPE_CHAT_ME', 'MODULE_BOXES_SKYPE_HEADING',  'MODULE_BOXES_SKYPE_CONTENTS','MODULE_BOXES_SKYPE_NAME', 'MODULE_BOXES_SKYPE_DISPLAY_PAGES');
    }
  }
?>