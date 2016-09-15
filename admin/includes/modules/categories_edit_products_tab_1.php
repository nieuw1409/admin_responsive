<?php
 
	  $name_navtabs = '';
	  $name_tabcontent = '';
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
	  
	    $name_navtabs .= '                         <li '. (($i == 0) ? 'class="active"' : '') .'>' .  PHP_EOL ;
  	    $name_navtabs .= '                           <a href="#tab_edit_prod_name' . $i . '" data-toggle="tab">' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], null, null, null, false) . "  " . $languages[$i]['name'] . '</a>' .  PHP_EOL ;
	    $name_navtabs .= '                         </li>' . PHP_EOL;
   
  	    // now the content for each language
		
	    $name_tabcontent .= '                <div class="tab-pane fade'. (($i == 0) ? ' active in' : '') .'" id="tab_edit_prod_name' . $i . '">' . PHP_EOL; 
	    $name_tabcontent .= '                   <div class="panel panel-primary">' . PHP_EOL; 		
	    $name_tabcontent .= '                      <div class="panel-body">' . PHP_EOL;		
        $name_tabcontent .= '                         <div class="form-group">' . PHP_EOL;		
	    $name_tabcontent .= '                             <label class="col-xs-4  col-xs-5  control-label">' . TEXT_PRODUCTS_NAME . '</label>' . PHP_EOL;
	    $name_tabcontent .= '                             <div class="col-xs-8  col-xs-7">' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (empty($pInfo->products_id) ? '' : tep_get_products_name($pInfo->products_id, $languages[$i]['id']))) . '</div>' . PHP_EOL;
	    $name_tabcontent .= '                         </div>' . PHP_EOL;	  
	  
        $name_tabcontent .= '                         <div class="form-group">' . PHP_EOL;			  
	    $name_tabcontent .= '                             <label class="col-xs-4  col-xs-5  control-label">' . TEXT_PRODUCTS_URL . ' <small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small></label>' .  PHP_EOL ;
	    $name_tabcontent .= '                             <div class="col-xs-8  col-xs-7">' . tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : tep_get_products_url($pInfo->products_id, $languages[$i]['id']))) . '</div>' . PHP_EOL;  
	    $name_tabcontent .= '                         </div>' . PHP_EOL;	  
	    $name_tabcontent .= '                      </div>' . PHP_EOL;
	    $name_tabcontent .= '                   </div>' . PHP_EOL ;
	    $name_tabcontent .= '                </div>' . PHP_EOL ;		
      }	
	
	  $contents_edit_prod_tab1 .= '                <br />' . PHP_EOL ;	
	  $contents_edit_prod_tab1 .= '                <div role="tabpanel" id="tab_edit_name_products">'  . PHP_EOL;    
	  $contents_edit_prod_tab1 .= '                    <ul class="nav nav-tabs" role="tablist" id="tab_edit_name_products">' . PHP_EOL; 
	  $contents_edit_prod_tab1 .= 	                        $name_navtabs;
	  $contents_edit_prod_tab1 .= '                    </ul>' . PHP_EOL; 

	  $contents_edit_prod_tab1 .= '                    <div class="tab-content" id="tab_edit_name_products">' . PHP_EOL; 
	  $contents_edit_prod_tab1 .=                           $name_tabcontent;
	  $contents_edit_prod_tab1 .= '                    </div>' . PHP_EOL; 
	  $contents_edit_prod_tab1 .= '                </div>' . PHP_EOL; 	
	  
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;	  	  
  
      $contents_edit_prod_tab1 .= '                <div class="input-group">' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                    <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
	  $contents_edit_prod_tab1 .= '                       <label>' . TEXT_EDIT_STATUS . '</label><br />' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                       <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_PRODUCT_AVAILABLE  . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                           ' . tep_draw_radio_field("products_status", "1", $in_status) . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                       </label>' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                        <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_PRODUCT_NOT_AVAILABLE .  PHP_EOL;
	  $contents_edit_prod_tab1 .= '                               ' . tep_draw_radio_field("products_status", "0", $out_status) . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                        </label>' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                    </div>' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                </div>' . PHP_EOL;	
	  
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;	  	  

      $contents_edit_prod_tab1 .= '                <div class="input-group">' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                    <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
	  $contents_edit_prod_tab1 .= '                          <label>' . TEXT_PRODUCTS_PURCHASE . '</label><br />' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                          <label class="btn btn-default' . ( $in_status1 ==  1 ? " active " : "" ) . '">' . TEXT_PRODUCT_AVAILABLE_PURCHASE  . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                             ' . tep_draw_radio_field("products_purchase", "1", $in_status1) . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                          </label>' . PHP_EOL;		  
	  $contents_edit_prod_tab1 .= '                          <label class="btn btn-default ' . ( $out_status1 == 1 ? " active " : "" ) . '">'. TEXT_PRODUCT_NOT_AVAILABLE_PURCHASE .  PHP_EOL;
	  $contents_edit_prod_tab1 .= '                             ' . tep_draw_radio_field("products_purchase", "0", $out_status1) . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                          </label>' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                    </div>' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                 </div>' . PHP_EOL;	  
	  
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;	  

      $contents_edit_prod_tab1 .= '                <div class="form-group">' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                     <label class="col-xs-4  col-xs-5 control-label">' . TEXT_PRODUCTS_MANUFACTURER . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                         <div class="col-xs-8  col-xs-7">' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id) .  '</div>' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                          ' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                    </div>' . PHP_EOL;
	  
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;		  
	  
      $contents_edit_prod_tab1 .= '                <div class="form-group">' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                     <label class="col-xs-4  col-xs-5 control-label">' . TEXT_PRODUCTS_GOOGLE_TAXONOMY_CODE . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                         <div class="col-xs-8  col-xs-7">' . tep_draw_pull_down_menu('google_taxonomy_code', $google_taxonomy_codes_array, $pInfo->google_product_category) .  '</div>' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                          ' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                    </div>' . PHP_EOL;

	  $contents_edit_prod_tab1 .= '                 <div class="clearfix"></div>'. PHP_EOL ;
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;		  	  
	  
      $contents_edit_prod_tab1 .= '                <div class="form-group">' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                     <label class="col-xs-4  col-xs-5 control-label">' . TEXT_PRODUCTS_MODEL . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                            <div class="col-xs-8  col-xs-7">' . tep_draw_input_field('products_model', $pInfo->products_model) .  '</div>' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                           ' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                 </div>' . PHP_EOL;
	  
	  $contents_edit_prod_tab1 .= '                 <div class="clearfix"></div>'. PHP_EOL ;	  
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;		  
	  
      $contents_edit_prod_tab1 .= '                 <div class="form-group">' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                     <label class="col-xs-4  col-xs-5 control-label">' . TEXT_PRODUCTS_WEIGHT . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                            <div class="col-xs-8  col-xs-7">' . tep_draw_input_field('products_weight', $pInfo->products_weight).  '</div>' .PHP_EOL;
	  $contents_edit_prod_tab1 .= '                           ' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                 </div>' . PHP_EOL;

	  $contents_edit_prod_tab1 .= '                 <div class="clearfix"></div>'. PHP_EOL ;	  
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;			  
	  
      $contents_edit_prod_tab1 .= '                 <div class="form-group">' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                     <label class="col-xs-4 col-xs-5">' . TEXT_EDIT_SORT_ORDER . '</label>' . PHP_EOL;	  
	  $contents_edit_prod_tab1 .= '                           <div class="col-xs-8 col-xs-7">' . tep_draw_input_field('products_sort_order', $pInfo->products_sort_order) . '</div>'. PHP_EOL;
	  $contents_edit_prod_tab1 .= '                           ' . PHP_EOL;
	  $contents_edit_prod_tab1 .= '                 </div>' . PHP_EOL;

	  $contents_edit_prod_tab1 .= '                 <div class="clearfix"></div>'. PHP_EOL ;	  
	  $contents_edit_prod_tab1 .= '                 <br />' . PHP_EOL;	
?>