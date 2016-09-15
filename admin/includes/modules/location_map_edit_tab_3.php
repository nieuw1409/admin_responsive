<?php
            $contents_edit_location_tab3       .=  PHP_EOL . '  <!-- Nav tabs location map edit Description marker -->' . PHP_EOL ;						
            $contents_edit_location_tab3       .=  '<div role="tabpanel" id="tab_loc_map_edit_description_marker">'. PHP_EOL  ;			
			
            $locmap_edit_contents_tab3_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_loc_map_edit_description_marker">'. PHP_EOL  ;

            $loc_map_edit_contents_tab3_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $loc_map_edit_contents_tab3_tabs .=  '  <div class="tab-content" id="tab_loc_map_edit_description_marker">'. PHP_EOL  ;	
			
			$active_tab = 'active' ;
			
			$languages = tep_get_languages();			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
			  $locmap_edit_contents_tab3_links .=  '    <li class="'. $active_tab . '"><a href="#loc_map_edit_marker_description' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_loc_map_edit_description_marker">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             									 
	          $prod_edit_contents_tab3_content   = '                                <div class="form-group">' . PHP_EOL .										 
										           '                                  <div class="col-xs-12">' . tep_draw_textarea_ckeditor('location_map_text_marker[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_location_map_text_marker( $id_marker, $languages[$i]['id']), 'id = "location_descrip_marker[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
										 '                                            </div>' . PHP_EOL .
										 '                                          </div>' . PHP_EOL;			
										 
             $loc_map_edit_contents_tab3_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="loc_map_edit_marker_description' . $languages[$i]['name'] . '">' . $prod_edit_contents_tab3_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

 
			
            $locmap_edit_contents_tab3_links .=  '  </ul>'. PHP_EOL  ;	
            $loc_map_edit_contents_tab3_tabs  .=  '  </div>'. PHP_EOL  ; 			
 
			

			
			$contents_edit_location_tab3       .=	 '<br />' . PHP_EOL ;			
			$contents_edit_location_tab3       .=	 '<p>' . TEXT_LOCATIONMAP_TEXT_MARKER . '</p>'. PHP_EOL ;
			$contents_edit_location_tab3       .=	 			$locmap_edit_contents_tab3_links . PHP_EOL ;
			$contents_edit_location_tab3       .=			    $loc_map_edit_contents_tab3_tabs  . PHP_EOL ;
            $contents_edit_location_tab3       .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
            $contents_edit_location_tab3       .=  '<!-- end nav location map edit Description marker  -->'. PHP_EOL  ;	
?>