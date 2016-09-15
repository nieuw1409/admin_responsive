<?php
/*
  $Id: newsletter_unsubscribe.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Unsubscribe');
define('HEADING_TITLE', 'Unsubscribe to Newsletter');

define('PREVENIR_EMAIL_DESINSCRIT_NL', 'oui');
define('EMAIL_DESINSCRIT_NL', 'Unsubscribed from the newsletter');

define('UNSUBSCRIBE_TEXT_INFORMATION', '<br />We are sorry to see you go. Regarding our policy on privacy, see our <a href="' .FILENAME_PRIVACY . '"><u>policy</u></a>.<br /><br />If you wish to unsubscribe from our newsletter, click the button below.');

define('UNSUBSCRIBE_TEXT_OK', '<br />Your email address <strong>(%s)</strong> was removed from our subscribers list of newsletter.<br /><br />');

define('UNSUBSCRIBE_TEXT_ERROR', '<br />Email adress <strong>(%s)</strong> not found in our database, or it has already been removed from our list.<br /><br />');

define('UNSUBSCRIBE_SUBSCRIBER', 'Newsletter User') ;
define('UNSUBSCRIBE_CUSTOMER',   'Registered Customer') ;
?>