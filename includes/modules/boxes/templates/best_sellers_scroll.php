<div  align="center" class="panel panel-default panel-primary">
<?php 
    if ( $title == True ) {
?>		
      <div class="panel-heading text-center"><?php echo MODULE_BOXES_BEST_SELLERS_SCROLL_BOX_TITLE ?></div>
<?php 
    }
?>	
      <div class="panel-body">
         <div id="carousel_bestsellers" class="carousel slide" data-ride="carousel">
           <div class="carousel-inner">
                <?php echo $carrousel_item ; ?>
           </div>
         </div>
	  </div>
</div>