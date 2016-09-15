<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  Released under the GNU General Public License
*/

  $paypal_express_ping_button = '';
  if (defined('MODULE_PAYMENT_PAYPAL_EXPRESS_STATUS')) {
    $paypal_express_ping_button = '<p><img src="images/icons/locked.gif" border="0">&nbsp;<a href=' . tep_href_link('ext/modules/payment/paypal/paypal_express.php', 'action=test', 'SSL') . ' target="_blank" style="text-decoration: underline; font-weight: bold;">Test API Voorwaarden</a></p>';
  }

  define('MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_TITLE', 'PayPal Express Afrekenen');
  define('MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_PUBLIC_TITLE', 'PayPal (inclusief kredietkaarten)');
  define('MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_DESCRIPTION', '<img src="images/icon_popup.gif" border="0">&nbsp;<a href="https://www.paypal.com/mrb/pal=PS2X9Q773CKG4" target="_blank" style="text-decoration: underline; font-weight: bold;">Bezoek de PayPal website</a>&nbsp;<a href="javascript:toggleDivBlock(\'paypalExpressInfo\');">(info)</a><span id="paypalExpressInfo" style="display: none;"><br /><i>Door bovenstaande link te gebruiken om u aan te melden bij PayPal krijgt osCommerce een kleine financi&euml;le bonus voor het aanbrengen van een klant.</i></span>' . $paypal_express_ping_button);
  define('MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_BUTTON', 'Afrekenen met PayPal');
  define('MODULE_PAYMENT_PAYPAL_EXPRESS_TEXT_COMMENTS', 'Opmerkingen:');
  define('MODULE_PAYMENT_PAYPAL_EXPRESS_EMAIL_PASSWORD', 'Er is een account voor u aangemaakt met volgend e-mailadres en wachtwoord:' . "\n\n" . 'Winkel Account e-mailadres: %s' . "\n" . 'Winkel Account Wachtwoord: %s' . "\n\n");
  define('MODULE_PAYMENT_PAYPAL_EXPRESS_BUTTON', 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');
  define('MODULE_PAYMENT_PAYPAL_EXPRESS_LANGUAGE_LOCALE', 'nl_NL');

  unset($paypal_express_ping_button);
?>
