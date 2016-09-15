<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $banner_extension = tep_banner_image_extension();

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          tep_set_banner_status($HTTP_GET_VARS['bID'], $HTTP_GET_VARS['flag']);

          $messageStack->add_session(SUCCESS_BANNER_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $HTTP_GET_VARS['bID']));
        break;
      case 'insert':
      case 'update':
        $banners_id              = tep_db_prepare_input($HTTP_POST_VARS['banners_id']);
        $banners_status          = tep_db_prepare_input($HTTP_POST_VARS['banners_status']);		
        $banners_title           = tep_db_prepare_input($HTTP_POST_VARS['banners_title']);
        $banners_url             = tep_db_prepare_input($HTTP_POST_VARS['banners_url']);
        $new_banners_group       = tep_db_prepare_input($HTTP_POST_VARS['new_banners_group']);
        $banners_group           = (empty($new_banners_group)) ? tep_db_prepare_input($HTTP_POST_VARS['banners_group']) : $new_banners_group;
        $banners_html_text       = tep_db_prepare_input($HTTP_POST_VARS['banners_html_text']);
        $banners_image_local     = tep_db_prepare_input($HTTP_POST_VARS['banners_image_local']);
        $banners_image_target    = tep_db_prepare_input($HTTP_POST_VARS['banners_image_target']);
        $db_image_location = '';
		
        $expires_date = tep_db_prepare_input( tep_date_raw( $HTTP_POST_VARS['expires_date']) );

        $date_scheduled = tep_db_prepare_input( tep_date_raw( $HTTP_POST_VARS['date_scheduled']) );	
	
//        $expires_date = tep_db_prepare_input($HTTP_POST_VARS['expires_date']);
        $expires_impressions = tep_db_prepare_input($HTTP_POST_VARS['expires_impressions']);
//        $date_scheduled = tep_db_prepare_input($HTTP_POST_VARS['date_scheduled']);

//        $db_image_location = (tep_not_null($banners_image_local)) ? $banners_image_local : $banners_image_target . $banners_image_local;

