<?php
/*
  $Id: sitemonitor_admin.php,v 1.2 2005/09/24 Jack_mcs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('HEADING_SITEMONITOR_CONFIGURE_SETUP', 'Configuration Control');
define('TEXT_CHOOSE_INSTANCE', 'Switch Configure Files:');
define('TEXT_CHOOSE_INSTANCE_EXPLAIN', 'Setup a different configure file based upon this setting.');
define('TEXT_MAKE_SELECTION', 'Make Selection');
define('TEXT_SITEMONITOR_CONFGIURE_SETUP', 'Use this section to set up the options for SiteMonitor.
Setup the Exclude list the way you want it, following the directions to the right of that section. Then
click on the Update button at the bottom to save all of the settings to the SiteMonitor configure file.');
define('TEXT_OPTION_ALWAYS_EMAIL', 'Always Email');
define('TEXT_OPTION_ALWAYS_EMAIL_EXPLAIN', 'If this option is checked, an email will be sent whenever
the SiteMonitor script is ran. Otherwise, it is only sent when exceptions are found.');
define('TEXT_OPTION_VERBOSE', 'Verbose');
define('TEXT_OPTION_VERBOSE_EXPLAIN', 'If this option is checked, a message is printed for each exception
when ran manually.');
define('TEXT_OPTION_LOGFILE', 'Log File');
define('TEXT_OPTION_LOGFILE_EXPLAIN', 'If this option is checked, the SiteMonitor.txt log file will be
updated indicating the changes, if any, that were found.');
define('TEXT_OPTION_LOGFILE_DELETE', 'Delete Log files');
define('TEXT_OPTION_LOGFILE_DELETE_EXPLAIN', 'SiteMonitor will delete all logs files with dates older than the days entered, Leave blank to never delete.');
define('TEXT_OPTION_LOGFILE_SIZE', 'Log File Size');
define('TEXT_OPTION_LOGFILE_SIZE_EXPLAIN', 'Enter in the maximum size of the file. Once the size is reached,
the file is renamed and a new log file will be created.');

define('TEXT_OPTION_QUARANTINE', 'Quarantine Files');
define('TEXT_OPTION_QUARANTINE_EXPLAIN', 'Any new files found will be moved to the quarantine directory in admin.
If this option is used, then a new reference file will need to be re-created each time you make a
change to any files you are monitoring or they will be moved too.');

define('TEXT_OPTION_TO_EMAIL', 'To:');
define('TEXT_OPTION_TO_ADDRESS_EXPLAIN', 'Email address that the SiteMonitor email will be sent to.');
define('TEXT_OPTION_FROM_EMAIL', 'From:');
define('TEXT_OPTION_FROM_ADDRESS_EXPLAIN', 'Email address that the SiteMonitor email is sent from (useful for multiple shops).');
define('TEXT_OPTION_REFERENCE_RESET', 'Delete Reference file:');
define('TEXT_OPTION_REFERENCE_RESET_EXPLAN', 'SiteMonitor will delete your reference file after the number of days entered have passed.
Leave blank to not have it deleted.');
define('TEXT_OPTION_START_DIR', 'Start Directory:');
define('TEXT_OPTION_START_DIR_EXPLAIN', 'Usually the root of the shop. Using a different location may not result
in the best results.');
define('TEXT_OPTION_ADMIN_DIR', 'Admin Directory:');
define('TEXT_OPTION_ADMIN_DIR_EXPLAIN', 'This is the full web address to your admin. It is only
needed for the use of curl. If you don\'t want to use curl or it is not installed on your server,
this setting can be ignored.');
define('TEXT_OPTION_ADMIN_USERNAME', 'Admin Username:');
define('TEXT_OPTION_ADMIN_USERNAME_EXPLAIN', 'This is the username for your admin section. It should only be filled in if curl is to be used.');
define('TEXT_OPTION_ADMIN_PASSWORD', 'Admin Password:');
define('TEXT_OPTION_ADMIN_PASSWORD_EXPLAIN', 'This is the password for your admin section. It should only be filled in if curl is to be used.');
define('TEXT_OPTION_EXCLUDE_SELECTOR', 'Exclude Selector:');
define('TEXT_OPTION_EXCLUDE_LIST', 'Exclude List:');
define('TEXT_OPTION_EXCLUDE_LIST_EXPLAIN', 'Enter any directories that should not be monitored here.
That can be done by selecting an entry from the dropdown list above or by entering them directly into
the box on the left. If they are entered directly, or modified, be sure to surround each entry
with quotes and separate them with commas. Selecting the same item twice from the list will remove it. <span style="color:red;">At least one entry is required.</span>');
define('TEXT_OPTION_EXCLUDE_HACKED_FILES_LIST', 'Exclude Hacked Files List:');
define('TEXT_OPTION_EXCLUDE_HACKED_FILES_LIST_EXPLAIN', 'Enter any files that should not be checked when running the hacker check by entering them
directly into the box on the left. Be sure to surround each entry with quotes and separate them with commas. Erasing an item from the list will remove it.');
define('TEXT_OPTION_EXCLUDE_HACKED_CODE_SEGMENTS', 'Hacker Code:');
define('TEXT_OPTION_EXCLUDE_HACKED_CODE_SEGMENTS_EXPLAIN', 'Enter any code segments that you would like the files searched for by entering
them directly into the box on the left. Be sure to surround each entry with quotes and separate them with commas. Erasing an item from the list will remove it.');

define('ERROR_ALREADY_EXISTS', 'Not added since this location already exists in the list.');
define('ERROR_CHILD_EXISTS', 'Not added since children of this location already exists in the list.');
define('ERROR_INVALID_USERNAME', 'Your username is invalid. Please change it and try again.');
define('ERROR_PARENT_EXISTS', 'Not added since parent of this location already exists in the list.');
?>
