<?php

//	  $contents_edit_prod_tab2 .= '         <div role="tabpanel" id="tab_edit_products_price">'  . PHP_EOL;  				
      $contents_edit_prod_tab2 .= '                <div class="col-xs-12 col-md-7">' . PHP_EOL;	
      $contents_edit_prod_tab2 .= '                   <br />' . PHP_EOL;
      $contents_edit_prod_tab2 .= '                   <div class="form-group">' . PHP_EOL;	  	  
	  $contents_edit_prod_tab2 .= '                     <label class="col-xs-5  col-xs-5 control-label">' . TEXT_PRODUCTS_TAX_CLASS . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab2 .= '                            <div class="col-xs-7  col-xs-7">' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id) . '</div>' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                          ' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                   </div>' . PHP_EOL;	    
	  $contents_edit_prod_tab2 .= '                   <br />' . PHP_EOL;		  

      $contents_edit_prod_tab2 .= '                   <div class="form-group">' . PHP_EOL;	  	 	  
	  $contents_edit_prod_tab2 .= '                     <label class="col-xs-5  col-xs-5 control-label">' . TEXT_PRODUCTS_PRICE_NET . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab2 .= '                           <div class="col-xs-7  col-xs-7">'. tep_draw_input_field('products_price', $pInfo->products_price, 'onkeyup="updateGross()"') . '</div>' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                          ' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                   </div>' . PHP_EOL;	  
	  $contents_edit_prod_tab2 .= '                   <br />' . PHP_EOL;
	  
      $contents_edit_prod_tab2 .= '                   <div class="form-group">' . PHP_EOL;	  	 	  
	  $contents_edit_prod_tab2 .= '                     <label class="col-xs-5  col-xs-5 control-label">' . TEXT_PRODUCTS_PRICE_GROSS . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab2 .= '                           <div class="col-xs-7  col-xs-7">' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'onkeyup="updateNet()"') . '</div>' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                          ' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                   </div>' . PHP_EOL;	 
	  $contents_edit_prod_tab2 .= '                   <br />' . PHP_EOL;
	  
      $contents_edit_prod_tab2 .= '                   <div class="form-group">' . PHP_EOL;	  	 	  
	  $contents_edit_prod_tab2 .= '                     <label class="col-xs-5  col-xs-5 control-label">' . TEXT_PRODUCTS_PRICE_COST . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab2 .= '                         <div class="col-xs-7  col-xs-7">' . tep_draw_input_field('products_cost', $pInfo->products_cost, '')  . '</div>' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                          ' . PHP_EOL;
	  $contents_edit_prod_tab2 .= '                   </div>' . PHP_EOL;		  
	  $contents_edit_prod_tab2 .= '                   <br />' . PHP_EOL;
	  
	  $contents_edit_prod_tab2 .= '                </div>' . PHP_EOL;  // end div prices
	  
	  $contents_edit_prod_tab2 .= '                <br />' . PHP_EOL;	
 
      $contents_edit_prod_tab2 .= '                <div class="col-xs-12 col-md-5">' . PHP_EOL;	   	 	  

      $contents_edit_prod_tab2 .= '                  <div class="panel panel-primary">' . PHP_EOL ;	  
	  $contents_edit_prod_tab2 .= '                     <div class="panel-heading">' . TEXT_PAYMENT_METHODS . '</div>' . PHP_EOL;	   
      $contents_edit_prod_tab2 .= '                        <div class="panel-body">' . PHP_EOL ;
	  
   	  $module_type = 'payment';
	  $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
	  $module_key = 'MODULE_PAYMENT_INSTALLED';
	  $current_methods = array();
	  $current_methods = explode(';',$product['payment_methods']);

	  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
	  $directory_array = array();
	  if ($dir = @dir($module_directory)) {
			while ($file = $dir->read()) {
				  if (!is_dir($module_directory . $file)) {
					if (substr($file, strrpos($file, '.')) == $file_extension) {
					  $directory_array[] = $file;
					}
				  }
			}
			sort($directory_array);
			$dir->close();
	  }
			
	  for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
				$file = $directory_array[$i];
				
				include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/' . $module_type . '/' . $file);
				include($module_directory . $file);
				
				$class = substr($file, 0, strrpos($file, '.'));
				if (tep_class_exists($class)) {
					$module = new $class;
					if($module->check() > 0){
                       $contents_edit_prod_tab2 .= '<div class="form-group">' . PHP_EOL;							
                       $contents_edit_prod_tab2 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;	
                       $contents_edit_prod_tab2 .=			  '<input type="checkbox" name="payment_methods[]" value="'.$class.'"'.(in_array($class,$current_methods) ? ' CHECKED' : '').'> ' ;
                       $contents_edit_prod_tab2 .= '          <label>'. PHP_EOL  ;						   
                       $contents_edit_prod_tab2 .= '          ' .$module->title.'<br />' . PHP_EOL;					   
                       $contents_edit_prod_tab2 .= '          </label>'. PHP_EOL  ;						   
                       $contents_edit_prod_tab2 .= '       </div>'. PHP_EOL  ;	
                       $contents_edit_prod_tab2 .= '</div>'. PHP_EOL  ;						   
					}
				}
	  }
      
	  $contents_edit_prod_tab2 .= '                     </div>' . PHP_EOL;  // end div <div class="panel-body
      $contents_edit_prod_tab2 .= '                  </div>' . PHP_EOL;  // end div <div class="panel panel-pr	  
      $contents_edit_prod_tab2 .= '                </div>' . PHP_EOL;  // end div <div class="col-xs-5
	  
	  
