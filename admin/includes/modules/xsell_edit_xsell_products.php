<?php 


			     $xsell_array = array(); 
                 $xsell_query = tep_db_query('select x.xsell_id from ' . TABLE_PRODUCTS . ' p, ' . TABLE_PRODUCTS_XSELL . ' x where x.products_id = "'.$cInfo->products_id.'"');
			     while ($xsell = tep_db_fetch_array($xsell_query)) {
			        $xsell_array[] = $xsell['xsell_id'];
		         } // end while ($xsell = tep_db_fetch_array($xsell_query))
					 
		         $products_xsell_query_raw = 'select distinct p.products_id, p.products_image, p.products_model, pd.products_name, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd 
				    where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" group by p.products_id order by pd.products_name asc';
				 
		         $products_query_xsell = tep_db_query($products_xsell_query_raw);
 
			     while ($products_xsell = tep_db_fetch_array($products_query_xsell)) {
			        if (!in_array($products_xsell['products_id'], $xsell_array)) {
			           //$xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$cInfo->products_id.'" and xsell_id = "'.$products['products_id'].'"');
			           //$xsold_query_reciprocal = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$products['products_id'].'" and xsell_id = "'.$cInfo->products_id.'"');				 
					   
					   // get the products not already related to this product
					   $products_no_xsell[] = array("id" => $products_xsell['products_id'], "text" => $products_xsell['products_name']);					   
					}

				 }
				 
                 $content_edit_xsell .= '            <table class="table table-condensed table-striped">' . PHP_EOL ;
                 $content_edit_xsell .= '              <thead>' . PHP_EOL ;
                 $content_edit_xsell .= '                <tr class="heading-row">' . PHP_EOL ;
                 $content_edit_xsell .= '                   <th>' . TABLE_HEADING_PRODUCT_ID        . '</th>' . PHP_EOL ;
                 $content_edit_xsell .= '                   <th>' . TABLE_HEADING_PRODUCT_MODEL     . '</th>' . PHP_EOL ;    			   
                 $content_edit_xsell .= '                   <th>' . TABLE_HEADING_PRODUCT_IMAGE     . '</th>' . PHP_EOL ;   
                 $content_edit_xsell .= '                   <th>' . TABLE_HEADING_PRODUCT_NAME      . '</th>' . PHP_EOL ;    
                 $content_edit_xsell .= '                   <th>' . TABLE_HEADING_PRODUCT_SORT      . '</th>' . PHP_EOL ; 				 
                 $content_edit_xsell .= '                   <th>' . TABLE_HEADING_RECIPROCAL_LINK   . '</th>' . PHP_EOL ; 					 
                 $content_edit_xsell .= '                   <th>' . TABLE_HEADING_ACTION            . '</th>' . PHP_EOL ;
                 $content_edit_xsell .= '                </tr>' . PHP_EOL ;
                 $content_edit_xsell .= '              </thead>' . PHP_EOL ;
                 $content_edit_xsell .= '              <tbody>' . PHP_EOL ;
 
			  
				
				
                 $products_query_cross_sell_raw = 'select p.products_id, p.products_model, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, x.products_id, x.xsell_id, x.sort_order, x.ID 
				                                           from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x 
														   where x.products_id = "'.$cInfo->products_id.'" and x.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc';
