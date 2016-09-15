<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
?>
		  
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                  <th><?php echo TABLE_HEADING_IMAGE; ?></th>
                  <th><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
                  <th><?php echo TABLE_HEADING_HIDE_CATEGORIES; ?></th>				  
                  <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>		
			      <th class="text-center"><?php echo TABLE_HEADING_PURCHASE; ?></td>				  
                  <th class="text-center"><?php echo TABLE_HEADING_PRODUCT_SORT; ?></th>				  
                  <th ><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                </tr>
              </thead>
              <tbody>		   
<?php
    $categories_count = 0;
    $rows = 0;
// BOF SPPC hide products and categories from groups
    $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id");
    while ($customer_groups = tep_db_fetch_array($customers_group_query)) {
      $customers_groups[] = array('id' => $customer_groups['customers_group_id'], 'text' => $customer_groups['customers_group_name']);  
    }	
// EOF SPPC hide products and categories from groups	
// BOF multi stores
    $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
    while ($stores_stores = tep_db_fetch_array($stores_query)) {
      $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
    }	
		
// EOF multi stores
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_hide_from_groups, c.categories_status, c.categories_hide_from_groups, c.categories_to_stores , cd.categories_htc_title_tag, cd.categories_htc_title_tag_alt, cd.categories_htc_title_tag_url, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_breadcrumb_text, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");	    
    } else { 
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status, c.categories_hide_from_groups, c.categories_to_stores, cd.categories_htc_title_tag, cd.categories_htc_title_tag_alt, cd.categories_htc_title_tag_url,cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_breadcrumb_text, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");	  
    }

    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge((array)$categories, (array)$category_childs, (array)$category_products);
        $cInfo = new objectInfo($cInfo_array);
      }
	
      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '                <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";	
      } else {
        echo '                <tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }

?>
                  <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_CATALOG_IMAGES . $categories['categories_image'], $categories['categories_name'], 40, 40, NULL, false) . '</a>'; ?></td>
                  <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '"></a>&nbsp;' . $categories['categories_name']; ?></td>			  
                
<?php // BOF SPPC hide products and categories from groups ?>
       <td class="text-center"><?php
			$hide_cat_from_groups_array = explode(',', $categories['categories_hide_from_groups']);
			$hide_cat_from_groups_array = array_slice($hide_cat_from_groups_array, 1); // remove "@" from the array
			$cats_to_stores_array = explode(',', $categories['categories_to_stores']); // multi stores
			$cats_to_stores_array = array_slice($cats_to_stores_array, 1); // remove "@" from the array	 // multi stores
		   $category_hidden_from_string = '';
		   if (LAYOUT_HIDE_FROM == '1') {
				 for ($i = 0; $i < count($customers_groups); $i++) {
				   if (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) {
				   $category_hidden_from_string .= $customers_groups[$i]['text'] . ', '; 
				   }
				 } // end for ($i = 0; $i < count($customers_groups); $i++)
				 $category_hidden_from_string = rtrim($category_hidden_from_string); // remove space on the right
				 $category_hidden_from_string = substr($category_hidden_from_string,0,strlen($category_hidden_from_string) -1); // remove last comma
				 if (!tep_not_null($category_hidden_from_string)) { 
				 $category_hidden_from_string = TEXT_GROUPS_NONE; 
				 }
				 $category_hidden_from_string = TEXT_HIDDEN_FROM_GROUPS . $category_hidden_from_string;
				 // if the string in the database field is @, everything will work fine, however tep_not_null
				 // will not discover the associative array is empty therefore the second check on the value
			 if (tep_not_null($hide_cat_from_groups_array) && tep_not_null($hide_cat_from_groups_array[0])) {
				echo tep_image(DIR_WS_ICONS . 'tick_black.gif', $category_hidden_from_string);
			  } else {
				echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $category_hidden_from_string);
			 }
		   } else {
		// default layout: icons for all groups
			  for ($i = 0; $i < count($customers_groups); $i++) {
				if (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) {
				  echo tep_glyphicon('remove-sign glyphicon-lg', 'danger') . $customers_groups[$i]['text'] . '&nbsp;&nbsp;';
				} else {
				  echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif'  ) . '&nbsp;&nbsp;';
				}
			  }
		   }
?>
       </td>
