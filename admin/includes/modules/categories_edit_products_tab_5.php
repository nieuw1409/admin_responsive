<?php
            $contents_edit_prod_tab5       .=  PHP_EOL . '  <!-- Nav tabs Meta Tags -->' . PHP_EOL ;						
            $contents_edit_prod_tab5       .=  '<div role="tabpanel" id="prod_edit_tab_htc">'. PHP_EOL  ;			
			
            $contents_edit_prod_tab5_links .=  '  <ul class="nav nav-tabs" role="tablist" id="prod_edit_tab_htc">'. PHP_EOL  ;
            
			$contents_edit_prod_tab5 .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_edit_prod_tab5 .=  '  <div class="tab-content" id="prod_edit_tab_htc">'. PHP_EOL  ;	
		
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
              $contents_edit_prod_tab5_links .=  '    <li class="'. $active_tab . '"><a href="#prod_edit_htc_meta_tags_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="prod_edit_tab_htc">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

            			
			  $contents_edit_prod_tab5_content = '                                <div class="form-group">' . PHP_EOL .
										         '                                    <label for="prod_edit_head_title' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_PAGE_TITLE . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .										 
										         '                                    <div class="col-xs-9">'  . tep_draw_input_field('products_head_title_tag[' . $languages[$i]["id"] . ']', (isset($products_head_title_tag[ $languages[$i]['id']]) ? stripslashes($products_head_title_tag[      $languages[$i]['id']]) : tep_get_products_head_title_tag(      $pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_head_title' . $languages[$i]["name"] . '"' ) . PHP_EOL .
										         '                                    </div>' .
										         '                                </div><div class="clearfix"></div><br />' . PHP_EOL;										 
             $contents_edit_prod_tab5_content .= '                                <div class="form-group">' . PHP_EOL . 
										         '                                    <label for="prod_edit_head_breadcrumb' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_BREADCRUMB . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .												 
										         '                                    <div class="col-xs-9">' . tep_draw_input_field('products_head_breadcrumb_text[' . $languages[$i]['id'] . ']', (isset($products_head_breadcrumb_text[$languages[$i]['id']]) ? stripslashes($products_head_breadcrumb_text[$languages[$i]['id']]) : tep_get_products_head_breadcrumb_text($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_head_breadcrumb' . $languages[$i]['name'] . '"' ) . PHP_EOL .
										         '                                    </div>' . PHP_EOL .
										         '                                </div><div class="clearfix"></div><br />' . PHP_EOL;	
             $contents_edit_prod_tab5_content .= '                                <div class="form-group">' . PHP_EOL .
										         '                                    <label for="prod_edit_head_title_alt' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_PAGE_TITLE_ALT . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .										 
										         '                                    <div class="col-xs-9">' . tep_draw_input_field('products_head_title_tag_alt[' . $languages[$i]['id'] . ']', (isset($products_head_title_tag_alt[$languages[$i]['id']]) ? stripslashes($products_head_title_tag_alt[$languages[$i]['id']]) : tep_get_products_head_title_tag_alt($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_head_title_alt' . $languages[$i]['name'] . '"') . PHP_EOL .
										         '                                    </div>' . PHP_EOL .
										         '                                </div><div class="clearfix"></div><br />' . PHP_EOL;	
             $contents_edit_prod_tab5_content .= '                                <div class="form-group">' . PHP_EOL .
										         '                                    <label for="prod_edit_title_url' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_PAGE_TITLE_URL . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL .										 
										         '                                    <div class="col-xs-9">' . tep_draw_input_field('products_head_title_tag_url[' . $languages[$i]['id'] . ']', (isset($products_head_title_tag_url[$languages[$i]['id']]) ? stripslashes($products_head_title_tag_url[$languages[$i]['id']]) : tep_get_products_head_title_tag_url($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_title_url' . $languages[$i]['name'] . '"') . PHP_EOL .
										         '                                  </div><div class="clearfix"></div><br />' . PHP_EOL .
										         '                                </div>' . PHP_EOL;											 
             $contents_edit_prod_tab5_content .= '                                <div class="form-group">' . PHP_EOL .
 										         '                                    <label for="prod_edit_head_description' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_HEADER_DESCRIPTION . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL ;
             
			 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_DESC == 'false') {
                       $contents_edit_prod_tab5_content .= '<div class="col-xs-9">' .tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? stripslashes($products_head_desc_tag[$languages[$i]['id']]) : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_head_description' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
             } else {
                 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_ckeditor('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '110', '15', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])), 'id = "prod_edithead_description' . $languages[$i]['name'] . '" class="ckeditor"') . '</div>' . PHP_EOL ;
                 } else { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edithead_description' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
                 }
             } 												 
             $contents_edit_prod_tab5_content .= '                                </div><div class="clearfix"></div><br />' . PHP_EOL;
			 
	         $contents_edit_prod_tab5_content .= '                                <div class="form-group">' . PHP_EOL .
 										         '                                    <label for="prod_edit_head_keywords' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_KEYWORDS . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL ;
												 
			 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_KEYWORDS == 'false') {
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' .tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($products_head_keywords_tag[$languages[$i]['id']]) : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_head_keywords' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
             } else {
                 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_ckeditor('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($products_head_keywords_tag[$languages[$i]['id']]) : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'])), 'id = "prod_edit_head_keywords' . $languages[$i]['name'] . '" class="ckeditor"') . '</div>' . PHP_EOL ;;   
                 } else { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($products_head_keywords_tag[$languages[$i]['id']]) : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'])), 'id = "prod_edit_head_keywords' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;; 
                 }
             } 									 

	         $contents_edit_prod_tab5_content .= '                                </div><div class="clearfix"></div><br />' . PHP_EOL;	
			 
			 
	         $contents_edit_prod_tab5_content .= '                                <div class="form-group">' . PHP_EOL .
 										         '                                    <label for="prod_edit_head_listing_text' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_LISTING_TEXT . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL ;
												 
			 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_KEYWORDS == 'false') {
                       $contents_edit_prod_tab5_content .= '<div class="col-xs-9">' .tep_draw_textarea_field('products_head_listing_text[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_listing_text[$languages[$i]['id']]) ? stripslashes($products_head_listing_text[$languages[$i]['id']]) : tep_get_products_head_listing_text($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_head_listing_text' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
             } else {
                 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_ckeditor('products_head_listing_text[' . $languages[$i]['id'] . ']', 'soft', '110', '15', (isset($products_head_listing_text[$languages[$i]['id']]) ? $products_head_listing_text[$languages[$i]['id']] : tep_get_products_head_listing_text($pInfo->products_id, $languages[$i]['id'])), 'id = "prod_edit_head_listing_text' . $languages[$i]['name'] . '" class="ckeditor"') . '</div>' . PHP_EOL ;;   
                 } else { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_field('products_head_listing_text[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_listing_text[$languages[$i]['id']]) ? stripslashes($products_head_listing_text[$languages[$i]['id']]) : tep_get_products_head_listing_text($pInfo->products_id, $languages[$i]['id'])), 'id = "prod_edit_head_listing_text' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;; 
                 }
             } 									 

	         $contents_edit_prod_tab5_content .= '                                </div><div class="clearfix"></div><br />' . PHP_EOL;			 

	         $contents_edit_prod_tab5_content .= '                                <div class="form-group">' . PHP_EOL .
 										         '                                    <label for="prod_edit_head_sub_text' . $languages[$i]['name'] . '" class="col-xs-3 control-label">' . TEXT_PRODUCTS_SUB_TEXT . PHP_EOL . 								 
										         '                                    </label> ' . PHP_EOL ;
												 
			 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_KEYWORDS == 'false') {
                       $contents_edit_prod_tab5_content .= '                                <div class="col-xs-9">' .tep_draw_textarea_field('products_head_sub_text[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_sub_text[$languages[$i]['id']]) ? stripslashes($products_head_sub_text[$languages[$i]['id']]) : tep_get_products_head_sub_text($pInfo->products_id, $languages[$i]['id'])), 'id="prod_edit_head_sub_text' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;
             } else {
                 if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_ckeditor('products_head_sub_text[' . $languages[$i]['id'] . ']', 'soft', '110', '15', (isset($products_head_sub_text[$languages[$i]['id']]) ? $products_head_sub_text[$languages[$i]['id']] : tep_get_products_head_sub_text($pInfo->products_id, $languages[$i]['id'])), 'id = "prod_edit_head_sub_text' . $languages[$i]['name'] . '" class="ckeditor"') . '</div>' . PHP_EOL ;;   
                 } else { 
                     $contents_edit_prod_tab5_content .= '                                  <div class="col-xs-9">' . tep_draw_textarea_field('products_head_sub_text[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_sub_text[$languages[$i]['id']]) ? stripslashes($products_head_sub_text[$languages[$i]['id']]) : tep_get_products_head_sub_text($pInfo->products_id, $languages[$i]['id'])), 'id = "prod_edit_head_sub_text' . $languages[$i]['name'] . '"') . '</div>' . PHP_EOL ;; 
                 }
             } 									 

	         $contents_edit_prod_tab5_content .= '                                </div><div class="clearfix"></div><br />' . PHP_EOL;				 
										 									 
              $contents_edit_prod_tab5_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="prod_edit_htc_meta_tags_'     . $languages[$i]['name'] . '">' . $contents_edit_prod_tab5_content .'</div>'. PHP_EOL  ;										 
			  $active_tab = '' ;			  
            }		

            $contents_edit_prod_tab5_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_edit_prod_tab5_tabs  .=  '  </div>'. PHP_EOL  ; 
			
			$contents_edit_prod_tab5       .=	$contents_edit_prod_tab5_links . PHP_EOL . $contents_edit_prod_tab5_tabs . PHP_EOL ;
            $contents_edit_prod_tab5       .=  '</div>'. PHP_EOL  ;			
            $contents_edit_prod_tab5       .=  '<!-- end nav prod edit meta tags -->'. PHP_EOL  ;	
?>