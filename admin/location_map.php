<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
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
  $locationmap_to_stores = '@,';
  if ( $HTTP_POST_VARS['stores_location_map'] ) { // if any of the checkboxes are checked
    $_stores = array_keys( $HTTP_POST_VARS['stores_location_map'] )  ;
    foreach($_stores as $val) {	
//    foreach($HTTP_POST_VARS['stores_location_map'] as $val) {
        $locationmap_to_stores .= tep_db_prepare_input($val).','; 
    } // end foreach
  }
  $locationmap_to_stores = substr($locationmap_to_stores,0,strlen($locationmap_to_stores)-1); // remove last comma
  if ( $stores_multi_present != 'true' ) $locationmap_to_stores = '@,1' ; // 1 store automatic active   
// eof multi stores	  

  $map_zoom_level = array();
  for ($i = 0; $i < 22; ++$i) {
      $map_zoom_level[] = array('id' => $i, 'text'  => $i);
  }  

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['mID'])) $locationmap_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $locationmap_name = tep_db_prepare_input($HTTP_POST_VARS['locationmap_name']);
        $locationmap_address = tep_db_prepare_input($HTTP_POST_VARS['locationmap_address']);		
        $locationmap_zoom = tep_db_prepare_input($HTTP_POST_VARS['zoomlevel']);			

        $sql_data_array = array('locationmap_name' => $locationmap_name,
		                        'locationmap_address' =>  $locationmap_address,
								'locationmap_zoom' => $locationmap_zoom, 
		                        'locationmap_to_stores' =>  $locationmap_to_stores);								

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

          tep_db_perform(TABLE_LOCATIONMAP, $sql_data_array);
          $locationmap_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()' );

          $sql_data_array = array_merge((array)$sql_data_array, (array)$update_sql_data);

          tep_db_perform(TABLE_LOCATIONMAP, $sql_data_array, 'update', "locationmap_id = '" . (int)$locationmap_id . "'");
        }

        $locationmap_image = new upload('locationmap_image');
// bof multi stores		
//        $locationmap_image->set_destination(DIR_FS_CATALOG_IMAGES); 
        $locationmap_image->set_destination(DIR_FS_CATALOG_IMAGES . DIR_FS_MANUFACTURERS_IMAGES); 		

        if ($locationmap_image->parse() && $locationmap_image->save()) {
          tep_db_query("update " . TABLE_LOCATIONMAP . " set locationmap_image = '" . DIR_FS_MANUFACTURERS_IMAGES . tep_db_input($locationmap_image->filename) . "' where locationmap_id = '" . (int)$locationmap_id . "'");
        }
		$stores_locationmap_image = tep_db_query("select locationmap_image from " . TABLE_LOCATIONMAP . " where locationmap_id = '" . (int)$locationmap_id  . "'");
		$stores_image_manufacturers = tep_db_fetch_array($stores_locationmap_image) ;
        tep_multi_stores_images( $stores_image_manufacturers[ 'locationmap_image' ], $HTTP_POST_VARS['stores_location_map'] , $default_store_images_directory, '', DIR_FS_MANUFACTURERS_IMAGES )    ; 
// eof multi stores		

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
 
          $locationmap_text_map_array    = $HTTP_POST_VARS['location_map_text'];
          $locationmap_text_marker_array = $HTTP_POST_VARS['location_map_text_marker'];
  
          $language_id = $languages[$i]['id'];

         /*** Begin Header Tags SEO 331 ***/
          $sql_data_array = array(
 		         'location_text_map'    => (tep_not_null($locationmap_text_map_array[$language_id])    ? $locationmap_text_map_array[$language_id]    : $locationmap_name),
 		         'location_text_marker' => (tep_not_null($locationmap_text_marker_array[$language_id]) ? tep_db_prepare_input($locationmap_text_marker_array[$language_id]) : $locationmap_name)				 
          );

          if ($action == 'insert') {
            $insert_sql_data = array('locationmap_id' => $locationmap_id,
                                     'languages_id' => $language_id);

            $sql_data_array = array_merge((array)$sql_data_array, (array)$insert_sql_data);

            tep_db_perform(TABLE_LOCATIONMAP_INFO, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_LOCATIONMAP_INFO, $sql_data_array, 'update', "locationmap_id = '" . (int)$locationmap_id . "' and languages_id = '" . (int)$language_id . "'");
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('location_map');
        }

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'm_' . $locationmap_id);
        }
        /*** End Header Tags SEO ***/
          
        tep_redirect(tep_href_link(FILENAME_LOCATION_MAP, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'mID=' . $locationmap_id));
        break;
      case 'deleteconfirm':
        $locationmap_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $manufacturer_query = tep_db_query("select locationmap_image from " . TABLE_LOCATIONMAP . " where locationmap_id = '" . (int)$locationmap_id . "'");
          $manufacturer = tep_db_fetch_array($manufacturer_query);

