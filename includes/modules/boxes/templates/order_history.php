<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
     <div class="panel-heading"><?php echo MODULE_BOXES_ORDER_HISTORY_BOX_TITLE; ?></div>
<?php
   }
?> 
  <div class="panel-body"><?php echo $customer_orders_string; ?></div>
</div>