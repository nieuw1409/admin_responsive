<?php
/*
  $Id: database_optimizer_cron.php,v 1.0 2011/02/02
  database_optimizer_cron.php Originally Created by: Jack_mcs - http://www.oscommerce-solution.com
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2011 oscommerce-solution.com

  Released under the GNU General Public License
*/
?>
<style type="text/css">
.describe {padding-bottom:5px; }
</style>

<?php
define('TEXT_DESCRIPTIONS', '

<div style="text-align:center;  font-size:14px; font-weight:bold; padding-bottom:10px; ">
 <div>Database Optimizer</div>
 <div>by Jack_mcs of </div>
 <div>oscommerce-solution.com</div>
</div>

<div class="describe">
<b>Analyze</b> - Cleans up internal keys in the tables and improves the speed of the database. This is the same function available
in phpmyadmin and is safe to run.
</div>

<div class="describe">
<b>Optimize</b> - Defragments the database and reduces query times, in some cases. A database can be fragmented and not load
any slower. It depends on which parts are fragmented. This is the same function available
in phpmyadmin and is safe to run.
</div>

<div class="describe">
<b>Customers</b> - When a customer adds something to their cart but never completes the order, the product stays in the customers basket
and customers basket attributes tables. These are never removed so over time all they are doing is bloating the database.
</div>

<div class="describe">
<b>Customers Old</b> - Many times a customer, hacker or just some person curious about how your site works will create an account
and never use it. These accounts are never removed and just bloat the database and should be removed. The default value is
set to 300 days. If a person hasn\'t used an account in almost a year, they probably won\'t. But you can change that 
value in the settings.
</div>

<div class="describe">
<b>Orders CC</b> - If you store credit card numbers, this option will remove them from the orders table. Be sure to set the number of 
days in the settings high enough so that credit card numbers you may need are not deleted.
</div>

<div class="describe">
<b>Sessions</b> - Session numbers are stored for every visitor, sometimes more than one per visitor. They never get deleted so the
sessions table can become quite large. It is the most likely table to have failures so keeping it trimmed will help reduce
that as well as speed up access times.
</div>

<div class="describe">
<b>User Tracking</b> - This option only shows up if you have this contribution installed. It stores a large amount of data, most of
which is useless after a few days. While this contribution can be useful to a site, it can swell a database to many times its
normal size so the user tracking table should be cleared fairly regularly.
</div>

');

define('TEXT_CLOSE_WINDOW', 'Close Window [x]');
?>