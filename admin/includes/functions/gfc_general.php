<?php
/*
  $Id: gfc_general.php 2011-04-20 kdm $
  Copyright 2011 Kerry Mapes
  Released under the GNU General Public License  
*/
    
    function gfc_format_date($date, $time=false) {
     
       if($time === false) {
          if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})$/',$date)) {
             list($month, $day, $year) = explode('/', $date);
             $date = strftime("%Y-%m-%d %H:%M:%S", mktime(00, 00, 00, $month, $day, $year));
            }
         } else {       
          if(preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})$/',substr($date, 0, strrpos($date, '/')+5))) {
             list($month, $day, $year) = explode('/', substr($date, 0, strrpos($date, '/')+5));
             list($hours, $minute, $seconds) = explode(':', substr($date, strrpos($date, '/')+5));
             $date = strftime("%Y-%m-%d %H:%M:%S", mktime($hours, $minute, $seconds, $month, $day, $year));
            }
         }      
     
       return $date;
      }
    
    function gfc_current_date($time=false) {
     
       $timestamp = getdate();
       if($time === false) {
          $date = gfc_format_date($timestamp['mon'].'/'.$timestamp['mday'].'/'.$timestamp['year'], $time);
         } else {
          $date = gfc_format_date($timestamp['mon'].'/'.$timestamp['mday'].'/'.$timestamp['year'].' '.
                                  $timestamp['hours'].':'.$timestamp['minutes'].':'.$timestamp['seconds'],  $time);
         }
     
       return $date;
      }
    
////
// HTML form for image submit
    function gfc_image_submit($image, $alt = '', $width = '', $height = '', $parameters = '', $dir = '') {
       global $language;

       if(!tep_not_null($dir)) $dir = DIR_WS_LANGUAGES . $language . '/images/buttons/';
          $image_submit = '<input type="image" src="' . tep_output_string($dir . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

       if(tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

       if(tep_not_null($width) && tep_not_null($height))
          $image_submit .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';

       if(tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

       $image_submit .= '>';

       return $image_submit;
      }////

// Check date
    function tep_checkdate($date_to_check, $format_string, &$date_array) {
       $separator_idx = -1;

       $separators = array('-', ' ', '/', '.');
       $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
       $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

       $format_string = strtolower($format_string);

       if (strlen($date_to_check) != strlen($format_string)) {
          return false;
        }

       $size = sizeof($separators);
       for ($i=0; $i<$size; $i++) {
          $pos_separator = strpos($date_to_check, $separators[$i]);
          if ($pos_separator != false) {
             $date_separator_idx = $i;
             break;
            }
         }

       for ($i=0; $i<$size; $i++) {
          $pos_separator = strpos($format_string, $separators[$i]);
          if ($pos_separator != false) {
             $format_separator_idx = $i;
             break;
            }
         }

       if ($date_separator_idx != $format_separator_idx) {
          return false;
         }

       if ($date_separator_idx != -1) {
          $format_string_array = explode( $separators[$date_separator_idx], $format_string );
          if (sizeof($format_string_array) != 3) {
             return false;
            }

          $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
          if (sizeof($date_to_check_array) != 3) {
             return false;
            }

          $size = sizeof($format_string_array);
          for ($i=0; $i<$size; $i++) {
             if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
             if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
             if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
            }
         } else {
          if (strlen($format_string) == 8 || strlen($format_string) == 9) {
             $pos_month = strpos($format_string, 'mmm');
             if ($pos_month != false) {
                $month = substr( $date_to_check, $pos_month, 3 );
                $size = sizeof($month_abbr);
                for ($i=0; $i<$size; $i++) {
                   if ($month == $month_abbr[$i]) {
                      $month = $i;
                      break;
                     }
                  }
               } else {
                $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
               }
            } else {
             return false;
            }

          $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
          $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
         }

       if (strlen($year) != 4) {
          return false;
         }

       if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
          return false;
         }

       if ($month > 12 || $month < 1) {
          return false;
         }

       if ($day < 1) {
          return false;
         }

       if (tep_is_leap_year($year)) {
          $no_of_days[1] = 29;
         }

       if ($day > $no_of_days[$month - 1]) {
          return false;
         }

       $date_array = array($year, $month, $day);

       return true;
      }

////
// Check if year is a leap year
    function tep_is_leap_year($year) {
       if ($year % 100 == 0) {
          if ($year % 400 == 0) return true;
         } else {
          if (($year % 4) == 0) return true;
         }

       return false;
      }
?>