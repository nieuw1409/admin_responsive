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
        $tax_class_title = tep_db_prepare_input($HTTP_POST_VARS['tax_class_title']);
        $tax_class_description = tep_db_prepare_input($HTTP_POST_VARS['tax_class_description']);

        tep_db_query("insert into " . TABLE_TAX_CLASS . " (tax_class_title, tax_class_description, date_added) values ('" . tep_db_input($tax_class_title) . "', '" . tep_db_input($tax_class_description) . "', now())");

        tep_redirect(tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'save':
        $tax_class_id = tep_db_prepare_input($HTTP_GET_VARS['tID']);
        $tax_class_title = tep_db_prepare_input($HTTP_POST_VARS['tax_class_title']);
        $tax_class_description = tep_db_prepare_input($HTTP_POST_VARS['tax_class_description']);

        tep_db_query("update " . TABLE_TAX_CLASS . " set tax_class_id = '" . (int)$tax_class_id . "', tax_class_title = '" . tep_db_input($tax_class_title) . "', tax_class_description = '" . tep_db_input($tax_class_description) . "', last_modified = now() where tax_class_id = '" . (int)$tax_class_id . "'");

        tep_redirect(tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tax_class_id));
        break;
      case 'deleteconfirm':
        $tax_class_id = tep_db_prepare_input($HTTP_GET_VARS['tID']);

        tep_db_query("delete from " . TABLE_TAX_CLASS . " where tax_class_id = '" . (int)$tax_class_id . "'");

        tep_redirect(tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page']));
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
                   <th><?php echo TABLE_HEADING_TAX_CLASSES; ?></th>		   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>				   

                </tr>
              </thead>
              <tbody>
<?php
              $classes_query_raw = "select tax_class_id, tax_class_title, tax_class_description, last_modified, date_added from " . TABLE_TAX_CLASS . " order by tax_class_title";
              $classes_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $classes_query_raw, $classes_query_numrows);
              $classes_query = tep_db_query($classes_query_raw);
              while ($classes = tep_db_fetch_array($classes_query)) {
                 if ((!isset($HTTP_GET_VARS['tID']) || (isset($HTTP_GET_VARS['tID']) && ($HTTP_GET_VARS['tID'] == $classes['tax_class_id']))) && !isset($tcInfo) && (substr($action, 0, 3) != 'new')) {
                    $tcInfo = new objectInfo($classes);
                 }

                 if (isset($tcInfo) && is_object($tcInfo) && ($classes['tax_class_id'] == $tcInfo->tax_class_id)) {
                     echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=edit') . '\'">' . PHP_EOL;
                 } else {
                     echo'<tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $classes['tax_class_id']) . '\'">' . PHP_EOL;
    }
?>
                              <td><?php echo $classes['tax_class_title']; ?></td>
                              <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $classes['tax_class_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $classes['tax_class_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $classes['tax_class_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				              </td>					
              </tr>
<?php
				  
                  if (isset($tcInfo) && is_object($tcInfo) && ($classes['tax_class_id'] == $tcInfo->tax_class_id) && isset($HTTP_GET_VARS['action'])) {  
 	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_TAX_CLASS . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('classes', FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $classes['tax_class_title']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tcInfo->tax_class_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_TAX_CLASS . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('classes', FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_tax_classes') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_class_title',       $tcInfo->tax_class_title,              TEXT_INFO_CLASS_TITLE,             'id_input_title' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CLASS_TITLE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_class_description', $tcInfo->tax_class_description,        TEXT_INFO_CLASS_DESCRIPTION,       'id_input_description' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CLASS_DESCRIPTION, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
/*                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('image',      $tcInfo->image,       TEXT_INFO_LANGUAGE_IMAGE,      'id_input_image' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_IMAGE,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('directory',  $tcInfo->directory,   TEXT_INFO_LANGUAGE_DIRECTORY, 'id_input_directory' ,    'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_DIRECTORY,  '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('sort_order', $tcInfo->sort_order,  TEXT_INFO_LANGUAGE_SORT_ORDER, 'id_input_sort_order' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_SORT_ORDER, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	                                     
*/			   
		                               
 
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tcInfo->tax_class_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . TEXT_INFO_LANGUAGE_NAME . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_CLASS_DESCRIPTION . ' <br />' . $tcInfo->tax_class_description . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;				                          
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_DATE_ADDED . ' <br />' . $tcInfo->date_added . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;												
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_LAST_MODIFIED . ' <br />' . $tcInfo->last_modified . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;												
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_TAX_CLASSES ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
                              }	// end if action
              } // end while class on screen
?>			  
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $classes_split->display_count($classes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $classes_split->display_links($classes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
             </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_TAX_CLASS, 'plus', null,'data-toggle="modal" data-target="#new_tax_class"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  

    </table>	

        <div class="modal fade"  id="new_tax_class" role="dialog" aria-labelledby="new_tax_class" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('classes', FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert')
				//tep_draw_bs_form('languages', FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_languages'); ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_TAX_CLASS; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
// BOF multi stores
                                       $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
                                       while ($stores_stores = tep_db_fetch_array($stores_query)) {
                                           $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
                                       }	 
// EOF multi stores 
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_INSERT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' .  PHP_EOL;	
 	                                   
									   $contents            .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '                         <div class="panel-body">' . PHP_EOL;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_class_title',       null,        TEXT_INFO_CLASS_TITLE,             'id_input_title' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CLASS_TITLE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_class_description', null,        TEXT_INFO_CLASS_DESCRIPTION,       'id_input_description' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CLASS_DESCRIPTION, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;												   
									   $contents            .= '                               <br />' . PHP_EOL;	
		                               $contents            .= '                         </div>' . PHP_EOL; // end div std 	panel body
		                               $contents            .= '                       </div>' . PHP_EOL; // end div std 	panel									   					  										  
 
		                               $contents        .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents        .= '           </div>' . PHP_EOL; // end div 	panel	
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . 
				             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_TAX_CLASSES, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #tax class -->	

 
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
