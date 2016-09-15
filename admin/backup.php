<?php
/*
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2015 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  if (tep_not_null($action)) {
    switch ($action) {
      case 'forget':
        tep_db_query("delete from " . $multi_stores_config . " where configuration_key = 'DB_LAST_RESTORE'");
        $messageStack->add_session(SUCCESS_LAST_RESTORE_CLEARED, 'success');
        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'backupnow':
        tep_set_time_limit(0);
        $backup_file = 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
        $fp = fopen(DIR_FS_BACKUP . $backup_file, 'w');
        $schema = '# osCommerce, Open Source E-Commerce Solutions' . "\n" .
                  '# http://www.oscommerce.com' . "\n" .
                  '#' . "\n" .
                  '# Database Backup For ' . STORE_NAME . "\n" .
                  '# Copyright (c) ' . date('Y') . ' ' . STORE_OWNER . "\n" .
                  '#' . "\n" .
                  '# Database: ' . DB_DATABASE . "\n" .
                  '# Database Server: ' . DB_SERVER . "\n" .
                  '#' . "\n" .
                  '# Backup Date: ' . date(PHP_DATE_TIME_FORMAT) . "\n\n";
        fputs($fp, $schema);
        $tables_query = tep_db_query('show tables');
        while ($tables = tep_db_fetch_array($tables_query)) {
          list(,$table) = each($tables);
          $schema = 'drop table if exists ' . $table . ';' . "\n" .
                    'create table ' . $table . ' (' . "\n";
          $table_list = array();
          $fields_query = tep_db_query("show fields from " . $table);
          while ($fields = tep_db_fetch_array($fields_query)) {
            $table_list[] = $fields['Field'];
            $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];
            if (strlen($fields['Default']) > 0) $schema .= ' default \'' . $fields['Default'] . '\'';
            if ($fields['Null'] != 'YES') $schema .= ' not null';
            if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];
            $schema .= ',' . "\n";
          }
          $schema = preg_replace("/,\n$/", '', $schema);
// add the keys
          $index = array();
          $keys_query = tep_db_query("show keys from " . $table);
          while ($keys = tep_db_fetch_array($keys_query)) {
            $kname = $keys['Key_name'];
            if (!isset($index[$kname])) {
              $index[$kname] = array('unique' => !$keys['Non_unique'],
                                     'fulltext' => ($keys['Index_type'] == 'FULLTEXT' ? '1' : '0'),
                                     'columns' => array());
            }
            $index[$kname]['columns'][] = $keys['Column_name'];
          }
          while (list($kname, $info) = each($index)) {
            $schema .= ',' . "\n";
            $columns = implode($info['columns'], ', ');
            if ($kname == 'PRIMARY') {
              $schema .= '  PRIMARY KEY (' . $columns . ')';
            } elseif ( $info['fulltext'] == '1' ) {
              $schema .= '  FULLTEXT ' . $kname . ' (' . $columns . ')';
            } elseif ($info['unique']) {
              $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
            } else {
              $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
            }
          }
          $schema .= "\n" . ');' . "\n\n";
          fputs($fp, $schema);
// dump the data
          if ( ($table != TABLE_SESSIONS ) && ($table != TABLE_WHOS_ONLINE) ) {
            $rows_query = tep_db_query("select " . implode(',', $table_list) . " from " . $table);
            while ($rows = tep_db_fetch_array($rows_query)) {
              $schema = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';
              reset($table_list);
              while (list(,$i) = each($table_list)) {
                if (!isset($rows[$i])) {
                  $schema .= 'NULL, ';
                } elseif (tep_not_null($rows[$i])) {
                  $row = addslashes($rows[$i]);
                  $row = preg_replace("/\n#/", "\n".'\#', $row);
                  $schema .= '\'' . $row . '\', ';
                } else {
                  $schema .= '\'\', ';
                }
              }
              $schema = preg_replace('/, $/', '', $schema) . ');' . "\n";
              fputs($fp, $schema);
            }
          }
        }
        fclose($fp);
        if (isset($HTTP_POST_VARS['download']) && ($HTTP_POST_VARS['download'] == 'yes')) {
          switch ($HTTP_POST_VARS['compress']) {
            case 'gzip':
              exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
              $backup_file .= '.gz';
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
              $backup_file .= '.zip';
          }
          header('Content-type: application/x-octet-stream');
          header('Content-disposition: attachment; filename=' . $backup_file);
          readfile(DIR_FS_BACKUP . $backup_file);
          unlink(DIR_FS_BACKUP . $backup_file);
          exit;
        } else {
          switch ($HTTP_POST_VARS['compress']) {
            case 'gzip':
              exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
              break;
            case 'zip':
              exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
              unlink(DIR_FS_BACKUP . $backup_file);
          }
          $messageStack->add_session(SUCCESS_DATABASE_SAVED, 'success');
        }
        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'restorenow':
      case 'restorelocalnow':
        tep_set_time_limit(0);
        if ($action == 'restorenow') {
          $read_from = $HTTP_GET_VARS['file'];
          if (file_exists(DIR_FS_BACKUP . $HTTP_GET_VARS['file'])) {
            $restore_file = DIR_FS_BACKUP . $HTTP_GET_VARS['file'];
            $extension = substr($HTTP_GET_VARS['file'], -3);
            if ( ($extension == 'sql') || ($extension == '.gz') || ($extension == 'zip') ) {
              switch ($extension) {
                case 'sql':
                  $restore_from = $restore_file;
                  $remove_raw = false;
                  break;
                case '.gz':
                  $restore_from = substr($restore_file, 0, -3);
                  exec(LOCAL_EXE_GUNZIP . ' ' . $restore_file . ' -c > ' . $restore_from);
                  $remove_raw = true;
                  break;
                case 'zip':
                  $restore_from = substr($restore_file, 0, -4);
                  exec(LOCAL_EXE_UNZIP . ' ' . $restore_file . ' -d ' . DIR_FS_BACKUP);
                  $remove_raw = true;
              }
              if (isset($restore_from) && file_exists($restore_from) && (filesize($restore_from) > 15000)) {
                $fd = fopen($restore_from, 'rb');
                $restore_query = fread($fd, filesize($restore_from));
                fclose($fd);
              }
            }
          }
        } elseif ($action == 'restorelocalnow') {
          $sql_file = new upload('sql_file');
          if ($sql_file->parse() == true) {
            $restore_query = fread(fopen($sql_file->tmp_filename, 'r'), filesize($sql_file->tmp_filename));
            $read_from = $sql_file->filename;
          }
        }
        if (isset($restore_query)) {
          $sql_array = array();
          $drop_table_names = array();
          $sql_length = strlen($restore_query);
          $pos = strpos($restore_query, ';');
          for ($i=$pos; $i<$sql_length; $i++) {
            if ($restore_query[0] == '#') {
              $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
              $sql_length = strlen($restore_query);
              $i = strpos($restore_query, ';')-1;
              continue;
            }
            if ($restore_query[($i+1)] == "\n") {
              for ($j=($i+2); $j<$sql_length; $j++) {
                if (trim($restore_query[$j]) != '') {
                  $next = substr($restore_query, $j, 6);
                  if ($next[0] == '#') {
// find out where the break position is so we can remove this line (#comment line)
                    for ($k=$j; $k<$sql_length; $k++) {
                      if ($restore_query[$k] == "\n") break;
                    }
                    $query = substr($restore_query, 0, $i+1);
                    $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
                    $restore_query = $query . $restore_query;
                    $sql_length = strlen($restore_query);
                    $i = strpos($restore_query, ';')-1;
                    continue 2;
                  }
                  break;
                }
              }
              if ($next == '') { // get the last insert query
                $next = 'insert';
              }
              if ( (preg_match('/create/i', $next)) || (preg_match('/insert/i', $next)) || (preg_match('/drop t/i', $next)) ) {
                $query = substr($restore_query, 0, $i);
                $next = '';
                $sql_array[] = $query;
                $restore_query = ltrim(substr($restore_query, $i+1));
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
                if (preg_match('/^create*/i', $query)) {
                  $table_name = trim(substr($query, stripos($query, 'table ')+6));
                  $table_name = substr($table_name, 0, strpos($table_name, ' '));
                  $drop_table_names[] = $table_name;
                }
              }
            }
          }
          tep_db_query('drop table if exists ' . implode(', ', $drop_table_names));
          for ($i=0, $n=sizeof($sql_array); $i<$n; $i++) {
            tep_db_query($sql_array[$i]);
          }
          tep_session_close();
          tep_db_query("delete from " . TABLE_WHOS_ONLINE);
          tep_db_query("delete from " . TABLE_SESSIONS);
          tep_db_query("delete from " . $multi_stores_config . " where configuration_key = 'DB_LAST_RESTORE'");
          tep_db_query("insert into " . $multi_stores_config . " values (null, 'Last Database Restore', 'DB_LAST_RESTORE', '" . $read_from . "', 'Last database restore file', '6', '0', null, now(), '', '')");
          if (isset($remove_raw) && ($remove_raw == true)) {
            unlink($restore_from);
          }
          $messageStack->add_session(SUCCESS_DATABASE_RESTORED, 'success');
        }
        tep_redirect(tep_href_link(FILENAME_BACKUP));
        break;
      case 'download':
        $extension = substr($HTTP_GET_VARS['file'], -3);
        if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') ) {
          if ($fp = fopen(DIR_FS_BACKUP . $HTTP_GET_VARS['file'], 'rb')) {
            $buffer = fread($fp, filesize(DIR_FS_BACKUP . $HTTP_GET_VARS['file']));
            fclose($fp);
            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $HTTP_GET_VARS['file']);
            echo $buffer;
            exit;
          }
        } else {
          $messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
        break;
      case 'deleteconfirm':
        if (strstr($HTTP_GET_VARS['file'], '..')) tep_redirect(tep_href_link(FILENAME_BACKUP));
        tep_remove(DIR_FS_BACKUP . '/' . $HTTP_GET_VARS['file']);
        if (!$tep_remove_error) {
          $messageStack->add_session(SUCCESS_BACKUP_DELETED, 'success');
          tep_redirect(tep_href_link(FILENAME_BACKUP));
        }
        break;
    }
  }