<?php // EOF SPPC hide products and categories from groups ?>
               <!-- // BOF Enable & Disable Categories -->
                <!--<td class="dataTableContent" align="center">&nbsp;</td>-->
                <td class="text-center">
                <?php
                  if ($categories['categories_status'] == '1') {
                        echo '                    ' . tep_glyphicon('ok-sign glyphicon-lg', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('remove-sign glyphicon-lg', 'muted') . '</a>' . "\n";
                    } else {
                        echo '                    <a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('ok-sign glyphicon-lg', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign glyphicon-lg', 'danger') . "\n";
                    }
                ?>
                </td>
                <!-- // EOF Enable & Disable Categories -->						   
                <td class="text-center"><?php echo '' ; ?></td>					
                <td class="text-center"><?php echo $categories[sort_order]; ?></td>					
                  <td class="text-right">
                    <div class="btn-toolbar" role="toolbar">                  
<?php
      echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO, 'info-sign', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=info'), null, 'info') . '</div>' . "\n" .
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=edit_category'), null, 'warning') . '</div>' . "\n" .
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_MOVE, 'move', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=move_category'), null, 'muted') . '</div>' . "\n" .
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=delete_category'), null, 'danger') . '</div>' . "\n"; ?>

                    </div> 
				  </td>										
              </tr>
