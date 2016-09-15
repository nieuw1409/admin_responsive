<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  SPPC Group List 2.0 by stClem
*/

  require('includes/application_top.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<!-- header_eof //-->

<!-- body //-->
<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
   <tr>
        <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
        <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . 'store_logo.png', STORE_NAME); ?></td>
      </tr>
<tr><td class="pageHeading"><br>	            
              
<?php
// Get language
$sppcgllangnr = tep_db_query("SELECT languages_id FROM languages WHERE directory = '$language'");
 while($row3 = tep_db_fetch_array($sppcgllangnr)) 
  { 
 $sppcgllang = $row3['languages_id'];  
  }

//Get customer
$customerid = $_GET['Customer'];

$sppcglcompany = tep_db_query("SELECT entry_company FROM address_book WHERE customers_id = $customerid");
 while($row = tep_db_fetch_array($sppcglcompany))
  {
   echo $row['entry_company'];
  }
?> </td></tr><table border="1" width="600" cellspacing="0" cellpadding="2">
<?php
$sppcglcustomernr = tep_db_query("SELECT customers_group_id FROM customers WHERE customers_id = $customerid");
 while($row2 = tep_db_fetch_array($sppcglcustomernr)) 
  { 
 $sppcglcustomer = $row2['customers_group_id'];  
  }

  // Get list
  $specials_methods = "SELECT products.products_model, products_description.products_name, products_groups.customers_group_price FROM products_groups, products, products_description WHERE products_groups.products_id = products.products_id AND products.products_id = products_description.products_id AND products_groups.customers_group_id =  $sppcglcustomer AND products_description.language_id = $sppcgllang";
  $specials_query = tep_db_query($specials_methods);

  while ($specials = tep_db_fetch_array($specials_query)) {
  
 
?>

<tr>
<td class="dataTableContent" width="70"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'search=' . $specials['products_model'], 'NONSSL') . '">' . $specials['products_model']; '<a>'; ?></td>	
			  <td class="dataTableContent" width="430"><?php echo $specials['products_name']; ?></td>	
			  <td class="dataTableContent" width="100"><?php printf ("%.2f",$specials['customers_group_price']); ?></td>			  
              </tr>
<?php
  }
?>
      

<!-- body_text_eof //-->
</table></table>
<!-- body_eof //-->

<!-- footer //-->
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
