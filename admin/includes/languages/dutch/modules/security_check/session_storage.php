<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'De sessies map bestaat niet: ' . tep_session_save_path() . '. Sessies functie zal niet werken tot deze map wordt aangemaakt.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Ik kan niet schrijven naar de sessies map: ' . tep_session_save_path() . '. Sessies functie zal niet werken tot de juiste parameters voor deze map zijn ingesteld.');
?>
