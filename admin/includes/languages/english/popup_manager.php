<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Popup Manager');

define('TABLE_HEADING_POPUPS', 'Popups');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_POPUPS_TITLE', 'Popup Title:');
define('TEXT_POPUPS_URL', 'Popup URL:');
define('TEXT_POPUPS_IMAGE', 'Image:');
define('TEXT_POPUPS_IMAGE_LOCAL', ', or enter local file below');
define('TEXT_POPUPS_IMAGE_TARGET', 'Image Target (Save To):');
define('TEXT_POPUPS_HTML_TEXT', 'HTML Text:');
define('TEXT_POPUPS_EXPIRES_ON', 'Expires On:');
define('TEXT_POPUPS_SCHEDULED_AT', 'Scheduled At:');
define('TEXT_POPUPS_POPUP_NOTE', '<strong>Popup Notes:</strong><ul><li>Use an image or HTML text for the popup - not both.</li><li>HTML Text has priority over an image</li></ul>');
define('TEXT_POPUPS_INSERT_NOTE', '<strong>Image Notes:</strong><ul><li>Uploading directories must have proper user (write) permissions setup!</li><li>Do not fill out the \'Save To\' field if you are not uploading an image to the webserver (ie, you are using a local (serverside) image).</li><li>The \'Save To\' field must be an existing directory with an ending slash (eg, popups/).</li></ul>');
define('TEXT_POPUPS_EXPIRCY_NOTE', '<strong>Expiry Notes:</strong><ul><li>Only one of the two fields should be submitted</li><li>If the popup is not to expire automatically, then leave these fields blank</li></ul>');
define('TEXT_POPUPS_SCHEDULE_NOTE', '<strong>Schedule Notes:</strong><ul><li>If a schedule is set, the popup will be activated on that date.</li><li>All scheduled popups are marked as deactive until their date has arrived, to which they will then be marked active.</li></ul>');

define('TEXT_POPUPS_DATE_ADDED', 'Date Added:');
define('TEXT_SCHEDULED', 'Scheduled at: ');
define('TEXT_EXPIRES', 'Expires At: ');
define('TEXT_POPUPS_SCHEDULED_AT_DATE', 'Scheduled At: <strong>%s</strong>');
define('TEXT_POPUPS_EXPIRES_AT_DATE', 'Expires At: <strong>%s</strong>');
define('TEXT_POPUPS_STATUS_CHANGE', 'Status Change: %s');

define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this popup?');
define('TEXT_INFO_DELETE_IMAGE', 'Delete popup image');

define('SUCCESS_POPUP_INSERTED', 'Success: The popup has been inserted.');
define('SUCCESS_POPUP_UPDATED', 'Success: The popup has been updated.');
define('SUCCESS_POPUP_REMOVED', 'Success: The popup has been removed.');
define('SUCCESS_POPUP_STATUS_UPDATED', 'Success: The status of the popup has been updated.');

define('ERROR_POPUP_TITLE_REQUIRED', 'Error: Popup title required.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Target directory does not exist: %s');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Target directory is not writeable: %s');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Error: Image does not exist.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Error: Image can not be removed.');
define('ERROR_UNKNOWN_STATUS_FLAG', 'Error: Unknown status flag.');
define('NO_POPUPS', 'No Popup');
define('NO_POPUPS_HERE', 'There are no info popups.');
define('NO_POPUPS_HERE', 'Sie haben keine Info Popups definiert.');
define('ACTIVATE_POPUP', '<br />The popup manager is disabled. Please enable it via configuration ->my shop ->Popup-Layer aktivieren?');

define( 'TEXT_POPUP_NONE', 'Popup Text not active') ; // multi stores
define( 'TEXT_POPUP_TO_STORES', 'Popup Text Active in Store(s)') ; // multi stores
define( 'TEXT_POPUPS', 'Popups' ) ;
?>