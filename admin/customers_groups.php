<?php
/*
  $Id: catalog/admin/customers_groups.php v1.2 2008/03/07
  for Separate Pricing Per Customer (exempt specific tax rates added in v1.1, order_total modules allowed added in v1.2)
  
  adapted from the file of the same name from TotalB2B
  by hOZONE, hozone[at]tiscali.it, http://hozone.cjb.net
  
  who in turn credits:
  Discount_Groups_v1.1, by Enrico Drusiani, 2003/5/22
  
  setting of allowed payments inspired by b2bsuite_b097 but implemented differently
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  
  Copyright (c) 2005 osCommerce
  
  Released under the GNU General Public License 
*/

  require('includes/application_top.php');
  
    $cg_show_tax_array = array(array('id' => '1', 'text' => ENTRY_GROUP_SHOW_TAX_YES),
                              array('id' => '0', 'text' => ENTRY_GROUP_SHOW_TAX_NO));
    $cg_tax_exempt_array = array(array('id' => '1', 'text' => ENTRY_GROUP_TAX_EXEMPT_YES),
                              array('id' => '0', 'text' => ENTRY_GROUP_TAX_EXEMPT_NO));
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

      case 'update':
        $error = false;
	    $customers_group_id = tep_db_prepare_input($_GET['cID']);
		$customers_group_name = tep_db_prepare_input($HTTP_POST_VARS['customers_group_name']);
		$customers_group_show_tax = tep_db_prepare_input($HTTP_POST_VARS['customers_group_show_tax']);
		$customers_group_tax_exempt = tep_db_prepare_input($HTTP_POST_VARS['customers_group_tax_exempt']);
// totab2b start		
		$customers_group_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['customers_group_discount_sign']);
        $customers_group_discount = tep_db_prepare_input($HTTP_POST_VARS['customers_group_discount']);		
// totalb2b end		
		$group_payment_allowed = '';
