<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
// BOF Separate Pricing Per Customer
  $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id");
  $cg_array = array();
  while ($customers_group = tep_db_fetch_array($customers_group_query)) {
    $cg_array[] = array('id' => $customers_group['customers_group_id'], 'customers_group_name' => $customers_group['customers_group_name']);
  }

// EOF Separate Pricing Per Custom

// BOF multi stores
  $stores_group_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
  $stores_array = array();
  while ($stores_active = tep_db_fetch_array($stores_group_query)) {
    $stores_array[] = array('id' => $stores_active['stores_id'], 'stores_name' => $stores_active['stores_name']);
  }
  if ( tep_db_num_rows( $stores_group_query ) > 1 ) $stores_multi_present = 'true' ;
 
 // this is now defined as a global variable since we need it everywhere
 global $cg_array, $stores_array ;
 
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
    // BOF Separate Pricing Per Customer
    case 'update_cgs':
    $customer_groups_chosen = '';
    if (isset($_POST['send_to_cgs'])) {
	    foreach ($_POST['send_to_cgs'] as $key => $value) {
		    $customer_groups_chosen .= tep_db_prepare_input($value).',';
	    }
	    // remove last comma
	    $customer_groups_chosen = substr($customer_groups_chosen,0,strlen($customer_groups_chosen)-1);
    } // end if (isset($HTTP_POST['send_to_cgs']))	    
	    $newsletter_id = tep_db_prepare_input($HTTP_GET_VARS['nID']);
	    
	    tep_db_query("update " . TABLE_NEWSLETTERS . " set send_to_customer_groups = '" . $customer_groups_chosen . "' where newsletters_id = '" . (int)$newsletter_id . "'");

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']));
        break;
    // EOF Separate Pricing Per Customer	
      case 'lock':
      case 'unlock':
        $newsletter_id = tep_db_prepare_input($HTTP_GET_VARS['nID']);
        $status = (($action == 'lock') ? '1' : '0');

        tep_db_query("update " . TABLE_NEWSLETTERS . " set locked = '" . $status . "' where newsletters_id = '" . (int)$newsletter_id . "'");

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']));
        break;
      case 'insert':
      case 'update':
        if (isset($HTTP_POST_VARS['newsletter_id'])) $newsletter_id = tep_db_prepare_input($HTTP_POST_VARS['newsletter_id']);
        $newsletter_module = tep_db_prepare_input($HTTP_POST_VARS['module']);
        $title = tep_db_prepare_input($HTTP_POST_VARS['title']);
        $content = tep_db_prepare_input($HTTP_POST_VARS['content']);
        $text_unsubscribe = tep_db_prepare_input($HTTP_POST_VARS['text_unsubscribe']);		
		
		$customer_groups_chosen = '';
		if (isset($_POST['send_to_cgs'])) {
			foreach ($_POST['send_to_cgs'] as $key => $value) {
				$customer_groups_chosen .= tep_db_prepare_input($value).',';
			}
			// remove last comma
			$customer_groups_chosen = substr($customer_groups_chosen,0,strlen($customer_groups_chosen)-1);
		} // end if (isset($HTTP_POST['send_to_cgs']))			
			
		$stores_chosen = '';
		if (isset($_POST['send_to_stores'])) {
			foreach ($_POST['send_to_stores'] as $key => $value) {
				$stores_chosen .= tep_db_prepare_input($value).',';
			}
			// remove last comma
			$stores_chosen = substr($stores_chosen,0,strlen($stores_chosen)-1);
		} // end if (isset($HTTP_POST['send_to_cgs']))
		if( $stores_multi_present == 'false') $stores_chosen = $multi_stores_id ;
	

        $newsletter_error = false;
        if (empty($title)) {
          $messageStack->add(ERROR_NEWSLETTER_TITLE, 'error');
          $newsletter_error = true;
        }

        if (empty($newsletter_module)) {
          $messageStack->add(ERROR_NEWSLETTER_MODULE, 'error');
          $newsletter_error = true;
        }

        if ($newsletter_error == false) {
          $sql_data_array = array('title' => $title,
                                  'content' => $content,
                                  'module' => $newsletter_module,
								  'send_to_customer_groups' => $customer_groups_chosen,
								  'send_to_stores' => $stores_chosen,
								  'newsletters_unsubscribe_text' => $text_unsubscribe);

          if ($action == 'insert') {
            $sql_data_array['date_added'] = 'now()';
            $sql_data_array['status'] = '0';
            $sql_data_array['locked'] = '0';

            tep_db_perform(TABLE_NEWSLETTERS, $sql_data_array);
            $newsletter_id = tep_db_insert_id();
          } elseif ($action == 'update') {
            tep_db_perform(TABLE_NEWSLETTERS, $sql_data_array, 'update', "newsletters_id = '" . (int)$newsletter_id . "'");
          }

          tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'nID=' . $newsletter_id));
        } else {
          $action = 'new';
        }
        break;
      case 'deleteconfirm':
        $newsletter_id = tep_db_prepare_input($HTTP_GET_VARS['nID']);

        tep_db_query("delete from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$newsletter_id . "'");

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
      case 'new': if (!isset($HTTP_GET_VARS['nID'])) break;
      case 'send':
      case 'confirm_send':
        $newsletter_id = tep_db_prepare_input($HTTP_GET_VARS['nID']);

        $check_query = tep_db_query("select locked from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$newsletter_id . "'");
        $check = tep_db_fetch_array($check_query);

        if ($check['locked'] < 1) {
          switch ($action) {
            case 'delete': $error = ERROR_REMOVE_UNLOCKED_NEWSLETTER; break;
            case 'new': $error = ERROR_EDIT_UNLOCKED_NEWSLETTER; break;
            case 'send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
            case 'confirm_send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
          }

          $messageStack->add_session($error, 'error');

          tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']));
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
<?php
if ($action == 'confirm_send') {
    $nID = tep_db_prepare_input($HTTP_GET_VARS['nID']);
// BOF Separate Pricing Per Customer
//    $newsletter_query = tep_db_query("select newsletters_id, title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$nID . "'");
    $newsletter_query = tep_db_query("select newsletters_id, title, content, module, send_to_customer_groups, send_to_stores from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$nID . "'");
// EOF Separate Pricing Per Customer	
    $newsletter = tep_db_fetch_array($newsletter_query);

    $nInfo = new objectInfo($newsletter);

    include(DIR_WS_LANGUAGES . $language . '/modules/newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    $module_name = $nInfo->module;
// BOF Separate Pricing Per Customer
//    $module = new $module_name($nInfo->title, tep_add_base_ref($nInfo->content) );
    $module = new $module_name($nInfo->title, $nInfo->content, $nInfo->send_to_customer_groups, $nInfo->send_to_stores );
// EOF Separate Pricing Per Customer	
	$contents             = '       <div class="panel panel-primary">' . PHP_EOL ;
	$contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_SENDING_NEWSLETTER . '</div>' . PHP_EOL; 
	$contents            .= '          <div class="panel-body">' . PHP_EOL;	
	$contents            .= '            <div class="well"><p>' . TEXT_PLEASE_WAIT . '</p></div>' . PHP_EOL ;
	$contents            .= '          </div>'. PHP_EOL ;
	$contents            .= '       </div>'. PHP_EOL ;	
	
	echo $contents ; 
	
    $send_mail = $module->send($nInfo->newsletters_id);
  
	$contents             = '       <div class="panel panel-primary">' . PHP_EOL ;
	$contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_SEND_NEWSLETTER . '</div>' . PHP_EOL; 
	$contents            .= '          <div class="panel-body">' . PHP_EOL;	
	$contents            .= '            <div class="well"><p>' . TEXT_FINISHED_SENDING_EMAILS . '<br />' . sprintf( TEXT_INFO_SEND_NEWSLETTERS, (int)$send_mail ) . '</p></div>' . PHP_EOL ;
	$contents            .= 	            tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']), null, null, 'btn-default text-info') ;
	$contents            .= '          </div>'. PHP_EOL ;
	$contents            .= '       </div>'. PHP_EOL ;	
	
	echo $contents ; 
	  
?>
 </table>
<?php
  } else {
?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
 

            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                         <?php echo TABLE_HEADING_NEWSLETTERS; ?></th>
                   <th class="text-center">     <?php echo TABLE_HEADING_SIZE; ?></th>	
                   <th class="text-right">      <?php echo TABLE_HEADING_MODULE; ?></td>
                   <th class="text-center">     <?php echo TABLE_HEADING_SENT; ?></td>
                   <th class="text-center">     <?php echo TABLE_HEADING_STATUS; ?></td>				   
                   <th class="text-left">       <?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
// BOF Separate Pricing Per Customer
			//    $newsletters_query_raw = "select newsletters_id, title, length(content) as content_length, module, date_added, date_sent, status, locked from " . TABLE_NEWSLETTERS . " order by date_added desc";
				$newsletters_query_raw = "select newsletters_id, title, length(content) as content_length, module, date_added, date_sent, status, locked, send_to_customer_groups,content, send_to_stores, newsletters_unsubscribe_text from " . TABLE_NEWSLETTERS . " order by date_added desc";
// EOF Separate Pricing Per Customer	
				$newsletters_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsletters_query_raw, $newsletters_query_numrows);
				$newsletters_query = tep_db_query($newsletters_query_raw);
				while ($newsletters = tep_db_fetch_array($newsletters_query)) {
					if ((!isset($HTTP_GET_VARS['nID']) || (isset($HTTP_GET_VARS['nID']) && ($HTTP_GET_VARS['nID'] == $newsletters['newsletters_id']))) && !isset($nInfo) && (substr($action, 0, 3) != 'new')) {
						$nInfo = new objectInfo($newsletters);
					  }

					  if (isset($nInfo) && is_object($nInfo) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id) ) {
						echo '<tr class="active"  onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '\'">' . "\n";
					  } else {
						echo '<tr                 onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters['newsletters_id']) . '\'">' . "\n";
					  }
	?>
					<td>                              <?php echo  $newsletters['title']; ?></td>
					<td class="text-right">           <?php echo number_format($newsletters['content_length']) . ' bytes'; ?></td>
					<td class="text-right">           <?php echo $newsletters['module']; ?></td>
					<td class="text-center">          <?php if ($newsletters['status'] == '1') { echo tep_glyphicon('ok-sign', 'success') ; } else { echo tep_glyphicon('remove-sign', 'danger'); } ?></td>
					<td class="text-center">          <?php if ($newsletters['locked'] > 0) { echo tep_glyphicon('lock', 'success'); } else { echo tep_glyphicon('folder-open', 'danger'); } ?></td>
					<td class="text-left">
						<div class="btn-toolbar" role="toolbar">                  
	<?php
								if ($newsletters['locked'] > 0) {
									   echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_PREVIEW,         'info',          tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters[ 'newsletters_id' ] . '&action=info'),     null, 'info') . '</div>' . PHP_EOL .
											'<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters[ 'newsletters_id' ] . '&action=edit'),     null, 'warning') . '</div>' . PHP_EOL .										
											'<div class="btn-group">' . tep_glyphicon_button(IMAGE_SEND,            'send',          tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters[ 'newsletters_id' ] . '&action=send'),     null, 'success') . '</div>' . PHP_EOL .
											'<div class="btn-group">' . tep_glyphicon_button(IMAGE_UNLOCK,          'folder-open',   tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters[ 'newsletters_id' ] . '&action=unlock'),   null, 'danger') . '</div>' . PHP_EOL .											 
											'<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters[ 'newsletters_id' ] . '&action=delete'),   null, 'danger')  . '</div>' . PHP_EOL ;
								} else {
									   echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_PREVIEW,         'info',          tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters[ 'newsletters_id' ] . '&action=info'),     null, 'info') . '</div>' . PHP_EOL .		   
											'<div class="btn-group">' . tep_glyphicon_button(IMAGE_LOCK ,           'lock',          tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $newsletters[ 'newsletters_id' ] . '&action=lock'),     null, 'success') . '</div>' . PHP_EOL ;							 		 
								}											 
	?>
						</div> 
					</td>	
					       </tr>
