<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
// multi stores
  // check if there more then 1 store active
  $stores_query = tep_db_query( "select stores_id from " . TABLE_STORES ) ;
  if ( tep_db_num_rows( $stores_query ) > 1 ) $stores_multi_present = 'true' ;  

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  
// multi stores
  require(DIR_WS_CLASSES . 'class.get.image.php');  
  
// multi stores get DIR_FS_CATALOG_IMAGES from default store this is used for copying the images to the main images directory
   $stores_query_imgage_dir = tep_db_query("select stores_config_table from " . TABLE_STORES . "  where stores_id = 1");
   $config_table_default_store = tep_db_fetch_array($stores_query_imgage_dir) ;
   $config_table_img_prod = $config_table_default_store[ 'stores_config_table' ] ; 
   $get_config_image_directory_query = tep_db_query("select configuration_value from " . $config_table_img_prod . " where configuration_key = 'DIR_FS_CATALOG_IMAGES'"); // get location images
   $config_image_directory_query_prod= tep_db_fetch_array($get_config_image_directory_query); 	   
   $default_store_images_directory = $config_image_directory_query_prod[ 'configuration_value' ] ;
// eof multi stores  

// bof multi stores
  $manufacturers_to_stores = '@,';
  if ( $HTTP_POST_VARS['stores_manufacturers'] ) { // if any of the checkboxes are checked
//    foreach($HTTP_POST_VARS['stores_manufacturers'] as $val) {
    $_stores = array_keys( $HTTP_POST_VARS['stores_manufacturers'] ) ;    
    foreach($_stores as $val) {	
        $manufacturers_to_stores .= tep_db_prepare_input($val).','; 
    } // end foreach
  }
  $manufacturers_to_stores = substr($manufacturers_to_stores,0,strlen($manufacturers_to_stores)-1); // remove last comma
// eof multi stores	  

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['mID'])) $manufacturers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $manufacturers_name = tep_db_prepare_input($HTTP_POST_VARS['manufacturers_name']);

        $sql_data_array = array('manufacturers_name' => $manufacturers_name,
		                        'manufacturers_to_stores' =>  $manufacturers_to_stores);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

          tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array);
          $manufacturers_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()' );

          $sql_data_array = array_merge((array)$sql_data_array, (array)$update_sql_data);

          tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        $manufacturers_image = new upload('manufacturers_image');
// bof multi stores		
//        $manufacturers_image->set_destination(DIR_FS_CATALOG_IMAGES); 
        $manufacturers_image->set_destination(DIR_FS_CATALOG_IMAGES . DIR_FS_MANUFACTURERS_IMAGES); 		

        if ($manufacturers_image->parse() && $manufacturers_image->save()) {
          tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . DIR_FS_MANUFACTURERS_IMAGES . tep_db_input($manufacturers_image->filename) . "' where manufacturers_id = '" . (int)$manufacturers_id . "'");
        }
		$stores_manufacturers_image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id  . "'");
		$stores_image_manufacturers = tep_db_fetch_array($stores_manufacturers_image) ;
        tep_multi_stores_images( $stores_image_manufacturers[ 'manufacturers_image' ], $HTTP_POST_VARS['stores_manufacturers'] , $default_store_images_directory, '', DIR_FS_MANUFACTURERS_IMAGES )    ; 
