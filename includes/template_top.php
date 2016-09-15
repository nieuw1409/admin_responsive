<?php
/* overtollig verwijderd
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com 
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  $oscTemplate->buildBlocks();

  if (!$oscTemplate->hasBlocks('boxes_column_left')) {
    $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
  }

  if (!$oscTemplate->hasBlocks('boxes_column_right')) {
    $oscTemplate->setGridContentWidth($oscTemplate->getGridContentWidth() + $oscTemplate->getGridColumnWidth());
  }  
?>
<!DOCTYPE html>  
<html <?php echo HTML_PARAMS; ?>>
<!-- microdata <head> -->
<head itemscope itemtype="http://schema.org/WebSite">
 <meta charset="utf-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<?php
/*** Begin Header Tags SEO ***/
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <title  itemprop="name"><?php echo tep_output_string_protected($oscTemplate->getTitle()); ?></title>
<?php
}
/*** End Header Tags SEO ***/
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
 
<?php echo '<!-- BOF: Javascript and CSS files -->' . PHP_EOL ; ?>
<?php echo $oscTemplate->getBlocks('javascript_css_top') . PHP_EOL; ?>
<?php echo '<!-- EOF: Javascript and CSS files  -->' . PHP_EOL; ?> 

<!-- bof image in checkout confirmation -->
<?php echo '<!-- BOF: Header Tags Standaard -->' . PHP_EOL; ?>
<?php echo $oscTemplate->getBlocks('header_tags'); ?>
<?php echo '<!-- EOF: Header Tags Standaard -->' . PHP_EOL; ?>


</head>
<body>

<?php echo $oscTemplate->getContent('navigation'); ?>
<div id="bodyWrapper" class="container-fluid">
<div class="row safari-fix">
<?php
// BOF: WebMakers.com Added: Down for Maintenance
  if ((DOWN_FOR_MAINTENANCE == 'false') ||
	  ((DOWN_FOR_MAINTENANCE == 'true') && (DOWN_FOR_MAINTENANCE_HEADER_OFF == 'false')) ) {
            if ( $header !== false ) {  require(DIR_WS_INCLUDES . 'header.php'); }
  }
// EOF: WebMakers.com Added: Down for Maintenance
?>
<div id="bodyContent" class="col-sm-<?php echo $oscTemplate->getGridContentWidth(); ?> col-md-<?php echo $oscTemplate->getGridContentWidth(); ?> <?php echo ($oscTemplate->hasBlocks('boxes_column_left') ? 'col-md-push-' . $oscTemplate->getGridColumnWidth() : ''); ?> <?php echo ($oscTemplate->hasBlocks('boxes_column_left') ? 'col-sm-push-2' : ''); ?>">