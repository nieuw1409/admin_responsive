<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License

  Discount Code 3.2
  http://high-quality-php-coding.com/
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        tep_db_query("update " . TABLE_DISCOUNT_CODES . " set status = '" . (int)$HTTP_GET_VARS['flag'] . "' where discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "' limit 1");

        tep_redirect(tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'insert':
      case 'save':	

          $expires_date	 = tep_db_prepare_input( tep_date_raw( $HTTP_POST_VARS['expires_date']) )  ;
		  $discount_codes = tep_db_prepare_input($HTTP_POST_VARS['discount_codes']) ;
		  $exclude_specials = (int)$HTTP_POST_VARS['exclude_specials'] ;
		  $order_info = (int)$HTTP_POST_VARS['order_info'] ;
		  $number_of_use = (int)$HTTP_POST_VARS['number_of_use'] ; 
		  $number_of_products = (int)$HTTP_POST_VARS['number_of_products'] ; 
//        if (!empty($HTTP_POST_VARS['discount_codes']) && !empty($HTTP_POST_VARS['discount_values'])) {
          $sql_data_array = array('products_id' => '',
                                  'categories_id' => '',
                                  'manufacturers_id' => '',
                                  'excluded_products_id' => '',
                                  'customers_id' => '',
                                  'orders_total' => '0',
                                  'order_info' => $order_info,
                                  'exclude_specials' => $exclude_specials,
                                  'discount_codes' => $discount_codes ,
                                  'discount_values' => tep_db_prepare_input($HTTP_POST_VARS['discount_values']),
                                  'minimum_order_amount' => tep_db_prepare_input($HTTP_POST_VARS['minimum_order_amount']),
                                  'expires_date' => $expires_date,
                                  'number_of_use' => $number_of_use,
                                  'number_of_products' => $number_of_products);

          $error = true;
          if ((int)$HTTP_POST_VARS['applies_to'] == 1) {
            if (is_array($HTTP_POST_VARS['products_id']) && sizeof($HTTP_POST_VARS['products_id']) > 0) {
              $sql_data_array['products_id'] = implode(',', $HTTP_POST_VARS['products_id']);
              $error = false;
            }
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 2) {
            if (is_array($HTTP_POST_VARS['categories_id']) && sizeof($HTTP_POST_VARS['categories_id']) > 0) {
              $sql_data_array['categories_id'] = implode(',', $HTTP_POST_VARS['categories_id']);
              $error = false;
            }
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 3) {
            $sql_data_array['orders_total'] = 1; // total
            $error = false;
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 4) {
            if (is_array($HTTP_POST_VARS['manufacturers_id']) && sizeof($HTTP_POST_VARS['manufacturers_id']) > 0) {
              $sql_data_array['manufacturers_id'] = implode(',', $HTTP_POST_VARS['manufacturers_id']);
              $error = false;
            }
          } elseif ((int)$HTTP_POST_VARS['applies_to'] == 5) {
            $sql_data_array['orders_total'] = 2; // subtotal
            $error = false;
          }

          if ((int)$HTTP_POST_VARS['applies_to'] == 2 || (int)$HTTP_POST_VARS['applies_to'] == 4) {
            if (is_array($HTTP_POST_VARS['excluded_products_id']) && sizeof($HTTP_POST_VARS['excluded_products_id']) > 0) {
              $sql_data_array['excluded_products_id'] = implode(',', $HTTP_POST_VARS['excluded_products_id']);
            }
          }

          if ((int)$HTTP_POST_VARS['applies_to'] != 3 && !empty($HTTP_POST_VARS['number_of_products'])) {
            $sql_data_array['number_of_products'] = (int)$HTTP_POST_VARS['number_of_products'];
          }

          if (!empty($HTTP_POST_VARS['customers']) && $HTTP_POST_VARS['customers'] == 1) {
            if (is_array($HTTP_POST_VARS['customers_id']) && sizeof($HTTP_POST_VARS['customers_id']) > 0) {
              $sql_data_array['customers_id'] = implode(',', $HTTP_POST_VARS['customers_id']);
            }
          }
	  
//          if ($error == false) {
            if ($action == 'insert') {
              tep_db_perform(TABLE_DISCOUNT_CODES, $sql_data_array);
              $messageStack->add_session(SUCCESS_DISCOUNT_CODE_INSERTED, 'success');
            } else {
              tep_db_perform(TABLE_DISCOUNT_CODES, $sql_data_array, 'update', "discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "'");
              $messageStack->add_session(SUCCESS_DISCOUNT_CODE_UPDATED, 'success');
            }
//            tep_redirect(tep_href_link(FILENAME_DISCOUNT_CODES));
//          }
//        }
        tep_redirect(tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] .  $expires_date ) );
        break;
      case 'deleteconfirm':
        tep_db_query("delete from " . TABLE_CUSTOMERS_TO_DISCOUNT_CODES . " where discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "'");
        tep_db_query("delete from " . TABLE_DISCOUNT_CODES . " where discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "' limit 1");

        $messageStack->add_session(SUCCESS_DISCOUNT_CODE_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }
 

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  require(DIR_WS_INCLUDES . 'template_top.php');

 
										$manufacturers_array = array();
										$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
										while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
										  $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
																		 'text' => $manufacturers['manufacturers_name']);
										}
					
										$product_array = array();
										$products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, s.specials_new_products_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . TABLE_SPECIALS . " s on (p.products_id = s.products_id and s.status = '1' and ifnull(s.expires_date, now()) >= now()) where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by products_name");
										while ($products = tep_db_fetch_array($products_query)) {
											$product_array[]         = array('id'   => $products['products_id'],
																			  'text' => $products['products_name'] . ' (' . (empty($products['specials_new_products_price']) ? '' : $currencies->format($products['specials_new_products_price']) . '/') . $currencies->format($products['products_price']) . ')' );
											$exclude_product_array[] = array('id'   => $products['products_id'],
																			 'text' => $products['products_name'] . ' (' . (empty($products['specials_new_products_price']) ? '' : $currencies->format($products['specials_new_products_price']) . '/') . $currencies->format($products['products_price']) . ')' );
																	 
										}	

										$customers_array = array();
										$customers_query = tep_db_query("select customers_id, concat(customers_lastname, ', ', customers_firstname, ' (', customers_email_address, ')') as customers_info from " . TABLE_CUSTOMERS . " order by customers_lastname, customers_firstname");
										while ($customers = tep_db_fetch_array($customers_query)) {
										  $customers_array[] = array('id'   => $customers['customers_id'],
																	 'text' => $customers['customers_info'] ) ;
										}    
