<?php
/*
  $Id: FAQDesk 2.3.1

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  $fID = (!empty($HTTP_GET_VARS['fID']) ? (int)$HTTP_GET_VARS['fID'] : 0 );
  $action = (!empty($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '' );
  
  $languages = tep_get_languages();  
  switch($action){
      case 'setflag':
          if( !empty($fID) && isset($HTTP_GET_VARS['flag']) && ( $HTTP_GET_VARS['flag'] == '1' || $HTTP_GET_VARS['flag'] == '0' ) ){
              tep_db_query('update '.TABLE_FAQ.' set faq_status='.$HTTP_GET_VARS['flag'].' where faq_id='.$fID.'');
					}
	        tep_redirect(tep_href_link(FILENAME_FAQ, (isset($HTTP_GET_VARS['page']) ? 'page='.$HTTP_GET_VARS['page'].'&' : '' ).'iID='.$fID));
        break;
      case 'insert':
      case 'update':
        $sort_order = (int)$HTTP_POST_VARS['sort_order'];
        $faq_status = !empty($HTTP_POST_VARS['faq_status']) ? '1' : '0';
        $faq_question_array = $HTTP_POST_VARS['faq_question'];
        $faq_answer_array = $HTTP_POST_VARS['faq_answer'];
        $error = false;
        for ($i=0,$n=sizeof($languages);$i<$n;$i++) {
          $lid = $languages[$i]['id'];
          $faq_question_array[$lid] = tep_db_prepare_input($faq_question_array[$lid]);
//          if ($faq_question_array[$lid] == '') {
//            $error = true;
//            $messageStack->add(ERROR_MISSING_QUESTION . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']), 'error');
//          }
          $faq_answer_array[$lid] = tep_db_prepare_input($faq_answer_array[$lid]);
//          if ($faq_answer_array[$lid] == '') {
//            $error = true;
//            $messageStack->add(ERROR_MISSING_ANSWER . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']), 'error');
//          }
        }

        if ($error) {
          $action = (($action == 'insert') ? 'new_faq' : 'edit_faq');
        } else {
          $sql_data_array = array('sort_order' => $sort_order,
	  															'last_modified' => 'now()',
		  														'faq_status' => $faq_status);

          if ($action == 'insert') {
            tep_db_perform(TABLE_FAQ, $sql_data_array);
            $fID = tep_db_insert_id();
          } elseif ($action == 'update') {
            tep_db_perform(TABLE_FAQ, $sql_data_array, 'update', "faq_id = '".(int)$fID."'");
          }
          for ($i=0,$n=sizeof($languages);$i<$n;$i++) {
            $lid = $languages[$i]['id'];

            $sql_data_array = array('faq_question' => $faq_question_array[$lid],
			  														'faq_answer' => $faq_answer_array[$lid]);

            if ($action == 'insert') {
              $insert_sql_data = array('faq_id' => (int)$fID,
                                       'language_id' => (int)$lid);
              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_FAQ_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update') {
              tep_db_perform(TABLE_FAQ_DESCRIPTION, $sql_data_array, 'update', "faq_id = '".(int)$fID."' and language_id = '".(int)$lid . "'");
            }
          }

          tep_redirect(tep_href_link(FILENAME_FAQ, (isset($HTTP_GET_VARS['page']) ? 'page='.$HTTP_GET_VARS['page'].'&' : '') . 'fID=' . $fID));
        }
        break;
      case 'deleteconfirm':
        $faq_id = tep_db_prepare_input($HTTP_GET_VARS['fID']);

        tep_db_query("delete from " . TABLE_FAQ . " where faq_id = '".(int)$fID."'");
        tep_db_query("delete from " . TABLE_FAQ_DESCRIPTION . " where faq_id = '".(int)$fID."'");

        tep_redirect(tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page']));
        break;

	}
  
  require(DIR_WS_INCLUDES . 'template_top.php');   
?>

<script language="JavaScript">
 	function showItem(id){
			var item = document.getElementById(id);
			if (item){
					if (!item.style.display || item.style.display == '' )
					    item.style.display = 'none';
					else
					    item.style.display = '';
			}
	}
</script>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo TABLE_HEADING_FAQ_ID; ?></th>
                   <th>                    <?php echo TABLE_HEADING_FAQ_QUESTION; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_FAQ_STATUS; ?></th>				   
                   <th class="text-center"><?php echo TABLE_HEADING_FAQ_LAST_MODIFIED; ?></th>				  
                   <th class="text-left" ><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php 
				  $faq_query_numrows = 0;
				  $faq_query_raw = 'select f.faq_id, fd.faq_question, fd.faq_answer, f.last_modified, f.sort_order, f.faq_status from '.TABLE_FAQ.' f,  '.TABLE_FAQ_DESCRIPTION.' fd where f.faq_id=fd.faq_id and fd.language_id=' . (int)$languages_id . ' order by f.sort_order, fd.faq_id';
				  $faq_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $faq_query_raw, $faq_query_numrows);
				  $faq_query = tep_db_query($faq_query_raw);
				  while ($faq = tep_db_fetch_array($faq_query)) {
					if ((!isset($HTTP_GET_VARS['fID']) || (isset($HTTP_GET_VARS['fID']) && ($HTTP_GET_VARS['fID'] == $faq['faq_id']))) && !isset($fInfo)) {
					  $fInfo = new objectInfo($faq);
					}

					if (isset($fInfo) && is_object($fInfo) && ($faq['faq_id'] == $fInfo->faq_id)) {
					  echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $faq['faq_id'] . '&action=edit_faq') . '\'">' . "\n";
					} else {
					  echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $faq['faq_id']) . '\'">' . "\n";
					}
?>			  
                                 <td><?php echo $faq['faq_id']; ?></td>
                                 <td><?php echo $faq['faq_question']; ?></td>
								 <td class="text-center">
<?php								 
                                     if ($faq['faq_status'] == '1') {
                                       echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQ, 'action=setflag&flag=0&fID=' . $faq['faq_id'] ) . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . PHP_EOL ;
                                     } else {
                                       echo '                    <a href="' . tep_href_link(FILENAME_FAQ, 'action=setflag&flag=1&fID=' . $faq['faq_id']) . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
                                     }								 
?>									 
								 </td>
                                 <td class="text-center"><?php echo $faq['last_modified']; ?></td>								 
                                 <td class="text-right">
                                     <div class="btn-toolbar" role="toolbar">                  
<?php
      echo '                             <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,      'pencil',    tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $faq['faq_id'] . '&action=edit'),          null, 'warning') . '</div>' . PHP_EOL  . 
           '                             <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,    'remove',    tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $faq['faq_id'] . '&action=confirm'),       null, 'danger') . '</div>' . PHP_EOL ; ?>

                                      </div> 
				                  </td>									 
							</tr>
<?php
                            if (isset($fInfo) && is_object($fInfo) && ($faq['faq_id'] == $fInfo->faq_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_QUESTION_ANSWER . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_bs_form('delete_faq', FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->faq_id . '&action=deleteconfirm', 'post', 'role="form"', 'id_delete_faq') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_HEADING_DELETE_INTRO . '<br />' . $fInfo->faq_id   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->faq_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
									
									   $faq_info_query = tep_db_query('select f.faq_id, fd.language_id,  fd.faq_question, fd.faq_answer, f.last_modified, f.sort_order, f.faq_status from '.TABLE_FAQ.' f,  '.TABLE_FAQ_DESCRIPTION.' fd where f.faq_id=fd.faq_id and f.faq_id='.(int)$fID);
									   $faq_info['faq_question'] = array();
									   $faq_info['faq_answer'] = array();

									   while($faq = tep_db_fetch_array($faq_info_query)){
											$faq_info['status'] = $faq['faq_status'];
											$faq_info['question'][$faq['language_id']] = $faq['faq_question'];
											$faq_info['answer'][$faq['language_id']] = $faq['faq_answer'];
											$faq_info['sort_order'] = $faq['sort_order'];
									   }	

									   if (!isset( $fInfo->faq_status))  $fInfo->faq_status = '1';
									   switch ( $fInfo->faq_status) {
										  case '0': $in_status = false; $out_status = true; break;
										  case '1':
										  default: $in_status = true; $out_status = false;
									   }									   
									
									   $contents_edit_faq_tab1 = '' ;
									   include( DIR_WS_MODULES . 'faq_edit_question.php' ) ;
										
									   $contents_edit_faq_tab2 = '' ;
									   include( DIR_WS_MODULES . 'faq_edit_answer.php' ) ;
									   
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_QUESTION_ANSWER . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('zones', FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->faq_id . '&action=update', 'post', 'class="form-horizontal" role="form"', 'id_edit_faq') . PHP_EOL;													   
									   
 									   $contents            .= '                <div class="input-group">' . PHP_EOL;	  
									   $contents            .= '                    <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
									   $contents            .= '                       <label>' . TITLE_STATUS . '</label><br />' . PHP_EOL;
									   $contents            .= '                       <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_ON  . PHP_EOL;
									   $contents            .= '                           ' . tep_draw_radio_field("faq_status", "1", $in_status) . PHP_EOL;
									   $contents            .= '                       </label>' . PHP_EOL;	  
									   $contents            .= '                        <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_OFF .  PHP_EOL;
									   $contents            .= '                               ' . tep_draw_radio_field("faq_status", "0", $out_status) . PHP_EOL;
									   $contents            .= '                        </label>' . PHP_EOL;
									   $contents            .= '                    </div>' . PHP_EOL;
									   $contents            .= '                </div>' . PHP_EOL;	
									   $contents            .= '                <br />' . PHP_EOL;									   
	  
                                       $contents            .= '                <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('sort_order',       $fInfo->sort_order,        TITLE_SORT,       'id_input_qa_sort_order' ,        'col-xs-3', 'col-xs-9', 'left', TITLE_SORT,       '', true ) . PHP_EOL;	
                                       $contents            .= '                </div>' . PHP_EOL ;	
									   
                                       $contents            .=  '               <div role="tabpanel" id="tab_edit_question_answer">' . PHP_EOL;
                                       $contents            .=  '                    <!-- Nav tabs edit question and answer -->' . PHP_EOL ;
                                       $contents            .=  '                    <ul class="nav nav-tabs" role="tablist" id="tab_edit_question_answer">' . PHP_EOL ;
                                       $contents            .=  '                           <li  id="tab_edit_question_answer" class="active"><a href="#tab_edit_question"       aria-controls="tab_edit_question"             role="tab" data-toggle="tab">' . TEXT_TABS_01 . '</a></li>' . PHP_EOL ;
                                       $contents            .=  '                           <li  id="tab_edit_question_answer"               ><a href="#tab_edit_answer"         aria-controls="tab_edit_answer"               role="tab" data-toggle="tab">' . TEXT_TABS_02 . '</a></li>' . PHP_EOL ;

                                       $contents            .=  '                    </ul>'  . PHP_EOL;

                                       $contents            .=  '                    <!-- Tab panes -->' . PHP_EOL ;
                                       $contents            .=  '                    <div  id="tab_edit_question_answer" class="tab-content">'  . PHP_EOL;
                                       $contents            .=  '                        <div role="tabpanel" class="tab-pane active" id="tab_edit_question">'        . $contents_edit_faq_tab1 . '</div>' . PHP_EOL ;
                                       $contents            .=  '                        <div role="tabpanel" class="tab-pane"        id="tab_edit_answer">'          . $contents_edit_faq_tab2 . '</div>' . PHP_EOL ;
                                       $contents            .=  '                    </div>' . PHP_EOL ;
                                       $contents            .=  '               </div>' . PHP_EOL ;							   
									   $contents            .= '                <br />' . PHP_EOL;								   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'] . '&fID=' . $fInfo->faq_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
/*		                            default: 
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
*/							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="5">' . PHP_EOL .
                                      '                    <div class="row ' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                            }		// end if assets
                  }
