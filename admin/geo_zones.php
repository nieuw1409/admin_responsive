<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $saction = (isset($HTTP_GET_VARS['saction']) ? $HTTP_GET_VARS['saction'] : '');

  if (tep_not_null($saction)) {
    switch ($saction) {
      case 'insert_sub':
        $zID = tep_db_prepare_input($HTTP_GET_VARS['zID']);
        $zone_country_id = tep_db_prepare_input($HTTP_POST_VARS['zone_country_id']);
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);

        tep_db_query("insert into " . TABLE_ZONES_TO_GEO_ZONES . " (zone_country_id, zone_id, geo_zone_id, date_added) values ('" . (int)$zone_country_id . "', '" . (int)$zone_id . "', '" . (int)$zID . "', now())");
        $new_subzone_id = tep_db_insert_id();

        tep_redirect(tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $new_subzone_id));
        break;
      case 'save_sub':
        $sID = tep_db_prepare_input($HTTP_GET_VARS['sID']);
        $zID = tep_db_prepare_input($HTTP_GET_VARS['zID']);
        $zone_country_id = tep_db_prepare_input($HTTP_POST_VARS['zone_country_id']);
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);

        tep_db_query("update " . TABLE_ZONES_TO_GEO_ZONES . " set geo_zone_id = '" . (int)$zID . "', zone_country_id = '" . (int)$zone_country_id . "', zone_id = " . (tep_not_null($zone_id) ? "'" . (int)$zone_id . "'" : 'null') . ", last_modified = now() where association_id = '" . (int)$sID . "'");

        tep_redirect(tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $HTTP_GET_VARS['sID']));
        break;
      case 'deleteconfirm_sub':
        $sID = tep_db_prepare_input($HTTP_GET_VARS['sID']);

        tep_db_query("delete from " . TABLE_ZONES_TO_GEO_ZONES . " where association_id = '" . (int)$sID . "'");

        tep_redirect(tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage']));
        break;
    }
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert_zone':
        $geo_zone_name = tep_db_prepare_input($HTTP_POST_VARS['geo_zone_name']);
        $geo_zone_description = tep_db_prepare_input($HTTP_POST_VARS['geo_zone_description']);

        tep_db_query("insert into " . TABLE_GEO_ZONES . " (geo_zone_name, geo_zone_description, date_added) values ('" . tep_db_input($geo_zone_name) . "', '" . tep_db_input($geo_zone_description) . "', now())");
        $new_zone_id = tep_db_insert_id();

        tep_redirect(tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $new_zone_id));
        break;
      case 'save_zone':
        $zID = tep_db_prepare_input($HTTP_GET_VARS['zID']);
        $geo_zone_name = tep_db_prepare_input($HTTP_POST_VARS['geo_zone_name']);
        $geo_zone_description = tep_db_prepare_input($HTTP_POST_VARS['geo_zone_description']);

        tep_db_query("update " . TABLE_GEO_ZONES . " set geo_zone_name = '" . tep_db_input($geo_zone_name) . "', geo_zone_description = '" . tep_db_input($geo_zone_description) . "', last_modified = now() where geo_zone_id = '" . (int)$zID . "'");

        tep_redirect(tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID']));
        break;
      case 'deleteconfirm_zone':
        $zID = tep_db_prepare_input($HTTP_GET_VARS['zID']);

        tep_db_query("delete from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$zID . "'");
        tep_db_query("delete from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)$zID . "'");

        tep_redirect(tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');

  if (isset($HTTP_GET_VARS['zID']) && (($saction == 'edit') || ($saction == 'new'))) {
?>
<script type="text/javascript"><!--
function resetZoneSelected(theForm) {
  if (theForm.state.value != '') {
    theForm.zone_id.selectedIndex = '0';
    if (theForm.zone_id.options.length > 0) {
      theForm.state.value = '<?php echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
  var NumState = theForm.zone_id.options.length;
  var SelectedCountry = "";

  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }         

  SelectedCountry = theForm.zone_country_id.options[theForm.zone_country_id.selectedIndex].value;

<?php echo tep_js_zone_list('SelectedCountry', 'theForm', 'zone_id'); ?>

}
//--></script>
<?php
  }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <div class="page-header">
          <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
          <div class="clearfix"></div>
      </div><!-- page-header-->
      <div class="table-responsive">
        <table class="table table-condensed table-striped">
          <thead>
                <tr class="heading-row">
<?php
                    if ($action == 'list') {
?>		
                      <th>                     <?php echo TABLE_HEADING_COUNTRY; ?></th>
                      <th>                     <?php echo TABLE_HEADING_COUNTRY_ZONE; ?></th>				    			    			   
                      <th>                     <?php echo TABLE_HEADING_ACTION; ?></th>	   
<?php
                   } else {
?>

                      <th>                   <?php echo TABLE_HEADING_TAX_ZONES; ?></td>
                      <th>                   <?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>				   
<?php
                   }
?>				   
                </tr>
          </thead>
          <tbody>
<?php
             if ($action == 'list') {
				$rows = 0;
				$zones_query_raw = "select a.association_id, a.zone_country_id, c.countries_name, a.zone_id, a.geo_zone_id, a.last_modified, a.date_added, z.zone_name from " . TABLE_ZONES_TO_GEO_ZONES . " a left join " . TABLE_COUNTRIES . " c on a.zone_country_id = c.countries_id left join " . TABLE_ZONES . " z on a.zone_id = z.zone_id where a.geo_zone_id = " . $HTTP_GET_VARS['zID'] . " order by association_id";
				$zones_split = new splitPageResults($HTTP_GET_VARS['spage'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
				$zones_query = tep_db_query($zones_query_raw);
				while ($zones = tep_db_fetch_array($zones_query)) {
				  $rows++;
				  if ((!isset($HTTP_GET_VARS['sID']) || (isset($HTTP_GET_VARS['sID']) && ($HTTP_GET_VARS['sID'] == $zones['association_id']))) && !isset($sInfo) && (substr($action, 0, 3) != 'new')) {
					$sInfo = new objectInfo($zones);
				  }
				  if (isset($sInfo) && is_object($sInfo) && ($zones['association_id'] == $sInfo->association_id)) {
					echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id . '&saction=edit') . '\'">' . "\n";
				  } else {
					echo '<tr                onclick="document.location.href=\'' . tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $zones['association_id']) . '\'">' . "\n";
				  }
?>
							   <td ><?php echo (($zones['countries_name']) ? $zones['countries_name'] : TEXT_ALL_COUNTRIES); ?></td>
							   <td ><?php echo (($zones['zone_id']) ? $zones['zone_name'] : PLEASE_SELECT); ?></td>
							   <td class="text-right">								 
								   <div class="btn-toolbar" role="toolbar">                  
<?php
					   echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&' . (isset($sInfo) ? 'sID=' . $zones['association_id']. '&' : '') . 'saction=info_sub_zone'),    null, 'info')    . '</div>' . PHP_EOL .
							'             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&' . (isset($sInfo) ? 'sID=' . $zones['association_id']. '&' : '') . 'saction=edit_sub_zone'),    null, 'warning') . '</div>' . PHP_EOL .
							'             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&' . (isset($sInfo) ? 'sID=' . $zones['association_id'] . '&' : ''). 'saction=confirm_sub_zone'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
								   </div> 
							   </td>	              
						   </tr>				 
<?php	
                           if (isset($sInfo) && is_object($sInfo) && ($zones['zone_id'] == $sInfo->zone_id) && isset($HTTP_GET_VARS['saction'])) { 
				  
	                             $alertClass = '';
                                 switch ($saction) {			 
		                            case 'confirm_sub_zone':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_SUB_ZONE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id . '&saction=deleteconfirm_sub') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_SUB_ZONE_INTRO . '<br />' . $zInfo->geo_zone_name  . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit_sub_zone':  
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_SUB_ZONE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id . '&saction=save_sub') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;
                                       $contents            .= '                       ' . TEXT_INFO_EDIT_SUB_ZONE_INTRO. PHP_EOL ;								   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('zone_country_id', tep_get_countries(TEXT_ALL_COUNTRIES), $sInfo->zone_country_id, TEXT_INFO_COUNTRY, 'input_Geo_Zone_Country', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left')	. PHP_EOL;
//									   $contents            .= '                           ' . tep_draw_bs_input_field('geo_zone_name',       $zInfo->geo_zone_name,        TEXT_INFO_ZONE_NAME,       'id_input_geo_zones_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_ZONE_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('zone_id', tep_prepare_country_zones_pull_down($sInfo->zone_country_id), $sInfo->zone_id, TEXT_INFO_COUNTRY_ZONE, 'input_Geo_Zone', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left')	. PHP_EOL;
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;				  
                                    case 'info_sub_zone':
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $cInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_DATE_ADDED . tep_date_short($sInfo->date_added) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        if (tep_not_null($sInfo->last_modified)) {										
                                          $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                              $contents .= '                              ' . TEXT_INFO_LAST_MODIFIED . '  ' . tep_date_short($sInfo->last_modified) . PHP_EOL;
			                              $contents .= '                          </li>' . PHP_EOL;					
								        }										  
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_LAST_MODIFIED . '  ' . tep_date_short($zInfo->last_modified) . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
 //                                       $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_ZONE_DESCRIPTION . '  ' . $zInfo->geo_zone_description . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;											
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id), null, null, 'btn-default text-danger') . PHP_EOL;						   
                                      break;				  									  
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
		                   }  // end  if (isset($sInfo) && is_object($sInfo) && ($zones['zone_id'] == $sInfo->zone_id				   
				} // end while ($zones = tep_db_fetch_array($zones_quer
				
             } else {
				$zones_query_raw = "select geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added from " . TABLE_GEO_ZONES . " order by geo_zone_name";
				$zones_split = new splitPageResults($HTTP_GET_VARS['zpage'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
				$zones_query = tep_db_query($zones_query_raw);
				while ($zones = tep_db_fetch_array($zones_query)) {
				  if ((!isset($HTTP_GET_VARS['zID']) || (isset($HTTP_GET_VARS['zID']) && ($HTTP_GET_VARS['zID'] == $zones['geo_zone_id']))) && !isset($zInfo) && (substr($action, 0, 3) != 'new')) {
					$num_zones_query = tep_db_query("select count(*) as num_zones from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)$zones['geo_zone_id'] . "' group by geo_zone_id");
					$num_zones = tep_db_fetch_array($num_zones_query);

					if ($num_zones['num_zones'] > 0) {
					  $zones['num_zones'] = $num_zones['num_zones'];
					} else {
					  $zones['num_zones'] = 0;
					}

					$zInfo = new objectInfo($zones);
				  }
				  if (isset($zInfo) && is_object($zInfo) && ($zones['geo_zone_id'] == $zInfo->geo_zone_id)) {
					echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=list') . '\'">' . "\n";
				  } else {
					echo '<tr                onclick="document.location.href=\'' . tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id']) . '\'">' . "\n";
				  }
?>
                <td class="dataTableContent"><?php echo $zones['geo_zone_name']; ?></td>
							   <td class="text-right">								 
								   <div class="btn-toolbar" role="toolbar">                  
<?php
					   echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id'] . '&action=info_geo_zone'),    null, 'info')    . '</div>' . PHP_EOL .
							'             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id'] . '&action=edit_geo_zone'),    null, 'warning') . '</div>' . PHP_EOL .
							'             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id'] . '&action=confirm_geo_zone'), null, 'danger')  . '</div>' . PHP_EOL .
							'             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'globe',         tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zones['geo_zone_id'] . '&action=list')            , null, 'warning')  . '</div>' . PHP_EOL ;