// bof multi stores		  
//          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['locationmap_image'];
//          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['locationmap_image'];
//          if (file_exists($image_location)) {
          if (file_exists($default_store_images_directory . $manufacturer['locationmap_image'])) {
             @unlink($default_store_images_directory . $manufacturer['locationmap_image']);		
		     tep_multi_stores_images(  $manufacturer['locationmap_image'], '', $default_store_images_directory, '', '' )    ; 
          }
// eof multi stores
        }

        tep_db_query("delete from " . TABLE_LOCATIONMAP . " where locationmap_id = '" . (int)$locationmap_id . "'");
        tep_db_query("delete from " . TABLE_LOCATIONMAP_INFO . " where locationmap_id = '" . (int)$locationmap_id . "'");


        if (USE_CACHE == 'true') {
          tep_reset_cache_block('manufacturers');
        }

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'm_' . $locationmap_id);
        }
        /*** End Header Tags SEO ***/
                
        tep_redirect(tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page']));
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
                   <th><?php echo TABLE_HEADING_LOCATIONMAP; ?></th>			   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>	  
                </tr>
              </thead>
              <tbody>
<?php			  
              $locations_chart_query_raw =   "select m.locationmap_id, m.locationmap_name, m.locationmap_image, m.locationmap_address, m.date_added, m.last_modified, mi.location_text_map, mi.location_text_marker, 
                                       m.locationmap_to_stores, m.locationmap_zoom from " . TABLE_LOCATIONMAP . " m LEFT JOIN " .  TABLE_LOCATIONMAP_INFO . " mi on m.locationmap_id = mi.locationmap_id where mi.languages_id = '".$languages_id ."' order by m.locationmap_name"; 

              $locations_chart_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $locations_chart_query_raw, $location_map_query_numrows);
              $locations_map_query = tep_db_query($locations_chart_query_raw);
              while ($location_map = tep_db_fetch_array($locations_map_query)) {
                 if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $location_map['locationmap_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
                   $mInfo_array = (array)$location_map ;
                   $mInfo = new objectInfo($mInfo_array);
              }

             if (isset($mInfo) && is_object($mInfo) && ($location_map['locationmap_id'] == $mInfo->locationmap_id)) {
                 echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $location_map['locationmap_id'] . '&action=edit') . '\'">' . PHP_EOL;
             } else {
                 echo '<tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $location_map['locationmap_id']) . '\'">' . PHP_EOL;
    }
