<div class="col-sm-<?php echo $content_width; ?>">
  <div class="container-fluid">
    <div class="panel panel-info">
<?php 
    if ( tep_not_null( $featured__content_heading ) ) {
?>
      <div class="panel-heading"><?php echo $featured__content_heading ; ?></div>
<?php
    }
?>	
     <div class="panel-body"><?php echo $text_featured_products  ; ?></div>
    </div>
   </div>
</div>
<br />