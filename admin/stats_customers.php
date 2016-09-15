<?php
/*
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2015 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  $page = $HTTP_GET_VARS['page'] ; 
  
  if (isset($HTTP_GET_VARS['viewedSortOrder']))
  {
    if ( ! tep_session_is_registered('viewedSortOrder') ) tep_session_register('viewedSortOrder');
    $viewedSortOrder = $HTTP_GET_VARS['viewedSortOrder'];
  }

  switch ($viewedSortOrder)
 {
      case "name-asc":
          $sortOrderViewed = "customers_lastname ASC , ordersum DESC";
          break;
      case "name-desc":
          $sortOrderViewed = "customers_lastname DESC, ordersum DESC";
          break;
      case "viewed-asc":
          $sortOrderViewed = "ordersum ASC , customers_lastname ASC ";
          break;
      case "viewed-desc":
          $sortOrderViewed = "ordersum DESC , customers_lastname ASC ";
          break;
      default:
          $sortOrderViewed = "customers_lastname ASC , ordersum DESC";
  }
  
  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->

        <table class="table table-hover table-striped table-responsive  table-condensed">	  
         <thead> 
           <tr>											 		   
             <th><?php echo TABLE_HEADING_NUMBER; ?></th>
             <th class="text-left">    <?php echo '<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, 'viewedSortOrder=name-asc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-by-alphabet' ) . '</a>'; ?>&nbsp;
				                       <?php echo TABLE_HEADING_CUSTOMERS; ?>&nbsp;
									   <?php echo '<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, 'viewedSortOrder=name-desc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-by-alphabet-alt' ) .  '</a>'; ?></th>	

             <th class="text-center"> <?php echo '<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, 'viewedSortOrder=viewed-desc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-numeric-desc' ) . '</a>'; ?>&nbsp;
				                      <?php echo TABLE_HEADING_TOTAL_PURCHASED; ?>&nbsp;
									  <?php echo '<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS, 'viewedSortOrder=viewed-asc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-numeric-asc ' ) . '</a>'; ?></th>									   
 
           </tr>
		 </thead>
        <tbody>	
<?php
			  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
			  $customers_query_raw = "select c.customers_firstname, c.customers_lastname, sum(op.products_quantity * op.final_price) as ordersum 
			                               from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o 
										   where c.customers_id = o.customers_id and o.orders_id = op.orders_id group by c.customers_firstname, c.customers_lastname order by " .  $sortOrderViewed;
			  $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
			// fix counted customers
			  $customers_query_numrows = tep_db_query("select customers_id from " . TABLE_ORDERS . " group by customers_id");
			  $customers_query_numrows = tep_db_num_rows($customers_query_numrows);
			  $rows = 0;
			  $customers_query = tep_db_query($customers_query_raw);
			  while ($customers = tep_db_fetch_array($customers_query)) {
				$rows++;
				if (strlen($rows) < 2) {
				  $rows = '0' . $rows;
				}
?>
              <tr onclick="document.location.href='<?php echo tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'], 'NONSSL'); ?>'">
                <td><?php echo $rows; ?>.</td>
                <td class="text-left"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $customers['customers_lastname'], 'NONSSL') . '">' . $customers['customers_lastname'] . ' ' . $customers['customers_firstname'] . '</a>'; ?></td>
                <td class="text-center"><?php echo tep_decode_specialchars( $currencies->format($customers['ordersum']) ); ?></td>
              </tr>
<?php
  }
?>
           </tbody>
            </table>
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>

    </table>			
</table>
	
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>