<?php
/* overtollig verwijderd
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
// Include the Cache_Lite Class
// php lite require_once( 'D:/usbwebserver8 clean/root/includes/functions/Lite.php' );   // change "/web/htdocs/www.YOURSITE.com/home/" with the full path of your oscommerce installation
// Create a unique ID for the page, in this case, the full URL. You
// should stick to a single convention here!
// php lite $id = md5('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
// Set options and create a new instance of the class - self explantory!
// php lite $options = array(
// php lite  'cacheDir' => "D:/usbwebserver8 clean/root/temp/", // "/home/verfwebs/domains/verfwebshop.nl/public_html/temp/",    //this is the folder were the file will be cached, change "/web/htdocs/www.YOURSITE.com/home/" with the full path of your oscommerce installation
// php lite 'lifeTime' => 10800, // time in seconds of the cache file 
// php lite 'automaticSerialization' => true,
// php lite );
// php lite $cache_lite = new Cache_Lite($options);
// php lite if ($data = $cache_lite->get($id)) { 
// php lite } else { 
         // Use PHP output buffering to save the contents of the webpage to a variable,
         //for use in the cache file
// php lite          ob_start();
 
  require('includes/application_top.php');
  
  // 2.3.3
  if (!isset($HTTP_GET_VARS['products_id'])) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
  }
  // eof 2.3.3 

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p 
                                                           left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id left join " . 
														                 TABLE_CATEGORIES . " c on p2c.categories_id = c.categories_id, " . 
																		 TABLE_PRODUCTS_DESCRIPTION . "  pd 
																	where c.categories_status = '1'                                    and p.products_status = '1' 
																	  and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id 
																	  and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 
																	  and pd.language_id = '" . (int)$languages_id . "'");

  $product_check = tep_db_fetch_array($product_check_query);

  $customer_group_id =  tep_get_cust_group_id() ;
  require(DIR_WS_INCLUDES . 'template_top.php');
  
// bof option type 231
?>
<script language="javascript"><!--

// BOF Option Types v2.3.1 - Added for Form Field Progress Bar 
function textCounter(field,counter,maxlimit,linecounter) {
  // text width//
  var fieldWidth =  parseInt(field.offsetWidth);
  var charcnt = field.value.length;
  var percentage = parseInt(100 - (( maxlimit - charcnt) * 100)/maxlimit) ;
  if (linecounter) {
    document.getElementById(counter).style.width =  parseInt((fieldWidth*percentage)/100)+"px";
    document.getElementById(linecounter).style.width =  parseInt(fieldWidth)+"px";
    document.getElementById(counter).innerHTML="<nobr>Max: "+percentage+"% - ("+charcnt+"/"+maxlimit+")</nobr>";
  } else {
    // trim the extra text
    if (charcnt > maxlimit) {
      field.value = field.value.substring(0, maxlimit);
    } else {
      // progress bar percentage
      document.getElementById(counter).style.width =  parseInt((fieldWidth*percentage)/100)+"px";
      document.getElementById(counter).innerHTML="<nobr>Max: "+percentage+"% - ("+charcnt+"/"+maxlimit+")</nobr>";
      // color correction on style from CCFFF -> CC0000
      document.getElementById(counter).style["background-color"] = "rgb(80%,"+(100-percentage)+"%,"+(100-percentage)+"%)";
    }  
  }
}
// EOF Option Types v2.3.1 - Added for Form Field Progress Bar 
//--></script>
<?php

  echo "<a name=\"\$header_tags_array['title']\"></a>";
  /*** End Header Tags SEO ***/ 
 
 if ($product_check['total'] < 1) {
?>
<div class="contentContainer">
  <div class="contentText">
    <div class="alert alert-warning"><?php echo TEXT_PRODUCT_NOT_FOUND; ?></div>
  </div>

  <div class="pull-right">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?>
  </div>
</div>

<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_instock_id, p.products_nostock_id, p.products_purchase, p.products_qty_blocks, pd.products_head_sub_text from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

// bof mult stores
    $product_viewed_query = tep_db_query("select products_viewed from " . TABLE_PRODUCTS_VIEWED . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "' and stores_id='" . SYS_STORES_ID . "'");

	if ( tep_db_num_rows($product_viewed_query) ) {
       tep_db_query("update " . TABLE_PRODUCTS_VIEWED . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "' and stores_id='" . SYS_STORES_ID . "'");
    } else {
       tep_db_query("insert into " . TABLE_PRODUCTS_VIEWED . " (products_id, language_id, products_viewed, stores_id) 
	        values ('" . (int)$product_info['products_id'] . "', '" . (int)$languages_id . "', 1, '" . SYS_STORES_ID . "')");
    }	
// eof multi stores 	

    $pf->loadProduct((int)$_GET['products_id'], (int)$languages_id);

    echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'), 'post', 'class="form-horizontal" role="form" enctype="multipart/form-data"'); ?>

<!-- // bof micro data schema.org -->
<div itemscope itemtype="http://schema.org/Product">
<?php
  if ($messageStack->size('product_action') > 0) {
    echo $messageStack->output('product_action');
  }
?>

<div class="contentContainer">
  <div class="contentText">
<?php
//2.3.3
    $reviews_query = tep_db_query("select count(*) as count, avg(reviews_rating) as avgrating from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and reviews_status = 1");
// eof 2.3.3
    $reviews = tep_db_fetch_array($reviews_query);

    if ($reviews['count'] > 0) {
      echo '<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><meta itemprop="ratingValue" content="' . $reviews['avgrating'] . '" /><meta itemprop="ratingCount" content="' . $reviews['count'] . '" /></span>';	  
    }	
// eof micro data	
?>
 <div class="row">
    <?php echo $oscTemplate->getContent('product_info'); ?>
  </div>				
 
      <!--- BEGIN Header Tags SEO Social Bookmarks -->
      <?php if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') {
         echo '<div style="margin-top:5px;">';
	        include(DIR_WS_MODULES . FILENAME_HT_SOCIAL_BOOKMARKS); 
	        echo '</div>'; 
      }
      ?>
      <!--- END Header Tags SEO Social Bookmarks -->      
<?php
// bof micro data
      if ($product_info['manufacturers_id'] > 0) {
        $manufacturer_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$product_info['manufacturers_id'] . "'");
        if (tep_db_num_rows($manufacturer_query)) {
          $manufacturer = tep_db_fetch_array($manufacturer_query);
          echo '<span itemprop="manufacturer" itemscope itemtype="http://schema.org/Organization"><meta itemprop="name" content="' . tep_output_string($manufacturer['manufacturers_name']) . '" /></span>';		  
        }		  
      } 
// eof micro data  
?>  
<!--  bof 231 option types -->
<div><?php echo tep_draw_hidden_field('number_of_uploads', $number_of_uploads); ?></div>
<!-- eof 231 option types -->
</div> <!-- end contenttext -->
</div> <!-- end content container -->
</div> <!-- end google microdata -->
</form>
<?php
  } // end else if product not found
  
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
  
// php lite   $data = ob_get_contents(); 
  // Save the page to a cache file, using the ID created earlier
// php lite   $cache_lite->save($data, $id);
// php lite   ob_get_clean();
// php lite  } // end of else from chach lite
// show page or from cache or from normal php
// php lite   echo $data; 
?>