//        $_paymeny_allowed = array_keys( $HTTP_POST_VARS['payment_allowed'] ) ;  
		if ( isset($HTTP_POST_VARS['payment_allowed'] ) ) {
		   $_paymeny_allowed = array_keys( $HTTP_POST_VARS['payment_allowed'] ) ;  
		} else {
		   $_paymeny_allowed = array() ;
		}		
		if ($_paymeny_allowed && $HTTP_POST_VARS['group_payment_settings'] == '1') {
		  foreach($_paymeny_allowed as $val) {
		    if ($val == true) { 
		      $group_payment_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_payment_allowed = substr($group_payment_allowed,0,strlen($group_payment_allowed)-1);
		} // end if ($HTTP_POST_VARS['payment_allowed'])
		$group_shipment_allowed = '';
//        $_shipping_allowed = array_keys( $HTTP_POST_VARS['shipping_allowed'] ) ; 
		if ( isset($HTTP_POST_VARS['shipping_allowed'] ) ) {
		   $_shipping_allowed = array_keys( $HTTP_POST_VARS['shipping_allowed'] ) ;  
		} else {
		   $_shipping_allowed = array() ;
		}			
		if ($_shipping_allowed && $HTTP_POST_VARS['group_shipment_settings'] == '1') {
		  foreach($_shipping_allowed as $val) {
		    if ($val == true) { 
		    $group_shipment_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_shipment_allowed = substr($group_shipment_allowed,0,strlen($group_shipment_allowed)-1);
		} // end if ($HTTP_POST_VARS['shipment_allowed'])
			
	    $group_tax_rates_exempt = '';
//        $_tax_rate_exempt = array_keys( $HTTP_POST_VARS['group_tax_rate_exempt_id'] ) ;  
		if ( isset($HTTP_POST_VARS['group_tax_rate_exempt_id'] ) ) {
		   $_tax_rate_exempt = array_keys( $HTTP_POST_VARS['group_tax_rate_exempt_id'] ) ;  
		} else {
		   $_tax_rate_exempt = array() ;
		}		
	    if ($_tax_rate_exempt  && $HTTP_POST_VARS['group_tax_rate_exempt_settings'] == '1') {
	      foreach($_tax_rate_exempt as $val) {
	         if (tep_not_null($val)) { 
	            $group_tax_rates_exempt .= tep_db_prepare_input($val).','; 
	         }
	      } // end while
	      $group_tax_rates_exempt = substr($group_tax_rates_exempt,0,strlen($group_tax_rates_exempt)-1);
	    } // end if ($HTTP_POST_VARS['group_tax_rate_exempt_settings'])
		$group_order_total_allowed = '';
//        $_order_total_allowed = array_keys( $HTTP_POST_VARS['order_total_allowed'] ) ;  
		if ( isset($HTTP_POST_VARS['order_total_allowed'] ) ) {
		   $_order_total_allowed = array_keys( $HTTP_POST_VARS['order_total_allowed'] ) ;  
		} else {
		   $_order_total_allowed = array() ;
		}
		if ( (isset( $_order_total_allowed ) ) && ( $HTTP_POST_VARS['group_order_total_settings'] == '1') ) {
		  foreach($_order_total_allowed as $val) {
		    if ($val == true) { 
		    $group_order_total_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_order_total_allowed = substr($group_order_total_allowed,0,strlen($group_order_total_allowed)-1);
		} // end if ($HTTP_POST_VARS['order_total_allowed'])

        tep_db_query("update " . TABLE_CUSTOMERS_GROUPS . " set customers_group_name='" . $customers_group_name . "', customers_group_discount='" . $customers_group_discount_sign . $customers_group_discount . "', customers_group_show_tax = '" . $customers_group_show_tax . "', customers_group_tax_exempt = '" . $customers_group_tax_exempt . "', group_payment_allowed = '". $group_payment_allowed ."', group_shipment_allowed = '". $group_shipment_allowed ."', group_order_total_allowed = '". $group_order_total_allowed ."', group_specific_taxes_exempt = '". $group_tax_rates_exempt ."' where customers_group_id = '" . $customers_group_id ."'");
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_group_id));
		break;
        
      case 'deleteconfirm':
        $group_id = tep_db_prepare_input($_GET['cID']);
        tep_db_query("delete from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id= " . $group_id); 
        $customers_id_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_group_id=" . $group_id);
        while($customers_id = tep_db_fetch_array($customers_id_query)) {
            tep_db_query("UPDATE " . TABLE_CUSTOMERS . " set customers_group_id = '0' where customers_id=" . $customers_id['customers_id']);
        }     
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')))); 
        break;
        
      case 'newconfirm' :
 /*     echo '<pre>';
      print_r($HTTP_POST_VARS);
      exit; */
    $customers_group_name = tep_db_prepare_input($HTTP_POST_VARS['customers_group_name']);
	$customers_group_tax_exempt = tep_db_prepare_input($HTTP_POST_VARS['customers_group_tax_exempt']);
	$customers_group_show_tax = tep_db_prepare_input($HTTP_POST_VARS['customers_group_show_tax']);
// totab2b start		
    $customers_group_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['customers_group_discount_sign']);
    $customers_group_discount = tep_db_prepare_input($HTTP_POST_VARS['customers_group_discount']);		
// totalb2b end		
	$group_payment_allowed = '';
	if ($HTTP_POST_VARS['payment_allowed']) {
	      foreach($HTTP_POST_VARS['payment_allowed'] as $val) {
	         if ($val == true) { 
	         $group_payment_allowed .= tep_db_prepare_input($val).';'; 
	         }
	      } // end while
	   $group_payment_allowed = substr($group_payment_allowed,0,strlen($group_payment_allowed)-1);
	} // end if ($HTTP_POST_VARS['payment_allowed'])
		$group_shipment_allowed = '';
		if ($HTTP_POST_VARS['shipping_allowed'] && $HTTP_POST_VARS['group_shipment_settings'] == '1') {
		  foreach($HTTP_POST_VARS['shipping_allowed'] as $val) {
		    if ($val == true) { 
		    $group_shipment_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_shipment_allowed = substr($group_shipment_allowed,0,strlen($group_shipment_allowed)-1);
		} // end if ($HTTP_POST_VARS['shipment_allowed'])
	$group_tax_rates_exempt = '';
	if ($HTTP_POST_VARS['group_tax_rate_exempt_id'] && $HTTP_POST_VARS['group_tax_rate_exempt_settings'] == '1') {
	      foreach($HTTP_POST_VARS['group_tax_rate_exempt_id'] as $val) {
	         if (tep_not_null($val)) { 
	         $group_tax_rates_exempt .= tep_db_prepare_input($val).','; 
	         }
	      } // end while
	   $group_tax_rates_exempt = substr($group_tax_rates_exempt,0,strlen($group_tax_rates_exempt)-1);
	} // end if ($HTTP_POST_VARS['group_tax_rate_exempt_settings'])
		$group_order_total_allowed = '';
		if ($HTTP_POST_VARS['order_total_allowed'] && $HTTP_POST_VARS['group_order_total_settings'] == '1') {
		  foreach($HTTP_POST_VARS['order_total_allowed'] as $val) {
		    if ($val == true) { 
		    $group_order_total_allowed .= tep_db_prepare_input($val).';'; 
		    }
		  } // end while
		  $group_order_total_allowed = substr($group_order_total_allowed,0,strlen($group_order_total_allowed)-1);
		} // end if ($HTTP_POST_VARS['order_total_allowed'])

        $last_id_query = tep_db_query("select MAX(customers_group_id) as last_cg_id from " . TABLE_CUSTOMERS_GROUPS . "");
        $last_cg_id_inserted = tep_db_fetch_array($last_id_query);
        $new_cg_id = $last_cg_id_inserted['last_cg_id'] +1;
        tep_db_query("insert into " . TABLE_CUSTOMERS_GROUPS . " set customers_group_id = " . $new_cg_id . ", customers_group_name = '" . tep_db_input($customers_group_name) . "', customers_group_discount = '" . $customers_group_discount_sign . $customers_group_discount . "', customers_group_show_tax = '" . $customers_group_show_tax . "', customers_group_tax_exempt = '" . $customers_group_tax_exempt . "', group_payment_allowed = '". $group_payment_allowed ."', group_shipment_allowed = '". $group_shipment_allowed ."', group_order_total_allowed = '". $group_order_total_allowed ."', group_specific_taxes_exempt = '". $group_tax_rates_exempt ."'");
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('action'))));
        break;
    }
  }
  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

