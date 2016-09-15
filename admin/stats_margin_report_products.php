<?php
/*
  $Id: margin_report.php,v 3.00 2008/03/15  Exp $
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  percentage margin per order & order status filter by mr_absinthe 2008/03/15

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_INCLUDES . 'template_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $languages = tep_get_languages();


      $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id order by m.manufacturers_name";
      $filterlist_query = tep_db_query($filterlist_sql);
  
      if (tep_db_num_rows($filterlist_query) > 1) { 
        $manufacturers[] = array('id' => 'all', 'text' => TEXT_ALL_MANUFACTURERS);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $manufacturers[] = array('id' => $filterlist['id'], 'text' => $filterlist['name'] );
        }
      }
// BOF multi stores
      $stores_sql= "select distinct s.stores_id as id, s.stores_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_STORES . " s where p.products_status = '1' order by s.stores_name";
      $stores_query = tep_db_query($stores_sql);
  
      $stores_present = false ;
      if (tep_db_num_rows($stores_query) > 1) {
		$stores_present = true ;
        $stores[] = array('id' => 'all', 'text' => TEXT_ALL_STORES);

        while ($filterlist = tep_db_fetch_array($stores_query)) {
          $stores[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        } 
      }
// EOF multi stores	
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1  class="col-xs-12 col-md-4"><?php echo HEADING_TITLE; ?></h1> 
            <div class="col-xs-12 col-md-8 ">
			  <div class="row">  
			    <div>
<?php
                  if (isset($HTTP_GET_VARS['products_name']) && tep_not_null($HTTP_GET_VARS['products_name'])) {
					  $col_input = ' col-xs-5 ' ;
				  } else {
					  $col_input = ' col-xs-9 ' ;					  
				  }
                  echo '' . tep_draw_bs_form('customer_search', FILENAME_MARGIN_REPORT_PRODUCTS, tep_get_all_get_params(), 'get', 'role="form"', 'id_get_product_name' ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;				  
                  echo          tep_draw_bs_input_field('products_name', $HTTP_GET_VARS['products_name'],  TEXT_SEARCH_PRODUCT, 'report_product_select_cust_name' , 'control-label col-xs-3', $col_input, 'left', TEXT_SEARCH_PRODUCT	).  PHP_EOL; 
 				  echo '    </div>' . PHP_EOL;						  

                  if (isset($HTTP_GET_VARS['products_name']) && tep_not_null($HTTP_GET_VARS['products_name'])) {
			         echo '  <div class="col-xs-4">'. PHP_EOL ; 					  
		             echo       tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_MARGIN_REPORT_PRODUCTS)); 
                     echo '    </div>' . PHP_EOL ;					 
                  }		   				   
 				  echo '    <br />' . PHP_EOL;
 				  echo '    <br />' . PHP_EOL;				  
			  
				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 
?>
                </div>				  
			    <div>
<?php	 
                  echo '' . tep_draw_bs_form('filter', FILENAME_MARGIN_REPORT_PRODUCTS, '', 'get', 'role="form"', 'id_get_manufacturer' ). PHP_EOL ;
                  echo '    <div class="form-group">' . PHP_EOL;
			      echo          tep_draw_bs_pull_down_menu( 'manufacturer_id', $manufacturers, $HTTP_GET_VARS['manufacturer_id'], TEXT_SHOW, 'order_select_status', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"', null, true ) . PHP_EOL;
 				  echo '    </div>' . PHP_EOL;				  
				  echo          tep_hide_session_id() ;
	              echo '    </form>' . PHP_EOL ; 

                  echo '</div>' . PHP_EOL ;
 
                  if ( $stores_present == true ) {
				
                     echo '                <br />' . PHP_EOL ;
                     echo '                <br />' . PHP_EOL ;	
                     echo '			    <div>' . PHP_EOL ;

                     echo '               ' . tep_draw_bs_form('filter_store', FILENAME_MARGIN_REPORT_PRODUCTS, '', 'get', 'role="form"', 'id_filter_store' ). PHP_EOL ;
                     echo '                   <div class="form-group">' . PHP_EOL;
			         echo                         tep_draw_bs_pull_down_menu( 'stores_id', $stores, $HTTP_GET_VARS['stores'], TEXT_TITLE_STORES, 'order_select_status', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"', null, true ) . PHP_EOL;
 				     echo '                   </div>' . PHP_EOL;				  
				     echo                     tep_hide_session_id() ;
	                 echo '                 </form>' . PHP_EOL ; 
                     echo '             </div>' . PHP_EOL ;					 
				  }
?>
 					

              </div>			
              <div class="clearfix"></div>
            </div><!-- page-header-->

            <div class="table-responsive">			
             <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo TABLE_HEADING_PRODUCT; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_COST; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_PRICE; ?></th>				   
                   <th class="text-center"><?php echo TABLE_HEADING_SPECIAL_PRICE; ?></th>	   
                   <th class="text-center"><?php echo TABLE_HEADING_MARGIN_DOLLARS; ?></th>					   
                   <th class="text-center"><?php echo TABLE_HEADING_MARGIN_PERCENTAGE; ?></th>				   
                </tr>
              </thead>
              <tbody>
<?php
                 $products_name_search = '' ;
                 $products_name_parameter = '' ;				 
                  if (isset($HTTP_GET_VARS['products_name']) ) {
					$products_name_search = '  and pd.products_name like "%' . $HTTP_GET_VARS['products_name'] . '%"'  ;
					$products_name_parameter = '&products_name=' . $HTTP_GET_VARS['products_name'] ;
				 }
                 $manufacturers_search = '' ;
                 $manufacturers_parameter = '' ;				 
                  if (isset($HTTP_GET_VARS['manufacturer_id']) and ( $HTTP_GET_VARS['manufacturer_id'] != 'all') ) {
					$manufacturers_search = ' and m.manufacturers_id = ' . (int)$HTTP_GET_VARS['manufacturer_id'] . ' '  ;
					$manufacturers_parameter = '&manufacturer_id=' . $HTTP_GET_VARS['manufacturer_id'] ;					
				 }				 
// BOF multi stores
                 $stores_search = '' ;
                 $stores_parameter = '' ;				 
                 if ( isset($HTTP_GET_VARS['stores_id'] ) &&  $HTTP_GET_VARS['stores_id'] != '' ) {
                    $stores_search = " and ( find_in_set('" . $HTTP_GET_VARS['stores_id'] . "', p.products_to_stores) != 0 ) ";
                    $stores_parameter = '&stores_id=' . $HTTP_GET_VARS['stores_id'] ;				
                 }	
				 
                 $products_query_raw = "select p.products_id, p.products_price as products_price, p.products_cost, pd.products_name, IF(s.status, s.specials_new_products_price, NULL) as specials_price, if(s.status, s.specials_new_products_price-p.products_cost, p.products_price-p.products_cost) as margin_dollars, if(s.status, (s.specials_new_products_price-p.products_cost)/s.specials_new_products_price*100, (p.products_price-p.products_cost)/p.products_price*100) as margin_percentage, p2c.categories_id as category, c.parent_id as parent, s.customers_group_id as customers_group  
				                                   from (" . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_DESCRIPTION . " pd) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id 
												   where pd.language_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id and p2c.products_id = p.products_id and c.categories_id = p2c.categories_id and p.manufacturers_id = m.manufacturers_id " . $manufacturers_search . $stores_search . $products_name_search; 

                 $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
                 $products_query = tep_db_query($products_query_raw);
                 while ($products = tep_db_fetch_array($products_query)) {
                    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $products['products_id']))) && !isset($cInfo) ) { //&& (substr($action, 0, 3) != 'new')
                       $cInfo = new objectInfo($products);
                    }

                    if (isset($cInfo) && is_object($cInfo) && ($products['products_id'] == $cInfo->products_id)) {
                       echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_MARGIN_REPORT_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->products_id . '&action=edit') . '\'">' . PHP_EOL ;
                    } else {
                       echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_MARGIN_REPORT_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $products['products_id']) . '\'">' . PHP_EOL ;
                    }
?>					
                                 <td>
<?php							   
                                    if ($products['parent'] == '0') {
			                          echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $products['category'] . '&pID=' . $products['products_id'] . '&action=new_product') . '">' . $products['products_name'] . '</a>';
			                        } else {
			                          echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $products['parent'] . '_' . $products['category'] . '&pID=' . $products['products_id'] . '&action=new_product') . '">' . $products['products_name'] . '</a>';
			                        }									
?>
                                 </td>
                                 <td class="text-center"><?php echo $currencies->format($products['products_cost']); ?></td>
                                 <td class="text-center">
<?php							   
                                    if (tep_not_null($products['specials_price'])) {
			                          echo '<del>' . $currencies->format($products['products_price']) . '</del>' ;
			                        } else {
			                          echo $currencies->format($products['products_price']) ;
			                        }									
?>
                                 </td>								 
                                 <td class="text-center">
<?php							   
									$customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " 
											   where customers_group_id = '". $products['customers_group'] . "'");
									if (!tep_db_num_rows($customers_group_query) > 0) {
									   $text_group = ERROR_ALL_CUSTOMER_GROUPS_DELETED ;
									} else {
									   $customers_group = tep_db_fetch_array($customers_group_query) ;
									   $text_group = $customers_group['customers_group_name'] ;
									}  
								    if (tep_not_null($products['specials_price'])) {
									  echo '<span class="text-danger">' . $currencies->format($products['specials_price']) . '</span><span class="text-info">' . '&nbsp' . $text_group . '</span>&nbsp;';
									} else {
									  echo ' ' ;
									} 								
?>
                                 </td>									 
                                 <td class="text-center">
<?php							   
			                        if ($products['products_price'] > $products['products_cost']) {
			                          echo  $currencies->format($products['margin_dollars'])  ;
			                        } else {
			                          echo '<span class="bg-danger">' . $currencies->format($products['margin_dollars']) . '</span>' ;
			                        }									
?>
                                 </td>	
                                <td class="text-center">
<?php							   
			                        if ($products['products_price'] > $products['products_cost']) {
			                          echo  number_format($products['margin_percentage'], '2', '.', ',') . '%'  ;
			                        } else {
			                          echo '<span class="bg-danger">' . number_format($products['margin_percentage'], '2', '.', ',') . '%</span>' ;
			                        }									
?>
                                 </td>									  								 						
                           </tr>	
<?php
                 }
?>				 
			  
			  </tbody>
			</table>
		  <div>
   </table>
   <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], $products_name_parameter . $manufacturers_parameter . $stores_parameter ); ?></div>		   
	     </div>	  
    </table>

<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?> 
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>