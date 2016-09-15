<?php
/*
  $Id: dutch.php,v 1.1061 2005/01/21 00:18:31 gjw Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'de_DE'
// on FreeBSD 4.0 I use 'de_DE.ISO_8859-1'
// this may not work under win32 environments..
// setlocale(LC_TIME, 'nl_NL.ISO_8859-1');
// @setlocale (LC_TIME, 'Dutch');
// 2.3.3.3 @setlocale(LC_TIME, 'nl_NL.ISO_8859-1' , 'Dutch');
// 2.3.3.3 @setlocale (LC_TIME, 'Dutch');
@setlocale(LC_ALL, array('nl_NL.utf-8', 'nl_NL.utf8', 'nld_nld')); // 2.3.3.3
define('DATE_FORMAT_SHORT', '%d.%m.%Y');       // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d. %B %Y');   // this is used for strftime()
//define('DATE_FORMAT', 'd.m.Y');                // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'd.m.Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('JQUERY_DATEPICKER_I18N_CODE', 'nl'); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
//define('JQUERY_DATEPICKER_FORMAT', 'dd/mm/yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate
define('JQUERY_DATEPICKER_FORMAT', 'dd/mm/yyyy'); // see http://docs.jquery.com/UI/Datepicker/formatDat
////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="nl"');

// charset for web pages and emails
//define('CHARSET', 'iso-8859-1');
define('CHARSET', 'utf-8');


// page title
define('TITLE', 'Verander');
define('TITLE', 'naar behoeven deze en bovenstaande regel');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administratie');
define('HEADER_TITLE_SUPPORT_SITE',   'Support website');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online catalogus');
define('HEADER_TITLE_ADMINISTRATION', 'Administratie');
define('HEADER_TITLE_APPLICATIONS',   'Programma s');
define('HEADER_TITLE_LANGUAGES',      'Taal');
define('HEADER_TITLE_STORES',         'Winkels');
define('HEADER_TITLE_LOGOFF',         'Uitloggen');
define('HEADER_NAV_TEXT_ADD_CUSTOMER',   'Toevoegen Klant');
define('HEADER_NAV_TEXT_ADD_ORDERS',     'Toevoegen Order');
define('HEADER_NAV_TEXT_PRODUCTS',       'Producten');
define('HEADER_NAV_TEXT_ACTIVE_CUST',    'Actieve Klanten');
define('HEADER_NAV_TEXT_ORDERS',         'Orders');
define('HEADER_NAV_TEXT_CUSTOMERS',      'Klanten');
define('HEADER_NAV_TEXT_DIRECT_ACCESS',  'Direct Naar');

// text for gender
define('MALE', 'Man');
define('FEMALE', 'Vrouw');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// configuration box text in includes/boxes/configuration.php
//define('BOX_HEADING_CONFIGURATION', 'Configuratie');
//define('BOX_CONFIGURATION_MYSTORE', 'Mijn winkel');
//define('BOX_CONFIGURATION_LOGGING', 'Logging');
// define('BOX_CONFIGURATION_CACHE', 'Cache');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');
define('BOX_CONFIGURATION_ADMINISTRATORS', 'Administrators');
define('BOX_CONFIGURATION_STORE_LOGO', 'Winkel Logo');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Betaling');
define('BOX_MODULES_SHIPPING', 'Verzenden');
define('BOX_MODULES_ORDER_TOTAL', 'Totaal bestelling');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalogus');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categorieen / Artikelen');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Artikelopties');
define('BOX_CATALOG_MANUFACTURERS', 'Fabrikanten');
define('BOX_CATALOG_REVIEWS', 'Recensies');
define('BOX_CATALOG_SPECIALS', 'Speciale aanbiedingen');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Verwachte producten');
define('TEXT_IMAGE_NONEXISTENT','Geen afbeelding');
define('BOX_CATALOG_CATEGORIES_DISCOUNT_CATEGORIES',     'Kortings Categorieen');// BOF qpbpp
define('BOX_CATALOG_XSELL_PRODUCTS',                     'Gerelateerde Produkten'); // XSell 
define('HEADING_TITLE_SEARCH',                           'Zoeken:');// XSell 
define('TEXT_CACHE_XSELL_PRODUCTS',                      'Gerelateerde Produkten'); // XSell 
define('BOX_CATALOG_PRODUCTS_SORTER',                    'Volgorde Product'); // BOF Product Sort
define('BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI',          'Meerdere Producten Manager');  // bof products multi copy etc
define('BOX_CATALOG_QUICK_PRICE_UPDATE',                 'Snelle PrijsAanpassing');  // quick prices update
define('BOX_CATALOG_EASYPOPULATE',                       'Inlezen Gegevens');  // easy populate 276
define('BOX_CATALOG_SPECIALS_BY_CATEGORIES',             'Speciale aanbiedingen Per Categorie'); // sppc 716
define('BOX_CATALOG_DISCOUNT_BY_CATEGORIES',             'Kortingen Per Categorie');  //TotalB2B start
define('BOX_CATALOG_DISCOUNT_BY_CATEGORIES_MANUFACTURER','Kortingen Per Categorie/Fabrikant');  //TotalB2B start
define('BOX_CATALOG_DISCOUNT_BY_MANUFACTURER',           'Kortingen Per Fabrikant');  //TotalB2B start
define('BOX_CATALOG_AVAILABILITY',                       'Product Voorraad Status');  // availability
define('BOX_CATALOG_QUICK_INVENTORY',                    'Aanpassen Voorraad');  // quick inventory 8672
define('BOX_CATALOG_GOOGLE_TAXONOMY',                    'Google Taxonomy Code');  // quick inventory 8672

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Klanten');
define('BOX_CUSTOMERS_CUSTOMERS', 'Klanten');
// 2.3.4 define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_ADMIN_CREATE_ACCOUNT',                       'Toevoegen Nieuwe Klant');// BOF Admin Create Account 1.0
define('BOX_CUSTOMERS_CHANGE_PASSWORD',                  'Wijzig Wachtwoord');// Change Password
define('BOX_CUSTOMERS_CUSTOMER_GROUPS',                  'Klanten Groepen');// SPPC customers groups
define('BOX_CUSTOMERS_ADD_ORDERS',                       'Toevoegen Nieuwe Order');// manual add order

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locatie / BTW');
define('BOX_TAXES_COUNTRIES', 'Landen');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Zones Geografisch');
define('BOX_TAXES_TAX_CLASSES', 'BTW tariefgroepen');
define('BOX_TAXES_TAX_RATES', 'BTW tarieven');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Rapporten');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Bekeken artikelen');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Gekochte artikelen');
define('BOX_REPORTS_ORDERS_TOTAL', 'Totaalbedrag klantenorders');
define('BOX_REPORTS_ORDERS_TRACKING',                     'Order Tracking'); // order tracking v30
define('BOX_REPORTS_STOCK_LEVEL',                         'Overzicht Lage Voorraad'); // low stock report
define('BOX_REPORTS_SUPERTRACKER',                        'Supertracker');  //supertracker 3.5
define('BOX_REPORTS_PRODUCTS_KEYWORDS',                   'ZoekTermen');  // search product keywords
define('BOX_REPORTS_MARGIN_REPORT_PRODUCTS', 			  'Marge Overzicht Producten');  // bof product cost price
define('BOX_REPORTS_MARGIN_REPORT_ORDERS', 			      'Marge Overzicht Orders');  // bof product cost price
define('BOX_REPORTS_MONTHLY_SALES',                       'Overzicht Per Maand'); // monthly report
define('BOX_REPORTS_STATS_LOW_STOCK_ATTRIB',              'Voorraad Overzicht'); //++++ QT Pro: Begin Changed code

// tools text in includes/boxes/emails.php
define('BOX_HEADING_EMAILS',                              'Email Tekst');
define('BOX_EMAIL_TEXT_NEW_ORDER',                        'Tekst Nieuwe Order Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEW_ORDER_ADMIN',                  'Tekst Nieuwe Order Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEW_CUSTOMER',                     'Tekst Nieuwe Klant Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEW_CUSTOMER_ADMIN',               'Tekst Nieuwe Klant Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_UPD_ORDER_ADMIN',                  'Tekst Update Order Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_PASSWORD_FORGOTTEN',               'Tekst Wachtwoord Vergeten Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_CUSTOMER_REVIEW',                  'Tekst Nieuwe Beoordeling Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_CONTACT_US',                       'Tekst Contact US Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_WISHLIST',                         'Tekst WensLijst Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_SUBSCRIBE',             'Tekst Nieuwsbrief Aanmelden Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_UNSUBSCRIBE',           'Tekst Nieuwsbrief Afmelden Email' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_WISHLIST_ADMIN',                   'Tekst WensLijst Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_SUBSCRIBE_ADMIN',       'Tekst Nieuwsbrief Aanmelden Email Admin' );  // bof email text for different emails
define('BOX_EMAIL_TEXT_NEWSLETTER_UNSUBSCRIBE_ADMIN',     'Tekst Nieuwsbrief Afmelden Email Admin' );  // bof email text for different emails

// sitemonitor text in includes/boxes/sitemonitor.php
define('BOX_HEADING_SITEMONITOR', 'SiteMonitor');
define('BOX_SITEMONITOR_ADMIN', 'Admin');
define('BOX_SITEMONITOR_CONFIG_SETUP', 'Configuratie');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Gereedschap');
define('BOX_TOOLS_ACTION_RECORDER', 'Actie Recorder');
define('BOX_TOOLS_BACKUP', 'Database backup-manager');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner-manager');
define('BOX_TOOLS_CACHE', 'Cache-manager');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Talen-manager');
define('BOX_TOOLS_FILE_MANAGER', 'File-manager');
define('BOX_TOOLS_MAIL', 'Stuur Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Nieuwsbrief-manager');
define('BOX_TOOLS_SEC_DIR_PERMISSIONS', 'Beveiligings Directory Permissions');
define('BOX_TOOLS_SERVER_INFO', 'Server info');
define('BOX_TOOLS_VERSION_CHECK', 'Version Checker');
define('BOX_TOOLS_WHOS_ONLINE', 'Wie is er online?');
define('BOX_TOOLS_GOOGLE_XMLMAPS',                    'Google XML SiteMaps' );  // bof google xml maps ultimate seu urls fwr 7704
define('BOX_TOOLS_DATABASE_OPTIMIZER',                'Database Optimizer');
define('BOX_TOOLS_BANNED_IP',                         'Geweigerde IP Adres');
define('BOX_TOOLS_FILEMANAGER', 					  'Bestanden Winkel'); 
define('BOX_TOOLS_QTPRODOCTOR',                       'QTPro Doctor'); //++++ QT Pro: Begin Changed code
define('BOX_TOOLS_REMOVE_IMAGES',                     'Verwijder Overtollige Afbeeldingen'); // remove unused images 8157
define('BOX_TOOLS_FAQ',                               'VraagEnAntwoord Manager'); //FAQDesk 2.3
define('BOX_TOOLS_POPUP_MANAGER',                     'PopUp Venster Manager'); // popup
define('BOX_TOOLS_SUBSCRIBER_MANAGER',                'Nieuwsbrief Gebruikers'); // newletter

define('BOX_HEADING_INFORMATION',                     'Informatie manager'); // info manager
define('BOX_REPORTS_GOOGLEMAP',                       'Google Kaart'); // google maps + directions
define('BOX_REPORTS_GOOGLE_FEEDS',                    'Google Feeds'); // google feeds voor shopping 

define('BOX_CATALOG_DEFINE_MAINPAGE',                 'Tekst Start Pagina');// define_mainpage
define('BOX_CATALOG_DEFINE_PRIVACY',                  'Tekst Privacy Pagina');// define_mainpage
define('BOX_CATALOG_DEFINE_CONDITIONS',               'Tekst Alg. Voorwaarde Pagina');// define_mainpage
define('BOX_CATALOG_DEFINE_SHIPPING',                 'Tekst Verzend Pagina');// define_mainpage

define('BOX_CATALOG_DEFINE_MOBILE_MAINPAGE',          'Tekst Mobile Start Pagina');// define_mainpage
define('BOX_CATALOG_DEFINE_MOBILE_PRIVACY',           'Tekst Mobile Privacy Pagina');// define_mainpage
define('BOX_CATALOG_DEFINE_MOBILE_CONDITIONS',        'Tekst Mobile Alg. Voorwaarde Pagina');// define_mainpage
define('BOX_CATALOG_DEFINE_MOBILE_SHIPPING',          'Tekst Mobile Verzend Pagina');// define_mainpage
					

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Lokalisatie');
define('BOX_LOCALIZATION_CURRENCIES', 'Valuta');
define('BOX_LOCALIZATION_LANGUAGES', 'Talen');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Order status');

// localizaion box text in includes/boxes/stores.php
define('BOX_HEADING_STORES', 'Websites/Winkels');
define('BOX_STORES_STORES', 'Winkels');

// orders box text in includes/boxes/orders.php
define('BOX_HEADING_ORDERS', 'Orders');
define('BOX_ORDERS_ORDERS', 'Orders');

// javascript messages
define('JS_ERROR', 'Er zijn fouten opgetreden tijdens het verwerken van uw formulier!\nBreng de volgende wijzigingen aan:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* U dient het nieuwe artikel een prijs te geven\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* U dient de nieuwe artikelprijs een voorvoegsel te geven\n');

define('JS_PRODUCTS_NAME', '* Voer een artikelnaam in\n');
define('JS_PRODUCTS_DESCRIPTION', '* Voer een artikelomschrijving in\n');
define('JS_PRODUCTS_PRICE', '* Voer een prijs in\n');
define('JS_PRODUCTS_WEIGHT', '* Voer een gewicht in\n');
define('JS_PRODUCTS_QUANTITY', '* Voer een aantal in\n');
define('JS_PRODUCTS_MODEL', '* Voer een model in\n');
define('JS_PRODUCTS_IMAGE', '* Voer een afbeelding in\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Er dient een nieuwe prijs voor dit artikel te worden ingevoerd\n');

define('JS_GENDER', '* Het \'Geslacht\' dient te worden gekozen.\n');
define('JS_FIRST_NAME', '* De  \'Voornaam\' dient minstens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_LAST_NAME', '* De \'Achternaam\' dient minstens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_DOB', '* De  \'Geboortedatum\' dient in het volgende formaat te zijn: xx/xx/xxxx (dag/maand/jaar).\n');
define('JS_EMAIL_ADDRESS', '* Het \'Emailadres\' dient minstens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_ADDRESS', '* Het \'Adres\' dient minstens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_POST_CODE', '* De \'Postcode\' dient minstens ' . ENTRY_POSTCODE_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_CITY', '* De \'Plaats\' dient minstens ' . ENTRY_CITY_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_STATE', '* De \'Provincie\' dient geselecteerd te zijn\n');
define('JS_STATE_SELECT', '-- Selecteer --');
define('JS_ZONE', '* De \'Provincie\' dient geselecteerd te zijn.');
define('JS_COUNTRY', '* Het \'Land\' dient geselecteerd te zijn.\n');
define('JS_TELEPHONE', '* Het \'Telefoonnummer\' dient minstens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_PASSWORD', '* De velden \'Wachtwoord\' en \'Bevestiging\' moeten exact gelijk zijn en minimaal ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens bevatten.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Bestelnummer %s komt niet voor in de database!');

define('CATEGORY_PERSONAL', 'Persoonlijk');
define('CATEGORY_ADDRESS', 'Adres');
define('CATEGORY_CONTACT', 'Contactpersoon');
define('CATEGORY_COMPANY', 'Bedrijfsnaam');
define('CATEGORY_OPTIONS', 'Opties');

define('ENTRY_GENDER', 'Geslacht:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">verplicht</span>');
define('ENTRY_FIRST_NAME', 'Voornaam:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' tekens</span>');
define('ENTRY_LAST_NAME', 'Achternaam:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' tekens</span>');
define('ENTRY_DATE_OF_BIRTH', 'Geboortedatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(bijv. 21/05/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'Emailadres:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' tekens</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">Het emailadres lijkt ongeldig te zijn!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">Dit emailadres bestaat al!</span>');
define('ENTRY_COMPANY', 'Bedrijfsnaam');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', 'Adres:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' tekens</span>');
define('ENTRY_SUBURB', 'Wijk:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', 'Postcode:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' tekens</span>');
define('ENTRY_CITY', 'Plaats:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' tekens</span>');
define('ENTRY_STATE', 'Provincie:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">verplicht</span>');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', 'Telefoonnummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' tekens</span>');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', 'Nieuwsbrief:');
define('ENTRY_NEWSLETTER_YES', 'Wel Geabonneerd');
define('ENTRY_NEWSLETTER_NO', 'Niet Geabonneerd');
define('ENTRY_SUSPENDED_YES', 'Niet Actief'); // suspend customer
define('ENTRY_SUSPENDED_NO', 'Actief'); // suspend customer
define('ENTRY_ACCOUNT_STATUS', 'Status Klant :'); // suspend customer
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_CUSTOMERS_DISCOUNT', 'KortingPercentage'); // total b2b
// BOF Separate Pricing Per Customer
define('ENTRY_CUSTOMERS_GROUP_NAME', 'Klanten Groep:');
define('ENTRY_COMPANY_TAX_ID', 'BTW nummer bedrijf:');
define('ENTRY_COMPANY_TAX_ID_ERROR', '');
define('ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION', 'Verwijder waarschuwing voor authenticatie:');
define('ENTRY_CUSTOMERS_GROUP_RA_NO', 'Waarschuwing Uit');
define('ENTRY_CUSTOMERS_GROUP_RA_YES', 'Waarschuwing Aan');
define('ENTRY_CUSTOMERS_GROUP_RA_ERROR', '');
define('HEADING_TITLE_MODULES_PAYMENT', 'Betalings Modules');
define('HEADING_TITLE_MODULES_SHIPPING', 'Verzend Modules');
define('HEADING_TITLE_MODULES_ORDER_TOTAL', 'Order Totaal Modules');
// EOF Separate Pricing Per Customer

// images
define('IMAGE_ANI_SEND_EMAIL', 'Email wordt verzonden');
define('IMAGE_BACK', 'Terug');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Annuleer');
define('IMAGE_CONFIRM', 'Bevestig');
define('IMAGE_COPY', 'Kopieer');
define('IMAGE_COPY_TO', 'Kopieer naar');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Verwijder');
define('IMAGE_EDIT', 'Wijzigen');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FILE_MANAGER', 'File-manager');
define('IMAGE_ICON_STATUS_GREEN', 'Actief');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Activeren');
define('IMAGE_ICON_STATUS_RED', 'Niet Actief');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Deactiveren');
define('IMAGE_ICON_INFO', 'Informatie');
define('IMAGE_INSERT', 'Invoegen');
define('IMAGE_LOCK', 'Vastzetten');
define('IMAGE_MODULE_INSTALL', 'Module installeren');
define('IMAGE_MODULE_REMOVE', 'Module verwijderen');
define('IMAGE_MOVE', 'Verplaatsen');
define('IMAGE_NEW_BANNER', 'Nieuwe banner');
define('IMAGE_NEW_CATEGORY', 'Nieuwe categorie');
define('IMAGE_NEW_COUNTRY', 'Nieuw land');
define('IMAGE_NEW_CURRENCY', 'Nieuwe valuta');
define('IMAGE_NEW_FILE', 'Nieuw bestand');
define('IMAGE_NEW_FOLDER', 'Nieuwe directorie');
define('IMAGE_NEW_LANGUAGE', 'Nieuwe taal');
define('IMAGE_NEW_NEWSLETTER', 'Nieuwe nieuwsbrief');
define('IMAGE_NEW_PRODUCT', 'Nieuw artikel');
define('IMAGE_NEW_TAX_CLASS', 'Nieuwe BTW tariefgroep');
define('IMAGE_NEW_TAX_RATE', 'Nieuw BTW tarief');
define('IMAGE_NEW_TAX_ZONE', 'Nieuwe BTW tariefzone');
define('IMAGE_NEW_ZONE', 'Nieuwe zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Factuur');
define('IMAGE_ORDERS_PACKINGSLIP', 'Pakbon');
define('IMAGE_PREVIEW', 'Voorbeeld');
define('IMAGE_RESTORE', 'Herstel');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Opslaan');
define('IMAGE_SEARCH', 'Zoeken');
define('IMAGE_SELECT', 'Selecteer');
define('IMAGE_SEND', 'Verstuur');
define('IMAGE_SEND_EMAIL', 'Stuur email');
define('IMAGE_UNLOCK', 'Vrijgeven');
define('IMAGE_UPDATE', 'Bijwerken');
define('IMAGE_UPDATE_CURRENCIES',       'Wisselkoersen bijwerken');
define('IMAGE_UPLOAD',                  'Upload');
define('IMAGE_EDIT_ORDER',              'Wijzig Order' ); // edit order 509
define('IMAGE_NEW_DISCOUNT_CODE',       'Toevoegen Kortings Code');// discount codes 2.62
define('IMAGE_ORDERS_LABEL',            'Adres Etiket');// label pdf
define('IMAGE_CROSS_SELL',              'Gerelateerde Producten');// XSell
define('IMAGE_WITHOUT_XSELLS',          'Zonder Gerelateerde Producten');// XSell
define('IMAGE_WITH_XSELLS',             'Met Gerelateerde Producten');// XSell
define('IMAGE_BUTTON_CONTINUE',         'Bevestig');// create account
define('IMAGE_SPEC_ACTIVE_ALL',         'Activeer Aanbieding Selectie');// specials enhanced
define('IMAGE_SPEC_DEACTIVE_ALL',       'Verwijder Aanbieding Selectie');// specials enhanced
define('IMAGE_SPEC_REMOVE_ALL',         'Verwijder Alle Aanbiedingen');// specials enhanced
define('IMAGE_NEW_PAGE',                'Toevoegen Informatie Pagina');// info manager 
define('IMAGE_CHANGE_PASSWORD',         'Wijzig Wachtwoord');// change password customer 
define('IMAGE_EXCLUDE',                 'UitSluiten');  // sitemonitor
define('IMAGE_GOOGLE_DIRECTIONS',       'Google Delivery Directions'); // google maps + directions
define('IMAGE_EXPORT_MARGIN',           'Export Naar Bestand'); // product price cost
define('IMAGE_SHOW_PRODUCTS',           'Show Products'); // BOF QPBPP for SPPC
define('IMAGE_QTSTOCK',                 'Voorraad'); //++++ QT Pro: Begin Changed code
define('IMAGE_ORDERS_INVOICE_PDF',      'Factuur PDF'); // BOF ADMIN pdf invoice 1.6
define('IMAGE_ORDERS_PACKINGSLIP_PDF',  'Pakbon PDF'); // BOF ADMIN pdf invoice 1.6
define('IMAGE_REMOVE_CACHE',            'Verwijder Cache Categorien'); // 
define('IMAGE_NEW_POPUP',               'Toevoegen Tekst Venster'); //  popup
define('IMAGE_MULTI_STORES',            'Actieve Winkels'); //  multi stores
define('IMAGE_SPPC_PROD_LIST',          'SPPC Producten Lijst');// BOF SPPC Group List
define('IMAGE_SPPC_GROUP_LIST',         'SPPC Groep Lijst');// BOF SPPC Group List
define('IMAGE_NEW_ORDER',               'Nieuwe Order');// BOF SPPC Group List
define('IMAGE_NEW_LOCATION_MAP',        'Nieuwe Locatie Kaart'); 
define('IMAGE_NEW_CUSTOMER_GROUP',      'Nieuwe Klanten Groep'); 
define('IMAGE_NEW_SEO_PAGE',            'Controleer op Nieuwe SEO Pagina'); 
define('IMAGE_NEW_SEO_PAGE_DEFAULT',    'Controleer op Nieuwe Standaard SEO Pagina'); 
define('IMAGE_NEW_SEO_PSEUDO_PAGE',     'Nieuwe Pseudo SEO Pagina'); 
define('IMAGE_NEW_ATTRIBUTE_NEW_OPTION','Nieuwe Product Optie'); 
define('IMAGE_NEW_ATTRIBUTE_NEW_VALUE', 'Nieuwe Product Waarde'); 
define('IMAGE_OPTIONS_ATTRIBUTES',      'Opties + Waardes'); 
define('IMAGE_ORDERS_ADDRESS',          'Adres Gegevens'); 
define('IMAGE_NEW_ORDER_EMAIL',         'Nieuwe Order Email'); 
define('IMAGE_ORDERS_STATUS',           'Status Order'); 
define('IMAGE_NEW_ORDER_EMAIL',         'Nieuwe Order Email'); 
define('IMAGE_ORDERS_PRODUCTS',         'Producten Opties Order'); 
define('IMAGE_NEW_GOOGLE_TAXECO_CODE',  'Nieuwe Google Economic Code'); 
define('IMAGE_CLOSE',                   'Sluiten') ;
define('IMAGE_INSTALL',                 'Istalleer') ;
define('IMAGE_UNINSTALL',               'Verwijderen') ;
define('IMAGE_DISABLE',                 'DeActiveer') ;
define('IMAGE_ENABLE',                  'Activeer') ;
define('IMAGE_NEW_SUBSCRIBER',          'Nieuwe Nieuwsbrief Gebruiker') ;
define('IMAGE_NEW_SPECIALS',            'Nieuwe Aanbieding') ;
define('IMAGE_NEW_XSELL_PRODUCTS',      'Nieuwe Gerelateerd Product') ;
define('IMAGE_NEW_PRODUCT_AVAILABILITY','Nieuwe Product Voorraad Status') ;
define('IMAGE_NEW_DISCOUNT',            'Nieuwe Korting') ;
define('IMAGE_NEW_REVIEW',              'Nieuwe Beoordeling') ;
define('IMAGE_ICON_DOWNLOAD',           'Download Bestand' ) ;
define('IMAGE_NEW_BANNED_IP',           'Nieuwe Geweigerde IP Adres' ) ;
define('IMAGE_NEW_ATTRIBUTE_STOCK',     'Nieuwe Voorraad Opties' ) ;
define('IMAGE_EXPORT',                  'Export Gegevens' ) ;
define('IMAGE_NEW_FQA',                 'Nieuwe Vraag en Antwoord' ) ;
define('IMAGE_OPTIONS_STOCK',           'Product Voorraad Opties' ) ;

define('ICON_CROSS', 'Niet waar');
define('ICON_CURRENT_FOLDER', 'Huidige directorie');
define('ICON_DELETE', 'Verwijder');
define('ICON_ERROR', 'Fout');
define('ICON_FILE', 'Bestand');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Directorie');
define('ICON_LOCKED', 'Vastgezet');
define('ICON_PREVIOUS_LEVEL', 'Vorige directorie');
define('ICON_PREVIEW', 'Voorbeeld');
define('ICON_STATISTICS', 'Statistieken');
define('ICON_SUCCESS', 'Succes');
define('ICON_TICK', 'Waar');
define('ICON_UNLOCKED', 'Vrijgegeven');
define('ICON_WARNING', 'Waarschuwing');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Pagina %s van %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> landen)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> klanten)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> valuta)');
define('TEXT_DISPLAY_NUMBER_OF_ENTRIES','Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> valuta)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> talen)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> fabrikanten)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> nieuwsbrieven)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> bestellingen)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> order statussen)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> artikelen)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> verwachte producten)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Vertoont <b>%d</b> tot <b>%d</b> (vanf <b>%d</b> artikelrecensies)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> speciale aanbiedingen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> BTW tariefgroepen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> BTW tariefzones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> BTW tarieven)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> zones)');
define('TEXT_DISPLAY_NUMBER_OF_KEYWORDS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> ZoekTermen)');  // search keyword report
define('TEXT_DISPLAY_NUMBER_OF_DISCOUNT', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> Korting(en))');  // totalb2b
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_AVAILABILITY', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> ProductVoorraad Status(sen))');  // availability
define('TEXT_DISPLAY_NUMBER_OF_STORES', 'Vertoont <b>%d</b> tot <b>%d</b> ( van <b>%d</b> Webwinkels )');  // multi stores
define('TEXT_DISPLAY_NUMBER_OF_LOCATIONMAPS', 'Vertoont <b>%d</b> tot <b>%d</b> ( van <b>%d</b> Locaties )');  // location google map
define('TEXT_DISPLAY_NUMBER_OF_SEO_PAGES', 'Vertoont <b>%d</b> tot <b>%d</b> ( van <b>%d</b> SEO Pagina  )');  
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_GOOGLE_TAXONOMY', 'Vertoont <b>%d</b> tot <b>%d</b> ( van <b>%d</b> Google Taxonomy Codes  )');
define('TEXT_DISPLAY_NUMBER_OF_SUBSCRIBERS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> Nieuwsbrief Gebruiker(s))');
define('TEXT_DISPLAY_NUMBER_OF_CROSS_SELLS', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> Gerelateerde Producten)');
define('TEXT_DISPLAY_NUMBER_OF_BANNED_IP', 'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> Geweigerde IP adres(en))');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'standaard');
define('TEXT_SET_DEFAULT', 'Als standaard defini&euml;ren');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Verplicht</span>');
define('TEXT_KEYWORDS','Lijst Overzicht ZoekTermen'); // search keyword report

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Fout: Er is geen standaard valuta aanwezig. Definieer deze s.v.p.: Administratie gereedschap -&gt; Lokalisatie -&gt; Valuta');

define('TEXT_CACHE_CATEGORIES', 'Categori&euml;n box');
define('TEXT_CACHE_CATEGORIES_ACCORDION', 'Categories Accordion Box');
define('TEXT_CACHE_MANUFACTURERS', 'Fabrikanten box');
define('TEXT_CACHE_ALSO_PURCHASED', 'Eveneens gekocht module');

define('TEXT_NONE', '--geen--');
define('TEXT_TOP', 'Top');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Fout: Bestemming bestaat niet.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Fout: bestemming niet schrijfbaar.');
define('ERROR_FILE_NOT_SAVED', 'Fout: Bestand upload niet bewaard.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Fout: Upload bestand niet toegestaan.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: Bestand met succes geupload.');
define('WARNING_NO_FILE_UPLOADED', 'Waarschuwing: Geen bestand geupload.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Waarschuwing: Bestanden uploaden niet toegestaan in php.ini configuratie bestand.');

// header_tags_seo text in includes/boxes/header_tags_seo.php
define('BOX_HEADING_HEADER_TAGS_SEO', 								 'Header Tags SEO');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_ADD_A_PAGE', 							     'SEO Pagina Controle');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_ADD_PAGE_DEFAULT', 					         'SEO Standaard Pagina');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_FILL_TAGS', 								 'Vul Header Tags met Standaard');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_SILO', 										 'Silo Controle');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_KEYWORDS', 									 'Keywords');/*** Begin Header Tags SEO ***/
define('BOX_HEADER_TAGS_SOCIAL',                                     'Social Bookmarks'); /*** Begin Header Tags SEO 331 ***/
define('BOX_HEADER_TAGS_TEST', 										 'Test Header Tags'); // Discount Code - start
define('BOX_CATALOG_DISCOUNT_CODE',                                  'Kortings Codes');// Discount Code - start
define('TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES',                      'Vertoont <b>%d</b> tot <b>%d</b> (van <b>%d</b> kortings codes)'); // Discount Code - start
define('BOX_HEADING_EASYMAP',                                        'Locatie Kaart'); // easymap box text in includes/boxes/easymap.php
define('BOX_EASYMAP',                                                'Locatie Kaart'); // easymap box text in includes/boxes/easymap.php
define('BOX_HEADING_CRON_SIMULATOR',                                 'Cron Simulator'); /**** BEGIN CRON SIMULATOR ****/
define('BOX_CRON_SIMULATOR_CONTROL',                                 'Cron Simulator'); /**** BEGIN CRON SIMULATOR ****/
define ('TEXT_ADMIN_DOWN_FOR_MAINTENANCE',                           'De WebWinkel is gesloten.  Indien U gereed met de het onderhoud moet U de WebWinkel weer vrij geven !!');// Down for Maintenance Admin reminder
define('BOX_CATALOG_TAGS',                                           'ZoekTermen'); // tags cloud

