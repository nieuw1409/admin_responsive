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
  /**
  * ULTIMATE Seo Urls 5 PRO by FWR Media
  * Replacement for osCommerce href link wrapper function
  */
  require_once( DIR_WS_MODULES . 'ultimate_seo_urls5/main/usu5.php' );
  
  function tep_href_link( $page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true, $target_language_code = false ) {
    return Usu_Main::i()->hrefLink( $page, $parameters, $connection, $add_session_id, $search_engine_safe, $target_language_code );
  }
  
////
// The HTML image wrapper function
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '', $responsive = true, $bootstrap_css = '') {
    // If width and height are not numeric then we can't do anything with it
    if ( !is_numeric ( $width ) || !is_numeric ( $height ) ) return tep_image_legacy( $src, $alt, $width, $height, $parameters, $responsive, $bootstrap_css = '' );
    require_once DIR_WS_MODULES . 'kiss_image_thumbnailer/classes/Image_Helper.php';
    $attributes = array( 'alt' => $alt, 'width' => $width, 'height' => $height );
    $image = new Image_Helper( array( 'src'                   => $src,
                                      'attributes'            => $attributes,
                                      'parameters'            => $parameters,
                                      'default_missing_image' => DIR_WS_IMAGES . 'no_image_available_150_150.gif',
                                      'isXhtml'               => true,
                                      'thumbs_dir_path'       => DIR_WS_IMAGES . 'thumbs/',
                                      'thumb_quality'         => 75,
                                      'responsive'            => $responsive,  // responsive design									  
                                      'bootstrap_css'         => $bootstrap_css,  // responsive design										  
                                      'thumb_background_rgb' => array( 'red'   => 255,
                                                                       'green' => 255,
                                                                       'blue'  => 255 ) ) );
    if ( false === $image_assembled = $image->assemble() ) {
      return tep_image_legacy( $src, $alt, $width, $height, $parameters, $responsive, $bootstrap_css );
    }
    return $image_assembled;
  } // end function
  
////
// The HTML image wrapper function
  function tep_image_legacy($src, $alt = '', $width = '', $height = '', $parameters = '', $responsive = true, $bootstrap_css = '') {
  

    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . '"'; // 2.3.3
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = intval($image_size[0] * $ratio);
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = intval($image_size[1] * $ratio);
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"  itemprop="image"';
    }
	
// bof responsive design
    $image .= ' class="';

    if ( tep_not_null($responsive) && ( $responsive === true)) {
      $image .= 'img-responsive' ;
    }

    if (tep_not_null($bootstrap_css)) $image .= ' ' . $bootstrap_css;

    $image .= '"';
	
// eof responsive design	

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '', $tokenize = false) {
    global $sessiontoken;

    $form = '<form role="form" name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" id="' . tep_output_string($name) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    if ( ($tokenize == true) && isset($sessiontoken) ) {
      $form .= '<input type="hidden" name="formid" value="' . tep_output_string($sessiontoken) . '" id="' . tep_output_string($sessiontoken) . '"/>';
    }

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $placeholder = '' ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<input type="' . tep_output_string($type) . '" class="form-control" name="' . tep_output_string($name) . '"';

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

    $field .= ' />';

    return $field;
  }

  function tep_bootstrap_form_input_field($name,                       // name input
                                          $value = '',                 // value
										  $id = '',                    // id
										  $placeholder = '' ,          // initial text
										  $label = '',                 // label
										  $label_columns = '',         // columns label
										  $columns = '',               // columns input
										  $size_control = '',          // size of input
										  $parameters = '',            // parameters
										  $help_text = '',             // help text input
										  $validation_states = '',     // validation states exampl has-success
										  $disabled = false,           // disabled input
										  $readonly = '',              // readonly input
										  $type = 'text',              // type of input text password html 5
										  $reinsert_value = true) {    // reinsert value
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$field = '<div class="form_group ' . $validation_states . '">' ; 
	
	if (tep_not_null($label)) {
	  if (tep_not_null($validation_states)) {	
	    $field .= '<label class="control-label ' . $label_columns . '" for="' . $id . '">' . $label . '</label>' ;	
	  } else {
	    $field .= '<label class="' . $label_columns . '" for="' . $id . '">' . $label . '</label>' . PHP_EOL ;			
	  }
    }		
	$field .= '<div class="' . $columns . '">' ;
	$field .= '  <input type="' . tep_output_string($type) . '" class="form-control" size="' . $size_control . '"  id="' . $id . '" name="' . tep_output_string($name) . '" ';	
	if (tep_not_null($help_text)) {	
      $field .= '<p class="help-block">' . $help_text . '</p>' ;
	}
//    $field .= '<input type="' . tep_output_string($type) . '" class="form-control" name="' . tep_output_string($name) . '"';

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
	
    if (tep_not_null($placeholder)) {
      $field .= ' placeholder="' . tep_output_string($placeholder) . '"';
    }		

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';   // end input type
    $field .= '</div>';	 // end class columns	
	
    $field .= '</div>';	 // end form group
	

    return $field;
  }  

