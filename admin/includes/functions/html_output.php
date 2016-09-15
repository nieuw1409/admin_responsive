<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    $page = tep_output_string($page);

    if ($page == '') {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><strong>Error!</strong></font><br /><br /><strong>Unable to determine the page link!<br /><br />Function used:<br /><br />tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</strong>');
    }
    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_ADMIN;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL_CATALOG == 'true') {
        $link = HTTPS_SERVER . DIR_WS_ADMIN;
      } else {
        $link = HTTP_SERVER . DIR_WS_ADMIN;
      }
    } else {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><strong>Error!</strong></font><br /><br /><strong>Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL<br /><br />Function used:<br /><br />tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</strong>');
    }
    if ($parameters == '') {
      $link = $link . $page . '?' . SID;
    } else {
      $link = $link . $page . '?' . tep_output_string($parameters) . '&' . SID;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }

  function tep_catalog_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
    if ($connection == 'NONSSL') {
      $link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL_CATALOG == 'true') {
// bof 2.3.3.2	  
//        $link = HTTPS_CATALOG_SERVER . DIR_WS_CATALOG;
        $link = HTTPS_CATALOG_SERVER . (defined('DIR_WS_HTTPS_CATALOG') ? DIR_WS_HTTPS_CATALOG : DIR_WS_CATALOG);
// 2.3.3.2		
      } else {
        $link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><strong>Error!</strong></font><br /><br /><strong>Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL<br /><br />Function used:<br /><br />tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</strong>');
    }
    if ($parameters == '') {
      $link .= $page;
    } else {
      $link .= $page . '?' . $parameters;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }
// Output a form input field
  function tep_draw_bs_input_date($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $placeholder = NULL ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    global $_GET, $_POST ;
    $field = '<input type="' . tep_output_string($type) . '" 
	                 data-provide="datepicker"  
					 class="form-control"  
					 name="' . tep_output_string($name) . '" 
	                 data-date-format="' . DOB_FORMAT_STRING . '"                      
					 ';

    if ( ($reinsert_value == true) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $value = stripslashes($_GET[$name]);
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $value = stripslashes($_POST[$name]);
      }
    }

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }
	
    if (tep_not_null($placeholder)) {	
	        $field .= ' placeholder="' . $placeholder . '"' ;
	}

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

////
// The HTML image wrapper function
// added bootstrap classes for responsivness with ability to set class to false
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '', $responsive = true, $bootstrap_css = '') {
    $image = '<img src="' . tep_output_string($src) . '" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . '"'; // 2.3.3
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }
	
    $image .= ' class="';

    if (isset($responsive) && ($responsive === true)) {
      $image .= 'img-responsive';
    }

    if (tep_not_null($bootstrap_css)) $image .= ' ' . $bootstrap_css;

    $image .= '"';
	
    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';

    return $image_submit;
  }

  function tep_image_bs_submit( $glyph= 'ok', $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" ' . tep_glyphicon($glyph, '', $parameters) . ' border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';

    return $image_submit;
  }
  
////
// Draw a 1 pixel black line
  function tep_black_line() {
    return tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $params = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $params);
  }

////
// javascript to dynamically update the states/provinces list when the country is changed
// TABLES: zones
  function tep_js_zone_list($country, $form, $field) {
    $countries_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
    $num_country = 1;
    $output_string = '';
    while ($countries = tep_db_fetch_array($countries_query)) {
      if ($num_country == 1) {
        $output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      } else {
        $output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      }

      $states_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $countries['zone_country_id'] . "' order by zone_name");

      $num_state = 1;
      while ($states = tep_db_fetch_array($states_query)) {
        if ($num_state == '1') $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
        $output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
        $num_state++;
      }
      $num_country++;
    }
    $output_string .= '  } else {' . "\n" .
                      '    ' . $form . '.' . $field . '.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
                      '  }' . "\n";

    return $output_string;
  }

