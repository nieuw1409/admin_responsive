<?php
/*
  Advanced Order Handler Rev3 for osCommerce 2.3.3
  Copyright (C) 2014  Jonas jonas@jholmster.com

  This file is part of Advanced Order Handler Rev3.

  Advanced Order Handler Rev3 is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Advanced Order Handler Rev3 is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Advanced Order Handler Rev3.  If not, see <http://www.gnu.org/licenses/>.
*/

@define('HEADING_TITLE', 'Order Handler');
@define('HEADING_TITLE_REVISION', ' <small>Rev3</small>');
@define('HEADING_TITLE_SEARCH', 'Order #:');
@define('HEADING_TITLE_SEARCH_ORDERS', 'Email Address, etc:');
@define('HEADING_TITLE_SEARCH_CUSTOMERS', 'Search Customer Address');
@define('HEADING_TITLE_STATUS', 'Status:');

@define('TABLE_HEADING_COMMENTS', 'Comments');
@define('TABLE_HEADING_CONFIGURATION', 'Order Handler Configuration');
@define('TABLE_HEADING_CUSTOMERS', 'Customers');
@define('TABLE_HEADING_ORDER_NUMBER', 'Order #');
@define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
@define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
@define('TABLE_HEADING_STATUS', 'Status');
@define('TABLE_HEADING_ACTION', 'Action');
@define('TABLE_HEADING_QUANTITY', 'Quantity');
@define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
@define('TABLE_HEADING_PRODUCTS', 'Products &nbsp; <span id="addProduct" href="#">Add Product</span>');
@define('TABLE_HEADING_PRODUCT', 'Products');
@define('TABLE_HEADING_TAX', 'VAT');
@define('TABLE_HEADING_TOTAL', 'Total');
@define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (excl. VAT)');
@define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (incl. VAT)');
@define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (excl. VAT)');
@define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (incl. VAT)');

@define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer notified');
@define('TABLE_HEADING_DATE_ADDED', 'Date added');

@define('TOOLTIP_ACTION_FIELD_TITLE', 'Action Field');
@define('TOOLTIP_ADMIN_MENU', 'Close Menu');
@define('TOOLTIP_DELETE_ORDERS', 'Delete Orders');
@define('TOOLTIP_DISABLE_POLLING', 'Disable AJAX Long-Polling');
@define('TOOLTIP_DO_NOT_SEND_EMAIL', 'Do not Send E-Mail');
@define('TOOLTIP_DO_NOT_UPDATE_STATUS', 'Do not Update Order Status');
@define('TOOLTIP_DUPLICATE_ORDER', 'Duplicate Order');
@define('TOOLTIP_EDIT_ORDERS', 'Edit Orders');
@define('TOOLTIP_ENABLE_POLLING', 'Enable AJAX Long-Polling');
@define('TOOLTIP_EXPAND_ORDER', 'Expand Order');
@define('TOOLTIP_EXPAND_TABLE', 'Expand Window');
@define('TOOLTIP_GRITTER_REMOVE', 'Clear Gritter Notifications');
@define('TOOLTIP_LOGOUT', 'Logout from osCommerce');
@define('TOOLTIP_MAIL_CONFIRMATION', 'E-Mail Order Confirmation');
@define('TOOLTIP_NAVIGATION_BOTTOM_TITLE', 'Order Management');
@define('TOOLTIP_NAVIGATION_TOP_TITLE', 'Display Settings');
@define('TOOLTIP_REFRESH', 'Refresh Table');
@define('TOOLTIP_SEARCH_ORDERS', '<p>Search by <i>Customers Name</i>, <i>Customers ID</i>, <i>E-mail Address</i> or <i>Order Number</i>.</p><p>Enter search term and press ENTER to get results as a list or click on the result to go straight to Edit Order Page.</p>');
@define('TOOLTIP_SORT_STATUS', 'Sort by Orders Status');
@define('TOOLTIP_TIMEOUT', 'Time in Seconds to next New Order Poll');
@define('TOOLTIP_UPDATE_STATUS', 'Update Orders Status');

