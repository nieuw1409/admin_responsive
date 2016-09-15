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
  $shopsMetaInfo = array();

  /********************** RUN OPTIONS *********************/
/*  if (HEADER_TAGS_AUTO_ADD_PAGES == 'true') {
      $newfiles = AddMissingPages($languages_id, $languages);
  } else {
      $newfiles = GetFileList($languages_id);
  } 
       
  if (HEADER_TAGS_CHECK_TAGS == 'true')
   if (tep_not_null($missingTags = CheckForMissingTags()))
    $messageStack->add(ERROR_MISSING_TAGS . $missingTags, 'error');  

  if (HEADER_TAGS_DIABLE_PERMISSION_WARNING == 'false')
   if (GetPermissions(DIR_FS_CATALOG_IMAGES) != Getpermissions($filename))
    $messageStack->add(sprintf(ERROR_WRONG_PERMISSIONS, $filename, Getpermissions(DIR_WS_IMAGES)), 'error');    
  /* end run options */ 

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
        // use headertags insert function
		AddMissingPages($languages_id, $languages);
        tep_redirect(tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES));
        break;		
      case 'save':
        $ht_pages_id = tep_db_prepare_input($HTTP_GET_VARS['htpID']);
        $ht_pages_language = $HTTP_GET_VARS['LangID'];		
        $ht_pages_title = tep_db_prepare_input($HTTP_POST_VARS['input_page_title']);
        $ht_pages_description = tep_db_prepare_input($HTTP_POST_VARS['input_page_description']);
        $ht_pages_keywords = tep_db_prepare_input($HTTP_POST_VARS['input_page_keywords']);
        $ht_pages_logo = tep_db_prepare_input($HTTP_POST_VARS['input_page_logo']);

        $sql_data_array = array('page_title' => $ht_pages_title, 
                                'page_description' => $ht_pages_description,
                                'page_keywords' => $ht_pages_keywords, 
                                'page_logo' => $ht_pages_logo,  
                                'append_category' =>  $HTTP_POST_VARS['append_category'],
                                'sortorder_category' =>  $HTTP_POST_VARS['sortorder_category'],

                                'append_manufacturer' =>  $HTTP_POST_VARS['append_manufacturer'],
                                'sortorder_manufacturer' =>  $HTTP_POST_VARS['sortorder_manufacturer'],

                                'append_model' =>  $HTTP_POST_VARS['append_model'],
                                'sortorder_model' =>  $HTTP_POST_VARS['sortorder_model'],

                                'append_product' =>  $HTTP_POST_VARS['append_product'],
                                'sortorder_product' =>  $HTTP_POST_VARS['sortorder_product'],								

                                'append_root' =>  $HTTP_POST_VARS['append_root'],
                                'sortorder_root' =>  $HTTP_POST_VARS['sortorder_root'],

                                'append_default_title' =>  $HTTP_POST_VARS['append_default_title'],
                                'sortorder_title' =>  $HTTP_POST_VARS['sortorder_title'],

                                'append_default_description' =>  $HTTP_POST_VARS['append_default_description'],
                                'sortorder_description' =>  $HTTP_POST_VARS['sortorder_description'],

                                'append_default_keywords' =>  $HTTP_POST_VARS['append_default_keywords'],
                                'sortorder_keywords' =>  $HTTP_POST_VARS['sortorder_keywords'],

                                'append_default_logo' =>  $HTTP_POST_VARS['append_default_logo'],
                                'sortorder_logo' =>  $HTTP_POST_VARS['sortorder_logo'],
								
                                'language_id' =>  $ht_pages_language,
								'stores_id' => tep_db_prepare_input($multi_stores_id ) 								
								
								) ;                                         
                                      

                        tep_db_perform(TABLE_HEADERTAGS, $sql_data_array, 'update', "page_name = '" . $ht_pages_id . "' 
						              and language_id = '" . (int)$ht_pages_language . "' and stores_id = '" . (int)$multi_stores_id . "' "); // multi stores		

        tep_redirect(tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $ht_pages_id));
        break;
      case 'deleteconfirm':
        $ht_pages_id          = tep_db_prepare_input($HTTP_GET_VARS['htpID']);
        $ht_pages_language_id = tep_db_prepare_input($HTTP_GET_VARS['LangID']);		
 
        tep_db_query("delete from " . TABLE_HEADERTAGS . " where page_name = '" . $ht_pages_id . "' and language_id = '" . (int)$ht_pages_language_id . "' and stores_id = '" . (int)$multi_stores_id . "'");

        tep_redirect(tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&name=' . $ht_pages_id));
        break;

      case 'insert_pseudo':
		  $psedudoPage = tep_db_prepare_input($_POST['pseudo_page_name']);
		  if (strpos($psedudoPage, ".php") === FALSE || strpos($psedudoPage, "?") === FALSE || strpos($psedudoPage, "=") === FALSE)
		  {
			 $messageStack->add(sprintf(ERROR_INVALID_PSEUDO_FORMAT, $psedudoPage), 'error');
		  }
		  else
		  {
			$parts = explode("?", $psedudoPage);
			$baseFiles = (array)GetBaseFiles();
			if (in_array($parts[0], $baseFiles)) //don't allow pseudo pages for base files
			{
			   $messageStack->add(sprintf(ERROR_INVALID_PSEUDO_PAGE, $parts[0]), 'error');
			}

			else if (($result = FileNotUsingHeaderTags($parts[0])) === 'FALSE' || IsTemplate())
			{
				$pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . $psedudoPage . "' and language_id = '" . (int)$languages_id . "' and stores_id =   '" . (int)$multi_stores_id . "' "); // multi stores
				$pageTags = tep_db_fetch_array($pageTags_query);

				if (tep_db_num_rows($pageTags_query) == 0)
				{
				   $filenameInc = DIR_FS_CATALOG. DIR_WS_INCLUDES . 'header_tags.php';
				   $fp = @file($filenameInc);  
				  
				   if (AddedToHeaderTagsIncludesFilePseudo($psedudoPage, $fp, $languages_id))
				   {
					  if (WriteHeaderTagsFile($filenameInc, $fp))
					  {             
						$pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . $psedudoPage . "' and language_id = '" . (int)$languages_id . "' and stores_id = '" . $multi_stores_id . "' "); // multi stores
						if (tep_db_num_rows($pageTags_query) == 0)
						{
						  for ($i=0; $i < count($languages); ++$i) 
						  {
							 $sql_data_array = array('page_name' => $psedudoPage,
													 'page_title' => '', 
													 'page_description' => '',
													 'page_keywords' => '', 
													 'page_logo' => '', 
													 'page_logo_1' => '', 
													 'page_logo_2' => '', 
													 'page_logo_3' => '', 
													 'page_logo_4' => '',                                                  
													 'append_default_title' => 0,
													 'append_default_description' => 0,
													 'append_default_keywords' => 0,
													 'append_default_logo' => 0,
													 'append_category' =>  0,
													 'append_manufacturer' =>  0,
													 'append_model' =>  0,
													 'append_product' =>  0,
													 'append_root' =>  1,
													 'sortorder_title' =>  0,
													 'sortorder_description' =>  0,
													 'sortorder_keywords' =>  0,
													 'sortorder_logo' =>  0,
													 'sortorder_logo_1' =>  0,
													 'sortorder_logo_2' =>  0,
													 'sortorder_logo_3' =>  0,
													 'sortorder_logo_4' =>  0,                                                 
													 'sortorder_category' =>  0,
													 'sortorder_manufacturer' =>  0,  
													 'sortorder_model' =>  0, 
													 'sortorder_product' =>  0,                                    
													 'sortorder_root' =>  1,                                    
													 'sortorder_root_1' =>  0,                                    
													 'sortorder_root_2' =>  0,                                    
													 'sortorder_root_3' =>  0,                                    
													 'sortorder_root_4' =>  0,                                                                                   
													 'language_id' => $languages[$i]['id'],
													 'stores_id' => $multi_stores_id );                             
											  
							 tep_db_perform(TABLE_HEADERTAGS, $sql_data_array);
							 
							 if (HEADER_TAGS_ENABLE_CACHE != 'None') {
								 ResetCache_HeaderTags($psedudoPage, '', true);
							 }
						  }
						  $newfiles = GetFileList($languages_id);
						}
					  }
				   }
				}
				else
				  $messageStack->add(sprintf(ERROR_DUPLICATE_PAGE, $psedudoPage), 'error'); 
			}
			else if ($result != 'TRUE')
			 $messageStack->add(sprintf(ERROR_NOT_USING_HEADER_TAGS, $parts[0]), 'error');
		  } 	  
        break;	  
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE  ; ?></h1> 
            <p class="col-xs-12 col-md-6"><?php echo TEXT_INFORMATION_PAGES ;  ?></p> 			
            <div class="clearfix"></div>
          </div><!-- page-header-->

            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo TABLE_HEADING_HT_SEO_PAGES_NAME; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_HT_SEO_PAGES_LANGUAGE; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_HT_SEO_PAGES_TITLE; ?></th>					   
                   <th class="text-center"><?php echo TABLE_HEADING_HT_SEO_PAGES_DESCRIPTION; ?></th>					   
                   <th>                    <?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
