<?php
/*
  $Id: newsletter_unsubscribe.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Afmelden');
define('HEADING_TITLE', 'Afmelden Nieuwsbrief');

define('PREVENIR_EMAIL_DESINSCRIT_NL', 'oui');
define('EMAIL_DESINSCRIT_NL', 'Afmelden van onze Nieuwsbrief');

define('UNSUBSCRIBE_TEXT_INFORMATION', '<br />Wij vinden het jammer dat u zich wilt afmelden voor de nieuwsbrief. Wij hebben devolgende <a href="' .FILENAME_PRIVACY . '"><u>privacy regels</u></a>.<br /><br />Om u af te melden klikt u op onderstaande verzendknop. Uiteraard kunt u zich altijd weer opnieuw aanmelden voor de nieuwsbrief.<br /><br />
                                              Nadat u klikt op onderstaande verzendknop, ontvangt u aansluitend per e-mail een bevestiging van uw opzegging.');

define('UNSUBSCRIBE_TEXT_OK', '<br />Uw emailadres <strong>(%s)</strong> is verwijderd van onze nieuwsbrief lijst.<br /><br />');

define('UNSUBSCRIBE_TEXT_ERROR', '<br />Email adres <strong>(%s)</strong> niet gevonden in onze database, of uw email adres is reeds verwijderd van onze nieuwsbrief lijst.<br /><br />');
define('UNSUBSCRIBE_SUBSCRIBER', 'Nieuwsbrief Gebruiker') ;
define('UNSUBSCRIBE_CUSTOMER',   'Bestaande Klant') ;
?>