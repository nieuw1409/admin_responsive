<?php
//	  $contents_edit_orders_heading .= '       <div class="panel panel-primary">' . PHP_EOL ;
//	  $contents_edit_orders_heading .= '          <div class="panel-heading">' . ENTRY_CUSTOMER . '</div>' . PHP_EOL;
//	  $contents_edit_orders_heading .= '          <div class="panel-body">' . PHP_EOL;	

  // include the appropriate functions & classes
/* in ordderrs.php  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
  
*/

   
  // Include currencies class
  require_once(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();	
       $oID = $HTTP_GET_VARS['oID'] ;
      $edit_address_order = new manualOrder($oID);	
      $contents_edit_orders_heading .= '                        <div class="col-xs-12 col-xs-12 col-md-12">' . PHP_EOL;	   

	  $contents_edit_orders_tab1    = '' ;
      include( DIR_WS_MODULES . 'edit_order_edit_customer_address.php' ) ;	

	  $contents_edit_orders_tab2    = '' ;
      include( DIR_WS_MODULES . 'edit_order_edit_shipping_address.php' ) ;	  
	  
	  $contents_edit_orders_tab3    = '' ;
      include( DIR_WS_MODULES . 'edit_order_edit_invoice_address.php' ) ;	 	   	  

	  $contents_edit_orders_tab4    = '' ;
      include( DIR_WS_MODULES . 'edit_order_edit_payment_methode.php' ) ;	 		  

	  $contents_edit_orders_footer .= '                         </div>' . PHP_EOL;	// class="col-xs-12 col

	  $contents_edit_orders_footer .= '                         <br />' . PHP_EOL;	 
	  
/*	  $contents_edit_orders_footer .= '                         <div class="row">' . PHP_EOL;		
	  $contents_edit_orders_footer .= '                           <div class="col-md-12">' . PHP_EOL;
         
	  $contents_edit_orders_footer .= '		                     ' .  tep_draw_bs_button(IMAGE_SAVE, "ok", null) . '&nbsp;&nbsp;' . PHP_EOL;
	  $contents_edit_orders_footer .= '		                     ' .  tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $_GET['oID'])). PHP_EOL;
	  $contents_edit_orders_footer .= '                           </div>' . PHP_EOL;
	  $contents_edit_orders_footer .= '                         </div>' . PHP_EOL; 	
*/
 
//	  $contents_edit_orders_footer .= '       </div>' . PHP_EOL; // end div 	panel body
//	  $contents_edit_orders_footer .= '     </div>' . PHP_EOL; // end div 	panel
	  
	  
	  $contents_edit_orders_edit .=   $contents_edit_orders_heading . PHP_EOL ;	  
	  
      $contents_edit_orders_edit .=  '<div role="tabpanel" id="tab_edit_orders_address">' . PHP_EOL;
      $contents_edit_orders_edit .=  '  <!-- Nav tabs edit products -->' . PHP_EOL ;
      $contents_edit_orders_edit .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_orders_address">' . PHP_EOL ;
      $contents_edit_orders_edit .=  '    <li  id="tab_edit_orders_address" class="active"><a href="#tab_edit_orders_cust_std_address"      aria-controls="tab_edit_orders_customer_address"             role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_ORDER_01 . '</a></li>' . PHP_EOL ;
      $contents_edit_orders_edit .=  '    <li  id="tab_edit_orders_address">               <a href="#tab_edit_orders_cust_ship_address"     aria-controls="tab_edit_orders_customer_ship_adr"           role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_ORDER_02 . '</a></li>'  . PHP_EOL;	  
      $contents_edit_orders_edit .=  '    <li  id="tab_edit_orders_address">               <a href="#tab_edit_orders_cust_invoi_address"    aria-controls="tab_edit_orders_customer_invoice_adr"        role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_ORDER_03 . '</a></li>'  . PHP_EOL;	  
      $contents_edit_orders_edit .=  '    <li  id="tab_edit_orders_address">               <a href="#tab_edit_orders_cust_payment_method"  aria-controls="tab_edit_orders_customer_payment"            role="tab" data-toggle="tab">' . TEXT_TABS_EDIT_ORDER_04 . '</a></li>'  . PHP_EOL;	  
  
      $contents_edit_orders_edit .=  '  </ul>'  . PHP_EOL;

      $contents_edit_orders_edit .=  '  <!-- Tab panes -->' . PHP_EOL ;
      $contents_edit_orders_edit .=  '  <div  id="tab_edit_orders_address" class="tab-content">'  . PHP_EOL;
      $contents_edit_orders_edit .=  '    <div role="tabpanel" class="tab-pane active" id="tab_edit_orders_cust_std_address">'           . $contents_edit_orders_tab1 . '</div>' . PHP_EOL ;
      $contents_edit_orders_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_orders_cust_ship_address">'          . $contents_edit_orders_tab2 . '</div>' . PHP_EOL ;
      $contents_edit_orders_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_orders_cust_invoi_address">'         . $contents_edit_orders_tab3 . '</div>' . PHP_EOL ;			  
      $contents_edit_orders_edit .=  '    <div role="tabpanel" class="tab-pane"        id="tab_edit_orders_cust_payment_method">'       . $contents_edit_orders_tab4 . '</div>' . PHP_EOL ;		  
      $contents_edit_orders_edit .=  '  </div>' . PHP_EOL ; 
      $contents_edit_orders_edit .=  '</div>' . PHP_EOL ;	
	  $contents_edit_orders_edit .=   $contents_edit_orders_footer . PHP_EOL ;	
	
// end bootstrap products	
	
//       echo '      <!-- begin tabs adress -->           ' . PHP_EOL .
//             '                   ' . PHP_EOL .
//             '                     <div class="row' . $alertClass . '">' . PHP_EOL .
//                                    $contents_edit_orders_edit . 
//             '                    </div>' . PHP_EOL .
//             '                  ' . PHP_EOL .
//             '                ' . PHP_EOL .
//			 '     <!-- end tabs adress --> ' . PHP_EOL ;
?>