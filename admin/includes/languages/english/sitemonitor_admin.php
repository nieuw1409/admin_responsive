<?php
/*
  $Id: sitemonitor_admin.php,v 1.2 2005/09/24 Jack_mcs of oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('HEADING_TITLE', 'SiteMonitor&nbsp;Admin');
define('HEADING_TITLE_AUTHOR', 'by Jack_mcs from <a href="http://www.oscommerce-solution.com/" target="_blank"><span style="font-family: Verdana, Arial, sans-serif; color: sienna; font-size: 12px;">oscommerce-solution.com</span></a>');
define('HEADING_TITLE_SUPPORT_THREAD', '<a href="http://forums.oscommerce.com/topic/221438-sitemonitor/" target="_blank"><span style="color: sienna;">(visit the support thread)</span></a>');

define('TEXT_MISSING_VERSION_CHECKER', 'Version Checker is not installed. See <a href="http://addons.oscommerce.com/info/7148" target="_blank">here</a> for details.');

define('TEXT_SITEMONITOR_ADMIN', 'Use the top Update button to delete the existing reference
file and then create a new one. Use the second Update button to update the reference
file without deleting it. The third Update button is used to run the SiteMonitor script as a cron job would.
And the bottom Update button will check all of the shops files for known hacker-type code. It is important
to realize that some such code is valid so found files are not necessarily infected files. But, if found, you should
look more closely at them.');
define('TEXT_SITEMONITOR_DELETE_REFERENCE', 'Delete Reference File');
define('TEXT_SITEMONITOR_EXECUTE', 'Execute Sitemonitor');
define('TEXT_SITEMONITOR_MANUAL', 'Manually Execute Sitemonitor');
define('TEXT_SITEMONITOR_HACKER_CHECK', 'Manually Check for Hacked Files');

define('TEXT_CHOOSE_INSTANCE', 'Switch Configure Files:');
define('TEXT_CHOOSE_INSTANCE_EXPLAIN', 'Select the configure file to run.');
define('TEXT_LOG_READER', 'View a Log File:');
define('TEXT_SELECT_LOG_FILE', 'Select a log file');

define('TEXT_SITEMONITOR_DELETE_EXPLAIN', 'Delete the reference file and create a new one.');
define('TEXT_SITEMONITOR_EXECUTE_EXPLAIN', 'Run Sitemonitor. Reference file is not deleted.');
define('TEXT_SITEMONITOR_MANUAL_EXPLAIN', 'Execute the SiteMonitor script locally. Use browsers back button to return here.');
define('TEXT_SITEMONITOR_HACKER_CHECK_EXPLAIN', 'Check all files for known hacker type code. Found files are suspect but not necessarily infected.');

define('TEXT_HACKED_FILES', 'Hacked Files Found');
define('TEXT_NO_HACKED_FILES', 'No hacked files were found.');
define('TEXT_HACK_TITLE_DATE_CMP', 'Dates Match');
define('TEXT_HACK_TITLE_DELETE', 'Delete');
define('TEXT_HACK_TITLE_NOTES', 'Notes:');
define('TEXT_HACK_TITLE_REF', 'Ref');
define('TEXT_HACK_TITLE_LINE', 'Line');
define('TEXT_HACK_TITLE_FILE', 'File');
define('TEXT_HACK_TITLE_HACKER_CODE', 'Hacker Code');
define('TEXT_HACK_TITLE_EXCLUDE', 'Exclude');
define('TEXT_HACK_TITLE_USE_EXCLUDE_FILE', '<b>Use Exclude File:</b> Use the exclude file, if present, to filter the results.');
define('TEXT_HACK_TITLE_OVERWRITE_EXCLUDE_FILE', '<b>Overwrite Exclude File:</b> Create a new exclude file, if checked, or add to the existing one.');
define('TEXT_HACK_EXPLAIN_REF', '- If the Ref column is checked, it means the file was found in the reference file and is unlikely to be a hacked file.');
define('TEXT_HACK_EXPLAIN_LINE', '- The entry in the Line column refers to the line the suspected hacker code was found in the file.');
define('TEXT_HACK_EXPLAIN_LOCN', '- The majority of hacker code is inserted in the first or last few lines of a file. Use that as a guide to determine if a file has been hacked.');
define('TEXT_HACK_EXPLAIN_FILE', '- The entry in the File column refers to the suspected hacked file. Clicking on it will display a popup displaying the contents of the file.');
define('TEXT_HACK_EXPLAIN_DATES_MATCH', '- If the Dates Match column is checked, it means the suspect file has the same date as that recorded in the reference file.');

define('HACKER_COLOR_1', '#000');
define('HACKER_COLOR_2', '#ff0000');
define('HACKER_COLOR_3', '#B88A00');
define('HACKER_COLOR_4', '#6633FF');
define('TEXT_HACK_EXPLAIN_COLOR', '- The color of the entry provides the following information:
<ul>
<li style="color:'.HACKER_COLOR_1.';">Found in the reference file</li>
<li style="color:'.HACKER_COLOR_2.';">Not in the reference file - higher probability of it being a hacker file</li>
<li style="color:'.HACKER_COLOR_3.';">A php file was found in an images directory - not normal</li>
<li style="color:'.HACKER_COLOR_4.';">A php file was found in an images directory and was not in the reference file - higher probability of it being a hacker file</li>
</ul>
');

define('TEXT_HACK_RETURN_MSG', 'Checked %d directories containing a total of %d files. Skipped %d files. <span style="color:red; font-weight:bold;">%d</span> suspected <span style="color:red; font-weight:bold;">hacked files found</span>. %s');

define('TEXT_FOUND_NEW_FILE', 'Found a new file named $s');
define('TEXT_TIME_NOT_CHECKED', 'Time differences not checked due to deleted file(s)');

define('ERROR_CONFIGURE_FILE_INVALID', '<sapn style="color:red; font-weight:bold;">!!! Configure file is invalid !!!</span>');
define('ERROR_CONFIGURE_FILE_FAILED_OPEN', '<sapn style="color:red; font-weight:bold;">!!! Could not open the Configure file !!!</span>');
define('ERROR_FAILED_CREATE_QUARATINE_DIRECTORY', 'Failed to create Quarantine directory for: %s');
define('ERROR_FAILED_REFERENCE_NOT_FOUND', 'Reference File for this instance not found. Run SiteMonitor before using this option.');
define('ERROR_FAILED_REFERENCE_READ', 'Failed to read Reference File');
define('ERROR_FAILED_FILE_OPEN', 'Failed to open file - %s');
define('ERROR_FAILED_FILE_WRITE', 'Failed to write to file - %s');
define('ERROR_FAILED_MODE_CHANGE', 'Failed to change mode of file - %s');
define('ERROR_FAILED_EXCLUDE_LIST_FORMAT', 'FAILED: Exclude list does not begin with quotes.');
define('ERROR_FAILED_MISSING_QUOTES', 'FAILED: %s is not enclosed in quotation marks.');
define('ERROR_FAILED_PERMISSION_MISMATCH', 'Permissions Mismatch on %s. Currently set to %s. Was set to %s');

define('ERROR_ADMIN_NAME', 'WARNING: Your admin name is admin. That should be changed.');
define('ERROR_FILE_MANAGER', 'WARNING: The file_manager.php file in admin is a security risk. It should be deleted.');
define('ERROR_IMAGES_NOT_PROTECTED', 'WARNING: Your images directory is not protected by a .htaccess file.');
define('ERROR_IMAGES_HAS_PHP', 'WARNING: Your images directory contains non-image type files which is, generally, not correct.');
define('ERROR_LOG_NOT_WRITEABLE', 'The SiteMonitor_log.txt file cannot be written to.');
define('ERROR_REFERENCE_NOT_WRITEABLE', 'The sitemonitor_reference.php file cannot be written to.');
?>