<style>
.tab-pane {
    height: 500px;
}
</style>
<?php 

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) && isset($HTTP_GET_VARS['action'])) { 
	    $alertClass = '';
        switch ($action) {
		  case 'edit_category':
            if (!isset($cInfo->categories_status)) $cInfo->categories_status = '1';
            switch ($cInfo->categories_status) {
             case '0': $in_status2 = false; $out_status2 = true; break;
             case '1':
             default: $in_status2 = true; $out_status2 = false;
           }
// BOF SPPC hide products and categories from groups
           $hide_cat_from_groups_array = explode(',',$cInfo->categories_hide_from_groups);
           $hide_cat_from_groups_array = array_slice($hide_cat_from_groups_array, 1); // remove "@" from the array
//    $hide_product_from_groups_array = explode(',',$pInfo->products_hide_from_groups);
//    $hide_product_from_groups_array = array_slice($hide_product_from_groups_array, 1); // remove "@" from the array
// EOF SPPC hide products and categories from groups
           $cats_to_stores_array = explode(',', $cInfo->categories_to_stores); // multi stores
           $cats_to_stores_array = array_slice($cats_to_stores_array, 1); // remove "@" from the array	 // multi stores
//    $products_to_stores_array = explode(',', $pInfo->products_to_stores); // multi stores
//    $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores		   
// eof enable disable categories 			  
			$contents_heading .= '       <div class="panel panel-primary">' . PHP_EOL ;
			$contents_heading .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</div>' . PHP_EOL;
			$contents_heading .= '          <div class="panel-body">' . PHP_EOL;			
			$contents_heading .= '                      ' . tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'class="form-horizontal" role="form" enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id) . PHP_EOL;		
			$contents_heading .= '                          <p>' . TEXT_EDIT_INTRO . '</p>' . PHP_EOL;			
            $contents_heading .= '                        <div class="col-xs-12 col-sm-10 col-md-10">' . PHP_EOL;

            $category_inputs_string = '';
            $languages = tep_get_languages();
            for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
              $category_inputs_string .= '                                <div >' . PHP_EOL .
                                         '                                  <div class="input-group">' . PHP_EOL .
										 '                                    <div class="input-group-addon">' . PHP_EOL . 
										 '                                      ' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'],24,15) . PHP_EOL .
										 
										 '                                    </div>' . PHP_EOL .
										 '                                    ' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id'])) . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;
            }
			$contents_tab1 .= '                                           <br />' . PHP_EOL;				
			$contents_tab1 .= '                                           <div class="row">' . PHP_EOL;
			$contents_tab1 .= '                                              <div class="col-md-12">' . PHP_EOL;
			$contents_tab1 .= '                                                  <label>' . TEXT_CATEGORIES_NAME . '</label>' . PHP_EOL;
			$contents_tab1 .=                                                    $category_inputs_string;
			$contents_tab1 .= '                                              </div>' . PHP_EOL;
			$contents_tab1 .= '                                           </div>' . PHP_EOL;
			$contents_tab1 .= '                                           <br />' . PHP_EOL;			

            $contents_tab1 .= '                                           <label>' . TEXT_EDIT_SORT_ORDER . '</label>' . PHP_EOL;
            $contents_tab1 .= '                                           ' . tep_draw_input_field('sort_order', $cInfo->sort_order) . '<br />' . PHP_EOL;

		    $contents_tab1 .= '                                           <div class="btn-group" data-toggle="buttons">' . PHP_EOL; 
		    $contents_tab1 .= '                                               <label>' . TEXT_EDIT_STATUS . '</label><br />' . PHP_EOL;
		    $contents_tab1 .= '                                               <label class="btn btn-default' . ( $in_status2 ===  True ? " active " : "" ) . '">' . TEXT_CATEGORIES_ONLINE  . PHP_EOL;
		    $contents_tab1 .= '                                                 ' . tep_draw_radio_field("categories_status", "1", $in_status2) . PHP_EOL;
		    $contents_tab1 .= '                                               </label>' . PHP_EOL;
 
		    $contents_tab1 .= '                                               <label class="btn btn-default ' . ( $out_status2 === True ? " active " : "" ) . '">'. TEXT_CATEGORIES_OFFLINE .  PHP_EOL;
		    $contents_tab1 .= '                                                  ' . tep_draw_radio_field("categories_status", "0", $out_status2) . PHP_EOL;
		    $contents_tab1 .= '                                               </label>' . PHP_EOL;
			$contents_tab1 .= '                                           </div>' . PHP_EOL;			
            
			$contents_tab2 .= '                                           <div class="row">' . PHP_EOL;
			$contents_tab2 .= '                                              <div class="col-md-12">' . PHP_EOL;			
            $contents_tab2 .= '                                                  <label>' . TEXT_EDIT_CATEGORIES_IMAGE . '</label>' . PHP_EOL;
            $contents_tab2 .= '                                                  <p>'. tep_draw_file_field('categories_image') . '</p>' . PHP_EOL;
            $contents_tab2 .= '                                                  <br>' . PHP_EOL;			
            $contents_tab2 .= '                                                  <figure>' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<figcaption><small>' . DIR_WS_CATALOG_IMAGES . '<strong>' . $cInfo->categories_image . '</strong></small></figcaption></figure>' . PHP_EOL;
			$contents_tab2 .= '                                               </div>' . PHP_EOL;
			$contents_tab2 .= '                                           </div>' . PHP_EOL;

            $contents_tab3       .=  PHP_EOL . '  <!-- Nav tabs Meta Tags -->' . PHP_EOL ;						
            $contents_tab3       .=  '<div role="tabpanel" id="tab_htc">'. PHP_EOL  ;			
			
            $contents_tab3_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_htc">'. PHP_EOL  ;
            
			$contents_tab3_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_tab3_tabs .=  '  <div class="tab-content" id="tab_htc">'. PHP_EOL  ;	

            $contents_tab4       .=  PHP_EOL . '  <!-- Nav tabs Htc Description Index page -->' . PHP_EOL ;						
            $contents_tab4       .=  '<div role="tabpanel" id="tab_htc_oms_idx_page">'. PHP_EOL  ;			
			
            $contents_tab4_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_htc_oms_idx_page">'. PHP_EOL  ;

            $contents_tab4_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
            $contents_tab4_tabs .=  '  <div class="tab-content" id="tab_htc_oms_idx_page">'. PHP_EOL  ;			
				
			$active_tab = 'active' ;
			
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
			
              $contents_tab3_links .=  '    <li class="'. $active_tab . '"><a href="#htc_meta_tags_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_htc">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

			  $contents_tab4_links .=  '    <li class="'. $active_tab . '"><a href="#htc_desc_idx_page_' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_htc_oms_idx_page">' .
			                                             tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;
             
             
            			
			  $contents_tab3_content   = '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="cat_htc_title' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">'  . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_title($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_title' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' .
										 '                                </div><br />' . PHP_EOL;										 
              $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL . 
										 '                                    <label for="cat_htc_title_tag_alt' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE_ALT . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .												 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_title_tag_alt[' . $languages[$i]['id'] . ']', tep_get_category_htc_title_alt($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_title_tag_alt' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
              $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="cat_htc_title_tag_url' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_TITLE_URL . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_title_tag_url[' . $languages[$i]['id'] . ']', tep_get_category_htc_title_url($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_title_tag_url' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
              $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL .
										 '                                    <label for="cat_htc_desc_tag' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_DESCRIP_CATOGORIES . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_desc($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_desc_tag' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;											 
             $contents_tab3_content   .= '                                <div class="form-group">' . PHP_EOL .
 										 '                                    <label for="cat_htc_keywords_tag' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_KEYWORD . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_keywords($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_keywords_tag' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
	          $contents_tab3_content  .= '                                <div class="form-group">' . PHP_EOL .
 										 '                                    <label for="cat_htc_breadcrumb_text' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_BREADCRUMB . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-sm-10">' . tep_draw_input_field('categories_htc_breadcrumb_text[' . $languages[$i]['id'] . ']', tep_get_category_htc_breadcrumb($cInfo->categories_id, $languages[$i]['id']), 'id="cat_htc_breadcrumb_text' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                  </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;	
										 
	          $contents_tab4_content   = '                                <div class="form-group">' . PHP_EOL .
// 										 '                                    <label for="cat_htc_description' . $languages[$i]['name'] . '" class="col-sm-2 control-label">' . TEXT_HEADER_CAT_HEADER_DESCRIPTION . PHP_EOL . 								 
										 '                                    </label> ' . PHP_EOL .										 
										 '                                    <div class="col-xs-12">' . tep_draw_textarea_ckeditor('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 100, 20, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']), 'id = "cat_htc_description' . $languages[$i]['name'] . '"') . PHP_EOL .
										 '                                    </div>' . PHP_EOL .
										 '                                </div>' . PHP_EOL;			
										 
              $contents_tab3_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="htc_meta_tags_'     . $languages[$i]['name'] . '">' . $contents_tab3_content .'</div>'. PHP_EOL  ;										 
              $contents_tab4_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="htc_desc_idx_page_' . $languages[$i]['name'] . '">' . $contents_tab4_content .'</div>'. PHP_EOL  ;					  
			  $active_tab = '' ;			  
            }		

            $contents_tab3_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_tab3_tabs  .=  '  </div>'. PHP_EOL  ; 
			
            $contents_tab4_links .=  '  </ul>'. PHP_EOL  ;	
            $contents_tab4_tabs  .=  '  </div>'. PHP_EOL  ; 			
 
			
			$contents_tab3       .=	$contents_tab3_links . PHP_EOL . $contents_tab3_tabs . PHP_EOL ;
            $contents_tab3       .=  '</div>'. PHP_EOL  ;			
            $contents_tab3       .=  '<!-- end nav meta tags -->'. PHP_EOL  ;	
			
			
			$contents_tab4       .=	 '<p>' . TEXT_HEADER_CAT_HEADER_DESCRIPTION . '</p>'. PHP_EOL ;
			$contents_tab4       .=	 			$contents_tab4_links . PHP_EOL . $contents_tab4_tabs . PHP_EOL ;
            $contents_tab4       .=  '</div>'. PHP_EOL  ;			
            $contents_tab4       .=  '<!-- end nav Htc Description Index page  -->'. PHP_EOL  ;		
			
		    $contents_tab5 .= '<div class="well well-info">' . sprintf( TEXT_HIDE_CATEGORIES_FROM_GROUPS, $cInfo->categories_name ) . '</div>' ;
            for ($i = 0; $i < count($customers_groups); $i++) {
	           $contents_tab5 .= '   <div class="form-group">' . PHP_EOL ;
               $contents_tab5 .= '       <div class="checkbox checkbox-danger">'. PHP_EOL  ;	
               $contents_tab5 .= '          ' .  tep_draw_checkbox_field('hide_cat[' . $customers_groups[$i]['id'] . ']',  $customers_groups[$i]['id'] , (in_array($customers_groups[$i]['id'], $hide_cat_from_groups_array)) ? 1: 0) . PHP_EOL  ;	
               $contents_tab5 .= '               <label for="' . $customers_groups[$i]['id']  . '">'. PHP_EOL  ;	
               $contents_tab5 .= '                       ' . $customers_groups[$i]['text']  .  PHP_EOL  ;	
               $contents_tab5 .= '               </label>'. PHP_EOL  ;	
               $contents_tab5 .= '       </div>'. PHP_EOL  ;	
               $contents_tab5 .= '   </div>'. PHP_EOL  ;					
            }	
			
		    $contents_tab6 .= '<div class="well danger">' . sprintf( TEXT_CATEGORIES_TO_STORE, $cInfo->categories_name ) . '</div>' ;
            for ($i = 0; $i < count($stores_array); $i++) {
	           $contents_tab6 .= '   <div class="form-group">' . PHP_EOL ;
               $contents_tab6 .= '       <div class="checkbox checkbox-success">'. PHP_EOL  ;	
               $contents_tab6 .= '          ' .  tep_draw_checkbox_field('stores_cat[' . $stores_array[$i]['id'] . ']',  $stores_array[$i]['id'] , (in_array($stores_array[$i]['id'], $cats_to_stores_array)) ? 1: 0) . PHP_EOL  ;	
               $contents_tab6 .= '               <label for="' . $stores_array[$i]['id']  . '">'. PHP_EOL  ;	
               $contents_tab6 .= '                       ' . $stores_array[$i]['text']  .  PHP_EOL  ;	
               $contents_tab6 .= '               </label>'. PHP_EOL  ;	
               $contents_tab6 .= '       </div>'. PHP_EOL  ;	
               $contents_tab6 .= '   </div>'. PHP_EOL  ;					
            }			

		    $contents_footer .= '                      </div>' . PHP_EOL;	
            $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id), null, null, 'btn-default text-danger') . PHP_EOL;
			
		    $contents_footer .= '                      </form>' . PHP_EOL;
		    $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		    $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
			
			$contents  =   $contents_heading ;
            $contents .=  '<div role="tabpanel" id="tab_category">' . PHP_EOL;

            $contents .=  '  <!-- Nav tabs Category -->' . PHP_EOL ;
            $contents .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_category">' . PHP_EOL ;
            $contents .=  '    <li  id="tab_category" class="active"><a href="#tab_name"     aria-controls="tab_name"      role="tab" data-toggle="tab">' . TEXT_TABS_CAT_1 . '</a></li>' . PHP_EOL ;
            $contents .=  '    <li  id="tab_category">              <a href="#tab_image"     aria-controls="tab_image"     role="tab" data-toggle="tab">' . TEXT_TABS_CAT_2 . '</a></li>'  . PHP_EOL;
            $contents .=  '    <li  id="tab_category">              <a href="#tab_htc"       aria-controls="tab_htc"       role="tab" data-toggle="tab">' . TEXT_TABS_CAT_3 . '</a></li>'  . PHP_EOL;			
            $contents .=  '    <li  id="tab_category">              <a href="#tab_descrip"   aria-controls="tab_descrip"   role="tab" data-toggle="tab">' . TEXT_TABS_CAT_4 . '</a></li>'  . PHP_EOL;
            $contents .=  '    <li  id="tab_category">              <a href="#tab_cust_gr"   aria-controls="tab_cust_gr"   role="tab" data-toggle="tab">' . TEXT_TABS_CAT_5 . '</a></li>'  . PHP_EOL;

			if ( count( $stores_array ) > 1 ) {
                $contents .=  '    <li  id="tab_category">          <a href="#tab_stores"    aria-controls="tab_stores"    role="tab" data-toggle="tab">' . TEXT_TABS_CAT_6 . '</a></li>'  . PHP_EOL;			
		    }
            $contents .=  '  </ul>'  . PHP_EOL;

            $contents .=  '  <!-- Tab panes -->' . PHP_EOL ;
            $contents .=  '  <div  id="tab_category" class="tab-content">'  . PHP_EOL;
            $contents .=  '    <div role="tabpanel" class="tab-pane active" id="tab_name">' . $contents_tab1 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_image">'       . $contents_tab2 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_htc">'         . $contents_tab3 . '</div>' . PHP_EOL ;			
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_descrip">'     . $contents_tab4 . '</div>' . PHP_EOL ;
            $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_cust_gr">'     . $contents_tab5 . '</div>' . PHP_EOL ;
			
			if ( count( $stores_array ) > 1 ) {
               $contents .=  '    <div role="tabpanel" class="tab-pane" id="tab_stores">'     . $contents_tab6 . '</div>' . PHP_EOL ;			
            }
			
            $contents .=  '  </div>' . PHP_EOL ;

            $contents .=  '</div>' . PHP_EOL ;
			$contents .=  $contents_sv_cncl  . PHP_EOL;
            $contents .=  $contents_footer  . PHP_EOL;	

		  break;
        
		  case 'delete_category':
		    $alertClass .= ' alert alert-danger';
		    $contents .= '                      ' . tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id) . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_DELETE_CATEGORY . ': ' . $cInfo->categories_name. '</h4>' . PHP_EOL;
            $contents .= '                          <p>' . TEXT_DELETE_CATEGORY_INTRO . '</p>' . PHP_EOL;
            if ($cInfo->childs_count > 0) $contents .= '                          ' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count) . PHP_EOL;
            if ($cInfo->products_count > 0) $contents .= '                          <br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count) . PHP_EOL;
            $contents .= '                        </div>' . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id), null, null, 'btn-default text-danger') . PHP_EOL;
            $contents .= '                        </div>' . PHP_EOL;
		    $contents .= '                      </form>' . PHP_EOL;
          break;
		  
          case 'move_category':
		    $contents .= '                      ' . tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id) . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</h4>' . PHP_EOL;
            $contents .= '                          <p>' . sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name) . '</p>' . PHP_EOL;
            $contents .= '                          <p>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . '</p>' . PHP_EOL;
			$contents .= '                        </div>' . PHP_EOL;
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_MOVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id), null, null, 'btn-default text-danger') . PHP_EOL;
            $contents .= '                        </div>' . PHP_EOL;
		    $contents .= '                      </form>' . PHP_EOL;
          break;
		
		  default:
            $contents .= '                      <div class="col-xs-12 col-sm-2 col-md-4">' . PHP_EOL;
			$contents .= '                        <strong>' . $cInfo->categories_name . '</strong>' . PHP_EOL;
            $contents .= '                        <figure>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<figcaption>' . $cInfo->categories_image.'</figcaption></figure>' . PHP_EOL;
            $contents .= '                      </div>' . PHP_EOL;
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL;
			$contents .= '                        <ul class="list-group">' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                            <span class="badge badge-info">' . $cInfo->childs_count . '</span>' . PHP_EOL;			
			$contents .= '                              ' . TEXT_SUBCATEGORIES . PHP_EOL;
			$contents .= '                          </li>' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                            <span class="badge badge-info">' . $cInfo->products_count . '</span>' . PHP_EOL;			
			$contents .= '                              ' . TEXT_PRODUCTS . PHP_EOL;
			$contents .= '                          </li>' . PHP_EOL;
			$contents .= '                        </ul>' . PHP_EOL;
			$contents .= '                      </div>' . PHP_EOL;
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL;
			$contents .= '                        <ul class="list-group">' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                            <span class="badge badge-info">' . tep_date_short($cInfo->date_added) . '</span>' . PHP_EOL;			
			$contents .= '                              ' . TEXT_DATE_ADDED . PHP_EOL;
			$contents .= '                          </li>' . PHP_EOL;
            if (tep_not_null($cInfo->last_modified)) {
		      $contents .= '                          <li class="list-group-item">' . PHP_EOL;
			  $contents .= '                            <span class="badge badge-info">' . tep_date_short($cInfo->last_modified) . '</span>' . PHP_EOL;			
			  $contents .= '                              ' . TEXT_LAST_MODIFIED . PHP_EOL;
			  $contents .= '                          </li>' . PHP_EOL;					
			}
			$contents .= '                        </ul>' . PHP_EOL;
			$contents .= '                      </div>' . PHP_EOL;
          break;
        }

        echo '                <tr class="content-row">' . PHP_EOL .
             '                  <td colspan="6">' . PHP_EOL .
             '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                    $contents . 
             '                    </div>' . PHP_EOL .
             '                  </td>' . PHP_EOL .
             '                </tr>' . PHP_EOL;
      }			  
    }

