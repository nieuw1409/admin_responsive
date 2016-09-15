<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_login {
    var $code = 'bm_login';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $my_account_links;
    var $greeting;
	var $pages ;

    function bm_login() {
      $this->title = MODULE_BOXES_LOGIN_TITLE;
      $this->description = MODULE_BOXES_LOGIN_DESCRIPTION;

      if ( defined('MODULE_BOXES_LOGIN_STATUS') ) {
        $this->sort_order       = MODULE_BOXES_LOGIN_SORT_ORDER;
        $this->enabled          = (MODULE_BOXES_LOGIN_STATUS == 'True');
        $this->my_account_links = MODULE_BOXES_LOGIN_ACCOUNT_LINKS;
        $this->greeting         = MODULE_BOXES_LOGIN_GREETING;
		$this->pages            = MODULE_BOXES_LOGIN_DISPLAY_PAGES ;

//       $this->group = ((MODULE_BOXES_LOGIN_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_LOGIN_CONTENT_PLACEMENT;
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
      global $PHP_SELF, $customer_id, $customer_first_name, $oscTemplate;
	  
      $title = True ;
	  
/*   
	   $placement = MODULE_BOXES_LOGIN_CONTENT_PLACEMENT;
       switch($placement) {		  
            case 'Left Header' : 
            case 'Center Header' : 
            case 'Right Header' :           
            case 'Left Footer' : 
            case 'Center Footer' :
            case 'Right Footer' :
            case 'Header Line' :			
            case 'Footer Line' : 			
			case "Bread Column" :	
			  if (!tep_session_is_registered('customer_id')) {
				if ((basename($PHP_SELF) != FILENAME_LOGIN) && (basename($PHP_SELF) != FILENAME_CREATE_ACCOUNT)) {
				  $data = '<div class="panel panel-default panel-primary text-center">' .
				          '  <div class="panel-body">' .
						  '    ' . tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', '', true) .
						  '    ' . '<span style="float: left">' . MODULE_BOXES_LOGIN_BOX_EMAIL_ADDRESS . '</span><br />' . tep_draw_input_field('email_address', '', 'size="10" style="width: 90%"') . '<br />'. 
						  '         <span style="float: left">' . MODULE_BOXES_LOGIN_BOX_PASSWORD . '</span><br />' . tep_draw_password_field('password', '', 'size="10" style="width: 90%"') . '<br />' . 
						       tep_draw_button(IMAGE_BUTTON_LOGIN, 'key', null, 'primary') . '<br /><a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_PASSWORD_FORGOTTEN . '</a><br /><a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_NEW_ACCOUNT . '</a>' .
						  '    </form>' .
						  '  </div>' .
						  '</div>';

				  $oscTemplate->addBlock($data, $this->group);
				}
			  } elseif (tep_session_is_registered('customer_id')) {
				if (MODULE_BOXES_LOGIN_ACCOUNT_LINKS == 'True') {
				  $data = '<div class="ui-widget infoBoxContainer">' .						  
						  '  <div class="ui-widget-content ui-corner-all infoBoxContents" style="text-align: left;">' .
						  ((MODULE_BOXES_LOGIN_GREETING == 'True') ? '  <strong>' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_TEXT . $customer_first_name . '</strong><br />' : '') .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_INFORMATION . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_ADDRESS_BOOK . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_PASSWORD . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_ORDERS_VIEW . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_EMAIL_NOTIFICATIONS_PRODUCTS . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_LOGOFF . '</a>' .
						  '  </div>' .
						  '</div>';

				  $oscTemplate->addBlock($data, $this->group);
				}
			  }	
              break ;			  
            case "Left Column" :				
			case "Right Column" :
			case "Head Column" :				
			case "Foot Column" :

			  if (!tep_session_is_registered('customer_id')) {
				if ((basename($PHP_SELF) != FILENAME_LOGIN) && (basename($PHP_SELF) != FILENAME_CREATE_ACCOUNT)) {
				  $data = '<div class="panel panel-default panel-primary ">' .
				          '  <div class="panel-heading text-center">' . MODULE_BOXES_LOGIN_BOX_TITLE . '</div>' .
						  '  <div class="panel-body text-left">' .
						  '    ' . tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', '', true) .
                          '    <div class="input-group">' .
                          '    ' . tep_draw_input_field('email_address', '', 'required placeholder="' . MODULE_BOXES_LOGIN_BOX_EMAIL_ADDRESS . '"') . '<br />' . 
                          '    ' . tep_draw_input_field('password', '', 'required placeholder="' . MODULE_BOXES_LOGIN_BOX_PASSWORD . '"') . '<br />' . 
//                          '    <br /><span class="input-group-btn"><button type="submit" class="btn btn-suearch"><i class="glyphicon glyphicon-password">' . IMAGE_BUTTON_LOGIN . '</i></button></span><br />' .						  
                          tep_draw_button(IMAGE_BUTTON_LOGIN, 'log-in', null, 'primary', '', '') . '<br /><br />' .
						  '      <a class="label label-info"    href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_PASSWORD_FORGOTTEN . '</a><br /><br />'.
						  '      <a class="label label-success" href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_NEW_ACCOUNT . '</a>' .						  
                          '    </div>' 	.					  
//						  '    ' . '<span style="float: left">' . MODULE_BOXES_LOGIN_BOX_EMAIL_ADDRESS . '</span><br />' . tep_draw_input_field('email_address', '', 'size="10" style="width: 90%"') . '<br /><span style="float: left">' . MODULE_BOXES_LOGIN_BOX_PASSWORD . '</span><br />' . 
//						  tep_draw_password_field('password', '', 'size="10" style="width: 90%"') . '<br />' . tep_draw_button(IMAGE_BUTTON_LOGIN, 'key', null, 'primary') . '<br /><a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_PASSWORD_FORGOTTEN . '</a><br /><a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_NEW_ACCOUNT . '</a>' .
						  '    </form>' .
						  '  </div>' .
						  '</div>';

				  $oscTemplate->addBlock($data, $this->group);
				}
			  } elseif (tep_session_is_registered('customer_id')) {
				if (MODULE_BOXES_LOGIN_ACCOUNT_LINKS == 'True') {
				  $data = '<div class="panel panel-default panel-primary">' .
						  '  <div class="panel-heading">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_TITLE . '</div>' .
						  '  <div class="panel-body text-left">' .
						  ((MODULE_BOXES_LOGIN_GREETING == 'True') ? '  <strong>' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_TEXT . $customer_first_name . '</strong><br /><hr>' : '') .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_INFORMATION . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_ADDRESS_BOOK . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_PASSWORD . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_ORDERS_VIEW . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a><br />' .
						  '    <a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_EMAIL_NOTIFICATIONS_PRODUCTS . '</a><hr>' .
						  '    <a href="' . tep_href_link(FILENAME_LOGOFF, '', 'SSL') . '">' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_LOGOFF . '</a>' .
						  '  </div>' .
						  '</div>';

				  $oscTemplate->addBlock($data, $this->group);
				}
			  }
			 break;
		}
*/

      ob_start();
        include(DIR_WS_MODULES . 'boxes/templates/login.php');
      $data = ob_get_clean();

      $oscTemplate->addBlock($data, $this->group);		
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_LOGIN_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Login Box Module', 'MODULE_BOXES_LOGIN_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display My Account Links', 'MODULE_BOXES_LOGIN_ACCOUNT_LINKS', 'True', 'Do you want to display the my account links?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Greeting', 'MODULE_BOXES_LOGIN_GREETING', 'True', 'Do you want to display the welcome greeting?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_LOGIN_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_LOGIN_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_LOGIN_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	  
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_LOGIN_STATUS', 'MODULE_BOXES_LOGIN_ACCOUNT_LINKS', 'MODULE_BOXES_LOGIN_GREETING', 'MODULE_BOXES_LOGIN_CONTENT_PLACEMENT', 'MODULE_BOXES_LOGIN_SORT_ORDER', 'MODULE_BOXES_LOGIN_DISPLAY_PAGES');
    }
  }
?>
