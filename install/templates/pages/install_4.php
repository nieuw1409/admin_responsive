<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('../includes/database_tables.php');

  osc_db_connect(trim($_POST['DB_SERVER']), trim($_POST['DB_SERVER_USERNAME']), trim($_POST['DB_SERVER_PASSWORD']));
  osc_db_select_db(trim($_POST['DB_DATABASE']));

  osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_NAME']) . '" where configuration_key = "STORE_NAME"');
  osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_NAME']) . '" where configuration_key = "STORE_OWNER"');
  osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where configuration_key = "STORE_OWNER_EMAIL_ADDRESS"');

  if (!empty($_POST['CFG_STORE_OWNER_NAME']) && !empty($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS'])) {
    osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "\"' . trim($_POST['CFG_STORE_OWNER_NAME']) . '\" <' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '>" where configuration_key = "EMAIL_FROM"');
  } else {
    osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where configuration_key = "EMAIL_FROM"');
  }

  if ( !empty($_POST['CFG_ADMINISTRATOR_USERNAME']) ) {
    $check_query = osc_db_query('select user_name from ' . TABLE_ADMINISTRATORS . ' where user_name = "' . trim($_POST['CFG_ADMINISTRATOR_USERNAME']) . '"');

    if (osc_db_num_rows($check_query)) {
      osc_db_query('update ' . TABLE_ADMINISTRATORS . ' set user_password = "' . osc_encrypt_password(trim($_POST['CFG_ADMINISTRATOR_PASSWORD'])) . '" where user_name = "' . trim($_POST['CFG_ADMINISTRATOR_USERNAME']) . '"');
    } else {
      osc_db_query('insert into ' . TABLE_ADMINISTRATORS . ' (user_name, user_password) values ("' . trim($_POST['CFG_ADMINISTRATOR_USERNAME']) . '", "' . osc_encrypt_password(trim($_POST['CFG_ADMINISTRATOR_PASSWORD'])) . '")');
    }
  }

  osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . trim($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']) . '" where configuration_key = "MODULE_PAYMENT_PAYPAL_EXPRESS_SELLER_ACCOUNT"');

  require ('includes/languages/' . $language . '/' . basename($_SERVER['PHP_SELF']));  
?>

<div class="row">
  <div class="col-sm-9">
    <div class="alert alert-info">
      <h1><?php echo PAGE_TITLE_INSTALLATION ; ?></h1>

      <p></p>
      <p><?php echo TEXT_DESCRIPTION ; ?></p>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="panel panel-default">
      <div class="panel-body">
        <ol>
          <li class="text-muted"><?php echo TEXT_BOX_DB ; ?></li>
          <li class="text-muted"><?php echo TEXT_BOX_WS ; ?></li>
          <li class="text-muted"><?php echo TEXT_BOX_ON ; ?></li>
          <li class="text-success"><strong><?php echo TEXT_BOX_FINISH ; ?></strong></li>
        </ol>
      </div>
    </div>
    <div class="progress">
      <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">100%</div>
    </div>
  </div>
</div>

<div class="clearfix"></div>

<div class="row">
  <div class="col-xs-12 col-sm-push-3 col-sm-9">

    <div class="page-header">
      <h2><?php echo TEXT_STEP4 ; ?></h2>
    </div>
    
    <?php
    $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
    if ((substr($dir_fs_document_root, -1) != '\\') && (substr($dir_fs_document_root, -1) != '/')) {
      if (strrpos($dir_fs_document_root, '\\') !== false) {
        $dir_fs_document_root .= '\\';
      } else {
        $dir_fs_document_root .= '/';
      }
    }

    osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $dir_fs_document_root . 'includes/work/" where configuration_key = "DIR_FS_CACHE"');
    osc_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $dir_fs_document_root . 'includes/work/" where configuration_key = "SESSION_WRITE_DIRECTORY"');

    if ($handle = opendir($dir_fs_document_root . 'includes/work/')) {
      while (false !== ($filename = readdir($handle))) {
        if (substr($filename, strrpos($filename, '.')) == '.cache') {
          @unlink($dir_fs_document_root . 'includes/work/' . $filename);
        }
      }

      closedir($handle);
    }

    $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
    $http_server = $http_url['scheme'] . '://' . $http_url['host'];
