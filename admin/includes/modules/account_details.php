<?php
/*
  $Id: account_details.php,v 1 2003/08/24 23:22:27 frankl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Admin Create Account
*/

  $newsletter_array = array(array('id' => '1',
                                  'text' => ENTRY_NEWSLETTER_YES),
                            array('id' => '0',
                                  'text' => ENTRY_NEWSLETTER_NO));
								  
// bof customer suspend							  
         $suspended_array = array(array('id' => 'True', 'text' => ENTRY_SUSPENDED_YES),
                           array('id' => 'False', 'text' => ENTRY_SUSPENDED_NO));							  
						   
         $discount_array = array(array('id' => '-', 'text' => 'minus'),
                           array('id' => '+', 'text' => 'plus'));							   
// eof customer suspend								  

function sbs_get_zone_name($country_id, $zone_id) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }
 
 // Returns an array with countries
// TABLES: countries
  function sbs_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if ($countries_id) {
      if ($with_iso_codes) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
      }
    }

    return $countries_array;
  } 
  ////
function sbs_get_country_list($name, $selected = '', $parameters = '') { 
   $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT)); 
   $countries = sbs_get_countries(); 
   $size = sizeof($countries); 
   for ($i=0; $i<$size; $i++) { 
     $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']); 
   } 

//   return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters); 
   return tep_draw_bs_pull_down_menu($name, $countries_array, $selected,     null,        'input_country', null,                       null,                                     null,            null,                     $parameters ,     false,            true) ;
//          tep_draw_bs_pull_down_menu($name, $values,          $default = '', $label = '', $id = 'id_id', $input_class= '',           $select_class=' selectpicker show-tick ', $label_class='', $label_lft_rght = 'left', $parameters = '', $required = false, $search = false)
}

// multi stores
function sbs_get_stores_list($name, $selected = '', $parameters = '') { 
   
   $stores_array = array(array('id' => '', 'text' => TEXT_SELECT_STORES)); 
//   $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
   $result = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");

//	if (tep_db_num_rows($result) > 0){	
      while ($stores_values = tep_db_fetch_array($result)) {
        $stores_array[] = array('id' => $stores_values['stores_id'],
                                'text' => $stores_values['stores_name']);
      }
//    }	  

//   return tep_draw_pull_down_menu($name, $stores_array, $selected, $parameters); 
   return tep_draw_bs_pull_down_menu($name, $stores_array, $selected, $parameters);    
}
// eof multi stores


////
// Alias function to tep_get_countries, which also returns the countries iso codes
 /* function tep_get_countries_with_iso_codes($countries_id) {
    return tep_get_countries($countries_id, true);
  }*/
?>
          <div class="panel panel-primary"> 
		     <div class="panel-heading"><?php echo CATEGORY_PERSONAL; ?></div>		  
			 <div class="panel-body">
<?php
              if (ACCOUNT_GENDER == 'true') {
                 $male = true ;
                 $female = !$male ;
?>
                 <div class="input-group has-feedback">
				    <div class="btn-group btn-inline" data-toggle="buttons">
                        <label class="control-label"><?php echo ENTRY_GENDER; ?></label><br />
                        <label class="btn btn-default <?php echo ( $male ==  true ? ' active ' : '' ) ; ?>">
                            <?php echo tep_draw_radio_field('gender', 'm', $male, 'required aria-required="true"') . ' ' . MALE; ?>
                        </label>
                        <label class="btn btn-default <?php echo ( $female == true ? ' active ' : '' ) ; ?>">
                            <?php echo tep_draw_radio_field('gender', 'f', $female, 'required aria-required="true"') . ' ' . FEMALE; ?>
                        </label>
                    </div>
                 </div>
		 
				 <br />
			
<?php
			  }
?>
              <div class="form-group has-feedback">
                <label for="inputFirstName" class="control-label col-xs-2"><?php echo ENTRY_FIRST_NAME; ?></label>
                <div class="col-xs-10">
                     <?php echo tep_draw_input_field('firstname', $account['customers_firstname'], 'required aria-required="true" id="inputFirstName" placeholder="' . ENTRY_FIRST_NAME . '"'); ?>                  
                </div>
              </div>
              <div class="form-group has-feedback">
                <label for="inputLastName" class="control-label col-xs-2"><?php echo ENTRY_LAST_NAME; ?></label>
                <div class="col-xs-10">
                    <?php echo tep_draw_input_field('lastname', $account['customers_lastname'], 'required aria-required="true" id="inputLastName" placeholder="' . ENTRY_LAST_NAME . '"'); ?>
                </div>
              </div>
