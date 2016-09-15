<div class="panel panel-primary">
<?php 
    if (MODULE_BOXES_SKYPE_HEADING =='True') {
?>
     <div class="panel-heading"><?php echo MODULE_BOXES_SKYPE_BOX_TITLE ; ?></div>
     <div class="panel-body text-center"><?php echo ' '. $skype_data_call .' '. $skype_data_chat ; ?></div>
<?php
   } else {
         if (MODULE_BOXES_SKYPE_CONTENTS =='True') {	   
?> 
           <div class="panel-body text-center"><?pgp echo ' '. $skype_data_call .' '. $skype_data_chat ; ?></div>
<?php
         } else {            
?> 		   
           <div class="panel-heading"><?php echo MODULE_BOXES_SKYPE_BOX_TITLE ; ?></div>
           <div class="panel-body text-center"><?php echo ' '. $skype_data_call .' '. $skype_data_chat ; ?></div>
<?php
         }  
   }		 
?>		   
</div>