?>

  <script language="javascript">
	function applies_to_onclick() {
		var a = document.new_discount_code.applies_to, b = document.getElementById("excluded_products_id"), c = document.getElementById("number_of_products"), d = document.getElementById("exclude_specials");
		for (var i = 0, n = a.length; i < n; i++) if (a[i].checked) { b.disabled = (a[i].value == 2 || a[i].value == 4 ? false : true); c.disabled = (a[i].value == 3 || a[i].value == 5 ? true : false); d.disabled = (a[i].value == 3 || a[i].value == 5 ? true : false) }
	}
	function customers_onclick() {
		var d = document.getElementById("customers"), e = document.getElementById("customers_id"); e.disabled = !d.checked;
	}
	function onload() {
		SetFocus();
		applies_to_onclick();
		customers_onclick();
	}
</script>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->

            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                <th>                                                                                                                                                  <?php echo TABLE_HEADING_DISCOUNT_CODE; ?></th>
                <th>                                                                                                                                                  <?php echo TABLE_HEADING_APPLIES_TO; ?></th>
                <th class="text-center">                                                                                                                              <?php echo TABLE_HEADING_DISCOUNT; ?></th>
                <th class="text-center" alt="<?php echo TABLE_HEADING_MINIMUM_ORDER_AMOUNT_FULL; ?>" title=" <?php echo TABLE_HEADING_MINIMUM_ORDER_AMOUNT_FULL; ?> "><?php echo TABLE_HEADING_MINIMUM_ORDER_AMOUNT; ?></th>
                <th class="text-center">                                                                                                                              <?php echo TABLE_HEADING_EXPIRY; ?></th>
                <th class="text-center" alt="<?php echo TABLE_HEADING_NUMBER_OF_ORDERS_FULL; ?>" title=" <?php echo TABLE_HEADING_NUMBER_OF_ORDERS_FULL; ?> ">        <?php echo TABLE_HEADING_NUMBER_OF_ORDERS; ?></th>
                <th class="text-left">                                                                                                                                <?php echo TABLE_HEADING_STATUS; ?></th>
                <th class="text-left" >                                                                                                                               <?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php			  
				$discount_codes_query_raw = "select dc.discount_codes_id, dc.products_id, dc.categories_id, dc.manufacturers_id, dc.excluded_products_id, dc.customers_id, dc.orders_total, dc.order_info, dc.exclude_specials, dc.discount_codes, dc.discount_values, dc.minimum_order_amount, dc.expires_date, dc.number_of_orders, dc.number_of_use, dc.number_of_products, dc.status from discount_codes dc order by dc.discount_codes_id desc";
				$discount_codes_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $discount_codes_query_raw, $discount_codes_query_numrows);
				$discount_codes_query = tep_db_query($discount_codes_query_raw);
				while ($discount_codes = tep_db_fetch_array($discount_codes_query)) {
				  $applies_to = '';
				  if (!empty($discount_codes['orders_total'])) {
					if ($discount_codes['orders_total'] == 1) {
					  $applies_to = TEXT_ORDER_TOTAL;
					} elseif ($discount_codes['orders_total'] == 2) {
					  $applies_to = TEXT_ORDER_SUBTOTAL;
					}
				  } elseif (!empty($discount_codes['products_id'])) {
					$applies_to = TEXT_PRODUCTS;
					$product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $discount_codes['products_id'] . ") and language_id = '" . (int)$languages_id . "' order by products_name");
					while ($product = tep_db_fetch_array($product_query)) {
					  $applies_to .= (empty($applies_to) ? '' : '<br>') . $product['products_name'];
					}
				  } elseif (!empty($discount_codes['categories_id'])) {
					$applies_to = TEXT_CATEGORIES;
					$category_query = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_id in (" . $discount_codes['categories_id'] . ") order by c.parent_id, cd.categories_name");
					while ($category = tep_db_fetch_array($category_query)) {
					  $applies_to .= (empty($applies_to) ? '' : '<br>') . tep_output_generated_category_path($category['categories_id']);
					}
				  } else {
					$applies_to = TEXT_MANUFACTURERS;
					$manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id in (" . $discount_codes['manufacturers_id'] . ") order by manufacturers_name");
					while ($manufacturer = tep_db_fetch_array($manufacturer_query)) {
					  $applies_to .= (empty($applies_to) ? '' : '<br>') . $manufacturer['manufacturers_name'];
					}
				  }
				  if (!empty($discount_codes['excluded_products_id'])) {
					$applies_to .= '<br>' . TEXT_EXCLUDED_PRODUCTS;
					$product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id in (" . $discount_codes['excluded_products_id'] . ") and language_id = '" . (int)$languages_id . "' order by products_name");
					while ($product = tep_db_fetch_array($product_query)) {
					  $applies_to .= (empty($applies_to) ? '' : '<br>') . $product['products_name'];
					}
				  }

				  if ((!isset($HTTP_GET_VARS['dID']) || (isset($HTTP_GET_VARS['dID']) && ($HTTP_GET_VARS['dID'] == $discount_codes['discount_codes_id']))) && !isset($dInfo) && (substr($action, 0, 3) != 'new')) {
					$dInfo = new objectInfo($discount_codes);
				  }

				  if (isset($dInfo) && is_object($dInfo) && ($discount_codes['discount_codes_id'] == $dInfo->discount_codes_id)) {
					echo '              <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id) . '\'">' . "\n";
				  } else {
					echo '              <tr                onclick="document.location.href=\'' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id']) . '\'">' . "\n";
				  }
