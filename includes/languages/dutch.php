<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Dutch translation for v2.3.1 by dfirefire
  http://www.dfirefire.be
 
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directotry for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
// 2.3.3.3 @setlocale(LC_TIME, 'nl_NL.ISO_8859-1');
@setlocale(LC_ALL, array('nl_NL.UTF-8', 'nl_NL.UTF8', 'nld_nld'));

define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('DATE_TIME_FORMAT_2', DATE_FORMAT . ' H:i:s');
define('JQUERY_DATEPICKER_I18N_CODE', 'nl'); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
define('JQUERY_DATEPICKER_FORMAT', 'dd/mm/yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS', 'dir="ltr" lang="nl"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Klantgegevens invoeren');
define('HEADER_TITLE_MY_ACCOUNT', 'Uw klantgegevens');
define('HEADER_TITLE_CART_CONTENTS', 'Winkelmandje');
define('HEADER_TITLE_CHECKOUT', 'Afrekenen');
define('HEADER_TITLE_TOP', 'Hoofdpagina');
define('HEADER_TITLE_CATALOG', 'Winkel');
define('HEADER_TITLE_LOGOFF', 'Uitloggen');
define('HEADER_TITLE_LOGIN', 'Inloggen');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'aanroepen sinds');

// text for gender
define('MALE', 'Man');
define('FEMALE', 'Vrouw');
define('MALE_ADDRESS', 'Mr.');
define('FEMALE_ADDRESS', 'Mevr.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Leveringsinfo');
define('CHECKOUT_BAR_PAYMENT', 'Betalingsinfo');
define('CHECKOUT_BAR_CONFIRMATION', 'Bevestiging');
define('CHECKOUT_BAR_FINISHED', 'Klaar!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Kies');
define('TYPE_BELOW', 'Vul hieronder aan');

// javascript messages
define('JS_ERROR', 'Er zijn fouten opgetreden tijdens het verwerken van uw formulier.\n\nGelieve volgende verbetering(en) te maken:\n\n');

define('JS_REVIEW_TEXT', '* De \'beoordelingstekst\' moet minstens ' . REVIEW_TEXT_MIN_LENGTH . ' tekens bevatten.\n');
define('JS_REVIEW_RATING', '* U moet het artikel een waarde geven voor uw beoordeling.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Gelieve een betalingsmodule voor uw bestelling te kiezen.\n');

define('JS_ERROR_SUBMITTED', 'Dit formulier werd al verstuurd. Gelieve OK te klikken en te wachten tott het verwerkt is.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Gelieve een betalingsmodule voor uw bestelling te kiezen.');

define('CATEGORY_COMPANY', 'Bedrijfsgegevens');
define('CATEGORY_PERSONAL', 'Uw persoonlijke gegevens');
define('CATEGORY_ADDRESS', 'Uw adres');
define('CATEGORY_CONTACT', 'Uw contactinformatie');
define('CATEGORY_OPTIONS', 'Opties');
define('CATEGORY_PASSWORD', 'Uw wachtwoord');

