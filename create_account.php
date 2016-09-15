<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $process = false;
  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') {
      if (isset($HTTP_POST_VARS['gender'])) {
        $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
//    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
// BOF Separate Pricing Per Customer, added: field for tax id number
    if (ACCOUNT_COMPANY == 'true') {
    $company = tep_db_prepare_input($_POST['company']);
    $company_tax_id = tep_db_prepare_input($_POST['company_tax_id']);
    }
// EOF Separate Pricing Per Customer, added: field for tax id number
    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
      if (isset($HTTP_POST_VARS['zone_id'])) {
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
    if (isset($HTTP_POST_VARS['newsletter'])) {
      $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);

    $error = false;
//-----   BEGINNING OF ADDITION: MATC   -----// 
	if (tep_db_prepare_input($HTTP_POST_VARS['TermsAgree']) != 'true' and MATC_AT_REGISTER != 'false') {
        $error = true;
        $messageStack->add('create_account', MATC_ERROR);
    }
//-----   END OF ADDITION: MATC   -----//
    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
// 2.3.3.3      if ((is_numeric(tep_date_raw($dob)) == false) || (@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false)) {
      if ((strlen($dob) < ENTRY_DOB_MIN_LENGTH) || (!empty($dob) && (!is_numeric(tep_date_raw($dob)) || !@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))))) {  // 2.3.3.3
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
      $check_email = tep_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      }
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    } else { // eric
          $sql_data_array['entry_zone_id'] = STORE_ZONE;
          $sql_data_array['entry_state'] = STORE_COUNTRY;	  
//	     $zone_id = STORE_ZONE ;
	}
	
    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => tep_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

// BOF Separate Pricing Per Customer
   // if you would like to have an alert in the admin section when either a company name has been entered in
   // the appropriate field or a tax id number, or both then uncomment the next line and comment the default
   // setting: only alert when a tax_id number has been given
      if ( (ACCOUNT_COMPANY == 'true' && tep_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) ) {
//	  if ( ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)  ) {
         if ( SYS_REQ_APPROVAL == 'true' ) {
		      $sql_data_array['customers_group_ra'] = '1';
	     } else {
		      $sql_data_array['customers_group_ra'] = '0';		 
		 }
// entry_company_tax_id moved from table address_book to table customers in version 4.2.0
      $sql_data_array['entry_company_tax_id'] = $company_tax_id; 
    }
// EOF Separate Pricing Per Customer
// bof multi stores
    $sql_data_array['customers_group_id'] = _SYS_STORE_CUSTOMER_GROUP ;
    $sql_data_array['customers_stores_id'] = SYS_STORES_ID ;	
// eof multi stores	  	  

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
       } else { // eric
        //  $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = '';	  	  
	    $sql_data_array['entry_zone_id'] = STORE_ZONE;
	  }


      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }
// BOF Separate Pricing Per Customer
// register SPPC session variables for the new customer
// if there is code above that puts new customers directly into another customer group (default is retail)
// then the below code need not be changed, it uses the newly inserted customer group
      $check_customer_group_info = tep_db_query("select c.customers_group_id, cg.customers_group_show_tax, cg.customers_group_tax_exempt, cg.group_specific_taxes_exempt from " . TABLE_CUSTOMERS . " c left join " . TABLE_CUSTOMERS_GROUPS . " cg using(customers_group_id) where c.customers_id = '" . $customer_id . "'");
      $customer_group_info = tep_db_fetch_array($check_customer_group_info);
      $sppc_customer_group_id = $customer_group_info['customers_group_id'];
      $sppc_customer_group_show_tax = (int)$customer_group_info['customers_group_show_tax'];
      $sppc_customer_group_tax_exempt = (int)$customer_group_info['customers_group_tax_exempt'];
      $sppc_customer_specific_taxes_exempt = '';
      if (tep_not_null($customer_group_info['group_specific_taxes_exempt'])) {
        $sppc_customer_specific_taxes_exempt = $customer_group_info['group_specific_taxes_exempt'];
      }
// EOF Separate Pricing Per Customer

      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');
