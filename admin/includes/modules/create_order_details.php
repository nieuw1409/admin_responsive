<?php
/*
  $Id: create_order_details.php,v 1.2 2005/09/04 04:42:56 loic Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  $newsletter_array = array(array('id' => '1',
                                  'text' => ENTRY_NEWSLETTER_YES),
                            array('id' => '0',
                                  'text' => ENTRY_NEWSLETTER_NO));
?>
<div class="panel panel-primary">   
	<div class="panel-body">
      <div class="form-group">	
<?php 
        echo ENTRY_CUSTOMERS_ID . '&nbsp;&nbsp;' . $account['customers_id']; 
		echo tep_draw_hidden_field('customers_id', $account['customers_id']) ; 
?>
      </div>
      <div>
<?php	  
        if (ACCOUNT_GENDER == 'true') { 
   	       echo  '<label class="col-xs-3 control-label">' . ENTRY_GENDER . '</label>' . PHP_EOL ;
 	       echo  ' <div class="col-xs-9">' . PHP_EOL ; 		   
 	       echo  '   <div class="form-group">' . PHP_EOL ; 	 
           echo   			   tep_bs_radio_field('customers_gender', 'm', ENTRY_GENDER_MALE,     'input_Cust_Gender_Male',   ($account['customers_gender']=='m'?true:false), 'radio radio-success radio-inline', '', '', 'right') . PHP_EOL  ;	
           echo   			   tep_bs_radio_field('customers_gender', 'f', ENTRY_GENDER_FEMALE,   'input_Cust_Gender_Female', ($account['customers_gender']=='f'?true:false), 'radio radio-success radio-inline', '', '', 'right') . PHP_EOL  ;	 	 
           echo  '   </div>'. PHP_EOL  ;			   
           echo  ' </div>'. PHP_EOL  ;	
	       echo  '<br />'. PHP_EOL  ;
        }
?>
	
	  </div>
	  <div class="form-group">
           <?php echo tep_draw_bs_input_field('customers_firstname', $account['customers_firstname'], ENTRY_FIRST_NAME, 'input_Cust_First_Name' , 'control-label col-xs-3', 'col-xs-9', 'left'	) ; ?>
	  </div>
	  <div class="form-group">
           <?php echo tep_draw_bs_input_field('customers_lastname', $account['customers_lastname'], ENTRY_LAST_NAME,    'input_Cust_Last_Name' , 'control-label col-xs-3', 'col-xs-9', 'left'	) ; ?>	  
	  </div>

<?php
      if (ACCOUNT_DOB == 'true') {

		echo '                                <div class="form-group">' . PHP_EOL .
             '                                    <label for="customers_dob" class="col-xs-3 control-label">' . ENTRY_DATE_OF_BIRTH . PHP_EOL . 								 
			 '                                    </label> ' . PHP_EOL .										 
			 '                                    <div class="col-xs-9">'  . tep_draw_bs_input_date('customers_dob',                                               // name
	                                                                          (!empty($account['customers_dob'])?tep_date_short($account['customers_dob']):''),           // value
			                                                                  'id="customers_dob"',            // parameters
			                                                                  null,                                                // type
			                                                                  true,                                              // reinsert value
			                                                                  ENTRY_DATE_OF_BIRTH                             // placeholder
				                                                                        ) . PHP_EOL .
			'                                    </div>' .
			'                                </div><hr><br />' . PHP_EOL;			
 
      }
?>	  
	  <div class="form-group">
           <?php echo tep_draw_bs_input_field('customers_email_address', $account['customers_email_address'], ENTRY_EMAIL_ADDRESS,    'input_Cust_Email' , 'control-label col-xs-3', 'col-xs-9', 'left'	) ; ?>	  
	  </div>
  
	</div>
</div>
<?php 
if (ACCOUNT_COMPANY == 'true') { 
?> 
    <div class="panel panel-primary">   
       <div class="panel-body">
	       <div class="form-group">
                <?php echo tep_draw_bs_input_field('entry_company', $address['entry_company'], ENTRY_COMPANY,    'input_Cust_Company_Name' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_COMPANY_TEXT	) ; ?>	  
	       </div>
	   </div>
    </div>		   
<?php 
} 
?>	
<div class="panel panel-primary">   
	<div class="panel-body">
	    <div class="form-group">
                <?php echo tep_draw_bs_input_field('entry_street_address', $address['entry_street_address'], ENTRY_STREET_ADDRESS,    'input_Cust_Street_Adress' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_STREET_ADDRESS_TEXT	) ; ?>	  
	    </div>
<?php 
        if (ACCOUNT_SUBURB == 'true') { 
?>		
	      <div class="form-group">
                <?php echo tep_draw_bs_input_field('entry_suburb', $address['entry_suburb'], ENTRY_SUBURB,    'input_Cust_Suburb' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_SUBURB_TEXT	) ; ?>	  
	      </div>

<?php
		}
?>		
	    <div class="form-group">
                <?php echo tep_draw_bs_input_field('entry_postcode', $address['entry_postcode'], ENTRY_POST_CODE,    'input_Cust_Postal_Code' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_POST_CODE_TEXT	) ; ?>	  
	    </div>
	    <div class="form-group">
                <?php echo tep_draw_bs_input_field('entry_city', $address['entry_city'], ENTRY_CITY,    'input_Cust_City' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_CITY_TEXT	) ; ?>	  
	    </div>
<?php 
        if (ACCOUNT_STATE == 'true') { 
 		  if (!empty($address['entry_zone_id'])) {
              $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $address['entry_country_id'] . "' and zone_id = '" . $address['entry_zone_id'] . "'");
              if (tep_db_num_rows($zone_query)) {
                  $zone = tep_db_fetch_array($zone_query);
                  $state = $zone['zone_name'];
              } else {
                  $state = $default_zone;
              }
		  } elseif (!empty($address['entry_state']))  {
			    $state = $address['entry_state'];
		  }
?>
	      <div class="form-group">
                <?php echo tep_draw_bs_input_field('entry_state', $state, ENTRY_STATE,    'input_Cust_State' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_STATE_TEXT	) ; ?>	  
	      </div>
<?php		
 
        } 
?>	
	      <div class="form-group">
		    <div class="selectpicker show-tick">
<?php
              if ($address['entry_country_id']){
                  echo tep_draw_bs_pull_down_menu('entry_country', tep_get_countries(), $address['entry_country_id'], ENTRY_COUNTRY, 'input_Cust_Country', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left', null, null, true ) ; 
				  //tep_draw_pull_down_menu('entry_country', tep_get_countries(), $address['entry_country_id']);
              }else{
                  echo tep_draw_bs_pull_down_menu('entry_country', tep_get_countries(), STORE_COUNTRY, ENTRY_COUNTRY, 'input_Cust_Country', 'col-xs-9', ' selectpicker show-tick ',  'control-label col-xs-3',   'left', null, null, true ) ;
              }
              tep_draw_hidden_field('step', '3');
?>                 
           </div>
	      </div>	

	</div>
</div>	

<div class="panel panel-primary">
    <div class="panel-heading"><?php echo CATEGORY_CONTACT; ?></div>   
	<div class="panel-body">
	

	    <div class="form-group">
                <?php echo tep_draw_bs_input_field('customers_telephone', $account['customers_telephone'], ENTRY_TELEPHONE_NUMBER,    'input_Cust_Phone_Number' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_TELEPHONE_NUMBER_TEXT	) ; ?>	  
	    </div>
	    <div class="form-group">
                <?php echo tep_draw_bs_input_field('customers_fax', $account['customers_fax'], ENTRY_FAX_NUMBER,    'input_Cust_Fac_Number' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_FAX_NUMBER_TEXT	) ; ?>	  
	    </div>	
	
	</div>
</div>	

<div class="panel panel-primary">   
    <div class="panel-heading"><?php echo ACCOUNT_EXTRAS; ?></div>
	<div class="panel-body">
	

	    <div class="form-group">
                <?php echo tep_draw_bs_input_field('customers_password', '', ENTRY_ACCOUNT_PASSWORD,    'customers_password' , 'control-label col-xs-3', 'col-xs-9', 'left', ENTRY_ACCOUNT_PASSWORD_TEXT	) ; ?>	  
	    </div>
	    <div class="form-group">		
                <?php echo tep_draw_bs_pull_down_menu('customers_newsletter', $newsletter_array, $account['customers_newsletter'], ENTRY_NEWSLETTER_SUBSCRIBE,'customers_newsletter', 'col-xs-9', 'selectpicker show-tick', 'control-label col-xs-3') ; ?>
	    </div>	
	
	</div>
</div>

<div class="panel panel-primary">   
    <div class="panel-heading"><?php echo TEXT_SELECT_CURRENCY_TITLE; ?></div>
	<div class="panel-body">
	    <div class="form-group">		
                <?php echo tep_draw_bs_pull_down_menu('Currency', $SelectCurrencyBox, $Selected_Currency, ENTRY_CURRENCY,'input_Cust_Currency', 'col-xs-9', 'selectpicker show-tick', 'control-label col-xs-3') ; ?>
	    </div>	
	
	</div>
</div>

<div class="panel panel-primary">   
    <div class="panel-heading"><?php echo TEXT_SELECT_STORES_TITLE; ?></div>
	<div class="panel-body">
	    <div class="form-group">		
                <?php echo tep_draw_bs_pull_down_menu('stores', $SelectStoreBox, $Selected_Store, ENTRY_STORES,'input_Cust_Store', 'col-xs-9', 'selectpicker show-tick', 'control-label col-xs-3') ; ?>
	    </div>	
	
	</div>
</div>

<div class="panel panel-primary">   
    <div class="panel-heading"><?php echo TEXT_CS; ?></div>
	<div class="panel-body">
<?php	
      if (isset($admin['id'])){
            $cs_id=$admin['id'].'-'. $admin['username'];
      }else{
            $cs_id = $_SERVER['REMOTE_USER']; 
      }	
?>	  
	    <div class="form-group">		
                <?php echo tep_draw_bs_input_field('cust_service', $cs_id, ENTRY_ADMIN,'input_Cust_Service', 'control-label col-xs-3', 'col-xs-9') ; ?>				 
	    </div>	
	
	</div>
</div>