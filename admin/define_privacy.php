<?php

  require('includes/application_top.php');

// This will cause it to look for 'catalog/language/(L)/define_mainpage.php'

  $HTTP_GET_VARS['filename'] = FILENAME_DEFINE_PRIVACY;
  
   switch ($HTTP_GET_VARS['action']) {
    case 'save':
      if ( ($HTTP_GET_VARS['lngdir']) && ($HTTP_GET_VARS['filename']) ) {
        if ($HTTP_GET_VARS['filename'] == $language . '.php') {
          $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['filename'];
        } else {
          $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['lngdir'] . '/' . $HTTP_GET_VARS['filename'];
        }
        if (file_exists($file)) {
          if (file_exists('bak' . $file)) {
            @unlink('bak' . $file);
          }
          @rename($file, 'bak' . $file);
          $new_file = fopen($file, 'w');
          $file_contents = stripslashes($HTTP_POST_VARS['file_contents']);
          $file_contents = '<?php' . "\n" .
                     '  define(\'NAVBAR_TITLE\', \'' . NAV_BAR_TITLE . '\');' . "\n" .
                      '  define(\'HEADING_TITLE\', \'' . NAV_HEADING_TITLE . '\');' . "\n" .	
                      '  define(\'TEXT_INFORMATION\', \'' . $file_contents . '\');' . "\n" ;
          $file_contents .= '?>';					 
		  
          fwrite($new_file, $file_contents, strlen($file_contents));
          fclose($new_file);
        }
 //       tep_redirect(tep_href_link(FILENAME_DEFINE_PRIVACY, 'lngdir=' . $HTTP_GET_VARS['lngdir']));
      }
      break;
  }

  if (!$HTTP_GET_VARS['lngdir']) $HTTP_GET_VARS['lngdir'] = $language;

  $languages_array = array();
  $languages = tep_get_languages();
  $lng_exists = false;
  for ($i=0; $i<sizeof($languages); $i++) {
    if ($languages[$i]['directory'] == $HTTP_GET_VARS['lngdir']) $lng_exists = true;

    $languages_array[] = array('id' => $languages[$i]['directory'],
                               'text' => $languages[$i]['name']);
  }
  if (!$lng_exists) $HTTP_GET_VARS['lngdir'] = $language;
  require('includes/template_top.php');  
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo $HTTP_GET_VARS['filename']; ?></h1>
            <div class="col-md-6">
              <div class="row">               
<?php
                    echo tep_draw_bs_form('lng', FILENAME_DEFINE_PRIVACY, '','GET', 'role="form"', 'cust_select') . PHP_EOL;				                         
                    echo tep_hide_session_id();
                    echo '<div class="col-md-9 col-xs-12">' . PHP_EOL;
                    echo '    <div class="form-group">' . PHP_EOL;
					echo          tep_draw_bs_pull_down_menu( 'lngdir', $languages_array,     null, HEADING_TITLE, 'lngdir', 'col-md-9', ' selectpicker show-tick', 'control-label col-md-3', 'left', 'onChange="this.form.submit();"' ) . PHP_EOL;
 				
					echo '    </div>' . PHP_EOL;
					echo '</div>' . PHP_EOL; 
                    echo '</form>' . PHP_EOL;?>
                </div>  					   
            </div> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
   </table>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">   
<?php
  if ( ($HTTP_GET_VARS['lngdir']) && ($HTTP_GET_VARS['filename']) ) {
    if ($HTTP_GET_VARS['filename'] == $language . '.php') {
      $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['filename'];
    } else {
      $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['lngdir'] . '/' . $HTTP_GET_VARS['filename'];
    }
    if (file_exists($file)) {
      $file_array = @file($file);
      $file_contents = @implode('', $file_array);

      $file_writeable = true;
      if (!is_writeable($file)) {
        $file_writeable = false;
        $messageStack->reset();
        $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $file), 'error');
        echo $messageStack->output();
      }
	                        // name, $action, $parameters = '', $method = 'post', $params = '', $id='id_form'; 
?>
          <div class="panel panel-primary"> 
		     <div class="panel-heading"><?php echo $HTTP_GET_VARS['filename']; ?></div>		  
			 <div class="panel-body">
<?php			 
                 echo tep_draw_bs_form('language', FILENAME_DEFINE_PRIVACY, 'lngdir=' . $HTTP_GET_VARS['lngdir'] . '&filename=' . $HTTP_GET_VARS['filename'] . '&action=save', 'post', 'class="form-inline" role="form"', 'id_def_cond') ;
?>	  
			 
                 <div class="form-group"> 
<?php 
                       echo tep_draw_textarea_ckeditor('file_contents', 'soft',  '100', '100', $file_contents, 'ckeditor') ;  
?>
                 </div>	
				 <div>
<?php 				 
                      if ($file_writeable) { 
					       echo  tep_draw_bs_button(IMAGE_SAVE, 'ok', null, 'primary') ; 
					   } 				 
?>					  
                </div> 
                </form>					
			 </div> <!-- div end panel body -->
		  </div> <!-- div end panel  -->
<?php		  
    } else {
?>
    <div class="alert alert-info"><?php echo TEXT_FILE_DOES_NOT_EXIST ; ?></div>		  
<?php		  
	}
?>
    </table>
<?php	
  } else {
    $filename = $HTTP_GET_VARS['lngdir'] . '.php';
?>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText"><a href="<?php echo tep_href_link(FILENAME_DEFINE_PRIVACY, 'lngdir=' . $HTTP_GET_VARS['lngdir'] . '&filename=' . $filename); ?>"><b><?php echo $filename; ?></b></a></td>
<?php
    $dir = dir(DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['lngdir']);
    $left = false;
    if ($dir) {
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      while ($file = $dir->read()) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          echo '                <td class="smallText"><a href="' . tep_href_link(FILENAME_DEFINE_PRIVACY, 'lngdir=' . $HTTP_GET_VARS['lngdir'] . '&filename=' . $file) . '">' . $file . '</a></td>' . "\n";
          if (!$left) {
            echo '              </tr>' . "\n" .
                 '              <tr>' . "\n";
          }
          $left = !$left;
        }
      }
      $dir->close();
    }
  }	
require(DIR_WS_INCLUDES . 'template_bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>