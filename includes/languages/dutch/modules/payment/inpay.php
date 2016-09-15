<?php
/*
 $Id: inpay.php VER: 1.0.3443 $
 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com
 Copyright (c) 2008 osCommerce
 Released under the GNU General Public License
 */

  define('MODULE_PAYMENT_INPAY_TEXT_TITLE', 'Inpay - instant online bank transfers');
  define('MODULE_PAYMENT_INPAY_TEXT_PUBLIC_TITLE', 'Betaal met uw online bank - meteen en 100% veilig');
  define('MODULE_PAYMENT_INPAY_TEXT_PUBLIC_HTML', '<img src="https://resources.Inpay.com/images/oscommerce/inpay_checkout.png" alt="Veilig afrekenen met Inpay" /><br /><br />
  <table cellspacing="5">
  	  <tr><td><img src="https://resources.Inpay.com/images/oscommerce/inpay_check.png" alt="100% Veilig betalen met Inpay" /></td><td class="main">100% Veilig betalen met Inpay <span style="color: #666;">- onze beveiliging komt overeen met die van uw online bank.</span></td></tr>
  	  <tr><td><img src="https://resources.Inpay.com/images/oscommerce/inpay_check.png" alt="Instant payments met Inpay" /></td><td class="main">Onmiddellijke betalingen met Inpay <span style="color: #666;">- ons systeem zorgt er voor dat u uw bestelling zo spoedig mogelijk ontvangt.</span></td></tr>
  	  <tr><td><img src="https://resources.Inpay.com/images/oscommerce/inpay_check.png" alt="Anoniem betalen met Inpay" /></td><td class="main">Anoniem betalen met Inpay <span style="color: #666;">- zonder uw kredietkaart-nummer of andere persoonlijke informatie.</span></td></tr>
  </table><a href="http://Inpay.com/shoppers" style="text-decoration: underline;" target="_blank" class="main">Klik hier om meer te lezen over Inpay</a><br />');
  define('MODULE_PAYMENT_INPAY_TEXT_DESCRIPTION', '<strong>Wat is Inpay?</strong><br />
  	  Inpay is een extra betalingsoptie voor webshops, dat klanten toelaat te betalen via hun online bank - meteen en wereldwijd.<br />
  	  <br />
  	  <strong>Verhoog inkomsten</strong><br />
	Door klanten toe te laten te betalen via hun online bank, kunt u nu verkopen aan klanten die anders niet willen of niet kunnen betalen.<br />
<br />
<strong>Verhoog uw marktaandeel</strong><br />
Door uw klanten de Inpay betalingsmogelijkheid te bieden breidt u uw aanwezigheid niet enkel uit naar kredietkaartbezitters, maar ook naar gebruikers van online banken over de gehele wereld.<br />
<br />
<strong>Geen risico</strong><br />
Met Inpay is er geen risico op kredietkaartfraude of enige vorm van verrekeningen achteraf. Dit betekent dat wanneer u betaald werd, u betaald blijft! Met Inpay kan u zelfs verkopen aan klanten van \'hoge risico-gebieden\' inclusief alle delen van Azi&euml; en Oost-Europa.<br /><br />
  <a href="http://Inpay.com/" style="text-decoration: underline;" target="_blank">Lees meer over inschrijven bij Inpay.com</a><br />');
  // ------------- e-mail settings ---------------------------------
  define('EMAIL_TEXT_SUBJECT', 'Betaling bevestigd door Inpay');
  define('EMAIL_TEXT_ORDER_NUMBER', 'Bestelnummer:');
  define('EMAIL_TEXT_INVOICE_URL', 'Gedetailleerde factuur:');
  define('EMAIL_TEXT_DATE_ORDERED', 'Besteldatum:');
  define('EMAIL_TEXT_PRODUCTS', 'Artikelen');
  define('EMAIL_TEXT_SUBTOTAL', 'Subtotaal:');
  define('EMAIL_TEXT_TAX', 'BTW:        ');
  define('EMAIL_TEXT_SHIPPING', 'Verzending: ');
  define('EMAIL_TEXT_TOTAL', 'Totaal:    ');
  define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Leveringsadres');
  define('EMAIL_TEXT_BILLING_ADDRESS', 'Betalingsadres');
  define('EMAIL_TEXT_PAYMENT_METHOD', 'Betalingswijze');
  define('EMAIL_SEPARATOR', '------------------------------------------------------');
  define('TEXT_EMAIL_VIA', 'via'); 
  
?>