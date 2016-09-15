<?php 
/*			  $contents_edit_prod_tab7 .= '                                <div class="form-group">' . PHP_EOL .
										  '                                    <label for="products_date_available" class="col-xs-2 control-label">' . TEXT_PRODUCTS_DATE_AVAILABLE . PHP_EOL . 								 
										  '                                    </label> ' . PHP_EOL .										 
										  '                                    <div class="col-xs-10">'  . tep_draw_input_field('products_date_available', $pInfo->products_date_available, 'id="products_date_available"') . PHP_EOL .
										  '                                    </div>' .
										  '                                </div><hr><br />' . PHP_EOL;		
*/
			  $contents_edit_prod_tab7 .= '                                <div class="form-group">' . PHP_EOL .
										  '                                    <label for="products_date_available" class="col-xs-2 control-label">' . TEXT_PRODUCTS_DATE_AVAILABLE . PHP_EOL . 								 
										  '                                    </label> ' . PHP_EOL .										 
										  '                                    <div class="col-xs-10">'  . tep_draw_bs_input_date('products_date_available',                                               // name
	                                                                                                                tep_date_short($pInfo->products_date_available),           // value
									                                                                                'id="products_date_available"',            // parameters
									                                                                                 null,                                                // type
									                                                                                 true,                                              // reinsert value
									                                                                                 TEXT_PRODUCTS_DATE_AVAILABLE                             // placeholder
									                                                                        ) . PHP_EOL .
										  '                                    </div>' .
										  '                                </div><div class="clearfix"></div><hr><br />' . PHP_EOL;												  

									 
										 
			  if($product_investigation['has_tracked_options'] or $product_investigation['stock_entries_count'] > 0) {
//			    $contents_edit_prod_tab7 .= '                                <div class="form-group">' . PHP_EOL .
//										  '                                    <label for="prod_edit_stock" class="col-xs-2 control-label">' . TEXT_PRODUCTS_QUANTITY . PHP_EOL . 								 
//										  '                                    </label> ' . PHP_EOL ;			 
//			       $contents_edit_prod_tab7 .=  '                                    <div class="col-xs-10">'  .tep_draw_bs_button(IMAGE_QTSTOCK, 'shopping-cart', tep_href_link(FILENAME_STOCK, 'id="prod_edit_stock"&product_id=' . $pInfo->products_id ) ) . PHP_EOL ; 
//			       $contents_edit_prod_tab7 .= '                                     </div>' . PHP_EOL ;
//			  $contents_edit_prod_tab7 .= '                                </div><hr><br />' . PHP_EOL ;
//			  $contents_edit_prod_tab7 .= '                                <div class="well well-info">' . TEXT_PRODUCTS_STOCK . '</div>' . PHP_EOL ;
			  } else {
			        $contents_edit_prod_tab7 .= '                          <div class="form-group">' . PHP_EOL .
										  '                                  <label for="prod_edit_stock" class="col-xs-2 control-label">' . TEXT_PRODUCTS_QUANTITY . PHP_EOL . 								 
										  '                                  </label> ' . PHP_EOL ;					  
			       $contents_edit_prod_tab7 .=  '                              <div class="col-xs-10">'  .tep_draw_input_field('products_quantity', $pInfo->products_quantity, 'id="prod_edit_stock"') . PHP_EOL ; 
			       $contents_edit_prod_tab7 .= '                              </div>' . PHP_EOL ;				   
			       $contents_edit_prod_tab7 .= '                           </div><div class="clearfix"></div><br />' . PHP_EOL ;				   
			       $contents_edit_prod_tab7 .= '                           <div class="well well-info">' . TEXT_PRODUCTS_STOCK . '</div>' . PHP_EOL ;				   
			  }										 
			  
			  $contents_edit_prod_tab7 .= '                                <div class="form-group">' . PHP_EOL .
										  '                                    <label for="products_edit_instock" class="col-xs-2 control-label">' . TEXT_PRODUCTS_INSTOCK . PHP_EOL . 								 
										  '                                    </label> ' . PHP_EOL .										 
										  '                                    <div class="col-xs-10">'  . tep_draw_pull_down_menu('products_instock_id', $products_availability_array, $pInfo->products_instock_id, 'id="products_edit_instock"') . PHP_EOL .
										  '                                    </div>' .
										  '                                </div><div class="clearfix"></div><br />' . PHP_EOL;	
										  
			  $contents_edit_prod_tab7 .= '                                <div class="form-group">' . PHP_EOL .
										  '                                    <label for="products_edit_outstock" class="col-xs-2 control-label">' . TEXT_PRODUCTS_NOSTOCK . PHP_EOL . 								 
										  '                                    </label> ' . PHP_EOL .										 
										  '                                    <div class="col-xs-10">'  . tep_draw_pull_down_menu('products_nostock_id', $products_availability_array, $pInfo->products_nostock_id, 'id="products_edit_outstock"') . PHP_EOL .
										  '                                    </div>' .
										  '                                </div><div class="clearfix"></div><br />' . PHP_EOL;											  

?>