// check if the backup directory exists
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    if (tep_is_writable(DIR_FS_BACKUP)) {
      $dir_ok = true;
    } else {
      $messageStack->add(ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add(ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
  require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header--> 
          <div class="table-responsive">		  
          <table class="table table-hover table-responsive table-striped">
          <thead>
		    <tr>
                <th>                           <?php echo TABLE_HEADING_TITLE; ?></th>
                <th>                           <?php echo TABLE_HEADING_FILE_DATE; ?></th>
                <th class="hidden-xs">         <?php echo TABLE_HEADING_FILE_SIZE; ?></th>
                <th class="text-left">         <?php echo TABLE_HEADING_ACTION; ?></th>
              </tr>
			</thead>
           <tbody> 			
<?php
  if ($dir_ok == true) {
    $dir = dir(DIR_FS_BACKUP);
    $contents = array();
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_BACKUP . $file) && in_array(substr($file, -3), array('zip', 'sql', '.gz'))) {
        $contents[] = $file;
      }
    }
    sort($contents);
    for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
      $entry = $contents[$i];
      $check = 0;
      if ((!isset($HTTP_GET_VARS['file']) || (isset($HTTP_GET_VARS['file']) && ($HTTP_GET_VARS['file'] == $entry))) && !isset($buInfo) && ($action != 'backup') && ($action != 'restorelocal')) {
        $file_array['file'] = $entry;
        $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
        $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes';
        switch (substr($entry, -3)) {
          case 'zip': $file_array['compression'] = 'ZIP'; break;
          case '.gz': $file_array['compression'] = 'GZIP'; break;
          default: $file_array['compression'] = TEXT_NO_EXTENSION; break;
        }
        $buInfo = new objectInfo($file_array);
      }
      if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
        echo '<tr class="active">';
//        $onclick_link = 'file=' . $buInfo->file . '&action=restore';
      } else {
        echo '<tr>';
//        $onclick_link = 'file=' . $entry;
      }
