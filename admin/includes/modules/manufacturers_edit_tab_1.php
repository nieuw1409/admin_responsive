<?php
            $languages = tep_get_languages();
            $contents_edit_manu_tab1       .=  PHP_EOL . '  <!-- Nav tabs manufacturers Tags -->' . PHP_EOL ;						
            $contents_edit_manu_tab1       .=  '<div role="tabpanel" id="manu_edit_tab_ht">'. PHP_EOL  ;			
			
            $contents_edit_manu_tab1_links .=  '  <ul class="nav nav-tabs" role="tablist" id="manu_edit_tab_ht">'. PHP_EOL  ;
            
			$contents_edit_manu_tab1 .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_edit_manu_tab1 .=  '  <div class="tab-content" id="manu_edit_tab_ht">'. PHP_EOL  ;	
		
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
              $contents_edit_manu_tab1_links .=  '    <li class="'. $active_tab . '"><a href="#manu_edit_htc_meta_tags_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="manu_edit_tab_ht">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

            			
			  $contents_edit_manu_tab1_content = '                                <div class="form-group">' . PHP_EOL .
										         '                                    <label for="manu_edit_head_url' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_MANUFACTURERS_URL . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .										 
										         '                                    <div class="col-xs-9">'  . tep_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']', tep_get_manufacturer_url($manu_id, $languages[$i]['id']), 'id="manu_edit_head_url' . $languages[$i]["name"] . '"') . PHP_EOL .
										         '                                    </div>' .
										         '                                </div><hr><br />' . PHP_EOL;										 
			  $contents_edit_manu_tab1_content.= '                                <div class="form-group">' . PHP_EOL .
										         '                                    <label for="manu_edit_head_title' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_MANUFACTURERS_HT_TITLE . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .										 
										         '                                    <div class="col-xs-9">'  . tep_draw_input_field('manufacturers_htc_title_tag[' . $languages[$i]['id'] . ']', tep_get_manufacturer_htc_title($manu_id, $languages[$i]['id']), 'id="manu_edit_head_title' . $languages[$i]["name"] . '"') . PHP_EOL .
										         '                                    </div>' .
										         '                                </div><hr><br />' . PHP_EOL;													 
             $contents_edit_manu_tab1_content .= '                                <div class="form-group">' . PHP_EOL . 
										         '                                    <label for="manu_edit_head_title_alt' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_MANUFACTURERS_HT_TITLE_ALT . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .												 
										         '                                    <div class="col-xs-9">' . tep_draw_input_field('manufacturers_htc_title_tag_alt[' . $languages[$i]['id'] . ']', tep_get_manufacturer_htc_title_alt($manu_id, $languages[$i]['id']), 'id="manu_edit_head_title_alt' . $languages[$i]["name"] . '"') . PHP_EOL .
										         '                                    </div>' . PHP_EOL .
										         '                                </div><hr><br />' . PHP_EOL;	
             $contents_edit_manu_tab1_content .= '                                <div class="form-group">' . PHP_EOL .
										         '                                    <label for="manu_edit_head_title_url' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_MANUFACTURERS_HT_TITLE_URL . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .										 
										         '                                    <div class="col-xs-9">' . tep_draw_input_field('manufacturers_htc_title_tag_url[' . $languages[$i]['id'] . ']', tep_get_manufacturer_htc_title_url($manu_id, $languages[$i]['id']), 'id="manu_edit_head_title_url' . $languages[$i]['name'] . '"') . PHP_EOL .
										         '                                    </div>' . PHP_EOL .
										         '                                </div><hr><br />' . PHP_EOL;	
												 
             $contents_edit_manu_tab1_content .= '                                <div class="form-group">' . PHP_EOL . 
										         '                                    <label for="manu_edit_head_breadcrumb' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_MANUFACTURERS_HT_BREADCRUM_TXT . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .												 
										         '                                    <div class="col-xs-9">' . tep_draw_input_field('manufacturers_htc_breadcrumb_text[' . $languages[$i]['id'] . ']', tep_get_manufacturer_htc_breadcrumb($manu_id, $languages[$i]['id']), 'id="manu_edit_head_breadcrumb' . $languages[$i]["name"] . '"') . PHP_EOL .
										         '                                    </div>' . PHP_EOL .
										         '                                </div><hr><br />' . PHP_EOL;															 
			 
              $contents_edit_manu_tab1_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="manu_edit_htc_meta_tags_'     . $languages[$i]['name'] . '">' . $contents_edit_manu_tab1_content .'</div>'. PHP_EOL  ;										 
			  $active_tab = '' ;			  
            }		

            $contents_edit_manu_tab1_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_edit_manu_tab1_tabs  .=  '  </div>'. PHP_EOL  ; 
			
			$contents_edit_manu_tab1       .=	$contents_edit_manu_tab1_links . PHP_EOL . $contents_edit_manu_tab1_tabs . PHP_EOL ;
            $contents_edit_manu_tab1       .=  '</div>'. PHP_EOL  ;			
            $contents_edit_manu_tab1       .=  '<!-- end nav prod edit meta tags -->'. PHP_EOL  ;	
			
//			$contents .= $contents_edit_manu_tab1 ;
?>