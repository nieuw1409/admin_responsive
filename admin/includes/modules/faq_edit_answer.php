<?php 
            $contents_edit_faq_tab2       .=  '<div class="panel panel-primary">' . PHP_EOL ;	
            $contents_edit_faq_tab2       .=  '  <div class="panel-body">' . PHP_EOL ;			
            $contents_edit_faq_tab2       .=  '   <!-- Nav tabs product edit faq answer   -->' . PHP_EOL ;						
            $contents_edit_faq_tab2       .=  '   <div role="tabpanel" id="tab_faq_edit_answer">'. PHP_EOL  ;			
			
            $faq_edit_answer_tab2_links .=  '      <ul class="nav nav-tabs" role="tablist" id="tab_faq_edit_answer">'. PHP_EOL  ;

            $faq_edit_answer_tab2_tabs .=  '          <!-- Tab panes -->'. PHP_EOL  ;
            $faq_edit_answer_tab2_tabs .=  '          <div class="tab-content" id="tab_faq_edit_answer">'. PHP_EOL  ;	
			
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
			  if (isset($faq_answer_array) && !empty($faq_answer_array[$languages[$i]['id']])) {
				$faq_answer = $faq_answer_array[$languages[$i]['id']]; // reposted answer
			  } elseif (!empty($faq_info['answer'][$languages[$i]['id']])) {
				$faq_answer = $faq_info['answer'][$languages[$i]['id']];
			  } else {
				$faq_answer = '';
			  }			
			
			  $faq_edit_answer_tab2_links .=  '               <li class="'. $active_tab . '"><a href="#faq_edit_answer' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_faq_edit_answer">' .
			                                                       tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             									
												
	          $faq_edit_answer_tab2_content   = '                                <div class="form-group">' . PHP_EOL .										 
										           '                                  <div class="col-xs-12">' . tep_draw_textarea_ckeditor('faq_answer['.$languages[$i]['id'].']', 'soft', '140', '40', $faq_answer, 'id = "id_faq_answer[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
										 '                                            </div>' . PHP_EOL .
										 '                                          </div>' . PHP_EOL;			
										 
             $faq_edit_answer_tab2_tabs      .=  '            <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="faq_edit_answer' . $languages[$i]['name'] . '">' . $faq_edit_answer_tab2_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

 
			
            $faq_edit_answer_tab2_links .=  '             </ul>'. PHP_EOL  ;	
            $faq_edit_answer_tab2_tabs  .=  '          </div>'. PHP_EOL  ; 			
 
			

			
			
			$contents_edit_faq_tab2       .=  '         <p>' . TEXT_FAQ_ANSWER . '</p>'. PHP_EOL ;
			$contents_edit_faq_tab2       .=	 			$faq_edit_answer_tab2_links . PHP_EOL ;
			$contents_edit_faq_tab2       .=			    $faq_edit_answer_tab2_tabs  . PHP_EOL ;
            $contents_edit_faq_tab2       .=  '        </div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
            $contents_edit_faq_tab2       .=  '        <!-- end nav products edit faq answer   -->'. PHP_EOL  ;	
            $contents_edit_faq_tab2       .=  '  </div>'. PHP_EOL  ;			
            $contents_edit_faq_tab2       .=  '</div>'. PHP_EOL  ;			
?>