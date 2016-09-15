<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  class bm_search {
    var $code = 'bm_search';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_search() {
      $this->title = MODULE_BOXES_SEARCH_TITLE;
      $this->description = MODULE_BOXES_SEARCH_DESCRIPTION;
	  $this->pages = MODULE_BOXES_SEARCH_DISPLAY_PAGES;

      if ( defined('MODULE_BOXES_SEARCH_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SEARCH_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SEARCH_STATUS == 'True');

     //   $this->group = ((MODULE_BOXES_SEARCH_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_SEARCH_CONTENT_PLACEMENT;
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
      global $oscTemplate,$request_type;
	  
	  $title = True ;
/*	  
	  $placement = MODULE_BOXES_SEARCH_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :				
			case "Right Column" :
			case "Head Column" :
			case "Foot Column" :
               $data = '<div class="panel panel-default panel-primary">' .
                       '  <div class="panel-heading">' . MODULE_BOXES_SEARCH_BOX_TITLE . '</div>' .
                       '  <div class="panel-body text-center">' .
                       '    ' . tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', $request_type, false), 'get') .
                       '    <div class="input-group">' .
                       '    ' . tep_draw_input_field('keywords', '', 'required placeholder="' . TEXT_SEARCH_PLACEHOLDER . '"') .
                       '      <span class="input-group-btn"><button type="submit" class="btn btn-search"><i class="glyphicon glyphicon-search"></i></button></span>' .
                       '    </div>' . tep_draw_hidden_field('search_in_description', '0') . tep_hide_session_id() . '<br />' . MODULE_BOXES_SEARCH_BOX_TEXT . '<br /><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"><strong>' . MODULE_BOXES_SEARCH_BOX_ADVANCED_SEARCH . '</strong></a>' .
                       '    </form>' .
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
                       '' . tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', $request_type, false), 'get') .
                       '    <div class="input-group">' .
                       '    ' . tep_draw_input_field('keywords', '', '', 'text', '', TEXT_SEARCH_PLACEHOLDER ) .
                       '      <span class="input-group-btn"><button type="submit" class="btn btn-search"><i class="glyphicon glyphicon-search"></i></button></span>' .
                       '     </div>' . tep_draw_hidden_field('search_in_description', '0') . tep_hide_session_id() . '<br />' . MODULE_BOXES_SEARCH_BOX_TEXT . '<br /><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"><strong>' . MODULE_BOXES_SEARCH_BOX_ADVANCED_SEARCH . '</strong></a>' .
                       '    </form>' .
                       '  </div>' .
                       '</div>';			
				break;	      
		 }
*/
//glyphicon glyphicon-search
     $form_output = '    ' . tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', $request_type, false), 'get') .
                     '    <div class="input-group">' .
                     '    ' . tep_draw_input_field('keywords', '', ' placeholder="' . TEXT_SEARCH_PLACEHOLDER . '"') .
                     '      <span class="input-group-btn"><button type="submit" class="btn btn-search"><i class="' . glyphicon_icon_to_fontawesome( "search" ) . '"></i></button></span>' .
                     '    </div>' . tep_draw_hidden_field('search_in_description', '0') . tep_hide_session_id() .
                     '    </form>';
					 
	  ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/search.php');
      $data =  ob_get_clean() ;		 
      $oscTemplate->addBlock($data, $this->group);
	  
/*	  $header .= '<!-- bof autocomplete -->' . "\n";
	  $header .= '<style>' . "\n";
	  $header .= '.ui-autocomplete-loading { background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat; }' . "\n";
	  $header .= '</style>' . "\n";
	  $header .= '' . "\n";
	  $header .= '<script type="text/javascript">' . "\n";
	  $header .= '$(function() {' . "\n";
	  $header .= 'function log( message ) {' . "\n";
	  $header .= '$( "<div>" ).text( message ).prependTo( "#log" );' . "\n";
	  $header .= '$( "#log" ).scrollTop( 0 );' . "\n";
	  $header .= '}' . "\n";
	  $header .= '' . "\n";	  
	  $header .= '$( "#searchBox" ).autocomplete({' . "\n";
	  $header .= 'source: "../autocomplete.php",' . "\n";
	  $header .= 'minLength: 2,' . "\n";
	  $header .= 'select: function( event, ui ) {' . "\n";
	  $header .= '' . "\n";
	  $header .= 'log( ui.item ?' . "\n";
	  $header .= '"Selected: " + ui.item.value + " aka " + ui.item.id :' . "\n";
	  $header .= '"Nothing selected, input was " + this.value );' . "\n";
	  $header .= '}' . "\n";
	  $header .= '});' . "\n";
	  $header .= '});' . "\n";
	  $header .= '</script>' . "\n";
	  
	  $header .= '<script type="text/javascript">' . "\n";
	  $header .= '$(document).ready(function() { ' . "\n";
	  $header .= '$("input.keywords").typeahead({ ' . "\n";
	  $header .= 'name: "keywords",' . "\n";
	  $header .= 'remote : "autocomplete.php?query=%QUERY"' . "\n";
	  $header .= '});; ' . "\n";
	  $header .= '}) ' . "\n";
	  $header .= '</script>' . "\n";	  
	  $header .= '<!-- eof autocomplete --> ' . "\n";

      $oscTemplate->addBlock( $header, 'header_tags' );	
*/	  
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SEARCH_STATUS');
    }

    function install() {
	  global $multi_stores_config;
	  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Search Module', 'MODULE_BOXES_SEARCH_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SEARCH_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SEARCH_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_SEARCH_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");
      }

    function remove() {
	  global $multi_stores_config;
	  
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_SEARCH_STATUS', 'MODULE_BOXES_SEARCH_CONTENT_PLACEMENT', 'MODULE_BOXES_SEARCH_SORT_ORDER', 'MODULE_BOXES_SEARCH_DISPLAY_PAGES');
    }
  }
?>