?>				  

			  </tbody>
		  </table>
		</div>
   </table>	
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $faq_split->display_count($faq_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_QUESTIONS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $faq_split->display_links($faq_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_FQA, 'plus', null,'data-toggle="modal" data-target="#new_question_answer"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  
    </table>
       <div class="modal fade"  id="new_question_answer" role="dialog" aria-labelledby="new_question_answer" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_faq', FILENAME_FAQ, 'action=insert', 'post', 'role="form"', 'id_new_question_answer') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_QUESTION_ANSWER; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php						   
									    
									   $in_status = false; 
									   $out_status = true;
									   $contents_edit_faq_tab1 = '' ;
									   include( DIR_WS_MODULES . 'faq_edit_question.php' ) ;
										
									   $contents_edit_faq_tab2 = '' ;
									   include( DIR_WS_MODULES . 'faq_edit_answer.php' ) ;
																				
			                           $contents_new            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_QUESTION_ANSWER . '</div>' . PHP_EOL;
			                           $contents_new            .= '          <div class="panel-body">' . PHP_EOL;	

									   $contents_new            .= '                <div class="input-group">' . PHP_EOL;	  
									   $contents_new            .= '                    <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
									   $contents_new            .= '                       <label>' . TITLE_STATUS . '</label><br />' . PHP_EOL;
									   $contents_new            .= '                       <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_ON  . PHP_EOL;
									   $contents_new            .= '                           ' . tep_draw_radio_field("faq_status", "1", $in_status) . PHP_EOL;
									   $contents_new            .= '                       </label>' . PHP_EOL;	  
									   $contents_new            .= '                        <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_OFF .  PHP_EOL;
									   $contents_new            .= '                               ' . tep_draw_radio_field("faq_status", "0", $out_status) . PHP_EOL;
									   $contents_new            .= '                        </label>' . PHP_EOL;
									   $contents_new            .= '                    </div>' . PHP_EOL;
									   $contents_new            .= '                </div>' . PHP_EOL;	
									   $contents_new            .= '                <br />' . PHP_EOL;									   
	  
                                       $contents_new            .= '                <div class="form-group">' . PHP_EOL ;									   
									   $contents_new            .= '                           ' . tep_draw_bs_input_field('sort_order',       null,        TITLE_SORT,       'id_input_qa_sort_order' ,        'col-xs-3', 'col-xs-9', 'left', TITLE_SORT,       '', true ) . PHP_EOL;	
                                       $contents_new            .= '                </div>' . PHP_EOL ;	
									   
                                       $contents_new            .=  '               <div role="tabpanel" id="tab_edit_question_answer">' . PHP_EOL;
                                       $contents_new            .=  '                    <!-- Nav tabs edit question and answer -->' . PHP_EOL ;
                                       $contents_new            .=  '                    <ul class="nav nav-tabs" role="tablist" id="tab_edit_question_answer">' . PHP_EOL ;
                                       $contents_new            .=  '                           <li  id="tab_edit_question_answer" class="active"><a href="#tab_edit_question"       aria-controls="tab_edit_question"             role="tab" data-toggle="tab">' . TEXT_TABS_01 . '</a></li>' . PHP_EOL ;
                                       $contents_new            .=  '                           <li  id="tab_edit_question_answer"               ><a href="#tab_edit_answer"         aria-controls="tab_edit_answer"             role="tab" data-toggle="tab">' . TEXT_TABS_02 . '</a></li>' . PHP_EOL ;

                                       $contents_new            .=  '                    </ul>'  . PHP_EOL;

                                       $contents_new            .=  '                    <!-- Tab panes -->' . PHP_EOL ;
                                       $contents_new            .=  '                    <div  id="tab_edit_question_answer" class="tab-content">'  . PHP_EOL;
                                       $contents_new            .=  '                        <div role="tabpanel" class="tab-pane active" id="tab_edit_question">'        . $contents_edit_faq_tab1 . '</div>' . PHP_EOL ;
                                       $contents_new            .=  '                        <div role="tabpanel" class="tab-pane"        id="tab_edit_answer">'          . $contents_edit_faq_tab2 . '</div>' . PHP_EOL ;
                                       $contents_new            .=  '                    </div>' . PHP_EOL ;
                                       $contents_new            .=  '               </div>' . PHP_EOL ;							   
									   $contents_new            .= '                <br />' . PHP_EOL;	
                                       
		                               $contents_new_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_new_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_new . $contents_new_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . 
				             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_FAQ, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_question_answer -->   
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>