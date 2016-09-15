<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
  <div class="panel-heading text-center"><?php echo '<a class="label label-info" href="' . tep_href_link('specials.php') . '">' . MODULE_BOXES_SPECIALS_BOX_TITLE . '</a>'; ?></div>
<?php
   }
?>  
  <div class="panel-body text-center"><?php echo $specials_content; ?></div>
</div>