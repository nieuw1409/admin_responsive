<?php
            $contents_edit_location_tab1       .=  PHP_EOL . '  <!-- Nav tabs location map edit Description  -->' . PHP_EOL ;						
            $contents_edit_location_tab1       .=  '<div role="tabpanel" id="tab_loc_map_edit_description">'. PHP_EOL  ;			
			
            $info_pages__edit_contents_tab1_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_loc_map_edit_description">'. PHP_EOL  ;

            $info_pages_edit_contents_tab1_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $info_pages_edit_contents_tab1_tabs .=  '  <div class="tab-content" id="tab_loc_map_edit_description">'. PHP_EOL  ;	
			
			$active_tab = 'active' ;
			
			$languages = tep_get_languages();			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
			  $info_pages__edit_contents_tab1_links .=  '    <li class="'. $active_tab . '"><a href="#loc_map_edit_description' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_loc_map_edit_description">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
														 
	          $prod_edit_contents_tab3_content    = '<div class="form-group">' . PHP_EOL ; 									   
	          $prod_edit_contents_tab3_content   .=     tep_draw_bs_input_field('information_title[' . $languages[$i]['id'] . ']', (isset($information_title[$languages[$i]['id']]) ? stripslashes($edit[information_title]) : tep_get_information_entry($information_id, $languages[$i]['id'], 'information_title')),   ENTRY_TITLE, 'id_input_lang_title'[$i] ,    'col-xs-3', 'col-xs-9', 'left', ENTRY_TITLE ) . PHP_EOL; 
	          $prod_edit_contents_tab3_content   .= '</div>' . PHP_EOL ;
	          $prod_edit_contents_tab3_content   .= '<br />' . PHP_EOL ;
														 
             									 
	          $prod_edit_contents_tab3_content   .= '<div class="form-group">' . PHP_EOL .										 
										            '    <div class="col-xs-12">' . tep_draw_textarea_ckeditor('information_description[' . $languages[$i]['id'] . ']', 'hard', 50, 10, (isset($information_description[$languages[$i]['id']]) ? stripslashes($edit[information_description]) : tep_get_information_entry($information_id, $languages[$i]['id'], 'information_description'))) . PHP_EOL .
										            '    </div>' . PHP_EOL .
										            '</div>' . PHP_EOL;			
										 
             $info_pages_edit_contents_tab1_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="loc_map_edit_description' . $languages[$i]['name'] . '">' . $prod_edit_contents_tab3_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

 
			
            $info_pages__edit_contents_tab1_links .=  '  </ul>'. PHP_EOL  ;	
            $info_pages_edit_contents_tab1_tabs  .=  '  </div>'. PHP_EOL  ; 
			
			$contents_edit_location_tab1       .=	 '<br />' . PHP_EOL ;			
			$contents_edit_location_tab1       .=	 '<label class="control-label">' . '   '. ENTRY_TEXT . '</label><br />'. PHP_EOL ;
			$contents_edit_location_tab1       .=	 			$info_pages__edit_contents_tab1_links . PHP_EOL ;
			$contents_edit_location_tab1       .=			    $info_pages_edit_contents_tab1_tabs  . PHP_EOL ;
            $contents_edit_location_tab1       .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
            $contents_edit_location_tab1       .=  '<!-- end nav location map edit Description   -->'. PHP_EOL  ;	
?>