<?php

                    if (isset($nInfo) && is_object($nInfo) && ($newsletters[ 'newsletters_id' ] == $nInfo->newsletters_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'delete':
									
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_NEWSLETTER . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('newsletters', FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $nInfo->newsletters_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $newsletters[ 'title' ]   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
									   
                                      break;	

									case 'send':									  
									case 'confirm':									
                                       include(DIR_WS_LANGUAGES . $language . '/modules/newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
                                       include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
                                       $module_name = $nInfo->module;

									   // BOF Separate Pricing Per Customer	
									   $module = new $module_name($nInfo->title, $nInfo->content, 
									                              $nInfo->send_to_customer_groups, $nInfo->send_to_stores );
                                       // EOF Separate Pricing Per Customer
                                       if ( $action =='send') {
                                         if ($module->show_choose_audience) { 
                                           $contents .=  $module->choose_audience(); 
									     } else { 
                                           $contents .=  $module->confirm();									   
									     }
								       }
									   if ( $action =='confirm') {
                                          $contents .=  $module->confirm();											   
									   }									   
                                       break;
								    
									case 'edit':
									   $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
									   $directory_array = array();
									   if ($dir = dir(DIR_WS_MODULES . 'newsletters/')) {
										  while ($file = $dir->read()) {
											if (!is_dir(DIR_WS_MODULES . 'newsletters/' . $file)) {
											  if (substr($file, strrpos($file, '.')) == $file_extension) {
												$directory_array[] = $file;
											  }
											}
										  }
										  sort($directory_array);
										  $dir->close();
									   }

									   for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
										  $modules_array[] = array('id' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')), 'text' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')));
									   }										   
							   
									   
 			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_NEWSLETTER . '</div>' . PHP_EOL; 
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('newsletter', FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $nInfo->newsletters_id . '&action=update', 'post', 'class="form-horizontal" role="form"', 'id_edit_newsletters') . PHP_EOL;													   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_hidden_field('newsletter_id', $nInfo->newsletters_id)  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('module', $modules_array, $nInfo->module, TEXT_NEWSLETTER_MODULE, 'input_modeles_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;											   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('title', $nInfo->title,        TEXT_NEWSLETTER_TITLE,       'id_input_newsletter_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NEWSLETTER_TITLE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;

//                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
//									   $contents            .= '                           ' . tep_draw_bs_input_field('text_unsubscribe', $nInfo->newsletters_unsubscribe_text,        TEXT_NEWSLETTER_UNSUBSCRIBE,       'id_input_newsletter_unsubscribe' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NEWSLETTER_UNSUBSCRIBE,       '', true ) . PHP_EOL;	
//                                       $contents            .= '                       </div>' . PHP_EOL ;	

                                       $contents            .= '		               <div class="col-md-6 col-xs-12">' . PHP_EOL ;
                                       $contents            .= '                           <ul class="list-group">' . PHP_EOL ;
                                       $contents            .= '                              <li class="list-group-item"><-SYS_UNSUBSCRIBE_LINK-> :  ' . POPUP_UNSUBSCRIBE_LINK . '</li>' . PHP_EOL ;
			    		  
                                       $contents            .= '                           </ul>' . PHP_EOL ;
                                       $contents            .= '                       </div>' . PHP_EOL ;								   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;
                                       $contents            .= '                                  <div class="col-xs-12">' . tep_draw_textarea_ckeditor(content, 'soft', '100%', '40',$nInfo->content, 'id = "id_input_content" class="ckeditor"') . PHP_EOL ;
                                       $contents            .= '                                  </div>' . PHP_EOL ;
                                       $contents            .= '                       </div>' . PHP_EOL;	


									   $contents            .= '           <div class="col-xs-12 col-xs-12 col-md-12">' . PHP_EOL;	   

									   $contents_edit_orders_tab1    = '    <div class="panel panel-info">' . PHP_EOL ;
 
									   $stores_array_in_db = explode(',',$nInfo->send_to_stores);
										  
									    for ($y = 0; $y < sizeof($stores_array); $y++) { 
										  $contents_edit_orders_tab1 .= '   <div class="form-group">' . PHP_EOL ;
										  $contents_edit_orders_tab1 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;
										  $contents_edit_orders_tab1 .=                                      tep_bs_checkbox_field('send_to_stores[' . $y . ']', $stores_array[$y]['id'], $stores_array[$y]['stores_name'], 'id_send_to_stores', (in_array ($stores_array[$y]['id'], $stores_array_in_db)) ?  1 : 0, 
												                                                                                           'checkbox checkbox-success') . PHP_EOL ;	
										  $contents_edit_orders_tab1 .= '       </div>'. PHP_EOL  ;	
										  $contents_edit_orders_tab1 .= '   </div>'. PHP_EOL  ;					
									   }
 									   
									   $contents_edit_orders_tab1    .= '    </div>' . PHP_EOL ;

									   $contents_edit_orders_tab2    = '    <div class="panel panel-info">' . PHP_EOL ;
									   
//									   $contents_edit_orders_tab2    .= '     <div class="well well-info">' . PHP_EOL ;									   
//									   $contents_edit_orders_tab2    .= '           <p>' . TEXT_INFO_CUST_GROUPS . '</p>' .PHP_EOL ;									   
//									   $contents_edit_orders_tab2    .= '     </div>' . PHP_EOL ;	
									   
		                               $cg_array_in_db = explode(',', $nInfo->send_to_customer_groups);																	 
									   for ($z = 0; $z < sizeof($cg_array); $z++) { 
										    $contents_edit_orders_tab2 .= '   <div class="form-group">' . PHP_EOL ;
											$contents_edit_orders_tab2 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;
											$contents_edit_orders_tab2 .=                                      tep_bs_checkbox_field('send_to_cgs[' . $z . ']', $cg_array[$z]['id'], $cg_array[$z]['customers_group_name'], 'id_check_active_cust_groups', (in_array ($cg_array[$z]['id'], $cg_array_in_db)) ?  1 : 0, 
												                                                                                           'checkbox checkbox-success') . PHP_EOL ;	
											$contents_edit_orders_tab2 .= '       </div>'. PHP_EOL  ;	
											$contents_edit_orders_tab2 .= '   </div>'. PHP_EOL  ;											
									   }																			   
									   $contents_edit_orders_tab2    .= '    </div>' . PHP_EOL ;
								  
                                       if ( $stores_multi_present == 'true' ) { // 1 store automatic active 									  
										   $contents .=  '                                 <div role="tabpanel" id="tab_edit_newsletters">' . PHP_EOL;
										   $contents .=  '                                    <!-- Nav tabs edit newsletters -->' . PHP_EOL ;
										   $contents .=  '                                    <ul class="nav nav-tabs" role="tablist" id="tab_edit_newsletters">' . PHP_EOL ;
										   $contents .=  '                                       <li  id="tab_edit_newsletters" class="active"><a href="#tab_edit_newsletters_stores"      aria-controls="tab_edit_news_letters_stores"         role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_NEWSLETTER_01 . '</a></li>' . PHP_EOL ;
										   $contents .=  '                                       <li  id="tab_edit_newsletters">               <a href="#tab_edit_newsletter_cust_group"   aria-controls="tab_edit_news_letters_cust_group"     role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_NEWSLETTER_02 . '</a></li>'  . PHP_EOL;	  
									  
										   $contents .=  '                                    </ul>'  . PHP_EOL;

										   $contents .=  '                                    <!-- Tab panes -->' . PHP_EOL ;
										   $contents .=  '                                    <div  id="tab_edit_newsletters" class="tab-content">'  . PHP_EOL;
										   $contents .=  '                                        <div role="tabpanel" class="tab-pane active" id="tab_edit_newsletters_stores">'           . $contents_edit_orders_tab1 . '</div>' . PHP_EOL ;
										   $contents .=  '                                        <div role="tabpanel" class="tab-pane"        id="tab_edit_newsletter_cust_group">'         . $contents_edit_orders_tab2 . '</div>' . PHP_EOL ;
										   $contents .=  '                                    </div>' . PHP_EOL ; 
										   $contents .=  '                                 </div>' . PHP_EOL ;	
									   } else {
										   $contents .=  $contents_edit_orders_tab2	;
									   }
									   
									   $contents .= '                         </div>' . PHP_EOL;	// class="col-xs-12 col

									   $contents .= '                         <br />' . PHP_EOL;	 
										   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
									   
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  PHP_EOL ;
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] ), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   

                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-info">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $nInfo->title . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_NEWSLETTER_CONTENT . '<br />' . $nInfo->content . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_CODE_2 . '  ' . $cInfo->countries_iso_code_2 . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;					
 //                                       $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_CODE_3 . '  ' . $cInfo->countries_iso_code_3. PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
 //                                       $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_ADDRESS_FORMAT . '  ' . $cInfo->address_format_id. PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;											
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
                } // while ($newsletters = tep_db_fetch_array(  
