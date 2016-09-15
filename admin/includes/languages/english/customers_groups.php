<?php
/*
   for Separate Pricing Per Customer v4.2.1 2007/11/04
*/
  
define('HEADING_TITLE', 'Groups');
define('HEADING_TITLE_SEARCH', 'Search:');

define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_DISCOUNT',            'Discount'); // totalb2b

define('ENTRY_GROUPS_NAME', 'Group&#160;Name:');
define('ENTRY_DEFAULT_DISCOUNT', 'Discount Percentage') ; // totalb2b
define('ENTRY_GROUP_NAME_MAX_LENGTH', '&#160;&#160;Maximum length: 32 characters');
define('ENTRY_GROUP_SHOW_TAX', 'Show&#160;prices&#160;with/without&#160;tax:');
define('ENTRY_GROUP_SHOW_TAX_EXPLAIN_1', '&#160;&#160;This Setting only works when \'Display Prices with Tax\'');
define('ENTRY_GROUP_SHOW_TAX_EXPLAIN_2', 'is set to true in the Configuration for your store and Tax Exempt (below) to \'No\'.');
define('ENTRY_GROUP_SHOW_TAX_YES', 'Show prices with tax');
define('ENTRY_GROUP_SHOW_TAX_NO', 'Show prices without tax');

define('ENTRY_GROUP_TAX_EXEMPT', 'Tax Exempt:'); 
define('ENTRY_GROUP_TAX_EXEMPT_YES', 'Yes'); 
define('ENTRY_GROUP_TAX_EXEMPT_NO', 'No'); 

define('ENTRY_GROUP_PAYMENT_SET', 'Set payment modules for the customer group');
define('ENTRY_GROUP_PAYMENT_DEFAULT', 'Use settings from Configuration');
define('ENTRY_PAYMENT_SET_EXPLAIN', 'If you choose <b><i>Set payment modules for the customer group</i></b> but do not check any of the boxes, default settings will still be used.');

define('ENTRY_GROUP_SHIPPING_SET', 'Set shipping modules for the customer group');
define('ENTRY_GROUP_SHIPPING_DEFAULT', 'Use settings from Configuration');
define('ENTRY_SHIPPING_SET_EXPLAIN', 'If you choose <b><i>Set shipping modules for the customer group</i></b> but do not check any of the boxes, default settings will still be used.');

define('ENTRY_GROUP_ORDER_TOTAL_SET', 'Set order total modules for the customer group');
define('ENTRY_GROUP_ORDER_TOTAL_DEFAULT', 'Use settings from Configuration');
define('ENTRY_ORDER_TOTAL_SET_EXPLAIN', 'If you choose <b><i>Set order total modules for the customer group</i></b> but do not check any of the boxes, default settings will still be used.');

define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this group?');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_GROUPS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Customers Groups)');
define('TEXT_INFO_HEADING_DELETE_GROUP', 'Delete Group');

define('ERROR_CUSTOMERS_GROUP_NAME', 'Please enter a Group Name');

define('HEADING_TITLE_GROUP_TAX_RATES_EXEMPT', 'Exempt Group from Specific Tax Rates');
define('ENTRY_GROUP_TAX_RATES_EXEMPT', 'Exempt tax rates from the customer group');
define('ENTRY_GROUP_TAX_RATES_DEFAULT', 'Use settings from Configuration (zone based)');
define('ENTRY_TAX_RATES_EXEMPT_EXPLAIN', 'If you choose <b><i>Exempt tax rates from the customer group</i></b> but do not check any of the boxes, default settings (zone based) will still be used.<br />If you have set this group to Tax Exempt "Yes" above, none of these settings will have any effect.');
?>