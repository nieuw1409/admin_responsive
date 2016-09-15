<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $htaccess_array = null;
  $htpasswd_array = null;
  $is_iis = stripos($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'iis');

  $authuserfile_array = array('##### OSCOMMERCE ADMIN PROTECTION - BEGIN #####',
                              'AuthType Basic',
                              'AuthName "osCommerce Online Merchant Administration Tool"',
                              'AuthUserFile ' . DIR_FS_ADMIN . '.htpasswd_oscommerce',
                              'Require valid-user',
                              '##### OSCOMMERCE ADMIN PROTECTION - END #####');

  if (!$is_iis && file_exists(DIR_FS_ADMIN . '.htpasswd_oscommerce') && tep_is_writable(DIR_FS_ADMIN . '.htpasswd_oscommerce') && file_exists(DIR_FS_ADMIN . '.htaccess') && tep_is_writable(DIR_FS_ADMIN . '.htaccess')) {
    $htaccess_array = array();
    $htpasswd_array = array();

    if (filesize(DIR_FS_ADMIN . '.htaccess') > 0) {
      $fg = fopen(DIR_FS_ADMIN . '.htaccess', 'rb');
      $data = fread($fg, filesize(DIR_FS_ADMIN . '.htaccess'));
      fclose($fg);

      $htaccess_array = explode("\n", $data);
    }

    if (filesize(DIR_FS_ADMIN . '.htpasswd_oscommerce') > 0) {
      $fg = fopen(DIR_FS_ADMIN . '.htpasswd_oscommerce', 'rb');
      $data = fread($fg, filesize(DIR_FS_ADMIN . '.htpasswd_oscommerce'));
      fclose($fg);

      $htpasswd_array = explode("\n", $data);
    }
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        require('includes/functions/password_funcs.php');

        $username = tep_db_prepare_input($HTTP_POST_VARS['username']);
        $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

        $check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " where user_name = '" . tep_db_input($username) . "' limit 1");

        if (tep_db_num_rows($check_query) < 1) {
          tep_db_query("insert into " . TABLE_ADMINISTRATORS . " (user_name, user_password) values ('" . tep_db_input($username) . "', '" . tep_db_input(tep_encrypt_password($password)) . "')");

          if (is_array($htpasswd_array)) {
            for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
              list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

              if ($ht_username == $username) {
                unset($htpasswd_array[$i]);
              }
            }

            if (isset($HTTP_POST_VARS['htaccess']) && ($HTTP_POST_VARS['htaccess'] == 'true')) {
              $htpasswd_array[] = $username . ':' . tep_crypt_apr_md5($password);
            }

            $fp = fopen(DIR_FS_ADMIN . '.htpasswd_oscommerce', 'w');
            fwrite($fp, implode("\n", $htpasswd_array));
            fclose($fp);

            if (!in_array('AuthUserFile ' . DIR_FS_ADMIN . '.htpasswd_oscommerce', $htaccess_array) && !empty($htpasswd_array)) {
              array_splice($htaccess_array, sizeof($htaccess_array), 0, $authuserfile_array);
            } elseif (empty($htpasswd_array)) {
              for ($i=0, $n=sizeof($htaccess_array); $i<$n; $i++) {
                if (in_array($htaccess_array[$i], $authuserfile_array)) {
                  unset($htaccess_array[$i]);
                }
              }
            }

            $fp = fopen(DIR_FS_ADMIN . '.htaccess', 'w');
            fwrite($fp, implode("\n", $htaccess_array));
            fclose($fp);
          }
        } else {
          $messageStack->add_session(ERROR_ADMINISTRATOR_EXISTS, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_ADMINISTRATORS));
        break;
      case 'save':
        require('includes/functions/password_funcs.php');

        $username = tep_db_prepare_input($HTTP_POST_VARS['username']);
        $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

        $check_query = tep_db_query("select id, user_name from " . TABLE_ADMINISTRATORS . " where id = '" . (int)$HTTP_GET_VARS['aID'] . "'");
        $check = tep_db_fetch_array($check_query);

// update username in current session if changed
        if ( ($check['id'] == $admin['id']) && ($check['user_name'] != $admin['username']) ) {
          $admin['username'] = $username;
        }

