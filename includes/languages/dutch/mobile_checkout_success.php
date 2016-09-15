<?php
/*
  $Id: checkout_success.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Succes');

define('HEADING_TITLE', 'Uw Order is verwerkt!');

define('TEXT_SUCCESS', 'Uw order is verwerkt! Uw producten zullen worden geleverd binnen 2-5 werkdagen.');
define('TEXT_NOTIFY_PRODUCTS', 'Please notify me of updates to the products I have selected below:');
define('TEXT_SEE_ORDERS', 'Uw kunt Uw order historie bekijken op devolgende pagina <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Mijn Account\'</a> pagina en clicking op <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'Historie\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', 'Indien U vragen heeft kunt elke vraag stellen aand <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">Webshop Eigenaar</a>.');
define('TEXT_THANKS_FOR_SHOPPING', 'Hartelijk dank voor uw order !');

define('TABLE_HEADING_COMMENTS', 'Voer een tekst in als commentaar voor de geplaatste order');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Datum vervallen: ');
define('TABLE_HEADING_DOWNLOAD_COUNT', ' downloads resterend');
define('HEADING_DOWNLOAD', 'Download uw producten hier:');
define('FOOTER_DOWNLOAD', 'U kunt de download van uw producten ook op een later tijdstip voltooien \'%s\'');
?>