<?php
/*
  $Id: header_tags_seo_silo.php,v 3.0 2009/10/10 14:07:36 hpdl Exp $
  Created by Jack_mcs from http://www.oscommerce-solution.com
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/

define('HEADING_TITLE_SILO', 'Header Tags Silo Control');
define('HEADING_TITLE_SECTION_MAIN', 'Main Control');
define('HEADING_TITLE_SECTION_LINKS', 'Links Control');

define('SELECT_A_FILE', 'Select A File');

define('TABLE_HEADING_CAT_NAME', 'Category Name');
define('TABLE_HEADING_BOX_TITLE', 'Box Title');
define('TABLE_HEADING_MAX_LINKS', 'Max Links');
define('TABLE_HEADING_SORT_ORDER', 'Sort Order');

define('TEXT_PAGE_HEADING', 'Dit gedeelte regelt de links in een infobox die worden weergegeven wanneer de categorieen of product lijst pagina 
worden weergegeven . Er is een instelling in de header-tags configuratiesectie dat moet ook worden ingeschakeld . 
Deze optie maakt beschikking een SEO techniek van " Siloing " en wordt gebruikt in SEO cirkels om het belang van een bepaalde pagina te verhogen . 
Door het hebben van meer links naar een pagina , zien de zoekmachines  die pagina als belangrijker . Dit kan , in theorie ,  helpen bij alle zoekmachines , 
maar moet vooral nuttig met Googles page ranking systeem. In de praktijk , wanneer een Silo wordt weergegeven , de andere links , 
zoals de categorieen infobox , moet echt worden verwijderd. <br><br>

Geen van de onderstaande opties is nodig om dit te gebruiken . 
Zolang de belangrijkste bovengenoemde optie is ingeschakeld, zal da standaard waarden actief zijn . Maar elke categorie kan vanaf hier worden ingesteld  , 
indien gewenst. Als u slechts een of twee categorieen om deze optie te gebruiken , vinkt u het vakje uitschakelen 
terwijl het selecteren Categorieën zichtbaar is en klik op bijwerken . Selecteer vervolgens een bepaalde categorie en stel deze in. 
Alle, maar die ene categorie worden uitgeschakeld 
');

define('SELECT_ALL_CATEGORIES', 'Selecteer Alle Categorien');
define('TEXT_FILTER_LIST_CATEGORY', 'Categorien');

define('ENTRY_SELECT_A_PAGE', 'Selecteen Een Pagina');
define('ENTRY_SILO_BOX_TITLE', 'Silo Box Titel:');
define('ENTRY_SILO_DISABLE', 'Niet Actief');
define('ENTRY_SILO_NUMBER_LINKS', 'Aantal Links:');
define('ENTRY_SILO_SORT_BY', 'Sorteer Op:');
define('ENTRY_SILO_SORT_BEST', 'Best Seller');
define('ENTRY_SILO_SORT_DATE', 'Datum');
define('ENTRY_SILO_SORT_NAME', 'Naam');
define('ENTRY_SILO_SORT_CUSTOM', 'Custom');

define('ERROR_INCORRECT_MAX_LINKS', 'Error - Het Aantal links moet groter zijn dan 0 voor %s taal.');
?>