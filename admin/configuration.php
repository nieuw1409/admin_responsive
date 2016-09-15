<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  define( 'MULTI_STORES_CONFIG', $multi_stores_config ) ; // multi stores
  
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
        $configuration_value = tep_db_prepare_input($HTTP_POST_VARS['configuration_value']);
        $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        tep_db_query("update " . MULTI_STORES_CONFIG . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");
       // set the WARN_BEFORE_DOWN_FOR_MAINTENANCE to false if DOWN_FOR_MAINTENANCE = true
        if ( (WARN_BEFORE_DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE == 'true') ) {
	       tep_db_query("update " . MULTI_STORES_CONFIG . " set configuration_value = 'false', last_modified = now() where configuration_key = 'WARN_BEFORE_DOWN_FOR_MAINTENANCE'"); 
		} 		
		// Configuration Cache modification start
        require ('includes/configuration_cache.php');
        // Configuration Cache modification end

        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));
      break;
    }
  }

  $gID = (isset($HTTP_GET_VARS['gID'])) ? $HTTP_GET_VARS['gID'] : 1;

  $cfg_group_query = tep_db_query("select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");
  $cfg_group = tep_db_fetch_array($cfg_group_query);

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

          <div class="page-header">
            <h1><?php echo $cfg_group['configuration_group_title']; ?></h1>
          </div>
  
          <div class="panel panel-default">        
  
            <table class="table table-hover table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                  <th><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></th>
                  <th><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></th>
                  <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
<?php
  // do not get title get the title later
  $configuration_query = tep_db_query("select configuration_id, configuration_group_id, sort_order, configuration_value, use_function from " . MULTI_STORES_CONFIG . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");
 
   while ($configuration = tep_db_fetch_array($configuration_query)) {
    if (tep_not_null($configuration['use_function'])) {
      $use_function = $configuration['use_function'];
      if (preg_match('/->/', $use_function)) {
        $class_method = explode('->', $use_function);
        if (!is_object(${$class_method[0]})) {
          include(DIR_WS_CLASSES . $class_method[0] . '.php');
          ${$class_method[0]} = new $class_method[0]();
        }
        $cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
      } else {
        $cfgValue = tep_call_function($use_function, $configuration['configuration_value']);
      }
    } else {
      $cfgValue = $configuration['configuration_value'];
    }
	
	// get title and description for this languages
    $cfg_languages_query = tep_db_query("select configuration_title, configuration_description from " . TABLE_CONFIGURATION_LANGUAGES . " where configuration_group_id = '" . (int)$configuration['configuration_group_id'] . "' and configuration_sort_order = '" . (int)$configuration['sort_order'] . "' and languages_id='" . (int)$languages_id ."'");
    $cfg_languages = tep_db_fetch_array($cfg_languages_query);
	  
	  
    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
	  // do not get the description get this later
      //$cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
      $cfg_extra_query = tep_db_query("select configuration_key, configuration_group_id, sort_order,date_added, last_modified, use_function, set_function from " . MULTI_STORES_CONFIG . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
      $cfg_extra = tep_db_fetch_array($cfg_extra_query);
  
      $cInfo_array_temp   = array_merge((array)$configuration, (array)$cfg_extra);
      $cInfo_array        = array_merge((array)$cInfo_array_temp, (array)$cfg_languages);	  
      $cInfo = new objectInfo($cInfo_array);
    }

    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
      echo '                <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') .'\'">' . "\n";
    } else {
      echo '                <tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
    }
?>
                  <td><?php echo $cfg_languages['configuration_title']; ?></td>
                  <td><?php echo htmlspecialchars($cfgValue); ?></td>
                  <td class="text-right">
                    <div class="btn-toolbar" role="toolbar">                   
<?php
    echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO, 'info-sign', tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['configuration_id'] . '&action=info'), null, 'info') . '</div>' . "\n" . 
         '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['configuration_id'] . '&action=edit'), null, 'warning') . '</div>' . "\n";
?>
                    </div>
                  </td>
                </tr>
<?php
    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) && (isset($HTTP_GET_VARS['action'])) ) {

      switch ($action) {
        case 'edit': 
          $heading[] = array('text' => '<strong>' . $cInfo->configuration_title . '</strong>');
      
          if ($cInfo->set_function) {
            eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');

          } else {
            $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
          }
	  
          $contents = '';
          $contents .= '                      ' . tep_draw_form('configuration', FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save') . "\n";
          $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . "\n";
          $contents .= '                          <p>' . $cInfo->configuration_description . '</p>' . "\n";
          $contents .= '                          ' . $value_field . "\n";
          $contents .= '                        </div>' . "\n";
          $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . "\n";
          $contents .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->configuration_id), null, null, 'btn-default text-danger') . "\n";
          $contents .= '                        </div>' . "\n";
          $contents .= '                      </form>' . "\n";
        break;
      
        default:
          if (isset($cInfo) && is_object($cInfo)) {
            $contents = '';
            $contents .= '                      <div class="col-xs-12 col-sm-8 col-md-8">' . "\n";      
            $contents .= '                        <p>' . $cInfo->configuration_description . '</p>' . "\n"; 
            $contents .= '                      </div>' . "\n";
            $contents .= '                      <div class="col-xs-12 col-sm-4 col-md-4">' . "\n";
			
			$contents .= '                        <ul class="list-group">' . "\n";
			$contents .= '                          <li class="list-group-item">' . "\n";
			$contents .= '                            <span class="badge badge-info">' . tep_date_short($cInfo->date_added) . '</span>' . "\n";			
			$contents .= '                              ' . TEXT_INFO_DATE_ADDED . "\n";
			
			$contents .= '                          </li>' . "\n";
			
            if (tep_not_null($cInfo->last_modified)) {
		      $contents .= '                          <li class="list-group-item">' . "\n";
			  $contents .= '                            <span class="badge badge-info">' . tep_date_short($cInfo->last_modified) . '</span>' . "\n";			
			  $contents .= '                              ' . TEXT_INFO_LAST_MODIFIED . "\n";
			  $contents .= '                          </li>' . "\n";					
			}
			
			$contents .= '                        </ul>' . "\n";
            
			$contents .= '                      </div>' . "\n";
          }
        break;
      }	
	
      echo '                <tr class="content-row">' . "\n" .
           '                  <td colspan="3">' . "\n" .
		   '                    <div class="row">' . "\n" .
                                  $contents . 
           '                    </div>' . "\n" .
           '                  </td>' . "\n" .
           '                </tr>' . "\n";
    }
	
  }
?>

              </tbody>
            </table>
          </div>
  
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
