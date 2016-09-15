<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Orders');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');

define('HEADING_SEARCH_CUSTOMER', 'Customer Name') ;
define('HEADING_SEARCH_ORDER',    'Order Number') ;

define('TABLE_HEADING_COMMENTS', 'Comments');
define('TABLE_HEADING_CUSTOMERS', 'Customers');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');
define('TABLE_HEADING_CUSTOMERS_GROUPS', 'CustomerGroup'); //sppc
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');
define('TABLE_HEADING_CUST_GROUP', 'KlantenGroep');

define('ENTRY_CUSTOMER', 'Customer:');
define('ENTRY_SOLD_TO', 'SOLD TO:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'SHIP TO:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
define('ENTRY_PRINTABLE', 'Print Invoice');
define('ENTRY_NAME', 'Name:');
define('ENTRY_CITY_STATE', 'City');
define('ENTRY_CURRENCY_TYPE', 'Currency');
define('ENTRY_CURRENCY_VALUE', 'Currency Value');
define('ENTRY_STORES_ID', 'Store Activated In');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');
define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');

define('TEXT_ALL_ORDERS', 'All Orders');
define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Update');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Error: Order does not exist.');
define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');
define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The order was not updated.');
// SADESA ORDER TRACKING
define('TABLE_HEADING_TRACKING', 'Track en Trace');
define('TABLE_HEADING_TRACKNR', 'Number');
define('TABLE_HEADING_TRACKPC', 'Postal Code');
define('URL_TO_TRACKNR', 'https://securepostplaza.tntpost.nl/TPGApps/tracktrace/findByBarcodeServlet?BARCODE=');
define('URL_TO_TRACKPC', '&ZIPCODE=');
define('ENTRY_NOTIFY_TRACKING', 'Tracking nr. attach:');
define('EMAIL_TEXT_TRACKING_NUMBER', 'The TPG Post Tracktrace number of the above order has been appended.');
/*** BOF: Additional Orders Info ***/
define('TEXT_ADDITIONAL_WARNING_TEXT_PAYMENT', '<span style="background-color:' . SHOW_ORDERS_COLOR_PAYMENT . '">***check payment information***</span>');
define('TEXT_ADDITIONAL_WARNING_TEXT_SHIPPING', '<span class="smallText" style="color:' . SHOW_ORDERS_COLOR_ADDRESS . '">***check shipping information***</span>');
define('TEXT_ADDITIONAL_COLOR_ADDRESS', '<span style="color:black; background-color:' . SHOW_ORDERS_COLOR_ADDRESS . '">address mismatch</span>');
define('TEXT_ADDITIONAL_COLOR_PAYMENT', '<span style="color:black; background-color:' . SHOW_ORDERS_COLOR_PAYMENT . '">monitored payment used</span>');
define('TEXT_ADDITIONAL_COLOR_BOTH_ADDRESS_PAYMENT', '<span style="color:black; background-color:' . SHOW_ORDERS_COLOR_BOTH . '">both conditions exist</span>');
define('TEXT_COLOR_SCHEME', 'color scheme:');
define('TEXT_INFO_ADDRESS_MISMATCH', '<span style="font-weight: bold; color:' . SHOW_ORDERS_COLOR_ADDRESS . '">Billing/Shipping address mismatch</span>');
define('TEXT_INFO_SEE_OTHER_ORDERS', 'Click to see other orders');
define('TEXT_INFO_SHIPPING_METHOD', 'Ship by:');
define('TEXT_INFO_OTHER_ORDERS', '<b>Other Orders:</b> (%s)');
define('TEXT_INFO_OTHER_ORDERS_TOTALS', '&nbsp;<b>Completed:</b> %s<br>&nbsp;<b>Totaling:</b> %s');
define('TEXT_INFO_PRODUCTS_ORDERED', 'Products Ordered: ');
define('TEXT_PREVIOUS_ORDERS_YES', '<span class="smallText">Previous Orders: <b>Yes</b></span>');
define('TEXT_PREVIOUS_ORDERS_NO', '<span class="smallText">Previous Orders: <b>No</b></span>');
define('TEXT_PRODUCTS_REMAINING', '<br>&nbsp;&nbsp;(On Hand: %s)'); 
/*** EOF: Additional Orders Info ***/
define('TEXT_CUSTOMER_CODE', 'KlantNummer :' ) ;
define('TEXT_SHIPPING_METHOD', 'Verzenden Via :' ) ;
define('TEXT_ORDER_CODE', 'Order Nummer :' ) ;
define('TEXT_STORES_ID', 'Storel Name :' ) ; // MULTI STORES
define('TEXT_INFO_STORE_ACTIVATED_NAME', 'Store Name ' ) ; // MULTI STORES
define('TEXT_ALL_STORES', 'All Stores ' ) ; // MULTI STORES
define('HEADING_TITLE_STORES', 'Winkels:'); // MULTI STORES

define( 'TEXT_COLLAPSE_ADRESS', 'Customer Address and Payment') ;
define( 'TEXT_COLLAPSE_ORDER_STATUS', 'Status + History Order Status') ;
define( 'TEXT_ORDER_PRODUCTS', 'This contains the following products');
define( 'TABLE_HEADING_NEW_STATUS', 'New Order Status');
define( 'TEXT_TABS_EDIT_ORDER_ORDER_HIST_02', 'Order Status History');
define( 'TABLE_HEADING_DELETE', 'Delete ?');

define('TEXT_TABS_EDIT_ORDER_01', 'Order Adress Customer');
define('TEXT_TABS_EDIT_ORDER_02', 'Order Shipping Adress');
define('TEXT_TABS_EDIT_ORDER_03', 'Order Invoice Adress');
define('TEXT_TABS_EDIT_ORDER_04', 'Order Payment Methode');

define('TEXT_SEARCH_NAME', 'Search on Name') ;
define('TEXT_SEARCH_ORDER', 'Search on Order') ;
?>