// BOF Separate Pricing Per Customer
      tep_session_register('sppc_customer_group_id');
      tep_session_register('sppc_customer_group_show_tax');
      tep_session_register('sppc_customer_group_tax_exempt');
      tep_session_register('sppc_customer_specific_taxes_exempt');
// EOF Separate Pricing Per Customer	  

// reset session token
      $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());

// restore cart contents
      $cart->restore_contents();
// restore wishlist to sesssion
      $wishList->restore_wishlist();	  

// build the message content

// bof order confirmation html email
  if (EMAIL_USE_HTML == 'true'){
     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWCUSTOMER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
  } else{
     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWCUSTOMER . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
  }
  $get_text = tep_db_fetch_array($text_query);
  $text = $get_text["eorder_text_one"];
  $subject = $get_text["eorder_title"];
	
  $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME, $text);

  if (ACCOUNT_GENDER == 'true') {
    if ($gender == 'm') {
       $text = preg_replace('/<-SYS_CREATE_ACC_GENDER->/',             EMAIL_GREET_MR2,               $text);
    } else {
       $text = preg_replace('/<-SYS_CREATE_ACC_GENDER->/',             EMAIL_GREET_MS2,               $text);
    }
  }

  $text = preg_replace('/<-SYS_CREATE_ACC_ID->/',                    $customer_id,                    $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_FIRSTNAME->/',             $firstname,                      $text);  
  $text = preg_replace('/<-SYS_CREATE_ACC_LASTNAME->/',              $lastname,                       $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_STREET->/',                $street_address,                 $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_POSTCODE->/',              $postcode,                       $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_CITY->/',                  $city,                           $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_COUNTRY->/',               tep_get_country_name($country),  $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_SUBURB->/',                $suburb,                         $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_STATE->/',                 $state,                          $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_BIRTHDATE->/',             $dob,                            $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_COMPANY->/',               $company,                        $text);

  if ( $newsletter == 0 ) {
    $text = preg_replace('/<-SYS_CREATE_ACC_NEWSLETTER->/',       ENTRY_NEWSLETTER_NO, $text);
  } else {
    $text = preg_replace('/<-SYS_CREATE_ACC_NEWSLETTER->/',       ENTRY_NEWSLETTER_YES, $text);
  }  

  $text = preg_replace('/<-SYS_CREATE_ACC_EMAILADDRESS->/',          $email_address,  $text);
  $text = preg_replace('/<-SYS_CREATE_ACC_TELEPHONE->/',             $telephone,      $text);  
  $text = preg_replace('/<-SYS_CREATE_ACC_FAX->/',                   $fax,            $text);  
  
  // picture mode
  $email_text = tep_add_base_ref($text);  

  tep_mail($name, $email_address, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 

// send emails to admin
  if ( EMAIL_ADMIN_CREATE_CUSTOMER != '') {
	  if (EMAIL_USE_HTML == 'true'){
		 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	  } else{
		 $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN . "' and language_id = '" . $languages_id . "' and stores_id = '" . SYS_STORES_ID . "'");	
	  }
	  $get_text = tep_db_fetch_array($text_query);
	  $text = $get_text["eorder_text_one"];
	  $subject = $get_text["eorder_title"];
		
      $text = preg_replace('/<-SYS_STORE_NAME->/',             STORE_NAME, $text);

      if (ACCOUNT_GENDER == 'true') {
        if ($gender == 'm') {
          $text = preg_replace('/<-SYS_CREATE_ACC_GENDER->/',             EMAIL_GREET_MR2,               $text);
        } else {
          $text = preg_replace('/<-SYS_CREATE_ACC_GENDER->/',             EMAIL_GREET_MS2,               $text);
        }
      }

      $text = preg_replace('/<-SYS_CREATE_ACC_ID->/',                    $customer_id,                    $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_FIRSTNAME->/',             $firstname,                      $text);  
      $text = preg_replace('/<-SYS_CREATE_ACC_LASTNAME->/',              $lastname,                       $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_STREET->/',                $street_address,                 $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_POSTCODE->/',              $postcode,                       $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_CITY->/',                  $city,                           $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_COUNTRY->/',               tep_get_country_name($country),  $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_SUBURB->/',                $suburb,                         $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_STATE->/',                 $state,                          $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_BIRTHDATE->/',             $dob,                            $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_COMPANY->/',               $company,                        $text);

      if ( $newsletter == 0 ) {
        $text = preg_replace('/<-SYS_CREATE_ACC_NEWSLETTER->/',       ENTRY_NEWSLETTER_NO, $text);
      } else {
        $text = preg_replace('/<-SYS_CREATE_ACC_NEWSLETTER->/',       ENTRY_NEWSLETTER_YES, $text);
      }  

      $text = preg_replace('/<-SYS_CREATE_ACC_EMAILADDRESS->/',          $email_address,  $text);
      $text = preg_replace('/<-SYS_CREATE_ACC_TELEPHONE->/',             $telephone,      $text);  
      $text = preg_replace('/<-SYS_CREATE_ACC_FAX->/',                   $fax,            $text);  
  
      // picture mode
      $email_text = tep_add_base_ref($text); 
      //tep_mail('', EMAIL_ADMIN_CREATE_CUSTOMER, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      tep_mail('', EMAIL_ADMIN_CREATE_CUSTOMER, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);	  
  }
// eof order confirmation html email
// eric
//      $name = $firstname . ' ' . $lastname;
//
//      if (ACCOUNT_GENDER == 'true') {
//         if ($gender == 'm') {
//           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
//         } else {
//           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
//         }
//      } else {
//        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
//      }

//      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
//      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
  require('includes/form_check.js.php');
?>
<script type="text/javascript" src="ext/jquery/pstrength/jquery.pstrength-min.1.2.js"></script> <!-- password strength -->

<div class="page-header">
  <h1><?php echo HEADING_TITLE  ; ?></h1>
</div>

<?php
  if ($messageStack->size('create_account') > 0) {
    echo $messageStack->output('create_account');
  }
?>

<!-- <p><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></p> -->

<!--  <p><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?><span class="inputRequirement pull-right text-right"><?php echo FORM_REQUIRED_INFORMATION; ?></span></p>-->
  <div class="alert alert-warning">
     <?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?><span class="inputRequirement pull-right text-right"><?php echo FORM_REQUIRED_INFORMATION; ?></span>
  </div>


<?php echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'class="form-horizontal" role="form" onsubmit="return check_form(create_account);"', true) . tep_draw_hidden_field('action', 'process'); ?>

<div class="contentContainer">


  <h2><?php echo CATEGORY_PERSONAL; ?></h2>
  <div class="contentText">


<?php
  if (ACCOUNT_GENDER == 'true') {

   	       echo  '<label class="col-xs-3 control-label">' . ENTRY_GENDER . '</label>' . PHP_EOL ;
 	       echo  ' <div class="col-xs-9">' . PHP_EOL ; 		   
 	       echo  '   <div class="form-group has-feedback">' . PHP_EOL ; 	 
           echo   			   tep_bs_radio_field('gender', 'm', MALE,     'input_Cust_Gender_Male',    null, 'radio radio-success radio-inline', '', 'required aria-required="true"', 'right') . PHP_EOL  ;	
           echo   			   tep_bs_radio_field('gender', 'f', FEMALE,   'input_Cust_Gender_Female',  null, 'radio radio-success radio-inline', '', 'required aria-required="true"', 'right') . PHP_EOL  ;	 	 
           echo  '   </div>'. PHP_EOL  ;
           echo  '   ' . FORM_REQUIRED_INPUT . PHP_EOL  ;	
           echo  ' </div>'. PHP_EOL  ;	
	       echo  '<br />'. PHP_EOL  ;
/*		   
    <div class="form-group has-feedback">
      <label class="control-label col-xs-3"><?php echo ENTRY_GENDER; ?></label>
      <div class="col-xs-9">
        <label class="radio-inline">
          <?php echo tep_bs_radio_field('gender','m', MALE, 'input_gender_male', false, '', 'control-label col-xs-3', 'required aria-required="true"', 'left')
		  //tep_draw_radio_field('gender', 'm', NULL, 'required aria-required="true"') . ' ' . MALE; ?>
        </label>
        <label class="radio-inline">
          <?php echo tep_draw_radio_field('gender', 'f') . ' ' . FEMALE; ?>
        </label>
        <?php echo FORM_REQUIRED_INPUT; ?>
        <?php if (tep_not_null(ENTRY_GENDER_TEXT)) echo '<span class="help-block"></span>'; ?>
      </div>
    </div>
<?php
*/
  }
?>
    <div class="form-group has-feedback">
      <label for="inputFirstName" class="control-label col-xs-3"><?php echo ENTRY_FIRST_NAME; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('firstname', NULL, 'required aria-required="true" id="inputFirstName" placeholder="' . ENTRY_FIRST_NAME . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_FIRST_NAME_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
    <div class="form-group has-feedback">
      <label for="inputLastName" class="control-label col-xs-3"><?php echo ENTRY_LAST_NAME; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('lastname', NULL, 'required aria-required="true" id="inputLastName" placeholder="' . ENTRY_LAST_NAME . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_LAST_NAME_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
    <div class="form-group has-feedback">
      <label for="dob" class="control-label col-xs-3"><?php echo ENTRY_DATE_OF_BIRTH; ?></label>
      <div class="col-xs-9">
        <?php
        //echo tep_draw_input_field('dob', '', 'required aria-required="true" id="dob" placeholder="' . ENTRY_DATE_OF_BIRTH . '"');
        echo tep_draw_input_date('dob',                                               // name
	                              '',                                                 // value
									 'required aria-required="true" id="dob"',        // parameters
									 null,                                            // type
									 'true',                                          // reinsert value
									 ENTRY_DATE_OF_BIRTH                              // placeholder
									 ); 
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
<?php
  }
?>
    <div class="form-group has-feedback">
      <label for="inputEmail" class="control-label col-xs-3"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('email_address', NULL, 'required aria-required="true" id="inputEmail" placeholder="' . ENTRY_EMAIL_ADDRESS . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
  </div>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>

  <h2><?php echo CATEGORY_COMPANY; ?></h2>

  <div class="contentText">
    <div class="form-group">
      <label for="inputCompany" class="control-label col-xs-3"><?php echo ENTRY_COMPANY; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('company', NULL, 'id="inputCompany" placeholder="' . ENTRY_COMPANY . '"');
        if (tep_not_null(ENTRY_COMPANY_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
    <div class="form-group">
      <label for="inputCompanyTaxId" class="control-label col-xs-3"><?php echo ENTRY_COMPANY_TAX_ID; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('company_tax_id', NULL, 'id="inputCompanyTaxId" placeholder="' . ENTRY_COMPANY_TAX_ID . '"');
        if (tep_not_null(ENTRY_COMPANY_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
  </div>

<?php
  }
?>

  <h2><?php echo CATEGORY_ADDRESS; ?></h2>

  <div class="contentText">
    <div class="form-group has-feedback">
      <label for="inputStreet" class="control-label col-xs-3"><?php echo ENTRY_STREET_ADDRESS; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('street_address', NULL, 'required aria-required="true" id="inputStreet" placeholder="' . ENTRY_STREET_ADDRESS . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_STREET_ADDRESS_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>

<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
    <div class="form-group">
    <label for="inputSuburb" class="control-label col-xs-3"><?php echo ENTRY_SUBURB; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('suburb', NULL, 'id="inputSuburb" placeholder="' . ENTRY_SUBURB . '"');
        if (tep_not_null(ENTRY_SUBURB_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
<?php
  }
?>
    <div class="form-group has-feedback">
      <label for="inputCity" class="control-label col-xs-3"><?php echo ENTRY_CITY; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('city', NULL, 'required aria-required="true" id="inputCity" placeholder="' . ENTRY_CITY. '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_CITY_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
    <div class="form-group has-feedback">
      <label for="inputZip" class="control-label col-xs-3"><?php echo ENTRY_POST_CODE; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('postcode', NULL, 'required aria-required="true" id="inputZip" placeholder="' . ENTRY_POST_CODE . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_POST_CODE_TEXT)) echo '<span class="help-block"></span>';
        ?>
     </div>
    </div>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
    <div class="form-group has-feedback">
      <label for="inputState" class="control-label col-xs-3"><?php echo ENTRY_STATE; ?></label>
      <div class="col-xs-9">
        <?php
        if ($process == true) {
          if ($entry_state_has_zones == true) {
            $zones_array = array();
            $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
            while ($zones_values = tep_db_fetch_array($zones_query)) {
              $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
            }
            echo tep_draw_pull_down_menu('state', $zones_array, 0, 'id="inputState"');
            echo FORM_REQUIRED_INPUT;
          } else {
            echo tep_draw_input_field('state', NULL, 'id="inputState" placeholder="' . ENTRY_STATE . '"');
            echo FORM_REQUIRED_INPUT;
          }
        } else {
          echo tep_draw_input_field('state', NULL, 'id="inputState" placeholder="' . ENTRY_STATE    . '"');
          echo FORM_REQUIRED_INPUT;
        }
        if (tep_not_null(ENTRY_STATE_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
<?php
  }
?>
    <div class="form-group has-feedback">
      <label for="inputCountry" class="control-label col-xs-3"><?php echo ENTRY_COUNTRY; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_get_country_list('country', STORE_COUNTRY, 'required aria-required="true"', 'inputCountry');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_COUNTRY_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
  </div>

  <h2><?php echo CATEGORY_CONTACT; ?></h2>

  <div class="contentText">
    <div class="form-group has-feedback">
      <label for="inputTelephone" class="control-label col-xs-3"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('telephone', NULL, 'required aria-required="true" id="inputTelephone" placeholder="' . ENTRY_TELEPHONE_NUMBER . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
    <div class="form-group">
      <label for="inputFax" class="control-label col-xs-3"><?php echo ENTRY_FAX_NUMBER; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('fax', '', 'id="inputFax" placeholder="' . ENTRY_FAX_NUMBER . '"');
        if (tep_not_null(ENTRY_FAX_NUMBER_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>

    <div class="form-group">
      <label for="newsletter" class="control-label col-xs-3"><?php echo ENTRY_NEWSLETTER; ?></label>
      <div class="col-xs-9">
        <?php echo tep_bs_checkbox_field('newsletter',                                      											 '1', 
																				 '', 
																				 'inputNewsletter', 
																				 null, 
																				 'checkbox checkbox-success', 
																				 '', 
																				 '', 
																				 'right' )	;
		//echo tep_draw_checkbox_field('newsletter', '1', NULL, 'id="inputNewsletter"');;
        if (tep_not_null(ENTRY_NEWSLETTER_TEXT)) echo ENTRY_NEWSLETTER_TEXT;
        ?>
      </div>
    </div>	  
  </div>

  <h2><?php echo CATEGORY_PASSWORD; ?></h2>

  <div class="contentText">
    <div class="form-group has-feedback">
      <label for="inputPassword" class="control-label col-xs-3"><?php echo ENTRY_PASSWORD; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_password_field('password', NULL, 'required aria-required="true" id="inputPassword" placeholder="' . ENTRY_PASSWORD . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_PASSWORD_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
    <div class="form-group has-feedback">
      <label for="inputConfirmation" class="control-label col-xs-3"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_password_field('confirmation', NULL, 'required aria-required="true" id="inputConfirmation" placeholder="' . ENTRY_PASSWORD_CONFIRMATION . '"');
        echo FORM_REQUIRED_INPUT;
        if (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT)) echo '<span class="help-block"></span>';
        ?>
      </div>
    </div>
  </div>

<!--   BEGINNING OF ADDITION: MATC   -->
<?php
  if(MATC_AT_REGISTER != 'false'){
	require(DIR_WS_MODULES . 'matc.php');
  }
?>
<!--   END OF ADDITION: MATC   -->

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_SAVE, 'save', null, 'primary'); ?></span>
  </div>
</div>

</form>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
