<?php
/*
  $Id: stores.php,v 1.0 2004/08/23 22:50:52 rmh Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['storeID'])) {
            tep_set_store_status($HTTP_GET_VARS['storeID'], $HTTP_GET_VARS['flag']);
          }
        }
        tep_redirect(tep_href_link(FILENAME_STORES, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'storeID=' . $HTTP_GET_VARS['storeID']));
        break;
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['storeID'])) $stores_id = tep_db_prepare_input($HTTP_GET_VARS['storeID']);
        $stores_name = tep_db_prepare_input($HTTP_POST_VARS['stores_name']);
        $stores_config_table = tep_db_prepare_input($HTTP_POST_VARS['stores_config_table']);
        $error = false;
        $entry_stores_name_error = false;
        $entry_stores_config_table_error = false;
        $entry_stores_config_table_exists_error = false;
        $entry_stores_config_table_unchanged = false;

        if (!tep_not_null($stores_name)) {
          $error = true;
          $entry_stores_name_error = true;
        }

        $check_config_table_query = tep_db_query("select stores_config_table from " . TABLE_STORES . " where stores_id = '" . (int)$stores_id . "'");
        $check_config_table = tep_db_fetch_array($check_config_table_query);
        if (!tep_not_null($stores_config_table)) {
          $error = true;
          $entry_stores_config_table_error = true;
        } else if (tep_table_exists($stores_config_table) == true) {
            if ($check_config_table['stores_config_table'] != $stores_config_table) {
              $error = true;
              $entry_stores_config_table_exists_error = true;
            } else {
              $entry_stores_config_table_unchanged = true;
            }
        }

        if (($error == false) && ($entry_stores_config_table_exists_error == false)) {
          $sql_data_array = array('stores_name' => tep_db_prepare_input($HTTP_POST_VARS['stores_name']),
                                  'stores_url'  => tep_db_prepare_input($HTTP_POST_VARS['stores_url']),
                                  'stores_absolute'  => tep_db_prepare_input($HTTP_POST_VARS['stores_absolute']),								  
                                  'stores_config_table'  => tep_db_prepare_input($HTTP_POST_VARS['stores_config_table']),
                                  'stores_status' => tep_db_prepare_input($HTTP_POST_VARS['stores_status']),
                                  'stores_std_cust_group' => tep_db_prepare_input($HTTP_POST_VARS['stores_std_cust_group']),
                                  'stores_admin_color' => tep_db_prepare_input($HTTP_POST_VARS['stores_admin_color'])								  
								  );

          if ($action == 'insert') {
            $sql_data_array['date_added'] = 'now()';

            tep_db_perform(TABLE_STORES, $sql_data_array);
            $stores_id = tep_db_insert_id();

//            if (isset($HTTP_POST_VARS['insert_table']) && ($HTTP_POST_VARS['insert_table'] == 'on')) {
              tep_db_query("CREATE TABLE " . $stores_config_table . " LIKE " . TABLE_CONFIGURATION_STD );
              tep_db_query("INSERT INTO "  . $stores_config_table . " SELECT * from " . TABLE_CONFIGURATION_STD );			  

 //rmh M-S_multi-stores begin
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website URL', 'HTTP_CATALOG_SERVER', '" . tep_db_prepare_input($HTTP_POST_VARS['stores_url']) . "', 'The URL for your stores catalog (eg. http://www.domain.com)', 8600, 10, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website SSL URL', 'HTTPS_CATALOG_SERVER', '', 'The SSL URL for your stores catalog (eg. https://www.domain.com)', 8600, 20, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Enable SSL Store Catalog', 'ENABLE_SSL_CATALOG', 'false', 'Enable SSL links for Store Catalog', 8600, 30, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),')");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website Path', 'DIR_WS_CATALOG', '/', 'Directory Website Path for Store Catalog (absolute path required -- eg. /catalog/)', 8600, 40, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Path', 'DIR_FS_CATALOG', '" . osc_realpath(dirname(__FILE__) . '/../') . "/', 'Directory Filesystem Path for Store Catalog (absolute path required -- eg. /home/user/public_html/catalog/)', 8600, 50, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website Images Path', 'DIR_WS_CATALOG_IMAGES', '" . tep_db_prepare_input($HTTP_POST_VARS['stores_url']) . "/images/', 'Store Catalog Website Images Path (with trailing slash -- eg. http://www.domain.com/catalog/images/)', 8600, 60, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website Languages Path', 'DIR_WS_CATALOG_LANGUAGES', '" . tep_db_prepare_input($HTTP_POST_VARS['stores_url']) . "/includes/languages/', 'Store Catalog Website Languages Path (with trailing slash -- eg. http://www.domain.com/catalog/includes/languages/)', 8600, 70, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Languages Path', 'DIR_FS_CATALOG_LANGUAGES', '', 'Store Catalog Filesystem Languages Path (with trailing slash -- eg. /home/user/public_html/catalog/includes/languages/)', 8600, 80, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Images Path', 'DIR_FS_CATALOG_IMAGES', '', 'Store Catalog Filesystem Images Path (with trailing slash -- eg. /home/user/public_html/catalog/images/)', 8600, 90, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Modules Path', 'DIR_FS_CATALOG_MODULES', '', 'Store Catalog Filesystem Modules Path (with trailing slash -- eg. /home/user/public_html/catalog/includes/modules/)', 8600, 100, now(), now(), NULL, NULL)");
              tep_db_query("INSERT INTO " .$stores_config_table . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stores ID', 'SYS_STORES_ID', '" . $stores_id . "', 'The id of my store', '6', '0', now())"); //rmh M-S_multi-stores			  
//rmh M-S_multi-stores end
//            }
              $languages_query = tep_db_query("select languages_id from " . TABLE_LANGUAGES . " order by languages_id");
              while ($lang_lang = tep_db_fetch_array($languages_query)) {
                  $lang_to_stores = $lang_lang['languages_id'] ;
			 
		          // check if store is already defined
			      $query = tep_db_query("SELECT language_id FROM ". TABLE_EMAIL_ORDER_TEXT ." WHERE stores_id = '" . $stores_id . "' and language_id = '" . (int)$email_to_stores . "'");
                  if ( tep_db_num_rows( $query ) > 0 ) {
			         // present
			      } else { // add email text
                  

                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWCUSTOMER           . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWCUSTOMER             . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN     . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWORDER              . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWORDER                . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWORDER_ADMIN        . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')"); 
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWORDER_ADMIN          . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_ORDER_UPDATE          . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_ORDER_UPDATE            . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN     . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_PASSWORDFORGOTTEN       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_CREATE_REVIEW         . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_CREATE_REVIEW		     . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_CONTACT_US            . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_CONTACT_US   		     . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");	

                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_WISHLIST                           . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_WISHLIST                             . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER               . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')"); 
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER                 . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER             . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER               . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_WISHLIST_ADMIN                     . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_WISHLIST_ADMIN                       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER_ADMIN         . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER_ADMIN		      . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
                  tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN   	  . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");							  
                 }			   
              }		

          } elseif ($action == 'save') {
            if (($entry_stores_config_table_unchanged == false) && (tep_table_exists($stores_config_table) == false)) {
              tep_db_query("RENAME TABLE " . $check_config_table['stores_config_table'] . " TO " . tep_db_prepare_input($HTTP_POST_VARS['stores_config_table']));
              $messageStack->add_session(TEXT_UPDATE_WARNING_CONFIG, 'warning');
            }

            $sql_data_array['last_modified'] = 'now()';

            tep_db_perform(TABLE_STORES, $sql_data_array, 'update', "stores_id = '" . (int)$stores_id . "'");

          }  
          tep_session_unregister('multi_stores');
//          tep_session_unregister('multi_stores_id');
//          tep_session_unregister('multi_stores_config');	  
//          tep_session_unregister('multi_stores_url');	  	  
//          tep_session_unregister('multi_stores_absolute');	  	  
//          tep_session_unregister('multi_stores_std_cust_group');	  
//          tep_session_unregister('multi_stores_admin_color');	  		  

//          if ($stores_image = new upload('stores_image', DIR_FS_CATALOG_IMAGES)) {
//            tep_db_query("update " . TABLE_STORES . " set stores_image = '" . $stores_image->filename . "' where stores_id = '" . (int)$stores_id . "'");
//          }
          tep_redirect(tep_href_link(FILENAME_STORES, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'storeID=' . $stores_id));

        } else if ($error == true) {
          if (($entry_stores_name_error == true) OR ($entry_stores_config_table_error == true)) $messageStack->add_session(ERROR_STORES_NAME_CONFIG, 'error');
          if ($entry_stores_config_table_exists_error == true) $messageStack->add_session(ERROR_STORES_CONFIG_TABLE_EXISTS, 'error');
          tep_redirect(tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page']));
        }
        break;
      case 'deleteconfirm':
        $stores_id = tep_db_prepare_input($HTTP_GET_VARS['storeID']);

        if ($stores_id == '1') {
          $messageStack->add(ERROR_DEFAULT_STORE, 'error');
          break;
        }
        $store_query = tep_db_query("select stores_image, stores_config_table from " . TABLE_STORES . " where stores_id = '" . (int)$stores_id . "'");
        $store = tep_db_fetch_array($store_query);

//        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
//          $image_location = DIR_FS_CATALOG_IMAGES . $store['stores_image']; //rmh M-S_fixes
//
//          if (file_exists($image_location)) @unlink($image_location);
//        }
//        if (isset($_POST['delete_table']) && ($_POST['delete_table'] == 'on')) {
          tep_db_query("DROP TABLE IF EXISTS " . $store['stores_config_table']);
//        }

        tep_db_query("delete from " . TABLE_STORES . " where stores_id = '" . (int)$stores_id . "'");
//        tep_db_query("delete from " . TABLE_PRODUCTS_TO_STORES . " where stores_id = '" . (int)$stores_id . "'");
//        tep_db_query("delete from " . TABLE_CATEGORIES_TO_STORES . " where stores_id = '" . (int)$stores_id . "'");
        tep_db_query("update "      . TABLE_SPECIALS . " set stores_id = '0' where stores_id = '" . (int)$stores_id . "'");
//        tep_db_query("delete from " . TABLE_MANUFACTURERS_TO_STORES . " where stores_id = '" . (int)$stores_id . "'");
		// delete email text
        tep_db_query("delete from " . TABLE_EMAIL_ORDER_TEXT . " where stores_id = '" . (int)$stores_id . "'");	

        tep_redirect(tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
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
                   <th><?php echo TABLE_HEADING_STORES; ?></th>
                   <th><?php echo TABLE_HEADING_STATUS; ?></th>				                       			   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>
                </tr>
              </thead>
              <tbody>
<?php
				  $stores_query_raw = "select stores_id, stores_name, stores_image, stores_url, stores_absolute, stores_config_table, stores_status, stores_std_cust_group, date_added, last_modified, stores_admin_color from " . TABLE_STORES . " order by stores_id";
				  $stores_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $stores_query_raw, $stores_query_numrows);
				  $stores_query = tep_db_query($stores_query_raw);
				  while ($stores = tep_db_fetch_array($stores_query)) {
					if ((!isset($HTTP_GET_VARS['storeID']) || (isset($HTTP_GET_VARS['storeID']) && ($HTTP_GET_VARS['storeID'] == $stores['stores_id']))) && !isset($storeInfo) && (substr($action, 0, 3) != 'new')) {
					  $storeInfo = new objectInfo($stores);					  
					}

					if (isset($storeInfo) && is_object($storeInfo) && ($stores['stores_id'] == $storeInfo->stores_id)) {
					  echo '              <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] . '&storeID=' . $stores['stores_id'] . '&action=edit') . '\'">' . "\n";
					} else {
					  echo '              <tr                onclick="document.location.href=\'' . tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] . '&storeID=' . $stores['stores_id']) . '\'">' . "\n";
					  
					}
?>
									<td><?php echo $stores['stores_name'] ; ?></td>
									<td>
<?php
                                       if ($stores['stores_status'] == '1') {
                                          echo  tep_glyphicon('ok-sign', 'success') . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STORES, 'action=setflag&flag=0&storeID=' . $stores['stores_id']) . '">' . tep_glyphicon('remove-sign', 'muted') . '</a>' . "\n";
										   
                                       } else {
                                         echo '                    <a href="' . tep_href_link(FILENAME_STORES, 'action=setflag&flag=1&storeID=' . $stores['stores_id']) . '">' . tep_glyphicon('ok-sign', 'muted') . '</a>&nbsp;&nbsp;' . tep_glyphicon('remove-sign', 'danger') . "\n";
                                       }
?>
                                    </td>
                                           
                              <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_STORES, 'storeID=' . $stores['stores_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_STORES, 'storeID=' . $stores['stores_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_STORES, 'storeID=' . $stores['stores_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL   ;
?>
                                   </div> 
				              </td>											
                     </tr>
<?php 
                
					  
                  if (isset($storeInfo) && is_object($storeInfo) && ($stores['stores_id'] == $storeInfo->stores_id) && isset($HTTP_GET_VARS['action'])) { 
			  
 	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_HEADING_DELETE_STORE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('stores', FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] . '&storeID=' . $storeInfo->stores_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $storeInfo->stores_name   . '</p>' . PHP_EOL;
										
                                       if ($storeInfo->products_count > 0) {
                                          $contents .=                         '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $storeInfo->products_count);
                                       }										
									   
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] . '&storeID=' . $storeInfo->stores_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_HEADING_EDIT_STORE . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('stores', FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] . '&storeID=' . $storeInfo->stores_id . '&action=save', 'post', 'class="form-horizontal" role="form"' , 'id_edit_stores') . PHP_EOL;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
			                           $contents            .= '               ' .         tep_draw_hidden_field('stores_status', $storeInfo->stores_status) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_name',           $storeInfo->stores_name,          TEXT_STORES_NAME,          'id_stores_name' ,           'col-xs-3', 'col-xs-9', 'left', TEXT_STORES_NAME,         '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_url',            $storeInfo->stores_url,           TEXT_STORES_URL,           'id_stores_url' ,            'col-xs-3', 'col-xs-9', 'left', TEXT_STORES_URL,          '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_absolute',       $storeInfo->stores_absolute,      TEXT_STORES_ABSOLUTE,      'id_stores_absolute' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_STORES_ABSOLUTE,     '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_config_table',   $storeInfo->stores_config_table,  TEXT_STORES_CONFIG_TABLE,  'id_stores_config_table' ,   'col-xs-3', 'col-xs-9', 'left', TEXT_STORES_CONFIG_TABLE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;										   
									   
									   $index = 0;
									   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
									   while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
									        $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
										    ++$index;
									   }
 									   

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('stores_std_cust_group', $existing_customers_array, $storeInfo->stores_std_cust_group, TEXT_STORES_CUSTOMERS_GROUP_NAME, 'id_stores_std_cust_group', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left') . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;										   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('stores_admin_color', tep_return_themes(), $storeInfo->stores_admin_color, TEXT_STORES_ADMIN_COLOR, 'id_stores_std_admin_color', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left') . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;											   

					   
									   $contents            .= '                       <br />' . PHP_EOL;	                                  

 
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] . '&storeID=' . $storeInfo->stores_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
									    $customer_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id=" . $storeInfo->stores_std_cust_group . " order by customers_group_id ");									
										$customer_group_name =  tep_db_fetch_array($customer_group_query) ;										
										
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $aInfo->user_name  . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_STORES_NAME . ' : ' .      $storeInfo->stores_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_STORES_URL . ' : ' .       $storeInfo->stores_url . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_STORES_ABSOLUTE . ' : ' .  $storeInfo->stores_absolute . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
										if ($storeInfo->products_count > 0) {
                                           $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                               $contents .= '                              ' . TEXT_PRODUCTS . ' : ' . $storeInfo->products_count . PHP_EOL;
			                               $contents .= '                          </li>' . PHP_EOL;					
										}
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;	        
		
//			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;										
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;										
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_STORES_CONFIG_TABLE . ' : ' .               $storeInfo->stores_config_table . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_STORES_CUSTOMERS_GROUP_NAME . ' :  ' .       $customer_group_name['customers_group_name']  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_STORES_ABSOLUTE . ' : ' .  $storeInfo->stores_absolute . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        if (tep_not_null($storeInfo->last_modified)) {
                                           $contents .= '                        <li class="list-group-item">' . PHP_EOL;		
										}
			                            $contents .= '                              ' . TEXT_DATE_ADDED . ' : ' . tep_date_short($storeInfo->last_modified) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;										
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
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
								  
                              }		// end if assets						  						 
				} // end while ($customers = tep_db_fetch_array($cus				
?>			  
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <div>
             <div class="mark text-left  col-xs-12 col-md-6"><?php echo $stores_split->display_count($stores_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_STORES); ?></div>
             <div class="mark text-left  col-xs-12 col-md-6"><?php echo $stores_split->display_links($stores_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']) ; ?></div>	   
		  </div>
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_INSERT, 'plus', null,'data-toggle="modal" data-target="#new_store"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  

    </table>	
       <div class="modal fade"  id="new_store" role="dialog" aria-labelledby="new_store" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('stores', FILENAME_STORES, 'action=insert', 'post', 'autocomplete="off"') ; ?>
              
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_HEADING_NEW_STORE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
                                       $contents             = '' ;
									   $contents_footer      = '' ;

			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_NEW_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' .  PHP_EOL;	
 	                                   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
			                           $contents            .= '               ' .         tep_draw_hidden_field('stores_status', $storeInfo->stores_status) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_name',           null,  TEXT_STORES_NAME,          'id_stores_name' ,           'col-xs-12', 'col-xs-12', 'left', TEXT_STORES_NAME,         '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   $contents            .= '                       <br />' . PHP_EOL;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_url',            null,  TEXT_STORES_URL,           'id_stores_url' ,            'col-xs-12', 'col-xs-12', 'left', TEXT_STORES_URL,          '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   $contents            .= '                       <br />' . PHP_EOL;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_absolute',       null,  TEXT_STORES_ABSOLUTE,      'id_stores_absolute' ,       'col-xs-12', 'col-xs-12', 'left', TEXT_STORES_ABSOLUTE,     '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   $contents            .= '                       <br />' . PHP_EOL;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('stores_config_table',   null,  TEXT_STORES_CONFIG_TABLE,  'id_stores_config_table' ,   'col-xs-12', 'col-xs-12', 'left', TEXT_STORES_CONFIG_TABLE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;										   
									   $contents            .= '                       <br />' . PHP_EOL;									   
									   
									   $index = 0;
									   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
									   while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
									        $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
										    ++$index;
									   }
 									   
//		                               $contents            .= '              </div>' . PHP_EOL; // end div 	panel body
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('stores_std_cust_group', $existing_customers_array, null, TEXT_STORES_CUSTOMERS_GROUP_NAME, 'id_stores_std_cust_group', 'col-xs-12', ' selectpicker show-tick ', 'col-xs-12', 'left') . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;		
									   $contents            .= '                       <br />' . PHP_EOL;	

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('stores_admin_color', tep_return_themes(), 'default', TEXT_STORES_ADMIN_COLOR, 'id_stores_std_admin_color', 'col-xs-12', ' selectpicker show-tick ', 'col-xs-12', 'left') . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;													   
 
		                               $contents            .= '          </div>' . PHP_EOL; // end div 	panel body
		                               $contents            .= '       </div>' . PHP_EOL; // end div 	panel	
?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_STORES, 'page=' . $HTTP_GET_VARS['page'] . '&storeID=' . $storeInfo->stores_id)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_language -->

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>