?>				  
											<td>                      <?php echo $discount_codes['discount_codes']; ?></td>
											<td>                      <?php echo $applies_to; ?></td>
											<td class="text-center">  <?php echo strpos($discount_codes['discount_values'], '%') === false ? $currencies->format($discount_codes['discount_values']) : $discount_codes['discount_values']; ?>&nbsp;</td>
											<td class="text-center">  <?php echo $discount_codes['minimum_order_amount'] == '0.0000' ? '-' : $currencies->format($discount_codes['minimum_order_amount']); ?></td>
											<td class="text-center">  <?php echo $discount_codes['expires_date'] == '0000-00-00' ? '-' : tep_date_short($discount_codes['expires_date']); ?>&nbsp;</td>
											<td class="text-center">  <?php echo $discount_codes['number_of_orders']; ?></td>
											<td class="text-left">
											<?php
											  if ($discount_codes['status'] == '1') {
													echo '                    ' . tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=setflag&flag=0') . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . PHP_EOL ;
												} else {
													echo '                    <a href="' . tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=setflag&flag=1') . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
												}
											?>
											</td>
 											<td class="text-left">
												<div class="btn-toolbar" role="toolbar">                  
<?php
													  echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO, 'info-sign', tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=info'),      null, 'info')    . '</div>' . PHP_EOL  .
														   '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,      'pencil',    tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=edit'),      null, 'warning') . '</div>' . PHP_EOL  .
                                                           '             		  <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,    'remove',    tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $discount_codes['discount_codes_id'] . '&action=confirm'),   null, 'danger')  . '</div>' . PHP_EOL ;
?>
												</div> 
											  </td>												
                                        </tr>