// recipocal search x.xsell_id = p.products_id and 
				 
		         $products_split_xsell = new splitPageResults($_GET['page_edit_xsell'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_cross_sell_raw, $products_xsell_query_numrows);
		         $products_query_xsell = tep_db_query($products_query_cross_sell_raw);
			  
                 while ($products_xsell = tep_db_fetch_array($products_query_xsell)) {
                    if ((!isset($HTTP_GET_VARS['xID']) || (isset($HTTP_GET_VARS['xID']) && ($HTTP_GET_VARS['xID'] == $products_xsell['products_id']))) && !isset($cInfo_xsell_xsell) ) { //&& (substr($action, 0, 3) != 'new')
                       $cInfo_xsell_xsell = new objectInfo($products_xsell);
                    }

		            if ($products_xsell['products_model'] == NULL) {
		               $products_xsell_model = TEXT_NONE;
		            } else {
		               $products_xsell_model = $products_xsell['products_model'];
		            } // end if ($products_xsell['products_model'] == NULL)					
                    if (isset($cInfo_xsell_xsell) && is_object($cInfo_xsell_xsell) && ($products_xsell['products_id'] == $cInfo_xsell_xsell->products_id)) {
                        $content_edit_xsell .= '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_XSELL_PRODUCTS, 'page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $cInfo_xsell_xsell->products_id . '&action=edit') . '\'">' . PHP_EOL ;
                    } else {
                        $content_edit_xsell .= '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $cInfo_xsell_xsell->products_id, 'NONSSL') . '\'">' . "\n";
                    }

                    $content_edit_xsell .= '                                 <td>' .                     $products_xsell['ID'] . '</td>'. PHP_EOL ;
                    $content_edit_xsell .= '                                 <td>' .                     $products_xsell_model  . '</td>'. PHP_EOL ;
                    $content_edit_xsell .= '                                 <td class="text-center">'.  tep_image(DIR_WS_CATALOG_IMAGES  . $products_xsell['products_image'], $products_xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</td>'. PHP_EOL ;
                    $content_edit_xsell .= '                                 <td>' .                     $products_xsell['products_name'] . '</td>'. PHP_EOL ;
                    $content_edit_xsell .= '                                 <td>' .                     $products_xsell['sort_order'] . '</td>'. PHP_EOL ;	

                    $xsold_query_reciprocal_table = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'. $products_xsell['xsell_id']  .'" and xsell_id = "'. $products_xsell['products_id'] . '"'); 
                    $content_edit_xsell .= '                                 <td>' .                    ((tep_db_num_rows($xsold_query_reciprocal_table) > 0) ? tep_glyphicon('ok-sign', 'success') : tep_glyphicon('remove-sign', 'danger'))  . '</td>'. PHP_EOL ;
					

                    $content_edit_xsell .= '                                 <td class="text-right">' . PHP_EOL ;								 
                    $content_edit_xsell .= '                                   <div class="btn-toolbar" role="toolbar">' . PHP_EOL ;                  
 
//                    $content_edit_xsell .= '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_XSELL_PRODUCTS, 'page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $products_xsell['products_id'] . '&action_xsell=info'),    null, 'info')    . '</div>' . PHP_EOL ;
                    $content_edit_xsell .= '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $products_xsell['products_id'] . '&xsellID=' . $products_xsell['ID'] . '&action=edit&action_xsell=edit_xsell'),    null, 'warning') . '</div>' . PHP_EOL ;				
                    $content_edit_xsell .= '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $products_xsell['products_id'] . '&xsellID=' . $products_xsell['ID'] . '&action=edit&action_xsell=confirm_xsell'), null, 'danger')  . '</div>' . PHP_EOL ;

                    $content_edit_xsell .= '                                   </div>' . PHP_EOL ;
                    $content_edit_xsell .= '				               </td>' . PHP_EOL ;	
                    $content_edit_xsell .= '               </tr>' . PHP_EOL ;

//		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ADD,             'plus',          tep_href_link(FILENAME_XSELL_PRODUCTS_PRODUCTS, 'page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $products['products_id'] . '&action=add'),     null, 'warning') . '</div>' . PHP_EOL .
                  if (isset($cInfo_xsell_xsell) && is_object($cInfo_xsell_xsell) && ($products_xsell['ID'] == $HTTP_GET_VARS['xsellID'] ) && isset($HTTP_GET_VARS['action_xsell'])) { 

	                             $alertClass_xsell = '';
                                 switch ($action_xsell) {			 
		                            case 'confirm_xsell':
			                           $contents_action .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_action .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CROSS_SELL . '</div>' . PHP_EOL;
			                           $contents_action .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass_xsell .= ' alert alert-danger';
		                               $contents_action .= '                      ' . tep_draw_form('zones', FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $products_xsell['products_id'] . '&xsellID=' . $products_xsell['xsell_id'] . '&action=edit&action_xsell=deleteconfirm') . PHP_EOL;
                                       $contents_action .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents_action .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $products_xsell['products_name']  .'</p>' . PHP_EOL;

                                       $xsold_query_reciprocal = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.  $products_xsell['xsell_id']  .'" and xsell_id = "'. $products_xsell['products_id']. '"'); 
									   if (tep_db_num_rows($xsold_query_reciprocal) > 0) {
										   // remove reciprocal link
									       $contents_action            .= '                      <div class="form-group">' . PHP_EOL ;								   
									       $contents_action            .= '                          <label class="col-xs-3">' . TEXT_REMOVE_XSELLS .  '</label>' . PHP_EOL ;									   
									       $contents_action            .=                                tep_bs_checkbox_field('product_xsell_reciprocal', true, null, 'input_product_xsell_reciprocal', true, ' checkbox checkbox-success ') . PHP_EOL ;
									       $contents_action            .= '                      </div>'  . PHP_EOL ;										   										   
									   }
									   
                                       $contents_action .= '                        </div>' . PHP_EOL;
                                       $contents_action .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents_action .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $products_xsell['products_id'] . '&action=edit' ), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents_action .= '                        </div>' . PHP_EOL;
		                               $contents_action .= '                      </form>' . PHP_EOL;
		                               $contents_action_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_action_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit_xsell':
			                           $contents_action            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_action            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_ZONE . $products_xsell['ID'] . $products_xsell['ID'] .'</div>' . PHP_EOL;
			                           $contents_action            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents_action            .= '               ' . tep_draw_bs_form('zones', FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $products_xsell['products_id'] . '&xsellID=' . $products_xsell['xsell_id']. '&action=edit&action_xsell=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_zones') . PHP_EOL;													   
									   
                                       $contents_action            .= '			           <div class="form-group">' . PHP_EOL ;	
                                       $contents_action            .=                             tep_draw_hidden_field('product_xsell_id', $products_xsell['xsell_id']) . PHP_EOL ;	
                                       $contents_action            .= '				       </div>			' . PHP_EOL ;		
									   
                                       $contents_action            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_action            .= '                           ' . tep_draw_bs_input_field('product_xsell_name',        $products_xsell['products_name'],        TABLE_HEADING_PRODUCT_NAME,       'id_input_product_xsell_product_name' ,        'col-xs-3', 'col-xs-9', 'left', TABLE_HEADING_PRODUCT_NAME, 'disabled ' ) . PHP_EOL ;
                                       $contents_action            .= '                       </div>' . PHP_EOL ;	
									   
                                       $contents_action            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_action            .= '                           ' . tep_draw_bs_input_field('product_xsell_sort_order',     $products_xsell['sort_order'],        TABLE_HEADING_PRODUCT_SORT,       'id_input_product_xsell_sort_order' ,        'col-xs-3', 'col-xs-9', 'left', TABLE_HEADING_PRODUCT_SORT ) . PHP_EOL;	
                                       $contents_action            .= '                       </div>' . PHP_EOL ;
									   
                                       $contents_action            .= '                       <div class="clearfix"></div>' . PHP_EOL ;										    									   
                                       $contents_action            .= '                       <br />' . PHP_EOL ;										  
									   						
                                       $xsold_query_reciprocal = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.  $products_xsell['xsell_id']  .'" and xsell_id = "'. $products_xsell['products_id']. '"');   															

									   $contents_action            .= '                      <div class="form-group">' . PHP_EOL ;								   
									   $contents_action            .= '                          <label class="col-xs-3">' . TEXT_RECIPROCAL_LINK .  '</label>' . PHP_EOL ;									   
									   $contents_action            .=                                tep_bs_checkbox_field('product_xsell_reciprocal', true, null, 'input_product_xsell_reciprocal', ((tep_db_num_rows($xsold_query_reciprocal) > 0) ? true : false), ' checkbox checkbox-success ') . PHP_EOL ;
									   $contents_action            .= '                      </div>'  . PHP_EOL ;								   
									   $contents_action            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_action_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page']  . '&xID=' . $cInfo_xsell_xsell->products_id . '&action=edit' ), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_action_footer .= '                      </form>' . PHP_EOL;
		                               $contents_action_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_action_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
/*		                            default: 
			                            $contents_action .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents_action .= '          <div class="panel-heading">' . $cInfo_xsell->countries_name . '</div>' . PHP_EOL;
			                            $contents_action .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents_action .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents_action .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents_action .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents_action .= '                              ' . TEXT_INFO_ZONES_NAME . '<br />' . $cInfo_xsell->zone_name . PHP_EOL;
			                            $contents_action .= '                          </li>' . PHP_EOL;
                                        $contents_action .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents_action .= '                              ' . TEXT_INFO_ZONES_CODE . '  ' . $cInfo_xsell->zone_code . PHP_EOL;
			                            $contents_action .= '                          </li>' . PHP_EOL;					
                                        $contents_action .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents_action .= '                              ' . TEXT_INFO_COUNTRY_NAME . '  ' . tep_get_country_name( $cInfo_xsell->countries_id ) . PHP_EOL;
			                            $contents_action .= '                          </li>' . PHP_EOL;						                          
 			                            $contents_action .= '                        </ul>' . PHP_EOL;
			                            $contents_action .= '                      </div>' . PHP_EOL;
                                        
										$contents_action_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
*/							
                                 }
	

		
		                         $contents_action .=  '</div>' . PHP_EOL ;
		                         $contents_action .=  $contents_action_sv_cncl  . PHP_EOL;
                                 $contents_action .=  $contents_action_footer  . PHP_EOL;			

                                 $content_edit_xsell .= '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="7">' . PHP_EOL .
                                      '                    <div class="row ' . $alertClass_xsell . '">' . PHP_EOL .
                                                               $contents_action . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets				   
				} // end while while ($countries = tep_db_fetch_arra			   
				
                $content_edit_xsell .= '			  </tbody>'. PHP_EOL ;
