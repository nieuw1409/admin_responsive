<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_CONTENT_PAYPAL_LOGIN_TITLE', 'Login Inloggen met PayPal');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DESCRIPTION', 'Inschakelen inloggen met PayPal met seamless checkout voor PayPal Express Checkout betalingen<br /><br /><img src="images/icon_info.gif" border="0" />&nbsp;<a href="http://library.oscommerce.com/Package&en&paypal&oscom23&log_in" target="_blank" style="text-decoration: underline; font-weight: bold;">Bekijk online documentatie</a><br /><br /><img src="images/icon_popup.gif" border="0">&nbsp;<a href="https://www.paypal.com" target="_blank" style="text-decoration: underline; font-weight: bold;">Bezoek PayPal website</a>');

  define('MODULE_CONTENT_PAYPAL_LOGIN_TEMPLATE_TITLE', 'Inloggen met PayPal');
  define('MODULE_CONTENT_PAYPAL_LOGIN_TEMPLATE_CONTENT', 'Heeft u een PayPal account? Log an veilig in met uw PayPal om nog sneler te winkelen!');
  define('MODULE_CONTENT_PAYPAL_LOGIN_TEMPLATE_SANDBOX', 'Test Mode: De testserver is nu geselecteerd.');

  define('MODULE_CONTENT_PAYPAL_LOGIN_ERROR_ADMIN_CURL', 'Voor deze module is het nodig dat cURL in PHP is ingeschakeld, anders zal de module niet geladen worden.');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ERROR_ADMIN_CONFIGURATION', 'Deze module wordt niet geladen totdat de Client ID en Secret parameters zijn ingesteld. Wijzig eetrst de instellingen van deze module.');

  define('MODULE_CONTENT_PAYPAL_LOGIN_LANGUAGE_LOCALE', 'nl');

  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_GROUP_personal', 'Personlijke informatie');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_GROUP_address', 'Adres informatie');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_GROUP_account', 'Account informatie');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_GROUP_checkout', 'Checkout Express');

  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_full_name', 'Volledige naam');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_date_of_birth', 'Geboortedatum');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_age_range', 'Leeftijdsgrenzen');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_gender', 'Geslacht');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_email_address', 'E-mailadres');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_street_address', 'Straat');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_city', 'Plaats');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_state', 'Staat');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_country', 'Land');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_zip_code', 'Postcode');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_phone', 'Tel');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_account_status', 'Account Status (gecontroleerd)');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_account_type', 'Account Type');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_account_creation_date', 'Account aanmaakdatum');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_time_zone', 'Tijdszone');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_locale', 'Locale');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_language', 'Taal');
  define('MODULE_CONTENT_PAYPAL_LOGIN_ATTR_seamless_checkout', 'Seamless Checkout');

  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_LINK_TITLE', 'Test API Server verbinding');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_TITLE', 'API Server Verbindings Test');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_GENERAL_TEXT', 'Testen verbinding met server..');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_BUTTON_CLOSE', 'Sluiten');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_TIME', 'Verbindingstijd:');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_SUCCESS', 'Gelukt!');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_FAILED', 'Mislukt! Bekijk de  Verifieer SSL Certificaat instellingen en probeer het opnieuw.');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_CONNECTION_ERROR', 'Er is iets fout gegaan. Ververs de pagina, controleer uw instellingen en probeer het opnieuw.');

  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_URLS_LINK_TITLE', 'Toon PayPal Applicatie URLs');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_URLS_TITLE', 'PayPal Applicatie URLs');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_URLS_RETURN_TEXT', 'Redirect/Return URL:');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_URLS_PRIVACY_TEXT', 'Privacy Policy URL:');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_URLS_TERMS_TEXT', 'User Agreement URL:');
  define('MODULE_CONTENT_PAYPAL_LOGIN_DIALOG_URLS_BUTTON_CLOSE', 'Sluiten');
  
  define('TITLE_MODULE_CONTENT_PAYPAL_LOGIN_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_CONTENT_PAYPAL_LOGIN_TITLE ) ;
  define('DESCRIPTION_MODULE_CONTENT_PAYPAL_LOGIN_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_CONTENT_PAYPAL_LOGIN_CONTENT_PLACEMENT', _MODULES_CONTENT_PLACEMENT_1 . MODULE_CONTENT_PAYPAL_LOGIN_TITLE . _MODULES_CONTENT_PLACEMENT_2 ) ;
  define('DESCRIPTION_MODULE_CONTENT_PAYPAL_LOGIN_CONTENT_PLACEMENT', _MODULES_CONTENT_PLACEMENT_DESCRIPTION ) ;  

  define('TITLE_MODULE_CONTENT_PAYPAL_LOGIN_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_CONTENT_PAYPAL_LOGIN_TITLE ) ;
  define('DESCRIPTION_MODULE_CONTENT_PAYPAL_LOGIN_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    

  define('TITLE_MODULE_CONTENT_PAYPAL_LOGIN_DISPLAY_PAGES', _MODULES_DISPLAY_PAGES_1 . MODULE_CONTENT_PAYPAL_LOGIN_TITLE . _MODULES_DISPLAY_PAGES_2 ) ;
  define('DESCRIPTION_MODULE_CONTENT_PAYPAL_LOGIN_DISPLAY_PAGES', _MODULES_DISPLAY_PAGES_DESCRIPTION ) ;    
  
  define('TITLE_MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH', _MODULES_QNT_COLUMN_BOOTSTRAP ) ;
  define('DESCRIPTION_MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH', _MODULES_QNT_COLUMN_BOOTSTRAP_DESCRIPTION ) ;    
?>