?>

<!-- show customers group on screen -->
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1>
            <div class="col-md-6">
              <div class="row">              
			    <div class="col-md-10 col-xs-8">
<?php
                  echo '' . tep_draw_form('search', FILENAME_CUSTOMERS_GROUPS, '', 'get', 'class="col-sm-6 col-md-6"'). PHP_EOL .
				                 tep_draw_bs_input_field( 'search', '', HEADING_TITLE_SEARCH, 'id_cust_group_search' , 'sr-only', '', 'left', HEADING_TITLE_SEARCH ) . tep_hide_session_id() . PHP_EOL .
//                       '      <label class="sr-only" for="search">' . HEADING_TITLE_SEARCH . '</label>' . PHP_EOL .  
//                       '      '. tep_draw_input_field('search','', 'placeholder="' . HEADING_TITLE_SEARCH . '"') . tep_hide_session_id() . PHP_EOL .
	                   '    </form>' . PHP_EOL ; 
?>
                </div>  					   
			    <div class="col-md-2 col-xs-4"> 
<?php				
                   if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {

		             echo tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_CUSTOMERS_GROUPS)); 
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
                   <th>                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=group'  ); ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a>
				                           <?php echo TABLE_HEADING_NAME; ?>
				                           <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=group-desc');   ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a>
					</th>
                   <th class="text-center"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=discount'  ); ?>"><?php echo tep_glyphicon('sort-by-order' ); ?></a>
				                           <?php echo TABLE_HEADING_DISCOUNT; ?>
				                           <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=discount-desc');   ?>"><?php echo tep_glyphicon('sort-by-order-alt' ); ?></a>
					</th>				    			   
                   <th class="text-left" ><?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php
          switch ($HTTP_GET_VARS['listing']) {
              case "group":
              $order = "g.customers_group_name";
              break;
              case "group-desc":
              $order = "g.customers_group_name DESC";
              break;
             case "discount":
              $order = "g.customers_group_discount";
              break;
             case "discount-desc":
              $order = "g.customers_group_discount DESC";
              break;			  
             default:
              $order = "g.customers_group_id ASC";
          }

          $search = '';
          if ( ($HTTP_GET_VARS['search']) && (tep_not_null($HTTP_GET_VARS['search'])) ) {
             $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
             $search = "where g.customers_group_name like '%" . $keywords . "%'";
          }

          $customers_groups_query_raw = "select g.customers_group_id, g.customers_group_name, g.customers_group_discount from " . TABLE_CUSTOMERS_GROUPS . " g  " . $search . " order by $order";
          $customers_groups_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_groups_query_raw, $customers_groups_query_numrows);
          $customers_groups_query = tep_db_query($customers_groups_query_raw);

          while ($customers_groups = tep_db_fetch_array($customers_groups_query)) {
             if ((!isset($HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['cID'] == $customers_groups['customers_group_id'])) && (!$cInfo)) {
                $cInfo = new objectInfo($customers_groups);
             }

             if ( (is_object($cInfo)) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) ) {
                echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=edit') . '\'">' . "\n";
             } else {
                echo '<tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers_groups['customers_group_id']) . '\'">' . "\n";
		
             }
