<div class="col-sm-<?php echo $content_width; ?>">
  <div class="container-fluid">
     <div class="panel panel-info">
<?php 
     if ( tep_not_null( $specials_content_heading ) ) {
?>
            <div class="panel-heading"><a class="label label-info" href="<?php echo tep_href_link(FILENAME_SPECIALS); ?>"><?php echo $specials_content_heading; ?></a></div>
<?php
     }
?>		 
	   <div class="panel-body"><?php echo $specials_content ; ?></div>
	 </div>
   </div>
</div>
<br />