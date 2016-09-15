<?php
/*
  $Id: wishlist.php,v 3.11  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_WISHLIST', 'My Wish List');
define('HEADING_TITLE', 'My Wish List contains:');
define('HEADING_TITLE2', '\'s Wish List contains:');
define('BOX_TEXT_PRICE', 'Price');
define('BOX_TEXT_PRODUCT', 'Product Name');
define('BOX_TEXT_IMAGE', 'Image');
define('BOX_TEXT_SELECT', 'Select');

define('BOX_TEXT_VIEW', 'Show');
define('BOX_TEXT_HELP', 'Help');
define('BOX_WISHLIST_EMPTY', '0 items');
define('BOX_TEXT_NO_ITEMS', 'No products are in your Wish List. ' ) ;
define('BUTTON_HELP',       'Click here for help on using your Wish List');

define('TEXT_NAME', 'Name: ');
define('TEXT_EMAIL', 'Email: ');
define('TEXT_YOUR_NAME', 'Your Name: ');
define('TEXT_YOUR_EMAIL', 'Your Email: ');
define('TEXT_MESSAGE', 'Message: ');
define('TEXT_ITEM_IN_CART', 'Item in Cart');
define('TEXT_ITEM_NOT_AVAILABLE', 'Item no longer available');
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> items on your wish list.)');
define('WISHLIST_EMAIL_TEXT', 'If you would like to email your wish list to multiple friends or family, just enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people to whom you want to email your wish list link.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all of the emails you send. Click the Continue button to send the emails.');
define('WISHLIST_EMAIL_TEXT_GUEST', 'If you would like to email your wish list to multiple friends or family, please enter your name and email address.  Then enter their name\'s and email\'s in each row.  You don\'t have to fill every box up, you can just fill in for however many people to whom you want to email your wish list products.  Then fill out a short message you would like to include in with your email in the text box provided.  This message will be added to all of the emails you send. Click the Continue button to send the emails.');
define('WISHLIST_EMAIL_SUBJECT', 'has sent you their wish list from ' . STORE_NAME);  //Customers name will be automatically added to the beginning of this.
define('WISHLIST_SENT', 'Your wish list has been sent.');
define('WISHLIST_EMAIL_LINK', '

%s\'s public wish list is located here:
<a href="%s">%s</a>

Thank you,
' . STORE_NAME); //$from_name = Customers name  $link = public wishlist link

define('WISHLIST_EMAIL_GUEST', ' wishes for the products listed above.

Thank you,
' . STORE_NAME);

define('ERROR_YOUR_NAME' , 'Please enter your Name.');
define('ERROR_YOUR_EMAIL' , 'Please enter your Email.');
define('ERROR_VALID_EMAIL' , 'Please enter a valid email address.');
define('ERROR_ONE_EMAIL' , 'You must include at least one name and email.');
define('ERROR_ENTER_EMAIL' , 'Please enter an email address.');
define('ERROR_ENTER_NAME' , 'Please enter the email recipents name.');
define('ERROR_MESSAGE', 'Please include a brief message.');
define('BUTTON_TEXT_ADD_CART', 'Add Checked Items To Shopping Cart');
define('BUTTON_TEXT_DELETE', 'Delete Checked Items From Wish List');
define('ERROR_ACTION_RECORDER', 'Error: An e-mail has already been sent. Please try again in %s minutes.');
define('ERROR_INVALID_LINK', 'Error: Your message may not contain links to other web sites!');
define('ERROR_SPAM_BLOCKED', 'ERROR! Attempt to send spam by accessing this script from another web site has been detected and blocked!');
define('TEXT_SPAM_SUBJECT', 'Attempted Wish List spam was blocked.');
define('TEXT_SPAM_MESSAGE', "Warning! The site detected an attempt to send spam from another web site using the Wish List script.\n\nDate and Time: %s\nCustomer ID: %s\nFrom Name: %s\nFrom Email: %s\n\n\nAttempted to connect from: %s\n\nRemote Address: %s  Port: %s\nUser Agent: %s\n\n\nThe following is the attempted message:\n\n\n");
define('TEXT_SPAM_NO_ID', 'No customer ID, not logged in');

define('HELP_HEADING_TITLE', 'Wish List Help');

define('TEXT_INFORMATION', 'If you are a guest user on this site your wish list will remain only as long as your browser is open.<br /><br />Log in to your accout (or create one) and your wish list will be permanently saved.<br /><br />Items in a permanently saved wish list will remain there until:<ol><li>You remove it from your wish list yourself.</li><li>You transfer the item from your wish list to your shopping cart.</li><li>The item is permanently deleted from the web site.</li></ol><br /><br />When viewing your wish list you may click the product name or image to view details about the product.<br /><br />Whan a guest user emails friends a wish list they will receive the list of products. If you are logged in to your account and email the wish list they will receive a link to a page where they can view your permanent wish list.<br /><br />The Add to Cart button on the wish list page transfers the checked items from your wish list to your shopping cart, thus removing them from the wish list.');

?>