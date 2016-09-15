<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir(DIR_FS_CATALOG_MODULES . 'action_recorder/')) {
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_CATALOG_MODULES . 'action_recorder/' . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

  for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
    $file = $directory_array[$i];

    if (file_exists(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/action_recorder/' . $file)) {
      include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/action_recorder/' . $file);
    }

    include(DIR_FS_CATALOG_MODULES . 'action_recorder/' . $file);

    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
      ${$class} = new $class;
    }
  }

  $modules_array = array();
  $modules_list_array = array(array('id' => '', 'text' => TEXT_ALL_MODULES));

  $modules_query = tep_db_query("select distinct module from " . TABLE_ACTION_RECORDER . " order by module");
  while ($modules = tep_db_fetch_array($modules_query)) {
    $modules_array[] = $modules['module'];

    $modules_list_array[] = array('id' => $modules['module'],
                                  'text' => (is_object(${$modules['module']}) ? ${$modules['module']}->title : $modules['module']));
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'expire':
        $expired_entries = 0;

        if (isset($HTTP_GET_VARS['module']) && in_array($HTTP_GET_VARS['module'], $modules_array)) {
          if (is_object(${$HTTP_GET_VARS['module']})) {
            $expired_entries += ${$HTTP_GET_VARS['module']}->expireEntries();
          } else {
            $delete_query = tep_db_query("delete from " . TABLE_ACTION_RECORDER . " where module = '" . tep_db_input($HTTP_GET_VARS['module']) . "'");
//              $expired_entries += mysql_affected_rows($db_link); // 2.3.3.2
//           $expired_entries += tep_db_affected_rows($db_link); // 2.3.3.2
           $expired_entries += tep_db_affected_rows($db_link); // 2.3.3.3

          }
        } else {
          foreach ($modules_array as $module) {
            if (is_object(${$module})) {
              $expired_entries += ${$module}->expireEntries();
            }
          }
        }

        $messageStack->add_session(sprintf(SUCCESS_EXPIRED_ENTRIES, $expired_entries), 'success');

        tep_redirect(tep_href_link(FILENAME_ACTION_RECORDER));

        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1>
            <div class="col-md-6">
              <div class="row">              
<?php
  echo '                ' . tep_draw_form('search', FILENAME_ACTION_RECORDER, '', 'get', 'class="col-sm-6 col-md-6"') . "\n" .
       '                  <label class="sr-only" for="search">' . TEXT_FILTER_SEARCH . '</label>' . "\n" .  
       '                  ' . tep_draw_input_field('search','', 'placeholder="' . TEXT_FILTER_SEARCH . '"') . tep_hide_session_id() . "\n" .
	   '                </form>' . "\n";
	   
  echo '                ' . tep_draw_form('filter', FILENAME_ACTION_RECORDER, '', 'get', 'class="col-sm-6 col-md-6"') . "\n" .
       '                  <label class="sr-only" for="module">' . TEXT_FILTER_SEARCH . '</label>' . "\n" .  
       '                  ' . tep_draw_pull_down_menu('module', $modules_list_array, null, 'onchange="this.form.submit();"') . tep_draw_hidden_field('search') . tep_hide_session_id() . "\n" .
	   '                </form>' . "\n";
?>
              </div>
            </div>
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">			  
            <table class="table table-condensed">
              <thead>
                <tr class="heading-row">
                <th width="20">&nbsp;</th>
                <th><?php echo TABLE_HEADING_MODULE; ?></th>
                <th><?php echo TABLE_HEADING_CUSTOMER; ?></th>
                <th align="right"><?php echo TABLE_HEADING_DATE_ADDED; ?></th>
                <th align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                </tr>
              </thead>
              <tbody>    
<?php
  $filter = array();

  if (isset($HTTP_GET_VARS['module']) && in_array($HTTP_GET_VARS['module'], $modules_array)) {
    $filter[] = " module = '" . tep_db_input($HTTP_GET_VARS['module']) . "' ";
  }

  if (isset($HTTP_GET_VARS['search']) && !empty($HTTP_GET_VARS['search'])) {
    $filter[] = " identifier like '%" . tep_db_input($HTTP_GET_VARS['search']) . "%' ";
  }

  $actions_query_raw = "select * from " . TABLE_ACTION_RECORDER . (!empty($filter) ? " where " . implode(" and ", $filter) : "") . " order by date_added desc";
  $actions_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $actions_query_raw, $actions_query_numrows);
  $actions_query = tep_db_query($actions_query_raw);
  while ($actions = tep_db_fetch_array($actions_query)) {
    $module = $actions['module'];

    $module_title = $actions['module'];
    if (is_object(${$module})) {
      $module_title = ${$module}->title;
    }

    if ((!isset($HTTP_GET_VARS['aID']) || (isset($HTTP_GET_VARS['aID']) && ($HTTP_GET_VARS['aID'] == $actions['id']))) && !isset($aInfo)) {
      $actions_extra_query = tep_db_query("select identifier from " . TABLE_ACTION_RECORDER . " where id = '" . (int)$actions['id'] . "'");
      $actions_extra = tep_db_fetch_array($actions_extra_query);

      $aInfo_array = array_merge($actions, $actions_extra, array('module' => $module_title));
      $aInfo = new objectInfo($aInfo_array);
    }

    if ( (isset($aInfo) && is_object($aInfo)) && ($actions['id'] == $aInfo->id) ) {
//      echo '                  <tr id="defaultSelected" class="ui-state-active" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
//    } else {
//      echo '              <tr class="ui-state-default" onmouseover="this.className=\'ui-state-hover\';this.style.cursor=\'hand\'" onmouseout="this.className=\'ui-state-default\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ACTION_RECORDER, tep_get_all_get_params(array('aID')) . 'aID=' . $actions['id']) . '\'">' . "\n";
//    }

       echo '                <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_ACTION_RECORDER, tep_get_all_get_params(array('aID')) . 'aID=' . $actions['id']) . '\'">' . "\n";	
      } else {
        echo '                <tr onclick="document.location.href=\'' . tep_href_link(FILENAME_ACTION_RECORDER, tep_get_all_get_params(array('aID')) . 'aID=' . $actions['id']) . '\'">' . "\n";
      }	
?>
                <td align="center"><?php echo (($actions['success'] == '1') ? tep_glyphicon('ok-sign', 'success' ) : tep_glyphicon('remove-sign', 'danger' )); ?></td>
                <td><?php echo $module_title; ?></td>
                <td><?php echo tep_output_string_protected($actions['user_name']) . ' [' . (int)$actions['user_id'] . ']'; ?></td>
                <td align="right"><?php echo tep_datetime_short($actions['date_added']); ?></td>
                <td><div class="btn-toolbar" role="toolbar">                  
<?php
      echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO, 'info-sign', tep_href_link(FILENAME_ACTION_RECORDER, tep_get_all_get_params(array('aID')) . 'aID=' . $actions['id'] . '&action=info' ), null, 'info') . '</div>' . PHP_EOL ;
