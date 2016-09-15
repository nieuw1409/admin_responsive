<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['rID'])) {
            tep_set_review_status($HTTP_GET_VARS['rID'], $HTTP_GET_VARS['flag']);
          }
        }

        tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $HTTP_GET_VARS['rID']));
        break;
      case 'save':
        $reviews_id = tep_db_prepare_input($HTTP_GET_VARS['rID']);
        $reviews_rating = tep_db_prepare_input($HTTP_POST_VARS['reviews_rating']);
        $reviews_text = tep_db_prepare_input($HTTP_POST_VARS['reviews_text']);
        $reviews_status = tep_db_prepare_input($HTTP_POST_VARS['reviews_status']);

        tep_db_query("update " . TABLE_REVIEWS . " set reviews_rating = '" . tep_db_input($reviews_rating) . "', reviews_status = '" . tep_db_input($reviews_status) . "', last_modified = now() where reviews_id = '" . (int)$reviews_id . "'");

		
        tep_db_query("update " . TABLE_REVIEWS_DESCRIPTION . " set reviews_text = '" . tep_db_input($reviews_text) . "' where reviews_id = '" . (int)$reviews_id . "'");

        tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews_id . '$rating=' . $reviews_rating ));
        break;
      case 'deleteconfirm':
        $reviews_id = tep_db_prepare_input($HTTP_GET_VARS['rID']);

        tep_db_query("delete from " . TABLE_REVIEWS . " where reviews_id = '" . (int)$reviews_id . "'");
        tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews_id . "'");

        tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo TABLE_HEADING_PRODUCTS; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_RATING; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_DATE_ADDED; ?></th>				   
                   <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>					   
                   <th class="text-left"  ><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
				$reviews_query_raw = "select reviews_id, products_id, date_added, last_modified, reviews_rating, reviews_status from " . TABLE_REVIEWS . " order by date_added DESC";
				$reviews_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $reviews_query_raw, $reviews_query_numrows);
				$reviews_query = tep_db_query($reviews_query_raw);
				while ($reviews = tep_db_fetch_array($reviews_query)) {
				  if ((!isset($HTTP_GET_VARS['rID']) || (isset($HTTP_GET_VARS['rID']) && ($HTTP_GET_VARS['rID'] == $reviews['reviews_id']))) && !isset($rInfo)) {
					$reviews_text_query = tep_db_query("select r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size, rd.reviews_text from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$reviews['reviews_id'] . "' and r.reviews_id = rd.reviews_id");
 					
					$reviews_text = tep_db_fetch_array($reviews_text_query);

					$products_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$reviews['products_id'] . "'");
					$products_image = tep_db_fetch_array($products_image_query);

					$products_name_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$reviews['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
					$products_name = tep_db_fetch_array($products_name_query);

					$reviews_average_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$reviews['products_id'] . "'");
					$reviews_average = tep_db_fetch_array($reviews_average_query);

					$review_info = array_merge((array)$reviews_text, (array)$reviews_average, (array)$products_name);
					$rInfo_array = array_merge((array)$reviews, (array)$review_info, (array)$products_image);
					$rInfo = new objectInfo($rInfo_array);
				  }

				  if (isset($rInfo) && is_object($rInfo) && ($reviews['reviews_id'] == $rInfo->reviews_id) ) {
					echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=preview') . '\'">' . PHP_EOL;
				  } else {
				    echo '<tr                onclick="document.location.href=\'' . tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews['reviews_id']) . '\'">' . PHP_EOL;
				  }
				  $stars = '' ;
				  for ($i = 1; $i <= $reviews['reviews_rating']; $i++) {
                     $stars .= tep_glyphicon( 'star', 'success'  );
                  }
?>			  
                                 <td>                    <?php echo tep_get_products_name($reviews['products_id']); ?></td>
                                 <td class="text-center"><?php echo $stars ; ?></td>
                                 <td class="text-center"><?php echo tep_date_short($reviews['date_added']); ?></td>
                                 <td class="text-center">
<?php
                                     if ($reviews['reviews_status'] == '1') {
                                         echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=setflag&flag=0&rID=' . $reviews['reviews_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . PHP_EOL ;
										 
                                     } else {
                                         echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=setflag&flag=1&rID=' . $reviews['reviews_id'] . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
										 
                                     }
