<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $error = false;
  $processed = false;
  
  $test = tep_db_prepare_input($HTTP_POST_VARS['entry_country_id']);

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $customers_firstname = tep_db_prepare_input($HTTP_POST_VARS['customers_firstname']);
        $customers_lastname = tep_db_prepare_input($HTTP_POST_VARS['customers_lastname']);
        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);
        $customers_telephone = tep_db_prepare_input($HTTP_POST_VARS['customers_telephone']);
        $customers_fax = tep_db_prepare_input($HTTP_POST_VARS['customers_fax']);
        $customers_newsletter = tep_db_prepare_input($HTTP_POST_VARS['customers_newsletter']);
//TotalB2B start
		    $customers_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['customers_discount_sign']);
		    $customers_discount = tep_db_prepare_input($HTTP_POST_VARS['customers_discount']);			
//TotalB2B end			
// bof multi stores 
		    $customers_stores_id = tep_db_prepare_input($HTTP_POST_VARS['customers_stores_id']);		
// eof multis stores
			// BOF Separate Pricing Per Customer
				$customers_group_id = tep_db_prepare_input($_POST['customers_group_id']);
				$customers_group_ra = tep_db_prepare_input($_POST['customers_group_ra']);
				$entry_company_tax_id = tep_db_prepare_input($_POST['entry_company_tax_id']);
				if ($_POST['customers_payment_allowed'] && $_POST['customers_payment_settings'] == '1') {
				$customers_payment_allowed = tep_db_prepare_input($_POST['customers_payment_allowed']);
				} else { // no error with subsequent re-posting of variables
				$customers_payment_allowed = '';
				if ($_POST['payment_allowed'] && $_POST['customers_payment_settings'] == '1') {
					foreach ($_POST['payment_allowed'] as $val) {
							if ($val == true) {
							$customers_payment_allowed .= tep_db_prepare_input($val).';';
							}
					 } // end while
						$customers_payment_allowed = substr($customers_payment_allowed,0,strlen($customers_payment_allowed)-1);
				} // end if ($_POST['payment_allowed'])
				} // end else ($_POST['customers_payment_allowed']
				if ($_POST['customers_shipment_allowed'] && $_POST['customers_shipment_settings'] == '1') {
				$customers_shipment_allowed = tep_db_prepare_input($_POST['customers_shipment_allowed']);
				} else { // no error with subsequent re-posting of variables
			
					$customers_shipment_allowed = '';
					if ($_POST['shipping_allowed'] && $_POST['customers_shipment_settings'] == '1') {
						foreach ($_POST['shipping_allowed'] as $val) {
							if ($val == true) {
							$customers_shipment_allowed .= tep_db_prepare_input($val).';';
							}
						} // end while
						$customers_shipment_allowed = substr($customers_shipment_allowed,0,strlen($customers_shipment_allowed)-1);
					} // end if ($_POST['shipment_allowed'])
				} // end else ($_POST['customers_shipment_allowed']
				if ($_POST['customers_order_total_allowed'] && $_POST['customers_order_total_settings'] == '1') {
				$customers_order_total_allowed = tep_db_prepare_input($_POST['customers_order_total_allowed']);
				} else { // no error with subsequent re-posting of variables
			
					$customers_order_total_allowed = '';
					if ($_POST['order_total_allowed'] && $_POST['customers_order_total_settings'] == '1') {
						foreach ($_POST['order_total_allowed'] as $val) {
							if ($val == true) {
							$customers_order_total_allowed .= tep_db_prepare_input($val).';';
							}
						} // end while
						$customers_order_total_allowed = substr($customers_order_total_allowed,0,strlen($customers_order_total_allowed)-1);
					} // end if ($_POST['order_total_allowed'])
				} // end else ($_POST['customers_order_total_allowed']
				if ($_POST['customers_specific_taxes_exempt'] && $_POST['customers_tax_rate_exempt_settings'] == '1') {
				$customers_specific_taxes_exempt = tep_db_prepare_input($_POST['customers_specific_taxes_exempt']);
				} else { // no error with subsequent re-posting of variables	
			
					$customers_specific_taxes_exempt = '';
					if ($_POST['customers_tax_rate_exempt_id'] && $_POST['customers_tax_rate_exempt_settings'] == '1') {
						foreach($_POST['customers_tax_rate_exempt_id'] as $val) {
							if (tep_not_null($val)) { 
							$customers_specific_taxes_exempt .= tep_db_prepare_input($val).','; 
							}
						} // end while
						$customers_specific_taxes_exempt = substr($customers_specific_taxes_exempt,0,strlen($customers_specific_taxes_exempt)-1);
					} // end if ($_POST['customers_tax_rate_exempt_id'])
				} // end else ($_POST['customers_specific_taxes_exempt']
			// EOF Separate Pricing Per Customer

        $customers_gender = tep_db_prepare_input($HTTP_POST_VARS['customers_gender']);
        $customers_dob = tep_db_prepare_input($HTTP_POST_VARS['customers_dob']);

        $default_address_id = tep_db_prepare_input($HTTP_POST_VARS['default_address_id']);
        $entry_street_address = tep_db_prepare_input($HTTP_POST_VARS['entry_street_address']);
        $entry_suburb = tep_db_prepare_input($HTTP_POST_VARS['entry_suburb']);
        $entry_postcode = tep_db_prepare_input($HTTP_POST_VARS['entry_postcode']);
        $entry_city = tep_db_prepare_input($HTTP_POST_VARS['entry_city']);
        $entry_country_id = tep_db_prepare_input($HTTP_POST_VARS['entry_country_id']);
// bof customer suspend
       $customers_suspended = tep_db_prepare_input($HTTP_POST_VARS['customers_suspended']);
