<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_ACTION_RECORDER_RESET_PASSWORD_TITLE', 'Klant Wachtwoord Reset');
  define('MODULE_ACTION_RECORDER_RESET_PASSWORD_DESCRIPTION', 'Aantal Wachtwoord resets van een klant.');
  
  define('TITLE_MODULE_ACTION_RECORDER_RESET_PASSWORD_ATTEMPTS', 'Aantal Minuten Login ' ) ; //Allowed Minutes
  define('DESCRIPTION_MODULE_ACTION_RECORDER_RESET_PASSWORD_ATTEMPTS',  'Het aantal minuten dat er weer een login poging mag worden ondernomen na het maximaal onderstaande inlog pogingen' ) ;  //Number of minutes to allow password resets to occur.

  define('TITLE_MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES', 'Aantal Pogingen voor Opnieuw Aanvragen Wachtwoord'  ) ; //Allowed Attempts
  define('DESCRIPTION_MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES', 'Het aantal pogingen om een Wachtwoord aan te vragen die per sessie mag worden uitgevoerd' ) ;    //Number of password reset attempts to allow within the specified period.
  
//     return array('MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES', 'MODULE_ACTION_RECORDER_RESET_PASSWORD_ATTEMPTS');

?>
