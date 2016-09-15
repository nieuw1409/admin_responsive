<?php
/*
  original: $Id: specialsbycategory.php,v 1.4 2005/06/29 23:03:00 calimeross Exp $
  adapted for Separate Pricing Per Customer (SPPC) 2005/07/20 JanZ (version 1.0)
  adapted for OSC 2.3.1 2012/01/13 JasonS (version 1.1)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<?php // <!----------------------- Actual code for Specials Admin starts here ------------------------->
  //Fetch all variables
  $fullprice = (isset($HTTP_POST_VARS['fullprice']) ? $HTTP_POST_VARS['fullprice'] : '');
  $productid = (isset($HTTP_POST_VARS['productid']) ? (int)$HTTP_POST_VARS['productid'] : '0');
  $inputupdate = (isset($HTTP_POST_VARS['inputupdate']) ? $HTTP_POST_VARS['inputupdate'] : '');
   if (isset($HTTP_POST_VARS['categories'])) {
	  $categories = (int)$HTTP_POST_VARS['categories'];
  } elseif (isset($_GET['categories'])) {
	 $categories = (int)$HTTP_GET_VARS['categories'];
  } else {
	  $categories = '0';
  }

  if (isset($HTTP_POST_VARS['manufacturer'])) { // from drop-down menu:
	  $manufacturer = (int)$HTTP_POST_VARS['manufacturer'];
  } elseif (isset($HTTP_POST_VARS['manufacturers_id'])) {
	  $manufacturer = (int)$HTTP_POST_VARS['manufacturers_id'];
  } elseif (isset($HTTP_GET_VARS['manufacturer'])) { // from page links
	  $manufacturer = (int)$HTTP_GET_VARS['manufacturer'];
  } else {
	  $manufacturer = '0';
  }
 
  if ($manufacturer) {
  	$man_filter = " and manufacturers_id = '". $manufacturer ."' ";
  } else {
    $man_filter = ' ';
  }

  if (isset($HTTP_POST_VARS['customers_groups'])) {   // from drop-down menu:
	$customers_group = (int)$HTTP_POST_VARS['customers_groups'];
  } elseif (isset($HTTP_POST_VARS['cg_id'])) {   // hidden values in forms
	$customers_group = (int)$HTTP_POST_VARS['cg_id'];
  } 
  
  if (isset($HTTP_POST_VARS['page'])) {
	  $this_page = $HTTP_POST_VARS['page'];
  } elseif (isset($HTTP_GET_VARS['page'])) {
	  $this_page = $HTTP_GET_VARS['page'];
  } else {
	  $this_page = '';
  }
	 
  if (array_key_exists('discount',$HTTP_POST_VARS)) {
  	if (is_numeric($HTTP_POST_VARS['discount'])) {
  	  $discount = (float)$HTTP_POST_VARS['discount'];
    } else {
  	  $discount = -1;    	
    }
  } else { 
  	$discount = -1;
  }

  if ($fullprice == 'yes') {
    tep_db_query("DELETE FROM " . TABLE_SPECIALS . " WHERE products_id = '" . $productid . "' AND customers_group_id = '" . $customers_group . "'");
  } elseif ($inputupdate == "yes") {
    $inputspecialprice = (isset($HTTP_POST_VARS['inputspecialprice']) ? $HTTP_POST_VARS['inputspecialprice'] : '');
    if (substr($inputspecialprice, -1) == '%') {
      $productprice = (isset($HTTP_POST_VARS['productprice']) ? (float)$HTTP_POST_VARS['productprice'] : '');
      $specialprice = ($productprice - (($inputspecialprice / 100) * $productprice));
    } elseif (substr($inputspecialprice, -1) == 'i') {
      $taxrate = (isset($HTTP_POST_VARS['taxrate']) ? (float)$HTTP_POST_VARS['taxrate'] : '1');
      $productprice = (isset($HTTP_POST_VARS['productprice']) ? (float)$HTTP_POST_VARS['productprice'] : '');
      $specialprice = ($inputspecialprice /(($taxrate/100)+1));
    } else {
     	$specialprice = $inputspecialprice;
    }
    $alreadyspecial = tep_db_query ("SELECT * FROM " . TABLE_SPECIALS . " WHERE products_id = '" . $productid. "' AND customers_group_id = '" . $customers_group . "'");
    $specialproduct= tep_db_fetch_array($alreadyspecial);
    if (tep_not_null($specialproduct['specials_id'])) {
      tep_db_query ("UPDATE " . TABLE_SPECIALS . " SET specials_new_products_price = '" . $specialprice . "', specials_last_modified = NOW() where specials_id = '" . $specialproduct['specials_id'] . "'"); 
    } else {
      tep_db_query ("INSERT INTO " . TABLE_SPECIALS . " (specials_id, products_id, specials_new_products_price, specials_date_added, specials_last_modified, expires_date, date_status_change, status, customers_group_id)VALUES ('','" . $productid . "','" . $specialprice . "',NOW(),NOW(),'0000-00-00 00:00','','1','" . $customers_group . "')");
    }
  }

  $customers_groups_query = tep_db_query("select customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
  while ($existing_groups =  tep_db_fetch_array($customers_groups_query)) {
      $input_groups[] = array("id" => $existing_groups['customers_group_id'], "text"=> $existing_groups['customers_group_name']);
      $all_groups[$existing_groups['customers_group_id']] = $existing_groups['customers_group_name'];
  }
  
  $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
     $manufacturers_array[] = array(
	        'id' => $manufacturers['manufacturers_id'],
            'text' => $manufacturers['manufacturers_name']
     );
  }  

  $contents            .= '    <div class="col-xs-8">' . PHP_EOL ;  
  $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
  $contents            .= '          <div class="panel-heading">' . TEXT_HEADING_SELECT . '</div>' . PHP_EOL;
  $contents            .= '          <div class="panel-body">' . PHP_EOL;			
  $contents            .= '               ' . tep_draw_bs_form('specialsbycategory', FILENAME_SPECIALS_BY_CATEGORIES, '', 'post', 'class="form-horizontal" role="form"', 'id_choose_category_special') . PHP_EOL;													   
  $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
  $contents            .= '                           ' . tep_draw_bs_pull_down_menu('categories', tep_get_category_tree(), $categories, TEXT_SELECT_CAT, 'id_select_cat', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;
  $contents            .= '                       </div>' . PHP_EOL ;	
  $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
  $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_groups', $input_groups, (isset($customers_group) ? $customers_group:''), TEXT_SELECT_CUST_GROUP, 'id_select_cust_group', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;
  $contents            .= '                       </div>' . PHP_EOL ;									   
  $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
  $contents            .= '                           ' . tep_draw_bs_pull_down_menu('manufacturer', $manufacturers_array, $manufacturer, TEXT_SELECT_MAN, 'id_select_manufactrer', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;
  $contents            .= '                       </div>' . PHP_EOL ;									   
  $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
  $contents            .= '                           ' . tep_draw_bs_input_field('discount',       ($discount > 0 ? $discount : '' ),       TEXT_ENTER_DISCOUNT,      'id_input_discount' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_ENTER_DISCOUNT ) . PHP_EOL;	
  $contents            .= '                       </div>' . PHP_EOL ;									   
  $contents            .= '                       <br />' . PHP_EOL;	
  $contents            .= '                   '.  tep_draw_bs_button(IMAGE_SAVE, 'ok', null, null, null, null, 'top_button') . PHP_EOL;	
  $contents            .= '                </form>' . PHP_EOL;                                         

  $contents            .= '          </div>' . PHP_EOL; // end div stores	panel body
  $contents            .= '       </div>' . PHP_EOL; // end div stores 	panel	
  $contents            .= '    </div>' . PHP_EOL ; 
  $contents            .= '    <div class="col-xs-4">' . PHP_EOL ;   
  $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
  $contents            .= '          <div class="panel-heading">' . TEXT_HEADING_OPTIONS . '</div>' . PHP_EOL;
  $contents            .= '          <div class="panel-body">' . PHP_EOL;		
  $contents            .= '                      <div class="col-xs-12">' . PHP_EOL;
  $contents            .= '                        <ul class="list-group">' . PHP_EOL;
  $contents            .= '                          <li class="list-group-item">' . PHP_EOL; 		
  $contents            .= '                              ' . TEXT_INSTRUCT_1 . PHP_EOL;
  $contents            .= '                          </li>' . PHP_EOL;
  $contents            .= '                          <li class="list-group-item">' . PHP_EOL;		
  $contents            .= '                              ' . TEXT_INSTRUCT_2 . PHP_EOL;
  $contents            .= '                          </li>' . PHP_EOL;	

  if (isset($HTTP_POST_VARS['top_button']) || (isset($HTTP_POST_VARS['inputupdate']) && $HTTP_POST_VARS['inputupdate'] == "yes") || isset($HTTP_GET_VARS['page'])) {  
     $contents         .= '                          <li class="list-group-item">' . PHP_EOL;		
     $contents         .= '                              ' . TEXT_INSTRUCT_3. PHP_EOL;
     $contents         .= '                          </li>' . PHP_EOL;						                          
     $contents         .= '                          <li class="list-group-item">' . PHP_EOL;		
     $contents         .= '                              ' . TEXT_INSTRUCT_4. PHP_EOL;
     $contents         .= '                          </li>' . PHP_EOL;	
     if( $customers_group != '0' )	 {
       $contents       .= '                          <li class="list-group-item">' . PHP_EOL;		
       $contents       .= '                              ' . TEXT_INSTRUCT_5. PHP_EOL;
       $contents       .= '                          </li>' . PHP_EOL;						 
     }
     $contents         .= '                          <li class="list-group-item">' . PHP_EOL;		
     $contents         .= '                              ' . TEXT_INSTRUCT_6. PHP_EOL;
     $contents         .= '                          </li>' . PHP_EOL;		 
  }	 
  $contents            .= '                        </ul>' . PHP_EOL;  
  $contents            .= '                      </div>' . PHP_EOL; 
  $contents            .= '          </div>' . PHP_EOL; // end div 	panel bo
  $contents            .= '       </div>' . PHP_EOL; // end div 	panel 
  $contents            .= '    </div>' . PHP_EOL ;   

  echo $contents ;
  
?>

 
<table border="0">
<?php
  if ($discount == -1) {
  	//echo 'do nothing';
  } elseif ($discount == '0') {
    if ($categories) {
      $result2 = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc WHERE p.products_id = ptc.products_id AND ptc.categories_id= '" . $categories. "' " . $man_filter . "");
	} else {
      $result2 = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p where " . substr($man_filter, 4) . ""); // substr chops of " and " from $man_filter
    }
    while ($row = tep_db_fetch_array($result2)) {
      $allrows[] = $row['products_id'];
    }
    if (tep_not_null($allrows)) { // implode will give an error when $allrows is empty
      tep_db_query("DELETE FROM " . TABLE_SPECIALS . " WHERE products_id in ('".implode("','",$allrows)."') AND customers_group_id = '" . $customers_group . "'");
    }
  } elseif ($discount > 0) {
    $specialprice = $discount / 100;

    if ($categories) {
	  $result2 = tep_db_query("select p.products_id, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc WHERE p.products_id = ptc.products_id AND ptc.categories_id = '" . $categories . "' " . $man_filter . "");
    } else {
      $result2 = tep_db_query("select p.products_id, p.products_price from " . TABLE_PRODUCTS . " p where " . ($where = substr($man_filter, 5) ? $where : 'p.products_id = p.products_id') . ""); // substr chops of " and " from $man_filter
	}
    $product_ids[] = '';
    while ($_row = tep_db_fetch_array($result2)) {
      $row3[] = $_row;
      $product_ids[] = $_row['products_id'];
    }
    // now get the group prices if necessary
    if ($customers_group != '0' && tep_not_null($product_ids)) {
	  $cg_prices_query = tep_db_query("select customers_group_price, products_id from " . TABLE_PRODUCTS_GROUPS . " where products_id in ('".implode("','", $product_ids)."') AND customers_group_id = '" . $customers_group . "'");
      while ($cg_prices = tep_db_fetch_array($cg_prices_query)) {
        for ($x = 0; $x < count($row3); $x++) {
          if ($row3[$x]['products_id'] == $cg_prices['products_id']) {
            $row3[$x]['products_price'] = $cg_prices['customers_group_price'];
          }
        }
      }
    }
    // now find those products that are already specials for this customer group
    $result3 = tep_db_query("select * from " . TABLE_SPECIALS . " where products_id in ('".implode("','", $product_ids)."') AND customers_group_id = '" . $customers_group . "'");
    $num_rows = tep_db_num_rows($result3);
    $specials_product_ids_array = array();
    if ($num_rows > 0) {
      while ($_result3 = tep_db_fetch_array($result3)) {
        $specials_product_ids_array[] = $_result3['products_id'];
      }
    }
      
    for ($x = 0; $x < count($row3); $x++) {
      $hello2 = $row3[$x]['products_price'];
      $hello3 = $hello2 * $specialprice;
      $hello4 = $hello2 - $hello3;
      $number = $row3[$x]['products_id'];

      if (in_array($row3[$x]['products_id'], $specials_product_ids_array)) {
        tep_db_query ("update " . TABLE_SPECIALS . " set specials_new_products_price = '" . $hello4 . "', specials_last_modified = NOW() where products_id = '" . $number. "' AND customers_group_id = '" . $customers_group . "'");
      } else {
        tep_db_query("insert into " . TABLE_SPECIALS . " (products_id, specials_new_products_price, specials_date_added, expires_date, status, customers_group_id) values ('" . $number . "', '" . $hello4 . "', NOW(), '0000-00-00 00:00', '1', '" . $customers_group . "')");
      }
    } 
  } 

  // only show the table when something was chosen
  if (isset($HTTP_POST_VARS['top_button']) || isset($HTTP_POST_VARS['inputupdate']) || isset($HTTP_GET_VARS['categories'])) {
     if (tep_not_null($categories) && !tep_not_null($manufacturer)) {
       $result2_query_raw = "select pd.products_name, p.products_price, p.products_id, ptc.categories_id, 'N' AS group_price, p.products_tax_class_id, NULL AS specials_new_products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES .
         " ptc, " . TABLE_PRODUCTS . " p where pd.products_id=ptc.products_id and p.products_id=ptc.products_id
         and ptc.categories_id = '" . $categories . "' and pd.language_id = " .(int)$languages_id . " order by pd.products_name asc ";
     } elseif (tep_not_null($manufacturer) && !tep_not_null($categories)) {
       $result2_query_raw = "select pd.products_name, p.products_price, p.products_id, 'N' AS group_price, p.products_tax_class_id, NULL AS specials_new_products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p where pd.products_id=p.products_id and pd.language_id = " .(int)$languages_id . $man_filter . " order by pd.products_name asc ";
     } else {
       $result2_query_raw = "select pd.products_name, p.products_price, p.products_id, ptc.categories_id, 'N' AS group_price, p.products_tax_class_id, NULL AS specials_new_products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES .
         " ptc, " . TABLE_PRODUCTS . " p where pd.products_id=ptc.products_id and p.products_id=ptc.products_id
         and ptc.categories_id = '" . $categories . "' and pd.language_id = " .(int)$languages_id . $man_filter . " order by pd.products_name asc ";
     }
   
     $result2_split = new splitPageResults($this_page, MAX_DISPLAY_SEARCH_RESULTS, $result2_query_raw,  $result2_query_numrows);
     $result2_query = tep_db_query($result2_query_raw);
    
     if (tep_db_num_rows($result2_query) > 0) { // only continue when there are products in this category ?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo TABLE_HEADING_PRODUCTS; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></th>
                   <th><?php echo TABLE_HEADING_SPECIAL_PRICE; ?></th>				   
                   <th><?php echo TABLE_HEADING_GROUP_PRICE; ?></th>				   
                   <th><?php echo TABLE_HEADING_PCT_OFF; ?></th>				   
                   <th><?php echo TABLE_HEADING_FULL_PRICE; ?></th>
				   <th><?php echo TABLE_HEADING_ACTION; ?></th>	
                </tr>
              </thead>
              <tbody>

<?php 
                while ($_row = tep_db_fetch_array($result2_query)) {
                   $row[] = $_row;
                   $product_ids[] = $_row['products_id'];
                }
                // now get the group prices if necessary
                if ($customers_group != '0') {
                  $cg_prices_query = tep_db_query("select customers_group_price, products_id from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $customers_group . "'");
                  while ($cg_prices = tep_db_fetch_array($cg_prices_query)) {
                     for ($x = 0; $x < count($row); $x++) {
                        if ($row[$x]['products_id'] == $cg_prices['products_id']) {
                          $row[$x]['products_price'] = $cg_prices['customers_group_price'];
                          $row[$x]['group_price'] = "Y";
                        }
                     }
                  }
                }

                // now add the special prices for the customers_group
                $result3 = tep_db_query("SELECT * FROM " . TABLE_SPECIALS . " where products_id IN ('" . implode("','", $product_ids) . "') AND customers_group_id = '" . $customers_group . "' ");
                while ( $row2 = tep_db_fetch_array($result3) ) {
                  for ($x = 0; $x < count($row); $x++) {
                    if ($row[$x]['products_id'] == $row2['products_id']) {
                       $row[$x]['specials_new_products_price'] = $row2['specials_new_products_price'];
                    }
                  }
                }


                for ($x = 0; $x < count($row); $x++) {
                   if (!tep_not_null($row[$x]['specials_new_products_price'])) {
                      $specialprice = "none";
                      $implieddiscount = '';
                   } else {
                      $specialprice = $row[$x]['specials_new_products_price'];
                      if ($row[$x]['products_price'] > 0) {
                        $implieddiscount = number_format(((($specialprice / $row[$x]['products_price'])*100)-100), 2, '.', '');
                        if ((int)$implieddiscount == $implieddiscount) { // round ##.00 to ##
                          $implieddiscount = (int)$implieddiscount;
                        }
                        $implieddiscount = $implieddiscount . "%";
                      } else {
                        $implieddiscount = '';
                      }
                   }
                   $tax_rate = tep_get_tax_rate($row[$x]['products_tax_class_id']); 
				   echo tep_draw_bs_form('spec_by_cat_p' . $row[$x]['products_id'], FILENAME_SPECIALS_BY_CATEGORIES, tep_get_all_get_params(array('inputspecialprice', 'fullprice')), 'POST', 'class="form-horizontal" role="form"', 'id_edit_price'. $row[$x]['products_id'])
?>				
                         <tr>
                            <td><?php echo $row[$x]['products_name'] ?></td>
                            <td><?php echo $row[$x]['products_price'] ?></td>			  
							<td>
<?php							
                                 $contents_1             = '                       <div class="form-group">' . PHP_EOL ;									   
								 $contents_1            .= '                           ' . tep_draw_bs_input_field('inputspecialprice',       $specialprice,    '',       'id_input_spec_price'.$row[$x]['products_id']  ) . PHP_EOL;	
                                 $contents_1            .= '                       </div>' . PHP_EOL ;
                                 
                                 echo	$contents_1  ;
?>
                            </td>	
                            <td><?php echo $implieddiscount ?></td>	
                            <td>
<?php 
                                 $contents_2          = '                           <div class="form-group">' . PHP_EOL ;											   
								 $contents_2         .=                                 tep_bs_checkbox_field('fullprice', 'yes', null, 'id_set_full_price', null, 'checkbox checkbox-success' ) . ' '  ;									   
                                 $contents_2         .=  tep_draw_hidden_field('cg_id',             $customers_group ) ;
								 $contents_2         .=  tep_draw_hidden_field('categories',        $categories  ) ;
								 $contents_2         .=  tep_draw_hidden_field('productprice',      $row[$x]['products_price'] ) ;
								 $contents_2         .=  tep_draw_hidden_field('taxrate',           $tax_rate ) ;
								 $contents_2         .=  tep_draw_hidden_field('manufacturers_id',  $manufacturer ) ;
								 $contents_2         .=  tep_draw_hidden_field('page',              $this_page ) ;
								 $contents_2         .=  tep_draw_hidden_field('productid',         $row[$x]['products_id'] ) ;
								 $contents_2         .=  tep_draw_hidden_field('inputupdate',       'yes' ) ;								 
                                 $contents_2         .= '                           </div>' . PHP_EOL ;									
                                 echo	$contents_2  ;								  
?>								  
                            </td>
                            <td class="text-right">
                              <div class="btn-toolbar" role="toolbar">  
<?php
								 $contents_3           =  tep_draw_bs_button(IMAGE_SAVE, 'pencil', null, null, null, 'btn-warning', 'button_save_'.$row[$x]['products_id']) ;
//								 $contents_3          .=  '<div class="btn-group">' . tep_glyphicon_button(IMAGE_SAVE,       'pencil',     null, null, 'warning')    . '</div>' . PHP_EOL ;
                                 echo	$contents_3  ;								 
/*							 
								              
               <input type="hidden" name="cg_id" value="<?php echo $customers_group ?>" />
               <input type="hidden" name="categories" value="<?php echo $categories ?>" />
               <input type="hidden" name="productprice" value="<?php echo $row[$x]['products_price'] ?>" />
               <input type="hidden" name="taxrate" value="<?php echo $tax_rate ?>" />
               <input type="hidden" name="manufacturers_id" value="<?php echo $manufacturer ?>" />
               <input type="hidden" name="page" value="<?php echo $this_page ?>" />
               <input type="hidden" name="productid" value="<?php echo $row[$x]['products_id'] ?>" />
               <input type="hidden" name="inputupdate" value="yes" />
               <input type="submit" value="<?php echo TEXT_BUTTON_UPDATE ?>" />
*/			   

?>
                              </div> 
				            </td>			   
       				     </tr>
				   </form>
<?php				   
                }	// for ($x = 0; $x < count($row); $			   
?>				
			  
			  </tbody>
		  </table>
		</div>
	</table> 
<?php
	 }
  }

require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>