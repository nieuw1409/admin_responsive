<?php
/*  $Id: create_order.php,v 1 2003/08/17 23:21:34 frankl Exp $  osCommerce, Open Source E-Commerce Solutions  http://www.oscommerce.com  Copyright (c) 2002 osCommerce  Released under the GNU General Public License  */// EXAMPLE TO MAKE REQUIRED VISIBLE// 
//define('ENTRY_STREET_ADDRESS_TEXT', '&nbsp;<small><font color="#AABBDD">required</font></small>');
// ### END ORDER MAKER ###// 
//pull down default text
define('PULL_DOWN_DEFAULT', 'Selecteer');
define('TYPE_BELOW', 'Onderstaand Type');

define('JS_ERROR', 'Tijdens verwerking zijn de volgende fouten ontdekt !\nMaak de volgende correcties:\n\n');
define('JS_GENDER', '* Het \'geslacht\' moet gekozen worden.\n');
define('JS_FIRST_NAME', '* De \'Voornaam\' dient ministens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_LAST_NAME', '* De \'Achternaam\'  dient ministens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_DOB', '* De \'Geboortedatum\' dient ials volgt zijn ingevoerd: xx/xx/xxxx (month/day/year).\n');
define('JS_EMAIL_ADDRESS', '* Het \'E-Mail Addres\'  dient ministens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_ADDRESS', '* Het \'Addres\'  dient ministens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_POST_CODE', '* De \'Postcode\'  dient ministens ' . ENTRY_POSTCODE_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_CITY', '* De \'Buurt\'  dient ministens ' . ENTRY_CITY_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_STATE', '* De \'Staat\' invoer moet geselecteerd zijn.\n');
define('JS_STATE_SELECT', '-- Selecteer Bovenstaande --');
define('JS_ZONE', '* De \'Staat\' invoer moet geselecteerd zijn uit de lijst van dit land.\n');
define('JS_COUNTRY', '* De \'Land\' invoer moet geselecteerd zijn.\n');
define('JS_TELEPHONE', '* Het \'TelefoonNummer\'  dient ministens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' tekens te bevatten.\n');
define('JS_PASSWORD', '* Het \'Password\' en  \'Confirmation\'  dienen gelijk tezijn en moeten ministens ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens te bevatten.\n');

define('CATEGORY_COMPANY', 'Bedrijfs Details');
define('CATEGORY_PERSONAL', 'Persoonlijke Details');
define('CATEGORY_ADDRESS', 'Addres');
define('CATEGORY_CONTACT', 'Contact Informatie');
define('CATEGORY_OPTIONS', 'Opties');
define('CATEGORY_PASSWORD', 'Password');
define('CATEGORY_CORRECT', 'Als dit de juiste klant is, Druk op de Bevestig button onderaan.');
define('ENTRY_CUSTOMERS_ID', 'ID:');
define('ENTRY_CUSTOMERS_ID_TEXT', '&nbsp;');
define('ENTRY_COMPANY', 'BedrijfsNaam:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Geslacht:');
define('ENTRY_GENDER_ERROR', '&nbsp;');
define('ENTRY_GENDER_TEXT', '&nbsp;');
define('ENTRY_FIRST_NAME', 'VoorNaam:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_FIRST_NAME_TEXT', '&nbsp;');
define('ENTRY_LAST_NAME', 'AchterNaam:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_LAST_NAME_TEXT', '&nbsp;');
define('ENTRY_DATE_OF_BIRTH', 'Geboortedatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<small><font color="#FF0000">(eg. 05/21/1970)</font></small>');
define('ENTRY_DATE_OF_BIRTH_TEXT', '&nbsp;<small>(eg. 05/21/1970) ');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Addres:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">Your email address doesn\'t appear to be valid!</font></small>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<small><font color="#FF0000">email address already exists!</font></small>');
define('ENTRY_EMAIL_ADDRESS_TEXT', 'Email Adres');

define('ENTRY_STREET_ADDRESS', 'Adres:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_STREET_ADDRESS_TEXT', 'Adres');
define('ENTRY_SUBURB', 'Buurt:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', 'Wijk');
define('ENTRY_POST_CODE', 'PostCode:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_POST_CODE_TEXT', 'Postcode');
define('ENTRY_CITY', 'Buurt:');
define('ENTRY_CITY_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_CITY_TEXT', 'Plaats');
define('ENTRY_STATE', 'Staat/Provincie:');
define('ENTRY_STATE_ERROR', '&nbsp;');
define('ENTRY_STATE_TEXT', 'Staat');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_COUNTRY_TEXT', 'Land');
define('ENTRY_TELEPHONE_NUMBER', 'TelefoonNummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_TELEPHONE_NUMBER_TEXT', 'Telefoonnumer');
define('ENTRY_FAX_NUMBER', 'FaxNummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', 'Fax Nummer');
define('ENTRY_NEWSLETTER', 'Nieuwsbrief:');
define('ENTRY_NEWSLETTER_TEXT', 'Nieuwsbrief');
define('ENTRY_NEWSLETTER_YES', 'Wel geabonneerd');
define('ENTRY_NEWSLETTER_NO', 'Niet geabonneerd');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Controle:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '&nbsp;');
define('ENTRY_PASSWORD_ERROR', '&nbsp;<small><font color="#FF0000">min ' . ENTRY_PASSWORD_MIN_LENGTH . ' chars</font></small>');
define('ENTRY_PASSWORD_TEXT', '&nbsp;');
define('PASSWORD_HIDDEN', '--VERBORGEN--');// ### END ORDER MAKER ###
define('CREATE_ORDER_TEXT_EXISTING_CUST', 'Bestaande Klant Gegevens');
define('CREATE_ORDER_TEXT_NEW_CUST', 'Nieuwe Klant Toevoegen');
define('CREATE_ORDER_TEXT_NO_CUST', 'Aankoop Zonder Registratie Klant Gegevens');
define('TEXT_STEP_2', 'Step 2 - Bevestig Bestaande Klant Gegevens of voer de nieuwe klant gegevens in voor Verzendig en Factuur Gegevens.');
define('ENTRY_GENDER_FEMALE', 'Vrouw:');
define('ENTRY_GENDER_MALE', 'Man:');
define('ACCOUNT_EXTRAS','Klant Extras');
define('ENTRY_ACCOUNT_PASSWORD','Wachtwoord');
define('ENTRY_NEWSLETTER_SUBSCRIBE','Niewwsletter');
define('ENTRY_ACCOUNT_PASSWORD_TEXT','');
define('ENTRY_NEWSLETTER_SUBSCRIBE_TEXT','1 = Wel geabonneerd,<br/> of 0 (Zero) = Niet geabonneerd.');
define('HEADING_TITLE', 'Handmatige Invoer Order');
define('HEADING_CREATE', 'Bevestig Klant gegevens:');
define('TEXT_SELECT_CUST', 'Selecteer een klant:'); 
define('TEXT_SELECT_CURRENCY', 'Selecteer valuta:');
define('BUTTON_TEXT_SELECT_CUST', 'Selecteer een klant:'); 
define('TEXT_OR_BY', 'of kies een klant ID:'); 
define('TEXT_OR_BY_EMAIL', 'of kies een klant Email:');
define('TEXT_OR_BY_NAME', 'of kies een Naam:');
define('TEXT_STEP_1', 'Step 1 - Kies een klant en controleer de details');
define('BUTTON_SUBMIT', 'Bevestig');
define('ENTRY_CURRENCY','Valuta');
define('CATEGORY_ORDER_DETAILS','Kies Valuta:');
define('ENTRY_ADMIN','Order Besteld door:');
define('TEXT_CS','Klanten Service');
define('TEXT_SELECT_CURRENCY_TITLE','Valuta Keuze');
define('TEXT_SELECT_STORES_TITLE','Winkel Keuze'); // MULTI STORES
define('TEXT_SELECT_STORES', 'Selecteer Winkel:'); // MULTI STORES
define('ENTRY_STORES','Winkel'); // MULTI STORES
?>