//$content_edit_xsell .= '		  </table>'. PHP_EOL ;
                $content_edit_xsell .= '	</table>'. PHP_EOL ;
                $content_edit_xsell .= '    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->'. PHP_EOL ;
                $content_edit_xsell .= '	     <hr>'. PHP_EOL ;
                $content_edit_xsell .= '         <div class="row">'. PHP_EOL ;
                $content_edit_xsell .= '               <div class="mark text-left  col-xs-12 col-md-6">' . $products_split_xsell->display_count($products_xsell_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page_edit_xsell'], TEXT_DISPLAY_NUMBER_OF_CROSS_SELLS) . '</div>'. PHP_EOL ;
                $content_edit_xsell .= '               <div class="mark text-right col-xs-12 col-md-6">' . $products_split_xsell->display_links($products_xsell_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page_edit_xsell']) . '</div>'. PHP_EOL ;
                $content_edit_xsell .= '	     </div>'. PHP_EOL ;
		  

                if (empty($action_xsell)) {

                   $content_edit_xsell .= '          <hr>'. PHP_EOL ;
                   $content_edit_xsell .= '          <div class="row">'. PHP_EOL ;
                   $content_edit_xsell .= '             <div class="col-xs-12 col-md-7">		  '. PHP_EOL ;
                   $content_edit_xsell .=                     tep_draw_bs_button(IMAGE_NEW_XSELL_PRODUCTS, 'plus', null,'data-toggle="modal" data-target="#new_xsell_products"'). PHP_EOL ;
                   $content_edit_xsell .= ' 			 </div>'. PHP_EOL ;
                   $content_edit_xsell .= '            </div>'. PHP_EOL ;

                }

                $content_edit_xsell .= '    </table>'. PHP_EOL ;

?>
      <div class="modal fade"  id="new_xsell_products" role="dialog" aria-labelledby="new_xsell_products" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('new_xsell_product', FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $cInfo->products_id . '&xsellID=' . $cInfo_xsell_xsell->ID . '&action=edit&action_xsell=insert_xsell') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_XSELL_PRODUCT; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_new_xsell            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_new_xsell            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_XSELL_PRODUCT . '</div>' . PHP_EOL;
			                           $contents_new_xsell            .= '          <div class="panel-body">' . PHP_EOL;			
									   
                                       $contents_new_xsell            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new_xsell            .= '                           ' . tep_draw_bs_pull_down_menu('product_xsell_id', $products_no_xsell, null, TABLE_HEADING_PRODUCT_NAME, 'id_input_product_xsell_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;	
                                       $contents_new_xsell            .= '                       </div>' . PHP_EOL ;	
									   
                                       $contents_new_xsell            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_new_xsell            .= '                           ' . tep_draw_bs_input_field('product_xsell_sort_order',       null,        TABLE_HEADING_PRODUCT_SORT,       'id_input_product_xsell_sort_order' ,        'col-xs-3', 'col-xs-9', 'left', TABLE_HEADING_PRODUCT_SORT ) . PHP_EOL;	
                                       $contents_new_xsell            .= '                       </div>' . PHP_EOL ;
									   
                                       $contents_new_xsell            .= '                       <div class="clearfix"></div>' . PHP_EOL ;										    									   
                                       $contents_new_xsell            .= '                       <br />' . PHP_EOL ;										  
									   									   

									   $contents_new_xsell            .= '                      <div class="form-group">' . PHP_EOL ;								   
									   $contents_new_xsell            .= '                          <label class="col-xs-3">' . TEXT_RECIPROCAL_LINK .  '</label>' . PHP_EOL ;									   
									   $contents_new_xsell            .=                                tep_bs_checkbox_field('product_xsell_reciprocal', true, null, 'input_product_xsell_reciprocal', null, ' checkbox checkbox-success ') . PHP_EOL ;
									   $contents_new_xsell            .= '                      </div>'  . PHP_EOL ;									   
									   					   
									   $contents_new_xsell            .= '                       <br />' . PHP_EOL;	
                                       
		                               $contents_new_xsell_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_new_xsell_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_new_xsell . $contents_new_xsell_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . 
				             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $HTTP_GET_VARS['xID'] . '&xsellID=' . $HTTP_GET_VARS['xsellID'] . '&action=edit')); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_xsell_product --> 				