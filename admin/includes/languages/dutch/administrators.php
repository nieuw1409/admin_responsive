<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Beheerders');

define('TABLE_HEADING_ADMINISTRATORS', 'Beheerders');
define('TABLE_HEADING_HTPASSWD', 'Beveiligd met htpasswd');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_INFO_INSERT_INTRO', 'Vul de gegevens van de nieuwe beheerder in');
define('TEXT_INFO_EDIT_INTRO', 'Maak alle noodzakelijke wijzigingen');
define('TEXT_INFO_DELETE_INTRO', 'Wilt u deze beheerder verwijderen?');
define('TEXT_INFO_HEADING_NEW_ADMINISTRATOR', 'Nieuwe Beheerder');
define('TEXT_INFO_USERNAME', 'Gebruikersnaam:');
define('TEXT_INFO_NEW_PASSWORD', 'Nieuw wachtwoord:');
define('TEXT_INFO_PASSWORD', 'Wachtwoord:');
define('TEXT_INFO_PROTECT_WITH_HTPASSWD', 'Beveiligd met htaccess/htpasswd');

define('ERROR_ADMINISTRATOR_EXISTS', 'Fout: Beheerder bestaat al.');

define('HTPASSWD_INFO', '<strong>Aanvullende beveiliging met htaccess/htpasswd</strong><p>Deze osCommerce installatie is niet aanvullend beveiligd met htaccess/htpasswd.</p><p>Door de htaccess/htpasswd beveiliging in te schakelen worden automatisch gegevens van beheerders in een htpasswd bestand bijgewerkt wanneer gegevens van beheerders gewijzigd worden.</p><p><strong>NB.: </strong>indien deze beveiliging is ingeschakeld en u kunt uw winkel niet meer beheren, maak dan de volgende wijzigingen en raadpleeg uw provider om deze beveiliging in te schakelen:</p><p><u><strong>1. Wijzig het bestand:</strong></u><br /><br />' . DIR_FS_ADMIN . '.htaccess</p><p>Verwijder de volgende regels indien die er zijn:</p><p><i>%s</i></p><p><u><strong>2. Verwijder het bestand:</strong></u><br /><br />' . DIR_FS_ADMIN . '.htpasswd_oscommerce</p>');
define('HTPASSWD_SECURED', '<strong>Aanvullende beveiliging met htaccess/htpasswd</strong><p>De installatie van het beheerprogramma is aanvullend beveiligd met htaccess/htpasswd.</p>');
define('HTPASSWD_PERMISSIONS', '<strong>Aanvullende beveiliging met htaccess/htpasswd</strong><p>De installatie van het beheerprogramma is niet aanvullend beveiligd met htaccess/htpasswd.</p><p>De volgende bestanden moeten schrijfbaar zijn voor de webserver om de htaccess/htpasswd security layer te kunnen inschakelen :</p><ul><li>' . DIR_FS_ADMIN . '.htaccess</li><li>' . DIR_FS_ADMIN . '.htpasswd_oscommerce</li></ul><p>Herlaad deze pagina om de gewijzigde bestandsrechten te controleren.</p>');
?>