?>
                                <td>                     <?php echo $entry; ?></td>
                                 <td>                    <?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
                                 <td class="text-center"><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?></td>
                                 <td class="text-right">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_DOWNLOAD,   'download',      tep_href_link(FILENAME_BACKUP, 'action=download&file=' . $entry),                                                      null, 'warning')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_RESTORE,         'import',        tep_href_link(FILENAME_BACKUP, 'file=' . $entry . '&action=restore'),                                           null, 'danger') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_BACKUP, 'page=' . $HTTP_GET_VARS['page'] . '&file=' . $entry . '&action=confirm'),     null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>
		
              </tr>
<?php			  
                  if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents_show .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_show .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_ZONE . '</div>' . PHP_EOL;
			                           $contents_show .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents_show .= '                      ' . tep_draw_form('delete', FILENAME_BACKUP, 'file=' . $entry  . '&action=deleteconfirm') . PHP_EOL;
                                       $contents_show .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents_show .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $entry   . '</p>' . PHP_EOL;
										
                                       $contents_show .= '                        </div>' . PHP_EOL;
                                       $contents_show .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents_show .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BACKUP, 'file=' . $buInfo->file), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents_show .= '                        </div>' . PHP_EOL;
		                               $contents_show .= '                      </form>' . PHP_EOL;
		                               $contents_show_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_show_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'restore':
		                               $alertClass .= ' alert alert-danger';									
			                           $contents_show .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_show .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_ZONE . '</div>' . PHP_EOL;
			                           $contents_show .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_show .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents_show .= '                          <p>' . TEXT_RESTORE_INTRO . '<br />' . tep_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' ')   . '</p>' . PHP_EOL;
										
                                       $contents_show .= '                        </div>' . PHP_EOL;
									   
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_RESTORE, 'restore', tep_href_link(FILENAME_BACKUP, 'file=' . $entry . '&action=restorenow'), null, null, 'btn-default text-danger') .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove',   tep_href_link(FILENAME_BACKUP, 'file=' . $entry),                        null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $cInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_NAME . '<br />' . $cInfo->zone_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_CODE . '  ' . $cInfo->zone_code . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_NAME . '  ' . tep_get_country_name( $cInfo->countries_id ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_ZONES ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

		
		                         $contents_show .=  '</div>' . PHP_EOL ;
		                         $contents_show .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents_show .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="4">' . PHP_EOL .
                                      '                    <div class="row ' . $alertClass . '">' . PHP_EOL .
                                                               $contents_show . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets				  
 
    }
    $dir->close();
  }
