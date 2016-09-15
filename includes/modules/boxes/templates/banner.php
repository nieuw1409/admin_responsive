<?php
			if (MODULE_BOXES_BANNER_HEADING =='True') {
?>
               <div class="panel panel-default panel-primary">
                   <div class="panel-heading"><?php echo MODULE_BOXES_BANNER_BOX_TITLE ; ?></div>
                   <div class="panel-body"><?php echo tep_display_banner('static', $banner) ; ?></div>
               </div>
<?php			   
			} else {			
			  if (MODULE_BOXES_BANNER_CONTENTS =='True') {
?>				  
                 <div class="panel panel-default panel-primary">
                   <div class="panel-body"><?php echo tep_display_banner('static', $banner) ; ?></div>
                 </div>
<?php			   
			  } else {
?>				  
                 <div class="panel panel-default">
                   <div class="panel-body"><?php echo tep_display_banner('static', $banner) ; ?></div>
               </div>
<?php			   
			  }
			}
?>			