?>
								   </div> 
							   </td>	              
						   </tr>		  
<?php
                           if (isset($zInfo) && is_object($zInfo) && ($zones['geo_zone_id'] == $zInfo->geo_zone_id) && isset($HTTP_GET_VARS['action'])) { 
				  
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm_geo_zone':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_ZONE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=deleteconfirm_zone') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_ZONE_INTRO . '<br />' . $zInfo->geo_zone_name  . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zInfo->geo_zone_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit_geo_zone':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_ZONE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('geo_zones', FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=save_zone', 'post', 'class="form-horizontal" role="form"', 'id_edit_geo_zones') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('geo_zone_name',       $zInfo->geo_zone_name,        TEXT_INFO_ZONE_NAME,       'id_input_geo_zones_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_ZONE_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('geo_zone_description',       $zInfo->geo_zone_description,        TEXT_INFO_ZONE_DESCRIPTION,       'id_input_countries_iso_code_2' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_ZONE_DESCRIPTION,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zInfo->geo_zone_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;				  
                                    case 'info_geo_zone':
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $cInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_NUMBER_ZONES .  $zInfo->num_zones . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_DATE_ADDED . '  ' . tep_date_short($zInfo->date_added ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_LAST_MODIFIED . '  ' . tep_date_short($zInfo->last_modified) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ZONE_DESCRIPTION . '  ' . $zInfo->geo_zone_description . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove-circle', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $zInfo->geo_zone_id), null, null, 'btn-default text-danger') . PHP_EOL;						   
                                      break;				  									  
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
		                   }  // end  if ( isset($sInfo) && isset(zInfo) && 
                }
             } 