// eof multi stores		

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $manufacturers_url_array = $HTTP_POST_VARS['manufacturers_url'];
          /*** Begin Header Tags SEO 327
          $manufacturers_htc_title_array = $HTTP_POST_VARS['manufacturers_htc_title_tag'];
          $manufacturers_htc_desc_array = $HTTP_POST_VARS['manufacturers_htc_desc_tag'];
          $manufacturers_htc_keywords_array = $HTTP_POST_VARS['manufacturers_htc_keywords_tag'];
          $manufacturers_htc_description_array = $HTTP_POST_VARS['manufacturers_htc_description'];
          /*** End Header Tags SEO 327***/
          /*** Begin Header Tags SEO 331***/
          $manufacturers_htc_title_array = $HTTP_POST_VARS['manufacturers_htc_title_tag'];
          $manufacturers_htc_title_alt_array = $HTTP_POST_VARS['manufacturers_htc_title_tag_alt'];
          $manufacturers_htc_title_url_array = $HTTP_POST_VARS['manufacturers_htc_title_tag_url'];
          $manufacturers_htc_desc_array = $HTTP_POST_VARS['manufacturers_htc_desc_tag'];
          $manufacturers_htc_keywords_array = $HTTP_POST_VARS['manufacturers_htc_keywords_tag'];
          $manufacturers_htc_description_array = $HTTP_POST_VARS['manufacturers_htc_description'];
          $manufacturers_htc_breadcrumb_array = $HTTP_POST_VARS['manufacturers_htc_breadcrumb_text'];
          /*** End Header Tags SEO 331 ***/		  
          $language_id = $languages[$i]['id'];

         /*** Begin Header Tags SEO 331 ***/
          $sql_data_array = array('manufacturers_url' => tep_db_prepare_input($manufacturers_url_array[$language_id]),
           'manufacturers_htc_title_tag' => (tep_not_null($manufacturers_htc_title_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_title_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_title_tag_alt' => (tep_not_null($manufacturers_htc_title_alt_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_title_alt_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_title_tag_url' => (tep_not_null($manufacturers_htc_title_alt_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_title_url_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_desc_tag' => (tep_not_null($manufacturers_htc_desc_array[$language_id]) ? tep_db_prepare_input($manufacturers_htc_desc_array[$language_id]) : $manufacturers_name),
           'manufacturers_htc_keywords_tag' => (tep_not_null($manufacturers_htc_keywords_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_keywords_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_description' => tep_db_prepare_input($manufacturers_htc_description_array[$language_id]),
           'manufacturers_htc_breadcrumb_text' => (tep_not_null($manufacturers_htc_breadcrumb_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_breadcrumb_array[$language_id])) : strip_tags($manufacturers_name)));
          /*** End Header Tags SEO 331 ***/		  
		  /* 327
           'manufacturers_htc_title_tag' => (tep_not_null($manufacturers_htc_title_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_title_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_desc_tag' => (tep_not_null($manufacturers_htc_desc_array[$language_id]) ? tep_db_prepare_input($manufacturers_htc_desc_array[$language_id]) : $manufacturers_name),
           'manufacturers_htc_keywords_tag' => (tep_not_null($manufacturers_htc_keywords_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_keywords_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_description' => tep_db_prepare_input($manufacturers_htc_description_array[$language_id]));
          /*** End Header Tags SEO 327***/
          
          if ($action == 'insert') {
            $insert_sql_data = array('manufacturers_id' => $manufacturers_id,
                                     'languages_id' => $language_id);

            $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

            tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "' and languages_id = '" . (int)$language_id . "'");
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('manufacturers');
        }

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'm_' . $manufacturers_id);
        }
        /*** End Header Tags SEO ***/
          
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'mID=' . $manufacturers_id));
        break;
      case 'deleteconfirm':
        $manufacturers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $manufacturer_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
          $manufacturer = tep_db_fetch_array($manufacturer_query);

// bof multi stores		  
//          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['manufacturers_image'];
//          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['manufacturers_image'];
//          if (file_exists($image_location)) {
          if (file_exists($default_store_images_directory . $manufacturer['manufacturers_image'])) {
             @unlink($default_store_images_directory . $manufacturer['manufacturers_image']);		
		     tep_multi_stores_images(  $manufacturer['manufacturers_image'], '', $default_store_images_directory, '', '' )    ; 
          }
// eof multi stores
        }

        tep_db_query("delete from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
        tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturers_id . "'");

        if (isset($HTTP_POST_VARS['delete_products']) && ($HTTP_POST_VARS['delete_products'] == 'on')) {
          $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
          while ($products = tep_db_fetch_array($products_query)) {
            tep_remove_product($products['products_id']);
          }
        } else {
          tep_db_query("update " . TABLE_PRODUCTS . " set manufacturers_id = '' where manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('manufacturers');
        }

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'm_' . $manufacturers_id);
        }
        /*** End Header Tags SEO ***/
                
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page']));
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
                   <th>                     <?php echo TABLE_HEADING_MANUFACTURERS; ?></th> 				    			   
                   <th>                     <?php echo TABLE_HEADING_IMAGE; ?></th>				   
                   <th class="text-right" ><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
				<?php
				  /*** Begin Header Tags SEO 327 ***/
				//  $manufacturers_query_raw = "select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, m.date_added, m.last_modified, mi.manufacturers_htc_title_tag,                                                                         m.manufacturers_to_stores from " . TABLE_MANUFACTURERS . " m LEFT JOIN " .  TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '".$languages_id ."' order by m.manufacturers_name";
				  /*** Begin Header Tags SEO 331 ***/
				  $manufacturers_query_raw =   "select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, m.date_added, m.last_modified, mi.manufacturers_htc_title_tag, mi.manufacturers_htc_title_tag_alt, mi.manufacturers_htc_title_tag_url, m.manufacturers_to_stores from " . TABLE_MANUFACTURERS . " m LEFT JOIN " .  TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '".$languages_id ."' order by m.manufacturers_name";
					
				/*** End Header Tags SEO ***/
				  $manufacturers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_query_raw, $manufacturers_query_numrows);
				  $manufacturers_query = tep_db_query($manufacturers_query_raw);
			      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
					if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
					  $manufacturer_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers['manufacturers_id'] . "'");
					  $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);

					  $mInfo_array = array_merge((array)$manufacturers, (array)$manufacturer_products);
					  $mInfo = new objectInfo($mInfo_array);
					}

					if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
					  echo '              <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
					} else {
					  echo '              <tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '\'">' . "\n";
					}
