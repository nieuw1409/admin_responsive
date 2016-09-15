<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Newsletter Manager');

define('TABLE_HEADING_NEWSLETTERS', 'Newsletters');
define('TABLE_HEADING_SIZE', 'Size');
define('TABLE_HEADING_MODULE', 'Module');
define('TABLE_HEADING_SENT', 'Sent');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_NEWSLETTER_MODULE', 'Module:');
define('TEXT_NEWSLETTER_TITLE', 'Newsletter Title:');
define('TEXT_NEWSLETTER_CONTENT', 'Content:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'Date Added:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Date Sent:');

define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this newsletter?');

define('TEXT_PLEASE_WAIT', 'Please wait .. sending emails ..<br /><br />Please do not interrupt this process!');
define('TEXT_FINISHED_SENDING_EMAILS', 'Finished sending e-mails!');

define('ERROR_NEWSLETTER_TITLE', 'Error: Newsletter title required');
define('ERROR_NEWSLETTER_MODULE', 'Error: Newsletter module required');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before deleting it.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before editing it.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before sending it.');
// BOF Separate Pricing Per Customer
define('TABLE_HEADING_CUSTOMERS_GROUPS', 'Customer Groups');
define('TEXT_SEND_TO_CUSTOMERS_GROUPS', '<div align=\'center\' style=\'margin-top: 10px; margin-bottom: 5px; padding-top: 5px; font-weight: bold; border-top: 1px solid black;\'>Send to these customer groups:</div>');
// EOF Separate Pricing Per Customer

define('TEXT_INFO_HEADING_DELETE_NEWSLETTER', 'Delete Newsletter') ;
define('TEXT_INFO_HEADING_EDIT_NEWSLETTER', 'Change Newsletter') ;

define( 'TEXT_TABS_EDIT_NEWSLETTER_01', 'Stores' ) ;
define( 'TEXT_TABS_EDIT_NEWSLETTER_02', 'Customer Groups' ) ;

define( 'TEXT_NEWSLETTER_TOTAL_CUSTOMERS', 'Total Customers') ;
define( 'TEXT_NEWSLETTER_TOTAL_CUST_NEWSLETTER', 'Customers with Active Newsletters') ; 
define( 'TEXT_NEWSLETTER_TOTAL_CUST_RECEIVE', 'Customers who will Receive Newsletter') ;
define( 'TEXT_NEWSLETTER_TOTAL_CUST_PROD_NOTI', 'Customers with Active Product Notification') ; 
define( 'TEXT_NEWSLETTER_TOTAL_CUST_RECEIVE_PN', 'Customers who will Receive Product Notification') ;
define( 'TEXT_INFO_HEADING_SEND_NEWSLETTER', 'Send Newsletter') ;
define( 'TEXT_INFO_SEND_NEWSLETTERS', 'Total send Newsletter : %s') ;
define( 'TEXT_INFO_HEADING_SENDING_NEWSLETTER', 'Sending Newsletters' ) ;
define( 'TEXT_INFO_HEADING_SEND_NEWSLETTER', 'Send All Newsletters' ) ;

define( 'TEXT_NEWSLETTER_ACTIVE_STORES', 'Send to Store(s) : ') ;
define( 'TEXT_NEWSLETTER_ACTIVE_CUST_GROUPS', 'Send to Customer Group(s) : ') ;

define('TEXT_INFO_CUST_GROUPS', 'If nonen of the customer groups is chosen the Newsletter will be send to everyone with a active newletter');
define('TEXT_NEWSLETTER_UNSUBSCRIBE' , 'Text Unsubscribe Newsletter:' ) ;
define('TEXT_NEWSLETTER_SEND_UNSUBSCRIBE' , 'Unsubscribe' ) ;
define('TEXT_INFO_HEADING_NEW_NEWSLETTER', 'New Newsletter') ;

define('TEXT_NEWSLETTER_TOTAL_SUBSCRIBERS', 'Subscribers:') ;
define('TEXT_NEWSLETTER_TOTAL_SUBSR_NEWSLETTER', 'Active Subscribers :' ) ;
?>