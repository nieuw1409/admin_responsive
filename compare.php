<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_COMPARE);
  require(DIR_WS_INCLUDES . 'template_top.php');
  
?>
<h1 class="ui-widget-header"> <?php echo TEXT_COMPARE_TITLE ; ?></h1>

<div class="contentContainer">
  <div class="contentText">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="fieldKey">
        <?php 
		  
		  $num = count($_POST['checkgroup']);
		  $data = $_POST['checkgroup'];
		 $i = 0;
		 echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="compareTable">
  <tr class="ui-widget-header">
    <td align="center">' . TEXT_COMPARE_NAME     . '</td>
    <td align="center">' . TEXT_COMPARE_IMAGE    . '</td>
    <td align="center">' . TEXT_COMPARE_PRICE    . '</td>
	<td align="center">' . TEXT_COMPARE_SPECIAL  . '</td>
	<td align="center">' . TEXT_COMPARE_QUANTITY . '</td>
	<td align="center">' . TEXT_COMPARE_MODEL    . '</td>
	<td align="center">' . TEXT_COMPARE_BUYNOW   . '</td>
	</tr>';
		while($i < $num)
		{
			
			$sql_res=tep_db_query("select *,p.products_id pid from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id LEFT JOIN ".TABLE_SPECIALS." ps on ps.products_id = pd.products_id  WHERE p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = '" . $_POST['checkgroup'][$i]. "'  LIMIT 0,10");
			
			
			while($listing=mysql_fetch_array($sql_res))
			{
			
	if($i % 2 )
	{
		$class = "ui-state-default" ; //"ComContent";
	}
	else
	{
		$class ="ui-state-highlight"; //ComContent2";
	}
	if (tep_not_null($listing['specials_new_products_price'])) {
              $lc_text = '<span class="productSpecialPrice" style="color:#f00">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
            } else {
              $lc_text = '&nbsp; NA';
			  
            }
			
 echo '
 
  <tr class="'.$class.'">
  
    <td align="center">'.$listing['products_name'].'</td>
    <td  align="center">'.tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT).'</td>
    <td  align="center">'. $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])).'</td>
	
	<td  align="center">' . $lc_text.'</td>
	<td  align="center">'.$listing['products_quantity'].'</td>
	<td  align="center">'.$listing['products_model'].'</td>
	 
	<td  class= "ui-widget-content" align="center">'. tep_draw_button(IMAGE_BUTTON_BUY_NOW, 'triangle-1-e', tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['pid'])) . '</td>
	
  </tr>';
  
// <a  id="pickMeBtns" title="pickMe" href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['pid']).'"><span>pickMe</span></a></td>
				
				
			}
							
			$i++;
			 
		}
		echo '</table>';
	
		  
		  
		?>
        </td>
        
      </tr>
      
    </table>
  </div>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link(FILENAME_DEFAULT)); ?></span>
  </div>
</div>





<?php


  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>