// bof customer suspend

        $entry_company = tep_db_prepare_input($HTTP_POST_VARS['entry_company']);
        $entry_state = tep_db_prepare_input($HTTP_POST_VARS['entry_state']);
        if (isset($HTTP_POST_VARS['entry_zone_id'])) $entry_zone_id = tep_db_prepare_input($HTTP_POST_VARS['entry_zone_id']);


      $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "' and customers_id != '" . (int)$customers_id . "'");
      if (tep_db_num_rows($check_email)) {
        $error = true;
        $entry_email_address_exists = true;
      } else {
        $entry_email_address_exists = false;
      }

      if ($error == false) {

        $sql_data_array = array('customers_firstname' => $customers_firstname,
                                'customers_lastname' => $customers_lastname,
                                'customers_email_address' => $customers_email_address,
                                'customers_telephone' => $customers_telephone,
                                'customers_fax' => $customers_fax,
                                'customers_newsletter' => $customers_newsletter,
// bof customer suspend								
                                'customers_suspended' => $customers_suspended,
// eof customer suspend									
//TotalB2B start
                                'customers_discount' => $customers_discount_sign . $customers_discount,
//                                'customers_group_id' => $customers_group_id,
//TotalB2B end	
// bof multi stores
                                'customers_stores_id' => $customers_stores_id,
// eof multi stores							
// BOF Separate Pricing Per Customer
				                'customers_group_id' => $customers_group_id,
        			            'customers_group_ra' => $customers_group_ra,
				                'customers_payment_allowed' => $customers_payment_allowed,
				                'customers_shipment_allowed' => $customers_shipment_allowed,
				                'customers_order_total_allowed' => $customers_order_total_allowed,
				                'customers_specific_taxes_exempt' => $customers_specific_taxes_exempt,
				                'entry_company_tax_id' => $entry_company_tax_id);
// EOF Separate Pricing Per Customer
        if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
        if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");

        if ($entry_zone_id > 0) $entry_state = '';

        $sql_data_array = array('entry_firstname' => $customers_firstname,
                                'entry_lastname' => $customers_lastname,
                                'entry_street_address' => $entry_street_address,
                                'entry_postcode' => $entry_postcode,
                                'entry_city' => $entry_city,
                                'entry_country_id' => $entry_country_id);

        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $entry_company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;

        if (ACCOUNT_STATE == 'true') {
          if ($entry_zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $entry_zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $entry_state;
          }
        }

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$default_address_id . "'");

        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action'))));

        } else if ($error == true) {
          $cInfo = new objectInfo($HTTP_POST_VARS);
          $processed = true;
        }

        break;
      case 'deleteconfirm':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        if (isset($HTTP_POST_VARS['delete_reviews']) && ($HTTP_POST_VARS['delete_reviews'] == 'on')) {
          $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
          while ($reviews = tep_db_fetch_array($reviews_query)) {
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews['reviews_id'] . "'");
          }

          tep_db_query("delete from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
        } else {
          tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . (int)$customers_id . "'");
        }
// BOF Separate Pricing Per Customer
// Once all customers with a specific customers_group_id have been deleted from
// the table customers, the next time a customer is deleted, all entries in the table products_groups
// that have the (now apparently obsolete) customers_group_id will be deleted!
// If you don't want that, leave this section out, or comment it out
// Note that when customers groups are deleted from the table customers_groups, all the
// customers with that specific customer_group_id will be changed to customer_group_id = '0' (default/Retail)
/*  $multiple_groups_query = tep_db_query("select customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " ");
while ($group_ids = tep_db_fetch_array($multiple_groups_query)) {
  $multiple_customers_query = tep_db_query("select distinct customers_group_id from " . TABLE_CUSTOMERS . " where customers_group_id = " . $group_ids['customers_group_id'] . " ");
  if (!($multiple_groups = tep_db_fetch_array($multiple_customers_query))) {
    tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $group_ids['customers_group_id'] . "'");
  }
}  */
// EOF Separate Pricing Per Customer

        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
// Wishlist addition to delete products from the wishlist when customer deleted
        tep_db_query("delete from " . TABLE_WISHLIST . " where customers_id = " . (int)$customers_id);
        tep_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where customers_id = " . (int)$customers_id);
// eof wishlist		
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$customers_id . "'"); //rmh M-S_fixes		

        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action'))));
        break;
      default:
        $customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, c.entry_company_tax_id, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_group_id, c.customers_discount,  c.customers_group_ra, c.customers_payment_allowed, c.customers_shipment_allowed, c.customers_order_total_allowed, c.customers_specific_taxes_exempt, c.customers_default_address_id, c.customers_suspended, c.customers_stores_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
// eof multi stores		
// bof customer suspend		
//TotalB2B end
        $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
        $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
        $order_total_module_directory = DIR_FS_CATALOG_MODULES . 'order_total/';

        $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
        $directory_array = array();
        if ($dir = @dir($module_directory)) {
        while ($file = $dir->read()) {
        if (!is_dir($module_directory . $file)) {
           if (substr($file, strrpos($file, '.')) == $file_extension) {
              $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
                  }
               }
            }
        sort($directory_array);
        $dir->close();
        }

        $ship_directory_array = array();
        if ($dir = @dir($ship_module_directory)) {
        while ($file = $dir->read()) {
        if (!is_dir($ship_module_directory . $file)) {
           if (substr($file, strrpos($file, '.')) == $file_extension) {
              $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
                }
              }
            }
            sort($ship_directory_array);
            $dir->close();
        }

   $order_total_directory_array = array();
   if ($dir = @dir($order_total_module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($order_total_module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $order_total_directory_array[] = $file; // array of all order total modules present in includes/modules/order_total
        }
      }
    }
    sort($order_total_directory_array);
    $dir->close();
  }
// EOF Separate Pricing Per Customer
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');

?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
  
<?php

