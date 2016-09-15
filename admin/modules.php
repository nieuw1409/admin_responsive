<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $set = (isset($_GET['set']) ? $_GET['set'] : '');

  $modules = $cfgModules->getAll();

  if (empty($set) || !$cfgModules->exists($set)) {
    $set = $modules[0]['code'];
  }

  $module_type = $cfgModules->get($set, 'code');
  $module_directory = $cfgModules->get($set, 'directory');
  $module_language_directory = $cfgModules->get($set, 'language_directory');
  $module_key = $cfgModules->get($set, 'key');
  define('HEADING_TITLE', $cfgModules->get($set, 'title'));
  $template_integration = $cfgModules->get($set, 'template_integration');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
//        foreach( $_POST['configuration'] as $key => $value ) {
//          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");
//        }
//        tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $_GET['module']));
//        break;
     case 'save':
//        reset($HTTP_POST_VARS['configuration']);
//        while (list($key, $value) = each($HTTP_POST_VARS['configuration'])) {
        foreach( $HTTP_POST_VARS['configuration'] as $key => $value ) {	
        // bof Dynamic Template System              
          if((is_array($value)) && (!empty($value))){
            $pages = '';
            $count = count($value);
            for($i=0 ; $i<$count; $i++){
              $pages = "$pages$value[$i]";
              tep_db_query("update " . $multi_stores_config  . " set configuration_value = '" . $pages . "' where configuration_key = '" . $key . "'"); 
            }
          }
        // eof Dynamic Template System
          else
          {         
          tep_db_query("update " . $multi_stores_config  . " set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'");	
          }
        }
// Configuration Cache modification start
        require_once('includes/configuration_cache.php');
// Configuration Cache modification end			
        tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $HTTP_GET_VARS['module']));
        break;
      case 'install':
      case 'remove':
        $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
        $class = basename($_GET['module']);
        if (file_exists($module_directory . $class . $file_extension)) {
          include($module_directory . $class . $file_extension);
          $module = new $class;
          if ($action == 'install') {
            if ($module->check() > 0) { // remove module if already installed
              $module->remove();
            }

            $module->install();

            $modules_installed = explode(';', constant($module_key));

            if (!in_array($class . $file_extension, $modules_installed)) {
              $modules_installed[] = $class . $file_extension;
            }

            tep_db_query("update " . $multi_stores_config  . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = '" . $module_key . "'");
             // Configuration Cache modification start
             require_once('includes/configuration_cache.php');
             // Configuration Cache modification end			
            tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class));
          } elseif ($action == 'remove') {
            $module->remove();

            $modules_installed = explode(';', constant($module_key));

            if (in_array($class . $file_extension, $modules_installed)) {
              unset($modules_installed[array_search($class . $file_extension, $modules_installed)]);
            }

            tep_db_query("update " . $multi_stores_config  . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = '" . $module_key . "'");
// Configuration Cache modification start
            require_once('includes/configuration_cache.php');
// Configuration Cache modification end					
            tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set));
          }
        }
        tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class));
        // Configuration Cache modification start
        require_once('includes/configuration_cache.php');
        // Configuration Cache modification end		
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');

  $modules_installed = (defined($module_key) ? explode(';', constant($module_key)) : array());
  $new_modules_counter = 0;

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          if (isset($_GET['list']) && ($_GET['list'] = 'new')) {
            if (!in_array($file, $modules_installed)) {
              $directory_array[] = $file;
            }
          } else {
            if (in_array($file, $modules_installed)) {
              $directory_array[] = $file;
            } else {
              $new_modules_counter++;
            }
          }
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }
?>

      <div class="pageHeading col-md-4 col-sm-4">
        <h1><?php echo HEADING_TITLE; ?></h1>
      </div>

