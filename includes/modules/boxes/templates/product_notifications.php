<div class="panel panel-primary">
<?php 
   if ( $title == True ) {
?>
     <div class="panel-heading"><a class="label label-info" href="<?php echo tep_href_link('account_notifications.php', '', 'SSL'); ?>"><?php echo MODULE_BOXES_PRODUCT_NOTIFICATIONS_BOX_TITLE; ?></a></div>
<?php
   }
?> 
  <div class="panel-body"><?php echo $notif_contents; ?></div>
</div>

