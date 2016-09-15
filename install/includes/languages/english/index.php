<?php
/*
  $Id: index.php,v 1.1 2004/02/16 06:59:36 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  define('PAGE_TITLE_WELCOME', 'Welcome to osCommerce ');
  define('TEXT_INDEX', '<strong>osCommerce </strong> Online Merchant  helps you sell products worldwide with your own online store. Its Administration Tool manages products, customers, orders, newsletters, specials, and more to successfully build the success of your online business.<br />
  <br /><strong>osCommerce</strong> has attracted a large community of store owners and developers who support each other and have provided over 6,000 free add-ons that can extend the features and potential of your online store.');

  define('TEXT_CHOOSE_LANGUAGE', 'Choose language Administration tool: ');
  define('TEXT_CONTINUE', 'Continue');
  define('TEXT_SERVEER_CAPABILITIES', 'Server Capabilities');
  define('TEXT_PHP_VERSION', 'PHP Version ');

  define('TEXT_PHP_SETTINGS', 'PHP Settings');
  define('TEXT_R_G', 'register_globals');
  define('TEXT_M_Q', 'magic_quotes');
  define('TEXT_F_U', 'file_uploads ');
  define('TEXT_SA_S', 'session.auto_start');
  define('TEXT_SU_T_S', 'session.use_trans_sid');
  define('TEXT_PHP_EXTENSIONS', 'Required PHP Extensions');
  define('TEXT_M_S', 'MySQL');
  define('TEXT_OPT_PHP_EXT', 'Optional PHP Extensions');
  define('TEXT_GD', 'GD libery');
  define('TEXT_CURL', 'cUrl');
  define('TEXT_OPENSSL', 'OpenSSL');  

  define('TEXT_INSTALLATION', 'Install osCommerce');
  define('TEXT_PARAMETERS', 'The webserver is not able to save the installation parameters to its configuration files.');
  define('TEXT_CHMOD', 'The following files need to have their file permissions set to world-writeable (chmod 777):');
  define('TEXT_CORRECT', 'Please correct the above errors and retry the installation procedure with the changes in place.');
  define('TEXT_CHANGING', 'Changing webserver configuration parameters may require the webserver service to be restarted before the changes take affect.');
  define('TEXT_VERIFIED', 'The webserver environment has been verified to proceed with a successful installation and configuration of your online store.');
  define('TEXT_INSTALL_PROCEDURE', '<br />It is possible to install this version of Oscommerce as a normal installation. Please press STANDARD to start the installation procedure.
                              <br /><br />But you can also install this version as a multi shop installation ( second, third etc ). Please press MULTI_SHOP to start the installation procedure
							  <br /><br />For a multi shop install you do not have to copy the admin category to the server of your second ( or third, fourth etc ) shop. The admin side of the website should only be installed/copied on the website of the first shop
							  <br /><br />');
  define('IMAGE_CONTINUE', 'CONTINUE') ;
  define('IMAGE_RETRY', 'RETRY') ;  
  
  define('IMAGE_STD_SHOP', 'STANDARD') ;
  define('IMAGE_MULTI_SHOP', 'MULTI-SHOP') ; 

  define('TEXT_NEW_INSTALLATION', 'New Installation') ;   
  define('TEXT_COMPAT_PHP43' , 'Compatibility with register_globals is supported from PHP 4.3+. This setting <u>must be enabled</u> due to an older PHP version being used.' ) ;
  define('TEXT_MYSQL_EXT',   'The MySQL extension is required but is not installed. Please enable it to continue installation.' ) ;
  define('TEXT_ENABLE_JAVA', 'Please enable Javascript in your browser to be able to start the installation procedure.' ) ;  
  
  define('TEXT_STANDARD_INSTALL', 'Start the STANDARD installation procedure' ) ;
  define('TEXT_MULTISHOP_INSTALL', 'Start the MULTISHOP installation procedure' ) ;    
?>