// products on screen
    $products_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_cost, p.products_qty_blocks, p.products_min_order_qty, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_purchase, p.products_to_stores, p.products_hide_from_groups, p2c.categories_id, ptdc.discount_categories_id, dc.discount_categories_name, p.products_sort_order  from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id left join " . TABLE_DISCOUNT_CATEGORIES . " dc using(discount_categories_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by p.products_sort_order,pd.products_name");
    } else {
     $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_cost, p.products_qty_blocks, p.products_min_order_qty, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_purchase, p.products_to_stores, p.products_hide_from_groups, ptdc.discount_categories_id, dc.discount_categories_name, p.products_sort_order  from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " ptdc on p.products_id = ptdc.products_id left join " . TABLE_DISCOUNT_CATEGORIES . " dc using(discount_categories_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by p.products_sort_order,pd.products_name");
    }
	
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $products['categories_id'];

      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge((array)$products, (array)$reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

     if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '                <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview') . '\'">' . "\n";
      } else {
        echo '                <tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
<!-- Begin Mini Images v2.0//-->
              <td ><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products['products_image'], $products['products_name'], 50, 50); ?></td>
<!-- End Mini Images v2.0//-->

<!--              <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>			-->
                  <td><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview') . '">' . tep_glyphicon('screenshot', 'info') . '</a>&nbsp;' . $products['products_name']; ?></td>
			  