//    $http_catalog = $http_url['path'];
	$http_catalog = '/' . strtok($_SERVER['PHP_SELF'], '/');
    if (isset($http_url['port']) && !empty($http_url['port'])) {
      $http_server .= ':' . $http_url['port'];
    }

    if (substr($http_catalog, -1) != '/') {
      $http_catalog .= '/';
    }

    $admin_folder = 'admin';
    if (isset($_POST['CFG_ADMIN_DIRECTORY']) && !empty($_POST['CFG_ADMIN_DIRECTORY']) && osc_is_writable($dir_fs_document_root) && osc_is_writable($dir_fs_document_root . 'admin')) {
      $admin_folder = preg_replace('/[^a-zA-Z0-9]/', '', trim($_POST['CFG_ADMIN_DIRECTORY']));

      if (empty($admin_folder)) {
        $admin_folder = 'admin';
      }
    }
	
// bof multi store save into stores   
   $stores_config_table = 'configuration' ;
   $sql_data_array = array('stores_name' => trim($_POST['CFG_STORE_NAME']),
                           'stores_url'  => $http_server . $http_catalog ,
                           'stores_absolute'  => $dir_fs_document_root ,
                           'stores_config_table'  =>  'configuration' ,
                           'stores_status' =>  '1' );

   $sql_data_array['date_added'] = 'now()';

   osc_db_perform(TABLE_STORES, $sql_data_array);
   $stores_id = osc_db_insert_id();   
   
   osc_db_query("CREATE TABLE " . TABLE_CONFIGURATION_STD . " LIKE configuration" ); // used in multi store shop
   osc_db_query("INSERT INTO "  . TABLE_CONFIGURATION_STD . " SELECT * from " . $stores_config_table ); // used in multi store shop

