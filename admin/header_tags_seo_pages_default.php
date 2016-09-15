<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  require_once(DIR_WS_FUNCTIONS . 'header_tags.php'); 

  $filename = DIR_FS_CATALOG. DIR_WS_INCLUDES . 'header_tags.php'; 
  $languages = tep_get_languages();
 
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {  
      case 'insert':
/*        $ht_pages_name = tep_db_prepare_input($HTTP_POST_VARS['countries_name']);
        $ht_pages_iso_code_2 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_2']);
        $ht_pages_iso_code_3 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);

        tep_db_query("insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) values ('" . tep_db_input($ht_pages_name) . "', '" . tep_db_input($ht_pages_iso_code_2) . "', '" . tep_db_input($ht_pages_iso_code_3) . "', '" . (int)$address_format_id . "')");
*/
        for ($i=0; $i < count($languages); ++$i)  {

          $defaultTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS_DEFAULT . " where language_id = '" . (int)$languages[$i]['id'] . "' and stores_id =   '" . (int)$multi_stores_id . "'  LIMIT 1"); // multi stores
          $defaultTags = tep_db_fetch_array($defaultTags_query);
          $sql_data_array = array('default_title' =>                      '', 
                                  'default_description' =>                '', 
                                  'default_keywords' =>                   '',                                 
                                  'default_logo_text' =>                  '',     
                                  'home_page_text' =>                     '', 
                                  'default_logo_append_group' =>          0, 
                                  'default_logo_append_category' =>       0,
                                  'default_logo_append_manufacturer' =>   0,
                                  'default_logo_append_product' =>        0,
                                  'meta_google' =>                        1,
                                  'meta_language' =>                      1,
                                  'meta_noodp' =>                         1,
                                  'meta_noydir' =>                        1,
                                  'meta_replyto' =>                       1,
                                  'meta_revisit' =>                       1,
                                  'meta_robots' =>                        1,
                                  'meta_unspam' =>                        1,
                                  'meta_canonical' =>                     1,
                                  'meta_og' =>                            1,								  
                                  'stores_id' =>                          (int)$multi_stores_id  							  
                                 ); 
                            
          if (tep_db_num_rows($defaultTags_query)) {
             // tep_db_perform(TABLE_HEADERTAGS_DEFAULT, $sql_data_array, 'update', "language_id = '" . (int)$languages[$i]['id'] . "' and stores_id =   '" . (int)$multi_stores_id . "' "); // muli stores
          } else {
             $insert_sql_data = array('language_id' => (int)$languages[$i]['id']);
             $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
             tep_db_perform(TABLE_HEADERTAGS_DEFAULT, $sql_data_array);   
          }     
        }
        tep_redirect(tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT));
        break;		
      case 'save':