?>
			  </tbody>
			</table>
  </table>
  <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $newsletters_split->display_count($newsletters_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS) ; ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $newsletters_split->display_links($newsletters_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'])             ; ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_NEWSLETTER, 'plus', null,'data-toggle="modal" data-target="#new_newsletter"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  
  </table>  
      <div class="modal fade"  id="new_newsletter" role="dialog" aria-labelledby="new_newsletter" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_newsletter', FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $nInfo->newsletters_id . '&action=insert', 'post', 'class="form-horizontal" role="form"', 'id_new_newsletters') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_NEWSLETTER; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
									   $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
									   $directory_array = array();
									   if ($dir = dir(DIR_WS_MODULES . 'newsletters/')) {
										  while ($file = $dir->read()) {
											if (!is_dir(DIR_WS_MODULES . 'newsletters/' . $file)) {
											  if (substr($file, strrpos($file, '.')) == $file_extension) {
												$directory_array[] = $file;
											  }
											}
										  }
										  sort($directory_array);
										  $dir->close();
									   }	

									   for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
										  $modules_array[] = array('id' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')), 'text' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')));
									   }	

									   
 			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_NEWSLETTER . '</div>' . PHP_EOL; 
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
//			                           $contents            .= '               ' . tep_draw_bs_form('newsletter', FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $nInfo->newsletters_id . '&action=update', 'post', 'class="form-horizontal" role="form"', 'id_edit_newsletters') . PHP_EOL;													   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_hidden_field('newsletter_id', $nInfo->newsletters_id)  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('module', $modules_array, null, TEXT_NEWSLETTER_MODULE, 'input_modeles_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;											   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('title', null,        TEXT_NEWSLETTER_TITLE,       'id_input_newsletter_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NEWSLETTER_TITLE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;
                                       $contents            .= '                                  <div class="col-xs-12">' . tep_draw_textarea_ckeditor(content, 'soft', '100%', '40',null, 'id = "id_input_content" class="ckeditor"') . PHP_EOL ;
                                       $contents            .= '                                  </div>' . PHP_EOL ;
                                       $contents            .= '                       </div>' . PHP_EOL;
									   $contents            .= '           <div class="col-xs-12 col-xs-12 col-md-12">' . PHP_EOL;	   

									   $contents_edit_orders_tab1    = '    <div class="panel panel-info">' . PHP_EOL ;
 
									   $stores_array_in_db = explode(',',$nInfo->send_to_stores);
										  
									    for ($y = 0; $y < sizeof($stores_array); $y++) { 
										  $contents_edit_orders_tab1 .= '   <div class="form-group">' . PHP_EOL ;
										  $contents_edit_orders_tab1 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;
										  $contents_edit_orders_tab1 .=                                      tep_bs_checkbox_field('send_to_stores[' . $y . ']', $stores_array[$y]['id'], $stores_array[$y]['stores_name'], 'id_send_to_stores', 0, 
												                                                                                           'checkbox checkbox-success') . PHP_EOL ;	
										  $contents_edit_orders_tab1 .= '       </div>'. PHP_EOL  ;	
										  $contents_edit_orders_tab1 .= '   </div>'. PHP_EOL  ;					
									   }
 									   
									   $contents_edit_orders_tab1    .= '    </div>' . PHP_EOL ;

									   $contents_edit_orders_tab2    = '    <div class="panel panel-info">' . PHP_EOL ;
		                               $cg_array_in_db = explode(',', $nInfo->send_to_customer_groups);																	 
									   for ($z = 0; $z < sizeof($cg_array); $z++) { 
										    $contents_edit_orders_tab2 .= '   <div class="form-group">' . PHP_EOL ;
											$contents_edit_orders_tab2 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;
											$contents_edit_orders_tab2 .=                                      tep_bs_checkbox_field('send_to_cgs[' . $z . ']', $cg_array[$z]['id'], $cg_array[$z]['customers_group_name'], 'id_check_active_cust_groups', 0, 
												                                                                                           'checkbox checkbox-success') . PHP_EOL ;	
											$contents_edit_orders_tab2 .= '       </div>'. PHP_EOL  ;	
											$contents_edit_orders_tab2 .= '   </div>'. PHP_EOL  ;											
									   }																			   
									   $contents_edit_orders_tab2    .= '    </div>' . PHP_EOL ;
								  
                                       if ( $stores_multi_present == 'true' ) { // 1 store automatic active 									  
										   $contents .=  '                                 <div role="tabpanel" id="tab_edit_newsletters">' . PHP_EOL;
										   $contents .=  '                                    <!-- Nav tabs edit newsletters -->' . PHP_EOL ;
										   $contents .=  '                                    <ul class="nav nav-tabs" role="tablist" id="tab_edit_newsletters">' . PHP_EOL ;
										   $contents .=  '                                       <li  id="tab_edit_newsletters" class="active"><a href="#tab_edit_newsletters_stores"      aria-controls="tab_edit_news_letters_stores"         role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_NEWSLETTER_01 . '</a></li>' . PHP_EOL ;
										   $contents .=  '                                       <li  id="tab_edit_newsletters">               <a href="#tab_edit_newsletter_cust_group"   aria-controls="tab_edit_news_letters_cust_group"     role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_NEWSLETTER_02 . '</a></li>'  . PHP_EOL;	  
	//									   $contents_edit_orders_edit .=  '    <li  id="tab_edit_newsletters">               <a href="#tab_edit_orders_cust_invoi_address"    aria-controls="tab_edit_orders_customer_invoice_adr"        role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_ORDER_03 . '</a></li>'  . PHP_EOL;	  
	//									   $contents_edit_orders_edit .=  '    <li  id="tab_edit_newsletters">               <a href="#tab_edit_orders_cust_payment_method"  aria-controls="tab_edit_orders_customer_payment"            role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_ORDER_04 . '</a></li>'  . PHP_EOL;	  
									  
										   $contents .=  '                                    </ul>'  . PHP_EOL;

										   $contents .=  '                                    <!-- Tab panes -->' . PHP_EOL ;
										   $contents .=  '                                    <div  id="tab_edit_newsletters" class="tab-content">'  . PHP_EOL;
										   $contents .=  '                                        <div role="tabpanel" class="tab-pane active" id="tab_edit_newsletters_stores">'           . $contents_edit_orders_tab1 . '</div>' . PHP_EOL ;
										   $contents .=  '                                        <div role="tabpanel" class="tab-pane"        id="tab_edit_newsletter_cust_group">'         . $contents_edit_orders_tab2 . '</div>' . PHP_EOL ;
	//									   $contents_edit_orders_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_orders_cust_invoi_address">'         . $contents_edit_orders_tab3 . '</div>' . PHP_EOL ;			  
	//									   $contents_edit_orders_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_orders_cust_payment_method">'       . $contents_edit_orders_tab4 . '</div>' . PHP_EOL ;		  
										   $contents .=  '                                    </div>' . PHP_EOL ; 
										   $contents .=  '                                 </div>' . PHP_EOL ;	
									   } else {
										   $contents .=  $contents_edit_orders_tab2	;
									   }
									   
									   $contents .= '                         </div>' . PHP_EOL;	// class="col-xs-12 col

									   $contents .= '                         <br />' . PHP_EOL;	 
										   
									   $contents            .= '                       <br />' . PHP_EOL;	
									   
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents . $contents_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] )); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_newsletter -->   

<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>