<?php  //BOF SPPC hide products and categories from groups ?>
			  <td align="center"><?php
			  $hide_prods_from_groups_array = explode(',', $products['products_hide_from_groups']);
			  $hide_prods_from_groups_array = array_slice($hide_prods_from_groups_array, 1); // remove "@" from the array
			  if (LAYOUT_HIDE_FROM == '1') {
				$product_hidden_from_string = '';
				 for ($i = 0; $i < count($customers_groups); $i++) {
				   if (in_array($customers_groups[$i]['id'], $hide_prods_from_groups_array)) {
				   $product_hidden_from_string .= $customers_groups[$i]['text'] . ', '; 
				   }
				 } // end for ($i = 0; $i < count($customers_groups); $i++)
				 $product_hidden_from_string = rtrim($product_hidden_from_string); // remove space on the right
				 $product_hidden_from_string = substr($product_hidden_from_string,0,strlen($product_hidden_from_string) -1); // remove last comma
		   if (tep_not_null($hide_prods_from_groups_array)&& tep_not_null($hide_prods_from_groups_array[0])) { 
				 $product_hidden_from_string = TEXT_GROUPS_NONE; 
				 }
				 $product_hidden_from_string = TEXT_HIDDEN_FROM_GROUPS . $product_hidden_from_string;
		   if (tep_not_null($hide_prods_from_groups_array)) {
				echo tep_image(DIR_WS_ICONS . 'tick_black.gif', $product_hidden_from_string, 20, 16);
			 } else {
				echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', $product_hidden_from_string, 20, 16);
			 }
		   } else {
		// default layout: icons for all groups
			  for ($i = 0; $i < count($customers_groups); $i++) {
				if (in_array($customers_groups[$i]['id'], $hide_prods_from_groups_array)) {
//				  echo tep_image(DIR_WS_ICONS . 'icon_tick.gif', $customers_groups[$i]['text'], 11, 11) . '&nbsp;&nbsp;';
				  echo tep_glyphicon('remove-sign glyphicon-lg', 'danger') . $customers_groups[$i]['text'] . '&nbsp;&nbsp;';				  
				} else {
				  echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', 11, 11) . '&nbsp;&nbsp;';
				}
			  }
		   } // end if/else (LAYOUT_HIDE_FROM == '1')
		?></td><?php // EOF SPPC hide products and categories from groups ?>				
                <td align="center">
