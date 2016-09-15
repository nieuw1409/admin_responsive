<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
    <div class="panel-heading"><?php echo MODULE_BOXES_SEARCH_BOX_TITLE; ?></div>
<?php
   }
?> 	
  <div class="panel-body text-center"><?php echo $form_output; ?></div>
  <div class="panel-footer"><?php echo MODULE_BOXES_SEARCH_BOX_TEXT . '<br /><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"><strong>' . MODULE_BOXES_SEARCH_BOX_ADVANCED_SEARCH . '</strong></a>'; ?></div>
</div>