@define('ENTRY_ADD_FIELD', 'Add a field');
@define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
@define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
@define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
@define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
@define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
@define('ENTRY_CURRENCY', 'Currency:');
@define('ENTRY_CUSTOMER', 'Customer');
@define('ENTRY_DATE_LAST_UPDATED', 'Last Updated:');
@define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
@define('ENTRY_DELIVERY_TO', 'DELIVER TO:');
@define('ENTRY_ENVELOPE', 'Envelope');
@define('ENTRY_EXPORT', ' Export');
@define('ENTRY_INVOICE', 'Invoice');
@define('ENTRY_IPADDRESS', 'IP-Address:');
@define('ENTRY_IPISP', 'ISP:');
@define('ENTRY_LABELS', '  Labels:');
@define('ENTRY_MAIL_CUSTOMER', 'Mail Customer');
@define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
@define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer: ');
@define('ENTRY_NOTIFY_NO', 'No');
@define('ENTRY_NOTIFY_YES', 'Yes');
@define('ENTRY_NOTIFY', 'Notify Customer: Yes');
@define('ENTRY_ORDER_NUMBER', 'Order Number');
@define('ENTRY_PAYMENT_METHOD', 'Payment method');
@define('ENTRY_PRINT_DATE', 'Print Date:');
@define('ENTRY_PRINTABLE', 'Print Invoice');
@define('ENTRY_SELECTED', 'With Selected - Invoice:');
@define('ENTRY_SHIP_TO', 'SHIP TO:');
@define('ENTRY_SHIPPING_ADDRESS', 'Delivery address:');
@define('ENTRY_SHIPPING_METHOD', 'Shipping Method:');
@define('ENTRY_SHIPPING', 'Shipping:');
@define('ENTRY_SOLD_TO', 'SOLD TO:');
@define('ENTRY_STATUS', 'Status:');
@define('ENTRY_SUB_TOTAL', 'Sub-Total:');
@define('ENTRY_TAX', 'Tax:');
@define('ENTRY_TOTAL', 'Total:');
@define('ENTRY_UPDATE_STATUS', 'Update status');
@define('ENTRY_XML', 'Export');

// Contact Pop-up
@define('ENTRY_CONTACT_CANCEL', 'Cancel');
@define('ENTRY_CONTACT_EMAIL', '*Email:');
@define('ENTRY_CONTACT_FALSE', 'Unfortunately, your message could not be verified.');
@define('ENTRY_CONTACT_GOODBYE', 'Goodbye...');
@define('ENTRY_CONTACT_INVALID_EMAIL', 'Email is invalid. ');
@define('ENTRY_CONTACT_LOADING', 'Loading...');
@define('ENTRY_CONTACT_MESSAGE', '*Message:');
@define('ENTRY_CONTACT_NAME', '*Name:');
@define('ENTRY_CONTACT_ORDER', 'Order:');
@define('ENTRY_CONTACT_REQUIRED_EMAIL', 'Email is required. ');
@define('ENTRY_CONTACT_REQUIRED_MESSAGE', 'Message is required.');
@define('ENTRY_CONTACT_REQUIRED_NAME', 'Name is required. ');
@define('ENTRY_CONTACT_SEND_COPY', 'Send me a copy');
@define('ENTRY_CONTACT_SEND_US', 'Send us a message:');
@define('ENTRY_CONTACT_SEND', 'Send');
@define('ENTRY_CONTACT_SENDING', 'Sending...');
@define('ENTRY_CONTACT_SUBJECT_MESSAGE', 'Re. Order: ');
@define('ENTRY_CONTACT_SUBJECT', 'Subject:');
@define('ENTRY_CONTACT_THANK_YOU', 'Thank you!');
@define('ENTRY_CONTACT_TRUE', 'Your message was successfully sent.');

@define('TOOLTIP_CONTACT_NAME', 'Name of the person sending this email');
@define('TOOLTIP_CONTACT_EMAIL', 'Recipient Email Address');
@define('TOOLTIP_CONTACT_ORDERS_ID', 'Order Number (Optional)');
@define('TOOLTIP_CONTACT_SUBJECT', 'Email Subject');
@define('TOOLTIP_CONTACT_MESSAGE', 'Email Message');

@define('TEXT_INFO_DISABLE_MENU', 'Disable Right-Click Menu');
@define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
@define('TEXT_INFO_DELETE_INTRO', 'Do you really want delete this order?');
@define('TEXT_INFO_ORDER_DELETED', 'Thank you, the orders have been deleted, you can close this window.');

// Order Handler Configuration
@define('TEXT_CONFIGURATION_CLEAR_SELECTION', 'Clear Selections');
@define('TOOLTIP_CONFIGURATION_CLEAR_SELECTION', 'Remove Order from Order Table instead of changing status after updating Order Status.<br>(This only affects Table Layout, <u>not</u> the database.)');

