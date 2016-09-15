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

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['oID'])) $orders_status_id = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $orders_status_name_array = $HTTP_POST_VARS['orders_status_name'];
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('orders_status_name' => tep_db_prepare_input($orders_status_name_array[$language_id]),
                                  'public_flag' => ((isset($HTTP_POST_VARS['public_flag']) && ($HTTP_POST_VARS['public_flag'] == '1')) ? '1' : '0'),
                                  'downloads_flag' => ((isset($HTTP_POST_VARS['downloads_flag']) && ($HTTP_POST_VARS['downloads_flag'] == '1')) ? '1' : '0'),
								  'orders_status_sort_order' => tep_db_prepare_input( $HTTP_POST_VARS['sort_order'] ) );

          if ($action == 'insert') {
            if (empty($orders_status_id)) {
              $next_id_query = tep_db_query("select max(orders_status_id) as orders_status_id from " . TABLE_ORDERS_STATUS . "");
              $next_id = tep_db_fetch_array($next_id_query);
              $orders_status_id = $next_id['orders_status_id'] + 1;
            }

            $insert_sql_data = array('orders_status_id' => $orders_status_id,
                                     'language_id' => $language_id);

            $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

            tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array, 'update', "orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
          }
        }

        if (isset($HTTP_POST_VARS['default']) && ($HTTP_POST_VARS['default'] == 'on')) {
          tep_db_query("update " . $multi_stores_config . " set configuration_value = '" . tep_db_input($orders_status_id) . "' where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end					  
        }

        tep_redirect(tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status_id));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        $orders_status_query = tep_db_query("select configuration_value from " . $multi_stores_config . " where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        $orders_status = tep_db_fetch_array($orders_status_query);

        if ($orders_status['configuration_value'] == $oID) {
          tep_db_query("update " . $multi_stores_config . " set configuration_value = '' where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end				  
        }

        tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . tep_db_input($oID) . "'");

        tep_redirect(tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        $status_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . (int)$oID . "'");
        $status = tep_db_fetch_array($status_query);

        $remove_status = true;
        if ($oID == DEFAULT_ORDERS_STATUS_ID) {
          $remove_status = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_ORDER_STATUS, 'error');
        } elseif ($status['count'] > 0) {
          $remove_status = false;
          $messageStack->add(ERROR_STATUS_USED_IN_ORDERS, 'error');
        } else {
          $history_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_status_id = '" . (int)$oID . "'");
          $history = tep_db_fetch_array($history_query);
          if ($history['count'] > 0) {
            $remove_status = false;
            $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
          }
        }
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1>
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                <th><?php echo TABLE_HEADING_ORDERS_STATUS; ?></th>
                <th class="text-center"><?php echo TABLE_HEADING_PUBLIC_STATUS; ?></th>
                <th class="text-center"><?php echo TABLE_HEADING_DOWNLOADS_STATUS; ?></th>
                <th class="text-center"><?php echo TABLE_HEADING_SORT_ORDER; ?></th> 
                <th class=""><?php echo TABLE_HEADING_ACTION; ?></td>				
                </tr>
              </thead>
              <tbody>
			  
