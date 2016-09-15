<?php
/*
  $Id: header_tags_seo_social.php,v 3.0 2013/01/10 14:07:36 hpdl Exp $
  Created by Jack York from http://www.oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/
define('HEADING_TITLE', 'Header Tags SEO Social Media');
define('HEADING_TEXT_SOCIAL_ICONS', 'Dit gedeelte biedt een manier om de diverse sociale media gerelateerde items te controleren. 
Bij het invoeren van een URL voor een van de sociale pictogrammen, is het belangrijk om de zoekwoorden URL en TITEL (in caps) 
waar ze nodig zijn in de url, omdat ze zullen worden vervangen in de code met de juiste waarden. Als die niet worden ingevoerd, 
zal het item niet worden opgenomen. U moet ook het pictogram controleren door de zelfde naam. 

Het programma werkt met afbeeldingen in de directorie /uw site/images/ socialbookmarks / . Als een nieuwe afbeelding wordt toegevoegd, 
dan verschijnt deze automatisch in de pictogrammen op deze pagina met een doos voor de link. 
Ook als u een pictogram te verwijderen, zal het niet langer hier te zien zijn. Er zijn veel sites die een gratis pakket van icoon afbeeldingen bieden. 
Sommige zijn vrij uniek dus misschien wil je spelen met het samenstellen van een set die past bij uw site.
');

define('HEADING_TEXT_TWITTER_CARD', 'Dit gedeelte controleert de Twitter Card opties. Als de instelling leeg is wordt de opgeslagen instellingen verwijderd.');
define('TEXT_DISABLE', 'Niet Actief');

define('TEXT_TWITTER_CREATOR', 'Uw Twitter user naam');
define('TEXT_TWITTER_SITE', 'Site Naam');
define('TEXT_TWITTER_SUMMARY', 'Samenvatting');


define('TEXT_SIZE_16', '16x16');
define('TEXT_SIZE_24', '24x24');
define('TEXT_SIZE_32', '32x32');
define('TEXT_SIZE_48', '48x48');

define('ERROR_NO_MATCH', 'Afbeelding en urls combineren niet.');
define('RESULT_DISABLED', '&nbsp;&nbsp;Social Bookmarks Niet Actief');
define('RESULT_FAILED', '&nbsp;&nbsp;Save Niet Successvol!');
define('RESULT_FAILED_NO_SELECTION', '&nbsp;&nbsp;Niet Opgeslagen - geen icons geselecteerd');
define('RESULT_MISSING_PARAMETERS', 'De vereiste url parameters niet gevonden');
define('RESULT_SUCCESS_INSERTED', '&nbsp;&nbsp;De Data is opgeslagen');
define('RESULT_SUCCESS_REMOVED_TWITTER', '&nbsp;&nbsp;Twitter Card Verwijderd');
define('RESULT_SUCCESS_UPDATED', '&nbsp;&nbsp;De Data was successful opgeslagen');

define('RESULT_TWITTER_DATA_MISSING', 'Beide velden zijn verplicht');
?>