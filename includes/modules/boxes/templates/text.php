<div class="panel panel-default panel-primary">
<?php 
   if ( $title_text == True ) {
?>	   
     <div class="panel-heading"><?php echo $text_heading ; ?></div>
<?php
   }
?> 
   <div class="panel-body ' . <?php echo $text_body_align ; ?> . '"><?php echo $text_contents ?></div>
</div>   