// show customers on screen	  
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE ; ?></h1>
            <div class="col-xs-12 col-md-6">
              <div class="row">              
			    <div class="col-md-10 col-xs-8">
<?php
                  echo '' . tep_draw_form('search', FILENAME_CUSTOMERS, '', 'get', 'role="form" class="form-horizontal"'). PHP_EOL .
//                       '      <label class="sr-only" for="search">' . HEADING_TITLE_SEARCH . '</label>' . PHP_EOL .  
//                       '      '. tep_draw_input_field('search','', 'placeholder="' . HEADING_TITLE_SEARCH . '"') . tep_hide_session_id() . PHP_EOL .
                       '      '. tep_draw_bs_input_field('search', '',HEADING_TITLE_SEARCH, 'id_input_search' , 'col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEARCH ) . PHP_EOL .
                       '      '. tep_hide_session_id() . PHP_EOL .							   
	                   '    </form>' . PHP_EOL ; 
?>
                </div>  					   
			    <div class="col-md-2 col-xs-4"> 
<?php				
                   if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {

		             echo tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_CUSTOMERS)); 
                   }		   				   
?>
                </div> 
              </div>
            </div>
            <div class="clearfix"></div>
          </div><!-- page-header-->
<?php		  
          switch ($_GET['listing']) {
              case "id-asc":
              $order = "c.customers_id";
	          break;
	          case "cg_name":
              $order = "cg.customers_group_name, c.customers_lastname";
	          break;
              case "cg_name-desc":
              $order = "cg.customers_group_name DESC, c.customers_lastname";
              break;
              case "firstname":
              $order = "c.customers_firstname";
              break;
              case "firstname-desc":
              $order = "c.customers_firstname DESC";
              break;
              case "company":
              $order = "a.entry_company, c.customers_lastname";
              break;
              case "company-desc":
              $order = "a.entry_company DESC,c .customers_lastname DESC";
              break;
              case "ra":
              $order = "c.customers_group_ra DESC, c.customers_id DESC";
              break;
              case "ra-desc":
              $order = "c.customers_group_ra, c.customers_id DESC";
              break;
              case "lastname":
              $order = "c.customers_lastname, c.customers_firstname";
              break;
              case "lastname-desc":
              $order = "c.customers_lastname DESC, c.customers_firstname";
              break;
              default:
              $order = "c.customers_id DESC";
          }		  
?>		  
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                <th><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=company'  ); ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo ENTRY_COMPANY; ?>                       <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=company-desc');   ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>
                <th><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=lastname' ); ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo TABLE_HEADING_LASTNAME; ?>              <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=lastname-desc');  ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>
                <th><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=firstname'); ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo TABLE_HEADING_FIRSTNAME; ?>             <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=firstname-desc'); ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>
                <th><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=cg_name'  ); ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo TABLE_HEADING_CUSTOMERS_GROUPS; ?>      <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=cg_name-desc');   ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>
                <th><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=id-asc'   ); ?>"><?php echo tep_glyphicon('sort-by-order' ); ?></a><?php echo TABLE_HEADING_ACCOUNT_CREATED; ?>       <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=id-desc');        ?>"><?php echo tep_glyphicon('sort-by-order-alt' ); ?></a></th>
                <th><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=ra'       ); ?>"><?php echo tep_glyphicon('triangle-top' ); ?></a><?php echo TABLE_HEADING_REQUEST_AUTHENTICATION; ?><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=ra-desc');        ?>"><?php echo tep_glyphicon('triangle-bottom' ); ?></a></th>
                <th><?php echo TABLE_HEADING_ACTION; ?></th>

                </tr>
              </thead>
              <tbody>
<?php			  
                 $search = '';
                 if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
                    $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
                    $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "%' or c.customers_telephone like '%" . $keywords . "%' or entry_postcode like '%" . $keywords . "%'";	  
                 }
// BOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer
                $customers_query_raw = "select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_group_id, c.customers_stores_id, c.customers_group_ra, a.entry_country_id, a.entry_company, cg.customers_group_name from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id left join " . TABLE_CUSTOMERS_GROUPS . " cg on c.customers_group_id = cg.customers_group_id " . $search . " order by " . $order ;
                $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
                $customers_query = tep_db_query($customers_query_raw);
                while ($customers = tep_db_fetch_array($customers_query)) {
                   $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
                   $info = tep_db_fetch_array($info_query);


                   if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
                     $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "'");
                     $country = tep_db_fetch_array($country_query);

                     $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
                     $reviews = tep_db_fetch_array($reviews_query);

					 if ( !isset( $country ) ) {
					    $country = array() ;
					 }
					 if ( !isset( $reviews ) ) {
					    $reviews = array() ;
					 }					 
                     $customer_info = array_merge($country, $info, $reviews);

                     $cInfo_array = array_merge($customers, $customer_info);
                     $cInfo = new objectInfo($cInfo_array);
                   }	
         
		           if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) {
                        echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers['customers_id']) . '\'">' . "\n";
                   } else {
                        echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '\'">' . "\n";
                   }
?>				   
                                 <td><?php echo $customers['entry_company'] ;                   ?></td>
                                 <td><?php echo $customers['customers_lastname'] ;              ?></td>								 
                                 <td><?php echo $customers['customers_firstname'] ;             ?></td>
                                 <td><?php echo $customers['customers_group_name'] ;            ?></td>								 
                                 <td><?php echo tep_date_short($info['date_account_created']) ; ?></td>								 
								 <td>
<?php								 
                                     if ($customers['customers_group_ra'] == '1') {
                                        echo tep_glyphicon('ok-sign', 'success');
                                     } 