<?php	  
              if (ACCOUNT_DOB == 'true') {
?>			   
                <div class="form-group  has-feedback"> 
                    <label for="customers_dob" class="col-xs-2 control-label"><?php echo ENTRY_DATE_OF_BIRTH ; ?></label>  										 
                    <div class="col-xs-10"><?php echo tep_draw_bs_input_date('dob',                                               // name 'dob', tep_date_short($account['customers_dob']
	                                                                      tep_date_short($account['customers_dob']),           // value
					                                                      'required aria-required="true" id="customers_dob"',            // parameters
					                                                      null,                                                // type
					                                                      null,                                              // reinsert value
					                                                      ENTRY_DATE_OF_BIRTH                             // placeholder
					                                                     ) ; ?>
                    </div>
                </div>
				<br />
<?php
			  }
?>			
              <div class="form-group has-feedback">
                <label for="inputEmail_Adress" class="control-label col-xs-2"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
                <div class="col-xs-10">
                    <?php echo tep_draw_input_field('email_address', $account['customers_email_address'], 'required aria-required="true" id="inputEmail_Adress" placeholder="' . ENTRY_EMAIL_ADDRESS . '"'); ?> 
                </div>
              </div>			
  				
			 </div>  <!-- end panel body category personal -->
		  </div>     <!-- end panel category personal -->
		  
<?php
          if (ACCOUNT_COMPANY == 'true') {
?>	 
               <div class="panel panel-primary"> 
	   	            <div class="panel-heading"><?php echo CATEGORY_COMPANY; ?></div>		  
			        <div class="panel-body">	
 
                      <div class="form-group has-feedback">
                        <label for="inputCompany_Name" class="control-label col-xs-2"><?php echo ENTRY_COMPANY; ?></label>
                        <div class="col-xs-10">
                             <?php echo tep_draw_input_field('company', $account['entry_company'], 'id="inputCompany_Name" placeholder="' . ENTRY_COMPANY . '"'); ?>
                        </div>
                      </div>

                      <div class="form-group has-feedback">
                        <label for="inputCompany_Number" class="control-label col-xs-2"><?php echo ENTRY_COMPANY_TAX_ID; ?></label>
                        <div class="col-xs-10">
                             <?php echo tep_draw_input_field('company_tax_id', $account[entry_company_tax_id], 'id="inputCompany_Number" placeholder="' . ENTRY_COMPANY_TAX_ID . '"'); ?>
                        </div>
                      </div>						   
			        </div>  <!-- end panel body category company --> 
		       </div>     <!-- end panel category company -->  					   
<?php
          }
?>			  
          <div class="panel panel-primary"> 
		     <div class="panel-heading"><?php echo CATEGORY_ADDRESS; ?></div>		  
			 <div class="panel-body">
			 
                 <div class="form-group has-feedback">
                     <label for="inputstreet_adress" class="control-label col-xs-2"><?php echo ENTRY_STREET_ADDRESS; ?></label>
                     <div class="col-xs-10">
                           <?php echo tep_draw_input_field('street_address', $account['entry_street_address'], 'required aria-required="true" id="inputstreet_adress" placeholder="' . ENTRY_STREET_ADDRESS . '"'); ?>
                     </div>
                 </div>			 

<?php
    if (ACCOUNT_SUBURB == 'true') {
?>			 
                 <div class="form-group has-feedback">
                     <label for="inputSuburb_Name" class="control-label col-xs-2"><?php echo ENTRY_SUBURB; ?></label>
                     <div class="col-xs-10">
                           <?php echo tep_draw_input_field('suburb', $account['entry_suburb'], 'required aria-required="true" id="inputSuburb_Name" placeholder="' . ENTRY_SUBURB . '"'); ?>  
                     </div>
                 </div>	
<?php 
   }				 