////
// Output a form
  function tep_draw_form($name, $action, $parameters = '', $method = 'post', $params = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="';
    if (tep_not_null($parameters)) {
      $form .= tep_href_link($action, $parameters);
    } else {
      $form .= tep_href_link($action);
    }
    $form .= '" method="' . tep_output_string($method) . '"';
    if (tep_not_null($params)) {
      $form .= ' ' . $params;
    }
    $form .= '>';

    return $form;
  }

// Output a form
  function tep_draw_bs_form($name, $action, $parameters = '', $method = 'post', $params = '', $id='id_form') {
    $form = '<form name="' . tep_output_string($name) . '" action="';
    if (tep_not_null($parameters)) {
      $form .= tep_href_link($action, $parameters);
    } else {
      $form .= tep_href_link($action);
    }
    $form .= '" method="' . tep_output_string($method) . '"';
    if (tep_not_null($params)) {
      $form .= ' ' . $params;
    }
	
    if (tep_not_null($id)) {
      $form .= ' id="' . tep_output_string($id) . '"' ;
    }	
    $form .= ' accept-charset="utf8">';

    return $form;
  }
  
////
// Output a form input field
// ripped from the catalog side for bootstrap changes
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $class = 'form-control', $id='id_input' ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '" $id="' . tep_output_string($id) . '"';

    if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $value = stripslashes($HTTP_GET_VARS[$name]);
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $value = stripslashes($HTTP_POST_VARS[$name]);
      }
    }

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    if (tep_not_null($class)) $field .= ' class="' . $class . '"';

    $field .= ' />';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

  function tep_draw_bs_input_field($name, $value = '', $label = '', $id='id_input' ,$label_class='', $input_class ='', $label_lft_rght = 'left', $placeholder='', $parameters = '', $required = false,  $text_required = '', $type = 'text', $reinsert_value = true, $class_hidden = null ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
	
	$placeholder = str_replace( ':', '', $placeholder ) ;
	
	if ( $label_lft_rght === 'left') {
       $field .= '<label for="' . tep_output_string($id). '" class="' . tep_output_string($label_class) . '">' . PHP_EOL ;		
       $field .= tep_output_string($label) . PHP_EOL ;
       $field .= '</label>' . PHP_EOL ;	   
	}	

    if (tep_not_null($input_class)) {
      $field .= '<div class="' . tep_output_string($input_class) . '">' . PHP_EOL ;
    }	

    $field .= '<input class="form-control" ' . $class_hidden . ' type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '" id="' . tep_output_string($id) . '" placeholder="' . tep_output_string($placeholder) . '"';

    if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $value = stripslashes($HTTP_GET_VARS[$name]);
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $value = stripslashes($HTTP_POST_VARS[$name]);
      }
    }

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
	
	if ( $required == true ) $field .= ' required aria-required="true" aria-describedby="' . tep_output_string($id). '_text_output' . '"' ;

    if (tep_not_null($class)) $field .= ' class="' . $class . '"';

    $field .= ' />'. PHP_EOL ;
	
    if (tep_not_null($input_class)) {
      $field .= '</div>' . PHP_EOL ;
    }
	
	if ( ( $required == true )	&& ( tep_not_null($text_required)) ) {		
	   $field .= '<span id="' . tep_output_string($id). '_text_output' . '">' . tep_output_string($text_required) . '</span>' ;
	}
	
	if ( $label_lft_rght === 'right') {
       $field .= '<label for="' . tep_output_string($id). '" class="' . $label_class . '">' . PHP_EOL ;		
       $field .= tep_output_string($label) . PHP_EOL ;
       $field .= '</label>' . PHP_EOL ;	 
	}	

//    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
  