?>
                                 </td>
                                 <td class="text-right">
                                     <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $customers['customers_id']. '&action=info'),                                                      null, 'info')    . '</div>' . PHP_EOL .
		        '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers['customers_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers['customers_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL . 
		        '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ORDERS,          'shopping-cart', tep_href_link(FILENAME_ORDERS, 'cID=' . $customers['customers_id']),                                                                         null, 'info')    . '</div>' . PHP_EOL .                                      
                '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_NEW_ORDER,       'plus',          tep_href_link(FILENAME_CREATE_ORDER, 'Customer='. $customers['customers_id'] ),                                                              null, 'info')    . '</div>' . PHP_EOL .
                '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EMAIL,           'send',          tep_href_link(FILENAME_MAIL, 'customer=' . $customers['customers_email_address']),                                                           null, 'success') . '</div>' . PHP_EOL .				
                '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_CHANGE_PASSWORD, 'user',          tep_href_link(FILENAME_CHANGE_PASSWORD, 'selected_box=customers&customer=' . $customers['customers_id']),                                    null, 'warning' ). '</div>' . PHP_EOL .				
                '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_SPPC_GROUP_LIST, 'th-list',       tep_href_link(FILENAME_SPPC_GROUP_LIST, 'Customer='. $customers['customers_id']),                                                            null, 'info' )   . '</div>' . PHP_EOL ; 
?>

                                     </div> 
				                 </td>									 
							 
				              </tr>
<?php							  
                              if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id) && isset($HTTP_GET_VARS['action'])) { 
 	                             $alertClass = '';
                                 switch ($action) {
									 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('customers', FILENAME_CUSTOMERS, 'cID=' . $cInfo->customers_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
//			                           $contents .= '                          <h4>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . ': ' . '</h4>' . PHP_EOL;
                                       $contents .= '                          <p>' . $customers['customers_lastname']  .  '<br />' . $customers['customers_firstname'] . '</p>' . PHP_EOL;
                                       if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) {
										    $contents =                          '<br />' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews) ;
                                       }											
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
                                      break;

		                            case 'edit':
									
									
									   $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
														         array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));
// bof customer suspend							  
									   $suspended_array = array(array('id' => 'True', 'text' => ENTRY_SUSPENDED_YES),
													            array('id' => 'False', 'text' => ENTRY_SUSPENDED_NO));							  
													   
									   $discount_array = array(array('id' => '-', 'text' => 'minus'),
										        			   array('id' => '+', 'text' => 'plus'));							   
