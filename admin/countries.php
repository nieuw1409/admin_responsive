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
        $countries_name = tep_db_prepare_input($HTTP_POST_VARS['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);

        tep_db_query("insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) values ('" . tep_db_input($countries_name) . "', '" . tep_db_input($countries_iso_code_2) . "', '" . tep_db_input($countries_iso_code_3) . "', '" . (int)$address_format_id . "')");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES));
        break;
      case 'save':
        $countries_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $countries_name = tep_db_prepare_input($HTTP_POST_VARS['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);

        tep_db_query("update " . TABLE_COUNTRIES . " set countries_name = '" . tep_db_input($countries_name) . "', countries_iso_code_2 = '" . tep_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . tep_db_input($countries_iso_code_3) . "', address_format_id = '" . (int)$address_format_id . "' where countries_id = '" . (int)$countries_id . "'");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries_id));
        break;
      case 'deleteconfirm':
        $countries_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        tep_db_query("delete from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page']));
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
                   <th>                     <?php echo TABLE_HEADING_COUNTRY_NAME; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_COUNTRY_CODES; ?></th>				    			   
                   <th class="text-right" ><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
                 $countries_query_raw = "select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id from " . TABLE_COUNTRIES . " order by countries_name";
                 $countries_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $countries_query_raw, $countries_query_numrows);
                 $countries_query = tep_db_query($countries_query_raw);
                 while ($countries = tep_db_fetch_array($countries_query)) {
                   if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $countries['countries_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                      $cInfo = new objectInfo($countries);
                   }

                   if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id)) {
                      echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=edit') . '\'">' . "\n";
                   } else {
                      echo '<tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id']) . '\'">' . "\n";
                   }
?>
                               <td><?php echo $countries['countries_name']; ?></td>
                               <td class="text-center"><?php echo $countries['countries_iso_code_2'] . '  -  ' .  $countries['countries_iso_code_3']; ?></td>
                               <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>						
               </tr>	
<?php
                  if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $countries['countries_name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_countries') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('countries_name',       $cInfo->countries_name,        TEXT_INFO_COUNTRY_NAME,       'id_input_countries_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_COUNTRY_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('countries_iso_code_2',       $cInfo->countries_iso_code_2,        TEXT_INFO_COUNTRY_CODE_2,       'id_input_countries_iso_code_2' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_COUNTRY_CODE_2,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('countries_iso_code_3',      $cInfo->countries_iso_code_3,       TEXT_INFO_COUNTRY_CODE_3,      'id_input_countries_iso_code_3' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_COUNTRY_CODE_3,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('address_format_id', tep_get_address_formats_for_countries(), $cInfo->address_format_id, TEXT_INFO_ADDRESS_FORMAT, 'address_format_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
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
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_NAME . '<br />' . $cInfo->countries_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_CODE_2 . '  ' . $cInfo->countries_iso_code_2 . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_CODE_3 . '  ' . $cInfo->countries_iso_code_3. PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ADDRESS_FORMAT . '  ' . $cInfo->address_format_id. PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_COUNTRIES ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
				} // end while while ($countries = tep_db_fetch_arra
?>			   
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_COUNTRY, 'plus', null,'data-toggle="modal" data-target="#new_country"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  
	  </table>
<style>
.tab-pane {
    height: 500px;
}
</style>	  
        <div class="modal fade"  id="new_country" role="dialog" aria-labelledby="new_country" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert')
				//tep_draw_bs_form('languages', FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_languages'); ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_COUNTRY; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_COUNTRY . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
//			                           $contents            .= '               ' . tep_draw_bs_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_countries') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('countries_name',       null,        TEXT_INFO_COUNTRY_NAME,       'id_input_countries_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_COUNTRY_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('countries_iso_code_2',       null,        TEXT_INFO_COUNTRY_CODE_2,       'id_input_countries_iso_code_2' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_COUNTRY_CODE_2,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('countries_iso_code_3',      null,       TEXT_INFO_COUNTRY_CODE_3,      'id_input_countries_iso_code_3' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_COUNTRY_CODE_3,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('address_format_id', tep_get_address_formats_for_countries(), null, TEXT_INFO_ADDRESS_FORMAT, 'address_format_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
//                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
//									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
//		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $HTTP_GET_VARS['lID'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_country -->
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