// Start theme admin switcher
  define( 'MODULE_THEME_ADMIN_SWITCHER_SEARCH_PRODUCTS',            'Zoek product' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_SEARCH_CUSTOMERS',           'Zoek Klant' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_OK',                         'Ok' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_ADD_PRODUCT',                'Producten' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_NEWSLETTER',                 'Nieuwsbrief' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_WHOISONLINE',                'Actieve Klanten' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_CUSTOMERS',                  'Klanten' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_ORDERS',                     'Orders' );    
  define( 'MODULE_THEME_ADMIN_SWITCHER_INSTALL_ERROR',              'Ga naar modules/admin voor de kleuren schema' );
  define( 'MODULE_ADMIN_BACKEND_THEME_ADMIN_SWITCHER_DIALOG_TITLE', 'Message' );
  define( 'MODULE_THEME_ADMIN_SWITCHER_ADD_ORDER',                  'Toevoegen Order' );  
  define( 'MODULE_THEME_ADMIN_SWITCHER_ADD_CUSTOMER',               'Toevoegen Klant' ); 
// End theme admin switcher
  define( 'BOX_CONFIGURATION_PDFINVOICE_PACKINGSLIP', 'PDF Factuur/PDF Verzendlijst') ;// BOF ADMIN pdf invoice 1.6
  define('BOXES_ALL_PAGES',                    'alle paginas.'); // box placement 7691
  define('BOXES_ANY_PAGES',                    'willekeurige pagina.'); // box placement 7691
  define('BOXES_ONE_BY_ONE',                   'of selecteer 1 van onderstaande :'); // box placement 7691
  
  define('SHIPPING_METHODS_ALL',               'alle verzend mogelijkheden.'); // PAYMENT DEPENDING ON SHIPPING  
  define('SHIPPING_METHODS_ONE_BY_ONE',        'of selecteer 1 van onderstaande verzendmethode(s)' ) ; // PAYMENT DEPENDING ON SHIPPING 

  define('SUCCESS_EMAIL_ORDER_TEXT', 'Success: Order bevestiging email updated successvol.' ); // text order confirmation text
  define('ERROR_EMAIL_ORDER_TEXT', 'Error: Order confirmation email update failed.');  // text order confirmation text  

// translations modules title and descriptions of the different options of the modules
// vertaling van de titel en omschrijving van de verschillende opties van de modules  
define( '_MODULES_ACTIVATE_STATUS',                            'Activeer Module ' ); 
define( '_MODULES_ACTIVATE_STATUS_DESCRIPTION',                'Wilt U deze Module op het scherm plaatsen in uw Winkel ?' ); 
define( '_MODULES_CONTENT_PLACEMENT_1',                        'Waar wilt U de ' ); 
define( '_MODULES_CONTENT_PLACEMENT_2',                        ' plaatsen ? ' ); 
define( '_MODULES_CONTENT_PLACEMENT_DESCRIPTION',              'Voor een uitgebreide uitleg van de verschillende posities verwijzen wij U naar de uitleg in de directory  extra/handleiding <br /><br /> Activeer een positie'  );
define( '_MODULES_SORT_ORDER',                                 'Volgorde van Module '  ); 
define( '_MODULES_SORT_ORDER_DESCRIPTION',                     'Het ingevoerde getal geeft aan in welke volgorde de verschillende Modules worden weergegeven op de pagina(s) van de winkel' );
define( '_MODULES_DISPLAY_PAGES_1',                            'Op welke Pagina wordt de Module ' ) ;
define( '_MODULES_DISPLAY_PAGES_2',                            ' getoond ' ) ;
define( '_MODULES_DISPLAY_PAGES_DESCRIPTION',                  'Onderstaande waarde geeft aan op welke pagina de informatie op het scherm van de winkel wordt getoond' ) ;

define('_MODULE_BOXES_USE_CACHE',                              'Activeer Cache voor ' ) ;
define('_MODULE_BOXES_USE_CACHE_DESCRIPTION',                  'Activeer de cache voor de infobox ' ) ;     
define('_MODULE_BOXES_USE_CACHE_DESCRIPTION_2',                'Activeer de cache voor de ' ) ;  

define('_MODULES_ALIGN_TEXT',                                  'Plaats van de tekst  ' ) ;  
define('_MODULES_ALIGN_TEXT_DESCRIPTION',                      'Hoe wilt u de tekst op het scherm plaatsen : <br />
                                                                  Left   = Links in de box <br />
																  Right  = Rechts in de box<br />
																  Center = In het midden van de box' ) ;

define('_MODULES_QNT_COLUMN_BOOTSTRAP',                        'Aantal kolommen'	) ;																  
define('_MODULES_QNT_COLUMN_BOOTSTRAP_DESCRIPTION',            'Hoeveel kolommen moet deze pagina inhoud beslaan '	) ;

define('BOX_REPORTS_WISHLISTS',                                'VerlangLijst');  // Wish List
  
  define('TEXT_MULTI_STORE_NAME', 'Winkel : '  ) ; // MULTI STORE
// separate shipping box text in includes/boxes/separate_rate.php
define('BOX_HEADING_SEPARATE_SHIPPING','Separate Shipping');
define('BOX_SEPARATE_SHIPPING_RATE', 'Verzend Kosten');   
define('TEXT_IMAGE_NONEXISTENT','GEEN AFBEELDING');
// bootstrap helper
define('MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION', '<p>Content Breedte kan 12 or minder kolomen per regel zijn .</p><p>12/12 = 100% -> gehele regel, 6/12 = 50% -> halve regel, 4/12 = 33% </p><p>Het totaal van de content zal een regel vormen van 12 kolommen (eg:  3 boxes van 4 kolommen , 1 box van 12 kolommen etc.).</p>');
define('TEXT_BROWSE', 'Selecteer een bestand' ) ;
define('TEXT_BROWSE_DONE', 'Bestand geselecteerd' ) ;


// navbar menu icons
define('BOX_HEADING_CATALOG_ICON', '<i class="fa fa-folder-open"></i> ');
define('BOX_HEADING_CONFIGURATION_ICON', '<i class="fa fa-cog"></i> ');
define('BOX_HEADING_CUSTOMERS_ICON', '<i class="fa fa-user"></i> ');
define('BOX_HEADING_LOCALIZATION_ICON', '<i class="fa fa-globe"></i> ');
define('BOX_HEADING_LOCATION_AND_TAXES_ICON', '<i class="fa fa-briefcase"></i> ');
define('BOX_HEADING_MODULES_ICON', '<i class="fa fa-tasks"></i> ');
define('BOX_HEADING_ORDERS_ICON', '<i class="fa fa-inbox"></i> ');
define('BOX_HEADING_REPORTS_ICON', '<i class="fa fa-bar-chart"></i> ');
define('BOX_HEADING_TOOLS_ICON', '<i class="fa fa-wrench"></i> ');
define('BOX_HEADING_EMAIL_ICON', '<i class="fa fa-paper-plane"></i> ');
define('BOX_HEADING_HEADER_TAGS_ICON', '<i class="fa fa-sitemap"></i> ');
define('BOX_HEADING_INFORMATION_ICON', '<i class="fa fa-info"></i> ');

define('TEXT_MONTH_JANUARY',     'Januari') ;
define('TEXT_MONTH_FEBRUARY',    'Ferbuari') ;
define('TEXT_MONTH_MARCH',       'Maart') ;
define('TEXT_MONTH_APRIL',       'April') ;
define('TEXT_MONTH_MAY',         'Mei') ;
define('TEXT_MONTH_JUNE',        'Juni') ;
define('TEXT_MONTH_JULY',        'Juli') ;
define('TEXT_MONTH_AUGUST',      'Augustus') ;
define('TEXT_MONTH_SEPTEMBER',   'September') ;
define('TEXT_MONTH_OKTOBER',     'Oktober') ;
define('TEXT_MONTH_NOVEMBER',    'November') ;
define('TEXT_MONTH_DECEMBER',    'December') ;
?>