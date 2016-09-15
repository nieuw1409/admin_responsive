<?php
/*
  $Id: create_order_details.php,v 1.2 2005/09/04 04:42:56 loic Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
  <div class="col-md-6 col-xs-12">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="heading_Options">
		  <h4 class="panel-title">
			<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_Options" aria-expanded="true" aria-controls="collapse_Options">
			  <?php echo TEXT_TAB_OPTIONS ; ?>
			</a>
		  </h4>
		</div>
		<div id="collapse_Options" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_Options">
		  <div class="panel-body">
                <div class="panel-heading">                 
				  <?php echo HEADING_TITLE_OPT . PHP_EOL ; ?>
                  <div class="pull-right">
<?php
                      $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_id";
                      $options_split = new splitPageResults($option_page, MAX_ROW_LISTS_OPTIONS, $options, $options_query_numrows);
                      echo $options_split->display_links($options_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $option_page, 'action=next_page_options_value&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'option_page');

?>
                  </div>
                </div><!--panel-heading -->
                <table class="table table-striped">
                  <thead>			  
                    <tr>
                      <th><?php echo TABLE_HEADING_ID;          ?></th>
                      <th><?php echo TABLE_HEADING_OPT_NAME;    ?></th> 
                      <th><?php echo TABLE_HEADING_OPT_TYPE ;   ?></th>
                      <th><?php echo TABLE_HEADING_OPT_ORDER;   ?></th>
                      <th><?php echo TABLE_HEADING_OPT_LENGTH;  ?></th>
                      <th><?php echo TABLE_HEADING_OPT_COMMENT; ?></th>
                      <th><?php echo TABLE_HEADING_TRACK_STOCK; ?></th>  
                      <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?></th>
                    </tr>
                  </thead>
                  <tbody>
<?php
                       $next_id = 1;
                       $rows = 0;
                       $options = tep_db_query($options);
                       while ($options_values = tep_db_fetch_array($options)) {
                          $rows++;
?>
                          <tr>
                              <td class="text-center"><?php echo $options_values["products_options_id"]; ?>                                           </td>
                              <td>                    <?php echo $options_values["products_options_name"]; ?>                                         </td>
							  <td>                    <?php echo translate_type_to_name($options_values["products_options_type"]); ?>                 </td>
                              <td>                    <?php echo $options_values["products_options_sort_order"]; ?>                                   </td>
                              <td>                    <?php echo $options_values["products_options_length"]; ?>                                       </td>
                              <td>                    <?php echo $options_values["products_options_comment"]; ?>                                      </td>
                              <td>                    <?php echo $options_values['products_options_track_stock']? TEXT_STOCK_YES : TEXT_STOCK_NO; ?>  </td>								  
                              <td class="text-right">
					              <div class="btn-toolbar" role="toolbar">
<?php
                                     echo '					      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info), null, 'warning') . '</div>' . "\n" .
                                          '					      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info), null, 'danger') . '</div>' . "\n";
?>
					              </div>
                              </td>
                          </tr>		
<?php
						  if ( ($HTTP_GET_VARS['option_id'] == $options_values['products_options_id']) && ($HTTP_GET_VARS['action'])) {
							switch ($action) {
 
							  case 'delete_product_option':
								$options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$HTTP_GET_VARS['option_id'] . "' and language_id = '" . (int)$languages_id . "'");
								$options_values = tep_db_fetch_array($options);
							//	$contents .= '                          <div class="col-md-12">' . "\n";
								$contents .= '                            <h4>'. $options_values['products_options_name'] .'</h4>' . "\n";
								$products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . (int)$HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
								if (tep_db_num_rows($products)) {	
								  $alertClass .= ' alert-message alert-message-danger';
								  $contents .= '                            <table class="table table-bordered" id="prdValDel">' . "\n";  
								  $contents .= '                              <tr>' . "\n";
								  $contents .= '                                <th>' . TABLE_HEADING_ID . '</th>' . "\n";
								  $contents .= '                                <th>' . TABLE_HEADING_PRODUCT . '</th>' . "\n";
								  $contents .= '                                <th>' . TABLE_HEADING_OPT_VALUE . '</th>' . "\n";
								  $contents .= '                              </tr>' . "\n";
								  $rows = 0;
								  while ($products_values = tep_db_fetch_array($products)) {
									$rows++;
									$contents .= '                              <tr>' . "\n";
									$contents .= '                                <td>' . $products_values['products_id'] . '</td>' . "\n";
									$contents .= '                                <td>' . $products_values['products_name'] . '</td>' . "\n";
									$contents .= '                                <td>' . $products_values['products_options_values_name'] . '</td>' . "\n";
									$contents .= '                              </tr>' . "\n";
								  }
								  $contents .= '                            </table>' . "\n";
								  $contents .= '                            <p>' . TEXT_WARNING_OF_DELETE . '</p>' . "\n";
								  $contents .= '                            <span class="pull-right">' . tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=no_delete_option&' . $page_info), null, null, 'btn-default text-danger') . '</span>' . "\n";
								} else {
								  $alertClass .= ' alert-message alert-message-notice';
								  $contents .= '                            <p>' . TEXT_OK_TO_DELETE . '</p>' . "\n";
								  $contents .= '                            <span class="pull-right">' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $HTTP_GET_VARS['option_id'] . '&' . $page_info), null, null, 'btn-danger') . '<br>' . 
								                                                                         tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=no_delete_option&' . $page_info), null, null, 'btn-default text-danger') . '</span>' . "\n";
								}
								//$contents .= '                          </div>' . "\n";		
							  break;

							  case 'update_option':
							  
			                    $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                    $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_OPTION . '</div>' . PHP_EOL;
			                    $contents            .= '          <div class="panel-body">' . PHP_EOL;									  
								$contents .= '                          ' . tep_draw_form('option', FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name&' . $page_info, 'post', 'role="form"') .  PHP_EOL;
								
								$contents .= '                <div class="form-group">' . PHP_EOL;			  
								$contents .=                        tep_draw_hidden_field('option_id', $options_values['products_options_id']) . PHP_EOL; 
								$contents .= '                </div>' . PHP_EOL;	 
							
								$new_option_name_navtabs = '';
								$new_option_name_tabcontent = '';
								for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
								  
									$new_option_name_navtabs .= '                         <li '. (($i == 0) ? 'class="active"' : '') .'>' . PHP_EOL;
									$new_option_name_navtabs .= '                           <a href="#tab_edit_prod_name' . $i . '" data-toggle="tab">' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], null, null, null, false) . "  " . $languages[$i]['name'] . '</a>' . PHP_EOL;
									$new_option_name_navtabs .= '                         </li>' . PHP_EOL;
			                        
			                        $option_name = tep_db_query("select products_options_name, products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
                                    $option_name = tep_db_fetch_array($option_name);
									// now the content for each language
			//						$new_option_name_tabcontent .= '                      <br />' . PHP_EOL;						
									$new_option_name_tabcontent .= '                      <div class="tab-pane fade'. (($i == 0) ? ' active in' : '') .'" id="tab_edit_prod_name' . $i . '">' . PHP_EOL; 
									$new_option_name_tabcontent .= '                         <div class="form-group">' . PHP_EOL;		
									$new_option_name_tabcontent .=                              tep_draw_bs_input_field('option_name[' . $languages[$i]['id'] . ']', $option_name['products_options_name'], TABLE_HEADING_OPT_NAME, 'id_input_option_name' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_NAME ) . PHP_EOL;
									$new_option_name_tabcontent .= '                         </div>' . PHP_EOL;	  

			//						$new_option_name_tabcontent .= '                         <br />' . PHP_EOL;						
									
									$new_option_name_tabcontent .= '                         <div class="form-group">' . PHP_EOL;			  
									$new_option_name_tabcontent .=                               tep_draw_bs_input_field('option_comment[' . $languages[$i]['id'] . ']', $option_name['products_options_comment'], TABLE_HEADING_OPT_COMMENT, 'id_input_option_comment' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_COMMENT ) . PHP_EOL; 
									$new_option_name_tabcontent .= '                         </div>' . PHP_EOL;	  
									$new_option_name_tabcontent .= '                      </div>' . PHP_EOL;
								  }	
								
								  $contents .= '                <br />' . PHP_EOL ;	
								  $contents .= '                <div role="tabpanel" id="tab_attribute_new_option">'  . PHP_EOL;    
								  $contents .= '                    <ul class="nav nav-tabs" role="tablist" id="tab_attribute_new_option">' . PHP_EOL; 
								  $contents .= 	                        $new_option_name_navtabs;
								  $contents .= '                    </ul>' . PHP_EOL; 

								  $contents .= '                    <div class="tab-content" id="tab_attribute_new_option">' . PHP_EOL; 
								  $contents .=                           $new_option_name_tabcontent;
								  $contents .= '                    </div>' . PHP_EOL; 
								  $contents .= '                </div>' . PHP_EOL; 	
								  
								  $contents .= '                <div class="clearfix"></div><br />' . PHP_EOL;	  	

								  $contents .= '                <div class="form-group">' . PHP_EOL;			  
								  $contents .=                        tep_draw_bs_input_field(option_length, $options_values['products_options_length'], TABLE_HEADING_OPT_LENGTH, 'id_input_option_lenght' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_LENGTH ) . PHP_EOL; 
								  $contents .= '                </div>' . PHP_EOL;	 					  
								  
								  $contents .= '                <div class="form-group">' . PHP_EOL;			  
								  $contents .=                        tep_draw_bs_input_field(option_order, $options_values['products_options_sort_order'], TABLE_HEADING_OPT_ORDER, 'id_input_option_sort_order' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_ORDER ) . PHP_EOL; 
								  $contents .= '                </div>' . PHP_EOL;	

								  $contents .= '               <div class="form-group">' . PHP_EOL ;									   
								  $contents .= '                  ' . tep_draw_bs_pull_down_menu('option_type', tep_optiontype_pulldown() , $options_values['products_options_type'], TABLE_HEADING_OPT_TYPE, 'id_input_option_type', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								  $contents .= '               </div>' . PHP_EOL ;	

								  switch ($options_values['products_options_track_stock']) {
									  case '0': $in_status = false; $out_status = true; break;
									  case '1':
									  default: $in_status = true; $out_status = false;
								  }
																  
								  $contents .= '                <div class="input-group">' . PHP_EOL;	  
								  $contents .= '                    <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
								  $contents .= '                       <label>' . TABLE_HEADING_OPT_STOCK . '</label><br />' . PHP_EOL;
								  $contents .= '                       <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_STOCK_ACTIVE  . PHP_EOL;
								  $contents .= '                           ' . tep_draw_radio_field("track_stock", "1", $in_status) . PHP_EOL;
								  $contents .= '                       </label>' . PHP_EOL;	  
								  $contents .= '                        <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_STOCK_NOT_ACTIVE .  PHP_EOL;
								  $contents .= '                               ' . tep_draw_radio_field("track_stock", "0", $out_status) . PHP_EOL;
								  $contents .= '                        </label>' . PHP_EOL;
								  $contents .= '                    </div>' . PHP_EOL;
								  $contents .= '                </div>' . PHP_EOL;										  
								
								

//								$contents .= '                            <div class="col-md-4 text-right">' . PHP_EOL;
//								$contents .= '                              ' . tep_draw_bs_button(IMAGE_SAVE, 'ok') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info)) . PHP_EOL;
//								$contents .= '                            </div>' . PHP_EOL;
//								$contents .= '                          </form>' . PHP_EOL;
                                
								$contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=no_save_option&' . $page_info), null, null, 'btn-default text-danger') . PHP_EOL;			
		                        $contents_footer .= '                      </form>' . PHP_EOL;
		                        $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                        $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel										
							  break;
							}
		                         
						    $contents .=  '</div>' . PHP_EOL ;
		                    $contents .=  $contents_sv_cncl  . PHP_EOL;
                            $contents .=  $contents_footer  . PHP_EOL;		
							
							echo '                    <tr class="content-row">' . PHP_EOL .
								 '                      <td colspan="8">' . PHP_EOL .
								 '                        <div class="row' . $alertClass . '">' . PHP_EOL .
															$contents . 
								 '                        </div>' . PHP_EOL .
								 '                      </td>' . PHP_EOL .
								 '                    </tr>' . PHP_EOL;			  
						  }
												  
                          $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
                          $max_options_id_values = tep_db_fetch_array($max_options_id_query);
                          $next_id = $max_options_id_values['next_id'];
                       }
?>
                  </tbody>
                </table>        
<?php
//                if (!($HTTP_GET_VARS['action'])) {
					if ( ( ( $HTTP_GET_VARS['action'] != delete_product_option)  ) and ( $HTTP_GET_VARS['action'] != update_option) ) {	
?>
					  <?php echo tep_draw_bs_button(IMAGE_NEW_ATTRIBUTE_NEW_OPTION, 'plus', null,'data-toggle="modal" data-target="#attributes_new_option"') ; ?>
<?php
                }
?>		  
		  </div> <!-- end div panel body-->
		</div>  <!-- <div id="collapse_Options" cla -->
	  </div>

  </div> <!-- <div class="panel-group" id="accord -->
</div>  <!-- col-md-12 -->

        <div class="modal fade"  id="attributes_new_option" role="dialog" aria-labelledby="attributes_new_option" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('attributes_new_options', FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&' . $page_info, 'post', 'role="form"', 'id_attributes_new_option' ) ;?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_OPTION; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			        $contents_new_option .= '       <div class="panel panel-primary">' . PHP_EOL ;
			        $contents_new_option .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_OPTION . '</div>' . PHP_EOL;
			        $contents_new_option .= '          <div class="panel-body">' . PHP_EOL;	
					  
					$contents_new_option .= '                <div class="form-group">' . PHP_EOL;			  
					$contents_new_option .=                        tep_draw_hidden_field('products_options_id', $next_id) . PHP_EOL; 
					$contents_new_option .= '                </div>' . PHP_EOL;	 
				
					$new_option_name_navtabs = '';
					$new_option_name_tabcontent = '';
					for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
					  
						$new_option_name_navtabs .= '                         <li '. (($i == 0) ? 'class="active"' : '') .'>' . PHP_EOL;
						$new_option_name_navtabs .= '                           <a href="#tab_edit_prod_name' . $i . '" data-toggle="tab">' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], null, null, null, false) . "  " . $languages[$i]['name'] . '</a>' . PHP_EOL;
						$new_option_name_navtabs .= '                         </li>' . PHP_EOL;
				   
						// now the content for each language
//						$new_option_name_tabcontent .= '                      <br />' . PHP_EOL;						
						$new_option_name_tabcontent .= '                      <div class="tab-pane fade'. (($i == 0) ? ' active in' : '') .'" id="tab_edit_prod_name' . $i . '">' . PHP_EOL; 
						$new_option_name_tabcontent .= '                         <div class="form-group">' . PHP_EOL;		
						$new_option_name_tabcontent .=                              tep_draw_bs_input_field('option_name[' . $languages[$i]['id'] . ']', null, TABLE_HEADING_OPT_NAME, 'id_input_option_name' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_NAME ) . PHP_EOL;
						$new_option_name_tabcontent .= '                         </div>' . PHP_EOL;	  

//						$new_option_name_tabcontent .= '                         <br />' . PHP_EOL;						
						
						$new_option_name_tabcontent .= '                         <div class="form-group">' . PHP_EOL;			  
						$new_option_name_tabcontent .=                               tep_draw_bs_input_field('option_comment[' . $languages[$i]['id'] . ']', null, TABLE_HEADING_OPT_COMMENT, 'id_input_option_comment' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_COMMENT ) . PHP_EOL; 
						$new_option_name_tabcontent .= '                         </div>' . PHP_EOL;	  
						$new_option_name_tabcontent .= '                      </div>' . PHP_EOL;
					  }	
					
					  $contents_new_option .= '                <br />' . PHP_EOL ;	
					  $contents_new_option .= '                <div role="tabpanel" id="tab_attribute_new_option">'  . PHP_EOL;    
					  $contents_new_option .= '                    <ul class="nav nav-tabs" role="tablist" id="tab_attribute_new_option">' . PHP_EOL; 
					  $contents_new_option .= 	                        $new_option_name_navtabs;
					  $contents_new_option .= '                    </ul>' . PHP_EOL; 

					  $contents_new_option .= '                    <div class="tab-content" id="tab_attribute_new_option">' . PHP_EOL; 
					  $contents_new_option .=                           $new_option_name_tabcontent;
					  $contents_new_option .= '                    </div>' . PHP_EOL; 
					  $contents_new_option .= '                </div>' . PHP_EOL; 	
					  
					  $contents_new_option .= '                <div class="clearfix"></div><br />' . PHP_EOL;	  	

					  $contents_new_option .= '                <div class="form-group">' . PHP_EOL;			  
					  $contents_new_option .=                        tep_draw_bs_input_field(option_length, null, TABLE_HEADING_OPT_LENGTH, 'id_input_option_lenght' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_LENGTH ) . PHP_EOL; 
					  $contents_new_option .= '                </div>' . PHP_EOL;	 					  
					  
					  $contents_new_option .= '                <div class="form-group">' . PHP_EOL;			  
					  $contents_new_option .=                        tep_draw_bs_input_field(option_order, null, TABLE_HEADING_OPT_ORDER, 'id_input_option_sort_order' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_ORDER ) . PHP_EOL; 
					  $contents_new_option .= '                </div>' . PHP_EOL;	

                      $contents_new_option .= '               <div class="form-group">' . PHP_EOL ;									   
				      $contents_new_option .= '                  ' . tep_draw_bs_pull_down_menu('option_type', tep_optiontype_pulldown() , null, TABLE_HEADING_OPT_TYPE, 'id_input_option_type', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                      $contents_new_option .= '               </div>' . PHP_EOL ;	
					  
					  $in_status  = false ;
					  $out_status = true ;
					  
                      $contents_new_option .= '                <div class="input-group">' . PHP_EOL;	  
	                  $contents_new_option .= '                    <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
	                  $contents_new_option .= '                       <label>' . TABLE_HEADING_OPT_STOCK . '</label><br />' . PHP_EOL;
	                  $contents_new_option .= '                       <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_STOCK_ACTIVE  . PHP_EOL;
	                  $contents_new_option .= '                           ' . tep_draw_radio_field("track_stock", "1", $in_status) . PHP_EOL;
	                  $contents_new_option .= '                       </label>' . PHP_EOL;	  
	                  $contents_new_option .= '                        <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_STOCK_NOT_ACTIVE .  PHP_EOL;
	                  $contents_new_option .= '                               ' . tep_draw_radio_field("track_stock", "0", $out_status) . PHP_EOL;
	                  $contents_new_option .= '                        </label>' . PHP_EOL;
	                  $contents_new_option .= '                    </div>' . PHP_EOL;
	                  $contents_new_option .= '                </div>' . PHP_EOL;				  
					  
		              $contents_new_option_footer .= '</div>' . PHP_EOL; // end div 	panel body
		              $contents_new_option_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents_new_option . $contents_new_option_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info )); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_country -->
<?php
?>