////
// Output a form password field
// slightly modified to go with the new input_field changes
  function tep_draw_password_field($name, $value = '', $parameters = '') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

  function tep_draw_bs_password_field($name, $value = '', $label = '', $id='id_input_pw' ,$label_class='', $input_class ='', $label_lft_rght = 'left', $placeholder='', $parameters = '', $required = false,  $text_required = '') {
//    return tep_draw_input_field($name, $value, $parameters, 'password', false);
      return tep_draw_bs_input_field($name, $value, $label, $id ,$label_class, $input_class, $label_lft_rght , $placeholder, $parameters , $required,  $text_required , $type = 'password', false) ;
	
  }
  
////
// Output a form filefield
// Also modified to go with the new input_field changes plus added some markup to apply a nice Bootstrap button style
  function tep_draw_file_field($name, $value = '', $parameters = '') {
	$field =  '<div class="file-wrapper">';
    $field .=  tep_draw_input_field($name, $value, '', 'file');
	$field .= '<span class="btn btn-default btn-sm">' . tep_glyphicon('floppy-open') . TEXT_BROWSE . '</span>';
    $field .= '</div>';
    return $field;
  }

  function tep_draw_bs_file_field($name, $value = '', $label = '', $id='id_input' ,$label_class='', $input_class ='', $label_lft_rght = 'left', $placeholder='', $parameters = '', $required = false,  $text_required = '') {
	$field =  '<div class="file-wrapper">';
//    $field .=  tep_draw_input_field($name, $value, '', 'file');
	$field .=  tep_draw_bs_input_field($name, $value, $label, $id ,$label_class, $input_class, $label_lft_rght, $placeholder, $parameters, $required ,  $text_required , 'file' ) ;
	$field .= '<br /><span class="btn btn-default btn-sm">' . tep_glyphicon('floppy-open') . TEXT_BROWSE . '</span>';
    $field .= '</div>';
    return $field;
  }
  
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $hidden = false ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
	
	if ( $hidden === true ) $class_hidden = " hidden " ;

    $selection = '<input class="form-control ' . $class_hidden . '" type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) || (tep_not_null($compare) && ($value == $compare)) ) {
      $selection .= ' checked="checked"';
    }

    $selection .= ' />';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $hidden = false) {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $hidden);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $hidden = false) {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $hidden);
  }
  
  
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_bs_selection_field($name, $type, $value = '', $label = '', $id, $checked = false, $radio_check_class='', $label_class='', $input_class ='', $label_lft_rght = 'left', $parameters = '', $hidden = false ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
	
	if ( $hidden === true ) $class_hidden = " hidden " ;

 
	if ( $label_lft_rght === 'left') {
       $selection .= '<label for="' . tep_output_string($id) . '" class="' . $label_class . $column_label . '">' . PHP_EOL ;		
       $selection .= $label . PHP_EOL ;
       $selection .= '</label>' . PHP_EOL ;	   
	}
    
	if (tep_not_null($radio_check_class)) {
		   $selection .= ' <div class="' . $radio_check_class . '">' . PHP_EOL ;	 
	}
	
    $selection .= '<input class="form-control ' . $class_hidden . $input_class . '" type="' . tep_output_string($type) . '" id="' . tep_output_string($id) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) || (tep_not_null($compare) && ($value == $compare)) ) {
      $selection .= ' checked="checked"' ;	 
    }
    
	if (tep_not_null($parameters)) $selection .= ' ' . $parameters ; 	 
	
    $selection .= ' />';
	
	if ( $label_lft_rght === 'right') {
       $selection .= '<label for="' . tep_output_string($id) . '" class="' . $label_class . $column_label . '">' . PHP_EOL ;		
       $selection .= $label . PHP_EOL ;
       $selection .= '</label>' . PHP_EOL ;	 
	}
	
    if (tep_not_null($radio_check_class)) {
		 $selection .= '</div>' . PHP_EOL ;	 
    }	
 
    return $selection;
  }
  
