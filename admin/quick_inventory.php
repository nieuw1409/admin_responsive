<?php
/*
quick_inventory.php

MODIFIED from quick_inventory.php v1.0 by pyramids
  

Released under the GPL licence.
*/

// edit this number to the max search amount you want to show
$limit_search_to = 300;

include('includes/application_top.php');

$show_images = isset($HTTP_POST_VARS['show_images']) ? $HTTP_POST_VARS['show_images'] : 'yes';

if ($HTTP_POST_VARS['stock_update']) {
	$stock_update = $HTTP_POST_VARS['stock_update'];
	$update_msg = 'Updated: ';
	
	while (list($key, $items) = each($stock_update)) {
	
		$sql = "UPDATE products p SET 
		
		p.products_status = '".tep_db_prepare_input($items['status'])."', 
		p.products_quantity = '".tep_db_prepare_input($items['stock'])."', 
		p.products_price = '".tep_db_prepare_input($items['price'])."', 
		p.products_weight = '".tep_db_prepare_input($items['weight'])."'
		
		WHERE p.products_id = $key";
		
		$update = tep_db_query($sql);
		
		$update_msg .= $items['model'] . '; ';
	}
	$messageStack->add_session($update_msg, 'success');
	tep_redirect(tep_href_link(FILENAME_QUICK_INVENTORY));
}
 
require(DIR_WS_INCLUDES . 'template_top.php');
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo QUICK_HEAD1; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
		  <tbody>
		    <div class="col-xs-12 col-md-6">
			   <div class="panel panel-primary">
			      <div class="panel-heading"><?php echo SEARCHBYNAME ; ?> </div>
				  <div class="panel-body">
<?php 
	                  if (isset($HTTP_POST_VARS['search_price'])){$hold_price = $HTTP_POST_VARS['search_price'];}else{$hold_price = '1500';}
                      echo tep_draw_bs_form('search1', FILENAME_QUICK_INVENTORY, '', 'post', 'role="form"', 'id_search_name');				  
