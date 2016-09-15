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
	<div class="panel-group" id="accordion_values" role="tablist" aria-multiselectable="true">
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="heading_Values">
		  <h4 class="panel-title">
			<a role="button" data-toggle="collapse" data-parent="#accordion_values" href="#collapse_values" aria-expanded="true" aria-controls="collapse_Options">
			  <?php echo TEXT_TAB_VALUES ; ?>
			</a>
		  </h4>
		</div>
		<div id="collapse_values" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_Values">
		  <div class="panel-body">
                <div class="panel-heading">                 
				  <?php echo HEADING_TITLE_VAL . PHP_EOL ; ?>
                  <div class="pull-right">
<?php
						$values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id where pov.language_id = '" . (int)$languages_id . "' order by pov.products_options_values_id";
						//$value_page = (int)preg_replace( '/=/', '', strstr( $page_info, '=') );		
						$values_split = new splitPageResults($value_page, MAX_ROW_LISTS_OPTIONS, $values, $values_query_numrows);
						echo $values_split->display_links($values_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $value_page, 'action=page_value&option_page=' . $option_page . '&attribute_page=' . $attribute_page, 'value_page');
?>
                  </div>
                </div><!--panel-heading -->
                <table class="table table-striped">
                  <thead>			  
                    <tr>
                      <th><?php echo TABLE_HEADING_ID;                        ?></th>
                      <th><?php echo TABLE_HEADING_OPT_NAME;                  ?></th> 
                      <th><?php echo TABLE_HEADING_OPT_VALUE ;                ?></th>
                      <th><?php echo TABLE_HEADING_ACTION;                    ?></th>
                    </tr>
                  </thead>
                  <tbody>
<?php
					   $next_id = 1;
					   $rows = 0;
					   $values = tep_db_query($values);
					   while ($values_values = tep_db_fetch_array($values)) {
                          if ( $values_values['products_options_id'] != 0 ) {				   
							  $options_name = tep_options_name($values_values['products_options_id']);
							  $values_name = $values_values['products_options_values_name'];
							  $option_id = $values_values['products_options_id'];
							  $rows++;
?>
							  <tr>
								  <td class="text-center"><?php echo $values_values["products_options_values_id"]; ?>          </td>
								  <td>                    <?php echo $options_name; ?>                                         </td>
								  <td>                    <?php echo $values_name; ?>                                          </td>
								  
								  <td class="text-right">
									  <div class="btn-toolbar" role="toolbar">
<?php
			  echo '                        <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info), null, 'warning') . '</div>' . "\n" .
				   '                        <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'] . '&' . $page_info), null, 'danger') . '</div>' . "\n";
?>
									  </div>
								  </td>
							  </tr>		
