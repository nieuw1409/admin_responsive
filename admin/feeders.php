<?php
/*
  $Id: feeders.php,v 1.6 2003/06/30 13:13:49 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  /********************** BEGIN VERSION CHECKER *********************/
  if (file_exists(DIR_WS_FUNCTIONS . 'version_checker.php'))
  {
     require(DIR_WS_LANGUAGES . $language . '/version_checker.php');
     require(DIR_WS_FUNCTIONS . 'version_checker.php');
     $contribPath = 'http://addons.oscommerce.com/info/4513';
     $currentVersion = 'GoogleBase V 3.4';
     $contribName = 'GoogleBase V';
     $versionStatus = '';
  }
  /********************** END VERSION CHECKER *********************/

  $checkingVersion = false;
  $action = (isset($HTTP_POST_VARS['action']) ? $HTTP_POST_VARS['action'] : '');

  if (tep_not_null($action))
  {
     /********************** CHECK THE VERSION ***********************/
     if ($action == 'getversion')
     {
         $checkingVersion = true;
         if (isset($HTTP_POST_VARS['version_check']) && $HTTP_POST_VARS['version_check'] == 'on')
             $versionStatus = AnnounceVersion($contribPath, $currentVersion, $contribName);
     }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo $currentVersion; ?></h1> 		
            <div class="clearfix"></div>
          </div><!-- page-header-->

          <div class="row-fluid">
		     <div class="col-xs-12 col-md-6">
<?php			 
	            echo   tep_draw_bs_button(TEXT_FEEDERS_GOOGLE_NOFTP, 'disk', tep_catalog_href_link('usu5_sitemaps/feeders_google.php?noftp=1&return=' . basename($PHP_SELF) . '&admin_id=' . $HTTP_GET_VARS['osCAdminID']), null, null, 'btn-primary ') . PHP_EOL;			
	            echo   tep_draw_bs_button(TEXT_FEEDERS_GOOGLE,       'link', tep_catalog_href_link('usu5_sitemaps/feeders_google.php?return=' . basename($PHP_SELF) . '&admin_id=' . $HTTP_GET_VARS['osCAdminID']),         null, null, 'btn-info') . PHP_EOL;			
?>				
             </div>				
		     <div class="col-xs-12 col-md-6">			
<?php			 
	            echo   tep_draw_bs_button(TEXT_FEEDERS_BING_NOFTP,   'disk', tep_catalog_href_link('usu5_sitemaps/feeders_bing.php?noftp=1&return=' . basename($PHP_SELF) . '&admin_id=' . $HTTP_GET_VARS['osCAdminID']),   null, null, 'btn-primary ') . PHP_EOL;			
	            echo   tep_draw_bs_button(TEXT_FEEDERS_BING,         'link', tep_catalog_href_link('usu5_sitemaps/feeders_bing.php?return=' . basename($PHP_SELF) . '&admin_id=' . $HTTP_GET_VARS['osCAdminID']),           null, null, 'btn-info') . PHP_EOL;			
?>				
             </div>				
			 <hr>
			 <br />
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>