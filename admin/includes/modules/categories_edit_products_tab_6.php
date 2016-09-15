<?php

define( 'MAX_ROW_LISTS_OPTIONS', 2) ;

        $contents_edit_prod_tab6 .= '                                  <div class="panel panel-primary">' . PHP_EOL;	 
	 
		 
        $contents_edit_prod_tab6 .= '                                      <div class="panel-heading">' . HEADING_TITLE_VAL . PHP_EOL ; 
				  
        $contents_edit_prod_tab6 .= '                                            <div class="pull-right">'  . PHP_EOL ; 

					$attributes = "select pa.*, pd.products_name from " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pa.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' where pa.products_id = '" . $pInfo->products_id  . "' order by pd.products_name,pa.products_options_sort_order";
//					$attribute_page = (int)preg_replace( '/=/', '', strstr( $page_info, '=') );	
				    $attributes_split = new splitPageResults($attribute_page, MAX_ROW_LISTS_OPTIONS, $attributes, $attributes_query_numrows);
					
        $contents_edit_prod_tab6 .=                                                  $attributes_split->display_links($attributes_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $attribute_page, 'option_page=' . $option_page . '&value_page=' . $value_page, 'attribute_page');
					

        $contents_edit_prod_tab6 .= '                                            </div>' . PHP_EOL;		
        $contents_edit_prod_tab6 .= '                                          </div>' . PHP_EOL;		// end panel-heading 
        $contents_edit_prod_tab6 .= '                                          <div class="panel-body">' . PHP_EOL;	
		
        $contents_edit_prod_tab6 .= '                                                          <table class="table table-striped">' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                            <thead>			  ' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                              <tr>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_ID                      . '</th>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_OPT_ORDER               . '</th>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_PRODUCT                 . '</th>' . PHP_EOL;			  
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_OPT_NAME                . '</th>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_OPT_VALUE               . '</th>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_OPT_PRICE               . '</th>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_OPT_PRICE_PREFIX        . '</th>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                          			             <th>' . TABLE_HEADING_HIDDEN                  . '</th>' . PHP_EOL;		  
        $contents_edit_prod_tab6 .= '                                                                <th>' . TABLE_HEADING_ACTION                  . '</th>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                              </tr>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                            </thead>' . PHP_EOL;	
        $contents_edit_prod_tab6 .= '                                                            <tbody>' . PHP_EOL;	
 
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
 
            $contents_edit_prod_tab6 .= '          							                          <tr>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          														  <td class="text-center">' . $attributes_values["products_attributes_id"]                                                                   . '</td>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          														  <td class="text-center">' . $attributes_values["products_options_sort_order"]                                                              . '</td>' . PHP_EOL;									  
            $contents_edit_prod_tab6 .= '          														  <td>'                     . $products_name_only                                                                                            . '</td>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          														  <td>'                     . $options_name                                                                                                  . '</td>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          														  <td>'                     . $values_name                                                                                                   . '</td>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          														  <td>'                     . $attributes_values["options_values_price"]                                                                     . '</td>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          														  <td>'                     . $attributes_values["price_prefix"]                                                                             . '</td>' . PHP_EOL;						  
            $contents_edit_prod_tab6 .= '          														  <td>'                     . $hide_info = tep_get_hide_info($customers_groups, $attributes_values['attributes_hide_from_groups'])           . '</td>' . PHP_EOL;	
								  
            $contents_edit_prod_tab6 .= '          														  <td class="text-right">' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          															  <div class="btn-toolbar" role="toolbar">' . PHP_EOL;	

            $contents_edit_prod_tab6 .= '          									                            <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_CATEGORIES, 'action_attribute=update_option_attribute&attribute_id=' . $attributes_values["products_attributes_id"]), null, 'warning') . '</div>' . PHP_EOL;