define('ENTRY_COMPANY', 'Bedrijfsnaam:');
define('ENTRY_COMPANY_TAX_ID', 'Bedrijfs BTW nummer:'); // BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TAX_ID_ERROR', '');  // BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TAX_ID_TEXT', '');  // BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Geslacht:');
define('ENTRY_GENDER_ERROR', 'Gelieve uw geslacht op te geven.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Voornaam:');
define('ENTRY_FIRST_NAME_ERROR', 'Uw voornaam moet minstens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Familienaam:');
define('ENTRY_LAST_NAME_ERROR', 'Uw familienaam moet minstens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Geboortedatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Uw geboortedatum moet het volgend formaat hebben: DD/MM/YYYY (eg 23/05/1971)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1970)');
define('ENTRY_EMAIL_ADDRESS', 'e-mail adres:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Uw e-mail-adres moet minstens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Uw e-mail-adres blijkt ongeldig - gelieve de nodige verbeteringen te maken.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Uw e-mail-adres bestaat al in onze gegevensbank - gelieve in te loggen met dit e-mail-adres of een account aan te maken met een ander e-mail-adres.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Straat en nummer:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Uw straat en nummer moet minstens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Wijk:');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Postcode:');
define('ENTRY_POST_CODE_ERROR', 'Uw postcode moet minstens ' . ENTRY_POSTCODE_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Woonplaats:');
define('ENTRY_CITY_ERROR', 'Uw woonplaats moet minstens ' . ENTRY_CITY_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Staat/Provincie:');
define('ENTRY_STATE_ERROR', 'Uw Staat moet minstens ' . ENTRY_STATE_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_STATE_ERROR_SELECT', 'Gelieve een staat uit het keuzemenu te selecteren.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', 'Gelieve een land uit het keuzemenu te selecteren.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telefoon Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Uw telefoonnummer moet minstens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Nieuwsbrief:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Ingeschreven');
define('ENTRY_NEWSLETTER_NO', 'Uitgeschreven');
define('ENTRY_PASSWORD', 'Wachtwoord:');
define('ENTRY_PASSWORD_ERROR', 'Uw Wachtwoord moet minstens ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Het bevestigings wachtwoord moet overeenkomen met uw wachtwoord.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Bevestigingswachtwoord:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Huidig wachtwoord:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Uw wachtwoord moet minstens ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_PASSWORD_NEW', 'Nieuw wachtwoord:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Uw nieuw wachtwoord moet minstens ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens bevatten.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Het bevestigingswachtwoord moet overeenkomen met uw nieuw wachtwoord.');
define('PASSWORD_HIDDEN', '--VERBORGEN--');
define('ENTRY_MATC', 'Algemene Voorwaarden');

define('FORM_REQUIRED_INFORMATION', '* Vereiste informatie');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Resultaatpagina\'s:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Weergave van <strong>%d</strong> tot <strong>%d</strong> (van <strong>%d</strong> artikelen)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Weergave van <strong>%d</strong> tot <strong>%d</strong> (van <strong>%d</strong> orders)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Weergave van <strong>%d</strong> tot <strong>%d</strong> (van <strong>%d</strong> beoordelingen)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Weergave van <strong>%d</strong> tot <strong>%d</strong> (van <strong>%d</strong> nieuwe artikelen)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Weergave van <strong>%d</strong> tot <strong>%d</strong> (van <strong>%d</strong> promoties)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Eerste pagina');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Vorige pagina');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Volgende pagina');
define('PREVNEXT_TITLE_LAST_PAGE', 'Laatste pagina');
define('PREVNEXT_TITLE_PAGE_NO', 'pagina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Vorige set van %d pagina\'s');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Volgende set van %d pagina\'s');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;EERSTE');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Vorige]');
define('PREVNEXT_BUTTON_NEXT', '[Volgende&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAATSTE&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Adres toevoegen');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Adresboek');
define('IMAGE_BUTTON_BACK', 'Terug');
define('IMAGE_BUTTON_BUY_NOW', 'Nu kopen');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Adres wijzigen');
define('IMAGE_BUTTON_CHECKOUT', 'Afrekenen');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Bestelling bevestigen');
define('IMAGE_BUTTON_CONTINUE', 'Verder');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Verder winkelen');
define('IMAGE_BUTTON_DELETE', 'Wissen');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Account bewerken');
define('IMAGE_BUTTON_HISTORY', 'Bestelgeschiedenis');
define('IMAGE_BUTTON_LOGIN', 'Inloggen met Uw Bestaande Gegevens');
define('IMAGE_BUTTON_IN_CART', 'Toevoegen aan winkelmandje');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Verwittigingen');
define('IMAGE_BUTTON_QUICK_FIND', 'Snel zoeken');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Verwittigingen wissen');
define('IMAGE_BUTTON_REVIEWS', 'Beoordelingen');
define('IMAGE_BUTTON_SEARCH', 'Zoeken');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Verzendingsopties');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Vertel een vriend(in)');
define('IMAGE_BUTTON_UPDATE', 'Verversen');
define('IMAGE_BUTTON_UPDATE_CART', 'Winkelmandje verversen');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Schrijf een beoordeling');
define('IMAGE_BUTTON_CLEAR_CART', 'Verwijder Alle Product(en)' ) ;
define('IMAGE_BUTTON_REMOVE', 'Verwijder Product' ) ;
define('IMAGE_BUTTON_COMPARE', 'Vergelijken Producten' ) ;
define('IMAGE_BUTTON_DETAILS', 'Details' ) ; // sppc
define('IMAGE_BUTTON_SAVE', 'Gegevens Verzenden' ) ; // mobile website opslaan adres gegevens
define('IMAGE_BUTTON_CLOSE', 'Sluiten' ) ; 
define('SMALL_IMAGE_BUTTON_DELETE', 'Wissen');
define('SMALL_IMAGE_BUTTON_EDIT', 'Bewerken');
define('SMALL_IMAGE_BUTTON_VIEW', 'Bekijken');
define('SMALL_IMAGE_BUTTON_BUY', 'Kopen');
define('SMALL_IMAGE_BUTTON_SEND_EMAIL', 'Verzend naar Email Adres');

