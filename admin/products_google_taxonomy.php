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
        $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
        $code = tep_db_prepare_input($HTTP_POST_VARS['code']);
        $image = tep_db_prepare_input($HTTP_POST_VARS['image']);
        $directory = tep_db_prepare_input($HTTP_POST_VARS['directory']);
        $sort_order = (int)tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        tep_db_query("insert into " . TABLE_PRODUCTS_GOOGLE_TAXONOMY . " ( google_taxonomy_number, google_taxonomy_name) values ('" . tep_db_input($code) . "', '" . tep_db_input($name) . "')");
        $insert_id = tep_db_insert_id();

        tep_redirect(tep_href_link(FILENAME_GOOGLE_TAXONOMY, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'lID=' . $insert_id));
        break;
      case 'save':
        $lID = tep_db_prepare_input($HTTP_GET_VARS['lID']);
        $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
        $code = tep_db_prepare_input($HTTP_POST_VARS['code']);

        tep_db_query("update " . TABLE_PRODUCTS_GOOGLE_TAXONOMY . " set google_taxonomy_name = '" . tep_db_input($name) . "', google_taxonomy_number = '" . tep_db_input($code) . "', last_modified = 'now()' where google_taxonomy_id = '" . (int)$lID . "'");
 

        tep_redirect(tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $HTTP_GET_VARS['lID']));
        break;
      case 'deleteconfirm':
        $lID = tep_db_prepare_input($HTTP_GET_VARS['lID']);


        tep_db_query("delete from " . TABLE_PRODUCTS_GOOGLE_TAXONOMY . " where google_taxonomy_id = '" . (int)$lID . "'");		

        tep_redirect(tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
        $lID = tep_db_prepare_input($HTTP_GET_VARS['lID']);

        $lng_query = tep_db_query("select code from " . TABLE_PRODUCTS_GOOGLE_TAXONOMY . " where google_taxonomy_id = '" . (int)$lID . "'");
        $lng = tep_db_fetch_array($lng_query);

        $remove_language = true;
        if ($lng['code'] == DEFAULT_LANGUAGE) {
          $remove_language = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
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
                   <th><?php echo TABLE_HEADING_GOOGLE_CODE_NAME; ?></th> 				   
                   <th><?php echo TABLE_HEADING_GOOGLE_CODE_CODE; ?></th>				   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>				   

                </tr>
              </thead>
              <tbody>
<?php
                  $google_taxonomy_query_raw = "select google_taxonomy_id, google_taxonomy_number, google_taxonomy_name from " . TABLE_PRODUCTS_GOOGLE_TAXONOMY . " order by google_taxonomy_id";
                  $google_taxonomy_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $google_taxonomy_query_raw, $google_taxonomy_query_numrows);
                  $google_taxonomy_query = tep_db_query($google_taxonomy_query_raw);
  

                  while ($google_taxonomy = tep_db_fetch_array($google_taxonomy_query)) {
                     if ((!isset($HTTP_GET_VARS['lID']) || (isset($HTTP_GET_VARS['lID']) && ($HTTP_GET_VARS['lID'] == $google_taxonomy['google_taxonomy_id']))) && !isset($GCTlInfo) && (substr($action, 0, 3) != 'new')) {
                        $GCTlInfo = new objectInfo($google_taxonomy);
                     }

                     if (isset($GCTlInfo) && is_object($GCTlInfo) && ($google_taxonomy['google_taxonomy_id'] == $GCTlInfo->google_taxonomy_id) ) {
                        echo '              <tr class=äctive" onclick="document.location.href=\'' . tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $GCTlInfo->google_taxonomy_id . '&action=edit') . '\'">' . PHP_EOL;
                     } else {
                        echo '              <tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $google_taxonomy['google_taxonomy_id']) . '\'">' . PHP_EOL;
                     }                   
?>
                              <td><?php echo $google_taxonomy['google_taxonomy_name']; ?></td>
                              <td><?php echo $google_taxonomy['google_taxonomy_number'] ; ?></td>							  
                                           
                              <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $google_taxonomy['google_taxonomy_id']. '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $google_taxonomy['google_taxonomy_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $google_taxonomy['google_taxonomy_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL  ;
?>
                                   </div> 
				              </td>											
                     </tr>
<?php 
                
					  
                  if (isset($GCTlInfo) && is_object($GCTlInfo) && ($google_taxonomy['google_taxonomy_id'] == $GCTlInfo->google_taxonomy_id) && isset($HTTP_GET_VARS['action'])) { 
// BOF multi stores
                                 $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
                                 while ($stores_stores = tep_db_fetch_array($stores_query)) {
                                     $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
                                 }	
                                 $lang_to_stores_array = explode(',', $GCTlInfo->languages_to_stores); // multi stores
                                 $lang_to_stores_array = array_slice($lang_to_stores_array, 1); // remove "@" from the array	 // multi stores	
// EOF multi stores 					  
 	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_GOOGLE_CODE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('del_google_taxonomy_code', FILENAME_GOOGLE_TAXONOMY, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $google_taxonomy['google_taxonomy_id'] . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $google_taxonomy['google_taxonomy_name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $GCTlInfo->google_taxonomy_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('google_taxonomy_code', FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $GCTlInfo->google_taxonomy_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_google_taxonomy_code') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('name',       $GCTlInfo->google_taxonomy_name,        TEXT_INFO_GOOGLE_CODE_NAME,       'id_input_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_GOOGLE_CODE_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('code',       $GCTlInfo->google_taxonomy_number,        TEXT_INFO_GOOGLE_CODE_CODE,       'id_input_code' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_GOOGLE_CODE_CODE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   

                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $GCTlInfo->google_taxonomy_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
// bof multi stores		
                                        $products_to_stores_array = explode(',', $stores_languages['stores_id']); // multi stores
                                        $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores
                                        $product_to_stores =  '';

										//foreach ($_stores_name as $key => $stores_languages) { //hide_customers_group
										
                                        for ($i = 0; $i < count($stores_array); $i++) {
                                           if (in_array($stores_array[$i]['id'], $lang_to_stores_array)) {  
                                              $product_to_stores .= $stores_array[$i]['text'] . '<br />'; 
                                           }
                                        } // end for ($i = 0; $i < count($stores_array); $i++)
											
									    if ( !tep_not_null( $product_to_stores ) ) $product_to_stores = TEXT_STORES_NONE ;
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . TEXT_INFO_LANGUAGE_NAME . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_LANGUAGES_TO_STORES . ' <br />' . $product_to_stores . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;					
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_GOOGLE_TAXONOMY ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
				} // end while ($customers = tep_db_fetch_array($cus				
?>			  
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $google_taxonomy_split->display_count($google_taxonomy_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_GOOGLE_TAXONOMY); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $google_taxonomy_split->display_links($google_taxonomy_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
             </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_GOOGLE_TAXECO_CODE, 'plus', null,'data-toggle="modal" data-target="#new_language"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  

    </table>
        <div class="modal fade"  id="new_language" role="dialog" aria-labelledby="new_language" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('languages', FILENAME_GOOGLE_TAXONOMY, 'action=insert')
				//tep_draw_bs_form('languages', FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $GCTlInfo->languages_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_languages'); ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_GOOGLE_CODE; ?></h4>
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
 	                                   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('name',       null,        TEXT_INFO_GOOGLE_CODE_NAME,       'id_input_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_GOOGLE_CODE_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('code',       null,        TEXT_INFO_GOOGLE_CODE_CODE,       'id_input_code' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_GOOGLE_CODE_CODE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	

//		                               $contents        .= '                      </form>' . PHP_EOL;
		                               $contents        .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents        .= '           </div>' . PHP_EOL; // end div 	panel	
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GOOGLE_TAXONOMY, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $HTTP_GET_VARS['lID'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_language -->

<?php

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>