////
// Output a form checkbox field
  function tep_bs_checkbox_field($name, $value = '', $label = '', $id = 'id_check_box', $checked = false, $checkbox_class= '', $label_class='', $input_class ='', $label_lft_rght = 'right', $parameters = '', $hidden = false) {
    return tep_bs_selection_field($name, 'checkbox', $value, $label , $id, $checked, $checkbox_class,$label_class, $input_class, $label_lft_rght, $parameters, $hidden);
  }

////
// Output a form radio field
  function tep_bs_radio_field($name, $value = '', $label = '', $id = 'id', $checked = false, $radio_class='', $label_class='', $input_class ='', $label_lft_rght = 'left', $parameters = '', $hidden = false) {
    return tep_bs_selection_field($name, 'radio', $value, $label , $id, $checked, $radio_class, $label_class, $input_class , $label_lft_rght, $parameters, $hidden);
  }
  

////
// Output a form textarea field
// The $wrap parameter is no longer used in the core xhtml template
// added form-control class for use with bootstrap
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<textarea class="form-control" name="' . tep_output_string($name) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $field .= tep_output_string_protected(stripslashes($HTTP_GET_VARS[$name]));
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $field .= tep_output_string_protected(stripslashes($HTTP_POST_VARS[$name]));
      }
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }
  
  function tep_draw_textarea_ckeditor($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS, $language_code;

    $field = '<textarea  class="form-control ckeditor" name="' . tep_output_string($name) . '" style="width:' . tep_output_string($width) . 'px;height:' . tep_output_string($width) . 'px" rows="40" cols="100" ';
	

	
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $field .= tep_output_string_protected(stripslashes($HTTP_GET_VARS[$name]));
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $field .= tep_output_string_protected(stripslashes($HTTP_POST_VARS[$name]));
      }
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';
        $field .=     '<script type="text/javascript">
        CKEDITOR.replace( \''.tep_output_string($name).'\',
    {	  
      filebrowserBrowseUrl : \''.DIR_WS_ADMIN.'ckeditor/elfinder/elfinder.html?editor=ckeditor\',
      filebrowserImageBrowseUrl : \''.DIR_WS_ADMIN.'ckeditor/elfinder/elfinder.html?editor=ckeditor&filter=image\',
      filebrowserFlashBrowseUrl : \''.DIR_WS_ADMIN.'ckeditor/elfinder/elfinder.html?editor=ckeditor&filter=flash\',        
    });

            </script>';
//      filebrowserBrowseUrl : \''.DIR_WS_ADMIN.'ckeditor/filemanager/index.php?editor=ckeditor\',
//      filebrowserImageBrowseUrl : \''.DIR_WS_ADMIN.'ckeditor/filemanager/index.php?editor=ckeditor&filter=image\',
//      filebrowserFlashBrowseUrl : \''.DIR_WS_ADMIN.'ckeditor/filemanager/index.php?editor=ckeditor&filter=flash\',  			
//        filebrowserBrowseUrl : \''.DIR_WS_ADMIN.'ckfinder/ckfinder.html\',
//        filebrowserImageBrowseUrl : \''.DIR_WS_ADMIN.'ckfinder/ckfinder.html?Type=Images\',
//        filebrowserUploadUrl : \''.DIR_WS_ADMIN.'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&currentFolder='.DIR_FS_CATALOG.'images/\',
//        filebrowserImageUploadUrl : \''.DIR_WS_ADMIN.'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=/userfiles/\'
    return $field;
  }
    

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    global $_GET, $_POST;	

    $field = '<input class="form-control" type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) {
      if ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($HTTP_GET_VARS[$name])) . '"';
      } elseif ( (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($HTTP_POST_VARS[$name])) . '"';
      }
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    $string = '';

    if (defined('SID') && tep_not_null(SID)) {
      $string = tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }

    return $string;
  }

////
// Output a form pull down menu
// added Bootstrap & select picker classes
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
	
	$field .= ' class="form-control selectpicker show-tick">';

    if (empty($default) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $default = stripslashes($HTTP_GET_VARS[$name]);
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $default = stripslashes($HTTP_POST_VARS[$name]);
      }
    }

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