?>

                    </div>
			    </td>
              </tr>			  
<?php
      if (isset($aInfo) && is_object($aInfo) && ( (isset($aInfo) && is_object($aInfo)) && ($actions['id'] == $aInfo->id) ) && isset($HTTP_GET_VARS['action'])) { 
			$contents_heading .= '       <div class="panel panel-primary">' . PHP_EOL ;
			$contents_heading .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</div>' . PHP_EOL;
			$contents_heading .= '          <div class="panel-body">' . PHP_EOL;			
			$contents_heading .= '                      ' . tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'class="form-horizontal" role="form" enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id) . PHP_EOL;		
			$contents_heading .= '                          <p>' . TEXT_EDIT_INTRO . '</p>' . PHP_EOL;			
            $contents_heading .= '                        <div class="col-xs-12 col-sm-10 col-md-10">' . PHP_EOL;
	  
	    $alertClass = '';
        switch ($action) {
          		
		  default:
			$contents .= '                    <br /><div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL;
			$contents .= '                        <ul class="list-group">' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                              ' . TEXT_INFO_IDENTIFIER . PHP_EOL;			
			$contents .= '                            <a href="' .tep_href_link(FILENAME_ACTION_RECORDER, 'search=' . $aInfo->identifier) . '">' . tep_output_string_protected($aInfo->identifier) . '' . PHP_EOL;			
			$contents .= '                          </li>' . PHP_EOL;
			$contents .= '                        </ul>' . PHP_EOL;
			$contents .= '                      </div>' . PHP_EOL;
			$contents .= '                      <div class="col-xs-12 col-sm-5 col-md-4">' . PHP_EOL;
			$contents .= '                        <ul class="list-group">' . PHP_EOL;
			$contents .= '                          <li class="list-group-item">' . PHP_EOL;
			$contents .= '                              ' . TEXT_INFO_DATE_ADDED . PHP_EOL;			
			$contents .= '                            ' . tep_datetime_short($aInfo->date_added) . '' . PHP_EOL;			
			$contents .= '                          </li>' . PHP_EOL;
 			$contents .= '                        </ul>' . PHP_EOL;
			$contents .= '                      </div>' . PHP_EOL;
          break;
        }
		
        $contents_footer .= '                      </div>' . PHP_EOL;	
        $contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_ACTION_RECORDER ), null, null, 'btn-default text-danger') . PHP_EOL;
			
		$contents_footer .= '                      </form>' . PHP_EOL;
		$contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		$contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
		
		$contents .=  '</div>' . PHP_EOL ;
		$contents .=  $contents_sv_cncl  . PHP_EOL;
        $contents .=  $contents_footer  . PHP_EOL;			

        echo '                <tr class="content-row">' . PHP_EOL .
             '                  <td colspan="4">' . PHP_EOL .
             '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                    $contents . 
             '                    </div>' . PHP_EOL .
             '                  </td>' . PHP_EOL .
             '                </tr>' . PHP_EOL;
      }			  
    
  }
?>
             </tbody>
           </table>
		 </div>
    </table>		
        <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText hidden-xs" valign="top"><?php echo $actions_split->display_count($actions_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ENTRIES); ?></td>
            <td class="smallText" style="text-align: right;"><?php echo $actions_split->display_links($actions_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], (isset($_GET['module']) && in_array($_GET['module'], $modules_array) && is_object(${$_GET['module']}) ? 'module=' . $_GET['module'] : null) . '&' . (isset($_GET['search']) && !empty($_GET['search']) ? 'search=' . $_GET['search'] : null)); ?></td>
          </tr>
		  <tr>
		   <td> <?php echo tep_draw_bs_button(IMAGE_DELETE, 'trash', tep_href_link(FILENAME_ACTION_RECORDER, 'action=expire' . (isset($HTTP_GET_VARS['module']) && in_array($HTTP_GET_VARS['module'], $modules_array) ? '&module=' . $HTTP_GET_VARS['module'] : '')), 'primary'); ?></td>
		  </tr>

        </table>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>