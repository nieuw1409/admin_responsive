<?php
/*
  Created by: Linda McGrath osCOMMERCE@WebMakers.com
  
  Updated by: Andy Nguyen 01-06-2011

  down_for_maintenance.php v2.4.1

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . DOWN_FOR_MAINTENANCE_FILENAME);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME));

  require(DIR_WS_INCLUDES . 'template_top.php');

   $stores_query_imgage_dir = tep_db_query("select stores_config_table from " . TABLE_STORES . "  where stores_id = " . SYS_STORES_ID );
   $config_table_default_store = tep_db_fetch_array($stores_query_imgage_dir) ;
   $multi_stores_config = $config_table_default_store[ 'stores_config_table' ] ; 

  if (DOWN_FOR_MAINTENANCE == 'true') { 
    $maintenance_on_at_time_raw = tep_db_query("select last_modified from " . $multi_stores_config . " WHERE configuration_key = 'DOWN_FOR_MAINTENANCE'"); 
    $maintenance_on_at_time= tep_db_fetch_array($maintenance_on_at_time_raw); 
    define('TEXT_DATE_TIME', $maintenance_on_at_time['last_modified']); 
  } 
?>
<div class="page-header">
  <h1>
     <?php echo HEADING_TITLE; ?>
  </h1>
<?php 
    echo tep_image(DIR_WS_IMAGES . 'down_for_maintenance.gif', HEADING_TITLE); 
?>  
</div>


<div class="panel panel-default panel-info">
  <div class="panel-body">	
	<div class="alert alert-info" role="alert">
	  <?php echo DOWN_FOR_MAINTENANCE_TEXT_INFORMATION; ?>
	</div>
	
	<div class="alert alert-success" role="alert">
<?php 
    	if (DISPLAY_MAINTENANCE_TIME == 'true'){ 
?>
		<p><?php echo TEXT_MAINTENANCE_ON_AT_TIME . TEXT_DATE_TIME . '&nbsp;' . 'EST'; ?></p>		
<?php
		} 
		if (DISPLAY_MAINTENANCE_PERIOD == 'true') { 
?>
		<p><?php echo TEXT_MAINTENANCE_PERIOD . TEXT_MAINTENANCE_PERIOD_TIME; ?></p>
<?php
		}
?>
		<p><?php echo DOWN_FOR_MAINTENANCE_STATUS_TEXT; ?></p>    
	</div>		
    <div class="buttonSet">
       <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?>
    </div>
   </div>
</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>