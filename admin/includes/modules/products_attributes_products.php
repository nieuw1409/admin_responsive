<?php
/*
  $Id: create_order_details.php,v 1.2 2005/09/04 04:42:56 loic Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/


?>
  <div class="col-xs-12">
	<div class="panel-group" id="accordion_products" role="tablist" aria-multiselectable="true">
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="heading_products">
		  <h4 class="panel-title">
			<a role="button" data-toggle="collapse" data-parent="#accordion_products" href="#collapse_produts" aria-expanded="true" aria-controls="collapse_Options">
			  <?php echo HEADING_TITLE_ATRIB ; ?>
			</a>
		  </h4>
		</div>
		<div id="collapse_produts" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_products">
		  <div class="panel-body">
                <div class="panel-heading">                 
				  <?php echo HEADING_TITLE_VAL . PHP_EOL ; ?>
				  
                  <div class="pull-right">
<?php
					$attributes = "select pa.*, pd.products_name from " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pa.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name,pa.products_options_sort_order";
//					$attribute_page = (int)preg_replace( '/=/', '', strstr( $page_info, '=') );	
				    $attributes_split = new splitPageResults($attribute_page, MAX_ROW_LISTS_OPTIONS, $attributes, $attributes_query_numrows);
				    echo $attributes_split->display_links($attributes_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $attribute_page, 'option_page=' . $option_page . '&value_page=' . $value_page, 'attribute_page');
					
?>
                  </div>
                </div><!--panel-heading -->
                <table class="table table-striped">
                  <thead>			  
                    <tr>
                      <th><?php echo TABLE_HEADING_ID;                       ?></th>
                      <th><?php echo TABLE_HEADING_OPT_ORDER ;               ?></th>
                      <th><?php echo TABLE_HEADING_PRODUCT;                  ?></th> 					  
                      <th><?php echo TABLE_HEADING_OPT_NAME ;                ?></th>
                      <th><?php echo TABLE_HEADING_OPT_VALUE;                ?></th>
                      <th><?php echo TABLE_HEADING_OPT_PRICE;                ?></th>
                      <th><?php echo TABLE_HEADING_OPT_PRICE_PREFIX;         ?></th>			
			          <th><?php echo TABLE_HEADING_HIDDEN;                   ?></th>					  
                      <th><?php echo TABLE_HEADING_ACTION;                   ?></th>
                    </tr>
                  </thead>
                  <tbody>
<?php
                       $next_id = 1; 
                       $attributes = tep_db_query($attributes);
                       while ($attributes_values = tep_db_fetch_array($attributes)) {
                           // BOF SPPC  
                           //    $products_name_only = tep_get_products_name($attributes_values['products_id']);
                           $products_name_only = $attributes_values['products_name'];
                           // EOF SPPC	
                           $options_name = tep_options_name($attributes_values['options_id']);
                           $values_name = tep_values_name($attributes_values['options_values_id']);
                           $rows++;
?>
							  <tr>
								  <td class="text-center"><?php echo $attributes_values["products_attributes_id"];                                                            ?></td>
								  <td class="text-center"><?php echo $attributes_values["products_options_sort_order"];                                                       ?></td>								  
								  <td>                    <?php echo $products_name_only;                                                                                     ?></td>
								  <td>                    <?php echo $options_name;                                                                                           ?></td>
								  <td>                    <?php echo $values_name;                                                                                            ?></td>
								  <td>                    <?php echo $attributes_values["options_values_price"];                                                              ?></td>								  
								  <td>                    <?php echo $attributes_values["price_prefix"];                                                                      ?></td>									  
								  <td>                    <?php echo $hide_info = tep_get_hide_info($customers_groups, $attributes_values['attributes_hide_from_groups']);    ?></td>									  
								  
								  <td class="text-right">
									  <div class="btn-toolbar" role="toolbar">
<?php
			  echo '                        <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_attribute&attribute_id=' . $attributes_values["products_attributes_id"] . '&' . $page_info), null, 'warning') . '</div>' . "\n" .
				   '                        <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_attribute&attribute_id=' . $attributes_values["products_attributes_id"] . '&' . $page_info), null, 'danger') . '</div>' . "\n";
?>
									  </div>
								  </td>
							  </tr>		
<?php
							  if (($HTTP_GET_VARS['attribute_id'] == $attributes_values["products_attributes_id"]) && ($HTTP_GET_VARS['action'])) {       
								switch ($action) {	 
								  case 'delete_option_attribute':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_OPTION_ATTRIBUTE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert-message alert-message-danger';
		                               $contents .= '                      ' . tep_draw_bs_form('del_attribute', FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_attribute&attribute_id=' . $HTTP_GET_VARS['attribute_id'] . '&' . $page_info, 'post', 'role="form"', 'id_del_option_attrubute') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $products_name_only .  '  ' .  $options_name  .  ' ' . $values_name . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_id=' . $HTTP_GET_VARS['attribute_id'] . '&' . $page_info), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;		
								  
								  case 'update_option_attribute':
 
									$retail_price_and_prefix = array('options_values_price' => $attributes_values['options_values_price'], 'price_prefix' => $attributes_values['price_prefix']);
									// array for attribute_hide_from_groups
								 	$hide_from_groups_array = explode(',' , $attributes_values['attributes_hide_from_groups']);
									$hide_from_groups_array = array_slice($hide_from_groups_array, 1); // remove "@" from the array


									$attribute_cg_query = tep_db_query("select customers_group_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " pag where products_attributes_id = '" . $attributes_values['products_attributes_id'] . "'");

									while ($_attributes_cg = tep_db_fetch_array($attribute_cg_query)) {
										$attributes_cg[] = $_attributes_cg;
									}
									
								    $no_of_attributes_cg = count($attributes_cg);
									

									$no_of_customer_groups = count($customers_groups);
									for ($x = 0; $x < $no_of_customer_groups; $x++) {
										$new_attributes_cg[$x] = $customers_groups[$x];
										$new_attributes_cg['0'] = array_merge($new_attributes_cg['0'], $retail_price_and_prefix);
										for ($i = 0; $i < $no_of_attributes_cg; $i++) {
											// customer group 0 is not in the table products_attributes_groups but price and prefix are in
											// the table product_attributes
											if( $customers_groups[$x]['customers_group_id'] == $attributes_cg[$i]['customers_group_id'] ) {
											$new_attributes_cg[$x] = array_merge($new_attributes_cg[$x], $attributes_cg[$i]);
											}
									  }
									} // end for ($x = 0; $x < $no_of_customer_groups; $x++)
						  
									// product name pulldown menu array for adding attributes to
									$select_products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
									while($select_products_values = tep_db_fetch_array($select_products)) {
										$attributes_array[] = array('id' =>   $select_products_values['products_id'],
																	'text' => $select_products_values['products_name']); 
									}
									//product values array pulldown menu for attribute use
									$select_option_value = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . $languages_id . "' order by products_options_values_name");
									while($select_values_values = tep_db_fetch_array($select_option_value)) {
										if ( $select_values_values['products_options_values_id'] != 0 ) {
										   $values_array[] = array('id' => $select_values_values['products_options_values_id'],
											    				 'text' => $select_values_values['products_options_values_name']); 
									    }
									} 
									
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
								  

			                        $contents  = '           <div class="panel panel-primary">' . PHP_EOL ;
			                        $contents .= '                 <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_VALUE . '</div>' . PHP_EOL;
			                        $contents .= '                 <div class="panel-body">' . PHP_EOL;	
									$contents .= '                        ' . tep_draw_bs_form('update_option_attribute', FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_product_attribute'  . '&' . $page_info, 'post', 'role="form"', 'id_update_option_attribute') . PHP_EOL;
									
								    $contents .= '                            <div class="form-group">' . PHP_EOL;			  
								    $contents .=                                 tep_draw_hidden_field('attribute_id', $attributes_values['products_attributes_id'])	 . PHP_EOL; 
								    $contents .= '                            </div>' . PHP_EOL;										
																	
								    $contents .= '                            <div class="form-group">' . PHP_EOL;	
								    $contents .= '                            ' . tep_draw_bs_pull_down_menu('products_id', $attributes_array, $attributes_values['products_id'], TABLE_HEADING_PRODUCT, 'id_input_att_products_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents .= '                            </div>' . PHP_EOL;										
									
								    $contents .= '                            <div class="form-group">' . PHP_EOL;	// tep_draw_pull_down_menu('options_id', $options_array, $attributes_values['options_id'])
								    $contents .= '                            ' . tep_draw_bs_pull_down_menu('options_id', $options_array, $attributes_values['options_id'], TABLE_HEADING_OPT_NAME, 'id_input_att_option_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents .= '                            </div>' . PHP_EOL;

								    $contents .= '                            <div class="form-group">' . PHP_EOL; // tep_draw_pull_down_menu('values_id', $values_array, $attributes_values['options_values_id'])
								    $contents .= '                            ' . tep_draw_bs_pull_down_menu('values_id', $values_array, $attributes_values['options_values_id'], TABLE_HEADING_OPT_VALUE, 'id_input_att_values_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents .= '                            </div>' . PHP_EOL;

								    $contents .= '                            <div class="form-group">' . PHP_EOL;			  
								    $contents .=                                 tep_draw_bs_input_field(value_order, $attributes_values['products_options_sort_order'], TABLE_HEADING_OPT_ORDER, 'id_input_values_order' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_ORDER ) . PHP_EOL; 
								    $contents .= '                            </div>' . PHP_EOL;									
									
									
								    $contents .= '                            <div class="form-group">' . PHP_EOL; // tep_draw_pull_down_menu('values_id', $values_array, $attributes_values['options_values_id'])
								    $contents .= '                            ' . tep_draw_bs_pull_down_menu('price_prefix', $price_prefix_array, $attributes_values['price_prefix'], TABLE_HEADING_OPT_PRICE, 'id_input_att_values_price_prefix', 'col-xs-1', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents .= '                            </div>' . PHP_EOL;
									
								    $contents .= '                            <div class="form-group">' . PHP_EOL;			  
								    $contents .=                                 tep_draw_bs_input_field(value_price, $attributes_values['options_values_price'], null, 'id_input_values_price' , null, 'col-xs-8',  null, TABLE_HEADING_OPT_PRICE ) . PHP_EOL; 
								    $contents .= '                            </div>' . PHP_EOL;	

//									$contents .= '                          <div class="clearfix"></div>' . PHP_EOL;									
									
										
									
									
									if (DOWNLOAD_ENABLED == 'true') {
									   $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
									   $download_query = tep_db_query($download_query_raw);
									   if (tep_db_num_rows($download_query) > 0) {
										  $download = tep_db_fetch_array($download_query);
										  $products_attributes_filename = $download['products_attributes_filename'];
										  $products_attributes_maxdays  = $download['products_attributes_maxdays'];
										  $products_attributes_maxcount = $download['products_attributes_maxcount'];
									   }
										
									   $contents .= '                          <div class="clearfix"></div><br />' . PHP_EOL;	
								       $contents .= '                            	<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#downloads">' . tep_glyphicon('download-alt glyphicon-lg') . TABLE_HEADING_DOWNLOAD . '</button>' . PHP_EOL;
								       $contents .= '                            	<div id="downloads" class="collapse">' . PHP_EOL;
								       $contents .= '                            	  <br>' . PHP_EOL;
								       $contents .= '                            	  <div class="col-md-4">' . PHP_EOL;
								       $contents .= '                            		  <label class="sr-only" for="products_attributes_filename">' . TABLE_TEXT_FILENAME . '</label>' . PHP_EOL;
								       $contents .= '                            		  <div class="form-group">' . PHP_EOL;
								       $contents .= '                            			 <div class="input-group">' . PHP_EOL;
								       $contents .= '                            			    <div class="input-group-addon">' . tep_glyphicon('file') . '</div>' . PHP_EOL;
								       $contents .=                             				   tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'placeholder="' . TABLE_TEXT_FILENAME . '"') . PHP_EOL;
								       $contents .= '                            			 </div>' . PHP_EOL;
								       $contents .= '                            			</div>' . PHP_EOL;
								       $contents .= '                            	  </div>' . PHP_EOL;
								       $contents .= '                            	  <div class="col-md-4">' . PHP_EOL;
								       $contents .= '                            		   <label class="sr-only" for="products_attributes_maxdays">'. TABLE_TEXT_MAX_DAYS . '</label>' . PHP_EOL;
								       $contents .= '                            		   <div class="form-group">' . PHP_EOL;
								       $contents .= '                            			  <div class="input-group">' . PHP_EOL;
								       $contents .= '                            			      <div class="input-group-addon">' . tep_glyphicon('calendar') . '</div>' . PHP_EOL;
								       $contents .=                             					     tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'placeholder="' . TABLE_TEXT_MAX_DAYS . '"') . PHP_EOL;
								       $contents .= '                            			   </div>' . PHP_EOL;
								       $contents .= '                            		   </div>' . PHP_EOL;
								       $contents .= '                            	  </div>' . PHP_EOL;
								       $contents .= '                            	  <div class="col-md-4">' . PHP_EOL;
								       $contents .= '                            			 <label class="sr-only" for="products_attributes_maxcount">' .  TABLE_TEXT_MAX_COUNT . '</label>' . PHP_EOL;
								       $contents .= '                            			 <div class="form-group">' . PHP_EOL;
								       $contents .= '                            			    <div class="input-group">' . PHP_EOL;
								       $contents .= '                            				    <div class="input-group-addon">' . tep_glyphicon('download') . '</div>' . PHP_EOL;
								       $contents .=                             					     tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'placeholder="' . TABLE_TEXT_MAX_COUNT . '"') . PHP_EOL;
								       $contents .= '                            					</div>' . PHP_EOL;
								       $contents .= '                            				</div>' . PHP_EOL;
								       $contents .= '                            		</div>' . PHP_EOL;
								       $contents .= '                            	</div>' . PHP_EOL;
									   $contents .= '                          <div class="clearfix"></div><hr><br />' . PHP_EOL;
									}	

									$_has_more_than_one_customer_group = false ;
	                                for ($x = 0; $x < $no_of_customer_groups; $x++) {
                                             $_has_more_than_one_customer_group = true ;	
									         $contents_cus_group_input .= '                          <tr>' . PHP_EOL;											 
									         $contents_cus_group_input .= '                             <td>' . $new_attributes_cg[$x]['customers_group_name'] . '</td>'. PHP_EOL;											 
											 
										     if ( $new_attributes_cg[$x]['customers_group_id'] != 0 ) {  // price of retail has input above
									
								                $contents_cus_group_input .= '                            <div class="form-group">' . PHP_EOL;			  
								                $contents_cus_group_input .=                                 tep_draw_hidden_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[cust_group_id]', $new_attributes_cg[$x]['customers_group_id'] )	 . PHP_EOL; 
								                $contents_cus_group_input .=                                 tep_draw_hidden_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[in_db]', (isset($new_attributes_cg[$x]['price_prefix']) ? '1' : '0') )	 . PHP_EOL; 
								                $contents_cus_group_input .= '                            </div>' . PHP_EOL;														 
												
									            $contents_cus_group_input .= '                             <td>' . PHP_EOL ;													 
								                $contents_cus_group_input .= '                               <div class="form-group">' . PHP_EOL;														 
											    $contents_cus_group_input .=                                      tep_draw_bs_pull_down_menu( $new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[price_prefix]', $price_prefix_array, (($new_attributes_cg[$x]['price_prefix'] == '-') ? '-' : '+')) . PHP_EOL ;
								                $contents_cus_group_input .= '                               </div>' . PHP_EOL;												 
									            $contents_cus_group_input .= '                             </td>' . PHP_EOL ;											 
											 
									            $contents_cus_group_input .= '                             <td>' . PHP_EOL ;												
								                $contents_cus_group_input .= '                                <div class="form-group">' . PHP_EOL;			  
								                $contents_cus_group_input .=                                      tep_draw_bs_input_field( $new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[price]', 
												                                                                                           $new_attributes_cg[$x]['options_values_price'], null, 'id_input_values_price' . $new_attributes_cg[$x]['customers_group_name'] ) . PHP_EOL; 
								                $contents_cus_group_input .= '                                </div>' . PHP_EOL;							
									            $contents_cus_group_input .= '                             </td>' . PHP_EOL ;					 
												
								                $contents_cus_group_input .= '                             <td>' . PHP_EOL ;	
                                                if ( isset($new_attributes_cg[$x]['price_prefix'] ) ){		
								                   $contents_cus_group_input .= '                               <div class="form-group">' . PHP_EOL;														 
											       $contents_cus_group_input .=                                      tep_bs_checkbox_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[del]', null, null, 'id_check_delete_cust_group_price' . $new_attributes_cg[$x]['customers_group_name'],
                                                                                     	                                                   null, 'checkbox checkbox-danger') . PHP_EOL ;
								                   $contents_cus_group_input .= '                               </div>' . PHP_EOL;												 
												
												}
									            $contents_cus_group_input .= '                             </td>' . PHP_EOL ;
												
								                $contents_cus_group_input .= '                             <td>' . PHP_EOL ;	
                                                if ( !isset($new_attributes_cg[$x]['price_prefix'] ) ) {										
								                   $contents_cus_group_input .= '                               <div class="form-group">' . PHP_EOL;														 
											       $contents_cus_group_input .=                                      tep_bs_checkbox_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[insert]', null, null, 'id_check_insert_cust_group_price' . $new_attributes_cg[$x]['customers_group_name'], 
												                                                                                           null, 'checkbox checkbox-success') . PHP_EOL ;
								                   $contents_cus_group_input .= '                               </div>' . PHP_EOL;												 
												}
									            $contents_cus_group_input .= '                             </td>' . PHP_EOL ;		
										
                                             } else { // align hide from groups to the end
             							         $contents_cus_group_input .= '<td></td><td></td><td></td><td></td>' . PHP_EOL ;
										     } // if ( $new_attributes_cg[$x]['customers
									         $contents_cus_group_input .= '                                <td>' . PHP_EOL ;													 
								             $contents_cus_group_input .= '                                   <div class="form-group">' . PHP_EOL;														 
											 $contents_cus_group_input .=                                         tep_bs_checkbox_field('hide[' . $new_attributes_cg[$x]['customers_group_id'] . ']', $new_attributes_cg[$x]['customers_group_id'], null, 'id_check_hidden_cust_group', (in_array($new_attributes_cg[$x]['customers_group_id'], $hide_from_groups_array )) ? 1 : 0, 'checkbox checkbox-danger') . PHP_EOL ;
								             $contents_cus_group_input .= '                                   </div>' . PHP_EOL;												 
									         $contents_cus_group_input .= '                                </td>' . PHP_EOL ;											 
									         $contents_cus_group_input .= '                          <tr>' . PHP_EOL; 								    

	                                } // end for ($x = 0; $x < $no_of_customer_groups; $x++)
                                    if ( $_has_more_than_one_customer_group == true ) {
									   $contents_cus_group .= '                    <table class="table table-striped">' . PHP_EOL;
									   $contents_cus_group .= '                        <thead>' . PHP_EOL;
									   $contents_cus_group .= '                            <tr>' . PHP_EOL;

									   $contents_cus_group .= '                               <th>' .  TABLE_HEADING_GROUP_NAME           . '</th>' . PHP_EOL;
									   $contents_cus_group .= '                               <th>' .  TABLE_HEADING_OPT_PRICE_PREFIX     . '</th>' . PHP_EOL;
									   $contents_cus_group .= '                               <th>' .  TABLE_HEADING_OPT_PRICE            . '</th>' . PHP_EOL;
									   $contents_cus_group .= '                               <th>' .  TABLE_HEADING_DELETE               . '</th>' . PHP_EOL;
									   $contents_cus_group .= '                            	  <th>' .  TABLE_HEADING_INSERT               . '</th>' . PHP_EOL;									   								   
									   $contents_cus_group .= '                            	  <th>' .  TABLE_HEADING_HIDDEN               . '</th>' . PHP_EOL;					  
 
									   $contents_cus_group .= '                            </tr>' . PHP_EOL;
									   $contents_cus_group .= '                        </thead>' . PHP_EOL;
									   $contents_cus_group .= '                        <tbody>' . PHP_EOL;							   
		 
									   $contents_cus_group .=                                $contents_cus_group_input . PHP_EOL;
								   
									   $contents_cus_group .= '                        </tbody>' . PHP_EOL;
									   $contents_cus_group .= '                    </table>' . PHP_EOL;
									   $contents           .= $contents_cus_group ;
								    }  // if ( $_has_more than_one_custom

									$contents .= '                            ' . tep_draw_bs_button(IMAGE_SAVE, 'ok'). tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info)) . PHP_EOL;
									$contents .= '                        </form>' . PHP_EOL;
		                            $contents .= '                 </div>' . PHP_EOL; // end div 	panel body
		                            $contents .= '           </div>' . PHP_EOL; // end div 	panel										
								  break;
								}
								
								echo '                    <tr class="content-row">' . PHP_EOL .
									 '                      <td colspan="9">' . PHP_EOL .
									 '                        <div class="row' . $alertClass . '">' . PHP_EOL .
																$contents . 
									 '                        </div>' . PHP_EOL .
									 '                      </td>' . PHP_EOL .
									 '                    </tr>' . PHP_EOL;		  
								} // eof if

								$max_attributes_id_query = tep_db_query("select max(products_attributes_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES);
								$max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
								$next_id = $max_attributes_id_values['next_id'];
					   } // end while 
?>
					  </tbody>
					</table>        
<?php
					if (!($HTTP_GET_VARS['action'])) {
?>
						  <?php echo tep_draw_bs_button(IMAGE_NEW_ATTRIBUTE_NEW_VALUE, 'plus', null,'data-toggle="modal" data-target="#attributes_new_option_attribute"') ; ?>
<?php
                }
?>		  
		  </div> <!-- end div panel body-->
		</div>  <!-- <div id="collapse_Options" cla -->
	  </div>
 
  </div> <!-- <div class="panel-group" id="accord -->
</div>  <!-- col-md-12 -->

        <div class="modal fade"  id="attributes_new_option_attribute" role="dialog" aria-labelledby="attributes_new_option_attribute" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_option_attribute', FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_attributes'  . '&' . $page_info, 'post', 'role="form"', 'id_attributes_new_option' ) ;?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_OPTION_ATTRIBUTE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
									$retail_price_and_prefix = array('options_values_price' => $attributes_values['options_values_price'], 'price_prefix' => $attributes_values['price_prefix']);
									// array for attribute_hide_from_groups
								 	$hide_from_groups_array = explode(',' , $attributes_values['attributes_hide_from_groups']);
									$hide_from_groups_array = array_slice($hide_from_groups_array, 1); // remove "@" from the array


/*									$attribute_cg_query = tep_db_query("select customers_group_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " pag where products_attributes_id = '" . $attributes_values['products_attributes_id'] . "'");

									while ($_attributes_cg = tep_db_fetch_array($attribute_cg_query)) {
										$attributes_cg[] = $_attributes_cg;
									}
*/									
								    $no_of_attributes_cg = count($attributes_cg);
									

									$no_of_customer_groups = count($customers_groups);
									for ($x = 0; $x < $no_of_customer_groups; $x++) {
										$new_attributes_cg[$x] = $customers_groups[$x];
										$new_attributes_cg['0'] = array_merge($new_attributes_cg['0'], $retail_price_and_prefix);
										for ($i = 0; $i < $no_of_attributes_cg; $i++) {
											// customer group 0 is not in the table products_attributes_groups but price and prefix are in
											// the table product_attributes
											if( $customers_groups[$x]['customers_group_id'] == $attributes_cg[$i]['customers_group_id'] ) {
											$new_attributes_cg[$x] = array_merge($new_attributes_cg[$x], $attributes_cg[$i]);
											}
									  }
									} // end for ($x = 0; $x < $no_of_customer_groups; $x++)
						  
									// product name pulldown menu array for adding attributes to
									$select_products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
									while($select_products_values = tep_db_fetch_array($select_products)) {
										$attributes_array[] = array('id' =>   $select_products_values['products_id'],
																	'text' => $select_products_values['products_name']); 
									}
									//product values array pulldown menu for attribute use
									$select_option_value = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . $languages_id . "' order by products_options_values_name");
									while($select_values_values = tep_db_fetch_array($select_option_value)) {
										if ( $select_values_values['products_options_values_id'] != 0 ) {
										   $values_array[] = array('id' => $select_values_values['products_options_values_id'],
											    				 'text' => $select_values_values['products_options_values_name']); 
									    }
									} 
									
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
								  

			                        $contents_new_option_attrib  = '           <div class="panel panel-primary">' . PHP_EOL ;
			                        $contents_new_option_attrib .= '                 <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_VALUE . '</div>' . PHP_EOL;
			                        $contents_new_option_attrib .= '                 <div class="panel-body">' . PHP_EOL;	