<?php
      if ($products['products_status'] == '1') {
         echo '                    ' . tep_glyphicon('ok-sign glyphicon-lg', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('remove-sign glyphicon-lg', 'muted') . '</a>' . "\n";
      } else {
        echo '                    <a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath)  . '">' . tep_glyphicon('ok-sign glyphicon-lg', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign glyphicon-lg', 'danger') . "\n";
      }
?></td>
<!-- START Added for the purchase feature option -->
                <td align="center">
<?php
      if ($products['products_purchase'] == '1') {
         echo '                    ' . tep_glyphicon('ok-sign glyphicon-lg', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag1&flag1=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_glyphicon('remove-sign glyphicon-lg', 'muted') . '</a>' . "\n";
      } else {
        echo '                    <a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag1&flag1=1&pID=' . $products['products_id'] . '&cPath=' . $cPath)  . '">' . tep_glyphicon('ok-sign glyphicon-lg', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign glyphicon-lg', 'danger') . "\n";
      }
?></td>
<!-- END Added for the purchase feature option -->
				<td  align="center"><?php echo $products['products_sort_order']; // Product Sort ?></td>
                 <td class="text-right">
                    <div class="btn-toolbar" role="toolbar">
                      
<?php
      echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO, 'info-sign', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=info'), null, 'info') . '</div>' . "\n" . 
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product'), null, 'warning') . '</div>' . "\n" . 
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_COPY_TO, 'transfer', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=copy_to'), null, 'muted'). '</div>' . "\n" . 
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_MOVE, 'move', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=move_product'), null, 'muted') . '</div>' . "\n" . 
           '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=delete_product'), null, 'danger') . '</div>' . "\n"; ?>

                    </div>
				  </td>              </tr>
