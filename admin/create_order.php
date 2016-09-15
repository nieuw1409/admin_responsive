<?php
/*
  $Id: create_order.php,v 1 2003/08/17 23:21:34 frankl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // #### Get Available Customers

  $query = tep_db_query("select a.customers_id, a.customers_firstname, a.customers_lastname, b.entry_company, b.entry_city, c.zone_code from " . TABLE_CUSTOMERS . " AS a, " . TABLE_ADDRESS_BOOK . " AS b LEFT JOIN " . TABLE_ZONES . " as c ON (b.entry_zone_id = c.zone_id) WHERE a.customers_default_address_id = b.address_book_id  ORDER BY entry_company,customers_lastname");
  $result = $query;


    $customer_id = $HTTP_GET_VARS['Customer'] ;
/*    $SelectCustomerBox = "<select class=\"form-control selectpicker show-tick\"  name=\"Customer\" id=\"Customer\"><option value=\"\">" . TEXT_SELECT_CUST . "</option>\n";

    while($db_Row = tep_db_fetch_array($result)){ 

      $SelectCustomerBox .= "<option value=\"" . $db_Row['customers_id'] . "\"";

      if(isSet($HTTP_GET_VARS['Customer']) and $db_Row['customers_id']==$HTTP_GET_VARS['Customer']){
        $SelectCustomerBox .= " SELECTED ";
        $SelectCustomerBox .= ">" . (empty($db_Row['entry_company']) ? "": strtoupper($db_Row['entry_company']) . " - " ) . $db_Row['customers_lastname'] . " , " . $db_Row['customers_firstname'] . " - " . $db_Row['entry_city'] . ", " . $db_Row['zone_code'] . "</option>\n";
      }else{
        $SelectCustomerBox .= ">" . (empty($db_Row['entry_company']) ? "": strtoupper($db_Row['entry_company']) . " - " ) . $db_Row['customers_lastname'] . " , " . $db_Row['customers_firstname'] . " - " . $db_Row['entry_city'] . ", " . $db_Row['zone_code'] . "</option>\n";
      }
      
    }

    $SelectCustomerBox .= "</select>\n";
*/
 	$SelectCustomerBox = array(array('id' => '4', 'text' => TEXT_NONE));
    while ($customers_existing = tep_db_fetch_array($result)) {
      if(isSet($HTTP_GET_VARS['Customer']) and $customers_existing['customers_id']==$HTTP_GET_VARS['Customer']){
         $default_customer =  $customers_existing['customers_id'];		
	  }
      $SelectCustomerBox[] = array('id' => $customers_existing['customers_id'] ,
                                          'text'  => (empty($customers_existing['entry_company']) ? "": strtoupper($customers_existing['entry_company']) . " - " ) . 
										             $customers_existing['customers_lastname'] . " , " . 
													 $customers_existing['customers_firstname'] . " - " . 
													 $customers_existing['entry_city'] . ", " . 
													 $customers_existing['zone_code'] );
    }
	
	
	$query = tep_db_query("select code, value, title from " . TABLE_CURRENCIES . " ORDER BY code");
	$result = $query;
	
	if (tep_db_num_rows($result) > 0){
		
 	  $SelectCurrencyBox = array(array('id' => '', 'text' => TEXT_SELECT_CURRENCY));		
      while($db_Row = tep_db_fetch_array($result)){
	    if ($db_Row["code"] == DEFAULT_CURRENCY){
	       $Selected_Currency = $db_Row["code"];
	    }		  
         $SelectCurrencyBox[] = array('id'   => $db_Row["code"] ,
                                      'text' => $db_Row["title"] );
      }
	}	

	$stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
	$result = $stores_customers_query ;
	if (tep_db_num_rows($result) > 0){
		
 	  $SelectStoreBox = array(array('id' => '', 'text' => TEXT_SELECT_STORES));		
      while($db_Row = tep_db_fetch_array($result)){
	    if ($db_Row["stores_id"] == $multi_stores_id ){
	       $Selected_Store = $db_Row["stores_id"];
	    }		  
         $SelectStoreBox[] = array('id'   => $db_Row["stores_id"] ,
                                   'text' => $db_Row["stores_name"] );
      }
	}	
	
