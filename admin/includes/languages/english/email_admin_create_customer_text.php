<?php
/*
  $Id: invoice.php,v 1.2 2002/06/15 12:45:05 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Admin Create Account confirmation: Email text');
define('INFO', 'You can enter your confirmation email here, it can be in plain text or HTML format depending on the configuration of your shop. To use HTML you must, under "Configuration/E-Mail Options" set "use MIME HTML" to "true".  You can check this setting <a href="configuration.php?gID=');
define('INFO2', '">>>Here<<</a>.');

define('VAR_LIST_POPUP', 'Variable List' ); 
define('TEXT_EMAIL_ORDER_TEXT', 'Create Account Email Text' ) ;
define('TEXT_EMAIL_SUBJECT',            'Text Email Subject' ) ;

define('POPUP_LIST_EXPLAIN', 				'The Following Variables can be Used in the Create Account Email Text');

define('POPUP_CA_STORENAME', 		'Name of the Webshop') ;
define('POPUP_CA_ID',			    'CustomerNumber') ;
define('POPUP_CA_GENDER',			'Gender') ;
define('POPUP_CA_FIRSTNAME',	    'FirstName') ;
define('POPUP_CA_LASTNAME',         'LastName') ;
define('POPUP_CA_STREET', 			'Street Address') ;
define('POPUP_CA_POSTCODE', 		'Postcode') ;
define('POPUP_CA_CITY', 			'City') ;
define('POPUP_CA_COUNTRY', 			'Country') ;
define('POPUP_CA_EMAILADDRESS', 	'EmailAddress') ;
define('POPUP_CA_TELEPHONE', 		'Telephonenumber') ;
define('POPUP_CA_FAX', 		        'Fax Number') ;
define('POPUP_CA_BIRTHDATE', 	    'BirthDate') ;
define('POPUP_CA_COMPANY', 	        'Companyname') ;
define('POPUP_CA_SUBURB', 	        'Suburb') ;
define('POPUP_CA_STATE', 	        'State') ;
define('POPUP_CA_NEWSLETTER', 	    'Newsletter') ;
?>