define('ICON_ARROW_RIGHT', 'meer');
define('ICON_CART', 'In winkelmandje');
define('ICON_ERROR', 'Fout');
define('ICON_SUCCESS', 'Succes');
define('ICON_WARNING', 'Waarschuwing');

define('TEXT_GREETING_PERSONAL', 'Welkom terug <span class="greetUser">%s!</span> Wenst u te zien welke <a href="%s"><u>nieuwe artikelen</u></a> er te koop aangeboden worden?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Als u niet %s bent, gelieve dan <a href="%s"><u>uzelf in te loggen</u></a> met uw accountgegevens.</small>');
define('TEXT_GREETING_GUEST', 'Welkom <span class="greetUser">Gast!</span> Wenst u <a href="%s"><u>in te loggen</u></a>? Of wilt u <a href="%s"><u>een account aanmaken</u></a>?');

define('TEXT_SORT_PRODUCTS', 'Sorteer artikelen ');
define('TEXT_DESCENDINGLY', 'aflopend');
define('TEXT_ASCENDINGLY', 'oplopend');
define('TEXT_BY', ' volgens ');

define('TEXT_REVIEW_BY', 'volgens %s');
define('TEXT_REVIEW_WORD_COUNT', '%s woorden');
define('TEXT_REVIEW_RATING', 'Beoordeling: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Datum toegevoegd: %s');
define('TEXT_NO_REVIEWS', 'Er zijn momenteel geen beoordelingen van artikelen.');

define('TEXT_NO_NEW_PRODUCTS', 'Er zijn momenteel geen artikelen.');

define('TEXT_UNKNOWN_TAX_RATE', 'Onbekend BTW-tarief');

define('TEXT_REQUIRED', '<span class="errorText">Vereist</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><strong><small>TEP FOUT:</small> Kan deze mail niet langs de opgegeven SMTP server versturen. Gelieve uw php.ini na te kijken en uw SMTP server aan te passen indien nodig.</strong></font>');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'De vervaldatum van deze kredietkaart is ongeldig. Gelieve de datum na te kijken en aan te passen.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Het nummer van de kredietkaart is ongeldig. GElieve het nummer na te kijken en opnieuw te proberen.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'De vier eerste opgegeven tekens van de kredietkaart zijn: %s. Indien dit correct is, aanvaarden we dit type kredietkaart niet. Indien dit fout is, gelieve dan opnieuw te proberen.');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a><br />Aangedreven door <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');
// Discount Code - start
define('TEXT_DISCOUNT', 'Korting');
define('TEXT_DISCOUNT_CODE', 'Kortings Code');
// bof down for maintenance 
define('TEXT_BEFORE_DOWN_FOR_MAINTENANCE', 'LET OP : Onze Winkel is gesloten wegens onderhoud . Tijd Periode is : ');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'LET OP : Onze Winkel is gesloten wegens onderhoud');
  //FAQDesk 2.3
  define('MODULE_BOXES_INFORMATION_BOX_FAQ', 'Vraag en Antwoord');
