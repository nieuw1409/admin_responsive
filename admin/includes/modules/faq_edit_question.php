<?php


		  
            $contents_edit_faq_tab1       .=  '<div class="panel panel-primary">' . PHP_EOL ;	
            $contents_edit_faq_tab1       .=  '  <div class="panel-body">' . PHP_EOL ;		
            $contents_edit_faq_tab1       .=  PHP_EOL . '  <!-- Nav tabs product edit faq question   -->' . PHP_EOL ;						
            $contents_edit_faq_tab1       .=  '     <div role="tabpanel" id="tab_faq_edit_question">'. PHP_EOL  ;			
			
            $faq_edit_question_tab1_links .=  '        <ul class="nav nav-tabs" role="tablist" id="tab_faq_edit_question">'. PHP_EOL  ;

            $faq_edit_question_tab1_tabs .=  '            <!-- Tab panes -->'. PHP_EOL  ;
            $faq_edit_question_tab1_tabs .=  '            <div class="tab-content" id="tab_faq_edit_question">'. PHP_EOL  ;	
			
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
						    if (isset($faq_question_array) && !empty($faq_question_array[$languages[$i]['id']])) {
						      $faq_question = $faq_question_array[$languages[$i]['id']]; // reposted question
								} elseif (!empty($faq_info['question'][$languages[$i]['id']])) {
									$faq_question = $faq_info['question'][$languages[$i]['id']];
								} else {
									$faq_question = '';
								}			
			
			  $faq_edit_question_tab1_links .=  '              <li class="'. $active_tab . '"><a href="#faq_edit_question' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_faq_edit_question">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             									 
	          $faq_edit_question_tab1_content   = '                                <div class="form-group">' . PHP_EOL .										 
										           '                                  <div class="col-xs-12">' . tep_draw_textarea_ckeditor('faq_question['.$languages[$i]['id'].']', 'soft', '140', '40', $faq_question, 'id = "id_faq_question[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
										 '                                            </div>' . PHP_EOL .
										 '                                          </div>' . PHP_EOL;			
										 
             $faq_edit_question_tab1_tabs      .=  '               <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="faq_edit_question' . $languages[$i]['name'] . '">' . $faq_edit_question_tab1_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

 
			
            $faq_edit_question_tab1_links .=  '        </ul>'. PHP_EOL  ;	
            $faq_edit_question_tab1_tabs  .=  '      </div>'. PHP_EOL  ; 			
 
			

			
			
			$contents_edit_faq_tab1       .=	      '<p>' . TEXT_FAQ_QUESTION . '</p>'. PHP_EOL ;
			$contents_edit_faq_tab1       .=	 			$faq_edit_question_tab1_links . PHP_EOL ;
			$contents_edit_faq_tab1       .=			    $faq_edit_question_tab1_tabs  . PHP_EOL ;
            $contents_edit_faq_tab1       .=  '       </div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
            $contents_edit_faq_tab1       .=  '       <!-- end nav products edit faq question   -->'. PHP_EOL  ;	
            $contents_edit_faq_tab1       .=  '  </div>'. PHP_EOL  ;			
            $contents_edit_faq_tab1       .=  '</div>'. PHP_EOL  ;				
?>