?>
	      </tbody>
      </table>
    </div>	  
</table>	

<?php 
if ($action == 'list') {	
?>
   <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['spage'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['spage'], 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list', 'spage'); ?></div>		   
	     </div>
		  
<?php
          if (empty($saction)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_BACK,     'arrow-left',  tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID']), null, null, 'btn-default') ; ?>
                   <?php echo tep_draw_bs_button(IMAGE_NEW_ZONE, 'plus',   null,'data-toggle="modal" data-target="#new_sub_zone"') ; ?>
				   
 			 </div>
            </div>
<?php
          }		  
?>		  
   </table>
<?php
}  else {
?>
   <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['zpage'], TEXT_DISPLAY_NUMBER_OF_TAX_ZONES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['zpage'], '', 'zpage'); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		   
                   <?php echo tep_draw_bs_button(IMAGE_NEW_ZONE, 'plus',   null,'data-toggle="modal" data-target="#new_geo_zone"') ; ?>				   
 			 </div>
            </div>
<?php
          }			  
?>		  
   </table>	
<?php
}

?>
       <div class="modal fade"  id="new_sub_zone" role="dialog" aria-labelledby="new_sub_zone" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&' . (isset($HTTP_GET_VARS['sID']) ? 'sID=' . $HTTP_GET_VARS['sID'] . '&' : '') . 'saction=insert_sub') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_NEW_SUB_ZONE_INTRO; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 

			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_NEW_SUB_ZONE_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id . '&saction=save_sub') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;