//                 $ht_pages_query_raw = "select page_name, page_title, page_description from " . TABLE_HEADERTAGS . " where stores_id=" . $multi_stores_id . " order by page_name";
                 $ht_pages_query_raw = "select page_name, page_title, page_description, page_keywords, page_logo, language_id,
				                             append_category, append_manufacturer, append_model, append_product, append_root, 
											 append_default_title, append_default_description, append_default_keywords, append_default_logo, 
											 sortorder_category, sortorder_manufacturer, sortorder_model, sortorder_product, sortorder_root, 
											 sortorder_title, sortorder_description, sortorder_keywords, sortorder_logo from " . TABLE_HEADERTAGS . " 
				                        where stores_id = '" . (int)$multi_stores_id . "' order by page_name" ; // multi stores
				 
                 $ht_pages_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $ht_pages_query_raw, $ht_pages_query_numrows);
                 $ht_pages_query = tep_db_query($ht_pages_query_raw);
                 while ($ht_pages = tep_db_fetch_array($ht_pages_query)) {
                   if ((!isset($HTTP_GET_VARS['htpID']) || (isset($HTTP_GET_VARS['htpID']) && ($HTTP_GET_VARS['htpID'] == $ht_pages['page_name']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                      $cInfo = new objectInfo($ht_pages);
                   }

                   if (isset($cInfo) && is_object($cInfo) && ($ht_pages['page_name'] == $cInfo->page_name)) {
                      echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $cInfo->page_name . '&action=edit') . '\'">' . "\n";
                   } else {
                      echo '<tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $ht_pages['page_name']) . '\'">' . "\n";
                   }
?>
                               <td><?php echo $ht_pages['page_name']  ; ?></td> 
                               <td class="text-center"><?php echo tep_get_language_name( $ht_pages['language_id']  )  ; ?></td>							   
                               <td class="text-center"><?php echo $ht_pages['page_title']  ; ?></td>
                               <td class="text-center"><?php echo $ht_pages['page_description']  ; ?></td>							   
                               <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $ht_pages['page_name'] . '&LangID=' . $ht_pages['language_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $ht_pages['page_name'] . '&LangID=' . $ht_pages['language_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $ht_pages['page_name'] . '&LangID=' . $ht_pages['language_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>						
               </tr>	
<?php
                  if (isset($cInfo) && is_object($cInfo) && ($ht_pages['page_name'] == $cInfo->page_name) && ($ht_pages['language_id'] == $HTTP_GET_VARS['LangID']) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_SEO_PAGE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_bs_form('htp_seo_delete', FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $HTTP_GET_VARS['htpID'] . '&LangID=' . $HTTP_GET_VARS['LangID'] . '&action=deleteconfirm', 'post', 'role=form') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_HEADING_DELETE_INTRO_SEO_PAGE . '<br />' .  $ht_pages['page_name']  . '<br />'  . 
									                                                                                                        $ht_pages['page_title'] . '<br /> ' .
																																			'</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $HTTP_GET_VARS['htpID'] . '&LangID=' . $HTTP_GET_VARS['LangID'] . '&action=deleteconfirm'), null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $cInfo->page_name), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('countries', FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&htpID=' . $ht_pages['page_name']  . '&LangID=' . $ht_pages['language_id'] . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_countries') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_page_title',       $ht_pages['page_title'] ,        OPTION_INCL_TITLE,       'id_input_page_title' ,        'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_TITLE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_page_description',       $ht_pages['page_description'] ,        OPTION_INCL_DESC,       'id_input_page_description' ,        'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_DESC,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_page_keywords',      $ht_pages['page_keywords'] ,       OPTION_INCL_KEYWORDS,      'id_input_page_keywords' ,       'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_KEYWORDS,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('input_page_logo',      $ht_pages['page_logo'] ,       OPTION_INCL_LOGO,      'id_input_page_logo' ,       'col-xs-3', 'col-xs-9', 'left', OPTION_INCL_LOGO,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;												   
									   $contents            .= '                       <br />' . PHP_EOL;

//			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
//			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
//			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;											   
    	                               $contents            .= '                       <table class="table table table-bordered table-responsive">' . PHP_EOL ;
			                           $contents            .= '                         <thead>' . PHP_EOL;	
			                           $contents            .= '                           <th>' .  TABLE_HEADING_DESCRIPTION . '</th>'. PHP_EOL; 									  
			                           $contents            .= '                           <th>' .  TABLE_HEADING_SORTORDER . '</th>'. PHP_EOL; 
			                           $contents            .= '                           <th>' .  TABLE_HEADING_ACTIVE . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                           <th>' .  TABLE_HEADING_DESCRIPTION . '</th>'. PHP_EOL; 
			                           $contents            .= '                           <th>' .  TABLE_HEADING_SORTORDER . '</th>'. PHP_EOL; 
			                           $contents            .= '                           <th>' .  TABLE_HEADING_ACTIVE . '</th>'. PHP_EOL; 									   
			                           $contents            .= '                         </thead>' . PHP_EOL;
									   
			                           $contents            .= '                         <tbody>' . PHP_EOL;

// row 1									   
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_INCL_CATEGORY . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_category',      $ht_pages['sortorder_category'],       '',      'id_input_sortorder_category' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_category', '1', null, 'id_append_category', $ht_pages['append_category'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_INCL_TITLE . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                                <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_title',      $ht_pages['sortorder_title'],       '',      'id_input_sortorder_title' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                                </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_default_title', '1', null, 'id_append_default_title', $ht_pages['append_default_title'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
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
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_manufacturer',      $ht_pages['sortorder_manufacturer'],       '',      'id_input_sortorder_manufacturer' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_manufacturer', '1', null, 'id_append_manufacturer', $ht_pages['append_manufacturer'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_INCL_DESC . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                                <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_description',      $ht_pages['sortorder_description'],       '',      'id_input_sortorder_description' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                                </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_default_description', '1', null, 'id_append_default_description', $ht_pages['append_default_description'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
			                           $contents            .= '                          </tr>' . PHP_EOL;
									   
// row 3
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_INCL_MODEL . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_model',      $ht_pages['sortorder_model'],       '',      'id_input_sortorder_model' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_model', '1', null, 'id_append_model', $ht_pages['append_model'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_INCL_KEYWORDS . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                                <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_keywords',      $ht_pages['sortorder_keywords'],       '',      'id_input_sortorder_keywords' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                                </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_default_keywords', '1', null, 'id_append_default_keywords', $ht_pages['append_default_keywords'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
			                           $contents            .= '                          </tr>' . PHP_EOL;
									   
// row 4
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_INCL_PRODUCT . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_product',      $ht_pages['sortorder_product'],       '',      'id_input_sortorder_product' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_product', '1', null, 'id_append_product', $ht_pages['append_product'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                                <p>' .  OPTION_INCL_LOGO . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                                <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_logo',     $ht_pages['sortorder_logo'],       '',      'id_input_sortorder_logo' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                                </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_default_logo', '1', null, 'id_append_default_logo', $ht_pages['append_default_logo'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;										   
			                           $contents            .= '                          </tr>' . PHP_EOL;									 
									   
// row 5
			                           $contents            .= '                           <tr>' . PHP_EOL;	
			                           $contents            .= '                             <td>' . PHP_EOL;											   
			                           $contents            .= '                               <p>' .  OPTION_INCL_ROOT . '</p>'. PHP_EOL; 									  
			                           $contents            .= '                             </td>' . PHP_EOL;									   
			                           $contents            .= '                             <td>' . PHP_EOL;											   
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;										   
									   $contents            .= '                               ' . tep_draw_bs_input_field('sortorder_root',      $ht_pages['sortorder_root'],       '',      'id_input_sortorder_root' ,  'col-xs-1', null, 'left' ) . PHP_EOL;										   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;
									   $contents            .= '                             <td>' . PHP_EOL;
                                       $contents            .= '                               <div class="form-group form-inline">' . PHP_EOL ;											   
									   $contents            .=                                    tep_bs_checkbox_field('append_root', '1', null, 'id_append_root', $ht_pages['append_root'], 'checkbox checkbox-success'  ) . PHP_EOL  ;									   
                                       $contents            .= '                               </div>' . PHP_EOL ;										  
			                           $contents            .= '                             </td>' . PHP_EOL;	
									   									   
			                           $contents            .= '                          </tr>' . PHP_EOL;											   
			                           $contents            .= '                         </tbody>' . PHP_EOL;									   

//		                               $contents            .= '              </div>' . PHP_EOL; // end div default stores	panel body
		                               $contents            .= '                                </table>' . PHP_EOL; // end div default stores 	panel												   
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $ht_pages['page_name'] . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . OPTION_INCL_TITLE . ' : ' . $ht_pages['page_title'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . OPTION_INCL_DESC . '  : ' . $ht_pages['page_description'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . OPTION_INCL_KEYWORDS . ' : ' . $ht_pages['page_keywords'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . OPTION_INCL_LOGO . ' : ' . $ht_pages['page_logo'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
				} // end while while ($ht_pages = tep_db_fetch_arra
?>			   
			  </tbody>
		  </table>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $ht_pages_split->display_count($ht_pages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SEO_PAGES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $ht_pages_split->display_links($ht_pages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_SEO_PAGE, 'plus', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert')) ; ?>
                   <?php echo tep_draw_bs_button(IMAGE_NEW_SEO_PSEUDO_PAGE, 'plus', null,'data-toggle="modal" data-target="#new_pseudo"') ; ?>				   
 			 </div>	 
            </div>
<?php
          }
?>			  
	  </table>
        <div class="modal fade"  id="new_pseudo" role="dialog" aria-labelledby="new_pseudo" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_pseudo', FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert_pseudo', 'post', 'class="form-horizontal" role="form"', 'id_new_pseudo') ; ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_PSEUDO_PAGE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_new_pseudo            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_new_pseudo            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_PSEUDO_PAGE . '</div>' . PHP_EOL;
			                           $contents_new_pseudo            .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_new_pseudo            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_new_pseudo            .= '                           ' . tep_draw_bs_input_field('pseudo_page_name',       null,        TEXT_NEW_PSEUDO_PAGE,       'id_input_new_pseudo_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NEW_PSEUDO_PAGE,       '', true ) . PHP_EOL;	
                                       $contents_new_pseudo            .= '                       </div>' . PHP_EOL ;	
								   
									   $contents_new_pseudo            .= '                       <br />' . PHP_EOL;	
									   $contents_new_pseudo            .= '                       <p>' . TEXT_PSEUDO_PAGE_NAME_NOTE . '</p>'.  PHP_EOL;									   
                                       
		                               $contents_new_pseudo_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_new_pseudo_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents_new_pseudo . $contents_new_pseudo_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_HEADER_TAGS_SEO_PAGES, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_pseudo page -->	  

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
