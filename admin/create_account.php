<?php
/*
  $Id: create_account.php,v 1 2003/08/24 23:21:27 frankl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
   
  Admin Create Accont
  (Step-By-Step Manual Order Entry Verion 1.0)
  (Customer Entry through Admin)
*/

  require('includes/application_top.php');
   require('includes/template_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <div class="page-header">
        <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE_CREATE_ACCOUNT; ?></h1>
<?php 
       if ( $multi_stores_array == 1 ) {
?>
        <h2 class="col-xs-12 col-md-6"><?php echo TEXT_MULTI_STORE_NAME . $multi_stores  ; ?></h2>		
<?php
       }		
?>	   
		<div class="clearfix"></div>
      </div><!-- page-header-->
  
<?php 
     echo tep_draw_form('account_edit', FILENAME_CREATE_ACCOUNT_PROCESS, tep_get_all_get_params(array('action')) , 'post', 'class="form-inline" role="form"') . tep_draw_hidden_field('action', 'process'); 
	 
 //$email_address = tep_db_prepare_input($HTTP_GET_VARS['email_address']);
        $account['entry_country_id'] = STORE_COUNTRY;
        $account['entry_zone_id']    = STORE_ZONE;
        $account['stores']           = SYS_STORES_ID ; 

        require(DIR_WS_MODULES . 'account_details.php');
?>

        <div class="col-xs-12 col-md-7">
<?php 
              echo tep_draw_bs_button(IMAGE_SAVE, 'ok' ) ; 
?>
		 </div>    
    </form>
  </table>
<!-- body_eof //-->
<?php 
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>