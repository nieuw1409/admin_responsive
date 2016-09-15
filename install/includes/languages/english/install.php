<?php
/*
  $Id: install.php,v 1.3 2004/11/07 21:02:11 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  define('PAGE_TITLE_INSTALLATION', 'New Installation');
  define('PAGE_TITLE_INSTALLATION_MULTI_STORE', 'New Installation Multi Store'); // multi store  
  define('TEXT_BOX_DB', 'Database Server');
  define('TEXT_BOX_WS', 'Web Server');
  define('TEXT_BOX_ON', 'Online Store Settings');
  define('TEXT_BOX_FINISH', 'Finished!');

  define('TEXT_DESCRIPTION', 'This web-based installation routine will correctly setup and configure osCommerce to run on this server.<br /><br />
  Please follow the on-screen instructions that will take you through the database server, web server, and store configuration options. If help is needed at any stage, please consult the documentation or seek help at the community support forums.');
  define('TEXT_DESCRIPTION2', '<br />The Database Server must be present on the webserver to install this second, third etc store in the database<br />' ) ;
  define('TEXT_STEP1', 'Step 1: Database Server');
  define('TEXT_STEP1_TEXT1', 'The database server stores the content of the online store such as product information, customer information, and the orders that have been made.');
  define('TEXT_STEP1_TEXT2', 'Please consult your server administrator if your database server parameters are not yet known.');

  define('TEXT_DATABASE_TEXT', 'The address of the database server in the form of a hostname or IP address.');
  define('TEXT_DATABASE_USERNAME', 'Username');
  define('TEXT_DATABASE_USERNAME_TEXT', 'The username used to connect to the database server.');
  define('TEXT_DATABASE_PASSWORD', 'Password');
  define('TEXT_DATABASE_PASSWORD_TEXT', 'The password that is used together with the username to connect to the database server.');
  define('TEXT_DATABASE_NAME', 'Database Name');
  define('TEXT_DATABASE_NAME_TEXT', 'The name of the database to hold the data in.');

  define('CONNECT_TESTING', 'Testing database connection..');
  define('CONNECT_PROBLEM', 'There was a problem importing the database. The following error had occured:</p><p><strong>%s</strong></p><p>Please verify the connection parameters and try again.');
  define('CONNECT_PROBLEM_MS', 'The database is not present. The following error had occured:<p><b>%s</b></p>Please verify the connection parameters and try again.'); // multi stores
  
  define('CONNECT_IMPORTED', 'The database structure is now being imported. Please be patient during this procedure.');
  define('CONNECT_SUCCESSFULLY', 'Database imported successfully.');
  define('CONNECT_SUCCESSFULLY_MS', 'The Database is present.');   // multi stores

  define('TEXT_STEP2', 'Step 2: Web Server');
  define('TEXT_STEP2_TEXT', 'The web server takes care of serving the pages of your online store to your guests and customers. The web server parameters make sure the links to the pages point to the correct location.');
  define('TEXT_WWW_ADDRESS', 'WWW Address');
  define('TEXT_WWW_ADDRESS_TEXT', 'The web address to the online store.');
  define('TEXT_WWW_ROOT_DIRECTORY', 'Webserver Root Directory');
  define('TEXT_WWW_ROOT_DIRECTORY_TEXT', 'The directory where the online store is installed on the server.');

  define('TEXT_STEP3', 'Step 3: Online Store Settings');
  define('TEXT_STEP3_TEXT', 'Here you can define the name of your online store and the contact information for the store owner.<br /><br />
      The administrator username and password are used to log into the protected administration tool section.');
  define('TEXT_STORE_NAME', 'Store Name');
  define('TEXT_STORE_NAME_TEXT', 'The name of the online store that is presented to the public.');
  define('TEXT_OWNER_NAME', 'Store Owner Name');
  define('TEXT_OWNER_NAME_TEXT', 'The name of the store owner that is presented to the public.');
  define('TEXT_OWNER_MAIL', 'Store Owner E-Mail Address');
  define('TEXT_OWNER_MAIL_TEXT', 'The e-mail address of the store owner that is presented to the public.');
  define('TEXT_AU', 'Administrator Username');
  define('TEXT_AU_TEXT', 'The administrator username to use for the administration tool.');
  define('TEXT_AP', 'Administrator Password');
  define('TEXT_AP_TEXT', 'The password to use for the administrator account.');
  define('TEXT_ADMIN_DN', 'Administration Directory Name');
  define('TEXT_ADMIN_DN_TEXT', 'This is the directory where the administration section will be installed. You should change this for security reasons.');
  define('TEXT_CFG_TIME_ZONE', 'Timezone of the Store');    
  define('TEXT_CFG_TIME_ZONE_TEXT', 'The time zone to base the date and time on.' ) ;
  
  define('TEXT_STEP4', 'Step 4: Finished!');
  define('TEXT_STEP4_TEXT', 'Congratulations on installing and configuring osCommerce Online Merchant as your online store solution!<br /><br />' ) ;
  define('TEXT_STEP4_TEXT2', 'We wish you all the best with the success of your online store and welcome you to join and participate in our community.');
  define('TEXT_OSC_TEAM', '- The osCommerce Team');
  define('TEXT_SUCCESSFUL', 'The installation and configuration was successful!');
  define('TEXT_RENAME', 'Rename the Administration Tool directory located at');
  
  define('TEXT_ADMINISTRATION', 'Admin access details:<br />
  E-Mail: <i><b>admin@localhost.com</b></i><br />
  Password: <i><b>admin</b></i><br /><font color=red>
  <b>After input in àdministration necessarily change e-mail the address and the password for an entrance.</b></font>');

  define('TEXT_CONTINUE', 'Continue');
  define('TEXT_CENCEL', 'Cancel');
  define('TEXT_CATALOG', 'Catalog');
  define('TEXT_ADMIN_TOOL', 'Administration Tool');
  define('TEXT_POST_INSTALL', 'Post-Installation Notes');
  define('TEXT1_POST_INSTALL', 'It is recommended to follow the following post-installation steps to secure your osCommerce Online Merchant online store:');
  define('TEXT_Rename', 'Rename the Administration Tool directory located at');
  define('TEXT_SET', 'Set the permissions on');
  define('TEXT_TO', 'to 644 (or 444 if this file is still writable).');
  define('TEXT_DEL', 'Delete the');
  define('TEXT_D', 'directory');
  define('TEXT_REW', 'Review the directory permissions on the Administration Tool -> Tools -> Security Directory Permissions page.');
  define('TEXT_REW2', 'The Administration Tool should be further protected using htaccess/htpasswd and can be set-up within the Configuration -> Administrators page.');
  define('IMAGE_CONTINUE', 'CONTINUE') ;
  define('IMAGE_CANCEL', 'START SCREEN') ;  
  define('IMAGE_TOWEBSHOP', 'TO STORE') ;  
  define('IMAGE_TOADMIN', 'LOGIN ADMINISTRATION') ;  
  define('TEXT_MULTI_SHOP', 'Before you can use this multi shop you must first activate <br />
                             - Activate Languages for this store <br />
                             - Add Products to this store<br />
                             - etc <br />') ;		  
  define('TEXT_INPUT_REQUIRED', 'Required information !' ) ;								 
  define('TEXT_NEXT_STEP2', 'Continue to step 2' ) ;
  define('TEXT_NEXT_STEP3', 'Continue to step 3' ) ;
  define('TEXT_NEXT_STEP4', 'Continue to step 4' ) ;    
?>