?>
                             <td class="dataTableContent"><?php echo $location_map['locationmap_name']; ?></td>
                             <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $location_map['locationmap_id']. '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $location_map['locationmap_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $location_map['locationmap_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL  ;
?>
                                   </div> 
				              </td>						   
                </tr>
<?php
                  if (isset($mInfo) && is_object($mInfo) && ($location_map['locationmap_id'] == $mInfo->locationmap_id) && isset($HTTP_GET_VARS['action'])) { 
// BOF multi stores
                                        $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
                                        while ($stores_stores = tep_db_fetch_array($stores_query)) {
                                            $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
                                        }	
                                        $location_map_to_stores_array = explode(',', $mInfo->locationmap_to_stores); // multi stores
                                        $location_map_to_stores_array = array_slice($location_map_to_stores_array, 1); // remove "@" from the array	 // multi stores											
// EOF multi stores		  
 	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_HEADING_DELETE_LOCATIONMAP . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('location_map', FILENAME_LOCATION_MAP, tep_get_all_get_params(array('mID', 'action')) . 'mID=' . $location_map['locationmap_id'] . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $location_map['locationmap_name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->languages_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
                                       $location_map_to_stores_array = explode(',',$mInfo->locationmap_to_stores);
                                       $location_map_to_stores_array = array_slice($location_map_to_stores_array, 1); // remove "@" from the array									
									   
			                           $contents_edit_location_heading       .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_edit_location_heading       .= '          <div class="panel-heading">' . TEXT_HEADING_EDIT_LOCATIONMAP . '</div>' . PHP_EOL;
			                           $contents_edit_location_heading       .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_edit_location_heading       .= '              <div class="col-xs-12 col-sm-10 col-md-10">' . PHP_EOL;									   
			                           $contents_edit_location_map           .= '               ' . tep_draw_bs_form('languages', FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->locationmap_id . '&action=save', 'post', 'class="form-horizontal" role="form"  enctype="multipart/form-data"', 'id_edit_locations_map') . PHP_EOL;													   
									   
									   $contents_edit_location_tab1          .= '                       <br />' . PHP_EOL;										   
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_input_field('locationmap_name',       $mInfo->locationmap_name,        TEXT_LOCATIONMAP_NAME,       'id_input_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_LOCATIONMAP_NAME,       '', true ) . PHP_EOL;	
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;	
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_file_field('locationmap_image', $mInfo->locationmap_image, TEXT_LOCATIONMAP_IMAGE, 'id_input_image' , 'col-xs-3', 'col-xs-9', 'left', TEXT_LOCATIONMAP_IMAGE  ) .
									                                                                          '<br /><div class="col-xs-3"></div>' . 
																											  '<div class="col-xs-9">' . 
																											      '<figure >' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $mInfo->locationmap_image, $mInfo->locationmap_image, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . 
																												    '<figcaption><small>' . DIR_WS_CATALOG_IMAGES . '<strong>' . $mInfo->locationmap_image . '</strong></small></figcaption>' . 
																												   '</figure>' . 
																											  '</div>' . PHP_EOL;
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;	
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_input_field('locationmap_address', $mInfo->locationmap_address,  TEXT_LOCATIONMAP_ADDRESS, 'id_input_adress' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_LOCATIONMAP_ADDRESS, '', true ) . PHP_EOL;	
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;										   
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_pull_down_menu('zoomlevel', $map_zoom_level, $mInfo->locationmap_zoom, TEXT_LOCATIONMAP_ZOOM, 'id_input_zoomlevel', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left') ;
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;	
									   $contents_edit_location_tab1          .= '                       <br />' . PHP_EOL;

	        
			                           $contents_edit_location_tab2           = '' ;
									   $id_text_map                           = $mInfo->locationmap_id ;									   
                                       include( DIR_WS_MODULES . 'location_map_edit_tab_2.php' ) ;									   
									   
			                           $contents_edit_location_tab3           = '' ;
									   $id_marker                             = $mInfo->locationmap_id ;	
                                       include( DIR_WS_MODULES . 'location_map_edit_tab_3.php' ) ;											   

			                           $contents_edit_location_tab4           = '' ;
                                       include( DIR_WS_MODULES . 'location_map_edit_tab_4.php' ) ;											   
	
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->locationmap_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '                </div>' . PHP_EOL; // end div 	class="col-xs-12 col-sm-10 col-md-10"								   
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel	
									   
									   $contents_edit_location_map .=   $contents_edit_location_heading . PHP_EOL ;	  
									  
									  $contents_edit_location_map .=  '<div role="tabpanel" id="tab_edit_location_map">' . PHP_EOL;
									  $contents_edit_location_map .=  '  <!-- Nav tabs edit location map -->' . PHP_EOL ;
									  $contents_edit_location_map .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_location_map">' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map" class="active"><a href="#tab_edit_location_map_name"       aria-controls="tab_edit_location_map_name"         role="tab" data-toggle="tab">' . TEXT_TABS_LM_01 . '</a></li>' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_price"      aria-controls="tab_edit_location_map_price"        role="tab" data-toggle="tab">' . TEXT_TABS_LM_02 . '</a></li>'  . PHP_EOL;	  
									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_descrip"    aria-controls="tab_edit_location_map_descrip"      role="tab" data-toggle="tab">' . TEXT_TABS_LM_03 . '</a></li>'  . PHP_EOL;	  
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_image"      aria-controls="tab_edit_location_map_image"        role="tab" data-toggle="tab">' . TEXT_TABS_04 . '</a></li>'  . PHP_EOL;
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_htc"        aria-controls="tab_edit_location_map_htc"          role="tab" data-toggle="tab">' . TEXT_TABS_05 . '</a></li>'  . PHP_EOL;			 
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_stock"      aria-controls="tab_edit_location_map_stock"        role="tab" data-toggle="tab">' . TEXT_TABS_07 . '</a></li>'  . PHP_EOL;			  
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_hide_cat"   aria-controls="tab_edit_location_map_hide_cat"     role="tab" data-toggle="tab">' . TEXT_TABS_08 . '</a></li>'  . PHP_EOL;

									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  	  
										 $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_hide_store" aria-controls="tab_edit_location_map_hide_store"   role="tab" data-toggle="tab">' . TEXT_TABS_LM_04 . '</a></li>'  . PHP_EOL;	  
									  }		 
									  
									  $contents_edit_location_map .=  '  </ul>'  . PHP_EOL;

									  $contents_edit_location_map .=  '  <!-- Tab panes -->' . PHP_EOL ;
									  $contents_edit_location_map .=  '  <div  id="tab_edit_location_map" class="tab-content">'  . PHP_EOL;
									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane active" id="tab_edit_location_map_name">'           . $contents_edit_location_tab1 . '</div>' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_price">'          . $contents_edit_location_tab2 . '</div>' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_descrip">'        . $contents_edit_location_tab3 . '</div>' . PHP_EOL ;			
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_image">'          . $contents_edit_prod_tab4 . '</div>' . PHP_EOL ;
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_htc">'            . $contents_edit_prod_tab5   . '</div>' . PHP_EOL ; 
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_stock">'          . $contents_edit_prod_tab7  . '</div>' . PHP_EOL ;	  
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_hide_cat">'       . $contents_edit_prod_tab8  . '</div>' . PHP_EOL ;
									  
									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  	  
										$contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_hide_store">'     . $contents_edit_location_tab4  . '</div>' . PHP_EOL ;	 	  
									  }		
									  
									  $contents_edit_location_map .=  '  </div>' . PHP_EOL ;
									  $contents_edit_location_map .=  '</div>' . PHP_EOL ;	
									  $contents_edit_location_map .=   $contents_products_footer . PHP_EOL ;										   
									  
									  $contents                    =   $contents_edit_location_map . PHP_EOL ;									  
                                      break;										  
		                            default: 


// BOF multi stores
                                       $location_map_to_stores_string = '';
                                       for ($i = 0; $i < count($stores_array); $i++) {
                                          if (in_array($stores_array[$i]['id'], $location_map_to_stores_array)) {
                                             $location_map_to_stores_string .= $stores_array[$i]['text'] . '<br />'; 
                                          }
                                       } // end for ($i = 0; $i < count($stores_array); $i++)
                                       
									   if (!tep_not_null($location_map_to_stores_string)) { 
                                          $location_map_to_stores_string = TEXT_STORES_NONE; 
                                       }             
// EOF multi stores	
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . TEXT_INFO_LOCATION_MAP . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-3 col-md-3">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_LOCATIONMAP_TO_STORES . ' <br />' . $location_map_to_stores_string . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;					
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;

			                            $contents .= '                      <div class="col-xs-12 col-sm-3 col-md-3">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_DATE_ADDED . ' <br />' .tep_date_short($mInfo->date_added) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_LOCATIONMAP_IMAGE . ' ' . tep_info_image($mInfo->locationmap_image, $mInfo->locationmap_name) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;														
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_LOCATIONMAP_ADDRESS . ' : ' .  $mInfo->locationmap_address  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_LOCATIONMAP_ZOOM . ' : ' .  $mInfo->locationmap_zoom  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;										
 												
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;											
			                            $contents .= '                      <div class="col-xs-12 col-sm-3 col-md-3">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_LOCATIONMAP_TEXT . ' <br /><div class="well">' . $mInfo->location_text_map . '</div>' . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					                          
			                            $contents .= '                        </ul>' . PHP_EOL;										
			                            $contents .= '                      </div>' . PHP_EOL;										

			                            $contents .= '                      <div class="col-xs-12 col-sm-3 col-md-3">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_LOCATIONMAP_TEXT_MARKER . ' <br /><div class="well">' . $mInfo->location_text_marker . '</div>' . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					                          
			                            $contents .= '                        </ul>' . PHP_EOL;										
			                            $contents .= '                      </div>' . PHP_EOL;												
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_LOCATION_MAP ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
								  
                              }								  					
                }     //    $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->locationmap_id . '&action=edit')) . tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->locationmap_id . '&action=delete')));

?>				
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="smallText hidden-xs mark text-left"><?php echo $locations_chart_split->display_count($location_map_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_LOCATIONMAPS); ?></div>
               <div class="smallText mark text-right"><?php echo $locations_chart_split->display_links($location_map_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		 
             </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_LOCATION_MAP, 'plus', null,'data-toggle="modal" data-target="#new_location_map"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  

    </table>
	
        <div class="modal fade"  id="new_location_map" role="dialog" aria-labelledby="new_location_map" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('new_location_map', FILENAME_LOCATION_MAP, 'action=insert')
				//tep_draw_bs_form('languages', FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_languages'); ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_NEW_INTRO; ?></h4>
                  </div>
                  <div class="modal-body">
<?php
// BOF multi stores
                                        $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
                                        while ($stores_stores = tep_db_fetch_array($stores_query)) {
                                            $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
                                        }	
                                        $location_map_to_stores_array = array() ; //explode(',', $mInfo->locationmap_to_stores); // multi stores
                                        //$location_map_to_stores_array = array_slice($location_map_to_stores_array, 1); // remove "@" from the array	 // multi stores											
// EOF multi stores		  
			                           $contents_edit_location_heading       .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_edit_location_heading       .= '          <div class="panel-heading">' . TEXT_HEADING_EDIT_LOCATIONMAP . '</div>' . PHP_EOL;
			                           $contents_edit_location_heading       .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_edit_location_heading       .= '              <div class="col-xs-12 col-xs-12 col-md-12">' . PHP_EOL;									   
//			                           $contents_edit_location_map           .= '               ' . tep_draw_bs_form('languages', FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->locationmap_id . '&action=save', 'post', 'class="form-horizontal" role="form"  enctype="multipart/form-data"', 'id_edit_locations_map') . PHP_EOL;													   
									   
									   $contents_edit_location_tab1          .= '                       <br />' . PHP_EOL;										   
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_input_field('locationmap_name',  '',        TEXT_LOCATIONMAP_NAME,       'id_input_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_LOCATIONMAP_NAME,       '', true ) . PHP_EOL;	
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;	
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_file_field('locationmap_image', '', TEXT_LOCATIONMAP_IMAGE, 'id_input_image' , 'col-xs-3', 'col-xs-9', 'left', TEXT_LOCATIONMAP_IMAGE  ) .
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;	
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_input_field('locationmap_address', '',  TEXT_LOCATIONMAP_ADDRESS, 'id_input_adress' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_LOCATIONMAP_ADDRESS, '', true ) . PHP_EOL;	
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;										   
                                       $contents_edit_location_tab1          .= '                       <div></div>' . PHP_EOL ;										   
                                       $contents_edit_location_tab1          .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_edit_location_tab1          .= '                           ' . tep_draw_bs_pull_down_menu('zoomlevel', $map_zoom_level, '', TEXT_LOCATIONMAP_ZOOM, 'id_input_zoomlevel', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left') ;
                                       $contents_edit_location_tab1          .= '                       </div>' . PHP_EOL ;	
									   $contents_edit_location_tab1          .= '                       <br />' . PHP_EOL;

	        
			                           $contents_edit_location_tab2           = '' ;
									   $id_text_map                           = '' ;
                                       include( DIR_WS_MODULES . 'location_map_edit_tab_2.php' ) ;									   
									   
			                           $contents_edit_location_tab3           = '' ;	
                                       $id_marker                             = '' ;									   
                                       include( DIR_WS_MODULES . 'location_map_edit_tab_3.php' ) ;											   

			                           $contents_edit_location_tab4           = '' ;
                                       include( DIR_WS_MODULES . 'location_map_edit_tab_4.php' ) ;											   
	
//                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
//									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->locationmap_id), null, null, 'btn-default text-danger') . PHP_EOL;			
//		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '                </div>' . PHP_EOL; // end div 	class="col-xs-12 col-sm-10 col-md-10"								   
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel	
									   
									   $contents_edit_location_map .=   $contents_edit_location_heading . PHP_EOL ;	  
									  
									  $contents_edit_location_map .=  '<div role="tabpanel" id="tab_edit_location_map">' . PHP_EOL;
									  $contents_edit_location_map .=  '  <!-- Nav tabs edit location map -->' . PHP_EOL ;
									  $contents_edit_location_map .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_location_map">' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map" class="active"><a href="#tab_edit_location_map_name"       aria-controls="tab_edit_location_map_name"         role="tab" data-toggle="tab">' . TEXT_TABS_LM_01 . '</a></li>' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_price"      aria-controls="tab_edit_location_map_price"        role="tab" data-toggle="tab">' . TEXT_TABS_LM_02 . '</a></li>'  . PHP_EOL;	  
									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_descrip"    aria-controls="tab_edit_location_map_descrip"      role="tab" data-toggle="tab">' . TEXT_TABS_LM_03 . '</a></li>'  . PHP_EOL;	  
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_image"      aria-controls="tab_edit_location_map_image"        role="tab" data-toggle="tab">' . TEXT_TABS_04 . '</a></li>'  . PHP_EOL;
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_htc"        aria-controls="tab_edit_location_map_htc"          role="tab" data-toggle="tab">' . TEXT_TABS_05 . '</a></li>'  . PHP_EOL;			 
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_stock"      aria-controls="tab_edit_location_map_stock"        role="tab" data-toggle="tab">' . TEXT_TABS_07 . '</a></li>'  . PHP_EOL;			  
//									  $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_hide_cat"   aria-controls="tab_edit_location_map_hide_cat"     role="tab" data-toggle="tab">' . TEXT_TABS_08 . '</a></li>'  . PHP_EOL;

									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  	  
										 $contents_edit_location_map .=  '    <li  id="tab_edit_location_map">               <a href="#tab_edit_location_map_hide_store" aria-controls="tab_edit_location_map_hide_store"   role="tab" data-toggle="tab">' . TEXT_TABS_LM_04 . '</a></li>'  . PHP_EOL;	  
									  }		 
									  
									  $contents_edit_location_map .=  '  </ul>'  . PHP_EOL;

									  $contents_edit_location_map .=  '  <!-- Tab panes -->' . PHP_EOL ;
									  $contents_edit_location_map .=  '  <div  id="tab_edit_location_map" class="tab-content">'  . PHP_EOL;
									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane active" id="tab_edit_location_map_name">'           . $contents_edit_location_tab1 . '</div>' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_price">'          . $contents_edit_location_tab2 . '</div>' . PHP_EOL ;
									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_descrip">'        . $contents_edit_location_tab3 . '</div>' . PHP_EOL ;			
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_image">'          . $contents_edit_prod_tab4 . '</div>' . PHP_EOL ;
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_htc">'            . $contents_edit_prod_tab5   . '</div>' . PHP_EOL ; 
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_stock">'          . $contents_edit_prod_tab7  . '</div>' . PHP_EOL ;	  
//									  $contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_hide_cat">'       . $contents_edit_prod_tab8  . '</div>' . PHP_EOL ;
									  
									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  	  
										$contents_edit_location_map .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_location_map_hide_store">'     . $contents_edit_location_tab4  . '</div>' . PHP_EOL ;	 	  
									  }		
									  
									  $contents_edit_location_map .=  '  </div>' . PHP_EOL ;
									  $contents_edit_location_map .=  '</div>' . PHP_EOL ;	
									  $contents_edit_location_map .=   $contents_products_footer . PHP_EOL ;										   
									  
									  $contents                    =   $contents_edit_location_map . PHP_EOL ;					  
				  
?>				  
                  <div class="full-iframe" width="100%"> 
                     <?php echo $contents . $contents_footer ; ?>
                  </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_LOCATION_MAP, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $HTTP_GET_VARS['mID'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_language -->	
	
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>