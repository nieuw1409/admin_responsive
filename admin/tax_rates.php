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
        $tax_zone_id = tep_db_prepare_input($HTTP_POST_VARS['tax_zone_id']);
        $tax_class_id = tep_db_prepare_input($HTTP_POST_VARS['tax_class_id']);
        $tax_rate = tep_db_prepare_input($HTTP_POST_VARS['tax_rate']);
        $tax_description = tep_db_prepare_input($HTTP_POST_VARS['tax_description']);
        $tax_priority = tep_db_prepare_input($HTTP_POST_VARS['tax_priority']);

        tep_db_query("insert into " . TABLE_TAX_RATES . " (tax_zone_id, tax_class_id, tax_rate, tax_description, tax_priority, date_added) values ('" . (int)$tax_zone_id . "', '" . (int)$tax_class_id . "', '" . tep_db_input($tax_rate) . "', '" . tep_db_input($tax_description) . "', '" . tep_db_input($tax_priority) . "', now())");

        tep_redirect(tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'save':
        $tax_rates_id = tep_db_prepare_input($HTTP_GET_VARS['tID']);
        $tax_zone_id = tep_db_prepare_input($HTTP_POST_VARS['tax_zone_id']);
        $tax_class_id = tep_db_prepare_input($HTTP_POST_VARS['tax_class_id']);
        $tax_rate = tep_db_prepare_input($HTTP_POST_VARS['tax_rate']);
        $tax_description = tep_db_prepare_input($HTTP_POST_VARS['tax_description']);
        $tax_priority = tep_db_prepare_input($HTTP_POST_VARS['tax_priority']);

        tep_db_query("update " . TABLE_TAX_RATES . " set tax_rates_id = '" . (int)$tax_rates_id . "', tax_zone_id = '" . (int)$tax_zone_id . "', tax_class_id = '" . (int)$tax_class_id . "', tax_rate = '" . tep_db_input($tax_rate) . "', tax_description = '" . tep_db_input($tax_description) . "', tax_priority = '" . tep_db_input($tax_priority) . "', last_modified = now() where tax_rates_id = '" . (int)$tax_rates_id . "'");

        tep_redirect(tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $tax_rates_id));
        break;
      case 'deleteconfirm':
        $tax_rates_id = tep_db_prepare_input($HTTP_GET_VARS['tID']);

        tep_db_query("delete from " . TABLE_TAX_RATES . " where tax_rates_id = '" . (int)$tax_rates_id . "'");

        tep_redirect(tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
  
                                 $classes_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
                                 while ($classes = tep_db_fetch_array($classes_query)) {
	                               $array_tax_rates[]  =   array('id' => $classes['tax_class_id'],
                                                                 'text' => $classes['tax_class_title']);
                                 }	
                                 $zones_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
                                 while ($zones = tep_db_fetch_array($zones_query)) {
	                               $array_geo_zones[]  =   array('id' => $zones['geo_zone_id'],
                                                                 'text' => $zones['geo_zone_name']);
                                 }		  
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
                   <th><?php echo TABLE_HEADING_TAX_RATE_PRIORITY; ?></th>
                   <th><?php echo TABLE_HEADING_TAX_CLASS_TITLE; ?></th>				   
                   <th><?php echo TABLE_HEADING_ZONE; ?></th>				   
                   <th><?php echo TABLE_HEADING_TAX_RATE; ?></th>					   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>				   

                </tr>
              </thead>
              <tbody>
<?php
                $rates_query_raw = "select r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id, r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified from " . TABLE_TAX_CLASS . " tc, " . TABLE_TAX_RATES . " r left join " . TABLE_GEO_ZONES . " z on r.tax_zone_id = z.geo_zone_id where r.tax_class_id = tc.tax_class_id";
                $rates_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $rates_query_raw, $rates_query_numrows);
                $rates_query = tep_db_query($rates_query_raw);
                while ($rates = tep_db_fetch_array($rates_query)) {
                   if ((!isset($HTTP_GET_VARS['tID']) || (isset($HTTP_GET_VARS['tID']) && ($HTTP_GET_VARS['tID'] == $rates['tax_rates_id']))) && !isset($trInfo) && (substr($action, 0, 3) != 'new')) {
                      $trInfo = new objectInfo($rates);
                   }

                  if (isset($trInfo) && is_object($trInfo) && ($rates['tax_rates_id'] == $trInfo->tax_rates_id)) {
                     echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $trInfo->tax_rates_id . '&action=edit') . '\'">' . PHP_EOL;
                  } else {
                     echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $rates['tax_rates_id']) . '\'">' . PHP_EOL;
                  }
