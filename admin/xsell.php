<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action       = (isset($HTTP_GET_VARS['action'])       ? $HTTP_GET_VARS['action']       : '');
  $action_xsell = (isset($HTTP_GET_VARS['action_xsell']) ? $HTTP_GET_VARS['action_xsell'] : '');  

  if (tep_not_null($action_xsell)) {
    switch ($action_xsell) {
      case 'insert_xsell':
        $product_id               = tep_db_prepare_input($HTTP_GET_VARS['xID']);
        $product_xsell_code       = tep_db_prepare_input($HTTP_POST_VARS['product_xsell_id']);
        $product_xsell_reciprocal = tep_db_prepare_input($HTTP_POST_VARS['product_xsell_reciprocal']);
		$product_xsell_sort_order = (isset($HTTP_POST_VARS['product_xsell_sort_order']) ? tep_db_prepare_input($HTTP_POST_VARS['product_xsell_sort_order']) : tep_db_prepare_input('1'));
		
		$insert_array = array();
        $insert_array = array('products_id'   => $product_id,
                              'xsell_id'      => $product_xsell_code,
							  'sort_order'    => $product_xsell_sort_order);
        tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);
		
		if( $product_xsell_reciprocal == true ) {
		  $insert_array = array();
          $insert_array = array('products_id'   => $product_xsell_code,
                                'xsell_id'      => $product_id,
			   				    'sort_order'    => $product_xsell_sort_order);
          tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);			
			
		}

//        tep_db_query("insert into " . TABLE_PRODUCTS_XSELL . " (zone_country_id, zone_code, zone_name) values ('" . (int)$zone_country_id . "', '" . tep_db_input($zone_code) . "', '" . tep_db_input($zone_name) . "')");

        tep_redirect(tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $product_id . '&action=edit' ) ) ;
        break;
      case 'save':
        $product_id               = tep_db_prepare_input($HTTP_GET_VARS['xID']);
        $product_xsell_code       = tep_db_prepare_input($HTTP_POST_VARS['product_xsell_id']);
        $product_xsell_reciprocal = tep_db_prepare_input($HTTP_POST_VARS['product_xsell_reciprocal']);
		$product_xsell_sort_order = (isset($HTTP_POST_VARS['product_xsell_sort_order']) ? tep_db_prepare_input($HTTP_POST_VARS['product_xsell_sort_order']) : tep_db_prepare_input('1'));
		
		$insert_array = array();
        $insert_array = array('products_id'   => $product_id,
                              'xsell_id'      => $product_xsell_code,
							  'sort_order'    => $product_xsell_sort_order);	
							  
        tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array, 'update', "products_id = '" . (int)$product_id . "' and xsell_id = '" . (int)$product_xsell_code . "'");							  
		
		if( $HTTP_POST_VARS['product_xsell_reciprocal'] == true ) {
		  // check if xsell is present
//		  $xsold_query_reciprocal = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'. $product_xsell_code  .'" and xsell_id = "'. $product_id  . '"'); 
//		  $present = ((tep_db_num_rows($xsold_query_reciprocal) > 0) ? true : false) ;
		  
//		  if ( $present == true ) {
//            tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array, 'update', "products_id = '" . (int)$product_xsell_code  . "' and xsell_id = '" . (int)$product_id . "'");			  
//          } else {			  
          $insert_array = array('products_id'   => $product_xsell_code,
                                'xsell_id'      => $product_id,
			   				    'sort_order'    => $product_xsell_sort_order);
            tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);			
//		  }
			
		}	
		if( $HTTP_POST_VARS['product_xsell_reciprocal'] != true ) {
	       tep_db_query("delete from " . TABLE_PRODUCTS_XSELL . " where products_id = '" . (int)$product_xsell_code  . "' and xsell_id = '" . (int)$product_id . "'");
        }			