/*	  
	  // Query Successful
	  $SelectCurrencyBox = "<select name=\"Currency\"><option value=\"\">" . TEXT_SELECT_CURRENCY . "</option>\n";
	  while($db_Row = tep_db_fetch_array($result)){ 
	    $SelectCurrencyBox .= "<option value='" . $db_Row["code"] . " , " . $db_Row["value"] . "'";

	    if ($db_Row["code"] == DEFAULT_CURRENCY){
	      $SelectCurrencyBox .= " SELECTED ";
	    }

	    $SelectCurrencyBox .= ">" . $db_Row["code"] . "</option>\n";
	  }
	  $SelectCurrencyBox .= "</select>\n";
	}
 
// multi stores	

    $SelectStoreBox .= "</select>\n";
    
	$stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
	$result = $stores_customers_query ;

	if (tep_db_num_rows($result) > 0){	
	  $SelectStoreBox = "<select name=\"Stores\"><option value=\"\">" . TEXT_SELECT_STORES . "</option>\n";
	  while($db_Row = tep_db_fetch_array($result)){ 
	    $SelectStoreBox .= "<option value='" . $db_Row["stores_id"] . " , " . $db_Row["stores_name"] . "'";

	    if ($db_Row["stores_id"] == $multi_stores_id ){
	      $SelectStoreBox .= " SELECTED ";
	    }

	    $SelectStoreBox .= ">" . $db_Row["stores_name"] . "</option>\n";
	  }
	  $SelectStoreBox .= "</select>\n";
	}
*/		
// eof multi stores

    

	if(isset($HTTP_GET_VARS['Customer'])){
 	  $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
 	  $account = tep_db_fetch_array($account_query);
 	  $customer = $account['customers_id'];
 	  $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
 	  $address = tep_db_fetch_array($address_query);
	}elseif (isset($HTTP_GET_VARS['Customer_nr'])){
 	  $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer_nr'] . "'");
 	  $account = tep_db_fetch_array($account_query);
 	  $customer = $account['customers_id'];
 	  $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer_nr'] . "'");
 	  $address = tep_db_fetch_array($address_query);
	}

    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);

  // #### Generate Page
  require('includes/template_top.php');  
?>

<?php require('includes/form_check.js.php'); ?>
<script language="javascript" type="text/javascript"><!--
$(function() {
  var cache = {};
  $("#cust_select_name_field").autocomplete({
     // source: "autocomplete.php",
      minLength: 2,
      select: function(event, ui) {
        window.location  = (ui.item.id);
      },
      source: function( request, response ) {
        var term = request.term;
        if ( term in cache ) {
          response( cache[ term ] );
          return;
        }

        $.getJSON( "create_order.php", request, function( data, status, xhr ) {
          cache[ term ] = data;
          response( data );
        });
      }
  }).data("ui-autocomplete")._renderItem = function( ul, item ) {
      return $( "<li></li>" )
      .data( "item.autocomplete", item )
      .append( "<a href='" + item.id + "' onClick='return false;'>"+ item.value + "</a>" )
      .appendTo( ul );
  };

  $('#customers_dob').datepicker({dateFormat: '<?php echo JQUERY_DATEPICKER_FORMAT; ?>'
, changeMonth: true, changeYear: true, yearRange: '-100:-18', defaultDate: "-30y"});

  $( "body" ).on('change', '#Customer', function( event ) {
    $( this ).closest( "form" ).submit();
  });

});

