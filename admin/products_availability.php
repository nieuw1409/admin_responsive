<?php
/*
  $Id: products_availiability.php,v 1.03 2003/11/09

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['mID'])) $products_availability_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
		$products_availability_prev_image  = $HTTP_POST_VARS['products_availability_image'] ;
 
        $products_availability_image = new upload( 'products_availability_image' );
        $products_availability_image->set_destination(DIR_FS_CATALOG_IMAGES);
        $image_array = array();		
        if ($products_availability_image->parse() && $products_availability_image->save()) {
		  $image_array = array( 'products_availability_image'=> tep_db_prepare_input($products_availability_image->filename) ) ;
          //tep_db_query("update " . TABLE_MANUFACTURERS . " set products_availability_image = '" . tep_db_input($products_availability_image->filename) . "' where products_availability_id = '" . (int)$products_availability_id . "' and language_id = '" . (int)$language_id . "'");
        }   

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $products_availability_name_array = $HTTP_POST_VARS['products_availability_name'];
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('products_availability_name' => tep_db_prepare_input($products_availability_name_array[$language_id]));
		  
          $sql_data_array = array_merge($sql_data_array, $image_array);

          if ($action == 'insert') {
            if (empty($products_availability_id)) {
              $next_id_query = tep_db_query("select max(products_availability_id) as products_availability_id from " . TABLE_PRODUCTS_AVAILABILITY . "");
              $next_id = tep_db_fetch_array($next_id_query);
              $products_availability_id = $next_id['products_availability_id'] + 1;
            }

            $insert_sql_data = array('products_availability_id' => $products_availability_id,
                                     'language_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_PRODUCTS_AVAILABILITY, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_PRODUCTS_AVAILABILITY, $sql_data_array, 'update', "products_availability_id = '" . (int)$products_availability_id . "' and language_id = '" . (int)$language_id . "'");
          }
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $products_availability_id));
        break;
      case 'deleteconfirm':
        $mID = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $availability_query = tep_db_query("select products_availability_image from " . TABLE_PRODUCTS_AVAILABILITY . " where products_availability_id = '" . (int)$mID . "'");
          $availability_image = tep_db_fetch_array($availability_query);

          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $availability_image['products_availability_image'];

          if (file_exists($image_location)) @unlink($image_location);
        }
		
       tep_db_query("delete from " . TABLE_PRODUCTS_AVAILABILITY . " where products_availability_id = '" . tep_db_input($mID) . "'");
 
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
        $mID = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        $status_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . (int)$mID . "'");
        $status = tep_db_fetch_array($status_query);

        $remove_status = true;

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
                   <th>                     <?php echo TABLE_HEADING_PRODUCTS_AVAILABILITY; ?></th>		   
                   <th>                     <?php echo TABLE_HEADING_PRODUCTS_AVAILABILITY; ?></th>					   
                   <th class="text-left" >  <?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
			  
<?php
				  $products_availability_query_raw = "select products_availability_id, products_availability_name, products_availability_image from " . TABLE_PRODUCTS_AVAILABILITY . " where language_id = '" . (int)$languages_id . "' order by products_availability_id";
				  $products_availability_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_availability_query_raw, $products_availability_query_numrows);
				  $products_availability_query = tep_db_query($products_availability_query_raw);
				  while ($products_availability = tep_db_fetch_array($products_availability_query)) {
					if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $products_availability['products_availability_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
					  $mInfo = new objectInfo($products_availability);
					}

					if (isset($mInfo) && is_object($mInfo) && ($products_availability['products_availability_id'] == $mInfo->products_availability_id)) {
					  echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->products_availability_id . '&action=edit') . '\'">' . PHP_EOL;
					} else {
					  echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $products_availability['products_availability_id']) . '\'">' . PHP_EOL ;
					}

					if (DEFAULT_PRODUCTS_AVAILABILITY_ID == $products_availability['products_availability_id']) {
					    echo '<td class="text-warning">' . $products_availability['products_availability_name'] . ' (' . TEXT_DEFAULT . ')</td>' . PHP_EOL;
					} else {
					    echo '<td>' .                      $products_availability['products_availability_name'] . '</td>' . PHP_EOL;
					}
					
?>			  
				  <td class="text-center"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_availability['products_availability_image'], $products_availability['products_availability_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) ; ?></td>
                              <td class="text-left">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $products_availability['products_availability_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $products_availability['products_availability_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $products_availability['products_availability_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>						
                            </tr>	
<?php		
                 if (isset($mInfo) && is_object($mInfo) && ($products_availability['products_availability_id'] == $mInfo->products_availability_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_HEADING_DELETE_PRODUCTS_AVAILABILITY . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('status', FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->products_availability_id  . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $products_availability['products_availability_name']   . '</p>' . PHP_EOL;

									   $contents .= '                          <div class="form-group">' . PHP_EOL ;								   
									   $contents .= '                            <label class="col-xs-3">' . TEXT_DELETE_IMAGE .  '</label>' . PHP_EOL ;									   
									   $contents .=                                  tep_bs_checkbox_field('delete_image', true, null, 'input_product_remove_image', true, ' checkbox checkbox-success ') . PHP_EOL ;
									   $contents .= '                          </div>'  . PHP_EOL ;										
									   
                                       $contents .= '                        </div>' . PHP_EOL;
									   
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->products_availability_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_HEADING_EDIT_PRODUCTS_AVAILABILITY . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('status',        FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->products_availability_id  . '&action=save', 'post', 'enctype="multipart/form-data" class="form-horizontal" role="form"', 'id_edit_product_availability') . PHP_EOL;													   
 
                                       $languages = tep_get_languages();
                                       for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                          $contents         .= '                       <div class="input-group">' . PHP_EOL ;	
                                          $contents         .= '                          <span class="input-group-addon" id="basic-addon'.$i.'">' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], null, null,null, false) . '</span>'. PHP_EOL ;
									      $contents         .= '                           ' . tep_draw_bs_input_field('products_availability_name[' . $languages[$i]['id'] . ']', tep_get_products_availability_name($mInfo->products_availability_id, $languages[$i]['id']),        null,       'id_input_availability_name'.$i ,        null, 'col-xs-12', null, null, 'aria-describedby="basic-addon' . $i . '"' ) . PHP_EOL;	
                                          $contents         .= '                       </div>' . PHP_EOL ;											  
                                       }
									   
                                       $contents            .= 						   '<br />'	. PHP_EOL ;							   
                                       $contents            .= 						   '<label>'. TEXT_PRODUCTS_AVAILABILITY_IMAGE . '</label><br />' . PHP_EOL ;
                                       $contents            .=                         tep_image(DIR_WS_CATALOG_IMAGES . $mInfo->products_availability_image, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"')  . PHP_EOL ;
									   

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_file_field(products_availability_image, $mInfo->products_availability_image, null, 'id_input_avalibility_image' , 'col-xs-3', 'col-xs-9', 'left')	 . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	

									   //tep_draw_bs_file_field($name, $value = '', $label = '', $id='id_input' ,$label_class='', $input_class ='', $label_lft_rght = 'left', $placeholder='', $parameters = '', $required = false,  $text_required = '')									   
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->products_availability_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            /*default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $mInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_NAME . '<br />' . $mInfo->zone_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_CODE . '  ' . $mInfo->zone_code . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_NAME . '  ' . tep_get_country_name( $mInfo->countries_id ) . PHP_EOL;
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
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $products_availability_split->display_count($products_availability_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_AVAILABILITY); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $products_availability_split->display_links($products_availability_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_PRODUCT_AVAILABILITY, 'plus', null,'data-toggle="modal" data-target="#new_product_availability_status"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  
           <div class="modal fade"  id="new_product_availability_status" role="dialog" aria-labelledby="new_product_availability_status" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('status', FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert', 'post', 'enctype="multipart/form-data"') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_NEW_INTRO; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_NEW_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
                                       $languages = tep_get_languages();
                                       for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                                          $contents         .= '                       <div class="input-group">' . PHP_EOL ;	
                                          $contents         .= '                          <span class="input-group-addon" id="basic-addon'.$i.'">' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], null, null,null, false) . '</span>'. PHP_EOL ;
									      $contents         .= '                           ' . tep_draw_bs_input_field('products_availability_name[' . $languages[$i]['id'] . ']', null,        null,       'id_input_availability_name'.$i ,        null, 'col-xs-12', null, null, 'aria-describedby="basic-addon' . $i . '"' ) . PHP_EOL;	
                                          $contents         .= '                       </div>' . PHP_EOL ;											  
                                       }
									   
                                       $contents            .= 						   '<br />'	. PHP_EOL ;							   
//                                       $contents            .= 						   '<label>'. TEXT_PRODUCTS_AVAILABILITY_IMAGE . '</label><br />' . PHP_EOL ;
//                                       $contents            .=                         tep_image(DIR_WS_CATALOG_IMAGES . $mInfo->products_availability_image, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"')  . PHP_EOL ;
									   

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_file_field(products_availability_image, null, null, 'id_input_avalibility_image' , 'col-xs-3', 'col-xs-9', 'left')	 . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents . $contents_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_AVAILABILITY, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_product_availability_status --> 	
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>