<?php
							  if (($HTTP_GET_VARS['value_id'] == $values_values['products_options_values_id']) && ($HTTP_GET_VARS['action'])) {       
								switch ($action) {	 
								  case 'delete_option_value':
									$values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$HTTP_GET_VARS['value_id'] . "' and language_id = '" . (int)$languages_id . "'");
									$values_values = tep_db_fetch_array($values);
									$contents .= '                          <div class="col-md-12">' . "\n";
									
									$products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name, po.products_options_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and po.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . (int)$HTTP_GET_VARS['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name");
									if (tep_db_num_rows($products)) {
									  $alertClass .= ' alert-message alert-message-danger';
									  $contents .= '                            <table class="table table-bordered">' . "\n";
									  $contents .= '                              <thead>' . "\n";			  
									  $contents .= '                                <tr>' . "\n";
									  $contents .= '                                <th>' . TABLE_HEADING_ID . '</th>' . "\n";
									  $contents .= '                                <th>' . TABLE_HEADING_PRODUCT . '</th>' . "\n";
									  $contents .= '                                <th>' . TABLE_HEADING_OPT_NAME . '</th>' . "\n";
									  $contents .= '                              </tr>' . "\n";
									  $contents .= '                            </thead>' . "\n";
									  $contents .= '                            <tbody>' . "\n";
									  while ($products_values = tep_db_fetch_array($products)) {
										$rows++;
										$contents .= '                              <tr>' . "\n";
										$contents .= '                                <td>' . $products_values['products_id'] . '</td>' . "\n";
										$contents .= '                                <td>' . $products_values['products_name'] . '</td>' . "\n";
										$contents .= '                                <td>' . $products_values['products_options_name'] . '</td>' . "\n";
										$contents .= '                              </tr>' . "\n";
									  
									  }
									  $contents .= '                            </tbody>' . "\n";
									  $contents .= '                          </table>' . "\n";
									  $contents .= '                          <p>' . TEXT_WARNING_OF_DELETE . '</p>' . "\n";
									  $contents .= '                          <span class="pull-right">' . tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=no_delete_value&' . $page_info), null, null, 'btn-default text-danger') . '</span>' . "\n";
									} else {
										
									  $alertClass .= ' alert-message alert-message-notice';
									  $contents .= '                          <p>' . TEXT_OK_TO_DELETE . '</p>' . "\n";
									  $contents .= '                          <span class="pull-right">' . tep_draw_bs_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $HTTP_GET_VARS['value_id'] . '&' . $page_info), null, null, 'btn-danger') . '<br>' . 
									                                                                       tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=nodelete_value&' . $page_info), null, null, 'btn-default text-danger') . '</span>' . "\n";
									}
									$contents .= '                        </div>' . "\n";
								  break;
								  
								  case 'update_option_value':
								  
									$select_options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
									while ($select_options_values = tep_db_fetch_array($select_options)) {
                                        switch ($options_values['products_options_type']) {
                                           case OPTIONS_TYPE_TEXT:
                                           case OPTIONS_TYPE_TEXTAREA:
                                           case OPTIONS_TYPE_FILE:
                                             // Exclude from dropdown
                                             break;
                                           default:										
										       $options_array[] = array('id' => $select_options_values['products_options_id'],
												       				  'text' => $select_options_values['products_options_name']);
										}
									}

			                        $contents .= '           <div class="panel panel-primary">' . PHP_EOL ;
			                        $contents .= '                 <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_VALUE . '</div>' . PHP_EOL;
			                        $contents .= '                 <div class="panel-body">' . PHP_EOL;	
									$contents .= '                        ' . tep_draw_form('values', FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value&' . $page_info, 'post', 'class="form-inline"') . PHP_EOL;
									
								    $contents .= '                            <div class="form-group">' . PHP_EOL;			  
								    $contents .=                                 tep_draw_hidden_field('value_id', $values_values['products_options_values_id'])	 . PHP_EOL; 
								    $contents .= '                            </div>' . PHP_EOL;										
																	
									$inputs = '';
									for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
									  $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_values['products_options_values_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
									  $value_name = tep_db_fetch_array($value_name);
									  $inputs .= '                            <div class="form-group">' .PHP_EOL .
												 '                              <div class="input-group">' .PHP_EOL .
												 '                                <div class="input-group-addon">' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'])  . '</div>' .PHP_EOL .
												 '                                  ' . tep_draw_input_field('value_name[' . $languages[$i]['id'] . ']', $value_name['products_options_values_name']) . PHP_EOL .
												 '                                </div>' .PHP_EOL .
												 '                              </div>' .PHP_EOL;	
								      $inputs .= '                              <div class="clearfix"></div><br />' . PHP_EOL;													 
									}
									$contents .= '                          <div class="col-md-1">' .PHP_EOL;
									$contents .= '                            '. $values_values['products_options_values_id'] .PHP_EOL;
									$contents .= '                          </div>' .PHP_EOL;							      
								
									$contents .= '                          <div class="col-md-7">' .PHP_EOL;
								    $contents .= '                            <div class="form-group">' . PHP_EOL;										
//									$contents .= '                            ' . tep_draw_pull_down_menu('option_id', $options_array, $values_values['products_options_id']) .PHP_EOL;
								    $contents .= '                            ' . tep_draw_bs_pull_down_menu('option_id', $options_array, $values_values['products_options_id'], TABLE_HEADING_OPT_VALUE, 'id_input_option_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents .= '                            </div>' . PHP_EOL;										
									$contents .= '                          <div class="clearfix"></div><br />' . PHP_EOL;	
									
									$contents .=                            $inputs;
 
									$contents .= '                          </div>' . PHP_EOL;
									$contents .= '                          <div class="col-md-4 text-right">' .PHP_EOL;
									$contents .= '                            ' . tep_draw_bs_button(IMAGE_SAVE, 'ok'). '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=no_save_value&'. $page_info)) . PHP_EOL;
									$contents .= '                          </div>' . PHP_EOL;
									$contents .= '                        </form>' . PHP_EOL;
		                            $contents .= '                 </div>' . PHP_EOL; // end div 	panel body
		                            $contents .= '           </div>' . PHP_EOL; // end div 	panel										
								  break;
								}
								
								echo '                    <tr class="content-row">' . PHP_EOL .
									 '                      <td colspan="4">' . PHP_EOL .
									 '                        <div class="row' . $alertClass . '">' . PHP_EOL .
																$contents . 
									 '                        </div>' . PHP_EOL .
									 '                      </td>' . PHP_EOL .
									 '                    </tr>' . PHP_EOL;		  
								} // eof if

							  $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
							  $max_values_id_values = tep_db_fetch_array($max_values_id_query);
							  $next_id = $max_values_id_values['next_id'];
						   } // endif id != 0
					   } // end while 
