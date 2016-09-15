<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
     <div class="panel-heading"><?php echo MODULE_BOXES_PRODUCT_SOCIAL_BOOKMARKS_BOX_TITLE; ?></div>
<?php
   }
?> 
  <div class="panel-body"><?php echo implode(' ', $social_bookmarks) ; ?></div>
</div>