//			tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_attribute&attribute_id=' . $attributes_values["products_attributes_id"] . '&' . $page_info), null, 'warning') . '</div>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          										                        <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_attribute&attribute_id=' . $attributes_values["products_attributes_id"] . '&' . $page_info), null, 'danger') . '</div>' . PHP_EOL;	

            $contents_edit_prod_tab6 .= '          															  </div>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          														  </td>' . PHP_EOL;	
            $contents_edit_prod_tab6 .= '          							                          </tr>' . PHP_EOL;	
 
							  if (($HTTP_GET_VARS['attribute_id'] == $attributes_values["products_attributes_id"]) && ($HTTP_GET_VARS['action_attribute'])) {       
								switch ($action_attribute) {	 
								  case 'delete_option_attribute':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_OPTION_ATTRIBUTE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert-message alert-message-danger';
		                               $contents .= '                      ' . tep_draw_bs_form('del_attribute', FILENAME_PRODUCTS_ATTRIBUTES, 'action_attribute=delete_attribute&attribute_id=' . $HTTP_GET_VARS['attribute_id'] . '&' . $page_info, 'post', 'role="form"', 'id_del_option_attrubute') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-xs-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $products_name_only .  '  ' .  $options_name  .  ' ' . $values_name . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-xs-4 col-md-4 text-right">' . PHP_EOL;
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
									$contents .= '                        ' . tep_draw_bs_form('update_option_attribute', FILENAME_PRODUCTS_ATTRIBUTES, 'action_attribute=update_product_attribute'  . '&' . $page_info, 'post', 'role="form"', 'id_update_option_attribute') . PHP_EOL;
									
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
											 $contents_cus_group_input .=                                         tep_bs_checkbox_field('hide[' . $new_attributes_cg[$x]['customers_group_id'] . ']', $new_attributes_cg[$x]['customers_group_id'], null, 'id_check_hidden_cust_group', (in_array($new_attributes_cg[$x]['customers_group_id'], $hide_from_groups_array )) ? 1 : 0, 'checkbox checkbox-success') . PHP_EOL ;
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
 
        $contents_edit_prod_tab6 .= '          							                         					  </tbody>' . PHP_EOL ;
        $contents_edit_prod_tab6 .= '          							                         					</table>' . PHP_EOL ;
 
		if (!($HTTP_GET_VARS['action_attribute'])) {
            $contents_edit_prod_tab6 .=     tep_draw_bs_button(IMAGE_NEW_ATTRIBUTE_NEW_VALUE, 'plus', null,'data-toggle="modal" data-target="#attributes_new_option_attribute"') . PHP_EOL ; 
        }
		  

		 
	     $contents_edit_prod_tab6 .= '                                     </div>' . PHP_EOL;   // end div tab panel body
		 
	     $contents_edit_prod_tab6 .= '                                  </div>' . PHP_EOL;   // end div tab panel  
		 
		 
function tep_get_hide_info($customers_groups, $attributes_hide_from_groups) {
  $hide_attr_from_groups_array = explode(',', $attributes_hide_from_groups);
  $hide_attr_from_groups_array = array_slice($hide_attr_from_groups_array, 1); // remove "@" from the array
  $attribute_hidden_from_string = '';
	$hide_info = '';
	if (LAYOUT_HIDE_FROM == '1') {
	      for ($i = 0; $i < count($customers_groups); $i++) {
		      if (in_array($customers_groups[$i]['customers_group_id'], $hide_attr_from_groups_array)) {
	        $attribute_hidden_from_string .= $customers_groups[$i]['customers_group_name'] . ', '; 
		      }
              } // end for ($i = 0; $i < count($customers_groups); $i++)
	      $attribute_hidden_from_string = rtrim($attribute_hidden_from_string); // remove space on the right
	      $attribute_hidden_from_string = substr($attribute_hidden_from_string,0,strlen($attribute_hidden_from_string) -1); // remove last comma
	      if (!tep_not_null($attribute_hidden_from_string)) { 
	      $attribute_hidden_from_string = TEXT_GROUPS_NONE; 
	      }
	      $attribute_hidden_from_string = TEXT_HIDDEN_FROM_GROUPS . $attribute_hidden_from_string;
				// if the string in the database field is @, everything will work fine, however tep_not_null
				// will not discover the associative array is empty therefore the second check on the value
	if (tep_not_null($hide_attr_from_groups_array) && tep_not_null($hide_attr_from_groups_array[0])) {
		  $hide_info = tep_image(DIR_WS_ICONS . 'tick_black.gif', $attribute_hidden_from_string, 20, 16);
		} else {
		  $hide_info = tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $attribute_hidden_from_string, 20, 16);
	  }
	} else {
		// default layout: icons for all groups
      for ($i = 0; $i < count($customers_groups); $i++) {
        if (in_array($customers_groups[$i]['customers_group_id'], $hide_attr_from_groups_array)) {
          $hide_info .= tep_image(DIR_WS_ICONS . 'icon_tick.gif', $customers_groups[$i]['customers_group_name'], 11, 11) . '&nbsp;&nbsp;';
        } else {
          $hide_info .= tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', 11, 11) . '&nbsp;&#160;';
        }
      }
	}
	return $hide_info;
}
 		 
?>