<?php
				  $orders_status_query_raw = "select * from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_sort_order";
				  $orders_status_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_status_query_raw, $orders_status_query_numrows);
				  $orders_status_query = tep_db_query($orders_status_query_raw);
				  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
					if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders_status['orders_status_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
					  $oInfo = new objectInfo($orders_status);
					}

					if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id)) {
					  echo '              <tr class="active"  onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->orders_status_id . '&action=edit') . '\'">' . "\n";
					} else {
					  echo '              <tr                 onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status['orders_status_id']) . '\'">' . "\n";
					}

					if (DEFAULT_ORDERS_STATUS_ID == $orders_status['orders_status_id']) {
					  echo '                <td class="text-warning">' . $orders_status['orders_status_name'] . ' (' . TEXT_DEFAULT . ')</td>' . "\n";
					} else {
					  echo '                <td>' .                      $orders_status['orders_status_name'] . '</td>' . "\n";
					}
	?>	
					<td class="text-center"><?php echo tep_glyphicon((($orders_status['public_flag'] == '1') ? 'ok' : 'remove'), (($orders_status['public_flag'] == '1') ? 'success' : 'danger'))     ; ?></td>
					<td class="text-center"><?php echo tep_glyphicon((($orders_status['downloads_flag'] == '1') ? 'ok' : 'remove'), (($orders_status['downloads_flag'] == '1') ? 'success' : 'danger'))  ; ?></td>
					<td class="text-center"><?php echo $orders_status['orders_status_sort_order'] ; ?></td>		

				   <td class="text-right">
						<div class="btn-toolbar" role="toolbar">                  
	<?php
							echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status['orders_status_id'] . '&action=info'),         null, 'info')    . $test . '</div>' . PHP_EOL .
								 '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status['orders_status_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
								 '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status['orders_status_id'] . '&action=confirm'),     null, 'danger')  . '</div>' . PHP_EOL ; 
	?>				
						</div> 
					</td>				 

	<?php							  
				  if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id) && isset($HTTP_GET_VARS['action'])) { 
									 $alertClass = '';
									 switch ($action) {
	 
										case 'confirm':
										   $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
										   $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_ORDERS_STATUS . '</div>' . PHP_EOL;
										   $contents .= '          <div class="panel-body">' . PHP_EOL;											
										   $alertClass .= ' alert alert-danger';
										   $contents .= '                      ' . tep_draw_bs_form('status', FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->orders_status_id  . '&action=deleteconfirm') ;
										   $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
										   $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $orders_status['orders_status_name']  . '</p>' . PHP_EOL;
										
										   $contents .= '                        </div>' . PHP_EOL;
										   $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
										   $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
																					   tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->orders_status_id), null, null, 'btn-default text-danger') . PHP_EOL;
										   $contents .= '                        </div>' . PHP_EOL;
										   $contents .= '                      </form>' . PHP_EOL;
										  break;									 

										case 'new':			
										   $orders_status_inputs_string = '';
										   $languages = tep_get_languages();
										   $orders_status_inputs_string .= '       <label class=label">' . PHP_EOL  ;									   
										   $orders_status_inputs_string .= '         ' . TEXT_INFO_ORDERS_STATUS_NAME  . PHP_EOL  ;
										   $orders_status_inputs_string .= '       </label>' . PHP_EOL  ;										  
										   for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
											  $orders_status_inputs_string .= '    <div>										' . PHP_EOL  ;   
											  $orders_status_inputs_string .= '       <div class="input-group ">' . PHP_EOL  ;
											  $orders_status_inputs_string .= '           <div class="input-group-addon">' . PHP_EOL ;
											  $orders_status_inputs_string .= '                 ' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'],24,15, null, false) . PHP_EOL ;
											  $orders_status_inputs_string .= '           </div>' . PHP_EOL ;										  
	//                                          $orders_status_inputs_string .= '         ' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], null, null, null, false) . '&nbsp;'. PHP_EOL ;
											  $orders_status_inputs_string .= '         ' . tep_draw_bs_input_field('orders_status_name[' . $languages[$i]['id'] . ']',       tep_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']), '', 'id_input_name' ) . PHP_EOL;	
											  $orders_status_inputs_string .=  '      </div>' . PHP_EOL ;	 
											  $orders_status_inputs_string .=  '   </div>' . PHP_EOL ;
										   }
									
										   $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
										   $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_ORDERS_STATUS . '</div>' . PHP_EOL;
										   $contents            .= '          <div class="panel-body">' . PHP_EOL;			
										   $contents            .= '               ' . tep_draw_bs_form('status', FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert', 'post', 'class="form-horizontal" role="form"',  'id_edit_order_status') . PHP_EOL;	
										   
										   $contents            .=                         $orders_status_inputs_string ;
										   
										   $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
										   $contents            .= '                           ' . tep_draw_bs_input_field('sort_order', $oInfo->orders_status_sort_order,  TEXT_EDIT_SORT_ORDER, 'id_input_sort_order' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_EDIT_SORT_ORDER, '', true ) . PHP_EOL;	
										   $contents            .= '                       </div>' . PHP_EOL ;									   
										   $contents            .= '                           <br />' . PHP_EOL;	
										   
										   $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
										   $contents         .= '                         <div class="panel-heading">' . TEXT_HEADING_SET_PUBLIC_STATUS . '</div>' . PHP_EOL;
										   $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
										   $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										   $contents         .=                                 tep_bs_checkbox_field('public_flag', '1', TEXT_SET_PUBLIC_STATUS, 'id_set_public_standard', $oInfo->public_flag, 'checkbox checkbox-success' ) . ' '  ;									   
										   $contents         .= '                           </div>' . PHP_EOL ;										  
										   $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										   $contents         .=                                 tep_bs_checkbox_field('downloads_flag', '1', TEXT_SET_DOWNLOADS_STATUS, 'id_set_download_standard', $oInfo->downloads_flag, 'checkbox checkbox-success' ) . ' '  ;									   
										   $contents         .= '                           </div>' . PHP_EOL ;										   
										   $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
										   $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  		 
										   
										   if (DEFAULT_ORDERS_STATUS_ID != $oInfo->orders_status_id) {
											  $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
											  $contents         .= '                         <div class="panel-heading">' . TEXT_SET_DEFAULT . '</div>' . PHP_EOL;
											  $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
											  $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
											  $contents         .=                                 tep_bs_checkbox_field('default', null, TEXT_SET_DEFAULT, 'id_set_standard', null, 'checkbox checkbox-success' ) . ' '  ;									   
											  $contents         .= '                           </div>' . PHP_EOL ;										  
											  $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
											  $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  
										   }						   
										   
	 
										   $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
																							  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
										   $contents_footer .= '                      </form>' . PHP_EOL;
										   $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
										   $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel																
										  break;										
		
										case 'edit':
										   $orders_status_inputs_string = '';
										   $languages = tep_get_languages();
										   $orders_status_inputs_string .= '       <label class=label">' . PHP_EOL  ;									   
										   $orders_status_inputs_string .= '         ' . TEXT_INFO_ORDERS_STATUS_NAME  . PHP_EOL  ;
										   $orders_status_inputs_string .= '       </label>' . PHP_EOL  ;										  
										   for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
											  $orders_status_inputs_string .= '    <div>										' . PHP_EOL  ;   
											  $orders_status_inputs_string .= '       <div class="input-group ">' . PHP_EOL  ;
											  $orders_status_inputs_string .= '           <div class="input-group-addon">' . PHP_EOL ;
											  $orders_status_inputs_string .= '                 ' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'],24,15, null, false) . PHP_EOL ;
											  $orders_status_inputs_string .= '           </div>' . PHP_EOL ;										  
											  $orders_status_inputs_string .= '         ' . tep_draw_bs_input_field('orders_status_name[' . $languages[$i]['id'] . ']',       tep_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']), '', 'id_input_name' ) . PHP_EOL;	
											  $orders_status_inputs_string .=  '      </div>' . PHP_EOL ;	 
											  $orders_status_inputs_string .=  '   </div>' . PHP_EOL ;										  
										   }
									
										   $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
										   $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_ORDERS_STATUS . '</div>' . PHP_EOL;
										   $contents            .= '          <div class="panel-body">' . PHP_EOL;			
										   $contents            .= '               ' . tep_draw_bs_form('status', FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->orders_status_id  . '&action=save', 'post', 'class="form-horizontal" role="form"',  'id_edit_order_status') . PHP_EOL;	
										   
										   $contents            .=                         $orders_status_inputs_string ;
										   
										   $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
										   $contents            .= '                           ' . tep_draw_bs_input_field('sort_order', $oInfo->orders_status_sort_order,  TEXT_EDIT_SORT_ORDER, 'id_input_sort_order' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_EDIT_SORT_ORDER, '', true ) . PHP_EOL;	
										   $contents            .= '                       </div>' . PHP_EOL ;									   
										   $contents            .= '                           <br />' . PHP_EOL;	
										   
										   $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
										   $contents         .= '                         <div class="panel-heading">' . TEXT_HEADING_SET_PUBLIC_STATUS . '</div>' . PHP_EOL;
										   $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
										   $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										   $contents         .=                                 tep_bs_checkbox_field('public_flag', '1', TEXT_SET_PUBLIC_STATUS, 'id_set_public_standard', $oInfo->public_flag, 'checkbox checkbox-success' ) . ' '  ;									   
										   $contents         .= '                           </div>' . PHP_EOL ;										  
										   $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										   $contents         .=                                 tep_bs_checkbox_field('downloads_flag', '1', TEXT_SET_DOWNLOADS_STATUS, 'id_set_download_standard', $oInfo->downloads_flag, 'checkbox checkbox-success' ) . ' '  ;									   
										   $contents         .= '                           </div>' . PHP_EOL ;										   
										   $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
										   $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  		 
										   
										   if (DEFAULT_ORDERS_STATUS_ID != $oInfo->orders_status_id) {
											  $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
											  $contents         .= '                         <div class="panel-heading">' . TEXT_SET_DEFAULT . '</div>' . PHP_EOL;
											  $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
											  $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
											  $contents         .=                                 tep_bs_checkbox_field('default', null, TEXT_SET_DEFAULT, 'id_set_standard', null, 'checkbox checkbox-success' ) . ' '  ;									   
											  $contents         .= '                           </div>' . PHP_EOL ;										  
											  $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
											  $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  
										   }						   
										   
	 
										   $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
																							  tep_draw_bs_button(IMAGE_CANCEL, 'remove',  tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->orders_status_id), null, null, 'btn-default text-danger') . PHP_EOL;			
										   $contents_footer .= '                      </form>' . PHP_EOL;
										   $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
										   $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
										  break;	

									 }
		
									 $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
									 $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
			
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
				  }	 // end if (isset($cInfo) && is_object($cInfo) && ($custo   
  }
