<?php
/*
  $Id: database_optimizer_cron.php,v 1.0 2011/02/02
  database_optimizer_cron.php Originally Created by: Jack_mcs - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2011 oscommerce-solution.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/database_optimizer_db_handler.php');
   
  
  $actionRunOptimizer  = ((isset($HTTP_POST_VARS['action_run_optimizer']) && $HTTP_POST_VARS['action_run_optimizer'] == 'process') ? true : false);
  $currentVersion = '';
  $message = '';

  /********************** BEGIN VERSION CHECKER *********************/
  if (file_exists(DIR_WS_FUNCTIONS . 'version_checker.php')) {
      require(DIR_WS_LANGUAGES . $language . '/version_checker.php');
      require(DIR_WS_FUNCTIONS . 'version_checker.php');
      $contribPath = 'http://addons.oscommerce.com/info/4441';
      $currentVersion = 'Database Optimizer V 1.5';
      $contribName = 'Database Optimizer V';
      $versionStatus = '';
  }
  /********************** END VERSION CHECKER *********************/

  if (isset($HTTP_POST_VARS['action']))  {
      /********************** CHECK THE VERSION ***********************/
      if ($HTTP_POST_VARS['action'] == 'getversion') {
          if (isset($HTTP_POST_VARS['version_check']) && $HTTP_POST_VARS['version_check'] == 'on') {
              $versionStatus = AnnounceVersion($contribPath, $currentVersion, $contribName);
          }
      }
  }

  else if ($actionRunOptimizer) {
      $forceOptimize = true;       //this is being ran manually so ignore the setting for optimizing
      require('includes/functions/database_optimizer.php');
      require(DIR_WS_MODULES . 'database_optimizer.php');
      if (! $optionSelected) {
          $messageStack->add(ERROR_NO_OPTION_SELECTED, 'error');
      }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<link rel="stylesheet" type="text/css" href="includes/database_optimizer.css"> 
<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
   
<?php 

		        $contents .= '                      ' . tep_draw_bs_form('database_optimizer', 'database_optimizer.php', '', 'post', 'role="form"', 'id_database_optimizer') . PHP_EOL;
                $contents .= '                       <div class="form-group">' . PHP_EOL ;	
                $contents .=                             tep_draw_hidden_field('action_run_optimizer', 'process') . PHP_EOL;			
				$contents .= '                        </div>' . PHP_EOL;
				
			    $contents .= '                        <ul class="list-group">' . PHP_EOL;				
                foreach ($optionsArray as $option) {	

					$contents .= '<li class="list-group-item">' . PHP_EOL ;
					$contents .= '     <div class="row">' . PHP_EOL ;	
					$contents .= '          <div class="col-xs-1">'. tep_bs_checkbox_field($option['post'], '', null, $option['option'], false, 'checkbox checkbox-success') . '</div>' . PHP_EOL ;	
					$contents .= '          <div class="col-xs-5"><span class="control-label">' . $option['option'] . '</span></div>' . PHP_EOL ;	
					$contents .= '          <div class="col-xs-6"><span class="control-label">' . $option['explain']  . '</span></div> ' . PHP_EOL ;	
					$contents .= '     </div>' . PHP_EOL ;	  
					$contents .= '</li>' . PHP_EOL ; 
				}
 			    $contents .= '                        </ul>' . PHP_EOL;					
				
                $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4">' . PHP_EOL;
                $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . PHP_EOL;
                $contents .= '                        </div>' . PHP_EOL;
		        $contents .= '                      </form>' . PHP_EOL;
				
				//<!-- BEGIN SHOW THE RESULTS -->
				if (tep_not_null($message)) {
		           $contents .= '                   <br /><br />' . PHP_EOL ;				
		           $contents .= '                   <div class="panel panel-primary">'. PHP_EOL ;
		           $contents .= '                       <div class="panel-body">'. PHP_EOL ;
		           $contents .= '                         <div class="well">' . str_replace("\r\n", "<br>", $message) . '</div>'. PHP_EOL ; 
		           $contents .= '                       </div>'. PHP_EOL ;
		           $contents .= '                   </div>'. PHP_EOL ;				
				}
				
                echo $contents ; 
				
?>
</table>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