////
// Output a form input field
  function tep_draw_input_date($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $placeholder = NULL ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<input type="' . tep_output_string($type) . '" 
	                 data-provide="datepicker"  
					 class="form-control"  
					 name="' . tep_output_string($name) . '" 
	                 data-date-format="' . DOB_FORMAT_STRING . '"                      
					 ';

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
	
    if (tep_not_null($placeholder)) {	
	        $field .= ' placeholder="' . $placeholder . '"' ;
	}

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a form checkbox field
  function tep_bootstrap_checkbox_field($name, $id = '', $value = '', $label = '', $checked = false, $disabled= false, $parameters = '') {
	$selection  = '<div class="checkbox checkbox-success">' . PHP_EOL ;
	$selection .= '   <label>' . PHP_EOL;
    $selection .= '      <input type="checkbox" class="form-control checkbox checkbox-success" name="' . tep_output_string($name) . '" id="' . tep_output_string($id) . '"';

	$selection .= ' >' . PHP_EOL;

    if (tep_not_null($label)) $selection .= ' ' . $label . PHP_EOL;		
	
    $selection .= '   </label>' . PHP_EOL;

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '</div>' . PHP_EOL;	

    return $selection;  
  }
  
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_bs_selection_field($name, $type, $value = '', $label = '', $id, $checked = false, $radio_check_class='', $label_class='', $input_class ='', $label_lft_rght = 'left', $parameters = '', $hidden = false ) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
	
	if ( $hidden === true ) $class_hidden = " hidden " ;

 
	if ( $label_lft_rght === 'left') {
       $selection .= '<label for="' . tep_output_string($name) . '" class="' . $label_class . $column_label . '">' . PHP_EOL ;		
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
       $selection .= '<label for="' . tep_output_string($name) . '" class="' . $label_class . $column_label . '">' . PHP_EOL ;		
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
  function tep_bs_checkbox_field($name, $value = '', $label = '', $id = 'id_id', $checked = false, $checkbox_class= '', $label_class='', $input_class ='', $label_lft_rght = 'right', $parameters = '', $hidden = false) {
    return tep_bs_selection_field($name, 'checkbox', $value, $label , $id, $checked, $checkbox_class,$label_class, $input_class, $label_lft_rght, $parameters, $hidden);
  }

////
// Output a form radio field
  function tep_bs_radio_field($name, $value = '', $label = '', $id = 'id_id', $checked = false, $radio_class='', $label_class='', $input_class ='', $label_lft_rght = 'right', $parameters = '', $hidden = false) {
    return tep_bs_selection_field($name, 'radio', $value, $label , $id, $checked, $radio_class, $label_class, $input_class , $label_lft_rght, $parameters, $hidden);
  }
    
  
////
// Output a form radio field
  function tep_bootstrap_radio_field($name, $id = '', $value = '', $label = '', $checked = false, $disabled = false, $parameters = '') {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$selection  = '<div class="radio">' . PHP_EOL ;
	$selection .= '   <label>' . PHP_EOL;
    $selection .= '      <input type="radio" name="' . tep_output_string($name) . '" id="' . tep_output_string($id) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) ) {
      $selection .= ' checked>' . PHP_EOL;
	} else {
	  $selection .= ' >' . PHP_EOL;
    }
	
   if (tep_not_null($label)) $selection .= ' ' . $label . PHP_EOL;	

    $selection .= '   </label>' . PHP_EOL;

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '</div>' . PHP_EOL;	

    return $selection;  
  }

// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $selection = '<input class="form-control" type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) ) {
      $selection .= ' checked="checked"';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= ' >';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }
  
