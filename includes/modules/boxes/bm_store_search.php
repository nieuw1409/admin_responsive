<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  class bm_store_search {
    var $code = 'bm_store_search';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;		

    function bm_store_search() {
      $this->title = MODULE_BOXES_STORE_SEARCH_TITLE;
      $this->description = MODULE_BOXES_STORE_SEARCH_DESCRIPTION;
      $this->pages = MODULE_BOXES_STORE_SEARCH_DISPLAY_PAGES;		  

      if ( defined('MODULE_BOXES_STORE_SEARCH_STATUS') ) {
        $this->sort_order = MODULE_BOXES_STORE_SEARCH_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_STORE_SEARCH_STATUS == 'True');
      $placement = MODULE_BOXES_STORE_SEARCH_CONTENT_PLACEMENT;
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
      global $request_type, $oscTemplate;

      $content_width = MODULE_BOXES_SEARCH_CONTENT_WIDTH;

      $search_box = tep_navbar_store_search('btn-info', (MODULE_BOXES_STORE_SEARCH_FUNCTIONS == 'Descriptions'));

      // define typeahead scripts
      $script  = '<script type="text/javascript" src="' . tep_href_link('ext/bootstrap-typeahead/bootstrap3-typeahead.min.js', null, $request_type) . '"></script>' . PHP_EOL ;
      $script .= '<script type="text/javascript" src="' . tep_href_link('ext/modules/content/header/store_search/content_searches.min.js', null, $request_type) . '"></script>' . PHP_EOL ;

      $oscTemplate->addBlock($script, 'footer_scripts');

      ob_start();
       include(DIR_WS_MODULES . 'boxes/templates/store_search.php');
      $template = ob_get_clean();

      $oscTemplate->addBlock($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_STORE_SEARCH_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Search Module', 'MODULE_BOXES_STORE_SEARCH_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_BOXES_SEARCH_CONTENT_WIDTH', '4', 'What width container should the content be shown in?', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_STORE_SEARCH_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");  

      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Extended Store Search Functions', 'MODULE_BOXES_STORE_SEARCH_FUNCTIONS', 'Standard', 'Do you want to enable search function in descriptions?', '6', '1', 'tep_cfg_select_option(array(\'Standard\', \'Descriptions\'), ', now())");
//      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_BOXES_STORE_SEARCH_PAGES', '" . implode(';', $this->get_default_pages()) . "', 'The pages to add the Store Search\'s results.', '6', '0', 'cm_header_store_search_show_pages', 'cm_header_store_search_pages(', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_STORE_SEARCH_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	   	  
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_STORE_SEARCH_SORT_ORDER', '10010', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;		
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_STORE_SEARCH_STATUS', 'MODULE_BOXES_STORE_SEARCH_CONTENT_PLACEMENT','MODULE_BOXES_STORE_SEARCH_FUNCTIONS', 'MODULE_BOXES_STORE_SEARCH_DISPLAY_PAGES', 'MODULE_BOXES_STORE_SEARCH_SORT_ORDER');
    }

    function get_default_pages() {
      return array( 'shipping.php',
                    'contact_us.php',
                    'conditions.php',
                    'cookie_usage.php',
                    'privacy.php',
                    'login.php',
                    'address_book.php',
                    'create_account.php',
                    'account_history.php',
                    'advanced_search.php',
                    'products_new.php',
                    'reviews.php',
                    'ssl_check.php',
                    'specials.php',
                    'shopping_cart.php');
    }
  }

  function tep_navbar_store_search($btnclass ='btn-default', $description = true) {
    global $request_type;

$search_link = '<div class="searchbox-margin">';
$search_link .= tep_draw_form('quick_find', tep_href_link('advanced_search_result.php', '', $request_type, false), 'get', 'class="form-horizontal"');
$search_link .= ' <div class="form-group typeahead">' .
tep_draw_input_field('keywords', '', 'required placeholder="' . MODULE_CONTENT_HEADER_STORE_SEARCH_PLACEHOLDER . '" id="quick_search" data-provide="typeahead" autocomplete="on" style="margin-right:-2px;"', 'text') .
' <span class="input-group-btn"><button type="submit" class="btn ' . $btnclass .'" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;"><i class="glyphicon glyphicon-search"></i></button></span>';
if (tep_not_null($description) && ($description === true)) {
$search_link .= tep_draw_hidden_field('search_in_description', '1');
}
$search_link .= ' </div>';
$search_link .= tep_hide_session_id() . '</form>';
$search_link .= ' </div>';

     return $search_link;
  }

  function cm_header_store_search_show_pages($text) {
    return nl2br(implode("\n", explode(';', $text)));
  }

  function cm_header_store_search_pages($values, $key) {
    global $PHP_SELF;

    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
    $files_array = array();
    if ($dir = @dir(DIR_FS_CATALOG)) {
      while ($file = $dir->read()) {
        if (!is_dir(DIR_FS_CATALOG . $file)) {
          if (substr($file, strrpos($file, '.')) == $file_extension) {
            $files_array[] = $file;
          }
        }
      }
      sort($files_array);
      $dir->close();
    }

    $values_array = explode(';', $values);

    $output = '';
    foreach ($files_array as $file) {
      $output .= tep_draw_checkbox_field('cm_header_store_search_file[]', $file, in_array($file, $values_array)) . '&nbsp;' . tep_output_string($file) . '<br />';
    }

    if (!empty($output)) {
      $output = '<br />' . substr($output, 0, -6);
    }

    $output .= tep_draw_hidden_field('configuration[' . $key . ']', '', 'id="htrn_files"');

    $output .= '<script>
                function htrn_update_cfg_value() {
                  var htrn_selected_files = \'\';

                  if ($(\'input[name="cm_header_store_search_file[]"]\').length > 0) {
                    $(\'input[name="cm_header_store_search_file[]"]:checked\').each(function() {
                      htrn_selected_files += $(this).attr(\'value\') + \';\';
                    });

                    if (htrn_selected_files.length > 0) {
                      htrn_selected_files = htrn_selected_files.substring(0, htrn_selected_files.length - 1);
                    }
                  }

                  $(\'#htrn_files\').val(htrn_selected_files);
                }

                $(function() {
                  htrn_update_cfg_value();

                  if ($(\'input[name="cm_header_store_search_file[]"]\').length > 0) {
                    $(\'input[name="cm_header_store_search_file[]"]\').change(function() {
                      htrn_update_cfg_value();
                    });
                  }
                });
                </script>';

    return $output;
  }
?>