?>  

                 <div class="form-group has-feedback">
                     <label for="inputPostal_Code" class="control-label col-xs-2"><?php echo ENTRY_POST_CODE; ?></label>
                     <div class="col-xs-10">
                           <?php echo tep_draw_input_field('postcode', $account['entry_postcode'], 'required aria-required="true" id="inputPostal_Code" placeholder="' . ENTRY_POST_CODE . '"'); ?> 
                     </div>
                 </div>	

                 <div class="form-group has-feedback">
                     <label for="inputCity_Name" class="control-label col-xs-2"><?php echo ENTRY_CITY; ?></label>
                     <div class="col-xs-10">
                           <?php echo tep_draw_input_field('city', $account['entry_city'], 'required aria-required="true" id="inputCity_Name" placeholder="' . ENTRY_CITY . '"'); ?> 
                     </div>
                 </div>	
				 
<?php				 
                 $entry_state = tep_get_zone_name($account['entry_country_id'], $account['entry_zone_id'], $account['entry_state']);
                 if ($entry_state_has_zones == true) {
                   $zones_array = array();
                   $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
                   while ($zones_values = tep_db_fetch_array($zones_query)) {
                     $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
                   }
?>				   
                    <div class="form-group has-feedback">
                       <label for="inputZone_Name" class="control-label col-xs-2"><?php echo ENTRY_STATE; ?></label>
                       <div class="col-xs-10">
                           
						   <?php echo tep_draw_bs_pull_down_menu('state', $zones_array, $account['entry_state'], ENTRY_STATE, 'inputZone_Name', 'col-xs-10', ' selectpicker show-tick ', 'col-xs-2', 'left', null, null, true) ; ?>
                       </div>
                    </div>				   
<?php					
//                   echo tep_draw_pull_down_menu('state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
                 } else {
?>
                    <div class="form-group has-feedback">
                       <label for="inputZone_Name" class="control-label col-xs-2"><?php echo ENTRY_STATE; ?></label>
                       <div class="col-xs-10">
                           <?php echo tep_draw_input_field('state', sbs_get_zone_name($account['entry_country_id'], $account['entry_zone_id'], $account['entry_state']), 'id="inputZone_Name" placeholder="' . ENTRY_STATE . '"'); ?>
                       </div>
                    </div>	
<?php					   
                } 									   
?>			
               <div class="form-group"> 
                    <?php echo tep_draw_bs_pull_down_menu('country', tep_get_countries(),  $account['entry_country_id'], ENTRY_COUNTRY, 'inputCountry_Name', 'col-xs-10', ' selectpicker show-tick ', 'col-xs-2', 'left', '', false, true)  ; ?> 
               </div> 
 
			 </div>  <!-- end panel body category adress etc --> 
		  </div>     <!-- end panel category adress etc --> 

          <div class="panel panel-primary"> 
		     <div class="panel-heading"><?php echo CATEGORY_CONTACT; ?></div>		  
			 <div class="panel-body">
			 
                 <div class="form-group has-feedback">
                     <label for="inputTelephoneNumber" class="control-label col-xs-2"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
                     <div class="col-xs-10">
                           <?php echo tep_draw_input_field('telephone',  $account['customers_telephone'], 'required aria-required="true" id="inputTelephoneNumber" placeholder="' . ENTRY_TELEPHONE_NUMBER . '"'); ?>
                     </div>
                 </div>				 
				 
                 <div class="form-group has-feedback">
                     <label for="inputFaxNumber" class="control-label col-xs-2"><?php echo ENTRY_FAX_NUMBER; ?></label>
                     <div class="col-xs-10">
                           <?php echo tep_draw_input_field('fax', $account['customers_fax'], 'id="inputFaxNumber" placeholder="' . ENTRY_FAX_NUMBER . '"'); ?>
                     </div>
                 </div>						 
			 		  
			 </div>  <!-- end panel body category contact --> 
		  </div>     <!-- end panel category contact -->

         <div class="panel panel-primary"> 
		     <div class="panel-heading"><?php echo CATEGORY_OPTIONS; ?></div>		  
			 <div class="panel-body">
			  
                 <div class="form-group has-feedback">
                     <label for="inputNewsletter" class="control-label col-xs-2"><?php echo ENTRY_NEWSLETTER; ?></label>
                     <div class="col-xs-10">