@define('TEXT_CONFIGURATION_CUSTOM_KEY', 'Keyboard Shortcut - Custom');
@define('TOOLTIP_CONFIGURATION_CUSTOM_KEY', 'Bind a key to to a Custom Function.
  <br>
  <i>Sensitive to Ctrl, Alt, Shift.</i>');

@define('TEXT_CONFIGURATION_DEBUG_TIME', 'Debug Page Load Time');
@define('TOOLTIP_CONFIGURATION_DEBUG_TIME', 'Show a Gritter Notification with the time it took for the page to load.
  <br>
  This will also write to the page_parse_time log and the developer console.');

@define('TEXT_CONFIGURATION_NEWWIN', 'Open Edit Order in New Tab');
@define('TOOLTIP_CONFIGURATION_NEWWIN', 'Select this option if you want the \'Edit Order\' link to open in a new Tab or Window.');

@define('TEXT_CONFIGURATION_PHPMAILER_CLASS', 'Use PHPMailer for Email');
@define('TOOLTIP_CONFIGURATION_PHPMAILER_CLASS', 'Use the faster and presumed more secure PHPMailer Class when sending Email.');

@define('TEXT_CONFIGURATION_REFRESH_KEY', 'Keyboard Shortcut - Refresh');
@define('TOOLTIP_CONFIGURATION_REFRESH_KEY', 'Bind a key to refresh the current displayed table records.
    <br>
  <i>Sensitive to Ctrl, Alt, Shift.</i>');

@define('TEXT_CONFIGURATION_PRINT', 'Direct Print');
@define('TOOLTIP_CONFIGURATION_PRINT', 'Select this if you want a Print Dialog Box to appear as soon as the invoices has loaded. This option will also close the invoice tab as soon as Print Dialog Box is closed.');

@define('TEXT_CONFIGURATION_REMOVE_KEYS', 'Remove Keyboard Shortcuts');
@define('TOOLTIP_CONFIGURATION_REMOVE_KEYS', 'Remove All Keyboard Event Handlers.');

@define('TEXT_CONFIGURATION_SHOW_OVERLAY', 'Toggle Dialog Overlay');
@define('TOOLTIP_CONFIGURATION_SHOW_OVERLAY', 'Toggle Display of Overlay when Dialog is Visible.');

@define('TEXT_CONFIGURATION_TOGGLE_HEADER', 'Toggle Header');
@define('TOOLTIP_CONFIGURATION_TOGGLE_HEADER', 'Show/Hide Header.');

@define('TEXT_CONFIGURATION_TOGGLE_GRITTER', 'Toggle Gritter Notifications');
@define('TOOLTIP_CONFIGURATION_TOGGLE_GRITTER', 'Enable/Disable Notifcations.');

@define('TEXT_CONFIGURATION_TOGGLE_TOOLTIP', 'Toggle Tooltips');
@define('TOOLTIP_CONFIGURATION_TOGGLE_TOOLTIP', 'Enable/Disable Bootstrap Tooltips.');


@define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock Products');
@define('TEXT_INFO_ORDER_BY_PRODUCTS_QUANTITY', 'Sort by Products Quantity');
@define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
@define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last modified:');
@define('TEXT_INFO_PAYMENT_METHOD', 'Payment method:');

@define('TEXT_ALL_ORDERS', 'All Orders');
@define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');

@define('TOOLTIP_ADMIN_MENU', 'Close Admin Menu');
@define('TOOLTIP_SORT_STATUS', 'Sort Orders by Status');

@define('EMAIL_SEPARATOR', '------------------------------------------------------');
@define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
@define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
@define('EMAIL_TEXT_SUBJECT', 'Order Status');
@define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
@define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
@define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');
@define('EMAIL_TEXT_PRODUCTS', 'Products');
@define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
@define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n\n" . '------------------------------------------------------<br/>');
@define('EMAIL_TEXT_COMMENTS_UPDATE', 'Comments for your order:' . "\n\n%s\n\n");

@define('ERROR_ORDER_DOES_NOT_EXIST', 'Order does not exist.');
@define('ERROR_NO_ORDERS_SELECTED', 'No orders selected.');
@define('SUCCESS_ORDER_UPDATED', 'Success: Order has been successfully updated.');
@define('WARNING_ORDER_NOT_UPDATED', 'Warning: Nothing to change. The order was not updated.');
//begin PayPal_Shopping_Cart_IPN
@define('TABLE_HEADING_PAYMENT_STATUS', 'Status');
//end PayPal_Shopping_Cart_IPN

@define('TEXT_INFO_CUSTOMER_SERVICE_ID','Added by:');

//AJAX Orders Editor
define('DIV_ADD_PRODUCT_HEADING', 'Add a product');
define('ADD_PRODUCT_SELECT_PRODUCT', 'Name or model of product:');
define('PRODUCTS_SEARCH_RESULTS', 'Search Results: ');
define('PRODUCTS_SEARCH_NO_RESULTS', 'There is no results.');
define('TEXT_PRODUCT_OPTIONS', 'Options');
define('TEXT_NO_PICTURE', 'No Picture Available');

// Order Maker
define('IMAGE_CREATE_ORDER', 'Create New Order');
define('BOX_CUSTOMERS_CREATE_ORDER', 'Create Order');
