<?php

  //DWD GOTCHA: /modules/ is hardcoded, but should be the same value as the value in configuration.php
  require(DIR_WS_LANGUAGES . $language . '/modules/' . FILENAME_BROWSE_CATEGORIES);
  
// bof sppc 	
// bof multi stores
  $customer_group_id = tep_get_cust_group_id() ;
// eof sppc   	
  //DWD Comment: Select Heading Text for Content Box based on current category.
  if ( (!isset($browse_category_id)) || ($browse_category_id == '0') ) {
    $browse_category_id = 0;
    $browse_category_heading = BOX_HEADING_BROWSE_TOP_CATEGORIES;
  } else {
    $browse_category_heading = BOX_HEADING_BROWSE_SUB_CATEGORIES;
  } // Checks to see if current category level is top or sub.

  //DWD Comment: Select all categories of current level.
  $categories_query = "select c.categories_id, cd.categories_name, c.categories_image
                         from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
                        where c.categories_id = cd.categories_id
						  and c.categories_status = '1'
						  and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
						  and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0 
                          and cd.language_id ='" . $languages_id . "'
                          and c.parent_id = '" . $browse_category_id . "'
                        order by c.sort_order, cd.categories_name";
  $arr_current_categories_query = tep_db_query($categories_query);
  //DWD Comment: Only show content box if there are categories at this level.
  if ((tep_db_num_rows($arr_current_categories_query) > 0) and (BRWCAT_ICON_MODE != 'off')) {

    //DWD Comment: Select parent category of current level.
    $parent_query = "select c.categories_id, cd.categories_name, c.parent_id
                       from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
                      where c.parent_id = cd.categories_id
					    and c.categories_status = '1'
                        and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
                        and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0 						
                        and cd.language_id ='" . $languages_id . "'
                        and c.categories_id = '" . $browse_category_id . "'";
    $arr_parent_category_query = tep_db_query($parent_query);
    $parent_category = tep_db_fetch_array($arr_parent_category_query);
    if ($parent_category['parent_id'] > 0) {
      //DWD Comment: Browse to parent category link from 3rd level categories to 2nd level category.
      $content_box_text_parent = '<a href="' . tep_href_link(FILENAME_DEFAULT . '?cPath=' . $parent_category['parent_id'], '', 'NONSSL') . '">' . TEXT_BROWSE_PARENT_CATEGORY . $parent_category['categories_name'] . '</a>';
    } else if ($browse_category_id > 0) {
      //DWD Comment: Browse to top-level category link.
      $content_box_text_parent = '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . TEXT_BROWSE_TOP_CATEGORY . '</a>';
    } else {
      // Top Category Level is 0, don't print back text.
    }
   // DWD Comment: Display Browse to ... Link.
    $content_box_contents = array();
    $content_box_contents[][] = array('align'  => 'left',
                                      'params' => 'class="smallText" valign="top" colspan=2"',
                                     'text'   => '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_cur, 'NONSSL') . '"><b>' . $current_category_name . '</b></a>');

    //DWD Comment: Initialize Content Box Row/Column Variables and Loop through all current categories.
    $row = 1;
    $col = 0;
    while ($current_categories = tep_db_fetch_array($arr_current_categories_query)) {
      $cPath_cur =  tep_get_path($current_categories['categories_id']);
      //DWD Comment: Apply Category Name Case setting to current category name.
      if (BRWCAT_NAME_CASE == 'same') {
        $current_category_name = $current_categories['categories_name'];
      } else {
        if (BRWCAT_NAME_CASE == 'upper') {
          $current_category_name = strtoupper($current_categories['categories_name']);
        } else if (BRWCAT_NAME_CASE == 'lower') {
          $current_category_name = strtolower($current_categories['categories_name']);
        } else if (BRWCAT_NAME_CASE == 'title') {
          $current_category_name = ucwords($current_categories['categories_name']);
        } else {
          //DWD Comment: Unknown Category Name Case.
        }
      }
      //DWD Comment: Select all Sub-Categories of Top-Category (Parent ID equal to Category ID).
      if (BRWCAT_SUBCAT_MODE != 'off') {   
        //DWD Comment: Sort rows by Sort Order and then Name.
        $sub_query = "select c.categories_id, cd.categories_name, c.categories_image
                        from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
                       where c.categories_id = cd.categories_id
                         and cd.language_id='" . $languages_id . "'
                         and c.parent_id ='" . $current_categories['categories_id'] . "'
	                     and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
                         and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0  							 
                       order by c.sort_order, cd.categories_name";
        $arr_sub_categories_query = tep_db_query($sub_query);

        //DWD Comment: Split Sub-Category Mode in bottom or right/valign.
        $arr_sub_category_mode = explode(' ', BRWCAT_SUBCAT_MODE);
        //DWD Comment: Build Sub Category Links.
        $sub_category_links = '';
        if (tep_db_num_rows($arr_sub_categories_query) > 0) {
          $sub_category_links .= '<ul class="BrowseBy">';
          while ($sub_categories = tep_db_fetch_array($arr_sub_categories_query)) {
            $cPath_new_sub = $cPath_cur . '_' . $sub_categories['categories_id'];
            $sub_category_links .= '<li class="BrowseBy">' . BRWCAT_SUBCAT_BULLET .
                                   '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new_sub, 'NONSSL') . '">' .
                                   $sub_categories['categories_name'] . '</a>';
            //DWD Comment: Display Sub-Category Product Count, if enabled.
            if (BRWCAT_SUBCAT_COUNTS != '') {
              $products_in_category = tep_count_products_in_category($sub_categories['categories_id']);
              if ($products_in_category > 0) {
                $sub_category_links .= sprintf('&nbsp;' . BRWCAT_SUBCAT_COUNTS, $products_in_category);
              }
            }

            $sub_category_links .= '';

          } // While Loop: Fetch all Query Rows. Each row is a Sub Category of current level Category.
          $sub_category_links .= '</ul>';
        } // Middle If: Build Sub Category Links if they exist.
      } // Outer If: Build Sub Category Links if enabled by Configuration Settings.

      //DWD Comment: Set Content Box Table Width depending on Sub-Category Link Mode.
      if ($arr_sub_category_mode[0] == 'right') {
        $table_cell_width = (100 / BRWCAT_ICONS_PER_ROW / 2) . '%';
      } else {
        $table_cell_width = (100 / BRWCAT_ICONS_PER_ROW) . '%';
      }
      //DWD Comment: Fill Content Box Array with Category Icon.
      //DWD Comment: BRWCAT_ICON_MODE is set on Control Panel: Configuration->My Store.
      //DWD Comment: If Mode is set as image only then the category name will be displayed on top of sub-category links.
      if (BRWCAT_ICON_MODE == 'text') {
        $content_box_contents[$row][$col] = array('align'  => 'center',
                                                  'params' => 'class="ui-state-active smallText" valign="top" width="' . $table_cell_width . '"',
                                                  'text'   => '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_cur, 'NONSSL') . '"><b>' .
                                                              $current_category_name . '</b></a>');
      } else if (BRWCAT_ICON_MODE == 'image only') {
        $content_box_contents[$row][$col] = array('align'  => 'center',
                                                  'params' => 'class="ui-widget-content smallText" valign="top" width="' . $table_cell_width . '"',
                                                  'text'   => '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_cur, 'NONSSL') . '">' .
                                                              tep_image(DIR_WS_IMAGES . $current_categories['categories_image'],
                                                                        $current_category_name,
                                                                        SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) .
                                                              '</a>');
        $sub_category_links = '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_cur, 'NONSSL') . '"><b>' .
                              $current_category_name . '</b></a><br>' . $sub_category_links;
      } else if (BRWCAT_ICON_MODE == 'image with caption') {
        $content_box_contents[$row][$col] = array('align'  => 'center',
                                                  'params' => 'class="ui-state-active smallText" valign="top" width="' . $table_cell_width . '"',
                                                  'text'   => '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_cur, 'NONSSL') . '">' .
                                                              tep_image(DIR_WS_IMAGES . $current_categories['categories_image'],
                                                                        $current_category_name,
                                                                        SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) .
                                                              '</a><br><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_cur, 'NONSSL') . '"><b>' .
                                                              $current_category_name . '</b></a>');
      } else {
        // Unknown Browse by Categories Content Box Type.
      } // Checks Type of Content Box for Browse by Categories.
      //DWD Comment: Fill Content Box Array with Sub-Category Links.
      if ($arr_sub_category_mode[0] == 'right') {
        $content_box_contents[$row][$col + 1] = array('align'  => 'left',
                                                      'params' => 'class="ui-widget-content smallText" valign="' . $arr_sub_category_mode[1] . '" width="' . $table_cell_width . '"',
                                                      'text'   => ($sub_category_links == '' && BRWCAT_SUBCAT_MODE != 'off') ? '&nbsp;' : $sub_category_links);
        $col = $col + 2;
        if ($col >= BRWCAT_ICONS_PER_ROW * 2) {
          $col = 0;
          $row++;
        }
      } else {
        $content_box_contents[$row + 1][$col] = array('align'  => 'left',
                                                      'params' => 'class="ui-widget-content smallText" valign="top" width="' . $table_cell_width . '"',
                                                      'text'   => ($sub_category_links == '' && BRWCAT_SUBCAT_MODE != 'off') ? '&nbsp;' : $sub_category_links);
        $col++;
        if ($col >= BRWCAT_ICONS_PER_ROW) {
          $col = 0;
          $row = $row + 2;
        }
      } // Inner If: Set Content Box contents based on Sub Categories Position
    } // While Loop: Grab all current level Categories.
 ?>
  <div class="panel panel-info">
    <div class="panel-heading"><?php echo $browse_category_heading; ?></div>
    <div class="panel-body"><?php echo $content_box_contents ; ?></div>
  </div>
<?php	
//  new contentBox($content_box_contents);
  } // Check if there are categories at this level.
?>