//									$contents_new_option_attrib .= '                        ' . tep_draw_bs_form('update_option_attribute', FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_product_attribute'  . '&' . $page_info, 'post', 'role="form"', 'id_update_option_attribute') . PHP_EOL;
									
//								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL;			  
//								    $contents_new_option_attrib .=                                 tep_draw_hidden_field('attribute_id', $attributes_values['products_attributes_id'])	 . PHP_EOL; 
//								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;										
																	
								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL;	
								    $contents_new_option_attrib .= '                            ' . tep_draw_bs_pull_down_menu('products_id', $attributes_array, null, TABLE_HEADING_PRODUCT, 'id_input_att_products_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;										
									
								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL;	// tep_draw_pull_down_menu('options_id', $options_array, $attributes_values['options_id'])
								    $contents_new_option_attrib .= '                            ' . tep_draw_bs_pull_down_menu('options_id', $options_array, null, TABLE_HEADING_OPT_NAME, 'id_input_att_option_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;

								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL; // tep_draw_pull_down_menu('values_id', $values_array, $attributes_values['options_values_id'])
								    $contents_new_option_attrib .= '                            ' . tep_draw_bs_pull_down_menu('values_id', $values_array, null, TABLE_HEADING_OPT_VALUE, 'id_input_att_values_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;

								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL;			  
								    $contents_new_option_attrib .=                                 tep_draw_bs_input_field(value_order, null, TABLE_HEADING_OPT_ORDER, 'id_input_values_order' , 'col-xs-3', 'col-xs-9',  'left', TABLE_HEADING_OPT_ORDER ) . PHP_EOL; 
								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;									
									
									
								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL; // tep_draw_pull_down_menu('values_id', $values_array, $attributes_values['options_values_id'])
								    $contents_new_option_attrib .= '                            ' . tep_draw_bs_pull_down_menu('price_prefix', $price_prefix_array, '+', TABLE_HEADING_OPT_PRICE, 'id_input_att_values_price_prefix', 'col-xs-1', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;
									
								    $contents_new_option_attrib .= '                            <div class="form-group">' . PHP_EOL;			  
								    $contents_new_option_attrib .=                                 tep_draw_bs_input_field(value_price, null, null, 'id_input_values_price' , null, 'col-xs-8',  null, TABLE_HEADING_OPT_PRICE ) . PHP_EOL; 
								    $contents_new_option_attrib .= '                            </div>' . PHP_EOL;	

//									$contents_new_option_attrib .= '                          <div class="clearfix"></div>' . PHP_EOL;									
									
										
									
									
									if (DOWNLOAD_ENABLED == 'true') {
//									   $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
//									   $download_query = tep_db_query($download_query_raw);
//									   if (tep_db_num_rows($download_query) > 0) {
//										  $download = tep_db_fetch_array($download_query);
//										  $products_attributes_filename = $download['products_attributes_filename'];
//										  $products_attributes_maxdays  = $download['products_attributes_maxdays'];
//										  $products_attributes_maxcount = $download['products_attributes_maxcount'];
//									   }
										
									   $contents_new_option_attrib .= '                          <div class="clearfix"></div><br />' . PHP_EOL;	
								       $contents_new_option_attrib .= '                            	<button type="button" class="btn btn-default btn-block" data-toggle="collapse" data-target="#downloads">' . tep_glyphicon('download-alt glyphicon-lg') . TABLE_HEADING_DOWNLOAD . '</button>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	<div id="downloads" class="collapse">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	  <br>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	  <div class="col-md-4">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            		  <label class="sr-only" for="products_attributes_filename">' . TABLE_TEXT_FILENAME . '</label>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            		  <div class="form-group">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			 <div class="input-group">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			    <div class="input-group-addon">' . tep_glyphicon('file') . '</div>' . PHP_EOL;
								       $contents_new_option_attrib .=                             				   tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'placeholder="' . TABLE_TEXT_FILENAME . '"') . PHP_EOL;
								       $contents_new_option_attrib .= '                            			 </div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			</div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	  </div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	  <div class="col-md-4">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            		   <label class="sr-only" for="products_attributes_maxdays">'. TABLE_TEXT_MAX_DAYS . '</label>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            		   <div class="form-group">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			  <div class="input-group">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			      <div class="input-group-addon">' . tep_glyphicon('calendar') . '</div>' . PHP_EOL;
								       $contents_new_option_attrib .=                             					     tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'placeholder="' . TABLE_TEXT_MAX_DAYS . '"') . PHP_EOL;
								       $contents_new_option_attrib .= '                            			   </div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            		   </div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	  </div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	  <div class="col-md-4">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			 <label class="sr-only" for="products_attributes_maxcount">' .  TABLE_TEXT_MAX_COUNT . '</label>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			 <div class="form-group">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            			    <div class="input-group">' . PHP_EOL;
								       $contents_new_option_attrib .= '                            				    <div class="input-group-addon">' . tep_glyphicon('download') . '</div>' . PHP_EOL;
								       $contents_new_option_attrib .=                             					     tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'placeholder="' . TABLE_TEXT_MAX_COUNT . '"') . PHP_EOL;
								       $contents_new_option_attrib .= '                            					</div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            				</div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            		</div>' . PHP_EOL;
								       $contents_new_option_attrib .= '                            	</div>' . PHP_EOL;
									   $contents_new_option_attrib .= '                          <div class="clearfix"></div><hr><br />' . PHP_EOL;
									}	

									$_has_more_than_one_customer_group = false ;
	                                for ($x = 0; $x < $no_of_customer_groups; $x++) {
                                             $_has_more_than_one_customer_group = true ;	
									         $contents_cus_group_new_input .= '                          <tr>' . PHP_EOL;											 
									         $contents_cus_group_new_input .= '                             <td>' . $new_attributes_cg[$x]['customers_group_name'] . '</td>'. PHP_EOL;											 
											 
										     if ( $new_attributes_cg[$x]['customers_group_id'] != 0 ) {  // price of retail has input above
									
								                $contents_cus_group_new_input .= '                            <div class="form-group">' . PHP_EOL;			  
								                $contents_cus_group_new_input .=                                 tep_draw_hidden_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[cust_group_id]', $new_attributes_cg[$x]['customers_group_id'] )	 . PHP_EOL; 
								                $contents_cus_group_new_input .=                                 tep_draw_hidden_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[in_db]', '0' )	 . PHP_EOL; 
								                $contents_cus_group_new_input .= '                            </div>' . PHP_EOL;														 
												
									            $contents_cus_group_new_input .= '                             <td>' . PHP_EOL ;													 
								                $contents_cus_group_new_input .= '                               <div class="form-group">' . PHP_EOL;														 
											    $contents_cus_group_new_input .=                                      tep_draw_bs_pull_down_menu( $new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[price_prefix]', $price_prefix_array,  '+' ). PHP_EOL ;
								                $contents_cus_group_new_input .= '                               </div>' . PHP_EOL;												 
									            $contents_cus_group_new_input .= '                             </td>' . PHP_EOL ;											 
											 
									            $contents_cus_group_new_input .= '                             <td>' . PHP_EOL ;												
								                $contents_cus_group_new_input .= '                                <div class="form-group">' . PHP_EOL;			  
								                $contents_cus_group_new_input .=                                      tep_draw_bs_input_field( $new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[price]', 
												                                                                                           null, null, 'id_input_values_price' . $new_attributes_cg[$x]['customers_group_name'] ) . PHP_EOL; 
								                $contents_cus_group_new_input .= '                                </div>' . PHP_EOL;							
									            $contents_cus_group_new_input .= '                             </td>' . PHP_EOL ;					 
												
//								                $contents_cus_group_new_input .= '                             <td>' . PHP_EOL ;	
//                                              if ( isset($new_attributes_cg[$x]['price_prefix'] ) ){		
//								                   $contents_cus_group_new_input .= '                               <div class="form-group">' . PHP_EOL;														 
//											       $contents_cus_group_new_input .=                                      tep_bs_checkbox_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[del]', null, null, 'id_check_delete_cust_group_price' . $new_attributes_cg[$x]['customers_group_name'],
//                                                                                     	                                                   null, 'checkbox checkbox-danger') . PHP_EOL ;
//								                   $contents_cus_group_new_input .= '                               </div>' . PHP_EOL;												 
//												
//												}
//									            $contents_cus_group_new_input .= '                             </td>' . PHP_EOL ;
												
								                $contents_cus_group_new_input .= '                             <td>' . PHP_EOL ;	
                                                if ( !isset($new_attributes_cg[$x]['price_prefix'] ) ) {										
								                   $contents_cus_group_new_input .= '                               <div class="form-group">' . PHP_EOL;														 
											       $contents_cus_group_new_input .=                                      tep_bs_checkbox_field($new_attributes_cg[$x]['customers_group_id'] . '_' . $new_attributes_cg[$x]['customers_group_name'] . '[insert]', null, null, 'id_check_insert_cust_group_price' . $new_attributes_cg[$x]['customers_group_name'], 
												                                                                                           null, 'checkbox checkbox-success') . PHP_EOL ;
								                   $contents_cus_group_new_input .= '                               </div>' . PHP_EOL;												 
												}
									            $contents_cus_group_new_input .= '                             </td>' . PHP_EOL ;		
										
                                             } else { // align hide from groups to the end
             							         $contents_cus_group_new_input .= '<td></td><td></td><td></td>' . PHP_EOL ;
										     } // if ( $new_attributes_cg[$x]['customers
									         $contents_cus_group_new_input .= '                                <td>' . PHP_EOL ;													 
								             $contents_cus_group_new_input .= '                                   <div class="form-group">' . PHP_EOL;														 
											 $contents_cus_group_new_input .=                                         tep_bs_checkbox_field('hide[' . $new_attributes_cg[$x]['customers_group_id'] . ']', $new_attributes_cg[$x]['customers_group_id'], null, 'id_check_hidden_cust_group', (in_array($new_attributes_cg[$x]['customers_group_id'], $hide_from_groups_array )) ? 1 : 0, 'checkbox checkbox-danger') . PHP_EOL ;
								             $contents_cus_group_new_input .= '                                   </div>' . PHP_EOL;												 
									         $contents_cus_group_new_input .= '                                </td>' . PHP_EOL ;											 
									         $contents_cus_group_new_input .= '                          <tr>' . PHP_EOL; 								    

	                                } // end for ($x = 0; $x < $no_of_customer_groups; $x++)
                                    if ( $_has_more_than_one_customer_group == true ) {
									   $contents_cus_group_new .= '                    <table class="table table-striped">' . PHP_EOL;
									   $contents_cus_group_new .= '                        <thead>' . PHP_EOL;
									   $contents_cus_group_new .= '                            <tr>' . PHP_EOL;

									   $contents_cus_group_new .= '                               <th>' .  TABLE_HEADING_GROUP_NAME           . '</th>' . PHP_EOL;
									   $contents_cus_group_new .= '                               <th>' .  TABLE_HEADING_OPT_PRICE_PREFIX     . '</th>' . PHP_EOL;
									   $contents_cus_group_new .= '                               <th>' .  TABLE_HEADING_OPT_PRICE            . '</th>' . PHP_EOL;
//									   $contents_cus_group_new .= '                               <th>' .  TABLE_HEADING_DELETE               . '</th>' . PHP_EOL;
									   $contents_cus_group_new .= '                            	  <th>' .  TABLE_HEADING_INSERT               . '</th>' . PHP_EOL;									   								   
									   $contents_cus_group_new .= '                            	  <th>' .  TABLE_HEADING_HIDDEN               . '</th>' . PHP_EOL;					  
 
									   $contents_cus_group_new .= '                            </tr>' . PHP_EOL;
									   $contents_cus_group_new .= '                        </thead>' . PHP_EOL;
									   $contents_cus_group_new .= '                        <tbody>' . PHP_EOL;							   
		 
									   $contents_cus_group_new .=                                $contents_cus_group_new_input . PHP_EOL;
								   
									   $contents_cus_group_new .= '                        </tbody>' . PHP_EOL;
									   $contents_cus_group_new .= '                    </table>' . PHP_EOL;
									   $contents_new_option_attrib           .= $contents_cus_group_new ;
								    }  // if ( $_has_more than_one_custom

//									$contents_new_option_attrib .= '                            ' . tep_draw_bs_button(IMAGE_SAVE, 'ok'). tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info)) . PHP_EOL;

//		                            $contents_new_option_attrib .= '                 </div>' . PHP_EOL; // end div 	panel body
//		                            $contents_new_option_attrib .= '           </div>' . PHP_EOL; // end div 	panel	
									  
					  
		              $contents_new_option_attrib_footer .= '</div>' . PHP_EOL; // end div 	panel body
		              $contents_new_option_attrib_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents_new_option_attrib . $contents_new_option_attrib_footer ; ?>
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