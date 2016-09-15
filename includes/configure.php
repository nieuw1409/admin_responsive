<?php
  define('HTTP_SERVER', '');
  define('HTTPS_SERVER', '');
  define('ENABLE_SSL', false);
  define('HTTP_COOKIE_DOMAIN', '');
  define('HTTPS_COOKIE_DOMAIN', '');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');
  define('DIR_FS_HOST_ROOT', ''); //no trailing slashes
  define('DIR_WS_ADMIN', '');
  define('DIR_FS_ADMIN', '');  

  define('DB_SERVER', '');
  define('DB_SERVER_USERNAME', '');
  define('DB_SERVER_PASSWORD', '');
  define('DB_DATABASE', '');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
  
// bof 231 option type 
  define('OPTIONS_TYPE_SELECT',      0);
  define('OPTIONS_TYPE_TEXT',        1);
  define('OPTIONS_TYPE_TEXTAREA',    2);
  define('OPTIONS_TYPE_RADIO',       3);
  define('OPTIONS_TYPE_CHECKBOX',    4);
  define('OPTIONS_TYPE_FILE',        5);
  define('OPTIONS_TYPE_IMAGE',       6);
  define('TEXT_PREFIX',             'txt_');
  define('UPLOAD_PREFIX',           'upload_');
  define('TEXT_UPLOAD_NAME',        'CUSTOMER-INPUT');
  define('OPTIONS_VALUE_TEXT_ID',    0);
// eof 231 option type   
  define("USE_DEFAULT_LANGUAGE_CURRENCY", true);

  define("_SYS_EMAIL_NORMAL_NEWCUSTOMER",           10 ) ;
  define("_SYS_EMAIL_HTML_NEWCUSTOMER",             11 ) ;
  define("_SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN",     15 ) ;
  define("_SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN",       16 ) ;
  define("_SYS_EMAIL_NORMAL_NEWORDER",              1  ) ;
  define("_SYS_EMAIL_HTML_NEWORDER",                2  ) ;
  define("_SYS_EMAIL_NORMAL_NEWORDER_ADMIN",        5  ) ;
  define("_SYS_EMAIL_HTML_NEWORDER_ADMIN",          6  ) ;
  define("_SYS_EMAIL_NORMAL_ORDER_UPDATE",          20 ) ;
  define("_SYS_EMAIL_HTML_ORDER_UPDATE",            21 ) ;
  define("_SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN",     25 ) ;
  define("_SYS_EMAIL_HTML_PASSWORDFORGOTTEN",       26 ) ;
  define("_SYS_EMAIL_NORMAL_CREATE_REVIEW",         30 ) ;
  define("_SYS_EMAIL_HTML_CREATE_REVIEW",           31 ) ;
  define('_SYS_EMAIL_NORMAL_CONTACT_US',                    35 ) ; 
  define('_SYS_EMAIL_HTML_CONTACT_US',                      36 ) ; 
  
  define('_SYS_EMAIL_NORMAL_WISHLIST',                      40 ) ; 
  define('_SYS_EMAIL_HTML_WISHLIST',                        45 ) ; 

  define('_SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER',          50 ) ; 
  define('_SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER',            55 ) ; 
  
  define('_SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER',        60 ) ; 
  define('_SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER',          65 ) ;   
  
  define('_SYS_EMAIL_NORMAL_WISHLIST_ADMIN',                70 ) ; 
  define('_SYS_EMAIL_HTML_WISHLIST_ADMIN',                  75 ) ; 

  define('_SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER_ADMIN',    80 ) ; 
  define('_SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER_ADMIN',      85 ) ; 
  
  define('_SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN',  90 ) ; 
  define('_SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN',    95 ) ;   
  define('CFG_TIME_ZONE', 'Europe/Berlin');
?>