// Output a form pull down menu
// added Bootstrap & select picker classes tep_bs_radio_field($name, $value = '', $label = '', $id, $checked = false, $radio_class='', $label_class='', $input_class ='', $label_lft_rght = 'left', $hidden = false)
  function tep_draw_bs_pull_down_menu($name, $values, $default = '', $label = '', $id = 'id_id', $input_class= '', $select_class=' selectpicker show-tick ', $label_class='', $label_lft_rght = 'left', $parameters = '', $required = false, $search = false) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
	
	if ($search == true ) {
		$select_search = 'true' ;
	} else {
		$select_search = 'false' ;		
	}
	
	if ( $label_lft_rght === 'left') {
       $field .= '<label for="' . tep_output_string($id) . '" class="' . $label_class . '">' . PHP_EOL ;		
       $field .= $label . PHP_EOL ;
       $field .= '</label>' . PHP_EOL ;	   
	}
	
    if (tep_not_null($input_class)) {
      $field .= '<div class="' . tep_output_string($input_class) . '">' . PHP_EOL ;
    }	
	
    $field .= '<select  class="form-control ' . tep_output_string($select_class) . '" data-live-search="' . $select_search . '" name="' . tep_output_string($name) . '" id="' . tep_output_string($id) . '"' ;//  data-width="100%"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	
	$field .= ' >';

    if (empty($default) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $default = stripslashes($HTTP_GET_VARS[$name]);
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $default = stripslashes($HTTP_POST_VARS[$name]);
      }
    }

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
	  if  ( is_array( $default) ) {
		 if ( in_array($values[$i]['id'], $default, true) ) {
           $field .= ' selected="selected"';			 
		 }
	  } else {
        if ($default == $values[$i]['id']) {
          $field .= ' selected="selected"';
        }
	  }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>'. PHP_EOL ;
    }
    $field .= '</select>';
	
    if (tep_not_null($input_class)) {
      $field .= '</div> <!-- input class -->'. PHP_EOL ;
    }		
	
	if ( $label_lft_rght === 'right') {
       $field .= '<label class="' . $label_class . '">' . PHP_EOL ;		
       $field .= $label . PHP_EOL ;
       $field .= '</label>' . PHP_EOL ;	   
	}	

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
  
////
// Output a jQuery UI Button
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null) {
    static $button_counter = 1;

    $types = array('submit', 'button', 'reset');

    if ( !isset($params['type']) ) {
      $params['type'] = 'submit';
    }

    if ( !in_array($params['type'], $types) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    if (!isset($priority)) {
      $priority = 'secondary';
    }

    $button = '<span class="tdbLink">';

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a id="tdb' . $button_counter . '" href="' . $link . '"';

      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank"';
      }
    } else {
      $button .= '<button id="tdb' . $button_counter . '" type="' . tep_output_string($params['type']) . '"';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= '>' . $title;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    $button .= '</span><script type="text/javascript">$("#tdb' . $button_counter . '").button(';

    $args = array();

    if ( isset($icon) ) {
      if ( !isset($params['iconpos']) ) {
        $params['iconpos'] = 'left';
      }

      if ( $params['iconpos'] == 'left' ) {
        $args[] = 'icons:{primary:"ui-icon-' . $icon . '"}';
      } else {
        $args[] = 'icons:{secondary:"ui-icon-' . $icon . '"}';
      }
    }

    if (empty($title)) {
      $args[] = 'text:false';
    }

    if (!empty($args)) {
      $button .= '{' . implode(',', $args) . '}';
    }

    $button .= ').addClass("ui-priority-' . $priority . '").parent().removeClass("tdbLink");</script>';

    $button_counter++;

    return $button;
  }