?>
                               <td class= "text-center"><?php echo $rates['tax_priority']; ?></td>
                               <td ><?php echo $rates['tax_class_title']; ?></td>
                               <td ><?php echo $rates['geo_zone_name']; ?></td>
                               <td ><?php echo tep_display_tax_value($rates['tax_rate']); ?>%</td>
                               <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $rates['tax_rates_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $rates['tax_rates_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $rates['tax_rates_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL  ;
?>
                                   </div> 
				                </td>
				            </tr>
<?php 
                
					  
                  if (isset($trInfo) && is_object($trInfo) && ($rates['tax_rates_id']  == $trInfo->tax_rates_id) && isset($HTTP_GET_VARS['action'])) { 							 
 	                             $alertClass = '';
                                 switch ($action) {
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_TAX_RATE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('rates', FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $trInfo->tax_rates_id  . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $trInfo->tax_class_title . ' ' . number_format($trInfo->tax_rate, TAX_DECIMAL_PLACES) . '%</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $trInfo->tax_rates_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_TAX_RATE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('rates', FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $trInfo->tax_rates_id  . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_tax_rates') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('tax_class_id', $array_tax_rates, $trInfo->tax_class_id, TEXT_INFO_CLASS_TITLE, 'id_tax_class_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('tax_zone_id', $array_tax_rates, $trInfo->tax_zone_id, TEXT_INFO_ZONE_NAME, 'id_tax_zone_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_rate',      $trInfo->tax_rate,       TEXT_INFO_TAX_RATE,      'id_input_tax_rate' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_TAX_RATE,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_description',  $trInfo->tax_description,   TEXT_INFO_RATE_DESCRIPTION, 'id_input_tax_description' ,    'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_RATE_DESCRIPTION,  '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_priority', $trInfo->tax_priority,  TEXT_INFO_TAX_RATE_PRIORITY, 'id_input_tax_priority' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_TAX_RATE_PRIORITY, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $trInfo->tax_rates_id), null, null, 'btn-default text-danger') . PHP_EOL;			
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
			                            $contents .= '                              ' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($trInfo->date_added) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($trInfo->last_modified) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_RATE_DESCRIPTION . '<br />' . $trInfo->tax_description . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_TAX_RATES ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
								  
                              }	// end if assets							
                }  // end while ($rates = tep_db_fetch_array(
?>  
			  </tbody>
		  </table>
	  </div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $rates_split->display_count($rates_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_TAX_RATES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $rates_split->display_links($rates_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
             </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_TAX_RATE, 'plus', null,'data-toggle="modal" data-target="#new_tax_rate"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  

    </table>			  

        <div class="modal fade"  id="new_tax_rate" role="dialog" aria-labelledby="new_tax_rate" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('rates', FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert', 'post', 'class="form-horizontal" role="form"' )
				//tep_draw_bs_form('languages', FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_languages'); ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_TAX_RATE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_TAX_RATE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
//			                           $contents            .= '               ' . tep_draw_bs_form('rates', FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $trInfo->tax_rates_id  . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_tax_rates') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('tax_class_id', $array_tax_rates, null, TEXT_INFO_CLASS_TITLE, 'id_tax_class_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('tax_zone_id', $array_tax_rates, null, TEXT_INFO_ZONE_NAME, 'id_tax_zone_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_rate',      null,       TEXT_INFO_TAX_RATE,      'id_input_tax_rate' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_TAX_RATE,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_description',  null,   TEXT_INFO_RATE_DESCRIPTION, 'id_input_tax_description' ,    'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_RATE_DESCRIPTION,  '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('tax_priority', null,  TEXT_INFO_TAX_RATE_PRIORITY, 'id_input_tax_priority' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_TAX_RATE_PRIORITY, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'] . '&tID=' . $trInfo->tax_rates_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel				
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . 
				             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_TAX_RATES, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_tax rate -->	

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