//        tep_db_query("update " . TABLE_ZONES . " set zone_country_id = '" . (int)$zone_country_id . "', zone_code = '" . tep_db_input($zone_code) . "', zone_name = '" . tep_db_input($zone_name) . "' where zone_id = '" . (int)$zone_id . "'");

        tep_redirect(tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $product_id . '&action=edit' ));
        break;
      case 'deleteconfirm':
        $product_id        = tep_db_prepare_input($HTTP_GET_VARS['xID']);
		$product_id_xsell  = tep_db_prepare_input($HTTP_GET_VARS['xsellID']);

        tep_db_query("delete from " . TABLE_PRODUCTS_XSELL . " where products_id = '" . (int)$product_id . "' and xsell_id = '" . (int)$product_id_xsell . "'");
		
		if( $HTTP_POST_VARS['product_xsell_reciprocal'] == true ) {
            tep_db_query("delete from " . TABLE_PRODUCTS_XSELL . " where products_id = '" . (int)$product_id_xsell . "' and xsell_id = '" . (int)$product_id . "'");
        }		

        tep_redirect(tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&page_edit_xsell=' . $HTTP_GET_VARS['page_edit_xsell'] . '&xID=' . $product_id . '&action=edit' ));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
			<div class="page-header">
				<h1 class="col-xs-12 col-md-4"><?php echo HEADING_TITLE; ?></h1>
				<div class="col-xs-12 col-md-8">
<?php 
					if ( (isset($HTTP_GET_VARS['category'])) && ($HTTP_GET_VARS['category'] != 0) ) {
						$category_filter =  " and p2c.categories_id = '" . (int)$HTTP_GET_VARS['category'] . "'";
					} else {
						$category_filter = "";
					}											
					if (isset($HTTP_GET_VARS['search'])) { 
						$search_string = " and ((pd.products_name like '%" . tep_db_prepare_input(addslashes($HTTP_GET_VARS['search'])) . "%') or (p.products_model like '%" . tep_db_prepare_input(addslashes($HTTP_GET_VARS['search'])) . "%'))";
						$searched_it = $HTTP_GET_VARS['search'] ; 					
					} else { 
						$search_string = "";
						$searched_it = '' ; 
					} 
					if (isset($HTTP_GET_VARS['search_plus_action'])) { 
						$searched_it = $HTTP_GET_VARS['search_plus_action'] ; 
						$search_plus_action_string = " and ((pd.products_name like '%" . tep_db_prepare_input(addslashes($HTTP_GET_VARS['search_plus_action'])) . "%') or (p.products_model like '%" . tep_db_prepare_input(addslashes($HTTP_GET_VARS['search_plus_action'])) . "%'))";
					} else { 
						$search_plus_action_string = "";
						$searched_it = '' ; 
					} 
				    if ($_GET['select_action'] == 'edit_xsell' || $_GET['select_action'] == 'new_xsell') {
						$xsell_array = array();
						$xsell_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_XSELL . " x where x.products_id = p.products_id");
									$num_xsell = tep_db_num_rows($xsell_query);	
						while ($xsell = tep_db_fetch_array($xsell_query)) {
						  $xsell_array[] = $xsell['products_id'];
						}
						// Build an array of products with cross sells
						$cleaned_xsell_array = array_unique($xsell_array);
						$list_string .= ' and p.products_id not in (';
						$list_string_ex .= ' and p.products_id in (';
						$i = 0;
						foreach ($cleaned_xsell_array as &$value) {
						  if ($i == 0) { // first item in array
							$list_string .= $value;
							$list_string_ex .= $value;
						  } else {
							$list_string .= ', ' . $value;
							$list_string_ex .= ', ' . $value;
						  }
						  $i++;
						} // end foreach
						$list_string .= ')';
						$list_string_ex .= ')';
						
						
						if ($i == 0) { // There are no products without cross sells
						  $list_string = '';
						  $list_string_ex = '';
						}				
					}

 
                    if ( $_GET['select_action'] == 'new_xsell') { 
					   $search_string = $search_plus_action_string . $list_string ;
				    }
                    if ($_GET['select_action'] == 'edit_xsell') {
 					   $search_string = $search_plus_action_string . $list_string_ex ;
				    }		
                    if ($_GET['select_action'] == 'all') {
 					   $search_string = $search_plus_action_string  ;
				    }							
 					
