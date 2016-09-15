<?php
/*
  $Id: newsletter_subscription.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Newsletters');
define('HEADING_TITLE', 'Subscribe to newsletter');

define('TEXT_INFORMATION', '<strong>Thank you for your newsletter subscription.</strong><br />A confirmation email has been sent.');

define('EMAIL_WELCOME_SUBJECT', 'Welcome to ' . STORE_NAME);

define('EMAIL_WELCOME', 'Thank you for your ' . STORE_NAME . ' Newsletter subscription.<br /><br />This message informs you that your registration was successfully processed and you are now registered for our newsletter. <br /><br />If you did not register to receive our Newsletter, you can unsubscribe by clicking the unsubscribe link at the bottom of this email.');

define('TEXT_PRIVACY_EMAIL', '<br /><br />Your information is confidential and will never be retransmitted, please visit our privacy policy : ' . '<a href="' . tep_href_link(FILENAME_PRIVACY, '', 'NONSSL') . '">' . tep_href_link(FILENAME_PRIVACY, '', 'NONSSL') . '</a>');

define('NL_UNSUBSCRIBE_LINK', 'To unsubscribe:<a href="' . tep_href_link(FILENAME_NEWSLETTER_UNSUBSCRIBE, '%s', 'NONSSL') . '">' . 'Unsubscribe' . '</a>');

define('UNSUBSCRIBE_TEXT', 'To unsubscribe') ;

define('TEXT_EMAIL_HTML', 'HTML');
define('TEXT_EMAIL_TXT', 'Text');

define('PREVENIR_EMAIL_NEW_INSCRIT_NL', 'oui');
define('EMAIL_NEW_INSCRIT_NL', 'A new subscribe to the Newsletter');

//mis en page html-----------------------------------------------
define('EMAIL_START_HTML', '<html><head> </head><body>');
define('EMAIL_STOP_HTML', '<br /></body></html>');
define('EMAIL_SPAN_START_STYLE', '<span style="font-family:Verdana, Arial, sans-serif; font-size:12px;">');
define('EMAIL_SPAN_STOP_STYLE', '</span>');
//fin mis en page html-----------------------------------------------
?>