?>
                          <td>                   <?php echo $customers_groups['customers_group_name']  ; ?></td>
                          <td class="text-center"><?php echo $customers_groups['customers_group_discount']; ?>%</td>
                          <td class="text-left">						  
                              <div class="btn-toolbar" role="toolbar">                  
<?php
               echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $customers_groups['customers_group_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		            '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $customers_groups['customers_group_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL ;
//               echo '         			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_SPPC_GROUP_LIST_GROUP, 'cID=' . $customers_groups['customers_group_id'] ),                                                                null, 'info')  . '</div>' . PHP_EOL;
		      if ( $customers_groups['customers_group_id'] != 0 ) {
                 echo  '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $customers_groups['customers_group_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
			  }			   
			
?>
                              </div> 
				          </td>						
               </tr>						  
  
<?php
                  if (isset($cInfo) && is_object($cInfo) && ($customers_groups['customers_group_id'] == $cInfo->customers_group_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_GROUP . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('customers_groups', FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_group_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $customers_groups['customers_group_name']  . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->customers_group_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
									    $new_cust_group = 'false' ;
										$customers_groups_query = tep_db_query("select c.customers_group_id, c.customers_group_name, c.customers_group_discount, c.customers_group_show_tax, c.customers_group_tax_exempt, c.group_payment_allowed, c.group_shipment_allowed, c.group_order_total_allowed, c.group_specific_taxes_exempt from " . TABLE_CUSTOMERS_GROUPS . " c  where c.customers_group_id = '" . (int)$_GET['cID'] . "'");
										$customers_groups = tep_db_fetch_array($customers_groups_query);
										$cInfo = new objectInfo($customers_groups);
										
									   $payments_allowed = explode (";",$cInfo->group_payment_allowed);
									   $shipment_allowed = explode (";",$cInfo->group_shipment_allowed);
									   $order_total_allowed = explode (";",$cInfo->group_order_total_allowed);
									   $group_tax_ids_exempt = explode (",",$cInfo->group_specific_taxes_exempt);
									   $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
									   $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
									   $order_total_module_directory = DIR_FS_CATALOG_MODULES . 'order_total/';

									   $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
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
                                      
									  if ( $cInfo->customers_group_discount > 0 ) {
	                                    $discount_sign = '+' ;
	                                    $discount_amount = $cInfo->customers_group_discount ;
                                      } else {
	                                    $discount_sign   = '-' ;	
                                        $discount_amount = substr($cInfo->customers_group_discount,1,strlen($cInfo->customers_group_discount))	 ;
                                      }		
                                      $discount_array = array(array('id' => '-', 'text' => 'minus'),
                                                              array('id' => '+', 'text' => 'plus'));										  
 															  
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ; 
			                           $contents            .= '          <div class="panel-heading">' . HEADING_TITLE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('customer_group_edit', FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->customers_group_id . '&action=update', 'post', 'class="form-horizontal" role="form"', 'id_edit_cust_groups') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('customers_group_name',       $cInfo->customers_group_name,        ENTRY_GROUPS_NAME,       'id_input_countries_name' ,        'col-xs-3', 'col-xs-9', 'left', ENTRY_GROUPS_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('customers_group_discount',       $discount_amount, ENTRY_DEFAULT_DISCOUNT,       'id_input_discount_cust_group',       'col-xs-3', 'col-xs-6', 'left', ENTRY_DEFAULT_DISCOUNT ) . PHP_EOL;									   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_group_discount_sign', $discount_array, $discount_sign, '', 'inputCust_Group_Discount_Sign', 'col-xs-3', ' selectpicker show-tick ', '', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_group_show_tax', $cg_show_tax_array, (($cInfo->customers_group_show_tax == '1') ? '1' : '0'), ENTRY_GROUP_SHOW_TAX, 'inputCust_Group_Show_tax', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;										   
                                       $contents            .= '                       </div>' . PHP_EOL ;
 								   
								       $contents            .= '                       <br /><p>' . ENTRY_GROUP_SHOW_TAX_EXPLAIN_1 . '<br />' . ENTRY_GROUP_SHOW_TAX_EXPLAIN_2 . '</p><br />' . PHP_EOL;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_group_tax_exempt', $cg_tax_exempt_array, (($cInfo->customers_group_tax_exempt == '1') ? '1' : '0'), ENTRY_GROUP_TAX_EXEMPT, 'inputCust_Group_tax_exempt', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;										   
                                       $contents            .= '                       </div>' . PHP_EOL ;
 								   
  									   
									   $contents            .= '                       <br />' . PHP_EOL;

 
		                               $contents_tab_sppc_cust_group_01 = '' ;	   
                                       include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_payment.php' ) ;			  

									   $contents_tab_sppc_cust_group_02 = '' ;	   
									   include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_shipping.php' ) ;				  

									   $contents_tab_sppc_cust_group_03 = '' ;	   
									   include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_order_total.php' ) ;			  
									  
									   $contents_tab_sppc_cust_group_04 = '' ;	   
									   include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_tax_exempt.php' ) ;		  

									   $contents            .= '									   <div role="tabpanel" id="tab_sppc_cust_group_options">' . PHP_EOL;

									   $contents            .= '										  <!-- Nav tabs Customers Group SPPC options -->' . PHP_EOL;
									   $contents            .= '										  <ul class="nav nav-tabs" role="tablist" id="tab_sppc_cust_group_options"> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options" class="active"><a href="#tab_sppc_cust_group_option_payment"      aria-controls="tab_sppc_cust_group_payment"      role="tab" data-toggle="tab">' .  HEADING_TITLE_MODULES_PAYMENT            . '</a></li> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options">               <a href="#tab_sppc_cust_group_option_shipment"     aria-controls="tab_sppc_cust_group_shipment"     role="tab" data-toggle="tab">' .  HEADING_TITLE_MODULES_SHIPPING           . '</a></li> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options">               <a href="#tab_sppc_cust_group_option_ordertotal"   aria-controls="tab_sppc_cust_group_ordertotal"   role="tab" data-toggle="tab">' .  HEADING_TITLE_MODULES_ORDER_TOTAL        . '</a></li> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options">               <a href="#tab_sppc_cust_group_option_vat_exempt"   aria-controls="tab_sppc_cust_group_vatexempt"    role="tab" data-toggle="tab">' .  HEADING_TITLE_GROUP_TAX_RATES_EXEMPT     . '</a></li> ' . PHP_EOL;
							 
									   $contents            .= '										   </ul>' . PHP_EOL; 

									   $contents            .= '										   <!-- Tab panes SPPC options --> ' . PHP_EOL;
									   $contents            .= '										   <div  id="tab_sppc_cust_group_options" class="tab-content"> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane active" id="tab_sppc_cust_group_option_payment">    ' . $contents_tab_sppc_cust_group_01 . '</div> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane"        id="tab_sppc_cust_group_option_shipment">   ' . $contents_tab_sppc_cust_group_02 . '</div> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane"        id="tab_sppc_cust_group_option_ordertotal"> ' . $contents_tab_sppc_cust_group_03 . '</div> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane"        id="tab_sppc_cust_group_option_vat_exempt"> ' . $contents_tab_sppc_cust_group_04 . '</div> ' . PHP_EOL;
									   $contents            .= '										   </div> ' . PHP_EOL;
									   $contents            .= '									   </div> 	  ' . PHP_EOL;
 								   
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL , 'remove', tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
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
			                            $contents .= '                              ' . ENTRY_GROUP_SHOW_TAX . ' ' . (($cInfo->customers_group_show_tax == '1') ? ENTRY_GROUP_TAX_EXEMPT_YES : ENTRY_GROUP_TAX_EXEMPT_NO ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . ENTRY_GROUP_TAX_EXEMPT . '  ' . (($cInfo->customers_group_tax_exempt == '1') ? ENTRY_GROUP_TAX_EXEMPT_YES : ENTRY_GROUP_TAX_EXEMPT_NO ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_CODE_3 . '  ' . $cInfo->countries_iso_code_3. PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_ADDRESS_FORMAT . '  ' . $cInfo->address_format_id. PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;											
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_CUSTOMERS_GROUPS ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

 	
//		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			
 
                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="3">' . PHP_EOL .
                                      '                    <div class="row ' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL ;
								  
                              }		// end if assets		
				} // end while while ($countries = tep_db_fetch_arra
?>				
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $customers_groups_split->display_count($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_GROUPS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $customers_groups_split->display_links($customers_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_CUSTOMER_GROUP, 'plus', null,'data-toggle="modal" data-target="#new_customer_group"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  
	  </table>	
	  
<?php
										$customers_groups_query = tep_db_query("select c.customers_group_id, c.customers_group_name, c.customers_group_discount, c.customers_group_show_tax, c.customers_group_tax_exempt, c.group_payment_allowed, c.group_shipment_allowed, c.group_order_total_allowed, c.group_specific_taxes_exempt from " . TABLE_CUSTOMERS_GROUPS . " c  where c.customers_group_id = '" . (int)$_GET['cID'] . "'");
										$customers_groups = tep_db_fetch_array($customers_groups_query);
										$cInfo = new objectInfo($customers_groups);
										
									   $payments_allowed = explode (";",$cInfo->group_payment_allowed);
									   $shipment_allowed = explode (";",$cInfo->group_shipment_allowed);
									   $order_total_allowed = explode (";",$cInfo->group_order_total_allowed);
									   $group_tax_ids_exempt = explode (",",$cInfo->group_specific_taxes_exempt);
									   $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
									   $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
									   $order_total_module_directory = DIR_FS_CATALOG_MODULES . 'order_total/';

									   $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
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
                                      
                                      $discount_sign   = '-' ;	
                                      $discount_amount = 0 	 ;
                                      	
                                      $discount_array = array(array('id' => '-', 'text' => 'minus'),
                                                              array('id' => '+', 'text' => 'plus'));										  
?>	  
        <div class="modal fade"  id="new_customer_group" role="dialog" aria-labelledby="new_customer_group" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('customer_group', FILENAME_CUSTOMERS_GROUPS, tep_get_all_get_params(array('action')) . 'action=newconfirm') ; ?>
 
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo HEADING_TITLE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
                                       $new_cust_group = 'true' ;
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ; 
			                           $contents            .= '          <div class="panel-heading">' . HEADING_TITLE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
//			                           $contents            .= '               ' . tep_draw_bs_form('customer_group_edit', FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->customers_group_id . '&action=update', 'post', 'class="form-horizontal" role="form"', 'id_edit_cust_groups') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('customers_group_name',       null,        ENTRY_GROUPS_NAME,       'id_input_countries_name' ,        'col-xs-3', 'col-xs-9', 'left', ENTRY_GROUPS_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('customers_group_discount',       $discount_amount, ENTRY_DEFAULT_DISCOUNT,       'id_input_discount_cust_group',       'col-xs-3', 'col-xs-6', 'left', ENTRY_DEFAULT_DISCOUNT ) . PHP_EOL;									   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_group_discount_sign', $discount_array, $discount_sign, '', 'inputCust_Group_Discount_Sign', 'col-xs-3', ' selectpicker show-tick ', '', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_group_show_tax', $cg_show_tax_array, null, ENTRY_GROUP_SHOW_TAX, 'inputCust_Group_Show_tax', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;										   
                                       $contents            .= '                       </div>' . PHP_EOL ;
 								   
								       $contents            .= '                       <br /><p>' . ENTRY_GROUP_SHOW_TAX_EXPLAIN_1 . '<br />' . ENTRY_GROUP_SHOW_TAX_EXPLAIN_2 . '</p><br />' . PHP_EOL;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('customers_group_tax_exempt', $cg_tax_exempt_array, null, ENTRY_GROUP_TAX_EXEMPT, 'inputCust_Group_tax_exempt', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;										   
                                       $contents            .= '                       </div>' . PHP_EOL ;
 								   
  									   
									   $contents            .= '                       <br />' . PHP_EOL;

/* 	  
		                               $contents_tab_sppc_cust_group_01 = '' ;	   
                                       include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_payment.php' ) ;			  

									   $contents_tab_sppc_cust_group_02 = '' ;	   
									   include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_shipping.php' ) ;				  

									   $contents_tab_sppc_cust_group_03 = '' ;	   
									   include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_order_total.php' ) ;			  
									  
									   $contents_tab_sppc_cust_group_04 = '' ;	   
									   include( DIR_WS_MODULES . 'cust_group_edit_sppc_option_tax_exempt.php' ) ;		  

									   $contents            .= '									   <div role="tabpanel" id="tab_sppc_cust_group_options_new">' . PHP_EOL;

									   $contents            .= '										  <!-- Nav tabs Customers Group SPPC options -->' . PHP_EOL;
									   $contents            .= '										  <ul class="nav nav-tabs" role="tablist" id="tab_sppc_cust_group_options_new"> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options_new" class="active"><a href="#tab_sppc_cust_group_option_payment_new"      aria-controls="tab_sppc_cust_group_payment_new"      role="tab" data-toggle="tab">' .  HEADING_TITLE_MODULES_PAYMENT            . '</a></li> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options_new">               <a href="#tab_sppc_cust_group_option_shipment_new"     aria-controls="tab_sppc_cust_group_shipment_new"     role="tab" data-toggle="tab">' .  HEADING_TITLE_MODULES_SHIPPING           . '</a></li> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options_new">               <a href="#tab_sppc_cust_group_option_ordertotal_new"   aria-controls="tab_sppc_cust_group_ordertotal_new"   role="tab" data-toggle="tab">' .  HEADING_TITLE_MODULES_ORDER_TOTAL        . '</a></li> ' . PHP_EOL;
									   $contents            .= '											 <li  id="tab_sppc_cust_group_options_new">               <a href="#tab_sppc_cust_group_option_vat_exempt_new"   aria-controls="tab_sppc_cust_group_vatexempt_new"    role="tab" data-toggle="tab">' .  HEADING_TITLE_GROUP_TAX_RATES_EXEMPT     . '</a></li> ' . PHP_EOL;
							 
									   $contents            .= '										   </ul>' . PHP_EOL; 

									   $contents            .= '										   <!-- Tab panes SPPC options --> ' . PHP_EOL;
									   $contents            .= '										   <div  id="tab_sppc_cust_group_options_new" class="tab-content"> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane active" id="tab_sppc_cust_group_option_payment_new">    ' . $contents_tab_sppc_cust_group_01 . '</div> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane"        id="tab_sppc_cust_group_option_shipment_new">   ' . $contents_tab_sppc_cust_group_02 . '</div> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane"        id="tab_sppc_cust_group_option_ordertotal_new"> ' . $contents_tab_sppc_cust_group_03 . '</div> ' . PHP_EOL;
									   $contents            .= '											 <div role="tabpanel" class="tab-pane"        id="tab_sppc_cust_group_option_vat_exempt_new"> ' . $contents_tab_sppc_cust_group_04 . '</div> ' . PHP_EOL;
									   $contents            .= '										   </div> ' . PHP_EOL;
									   $contents            .= '									   </div> 	  ' . PHP_EOL;

*/									   
                                       
//                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
//									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page']), null, null, 'btn-default text-danger') . PHP_EOL;			
//		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel			
?>
                                        <div class="full-iframe" width="100%"> 
                                            <?php echo $contents . $contents_footer ; ?>
                                        </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CUSTOMERS_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $HTTP_GET_VARS['lID'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_country -->	  

<?php 

require(DIR_WS_INCLUDES . 'template_bottom.php') ;
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>