<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $moneybookers_ping_button = '';
  if (defined('MODULE_PAYMENT_MONEYBOOKERS_STATUS') && tep_not_null(MODULE_PAYMENT_MONEYBOOKERS_SECRET_WORD)) {
    $moneybookers_ping_button = '<p><img src="images/icons/locked.gif" border="0">&nbsp;<a href=' . tep_href_link('ext/modules/payment/moneybookers/activation.php', 'action=testSecretWord', 'SSL') . ' style="text-decoration: underline; font-weight: bold;">Test Secret Word</a></p>';
  }

  define('MODULE_PAYMENT_MONEYBOOKERS_TEXT_TITLE', 'Moneybookers - Core Module');
  define('MODULE_PAYMENT_MONEYBOOKERS_TEXT_PUBLIC_TITLE', 'Moneybookers eWallet');
  define('MODULE_PAYMENT_MONEYBOOKERS_TEXT_DESCRIPTION', '<img src="images/icon_popup.gif" border="0">&nbsp;<a href="http://www.moneybookers.com/partners/oscommerce" target="_blank" style="text-decoration: underline; font-weight: bold;">Bezoek de Moneybookers website</a>' . $moneybookers_ping_button);
  define('MODULE_PAYMENT_MONEYBOOKERS_RETURN_TEXT', 'Vervolg en keer terug naar ' . STORE_NAME);
  define('MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE_CODE', 'EN');

  define('MB_ACTIVATION_TITLE', 'Moneybookers Account Activatie');
  define('MB_ACTIVATION_ACCOUNT_TITLE', 'Verifieer Account');
  define('MB_ACTIVATION_ACCOUNT_TEXT', 'Activering van Moneybookers Quick Afrekenen maakt het mogelijk dat u direct betalingen kunt accepteren van kredietkaarten, debit kaarten en meer dan 60 andere locale betalingsmogelijkheden in meer dan 200 landen en  ook de Moneybookers eWallet kunt gebruiken.<br /><br />Om toegang te krijgen tot dit internationale netwerk: <a href="http://www.moneybookers.com/partners/oscommerce" target="_blank">aanmelden</a> voor een gratis account als u dat nog niet heeft.');
  define('MB_ACTIVATION_EMAIL_ADDRESS', 'Moneybookers Account e-mailadres:');
  define('MB_ACTIVATION_ACTIVATE_TITLE', 'Account Activatie');
  define('MB_ACTIVATION_ACTIVATE_TEXT', 'Een activatie verzoek is verzonden naar Moneybookers. Het verifieren voor het gebruik van Moneybookers Quick Afrekenen kan tot 72 uur duren. <strong>Moneybookers neemt contact op als de verificatie is afgerond.</strong><br /><br /><i>Na activering geeft Moneybookers u toegang tot een nieuw sectie in uw Moneybookers account: "Merchant Tools". Kies hiervoor een toegangscode (niet het wachtwoord voor Moneybookers) en vul dit in en gebruik dit in de configuratie van  de betalingsmodule op de volgende pagina.</i>');
  define('MB_ACTIVATION_NONEXISTING_ACCOUNT_TITLE', 'Account Fout');
  define('MB_ACTIVATION_NONEXISTING_ACCOUNT_TEXT', 'Het e-mailadres is geen Moneybookers account. Meldt u zich eerst a.u.b. aan <a href="http://www.moneybookers.com/partners/oscommerce" target="_blank">Aanmelden</a> voor verkoop via Moneybookers.');
  define('MB_ACTIVATION_SECRET_WORD_TITLE', 'Toegangscode Test');
  define('MB_ACTIVATION_SECRET_WORD_SUCCESS_TEXT', 'Het instellen van de toegangscode is <strong>gelukt</strong>! Transacties kunnen nu veilig geverifieerd worden met de gateway voor de betalingen.');
  define('MB_ACTIVATION_SECRET_WORD_FAIL_TEXT', 'De configuratie van de toegangscode is  <strong>mislukt</strong>! Controleer de toegangscode op het Moneybookers "Merchant Tools" account en de configuratie van de betalingsmodule.');
  define('MB_ACTIVATION_SECRET_WORD_ERROR_TITLE', 'Fout');
  define('MB_ACTIVATION_SECRET_WORD_ERROR_EXCEEDED', 'Het maximale aantal pogingen is gedaan. Probeer over een uur opnieuw.');
  define('MB_ACTIVATION_CORE_REQUIRED_TITLE', 'Moneybookers Module vereist');
  define('MB_ACTIVATION_CORE_REQUIRED_TEXT', 'De Moneybookers betalingsmodule is vereist om de Moneybookers Quick Afrekenen betalingsopties te kunnen gebruiken. Ga a.u.b. verder met de installatie en configureer de basis betalingsmodule.');
  define('MB_ACTIVATION_VERIFY_ACCOUNT_BUTTON', 'Verifieer Account');
  define('MB_ACTIVATION_CONTINUE_BUTTON', 'Vervolg en configureer betalingsmodule');
  define('MB_ACTIVATION_SUPPORT_TITLE', 'Ondersteuning');
  define('MB_ACTIVATION_SUPPORT_TEXT', 'Neem bij vragen contact op met Moneybookers per e-mail <a href="mailto:ecommerce@moneybookers.com">ecommerce@moneybookers.com</a> of per telefoon +44 (0) 870 383 0762. Kijk echter eerst op <a href="http://forums.oscommerce.com/forum/78-moneybookers/" target="_blank">osCommerce Community Support forum</a>.');
?>
