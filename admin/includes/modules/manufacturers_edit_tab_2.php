<?php
            $languages = tep_get_languages();
            $contents_edit_manu_tab2       .=  PHP_EOL . '  <!-- Nav tabs manufacturers Tags 2 -->' . PHP_EOL ;						
            $contents_edit_manu_tab2       .=  '<div role="tabpanel" id="manu_edit_tab_ht_2">'. PHP_EOL  ;			
			
            $contents_edit_manu_tab2_links .=  '  <ul class="nav nav-tabs" role="tablist" id="manu_edit_tab_ht_2">'. PHP_EOL  ;
            
			$contents_edit_manu_tab2 .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_edit_manu_tab2 .=  '  <div class="tab-content" id="manu_edit_tab_ht_2">'. PHP_EOL  ;	
		
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
              $contents_edit_manu_tab2_links .=  '    <li class="'. $active_tab . '"><a href="#manu_edit_htc_meta_tags_2_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="manu_edit_tab_ht_2">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
										 
             $contents_edit_manu_tab2_content  = '                                <div class="form-group">' . PHP_EOL .
 										         '                                    <label for="manu_edit_head_description' . $languages[$i]['name'] . '" class="col-sm-3 control-label">' . TEXT_MANUFACTURERS_HT_DESCIPTION . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL ;
             
			 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_DESC == 'false') {
                       $contents_edit_manu_tab2_content .= '<div class="col-sm-9">' .tep_draw_textarea_field('manufacturers_htc_desc_tag[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_manufacturer_htc_desc($manu_id, $languages[$i]['id']), 'id="manu_edit_head_description' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
             } else {
                 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' . tep_draw_textarea_ckeditor('manufacturers_htc_desc_tag[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_manufacturer_htc_desc($manu_id, $languages[$i]['id']), 'id = "manu_edit_head_description' . $languages[$i]['name'] . '" class="ckeditor"') . '</div>' . PHP_EOL ;;   
                 } else { 
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' . tep_draw_textarea_field('manufacturers_htc_desc_tag[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_manufacturer_htc_desc($manu_id, $languages[$i]['id']), 'id="manu_edit_head_description' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;; 
                 }
             } 												 
             $contents_edit_manu_tab2_content .= '                                </div>' . PHP_EOL;
			 
	         $contents_edit_manu_tab2_content .= '                                <div class="form-group">' . PHP_EOL .
 										         '                                    <label for="manu_edit_head_keywords' . $languages[$i]['name'] . '" class="col-sm-3 control-label">' . TEXT_MANUFACTURERS_HT_KEYWORDS . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL ;
												 
			 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_KEYWORDS == 'false') {
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' .tep_draw_textarea_field('manufacturers_htc_keywords_tag[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_manufacturer_htc_keywords($manu_id, $languages[$i]['id']), 'id="manu_edit_head_keywords' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
             } else {
                 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' . tep_draw_textarea_ckeditor('manufacturers_htc_keywords_tag[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_manufacturer_htc_keywords($manu_id, $languages[$i]['id']), 'id = "manu_edit_head_keywords' . $languages[$i]['name'] . '" class="ckeditor"') . '</div>' . PHP_EOL ;;   
                 } else { 
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' . tep_draw_textarea_field('manufacturers_htc_keywords_tag[' . $languages[$i]['id'] . ']', 'hard', 50, 10,tep_get_manufacturer_htc_keywords($manu_id, $languages[$i]['id']), 'id = "manu_edit_head_keywords' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;; 
                 }
             } 									 

	         $contents_edit_manu_tab2_content .= '                                </div>' . PHP_EOL; 
			 
			 
 	         $contents_edit_manu_tab2_content .= '                                <div class="form-group">' . PHP_EOL .
 										         '                                    <label for="manu_edit_head_descrip_long' . $languages[$i]['name'] . '" class="col-sm-3 control-label">' . TEXT_MANUFACTURERS_HT_DESCIP_LONG . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL ;
												 
			 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_DESC == 'false') {
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' .tep_draw_textarea_field('manufacturers_htc_description[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_manufacturer_htc_description($manu_id, $languages[$i]['id']), 'id="manu_edit_head_descrip_long' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
             } else {
                 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' . tep_draw_textarea_ckeditor('manufacturers_htc_description[' . $languages[$i]['id'] . ']', 'hard', 50, 10, tep_get_manufacturer_htc_description($manu_id, $languages[$i]['id']), 'id = "manu_edit_head_descrip_long' . $languages[$i]['name'] . '" class="ckeditor"') . '</div>' . PHP_EOL ;;   
                 } else { 
                     $contents_edit_manu_tab2_content .= '                                  <div class="col-sm-9">' . tep_draw_textarea_field('manufacturers_htc_description[' . $languages[$i]['id'] . ']', 'hard', 50, 10,tep_get_manufacturer_htc_description($manu_id, $languages[$i]['id']), 'id = "manu_edit_head_descrip_long' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;; 
                 }
             } 									 

	         $contents_edit_manu_tab2_content .= '                                </div>' . PHP_EOL; 
			 
              $contents_edit_manu_tab2_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="manu_edit_htc_meta_tags_2_'     . $languages[$i]['name'] . '">' . $contents_edit_manu_tab2_content .'</div>'. PHP_EOL  ;										 
			  $active_tab = '' ;			  
            }		

            $contents_edit_manu_tab2_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_edit_manu_tab2_tabs  .=  '  </div>'. PHP_EOL  ; 
			
			$contents_edit_manu_tab2       .=	$contents_edit_manu_tab2_links . PHP_EOL . $contents_edit_manu_tab2_tabs . PHP_EOL ;
            $contents_edit_manu_tab2       .=  '</div>'. PHP_EOL  ;			
            $contents_edit_manu_tab2       .=  '<!-- end nav prod edit meta tags  2-->'. PHP_EOL  ;	
			
//			$contents .= $contents_edit_manu_tab2 ;
?>