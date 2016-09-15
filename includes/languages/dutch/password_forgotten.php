<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Dutch translation for v2.3.1 by dfirefire
  http://www.dfirefire.be
  
  Copyright (c) 2003 osCommerce

  Released under de GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Aanmelden');
define('NAVBAR_TITLE_2', 'Wachtwoord vergeten');

define('HEADING_TITLE', 'Ik ben mijn Wachtwoord vergeten!');

define('TEXT_MAIN', 'Indien u uw Wachtwoord vergeten bent, gelieve dan hieronder uw e-mail-adres op te geven en wij zullen u een nieuw Wachtwoord toesturen.');

define('TEXT_NO_EMAIL_ADDRESS_FOUND', 'Fout: Het E-Mail adress was niet gevonden in onze gegevens.');

define('TEXT_PASSWORD_RESET', 'Wachtwoord Herstellen URL'); // 232

define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Nieuw Wachtwoord');
define('EMAIL_PASSWORD_REMINDER_BODY', 'Een nieuw Wachtwoord werd aangevraagd van ' . tep_get_ip_address() . '.' . "\n\n" . 'Uw nieuwe Wachtwoord voor \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");

define('SUCCESS_PASSWORD_SENT', 'Succes: Een nieuw Wachtwoord werd verzonden naar uw e-mailadres.');
define('EMAIL_PASSWORD_RESET_SUBJECT', STORE_NAME . ' - Nieuw Wachtwoord');
define('EMAIL_PASSWORD_RESET_BODY', 'Een nieuw Wachtwoord werd aangevraagd voor uw account op ' . STORE_NAME . '.' . "\n\n" . 'Volg de deze personlijke link om veilig uw wachtwoord te wijzigen:' . "\n\n" . '%s' . "\n\n" . 'Deze link zal automatisch na 24 uren worden genegeerd of als U uw wachtwoord heeft gewijzigd.' . "\n\n" . 'Voor vragen of andere hulp stuurt u een email naar : ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");

define('ERROR_ACTION_RECORDER', 'Fout: Een wachtwoord link is reeds verstuurd. Probeer nogmaals in %s minuten.');
define('TEXT_PASSWORD_RESET_INITIATED', 'Controleer uw Email box voor deze e-mail voor instructies hoe U Uw wachtwoord kunt wijzigen. De instructies bevatten een link welke 24 uren geldig is voor het wijzigen van uw wachtwoord.');
?>
