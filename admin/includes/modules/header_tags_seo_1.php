<?php
 			                $contents_ht_seo_tab1  .= '                                      <div class="form-group">' . PHP_EOL .										 
															       							     tep_draw_bs_pull_down_menu('new_files', $newfiles, null, HEADING_TITLE_SEO_PAGENAME, 'id_get_name_file', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', 'onChange="this.form.submit();"', false) . 
							                                  '                              </div>' . PHP_EOL;	
 			                $contents_ht_seo_tab1  .= '                                      <div class="form-group">' . PHP_EOL .										 
															       							     tep_draw_hidden_field('action', 'update')	.	
							                                  '                              </div>' . PHP_EOL;																  
                                                                                                          														  

                            if ($currentFile == SELECT_A_FILE || $currentFile == ADD_MISSING_PAGES)  {  //don't display any boxes
                               $start = 0;
                               $stop = 0;
                               $title = '';
                            }  else if ($currentFile == SHOW_ALL_FILES) { //display all boxes               
                               $start = FIRST_PAGE_ENTRY;
                               $stop = count($newfiles);
                            } else {                                    //display the selected file
                               $start = $currentFile;
                               $stop = $currentFile + 1;
                            }
							$contents_ht_seo_tab1       .=  PHP_EOL . '  <!-- Nav  header tags seo setting   -->' . PHP_EOL ;						
							$contents_ht_seo_tab1       .=  '<div role="tabpanel" id="tab_ht_seo_setting">'. PHP_EOL  ;			
							
							$contents_ht_seo_tab1_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_ht_seo_setting">'. PHP_EOL  ;

							$contents_ht_seo_tab1_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
							$contents_ht_seo_tab1_tabs .=  '  <div class="tab-content" id="tab_ht_seo_setting">'. PHP_EOL  ;	
							
							$active_tab = 'active' ;							
  
                            $numbLanguages = count($languages);
                            for ($x = $start; $x < $stop; ++$x)  {   //show the correct boxes
                               for ($i=0; $i < $numbLanguages; ++$i) { //show one for each language
                                   $pageTags_query = tep_db_query("select page_title, page_description, page_keywords, page_logo, page_logo_1 as alt_1, append_category as opt_0, append_manufacturer as opt_1, append_model as opt_2, append_product as opt_3, append_root as opt_4, append_default_title as opt_5, append_default_description as opt_6, append_default_keywords as opt_7, append_default_logo as opt_8, sortorder_category as opt_9, sortorder_manufacturer as opt_10, sortorder_model as opt_11, sortorder_product as opt_12, sortorder_root as opt_13, sortorder_title as opt_14, sortorder_description as opt_15, sortorder_keywords as opt_16, sortorder_logo as opt_17 from " . TABLE_HEADERTAGS . " 
				                               where page_name like '" . $newfiles[$x]['text'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'  and stores_id = '" . (int)$multi_stores_id . "' LIMIT 1"); // multi stores
                                   $pageTags = tep_db_fetch_array($pageTags_query); 
    
                                   if ($checkedKeywordLive[$i] == 'checked') {
                                      $pageTags['page_keywords'] = $keywordStr;
                                   }    

                                   $id = sprintf("%d_%d", $x, $languages[$i]['id']);  //build unique id
                                   $id_toggle = sprintf("%d%d", $x, $languages[$i]['id']);  //build unique id
                                   $checked = ($viewResult == $id_toggle) ? 'checked disabled' : '';
								   
									  $contents_ht_seo_tab1_links .=  '    <li class="'. $active_tab . '"><a href="#edit_ht_seo_setting' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_ht_seo_setting">' .
																				 tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
																		 
									  $contents_ht_seo_tab1_content   = '                              <div class="form-group">' . PHP_EOL .										 
																										  tep_draw_bs_input_field('title_'. $id, ($pageTags['page_title']) ? $pageTags['page_title'] : "", HEADING_TITLE_SEO_TITLE,    'pagetitle_' . $id_toggle, 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEO_TITLE ) .
																        '                              </div>' . PHP_EOL;			
/*									  $contents_ht_seo_tab1_content   = '                       <div class="ui-state-default smallText" width="18%" style="font-weight: bold;"><?php echo HEADING_TITLE_SEO_TITLE; ?></div>' .
                        '<div class="ui-widget-content smallText" title="' . $commonPopup['title'] . 'eric" class="popup" >' .
                         '<div style="float:left; width:100%;">' .
                          '<div style="float:left;">' .
                           '<input type="text" name="title_'.  $id . '" value="'.  ($pageTags['page_title']) ? $pageTags['page_title'] : "" . '" maxlength="255" size="60" id="pagetitle_' . $id_toggle . '"' .
                            'onkeyup="javascript:return ShowCharacterCount("pagetitle_' . $id_toggle . '")">' .
                          '</div> ' .
                          '<div style="float:right;"><input type="text" disabled name="title_' . $id . '_cnt" id="pagetitle_' . $id_toggle . '_cnt" value="0" size="2" style=""></div>' .
                          '</div>' .
                        '</div>' ;
*/									  
									  $contents_ht_seo_tab1_content  .= '                              <div class="form-group">' . PHP_EOL .										 
																										  tep_draw_bs_input_field('desc_'. $id, $pageTags['page_description'], HEADING_TITLE_SEO_DESCRIPTION,    'pagedesc_' . $id_toggle, 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEO_DESCRIPTION ) .
																        '                              </div>' . PHP_EOL;																			
																 
									  $contents_ht_seo_tab1_content  .= '                              <div class="form-group">' . PHP_EOL .										 
																										  tep_draw_bs_input_field('keyword_'. $id, $pageTags['page_keywords'], HEADING_TITLE_SEO_KEYWORDS,    'keyword_' . $id_toggle, 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEO_KEYWORDS ) .
																        '                              </div>' . PHP_EOL;	
																		
									  $contents_ht_seo_tab1_content  .= '                              <div class="form-group">' . PHP_EOL .										 
																										  tep_draw_bs_input_field('logo_'. $id, $pageTags['page_logo'], HEADING_TITLE_SEO_LOGO,    'logo_' . $id_toggle, 'control-label col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEO_LOGO ) .
																        '                              </div>' . PHP_EOL;

								 
// get sort order
									 $contents_ht_seo_tab1_content  .=  '<div class="clearfix"></div><br />' . PHP_EOL;
                                     $incrCheckNumb = count($optionPopup) / 3;
                                     $incrSortNumb = count($optionPopup) - $incrCheckNumb;
                                     $rows = floor(count($options)/2) + 1;
                                     for ($y = 0; $y < $rows; ++$y) {
                                         $optTag = sprintf("opt_%d", $y);
                                         $sortoptTag = sprintf("opt_%d", $y + $incrCheckNumb);
                                         $checked = ($pageTags[$optTag] > 0) ? true : false ;
                                         $disabled = ($pageTags[$sortoptTag] > 0) ? '' : 'disabled';
                                         $sortNumb = (tep_not_null($pageTags[$sortoptTag]) && $pageTags[$sortoptTag] !== '0') ? $pageTags[$sortoptTag] : '';


									     $contents_ht_seo_tab1_content  .=	'   <div class="form-group form-inline"> ' . PHP_EOL ;	

									     $contents_ht_seo_tab1_content  .=            tep_draw_bs_input_field('sortoption_'.$y . '_'. $id, $sortNumb, $options[$y],  $y . '_' . $id, 'control-label col-xs-3', 'col-xs-2', 'left', $options[$y]). PHP_EOL ;	
										 
									     $contents_ht_seo_tab1_content  .=          tep_bs_checkbox_field('option_' . $y . '_' . $id, 
										                                                                   '', 
																										   '', 
																										   'option_' . $y . '_' . $id, 
																										   $checked, 'checkbox checkbox-success', '', 'col-xs-3', 'right') ;	
																										   
									     $contents_ht_seo_tab1_content  .=	'   </div> ' . PHP_EOL ;										 

				


 						
                                        if ($y < ($rows - 1)) {
                                            $z = $y + 5; 
                                            $optTag = sprintf("opt_%d", $z);
                                            $sortoptTag = sprintf("opt_%d", $z + $incrCheckNumb);
                                            $checked = ($pageTags[$optTag] > 0) ? 'checked' : '';
                                            $disabled = ($pageTags[$sortoptTag] > 0) ? '' : 'disabled';
                                            $sortNumb = (tep_not_null($pageTags[$sortoptTag]) && $pageTags[$sortoptTag] !== '0') ? $pageTags[$sortoptTag] : '';                              						   
						                } else { 
                                        }  										
						            } 
//									$contents_ht_seo_tab1_content  .=  '</div>' . PHP_EOL;									
//end sort order 
									 $contents_ht_seo_tab1_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="edit_ht_seo_setting' . $languages[$i]['name'] . '">' . $contents_ht_seo_tab1_content .'</div>'. PHP_EOL  ;					  
								     $active_tab = '' ;
                               }
							   $contents_ht_seo_tab1_links .=  '  </ul>'. PHP_EOL  ;	
							   $contents_ht_seo_tab1_tabs  .=  '  </div>'. PHP_EOL  ; 			
							   $contents_ht_seo_tab1      .=	 '<p>' . HEADING_TITLE_SEO_VIEW_RESULT . '</p>'. PHP_EOL ;
							   $contents_ht_seo_tab1      .=	 			$contents_ht_seo_tab1_links . PHP_EOL ;
							   $contents_ht_seo_tab1      .=			    $contents_ht_seo_tab1_tabs  . PHP_EOL ;
							   $contents_ht_seo_tab1      .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
							   $contents_ht_seo_tab1      .=  '<!-- end nav header tags seo setting   -->'. PHP_EOL  ;									   
							} 
							
							echo $contents_ht_seo_tab1 ;
?>