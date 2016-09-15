<?php
/*
  $Id$
  adapted for Separate Pricing Per Customer v4.2 2007/08/05

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
// BOF Separate Pricing Per Customer
  $customers_groups_query = tep_db_query("select customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
	while ($existing_groups = tep_db_fetch_array($customers_groups_query)) {
		$input_groups[] = array("id" => $existing_groups['customers_group_id'], "text" => $existing_groups['customers_group_name']);
		$all_groups[$existing_groups['customers_group_id']] = $existing_groups['customers_group_name'];
	}
// EOF Separate Pricing Per Customer

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        tep_set_specials_status($HTTP_GET_VARS['id'], $HTTP_GET_VARS['flag']);

        tep_redirect(tep_href_link(FILENAME_SPECIALS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'sID=' . $HTTP_GET_VARS['id'], 'NONSSL'));
        break;
      case 'insert':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $products_price = tep_db_prepare_input($HTTP_POST_VARS['products_price']);
        $specials_price = tep_db_prepare_input($HTTP_POST_VARS['specials_price']);
        $expdate = tep_db_prepare_input($HTTP_POST_VARS['expdate']);
// BOF Separate Pricing Per Customer
        $customers_group = tep_db_prepare_input($HTTP_POST_VARS['customers_group']);
        if (substr($specials_price, -1)  == '%') {
          $new_special_insert_query = tep_db_query("select products_id, products_price from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          $new_special_insert = tep_db_fetch_array($new_special_insert_query);
          $products_price = $new_special_insert['products_price'];

					$price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS. " WHERE products_id = ".(int)$products_id . " AND customers_group_id  = ".(int)$customers_group);
					while ($gprices = tep_db_fetch_array($price_query)) {
							$products_price = $gprices['customers_group_price'];
					}
          $specials_price = ($products_price - (($specials_price / 100) * $products_price));
				}
// EOF Separate Pricing Per Customer

        $expires_date = '';
        if (tep_not_null($expdate)) {
          $expires_date = substr($expdate, 0, 4) . substr($expdate, 5, 2) . substr($expdate, 8, 2);
        } 
		
		// check if combo excist
		 $check_special_excist_query = tep_db_query("select * from " . TABLE_SPECIALS . " where products_id=" . $products_id . " and customers_group_id=" . (int)$customers_group);
		 if (tep_db_num_rows($check_special_excist_query)) { // . "' where products_attributes_id = '" . (int)$attribute_id . "'"
		    tep_db_query("update " . TABLE_SPECIALS . " set products_id='" . (int)$products_id . "', 
			                                                specials_new_products_price='" . tep_db_input($specials_price) . "', 
															specials_date_added='now()', 
															expires_date=" . (tep_not_null($expires_date) ? "'" . tep_db_input($expires_date) . "'" : 'null') . ", 
															status='1', 
															customers_group_id=".(int)$customers_group."   
									  where products_id= '" . $products_id . "' and customers_group_id= '" . $customers_group . "'");
		 
	     } else {
// BOF Separate Pricing Per Customer
		    tep_db_query("insert into " . TABLE_SPECIALS . " (products_id, specials_new_products_price, specials_date_added, expires_date, status, customers_group_id) 
			                                         values ('" . (int)$products_id . "', '" . tep_db_input($specials_price) . "', now(), " . (tep_not_null($expires_date) ? "'" . tep_db_input($expires_date) . "'" : 'null') . ", '1', ".(int)$customers_group.")");
// EOF Separate Pricing Per Customer
			 
		 }

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'update':
        $specials_id = tep_db_prepare_input($HTTP_POST_VARS['specials_id']);
        $products_price = tep_db_prepare_input($HTTP_POST_VARS['products_price']);
        $specials_price = tep_db_prepare_input($HTTP_POST_VARS['specials_price']);
        $expdate = tep_db_prepare_input($HTTP_POST_VARS['expdate']);

        if (substr($specials_price, -1) == '%') $specials_price = ($products_price - (($specials_price / 100) * $products_price));

        $expires_date = '';
        if (tep_not_null($expdate)) {
          $expires_date = substr($expdate, 0, 4) . substr($expdate, 5, 2) . substr($expdate, 8, 2);
        }

        tep_db_query("update " . TABLE_SPECIALS . " set specials_new_products_price = '" . tep_db_input($specials_price) . "', specials_last_modified = now(), expires_date = " . (tep_not_null($expires_date) ? "'" . tep_db_input($expires_date) . "'" : 'null') . " where specials_id = '" . (int)$specials_id . "'");

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials_id));
        break;
      case 'deleteconfirm':
        $specials_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);

        tep_db_query("delete from " . TABLE_SPECIALS . " where specials_id = '" . (int)$specials_id . "'");

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
             <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE ; ?></h1>
            <div class="col-xs-12 col-md-6">
              <div class="row">              
			    <div class="col-md-10 col-xs-8">
<?php
                  echo '' . tep_draw_form('search', FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'], 'get', 'role="form" class="form-horizontal"'). PHP_EOL .
                       '      '. tep_draw_bs_input_field('search', '',HEADING_TITLE_SEARCH, 'id_input_search' , 'col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEARCH ) . PHP_EOL .
                       '      '. tep_hide_session_id() . PHP_EOL .							   
	                   '    </form>' . PHP_EOL ; 
?>
                </div>  					   
			    <div class="col-md-2 col-xs-4"> 
<?php				
                   if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {

		             echo tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_SPECIALS)); 
                   }		   				   
?>
                </div> 
              </div>
            </div> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                     <?php echo TABLE_HEADING_PRODUCTS;       ?></th>
                   <th class="text-center"> <?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></th>				    			   
                   <th class="text-center"> <?php echo TEXT_SPECIALS_SPECIAL_PRICE;  ?></th>	
                   <th class="text-center"> <?php echo TEXT_SPECIALS_GROUPS;         ?></th>					   
                   <th class="text-center"> <?php echo TABLE_HEADING_STATUS;         ?></th>				   
                   <th class="text-left"  > <?php echo TABLE_HEADING_ACTION;         ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php	
                 $search = '';
                 if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
                    $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
                    $search = " and pd.products_name like '%" . $keywords . "%'" ;
					//or c.customers_email_address like '%" . $keywords . "%' or c.customers_telephone like '%" . $keywords . "%' or entry_postcode like '%" . $keywords . "%'";	  
                 }		  
// BOF Separate Pricing Per Customer
				  $all_groups = array();
				  $customers_groups_query = tep_db_query("select customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
				  while ($existing_groups =  tep_db_fetch_array($customers_groups_query)) {
					$all_groups[$existing_groups['customers_group_id']] = $existing_groups['customers_group_name'];
				  }

				  $specials_query_raw = "select p.products_id, p.products_image, pd.products_name, p.products_price, s.specials_id, s.customers_group_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status 
				                         from " . TABLE_PRODUCTS . " p, " . 
										          TABLE_SPECIALS . " s, " . 
												  TABLE_PRODUCTS_DESCRIPTION . " pd 
										  where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id " . $search . " order by pd.products_name";

				  $customers_group_prices_query = tep_db_query("select s.products_id, s.customers_group_id, pg.customers_group_price from " . TABLE_SPECIALS . " s LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg using (products_id, customers_group_id) ");

				  while ($_customers_group_prices = tep_db_fetch_array($customers_group_prices_query)) {
				    $customers_group_prices[] = $_customers_group_prices;
				  }
// EOF Separate Pricing Per Customer
					
				  $specials_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $specials_query_raw, $specials_query_numrows);
				  $specials_query = tep_db_query($specials_query_raw);
// BOF Separate Pricing Per Customer
				  $no_of_rows_in_specials = tep_db_num_rows($specials_query);
				  while ($specials = tep_db_fetch_array($specials_query)) {
					for ($y = 0; $y < $no_of_rows_in_specials; $y++) {
						if ( tep_not_null($customers_group_prices[$y]['customers_group_price']) && $customers_group_prices[$y]['products_id'] == $specials['products_id'] && $customers_group_prices[$y]['customers_group_id'] == $specials['customers_group_id']) {
							$specials['products_price'] = $customers_group_prices[$y]['customers_group_price'] ;
						} // end if (tep_not_null($customers_group_prices[$y]['customers_group_price'] etcetera
					} // end for loop
// EOF Separate Pricing Per Customer
					if ((!isset($HTTP_GET_VARS['sID']) || (isset($HTTP_GET_VARS['sID']) && ($HTTP_GET_VARS['sID'] == $specials['specials_id']))) && !isset($sInfo)) {
//						$products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$specials['products_id'] . "'");
//						$products = tep_db_fetch_array($products_query);
//						$sInfo_array = array_merge($specials, $products);
//						$sInfo = new objectInfo($sInfo_array);
						$sInfo = new objectInfo($specials);						
					}

					if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) {
						echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '\'">' . "\n";
					  } else {
						echo '<tr                 onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials['specials_id']) . '\'">' . "\n";
					  }
?>
								    <td>                                          <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $specials['products_image'], $specials['products_name'], 40, 40, NULL, false) . $specials['products_name']; ?>      </td>
 								    <td class="text-center">                      <?php echo $currencies->format($specials['products_price']);                  ?>                                                                                 </td>
 								    <td class="text-center"><span class="mark">   <?php echo $currencies->format($specials['specials_new_products_price']) ;    ?>                                                                          </span></td>
 								    <td class="text-center"><span class="mark">   <?php echo $all_groups[$specials['customers_group_id']] ;                     ?>                                                                          </span></td>
 
								    <td  class="text-center">
<?php
					  if ($specials['status'] == '1') {
						echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=0&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . PHP_EOL;
					  } else {
						echo '                        <a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=1&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
					  }
?>					  
                                    <td class="text-right">								 
                                      <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('page', 'oID', 'action')) .'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials['specials_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('page', 'oID', 'action')) .'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials['specials_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('page', 'oID', 'action')) .'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials['specials_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                      </div> 
				                    </td>						
                          </tr>	
<?php
							  if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id) && isset($HTTP_GET_VARS['action'])) { 
											 $alertClass = '';
											 switch ($action) {			 
												case 'confirm':
												   $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
												   $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</div>' . PHP_EOL;
												   $contents .= '          <div class="panel-body">' . PHP_EOL;											
												   $alertClass .= ' alert alert-danger';
												   $contents .= '                      ' . tep_draw_form('specials', FILENAME_SPECIALS, tep_get_all_get_params(array('page', 'oID', 'action')) .'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id . '&action=deleteconfirm') . PHP_EOL;
												   $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
												   $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $sInfo->products_name . '<br />' . TEXT_SPECIALS_GROUPS . $all_groups[$sInfo->customers_group_id]  . '</p>' . PHP_EOL;
													
												   $contents .= '                        </div>' . PHP_EOL;
												   $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
												   $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
																							   tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('page', 'oID', 'action')) .'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id), null, null, 'btn-default text-danger') . PHP_EOL;
												   $contents .= '                        </div>' . PHP_EOL;
												   $contents .= '                      </form>' . PHP_EOL;
												   $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
												   $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
												  break;									 		  

												case 'edit':											
											
												   $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
												   $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_SPECIALS . '</div>' . PHP_EOL;
												   $contents            .= '          <div class="panel-body">' . PHP_EOL;			
												   $contents            .= '               ' . tep_draw_bs_form('edit_specials', FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=update&page=' . $HTTP_GET_VARS['page'], 'post', 'class="form-horizontal" role="form"', 'id_edit_specials') . PHP_EOL;													   
												   $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
												   $contents            .= '                           ' . tep_draw_hidden_field('specials_id', $HTTP_GET_VARS['sID']) . PHP_EOL;
												   $contents            .= '                           ' . tep_draw_hidden_field('products_price', $sInfo->products_price ) . PHP_EOL;  
												   $contents            .= '                       </div>' . PHP_EOL ;	
												   
												   $contents            .= '                        <ul class="list-group">' . PHP_EOL;
												   $contents            .= '                          <li class="list-group-item">' . PHP_EOL; 		
												   $contents            .= '                              ' . TEXT_SPECIALS_PRODUCT . '  ' . $sInfo->products_name . ' :  <span class="mark">(' . $currencies->format($sInfo->products_price) . ')</span>' . PHP_EOL;
												   $contents            .= '                          </li>' . PHP_EOL;
												   
                                                   for ($x=0; $x<count($input_groups); $x++) {
                                                      if ($input_groups[$x]['id'] == $sInfo->customers_group_id) {
                                                         $current_customer_group =  $input_groups[$x]['text'];
                                                      }
                                                   } // end for loop												   
												   
												   $contents            .= '                          <li class="list-group-item">' . PHP_EOL;		
												   $contents            .= '                              ' . TEXT_SPECIALS_GROUPS . '  ' . $current_customer_group . PHP_EOL;
												   $contents            .= '                          </li>' . PHP_EOL;										                          
												   $contents            .= '                        </ul>' . PHP_EOL;												   
												   
												   $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
												   $contents            .= '                           ' . tep_draw_bs_input_field('specials_price',      $sInfo->specials_new_products_price,        TEXT_SPECIALS_SPECIAL_PRICE,       'id_input_special_price' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_SPECIALS_SPECIAL_PRICE,       null, true ) . PHP_EOL;	
												   $contents            .= '                       </div>' . PHP_EOL ;									   

			                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;	
			                                       $contents            .= '                          <label for="id_expdate" class="col-xs-3">' . TEXT_SPECIALS_EXPIRES_DATE . '</label>' . PHP_EOL ;	
			                                       $contents            .= '                          <div class="col-xs-9">' . PHP_EOL ;	
			                                       $contents            .=                                 tep_draw_bs_input_date('expdate',                                               // name 
                                                                                                                                  tep_date_short($sInfo->expires_date),           // value
					                                                                                                              'id="id_expdate"',            // parameters
					                                                                                                              null,                                                // type
					                                                                                                              null,                                              // reinsert value
  					                                                                                                              TEXT_SPECIALS_EXPIRES_DATE                             // placeholder
					                                                                                                             ) ; 
			                                       $contents            .= '                             </div>' . PHP_EOL ;	
			                                       $contents            .= '                       </div>' . PHP_EOL ;				   
												   $contents            .= '                       <br />' . PHP_EOL;	
												   
												   $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
																									  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('page', 'oID', 'action')) .'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $HTTP_GET_VARS['sID'] ), null, null, 'btn-default text-danger') . PHP_EOL;			
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
													$contents .= '                              ' . TEXT_SPECIALS_EXPIRES_DATE . '<br />' . $sInfo->expires_date . PHP_EOL;
													$contents .= '                          </li>' . PHP_EOL;
													$contents .= '                          <li class="list-group-item">' . PHP_EOL;		
													$contents .= '                              ' . TEXT_INFO_DATE_ADDED . '  ' . $sInfo->specials_date_added . PHP_EOL;
													$contents .= '                          </li>' . PHP_EOL;					
													$contents .= '                          <li class="list-group-item">' . PHP_EOL;		
													$contents .= '                              ' . TEXT_INFO_LAST_MODIFIED . '  ' . $sInfo->specials_last_modified . PHP_EOL;
													$contents .= '                          </li>' . PHP_EOL;						                          
													$contents .= '                        </ul>' . PHP_EOL;
													$contents .= '                      </div>' . PHP_EOL;
													
													$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('page', 'oID', 'action')) .'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $HTTP_GET_VARS['sID'] ), null, null, 'btn-default text-danger') . PHP_EOL;										
												
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
                  } // end while ($specials = tep_db_fet					  
?>					  
			  </tbody>
		  </table>
		</div>  
   </table>	
   <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $specials_split->display_count($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $specials_split->display_links($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_SPECIALS, 'plus', null,'data-toggle="modal" data-target="#new_special_price"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  
   </table>   
       <div class="modal fade"  id="new_special_price" role="dialog" aria-labelledby="new_special_price" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_specials', FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=insert') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_SPECIALS; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
									   $exclude = array();
									   $specials_query = tep_db_query("select p.products_id, s.customers_group_id from " .  TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
									   while ($specials = tep_db_fetch_array($specials_query)) {
									      $exclude[] = (int)$specials['products_id'].":".(int)$specials['customers_group_id'];
									   }

									   if(isset($HTTP_GET_VARS['sID']) && $sInfo->customers_group_id!= '0') {
										  $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $sInfo->products_id . "' and customers_group_id =  '" . $sInfo->customers_group_id . "'");
										  if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
											$sInfo->products_price = $customer_group_price['customers_group_price'];
										  }
									   }
									   
									   $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by products_name");
									   while ($products = tep_db_fetch_array($products_query)) {
//										  if (!in_array($products['products_id'].":".$products['products_id'], $exclude)) {
//											$select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>';
										  if (!in_array($products['products_id'], $exclude)) {
											 $price_query=tep_db_query("select customers_group_price, customers_group_id from " . TABLE_PRODUCTS_GROUPS . " where products_id = " . $products['products_id']);
											 $product_prices=array();
											 while($prices_array=tep_db_fetch_array($price_query)){
												 $product_prices[$prices_array['customers_group_id']]=$prices_array['customers_group_price'];
											 }
											 reset($all_groups);
											 $price_string="";
											 $sde=0;
											 while(list($sdek,$sdev)=each($all_groups)){
												 if (!in_array((int)$products['products_id'].":".(int)$sdek, $exclude)) {
													 if($sde)
														$price_string.=", ";
													    $price_string.=$sdev.": ".$currencies->format(isset($product_prices[$sdek]) ? $product_prices[$sdek]:$products['products_price']);
													    $sde=1;
												 }
											 } 
                                             $select_specials_array[] = array('id'   => $products['products_id'],
                                                                              'text' => $products['products_name'] . '-' . $price_string);
										  }
									   }									   
									   
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_SPECIALS . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;	
									   
									   $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('products_id',     $select_specials_array, null, TEXT_SPECIALS_PRODUCT, 'id_input_product_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;	
									   $contents            .= '                       </div>' . PHP_EOL ;										   
                                       
									   $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_group', $input_groups,          null, TEXT_SPECIALS_GROUPS,  'id_input_customer_group_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;	
									   $contents            .= '                       </div>' . PHP_EOL ;										   
                                       									   
												   
									   $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
 								       $contents            .= '                           ' . tep_draw_bs_input_field('specials_price',      null,        TEXT_SPECIALS_SPECIAL_PRICE,       'id_input_special_price' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_SPECIALS_SPECIAL_PRICE,       null, true ) . PHP_EOL;	
 								       $contents            .= '                       </div>' . PHP_EOL ;									   

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;	
                                       $contents            .= '                          <label for="id_expdate" class="col-xs-3">' . TEXT_SPECIALS_EXPIRES_DATE . '</label>' . PHP_EOL ;	
                                       $contents            .= '                          <div class="col-xs-9">' . PHP_EOL ;	
                                       $contents            .=                                 tep_draw_bs_input_date('expdate',                                               // name 
                                                                                                                       null,           // value
	                                                                                                                   'id="id_uinput_expdate"',            // parameters
					                                                                                                   null,                                                // type
					                                                                                                   null,                                              // reinsert value
  					                                                                                                   TEXT_SPECIALS_EXPIRES_DATE                             // placeholder
					                                                                                                  ) ; 
			                                       $contents            .= '                             </div>' . PHP_EOL ;	
			                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents . $contents_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . (isset($HTTP_GET_VARS['sID']) ? '&sID=' . $HTTP_GET_VARS['sID'] : ''))); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_special_price -->    

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>