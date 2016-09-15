<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  tep_db_query("update " . TABLE_PRODUCTS . " set products_date_available = '' where to_days(now()) > to_days(products_date_available)");

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
                   <th>                    <?php echo TABLE_HEADING_PRODUCTS; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_DATE_EXPECTED; ?></th>				    			   				   
                   <th class="text-right" ><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
				  $products_query_raw = "select pd.products_id, pd.products_name, p.products_date_available from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p where p.products_id = pd.products_id and p.products_date_available != '' and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_available DESC";
				  $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
				  $products_query = tep_db_query($products_query_raw);
				  while ($products = tep_db_fetch_array($products_query)) {
					if ((!isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo)) {
					  $pInfo = new objectInfo($products);
					}

					if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
					  echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $products['products_id'] . '&action=new_product') . '\'">' . "\n";
					} else {
					  echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'page=' . $HTTP_GET_VARS['page'] . '&pID=' . $products['products_id']) . '\'">' . "\n";
					}
?>			  
                                 <td>                    <?php echo $products['products_name']; ?></td> 
                                 <td class="text-center"><?php echo tep_date_short($products['products_date_available']); ?></td>
                                 <td class="text-right">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_CATEGORIES, 'pID=' . $products['products_id'] . '&action=new_product'),    null, 'warning') . '</div>' . PHP_EOL  ;
?>
                                   </div> 
				               </td>						
               </tr>	
<?php				   
				} // end while while ($countries = tep_db_fetch_arra			   
?>				
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo  $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
		  
    </table>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>