<?php
      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) && isset($HTTP_GET_VARS['action'])) { 
	    $alertClass = '';
        switch ($action) {
			        
		  case 'delete_product':
		    $alertClass .= ' alert alert-danger';
		    $contents .= '                      ' . tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id) . "\n";
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . "\n";
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_DELETE_PRODUCT . ': ' . $pInfo->products_name . '</h4>' . "\n";
            $contents .= '                          <p>' . TEXT_DELETE_PRODUCT_INTRO . '</p>' . "\n";
			
			
            $product_categories_string = '';
            $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
            for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
              $category_path = '';
              for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
                $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
              }
              $category_path = substr($category_path, 0, -16);
              $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
            }
           // $product_categories_string = substr($product_categories_string, 0, -4);
			
            $contents .= '                          ' . $product_categories_string .  "\n";

			
            $contents .= '                        </div>' . "\n";
			
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . "\n";
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id), null, null, 'btn-default text-danger') . "\n";
            $contents .= '                        </div>' . "\n";
			
		    $contents .= '                      </form>' . "\n";
          break;
		  
          case 'move_product':
		    $contents .= '                      ' . tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id) . "\n";
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . "\n";
			$contents .= '                          <h4>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</h4>' . "\n";
            $contents .= '                          <p>' . sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name) . '</p>' . "\n";
			$contents .= '                          <p>' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><strong>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</strong>' . "\n";
			
            $contents .= '                          <p>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . '</p>' . "\n";
			$contents .= '                        </div>' . "\n";
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . "\n";
            $contents .= '                          ' . tep_draw_bs_button(IMAGE_MOVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id), null, null, 'btn-default text-danger') . "\n";
            $contents .= '                        </div>' . "\n";
		    $contents .= '                      </form>' . "\n";
          break;
		  
          case 'copy_to':
		    $contents .= '                      ' . tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id) . "\n";
            $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . "\n";
			$contents .= '                          <h4>' . TEXT_INFO_COPY_TO_INTRO . '</h4>' . "\n";
            $contents .= '                          <p>' . sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name) . '</p>' . "\n";
			$contents .= '                          <p>' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><strong>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</strong></p>' . "\n";
			
            $contents .= '                          <p>' . TEXT_CATEGORIES . '<br />' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id) . '</p>' . "\n";
			
			
			$contents .= '                        </div>' . "\n";
            $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4">' . "\n";
            $contents .= '                          <strong>' . TEXT_HOW_TO_COPY . '</strong>' . "\n";
            $contents .= '                          <div class="radio">' . "\n";
            $contents .= '                            <label>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '</label>' . "\n";
            $contents .= '                          </div>' . "\n";
            $contents .= '                          <div class="radio">' . "\n";
            $contents .= '                            <label>'. tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE . '</label>' . "\n";
            $contents .= '                          </div>' . "\n";
			
            $contents .= '                          <div class="text-right">' . "\n";
            $contents .= '                            ' . tep_draw_bs_button(IMAGE_COPY, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id), null, null, 'btn-default text-danger') . "\n";
            $contents .= '                          </div>' . "\n";
			
            $contents .= '                        </div>' . "\n";
		    $contents .= '                      </form>' . "\n";
          break;
		
		  default:
            $contents .= '                      <div class="col-xs-12 col-sm-2 col-md-4">' . "\n";
			$contents .= '                        <strong>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</strong>' . "\n";
            $contents .= '                        <figure>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<figcaption>' . $pInfo->products_image.'</figcaption></figure>' . "\n";
            $contents .= '                      </div>' . "\n";
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . "\n";
			$contents .= '                        <ul class="list-group">' . "\n";
			$contents .= '                          <li class="list-group-item">' . "\n";
			$contents .= '                            <span class="badge badge-info">' . $currencies->format($pInfo->products_price) . '</span>' . "\n";			
			$contents .= '                              ' . TEXT_PRODUCTS_PRICE_INFO . "\n";
			$contents .= '                          </li>' . "\n";
			$contents .= '                          <li class="list-group-item">' . "\n";
			$contents .= '                            <span class="badge badge-info">' . $pInfo->products_quantity . '</span>' . "\n";			
			$contents .= '                              ' . TEXT_PRODUCTS_QUANTITY_INFO . "\n";
			$contents .= '                          </li>' . "\n";
			$contents .= '                          <li class="list-group-item">' . "\n";
			$contents .= '                            <span class="badge badge-info">' . number_format($pInfo->average_rating, 2) . '%' . '</span>' . "\n";			
			$contents .= '                              ' . TEXT_PRODUCTS_AVERAGE_RATING . "\n";
			$contents .= '                          </li>' . "\n";
			$contents .= '                        </ul>' . "\n";
			$contents .= '                      </div>' . "\n";
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . "\n";
			$contents .= '                        <ul class="list-group">' . "\n";
			$contents .= '                          <li class="list-group-item">' . "\n";
			$contents .= '                            <span class="badge badge-info">' . tep_date_short($pInfo->products_date_added) . '</span>' . "\n";			
			$contents .= '                              ' . TEXT_DATE_ADDED . "\n";
			$contents .= '                          </li>' . "\n";
            if (tep_not_null($pInfo->products_last_modified)) {
		      $contents .= '                          <li class="list-group-item">' . "\n";
			  $contents .= '                            <span class="badge badge-info">' . tep_date_short($pInfo->products_last_modified) . '</span>' . "\n";			
			  $contents .= '                              ' . TEXT_LAST_MODIFIED . "\n";
			  $contents .= '                          </li>' . "\n";					
			}
            if (date('Y-m-d') < $pInfo->products_date_available) {
		      $contents .= '                          <li class="list-group-item">' . "\n";
			  $contents .= '                            <span class="badge badge-info">' . tep_date_short($pInfo->products_date_available) . '</span>' . "\n";			
			  $contents .= '                              ' . TEXT_DATE_AVAILABLE . "\n";
			  $contents .= '                          </li>' . "\n";					
			}
			$contents .= '                        </ul>' . "\n";
			$contents .= '                      </div>' . "\n";
          break;
        }
        echo '                <tr class="content-row">' . "\n" .
             '                  <td colspan="3">' . "\n" .
             '                    <div class="row' . $alertClass . '">' . "\n" .
                                    $contents . 
             '                    </div>' . "\n" .
             '                  </td>' . "\n" .
             '                </tr>' . "\n";
      }
    }
	
?> 
           
              </tbody>
            </table>
<?php			