// eof customer suspend											
 			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
 
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('update_cust', FILENAME_CUSTOMERS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->customers_id . '&action=update', 'post', 'class="form-horizontal" role="form"',  'id_edit_currencies') . PHP_EOL;	
									   
			                           $contents_tab_cust_edit_01            .= '     <div class="panel panel-primary">' . PHP_EOL;
 			                           $contents_tab_cust_edit_01            .= ' 		<div class="panel-body">' . PHP_EOL;
 						   
									   if (ACCOUNT_GENDER == 'true') {
										  if (isset($customers_gender)) {
										    $male = ($customers_gender == 'm') ? true : false;
										  } else {
										    $male = ($cInfo->customers_gender == 'm') ? true : false;
										  }
										  $female = !$male;	
 									  

 								  
										  
							              $contents_tab_cust_edit_01 .= '   <div class="col-xs-3"><label> ' . ENTRY_GENDER . '</label></div>' . PHP_EOL ;										  
							              $contents_tab_cust_edit_01 .= '   <div class="form-group">' . PHP_EOL ;
							              $contents_tab_cust_edit_01 .=  			   tep_bs_radio_field('customers_gender', 'm', MALE,  'id_input_gender_cust_male',    ( $male ==  true ?  true : false ), 'radio radio-success radio-inline', '', '', 'right') ;
							              $contents_tab_cust_edit_01 .=  			   tep_bs_radio_field('customers_gender', 'f', FEMALE, 'id_input_gender_cust_female', ( $female ==  true ?  true : false ), 'radio radio-success radio-inline', '', '', 'right') ;
							              $contents_tab_cust_edit_01 .= '   </div>'. PHP_EOL  ;				 


									   }
						 
                                       $contents_tab_cust_edit_01            .= '			           <div class="form-group">' . PHP_EOL ;	
                                       $contents_tab_cust_edit_01            .=                             tep_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id) . PHP_EOL ;	
                                       $contents_tab_cust_edit_01            .= '				       </div>			' . PHP_EOL ;						   
									   
                                       $contents_tab_cust_edit_01            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_tab_cust_edit_01            .= '                           ' . tep_draw_bs_input_field('customers_firstname', $cInfo->customers_firstname,  ENTRY_FIRST_NAME, 'inputFirstName' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_FIRST_NAME, '', true ) . PHP_EOL;	
                                       $contents_tab_cust_edit_01            .= '                       </div>' . PHP_EOL ;									   
									   $contents_tab_cust_edit_01            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents_tab_cust_edit_01            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_tab_cust_edit_01            .= '                           ' . tep_draw_bs_input_field('customers_lastname', $cInfo->customers_lastname,  ENTRY_LAST_NAME, 'inputLastName' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_LAST_NAME, '', true ) . PHP_EOL;	
                                       $contents_tab_cust_edit_01            .= '                       </div>' . PHP_EOL ;									   
									   $contents_tab_cust_edit_01            .= '                       <br />' . PHP_EOL;

                                       if (ACCOUNT_DOB == 'true') {
			   
								          $contents_tab_cust_edit_01            .= '                    <div class="form-group  has-feedback text-left">' . PHP_EOL;	
								          $contents_tab_cust_edit_01            .= '                      <label for="customers_dob" class="col-xs-3">' . ENTRY_DATE_OF_BIRTH . '</label>' . PHP_EOL;	 										 
								          $contents_tab_cust_edit_01            .= '                      <div class="col-xs-9">' . PHP_EOL ;                                              // name
								          $contents_tab_cust_edit_01            .=                            tep_draw_bs_input_date('customers_dob', 
										                                                                         tep_date_short($cInfo->customers_dob),           // value
					                                                                                             'required aria-required="true" id="customers_dob"',            // parameters
					                                                                                             null,                                                // type
					                                                                                             null,                                              // reinsert value
					                                                                                             ENTRY_DATE_OF_BIRTH                             // placeholder
					                                                                                            ) ; 
								          $contents_tab_cust_edit_01            .= '                      </div>' . PHP_EOL;	
								          $contents_tab_cust_edit_01            .= '                    </div>' . PHP_EOL;	
								          $contents_tab_cust_edit_01            .= '				       <br />' . PHP_EOL;	

			                           }									   
 
									   
                                       $contents_tab_cust_edit_01            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_tab_cust_edit_01            .= '                           ' . tep_draw_bs_input_field('customers_email_address', $cInfo->customers_email_address,  ENTRY_EMAIL_ADDRESS, 'inputEmail_Adress' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_EMAIL_ADDRESS, '', true ) . PHP_EOL;	
                                       $contents_tab_cust_edit_01            .= '                       </div>' . PHP_EOL ;									   
									   $contents_tab_cust_edit_01            .= '                           <br />' . PHP_EOL;	
									   
 
									   if (ACCOUNT_COMPANY == 'true') {
 
 
									       $contents_tab_cust_edit_01            .= '                      <div class="form-group has-feedback">' . PHP_EOL ;
									       $contents_tab_cust_edit_01            .= '                           ' . tep_draw_bs_input_field('entry_company', $cInfo->entry_company,  ENTRY_COMPANY, 'inputCompany_Name' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_COMPANY  ) . PHP_EOL;											   
										   $contents_tab_cust_edit_01            .= '                      </div>' . PHP_EOL ;

										   $contents_tab_cust_edit_01            .= '                      <div class="form-group has-feedback">' . PHP_EOL ;
									       $contents_tab_cust_edit_01            .= '                           ' . tep_draw_bs_input_field('entry_company_tax_id', $cInfo->entry_company_tax_id,  ENTRY_COMPANY_TAX_ID, 'inputCompany_Number' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_COMPANY_TAX_ID  ) . PHP_EOL;											   								
										   $contents_tab_cust_edit_01            .= '                      </div>' . PHP_EOL ;	

										   $contents_tab_cust_edit_01            .= '                      <div class="input-group has-feedback">' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .= '				        <div class="btn-group btn-inline" data-toggle="buttons">' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .= '                           <label class="control-label">'. ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION . '</label><br />' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .= '                           <label class="btn btn-default ' . ( $cInfo->customers_group_ra ==  0 ? " active " : "" ) . '">' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .=                                tep_draw_radio_field('customers_group_ra', '0', false ) . ' ' . ENTRY_CUSTOMERS_GROUP_RA_NO . PHP_EOL ;
										   $contents_tab_cust_edit_01            .= '                           </label>' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .= '                           <label class="btn btn-default ' . ( $cInfo->customers_group_ra ==  1 ? " active " : "" ) . '>">' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .=                                tep_draw_radio_field('customers_group_ra', '1', false) . ' ' . ENTRY_CUSTOMERS_GROUP_RA_YES; 
										   $contents_tab_cust_edit_01            .= '                           </label>' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .= '                        </div>' . PHP_EOL ;
										   $contents_tab_cust_edit_01            .= '                      </div>' . PHP_EOL ;	  
						   
 		   										   

                                       }
 
									   
									   $contents_tab_cust_edit_01            .= '			        </div>' . PHP_EOL ; //  <!-- end panel body personal tab --> 
									   $contents_tab_cust_edit_01            .= '		       </div>' . PHP_EOL ; //     <!-- end panel personal tab -->  										   
									   
									   $contents_tab_cust_edit_02            .= '                   <div class="panel panel-primary"> ' . PHP_EOL ;	  
  
									   $contents_tab_cust_edit_02            .= '			           <div class="panel-body">' . PHP_EOL ;	  
			 
									   $contents_tab_cust_edit_02            .= '                           <div class="form-group has-feedback">' . PHP_EOL ;
									   $contents_tab_cust_edit_02            .= '                           ' . tep_draw_bs_input_field('entry_street_address', $cInfo->entry_street_address,  ENTRY_STREET_ADDRESS, 'inputstreet_adress' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_STREET_ADDRESS, '', true ) . PHP_EOL;										   
									   $contents_tab_cust_edit_02            .= '                           </div>' . PHP_EOL ;	  	
									   
                                       if (ACCOUNT_SUBURB == 'true') {									   
                                          $contents_tab_cust_edit_02            .= '                        <div class="form-group has-feedback">' . PHP_EOL ;										   
									      $contents_tab_cust_edit_02            .= '                             ' . tep_draw_bs_input_field('entry_suburb', $cInfo->entry_suburb,  ENTRY_SUBURB, 'inputSuburb_Name' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_SUBURB, '', true ) . PHP_EOL;	
                                          $contents_tab_cust_edit_02            .= '                        </div>' . PHP_EOL ;									   
									      $contents_tab_cust_edit_02            .= '                        <br />' . PHP_EOL;	
									   }

                                       $contents_tab_cust_edit_02            .= '                            <div class="form-group has-feedback">' . PHP_EOL ;										   
									   $contents_tab_cust_edit_02            .= '                             ' . tep_draw_bs_input_field('entry_postcode', $cInfo->entry_postcode,  ENTRY_POST_CODE, 'inputPostal_Code' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_POST_CODE, '', true ) . PHP_EOL;	
                                       $contents_tab_cust_edit_02            .= '                            </div>' . PHP_EOL ;									   
									   $contents_tab_cust_edit_02            .= '                            <br />' . PHP_EOL;									   
									   
                                       $contents_tab_cust_edit_02            .= '                            <div class="form-group has-feedback">' . PHP_EOL ;										   
									   $contents_tab_cust_edit_02            .= '                              ' . tep_draw_bs_input_field('entry_city', $cInfo->entry_city,  ENTRY_CITY, 'inputCity_Name' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_CITY, ''  ) . PHP_EOL;	
                                       $contents_tab_cust_edit_02            .= '                            </div>' . PHP_EOL ;									   
									   $contents_tab_cust_edit_02            .= '                            <br />' . PHP_EOL;	
									   
									   if (ACCOUNT_STATE == 'true') {

									      $entry_state = tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state);
									      if ($entry_state_has_zones == true) {
										      $zones_array = array();
										      $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
										      while ($zones_values = tep_db_fetch_array($zones_query)) {
											    $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
										      }
								              $contents_tab_cust_edit_02            .= '               <div class="form-group">' . PHP_EOL;	// tep_draw_pull_down_menu('options_id', $options_array, $attributes_values['options_id'])
								              $contents_tab_cust_edit_02            .= '                   ' . tep_draw_bs_pull_down_menu('entry_state', $zones_array, $cInfo->entry_state, ENTRY_STATE, 'inputState_Name', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
								              $contents_tab_cust_edit_02            .= '               </div>' . PHP_EOL;										   
 
                                          } else {
										   
                                              $contents_tab_cust_edit_02            .= '                            <div class="form-group has-feedback">' . PHP_EOL ;										   
									          $contents_tab_cust_edit_02            .= '                              ' . tep_draw_bs_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state),  ENTRY_STATE, 'inputZone_Name' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_STATE, ''  ) . PHP_EOL;	
                                              $contents_tab_cust_edit_02            .= '                            </div>' . PHP_EOL ;									   
									          $contents_tab_cust_edit_02            .= '                            <br />' . PHP_EOL;				 
                                          }
									   }
									   
								       $contents_tab_cust_edit_02            .= '                       <div class="form-group">' . PHP_EOL;	// tep_draw_pull_down_menu('options_id', $options_array, $attributes_values['options_id'])
								       $contents_tab_cust_edit_02            .= '                         ' . tep_draw_bs_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id, ENTRY_COUNTRY, 'inputCountry_Name', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', '', false, true)  . PHP_EOL;	
								       $contents_tab_cust_edit_02            .= '                       </div>' . PHP_EOL;									   
									   
									   						   
 			 
									   $contents_tab_cust_edit_02            .= '                 <div class="form-group has-feedback">' . PHP_EOL ;
									   $contents_tab_cust_edit_02            .= '                             ' . tep_draw_bs_input_field('customers_telephone', $cInfo->customers_telephone,  ENTRY_TELEPHONE_NUMBER, 'inputTelephoneNumber' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_TELEPHONE_NUMBER, '', true ) . PHP_EOL;	
									   $contents_tab_cust_edit_02            .= '                 </div>' . PHP_EOL ;			 
				 
									   $contents_tab_cust_edit_02            .= '                 <div class="form-group has-feedback">' . PHP_EOL ;
									   $contents_tab_cust_edit_02            .= '                             ' . tep_draw_bs_input_field('customers_fax', $cInfo->customers_fax,  ENTRY_FAX_NUMBER, 'inputFaxNumber' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_FAX_NUMBER, '' ) . PHP_EOL;	
									   $contents_tab_cust_edit_02            .= '                 </div>' . PHP_EOL ;
			 		  
									   $contents_tab_cust_edit_02            .= '			 </div>' . PHP_EOL ; //  <!-- end panel body category contact --> 
									   $contents_tab_cust_edit_02            .= '		  </div>' . PHP_EOL ; //     <!-- end panel category contact -->  

									   $contents_tab_cust_edit_03            .= '         <div class="panel panel-primary">' . PHP_EOL ;