// price breaks	  
	  
      $contents_edit_prod_tab2 .= '                <div class="col-xs-12">' . PHP_EOL;  	  
      $contents_edit_prod_tab2 .=  '                  <div role="tabpanel" id="tab_edit_price_breaks_prod">' . PHP_EOL;
      $contents_edit_prod_tab2 .=  '                     <!-- Nav tabs edit price breaksproducts -->' . PHP_EOL ;
      $contents_edit_prod_tab2 .=  '                     <ul class="nav nav-tabs" role="tablist" id="tab_edit_price_breaks_prod">' . PHP_EOL ;
	  
	  $active = 'active ' ;
      foreach ($_hide_customers_group as $key => $cust_groups) {
           $contents_edit_prod_tab2 .=  '                    <li id="tab_edit_price_breaks_prod" class="' . $active  . '">
		                                                         <a href="#pricebreak-' . $cust_groups['customers_group_id'] . '" aria-controls="tab_pricebreak-' . $cust_groups['customers_group_id'] . '" role="tab" data-toggle="tab">' . $cust_groups['customers_group_name'] . '</a>' . PHP_EOL ;
           $contents_edit_prod_tab2 .=  '                    </li>' . PHP_EOL ;																 
           $active = '' ;				
      }	  
      $contents_edit_prod_tab2 .=  '                      </ul>'  . PHP_EOL;

     $contents_edit_prod_tab2 .=  '                      <!-- Tab panes price breaks products -->' . PHP_EOL ;
     $contents_edit_prod_tab2 .=  '                      <div   class="tab-content" id="tab_edit_price_breaks_prod">'  . PHP_EOL;
	 
	 
	  $active = 'active ' ;	  
      foreach ($_hide_customers_group as $key => $cust_groups) {
         $CustGroupID = $cust_groups['customers_group_id'];	  
         $contents_edit_prod_tab2 .=  '                     <div role="tabpanel" class="tab-pane ' . $active  . '" id="pricebreak-' . $cust_groups['customers_group_id'] . '">'      . PHP_EOL ;
         $active = '' ;		 
         $contents_edit_prod_tab2 .= '                         <div class="panel panel-primary">' . PHP_EOL;	 
		 
         $contents_edit_prod_tab2 .= '                            <br />	' . PHP_EOL;	
         $contents_edit_prod_tab2 .= '                            <div class="panel-heading panel-info">' . ENTRY_CUSTOMERS_GROUP_NAME . '&nbsp;' . $cust_groups['customers_group_name'] . '</div>' . PHP_EOL;	

         $contents_edit_prod_tab2 .= '                            <div class="panel-body">' . PHP_EOL;	

// bof price breaks content	 	 

		 if ($CustGroupID != 0) {
	        $contents_edit_prod_tab2 .= '                             <div class="well mark"> '.  TEXT_CUSTOMERS_GROUPS_NOTE . '</div>'.  PHP_EOL;
         }
		 
         if ($CustGroupID != 0) {
			 
	         $contents_edit_prod_tab2 .= '                             <div class="form-group form-inline">' . PHP_EOL ;
             $contents_edit_prod_tab2 .= '                                 <div class="checkbox checkbox-success">'. PHP_EOL  ;	
			 
            if (isset($pInfo->sppcprice[$CustGroupID])) { 
                $sppc_cg_price = $pInfo->sppcprice[$CustGroupID];
             } else { // nothing in the db, nothing in the post variables
                $sppc_cg_price = '';
             }			 
 					 
             if (isset($pInfo->sppcoption)) {
                $contents_edit_prod_tab2 .=                                    tep_draw_checkbox_field('sppcoption[' . $CustGroupID . ']', 'sppcoption[' . $CustGroupID . ']', (isset($pInfo->sppcoption[$CustGroupID])) ? true : false ) . PHP_EOL ;
             } else {
                $contents_edit_prod_tab2 .=                                    tep_draw_checkbox_field('sppcoption[' . $CustGroupID . ']', 'sppcoption[' . $CustGroupID . ']', true) . PHP_EOL ;
             }
             $contents_edit_prod_tab2 .= '                                     <label for="sppcoption[' . $CustGroupID . ']">'. PHP_EOL  ;	
             $contents_edit_prod_tab2 .= '                                       ' . TEXT_PRODUCTS_PRICE_NET  .  PHP_EOL  ;	
             $contents_edit_prod_tab2 .= '                                     </label>'. PHP_EOL  ;			   
             $contents_edit_prod_tab2 .= '                                 </div>'. PHP_EOL  ;
             $contents_edit_prod_tab2 .= '                                 <div class="form-group">'. PHP_EOL  ;			 
	         $contents_edit_prod_tab2 .= '                                 ' . tep_draw_input_field('sppcprice[' . $CustGroupID . ']', $sppc_cg_price ) .  PHP_EOL ;			 
             $contents_edit_prod_tab2 .= '                                 </div>'. PHP_EOL  ;
             $contents_edit_prod_tab2 .= '                             </div>'. PHP_EOL  ;				 
//             if (isset($pInfo->sppcprice[$CustGroupID])) { 
//                $sppc_cg_price = $pInfo->sppcprice[$CustGroupID];
//             } else { // nothing in the db, nothing in the post variables
//                $sppc_cg_price = '';
//             }
			 
//	          $contents_edit_prod_tab2 .= '                            <div class="form-group">' . PHP_EOL ;
//	          $contents_edit_prod_tab2 .= '                                     ' . tep_draw_input_field('sppcprice[' . $CustGroupID . ']', $sppc_cg_price ) .  PHP_EOL ;
//	          $contents_edit_prod_tab2 .= '                            </div>' . PHP_EOL ;		 
        } else {
			 
	          $contents_edit_prod_tab2 .= '                            <div class="form-group">' . PHP_EOL ;
	          $contents_edit_prod_tab2 .= '                                 ' . tep_draw_input_field('products_price_retail_net', $pInfo->products_price, 'readonly') . PHP_EOL ;
	          $contents_edit_prod_tab2 .= '                            </div>' . PHP_EOL ;
				 
         } // end if/else ($CustGroupID != 0)  	

	     $contents_edit_prod_tab2  .= '                                <div class="form-group">' . PHP_EOL .
									  '                                    <label for="products_edit_disc_cat" class="col-xs-2 control-label">' . TEXT_DISCOUNT_CATEGORY . PHP_EOL . 								 
									  '                                    </label> ' . PHP_EOL .										 
									  '                                    <div class="col-xs-10">'  . tep_draw_pull_down_menu('discount_categories_id[' . $CustGroupID . ']', $discount_categories_array, $pInfo->discount_categories_id[$CustGroupID], 'id="products_edit_disc_cat"') . PHP_EOL .
									  '                                                           '  . tep_draw_hidden_field('current_discount_cat_id[' . $CustGroupID . ']', (isset($pInfo->current_discount_cat_id[$CustGroupID]) ? (int)$pInfo->current_discount_cat_id[$CustGroupID] : $pInfo->discount_categories_id[$CustGroupID])). PHP_EOL .
									  '                                    </div>' .
									  '                                </div><hr><br />' . PHP_EOL;			 
										  
		 
	     $contents_edit_prod_tab2  .= '                                       <div class="row">'. PHP_EOL .
		                              '                                          <div class="col-xs-12">' . PHP_EOL .
		                              '                                             <div class="form-group">' . PHP_EOL .
 									  '                                               <label for="prod_edit_qnt_blocks" class="col-xs-2 control-label">' . TEXT_PRODUCTS_QTY_BLOCKS . PHP_EOL . 								 
									  '                                               </label> ' . PHP_EOL .										 
									  '                                               <div class="col-xs-10">' . tep_draw_input_field('products_qty_blocks[' . $CustGroupID . ']', $pInfo->products_qty_blocks[$CustGroupID], 'id="prod_edit_qnt_blocks"') . PHP_EOL .
									  '                                              '  . "&nbsp;" . TEXT_PRODUCTS_QTY_BLOCKS_HELP . 
									  '                                               </div>' . PHP_EOL .
									  '                                             </div>' . PHP_EOL .
									  '                                          </div>' . PHP_EOL .										 
									  '                                       </div>' . PHP_EOL;	 
									  
									  
	     $contents_edit_prod_tab2  .= '                                       <div class="row">'. PHP_EOL .
		                              '                                          <div class="col-xs-12">' . PHP_EOL .
		                              '                                             <div class="form-group">' . PHP_EOL .
 									  '                                               <label for="prod_edit_min_order_qty" class="col-xs-2 control-label">' . TEXT_PRODUCTS_MIN_ORDER_QTY . PHP_EOL . 								 
									  '                                               </label> ' . PHP_EOL .										 
									  '                                               <div class="col-xs-10">' . tep_draw_input_field('products_min_order_qty[' . $CustGroupID . ']', $pInfo->products_min_order_qty[$CustGroupID], 'id="prod_edit_min_order_qty"') . PHP_EOL .
									  '                                              '  . "&nbsp;" . TEXT_PRODUCTS_MIN_ORDER_QTY_HELP . 
									  '                                               </div>' . PHP_EOL .
									  '                                             </div>' . PHP_EOL .
									  '                                          </div>' . PHP_EOL .										 
									  '                                       </div>' . PHP_EOL;	 		


// price breaks
		 $contents_edit_prod_tab2  .= '                                      <div class="row">'. PHP_EOL .
		                              '                                         <div class="col-xs-12">' . PHP_EOL .
									  '                                           <table class="table">' . PHP_EOL .
									  '                                              <thead>' . PHP_EOL .
                                      '                                                 <tr>' . PHP_EOL .
                                      '                                                    <th></th>' . PHP_EOL .
                                      '                                                    <th>' .  TEXT_PRICE_PER_PIECE . '</th>' . PHP_EOL .
                                      '                                                    <th>' . TEXT_ENTER_QUANTITY   . '</th>' . PHP_EOL .
                                      '                                                    <th class="text-center">'. TEXT_PRODUCTS_DELETE   . '</th>' . PHP_EOL .
                                      '                                                 </tr>' . PHP_EOL .
                                      '                                              </thead>' . PHP_EOL .
                                      '                                              <tbody>' . PHP_EOL .
									  '                                                ' . PHP_EOL ;
									  
										  
         for ($count = 0; $count <= (PRICE_BREAK_NOF_LEVELS - 1); $count++) {
		    $contents_edit_prod_tab2  .= '                                              <tr>' . PHP_EOL ;
		    $contents_edit_prod_tab2  .= '                                                <td>' . TEXT_PRODUCTS_PRICE  . " " . ($count + 1) . '</td>' . PHP_EOL ;	

//	        $contents_edit_prod_tab2 .= '                                                 <td>' . PHP_EOL ;			

	        $contents_edit_prod_tab2 .= '                                                    <div class="form-group form-inline">' . PHP_EOL ;
			if(is_array($pInfo->products_price_break[$CustGroupID]) && array_key_exists($count, $pInfo->products_price_break[$CustGroupID])) {			 
		       $contents_edit_prod_tab2 .= '                                                     <td><div class="form-group form-inline">' . tep_draw_input_field('products_price_break[' . $CustGroupID .'][' . $count . ']', $pInfo->products_price_break[$CustGroupID][$count] ) . '</div></td>' . PHP_EOL ;				 
			 
               $contents_edit_prod_tab2 .= '                                                     <td><div class="form-group form-inline">' . tep_draw_input_field('products_qty['         . $CustGroupID .'][' . $count . ']', $pInfo->products_qty[$CustGroupID][$count])          . PHP_EOL .
			                                                                                             tep_draw_hidden_field('products_price_break_id[' . $CustGroupID .'][' . $count . ']', $pInfo->products_price_break_id[$CustGroupID][$count]) . 
																							    '</div></td>' . PHP_EOL ;
																								
			   if (isset($pInfo->products_price_break_id[$CustGroupID][$count]) && tep_not_null($pInfo->products_price_break_id[$CustGroupID][$count])) {
																							   
                 $contents_edit_prod_tab2 .= '                                                   <td class="text-center"><div class="checkbox checkbox-success">'. PHP_EOL  ;	 					 
                 $contents_edit_prod_tab2 .=                                                            tep_draw_checkbox_field('products_delete[' . $CustGroupID .'][' . $count . ']', 'y', (isset($pInfo->products_delete[$CustGroupID][$count]) ? 1 : 0)) . PHP_EOL ;

                 $contents_edit_prod_tab2 .= '                                                          <label for="sppcoption[' . $CustGroupID . ']">'. PHP_EOL  ;	
                 $contents_edit_prod_tab2 .= '                                                          ' . TEXT_PRODUCTS_DELETE  .  PHP_EOL  ;	
                 $contents_edit_prod_tab2 .= '                                                          </label>'. PHP_EOL  ;			   
                 $contents_edit_prod_tab2 .= '                                                   </div></td>'. PHP_EOL  ;	 
              }				 
            } else {			   
		       $contents_edit_prod_tab2  .= '                                                    <td><div class="form-group form-inline">' . tep_draw_input_field('products_price_break[' . $CustGroupID .'][' . $count . ']', ''). '</div></td>' . PHP_EOL ;				
		       $contents_edit_prod_tab2  .= '                                                    <td><div class="form-group form-inline">' . tep_draw_input_field('products_qty[' .         $CustGroupID .'][' . $count . ']', ''). '</div></td>' . PHP_EOL ;				 
            }			   
	        $contents_edit_prod_tab2 .= '                                                  </div>' . PHP_EOL;						
		    $contents_edit_prod_tab2  .= '                                              </tr>' . PHP_EOL ;				
		}
	    $contents_edit_prod_tab2  .= '                                               </tbody>' . PHP_EOL .
                                     '                               		      </table>' . PHP_EOL  . 
                                     '                                  		</div>'. PHP_EOL .
		                             '                                       </div>' . PHP_EOL ;  //  end div row price breaks		 
		 
		 

// eof price break content
	 
	 
	 
	     $contents_edit_prod_tab2 .= '                            </div>' . PHP_EOL;   // end div tab panel body
         $contents_edit_prod_tab2 .= '                         </div>' . PHP_EOL;	// end div panel price breaks groupr
	     $contents_edit_prod_tab2 .= '                      </div>' . PHP_EOL;   // end div <div role="tabpanel" class="tab-pane 
 
     }
     $contents_edit_prod_tab2 .=  '                      </div>' . PHP_EOL ;	// end div <div   class="tab-content" id="tab_edit_price_break
     $contents_edit_prod_tab2 .=  '                   </div>' . PHP_EOL ;	// end div role="tabpanel" id="tab_edit_price_breaks_p
     $contents_edit_prod_tab2 .= '                 </div>' . PHP_EOL;  // end div col-xs-12
     $contents_edit_prod_tab2 .= '                 <br />' . PHP_EOL; 
?>