<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>

</div> <!-- bodyContent //-->

<?php
  if ($oscTemplate->hasBlocks('boxes_column_left')) {
?>

<div id="columnLeft" class="col-xs-0 col-sm-<?php echo $oscTemplate->getGridColumnWidth(); ?> col-sm-pull-<?php echo $oscTemplate->getGridContentWidth(); ?> col-md-<?php echo $oscTemplate->getGridColumnWidth(); ?> col-md-pull-<?php echo $oscTemplate->getGridContentWidth(); ?>">
  <?php
// BOF: WebMakers.com Added: Down for Maintenance
	if ((DOWN_FOR_MAINTENANCE == 'false') ||
	   ((DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF == 'false')) ) {
	echo $oscTemplate->getBlocks('boxes_column_left');
	}
// EOF: WebMakers.com Added: Down for Maintenance
?>
</div>

<?php
  }

  if ($oscTemplate->hasBlocks('boxes_column_right')) {
?>
<div id="columnRight" class="col-xs-0 col-sm-<?php echo $oscTemplate->getGridColumnWidth(); ?> col-md-<?php echo $oscTemplate->getGridColumnWidth(); ?>">
  <?php
// BOF: WebMakers.com Added: Down for Maintenance
	if ((DOWN_FOR_MAINTENANCE == 'false') ||
	   ((DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE_COLUMN_RIGHT_OFF == 'false')) ) {
	  echo $oscTemplate->getBlocks('boxes_column_right');
	}
// EOF: WebMakers.com Added: Down for Maintenance
?>
</div>

<?php
  }
?>
</div> <!-- row -->

</div> <!-- bodyWrapper //-->
<?php
// BOF: WebMakers.com Added: Down for Maintenance
	if ((DOWN_FOR_MAINTENANCE == 'false') ||
	   ((DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE_FOOTER_OFF == 'false')) ) {
	require(DIR_WS_INCLUDES . 'footer.php');
	}
// EOF: WebMakers.com Added: Down for Maintenance
?>

<?php 

echo '<!-- BOF: Javascript and CSS files -->' . PHP_EOL ;   
echo $oscTemplate->getBlocks('javascript_css_bottom') . PHP_EOL; 
echo '<!-- EOF: Javascript and CSS files  -->' . PHP_EOL; 

?>	
<!-- horizontal tree -->
<script>
$(document).ready(function(){
    $('ul.dropdown-menu > li.dropdown > a.dropdown-toggle .caret').removeClass('caret');
    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
      event.preventDefault();
      event.stopPropagation(); 
      $(this).parent().siblings().removeClass('open');
      $(this).parent().toggleClass('open');
    });
    $(window).resize(function() {

        ellipses1 = $("#bc1 :nth-child(2)")
        if ($("#bc1 a:hidden").length >0) {ellipses1.show()} else {ellipses1.hide()}

        ellipses2 = $("#bc2 :nth-child(2)")
        if ($("#bc2 a:hidden").length >0) {ellipses2.show()} else {ellipses2.hide()}

    })
	
});
</script>
<?php
echo '<!-- BOF: Footer Scripts Javascript  files -->' . PHP_EOL ; 
echo $oscTemplate->getBlocks('footer_scripts'). PHP_EOL; 
echo '<!-- EOF: Footer Scripts Javascript  files  -->' . PHP_EOL; 
?>
</body>
</html>