?>
                           <div class="form-group">
						        <?php echo  tep_draw_bs_input_field('search',       null,        HEADING_TITLE_SEARCH,       'id_input_search_name' ,        'col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEARCH ) . PHP_EOL ; ?>
                           </div>
						   <br />
                           <div class="form-group">
						        <?php echo  tep_bs_checkbox_field('show_images', 'yes', SHOWIMAGES,     'input_show_images_name',   (($show_images=='yes') ? true : false),  'checkbox checkbox-success' ) . PHP_EOL ; ?>								
                           </div>
						   <br />
                           <div class="form-group">
						        <?php echo  tep_draw_bs_input_field('search_price',       $hold_price,        SHOWPRICELESS,       'id_input_search_name' ,        'col-xs-3', 'col-xs-9', 'left', SHOWPRICELESS ) . PHP_EOL ; ?>
                           </div>
                           <br />						   

                           <div><?php echo tep_draw_bs_button(IMAGE_SEARCH, 'ok', null) ; ?></div>
							

                      </form>					   
				  </div>
			   </div>
   </table>
<table border="0" width="100%" cellspacing="0" cellpadding="2">

  <tr>
    <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" valign="top" style="border:1px solid #ccc;"><?php
	if (isset($HTTP_POST_VARS['search_price'])){$hold_price = $HTTP_POST_VARS['search_price'];}else{$hold_price = '1500';}
    echo tep_draw_form('search1', 'quick_inventory.php', '', 'post');
?>
            <table border="0" width="100%" cellpadding="2" cellspacing="0">
              <tr><td colspan="2"><b><?php echo SEARCHBYMODEL ; ?></b></td></tr>
              <tr>
                <td width="65%"><?php echo HEADING_TITLE_SEARCH;?></td>
                <td><?php echo tep_draw_input_field('search');?></td>
              </tr>
              <tr>
                <td><?php echo SHOWIMAGES ; ?></td>
                <td><?php echo tep_draw_checkbox_field('show_images', 'yes', (($show_images=='yes') ? true : false));?></td>
              </tr>
              <tr>
                <td><?php echo SHOWPRICELESS ; ?> </td>
                <td><input type="text" name="search_price" value="<?php echo $hold_price;?>" size="6"></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><?php echo tep_draw_button(IMAGE_SEARCH, 'search', null, 'primary');?></td>
              </tr>
            </table>
            <?php echo '</form>'; ?></td>
          <td valign="middle" align="center"><b><?php echo QUICK_OR ; ?></b></td>
          <td valign="top" width="45%" style="border:1px solid #ccc;"><?php 
	echo tep_draw_form('search2', 'quick_inventory.php', '', 'post');
?>
            <table border="0" cellpadding="2" cellspacing="2" width="100%">
              <tr><td colspan="2"><b><?php echo SEARCHBYMODEL ; ?></b></td></tr>
              <tr>
                <td width="50%"><?php echo SELECTMODEL ; ?></td>
                <td><?php
	  $prod_model[] = array('id' => '',
                            'text' => QUICK_MODEL);
      $all_products_query = tep_db_query("select p.products_model from " . TABLE_PRODUCTS . " p order by p.products_model");
	  
	  while ($products = tep_db_fetch_array($all_products_query)) {
      	$prod_model[] = array('id' => $products['products_model'],
                              'text' => $products['products_model']);
	  }
	  echo tep_draw_pull_down_menu('search', $prod_model, $search);
?></td>
              </tr>
              <tr>
                <td><?php echo QUICK_SHOW;?></td>
                <td><?php echo tep_draw_checkbox_field('show_images', 'yes', (($show_images=='yes') ? true : false));?></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" align="center"><?php echo tep_draw_button(IMAGE_SEARCH, 'search', null, 'primary');?></td>
              </tr>
            </table>
            <?php echo '</form>'; ?></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        <!-- Search By Category -->
          <td width="50%" valign="top" style="border:1px solid #ccc;"><?php
    echo tep_draw_form('search3', 'quick_inventory.php', '', 'post');
?>
            <table border="0" width="100%" cellpadding="2" cellspacing="2">
              <tr><td colspan="2"><b><?php echo SEARCHCATEGORY ; ?></b></td></tr>
              <tr>
                <td width="65%"><?php echo SELECTCATEGORY ; ?></td>
                <td><?php echo tep_draw_pull_down_menu('category_id', tep_get_category_tree(), $current_category_id);?></td>
              </tr>
              <tr>
                <td><?pgp echo SHOWIMAGES ; ?></td>
                <td><?php echo tep_draw_checkbox_field('show_images', 'yes', (($show_images=='yes') ? true : false));?></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><?php echo tep_draw_button(IMAGE_SEARCH, 'search', null, 'primary');?></td>
              </tr>
            </table>
            <?php echo '</form>'; ?></td>
          <td valign="middle" align="center"><b><?php echo QUICK_OR ; ?></b></td>
          <!-- Search By Brand -->
          <td valign="top" width="45%" style="border:1px solid #ccc;"><?php 
	echo tep_draw_form('search4', 'quick_inventory.php', '', 'post');
?>
            <table border="0" cellpadding="2" cellspacing="2" width="100%">
              <tr><td colspan="2"><b><?PHP ECHO SEARCHBRAND ; ?></b></td></tr>
              <tr>
                <td width="50%"><?php echo SELECTBRAND ; ?></td>
                <td><?php
	  $prod_brand[] = array('id' => '',
                            'text' => QUICK_BRAND);
      $all_products_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m order by m.manufacturers_id");
	  
	  while ($products = tep_db_fetch_array($all_products_query)) {
      	$prod_brand[] = array('id' => $products['manufacturers_id'],
                              'text' => $products['manufacturers_name']);
	  }
	  echo tep_draw_pull_down_menu('brand_id', $prod_brand, $search);
?></td>
              </tr>
              <tr>
                <td><?php echo QUICK_SHOW;?></td>
                <td><?php echo tep_draw_checkbox_field('show_images', 'yes', (($show_images=='yes') ? true : false));?></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><?php echo tep_draw_button(IMAGE_SEARCH, 'search', null, 'primary');?></td>
              </tr>
            </table>
            <?php echo '</form>'; ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php
if (isset($HTTP_POST_VARS['search'])) {
	
	$search = tep_db_prepare_input($HTTP_POST_VARS['search']);
	$sql = tep_db_query("SELECT p.products_model, p.products_image, p.products_weight, p.products_id, p.products_quantity, p.products_status, p.products_price, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_price < $hold_price and p.products_id = pd.products_id and language_id = $languages_id and p.products_model like '%" . tep_db_input($search) . "%' order by p.products_model limit $limit_search_to");
	
}elseif(isset($HTTP_POST_VARS['category_id'])){
	$category_id = tep_db_prepare_input($HTTP_POST_VARS['category_id']);
	$sql = tep_db_query("SELECT p.products_model, p.products_image, p.products_weight, p.products_id, p.products_quantity, p.products_status, p.products_price, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION ." pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where p2c.products_id = p.products_id and p2c.categories_id= $category_id and p.products_id = pd.products_id and language_id = $languages_id order by p.products_model limit $limit_search_to");	
	
}elseif(isset($HTTP_POST_VARS['brand_id'])){
	
	$brand_id = tep_db_prepare_input($HTTP_POST_VARS['brand_id']);
	$sql = tep_db_query("SELECT p.products_model, p.products_image, p.products_weight, p.products_id, p.products_quantity, p.products_status, p.products_price, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION ." pd where p.manufacturers_id = '$brand_id' and p.products_id = pd.products_id and language_id = $languages_id order by p.products_model limit $limit_search_to");
	
}

if(isset($HTTP_POST_VARS['search']) || isset($HTTP_POST_VARS['category_id']) || isset($HTTP_POST_VARS['brand_id'])){
	
echo '<form method="post" action="quick_inventory.php">';
echo '<table border="0" width="100%" cellspacing=2 cellpadding=2><tr><td colspan="3">';
echo '&nbsp;</td><td align="right" colspan="3">';
echo '<font size="2" color="ff0000"><b>* ' . QUICK_INACTIVE . '</b></font>&nbsp;&nbsp;<font size="2" color="009933"><b>* ' . QUICK_ACTIVE . '</b></font>';
echo '</td></tr>';
echo '<tr class="dataTableHeadingRow">';
echo '<td class="dataTableContent" align="left"><b>' . QUICK_MODEL . '</b></td>';
echo '<td class="dataTableContent" align="left"><b>' . QUICK_NAME . '</b></td>';
echo '<td class="dataTableContent" align="left"><b>' . QUICK_PRICE . '</b></td>';
echo '<td class="dataTableContent" align="left"><b>' . QUICK_QTY . '</b></td>';
echo '<td class="dataTableContent" align="left"><b>' . QUICK_WEIGHT . '</b></td>';
echo '<td class="dataTableContent" align="left"><b>' . QUICK_STATUS . '</b></td>';
echo '</tr>';

$count_query = 0;
while ($results = tep_db_fetch_array($sql)) {
$count_query++;
             echo '<tr class="dataTableRow"><td class="dataTableContent" align="left"><input type="hidden" size="9" name="stock_update[' . $results['products_id'] . '][model]" value="' . $results['products_model'] . '">'.$results['products_model'].'</td>';

			 echo '<td class="dataTableContent" align="left">' . $results['products_name'];

if( ($HTTP_GET_VARS['show_images'] == 'yes')||($HTTP_POST_VARS['show_images'] == 'yes') ){
	if ( is_file('../'. DIR_WS_IMAGES .$results['products_image']) ){
echo '<br><img src="../'.DIR_WS_IMAGES.$results['products_image'].'" alt="" border="0" height="100">';
	}

}

			 echo '</td>';
			 
     		 echo '<td class="dataTableContent" align="left"><input type="text" size="8" name="stock_update[' . $results['products_id'] . '][price]" value="' . round($results['products_price'],2) . '"></td>';

			 echo '<td class="dataTableContent" align="left"><input type="text" size="4" name="stock_update[' . $results['products_id'] . '][stock]" value="' . $results['products_quantity'] . '">';

			 echo '<td class="dataTableContent" align="left"><input type="text" size="6" name="stock_update[' . $results['products_id'] . '][weight]" value="' . $results['products_weight'] . '">';

             echo '</td>';
			 echo '<td class="dataTableContent" align="left"><input type="text" size="2" name="stock_update[' . $results['products_id'] . '][status]" value="' . $results['products_status'] . '">';

             echo (($results['products_status'] == 0) ? '<font color="ff0000"><b>*</b></font>' : '<font color="009933"><b>*</b></font>');

echo '</td></tr>';

    }

if ($count_query == 0) {
	echo '<tr><td colspan="6"><font color="#FF0000">*** Nothing Found searching for the your critiria ***</font></td></tr>';
}

if($count_query == $limit_search_to) {
	echo '<tr><td bgcolor="lime"  colspan="6"><font color="black">Try a better match, this resulted in '.  $limit_search_to . ' listings (not all items may have been listed)</font></td></tr>';
}

if($count_query) {
	echo '<tr><td colspan="6"><font color="red">We listed '. $count_query .' items that match your search</font></td></tr>';
}

  echo '<tr><td align="center" colspan="6">';
  echo tep_draw_button(IMAGE_UPDATE, 'disk', null, 'primary') . '</td></tr></table></form>';
}
?></td>
  </tr>
</table>
<?php 
	require(DIR_WS_INCLUDES . 'template_bottom.php');
	require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>