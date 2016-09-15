<?php
  define('HTTP_SERVER', 'http://nieuwenhuisverf.nl');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_FS_DOCUMENT_ROOT', '/home/verfwebs/domains/nieuwenhuisverf.nl/public_html/');
  define('DIR_WS_ADMIN', '/triple/');
  define('DIR_WS_HTTPS_ADMIN', '/triple/');
  define('DIR_FS_ADMIN', '/home/verfwebs/domains/nieuwenhuisverf.nl/public_html/triple/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'verfwebs_nvms01');
  define('DB_SERVER_PASSWORD', 'TgngNvMs01');
  define('DB_DATABASE', 'verfwebs_nvms01');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
  define('USE_DEFAULT_LANGUAGE_CURRENCY ', 'true');
  // OTF contrib begins
  define('OPTIONS_TYPE_SELECT', 0);
  define('OPTIONS_TYPE_TEXT', 1);
  define('OPTIONS_TYPE_TEXTAREA', 2 );
  define('OPTIONS_TYPE_RADIO', 3);
  define('OPTIONS_TYPE_CHECKBOX', 4);
  define('OPTIONS_TYPE_FILE', 5);
  define('OPTIONS_TYPE_IMAGE', 6);
  define('OPTIONS_VALUE_TEXT_ID', 0);  //Must match id for user defined "TEXT" value in db table TABLE_PRODUCTS_OPTIONS_VALUES
  define('OPTIONS_TYPE_SELECT_NAME', 'Select'); //  (Names are just for displaying on admin side)
  define('OPTIONS_TYPE_TEXT_NAME', 'Text'); 
  define('OPTIONS_TYPE_TEXTAREA_NAME', 'TextArea'); 
  define('OPTIONS_TYPE_RADIO_NAME', 'Radio'); 
  define('OPTIONS_TYPE_CHECKBOX_NAME', 'Checkbox'); 
  define('OPTIONS_TYPE_FILE_NAME', 'File'); 
  define('OPTIONS_TYPE_IMAGE_NAME', 'Image'); 
  define('TEXT_PREFIX', 'txt_'); 
  define('UPLOAD_PREFIX', 'upload_'); 
  define('TEXT_UPLOAD_NAME', 'CUSTOMER-INPUT'); 
  // OTF contrib ends 				   
  define('_SYS_EMAIL_NORMAL_NEWCUSTOMER',           10 ) ; 
  define('_SYS_EMAIL_HTML_NEWCUSTOMER',             11 ) ; 
  define('_SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN',     15 ) ; 
  define('_SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN',       16 ) ; 
  define('_SYS_EMAIL_NORMAL_NEWORDER',              1  ) ; 
  define('_SYS_EMAIL_HTML_NEWORDER',                2  ) ; 
  define('_SYS_EMAIL_NORMAL_NEWORDER_ADMIN',        5  ) ; 
  define('_SYS_EMAIL_HTML_NEWORDER_ADMIN',          6  ) ; 
  define('_SYS_EMAIL_NORMAL_ORDER_UPDATE',          20 ) ; 
  define('_SYS_EMAIL_HTML_ORDER_UPDATE',            21 ) ; 
  define('_SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN',     25 ) ; 
  define('_SYS_EMAIL_HTML_PASSWORDFORGOTTEN',       26 ) ; 
  define('_SYS_EMAIL_NORMAL_CREATE_REVIEW',         30 ) ; 
  define('_SYS_EMAIL_HTML_CREATE_REVIEW',           31 ) ; 
  define('_SYS_EMAIL_NORMAL_CONTACT_US',            35 ) ; 
  define('_SYS_EMAIL_HTML_CONTACT_US',              36 ) ; 
  // multi stores 
  define('DIR_FS_CATEGORIES_IMAGES',                'categories/');  
  define('DIR_FS_PRODUCTS_IMAGES',                  'products/'); 
  define('DIR_FS_MANUFACTURERS_IMAGES',             'manufacturers/'); 
  define('CFG_TIME_ZONE', 'Europe/Amsterdam');
?>