<?
/*
Dutch Text for The Exchange Project Administration Tool Preview Release 2.0
Last Update: 17/04/2001
Author(s): Ronald Smit (ronald.smit@hetnet.nl)
*/

define('TOP_BAR_TITLE', 'Administratie informatie');
define('HEADING_TITLE', 'Wat doet deze knop?');
define('SUB_BAR_TITLE', 'The Exchange Project: Administration Tool Preview Release 2.0');
define('TEXT_MAIN', 'Any changes made with this administration tool takes effect immediately on the database. If you are unsure of what this administration tool can, and will, do, then it is suggested that you read through the <a href="http://theexchangeproject.org/documentation_dbmodel.php" target="_blank"><u>database model documentation</u></a>.<br>&nbsp;<br>What you do with this tool is caused by your own actions. No one but you can be held responsible for your actions.<br>&nbsp;<br>It is recommended that backups are made reguarly. To make backups with MySQL, use mysqldump:<br>&nbsp;<br>mysqldump catalog >./catalog.sql&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// dump the model + data to ./catalog.sql.<br>&nbsp;<br>mysql catalog <./catalog.sql&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// import the model + data to the database.<br>&nbsp;<br>This administration tool is made for Preview Release 2.0 of The Exchange Project. For quick notes on this administration tool, go to the support site.<br>&nbsp;<br><font color="#ff0000"><small><b>NOTE:</b></small></font> To upload or remove images, make sure the catalog/images directory has WRITE permission for the user that runs your Apache process (eg, nobody). This can be made by executing the following commanda:<br><br>cd catalog<br>chmod -R nobody.nobody images');

define('TABLE_HEADING_NEW_CUSTOMERS', 'Nieuw klanten');
define('TABLE_HEADING_LAST_ORDERS', 'Laatste bestellingen');
define('TABLE_HEADING_NEW_PRODUCTS', 'Nieuwe artikelen');
define('TABLE_HEADING_NEW_REVIEWS', 'Nieuwe recensies');
?>