<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
// bof multi stores
    $customer_group_id = tep_get_cust_group_id() ;
// eof multi stores	
   
	
  $expected_query = tep_db_query("select p.products_id, pd.products_name, products_date_available as date_expected from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
      where to_days(products_date_available) >= to_days(now())                 and p.products_id = pd.products_id 
	    and pd.language_id = '" . (int)$languages_id . "' 
	    and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0       and find_in_set('" . $customer_group_id . "', categories_hide_from_groups) = 0 
		and find_in_set('" . SYS_STORES_ID . "',      categories_to_stores) != 0    and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 		
		order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . " limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);
  if (tep_db_num_rows($expected_query) > 0) {
?>

  <div class="panel panel-info">
    <div class="panel-heading">
      <div class="pull-left">
        <?php echo TABLE_HEADING_UPCOMING_PRODUCTS; ?>
      </div>
      <div class="pull-right">
        <?php echo TABLE_HEADING_DATE_EXPECTED; ?>
      </div>
      <div class="clearfix"></div>
    </div>

    <div class="panel-body">
<?php
    while ($expected = tep_db_fetch_array($expected_query)) {
      echo '<div class="pull-left"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected['products_id']) . '">' . $expected['products_name'] . '</a></div>' . "\n" .
           '<div class="pull-right">' . tep_date_short($expected['date_expected']) . '</div>' .
           '<div class="clearfix"></div>' . "\n";
    }
?>


    </div>
  </div>

<?php
  }
?>
