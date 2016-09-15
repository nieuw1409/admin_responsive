<?php
/*
	$Id: low_stock report.php,v 2.MS2.rev0 2006/03/23  hpdl Exp $

	(v 1.1 by Alexandros Polydorou 2003/04/24; v 1.11 by Eric Lowe 2004/03/30; v 1.12 by Rob Woodgate 2004/04/01; v 1.15 by Aaron Hiatt 2004/11/09; v 1.16 by Rob Woodgate 2004/12/17; v 2.0 & v2.01 by Keith W. 2005/08/11; v 2.02 by Keith W. 2006/01/09)

	(v 2.MS2.rev0 by hakre 2006-03-23)

	osCommerce, Open Source E-Commerce Solutions
	http://www.oscommerce.com

	Copyright (c) 2002 osCommerce

	Released under the GNU General Public License
*/
	require('includes/application_top.php');
      $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
	    if ($action=='setflag') {
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }
	}
/*
* first of all a class is introduced to encapsulate all needed functions. 
* this is made to minimize crashes into the fragile OSC and contribs namespace
*/

	require(DIR_WS_CLASSES . 'stats_low_stock_class.php');

	$slsc = new stats_low_stock_class();

/*
* calculate start_date and end_date
* start default is now minus 2 month = 60 days = 1440 hours = 86400 minutes = 5184000 seconds
* 1 month is equal to 2592000
* end default is now
*
* edit: for what this period is used for and why is not documented.
*/

	$pastMonths = 2; //edit: if this is zero, the script throws warnings

	//edit: class variables?
	$start_date	= $slsc->httpGetVars('start_date', date('Y-m-d', time() -  $pastMonths * 2592000));

	$end_date		= $slsc->httpGetVars('end_date', date('Y-m-d'));
	
/* read in order and sorting values for the listing and sql query */

	$sorted = $HTTP_GET_VARS['sorted' ] ;
	//, 'ASC', array('ASC', 'DESC')] ;

	$get_orderby = $HTTP_GET_VARS[ 'orderby' ] ;
	$get_stock   = $HTTP_GET_VARS[ 'stock'];

    $orderby  = '';
	$db_orderby = 'p.products_quantity' ;		

	//db_orderby based on orderby
	switch($get_orderby) {
		case 'stock':
		default :
			$orderby  = 'stock';
			$db_orderby = 'p.products_quantity';
			break;

		case 'model':
			$db_orderby = 'p.products_model';
			break;

		case 'name':
			$db_orderby = 'pd.products_name';
			break;
			
		case 'status':
			$db_orderby = 'pd.products_status';
			break;

	}	
	
    require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE . ' : ' . STOCK_REORDER_LEVEL ; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th class="text-left">                    <?php echo TABLE_HEADING_NUMBER; ?></th>
                   <th class="text-left">  <?php echo ( $slsc->htmlCaptionSortLink('name', FILENAME_STATS_LOW_STOCK, TABLE_HEADING_PRODUCTS) ); ?></th>				    			   
                   <th class="text-left">  <?php echo ( $slsc->htmlCaptionSortLink('stock', FILENAME_STATS_LOW_STOCK, TABLE_HEADING_QTY_LEFT) ); ?></th>				   
                   <th class="text-left">  <?php echo ( $slsc->htmlCaptionSortLink('model', FILENAME_STATS_LOW_STOCK, TABLE_HEADING_PROD_ID) ); ?></th>	   
                   <th class="text-center"><?php echo TABLE_HEADING_SALES; ?></th>				   
                   <th class="text-center"><?php echo TABLE_HEADING_DAYS; ?></th>
                   <th class="text-left">  <?php echo ( $slsc->htmlCaptionSortLink('status', FILENAME_STATS_LOW_STOCK, TABLE_HEADING_STATUS) ); ?></th>				   
                </tr>
              </thead>
              <tbody>