////
// Output a form textarea field
// The $wrap parameter is no longer used in the core xhtml template
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<textarea name="' . tep_output_string($name) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';
	
	$field .= ' class="form-control"' ;

    if (tep_not_null($parameters)) $field .= ' '  . $parameters;

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
  
  function tep_bootstrap_form_textarea(   $name,                       // name input
                                          $value = '',                 // value
										  $id = '',                    // id
										  $placeholder = '' ,          // initial text
										  $label = '',                 // label
										  $label_columns = '',         // columns label
										  $columns = '',               // columns input
										  $width = 45,                 // width of texarea
										  $height = 10,                // height of texarea
										  $parameters = '',            // parameters
										  $help_text = '',             // help text input
										  $validation_states = '',     // validation states exampl has-success
										  $disabled = false,           // disabled input
										  $readonly = '',              // readonly input 										  
										  $reinsert_value = true) {    // reinsert value
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

	$field = '<div class="form_group ' . $validation_states . '">' ; 
	
	if (tep_not_null($label)) {
	  if (tep_not_null($validation_states)) {	
	    $field .= '<label class="control-label ' . $label_columns . '" for="' . $id . '">' . $label . '</label>' ;	
	  } else {
	    $field .= '<label class="' . $label_columns . '" for="' . $id . '">' . $label . '</label>' . PHP_EOL ;			
	  }
    }		
	$field .= '<div class="' . $columns . '">' ;
	$field .= ' <textarea class="form-control"  cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '" id="' . $id . '" name="' . tep_output_string($name) . '" ';	
	if (tep_not_null($help_text)) {	
      $field .= '<p class="help-block">' . $help_text . '</p>' ;
	}
//    $field .= '<input type="' . tep_output_string($type) . '" class="form-control" name="' . tep_output_string($name) . '"';

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
	
    if (tep_not_null($placeholder)) {
      $field .= ' placeholder="' . tep_output_string($placeholder) . '"';
    }		

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' ></textarea>';   // end input type
    $field .= '</div>';	 // end class columns	
	
    $field .= '</div>';	 // end form group
	

    return $field;
  }  
 

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

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
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

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

  function tep_bootstrap_pull_down_menu($name, $values, $id='', $default = '', $parameters = '', $required = false, $search = false) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<select class="form-control selectpicker show-tick" name="' . tep_output_string($name) . '" id="' . tep_output_string($id) . '" data-live-search="' . tep_output_string($search) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

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
  
////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '', $id= 'id_country_list' ) {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT ));
    $countries = tep_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_bootstrap_pull_down_menu($name, $countries_array, $id, $selected, $parameters, null, true )	;
	//tep_bootstrap_pull_down_menu($name, $countries_array, $id, $selected, $parameters, null, true )	;
	// tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);     
  }

////
// Output a jQuery UI Button
// bof responsive design
/*
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null, $target = null) {
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

//    if ( ($params['type'] == 'button') && isset($link) ) {
//      $button .= '<a id="tdb' . $button_counter . '" href="' . $link . '"';
//
//      if ( isset($params['newwindow']) ) {
//        $button .= ' target="_blank"';
//      }
//    } else {
//      $button .= '<button id="tdb' . $button_counter . '" type="' . tep_output_string($params['type']) . '"';
//    }
    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a id="tdb' . $button_counter . '" href="' . $link . '"';

      if ( isset($params['newwindow']) && !isset($target) ) {
        $button .= ' target="_blank"';

//ADDED LINES
      } else {
    	 if ($target == 'parent' ){
			$button .= ' target="_parent"';
		 }
    	 if ($target == 'blank' ){
			$button .= ' target="_blank"';
		 }
    	 if ($target == 'top' ){
			$button .= ' target="_top"';
		 }
    	 if ($target == 'self' ){
			$button .= ' target="_self"';
		 }
    	 if ($target == 'new' ){
			$button .= ' target="_new"';
		 }
//END ADDED LINES

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
 */
 
   function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null, $style = null) {
    static $button_counter = 1;
	

							
//	$array_font_awesome = array( , , "trash", "home" ) ;	
	
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

    $button = NULL;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a id="btn' . $button_counter . '" role="button" href="' . $link . '"';

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

    $button .= '">';

    if (isset($icon) && tep_not_null($icon)) {
		
	  //$icon = bootstrap_icon_fontawesome( $icon ) ;  	
      $button .= ' <span class="' . glyphicon_icon_to_fontawesome( $icon ) . '"></span> ';
    }

    $button .= $title;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    $button_counter++;

    return $button ;
  }

  // review stars
  function tep_draw_stars($rating = 0, $meta = false) {
    $stars = str_repeat('<span class="' . glyphicon_icon_to_fontawesome( "star" ) . '"></span>', (int)$rating);
    $stars .= str_repeat('<span class="' . glyphicon_icon_to_fontawesome( "star-empty" ) . '"></span>', 5-(int)$rating); //glyphicon glyphicon-star-empty
    if ($meta !== false) $stars .= '<meta itemprop="rating" content="' . (int)$rating . '" />';

    $stars = '<span class="text-nowrap">' . $stars . '</span>';	
    return $stars;
  }

  function tep_navbar_search($btnclass ='btn-default', $description = true) {
    global $request_type;

    $search_link = '<div class="searchbox-margin">';
    $search_link .= tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', $request_type, false), 'get', 'class="form-horizontal"');
    $search_link .= '    <div class="input-group">' .
                            tep_draw_input_field('keywords', '', 'required placeholder="' . TEXT_SEARCH_PLACEHOLDER . '"') .
                     '        <span class="input-group-btn"><button type="submit" class="btn ' . $btnclass .'"><i class="' . glyphicon_icon_to_fontawesome( "search" ) . '"></i></button></span>' .
                     '    </div>';  // glyphicon glyphicon-search
    $search_link .= '</div>';
    if (tep_not_null($description) && ($description === true)) {
      $search_link .= tep_draw_hidden_field('search_in_description', '1');
    }
    $search_link .=  tep_hide_session_id() . '</form>';

	  return $search_link;
  }

 // eof responsive design
 ?>