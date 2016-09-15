<?php
/*
  $Id: create_account_process.php,v 1 2003/08/24 23:21:38 frankl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
   
  Admin Create Accont
  (Step-By-Step Manual Order Entry Verion 1.0)
  (Customer Entry through Admin)
*/

  require('includes/application_top.php');
  require('includes/functions/password_funcs_create_account.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);


if (!@$HTTP_POST_VARS['action']) {
   tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'NONSSL'));
 }

  $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
  $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
  $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
  $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
  $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
  $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
  $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
  $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
  //$password = tep_db_prepare_input($HTTP_POST_VARS['password']);
  $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);
  $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
  $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
  $entry_company_tax_id = tep_db_prepare_input($HTTP_POST_VARS['company_tax_id']);  
  $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
  $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
  $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
  $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
  $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
  $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
  $suspended = tep_db_prepare_input($HTTP_POST_VARS['suspended']);  
  
  $customers_group_id = tep_db_prepare_input($_POST['customers_group_id']);  
  
//TotalB2B start
  $customers_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['customers_discount_sign']);
  $customers_discount = tep_db_prepare_input($HTTP_POST_VARS['customers_discount']);			
//TotalB2B end	  
//  $stores_id = tep_db_prepare_input($HTTP_POST_VARS['Stores']);
  
// bof multi stores  
  $stores_text = "1,default";
  if(isset($_POST['stores']))
  {
  	$stores_text = tep_db_prepare_input($_POST['stores']);
  }
  
  $stores_array = explode(",", $stores_text);
  
  $stores_id = $stores_array[0];
  $stores_name = $stores_array[1];
  
  $stores = $stores_array[0];
 
// eof multi stores  

    
  /////////////////      RAMDOMIZING SCRIPT BY PATRIC VEVERKA       \\\\\\\\\\\\\\\\\\

$t1 = date("mdy"); 
srand ((float) microtime() * 10000000); 
$input = array ("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", "X", "x", "Y", "y", "Z", "z"); 
$rand_keys = array_rand ($input, 3); 
$l1 = $input[$rand_keys[0]];
$r1 = rand(0,9); 
$l2 = $input[$rand_keys[1]];
$l3 = $input[$rand_keys[2]]; 
$r2 = rand(0,9); 

$password = $l1.$r1.$l2.$l3.$r2; 

/////////////////    End of Randomizing Script   \\\\\\\\\\\\\\\\\\\

  
  
  $error = false; // reset error flag

  if ($error == true) {
  
    $processed = true;
   require('includes/template_top.php');
?>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->

<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'SSL') . '"'; ?> onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="ui-widget-header ui-corner-all"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (sizeof($navigation->snapshot) > 0) {
?>
      <tr>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td class="ui-state-highlight ui-corner-all"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  //$email_address = tep_db_prepare_input($HTTP_GET_VARS['email_address']);
  $account['entry_country_id'] = STORE_COUNTRY;

  require(DIR_WS_MODULES . 'account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td align="left" class="main"><br>
		  <?php echo tep_draw_button( IMAGE_SAVE,               'disk', '', '', 'submit'  ) ; ?>
		</td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  } else  {
       $sql_data_array = array('customers_firstname' => $firstname,
                           'customers_lastname' => $lastname,
                           'customers_email_address' => $email_address,
                           'customers_telephone' => $telephone,
                           'customers_fax' => $fax,
                           'customers_newsletter' => $newsletter,
                           'customers_password' => tep_encrypt_password_for_create_account($password),
//TotalB2B start
                                'customers_discount' => $customers_discount_sign . $customers_discount,
//TotalB2B end							   
// bof customer suspend								
                           'customers_suspended' => $suspended,
// eof customer suspend							   
// BOF Separate Pricing Per Customer
				           'customers_group_id' => $customers_group_id,
						   'customers_stores_id' => $stores_id);
                           //'customers_password' => $password,
                           //'customers_default_address_id' => 1);

   if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
   if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
   
   if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company_tax_id'] = $company_tax_id;		 

   tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

   $customer_id = tep_db_insert_id();

   $sql_data_array = array('customers_id' => $customer_id,
                           //change line below to suit your version
                           //'address_book_id' => 1,  //pre MS2
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
          $sql_data_array['entry_zone_id'] = STORE_ZONE;
          $sql_data_array['entry_state'] = STORE_COUNTRY;	  
//	     $zone_id = STORE_ZONE ;
	  }

   tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

$address_id = tep_db_insert_id();

tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

   tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . tep_db_input($customer_id) . "', '0', now())");

   $customer_first_name = $firstname;
   //$customer_default_address_id = 1;
$customer_default_address_id = $address_id;
   $customer_country_id = $country;
   $customer_zone_id = $zone_id;
   tep_session_register('customer_id');
   tep_session_register('customer_first_name');
   tep_session_register('customer_default_address_id');
   tep_session_register('customer_country_id');
   tep_session_register('customer_zone_id');

    // build the message content
// bof order confirmation html email
  if (EMAIL_USE_HTML == 'true'){
     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWCUSTOMER . "' and language_id = '" . $languages_id . "' and stores_id='" . $stores_id . "'");	
  } else{
     $text_query = tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWCUSTOMER . "' and language_id = '" . $languages_id . "' and stores_id='" . $stores_id . "'");
  }
  $get_text = tep_db_fetch_array($text_query);
  $text = $get_text["eorder_text_one"];
	
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
  tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 	
    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
  }
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>