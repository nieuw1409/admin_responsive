<?php
/*
  $Id: install.php,v 1.3 2004/11/07 21:02:11 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  define('PAGE_TITLE_INSTALLATION', 'Nieuwe Installatie');
  define( 'PAGE_TITLE_INSTALLATION_MULTI_STORE', 'Nieuwe Installatie Multi Winkel');  // multi store
  define('TEXT_BOX_DB', 'Database Server');
  define('TEXT_BOX_WS', 'Web Server');
  define('TEXT_BOX_ON', 'Online Webshop Instellingen');
  define('TEXT_BOX_FINISH', 'Klaar!');

  define('TEXT_DESCRIPTION', 'Deze web-based installatie routine verzameld de juiste gegevens om de Oscommerce Webshop te installeren en te configuren op de webserver.<br /><br />
  Volg instructions voor de installatie van de database server, web server, en winkel configuratie opties. Voor eventuele hulp bij de verschillende instellingen verwijzen wij U naar de website van Oscommerce.');
  define('TEXT_DESCRIPTION2', '<br />De data bank moet reeds geinstallerd zijn als hoofd winkel om deze tweede, derde etc. winkel te installeren<br />' ) ;
  define('TEXT_STEP1', 'Stap 1: Database Server');
  define('TEXT_STEP1_TEXT1', 'De database server verzameld en slaat de gegevens op van de online webshop zoals gegevens van klanten, producten, kortingen etc.');
  define('TEXT_STEP1_TEXT2', 'Indien de database server parameters niet bekend zijn raadpleeg uw provider van de webserver.');

  define('TEXT_DATABASE_TEXT', 'Het adres van de database server ( of een hostname of een IP address).');
  define('TEXT_DATABASE_USERNAME', 'Gebruikersnaam');
  define('TEXT_DATABASE_USERNAME_TEXT', 'De gebruikersnaam welke gebruikt wordt om contact te maken met de database server.');
  define('TEXT_DATABASE_PASSWORD', 'Wachtwoord');
  define('TEXT_DATABASE_PASSWORD_TEXT', 'Het wachtwoord welke wordt gebruikt in combinatie met de gebruikersnaam om contact te maken met de database server.');
  define('TEXT_DATABASE_NAME', 'Databank Naam');
  define('TEXT_DATABASE_NAME_TEXT', 'De naam van de databank waar de gegevens worden opgeslagen.');

  define('CONNECT_TESTING', 'Test database connectie..');
  define('CONNECT_PROBLEM', 'Het inlezen en opslaan van de artikelgegevens in de databank is mislukt. Het probleem is:</p><p><strong>%s</strong></p><p>Controleer de connectie parameters en probeer opnieuw.');
  define('CONNECT_PROBLEM_MS', 'De databank is aanwezig. Het probleem is:<p><b>%s</b></p>Controleer de connectie parameters en probeer opnieuw.');  // multi stores
  define('CONNECT_IMPORTED', 'Het inlezen en opslaan van de artikelgegevens in de databank is gestart. Dit kan enkele minuten duren.');
  define('CONNECT_SUCCESSFULLY', 'Het inlezen en opslaan van de artikelgegevens in de databank is voltooid.');
  define('CONNECT_SUCCESSFULLY_MS', 'De databank is aanwezig de installatie wordt voltooid.');   // multi stores

  define('TEXT_STEP2', 'Stap 2: Web Server');
  define('TEXT_STEP2_TEXT', 'De web server verzorgt de aanvragen voor het tonen van de verschillende pagina van de webshop. ') ;
                             
  define('TEXT_WWW_ADDRESS', 'WWW Addres');
  define('TEXT_WWW_ADDRESS_TEXT', 'Het web addres van de online webshop.');
  define('TEXT_WWW_ROOT_DIRECTORY', 'Webserver Root Directory');
  define('TEXT_WWW_ROOT_DIRECTORY_TEXT', 'De directory waar de online webshop is geinstalleerd op de server.');

  define('TEXT_STEP3', 'Step 3: Online Webshop Instellingen');
  define('TEXT_STEP3_TEXT', 'Op deze pagina worden gegevens verzameld van de online webshop en de contact information van de webshop eigenaar.<br /><br />
      De administratie gebruikersnaam en wachtwoord worden gebruikt voor het inloggen in het beveiligde administratie gedeelte van Uw webshop.');
  define('TEXT_STORE_NAME', 'Webshop Naam');
  define('TEXT_STORE_NAME_TEXT', 'De naam van de Webshop.');
  define('TEXT_OWNER_NAME', 'Naam Eigenaar Webshop');
  define('TEXT_OWNER_NAME_TEXT', 'De naam van de eigenaar van de webshop.');
  define('TEXT_OWNER_MAIL', 'E-Mail Adres Eigenaar');
  define('TEXT_OWNER_MAIL_TEXT', 'Het E-mail addres van de eigenaar van de webshop.');
  define('TEXT_AU', 'Administratie Gebruikersnaam');
  define('TEXT_AU_TEXT', 'De naam van de hoofd administrator .');
  define('TEXT_AP', 'Administratie Wachtwoord');
  define('TEXT_AP_TEXT', 'Het Wachtwoord voor het administratie account.');
  define('TEXT_ADMIN_DN', 'Administratie Directory Naam');
  define('TEXT_ADMIN_DN_TEXT', 'Dit is de directory waar de administratie is geinstalleerd. De directory moet worden hernoemd vanwege veiligheids redenen.');
  define('TEXT_CFG_TIME_ZONE', 'Tijdzone van de Winkel');  
  define('TEXT_CFG_TIME_ZONE_TEXT', 'De tijdzone voor het bepalen van de datum en de datum.' ) ;
  
  define('TEXT_STEP4', 'Step 4: Compleet !');
  define('TEXT_STEP4_TEXT', 'Uw osCommerce Online Webshop is successvol geinstalleerd en geconfigureerd!<br /><br />.');
  define('TEXT_STEP4_TEXT2', 'Wij wensen u het beste met het succes van uw online Webwinkel.') ;  
  define('TEXT_OSC_TEAM', '- Het osCommerce Team');
  define('TEXT_SUCCESSFUL', 'De installatie en configuratie was successvol!');
  define('TEXT_RENAME', 'Hernoem de Administratie Tool directory ');
  
  define('TEXT_ADMINISTRATION', 'Administratie toegang details:<br />
  E-Mail: <i><b>admin@localhost.com</b></i><br />
  Wachtwoord: <i><b>admin</b></i><br /><font color=red>
  <b>Na een eerste inlogprocedure is het raadzaam om het een nieuwe administratie account voor het inloggen in het admnistratie gedeelte van uw Webwinkel.</b></font>');

  define('TEXT_CONTINUE', 'Verder');
  define('TEXT_CENCEL', 'Stop');
  define('TEXT_CATALOG', 'Webwinkel ');
  define('TEXT_ADMIN_TOOL', 'Administratie Webwinkel');
  define('TEXT_POST_INSTALL', 'Post-Installaties Opmerkingen ');
  define('TEXT1_POST_INSTALL', 'Na installatie van uw Webwinkel is het aan te bevelen om devolgende acties te ondernemen om de veiligheid van uw Webwinkel te waarborgen:');
  define('TEXT_Rename', 'Hernoem de Administration directorie ');
  define('TEXT_SET', 'Verander de bestands permissions van  ');
  define('TEXT_TO', ' naar 644 (of 444 als het bestand nog beschrijfbaar is).');
  define('TEXT_DEL', 'Verwijder de ');
  define('TEXT_D', ' directory');
  define('TEXT_REW', 'Bekijk de directorie beveiliging permissions in het Administratiie gedeelte van uw Webwinkel -> Tools -> Security Directory Permissions page.');
  define('TEXT_REW2', 'Het Administratie gedeelte van uw Webwinkel kan additioneel worden beveiligd door gebruik te maken van htaccess/htpasswd. Deze instellingen kunnen worden gewijzigd in de Configuration -> Administratie pagina.');
  define('IMAGE_CONTINUE', 'VERDER') ;
  define('IMAGE_CANCEL', 'BEGIN SCHERM') ;  
  define('IMAGE_TOWebwinkel', 'NAAR Webwinkel') ;  
  define('IMAGE_TOADMIN', 'INLOGGEN ADMINISTRATIE') ;  
  define('TEXT_MULTI_SHOP', 'Voordat U de multi shop kunt gaan gebruiken moet u de instellingen aanpassen <br />
                             - Activeer Talen voor deze shop <br />
                             - Voeg Producten toe aan deze Webwinkel<br />
                             - etc <br />') ;	

  define('TEXT_INPUT_REQUIRED', 'Invoer verplicht !' ) ;							 
  define('TEXT_NEXT_STEP2', 'Verder naar Stap 2' ) ;
  define('TEXT_NEXT_STEP3', 'Verder naar Stap 3' ) ;
  define('TEXT_NEXT_STEP4', 'Verder naar Stap 4' ) ;  
?>
