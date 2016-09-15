<?php
/*
  $Id: stats_wishlists.php
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
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
                   <th>                    <?php echo TABLE_HEADING_NUMBER; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_CUSTOMERS; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_CUSTOMERS_COMPANY; ?></th>				   
                   <th class="text-right" ><?php echo TABLE_HEADING_CUSTOMERS_WISHLIST; ?></th>	   
                   <th class="text-right" ><?php echo TABLE_HEADING_CUSTOMERS_GROUP_NAME; ?></th>					   
                </tr>
              </thead>
              <tbody>
<?php
				  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
				  $customers_query_raw = "select a.entry_company, pd.products_name, w.products_id, c.customers_id, c.customers_firstname, c.customers_lastname from " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id where c.customers_id = w.customers_id and w.products_id = pd.products_id group by c.customers_id order by c.customers_lastname, c.customers_firstname desc";
				  $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
				  
				// fix counted customers
				  $customers_query_numrows = tep_db_query("select customers_id from " . TABLE_WISHLIST . " group by customers_id");
				  $customers_query_numrows = tep_db_num_rows($customers_query_numrows);
				  
				  $rows = 0;
				  $customers_query = tep_db_query($customers_query_raw);
				  
				  while ($customers = tep_db_fetch_array($customers_query)) {  
					
					$rows++;

					if (strlen($rows) < 2) {
					$rows = '0' . $rows;
					}
	
?>
					<tr  onClick="document.location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers&page=1&cID=' . $customers['customers_id'], 'NONSSL'); ?>'">
						<td class="text-center"><?php echo $rows; ?>.</td>
						<td>                    <?php echo $customers['customers_firstname'] . ' ' . $customers['customers_lastname']; ?></td>
						<td>                    <?php echo $customers['entry_company']; ?></td>
						<td>
<?php			 
							$products_query = tep_db_query("select w.products_id, pd.products_id, pd.products_name, c.customers_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CUSTOMERS . " c, " . TABLE_WISHLIST . " w where w.customers_id = " . (int)$customers['customers_id'] . " and w.products_id = pd.products_id and c.customers_id = w.customers_id group by pd.products_id order by pd.products_name");
							while ($products = tep_db_fetch_array($products_query)) {
							
							echo $products['products_name'] . '<br />';
							
							}
?>
						</td>
						<td class="dataTableContent" valign="top"><?php echo $customers['customers_group_name']; ?></td>				
					</tr>
<?php
                  }
?>
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>	  
    </table>
 
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>