function selectExisting() {
  document.create_order.customers_create_type.value = 'existing';
  selectorsStatus(false);
  selectorsExtras(true);
}
function selectNew() {
  document.create_order.customers_create_type.value = 'new';
  selectorsStatus(true);
  selectorsExtras(false);
}
function selectNone() {
  document.create_order.customers_create_type.value = 'none';
  selectorsStatus(true);
  selectorsExtras(true);
}
function selectorsStatus(status) {
  document.cust_select.Customer.disabled = status;
  //document.cust_select.cust_select_button.disabled = status;
  $( "#cust_select_id_field" ).prop( 'disabled', status );
  $( "#cust_select_id_button" ).prop( 'disabled', status );
  $( "#cust_select_email_field" ).prop( 'disabled', status );
  $( "#cust_select_email_button" ).prop( 'disabled', status );
  $( "#cust_select_name_field" ).prop( 'disabled', status );
  $( "#cust_select_name_button" ).prop( 'disabled', status ); 
  $( "#customer_name" ).prop( 'disabled', status );   
}
function selectorsExtras(status) {
  $( "#customers_password" ).prop( 'disabled', status );
  $( "#customers_newsletter" ).prop( 'disabled', status );
<?php if (ACCOUNT_DOB == 'true') { ?>
  $( "#customers_dob" ).prop( 'disabled', status );
<?php } ?>
<?php if (ACCOUNT_GENDER == 'true') { ?>
  document.create_order.customers_gender[0].disabled = status;
  document.create_order.customers_gender[1].disabled = status;
<?php } ?>
}
//--></script>

      <div class="page-header">
        <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1>
<?php 
       if ( $multi_stores_array == 1 ) {
?>
        <h2 class="col-xs-12 col-md-6"><?php echo TEXT_MULTI_STORE_NAME . $multi_stores  ; ?></h2>		
<?php
       }		
?>	   
		<div class="clearfix"></div>
        </div><!-- page-header-->
        <div class="panel panel-primary" onLoad="selectorsExtras(true)"> 
		     <div class="panel-heading"><?php echo TEXT_STEP_1; ?></div>		  
			 <div class="panel-body">		
 
	              <div class="form-group"> 
                      <?php echo tep_bs_radio_field('handle_customer', 'existing', CREATE_ORDER_TEXT_EXISTING_CUST,     'existing_customer',   true , 'radio radio-success radio-inline', '', '', 'right', 'onClick="selectExisting();"') ; ?>
					  <hr>
                      <?php echo tep_bs_radio_field('handle_customer', 'new',  CREATE_ORDER_TEXT_NEW_CUST, 'new_customer',  null , 'radio radio-success', '', '', 'right', 'onClick="selectNew();"') ; ?> 
                      <hr>
                      <?php echo tep_bs_radio_field('handle_customer', 'none', CREATE_ORDER_TEXT_NO_CUST,  'no_customer',   null , 'radio radio-success ', '', '', 'right', 'onClick="selectNone();"') ; ?>					  
					  <hr>
                  </div> 
                  <div>				  
<?php				  
                    echo tep_draw_bs_form('cust_select', FILENAME_CREATE_ORDER, 'GET', 'role="form"', 'cust_select') . PHP_EOL;
                    echo tep_hide_session_id();
                    echo '<div class="col-md-9 col-xs-12">' . PHP_EOL;
                    echo '    <div class="form-group">' . PHP_EOL;
					echo          tep_draw_bs_pull_down_menu( 'Customer', $SelectCustomerBox,     $default_customer, TEXT_SELECT_CUST, 'customer', 'col-md-9', ' selectpicker show-tick', 'control-label col-md-3', 'left', null, null, true ) . PHP_EOL;
 				
					echo '    </div>' . PHP_EOL;
					echo '</div>' . PHP_EOL;
                    echo '<div class="col-md-3 col-xs-12">' . PHP_EOL;					
					echo     tep_draw_bs_button( BUTTON_SUBMIT, 'ok'  ) . PHP_EOL;
					echo '</div>' . PHP_EOL;
                    echo '</form>' . PHP_EOL;
?>					
                  </div>
				  <div>				  
 <?php
                    echo tep_draw_bs_form('cust_select_id', FILENAME_CREATE_ORDER, 'get', 'class="form-inline" role="form"', 'cust_select_id_field') . PHP_EOL;
                    echo tep_hide_session_id();
                    echo '<div class="col-md-9 col-xs-12">' . PHP_EOL;
                    echo '    <div class="form-group">' . PHP_EOL;
                    echo '       <div class="col-md-12 col-xs-12">' . PHP_EOL;					
                    echo           tep_draw_bs_input_field('Customer_nr', '',  TEXT_OR_BY, 'cust_select_id_field' , 'control-label col-md-3', 'col-md-9', 'left'	). PHP_EOL;
					echo '       </div>' . PHP_EOL;					
					echo '    </div>' . PHP_EOL;
					echo '</div>' . PHP_EOL;
                    echo '<div class="col-md-3 col-xs-12">' . PHP_EOL;					
					echo     tep_draw_bs_button( BUTTON_SUBMIT, 'ok' ) . PHP_EOL;
					echo '</div>' . PHP_EOL;					
                    echo "</form>" . PHP_EOL;
