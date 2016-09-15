<?php
/*
  $Id: sitemonitor_admin.php,v 1.2 2005/09/24 Jack_mcs
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('HEADING_SITEMONITOR_CONFIGURE_SETUP', 'Configuratie Sitemonitor');
define('TEXT_SITEMONITOR_CONFGIURE_SETUP', 'Gebruik de sectie om de opties van de sitemonitor aan te passen of te bekijken.
Wijzig de lijst van de Niet gewenste Personen, volg de aanwijzingen aan de rechter kant van deze optie.  
Click op de Update knop om alle instellingen van de Sitemonitor op te slaan in het configuratie bestand .');
define('TEXT_OPTION_ALWAYS_EMAIL', 'Email Altijd');
define('TEXT_OPTION_ALWAYS_EMAIL_EXPLAIN', 'Als deze optie aan staat zal elke keer dat Sitemonitor actief wordt een Email verzonden worden, Zoniet dan krijgt U alleen een Email bij uitzonderingen.');
define('TEXT_OPTION_VERBOSE', 'Handmatig');
define('TEXT_OPTION_VERBOSE_EXPLAIN', 'Als deze optie aan staat zal er een bericht geprint worden als de Sitemonitor handmatig start.');
define('TEXT_OPTION_LOGFILE', 'LogBoek Bestand');
define('TEXT_OPTION_LOGFILE_EXPLAIN', 'Als deze optie actief is , het bestand SiteMonitor.txt logboek bestand zal worden gevuld met alle wijzigingen');
define('TEXT_OPTION_LOGFILE_SIZE', 'Logboek BestandGrootte');
define('TEXT_OPTION_LOGFILE_SIZE_EXPLAIN', 'Vul hier de maximale groote van het logboek bestand in. Als de groote van het bestand is bereikt zal deze worden hernoemd en een nieuw logboek bestand zal worden aangemaakt');

define('TEXT_OPTION_QUARANTINE', 'Quarantaine Bestanden');
define('TEXT_OPTION_QUARANTINE_EXPLAIN', 'Alle nieuwe bestand die gevonden worden, worden verplaatst naar de quarantaine directory in admin.
Als deze optie gebruikt wordt, zal er elk nieuw bestand een aantekening moeten worden gemaakt in het bestand van de bestandnamen');

define('TEXT_OPTION_TO_EMAIL', 'Tot:');
define('TEXT_OPTION_TO_ADDRESS_EXPLAIN', 'Email address welke gebruikt moet worden tijdens de SiteMonitor .');
define('TEXT_OPTION_FROM_EMAIL', 'Van:');
define('TEXT_OPTION_FROM_ADDRESS_EXPLAIN', 'Email address van welk webshop deze afkomstig is (useful for multiple shops).');
define('TEXT_OPTION_REFERENCE_RESET', 'Verwijder Bestandsnamen file:');
define('TEXT_OPTION_REFERENCE_RESET_EXPLAN', 'SiteMonitor zal het bestandsnamen bestand na het ingevoerde aantal dagen verwijderen.
Voer hier niets in als het bestand niet wil laten verwijderen.');
define('TEXT_OPTION_START_DIR', 'Start Directory:');
define('TEXT_OPTION_START_DIR_EXPLAIN', 'Meestal de begin directory van de webshop. ');
define('TEXT_OPTION_ADMIN_DIR', 'Admin Directory:');
define('TEXT_OPTION_ADMIN_DIR_EXPLAIN', 'Dit is het logische web address naar de admin directory. Dit wordt alleen gebruikt voor CURL. Als deze functie niet gebruikt kan deze instelling worden overgeslagen');
define('TEXT_OPTION_ADMIN_USERNAME', 'Admin Gebruikersnaam:');
define('TEXT_OPTION_ADMIN_USERNAME_EXPLAIN', 'Dit is de Gebruikernaam voor de Admin sectie. Alleen invoeren als de CURL functie wordt gebruikt.');
define('TEXT_OPTION_ADMIN_PASSWORD', 'Admin Wachtwoord:');
define('TEXT_OPTION_ADMIN_PASSWORD_EXPLAIN', 'Dit is het wachtwoord voor de Admin sectie. Alleen invoeren als de CURL functie wordt gebruikt..');
define('TEXT_OPTION_EXCLUDE_SELECTOR', 'Uitzondering Selector:');
define('TEXT_OPTION_EXCLUDE_LIST', 'UitzonderingsLijst:');
define('TEXT_OPTION_EXCLUDE_LIST_EXPLAIN', 'Geef op welke directories de sitemonitor in de gaten moet houden
U kunt de directies kiezen uit de dropdown lijst of handmatig invoeren. Tijdens het handmatig invoeren moet u de namen omgeven met citaten ( enkele hoge commas ) en scheiden door middel van een comma.
Als u tweemaal een directie ingeeft wordt deze verwijdert van de lijst.');

// 2.9
define('TEXT_OPTION_EXCLUDE_HACKED_FILES_LIST', 'UitzonderingsLijst van GeHacked Bestanden :');
define('TEXT_OPTION_EXCLUDE_HACKED_FILES_LIST_EXPLAIN', 'Voer bestandsnamen in die niet moeten worden gecontroleerd tijdens HACKERS controle. U kunt de bestandsnamen invoeren in de linkse box
. De bestandsnamen moeten worden ingevoerd tussen enkele citaten en worden gescheiden met een comma. .');
define('TEXT_OPTION_EXCLUDE_HACKED_CODE_SEGMENTS', 'Hacker Code:');
define('TEXT_OPTION_EXCLUDE_HACKED_CODE_SEGMENTS_EXPLAIN', 'Voer de code in die HACKER kunnen achterlaten in bestanden en moeten worden gevonden tijdens de HACKER contrle
. De bestandsnamen moeten worden ingevoerd tussen enkele citaten en worden gescheiden met een comma.'); 

define('ERROR_ALREADY_EXISTS', 'Niet toegevoegd deze locatie bestaat reeds in de lijst.');
define('ERROR_CHILD_EXISTS', 'Niet toegevoegd omdat onderligende directories reeds bestaan in deze lijst');   
define('ERROR_PARENT_EXISTS', 'Niet toegevoegd omdat bovenliggende directies reeds bestaan in deze lijst'); 
// 2.9
define('TEXT_MAKE_SELECTION', 'Selecteer Map');
define('TEXT_OPTION_LOGFILE_DELETE', 'Verwijder Log Bestanden');
define('TEXT_OPTION_LOGFILE_DELETE_EXPLAIN', 'SiteMonitor verwijdert alle log bestanden indien deze ouder zijn dan het aantal ingevoerde dagen, Als dit veld leeg is worden de logbestanden nooit verwijderde.');
define('TEXT_CHOOSE_INSTANCE', 'Wijzig Configuratie Bestand:');
define('TEXT_CHOOSE_INSTANCE_EXPLAIN', 'Wijzig de instelling op basis van verschillende setups.');
?>
