<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  SPPC Group List 2.0 by stClem
*/

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPPC_GROUP_LIST);
require(DIR_WS_INCLUDES . 'template_top.php');
?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	
<tr><td class="pageHeading"><br>	            
              
<?php
//Get customer group
$sppcglgroupid = $_GET['cID'];
$sppcglgroup = tep_db_query("SELECT * FROM customers_groups WHERE customers_group_id = $sppcglgroupid");
 while($row = tep_db_fetch_array($sppcglgroup))
  {
   echo $row['customers_group_name'];
  }
  ?>
  </td></tr><tr><td class="dataTableContent">
  <?php
  echo TEXT_SPPCGL_INFO . '<br><br>';
  
if(isset($_POST['send']))
{
$prodid = tep_db_query("SELECT products_id FROM products WHERE products_model = $_POST[prodmod]");
 while($row2 = tep_db_fetch_array($prodid))
  {
   $prodids=$row2['products_id'];
  }
 
if($_POST[prodprice]=='0')
{
tep_db_query($sql="DELETE FROM products_groups WHERE customers_group_id=$sppcglgroupid AND products_id=$prodids");
echo TEXT_SPPCGL_DELETED . '&nbsp;' . TEXT_SPPCGL_MODEL . '&nbsp;' . $_POST[prodmod];

} else {

tep_db_query($sql="INSERT INTO products_groups (customers_group_id, customers_group_price, products_id, products_qty_blocks, products_min_order_qty)
VALUES ('$sppcglgroupid','$_POST[prodprice]', '$prodids', '$_POST[prodqtyb]', '$_POST[prodqtym]') ON DUPLICATE KEY UPDATE customers_group_price=$_POST[prodprice], products_qty_blocks=$_POST[prodqtyb], products_min_order_qty=$_POST[prodqtym]");

echo TEXT_SPPCGL_UPDATED . '&nbsp;' . TEXT_SPPCGL_MODEL . '&nbsp;' . $_POST[prodmod] . '&nbsp;' . TEXT_SPPCGL_PRICE . '&nbsp;' . $_POST[prodprice] . '&nbsp;' . TEXT_SPPCGL_QTY . '&nbsp;' . $_POST[prodqtyb] . '&nbsp;' . TEXT_SPPCGL_MINORD . '&nbsp;' . $_POST[prodqtym];
}}

?> </td></tr><tr><td><form action="<? echo $PHP_SELF; ?>?cID=<?php echo $sppcglgroupid;?>" method="post">
<table border="1" width="600" cellspacing="0" cellpadding="2"><tr>
<td class="dataTableContent" width="70"><?php echo TEXT_SPPCGL_MODEL; ?></td>	
<td class="dataTableContent" width="100"><?php echo TEXT_SPPCGL_PRICE; ?></td>	
<td class="dataTableContent" width="20"><?php echo TEXT_SPPCGL_QTY; ?></td>	
<td class="dataTableContent" width="20"><?php echo TEXT_SPPCGL_MINORD; ?></td>	  
</tr><tr>
<td class="dataTableContent" width="70"><input type="text" name="prodmod" value="" /></td>	
<td class="dataTableContent" width="100"><input type="text" name="prodprice" value="" /></td>	
<td class="dataTableContent" width="20"><input type="text" name="prodqtyb" value="1" /></td>	
<td class="dataTableContent" width="20"><input type="text" name="prodqtym" value="1" /></td>	  
</tr></table>
<tr><td><input name="send" type="submit" value="<?php echo TEXT_SPPCGL_SEND; ?>"><br><br>

<?php
// Get language
$sppcgllangnr = tep_db_query("SELECT languages_id FROM languages WHERE directory = '$language'");
 while($row3 = tep_db_fetch_array($sppcgllangnr)) 
  { 
 $sppcgllang = $row3['languages_id'];  
  }

?> <table border="1" width="600" cellspacing="0" cellpadding="2">
<?php
  
 
  // Get list
  $specials_methods = "SELECT products.products_model, products_description.products_name, products_groups.customers_group_price FROM products_groups, products, products_description WHERE products_groups.products_id = products.products_id AND products.products_id = products_description.products_id AND products_groups.customers_group_id = $sppcglgroupid AND products_description.language_id = $sppcgllang";
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
      

</form>
</td></tr>
<!-- body_text_eof //-->
</table>
<!-- body_eof //-->
<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>