//				if ( (!isset($HTTP_GET_VARS['sort'])) && (!isset($HTTP_POST_VARS['add_related_product_ID'])) ) {

					  // PGM adds cross sell filter
				  $action_filter[] = array('id' => 'all',
											'text' => TEXT_ALL_PRODUCTS);
				  $action_filter[] = array('id' => 'edit_xsell',
											'text' => TEXT_PRODUCTS_WITH);
				  $action_filter[] = array('id' => 'new_xsell',
											'text' => TEXT_PRODUCTS_WITHOUT);				
	?>			
				  <div class="row">             
					<br />					
					<div>
	<?php
					  echo '' . tep_draw_bs_form('action', FILENAME_XSELL_PRODUCTS_PRODUCTS, '', 'get', 'role="form"' ). PHP_EOL ;
					  echo '    <div class="form-group">' . PHP_EOL;
					  echo          tep_draw_bs_pull_down_menu( 'select_action', $action_filter, '', TEXT_FILTER_XSELL, 'xsell_select_status', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
					  echo '    </div>' . PHP_EOL;	
					  echo '    <div class="form-group">' . PHP_EOL;				  
					  echo          tep_draw_hidden_field('search_plus_action', $HTTP_GET_VARS['search']	).  PHP_EOL; 
					  echo '    </div>' . PHP_EOL;						  
					  echo          tep_hide_session_id() ;
					  echo '    </form>' . PHP_EOL ; 
	?>
					</div>
					<br />
					<div>
	<?php
					  echo '' . tep_draw_bs_form('category', FILENAME_XSELL_PRODUCTS_PRODUCTS, '', 'get', 'role="form"' ). PHP_EOL ;
					  echo '    <div class="form-group">' . PHP_EOL;
					  echo          tep_draw_bs_pull_down_menu('category', tep_get_category_tree(), '', TEXT_CATEGORY_XSELL, 'xsell_select_category', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"', null, true ) . PHP_EOL;
					  echo '    </div>' . PHP_EOL;				  
					  echo          tep_hide_session_id() ;
					  echo '    </form>' . PHP_EOL ; 
	?>
					</div>	

				    <div>
	<?php
						 echo '' . tep_draw_bs_form('search', FILENAME_XSELL_PRODUCTS_PRODUCTS, '', 'get', 'role="form"'  ). PHP_EOL ;				
						 echo '    <div class="form-group">' . PHP_EOL;				  
						 echo          tep_draw_bs_input_field('search', $searched_it,  TEXT_SEARCH_XSELL, 'xsell_input_search' , 'control-label col-xs-3', 'col-xs-8', 'left', TEXT_SEARCH_XSELL	).  PHP_EOL; 
						 echo '    </div>' . PHP_EOL;					  
	?>				  
					   </div>  					   
	<?php				
					   if ( ( isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search']) ) or ( isset($search_plus_action_string) && tep_not_null($search_plus_action_string) )) {
						 echo '<div class="col-xs-1">'. PHP_EOL ;
						 echo    tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_XSELL_PRODUCTS_PRODUCTS )); 
						 echo '</div>'. PHP_EOL ;
					   }		   				   
	?>
					</div> 
	<?php				
					  echo          tep_hide_session_id() ;
					  echo '    </form>' . PHP_EOL ; 
	?>
					</div> 	
					<br />	

					<br />	
					<br />
				  </div>
	<?php
//				}

	?>			
					
				  
				</div>
				<div class="clearfix"></div>
			</div><!-- end page-header-->
            <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th><?php echo TABLE_HEADING_PRODUCT_ID   ; ?></th>
                   <th><?php echo TABLE_HEADING_PRODUCT_MODEL; ?></th>				    			   
                   <th><?php echo TABLE_HEADING_PRODUCT_IMAGE; ?></th>				   
                   <th><?php echo TABLE_HEADING_PRODUCT_NAME; ?></th>				  
                   <th><?php echo TABLE_HEADING_CURRENT_SELLS; ?></th>				  
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
                 $products_query_raw = 'select distinct p.products_id, p.products_model, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id from ' . TABLE_PRODUCTS . ' p, ' . TABLE_PRODUCTS_DESCRIPTION . ' pd, ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c 
						  where p.products_id = pd.products_id ' . $search_string . ' and pd.language_id = "'.(int)$languages_id.'" and p.products_id = p2c.products_id ' . $category_filter .   ' group by p.products_id order by p.products_id asc';

				 
		         $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
		         $products_query = tep_db_query($products_query_raw);
			  
                 while ($products = tep_db_fetch_array($products_query)) {
                    if ((!isset($HTTP_GET_VARS['xID']) || (isset($HTTP_GET_VARS['xID']) && ($HTTP_GET_VARS['xID'] == $products['products_id']))) && !isset($cInfo) ) { //&& (substr($action, 0, 3) != 'new')
                       $cInfo = new objectInfo($products);
                    }

		            if ($products['products_model'] == NULL) {
		               $products_model = TEXT_NONE;
		            } else {
		               $products_model = $products['products_model'];
		            } // end if ($products['products_model'] == NULL)					
                    if (isset($cInfo) && is_object($cInfo) && ($products['products_id'] == $cInfo->products_id)) {
                       echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_XSELL_PRODUCTS_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&xID=' . $cInfo->products_id . '&action=edit') . '\'">' . PHP_EOL ;
                    } else {
                       echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_XSELL_PRODUCTS_PRODUCTS, 'add_related_product_ID=' . $cInfo->products_id, 'NONSSL') . '\'">' . "\n";
                    }
?>			  
                                 <td>                    <?php echo $products['products_id']; ?></td>
                                 <td>                    <?php echo  $products_model; ?></td>
                                 <td class="text-center"><?php echo tep_image(DIR_WS_CATALOG_IMAGES  . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></td>
                                 <td>                    <?php echo $products['products_name']; ?></td>
<?php 
                 if (isset($cInfo) && is_object($cInfo) && ($products['products_id'] == $cInfo->products_id) && isset($HTTP_GET_VARS['action'])) { 
?>					 
                                 <td class="text-center">
                                 </td>
<?php
                 } else {
?>					 
                                 <td class="text-center">
								    <ul class="list-group">
<?php
                                        $products_cross_query = tep_db_query('select p.products_id, p.products_model, pd.products_name, x.products_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x 
										                            where x.xsell_id = p.products_id and x.products_id = "'.$products['products_id'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc');
					 					$contents_list_items = '' ;		
			                            $i=0;
			                            while ($products_cross = tep_db_fetch_array($products_cross_query)) {
			                               $i++;
					                       $contents_list_items .= '<li class="list-group-item">' . PHP_EOL ;
					                       $contents_list_items .= '     <div class="row">' . PHP_EOL ;	
					                       $contents_list_items .= '          <div class="col-xs-1"><label>'. $i . '</label></div>' . PHP_EOL ;	
					                       $contents_list_items .= '          <div class="col-xs-5"><span class="control-label">' . $products_cross['products_model'] . '</span></div>' . PHP_EOL ;	
					                       $contents_list_items .= '          <div class="col-xs-6"><span class="control-label">' . $products_cross['products_name']  . '</span></div> ' . PHP_EOL ;	
					                       $contents_list_items .= '     </div>' . PHP_EOL ;	  //$i . '<b>' . $products_cross['products_model'] . '</b>' . $products_cross['products_name'] . 
					                       $contents_list_items .= '</li>' . PHP_EOL ; 
			                            } // end while ($products_cross = tep_db_fetch_array($products_cross_query))
		    
 			                            if ($i > 0) {
							              echo $contents_list_items ;
						                }
?>
                                    </ul>
								 </td>								 
<?php
				 }
?>				 
                                 <td class="text-right">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&xID=' . $products['products_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL ;		
//                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&xID=' . $products['products_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				               </td>						
               </tr>	
<?php
//		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ADD,             'plus',          tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&xID=' . $products['products_id'] . '&action=add'),     null, 'warning') . '</div>' . PHP_EOL .
                  if (isset($cInfo) && is_object($cInfo) && ($products['products_id'] == $cInfo->products_id) && isset($HTTP_GET_VARS['action'])) { 

	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-danger">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_XSELL_PRODUCT . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('zones', FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&xID=' . $cInfo->products_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $products['products_name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&xID=' . $cInfo->products_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_XSELL_PRODUCT . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;
									   
									   $content_edit_xsell = '' ; 
                                       include( DIR_WS_MODULES . 'xsell_edit_xsell_products.php' ) ;									   
									   $contents            .=  $content_edit_xsell ;							   
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&xID=' . $cInfo->products_id), null, null, 'btn-default text-danger') . PHP_EOL;			
//		                               $contents_footer .= '                      </form>' . PHP_EOL;
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
			                            $contents .= '                              ' . TEXT_INFO_ZONES_NAME . '<br />' . $cInfo->zone_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_CODE . '  ' . $cInfo->zone_code . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_NAME . '  ' . tep_get_country_name( $cInfo->countries_id ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ZONES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  	  
    </table>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>