<?php
/*
  $Id$ catalog/admin/popup_manager.php v.1.00 2013/04/10
  
  by De Dokta

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $popup_extension = tep_banner_image_extension();

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          tep_set_popup_status($HTTP_GET_VARS['bID'], $HTTP_GET_VARS['flag']);

//          $messageStack->add_session(SUCCESS_POPUP_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_POPUP_MANAGER, '&bID=' . $HTTP_GET_VARS['bID']));
        break;
		
      case 'insert':
      case 'update':
	  
// BOF multi stores
        $products_to_stores = '@,';
		$products_to_stores_images = '' ;
        if ( $HTTP_POST_VARS['stores_products'] ) { // if any of the checkboxes are checked
           foreach($HTTP_POST_VARS['stores_products'] as $val) {
              $products_to_stores .= tep_db_prepare_input($val).','; 
			  $products_to_stores_images = $val ;
           } // end foreach
        }
        $products_to_stores = substr($products_to_stores,0,strlen($products_to_stores)-1); // remove last comma
// EOF multi stores
        if (isset($HTTP_POST_VARS['popups_id'])) $popups_id = tep_db_prepare_input($HTTP_POST_VARS['popups_id']);
        $popups_title = tep_db_prepare_input($HTTP_POST_VARS['popups_title']);
//        $popups_url = tep_db_prepare_input($HTTP_POST_VARS['popups_url']);
        $popups_html_text = tep_db_prepare_input($HTTP_POST_VARS['popups_html_text']);
        $popups_image_local = tep_db_prepare_input($HTTP_POST_VARS['popups_image_local']);
        $popups_image_target = tep_db_prepare_input($HTTP_POST_VARS['popups_image_target']);
        $db_image_location = '';
        $expires_date = tep_db_prepare_input( tep_date_raw( $_POST['expires_date']) );		
        $date_scheduled = tep_db_prepare_input( tep_date_raw( $_POST['date_scheduled']) );		

        $popup_error = false;

        if ($popup_error == false) {
          $sql_data_array = array('popups_title' => $popups_title,
                                  'expires_date' => $expires_date,
                                  'date_scheduled' => $date_scheduled,
								  'stores_id'=> $products_to_stores);
          if ($action == 'insert') {
            $insert_sql_data = array('date_added' => 'now()',
                                     'status' => '1');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_POPUPS, $sql_data_array);

            $popups_id = tep_db_insert_id();

//            $messageStack->add_session(SUCCESS_POPUP_INSERTED, 'success');
          } elseif ($action == 'update') {
//            $insert_sql_data = array('status' => '0');

//            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          
            tep_db_perform(TABLE_POPUPS, $sql_data_array, 'update', "popups_id = '" . (int)$popups_id . "'");

//            $messageStack->add_session(SUCCESS_POPUP_UPDATED . $expires_date . 'eric', 'success');
          }
          
           $languages = tep_get_languages();
           for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              $language_id = $languages[$i]['id'];

              $sql_data_array = array('popups_html_text' => tep_db_prepare_input($_POST['popups_html_text'][$language_id]));

              if ($action == 'insert') {
                $insert_sql_data = array('popups_id' => $popups_id,
                                        'language_id' => $language_id);

                $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                tep_db_perform(TABLE_POPUPS_DESCRIPTION, $sql_data_array);
              } elseif ($action == 'update') {
                tep_db_perform(TABLE_POPUPS_DESCRIPTION, $sql_data_array, 'update', "popups_id = '" . (int)$popups_id . "' and language_id = '" . (int)$language_id . "'");
              }
           }

           tep_redirect(tep_href_link(FILENAME_POPUP_MANAGER, 'bID=' . $popups_id));
        } else {
          $action = 'new';
        }
        break;
      case 'deleteconfirm':
        if (isset($HTTP_POST_VARS['popups_id'])) {
          $delete_popups = tep_db_prepare_input($HTTP_POST_VARS['popups_id']);
          tep_db_query("delete from " . TABLE_POPUPS . " where popups_id = '" . tep_db_input($delete_popups) . "'");
        }
        
        $messageStack->add_session(SUCCESS_POPUP_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_POPUP_MANAGER));
        break;
    }
  }


  require(DIR_WS_INCLUDES . 'template_top.php');
// BOF multi stores
    $stores_group_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
    $header = false;
    if (!tep_db_num_rows($stores_group_query) > 0) {
      $messageStack->add_session(ERROR_ALL_STORES_DELETED, 'error');
   } else {
     while ($stores_stores = tep_db_fetch_array($stores_group_query)) {
       $_stores_name[] = $stores_stores;
     }
   }

  if ($action == 'new') {
    $form_action = 'insert';

    $parameters = array('expires_date' => '',
                        'date_scheduled' => '',
                        'popups_title' => '',
                        'popups_image' => '',
                        'popups_html_text' => '',
						'stores_id' => '' );
//                        'popups_url' => '',						

    $bInfo = new objectInfo($parameters);

    if (isset($HTTP_GET_VARS['popups_id'])) {
      $form_action = 'update';

      $bID = tep_db_prepare_input($HTTP_GET_VARS['popups_id']);
      $popup_query = tep_db_query("select po.popups_id,               po.popups_title, po.popups_image, pod.popups_html_text, po.status, po.stores_id, date_format(po.date_scheduled, '%Y/%m/%d') as date_scheduled, date_format(po.expires_date, '%Y/%m/%d') as expires_date,  po.date_status_change from " . TABLE_POPUPS . " po, " . TABLE_POPUPS_DESCRIPTION . " pod where po.popups_id = '" . (int)$bID . "' and pod.popups_id = po.popups_id and pod.language_id = '" . (int)$languages_id . "'");
      $popup = tep_db_fetch_array($popup_query);

      $bInfo->objectInfo($popup);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $bInfo->objectInfo($HTTP_POST_VARS);
    }
// BOF multi stores
    $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
    while ($stores_stores = tep_db_fetch_array($stores_query)) {
      $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
    }	
    $products_to_stores_array = explode(',', $bInfo->stores_id); // multi stores
    $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores	
// EOF multi stores
										$languages = tep_get_languages();

			                            $contents .= '  <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL; 	
			                            $contents .=          tep_draw_bs_form('new_popup', FILENAME_POPUP_MANAGER, 'action=' . $form_action, 'post', 'class="form-inline" role="form" enctype="multipart/form-data"', 'id_form_new_popup' ); 
		  
		                                if ($form_action == 'update') {
		                                   $contents .= tep_draw_hidden_field('popups_id', $bID) . PHP_EOL;  
                                        }										   
 	  
			                            $contents .= '           <div class="panel panel-primary"> 		     	  ' . PHP_EOL; 	
			                            $contents .= '			 <div class="panel-heading">'. HEADING_TITLE . '</div>	' . PHP_EOL; 			   
			                            $contents .= '			 <div class="panel-body">       		 ' . PHP_EOL; 	
			                            $contents .= '			    <div class="from-group  has-feedback">' . PHP_EOL; 	
			                            $contents .=                    tep_draw_bs_input_field('popups_title', $popup['popups_title'], TEXT_POPUPS_TITLE, 'input_popup_title', 'control-label col-xs-2', 'col-xs-10', 'left', TEXT_POPUPS_TITLE, '', true  ) . PHP_EOL; 	
			                            $contents .= '				</div>' . PHP_EOL; 	
										
			                            $contents .= '              <div class="form-group">' . PHP_EOL .
										             '                                    <label for="date_scheduled" class="col-sm-2 control-label">' . TEXT_POPUPS_SCHEDULED_AT . PHP_EOL . 								 
										             '                                    </label> ' . PHP_EOL .										 
										             '                                    <div class="col-sm-10">'  . tep_draw_bs_input_date('date_scheduled',                                               // name
	                                                                                                                tep_date_short($popup['date_scheduled']),           // value
									                                                                                'id="date_scheduled"',            // parameters
									                                                                                 null,                                                // type
									                                                                                 true,                                              // reinsert value
									                                                                                 TEXT_POPUPS_SCHEDULED_AT                             // placeholder
									                                                                        ) . PHP_EOL .
										              '                                    </div>' . PHP_EOL .										
													  '             </div>' .  PHP_EOL ;
													  
			                            $contents .= '              <div class="form-group">' . PHP_EOL .
										             '                                    <label for="expires_date" class="col-sm-2 control-label">' . TEXT_POPUPS_EXPIRES_ON . PHP_EOL . 								 
										             '                                    </label> ' . PHP_EOL .										 
										             '                                    <div class="col-sm-10">'  . tep_draw_bs_input_date('expires_date',                                               // name
	                                                                                                                tep_date_short($popup['expires_date'] ),           // value
									                                                                                'id="expires_date"',            // parameters
									                                                                                 null,                                                // type
									                                                                                 true,                                              // reinsert value
									                                                                                 TEXT_POPUPS_EXPIRES_ON                             // placeholder
									                                                                        ) . PHP_EOL .
										              '                                    </div>' . PHP_EOL .										
													  '             </div>' .  PHP_EOL ;													  
													  
			                            $contents .= '			 </div> <!-- end div panel body -->' . PHP_EOL; 	
			                            $contents .= '		    </div> <!-- end div panel -->' . PHP_EOL;  												
		 

 									
 											
			                            $contents .= '              <div class="panel panel-primary"> 		     	  ' . PHP_EOL; 	
			                            $contents .= '			      <div class="panel-heading">'. TEXT_POPUP_TO_STORES . '</div>	' . PHP_EOL; 			   
			                            $contents .= '			      <div class="panel-body">       		 ' . PHP_EOL; 														
// BOF multi stores
                                        $products_to_stores_array = explode(',', $popup['stores_id'] ); // multi stores
                                        $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores	
// EOF multi stores			 
			                            $contents .= '                  <div class="form-group">' . PHP_EOL ;
                                        foreach ($_stores_name as $key => $products_stores) { //hide_customers_group

//                                             echo tep_draw_checkbox_field('stores_products[' . $products_stores['stores_id'] . ']',  $products_stores['stores_id'] , (in_array($products_stores['stores_id'], $products_to_stores_array)) ? 1: 0);
                                             $contents .= tep_bs_checkbox_field('stores_products[' . $products_stores['stores_id'] . ']',
                                     											 $products_stores['stores_id'], 
																				 $products_stores['stores_name'], 
																				 $products_stores['stores_id'], 
																				 ((in_array($products_stores['stores_id'], $products_to_stores_array)) ? 1: 0), 
																				 'checkbox checkbox-success', 
																				 '', 
																				 '', 
																				 'right' )	;
			                                 $contents .= '                </div>' . PHP_EOL ;		  
                                        } // end foreach ($_hide_customers_group as $key => $products_stores)								
			                            $contents .= '			    </div> <!-- end div panel body  active stores -->' . PHP_EOL; 	
			                            $contents .= '		       </div> <!-- end div panel active stores -->' . PHP_EOL; 

 											
			                            $contents .= '              <div class="panel panel-primary"> 		     	  ' . PHP_EOL; 	
			                            $contents .= '			      <div class="panel-heading">'. TEXT_POPUPS_TEXT . '</div>	' . PHP_EOL; 			   
			                            $contents .= '			      <div class="panel-body">       		 ' . PHP_EOL;                                         

				                        $contents_popup_manager_tab = '' ; 
				                        include( DIR_WS_MODULES . 'popup_manager_tab_1.php' ) ;	
                			            $contents .=                 $contents_popup_manager_tab ;
			                            $contents .= '              <br />' . PHP_EOL ;										
			                            $contents .= '              <br />' . PHP_EOL ;	
			                            $contents .= '			    </div> <!-- end div panel body  text popup -->' . PHP_EOL; 	
			                            $contents .= '		       </div> <!-- end div panel text popup-->' . PHP_EOL; 												
	

			                            $contents .=  tep_draw_bs_button(IMAGE_SAVE, 'ok', null, 'primary') . 
										              tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_POPUP_MANAGER, (isset($HTTP_GET_VARS['bID']) ? 'bID=' . $HTTP_GET_VARS['bID'] : ''))) ;
										
			                            $contents .= '           </form>			' . PHP_EOL; 	
			                            $contents .= '   </table>		' . PHP_EOL; 	
										

		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
		
		                         $contents .=  '</div>' . PHP_EOL ;
		

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
									  


  } else {
	  
	  // show all popup text on screen
?>
      <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1>
		    <div class="clearfix"></div>
      </div><!-- page-header-->
      <div class="table-responsive">
       <table class="table table-condensed table-striped">
        <thead>
                <tr class="heading-row">
                <th><?php echo TABLE_HEADING_POPUPS; ?></td>
                <th><?php echo TABLE_HEADING_STATUS; ?></td>
				<th><?php echo TABLE_HEADING_ACTION; ?></th>
                </tr>
         </thead>
         <tbody>		  
<?php 
            $rows = 0;
            $popups_count = 0;
            $popups_query = tep_db_query('select popups_id, popups_title, popups_image, status, expires_date, date_status_change, date_scheduled, date_added, stores_id from ' . TABLE_POPUPS . ' order by popups_title');
            $selected_item ='';
            while ($popups = tep_db_fetch_array($popups_query)) {
               $popups_count++;  
               $rows++;

//               if ( ((!$HTTP_GET_VARS['popups_id']) || (@$HTTP_GET_VARS['popups_id'] == $popups['popups_id'])) && (!$selected_item) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new') ) {
               if ((!isset($HTTP_GET_VARS['bID']) || (isset($HTTP_GET_VARS['bID']) && ($HTTP_GET_VARS['bID'] == $popups['popups_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {	
//                  $selected_item = $popups;
                    $selected_item = new objectInfo($popups);
               }
//               if ( (is_array($selected_item)) && ($popups['popups_id'] == $selected_item['popups_id']) ) {
               if (isset($selected_item) && is_object($selected_item) && ($popups['popups_id'] == $selected_item->popups_id) ) {				   
                  echo '              <tr class="active"  onclick="document.location.href=\'' . tep_href_link(FILENAME_POPUP_MANAGER, 'popups_id=' . $popups['popups_id']) . '\'">' . "\n";
               } else {
                  echo '              <tr                 onclick="document.location.href=\'' . tep_href_link(FILENAME_POPUP_MANAGER, 'popups_id=' . $popups['popups_id']) . '\'">' . "\n";
               }
?>

               <td><?php echo '&nbsp;' . $popups['popups_title']; ?></td>

               <td class="text-center">
<?php
                    if ($popups['status']== '1') {
                        echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_POPUP_MANAGER, '&bID=' . $popups['popups_id'] . '&action=setflag&flag=0') . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . "\n";
                    } else {
                        echo '                    <a href="' . tep_href_link(FILENAME_POPUP_MANAGER, '&bID=' . $popups['popups_id'] . '&action=setflag&flag=1')  . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . "\n";
                    }
?>
               </td>
			   <td class="text-right">
                    <div class="btn-toolbar" role="toolbar">                  
<?php
                        echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_POPUP_MANAGER, 'bID=' . $popups['popups_id'] . '&action=info'),         null, 'info')    . '</div>' . PHP_EOL .
		                     '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_POPUP_MANAGER, 'popups_id=' . $popups['popups_id'] . '&action=new'),    null, 'warning') . '</div>' . PHP_EOL .
//							 tep_href_link(FILENAME_POPUP_MANAGER, 'bID=' . $popups['popups_id']  . '&action=update'),    null, 'warning') . '</div>' . PHP_EOL .
                             '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_POPUP_MANAGER, 'bID=' . $popups['popups_id']  . '&action=confirm'),     null, 'danger')  . '</div>' . PHP_EOL ; 
?>				
                    </div> 
				</td>
   			  </tr>	
<?php							  
              if (isset($selected_item) && is_object($selected_item) && ($popups['popups_id'] == $selected_item->popups_id) && isset($HTTP_GET_VARS['action'])) { 
 	                             $alertClass = '';
                                 switch ($action) {
									 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_DELETE_INTRO . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
//		                               $contents .= '                      ' . tep_draw_form('customers', FILENAME_POPUP_MANAGER, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $selected_item->popup_id . '&action=deleteconfirm') . PHP_EOL;
		                               $contents .= '                      ' . tep_draw_form('popups', FILENAME_POPUP_MANAGER, tep_get_all_get_params(array('cID', 'action')) .'cID=' . $popups['popups_id']. '&action=deleteconfirm')  ;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . $popups['popups_title']  . '</p>' . PHP_EOL;
 									
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_POPUP_MANAGER, tep_get_all_get_params(array('biD', 'action')) . 'bID=' . $selected_item->popup_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
                                      break;									 
          		
		                            case 'info':
// bof multi stores	 
                                        $products_to_stores_array = explode(',', $popups['stores_id']); // multi stores
                                        $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores
                                        $product_to_stores =  '';

										foreach ($_stores_name as $key => $products_stores) { //hide_customers_group
//                                        for ($i = 0; $i < count($stores_array); $i++) {
                                           if (in_array($products_stores['stores_id'], $products_to_stores_array)) {  
                                              $product_to_stores .= $products_stores['stores_name'] . '<br />'; 
                                           }
                                        } // end for ($i = 0; $i < count($stores_array); $i++)

 
										
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
 
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 			
			                            $contents .= '                              ' . TEXT_SCHEDULED . ' ' . tep_date_short($selected_item->date_scheduled) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_EXPIRES . ' ' . tep_date_short($selected_item->expires_date) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
//			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 	
//			                            $contents .= '                              ' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($info['date_last_logon']) . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;										
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_POPUP_TO_STORES . ' <br />' . $product_to_stores . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;					
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_POPUP_MANAGER ), null, null, 'btn-default text-danger') . PHP_EOL;										
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
      <div class="row">
           <div class="col-xs-12 col-md-12 text-right">
            <small> <?php echo TEXT_POPUPS . '<span class="badge badge-info pull-right">' . $popups_count . '</span>'; ?></small>
          </div>	  
 	    
         <br />       
         <div class="col-xs-12 col-md-7">
<?php 
             echo tep_draw_bs_button(IMAGE_NEW_POPUP, 'plus', tep_href_link(FILENAME_POPUP_MANAGER, 'action=new')); 
?>
         </div>
      </div><!--row-->	  
<?php
  }
?>
    </table>
<?php

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
