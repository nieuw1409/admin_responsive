<?php
            $contents_popup_manager_tab       .=  PHP_EOL . '  <!-- Nav tabs popoup manager Description  -->' . PHP_EOL ;						
            $contents_popup_manager_tab       .=  '<div role="tabpanel" id="tab_popoup managerdescription">'. PHP_EOL  ;			
			
            $contents_popup_manager_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_popup_manager_description">'. PHP_EOL  ;

            $contents_popup_manager_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_popup_manager_tabs .=  '  <div class="tab-content" id="tab_popup_manager_description">'. PHP_EOL  ;	
			
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
			  $contents_popup_manager_links .=  '    <li class="'. $active_tab . '"><a href="#popup_manager_description' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_popup_manager_description">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             									 
	          $contents_popup_manager_content   = '                                <div class="form-group">' . PHP_EOL .										 
										           '                                  <div class="col-xs-12">' . tep_draw_textarea_ckeditor('popups_html_text[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($popups_html_text[$languages[$i]['id']]) ? stripslashes($popups_html_text[$languages[$i]['id']]) : tep_get_popups_text($bInfo->popups_id, $languages[$i]['id']))) . PHP_EOL .
										 '                                            </div>' . PHP_EOL .
										 '                                          </div>' . PHP_EOL;			
										 
             $contents_popup_manager_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="popup_manager_description' . $languages[$i]['name'] . '">' . $contents_popup_manager_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

 
			
            $contents_popup_manager_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_popup_manager_tabs  .=  '  </div>'. PHP_EOL  ; 			
 
			

			
			
//			$contents_popup_manager_tab       .=	 '<p>' . TEXT_POPUPS_TEXT . '</p> '. PHP_EOL ;
			$contents_popup_manager_tab       .=	 			$contents_popup_manager_links . PHP_EOL ;
			$contents_popup_manager_tab       .=			    $contents_popup_manager_tabs  . PHP_EOL ;
            $contents_popup_manager_tab       .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
            $contents_popup_manager_tab       .=  '<!-- end nav popup manager Description   -->'. PHP_EOL  ;	
?>