//		     						   $contents_tab_cust_edit_03            .= '            <div class="panel-heading">' . CATEGORY_OPTIONS . '</div>' . PHP_EOL ;
									   $contents_tab_cust_edit_03            .= '			 <div class="panel-body">' . PHP_EOL ;
			  
									   $contents_tab_cust_edit_03            .= '                 <div class="form-group has-feedback">' . PHP_EOL ;	
								       $contents_tab_cust_edit_03            .= '                   ' . tep_draw_bs_pull_down_menu('customers_newsletter', $newsletter_array, (($cInfo->customers_newsletter == '1') ? '1' : '0'), ENTRY_NEWSLETTER, 'inputNewsletter', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_tab_cust_edit_03            .= '                 </div>' . PHP_EOL ;					 
				 
									   $contents_tab_cust_edit_03            .= '                 <div class="form-group has-feedback">' . PHP_EOL ;
								       $contents_tab_cust_edit_03            .= '                   ' . tep_draw_bs_pull_down_menu('customers_suspended', $suspended_array, (($cInfo->customers_suspended == 'True') ? 'True' : 'False'), ENTRY_ACCOUNT_STATUS, 'inputActiveCust', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_tab_cust_edit_03            .= '                 </div>' . PHP_EOL ;
     
 
									   $contents_tab_cust_edit_03            .= '                <div class="form-group">' . PHP_EOL ;

                                       if ( $cInfo->customers_discount > 0 ) {
	                                     $discount_sign = '+' ;
	                                     $discount_amount = $cInfo->customers_discount ;
                                       } else {
	                                     $discount_sign   = '-' ;	
                                         $discount_amount = substr($cInfo->customers_discount,1,strlen($cInfo->customers_discount))	 ;
                                       }
							  
								       $contents_tab_cust_edit_03            .= '                   ' . tep_draw_bs_pull_down_menu('customers_discount_sign', $discount_array, $discount_sign, ENTRY_CUSTOMERS_DISCOUNT, 'inputCust_Discount_Sign', 'col-xs-2', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_tab_cust_edit_03            .= '                   ' . tep_draw_bs_input_field('customers_discount', $discount_amount,  null, 'inputFaxNumber' ,  null, 'col-xs-7', null, ENTRY_CUSTOMERS_DISCOUNT, '' ) . PHP_EOL;	
									   $contents_tab_cust_edit_03            .= '                 </div>' . PHP_EOL ;
 
				 
                                       $index = 0;
	                                   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
                                       while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
                                            $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
                                            ++$index;
                                       }				 
									   
									   $contents_tab_cust_edit_03            .= '                 <div class="form-group ">' . PHP_EOL ;
								       $contents_tab_cust_edit_03            .= '                   ' . tep_draw_bs_pull_down_menu('customers_group_id', $existing_customers_array, $cInfo->customers_group_id, ENTRY_CUSTOMERS_GROUP_NAME, 'inputCust_Group', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_tab_cust_edit_03            .= '                 </div>' . PHP_EOL ;
				 
 
                                       $index = 0;
	                                   $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
                                       while ($stores_customers =  tep_db_fetch_array($stores_customers_query)) {
                                         $stores_customers_array[] = array("id" => $stores_customers['stores_id'], "text" => "&#160;".$stores_customers['stores_name']."&#160;");
                                         ++$index;
                                       }
									   $contents_tab_cust_edit_03            .= '                 <div class="form-group ">' . PHP_EOL ;
								       $contents_tab_cust_edit_03            .= '                   ' . tep_draw_bs_pull_down_menu('customers_stores_id', $stores_customers_array, $cInfo->customers_stores_id, ENTRY_CUSTOMERS_STORES_NAME, 'inputCust_Store', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_tab_cust_edit_03            .= '                 </div>' . PHP_EOL ;				 
				 				 
			 		  
									   $contents_tab_cust_edit_03            .= '			 </div>' . PHP_EOL ; //  <!-- end panel body category opties --> 
									   $contents_tab_cust_edit_03            .= '		  </div>' . PHP_EOL ;    //     <!-- end panel category opties --> 
									   
									   $contents_tab_sppc_01 = '' ;	   
									   include( DIR_WS_MODULES . 'customers_edit_sppc_option_payment.php' ) ;			  

									   $contents_tab_sppc_02 = '' ;	   
									   include( DIR_WS_MODULES . 'customers_edit_sppc_option_shipping.php' ) ;				  

									   $contents_tab_sppc_03 = '' ;	   
									   include( DIR_WS_MODULES . 'customers_edit_sppc_option_order_total.php' ) ;			  
									  
									   $contents_tab_sppc_04 = '' ;	   
									   include( DIR_WS_MODULES . 'customers_edit_sppc_option_tax_exempt.php' ) ;		  
 
 									   $contents_tab_cust_edit_04            .= '    <div class="panel panel-primary">' . PHP_EOL ;
 
									   $contents_tab_cust_edit_04            .= '	    <div class="panel-body">' . PHP_EOL ;
									   
									   $contents_tab_cust_edit_04            .= '          <div role="tabpanel" id="tab_sppc_options">' . PHP_EOL ;

									   $contents_tab_cust_edit_04            .= '              <!-- Nav tabs Customers SPPC options -->' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '              <ul class="nav nav-tabs" role="tablist" id="tab_sppc_options">' . PHP_EOL ; 
									   $contents_tab_cust_edit_04            .= '                 <li  id="tab_sppc_options" class="active"><a href="#tab_sppc_option_payment"      aria-controls="tab_sppc_payment"      role="tab" data-toggle="tab">' . HEADING_TITLE_MODULES_PAYMENT            . '</a></li>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '                 <li  id="tab_sppc_options">               <a href="#tab_sppc_option_shipment"     aria-controls="tab_sppc_shipment"     role="tab" data-toggle="tab">' . HEADING_TITLE_MODULES_SHIPPING           . '</a></li>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '                 <li  id="tab_sppc_options">               <a href="#tab_sppc_option_ordertotal"   aria-controls="tab_sppc_ordertotal"   role="tab" data-toggle="tab">' . HEADING_TITLE_MODULES_ORDER_TOTAL        . '</a></li>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '                 <li  id="tab_sppc_options">               <a href="#tab_sppc_option_vat_exempt"   aria-controls="tab_sppc_vatexempt"    role="tab" data-toggle="tab">' . HEADING_TITLE_CUSTOMERS_TAX_RATES_EXEMPT . '</a></li>' . PHP_EOL ;
 
									   $contents_tab_cust_edit_04            .= '               </ul>' . PHP_EOL ;

									   $contents_tab_cust_edit_04            .= '               <!-- Tab panes SPPC options -->' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '               <div  id="tab_sppc_options" class="tab-content">' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '                 <div role="tabpanel" class="tab-pane active" id="tab_sppc_option_payment">    ' .  $contents_tab_sppc_01 . '</div>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '                 <div role="tabpanel" class="tab-pane"        id="tab_sppc_option_shipment">   ' .  $contents_tab_sppc_02 . '</div>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '                 <div role="tabpanel" class="tab-pane"        id="tab_sppc_option_ordertotal"> ' .  $contents_tab_sppc_03 . '</div>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '                 <div role="tabpanel" class="tab-pane"        id="tab_sppc_option_vat_exempt"> ' .  $contents_tab_sppc_04 . '</div>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '               </div>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '          </div>' . PHP_EOL ;
									   $contents_tab_cust_edit_04            .= '       </div>' . PHP_EOL ; //  <!-- end panel body sppc opties --> 
									   $contents_tab_cust_edit_04            .= '    </div>' . PHP_EOL ;    //     <!-- end panel sppc opties --> 									   
									   

									   $contents            .= '          <div role="tabpanel" id="tab_edit_customers">' . PHP_EOL ;

									   $contents            .= '              <!-- Nav tabs Customers SPPC options -->' . PHP_EOL ;
									   $contents            .= '              <ul class="nav nav-tabs" role="tablist" id="tab_edit_customers">' . PHP_EOL ; 
									   $contents            .= '                 <li  id="tab_edit_customers" class="active"><a href="#tab_edit_customer_personal_comp"     aria-controls="tab_customer_edit_personal_comp"      role="tab" data-toggle="tab">' . TEXT_HEADING_PERSONAL             . '</a></li>' . PHP_EOL ;
									   $contents            .= '                 <li  id="tab_edit_customers">               <a href="#tab_edit_customer_address_contact"   aria-controls="tab_customer_edit_address_contact"    role="tab" data-toggle="tab">' . TEXT_HEADING_ADDRESS_CONTACT      . '</a></li>' . PHP_EOL ;
									   $contents            .= '                 <li  id="tab_edit_customers">               <a href="#tab_edit_customer_options"           aria-controls="tab_customer_edit_options"            role="tab" data-toggle="tab">' . TEXT_HEADING_OPTIONS              . '</a></li>' . PHP_EOL ;
									   $contents            .= '                 <li  id="tab_edit_customers">               <a href="#tab_edit_customer_sppc"              aria-controls="tab_customer_edit_sppc"               role="tab" data-toggle="tab">' . TEXT_HEADING_SPPC                 . '</a></li>' . PHP_EOL ;
 
									   $contents            .= '               </ul>' . PHP_EOL ;

									   $contents            .= '               <!-- Tab panes SPPC options -->' . PHP_EOL ;
									   $contents            .= '               <div  id="tab_edit_customers" class="tab-content">' . PHP_EOL ;
									   $contents            .= '                 <div role="tabpanel" class="tab-pane active" id="tab_edit_customer_personal_comp">    '     .  $contents_tab_cust_edit_01 . '</div>' . PHP_EOL ;
									   $contents            .= '                 <div role="tabpanel" class="tab-pane"        id="tab_edit_customer_address_contact">   '    .  $contents_tab_cust_edit_02 . '</div>' . PHP_EOL ;
									   $contents            .= '                 <div role="tabpanel" class="tab-pane"        id="tab_edit_customer_options"> '              .  $contents_tab_cust_edit_03 . '</div>' . PHP_EOL ;
									   $contents            .= '                 <div role="tabpanel" class="tab-pane"        id="tab_edit_customer_sppc"> '                 .  $contents_tab_cust_edit_04 . '</div>' . PHP_EOL ;
									   $contents            .= '               </div>' . PHP_EOL ;
									   $contents            .= '          </div>' . PHP_EOL ;											   		   
					   

                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove',  tep_href_link(FILENAME_CUSTOMERS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->customers_id  ), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
                                      break;	
																		  
          		
		                            default: 
// bof multi stores		
	                                    $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " where stores_id= '" . $cInfo->customers_stores_id . "'");
                                        $stores_customers =  tep_db_fetch_array($stores_customers_query) ;
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
//			                            $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 			
			                            $contents .= '                              ' . TEXT_DATE_ACCOUNT_CREATED . ' ' . $info['date_account_created'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($info['date_account_last_modified']) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 	
			                            $contents .= '                              ' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($info['date_last_logon']) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;										
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $info['number_of_logons'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_CUSTOMERS ), null, null, 'btn-default text-danger') . PHP_EOL;										
                                    break;
                                 }
	
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="7">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
                              }								  						 
				} // end while ($customers = tep_db_fetch_array($cus
