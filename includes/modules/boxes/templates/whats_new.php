<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
  <div class="panel-heading text-center"><?php echo '<a class="label label-info" href="' . tep_href_link(FILENAME_PRODUCTS_NEW) . '">' . MODULE_BOXES_WHATS_NEW_BOX_TITLE . '</a>'; ?></div>
<?php
   }
?>  
  <div class="panel-body text-center"><?php echo $whats_new_content; ?></div>
</div>