?>
                                 </td>								 
                                 <td class="text-right">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews['reviews_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews['reviews_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews['reviews_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>						
               </tr>	
<?php
                  if (isset($rInfo) && is_object($rInfo) && ($reviews['reviews_id'] == $rInfo->reviews_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_REVIEW . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('reviews', FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_REVIEW_INTRO . '<br />' . tep_get_products_name($reviews['products_id'])   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove',  tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
									
									   if (!isset($rInfo->reviews_status)) $rInfo->reviews_status = '1';
										  switch ($rInfo->reviews_status) {
										    case '0': $in_status = false; $out_status = true; break;
										    case '1':
										    default: $in_status = true; $out_status = false;
									   }	
									   
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_ZONE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('zones', FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_reviews') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_hidden_field('reviews_id', $rInfo->reviews_id) .            PHP_EOL ;	
									   $contents            .= '                           ' . tep_draw_hidden_field('products_id', $rInfo->products_id) .          PHP_EOL ;	
									   $contents            .= '                           ' . tep_draw_hidden_field('customers_name', $rInfo->customers_name) .    PHP_EOL ;	
									   $contents            .= '                           ' . tep_draw_hidden_field('products_name', $rInfo->products_name) .      PHP_EOL ;	
									   $contents            .= '                           ' . tep_draw_hidden_field('products_image', $rInfo->products_image) .    PHP_EOL ;	
									   $contents            .= '                           ' . tep_draw_hidden_field('date_added', $rInfo->date_added).             PHP_EOL ;	
                                       $contents            .= '                       </div>' . PHP_EOL ;

			                           $contents            .= '                       <ul class="list-group">' . PHP_EOL;
			                           $contents            .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                           $contents            .= '                              ' . ENTRY_PRODUCT . ' <span class="bg-info">' . $rInfo->products_name . '</span>' . PHP_EOL;
			                           $contents            .= '                          </li>' . PHP_EOL;
                                       $contents            .= '                          <li class="list-group-item">' . PHP_EOL;		
			                           $contents            .= '                              ' . ENTRY_FROM . '  <span class="bg-info">' . $rInfo->customers_name . '</span>'  . PHP_EOL;
			                           $contents            .= '                          </li>' . PHP_EOL;					
                                       $contents            .= '                          <li class="list-group-item">' . PHP_EOL;		
			                           $contents            .= '                              ' . ENTRY_DATE . '  <span class="bg-info">' . tep_date_short($rInfo->date_added) . '</span>' . PHP_EOL;
			                           $contents            .= '                          </li>' . PHP_EOL;						                          
 			                           $contents            .= '                       </ul>' . PHP_EOL;
									   
									   $contents            .= '                       <div class="input-group">' . PHP_EOL;	  
									   $contents            .= '                           <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL; 
									   $contents            .= '                               <label>' . TEXT_INFO_REVIEW_STATUS . '</label><br />' . PHP_EOL;
									   $contents            .= '                               <label class="btn btn-default' . ( $in_status ==  1 ? " active " : "" ) . '">' . TEXT_REVIEW_PUBLISHED  . PHP_EOL;
									   $contents            .= '                           ' .    tep_draw_radio_field("reviews_status", "1", $in_status) . PHP_EOL;
									   $contents            .= '                               </label>' . PHP_EOL;	  
									   $contents            .= '                               <label class="btn btn-default ' . ( $out_status == 1 ? " active " : "" ) . '">'. TEXT_REVIEW_NOT_PUBLISHED .  PHP_EOL;
									   $contents            .= '                               ' . tep_draw_radio_field("reviews_status", "0", $out_status) . PHP_EOL;
									   $contents            .= '                               </label>' . PHP_EOL;
									   $contents            .= '                           </div>' . PHP_EOL;
									   $contents            .= '                       </div>' . PHP_EOL;									   
									   
									   $contents            .= '                       <br />' . PHP_EOL;									   
									   
									   $rating = array( TEXT_RATING_1,TEXT_RATING_2, TEXT_RATING_3, TEXT_RATING_4, TEXT_RATING_5) ;
									   
 	                                   $contents            .= '                       <label class="col-xs-3">' . ENTRY_RATING . '</label>' . PHP_EOL ;										   
 	                                   $contents            .= '                       <div class="form-group">' . PHP_EOL ;
 	                                   $contents            .= '                           <div class="radio radio-success radio-inline">' . PHP_EOL ;
                                       for ($i=1; $i<=5; $i++)	{
                                         $contents            .=  			                    tep_bs_radio_field('reviews_rating', $i, $rating[ $i - 1 ],     'input_review_rating'.$i,   ($rInfo->reviews_rating == $i ? true : false ), 'radio radio-success radio-inline', '', '', 'right') ;
                                       }										 
                                       $contents            .= '                           </div>'. PHP_EOL  ;	
                                       $contents            .= '                       </div>'. PHP_EOL  ;										   
									   
									   $contents            .= '                       <br />' . PHP_EOL;									   
										
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_textarea_ckeditor('reviews_text', 'soft', '140', '40',$rInfo->reviews_text, 'id = "review_text" class="ckeditor"') . PHP_EOL ;
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   								   
									   $contents            .= '                       <br />' . PHP_EOL;	
									   								   
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $cInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . ENTRY_FROM . ' ' .     $rInfo->customers_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . ENTRY_DATE . '  ' .    tep_date_short($rInfo->date_added) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . ENTRY_TEXT . '<br />' . tep_draw_textarea_ckeditor('reviews_text', 'soft', '140', '40',$rInfo->reviews_text, 'id = "review_text" class="ckeditor"') . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row ' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets				   
				} // end while while ($countries = tep_db_fetch_arra			   
?>				
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>	  
    </table>
	

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>