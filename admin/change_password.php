<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
  $Id: change_password.php,v 3.0 11/23/2007 kymstion

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/


  require('includes/application_top.php');

// Include the password functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// Include the language definitions
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHANGE_PASSWORD);

//
// GET target -- the GET form has been sent to change a password
// If a password change has been submitted, check the results for errors
  $pass = 0;

  if ($_POST['customer_id'] != '') {
	$customer_id = (int) $_POST['customer_id'];

    if ($_POST['new_password'] == '' && $_POST['repeat_password'] == '') {  // Use generated password
      $pass = 1;
      $new_password = $_POST['auto_password'];
    } elseif ($_POST['new_password'] == $_POST['repeat_password']) {  // Use custom password
      $pass = 1;
      $new_password = $_POST['new_password'];
    } elseif (empty($_POST['new_password'])) {  // Missing password
      $pass = 2;
    } elseif (empty($_POST['repeat_password'])) {  // Missing repeat password
      $pass = 2;
    } elseif ($_POST['new_password'] != $_POST['repeat_password']) {  // Mismatched passwords
      $pass = 3;
    }



// If all is well, make the changes to the database
    if ($pass == 1) { 
      tep_db_query("UPDATE " . TABLE_CUSTOMERS . "	  
                   SET customers_password='" . tep_encrypt_password ($new_password) . "'
                   WHERE customers_id='" . $customer_id . "'
                ");

// Get the customer's information for the success message
      $customer_name_query = tep_db_query("SELECT customers_firstname,
                                                 customers_lastname
                                          FROM " . TABLE_CUSTOMERS . "
                                          WHERE customers_id='" . $customer_id . "'
                                       ");
      $customer_name = tep_db_fetch_array($customer_name_query);
     }
  }
// End POST section

//
// GET target -- a GET form has been sent
// Build a SQL string from the Search or Customer variables
  $search_string = '';
  if (isset ($HTTP_GET_VARS['search']) && strlen ($HTTP_GET_VARS['search']) > 1)  {
    $keywords = tep_db_input (tep_db_prepare_input ($HTTP_GET_VARS['search']));
    $search_string = "where customers_lastname like '%" . $keywords . "%' or customers_firstname like '%" . $keywords . "%' or customers_email_address like '%" . $keywords . "%'";

  } elseif (isset ($HTTP_GET_VARS['customer'])) {
    $customer_id = (int)$HTTP_GET_VARS['customer'];
    $search_string = "WHERE customers_id='" . $customer_id . "'";
  }
// End GET section

//
// Variable fields to insert into the page
// Build an array of customers for the select pulldown
  $customer_data_query = tep_db_query("SELECT customers_id,
                                             customers_firstname,
                                             customers_lastname,
                                             customers_email_address
                                      FROM " . TABLE_CUSTOMERS . "
                                           " . $search_string . "
                                      ORDER BY customers_lastname, customers_firstname
                                  ");
  $customers_array = array();
  while ($customer_data = tep_db_fetch_array($customer_data_query) ) {
    $customers_array[] = array('id' => $customer_data['customers_id'],
                               'text' => $customer_data['customers_firstname'] . ' ' . $customer_data['customers_lastname'] . ' (' . $customer_data['customers_email_address'] . ')'
                              );
  }

// Set the correct message to display for password change or errors
  $message = '';
  switch ($pass) {
    case 1:
      $message = '<b><font color=#009900>';
      $message .= CUSTOMER_PASSWORD . $customer_name['customers_firstname'] . ' ' . $customer_name['customers_lastname'];
      $message .= PASSWORD_UPDATED . '&nbsp;' . $new_password . '<br>' . PASSWORD_UPDATED_REMINDER;
      $message .= '</b></font><br>' . tep_black_line();
      break;
    case 2:
      $message = '<b><font color=#ff0000>'. PLEASE_NEW_PASSWORD . PLEASE_REPEAT . '</b></font>';
      break;
    case 3:
      $message = '<b><font color=#ff0000>'. ERROR_NEW_PASSWORD .  PLEASE_REPEAT . '</b></font>';
      break;
    case 0:
    default:
      $message = '&nbsp;';
      break;
  }

// Set up the search form
  $search_form = tep_draw_input_field ('search');
  $search_form .= tep_draw_hidden_field ('selected_box', 'customers');
  //$search_form .= tep_hide_session_id();

// Generate a random password and add it to the form
  $auto_password = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
  $auto_form = tep_draw_hidden_field ('auto_password', $auto_password) . $auto_password;

  require('includes/template_top.php');
?>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped"> 
              <tbody>
			    <div class="panel panel-primary"> 
				   <div class="panel-body">
			          <?php echo tep_draw_bs_form('search', FILENAME_CHANGE_PASSWORD, '', 'post', 'class="form-horizontal" role="form"', 'id_search_customer') . PHP_EOL;	?>  
                         <div class="form-group">									   
						      <?php echo tep_draw_bs_input_field('countries_name',       null,        SEARCH,       'id_input_countries_name' ,        'col-xs-3', 'col-xs-9', 'left', SEARCH,       '', true ) .
                                    tep_draw_hidden_field ('selected_box', 'customers')
  							  ?>
                         </div>
						 <br />
  					     <p><?php echo TEXT_SEARCH_INSTRUCTION ; ?></p>
						 <br />						   
						  <?php echo tep_draw_bs_button( IMAGE_SEARCH,  'search'  ) ; ?>
                          <br />						  
				      </form>
					</div>
				  </div>
				  
			    <div class="panel panel-primary"> 
				   <div class="panel-body">
			          <?php echo tep_draw_bs_form('password', FILENAME_CHANGE_PASSWORD, 'selected_box=customers', 'POST', 'class="form-horizontal" role="form"', 'id_enter_customer') . PHP_EOL;	?>   
                         <div class="form-group"> 										   
						         <?php echo tep_draw_bs_pull_down_menu('customer_id', $customers_array, null, SELECT_CUSTOMER, 'id_customer_id', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	?>
                         </div>
						 <br />				 
                         <div class="form-group">
						        <div class="col-xs-3">
                                  <label class="control-label">						 
								    <?php echo AUTO_PASSWORD . '  ' . PHP_EOL;	?>
								  </label>
								</div>
								<div class="col-xs-9"><?php echo $auto_form . PHP_EOL;	?></div>
                         </div> 
         				 <div class="clearfix"></div>
						 <br />						 
                         <div class="form-group"> 									   
								<?php echo tep_draw_bs_password_field('new_password', null, NEW_PASSWORD, 'id_input_new_pw' ,'col-xs-3',  'col-xs-9', 'left', NEW_PASSWORD ). PHP_EOL;	?>
                         </div> 	
                         <div class="form-group"> 									   
								<?php echo tep_draw_bs_password_field('repeat_password', null, REPEAT_NEW_PASSWORD, 'id_input_repeat_pw' ,'col-xs-3',  'col-xs-9', 'left', REPEAT_NEW_PASSWORD ). PHP_EOL;	?>
                         </div>		 
						 <div class="clearfix"></div>
 					     <?php echo tep_draw_bs_button( IMAGE_CHANGE_PASSWORD,  'save'  ) ; ?> 
				      </form>
					</div>
				  </div>				  
			  </tbody>
		  </table>
		 </div>
	</table> 			  


<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>