// update username in htpasswd if changed
        if (is_array($htpasswd_array)) {
          for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
            list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

            if ( ($check['user_name'] == $ht_username) && ($check['user_name'] != $username) ) {
              $htpasswd_array[$i] = $username . ':' . $ht_password;
            }
          }
        }

        tep_db_query("update " . TABLE_ADMINISTRATORS . " set user_name = '" . tep_db_input($username) . "' where id = '" . (int)$HTTP_GET_VARS['aID'] . "'");

        if (tep_not_null($password)) {
// update password in htpasswd
          if (is_array($htpasswd_array)) {
            for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
              list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

              if ($ht_username == $username) {
                unset($htpasswd_array[$i]);
              }
            }

            if (isset($HTTP_POST_VARS['htaccess']) && ($HTTP_POST_VARS['htaccess'] == 'true')) {
              $htpasswd_array[] = $username . ':' . tep_crypt_apr_md5($password);
            }
          }

          tep_db_query("update " . TABLE_ADMINISTRATORS . " set user_password = '" . tep_db_input(tep_encrypt_password($password)) . "' where id = '" . (int)$HTTP_GET_VARS['aID'] . "'");
        } elseif (!isset($HTTP_POST_VARS['htaccess']) || ($HTTP_POST_VARS['htaccess'] != 'true')) {
          if (is_array($htpasswd_array)) {
            for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
              list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

              if ($ht_username == $username) {
                unset($htpasswd_array[$i]);
              }
            }
          }
        }

// write new htpasswd file
        if (is_array($htpasswd_array)) {
          $fp = fopen(DIR_FS_ADMIN . '.htpasswd_oscommerce', 'w');
          fwrite($fp, implode("\n", $htpasswd_array));
          fclose($fp);

          if (!in_array('AuthUserFile ' . DIR_FS_ADMIN . '.htpasswd_oscommerce', $htaccess_array) && !empty($htpasswd_array)) {
            array_splice($htaccess_array, sizeof($htaccess_array), 0, $authuserfile_array);
          } elseif (empty($htpasswd_array)) {
            for ($i=0, $n=sizeof($htaccess_array); $i<$n; $i++) {
              if (in_array($htaccess_array[$i], $authuserfile_array)) {
                unset($htaccess_array[$i]);
              }
            }
          }

          $fp = fopen(DIR_FS_ADMIN . '.htaccess', 'w');
          fwrite($fp, implode("\n", $htaccess_array));
          fclose($fp);
        }

        tep_redirect(tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . (int)$HTTP_GET_VARS['aID']));
        break;
      case 'deleteconfirm':
        $id = tep_db_prepare_input($HTTP_GET_VARS['aID']);

        $check_query = tep_db_query("select id, user_name from " . TABLE_ADMINISTRATORS . " where id = '" . (int)$id . "'");
        $check = tep_db_fetch_array($check_query);

        if ($admin['id'] == $check['id']) {
          tep_session_unregister('admin');
        }

        tep_db_query("delete from " . TABLE_ADMINISTRATORS . " where id = '" . (int)$id . "'");

        if (is_array($htpasswd_array)) {
          for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
            list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

            if ($ht_username == $check['user_name']) {
              unset($htpasswd_array[$i]);
            }
          }

          $fp = fopen(DIR_FS_ADMIN . '.htpasswd_oscommerce', 'w');
          fwrite($fp, implode("\n", $htpasswd_array));
          fclose($fp);

          if (empty($htpasswd_array)) {
            for ($i=0, $n=sizeof($htaccess_array); $i<$n; $i++) {
              if (in_array($htaccess_array[$i], $authuserfile_array)) {
                unset($htaccess_array[$i]);
              }
            }

            $fp = fopen(DIR_FS_ADMIN . '.htaccess', 'w');
            fwrite($fp, implode("\n", $htaccess_array));
            fclose($fp);
          }
        }

        tep_redirect(tep_href_link(FILENAME_ADMINISTRATORS));
        break;
    }
  }

  $secMessageStack = new messageStack();

  if (is_array($htpasswd_array)) {
    if (empty($htpasswd_array)) {
      $secMessageStack->add(sprintf(HTPASSWD_INFO, implode('<br />', $authuserfile_array)), 'error');
    } else {
      $secMessageStack->add(HTPASSWD_SECURED, 'success');
    }
  } else if (!$is_iis) {
    $secMessageStack->add(HTPASSWD_PERMISSIONS, 'error');
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
<?php
            echo $secMessageStack->output();
?>
          <div class="table-responsive">	
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th><?php echo TABLE_HEADING_ADMINISTRATORS; ?></th>
                   <th><?php echo TABLE_HEADING_HTPASSWD; ?></th>				                       			   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>
                </tr>
              </thead>
              <tbody>
<?php
                  $admins_query = tep_db_query("select id, user_name from " . TABLE_ADMINISTRATORS . " order by user_name");
                  while ($admins = tep_db_fetch_array($admins_query)) {
                      if ((!isset($HTTP_GET_VARS['aID']) || (isset($HTTP_GET_VARS['aID']) && ($HTTP_GET_VARS['aID'] == $admins['id']))) && !isset($aInfo) && (substr($action, 0, 3) != 'new')) {
                         $aInfo = new objectInfo($admins);
                      }


				      $htpasswd_secured = tep_glyphicon(  'remove', 'danger') ;
					  //tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Not Secured', 10, 10);

					  if ($is_iis) {
						  $htpasswd_secured = 'N/A';
					  }

					  if (is_array($htpasswd_array)) {
						  for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
							list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

							if ($ht_username == $admins['user_name']) {
							  $htpasswd_secured = tep_glyphicon( 'ok' , 'success')  ;
							  //tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Secured', 10, 10);
							  break;
							} 
						  }
					  }

					  if ( (isset($aInfo) && is_object($aInfo)) && ($admins['id'] == $aInfo->id) ) {
						  echo '<tr id="defaultSelected" class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=edit') . '\'">' . "\n";
					  } else {
						  echo '<tr                                     onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $admins['id']) . '\'">' . "\n";
					  }