?>							
			  </tbody>			
          </table>
        </div>		  
    </table>
   <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <div class="row">
            <div class="smallText hidden-xs mark" valign="top"><?php echo $orders_status_split->display_count($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?></div>
            <div class="smallText mark" style="text-align: right;"><?php echo $orders_status_split->display_links($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>	   
		  </div>
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row"tr>
 
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_INSERT, 'plus', null,'data-toggle="modal" data-target="#new_order_status"') ; ?>  
            </div>
	       </div>
<?php
          }
?>		  
       <div class="modal fade"  id="new_order_status" role="dialog" aria-labelledby="new_order_status" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('status', FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert') ; ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_ORDERS_STATUS; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
                                       $orders_status_inputs_string = '';
                                       $languages = tep_get_languages();
                                       $orders_status_inputs_string .= '       <label class=label">' . PHP_EOL  ;									   
                                       $orders_status_inputs_string .= '         ' . TEXT_INFO_ORDERS_STATUS_NAME  . PHP_EOL  ;
                                       $orders_status_inputs_string .= '       </label>' . PHP_EOL  ;										  
                                       for ($i=0, $n=sizeof($languages); $i<$n; $i++) { 
                                          $orders_status_inputs_string .= '       <div class="input-group ">' . PHP_EOL  ;
										  $orders_status_inputs_string .= '           <div class="input-group-addon">' . PHP_EOL ;
										  $orders_status_inputs_string .= '                 ' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'],24,15, null, false) . PHP_EOL ;
										  $orders_status_inputs_string .= '           </div>' . PHP_EOL ;										  
                                          $orders_status_inputs_string .= '         ' . tep_draw_bs_input_field('orders_status_name[' . $languages[$i]['id'] . ']', null, null, 'id_input_name_new'.$i, null, null, null, $languages[$i]['name'] ) . PHP_EOL;	
                                          $orders_status_inputs_string .=  '      </div>' . PHP_EOL ;	 
										  $orders_status_inputs_string .=  '   </div>' . PHP_EOL ;											  
                                       }
								
 			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_ORDERS_STATUS . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('status', FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->orders_status_id  . '&action=save', 'post', 'class="form-horizontal" role="form"',  'id_edit_order_status') . PHP_EOL;	
									   
                                       $contents            .=                         $orders_status_inputs_string ;
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('sort_order', '',  TEXT_EDIT_SORT_ORDER, 'id_input_sort_order' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_EDIT_SORT_ORDER, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <hr>' . PHP_EOL;	
									   $contents            .= '                       <div class="clearfix"></div>' . PHP_EOL;
                                       
    	                               $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents         .= '                         <div class="panel-heading">' . TEXT_HEADING_SET_PUBLIC_STATUS . '</div>' . PHP_EOL;
			                           $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
                                       $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
									   $contents         .=                                 tep_bs_checkbox_field('public_flag', '1', TEXT_SET_PUBLIC_STATUS, 'id_set_public_standard', '', 'checkbox checkbox-success' ) . ' '  ;									   
                                       $contents         .= '                           </div>' . PHP_EOL ;										  
                                       $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
									   $contents         .=                                 tep_bs_checkbox_field('downloads_flag', '1', TEXT_SET_DOWNLOADS_STATUS, 'id_set_download_standard', '', 'checkbox checkbox-success' ) . ' '  ;									   
                                       $contents         .= '                           </div>' . PHP_EOL ;										   
		                               $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
		                               $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  		 
									   
									   if (DEFAULT_ORDERS_STATUS_ID != $oInfo->orders_status_id) {
			                              $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents         .= '                         <div class="panel-heading">' . TEXT_SET_DEFAULT . '</div>' . PHP_EOL;
			                              $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
                                          $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										  $contents         .=                                 tep_bs_checkbox_field('default', null, TEXT_SET_DEFAULT, 'id_set_standard', null, 'checkbox checkbox-success' ) . ' '  ;									   
                                          $contents         .= '                           </div>' . PHP_EOL ;										  
		                                  $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
		                                  $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  
									   }						   
		                               
 
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL , 'remove', tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents .= '           </div>' . PHP_EOL; // end div 	panel					
		                               $contents        .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents        .= '           </div>' . PHP_EOL; // end div 	panel	
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents  ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
 
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_language -->


    </table>	
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>