/*        $banners_image = new upload('banners_image');
        $banners_image->set_destination(DIR_FS_CATALOG_IMAGES . $banners_image_target);
        if ($banners_image->parse() && $banners_image->save()) {
           $db_image_location = $banners_image->filename ;
		}
*/
        $banner_error = false;
        if (empty($banners_html_text)) {
          if (empty($banners_image_local)) {
            $banners_image = new upload('banners_image');
            $banners_image->set_destination(DIR_FS_CATALOG_IMAGES . $banners_image_target);
            if ( ($banners_image->parse() == false) || ($banners_image->save() == false) ) {
              $banner_error = true;
            }
          }
        }

        if ($banner_error == false) {		
          $db_image_location = (tep_not_null($banners_image_local)) ? $banners_image_local : $banners_image_target . $banners_image->filename;
		}
		
        $sql_data_array = array('banners_title'        => $banners_title,
                                'banners_url'          => $banners_url,
                                'banners_image'        => $db_image_location,
                                'banners_group'        => $banners_group,
                                'banners_html_text'    => $banners_html_text,
                                'expires_date'         => $expires_date,
                                'expires_impressions'  => $expires_impressions,
                                'date_scheduled'       => $date_scheduled,
                                'status'               => $banners_status );

        if ($action == 'insert') {
            $insert_sql_data = array('date_added' => 'now()',
                                     'status' => '1');

            $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

            tep_db_perform(TABLE_BANNERS, $sql_data_array);

            $banners_id = tep_db_insert_id();

            $messageStack->add_session(SUCCESS_BANNER_INSERTED, 'success');
        } elseif ($action == 'update') {

          tep_db_perform(TABLE_BANNERS, $sql_data_array, 'update', "banners_id = '" . (int)$banners_id . "'");
		}

       $messageStack->add_session(SUCCESS_BANNER_UPDATED, 'success');
       tep_redirect(tep_href_link(FILENAME_BANNER_MANAGER, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'bID=' . $banners_id  ));
        break;
      case 'deleteconfirm':
        $banners_id = tep_db_prepare_input($HTTP_GET_VARS['bID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $banner_query = tep_db_query("select banners_image from " . TABLE_BANNERS . " where banners_id = '" . (int)$banners_id . "'");
          $banner = tep_db_fetch_array($banner_query);

          if (is_file(DIR_FS_CATALOG_IMAGES . $banner['banners_image'])) {
            if (tep_is_writable(DIR_FS_CATALOG_IMAGES . $banner['banners_image'])) {
              unlink(DIR_FS_CATALOG_IMAGES . $banner['banners_image']);
            } else {
              $messageStack->add_session(ERROR_IMAGE_IS_NOT_WRITEABLE, 'error');
            }
          } else {
            $messageStack->add_session(ERROR_IMAGE_DOES_NOT_EXIST, 'error');
          }
        }

        tep_db_query("delete from " . TABLE_BANNERS . " where banners_id = '" . (int)$banners_id . "'");
        tep_db_query("delete from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int)$banners_id . "'");

// 2.3.3
//        if (function_exists('imagecreate') && tep_not_null($banner_extensio)) {
        if (function_exists('imagecreate') && tep_not_null($banner_extension)) {
// eof 2.3.3		

          if (is_file(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banners_id . '.' . $banner_extension)) {
            if (tep_is_writable(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banners_id . '.' . $banner_extension);
            }
          }

          if (is_file(DIR_WS_IMAGES . 'graphs/banner_yearly-' . $banners_id . '.' . $banner_extension)) {
            if (tep_is_writable(DIR_WS_IMAGES . 'graphs/banner_yearly-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_yearly-' . $banners_id . '.' . $banner_extension);
            }
          }

          if (is_file(DIR_WS_IMAGES . 'graphs/banner_monthly-' . $banners_id . '.' . $banner_extension)) {
            if (tep_is_writable(DIR_WS_IMAGES . 'graphs/banner_monthly-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_monthly-' . $banners_id . '.' . $banner_extension);
            }
          }

          if (is_file(DIR_WS_IMAGES . 'graphs/banner_daily-' . $banners_id . '.' . $banner_extension)) {
            if (tep_is_writable(DIR_WS_IMAGES . 'graphs/banner_daily-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_daily-' . $banners_id . '.' . $banner_extension);
            }
          }
        }

        $messageStack->add_session(SUCCESS_BANNER_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

// check if the graphs directory exists
  $dir_ok = false;
  if (function_exists('imagecreate') && tep_not_null($banner_extension)) {
    if (is_dir(DIR_WS_IMAGES . 'graphs')) {
      if (tep_is_writable(DIR_WS_IMAGES . 'graphs')) {
        $dir_ok = true;
      } else {
        $messageStack->add(ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE, 'error');
      }
    } else {
      $messageStack->add(ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST, 'error');
    }
  }
  
  $groups_array = array();
  $groups_query = tep_db_query("select distinct banners_group from " . TABLE_BANNERS . " order by banners_group");
  while ($groups = tep_db_fetch_array($groups_query)) {
      $groups_array[] = array('id' => $groups['banners_group'], 'text' => $groups['banners_group']);
  }  

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

   <table table="responsive" border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo TABLE_HEADING_BANNERS; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_GROUPS; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_STATISTICS; ?></th>				   
                   <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>					  
                   <th class="text-left"  ><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
					$banners_query_raw = "select banners_id, banners_title, banners_image, banners_group, status, expires_date, expires_impressions, date_status_change, date_scheduled, date_added, banners_html_text, banners_url from " . TABLE_BANNERS . " order by banners_group, banners_title";
					$banners_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $banners_query_raw, $banners_query_numrows);
					$banners_query = tep_db_query($banners_query_raw);
					while ($banners = tep_db_fetch_array($banners_query)) {
					  $info_query = tep_db_query("select sum(banners_shown) as banners_shown, sum(banners_clicked) as banners_clicked from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int)$banners['banners_id'] . "'");
					  $info = tep_db_fetch_array($info_query);

					  if ((!isset($HTTP_GET_VARS['bID']) || (isset($HTTP_GET_VARS['bID']) && ($HTTP_GET_VARS['bID'] == $banners['banners_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
						$bInfo_array = array_merge((array)$banners, (array)$info);
						$bInfo = new objectInfo($bInfo_array);
					  }

					  $banners_shown = ($info['banners_shown'] != '') ? $info['banners_shown'] : '0';
					  $banners_clicked = ($info['banners_clicked'] != '') ? $info['banners_clicked'] : '0';

					  if (isset($bInfo) && is_object($bInfo) && ($banners['banners_id'] == $bInfo->banners_id)) {
						echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNER_STATISTICS, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->banners_id) . '\'">' . "\n";
					  } else {
						echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banners['banners_id']) . '\'">' . "\n";
					  }
?>			  
                                 <td>                    <?php echo $banners['banners_title'] ; ?></td>
                                 <td class="text-center"><?php echo $banners['banners_group']; ?></td>
                                 <td class="text-center"><?php echo $banners_shown . ' / ' . $banners_clicked; ?></td>
                                 <td class="text-center">
<?php
                                     if ($banners['status'] == '1') {
                                         echo tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banners['banners_id'] . '&action=setflag&flag=0') . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>';
                                     } else {
                                         echo '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banners['banners_id'] . '&action=setflag&flag=1') . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger');
                                     }
?>
                                 </td>							 
                                 <td class="text-left">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_BANNER_MANAGER,    'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banners['banners_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_BANNER_MANAGER,    'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banners['banners_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_STATISTICS,      'equalizer',     tep_href_link(FILENAME_BANNER_STATISTICS, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banners['banners_id']),                     null, 'info')  . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_BANNER_MANAGER,    'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $banners['banners_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
				
?>
                                   </div> 
				               </td>						
               </tr>	
<?php
                  if (isset($bInfo) && is_object($bInfo) && ($banners['banners_id'] == $bInfo->banners_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_BANNER . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('banners', FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->banners_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $banners['banners_title']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $HTTP_GET_VARS['bID']), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
//tep_image(DIR_WS_CATALOG_IMAGES . $bInfo->banners_image, $bInfo->banner_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"')  .    

										if (!isset($bInfo->status)) $bInfo->status = '1';
										switch ($bInfo->status) {
										 case '0': $in_status = false; $out_status = true; break;
										 case '1':
										 default: $in_status = true; $out_status = false;
									   }                                    
									
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_BANNER . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('edit_banner', FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $HTTP_GET_VARS['bID'] . '&action=update', 'post', 'class="form-horizontal" role="form" enctype="multipart/form-data"', 'id_edit_banners') . PHP_EOL;													   
									   
									   $contents            .= '                       <div class="input-group">' . PHP_EOL;	  
									   $contents            .= '                           <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
									   $contents            .= '                             <label>' . TEXT_EDIT_STATUS . '</label><br />' . PHP_EOL;
									   $contents            .= '                             <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_BANNERS_ACTIVE  . PHP_EOL;
									   $contents            .= '                           ' . tep_draw_radio_field("banners_status", "1", $in_status) . PHP_EOL;
									   $contents            .= '                             </label>' . PHP_EOL;	  
									   $contents            .= '                             <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_BANNERS_NOT_ACTIVE .  PHP_EOL;
									   $contents            .= '                               ' . tep_draw_radio_field("banners_status", "0", $out_status) . PHP_EOL;
									   $contents            .= '                             </label>' . PHP_EOL;
									   $contents            .= '                           </div>' . PHP_EOL;
									   $contents            .= '                       </div>' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_hidden_field('banners_id', $HTTP_GET_VARS['bID']) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('banners_title',       $bInfo->banners_title,        TEXT_BANNERS_TITLE,       'id_input_banner_title' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_TITLE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('banners_url',      $bInfo->banners_url,       TEXT_BANNERS_URL,      'id_input_banner_url' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_URL ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('banners_group', $groups_array, $bInfo->banners_group, TEXT_BANNERS_GROUP, 'id_input_banner_group', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
		                               $contents            .= '                           ' . tep_draw_bs_input_field('new_banners_group',      '',       TEXT_BANNERS_NEW_GROUP,      'id_input_banner_new_group' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_NEW_GROUP ) . PHP_EOL;	                            									   
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   
																	   
                                       $contents            .= '                       <div  class="form-group"> ' . PHP_EOL ;
                                       $contents            .=                             tep_draw_bs_file_field('banners_image' , null, TEXT_BANNERS_IMAGE, 'id_input_banner_image' , 'col-xs-3', 'col-xs-9', 'left') . PHP_EOL;	                 									   
//									   $contents            .= '                           <br />' . PHP_EOL;									   
                                       $contents            .= '                       </div>' . PHP_EOL ;

//									   $contents            .= '                       <div class="clearfix"></div>' . PHP_EOL;									   
                                       $contents            .= '                       <div  class="form-group"> ' . PHP_EOL ;
									   $contents            .=                             tep_draw_bs_input_field('banners_image_local',      (isset($bInfo->banners_image) ? $bInfo->banners_image : $bInfo->banners_image_local),       TEXT_BANNERS_IMAGE_LOCAL,      'id_input_banner_image_save' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_IMAGE_LOCAL, ' disabled ' ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .=                             tep_draw_bs_input_field('banners_image_target',     $bInfo->banners_image,                                                                TEXT_BANNERS_IMAGE_TARGET,      'id_input_banner_image_save_to' ,   'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_IMAGE_TARGET ) . PHP_EOL;										   
                                       $contents            .= '                       </div>' . PHP_EOL ;	
					   
			                           $contents            .= '                       <div class="form-group">' . PHP_EOL ;
			                           $contents            .= '                             <label for="date_scheduled" class="col-xs-3">' . TEXT_BANNERS_SCHEDULED_AT . PHP_EOL ;
			                           $contents            .= '                             </label> ' . PHP_EOL ;
			                           $contents            .= '                             <div class="col-xs-9">'  . tep_draw_bs_input_date('date_scheduled',                                               // name
	                                                                                                                                           tep_date_short($bInfo->date_scheduled),           // value
									                                                                                                           'id="date_scheduled"',            // parameters
									                                                                                                           null,                                                // type
									                                                                                                           true,                                              // reinsert value
									                                                                                                           TEXT_BANNERS_SCHEDULED_AT                             // placeholder
									                                                                                                          ) . PHP_EOL ;
			                           $contents            .= '                              </div>' . PHP_EOL ;
			                           $contents            .= '                       </div>' . PHP_EOL ;										   
									   
			                           $contents            .= '                       <div class="form-group">' . PHP_EOL ;
			                           $contents            .= '                             <label for="expires_date" class="col-xs-3">' . TEXT_BANNERS_EXPIRES_ON . PHP_EOL ;
			                           $contents            .= '                             </label> ' . PHP_EOL ;
			                           $contents            .= '                             <div class="col-xs-9">'  . tep_draw_bs_input_date('expires_date',                                               // name
	                                                                                                                                           tep_date_short($bInfo->expires_date),           // value
									                                                                                                           'id="expires_date"',            // parameters
									                                                                                                           null,                                                // type
									                                                                                                           true,                                              // reinsert value
									                                                                                                           TEXT_BANNERS_EXPIRES_ON                             // placeholder
									                                                                                                          ) . PHP_EOL ;
			                           $contents            .= '                              </div>' . PHP_EOL ;
			                           $contents            .= '                       </div>' . PHP_EOL ;	

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('expires_impressions',       $bInfo->expires_impressions,        TEXT_BANNERS_OR_AT,       'id_input_banner_title' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_OR_AT ) . PHP_EOL;	
//                                       $contents            .= '                           <p>' . TEXT_BANNERS_IMPRESSIONS . '</p>' . PHP_EOL ;									   
                                       $contents            .= '                       </div>' . PHP_EOL ;								   
									   
									   $contents            .= '                       <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;
                                       $contents            .= '                             <label for="banner_text" class="col-xs-3">' . TEXT_BANNERS_HTML_TEXT . PHP_EOL ;								 
                                       $contents            .= '                             </label> ' . PHP_EOL ;									   
									   $contents            .= '                             <div class="col-xs-9">' . tep_draw_textarea_ckeditor('banners_html_text', 'soft', '110', '15', $bInfo->banners_html_text, 'id = "banner_text" class="ckeditor"') . '</div>' . PHP_EOL ;	
                                       $contents            .= '                       </div>' . PHP_EOL ;					
									   
									   $contents            .= '                       <div class="clearfix"></div>' . PHP_EOL;										   
									   $contents            .= '                       <br /><hr>' . PHP_EOL;									   

                                       $contents            .= '                       <div class="row">' . PHP_EOL ;
                                       $contents            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_BANNER_NOTE    . '</div>' . PHP_EOL ;										   
                                       $contents            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_INSERT_NOTE    . '</div>' . PHP_EOL ;									   
                                       $contents            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_EXPIRCY_NOTE   . '</div>' . PHP_EOL ;
                                       $contents            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_SCHEDULE_NOTE  . '</div>' . PHP_EOL ;									   
                                       $contents            .= '                       <?div>' . PHP_EOL ;									   

									   
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&bID=' . $bInfo->banners_id), null, null, 'btn-default text-danger') . PHP_EOL;			
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
                                        
										if ( (function_exists('imagecreate')) && ($dir_ok) && ($banner_extension) ) {
										   $banner_id = $bInfo->banners_id;
                                           $days = '3';
                                           include(DIR_WS_INCLUDES . 'graphs/banner_infobox.php');
			                               $contents .= '                       <li class="list-group-item">' . PHP_EOL; 		
			                               $contents .= '                              ' . tep_image(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banner_id . '.' . $banner_extension) . PHP_EOL;
			                               $contents .= '                       </li>' . PHP_EOL;
										} else {
                                           include(DIR_WS_FUNCTIONS . 'html_graphs.php');											
                                           $contents .= '                       <li class="list-group-item">' . PHP_EOL;		
			                               $contents .= '                              ' . tep_banner_graph_infoBox($bInfo->banners_id, '3') . PHP_EOL;
			                               $contents .= '                       </li>' . PHP_EOL;					
                                        }
										
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . tep_image(DIR_WS_IMAGES . 'graph_hbar_blue.gif', 'Blue', '5', '5') . ' ' . TEXT_BANNERS_BANNER_VIEWS . '<br />' . tep_image(DIR_WS_IMAGES . 'graph_hbar_red.gif', 'Red', '5', '5') . ' ' . TEXT_BANNERS_BANNER_CLICKS . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
										
                                        if ($bInfo->date_scheduled)	{
                                           $contents .= '                       <li class="list-group-item">' . PHP_EOL;		
			                               $contents .= '                              ' . sprintf(TEXT_BANNERS_SCHEDULED_AT_DATE, tep_date_short($bInfo->date_scheduled)). PHP_EOL;
			                               $contents .= '                       </li>' . PHP_EOL;						                          
										}
                                        
										if ($bInfo->expires_date) {										
                                           $contents .= '                       <li class="list-group-item">' . PHP_EOL;		
			                               $contents .= '                              ' . sprintf(TEXT_BANNERS_EXPIRES_AT_DATE, tep_date_short($bInfo->expires_date)). PHP_EOL;
			                               $contents .= '                       </li>' . PHP_EOL;		
                                        } elseif ($bInfo->expires_impressions) {										   
                                           $contents .= '                       <li class="list-group-item">' . PHP_EOL;		
			                               $contents .= '                              ' . sprintf(TEXT_BANNERS_STATUS_CHANGE, tep_date_short($bInfo->date_status_change)). PHP_EOL;
			                               $contents .= '                       </li>' . PHP_EOL;												   
										}
										
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row ' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets				   
				} // end while while ($countries = tep_db_fetch_arra			   
?>				
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $banners_split->display_count($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_BANNERS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $banners_split->display_links($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_BANNER, 'plus', null,'data-toggle="modal" data-target="#new_banner"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  
    </table>
	
      <div class="modal fade"  id="new_banner" role="dialog" aria-labelledby="new_banner" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('newbanner', FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert', 'post', 'class="form-horizontal" role="form" enctype="multipart/form-data"', 'new_banner') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_BANNER; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
                                       $in_status = true; $out_status = false;

									   $contents_new            .= '                       <div class="input-group">' . PHP_EOL;	  
									   $contents_new            .= '                           <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
									   $contents_new            .= '                             <label>' . TEXT_EDIT_STATUS . '</label><br />' . PHP_EOL;
									   $contents_new            .= '                             <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_BANNERS_ACTIVE  . PHP_EOL;
									   $contents_new            .= '                           ' . tep_draw_radio_field("banners_status", "1", $in_status) . PHP_EOL;
									   $contents_new            .= '                             </label>' . PHP_EOL;	  
									   $contents_new            .= '                             <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_BANNERS_NOT_ACTIVE .  PHP_EOL;
									   $contents_new            .= '                               ' . tep_draw_radio_field("banners_status", "0", $out_status) . PHP_EOL;
									   $contents_new            .= '                             </label>' . PHP_EOL;
									   $contents_new            .= '                           </div>' . PHP_EOL;
									   $contents_new            .= '                       </div>' . PHP_EOL;
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new            .= '                           ' . tep_draw_bs_input_field('banners_title',       null,        TEXT_BANNERS_TITLE,       'id_input_banner_title' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_TITLE,       '', true ) . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;									   
									   
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new            .= '                           ' . tep_draw_bs_input_field('banners_url',      null,       TEXT_BANNERS_URL,      'id_input_banner_url' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_URL ) . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;									   
									   
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new            .= '                           ' . tep_draw_bs_pull_down_menu('banners_group', $groups_array, null, TEXT_BANNERS_GROUP, 'id_input_banner_group', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;

                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;									   
		                               $contents_new            .= '                           ' . tep_draw_bs_input_field('new_banners_group',      '',       TEXT_BANNERS_NEW_GROUP,      'id_input_banner_new_group' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_NEW_GROUP ) . PHP_EOL;	                            									   
                                       $contents_new            .= '                       </div>' . PHP_EOL ;									   
									   
																	   
                                       $contents_new            .= '                       <div  class="form-group"> ' . PHP_EOL ;
                                       $contents_new            .=                             tep_draw_bs_file_field('banners_image' , null, TEXT_BANNERS_IMAGE, 'id_input_banner_image' , 'col-xs-3', 'col-xs-9', 'left') . PHP_EOL;	                 									   
//									   $contents_new            .= '                           <br />' . PHP_EOL;									   
                                       $contents_new            .= '                       </div>' . PHP_EOL ;

//									   $contents_new            .= '                       <div class="clearfix"></div>' . PHP_EOL;									   
                                       $contents_new            .= '                       <div  class="form-group"> ' . PHP_EOL ;
									   $contents_new            .=                             tep_draw_bs_input_field('banners_image_local',      '',       TEXT_BANNERS_IMAGE_LOCAL,      'id_input_banner_image_save' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_IMAGE_LOCAL ) . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;									   

                                       $contents_new            .= '                       <div  class="form-group"> ' . PHP_EOL ;									   
									   $contents_new            .=                             tep_draw_bs_input_field('banners_image_target',     '',                                                                TEXT_BANNERS_IMAGE_TARGET,      'id_input_banner_image_save_to' ,   'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_IMAGE_TARGET ) . PHP_EOL;										   
                                       $contents_new            .= '                       </div>' . PHP_EOL ;	
					   
			                           $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;
			                           $contents_new            .= '                             <label for="date_scheduled" class="col-xs-3">' . TEXT_BANNERS_SCHEDULED_AT . PHP_EOL ;
			                           $contents_new            .= '                             </label> ' . PHP_EOL ;
			                           $contents_new            .= '                             <div class="col-xs-9">'  . tep_draw_bs_input_date('date_scheduled',                                               // name
	                                                                                                                                           null,           // value
									                                                                                                           'id="date_scheduled"',            // parameters
									                                                                                                           null,                                                // type
									                                                                                                           true,                                              // reinsert value
									                                                                                                           TEXT_BANNERS_SCHEDULED_AT                             // placeholder
									                                                                                                          ) . PHP_EOL ;
			                           $contents_new            .= '                              </div>' . PHP_EOL ;
			                           $contents_new            .= '                       </div>' . PHP_EOL ;										   
									   
			                           $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;
			                           $contents_new            .= '                             <label for="expires_date" class="col-xs-3">' . TEXT_BANNERS_EXPIRES_ON . PHP_EOL ;
			                           $contents_new            .= '                             </label> ' . PHP_EOL ;
			                           $contents_new            .= '                             <div class="col-xs-9">'  . tep_draw_bs_input_date('expires_date',                                               // name
	                                                                                                                                           null,           // value
									                                                                                                           'id="expires_date"',            // parameters
									                                                                                                           null,                                                // type
									                                                                                                           true,                                              // reinsert value
									                                                                                                           TEXT_BANNERS_EXPIRES_ON                             // placeholder
									                                                                                                          ) . PHP_EOL ;
			                           $contents_new            .= '                              </div>' . PHP_EOL ;
			                           $contents_new            .= '                       </div>' . PHP_EOL ;	

                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new            .= '                           ' . tep_draw_bs_input_field('expires_impressions',       null,        TEXT_BANNERS_OR_AT,       'id_input_banner_title' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_BANNERS_OR_AT ) . PHP_EOL;	
//                                       $contents_new            .= '                           <p>' . TEXT_BANNERS_IMPRESSIONS . '</p>' . PHP_EOL ;									   
                                       $contents_new            .= '                       </div>' . PHP_EOL ;								   
									   
									   $contents_new            .= '                       <br />' . PHP_EOL;	
									   
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;
                                       $contents_new            .= '                             <label for="banner_text" class="col-xs-3">' . TEXT_BANNERS_HTML_TEXT . PHP_EOL ;								 
                                       $contents_new            .= '                             </label> ' . PHP_EOL ;									   
									   $contents_new            .= '                             <div class="col-xs-9">' . tep_draw_textarea_ckeditor('banners_html_text', 'soft', '110', '15', null, 'id = "banner_text" class="ckeditor"') . '</div>' . PHP_EOL ;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;	
									   
								       $contents_new            .= '                       <div class="clearfix"></div>' . PHP_EOL;										   
									   $contents_new            .= '                       <br /><hr>' . PHP_EOL;									   

                                       $contents_new            .= '                       <div class="row">' . PHP_EOL ;
                                       $contents_new            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_BANNER_NOTE    . '</div>' . PHP_EOL ;										   
                                       $contents_new            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_INSERT_NOTE    . '</div>' . PHP_EOL ;									   
                                       $contents_new            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_EXPIRCY_NOTE   . '</div>' . PHP_EOL ;
                                       $contents_new            .= '                           <div class="col-xs-12 col-sm-3">' . TEXT_BANNERS_SCHEDULE_NOTE  . '</div>' . PHP_EOL ;									   
                                       $contents_new            .= '                       <?div>' . PHP_EOL ;										   
                                       
		                               $contents_new_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_new_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_new . $contents_new_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_banner --> 	

 
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>