// BOF qpbpp
define('TEXT_ENTER_QUANTITY', 'Aantal');
define('TEXT_PRICE_PER_PIECE', 'Prijs per stuk');
define('TEXT_SAVINGS', 'Uw voordeel');  
// XSell (English)
define('TEXT_XSELL_PRODUCTS', 'Gerelateerde Producten');
//-----   BEGINNING OF ADDITION: MATC   -----//
define('MATC_CONDITION_AGREEMENT', 'Ik heb de <a href="%s" target="_blank"><strong><u> Algemene Voorwaarden</u></strong></a> gelezen en Ik accepteer de voorwaarden : ');
define('MATC_HEADING_CONDITIONS', 'Voorwaarden en Condities ');
define('MATC_ERROR', 'U moet akkoord gaan met onze Algemene Voorwaarden om Uw Order te voltooien .');
define('TEXT_COMPARE_PRODUCTS', 'Vergelijken') ; // compare products
// bof 231 option type
define('ERROR_FILETYPE_NOT_ALLOWED', 'FOUT:  Bestands type is niet toegestaan.');
define('WARNING_NO_FILE_UPLOADED', 'LET OP!!:  GEEN bestand uploaded.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Succes:  bestand is succesvol opgeslagen.');
define('ERROR_FILE_NOT_SAVED', 'FOUT:  bestand is niet opgeslagen/geuploaded.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'FOUT:  bestands locatie niet beschrijfbaar.');
define('ERROR_DESTINATION_DOES_NOT_EXIST', 'FOUT:  bestands locatie bestaat niet .');
// eof 231option type
/*** Begin Header Tags SEO ***/
define('BOX_HEADING_HEADERTAGS_TAGCLOUD', 'Populaire Zoektermen');
define('TEXT_SEE_MORE', 'Meer info');
define('HTS_OG_AVAILABLE_STOCK', 'Op voorraad');
define('HTS_OG_PRICE', 'Prijs');
/*** End Header Tags SEO ***/
define('IMAGE_BUTTON_WISHLIST', 'VerlangLijst'); //Wishlist

// resaponsive design
// category views
define('TEXT_VIEW', 'Vertoon : ');
define('TEXT_VIEW_LIST', ' Lijst');
define('TEXT_VIEW_GRID', ' Grid');

// search placeholder
define('TEXT_SEARCH_PLACEHOLDER','Zoeken');

// message for required inputs
define('FORM_REQUIRED_INFORMATION', '<span class="' . glyphicon_icon_to_fontawesome( "asterisk" ) . ' form-control-feedback inputRequirement"></span> Required information'); //glyphicon glyphicon-asterisk
define('FORM_REQUIRED_INPUT', '<span class="' . glyphicon_icon_to_fontawesome( "asterisk" ) . ' form-control-feedback inputRequirement"></span>'); //glyphicon glyphicon-asterisk 

// reviews
define('REVIEWS_TEXT_RATED', 'Beoordeeld %s door <cite title="%s" itemprop="reviewer">%s</cite>');
define('REVIEWS_TEXT_AVERAGE', 'Gemiddelde Beoordeling beoordeling gebaseerd op <span itemprop="count">%s</span> beoordeling(en) %s');
define('REVIEWS_TEXT_TITLE', 'Wat zeggen onze klanten...');

// grid/list
define('TEXT_SORT_BY', 'Sorteer Op ');
// moved from index
define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product Naam');
define('TABLE_HEADING_MANUFACTURER', 'Fabrikant');
define('TABLE_HEADING_QUANTITY', 'Aantal');
define('TABLE_HEADING_PRICE', 'Prijs');
define('TABLE_HEADING_WEIGHT', 'Gewicht');
define('TABLE_HEADING_BUY_NOW', 'Kopen');
define('TABLE_HEADING_LATEST_ADDED', 'Laatste Producten');

//header titles
define('HEADER_CART_CONTENTS', '<i class="' . glyphicon_icon_to_fontawesome( "shopping-cart" ) . '"></i> %s product(en) <span class="caret"></span>'); //glyphicon glyphicon-shopping-cart
define('HEADER_CART_NO_CONTENTS', '<i class="' . glyphicon_icon_to_fontawesome( "shopping-cart" ) . '"></i> Leeg'); //glyphicon glyphicon-shopping-cart
define('HEADER_ACCOUNT_LOGGED_OUT', '<i class="' . glyphicon_icon_to_fontawesome( "user" ) . '"></i><span class="hidden-sm"> Myn Account</span> <span class="caret"></span>'); //glyphicon glyphicon-user
define('HEADER_ACCOUNT_LOGGED_IN', '<i class="' . glyphicon_icon_to_fontawesome( "user" ) . '"></i> %s <span class="caret"></span>'); //glyphicon glyphicon-user
define('HEADER_SITE_SETTINGS', '<i class="' . glyphicon_icon_to_fontawesome( "cog" ) . '"></i><span class="hidden-sm"> Instellingen</span> <span class="caret"></span>'); //glyphicon glyphicon-cog
define('HEADER_TOGGLE_NAV', 'Toggle Navigation');
define('HEADER_HOME', '<i class="' . glyphicon_icon_to_fontawesome( "home" ) . '"></i><span class="hidden-xs"> StartPagina</span>'); //glyphicon glyphicon-home
define('HEADER_WHATS_NEW', '<i class="' . glyphicon_icon_to_fontawesome( "certificate" ) . '"></i><span class="hidden-sm">  Nieuwe Producten</span>'); //glyphicon glyphicon-certificate
define('HEADER_SPECIALS', '<i class="' . glyphicon_icon_to_fontawesome( "fire" ) . '"></i><span class="hidden-sm"> Aanbiedingen</span>'); //glyphicon glyphicon-fire
define('HEADER_REVIEWS', '<i class="' . glyphicon_icon_to_fontawesome( "comment" ) . '"></i><span class="hidden-sm"> Beoordelingen</span>'); //glyphicon glyphicon-comment
// header dropdowns
define('HEADER_ACCOUNT_LOGIN', '<i class="' . glyphicon_icon_to_fontawesome( "log-in" ) . '"></i> Aanmelden'); //glyphicon glyphicon-log-in
define('HEADER_ACCOUNT_LOGOFF', '<i class="' . glyphicon_icon_to_fontawesome( "log-out" ) . '"></i> Afmelden');  //glyphicon glyphicon-log-out
define('HEADER_ACCOUNT', 'Myn Account');
define('HEADER_ACCOUNT_HISTORY', 'Myn Orders');
define('HEADER_ACCOUNT_EDIT', 'Myn Details');
define('HEADER_ACCOUNT_ADDRESS_BOOK', 'Myn Adres Boek');
define('HEADER_ACCOUNT_PASSWORD', 'Myn Wachtwoord');
define('HEADER_ACCOUNT_REGISTER', '<i class="' . glyphicon_icon_to_fontawesome( "pencil" ) . '"></i> Registreer'); //glyphicon glyphicon-pencil
define('HEADER_CART_HAS_CONTENTS', '%s item(s), %s');
define('HEADER_CART_VIEW_CART', 'Bekijk Winkelwagen');
define('HEADER_CART_CHECKOUT', '<i class="' . glyphicon_icon_to_fontawesome( "chevron-right" ) . '"></i> Afrekenen'); //glyphicon glyphicon-chevron-right
define('USER_LOCALIZATION', '<abbr title="Huidige Taal">T:</abbr> %s <abbr title="Geselecteerde Valuta">V:</abbr> %s');
define('HEADER_GENERIC', '<i class="' . glyphicon_icon_to_fontawesome( "question" ) . '"></i><span class="hidden-sm"> Generic</span>');
define('HEADER_SITE_CATEGORIES', '<i class="' . glyphicon_icon_to_fontawesome( "list-alt" ) . '"></i><span class="hidden-sm"> Producten</span>');

// product notifications
define('PRODUCT_SUBSCRIBED', '%s has been added to your Notification List');
define('PRODUCT_UNSUBSCRIBED', '%s has been removed from your Notification List');
define('PRODUCT_ADDED', '%s has been added to your Cart');
define('PRODUCT_REMOVED', '%s has been removed from your Cart');
define('PB_DROPDOWN_BEFORE', ''); // begin sppc
define('TEXT_PRICE_BREAKS', 'Vanaf'); // BOF qpbpp
define('TEXT_ON_SALE', 'Aanbieding'); // BOF qpbpp
define('MINIMUM_ORDER_NOTICE', 'Minimale order aantal voor for %s is %d. Uw winkel wagen is aangepast naar het juiste aantal producten.');
define('QUANTITY_BLOCKS_NOTICE', '%s kan aaleen aangekocht worden in veelvouden van %d. Uw winkel wagen is aangepast naar het juiste aantal producten.');
define('PB_DROPDOWN_BETWEEN', ' voor ');
define('PB_DROPDOWN_AFTER', ' per stuk');
define('PB_FROM', ' vanaf ');

define('TEXT_PRODUCTS_CATHOR', 'Producten '); // category horizontal menu

define('TEXT_FOOTER_PHONE', 'T: '); // footer abbr of phone
define('TEXT_FOOTER_EMAIL', 'E: '); // footer abbr of email

// BOF Visitor's Newsletter Contribution by brouillard s'embrouille
define('IMAGE_BUTTON_NEWSLETTER_SUBSCRIPTION', 'Aanmelden');
define('IMAGE_BUTTON_NEWSLETTER_UNSUBSCRIBE', 'Afmelden Nieuwsbrief');
define('BOX_HEADING_NEWSLETTER', 'Nieuwsbrief');
define('BOX_NEWSLETTER_TEXT_SUBSCRIPTION', '<em>Aanmelden Nieuwsbrief</em>');
define('BOX_NEWSLETTER_TEXT_EMAIL','Email: ');
define('BOX_NEWSLETTER_TEXT_NAME', 'Naam:' ) ;
define('BOX_NEWSLETTER_ERROR', 'Incorrect email address.\n" + form.emailsubscription.value + " is geen correct email address.');
define('BOX_NEWSLETTER_ERROR_EMPTY_FIELD', 'Error, the email address veld is leeg.');
define('BOX_NEWSLETTER_ERROR_COMMA', 'Incorrect format email address, cannot contain commas.');
define('BOX_NEWSLETTER_ERROR_SPACES', 'Incorrect format email address, cannot contain spaces.');
define('BOX_NEWSLETTER_ERROR_SIGN', 'Incorrect format email address, no at sign \"@\".');
// EOF Visitor's Newsletter Contribution by brouillard s'embrouille
?>