//                                       $contents            .= '                       ' . TEXT_INFO_EDIT_SUB_ZONE_INTRO. PHP_EOL ;								   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('zone_country_id', tep_get_countries(TEXT_ALL_COUNTRIES), null, TEXT_INFO_COUNTRY, 'input_Geo_Zone_Country', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left')	. PHP_EOL;
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('zone_id', tep_prepare_country_zones_pull_down('zone_country_id'), null, TEXT_INFO_COUNTRY_ZONE, 'input_Geo_Zone', 'col-xs-9', ' selectpicker show-tick ', 'control-label col-xs-3', 'left')	. PHP_EOL;
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                       <br />' . PHP_EOL;	
 
                                       
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents . $contents_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=list&spage=' . $HTTP_GET_VARS['spage'] . '&sID=' . $sInfo->association_id)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_sub_zone --> 	
		  
      <div class="modal fade"  id="new_geo_zone" role="dialog" aria-labelledby="new_geo_zone" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'] . '&action=insert_zone') ; ?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_ZONE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_geo            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_geo            .= '          <div class="panel-heading">' . TEXT_INFO_NEW_ZONE_INTRO . '</div>' . PHP_EOL;
			                           $contents_geo            .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_geo            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_geo            .= '                           ' . tep_draw_bs_input_field('geo_zone_name',       null,        TEXT_INFO_ZONE_NAME,       'id_input_geo_zones_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_ZONE_NAME,       '', true ) . PHP_EOL;	
                                       $contents_geo            .= '                       </div>' . PHP_EOL ;	
                                       $contents_geo            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_geo            .= '                           ' . tep_draw_bs_input_field('geo_zone_description',       null,        TEXT_INFO_ZONE_DESCRIPTION,       'id_input_geo_zone_description' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_ZONE_DESCRIPTION,       '', true ) . PHP_EOL;	
                                       $contents_geo            .= '                       </div>' . PHP_EOL ;									   
//                                       $contents_geo            .= '                       <div class="form-group">' . PHP_EOL ;										   
//									   $contents_geo            .= '                           ' . tep_draw_bs_pull_down_menu('zone_country_id', tep_get_countries(), DEFAULT_COUNTRY, TEXT_INFO_COUNTRY_NAME, 'id_input_country', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
//                                       $contents_geo            .= '                       </div>' . PHP_EOL ;									   
									   $contents_geo            .= '                       <br />' . PHP_EOL;	
                                       
		                               $contents_geo_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_geo_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_geo . $contents_geo_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_GEO_ZONES, 'zpage=' . $HTTP_GET_VARS['zpage'] . '&zID=' . $HTTP_GET_VARS['zID'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_geo_zone --> 		  
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