?>
           </tbody>
        </table>
	  </div>
	</table>
	
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->	  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_BACKUP,  'copy',             null, 'data-toggle="modal" data-target="#new_backup"') ; ?>
				   <?php echo tep_draw_bs_button(IMAGE_RESTORE, 'open', null, 'data-toggle="modal" data-target="#new_restore_local"' ) ; ?>
 			 </div>
            </div>
<?php
          }
?>		  
    </table>	
	
	
       <div class="modal fade"  id="new_backup" role="dialog" aria-labelledby="new_backup" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('backup', FILENAME_BACKUP, 'action=backupnow') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_BACKUP; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_new            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_NEW_BACKUP . '</div>' . PHP_EOL;
			                           $contents_new            .= '          <div class="panel-body">' . PHP_EOL;			
 
 	                                   $contents_new            .= '   <div class="form-group">' . PHP_EOL ;
 	                                   $contents_new            .= '      <div class="radio radio-success">' . PHP_EOL ;
                                       $contents_new            .=  			   tep_bs_radio_field('compress', 'no', TEXT_INFO_USE_NO_COMPRESSION,  'input_backup_use_compress_none',   true, 'radio radio-success radio-inline', '', '', 'right') ;
                                       if (file_exists(LOCAL_EXE_GZIP)) {
                                             $contents_new      .=  			   tep_bs_radio_field('compress', 'gzip', TEXT_INFO_USE_GZIP, 'input_backup_use_compress_gzip', false, 'radio radio-success radio-inline', '', '', 'right') ;
									   }										 
									   if (file_exists(LOCAL_EXE_ZIP)) {
                                             $contents_new      .=  			   tep_bs_radio_field('compress', 'zip', TEXT_INFO_USE_ZIP, 'input_backup_use_compress_zip', false, 'radio radio-success radio-inline', '', '', 'right') ;										  
                					   }			  
                                       $contents_new            .= '      </div>'. PHP_EOL  ;	
                                       $contents_new            .= '   </div>'. PHP_EOL  ;	
									   
                                       if ($dir_ok == true) {									    
									   
 	                                      $contents_new          .= '<div class="form-group">' . PHP_EOL ;			
 	                                      $contents_new          .= '  <div class="checkbox checkbox-success">' . PHP_EOL ;				
			                              $contents_new          .=        tep_bs_checkbox_field('download', 'yes', TEXT_INFO_BEST_THROUGH_HTTPS, 'input_download_yes', false, 'checkbox checkbox-success', '', '', 'right') . TEXT_INFO_DOWNLOAD_ONLY ;
			                              $contents_new          .= '  </div>' . PHP_EOL ;			
			                              $contents_new          .= '</div>' . PHP_EOL ;					
			                              $contents_new          .= '<br />';											   

									   } else {
 	                                      $contents_new          .= '   <div class="form-group">' . PHP_EOL ;
 	                                      $contents_new          .= '      <div class="radio radio-success">' . PHP_EOL ;
                                          $contents_new          .=  			   tep_bs_radio_field('download', 'yes', TEXT_INFO_BEST_THROUGH_HTTPS,  'input_download_yes',   false, 'radio radio-success radio-inline', '', '', 'right')  . TEXT_INFO_DOWNLOAD_ONLY ;
                                          $contents_new          .= '      </div>'. PHP_EOL  ;	
                                          $contents_new          .= '   </div>'. PHP_EOL  ;											   
									   }
									   
									   
									   $contents_new            .= '                       <br />' . PHP_EOL;	
                                       
		                               $contents_new_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_new_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_new . $contents_new_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BACKUP)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_backup --> 	
		  
       <div class="modal fade"  id="new_restore_local" role="dialog" aria-labelledby="new_restore_local" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('restore', FILENAME_BACKUP, 'action=restorelocalnow', 'post', 'enctype="multipart/form-data"', 'id_restore_local') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_RESTORE_LOCAL; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_local            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_local            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</div>' . PHP_EOL;
			                           $contents_local            .= '          <div class="panel-body">' . PHP_EOL;	
			                           $contents_local            .= '              <div class="well">' . TEXT_INFO_RESTORE_LOCAL . '<br /><br />' . TEXT_INFO_BEST_THROUGH_HTTPS	. '</div>' . PHP_EOL ;
									   
                                       $contents_local            .= '                       <div  class="form-group"> ' . PHP_EOL ;
                                       $contents_local            .=                             tep_draw_bs_file_field('sql_file' , null, TEXT_INFO_RESTORE_LOCAL_RAW_FILE, 'id_input_sql_file' , 'col-xs-3', 'col-xs-9', 'left') . PHP_EOL;	                 									   
                                       $contents_local            .= '                       </div>' . PHP_EOL ;
									                                         
		                               $contents_local_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_local_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_local . $contents_local_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_RESTORE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BACKUP, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_restore_local --> 		  

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>