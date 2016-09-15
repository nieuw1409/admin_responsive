<?php
/*
  $Id: wishlist.php,v 3.11  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_WISHLIST', 'VerlangLijst');
define('HEADING_TITLE', 'VerlangLijst bevat:');
define('HEADING_TITLE2', '\'s VerlangLijst bevat:');
define('BOX_TEXT_PRICE', 'Prijs');
define('BOX_TEXT_PRODUCT', 'Product Naam');
define('BOX_TEXT_IMAGE', 'Afbeelding');
define('BOX_TEXT_SELECT', 'Selecteer');

define('BOX_TEXT_VIEW', 'Show');
define('BOX_TEXT_HELP', 'Help');
define('BOX_WISHLIST_EMPTY', '0 producten');
define('BOX_TEXT_NO_ITEMS', 'Uw VerlangLijst is leeg. <br />' ) ;
define('BUTTON_HELP',       'Click hier voor hulp voor het gebruik van uw VerlangLijst');

define('TEXT_NAME', 'Naam: ');
define('TEXT_EMAIL', 'Email: ');
define('TEXT_YOUR_NAME', 'Uw Naam: ');
define('TEXT_YOUR_EMAIL', 'Uw Email: ');
define('TEXT_MESSAGE', 'Bericht: ');
define('TEXT_ITEM_IN_CART', 'Producten in WinkelWagen');
define('TEXT_ITEM_NOT_AVAILABLE', 'Product is niet leverbaar');
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', 'Vertoont <strong>%d</strong> to <strong>%d</strong> (van <strong>%d</strong> producten van uw VerlangLijst.)');
define('WISHLIST_EMAIL_TEXT', 'Als U Uw VerlangLijst naar vrienden of familie wilt versturen, vul dan de naam en email adres voor elk familielid.  Click de VERZEND button om alle emails te verzenden.');
define('WISHLIST_EMAIL_TEXT_GUEST', 'Als U Uw VerlangLijst naar vrienden of familie wilt versturen, vul dan de naam en email adres voor elk familielid.  Click de VERZEND button om alle emails te verzenden.');
define('WISHLIST_EMAIL_SUBJECT', 'heeft zijn Verlanglijst verstuurd van  ' . STORE_NAME);  //Customers name will be automatically added to the beginning of this.
define('WISHLIST_SENT', 'Uw verlangLijst is verstuurd.');
define('WISHLIST_EMAIL_LINK', '

%s\'s De Verlanglijst is hier te bekijken:
<a href="%s">%s</a>

Met vriendelijke groet,
' . STORE_NAME); //$from_name = Customers name  $link = public wishlist link

define('WISHLIST_EMAIL_GUEST', ' verlanglijst voor bovenstaande producten.

Met vriendelijke groet,
' . STORE_NAME);

define('ERROR_YOUR_NAME' , 'Uw Naam.');
define('ERROR_YOUR_EMAIL' , 'Uw Emailadres.');
define('ERROR_VALID_EMAIL' , 'Voer een geldig email adres in.');
define('ERROR_ONE_EMAIL' , 'Uw moet minstens 1 ontvanger invoeren ( naam en email adres).');
define('ERROR_ENTER_EMAIL' , 'Uw Email adres.');
define('ERROR_ENTER_NAME' , 'Email adres van de ontvanger.');
define('ERROR_MESSAGE', 'Een korte omschrijving voor uw Verlanglijst.');
define('BUTTON_TEXT_ADD_CART', 'Voeg geselecteerde Producten toe aan uw WinkelWagen');
define('BUTTON_TEXT_DELETE', 'Verwijder geselecteerde Producten van de VerlangLijst');
define('ERROR_ACTION_RECORDER', 'Error: Een e-mail is reeds verzonden. Probeer nogmaals in %s minutes.');
define('ERROR_INVALID_LINK', 'Error: Uw bericht mag geen links bevatten naar andere web sites!');
define('ERROR_SPAM_BLOCKED', 'ERROR! Attempt to send spam by accessing this script from another web site has been detected and blocked!');
define('TEXT_SPAM_SUBJECT', 'Verlanglijst spam is geblockeerd.');
define('TEXT_SPAM_MESSAGE', "Warning! De site heeft ontdekt dat een poging tot het verzenden van spam van een andere web site door gebruik te maken van
Verlanglijst script.\n\nDatum en Tijd: %s\nKlantnummer ID: %s\nVan Naam: %s\nVan Email: %s\n\n\nVerbonden via: %s\n\nRemote Address: %s  Port: %s\nUser Agent: %s\n\n\n
Een voorbeeld van de tekst:\n\n\n");
define('TEXT_SPAM_NO_ID', 'Geen Klantnummer, niet ingelogged ');

define('HELP_HEADING_TITLE', 'VerlangLijst Help');

define('TEXT_INFORMATION', 'Als U een niet geregistreerde gebruiker van deze Webshop zal Uw verlanglijst actief zolang U actief bent op de site van de Webshop<br /><br />
Als U een nieuw account aanmaakt of inlogd op uw bestaande acount zal de verlanglijst permanent wrden opgeslagen in uw account<br/><br />
Producten worden bewaard op de velanglijst totdat U :<ol><li>U het product handmatig van uw Verlanglijst verwijderd</li><li>
U het product van uw verlanglijst in uw Winkelwagen plaatst</li><li>
Het product verwijderd is uit de webshop</li></ol><br /><br />
U kunt de producten bekijken door te clicken op de naam of afbeelding van het product<br /><br />
Een niet geregistreerde klant kan de verlanglijst versturen naar vrienden of familieleden. Een geregistreerde en ingelogde klant kan de verlanglijst versturen naar vrienden of familielende
deze kunnen dan de verlanglijst online bekijken door middel van een link<br /><br />
De Koop Nu button verplaatst de geselecteerde producten van u verlanglijst naar uw Winkelwagen en zal deze producten verwijderen van de Verlanglijst.');


?>