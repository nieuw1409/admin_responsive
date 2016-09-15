<div align="center" class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
  <div class="panel-heading"><?php echo '<a class="label label-info" href="' . tep_href_link(FILENAME_SPECIALS) . '">' . MODULE_BOXES_SPECIALS_SCROLL_BOX_TITLE . '</a>'; ?></div>
<?php
   }
?>  
      <div class="panel-body">
         <div id="carousel-specials" class="carousel slide" data-ride="carousel">
           <div class="carousel-inner">
                <?php echo $carrousel_specials ; ?>
           </div>
         </div>
	  </div>
</div>