<!--                           <?php echo tep_draw_pull_down_menu('newsletter', $newsletter_array, (($account['customers_newsletter'] == '1') ? '1' : '0'), 'id="inputNewsletter"'); ?> -->
                           <?php echo tep_draw_bs_pull_down_menu('newsletter', $newsletter_array, (($account['customers_newsletter'] == '1') ? '1' : '0'), null, 'inputNewsletter'); ?> 						   
                     </div>
                 </div>				 
				 
                 <div class="form-group has-feedback">
                     <label for="inputActiveCust" class="control-label col-xs-2"><?php echo ENTRY_ACCOUNT_STATUS; ?></label>
                     <div class="col-xs-10">
<!--                           <?php echo tep_draw_pull_down_menu('suspended', $suspended_array, (($account['customers_suspended'] == 'True') ? 'True' : 'False'), 'id="inputActiveCust"'); ?> -->
                          <?php echo tep_draw_bs_pull_down_menu('suspended', $suspended_array, (($account['customers_suspended'] == 'True') ? 'True' : 'False'), null, 'inputActiveCust'); ?>						   
                     </div>
                 </div>	 
     
 
                <div class="form-group">
                     <label for="inputCust_Discount" class="control-label col-xs-2"><?php echo ENTRY_CUSTOMERS_DISCOUNT; ?></label>
                     <div class="col-xs-10">					 
<?php 
                              if ( $account['customers_discount'] > 0 ) {
	                             $discount_sign = '+' ;
	                             $discount_amount = $account['customers_discount'] ;
                              } else {
	                             $discount_sign   = '-' ;	
                                 $discount_amount = substr($account['customers_discount'],1,strlen($account['customers_discount']))	 ;
                              }
?>
                              <div class="col-xs-2">
<?php							  
//						          echo tep_draw_pull_down_menu('customers_discount_sign', $discount_array, $discount_sign, 'id="inputCust_Discount_Sign"') ;
						          echo tep_draw_bs_pull_down_menu('customers_discount_sign', $discount_array, $discount_sign, null, 'inputCust_Discount_Sign') ;								  
?>
                              </div>	
                              <div class="col-xs-10">							  
<?php							  
							      echo tep_draw_input_field('customers_discount', $discount_amount, 'id="inputCust_Discount" placeholder="' . ENTRY_CUSTOMERS_DISCOUNT . '"'); 							  
?>
                              </div>
                     </div>
                 </div>
 
				 
                 <div class="form-group ">
                     <label for="inputCust_Group" class="control-label col-xs-2"><?php echo ENTRY_CUSTOMERS_GROUP_NAME; ?></label>
                     <div class="col-xs-10">
<?php 
                         $index = 0;
	                     $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
                         while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
                             $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
                             ++$index;
                         }
//                         echo tep_draw_pull_down_menu('customers_group_id', $existing_customers_array, $account['customers_group_id'], 'id="inputCust_Group"'); 
                         echo tep_draw_bs_pull_down_menu('customers_group_id', $existing_customers_array, $account['customers_group_id'], null, 'inputCust_Group'); 						 
?>
                     </div>
                 </div>					 
				 
                 <div class="form-group ">
                     <label for="inputCust_Store" class="control-label col-xs-2"><?php echo ENTRY_CUSTOMERS_STORES_NAME; ?></label>
                     <div class="col-xs-10">
<?php 
                         $index = 0;
	                     $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
                         while ($stores_customers =  tep_db_fetch_array($stores_customers_query)) {
                              $stores_customers_array[] = array("id" => $stores_customers['stores_id'], "text" => "&#160;".$stores_customers['stores_name']."&#160;");
                              ++$index;
                         }
//                         echo tep_draw_pull_down_menu('customers_stores_id', $stores_customers_array, $cInfo->customers_stores_id, 'id="inputCust_Store"'); 
                         echo tep_draw_bs_pull_down_menu('customers_stores_id', $stores_customers_array, $cInfo->customers_stores_id, null, 'inputCust_Store'); 						 
?>
                     </div>
                 </div>					 
				 				 
			 		  
			 </div>  <!-- end panel body category opties --> 
		  </div>     <!-- end panel category opties -->