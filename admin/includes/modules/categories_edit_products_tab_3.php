<?php
            $contents_edit_prod_tab3       .=  PHP_EOL . '  <!-- Nav tabs product edit Description  -->' . PHP_EOL ;						
            $contents_edit_prod_tab3       .=  '<div role="tabpanel" id="tab_prod_edit_description">'. PHP_EOL  ;			
			
            $prod_edit_contents_tab3_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_prod_edit_description">'. PHP_EOL  ;

            $prod_edit_contents_tab3_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $prod_edit_contents_tab3_tabs .=  '  <div class="tab-content" id="tab_prod_edit_description">'. PHP_EOL  ;	
			
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
			  $prod_edit_contents_tab3_links .=  '    <li class="'. $active_tab . '"><a href="#prod_edit_description' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_prod_edit_description">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             									 
	          $prod_edit_contents_tab3_content   = '                                <div class="form-group">' . PHP_EOL .										 
										           '                                  <div class="col-xs-12">' . tep_draw_textarea_ckeditor('products_description[' . $languages[$i]['id'] . ']', 'soft', '140', '40',( empty($pInfo->products_id) ? '': tep_get_products_description($pInfo->products_id, $languages[$i]['id'])), 'id = "products_description[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
										 '                                            </div>' . PHP_EOL .
										 '                                          </div><div class="clearfix"></div><br />' . PHP_EOL;			
										 
             $prod_edit_contents_tab3_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="prod_edit_description' . $languages[$i]['name'] . '">' . $prod_edit_contents_tab3_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

 
			
            $prod_edit_contents_tab3_links .=  '  </ul>'. PHP_EOL  ;	
            $prod_edit_contents_tab3_tabs  .=  '  </div>'. PHP_EOL  ; 			
 
			

			
			
			$contents_edit_prod_tab3       .=	 '<p>' . TEXT_PRODUCTS_DESCRIPTION . '</p>'. PHP_EOL ;
			$contents_edit_prod_tab3       .=	 			$prod_edit_contents_tab3_links . PHP_EOL ;
			$contents_edit_prod_tab3       .=			    $prod_edit_contents_tab3_tabs  . PHP_EOL ;
            $contents_edit_prod_tab3       .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
            $contents_edit_prod_tab3       .=  '<!-- end nav products edit Description   -->'. PHP_EOL  ;	
?>