<?php
  if (isset($_GET['list'])) {
    echo '      <div class="col-md-2 col-md-offset-6 col-sm-2 col-sm-offset-5">' . tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_MODULES, 'set=' . $set)) . '</div>';
  } else {
    echo '      <div class="col-md-2 col-md-offset-6 col-sm-2 col-sm-offset-5">' . tep_draw_bs_button(IMAGE_MODULE_INSTALL . ' (' . $new_modules_counter . ')', 'plus', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&list=new')) . '</div>';
  }
?>
      <div class="col-xs-12 col-md-12">
        <table class="table table-hover table-bordered">
          <thead>
            <tr class="dataTableHeadingRow">
              <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULES; ?></th>
              <th class="dataTableHeadingContent" style="text-align: right;"><?php echo TABLE_HEADING_SORT_ORDER; ?></th>
              <th class="dataTableHeadingContent" style="text-align: right;"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
  $installed_modules = array();
  for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
    $file = $directory_array[$i];

    include($module_language_directory . $language . '/modules/' . $module_type . '/' . $file);
    include($module_directory . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
      $module = new $class;
      if ($module->check() > 0) {
        if (($module->sort_order > 0) && !isset($installed_modules[$module->sort_order])) {
          $installed_modules[$module->sort_order] = $file;
        } else {
          $installed_modules[] = $file;
        }
      }

      if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $class))) && !isset($mInfo)) {
        $module_info = array('code' => $module->code,
                             'title' => $module->title,
                             'description' => $module->description,
                             'status' => $module->check(),
                             'signature' => (isset($module->signature) ? $module->signature : null),
                             'api_version' => (isset($module->api_version) ? $module->api_version : null));

        $module_keys = $module->keys();

        $keys_extra = array();
        for ($j=0, $k=sizeof($module_keys); $j<$k; $j++) {
          $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . $multi_stores_config  . " where configuration_key = '" . $module_keys[$j] . "'");
          $key_value = tep_db_fetch_array($key_value_query);

//          $keys_extra[$module_keys[$j]]['title'] = $key_value['configuration_title'];
//          $keys_extra[$module_keys[$j]]['description'] = $key_value['configuration_description'];
		  // take the title and description defined in the language file of the module
		  // if not present use the standard english version
          if(defined('TITLE_'.$module_keys[$j])){
	         $keys_extra[$module_keys[$j]]['title'] =  CONSTANT('TITLE_'.$module_keys[$j] );
		     $keys_extra[$module_keys[$j]]['description'] = CONSTANT('DESCRIPTION_'.$module_keys[$j]);
          }else{
	         $keys_extra[$module_keys[$j]]['title'] = $key_value['configuration_title'];
		     $keys_extra[$module_keys[$j]]['description'] = $key_value['configuration_description'];
	      }

          $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
          $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
          $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
        }

        $module_info['keys'] = $keys_extra;

        $mInfo = new objectInfo($module_info);
      }

      if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) {
        if ($module->check() > 0) {
          echo '            <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class . '&action=edit') . '\'">' . "\n";
        } else {
          echo '            <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
        }
      } else {
        echo '            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $set . (isset($_GET['list']) ? '&list=new' : '') . '&module=' . $class) . '\'">' . "\n";
      }
?>
              <td class="dataTableContent"><?php echo $module->title; ?></td>
              <td class="dataTableContent" style="text-align: right;"><?php if (in_array($module->code . $file_extension, $modules_installed) && is_numeric($module->sort_order)) echo $module->sort_order; ?></td>
<!--              <td class="dataTableContent" style="text-align: right;"><?php if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) { echo tep_glyphicon('play'); } else { echo '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . (isset($_GET['list']) ? '&list=new' : '') . '&module=' . $class) . '">' . tep_glyphicon('info-sign') . '</a>'; } ?>&nbsp;</td> -->
                      <td class="text-center">
                       <div class="btn-toolbar" role="toolbar">                   
<?php
//                          echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO, 'info-sign', tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['configuration_id'] . '&action=info'), null, 'info') . '</div>' . PHP_EOL ;
	
                          if (isset($HTTP_GET_VARS['list']) && ($HTTP_GET_VARS['list'] == 'new')) {	
                             echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'plus', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $module->code . '&action=install'), null, 'warning') . '</div>' . PHP_EOL ;
						  } else {                           
							 echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $module->code . '&action=edit'), null, 'warning') . '</div>' . PHP_EOL ;						  
                             echo '<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'remove', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $module->code . '&action=remove'), null, 'danger') . '</div>' . PHP_EOL ;						  
							 
						  }
		
?>
                       </div>					  
					  </td>
            </tr>
<?php
    }
  }

  if (!isset($_GET['list'])) {
    ksort($installed_modules);
    $check_query = tep_db_query("select configuration_value from " . $multi_stores_config  . " where configuration_key = '" . $module_key . "'");
    if (tep_db_num_rows($check_query)) {
      $check = tep_db_fetch_array($check_query);
      if ($check['configuration_value'] != implode(';', $installed_modules)) {
        tep_db_query("update " . $multi_stores_config  . " set configuration_value = '" . implode(';', $installed_modules) . "', last_modified = now() where configuration_key = '" . $module_key . "'");
             // Configuration Cache modification start
        require('includes/configuration_cache.php');
             // Configuration Cache modification end		
      }
    } else {
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', '" . $module_key . "', '" . implode(';', $installed_modules) . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
             // Configuration Cache modification start
      require('includes/configuration_cache.php');
             // Configuration Cache modification end	  
    }

    if ($template_integration == true) {
      $check_query = tep_db_query("select configuration_value from " . $multi_stores_config  . " where configuration_key = 'TEMPLATE_BLOCK_GROUPS'");
      if (tep_db_num_rows($check_query)) {
        $check = tep_db_fetch_array($check_query);
        $tbgroups_array = explode(';', $check['configuration_value']);
        if (!in_array($module_type, $tbgroups_array)) {
          $tbgroups_array[] = $module_type;
          sort($tbgroups_array);
          tep_db_query("update " . $multi_stores_config  . " set configuration_value = '" . implode(';', $tbgroups_array) . "', last_modified = now() where configuration_key = 'TEMPLATE_BLOCK_GROUPS'");
             // Configuration Cache modification start
          require('includes/configuration_cache.php');
             // Configuration Cache modification end		  
        }
      } else {
        tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Template Block Groups', 'TEMPLATE_BLOCK_GROUPS', '" . $module_type . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
             // Configuration Cache modification start
        require('includes/configuration_cache.php');
             // Configuration Cache modification end		
      }
    }
  }
