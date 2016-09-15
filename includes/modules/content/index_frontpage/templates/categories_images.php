<!-- categories images -->
<div class="col-sm-<?php echo $content_width; ?>">
  <div class="panel panel-info">
<?php 
    if ( tep_not_null( $categories_content_heading ) ) {
?>
      <div class="panel-heading"><?php echo $categories_content_heading ; ?></div>
<?php
    }
?>	
    <div class="panel-body"><?php echo $categories_content  ; ?></div>
   </div>
</div>
<br />