?>
                    <td ><?php echo $manufacturers['manufacturers_name']; ?></td>
                    <td ><?php echo tep_info_image($manufacturers['manufacturers_image'], $manufacturers['manufacturers_name'], 25, 25); ?></td>					
                    <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				    </td>	              
				 </tr>
<?php
                  if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_HEADING_DELETE_MANUFACTURER . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $manufacturers['manufacturers_name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
									// BOF multi stores
										$stores_group_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
										$header = false;
										if (!tep_db_num_rows($stores_group_query) > 0) {
										  $messageStack->add_session(ERROR_ALL_STORES_DELETED, 'error');
									   } else {
										 while ($stores_stores = tep_db_fetch_array($stores_group_query)) {
										   $_stores_name[] = $stores_stores;
										 }
									   }									
									   $manu_id              = $mInfo->manufacturers_id ; // used in tab_1 to 3
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_HEADING_EDIT_MANUFACTURER . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=save', 'post', 'role="form" enctype="multipart/form-data"', 'id_edit_manufacturers') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('manufacturers_name',       $mInfo->manufacturers_name,        TEXT_MANUFACTURERS_NAME,       'id_input_manufacturers_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_MANUFACTURERS_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
                                       $contents            .= '                           ' . tep_draw_bs_file_field('manufacturers_image', $mInfo->manufacturers_image, TEXT_MANUFACTURERS_IMAGE, 'id_input_manufacturers_image' ,'col-xs-3', 'col-xs-9', 'left', TEXT_MANUFACTURERS_IMAGE )	 . PHP_EOL;									   

                                       $contents            .= '                           <figure>' . tep_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<figcaption>' . $mInfo->manufacturers_image.'</figcaption></figure>' . PHP_EOL;
                                       $contents            .= '                       </div>' . PHP_EOL;	
/*	                                   $contents_edit_manu_tab1    = '' ;
                                       include( DIR_WS_MODULES . 'manufacturers_edit_tab_1.php' ) ; 									   
                           								   
									   $contents            .=   $contents_edit_manu_tab1 . PHP_EOL;	
*/									   
									   $contents            .= '                       <br />' . PHP_EOL;									   
									   
									   $contents_manufacturers_heading .= '       <div class="panel panel-primary">' . PHP_EOL ;
									   $contents_manufacturers_heading .= '          <div class="panel-heading">' . TEXT_HEADING_EDIT_MANUFACTURER . '</div>' . PHP_EOL;
									   $contents_manufacturers_heading .= '          <div class="panel-body">' . PHP_EOL;			
									   $contents_manufacturers_heading .= '                        <div class="col-xs-12 col-xs-12 col-md-12">' . PHP_EOL;	   

									   $contents_edit_manu_tab1    = '' ;
									   include( DIR_WS_MODULES . 'manufacturers_edit_tab_1.php' ) ;	

									   $contents_edit_manu_tab2    = '' ;
									   include( DIR_WS_MODULES . 'manufacturers_edit_tab_2.php' ) ;	  
									  
									   $contents_edit_manu_tab3    = '' ;
									   include( DIR_WS_MODULES . 'manufacturers_edit_tab_3.php' ) ;	 	  
									  
									  $contents_manufacturers_footer .= '            </div>' . PHP_EOL;	// class="col-xs-12 col

									  $contents_manufacturers_footer .= '                 <br />' . PHP_EOL;	 
									  
									  $contents_manufacturers_footer .= '       </div>' . PHP_EOL; // end div 	panel body
									  $contents_manufacturers_footer .= '     </div>' . PHP_EOL; // end div 	panel
									  
									  
									  $contents_manufacturers_edit .=   $contents_manufacturers_heading . PHP_EOL ;	  
									  
									  $contents_manufacturers_edit .=  '<div role="tabpanel" id="tab_edit_manufacturer">' . PHP_EOL;
									  $contents_manufacturers_edit .=  '  <!-- Nav tabs edit tab_edit_manufacturer -->' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_manufacturer">' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '    <li  id="tab_edit_manufacturer" class="active"><a href="#tab_edit_manu_meta_1"     aria-controls="tab_edit_manufacturers_meta_1"          role="tab" data-toggle="tab">' . TEXT_MANU_TABS_01 . '</a></li>'  . PHP_EOL;	  
									  $contents_manufacturers_edit .=  '    <li  id="tab_edit_manufacturer">               <a href="#tab_edit_manu_meta_2"     aria-controls="tab_edit_manufacturers_meta_2"          role="tab" data-toggle="tab">' . TEXT_MANU_TABS_02 . '</a></li>'  . PHP_EOL;	
  									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
									     $contents_manufacturers_edit .=  '    <li  id="tab_edit_manufacturer">               <a href="#tab_edit_manu_stores"     aria-controls="tab_edit_manufacturers_stores"          role="tab" data-toggle="tab">' . TEXT_MANU_TABS_03 . '</a></li>'  . PHP_EOL;	  
                                      }										 
									  $contents_manufacturers_edit .=  '  </ul>'  . PHP_EOL;

									  $contents_manufacturers_edit .=  '  <!-- Tab panes -->' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '  <div  id="tab_edit_manufacturer" class="tab-content">'  . PHP_EOL; 
									  $contents_manufacturers_edit .=  '    <div role="tabpanel" class="tab-pane active" id="tab_edit_manu_meta_1">'        . $contents_edit_manu_tab1 . '</div>' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_manu_meta_2">'        . $contents_edit_manu_tab2 . '</div>' . PHP_EOL ;
                                      
									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  									  
									      $contents_manufacturers_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_manu_stores">'        . $contents_edit_manu_tab3 . '</div>' . PHP_EOL ;			
									  }
									  $contents_manufacturers_edit .=  '  </div>' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '</div>' . PHP_EOL ;	
									  $contents_manufacturers_edit .=   $contents_manufacturers_footer . PHP_EOL ;	
									  
									  $contents            .= $contents_manufacturers_edit ;
									
                                      $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id), null, null, 'btn-default text-danger') . PHP_EOL;	
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
									   
                                      break;										  
		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $mInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_NAME . '<br />' . $mInfo->manufacturers_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_CODE_2 . '  ' . $mInfo->manufacturers_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_CODE_3 . '  ' . $mInfo->manufacturers_name. PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ADDRESS_FORMAT . '  ' . $mInfo->manufacturers_name. PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_COUNTRIES ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
                  } // while
