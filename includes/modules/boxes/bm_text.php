<?php
/*$Id: bm_text.php v1.0.2 $
  $Loc: catalog/includes/modules/boxes/ $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2011 osCommerce
  Released under the GNU General Public License
*/
  class bm_text {
    var $code = 'bm_text';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;

    function bm_text() {
      $this->title = MODULE_BOXES_TEXT_TITLE;
      $this->description = MODULE_BOXES_TEXT_DESCRIPTION;

      if ( defined('MODULE_BOXES_TEXT_STATUS') ) {
        $this->sort_order = MODULE_BOXES_TEXT_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_TEXT_STATUS == 'True');
		$this->pages = MODULE_BOXES_TEXT_CONTENT_DISPLAY_PAGES;

       // $this->group = ((MODULE_BOXES_TEXT_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
     $placement = MODULE_BOXES_TEXT_CONTENT_PLACEMENT;
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
      global $language, $oscTemplate;

    	$content = constant( 'MODULE_BOXES_TEXT_CONTENT_' . strtoupper( $language ) );
      if (tep_not_null($content)) {

		$title_text = False ;
        if( tep_not_null( constant( 'MODULE_BOXES_TEXT_TITLE_' . strtoupper( $language ) ) ) ) {
		  $title_text = True ;

          if( tep_not_null( MODULE_BOXES_TEXT_TITLE_LINK ) ) {
            $text_heading .= '   <a class="label label-info" href="' . tep_href_link(MODULE_BOXES_TEXT_TITLE_LINK) . '">';
          }

          $text_heading .= constant( 'MODULE_BOXES_TEXT_TITLE_' . strtoupper( $language ) );

          if( tep_not_null( MODULE_BOXES_TEXT_TITLE_LINK ) ) {
            $text_heading .= '</a>';
          }
        } // if( tep_not_null( 'MODULE_BOXES_TEXT_TITLE_
	
        $text_body_align = 'text-' . MODULE_BOXES_TEXT_CONTENT_ALIGNMENT ;

        if( tep_not_null( MODULE_BOXES_TEXT_CONTENT_LINK ) ) {
          $text_contents .= '    <a href="' . tep_href_link(MODULE_BOXES_TEXT_CONTENT_LINK) . '">';
        }

        $text_contents .= $content . PHP_EOL;

        if( tep_not_null( MODULE_BOXES_TEXT_CONTENT_LINK ) ) {
          $text_contents .= '</a>';
        }
		
	    ob_start();
          include(DIR_WS_MODULES . 'boxes/templates/text.php');
        $data =  ob_get_clean();

        $oscTemplate->addBlock( $data , $this->group );
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_TEXT_STATUS');
    }

    function install() {
	  global $multi_stores_config;
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }	  
	
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Generic Module', 'MODULE_BOXES_TEXT_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_TEXT_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '1', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_TEXT_CONTENT_PLACEMENT', 'Left Column', 'Where should the module be placed : ', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Title Link', 'MODULE_BOXES_TEXT_TITLE_LINK', 'index.php', 'Link the title of the box to a page (Leave empty for no link.)', '6', '3', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Content Link', 'MODULE_BOXES_TEXT_CONTENT_LINK', 'index.php', 'Link the contents of the box to a page (Leave empty for no link.)', '6', '4', now())");
      tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Alignment', 'MODULE_BOXES_TEXT_CONTENT_ALIGNMENT', 'left', 'Align the module contents to the left, center, or right?', '6', '5', 'tep_cfg_select_option(array(\'left\', \'center\', \'right\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_TEXT_CONTENT_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	  

    	foreach( $this->languages_array as $language_name ) {
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ( '" . ucwords( $language_name ) . " Title', 'MODULE_BOXES_TEXT_TITLE_" . strtoupper( $language_name ) . "', 'Generic Title', 'Enter the title that you want on your box in " . $language_name . "', '6', '10', now())" );
        tep_db_query( "insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ( '" . ucwords( $language_name ) . " Contents', 'MODULE_BOXES_TEXT_CONTENT_" . strtoupper( $language_name ) . "', 'Generic Contents', 'Enter the contents that you want in your box in " . $language_name . "', '6', '20', 
		'tep_draw_textarea_ckeditor(\'configuration[MODULE_BOXES_TEXT_CONTENT_" . strtoupper( $language_name ) . "]\', false, 115, 100, tep_get_config_value( MODULE_BOXES_TEXT_CONTENT_" . strtoupper( $language_name ) . " ),  tep_get_text_class() , ' , now())" );
		
//		'tep_draw_textarea_field(\'configuration[MODULE_BOXES_TEXT_CONTENT_" . strtoupper($language_name) . "]\', false, 35, 20, ', now())" );
      }
    }

    function remove() {
	  global $multi_stores_config;
	
      tep_db_query( "delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      include_once( DIR_WS_CLASSES . 'language.php' );
      $bm_banner_language_class = new language;
      $languages = $bm_banner_language_class->catalog_languages;

      foreach( $languages as $this_language ) {
        $this->languages_array[$this_language['id']] = $this_language['directory'];
      }

    	$keys = array();

    	$keys[] = 'MODULE_BOXES_TEXT_STATUS';
    	$keys[] = 'MODULE_BOXES_TEXT_SORT_ORDER';
    	$keys[] = 'MODULE_BOXES_TEXT_TITLE_LINK';
    	$keys[] = 'MODULE_BOXES_TEXT_CONTENT_LINK';
    	$keys[] = 'MODULE_BOXES_TEXT_CONTENT_PLACEMENT';
    	$keys[] = 'MODULE_BOXES_TEXT_CONTENT_ALIGNMENT';
	

    	foreach( $this->languages_array as $language_name ) {
    	  $keys[] = 'MODULE_BOXES_TEXT_TITLE_' . strtoupper( $language_name );
    	  $keys[] = 'MODULE_BOXES_TEXT_CONTENT_' . strtoupper( $language_name );
    	}
		$keys[] = 'MODULE_BOXES_TEXT_CONTENT_DISPLAY_PAGES' ;	
      return $keys;
    }
  }
?>