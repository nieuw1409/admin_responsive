<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License

  Uses sameHeights snipped from http://benhowdle.im/easy-peasy-equal-heights.html

*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');


  $group_array = array();

  $check_query = tep_db_query("select configuration_value from " .  $multi_stores_config   . " where configuration_key = 'MODULE_CONTENT_INSTALLED' limit 1");
//  if (tep_db_num_rows($check_query) < 1) {
//    tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', 'MODULE_CONTENT_INSTALLED', '', 'This is automatically updated. No need to edit.', '6', '0', now())");
//    define('MODULE_CONTENT_INSTALLED', '');
//  }
  if (tep_db_num_rows($check_query) < 1) {
    tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', 'MODULE_CONTENT_INSTALLED', '', 'This is automatically updated. No need to edit.', '6', '0', now())");
// Configuration Cache modification start
    require('includes/configuration_cache.php');
// Configuration Cache modification end	
    define('MODULE_CONTENT_INSTALLED', '');
  }

  $modules_installed = (tep_not_null(MODULE_CONTENT_INSTALLED) ? explode(';', MODULE_CONTENT_INSTALLED) : array());
  $modules = array('installed' => array(), 'new' => array());

  $active = ' class="active" ' ;
  $tab_block_bs .=  '  <!-- Begin Nav tabs Modules Content -->' . PHP_EOL ;
  $tab_block_bs .=  '<div role="tabpanel" id="tab_content_category">' . PHP_EOL;  
  $tab_block_bs .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_content_category">' . PHP_EOL ;  
  
  if ($maindir = @dir(DIR_FS_CATALOG_MODULES . 'content/')) {
    while ($group = $maindir->read()) {
      if ( ($group != '.') && ($group != '..') && is_dir(DIR_FS_CATALOG_MODULES . 'content/' . $group)) {
        $group_array[] = $group;
//        $tab_block  .= '       <li><a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params()) . '#section_' . $group . '">' . $group  . '</a></li>' . "\n";
        $tab_block_bs .=  '    <li  id="tab_content_category"'. $active . '>
		                           <a href="#tab_content_'.  $group . '" aria-controls="tab_name_' .  $group . '" role="tab" data-toggle="tab">' . $group . '</a>
							   </li>' . PHP_EOL ;
 		
        $active = '' ;
        if ($dir = @dir(DIR_FS_CATALOG_MODULES . 'content/' . $group)) {
          while ($file = $dir->read()) {
            if (!is_dir(DIR_FS_CATALOG_MODULES . 'content/' . $group . '/' . $file)) { // es un archivo
              if (substr($file, strrpos($file, '.')) == '.php') { // es .php
                $class = substr($file, 0, strrpos($file, '.')); // elimina la extensión

                if (!class_exists($class)) { // si no existe la clase:
                  if ( file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/content/' . $group . '/' . $file) ) {
                    include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/content/' . $group . '/' . $file);
                  }
                  include(DIR_FS_CATALOG_MODULES . 'content/' . $group . '/' . $file);
                }

                if (class_exists($class)) {
                  $module = new $class();

                  if (in_array($group . '/' . $class, $modules_installed)) {
                    $modules['installed'][] = array('code' => $class,
                                                    'title' => $module->title,
                                                    'group' => $group,
                                                    'description' =>$module->description,
                                                    'sort_order' => (int)$module->sort_order);
                  } else {
                    $modules['new'][] = array('code' => $class,
                                              'title' => $module->title,
                                              'group' => $group);
                  }
                }
              }
            }
          }
          $dir->close();
        }
      }
    }
 
    $maindir->close();

    function _sortContentModulesInstalled($a, $b) {
      return strnatcmp($a['group'] . '-' . (int)$a['sort_order'] . '-' . $a['title'], $b['group'] . '-' . (int)$b['sort_order'] . '-' . $b['title']);
    }

    function _sortContentModuleFiles($a, $b) {
      return strnatcmp($a['group'] . '-' . $a['title'], $b['group'] . '-' . $b['title']);
    }

    usort($modules['installed'], '_sortContentModulesInstalled');
    usort($modules['new'], '_sortContentModuleFiles');
  }
  
  $tab_block_bs .=  '  </ul>' . PHP_EOL ;  