<?php
                  if (isset($dInfo) && is_object($dInfo) && ($discount_codes['discount_codes_id'] == $dInfo->discount_codes_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_ZONE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('discount_codes', FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $discount_codes['discount_codes']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                            $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_ZONE . '</div>' . PHP_EOL;
			                            $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                            $contents            .= '               ' . tep_draw_bs_form('edit_discount_code',FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id . '&action=save', 'post', 'role="form"', 'id_edit_zones') . PHP_EOL;													   
 							   
									    if (!empty($dInfo->products_id))                   $array_products_id             = explode(',', $dInfo->products_id);
									    if (!empty($dInfo->categories_id))                 $array_categories_id           = explode(',', $dInfo->categories_id);
									    if (!empty($dInfo->manufacturers_id))              $array_manufacturers_id        = explode(',', $dInfo->manufacturers_id);
									    if (!empty($dInfo->excluded_products_id))          $array_excluded_products_id    = explode(',', $dInfo->excluded_products_id);
									    if (!empty($dInfo->customers_id))                  $array_customers_id            = explode(',', $dInfo->customers_id);										

										$contents            .= '    <div class="col-md-6 col-xs-12">' . PHP_EOL;
										
										$contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
	//									$contents            .= '          <div class="panel-heading">' . TEXT_INFO_NEW_DISCOUNT_CODE . '</div>' . PHP_EOL;
										$contents            .= '          <div class="panel-body">' . PHP_EOL;			
															
										$contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
										$contents            .= '                           ' . tep_draw_bs_input_field('discount_codes', $dInfo->discount_codes,        TEXT_DISCOUNT_CODE,       'id_input_discount_codes' ,        'col-xs-4', 'col-xs-8', 'left', TEXT_DISCOUNT_CODE,       '', true ) . PHP_EOL;	
										$contents            .= '                       </div>' . PHP_EOL ;	
										
										$contents            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;				
													
										
										$contents            .= '                       <div class="form-group">' . PHP_EOL ;										   					
										$contents            .= '                           ' . tep_draw_bs_input_field('discount_values', $dInfo->discount_values,        TEXT_DISCOUNT,       'id_input_discount_values' ,        'col-xs-4', 'col-xs-8', 'left', TEXT_DISCOUNT,       '', true ) . PHP_EOL;	
										$contents            .= '                       </div>' . PHP_EOL ;									   

										$contents            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;					

										$contents            .= '                       <div class="form-group">' . PHP_EOL ;										   					
										$contents            .= '                           ' . tep_draw_bs_input_field('minimum_order_amount', $dInfo->minimum_order_amount,        TEXT_MINIMUM_ORDER_SUB_TOTAL,       'id_input_min_order_amount' ,        'col-xs-4', 'col-xs-8', 'left', TEXT_MINIMUM_ORDER_SUB_TOTAL ) . PHP_EOL;	
										$contents            .= '                       </div>' . PHP_EOL ;									   
										
										$contents            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;				
										
										$contents            .= '                       <div class="form-group">' . PHP_EOL ;
										$contents            .= '			                 <label for="expires_date" class="col-xs-4 control-label">' . TEXT_EXPIRY . PHP_EOL ;
										$contents            .= '                            </label> ' . PHP_EOL ;
										$contents            .= '                            <div class="col-xs-8">'. PHP_EOL  ;						
										$contents            .=                                  tep_draw_bs_input_date('expires_date',                                               // name 
																														  tep_date_short($dInfo->expires_date),           // value
																														  'id="expires_date"'            // parameters
																														 ) ; 	
										$contents            .= '                            </div>'. PHP_EOL  ;																			 
										$contents            .= '                       </div>'. PHP_EOL  ;	
										
										$contents            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;	

										$contents            .= '                       <div class="form-group">' . PHP_EOL ;
										$contents            .= '                           <div class="checkbox checkbox-success">'. PHP_EOL  ;
										$contents            .=                                      tep_bs_checkbox_field('order_info', '1', TEXT_ORDER_INFO, 'id_input_order_info', $dInfo->order_info, 
																																							   'checkbox checkbox-success', 'control-label') . PHP_EOL ;	
										$contents            .= '                           </div>'. PHP_EOL  ;	
										$contents            .= '                       </div>'. PHP_EOL  ;						
										$contents            .= '                       <div class="form-group">' . PHP_EOL ;
										$contents            .= '                           <div class="checkbox checkbox-success">'. PHP_EOL  ;
										$contents            .=                                      tep_bs_checkbox_field('exclude_specials', '1', TEXT_EXCLUDE_SPECIALS, 'exclude_specials', $dInfo->exclude_specials, 
																																							   'checkbox checkbox-success') . PHP_EOL ;	
										$contents            .= '                           </div>'. PHP_EOL  ;	
										$contents            .= '                       </div>'. PHP_EOL  ;							
										
										$contents            .= '          </div>' . PHP_EOL; // end div 	panel body
										$contents            .= '       </div>' . PHP_EOL; // end div 	panel	
										$contents            .= '    </div>' . PHP_EOL ;					
										$contents            .= '    <div class="col-md-6 col-xs-12">' . PHP_EOL;
										
										$contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
	//									$contents            .= '          <div class="panel-heading">' . TEXT_INFO_NEW_DISCOUNT_CODE . '</div>' . PHP_EOL;
										$contents            .= '          <div class="panel-body">' . PHP_EOL;	

										$contents            .= '                       <div class="form-group">' . PHP_EOL ;										   					
										$contents            .= '                           ' . tep_draw_bs_input_field('number_of_use', $dInfo->number_of_use,        TEXT_NUMBER_OF_USE,       'id_input_number_of_us' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NUMBER_OF_USE  ) . PHP_EOL;	
										$contents            .= '                       </div>' . PHP_EOL ;	

										$contents            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;				
															

										$contents            .= '                       <div class="form-group">' . PHP_EOL ;										   					
										$contents            .= '                           ' . tep_draw_bs_input_field('number_of_products', $dInfo->number_of_products,        TEXT_NUMBER_OF_PRODUCTS,       'number_of_products' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NUMBER_OF_PRODUCTS  ) . PHP_EOL;	
										$contents            .= '                       </div>' . PHP_EOL ;									   
									
										
										$contents            .= '          </div>' . PHP_EOL; // end div 	panel body
										$contents            .= '       </div>' . PHP_EOL; // end div 	panel										
										
										$contents            .= '    </div>' . PHP_EOL ;	

										$contents            .= '    <div class="clearfix"></div><br />' . PHP_EOL ;

										$contents            .= '    <div class="panel panel-primary">' . PHP_EOL ;
										$contents            .= '          <div class="panel-heading">' . TEXT_INFO_DISCOUNT_CODE_APPLIES_TO . '</div>' . PHP_EOL;
										$contents            .= '          <div class="panel-body">' . PHP_EOL;		

										
										$contents            .= '                   <div class="form-group">' . PHP_EOL ;
								
										$contents            .= '                          <div class="checkbox checkbox-success">'. PHP_EOL  ;
										$contents            .=                                      tep_bs_radio_field('applies_to', '5', TEXT_ORDER_SUBTOTAL, 'id_applies_to_orders_subtotal', ($dInfo->orders_total == 2), 
																																								 'radio radio-success', 'col-xs-3', 'col-xs-4', 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
										$contents            .= '                           </div>'. PHP_EOL  ;						
										
										$contents            .= '                   </div>'. PHP_EOL  ;		
										$contents            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;						
										
										$contents            .= '                   <div class="input-group">' . PHP_EOL ;
										$contents            .= '                       <span class="input-group-addon">' . PHP_EOL ;					
										$contents            .= '                          <div class="radio radio-success">'. PHP_EOL  ;
										$contents            .=                                      tep_bs_radio_field('applies_to', '1', TEXT_PRODUCTS .  $dInfo->products_id, 'id_applies_to_products', (is_array($array_products_id )), 
																																								 ' radio radio-success ', 'col-xs-3', 'col-xs-2', 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
										$contents            .= '                          </div>'. PHP_EOL  ;						
										$contents            .= '                       </span">' . PHP_EOL ;					
					 
										$contents            .=                         tep_draw_bs_pull_down_menu('products_id[]', $product_array, $array_products_id , null, 'id_select_to_products', 'col-xs-7', ' selectpicker show-tick ', null, null, ' multiple ', null, true ) . PHP_EOL;
										
										$contents            .= '	                </div>' . PHP_EOL; 
										
										$contents            .= '                   <div class="input-group">' . PHP_EOL ;
										$contents            .= '                       <span class="input-group-addon">' . PHP_EOL ;					
										$contents            .= '                          <div class="radio radio-success">'. PHP_EOL  ;
										$contents            .=                                      tep_bs_radio_field('applies_to', '2', TEXT_CATEGORIES, 'id_applies_to_categories', (is_array($array_categories_id)), 
																																								 ' radio radio-success ', 'col-xs-3', 'col-xs-2', 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
										$contents            .= '                          </div>'. PHP_EOL  ;						
										$contents            .= '                       </span">' . PHP_EOL ;					
					 
										$contents            .=                         tep_draw_bs_pull_down_menu('categories_id[]', tep_get_category_tree('0', '', '0', $category_array), $array_categories_id, null, 'id_select_to_categories', 'col-xs-7', ' selectpicker show-tick ', null, 'left', ' multiple ', null, true ) . PHP_EOL;
										$contents            .= '	                </div>' . PHP_EOL; 

										$contents            .= '                   <div class="input-group">' . PHP_EOL ;
										$contents            .= '                       <span class="input-group-addon">' . PHP_EOL ;					
										$contents            .= '                          <div class="radio radio-success">'. PHP_EOL  ;
										$contents            .=                                      tep_bs_radio_field('applies_to', '4', TEXT_MANUFACTURERS, 'id_applies_to_manufacturers', (is_array($array_manufacturers_id)), 
																																								 'radio radio-success', 'col-xs-3', 'col-xs-2', 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
										$contents            .= '                          </div>'. PHP_EOL  ;						
										$contents            .= '                       </span">' . PHP_EOL ;					
					 
										$contents            .=                         tep_draw_bs_pull_down_menu('manufacturers_id[]', $manufacturers_array, $array_manufacturers_id, null, 'id_select_to_manufacturers', 'col-xs-7', ' selectpicker show-tick ', null, 'left', ' multiple ', null, true ) . PHP_EOL;
										$contents            .= '	                </div>' . PHP_EOL; 
										$contents            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;										

										$contents            .= '                   <div class="input-group">' . PHP_EOL ;
										$contents            .= '                       <span class="input-group-addon">' . PHP_EOL ;							
										$contents            .= '                          <div class="checkbox checkbox-success">'. PHP_EOL  ;
										$contents            .=                                      tep_bs_checkbox_field('customers', '1', TEXT_CUSTOMERS, 'customers', (is_array($array_customers_id)), 
																																								 'checkbox checkbox-success', 'col-xs-3', 'col-xs-2', 'right', 'onclick="customers_onclick();"') . PHP_EOL ;	
										$contents            .= '                          </div>'. PHP_EOL  ;						
										$contents            .= '                       </span">' . PHP_EOL ;					
					 
										$contents            .=                         tep_draw_bs_pull_down_menu('customers_id[]', $customers_array, $array_customers_id, null, 'customers_id', 'col-xs-7', ' selectpicker show-tick ', null, 'left', ' multiple ', null, true ) . PHP_EOL;
										$contents            .= '	                </div>' . PHP_EOL; 		
										$contents            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;						

										$contents            .= '	                <div class="form-group">' . PHP_EOL;
										$contents            .=                         tep_draw_bs_pull_down_menu('excluded_products_id[]', $exclude_product_array,  $array_excluded_products_id, TEXT_EXCLUDED_PRODUCTS, 'excluded_products_id', 'col-xs-7', ' selectpicker show-tick ', 'control-label col-xs-3', 'left', ' multiple ', null, true ) . PHP_EOL;
										$contents            .= '	                </div>' . PHP_EOL;						
										
										$contents            .= '          </div>' . PHP_EOL;	 // end div panel body
										$contents            .= '    </div>' . PHP_EOL;	// end div panel							

										$contents            .= '    <br />' . PHP_EOL;		
                                       
                                        $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id ), null, null, 'btn-default text-danger') . PHP_EOL;			
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
			                            $contents .= '                              ' . TEXT_ORDER_INFO . ' : '       . ($dInfo->order_info == '1' ? tep_glyphicon('ok-sign', 'success') : tep_glyphicon('remove-sign', 'danger'))  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_EXCLUDE_SPECIALS . ' : ' . ($dInfo->exclude_specials == '1' ? tep_glyphicon('ok-sign', 'success') : tep_glyphicon('remove-sign', 'danger'))  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_USE . '  ' . $dInfo->number_of_use . PHP_EOL; 
			                            $contents .= '                          </li>' . PHP_EOL;						                          
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_PRODUCTS . '  ' . $dInfo->number_of_products . PHP_EOL; 
			                            $contents .= '                          </li>' . PHP_EOL;											
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&dID=' . $dInfo->discount_codes_id  ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="8">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets				   
				} // end while while ($countries = tep_db_fetch_arra
?>				
			  </tbody>
			</table>
   </table> <!-- end table border="0" width="100%" cellspacing -->
 </div> <!-- end div table-responsive -->
     <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $discount_codes_split->display_count($discount_codes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $discount_codes_split->display_links($discount_codes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_DISCOUNT_CODE, 'plus', null,'data-toggle="modal" data-target="#new_discount_code"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  
    </table> 
      <div class="modal fade"  id="new_discount_code" role="dialog" aria-labelledby="new_discount_code" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_discount_code', FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert', 'post', 'role="form"', 'id_new_discount_code') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_DISCOUNT_CODE; ?></h4>
                  </div>
                  <div class="modal-body">
                    <div class="container-full">			  
<?php 
					   $dInfo = new objectInfo(array('products_id' => '',
													  'categories_id' => '',
													  'manufacturers_id' => '',
													  'excluded_products_id' => '',
													  'customers_id' => '',
													  'orders_total' => '2',
													  'order_info' => '',
													  'exclude_specials' => '',
													  'discount_codes' => substr(md5(uniqid(rand(), true)), 0, 8),
													  'discount_values' => '',
													  'minimum_order_amount' => '',
													  'expires_date' => '',
													  'number_of_orders' => '',
													  'number_of_use' => '1',
													  'number_of_products' => '1',
													  'status' => ''));
/*
						if (isset($HTTP_GET_VARS['dID'])) {
						  $discount_code_query = tep_db_query("select dc.discount_codes_id, dc.products_id, dc.categories_id, dc.manufacturers_id, dc.excluded_products_id, dc.customers_id, dc.orders_total, dc.order_info, dc.exclude_specials, dc.discount_codes, dc.discount_values, dc.minimum_order_amount, dc.expires_date, dc.number_of_orders, dc.number_of_use, dc.number_of_products, dc.status from discount_codes dc where dc.discount_codes_id = '" . (int)$HTTP_GET_VARS['dID'] . "'");
						  $discount_code = tep_db_fetch_array($discount_code_query);

						  $dInfo->objectInfo($discount_code);
/*
						  if (!empty($discount_code['products_id'])) $dInfo->products_id = explode(',', $discount_code['products_id']);
						  if (!empty($discount_code['categories_id'])) $dInfo->categories_id = explode(',', $discount_code['categories_id']);
						  if (!empty($discount_code['manufacturers_id'])) $dInfo->manufacturers_id = explode(',', $discount_code['manufacturers_id']);
						  if (!empty($discount_code['excluded_products_id'])) $dInfo->excluded_products_id = explode(',', $discount_code['excluded_products_id']);
						  if (!empty($discount_code['customers_id'])) $dInfo->customers_id = explode(',', $discount_code['customers_id']);
						  if ($discount_code['minimum_order_amount'] == '0.0000') $dInfo->minimum_order_amount = '';
						  if ($discount_code['expires_date'] == '0000-00-00') $dInfo->expires_date = '';
						  if ($discount_code['number_of_use'] == 0) $dInfo->number_of_use = '';
						  if ($discount_code['number_of_products'] == 0) $dInfo->number_of_products = '';
						//} elseif (tep_not_null($HTTP_POST_VARS)) {
						  //$dInfo->objectInfo($HTTP_POST_VARS);
 						  
						}

						$manufacturers_array = array();
						$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
						while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
						  $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
														 'text' => $manufacturers['manufacturers_name']);
						}
    
                        $product_array = array();
                        $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, s.specials_new_products_price from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . TABLE_SPECIALS . " s on (p.products_id = s.products_id and s.status = '1' and ifnull(s.expires_date, now()) >= now()) where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by products_name");
                        while ($products = tep_db_fetch_array($products_query)) {
                            $product_array[]         = array('id'   => $products['products_id'],
                                                              'text' => $products['products_name'] . ' (' . (empty($products['specials_new_products_price']) ? '' : $currencies->format($products['specials_new_products_price']) . '/') . $currencies->format($products['products_price']) . ')' );
                            $exclude_product_array[] = array('id'   => $products['products_id'],
                                                             'text' => $products['products_name'] . ' (' . (empty($products['specials_new_products_price']) ? '' : $currencies->format($products['specials_new_products_price']) . '/') . $currencies->format($products['products_price']) . ')' );
													 
                        }	

						$customers_array = array();
						$customers_query = tep_db_query("select customers_id, concat(customers_lastname, ', ', customers_firstname, ' (', customers_email_address, ')') as customers_info from " . TABLE_CUSTOMERS . " order by customers_lastname, customers_firstname");
						while ($customers = tep_db_fetch_array($customers_query)) {
						  $customers_array[] = array('id'   => $customers['customers_id'],
						                             'text' => $customers['customers_info'] ) ;
						}
 						
*/
					$contents_new            .= '    <div class="col-md-6 col-xs-12">' . PHP_EOL;
					
			        $contents_new            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			        $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_NEW_DISCOUNT_CODE . '</div>' . PHP_EOL;
			        $contents_new            .= '          <div class="panel-body">' . PHP_EOL;			
                    					
					$contents_new            .= '                       <div class="form-group">' . PHP_EOL ;									   
					$contents_new            .= '                           ' . tep_draw_bs_input_field('discount_codes', $dInfo->discount_codes,        TEXT_DISCOUNT_CODE,       'id_input_discount_codes' ,        'col-xs-4', 'col-xs-8', 'left', TEXT_DISCOUNT_CODE,       '', true ) . PHP_EOL;	
                    $contents_new            .= '                       </div>' . PHP_EOL ;	
					
					$contents_new            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;				
								
                    
					$contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   					
					$contents_new            .= '                           ' . tep_draw_bs_input_field('discount_values', $dInfo->discount_values,        TEXT_DISCOUNT,       'id_input_discount_values' ,        'col-xs-4', 'col-xs-8', 'left', TEXT_DISCOUNT,       '', true ) . PHP_EOL;	
					$contents_new            .= '                       </div>' . PHP_EOL ;									   

					$contents_new            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;					

					$contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   					
					$contents_new            .= '                           ' . tep_draw_bs_input_field('minimum_order_amount', $dInfo->minimum_order_amount,        TEXT_MINIMUM_ORDER_SUB_TOTAL,       'id_input_min_order_amount' ,        'col-xs-4', 'col-xs-8', 'left', TEXT_MINIMUM_ORDER_SUB_TOTAL ) . PHP_EOL;	
					$contents_new            .= '                       </div>' . PHP_EOL ;									   
					
					$contents_new            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;				
					
				    $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;
				    $contents_new            .= '			                 <label for="expires_date" class="col-xs-4 control-label">' . TEXT_EXPIRY . PHP_EOL ;
				    $contents_new            .= '                            </label> ' . PHP_EOL ;
				    $contents_new            .= '                            <div class="col-xs-8">'. PHP_EOL  ;						
					$contents_new            .=                                  tep_draw_bs_input_date('expires_date',                                               // name 
	                                                                                                  null,           // value
					                                                                                  'id="expires_date"'            // parameters
                                                                                                     ) ; 	
					$contents_new            .= '                            </div>'. PHP_EOL  ;																			 
					$contents_new            .= '                       </div>'. PHP_EOL  ;	
					
					$contents_new            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;	

				    $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;
					$contents_new            .= '                           <div class="checkbox checkbox-success">'. PHP_EOL  ;
					$contents_new            .=                                      tep_bs_checkbox_field('order_info', '1', TEXT_ORDER_INFO, 'id_input_order_info', null, 
												                                                                                           'checkbox checkbox-success', 'control-label') . PHP_EOL ;	
					$contents_new            .= '                           </div>'. PHP_EOL  ;	
					$contents_new            .= '                       </div>'. PHP_EOL  ;						
				    $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;
					$contents_new            .= '                           <div class="checkbox checkbox-success">'. PHP_EOL  ;
					$contents_new            .=                                      tep_bs_checkbox_field('exclude_specials', '1', TEXT_EXCLUDE_SPECIALS, 'exclude_specials', null, 
												                                                                                           'checkbox checkbox-success') . PHP_EOL ;	
					$contents_new            .= '                           </div>'. PHP_EOL  ;	
					$contents_new            .= '                       </div>'. PHP_EOL  ;							
					
		            $contents_new            .= '          </div>' . PHP_EOL; // end div 	panel body
		            $contents_new            .= '       </div>' . PHP_EOL; // end div 	panel	
			        $contents_new            .= '    </div>' . PHP_EOL ;					
					$contents_new            .= '    <div class="col-md-6 col-xs-12">' . PHP_EOL;
					
			        $contents_new            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			        $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_NEW_DISCOUNT_CODE . '</div>' . PHP_EOL;
			        $contents_new            .= '          <div class="panel-body">' . PHP_EOL;	

					$contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   					
					$contents_new            .= '                           ' . tep_draw_bs_input_field('number_of_use', $dInfo->number_of_use,        TEXT_NUMBER_OF_USE,       'id_input_number_of_us' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NUMBER_OF_USE,       '', true ) . PHP_EOL;	
					$contents_new            .= '                       </div>' . PHP_EOL ;	

					$contents_new            .= '                       <div class="clearfix"></div><br />' . PHP_EOL ;				
										

					$contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   					
					$contents_new            .= '                           ' . tep_draw_bs_input_field('number_of_products', $dInfo->number_of_products,        TEXT_NUMBER_OF_PRODUCTS,       'number_of_products' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_NUMBER_OF_PRODUCTS,       '', true ) . PHP_EOL;	
					$contents_new            .= '                       </div>' . PHP_EOL ;									   
				
					
		            $contents_new            .= '          </div>' . PHP_EOL; // end div 	panel body
		            $contents_new            .= '       </div>' . PHP_EOL; // end div 	panel										
					
			        $contents_new            .= '    </div>' . PHP_EOL ;	

					$contents_new            .= '    <div class="clearfix"></div><br />' . PHP_EOL ;
//					$contents_new            .= '    <br />' . PHP_EOL;						

			        $contents_new            .= '    <div class="panel panel-primary">' . PHP_EOL ;
			        $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_DISCOUNT_CODE_APPLIES_TO . '</div>' . PHP_EOL;
			        $contents_new            .= '          <div class="panel-body">' . PHP_EOL;		

					
				    $contents_new            .= '                   <div class="form-group">' . PHP_EOL ;
 			
					$contents_new            .= '                          <div class="checkbox checkbox-success">'. PHP_EOL  ;
					$contents_new            .=                                      tep_bs_radio_field('applies_to', '5', TEXT_ORDER_SUBTOTAL, 'id_applies_to_orders_subtotal', null, 
												                                                                                             'radio radio-success', null, null, 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
					$contents_new            .= '                           </div>'. PHP_EOL  ;						
 					
					$contents_new            .= '                   </div>'. PHP_EOL  ;		
					$contents_new            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;						
					
				    $contents_new            .= '                   <div class="input-group">' . PHP_EOL ;
				    $contents_new            .= '                       <span class="input-group-addon">' . PHP_EOL ;					
					$contents_new            .= '                          <div class="radio radio-success">'. PHP_EOL  ;
					$contents_new            .=                                      tep_bs_radio_field('applies_to', '1', TEXT_PRODUCTS, 'id_applies_to_products', null, 
												                                                                                             'radio radio-success', 'col-xs-3', 'col-xs-2', 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
					$contents_new            .= '                          </div>'. PHP_EOL  ;						
				    $contents_new            .= '                       </span">' . PHP_EOL ;					
 
			        $contents_new            .=                         tep_draw_bs_pull_down_menu('products_id[]', $product_array, null, null, 'id_select_to_products', 'col-xs-7', ' selectpicker show-tick ', null, null, ' multiple ', null, true ) . PHP_EOL;
			        $contents_new            .= '	                </div>' . PHP_EOL; 
//					$contents_new            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;					
					
				    $contents_new            .= '                   <div class="input-group">' . PHP_EOL ;
				    $contents_new            .= '                       <span class="input-group-addon">' . PHP_EOL ;					
					$contents_new            .= '                          <div class="radio radio-success">'. PHP_EOL  ;
					$contents_new            .=                                      tep_bs_radio_field('applies_to', '2', TEXT_CATEGORIES, 'id_applies_to_categories', null, 
												                                                                                             'radio radio-success', 'col-xs-3', 'col-xs-2', 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
					$contents_new            .= '                          </div>'. PHP_EOL  ;						
				    $contents_new            .= '                       </span">' . PHP_EOL ;					
 
			        $contents_new            .=                         tep_draw_bs_pull_down_menu('categories_id[]', tep_get_category_tree('0', '', '0', $category_array), null, null, 'id_select_to_categories', 'col-xs-7', ' selectpicker show-tick ', null, 'left', ' multiple ', null, true ) . PHP_EOL;
			        $contents_new            .= '	                </div>' . PHP_EOL; 
//					$contents_new            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;					

				    $contents_new            .= '                   <div class="input-group">' . PHP_EOL ;
				    $contents_new            .= '                       <span class="input-group-addon">' . PHP_EOL ;					
					$contents_new            .= '                          <div class="radio radio-success">'. PHP_EOL  ;
					$contents_new            .=                                      tep_bs_radio_field('applies_to', '4', TEXT_MANUFACTURERS, 'id_applies_to_manufacturers', null, 
												                                                                                             'radio radio-success', 'col-xs-3', 'col-xs-2', 'right', 'onclick="applies_to_onclick();"') . PHP_EOL ;	
					$contents_new            .= '                          </div>'. PHP_EOL  ;						
				    $contents_new            .= '                       </span">' . PHP_EOL ;					
 
			        $contents_new            .=                         tep_draw_bs_pull_down_menu('manufacturers_id[]', $manufacturers_array, null, null, 'id_select_to_manufacturers', 'col-xs-7', ' selectpicker show-tick ', null, 'left', ' multiple ', null, true ) . PHP_EOL;
			        $contents_new            .= '	                </div>' . PHP_EOL; 
//					$contents_new            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;					

				    $contents_new            .= '                   <div class="input-group">' . PHP_EOL ;
				    $contents_new            .= '                       <span class="input-group-addon">' . PHP_EOL ;							
					$contents_new            .= '                          <div class="checkbox checkbox-success">'. PHP_EOL  ;
					$contents_new            .=                                      tep_bs_checkbox_field('customers', '1', TEXT_CUSTOMERS, 'customers', null, 
												                                                                                             'checkbox checkbox-success', 'col-xs-3', 'col-xs-2', 'right', 'onclick="customers_onclick();"') . PHP_EOL ;	
					$contents_new            .= '                          </div>'. PHP_EOL  ;						
				    $contents_new            .= '                       </span">' . PHP_EOL ;					
 
			        $contents_new            .=                         tep_draw_bs_pull_down_menu('customers_id[]', $customers_array, null, null, 'customers_id', 'col-xs-7', ' selectpicker show-tick ', null, 'left', ' multiple ', null, true ) . PHP_EOL;
			        $contents_new            .= '	                </div>' . PHP_EOL; 		
					$contents_new            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;						

			        $contents_new            .= '	                <div class="form-group">' . PHP_EOL;
			        $contents_new            .=                         tep_draw_bs_pull_down_menu('excluded_products_id[]', $exclude_product_array, null, TEXT_EXCLUDED_PRODUCTS, 'excluded_products_id', 'col-xs-7', ' selectpicker show-tick ', 'control-label col-xs-3', 'left', ' multiple ', null, true ) . PHP_EOL;
			        $contents_new            .= '	                </div>' . PHP_EOL;						
//					$contents_new            .= '                   <div class="clearfix"></div><br />' . PHP_EOL ;	
					
			        $contents_new            .= '          </div>' . PHP_EOL;	 // end div panel body
			        $contents_new            .= '    </div>' . PHP_EOL;	// end div panel							

					$contents_new            .= '    <br />' . PHP_EOL;							                               
				
                    echo $contents_new . $contents_new_footer ; 
?>		
   
                    </div> <!-- end div coontainer -->
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button( IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_DISCOUNT_CODES, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_discount_code --> 	

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>