//   tep_db_query("CREATE TABLE " . $stores_config_table . " LIKE " . TABLE_CONFIGURATION_STD );
//   tep_db_query("INSERT INTO "  . $stores_config_table . " SELECT * from " . TABLE_CONFIGURATION_STD );			  

 //rmh M-S_multi-stores begin
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website URL', 'HTTP_CATALOG_SERVER', '" . $http_server . "', 'The URL for your stores catalog (eg. http://www.domain.com)', 8600, 10, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website SSL URL', 'HTTPS_CATALOG_SERVER', '" . $http_server . "', 'The SSL URL for your stores catalog (eg. https://www.domain.com)', 8600, 20, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Enable SSL Store Catalog', 'ENABLE_SSL_CATALOG', 'false', 'Enable SSL links for Store Catalog', 8600, 30, now(), now(), NULL, 'tep_cfg_select_option(array(\'true\', \'false\'),')");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website Path', 'DIR_WS_CATALOG', '" . $http_catalog . "', 'Directory Website Path for Store Catalog (absolute path required -- eg. /catalog/)', 8600, 40, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Path', 'DIR_FS_CATALOG', '" . $dir_fs_document_root . "', 'Directory Filesystem Path for Store Catalog (absolute path required -- eg. /home/user/public_html/catalog/)', 8600, 50, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website Images Path', 'DIR_WS_CATALOG_IMAGES', '" . $http_catalog . "images/', 'Store Catalog Website Images Path (with trailing slash -- eg. http://www.domain.com/catalog/images/)', 8600, 60, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Website Languages Path', 'DIR_WS_CATALOG_LANGUAGES', '" . $http_catalog. "includes/languages/', 'Store Catalog Website Languages Path (with trailing slash -- eg. http://www.domain.com/catalog/includes/languages/)', 8600, 70, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Languages Path', 'DIR_FS_CATALOG_LANGUAGES', '" . $dir_fs_document_root . "includes/languages/', 'Store Catalog Filesystem Languages Path (with trailing slash -- eg. /home/user/public_html/catalog/includes/languages/)', 8600, 80, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Images Path', 'DIR_FS_CATALOG_IMAGES', '" . $dir_fs_document_root . "images/', 'Store Catalog Filesystem Images Path (with trailing slash -- eg. /home/user/public_html/catalog/images/)', 8600, 90, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " VALUES ('', 'Store Catalog Filesystem Modules Path', 'DIR_FS_CATALOG_MODULES', '" . $dir_fs_document_root . "includes/modules/', 'Store Catalog Filesystem Modules Path (with trailing slash -- eg. /home/user/public_html/catalog/includes/modules/)', 8600, 100, now(), now(), NULL, NULL)");
   osc_db_query("INSERT INTO " .$stores_config_table . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stores ID', 'SYS_STORES_ID', '" . $stores_id . "', 'The id of my store', '6', '0', now())"); //rmh M-S_multi-stores			  
//rmh M-S_multi-stores end 
// saving file with name of configuration table in default store   
  $file_contents = '<?php' . "\n" .
                   '  define(\'SYS_MULTI_STORES_CONFIG\', \'' . $stores_config_table . '\');' . "\n" .				   
                   '  define(\'SYS_MULTI_STORES_ADMIN\', \'' . $http_server . $http_catalog .  $admin_folder . '\');' . "\n" .					   
                   '?>';

  $fp = fopen($dir_fs_document_root . 'includes/multi_stores.inc.php', 'w');
  fputs($fp, $file_contents);
  fclose($fp);

  @chmod($dir_fs_document_root . 'includes/multi_stores.inc.php', 0644);
         
// eof multi stores 	

    $file_contents = '<?php' . "\n" .
                     '  define(\'HTTP_SERVER\', \'' . $http_server . '\');' . "\n" .
                     '  define(\'HTTPS_SERVER\', \'' . $http_server . '\');' . "\n" .
                     '  define(\'ENABLE_SSL\', false);' . "\n" .
                     '  define(\'HTTP_COOKIE_DOMAIN\', \'\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_DOMAIN\', \'\');' . "\n" .
                     '  define(\'HTTP_COOKIE_PATH\', \'' . $http_catalog . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_PATH\', \'' . $http_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                   '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n\n" .
                     '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n\n" .
                   '  define(\'DIR_WS_ADMIN\', \'' . $http_catalog .  $admin_folder . '/\');' . "\n" .
                   '  define(\'DIR_FS_ADMIN\', \'' . $dir_fs_document_root .  $admin_folder . '/\');' . "\n" .	
                     '  define(\'DB_SERVER\', \'' . trim($_POST['DB_SERVER']) . '\');' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . trim($_POST['DB_SERVER_USERNAME']) . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . trim($_POST['DB_SERVER_PASSWORD']) . '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . trim($_POST['DB_DATABASE']) . '\');' . "\n" .
                     '  define(\'USE_PCONNECT\', \'false\');' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'mysql\');' . "\n" .
		           '  define(\'USE_DEFAULT_LANGUAGE_CURRENCY \', \'true\');' . "\n" .	
                   '  // OTF contrib begins' . "\n" .
                   '  define(\'OPTIONS_TYPE_SELECT\', 0);' . "\n" .
                   '  define(\'OPTIONS_TYPE_TEXT\', 1);' . "\n" .
                   '  define(\'OPTIONS_TYPE_TEXTAREA\', 2);' . "\n" .
                   '  define(\'OPTIONS_TYPE_RADIO\', 3);' . "\n" .
                   '  define(\'OPTIONS_TYPE_CHECKBOX\', 4);' . "\n" .
                   '  define(\'OPTIONS_TYPE_FILE\', 5);' . "\n" .
                   '  define(\'OPTIONS_TYPE_IMAGE\', 6);' . "\n" .
                   '  define(\'TEXT_PREFIX\', \'txt_\');' . "\n" .				 
                   '  define(\'UPLOAD_PREFIX\', \'upload_\');' . "\n" .
                   '  define(\'TEXT_UPLOAD_NAME\', \'CUSTOMER\');' . "\n" .				   
                   '  define(\'OPTIONS_VALUE_TEXT_ID\', 0);  //Must match id for user defined "TEXT" value in db table TABLE_PRODUCTS_OPTIONS_VALUES' . "\n" .
                   '  // OTF contrib ends 				   ' . "\n" .		                   				   
                   '  define(\'_SYS_EMAIL_NORMAL_NEWCUSTOMER\',                 10 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWCUSTOMER\',                   11 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN\',           15 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN\',             16 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_NEWORDER\',                     1 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWORDER\',                       2 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_NEWORDER_ADMIN\',               5 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWORDER_ADMIN\',                 6 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_ORDER_UPDATE\',                20 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_ORDER_UPDATE\',                  21 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN\',           25 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_PASSWORDFORGOTTEN\',             26 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_CREATE_REVIEW\',               30 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_CREATE_REVIEW\',                 31 ) ; ' . "\n" .	 
                   '  define(\'_SYS_EMAIL_NORMAL_CONTACT_US\',                  35 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_CONTACT_US\',                    36 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_WISHLIST\',                     40 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_WISHLIST\',                      45 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER\',         50 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER\',          55 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER\',       60 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER\',        65 ) ; ' . "\n" .				   
                  '  define(\'_SYS_EMAIL_NORMAL_WISHLIST_ADMIN\',               70 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_WISHLIST_ADMIN\',                75 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER_ADMIN\',   80 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER_ADMIN\',    85 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN\', 90 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN\',  95 ) ; ' . "\n" ;					   

    if (isset($_POST['CFG_TIME_ZONE'])) {
      $file_contents .= '  define(\'CFG_TIME_ZONE\', \'' . trim($_POST['CFG_TIME_ZONE']) . '\');' . "\n";
    }

    $file_contents .= '?>';

    $fp = fopen($dir_fs_document_root . 'includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);

    @chmod($dir_fs_document_root . 'includes/configure.php', 0644);

    $file_contents = '<?php' . "\n" .
                    '  define(\'HTTP_SERVER\', \'' . $http_server . '\');' . "\n" .
  //                   '  define(\'HTTPS_SERVER\', \'' . $http_server . '\');' . "\n" .
  //                   '  define(\'ENABLE_SSL\', false);' . "\n" .
  //                   '  define(\'HTTP_COOKIE_DOMAIN\', \'\');' . "\n" .
  //                   '  define(\'HTTPS_COOKIE_DOMAIN\', \'\');' . "\n" .
  //                   '  define(\'HTTP_COOKIE_PATH\', \'' . $http_catalog . $admin_folder . '\');' . "\n" .
                   '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
  //                   '  define(\'HTTPS_COOKIE_PATH\', \'' . $http_catalog . $admin_folder . '\');' . "\n" .
  //                   '  define(\'HTTP_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" .
  //                   '  define(\'HTTPS_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" .
  //                   '  define(\'ENABLE_SSL_CATALOG\', \'false\');' . "\n" .
                     '  define(\'DIR_FS_DOCUMENT_ROOT\', \'' . $dir_fs_document_root . '\');' . "\n" .
                     '  define(\'DIR_WS_ADMIN\', \'' . $http_catalog .  $admin_folder . '/\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_ADMIN\', \'' . $http_catalog .  $admin_folder . '/\');' . "\n" .
                     '  define(\'DIR_FS_ADMIN\', \'' . $dir_fs_document_root .  $admin_folder . '/\');' . "\n" .
  //                   '  define(\'DIR_WS_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
  //                   '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
  //                   '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
  //                   '  define(\'DIR_WS_CATALOG_IMAGES\', DIR_WS_CATALOG . \'images/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
  //                   '  define(\'DIR_WS_CATALOG_LANGUAGES\', DIR_WS_CATALOG . \'includes/languages/\');' . "\n" .
  //                   '  define(\'DIR_FS_CATALOG_LANGUAGES\', DIR_FS_CATALOG . \'includes/languages/\');' . "\n" .
  //                   '  define(\'DIR_FS_CATALOG_IMAGES\', DIR_FS_CATALOG . \'images/\');' . "\n" .
  //                   '  define(\'DIR_FS_CATALOG_MODULES\', DIR_FS_CATALOG . \'includes/modules/\');' . "\n" .
                     '  define(\'DIR_FS_BACKUP\', DIR_FS_ADMIN . \'backups/\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n\n" .
                     '  define(\'DB_SERVER\', \'' . trim($_POST['DB_SERVER']) . '\');' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . trim($_POST['DB_SERVER_USERNAME']) . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . trim($_POST['DB_SERVER_PASSWORD']) . '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . trim($_POST['DB_DATABASE']) . '\');' . "\n" .
                     '  define(\'USE_PCONNECT\', \'false\');' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'mysql\');' . "\n" .
				   '  define(\'USE_DEFAULT_LANGUAGE_CURRENCY \', \'true\');' . "\n" .					   
                   '  // OTF contrib begins' . "\n" .
                   '  define(\'OPTIONS_TYPE_SELECT\', 0);' . "\n" .
                   '  define(\'OPTIONS_TYPE_TEXT\', 1);' . "\n" .
                   '  define(\'OPTIONS_TYPE_TEXTAREA\', 2 );' . "\n" .
  				   '  define(\'OPTIONS_TYPE_RADIO\', 3);' . "\n" .
                   '  define(\'OPTIONS_TYPE_CHECKBOX\', 4);' . "\n" .
                   '  define(\'OPTIONS_TYPE_FILE\', 5);' . "\n" .                   
				   '  define(\'OPTIONS_TYPE_IMAGE\', 6);' . "\n" . 
                   '  define(\'OPTIONS_VALUE_TEXT_ID\', 0);  //Must match id for user defined "TEXT" value in db table TABLE_PRODUCTS_OPTIONS_VALUES' . "\n" .
                   '  define(\'OPTIONS_TYPE_SELECT_NAME\', \'Select\'); //  (Names are just for displaying on admin side)'  . "\n" .
                   '  define(\'OPTIONS_TYPE_TEXT_NAME\', \'Text\'); '  . "\n" .
                   '  define(\'OPTIONS_TYPE_TEXTAREA_NAME\', \'TextArea\'); '  . "\n" .
                   '  define(\'OPTIONS_TYPE_RADIO_NAME\', \'Radio\'); '  . "\n" .
                   '  define(\'OPTIONS_TYPE_CHECKBOX_NAME\', \'Checkbox\'); '  . "\n" .
                   '  define(\'OPTIONS_TYPE_FILE_NAME\', \'File\'); '  . "\n" .
                   '  define(\'OPTIONS_TYPE_IMAGE_NAME\', \'Image\'); '  . "\n" .
                   '  define(\'TEXT_PREFIX\', \'txt_\'); '  . "\n" .
                   '  define(\'UPLOAD_PREFIX\', \'upload_\'); '  . "\n" .
                   '  define(\'TEXT_UPLOAD_NAME\', \'CUSTOMER-INPUT\'); '  . "\n" .			   
                   '  // OTF contrib ends 				   ' . "\n" .		
                   '  define(\'_SYS_EMAIL_NORMAL_NEWCUSTOMER\',                 10 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWCUSTOMER\',                   11 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN\',           15 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN\',             16 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_NEWORDER\',                     1 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWORDER\',                       2 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_NEWORDER_ADMIN\',               5 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_NEWORDER_ADMIN\',                 6 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_ORDER_UPDATE\',                20 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_ORDER_UPDATE\',                  21 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN\',           25 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_PASSWORDFORGOTTEN\',             26 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_NORMAL_CREATE_REVIEW\',               30 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_CREATE_REVIEW\',                 31 ) ; ' . "\n" .	 
                   '  define(\'_SYS_EMAIL_NORMAL_CONTACT_US\',                  35 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_CONTACT_US\',                    36 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_WISHLIST\',                     40 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_WISHLIST\',                      45 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER\',         50 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER\',          55 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER\',       60 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER\',        65 ) ; ' . "\n" .				   
                  '  define(\'_SYS_EMAIL_NORMAL_WISHLIST_ADMIN\',               70 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_WISHLIST_ADMIN\',                75 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER_ADMIN\',   80 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER_ADMIN\',    85 ) ; ' . "\n" .
                  '  define(\'_SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN\', 90 ) ; ' . "\n" .	
                   '  define(\'_SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN\',  95 ) ; ' . "\n" .
                   '  // multi stores '                                                          . "\n" .	
                   '  define(\'DIR_FS_CATEGORIES_IMAGES\',                \'categories/\');  '   . "\n" .	
                   '  define(\'DIR_FS_PRODUCTS_IMAGES\',                  \'products/\'); '      . "\n" .	
                   '  define(\'DIR_FS_MANUFACTURERS_IMAGES\',             \'manufacturers/\'); ' . "\n";

    if (isset($_POST['CFG_TIME_ZONE'])) {
      $file_contents .= '  define(\'CFG_TIME_ZONE\', \'' . trim($_POST['CFG_TIME_ZONE']) . '\');' . "\n";
    }

    $file_contents .= '?>';

    $fp = fopen($dir_fs_document_root . 'admin/includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);

    @chmod($dir_fs_document_root . 'admin/includes/configure.php', 0644);

    if ($admin_folder != 'admin') {
      @rename($dir_fs_document_root . 'admin', $dir_fs_document_root . $admin_folder);
    }
// bof multi stores
   require($dir_fs_document_root . 'includes/configure.php' );
   $languages_query = osc_db_query("select languages_id from " . TABLE_LANGUAGES . " order by languages_id");
   while ($lang_lang = osc_db_fetch_array($languages_query)) {
       $lang_to_stores = $lang_lang['languages_id'] ;
			 
	   // check if store is already defined
	   $query = osc_db_query("SELECT language_id FROM ". TABLE_EMAIL_ORDER_TEXT ." WHERE stores_id = '" . $stores_id . "' and language_id = '" . (int)$email_to_stores . "'");
       if ( osc_db_num_rows( $query ) > 0 ) {
	       // present
	   } else { // add email text
                
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWCUSTOMER           . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWCUSTOMER             . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN     . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWORDER              . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWORDER                . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWORDER_ADMIN        . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')"); 
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWORDER_ADMIN          . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_ORDER_UPDATE          . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_ORDER_UPDATE            . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN     . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_PASSWORDFORGOTTEN       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_CREATE_REVIEW         . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_CREATE_REVIEW		      . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_CONTACT_US            . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_CONTACT_US   		      . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");

           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_WISHLIST                           . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_WISHLIST                             . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER               . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')"); 
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER                 . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER             . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER               . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_WISHLIST_ADMIN                     . "', '"        . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_WISHLIST_ADMIN                       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER_ADMIN         . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER_ADMIN		   . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN       . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");
           osc_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN   	   . "', '" . (int)$lang_to_stores . "', '" . (int)$stores_id . "')");			   		   
       }			   
   }		
             
// eof multi stores
    ?>

    <div class="alert alert-success"><?php echo TEXT_SUCCESSFUL ; ?></div>

    <br />

    <div class="row">
      <div class="col-xs-6"><?php echo osc_draw_button( TEXT_CATALOG, 'cart', $http_server . $http_catalog . 'index.php', 'primary', array('newwindow' => 1), 'btn-success btn-block'); ?></div>
      <div class="col-xs-6"><?php echo osc_draw_button(TEXT_ADMIN_TOOL, 'locked', $http_server . $http_catalog . $admin_folder . '/index.php', 'primary', array('newwindow' => 1), 'btn-info btn-block'); ?></div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-pull-9 col-sm-3">
    <div class="panel panel-success">
      <div class="panel-heading">
        Step 4: Finished!
      </div>
      <div class="panel-body">
        <p><?php echo TEXT_STEP4_TEXT ; ?></p>
        <p><?php echo TEXT_STEP4_TEXT2 ; ?></p>
      </div>
      <div class="panel-footer">
        <p class="text-right">- <a href="http://www.oscommerce.com/Us&Team" target="_blank"><?php echo TEXT_OSC_TEAM ; ?></a></p>
      </div>
    </div>
  </div>
  
</div>
