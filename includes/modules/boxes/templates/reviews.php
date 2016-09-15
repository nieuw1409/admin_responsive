<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
     <div class="panel-heading"><a class="label label-info" href="<?php echo tep_href_link( FILENAME_REVIEWS ) ; ?>"><?php echo MODULE_BOXES_REVIEWS_BOX_TITLE ; ?></a></div>
<?php
   }
?> 
  <div class="panel-body"><?php echo $reviews_box_contents; ?></div>
</div>