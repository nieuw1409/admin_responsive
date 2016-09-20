<?php

define( 'MAX_ROW_LISTS_OPTIONS', 10) ;

		$q=tep_db_query("select products_name,products_options_name as _option,products_attributes.options_id as _option_id,products_options_values_name as _value,products_attributes.options_values_id as _value_id from ".
						  "products_description, products_attributes,products_options,products_options_values where ".
						  "products_attributes.products_id=products_description.products_id and ".
						  "products_attributes.products_id=" . (int)$pInfo->products_id . " and ".
						  "products_attributes.options_id=products_options.products_options_id and ".
						  "products_attributes.options_values_id=products_options_values.products_options_values_id and ".
						  "products_description.language_id=" . (int)$languages_id . " and ".
						  "products_options_values.language_id=" . (int)$languages_id . " and products_options.products_options_track_stock=1 and ".
						  "products_options.language_id=" . (int)$languages_id . " order by products_attributes.options_id, products_attributes.options_values_id");
		 //list($product_name,$option_name,$option_id,$value,$value_id)
		if (tep_db_num_rows($q)>0) {
			$flag=1;
			while($list=tep_db_fetch_array($q)) {
			  $options[$list[_option_id]][]=array($list[_value],$list[_value_id]);
			  $option_names[$list[_option_id]]=$list[_option];
			  $product_name=$list[products_name];
			}
		} else {
           //Commented out so items with 0 stock will show up in the stock report.			
		   $flag=0;
		   $q=tep_db_query("select products_quantity,products_name from " . TABLE_PRODUCTS . " p,products_description pd where pd.products_id=" . (int)$pInfo->products_id . " and p.products_id=" . (int)$pInfo->products_id);
			$list=tep_db_fetch_array($q);
			$db_quantity=$list[products_quantity];
			$product_name=stripslashes($list[products_name]);
		}
		
  
        $contents_edit_prod_options_stock .= '                                  <div class="panel panel-primary">' . PHP_EOL;	 
	 
		 
        $contents_edit_prod_options_stock .= '                                      <div class="panel-heading">' . HEADING_TITLE_STOCK . PHP_EOL ; 
				  
        $contents_edit_prod_options_stock .= '                                            <div class="pull-right">'  . PHP_EOL ; 

		$attributes = "select * from " . TABLE_PRODUCTS_STOCK . " where products_id=" . $pInfo->products_id . " order by products_stock_attributes" ;
		$attributes_split = new splitPageResults($attribute_stock_page, MAX_ROW_LISTS_OPTIONS, $attributes, $attributes_query_numrows);
					
        $contents_edit_prod_options_stock .= $attributes_split->display_links($attributes_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $attribute_stock_page, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $pInfo->products_id . '&action=options_stock&option_page=' . $option_page . '&value_page=' . $value_page . '&option_page=' . $option_page . '&attribute_stock_page=' . $attribute_stock_page, 'attribute_stock_page');
				//	$products_id

        $contents_edit_prod_options_stock .= '                                            </div>' . PHP_EOL;		
        $contents_edit_prod_options_stock .= '                                          </div>' . PHP_EOL;		// end panel-heading 
        $contents_edit_prod_options_stock .= '                                          <div class="panel-body">' . PHP_EOL;	
		
        $contents_edit_prod_options_stock .= '                                                          <table class="table table-striped">' . PHP_EOL;	
        $contents_edit_prod_options_stock .= '                                                            <thead>			  ' . PHP_EOL;	
        $contents_edit_prod_options_stock .= '                                                              <tr>' . PHP_EOL;	

        if ($flag) {
            while(list($k,$v)=each($options)) {
                $contents_edit_prod_options_stock .= '                                                        <th class="text-left">'. $option_names[$k] . '</th>' . PHP_EOL ;
                $title[$title_num]=$k;
            }
        }
        $contents_edit_prod_options_stock .= '                                                                <th class="text-center" >' . TEXT_HEADING_STOCK . '</th>' . PHP_EOL ;		
        $contents_edit_prod_options_stock .= '                                                                <th>' . TABLE_HEADING_ACTION                  . '</th>' . PHP_EOL;	
        $contents_edit_prod_options_stock .= '                                                              </tr>' . PHP_EOL;	
        $contents_edit_prod_options_stock .= '                                                            </thead>' . PHP_EOL;	
        $contents_edit_prod_options_stock .= '                                                            <tbody>' . PHP_EOL;	
 
        $next_id = 1; 
        $attributes = tep_db_query($attributes);
        while ($attributes_values = tep_db_fetch_array($attributes)) {
            $rows++;
 
            if ($flag) {	
               $temp_stock = '' ; 
			   // get array with existing attributes with stock
			   $stock_array[] = $attributes_values[products_stock_attributes] ;
               $val_array=explode(",",$attributes_values[products_stock_attributes]);
               $contents_edit_prod_options_stock .= '          											     <tr>' . PHP_EOL ; 
               foreach($val_array as $val) {
                  if (preg_match("/^(\d+)-(\d+)$/",$val,$m1)) {
                     $contents_edit_prod_options_stock .= '          											  <td class="text-left">' . tep_values_name($m1[2]). '</td>' . PHP_EOL ;
					 $temp_stock .= tep_values_name($m1[2]) . ' ' ;
                  } else {
                     $contents_edit_prod_options_stock .= '          											  <td>' . '' . '</td>' . PHP_EOL ;
                  }
               }
			   
               for($i=0;$i<sizeof($options)-sizeof($val_array);$i++) {
                   $contents_edit_prod_options_stock .= '          											      <td>' . '' . '</td>' . PHP_EOL ;
               }
               $contents_edit_prod_options_stock .= '          											          <td class="text-center">' . $attributes_values[products_stock_quantity] . '</td>' . PHP_EOL ;
  
               $contents_edit_prod_options_stock .= '          													  <td class="text-right">' . PHP_EOL;	
               $contents_edit_prod_options_stock .= '          															  <div class="btn-toolbar" role="toolbar">' . PHP_EOL;	

               $contents_edit_prod_options_stock .= '          									                            <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,   'pencil', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_stock&action_attribute_stock=update_option_stock&products_stock_id=' . $attributes_values["products_stock_id"] . '&' . $page_info), null, 'warning') . '</div>' . PHP_EOL;
               $contents_edit_prod_options_stock .= '          										                        <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_stock&action_attribute_stock=delete_option_stock&products_stock_id=' . $attributes_values["products_stock_id"] . '&' . $page_info), null, 'danger' ) . '</div>' . PHP_EOL;	

               $contents_edit_prod_options_stock .= '          															  </div>' . PHP_EOL;	
               $contents_edit_prod_options_stock .= '          														</td>' . PHP_EOL;	
               $contents_edit_prod_options_stock .= '          							                     </tr>' . PHP_EOL;	
			
 
		       if (($HTTP_GET_VARS['products_stock_id'] == $attributes_values["products_stock_id"]) && ($HTTP_GET_VARS['action_attribute_stock'])) { 
                    $action_attribute_stock = (isset($HTTP_GET_VARS['action_attribute_stock']) ? $HTTP_GET_VARS['action_attribute_stock'] : '');							   
					switch ($action_attribute_stock) {	 
 							  case 'delete_option_stock':
							  
			                           $contents_attribute_stock .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_attribute_stock .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_OPTION_STOCK . '</div>' . PHP_EOL;
			                           $contents_attribute_stock .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass_stock = ' alert alert-danger';
		                               $contents_attribute_stock .= '                      ' . tep_draw_bs_form('del_attribute_stock', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_stock&action_attribute_stock=delete_attribute_stock&products_stock_id=' . $attributes_values['products_stock_id'] . '&' . $page_info, 'post', 'role="form"', 'id_del_option_attrubute_stock') . PHP_EOL;
                                       $contents_attribute_stock .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents_attribute_stock .= '                          <p>' . TEXT_INFO_DELETE_STOCK_INTRO . '<br />' . $temp_stock . '</p>' . PHP_EOL;
										
                                       $contents_attribute_stock .= '                        </div>' . PHP_EOL;
                                       $contents_attribute_stock .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents_attribute_stock .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_stock' . '&' . $page_info), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents_attribute_stock .= '                        </div>' . PHP_EOL;
		                               $contents_attribute_stock .= '                      </form>' . PHP_EOL;
		                               $contents_attribute_stock .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_attribute_stock .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;		
 								  
								  case 'update_option_stock': 	

			                           $contents_attribute_stock  = '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_attribute_stock .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_STOCK . '</div>' . PHP_EOL;
			                           $contents_attribute_stock .= '          <div class="panel-body">' . PHP_EOL;	
									   $contents_attribute_stock .= '            ' . tep_draw_bs_form('update_attribute_stock', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_stock&action_attribute_stock=update_attribute_stock&products_stock_id=' . $attributes_values['products_stock_id'] . '&' . $page_info, 'post', 'role="form"', 'id_update_attribute_stock') . PHP_EOL;

								       $contents_attribute_stock .= '                       <div class="form-group">' . PHP_EOL;			  
								       $contents_attribute_stock .=                             tep_draw_bs_input_field('products_stock_quantity', $attributes_values['products_stock_quantity'], $temp_stock, 'id_input_attributes_stock' , 'col-xs-3', 'col-xs-9',  'left', $temp_stock ) . PHP_EOL; 
								       $contents_attribute_stock .= '                       </div>' . PHP_EOL;


									   $contents_attribute_stock .= '                  ' . tep_draw_bs_button(IMAGE_SAVE, 'ok'). 
									                                                       tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=options_stock' . '&' . $page_info)) . PHP_EOL;
									   
									   
		                               $contents_attribute_stock .= '                </form>' . PHP_EOL;									   
			                           $contents_attribute_stock .= '          </div>' . PHP_EOL;
			                           $contents_attribute_stock .= '       </div' . PHP_EOL;
								
								  break;
					}
								
                    $contents_edit_prod_options_stock .= '                     <tr class="content-row">' . PHP_EOL .
									 '                      <td colspan="7">' . PHP_EOL .
									 '                        <div class="row ' . $alertClass_stock . '">' . PHP_EOL .
																 $contents_attribute_stock .  
									 '                        </div>' . PHP_EOL .
									 '                      </td>' . PHP_EOL .
									 '                    </tr>' . PHP_EOL;		  
				} // eof if
			} // end if flag
	    } // end while 
 
        $contents_edit_prod_options_stock .= '          							                      </tbody>' . PHP_EOL ;
        $contents_edit_prod_options_stock .= '          							                   </table> <!-- end table options stock --> ' . PHP_EOL ;
 
		if (!($HTTP_GET_VARS['action_attribute_stock'])) {
            $contents_edit_prod_options_stock .=     tep_draw_bs_button(IMAGE_NEW_ATTRIBUTE_STOCK, 'plus', null,'data-toggle="modal" data-target="#products_new_option_stock"') . PHP_EOL ; 
			$contents_edit_prod_options_stock .=     tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'action_attribute_stock=options_attribute&cPath=' . $cPath . '&' . $page_info)) . PHP_EOL;
			
        }
		  

		 
	     $contents_edit_prod_options_stock .= '                                     </div> <!-- end div panel body edit option -->' . PHP_EOL;   // end div tab panel body
		 
	     $contents_edit_prod_options_stock .= '                                  </div> <!-- end div panel paneledit option -->' . PHP_EOL;   // end div tab panel  
 
?>