////
// Output a glyphicon
/* 
EX: tep_glyphicon('ok => then any other class needed here', 'success', 'style="whatever needed"');
Just choose the glyphicon name following "glyphicon-"; other classes such as pull-left, rotate etc; can be added after - choose from default bootstrap contextual helper classes for colors: muted, primary, success, info, warning, danger - leave blank to use same color as surrounding text (default);

References: admin/ext/stylesheet.css - Bootsrap Glyphicon helpers section
            http://getbootstrap.com/components/#glyphicons-glyphs
			http://getbootstrap.com/css/#helper-classes-colors 
*/
  function tep_glyphicon($glyph, $color = '', $parameters = '') {
	$icon = '';
//	$icon .= '<i class="glyphicon glyphicon-' . $glyph;
	$icon .= '<span class="' . glyphicon_icon_to_fontawesome( $glyph ) ;	
	if (tep_not_null($color)) $icon .= ' text-' . $color;
	$icon .= '"';
	if (tep_not_null($parameters)) $icon .= ' ' . $parameters;
	$icon .= '></span>&nbsp;';
	
	return $icon;  
  }
  
////
// Output a Bootstrap Button name
// took from the bootstrapped catalog side and removed priority and added parameters
  function tep_draw_bs_button($title = null, $icon = null, $link = null, $parameters = null, $params = null, $style = null, $name='btn_name', $id = '' ) {
    static $button_counter = 1;

    $types = array('submit', 'button', 'reset');

    if ( !isset($params['type']) ) {
      $params['type'] = 'submit';
    }

    if ( !in_array($params['type'], $types) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    $button = NULL;

    if ( ($params['type'] == 'button') && isset($link) ) {
	  if (tep_not_null($id)) {
//		  $button .= ' name="' . $name . '"';
         $button .= '<a id="' . $id . '" href="' . $link . '"';
	  } else {	  
         $button .= '<a id="btn' . $button_counter . '" href="' . $link . '"';
      }		 
      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank"';
      }
    } else {
      $button .= '<button ';
      $button .= ' type="' . tep_output_string($params['type']) . '"';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= ' class="btn ';

    $button .= (isset($style)) ? $style : 'btn-default';

    $button .= '"';
	
	$button .= (isset($parameters)) ?  ' ' . $parameters : '';
	
	if (tep_not_null($name)) $button .= ' name="' . $name . '"';		
	
	$button .= '>';	

    if (isset($icon) && tep_not_null($icon)) {
      $button .=  tep_glyphicon($icon);
    }

    $button .= $title;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    $button_counter++;

    return $button;
  } 
////
// review stars
// took from the bootstraped catalog 
  function tep_draw_stars($rating = 0) {
    $stars =  str_repeat(tep_glyphicon('star','info'), (int)$rating);
    $stars .= str_repeat(tep_glyphicon('star-empty','muted'), 5-(int)$rating);
    return $stars;
  }
  
////
// Output a Bootstrap Glyphicon Button
  function tep_glyphicon_button($title = null, $icon = null, $link = null, $style = null, $color = null, $size = null, $parameters = null) {

    $glyphbtn = '';

    if (isset($link) ) {
      $glyphbtn .= '<a role="button" href="' . $link . '"';
	  
    } else {
      $glyphbtn .= '<button type="button"';
    }

    $glyphbtn .= ' class="btn-glyphicon btn ';
    
    $glyphbtn .= (isset($style)) ? $style : 'btn-default ';
	
	$glyphbtn .= (isset($size)) ? $size : '';

    $glyphbtn .= '"';
	
	if (tep_not_null($parameters)) $glyphbtn .= ' ' . $parameters;
	
	$glyphbtn .= ' title="' . $title . '"';
	
	$glyphbtn .= '>';

    if (isset($icon) && tep_not_null($icon)) {
      $glyphbtn .=  str_replace('&nbsp;', '', tep_glyphicon($icon,(isset($color)) ? $color : ''));
    }
    if (isset($link) ) {
      $glyphbtn .= '</a>';
    } else {
      $glyphbtn .= '</button>';
    }
    return $glyphbtn;
  }
?>