<?php
				  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
					
				//	$rows = ((int)$HTTP_GET_VARS['page'] > 1) ? ( (int)$HTTP_GET_VARS['page'] - 1) * 100 : 0;

				/* SQL: setup query */

					// select query incl. orderby
					$products_query_raw = "select p.products_id, p.products_quantity, pd.products_name, p.products_model, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
							 where p.products_id = pd.products_id 
							  and pd.language_id = " . (int)$languages_id . " 
							  and p.products_quantity <= " . STOCK_REORDER_LEVEL . "  
									 group by pd.products_id order by p.products_quantity"   ;
				//	, TABLE_PRODUCTS, TABLE_PRODUCTS_DESCRIPTION, $languages_id, STOCK_REORDER_LEVEL, $db_orderby, $sorted);
					//limit results
					$products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
					
					//execute database query
					$products_query = tep_db_query($products_query_raw);

					while ($products = tep_db_fetch_array($products_query)) {
						$rows++;
					
						$products_id = $products['products_id'];

						/* get category path of item */

							// find the products category
							$last_category_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = $products_id");
							$last_category = tep_db_fetch_array($last_category_query);
							$p_category = $last_category["categories_id"];

							// store and find the parent until reaching root
							$p_category_array = array();		
							do
							{
								$p_category_array[] = $p_category;
												if  ($p_category == ""){
									//Dont run query this time, it will error. Skip to next record. 
								}else {
								$last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = $p_category");
								$last_category = tep_db_fetch_array($last_category_query);
								$p_category = $last_category["parent_id"];
								}
							}	while ($p_category);
							$cPath_array = array_reverse($p_category_array);
							unset($p_category_array);

						/* done */


						// Sold in Last x Months Query
						  $productSold_query = tep_db_query("select sum(op.products_quantity) as quantitysum FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op WHERE o.date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59' AND o.orders_id = op.orders_id AND op.products_id = $products_id GROUP BY op.products_id ORDER BY quantitysum DESC, op.products_id");
						  $productSold = tep_db_fetch_array($productSold_query);

						// Calculating days stock
						if ($products['products_quantity'] > 0) {
							$StockOnHand = $products['products_quantity'];
							$SalesPerDay = $productSold['quantitysum'] / ($pastMonths * 30);

							round ($SalesPerDay, 2);
							$daysSupply = 0;
							$display = y;
							if ($SalesPerDay > 0) {
							$daysSupply = $StockOnHand / $SalesPerDay;
							}

							round($daysSupply);
							if ($daysSupply <= '20') {
							  $daysSupply = '<div class="bg-danger text-danger"><b>' . round($daysSupply) . ' ' . DAYS . '</b></div>';
							} else {
							  $daysSupply = round($daysSupply) . ' ' . DAYS;
							}

							if (($SalesPerDay == 0) && ($StockOnHand > 1)) {
							  $display = n;
							  $daysSupply = '+60 '. DAYS;
							}

							if ($daysSupply > ($pastMonths * 30)) {
							$display = n;
							}

						} else {
						$daysSupply = '<div class="bg-danger text-danger"><b>NA</b></div>';
						$display = y;
						}

					//edit: skip, because I had no time to check the code above
					$display = 'y';
					if ($display == y) {

						// diverse urls used in output
						$url_newproduct = tep_href_link(FILENAME_CATEGORIES, tep_get_path() . '&pID=' . $products['products_id'] . '&action=new_product', 'NONSSL');
						$url_product = tep_href_link(FILENAME_CATEGORIES, tep_get_path() . '&pID=' . $products['products_id']);

						// some tweaking to make the output just looking better
						$prodsold = ($productSold['quantitysum'] > 0) ? (int)$productSold['quantitysum'] : 0;
						$prodmodel = trim((string)$products['products_model']);
						$prodmodel = (strlen($prodmodel)) ? htmlspecialchars($prodmodel) : '&nbsp;';

						// make negative qtys red b/c people have backordered them
						$productsQty = (int) $products['products_quantity'];
						$productsQty = ($productsQty < 0) ? sprintf('<font color="red"><b>%d</b></font>', $productsQty) : (string) $productsQty;
?>	
					    <tr onClick="document.location.href='<?php echo($url_newproduct); ?>'">
						  <td class="text-left" ><?php echo $rows; ?>.</td>
						  <td class="text-left" ><?php echo '<a href="' . $url_product . '" class="blacklink">' . $products['products_name'] . '</a>'; ?></td> 
						  <td class="text-left" ><?php echo $productsQty; ?></td>
						  <td class="text-left" ><?php echo '<a href="' . $url_product . '">' . $prodmodel . '</a>'; ?></td>
						  <td class="text-center"><?php echo($prodsold); ?></td>
						  <td class="text-center"><?php echo($daysSupply); ?></td>
						  <td class="text-left">
<?php		
						    if ($products['products_status'] == '1') {
						      echo tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath . '&page=' .$HTTP_GET_VARS['page']) . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>';  //.'&limite_1='.$limite_1.'&limite_2='.$limite_2
					        } else {
						      echo '<a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath . '&page=' .$HTTP_GET_VARS['page']) . '">' .  tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger');  // .'&limite_1='.$limite_1.'&limite_2='.$limite_2
					        }
?>
					      </td>
						</tr>
<?php
				        unset($cPath_array);
					}
			  }
?>
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], "orderby=" . $orderby . "&sorted=" . $sorted); ?></div>		   
	     </div> 
    </table>			  

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>