<?php
/*
  $Id: index.php,v 1.1 2004/02/16 06:59:36 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  define('PAGE_TITLE_WELCOME', 'Welkom bij osCommerce ! versie : ');
  define('TEXT_INDEX', '<strong>osCommerce</strong> Online Webshop helpt U verkopen van Uw producten op het internet. 
 OsCommerce is een open source pakket om een webwinkel te maken. Het is een uitstekend pakket om te gebruiken voor een online winkel- en e-commerce toepassing. OsCommerce is in het Nederlands vertaald en kent vele handige uitbreidingen, onder andere voor betaalmogelijkheden.

Met osCommerce kunt u eenvoudig een online winkel beginnen op uw website. U kunt een catalogus met productbeschrijvingen aanmaken. Klanten kunnen producten in hun winkelwagentje verzamelen en aan het einde door middel van diverse betaalmogelijkheden afrekenen.

De online betaaloplossingen van ChronoPay.nl, TrustCommerce, SecPay, iPayment en PayPal kunnen automatisch worden geinstalleerd. Vele andere betaalmodulen zijn ook beschikbaar.<br > U kunt Oscommerce ook uitbreiden met extenties om de functionaliteit te vergroten. Deze kunt u vinden op de website van Oscommerce');

  define('TEXT_CHOOSE_LANGUAGE', 'Kies de taal van dit Programma: ');
  define('TEXT_CONTINUE', 'Verder');
  define('TEXT_SERVEER_CAPABILITIES', 'Server Status ');
  define('TEXT_PHP_VERSION', 'PHP Versie ');

  define('TEXT_PHP_SETTINGS', 'PHP configuratie');
  define('TEXT_R_G', 'register_globals');
  define('TEXT_M_Q', 'magic_quotes');
  define('TEXT_F_U', 'file_uploads ');
  define('TEXT_SA_S', 'session.auto_start');
  define('TEXT_SU_T_S', 'session.use_trans_sid');
  define('TEXT_PHP_EXTENSIONS', 'Vereiste PHP Extensies');
  define('TEXT_M_S', 'MySQL');
  define('TEXT_OPT_PHP_EXT', 'Optionele PHP Extensies');
  define('TEXT_GD', 'GD libery');
  define('TEXT_CURL', 'cUrl');
  define('TEXT_OPENSSL', 'OpenSSL');  

  define('TEXT_INSTALLATION', 'Installeer osCommerce');
  define('TEXT_PARAMETERS', 'Het is niet mogelijk om het configuratie bestand op te slaan op de webserver (IF003).');
  define('TEXT_CHMOD', 'Voor de volgende bestanden is het noodzalijk om de file permissions op de webserver te veranderen naar world-writeable (chmod 777) (IF004):');
  define('TEXT_CORRECT', 'Wijzig of verander bovenstaande fouten en probeer nogmaals de installatie procedure.');
  define('TEXT_CHANGING', 'Als u verandering heeft doorgevoerd in de configuratie parameters van webserver, kan het opnieuw starten van de webserver noodzakelijk zijn om de wijzigingen door te voeren.');
  define('TEXT_VERIFIED', 'De webserver gegevens zijn gecontroleerd. De ingevoerde gegevens worden opgeslagen en de installatie zal worden afgerond.');
  define('TEXT_INSTALL_PROCEDURE', '<br />Het is mogelijk om Oscommerce te installeren als een normale Versie met WINKEL en ADMINISTRATIE. Druk op STANDAARD om verder te gaan met de installatie van Oscommerce.
                              <br /><br />Daarnaast kan deze versie van Oscommerce geinstalleerd worden als tweede, derde etc. Winkel. Druk op MULTI-WINKEL om verder te gaan
							  <br /><br />Voor een multi winkel installatie hoeft de admin directory niet gecopierd worden op de tweede ( of derde, vierde etc ) website. De administratie wordt geregeld op de website van de eerste webwinkel');
  define('IMAGE_CONTINUE', 'VERDER') ;
  define('IMAGE_RETRY', 'OPNIEUW') ;
  define('IMAGE_STD_SHOP', 'STANDAARD') ;
  define('IMAGE_MULTI_SHOP', 'MULTI-WINKEL') ;
  
  define('TEXT_NEW_INSTALLATION', 'Nieuwe Installatie') ;  
  define('TEXT_COMPAT_PHP43' , 'Compatibility with register_globals is supported from PHP 4.3+. This setting <u>must be enabled</u> due to an older PHP version being used.' ) ;
  define('TEXT_MYSQL_EXT',   'The MySQL extension is required but is not installed. Please enable it to continue installation.' ) ;
  define('TEXT_ENABLE_JAVA', 'Activeer Javascript in uw browser om de installatie af te ronden.' ) ;  

  define('TEXT_STANDARD_INSTALL', 'Start de STANDAARD installatie procedure' ) ;
  define('TEXT_MULTISHOP_INSTALL', 'Start de MULTIWINKEL installatie procedure' ) ;  
  
?>