?>
          </tbody>
        </table>
        <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText bg-info"><?php echo TEXT_MODULE_DIRECTORY . ' ' . $module_directory; ?></td>
          </tr>
        </table>
      </div>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      $keys = '';
      foreach( $mInfo->keys as $key => $value ) {
        $keys .= '<strong>' . $value['title'] . '</strong><br />' . $value['description'] . '<br />';

        if ($value['set_function']) {
          eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
//		  tep_call_function($value['set_function'] , $value['value'] ) ;
        } else {
          $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
        }
        $keys .= '<br /><br />';
      }
      $keys = substr($keys, 0, strrpos($keys, '<br /><br />'));

      $heading[] = array('text' => '<strong>' . $mInfo->title . '</strong>');

      $contents = array('form' => tep_draw_form('modules', FILENAME_MODULES, 'set=' . $set . '&module=' . $_GET['module'] . '&action=save', 'post', 'role="form" enctype="multipart/form-data"'));
      $contents[] = array('text' => $keys);
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null, 'primary') . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $_GET['module'])));
      break;
    default:
      $heading[] = array('text' => '<strong>' . $mInfo->title . '</strong>');

      if (in_array($mInfo->code . $file_extension, $modules_installed) && ($mInfo->status > 0)) {
        $keys = '';
        foreach( $mInfo->keys as $value ) {
          $keys .= '<strong>' . $value['title'] . '</strong><br />';
          if ($value['use_function']) {
            $use_function = $value['use_function'];
            if (preg_match('/->/', $use_function)) {
              $class_method = explode('->', $use_function);
              if (!isset(${$class_method[0]}) || !is_object(${$class_method[0]})) {
                include(DIR_WS_CLASSES . $class_method[0] . '.php');
                ${$class_method[0]} = new $class_method[0]();
              }
              $keys .= tep_call_function($class_method[1], $value['value'], ${$class_method[0]});
            } else {
              $keys .= tep_call_function($use_function, $value['value']);
            }
          } else {
            $keys .= $value['value'];
          }
          $keys .= '<br /><br />';
        }
        $keys = substr($keys, 0, strrpos($keys, '<br /><br />'));

//        $contents[] = array('align' => 'center', 'text' => tep_draw_bs_button(IMAGE_EDIT, 'document', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $mInfo->code . '&action=edit')) . tep_draw_bs_button(IMAGE_MODULE_REMOVE, 'minus', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $mInfo->code . '&action=remove')));

        if (isset($mInfo->signature) && (list($scode, $smodule, $sversion, $soscversion) = explode('|', $mInfo->signature))) {
          $contents[] = array('text' => '<br />' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '&nbsp;<strong>' . TEXT_INFO_VERSION . '</strong> ' . $sversion . ' (<a href="http://sig.oscommerce.com/' . $mInfo->signature . '" target="_blank">' . TEXT_INFO_ONLINE_STATUS . '</a>)');
        }

        if (isset($mInfo->api_version)) {
          $contents[] = array('text' => tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '&nbsp;<strong>' . TEXT_INFO_API_VERSION . '</strong> ' . $mInfo->api_version);
        }

        $contents[] = array('text' => '<br />' . $mInfo->description);
//        $contents[] = array('text' => '<br />' . $keys);
        // bof Dynamic Template System
        $files = explode(";", $keys);        
        foreach($files as $file){        
        $contents[] = array('text' =>  $file);
        }
        // eof Dynamic Template System
      } elseif (isset($_GET['list']) && ($_GET['list'] == 'new')) {
        if (isset($mInfo)) {
          $contents[] = array('align' => 'center', 'text' => tep_draw_bs_button(IMAGE_MODULE_INSTALL, 'plus', tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $mInfo->code . '&action=install')));

          if (isset($mInfo->signature) && (list($scode, $smodule, $sversion, $soscversion) = explode('|', $mInfo->signature))) {
            $contents[] = array('text' => '<br />' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '&nbsp;<strong>' . TEXT_INFO_VERSION . '</strong> ' . $sversion . ' (<a href="http://sig.oscommerce.com/' . $mInfo->signature . '" target="_blank">' . TEXT_INFO_ONLINE_STATUS . '</a>)');
          }

          if (isset($mInfo->api_version)) {
            $contents[] = array('text' => tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '&nbsp;<strong>' . TEXT_INFO_API_VERSION . '</strong> ' . $mInfo->api_version);
          }

          $contents[] = array('text' => '<br />' . $mInfo->description);
        }
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {

    $box = new box;
    echo $box->infoBox($heading, $contents);

  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