?>
					  </tbody>
					</table>        
<?php
					if ( ( ( $HTTP_GET_VARS['action'] != delete_option_value)  ) and ( $HTTP_GET_VARS['action'] != update_option_value) ) {
?>
						  <?php echo tep_draw_bs_button(IMAGE_NEW_ATTRIBUTE_NEW_VALUE, 'plus', null,'data-toggle="modal" data-target="#attributes_new_value"') ; ?>
<?php
                }
?>		  
		  </div> <!-- end div panel body-->
		</div>  <!-- <div id="collapse_Options" cla -->
	  </div>
 
  </div> <!-- <div class="panel-group" id="accord -->
</div>  <!-- col-md-12 -->

        <div class="modal fade"  id="attributes_new_value" role="dialog" aria-labelledby="attributes_new_value" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('attributes_new_value', FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values&' . $page_info, 'post', 'role="form"', 'id_attributes_new_values' ) ;?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_VALUE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			        $contents_new_value .= '       <div class="panel panel-primary">' . PHP_EOL ;
			        $contents_new_value .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_VALUE . '</div>' . PHP_EOL;
			        $contents_new_value .= '          <div class="panel-body">' . PHP_EOL;	
					  
					$contents_new_value .= '                <div class="form-group">' . PHP_EOL;			  
					$contents_new_value .=                        tep_draw_hidden_field('value_id', $next_id) . PHP_EOL; 
					$contents_new_value .= '                </div>' . PHP_EOL;	 
//									<input type="hidden" name="value_id" value="<?php echo $next_id; 
				
					$select_options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
					while ($select_options_values = tep_db_fetch_array($select_options)) {
                       switch ($options_values['products_options_type']) {
                           case OPTIONS_TYPE_TEXT:
                           case OPTIONS_TYPE_TEXTAREA:
                           case OPTIONS_TYPE_FILE:
                              // Exclude from dropdown
                              break;
                           default:										
							 $options_array[] = array('id' => $select_options_values['products_options_id'],
							       	                'text' => $select_options_values['products_options_name']);
						}
					}

				    $inputs = '';
					for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
//						$value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_values['products_options_values_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
//						$value_name = tep_db_fetch_array($value_name);
						$inputs .= '                            <div class="form-group">' .PHP_EOL .
								   '                              <div class="input-group">' .PHP_EOL .
								   '                                <div class="input-group-addon">' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'])  . '</div>' .PHP_EOL .
								   '                                  ' . tep_draw_input_field('value_name[' . $languages[$i]['id'] . ']', null) . PHP_EOL .
								   '                                </div>' .PHP_EOL .
								   '                              </div>' .PHP_EOL;	
						$inputs .= '                              <div class="clearfix"></div><br />' . PHP_EOL;													 
					}
					$contents_new_value .= '                          <div class="col-md-1">' .PHP_EOL;
					$contents_new_value .= '                            '. $values_values['products_options_values_id'] .PHP_EOL;
					$contents_new_value .= '                          </div>' .PHP_EOL;							      
								
					$contents_new_value .= '                          <div class="col-md-7">' .PHP_EOL;
//					$contents_new_value .= '                            ' . tep_draw_pull_down_menu('option_id', $options_array, $values_values['products_options_id']) .PHP_EOL;
					$contents_new_value .= '                            ' . tep_draw_bs_pull_down_menu('option_id', $options_array, null, TABLE_HEADING_OPT_VALUE, 'id_input_option_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
					$contents_new_value .= '                          <div class="clearfix"></div><br />' . PHP_EOL;	
									
					$contents_new_value .=                            $inputs;
 
					$contents_new_value .= '                          </div>' . PHP_EOL;
									  
					  
		              $contents_new_value_footer .= '</div>' . PHP_EOL; // end div 	panel body
		              $contents_new_value_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents_new_value . $contents_new_value_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL')); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_country -->
<?php
?>