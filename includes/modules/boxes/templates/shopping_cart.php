<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
    <div class="panel-heading"><a class="label label-info" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>"><?php echo MODULE_BOXES_SHOPPING_CART_BOX_TITLE; ?></a></div>
<?php
   }
?> 	
  <div class="panel-body">
    <ul class="shoppingCartList">
      <?php echo $cart_contents_string; ?>
    </ul>
  </div>
</div>