?>			   
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $manufacturers_split->display_count($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $manufacturers_split->display_links($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_INSERT, 'plus', null,'data-toggle="modal" data-target="#new_manufacturer"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  
	  </table>				
	  
        <div class="modal fade"  id="new_manufacturer" role="dialog" aria-labelledby="new_manufacturer" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo //tep_draw_form('countries', FILENAME_COUNTRIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert') 
				           tep_draw_bs_form('manufactur_new', FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=insert', 'post', 'role="form" enctype="multipart/form-data"', 'id_new_manufacturers')
                ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_HEADING_NEW_MANUFACTURER; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
				// BOF multi stores
					$stores_group_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
					$header = false;
					if (!tep_db_num_rows($stores_group_query) > 0) {
					  $messageStack->add_session(ERROR_ALL_STORES_DELETED, 'error');
				   } else {
					 while ($stores_stores = tep_db_fetch_array($stores_group_query)) {
					   $_stores_name[] = $stores_stores;
					 }
				   }
									   $manu_id              = null ; // used in tab_1 to 3				   
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_HEADING_NEW_MANUFACTURER . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('manufacturers_name',       null,        TEXT_MANUFACTURERS_NAME,       'id_input_manufacturers_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_MANUFACTURERS_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
                                       $contents            .= '                           ' . tep_draw_bs_file_field('manufacturers_image', null, TEXT_MANUFACTURERS_IMAGE, 'id_input_manufacturers_image' ,'col-xs-3', 'col-xs-9', 'left', TEXT_MANUFACTURERS_IMAGE )	 . PHP_EOL;									   

                                       $contents            .= '                           <figure>' . tep_info_image(null, null, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<figcaption>' . '' .'</figcaption></figure>' . PHP_EOL;
                                       $contents            .= '                       </div>' . PHP_EOL;	
									   $contents            .= '                       <br />' . PHP_EOL;									   
									   
									   $contents_manufacturers_heading .= '       <div class="panel panel-primary">' . PHP_EOL ;
									   $contents_manufacturers_heading .= '          <div class="panel-heading">' . TEXT_HEADING_NEW_MANUFACTURER . '</div>' . PHP_EOL;
									   $contents_manufacturers_heading .= '          <div class="panel-body">' . PHP_EOL;			
									   $contents_manufacturers_heading .= '                        <div class="col-xs-12 col-xs-12 col-md-12">' . PHP_EOL;	   

									   $contents_edit_manu_tab1    = '' ;
									   include( DIR_WS_MODULES . 'manufacturers_edit_tab_1.php' ) ;	

									   $contents_edit_manu_tab2    = '' ;
									   include( DIR_WS_MODULES . 'manufacturers_edit_tab_2.php' ) ;	  
									  
									   $contents_edit_manu_tab3    = '' ;
									   include( DIR_WS_MODULES . 'manufacturers_edit_tab_3.php' ) ;	 	  
									  
									  $contents_manufacturers_footer .= '            </div>' . PHP_EOL;	// class="col-xs-12 col

									  $contents_manufacturers_footer .= '                 <br />' . PHP_EOL;	 
									  
									  $contents_manufacturers_footer .= '       </div>' . PHP_EOL; // end div 	panel body
									  $contents_manufacturers_footer .= '     </div>' . PHP_EOL; // end div 	panel
									  
									  
									  $contents_manufacturers_edit .=   $contents_manufacturers_heading . PHP_EOL ;	  
									  
									  $contents_manufacturers_edit .=  '<div role="tabpanel" id="tab_edit_manufacturer">' . PHP_EOL;
									  $contents_manufacturers_edit .=  '  <!-- Nav tabs edit tab_edit_manufacturer -->' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_manufacturer">' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '    <li  id="tab_edit_manufacturer" class="active"><a href="#tab_edit_manu_meta_1"     aria-controls="tab_edit_manufacturers_meta_1"          role="tab" data-toggle="tab">' . TEXT_MANU_TABS_01 . '</a></li>'  . PHP_EOL;	  
									  $contents_manufacturers_edit .=  '    <li  id="tab_edit_manufacturer">               <a href="#tab_edit_manu_meta_2"     aria-controls="tab_edit_manufacturers_meta_2"          role="tab" data-toggle="tab">' . TEXT_MANU_TABS_02 . '</a></li>'  . PHP_EOL;	
  									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
									     $contents_manufacturers_edit .=  '    <li  id="tab_edit_manufacturer">               <a href="#tab_edit_manu_stores"     aria-controls="tab_edit_manufacturers_stores"          role="tab" data-toggle="tab">' . TEXT_MANU_TABS_03 . '</a></li>'  . PHP_EOL;	  
                                      }										 
									  $contents_manufacturers_edit .=  '  </ul>'  . PHP_EOL;

									  $contents_manufacturers_edit .=  '  <!-- Tab panes -->' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '  <div  id="tab_edit_manufacturer" class="tab-content">'  . PHP_EOL; 
									  $contents_manufacturers_edit .=  '    <div role="tabpanel" class="tab-pane active" id="tab_edit_manu_meta_1">'        . $contents_edit_manu_tab1 . '</div>' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_manu_meta_2">'        . $contents_edit_manu_tab2 . '</div>' . PHP_EOL ;
                                      
									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  									  
									      $contents_manufacturers_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_manu_stores">'        . $contents_edit_manu_tab3 . '</div>' . PHP_EOL ;			
									  }
									  $contents_manufacturers_edit .=  '  </div>' . PHP_EOL ;
									  $contents_manufacturers_edit .=  '</div>' . PHP_EOL ;	
									  $contents_manufacturers_edit .=   $contents_manufacturers_footer . PHP_EOL ;	
									  
									  $contents            .= $contents_manufacturers_edit ;
									
//                                      $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
//									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id), null, null, 'btn-default text-danger') . PHP_EOL;	
//		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_manufacturer -->	  

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>