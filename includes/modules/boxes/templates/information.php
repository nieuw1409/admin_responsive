<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
     <div class="panel-heading"><?php echo MODULE_BOXES_INFORMATION_BOX_TITLE; ?></div>
<?php
   }
?>  
  <div class="panel-body">
    <ul class="list-unstyled">
      <li><a href="<?php echo tep_href_link(FILENAME_SHIPPING); ?>"><?php echo MODULE_BOXES_INFORMATION_BOX_SHIPPING; ?></a></li><br />
      <li><a href="<?php echo tep_href_link(FILENAME_PRIVACY); ?>"><?php echo MODULE_BOXES_INFORMATION_BOX_PRIVACY; ?></a></li><br />
      <li><a href="<?php echo tep_href_link(FILENAME_CONDITIONS); ?>"><?php echo MODULE_BOXES_INFORMATION_BOX_CONDITIONS; ?></a></li><br />
      <li><a href="<?php echo tep_href_link(FILENAME_CONTACT_US); ?>"><?php echo MODULE_BOXES_INFORMATION_BOX_CONTACT; ?></a></li>
<?php 
      if ( MODULE_BOXES_INFORMATION_DISPLAY_MAP == 'True' ) {
?>	  	  
	    <br /><li><?php echo $use_map ; ?> </li>
<?php
      }
	  if ( MODULE_BOXES_INFORMATION_DISPLAY_FAQ == 'True' ) {	  
?>	  
	    <br /><li><?php echo $use_faq ; ?> </li>
<?php
      }
?>	  
    </ul>
  </div>
</div>
