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
        $banned_ip = tep_db_prepare_input($HTTP_POST_VARS['banned_ip']);

        tep_db_query("insert into " . TABLE_BANNED_IP . " (bannedip) values ('" . $banned_ip . "')");

        tep_redirect(tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'save':
        $id_banned = tep_db_prepare_input($HTTP_GET_VARS['biID']);
        $banned_ip = tep_db_prepare_input($HTTP_POST_VARS['banned_ip']);
  

        tep_db_query("update " . TABLE_BANNED_IP . " set bannedip = '" . $banned_ip . "' where id_banned = '" . (int)$id_banned . "'");

        tep_redirect(tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $id_banned));
        break;
      case 'deleteconfirm':
        $id_banned = tep_db_prepare_input($HTTP_GET_VARS['biID']);

        tep_db_query("delete from " . TABLE_BANNED_IP . " where id_banned = '" . (int)$id_banned . "'");

        tep_redirect(tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page']));
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
                   <th>                  <?php echo TABLE_HEADING_BANNED_IP; ?></th>				   
                   <th class="text-left"><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
                 $banip_query_raw = "select id_banned, bannedip from " . TABLE_BANNED_IP ;
                 $banned_ip_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $banip_query_raw, $banned_ip_query_numrows);
                 $banned_ip_query = tep_db_query($banip_query_raw);
                 while ($banned_ip = tep_db_fetch_array($banned_ip_query)) {
                    if ((!isset($HTTP_GET_VARS['biID']) || (isset($HTTP_GET_VARS['biID']) && ($HTTP_GET_VARS['biID'] == $banned_ip['id_banned']))) && !isset($biInfo) ) { //&& (substr($action, 0, 3) != 'new')
                       $biInfo = new objectInfo($banned_ip);
                    }

                    if (isset($biInfo) && is_object($biInfo) && ($banned_ip['id_banned'] == $biInfo->id_banned)) {
                       echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $biInfo->id_banned . '&action=edit') . '\'">' . PHP_EOL ;
                    } else {
                       echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $banned_ip['id_banned']) . '\'">' . "\n";
                    }
?>			  
                                 <td>                    <?php echo $banned_ip['bannedip']; ?></td>
                                 <td class="text-left">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $banned_ip['id_banned'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $banned_ip['id_banned'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>						
               </tr>	
<?php
                  if (isset($biInfo) && is_object($biInfo) && ($banned_ip['id_banned'] == $biInfo->id_banned) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_BANNED_IP . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('zones', FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $biInfo->id_banned . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $banned_ip['bannedip']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $biInfo->id_banned), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('edit_banned_ip', FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $biInfo->id_banned . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_banned_ip') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('banned_ip',       $biInfo->bannedip,        TEXT_INPUT_BANNED_IP,       'id_input_banned_ip' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INPUT_BANNED_IP,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
								   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $biInfo->id_banned), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
/*			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $biInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_NAME . '<br />' . $biInfo->zone_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_CODE . '  ' . $biInfo->zone_code . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_NAME . '  ' . tep_get_country_name( $biInfo->countries_id ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_BANNED_IP_ADDRESS ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
*/						
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="2">' . PHP_EOL .
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
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $banned_ip_split->display_count($banned_ip_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_BANNED_IP); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $banned_ip_split->display_links($banned_ip_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_BANNED_IP, 'plus', null,'data-toggle="modal" data-target="#new_banned_ip"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  
    </table>
       <div class="modal fade"  id="new_banned_ip" role="dialog" aria-labelledby="new_banned_ip" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_banned_ip', FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'] . '&biID=' . $biInfo->id_banned . '&action=insert', 'post', 'class="form-horizontal" role="form"', 'id_new_banned_ip') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_BANNED_IP; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_new            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_BANNED_IP . '</div>' . PHP_EOL;
			                           $contents_new            .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_new            .= '                           ' . tep_draw_bs_input_field('banned_ip',       null,        TEXT_INPUT_BANNED_IP,       'id_input_banned_ip' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INPUT_BANNED_IP,       '', true ) . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;									   
									   $contents_new            .= '                       <br />' . PHP_EOL;	
                                       
		                               $contents_new_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_new_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_new . $contents_new_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_BANNED_IP_ADDRESS, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_banned_ip --> 

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>