?>			  
			  
			  
			  
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText hidden-xs mark" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
            <td class="smallText mark" style="text-align: right;"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))) ?></td>	   
		  </tr>

    </table>
<?php
    if (!isset($HTTP_GET_VARS['search'])) {
       $customers_groups_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
       while ($existing_customers_groups =  tep_db_fetch_array($customers_groups_query)) {
           $existing_customers_groups_array[] = array("id" => $existing_customers_groups['customers_group_id'], "text" => $existing_customers_groups['customers_group_name']);
      }
      $count_groups_query = tep_db_query("select customers_group_id, count(*) as count from " . TABLE_CUSTOMERS . " group by customers_group_id order by count desc");
      while ($count_groups = tep_db_fetch_array($count_groups_query)) {
	    for ($n = 0; $n < sizeof($existing_customers_groups_array); $n++) {
		  if ($count_groups['customers_group_id'] == $existing_customers_groups_array[$n]['id']) {
			  $count_groups['customers_group_name'] = $existing_customers_groups_array[$n]['text'];
		  }
	    } // end for ($n = 0; $n < sizeof($existing_customers_groups_array); $n++)
	    $count_groups_array[] = array("id" => $count_groups['customers_group_id'], "number_in_group" => $count_groups['count'], "name" => $count_groups['customers_group_name']);
      }
?>
      <div class="col-md-6 col-xs-12">
	    <table class="table table-bordered" >
		   <thead>
		    <th><?php echo TABLE_HEADING_CUSTOMERS_GROUPS ?></td>
		    <th align="right"><?php echo TABLE_HEADING_CUSTOMERS_GROUPS_QNT ?></th>
		 </tr>
<?php $c = '0'; // variable used for background coloring of rows
   for ($z = 0; $z < sizeof($count_groups_array); $z++) {	  
?>
	<tr>
	  <td><?php echo $count_groups_array[$z]['name']; ?></td>
	  <td class="text-right"><?php echo $count_groups_array[$z]['number_in_group'] ?></td>
	</tr>
<?php
   } // end for ($z = 0; $z < sizeof($count_groups_array); $z++)
?>		 </table>
		 </td>
              <tr>
<?php
  } // end if (!isset($HTTP_GET_VARS['search']))
?>	
     </div>
<?php
// eric  }
?>
    </table>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>