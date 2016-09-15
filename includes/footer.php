<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>

<footer>
  <div class="footer">
<?php
//    require(DIR_WS_INCLUDES . 'counter.php');
?>
    <div class="container-fluid row-fluid">    
	 <div class="col-sm-12">
      <div class="col-sm-4">&nbsp;
<?php
         if ($oscTemplate->hasBlocks('footer_contents_left')) {
            echo $oscTemplate->getBlocks('footer_contents_left');
         }
?>
      </div>

      <div class="col-sm-4">&nbsp;
<?php
         if ($oscTemplate->hasBlocks('footer_contents_center')) {
           echo $oscTemplate->getBlocks('footer_contents_center');
         }
?>
      </div>

      <div class="col-sm-4">&nbsp;
<?php
         if ($oscTemplate->hasBlocks('footer_contents_right')) {
             echo $oscTemplate->getBlocks('footer_contents_right');
         }
?>
       </div>
	  </div>
    </div>
<?php	
    if ($oscTemplate->hasBlocks('footer_line')) {
?>
      <div class="container-fluid row-fluid">
	    <div class="col-sm-12">
          <?php echo $oscTemplate->getBlocks('footer_line'); ?>
		</div>
      </div>
<?php
    }
?>  
    <div class="container-fluid row-fluid">
<?php
       if ($oscTemplate->hasBlocks('boxes_column_foot')) {	
         echo  $oscTemplate->getBlocks('boxes_column_foot');  
       }
?>
    </div>
  </div>
<?php
    if ( SYS_USE_BANNERS == 'True' ) {

  if ($banner = tep_banner_exists('dynamic', '468x50')) {
?>
<div class="grid_24"> 
  <?php  echo tep_display_banner('static', $banner); ?>
</div>
<?php
  }
	}
/*** Begin Header Tags SEO ***/ //. tep_session_is_registered('popup')
if ($request_type == NONSSL) {
  if (HEADER_TAGS_DISPLAY_TAG_CLOUD == 'true') {
      echo '<tr><td align="center"><div style="text-align:center">';
      include(DIR_WS_INCLUDES . 'headertags_seo_tagcloud_footer.php');
      echo '</div></td></tr>';
  }
}
?>

  <div class="footer">
    <div class="container-fluid">
      <div class="row">
        <?php echo $oscTemplate->getContent('footer'); ?>
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="container-fluid">
      <div class="row">
	    <hr>
        <?php echo $oscTemplate->getContent('footer_suffix'); ?>
      </div>
    </div>
  </div>
</footer>