//        $ht_pages_id = tep_db_prepare_input($HTTP_GET_VARS['htpID']);
        $ht_default_language = $HTTP_GET_VARS['LangID'];		
        $ht_default_title = tep_db_prepare_input($HTTP_POST_VARS['input_default_title']);
        $ht_default_description = tep_db_prepare_input($HTTP_POST_VARS['input_default_description']);
        $ht_default_keywords = tep_db_prepare_input($HTTP_POST_VARS['input_default_keywords']);
        $ht_default_logo = tep_db_prepare_input($HTTP_POST_VARS['input_default_logo_text']);

        $sql_data_array = array('default_title' => $ht_default_title, 
                                'default_description' => $ht_default_description,
                                'default_keywords' => $ht_default_keywords, 
                                'default_logo_text' => $ht_default_logo,  
                                'home_page_text' =>  $HTTP_POST_VARS['input_home_page_text'],
						

                                'default_logo_append_group' =>  $HTTP_POST_VARS['default_logo_append_group'],
                                'default_logo_append_category' =>  $HTTP_POST_VARS['default_logo_append_category'],
                                'default_logo_append_manufacturer' =>  $HTTP_POST_VARS['default_logo_append_manufacturer'],
                                'default_logo_append_product' =>  $HTTP_POST_VARS['default_logo_append_product'],								

                                'meta_google' =>  $HTTP_POST_VARS['meta_google'],
                                'meta_language' =>  $HTTP_POST_VARS['meta_language'],

                                'meta_noodp' =>  $HTTP_POST_VARS['meta_noodp'],
                                'meta_noydir' =>  $HTTP_POST_VARS['meta_noydir'],

                                'meta_replyto' =>  $HTTP_POST_VARS['meta_replyto'],
                                'meta_revisit' =>  $HTTP_POST_VARS['meta_revisit'],

                                'meta_robots' =>  $HTTP_POST_VARS['meta_robots'],
                                'meta_unspam' =>  $HTTP_POST_VARS['meta_unspam'],

                                'meta_canonical' =>  $HTTP_POST_VARS['meta_canonical'],
                                'meta_og' =>  $HTTP_POST_VARS['meta_og'],
								
                                'language_id' =>  $ht_default_language,
                                'stores_id' =>  $multi_stores_id								
								
								) ;
                                  
                                      

                        tep_db_perform(TABLE_HEADERTAGS_DEFAULT, $sql_data_array, 'update', "language_id = '" . (int)$ht_default_language . "' and stores_id = '" . (int)$multi_stores_id . "' "); // multi stores		

        tep_redirect(tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page']  ));
        break;
      case 'deleteconfirm':
        $htd_pages_id          = tep_db_prepare_input($HTTP_GET_VARS['htpID']);
        $htd_pages_language_id = tep_db_prepare_input($HTTP_GET_VARS['LangID']);		
 
        tep_db_query("delete from " . TABLE_HEADERTAGS_DEFAULT . " where language_id = '" . (int)$htd_pages_language_id . "' and stores_id = '" . (int)$multi_stores_id . "'");

        tep_redirect(tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page']  ));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE  ; ?></h1> 
            <p class="col-xs-12 col-md-6"><?php echo TEXT_INFORMATION_DEFAULT ;  ?></p> 			
            <div class="clearfix"></div>
          </div><!-- page-header-->

            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row"> 
                   <th class="text-center"><?php echo TABLE_HEADING_HT_SEO_PAGES_LANGUAGE; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_HT_SEO_PAGES_TITLE; ?></th>					   
                   <th class="text-center"><?php echo TABLE_HEADING_HT_SEO_PAGES_DESCRIPTION; ?></th>					   
                   <th>                    <?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>   
<?php
                 $htd_pages_query_raw = "select default_title, default_description, default_keywords, default_logo_text, 
				                                home_page_text, 
												default_logo_append_group, default_logo_append_category, default_logo_append_manufacturer, default_logo_append_product, 
												meta_google, meta_language, meta_noodp, meta_noydir, meta_replyto, meta_revisit, meta_robots, meta_unspam, 
												meta_canonical, meta_og, 
												language_id, stores_id from " . TABLE_HEADERTAGS_DEFAULT . " 
				                        where stores_id = '" . (int)$multi_stores_id . "' order by language_id" ; // multi stores
				 
                 $htd_pages_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $htd_pages_query_raw, $htd_pages_query_numrows);
                 $htd_pages_query = tep_db_query($htd_pages_query_raw);
                 while ($htd_pages = tep_db_fetch_array($htd_pages_query)) {
                   if ((!isset($HTTP_GET_VARS['htpID']) || (isset($HTTP_GET_VARS['htpID']) && ($HTTP_GET_VARS['htpID'] == $htd_pages['language_id']))) && !isset($htp_info) && (substr($action, 0, 3) != 'new')) {
                      $htp_info = new objectInfo($htd_pages);
                   }

                   if (isset($htp_info) && is_object($htp_info) && ($htd_pages['language_id'] == $htp_info->language_id)) {
                      echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $htp_info->language_id . '&action=edit') . '\'">' . "\n";
                   } else {
                      echo '<tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $htd_pages['language_id']) . '\'">' . "\n";
                   }
?>
                               <td class="text-center"><?php echo tep_get_language_name( $htd_pages['language_id']  )  ; ?></td>							    						   
                               <td class="text-center"><?php echo $htd_pages['default_title']  ; ?></td>
                               <td class="text-center"><?php echo $htd_pages['default_description']  ; ?></td>							   
                               <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&LangID=' . $htd_pages['language_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&LangID=' . $htd_pages['language_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&LangID=' . $htd_pages['language_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>						
               </tr>	
<?php
                  if (isset($htp_info) && is_object($htp_info)  && ($htd_pages['language_id'] == $HTTP_GET_VARS['LangID']) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_SEO_PAGE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_bs_form('htp_seo_delete', FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $HTTP_GET_VARS['htpID'] . '&LangID=' . $HTTP_GET_VARS['LangID'] . '&action=deleteconfirm', 'post', 'role=form') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_HEADING_DELETE_INTRO_SEO_PAGE . '<br />' . $htd_pages['default_title']  . '<br />'  . 
									                                                                                                       tep_get_language_name( $htd_pages['language_id']  ). '<br /> ' .
																																			'</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $HTTP_GET_VARS['htpID'] . '&LangID=' . $HTTP_GET_VARS['LangID'] . '&action=deleteconfirm'), null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $htp_info->page_name), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;	

			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_DEFAULT_TAGS . '</div>' . PHP_EOL;									   
			                           $contents            .= '               ' . tep_draw_bs_form('ht_seo_pages_default', FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&LangID=' . $HTTP_GET_VARS['LangID'] . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_htp_seo_default') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_default_title',       $htd_pages['default_title'],        OPTION_INCL_TITLE,       'id_input_default_title' ,        'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_TITLE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_default_description',       $htd_pages['default_description'],        OPTION_INCL_DESC,       'id_input_default_description' ,        'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_DESC,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_default_keywords',      $htd_pages['default_keywords'],       OPTION_INCL_KEYWORDS,      'id_input_default_keywords' ,       'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_KEYWORDS,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_default_logo_text',      $htd_pages['default_logo_text'],       OPTION_INCL_LOGO,      'id_input_default_logo_text' ,       'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_LOGO,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
                                       $contents            .= '                           <label for="id_input_home_page_text" class="col-xs-3 control-label">' . OPTION_HOME_PAGE . PHP_EOL ;
                                       $contents            .= '                           </label> ' . PHP_EOL ;
									   $contents            .= '                           <div class="col-xs-9">' . tep_draw_textarea_ckeditor('input_home_page_text', 'soft', '140', '40', $htd_pages['home_page_text'], 'id = "id_input_home_page_text" class="ckeditor"') . PHP_EOL ;
                                       $contents            .= '                           </div>' . PHP_EOL ;
                                       $contents            .= '                       </div>' . PHP_EOL ;											   
									   $contents            .= '                       <br />' . PHP_EOL;

//			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
//			                           $contents            .= '          <div class="panel-heading">' . TEXT_DEFAULT_METATAGS . '</div>' . PHP_EOL;
//			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;											   
    	                               $contents            .= '                       <table class="table table table-bordered table-responsive">' . PHP_EOL ;
			                           $contents            .= '                         <thead>' . PHP_EOL;	
			                           $contents            .= '                           <th>' .  TABLE_HEADING_DESCRIPTION . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                           <th>' .  TABLE_HEADING_ACTIVE . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                           <th>' .  TABLE_HEADING_DESCRIPTION . '</th>'. PHP_EOL; 
			                           $contents            .= '                           <th>' .  TABLE_HEADING_ACTIVE . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                         </thead>' . PHP_EOL;
									   
			                           $contents            .= '                         <tbody>' . PHP_EOL;
// row 1									   
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_INCL_GROUP . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('default_logo_append_group', '1', null, 'id_input_default_logo_append_group', $htd_pages['default_logo_append_group'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_INCL_CATEGORY . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   

									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('default_logo_append_category', '1', null, 'id_input_default_logo_append_category', $htd_pages['default_logo_append_category'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
			                           $contents            .= '                          </tr>' . PHP_EOL;										   
									   
// row 2									   
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_INCL_MANUFACTURER . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('default_logo_append_manufacturer', '1', null, 'id_input_default_logo_append_manufacturer', $htd_pages['default_logo_append_manufacturer'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_INCL_PRODUCT . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('default_logo_append_product', '1', null, 'id_input_default_logo_append_product', $htd_pages['default_logo_append_product'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
			                           $contents            .= '                          </tr>' . PHP_EOL;
									   									   
			                           $contents            .= '                         </tbody>' . PHP_EOL;									   
//		                               $contents            .= '              </div>' . PHP_EOL; // end div default stores	panel body
		                               $contents            .= '             </div>' . PHP_EOL; // end div panel 
		                               $contents            .= '                                </table>' . PHP_EOL; // end div default stores 	panel									   
									   
// metatags default
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_DEFAULT_METATAGS . '</div>' . PHP_EOL;									   

//			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;											   
    	                               $contents            .= '                       <table class="table table table-bordered table-responsive">' . PHP_EOL ;
			                           $contents            .= '                         <thead>' . PHP_EOL;	
			                           $contents            .= '                           <th>' .  TABLE_HEADING_DESCRIPTION . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                           <th>' .  TABLE_HEADING_ACTIVE . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                           <th>' .  TABLE_HEADING_DESCRIPTION . '</th>'. PHP_EOL; 
			                           $contents            .= '                           <th>' .  TABLE_HEADING_ACTIVE . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                           <th>' .  TABLE_HEADING_DESCRIPTION . '</th>'. PHP_EOL; 
			                           $contents            .= '                           <th>' .  TABLE_HEADING_ACTIVE . '</th>'. PHP_EOL; 										   
			                           $contents            .= '                         </thead>' . PHP_EOL;
									   
			                           $contents            .= '                         <tbody>' . PHP_EOL;
// row 1									   
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_META_GOOGLE . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_google', '1', null, 'id_input_meta_google', $htd_pages['meta_google'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_META_LANGUAGE . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   

									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_language', '1', null, 'id_input_meta_language', $htd_pages['meta_language'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_META_NOODP . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;											   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_noodp', '1', null, 'id_input_meta_noodp', $htd_pages['meta_noodp'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;											   
			                           $contents            .= '                          </tr>' . PHP_EOL;										   
									   
// row 2									   
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_META_NOYDIR . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_noydir', '1', null, 'id_input_meta_noydir', $htd_pages['meta_noydir'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_META_REPLYTO . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   

									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_replyto', '1', null, 'id_input_meta_replyto', $htd_pages['meta_replyto'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_META_REVISIT . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;											   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_revisit', '1', null, 'id_input_meta_revisit', $htd_pages['meta_revisit'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;											   
			                           $contents            .= '                          </tr>' . PHP_EOL;	
									   
// row 3								   
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_META_ROBOTS . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_robots', '1', null, 'id_input_meta_robots', $htd_pages['meta_robots'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_META_UNSPAM . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   

									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_unspam', '1', null, 'id_input_meta_unspam', $htd_pages['meta_unspam'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_META_CANONICAL . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;											   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_canonical', '1', null, 'id_input_meta_canonical', $htd_pages['meta_canonical'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;											   
			                           $contents            .= '                          </tr>' . PHP_EOL;										   
									   
// row 3								   
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_META_OG . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('meta_og', '1', null, 'id_input_meta_og', $htd_pages['meta_og'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;											   
			                           $contents            .= '                          </tr>' . PHP_EOL;												   
									   									   
			                           $contents            .= '                         </tbody>' . PHP_EOL;									   
//		                               $contents            .= '              </div>' . PHP_EOL; // end div default stores	panel body
		                               $contents            .= '             </div>' . PHP_EOL; // end div panel 									   
		                               $contents            .= '                                </table>' . PHP_EOL; // end div default stores 	panel												   
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $htd_pages['page_name'] . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . OPTION_INCL_TITLE . ' : ' . $htd_pages['default_title'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . OPTION_INCL_DESC . '  : ' . $htd_pages['default_description']  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . OPTION_INCL_KEYWORDS . ' : ' . $htd_pages['default_keywords']  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . OPTION_INCL_LOGO . ' : ' . $htd_pages['default_logo_text']  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove-circle', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets					
				} // end while while ($htd_pages = tep_db_fetch_arra
?>			   
			  </tbody>
		  </table>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $htd_pages_split->display_count($htd_pages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SEO_PAGES_DEFAULT); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $htd_pages_split->display_links($htd_pages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">
                   <?php echo tep_draw_bs_button(IMAGE_NEW_SEO_PAGE_DEFAULT, 'plus', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES_DEFAULT, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert' ) ) ; ?>		 
 			 </div>
            </div>
<?php
          }
?>			  
	  </table>
  
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>