?>
									<td><?php echo $admins['user_name']; ?></td>
									<td><?php echo $htpasswd_secured; ?></td>
                                           
                              <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $admins['id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $admins['id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $admins['id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL   ;
?>
                                   </div> 
				              </td>											
                     </tr>
<?php 
                
					  
                  if (isset($aInfo) && is_object($aInfo) && ($admins['id'] == $aInfo->id) && isset($HTTP_GET_VARS['action'])) { 
			  
 	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . $aInfo->user_name . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('administrator', FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $aInfo->user_name   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ADMINISTRATORS ), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('administrator', FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_adminstrators') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('username',       $aInfo->user_name,   TEXT_INFO_USERNAME,       'id_admin_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_USERNAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('password',       '',                  TEXT_INFO_NEW_PASSWORD,   'id_admin_password' ,    'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_NEW_PASSWORD,       '', true, null, 'password' ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	

									   if (is_array($htpasswd_array)) {
											$default_flag = false;

									        for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
											  list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

											  if ($ht_username == $aInfo->user_name) {
												$default_flag = true;
												break;
											  }
									       }
									   
                                          $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										  $contents         .=                                 tep_bs_checkbox_field('htaccess', 'true', TEXT_INFO_PROTECT_WITH_HTPASSWD, 'id_protext_htpassword', $default_flag, 'checkbox checkbox-success' ) . PHP_EOL  ;									   
                                          $contents         .= '                           </div>' . PHP_EOL ;			   
                                       }										   
									   $contents            .= '                       <br />' . PHP_EOL;	                                  

 
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ADMINISTRATORS, 'aID=' . $aInfo->id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $aInfo->user_name  . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_USERNAME . ' <br />' .  $aInfo->user_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;					
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_ADMINISTRATORS ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets						  						 
				} // end while ($customers = tep_db_fetch_array($cus				
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
                   <?php echo tep_draw_bs_button(IMAGE_INSERT, 'plus', null,'data-toggle="modal" data-target="#new_administrator"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  

    </table>
	
        <div class="modal fade"  id="new_administrator" role="dialog" aria-labelledby="new_administrator" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('administrator', FILENAME_ADMINISTRATORS, 'action=insert', 'post', 'autocomplete="off"') ; ?>
              
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_ADMINISTRATOR; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 

			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_INSERT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' .  PHP_EOL;	
 	                                   
									   $contents            .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '                         <div class="panel-body">' . PHP_EOL;									   
                                       $contents            .= '                              <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                             ' . tep_draw_bs_input_field('username',       null,   TEXT_INFO_USERNAME,       'id_admin_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_USERNAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                              </div>' . PHP_EOL ;	
                                       $contents            .= '                              <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                             ' . tep_draw_bs_input_field('password',       '',     TEXT_INFO_NEW_PASSWORD,   'id_admin_password' ,    'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_NEW_PASSWORD,       '', true, null, 'password' ) . PHP_EOL;	
                                       $contents            .= '                              </div>' . PHP_EOL ;	

									   if (is_array($htpasswd_array)) {
											$default_flag = false;

									        for ($i=0, $n=sizeof($htpasswd_array); $i<$n; $i++) {
											  list($ht_username, $ht_password) = explode(':', $htpasswd_array[$i], 2);

											  if ($ht_username == $aInfo->user_name) {
												$default_flag = true;
												break;
											  }
									       }
									   
                                          $contents         .= '                               <div class="form-group">' . PHP_EOL ;											   
										  $contents         .=                                    tep_bs_checkbox_field('htaccess', 'true', TEXT_INFO_PROTECT_WITH_HTPASSWD, 'id_protext_htpassword', $default_flag, 'checkbox checkbox-success' ) . PHP_EOL  ;									   
                                          $contents         .= '                               </div>' . PHP_EOL ;			   
                                       }										   
									   $contents            .= '                               <br />' . PHP_EOL;	          				  
		                               $contents            .= '                         </div>' . PHP_EOL; // end div default stores	panel body
		                               $contents            .= '                       </div>' . PHP_EOL; // end div default stores 	panel										  										  
 
		                               $contents        .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents        .= '           </div>' . PHP_EOL; // end div 	panel	
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ADMINISTRATORS)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_language -->	
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>