?>
                 </div>
				 <div>
<?php
                    echo tep_draw_bs_form('cust_select_email', FILENAME_CREATE_ORDER, 'get', 'class="form-inline" role="form"', 'cust_select_email') . PHP_EOL;
                    echo tep_hide_session_id();
                    echo '<div class="col-md-9 col-xs-12">' . PHP_EOL;
                    echo '    <div class="form-group">' . PHP_EOL;
                    echo '       <div class="col-md-12 col-xs-12">' . PHP_EOL;					
                    echo           tep_draw_bs_input_field('Customer_email', '',  TEXT_OR_BY_EMAIL, 'cust_select_email_field' , 'control-label col-md-3', 'col-md-9', 'left'	). PHP_EOL;
					echo '       </div>' . PHP_EOL;					
					echo '    </div>' . PHP_EOL;
					echo '</div>' . PHP_EOL;
                    echo '<div class="col-md-3 col-xs-12">' . PHP_EOL;					
					echo     tep_draw_bs_button( BUTTON_SUBMIT, 'ok'  ) . PHP_EOL;
					
					echo '</div>' . PHP_EOL;					
                    echo "</form>" . PHP_EOL;				
?>				 
				 </div>
				 <div>
<?php
                    echo tep_draw_bs_form('cust_select_name', FILENAME_CREATE_ORDER, 'get', 'class="form-inline" role="form"', 'cust_select_name') . PHP_EOL;
                    echo tep_hide_session_id();
                    echo '<div class="col-md-9 col-xs-12">' . PHP_EOL;
                    echo '    <div class="form-group">' . PHP_EOL;
                    echo '       <div class="col-md-12 col-xs-12">' . PHP_EOL;					
                    echo           tep_draw_bs_input_field('Customer_name', '',  TEXT_OR_BY_NAME, 'customer_name' , 'control-label col-md-3', 'col-md-9', 'left'	). PHP_EOL;
					echo '       </div>' . PHP_EOL;					
					echo '    </div>' . PHP_EOL;
					echo '</div>' . PHP_EOL;
                    echo '<div class="col-md-3 col-xs-12">' . PHP_EOL;					
					echo     tep_draw_bs_button( BUTTON_SUBMIT, 'ok'  ) . PHP_EOL;
					echo '</div>' . PHP_EOL;					
                    echo "</form>" . PHP_EOL;
?>				 			 
				 </div>           				 
					 
		       
			 </div>  <!-- end panel body TEXT_STEP_1 -->
		</div>     <!-- end panel TEXT_STEP_1 --> 
		  
        <div class="panel panel-primary"> 
		     <div class="panel-heading"><?php echo TEXT_STEP_2; ?></div>		  
			 <div class="panel-body">	
			   <div>
			 
<?php 
               echo tep_draw_bs_form('create_order', FILENAME_CREATE_ORDER_PROCESS, '', 'post', 'role="form"', 'id_form_create_order') .
                    tep_draw_hidden_field('customers_create_type', 'existing', 'id="customers_create_type"')			   ; 
					
//tep_draw_form('account_edit', FILENAME_CREATE_ACCOUNT_PROCESS, tep_get_all_get_params(array('action')) , 'post', 'class="form-inline" role="form"') . tep_draw_hidden_field('action', 'process'); 					
                   //onSubmit="return check_form();"
                   require(DIR_WS_MODULES . FILENAME_CREATE_ORDER_DETAILS);
?>			   			   
			   <div> 
                    <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok'); ?>			   
                    <?php echo tep_draw_bs_button( IMAGE_BACK, 'remove', tep_href_link(FILENAME_DEFAULT, '', 'SSL') ) ; ?></td>					
			   </div>
			     </form>
			   </div>
			 </div>  <!-- end panel body TEXT_STEP_2 -->
		</div>     <!-- end panel TEXT_STEP_1 -->             			 

<script language="javascript" type="text/javascript"><!--
selectorsExtras(true);
//--></script>

<?php 
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>