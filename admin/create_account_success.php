<?php
/*
  $Id: create_account_success.php,v 1 2003/08/24 23:21:26 frankl Exp $

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

<div class="panel panel-info">
  <div class= "panel-body">
    <div class="well well-success">
	   <?php echo HEADING_TITLE_CREATE_ACCOUNT_SUCCESS; ?>
	</div>
  </div>
</div>
<?php echo tep_draw_bs_button( IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')))  ) ; ?>
<?php 
require(DIR_WS_INCLUDES . 'template_bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>