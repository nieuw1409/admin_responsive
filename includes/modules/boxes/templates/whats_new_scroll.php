<div align="center" class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
  <div class="panel-heading"><?php echo '<a class="label label-info" href="' . tep_href_link(FILENAME_PRODUCTS_NEW) . '">' . MODULE_BOXES_WHATS_NEW_SCROLL_BOX_TITLE . '</a>'; ?></div>
<?php
   }
?>  
      <div class="panel-body">
         <div id="carousel-whatsnew" class="carousel slide" data-ride="carousel">
           <div class="carousel-inner">
                <?php echo $carrousel_whatsnew ; ?>
           </div>
         </div>
	  </div>
</div>