// Update sort order in MODULE_CONTENT_INSTALLED
  $_installed = array();

  foreach ( $modules['installed'] as $m ) {
    $_installed[] = $m['group'] . '/' . $m['code'];
  }

  if ( implode(';', $_installed) != MODULE_CONTENT_INSTALLED ) {
    tep_db_query("update " .  $multi_stores_config   . " set configuration_value = '" . implode(';', $_installed) . "' where configuration_key = 'MODULE_CONTENT_INSTALLED'");
  }


switch ($action) {
      case 'save':
        $class = basename($_GET['module']);

        foreach ( $modules['installed'] as $m ) {
          if ( $m['code'] == $class ) {
            foreach ($HTTP_POST_VARS['configuration'] as $key => $value) {
              $key = tep_db_prepare_input($key);
              $value = tep_db_prepare_input($value);

              tep_db_query("update " .  $multi_stores_config   . " set configuration_value = '" . tep_db_input($value) . "' where configuration_key = '" . tep_db_input($key) . "'");
			  
            }
// Configuration Cache modification start
            require('includes/configuration_cache.php');
// Configuration Cache modification end	
            break;
          }
        }

        tep_redirect(tep_href_link('modules_content.php', 'module=' . $class));

        break;
    case 'disable':
      $class = basename($_GET['module']);

      foreach ( $modules['installed'] as $m ) {
        if ( $m['code'] == $class ) {
          $module = new $class();

          $module->disable();
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end	
          tep_redirect(tep_href_link('modules_content.php'));
        }
      }

      tep_redirect(tep_href_link($_SERVER['PHP_SELF'], 'module=' . $class));

      break;
    case 'enable':
      $class = basename($_GET['module']);

      foreach ( $modules['installed'] as $m ) {
        if ( $m['code'] == $class ) {
          $module = new $class();

          $module->enable();
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end	
          tep_redirect(tep_href_link('modules_content.php'));
        }
      }
// Configuration Cache modification start
      require('includes/configuration_cache.php');
// Configuration Cache modification end		
      tep_redirect(tep_href_link($_SERVER['PHP_SELF'], 'module=' . $class));
      break;

      case 'install':
        $class = basename($_GET['module']);

        foreach ( $modules['new'] as $m ) {
          if ( $m['code'] == $class ) {
            $module = new $class();

            $module->install();

            $modules_installed[] = $m['group'] . '/' . $m['code'];

            tep_db_query("update " .  $multi_stores_config   . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = 'MODULE_CONTENT_INSTALLED'");
// Configuration Cache modification start
            require('includes/configuration_cache.php');
// Configuration Cache modification end				
            tep_redirect(tep_href_link(basename($_SERVER['PHP_SELF']), 'module=' . $class . '&action=configure'));
          }
        }
// Configuration Cache modification start
        require('includes/configuration_cache.php');
// Configuration Cache modification end		
        tep_redirect(tep_href_link('modules_content.php', 'module=' . $class));

        break;

    case 'uninstall':
      $class = basename($_GET['module']);

      foreach ( $modules['installed'] as $m ) {
        if ( $m['code'] == $class ) {
          $module = new $class();

          $module->remove();

          $modules_installed = explode(';', MODULE_CONTENT_INSTALLED);

          if (in_array($m['group'] . '/' . $m['code'], $modules_installed)) {
            unset($modules_installed[array_search($m['group'] . '/' . $m['code'], $modules_installed)]);
          }

          tep_db_query("update " .  $multi_stores_config   . " set configuration_value = '" . implode(';', $modules_installed) . "' where configuration_key = 'MODULE_CONTENT_INSTALLED'");
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end	
          tep_redirect(tep_href_link('modules_content.php'));
        }
      }
// Configuration Cache modification start
      require('includes/configuration_cache.php');
// Configuration Cache modification end		
      tep_redirect(tep_href_link('modules_content.php', 'module=' . $class));

      break;
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
 
  $tab_block_bs .=  '    <!-- Begin Tab panes Modules Content -->' . PHP_EOL ;  
  $tab_block_bs .=  '    <div  id="tab_content_category" class="tab-content">'  . PHP_EOL;
 
  $active_tab = ' active' ; 
  foreach ($group_array as $group_item) { // Nivel 1 itera los grupos
    $group_count_installed = 0;
    $group_count_uninstalled = 0;
    $tab_block_bs_content .=  '       <!-- begin installed moules -->'  . PHP_EOL;		
    $tab_block_bs_content  =  '       <div class="panel panel-info">'  . PHP_EOL;  
    $tab_block_bs_content .=  '          <div class="panel-body">'  . PHP_EOL; 
    $tab_block_bs_content .=  '              <table class="table">' . PHP_EOL ;
    $tab_block_bs_content .=  '                 <thead>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_MODULES . TEXT_MODULE_INSTALLED . '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_DESCRIPTION .                     '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_SORT_ORDER .                      '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_ACTION .                          '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                 </thead>' . PHP_EOL ;
    $tab_block_bs_content .=  '                 <tbody>' . PHP_EOL ;

    foreach ( $modules['installed'] as $m ) { // Nivel 2 itera los módulos instalados
      $module = new $m['code']();
      if ($group_item == $module->group) {
        if ((isset($_GET['module']) && ($_GET['module'] == $module->code)))  { // agregar && ($action == 'configure')
          $module_info = array('code' => $module->code,
                               'title' => $module->title,
                               'description' => $module->description,
                               'signature' => (isset($module->signature) ? $module->signature : null),
                               'api_version' => (isset($module->api_version) ? $module->api_version : null),
                               'sort_order' => (int)$module->sort_order,
                               'keys' => array());

          foreach ($module->keys() as $key) {// Nivel 3 itera las claves del módulo actual de #2
            $key = tep_db_prepare_input($key);

            $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function, configuration_key from " .  $multi_stores_config   . " where configuration_key = '" . tep_db_input($key) . "'");
            $key_value = tep_db_fetch_array($key_value_query);
			
            if(defined('TITLE_'.$key_value['configuration_key'])){
               $key_value['configuration_title'] = CONSTANT('TITLE_'.$key_value['configuration_key']) . '</strong>' ; //<br />' . CONSTANT('DESCRIPTION_'.$key_value['configuration_key']) . '<br />';				   
               $key_value['configuration_description'] = CONSTANT('DESCRIPTION_'.$key_value['configuration_key']) ;
	        }		  			

            $module_info['keys'][$key] = array('title' => $key_value['configuration_title'],
                                               'value' => $key_value['configuration_value'],
                                               'description' => $key_value['configuration_description'],
                                               'use_function' => $key_value['use_function'],
                                               'set_function' => $key_value['set_function']);
          }
          $mInfo = new objectInfo($module_info);
        }

        if(method_exists($module, 'isEnabled')) {
          $module_enabled = $module->enabled ? 1 : 0;
        } else {
          $module_enabled = -1;
        }
      }

      if ($group_item == $module->group) {
        $group_count_installed ++;
//        $buttons= tep_draw_button ('Configure', 'gear', tep_href_link($PHP_SELF, 'action=configure&module=' . $module->code . '#section_' . $group_item));
        $buttons = '<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT, 'pencil', tep_href_link('modules_content.php', 'module=' . $module->code . '&action=configure'), null, 'warning') . '</div>' . PHP_EOL ;						  
                             
        if (($module_enabled ==1) && (method_exists($module, 'enable'))) {
//          $buttons .= tep_draw_button ('Disable', 'cancel', tep_href_link($PHP_SELF, 'action=disable&module=' . $module->code . '#section_' . $group_item));
          $buttons .=  '<div class="btn-group">' . tep_glyphicon_button(IMAGE_DISABLE, 'remove-circle', tep_href_link('modules_content.php', 'action=disable&module=' . $module->code ), null, 'danger') . '</div>' . PHP_EOL ;			  
        } elseif (($module_enabled ==0) && (method_exists($module, 'disable'))) {
//          $buttons .= tep_draw_button ('Enable', 'check', tep_href_link($PHP_SELF, 'action=enable&module=' . $module->code . '#section_' . $group_item));
          $buttons .= '<div class="btn-group">' . tep_glyphicon_button(IMAGE_ENABLE, 'ok-circle', tep_href_link($PHP_SELF, 'action=enable&module=' . $module->code ), null, 'success') . '</div>' . PHP_EOL ;			  
        }

//        $buttons .= tep_draw_button ('Uninstall', 'minus', tep_href_link($PHP_SELF, 'action=uninstall&module=' . $module->code . '#section_' . $group_item));
        $buttons .= '<div class="btn-group">' . tep_glyphicon_button(IMAGE_UNINSTALL, 'remove', tep_href_link('modules_content.php', 'action=uninstall&module=' . $module->code ), null, 'danger') . '</div>' . PHP_EOL ;	
		
        if (isset($mInfo) && is_object($mInfo) && ($module->code == $mInfo->code) ) {
          $tab_block_bs_content .=  '               <tr class="active">' . "\n";
        } else {
          $tab_block_bs_content .=  '               <tr onclick="document.location.href=\'' . tep_href_link('modules_content.php', 'module=' . $module->code) . '#section_' . $module->group . '\'">' . "\n";
        }

        $description = preg_replace('(<div\s+class="secWarning">.*?<\/div>)', '', $module->description);
        preg_match('(<div\s+class="secWarning">.*?<\/div>)', $module->description, $warning);

        if ($module_enabled ==0) {

//          $title = '<span><span class="ui-icon ui-icon-cancel" style="display: inline-block;"></span><del>' . $module->title . '</del></span>';
          $title = tep_glyphicon('ban-circle', 'warning') . '<span class="bg-warning">' . $module->title . '</span>' . PHP_EOL ;

          $sort  = TEXT_MODULE_DISABLED;
        } else {
//          $title = '<strong>' . $module->title . '</strong>';
          $title = $module->title;
          $sort = $module->sort_order;
        }
  
        $tab_block_bs_content .=  '                     <td>' . $title .         '</td>' . PHP_EOL ;
        $tab_block_bs_content .=  '                     <td>' . $description .   '</td>' . PHP_EOL ;
        $tab_block_bs_content .=  '                     <td>' . $sort.           '</td>' . PHP_EOL ;
        $tab_block_bs_content .=  '                     <td>' . $buttons .       '</td>' . PHP_EOL ;
		
             if ( (isset($mInfo) && is_object($mInfo)) && ($module->code == $mInfo->code) && (isset($HTTP_GET_VARS['action'])) ) {
 
               switch ($action) {
                   case 'configure': 
                      $keys = '';
 
                      foreach ($mInfo->keys as $key => $value) {
	  
                         $keys .= '<strong>' . $value['title'] . '</strong><br />' . $value['description'] . '<br />';

                         if ($value['set_function']) {
                            eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
                         } else {
                            $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']) ;
                         }

                         $keys .= '<br /><br />';
                      }

                      $keys = substr($keys, 0, strrpos($keys, '<br /><br />'));

                      $contents .=   tep_draw_form('modules', 'modules_content.php', 'module=' . $mInfo->code . '&action=save', 'post', 'role="form" enctype="multipart/form-data"');
                      $contents .= '   <div class="col-xs-12 col-sm-10 col-md-10">' . PHP_EOL;
                      $contents .= '   <p>' . $mInfo->configuration_description . '</p>' . PHP_EOL;
                      $contents .= '      ' . $keys . PHP_EOL;
                      $contents .= '   </div>' . PHP_EOL;
                      $contents .= '   <div class="col-xs-12 col-sm-2 col-md-2 text-right">' . PHP_EOL;
                      $contents .= '' .    tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '<br>' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link('modules_content.php', 'module=' . $mInfo->code), null, null, 'btn-default text-danger') . "\n";
                      $contents .= '    </div>' . PHP_EOL;
                      $contents .= ' </form>' . PHP_EOL;
                   break;   
      
        default:
        break;
      }	
	
      $tab_block_bs_content .= '                <tr class="content-row">' . "\n" .
           '                  <td colspan="4">' . "\n" .
		   '                    <div class="row">' . "\n" .
                                  $contents . 
           '                    </div>' . "\n" .
           '                  </td>' . "\n" .
           '                </tr>' . "\n";
    }  // END EDIT OR INFO		
        $tab_block_bs_content .=  '                  </tr>' . PHP_EOL ;
      }
	}
    if ($group_count_installed ==0) {
        $tab_block_bs_content .=  '                  <tr>' . PHP_EOL ;
        $tab_block_bs_content .=  '                      <td class="text-center">'. TEXT_MODULE_NO_MODULES . TEXT_MODULE_INSTALLED . '</td>' . PHP_EOL ;
        $tab_block_bs_content .=  '                  </tr>' . PHP_EOL ;
    }
    $tab_block_bs_content .=  '                 </tbody>'. PHP_EOL ;
    $tab_block_bs_content .=  '              </table>' . PHP_EOL ;
    $tab_block_bs_content .=  '       <!-- end installed moules -->'  . PHP_EOL;		
	// end installed modules
	// begin uninstalled
    $tab_block_bs_content .=  '       <!-- begin not installed moules -->'  . PHP_EOL;	
    $tab_block_bs_content .=  '              <table class="table">' . PHP_EOL ;
    $tab_block_bs_content .=  '                 <thead>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_MODULES . TEXT_MODULE_UNINSTALLED . '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_DESCRIPTION .                       '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_SORT_ORDER .                        '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                   <th>' . TABLE_HEADING_ACTION .                            '</th>'. PHP_EOL ;
    $tab_block_bs_content .=  '                 </thead>' . PHP_EOL ;
    $tab_block_bs_content .=  '                 <tbody>' . PHP_EOL ;	


    foreach ( $modules['new'] as $m ) { // itera los módulos no instalados
      $module = new $m['code']();
      if ($group_item == $module->group) {
        $group_count_uninstalled ++;
        if ((isset($_GET['module']) && ($_GET['module'] == $m->code))) {
          $module = new $m['code']();
          $module_info = array('code' => $module->code,
                               'title' => $module->title,
                               'description' => $module->description,
                               'signature' => (isset($module->signature) ? $module->signature : null),
                               'api_version' => (isset($module->api_version) ? $module->api_version : null),
                               'sort_order' => (int)$module->sort_order,
                               'keys' => array());
           $tab_block_bs_content .=  '<tr class="active">' . "\n";

        } else {
           $tab_block_bs_content .=  '<tr onclick="document.location.href=\'' . tep_href_link('modules_content.php', 'module=' . $module->code) . '#section_' . $module->group . '\'">' . "\n";
        }

//        $buttons = tep_draw_button ('Install', 'plus', tep_href_link($PHP_SELF, 'action=install&module=' . $module->code . '#section_' . $group_item));
        $buttons = '<div class="btn-group">' . tep_glyphicon_button(IMAGE_INSTAL, 'plus', tep_href_link('modules_content.php', 'module=' . $module->code . '&action=install'), null, 'warning') . '</div>' . PHP_EOL ;						  
		

        $description = preg_replace('(<div\s+class="secWarning">.*?<\/div>)', '', $module->description);
        preg_match('(<div\s+class="secWarning">.*?<\/div>)', $module->description, $warning);

        $tab_block_bs_content .=  '                     <td>' . $module->title . '</td>' . PHP_EOL ;
        $tab_block_bs_content .=  '                     <td>' . $description .   '</td>' . PHP_EOL ;
        $tab_block_bs_content .=  '                     <td>' . ''           .   '</td>' . PHP_EOL ;		
        $tab_block_bs_content .=  '                     <td>' . $buttons .       '</td>' . PHP_EOL ;
        $tab_block_bs_content .=  '                  </tr>' . PHP_EOL ;
 	  }
	}
    $tab_block_bs_content .=  '                 </tbody>'. PHP_EOL ;
    $tab_block_bs_content .=  '              </table>' . PHP_EOL ;	
    $tab_block_bs_content .=  '       <!-- end not installed moules -->'  . PHP_EOL;		
// end uninstalled

    $tab_block_bs_content .=  '          </div>' . PHP_EOL ;// end div panel  body
    $tab_block_bs_content .=  '       </div>' . PHP_EOL ; // end div panel	
	
    $tab_block_bs .=  '    <div role="tabpanel" class="tab-pane ' . $active_tab . '" id="tab_content_' . $group_item . '">' . $tab_block_bs_content .  '</div>' . PHP_EOL ;

    $active_tab = '' ;
  }	
  
 
  $tab_block_bs .=  '    </div>'. PHP_EOL ;   // end div tab_content 
  $tab_block_bs .=  '    <!-- End Tab panes Modules Content -->' . PHP_EOL ;   
  $tab_block_bs .=  '  </div>'. PHP_EOL ;   // end div tab_panel  
  $tab_block_bs .=  '  <!-- End Nav tabs Modules Content -->' . PHP_EOL ; 
  
?>
<?php echo $tab_block_bs ; ?>
<?php
/*
 <div id="contentmoduleTabs" style="overflow: auto;">
     <ul>
<?php echo $tab_block;?>
    </ul>
<?php
  foreach ($group_array as $group_item) { // Nivel 1 itera los grupos
    $group_count_installed = 0;
    $group_count_uninstalled = 0;
?>
    <div id="section_<?php echo $group_item;?>" style="padding: 10px;">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class ="dataTableHeadingRow">
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULES . TEXT_MODULE_INSTALLED; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
          <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
          <td class="dataTableHeadingContent" align="center" width="340"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
        </tr>
<?php

    foreach ( $modules['installed'] as $m ) { // Nivel 2 itera los módulos instalados
      $module = new $m['code']();
      if ($group_item == $module->group) {
        if ((isset($_GET['module']) && ($_GET['module'] == $module->code)))  { // agregar && ($action == 'configure')
          $module_info = array('code' => $module->code,
                               'title' => $module->title,
                               'description' => $module->description,
                               'signature' => (isset($module->signature) ? $module->signature : null),
                               'api_version' => (isset($module->api_version) ? $module->api_version : null),
                               'sort_order' => (int)$module->sort_order,
                               'keys' => array());

          foreach ($module->keys() as $key) {// Nivel 3 itera las claves del módulo actual de #2
            $key = tep_db_prepare_input($key);

            $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " .  $multi_stores_config   . " where configuration_key = '" . tep_db_input($key) . "'");
            $key_value = tep_db_fetch_array($key_value_query);

            $module_info['keys'][$key] = array('title' => $key_value['configuration_title'],
                                               'value' => $key_value['configuration_value'],
                                               'description' => $key_value['configuration_description'],
                                               'use_function' => $key_value['use_function'],
                                               'set_function' => $key_value['set_function']);
          }
          $mInfo = new objectInfo($module_info);
        }

        if(method_exists($module, 'isEnabled')) {
          $module_enabled = $module->enabled ? 1 : 0;
        } else {
          $module_enabled = -1;
        }
      }

      if ($group_item == $module->group) {
        $group_count_installed ++;
        $buttons= tep_draw_button ('Configure', 'gear', tep_href_link($PHP_SELF, 'action=configure&module=' . $module->code . '#section_' . $group_item));

        if (($module_enabled ==1) && (method_exists($module, 'enable'))) {
          $buttons .= tep_draw_button ('Disable', 'cancel', tep_href_link($PHP_SELF, 'action=disable&module=' . $module->code . '#section_' . $group_item));
        } elseif (($module_enabled ==0) && (method_exists($module, 'disable'))) {
          $buttons .= tep_draw_button ('Enable', 'check', tep_href_link($PHP_SELF, 'action=enable&module=' . $module->code . '#section_' . $group_item));
        }

        $buttons .= tep_draw_button ('Uninstall', 'minus', tep_href_link($PHP_SELF, 'action=uninstall&module=' . $module->code . '#section_' . $group_item));

        if (isset($mInfo) && is_object($mInfo) && ($module->code == $mInfo->code) ) {
          echo '        <tr id="defaultSelected" class="dataTableRowSelected ui-widget ui-state-focus" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
        } else {
          echo '        <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('modules_content.php', 'module=' . $module->code) . '#section_' . $module->group . '\'">' . "\n";
        }

        $description = preg_replace('(<div\s+class="secWarning">.*?<\/div>)', '', $module->description);
        preg_match('(<div\s+class="secWarning">.*?<\/div>)', $module->description, $warning);

        if ($module_enabled ==0) {

          $title = '<span><span class="ui-icon ui-icon-cancel" style="display: inline-block;"></span><del>' . $module->title . '</del></span>';
          $sort = TEXT_MODULE_DISABLED;
        } else {
//          $title = '<strong>' . $module->title . '</strong>';
          $title = $module->title;
          $sort = $module->sort_order;
        }
  ?>
          <td class="dataTableContent"><?php echo $title ; ?></td>
          <td class="dataTableContent"><?php echo $description; ?></td>
          <td class="dataTableContent" align="center"><?php echo $sort; ?></td>
          <td class="dataTableContent" align="center"><?php echo $buttons; ?></td>
        </tr>
<?php

        if ($action == 'configure' && $mInfo->keys && $module->code == $mInfo->code) {// NO ES NECEASARIO agregar && ($group_item == $module->group)
          $keys = '';
?>
      <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
        <td colspan="4">
<?php


          if ($warning[0]) echo '          ' . $warning[0] ."\n";
echo tep_draw_form('modules', basename($_SERVER['PHP_SELF']), 'module=' . $mInfo->code . '&action=save#section_' . $module->group) . "\n";
          foreach ($mInfo->keys as $key => $value) {
?>
          <div class="float_div">
          <div data-key="sameHeights" class="inner_div">
            <span><strong><?php echo $value['title'];?>: </strong><br /><?php echo $value['description'];?></span><br />
<?php
// tep_draw_form


            if ($value['set_function']) {
              $nobreak = preg_replace ('/(tep_cfg_select_option\()/','tep_cfg_select_option_responsive(', $value['set_function']);
              eval( "echo '            ' . " . $nobreak . "'" . $value['value'] . "', '" . $key . "');");
              echo "\n";
            } else {
              echo '            ' . tep_draw_input_field_responsive('configuration[' . $key . ']', $value['value']) ."\n";
          }

?>
          </div>
          </div>
<?php
      }
?>


<div class="buttons_div"><?php echo tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary');?><?php echo tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('modules_content.php', 'module=' . $mInfo->code));?>          </div>
          </form>
<?php
    }
    if (($action == 'configure') && ($module->code == $mInfo->code) ) {
?>
        </td>
      </tr>
<?php
        }
      }
    }
    if ($group_count_installed ==0) {
?>
      <td class="dataTableContent" colspan="4"><?php echo TEXT_MODULE_NO_MODULES . TEXT_MODULE_INSTALLED; ?></td>
<?php
    }
?>

        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULES . TEXT_MODULE_UNINSTALLED; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
          <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
          <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
        </tr>
<?php

    foreach ( $modules['new'] as $m ) { // itera los módulos no instalados
      $module = new $m['code']();
      if ($group_item == $module->group) {
        $group_count_uninstalled ++;
        if ((isset($_GET['module']) && ($_GET['module'] == $m->code))) {
          $module = new $m['code']();
          $module_info = array('code' => $module->code,
                               'title' => $module->title,
                               'description' => $module->description,
                               'signature' => (isset($module->signature) ? $module->signature : null),
                               'api_version' => (isset($module->api_version) ? $module->api_version : null),
                               'sort_order' => (int)$module->sort_order,
                               'keys' => array());
          echo '      <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";

        } else {
          echo '      <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('modules_content.php', 'module=' . $module->code) . '#section_' . $module->group . '\'">' . "\n";
        }

        $buttons = tep_draw_button ('Install', 'plus', tep_href_link($PHP_SELF, 'action=install&module=' . $module->code . '#section_' . $group_item));

        $description = preg_replace('(<div\s+class="secWarning">.*?<\/div>)', '', $module->description);
        preg_match('(<div\s+class="secWarning">.*?<\/div>)', $module->description, $warning);
?>
      <td class="dataTableContent"><?php echo $module->title; ?></td>
      <td class="dataTableContent" align="center"><?php echo $description;?></td>
      <td class="dataTableContent" colspan="2" align="center"><?php echo $buttons; ?></td>
    </tr>
<?php
      }
    }
    if ($group_count_uninstalled ==0) {
?>
      <td class="dataTableContent" colspan="4"><?php echo TEXT_MODULE_NO_MODULES . TEXT_MODULE_UNINSTALLED; ?></td>
<?php
    }
?>
      </table>
    </div>
<?php
  }
  */
?>

    </div>
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText bg-info"><?php echo TEXT_MODULE_DIRECTORY . ' ' . DIR_FS_CATALOG_MODULES . 'content/'; ?></td>
          </tr>
     </table>

<?php

////
// Output a form input field - modified for responsiveness and jquery UI
  function tep_draw_input_field_responsive($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) {
    global $_GET, $HTTP_POST_VARS;

    $field = '<input class="ui-widget ui-widget-content ui-corner-all" type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ( ($reinsert_value == true) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $value = stripslashes($_GET[$name]);
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $value = stripslashes($HTTP_POST_VARS[$name]);
      }
    }

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />' . "\n";

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Alias function for Store configuration values in the Administration Tool
  function tep_cfg_select_option_responsive($select_array, $key_value, $key = '') {
    $string = '';

    for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
      $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');

      $string .= '<input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';

      if ($key_value == $select_array[$i]) $string .= ' checked="checked"';

      $string .= ' />' . $select_array[$i] . '&nbsp;&nbsp;';
    }

    return $string;
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
