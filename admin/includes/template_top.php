<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> <!-- maybe :application/json -->
<meta name="robots" content="noindex,nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo 'active Store : ' . $multi_stores_id . ' ' . TITLE; ?></title>
<base href="<?php echo ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN; ?>">


<link href="<?php echo tep_catalog_href_link('ext/bootstrap/css/' . $multi_stores_admin_color . '/bootstrap.min.css', '', 'SSL'); ?>" rel="stylesheet"> 
 
    <style type="text/css">
       .modal-dialog {
         width: 90%;
         height: 90%;
         padding: 0;
         margin:2;
       }
    .modal-content {
      height: 100%;
      border-radius: 0;  
      overflow:auto;

    }	   
    </style>	
<script>
                      /*!
                      loadCSS: load a CSS file asynchronously.
                      [c]2014 @scottjehl, Filament Group, Inc.
                      Licensed MIT
                      */
                      function loadCSS( href, before, media ){
                        "use strict";
                       
					    // Arguments explained:
                        // `href` is the URL for your CSS file.
                        // `before` optionally defines the element we will use as a reference for injecting our <link>
                        // By default, `before` uses the first <script> element in the page.
                        // However, since the order in which stylesheets are referenced matters, you might need a more specific location in your document.
                        // If so, pass a different reference element to the `before` argument and it will insert before that instead
                        // note: `insertBefore` is used instead of `appendChild`, for safety re: http://www.paulirish.com/2011/surefire-dom-element-insertion/
                        var ss = window.document.createElement( "link" );
                        var ref = before || window.document.getElementsByTagName( "script" )[ 0 ];
                        var sheets = window.document.styleSheets;
                        ss.rel = "stylesheet";
                        ss.href = href;
                        // temporarily, set media to something non-matching to ensure it will fetch without blocking render
                        ss.media = "only x";
                        // inject link
                        ref.parentNode.insertBefore( ss, ref );
                        // This function sets the link s media back to `all` so that the stylesheet applies once it loads
                        // It is designed to poll until document.styleSheets includes the new sheet.
                        function toggleMedia(){
                            var defined;
                            for( var i = 0; i < sheets.length; i++ ){
                               if( sheets[ i ].href && sheets[ i ].href.indexOf( href ) > -1 ){
                                  defined = true;
                               }
                            }
                            if( defined ){
                               ss.media = media || "all";
                            } else {
                              setTimeout( toggleMedia );
                            }
                        }
                        toggleMedia();
                        return ss;
                      }
 		  
 
					  loadCSS("<?php echo tep_catalog_href_link('ext/bootstrap-select/css/bootstrap-select-admin.min.css', '', 'SSL'); ?>") ;
					  loadCSS("<?php echo tep_catalog_href_link('ext/bootstrap/css/bootstrap-datepicker3.min.css', '', 'SSL'); ?>") ;
					  loadCSS("<?php echo tep_catalog_href_link('ext/bootstrap-checkbox/bootstrap-checkbox-min.css', '', 'SSL'); ?>") ;
					  loadCSS("ext/font-awesome/css/font-awesome.min.css") ; 
					  loadCSS("ext/css/custom.css") ;
</script>

<noscript> 
          <link href="<?php echo          tep_catalog_href_link('ext/bootstrap-select/css/bootstrap-select-admin.min.css', '', 'SSL'); ?>" rel="stylesheet">
          <link href="<?php echo          tep_catalog_href_link('ext/bootstrap/css/bootstrap-datepicker3.min.css', '', 'SSL'); ?>" rel="stylesheet">
          <link href="<?php echo          tep_catalog_href_link('ext/bootstrap-checkbox/bootstrap-checkbox-min.css', '', 'SSL'); ?>" rel="stylesheet">
          <link href="ext/font-awesome/css/font-awesome.min.css" rel="stylesheet"> 	 
          <link href="ext/css/custom.css" rel="stylesheet">				  
</noscript>			

<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/jquery-2.2.4.min.js', '', 'SSL'); ?>"></script> 

<!--<link href="<?php echo tep_catalog_href_link('ext/font-awesome/css/font-awesome.min.css', '', 'SSL'); ?>" rel="stylesheet"> 	  -->
					  
<link rel="stylesheet" type="text/css" href="ext/stylesheet.css"> 
<!--[if lt IE 9]>
  <script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/js/html5shiv.js', '', 'SSL'); ?>/ext/html5shiv.js"></script>
  <script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/js/respond.min.js', '', 'SSL'); ?>"></script>
  <script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/js/excanvas.min.js', '', 'SSL'); ?>"></script>
<![endif]-->



<!--[if IE]><script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/excanvas.min.js'); ?>"></script><![endif]-->

<?php
/*** Begin Header Tags SEO ***/
switch (HEADER_TAGS_ENABLE_HTML_EDITOR) {
   case 'CKEditor':
     echo '<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>';
   break;
   default: break;
}
/*** End Header Tags SEO ***/
?>
<!-- AJAX Attribute Manager -->
<?php 
//  require( 'attributeManager/includes/attributeManagerHeader.inc.php' ) ;

  if (isset($templateModules['header']) && !empty($templateModules['header'])) {
    foreach ($templateModules['header'] as $key => $value) {
      echo $value . "\n";
    }
  }
?>
</head>
<!-- AJAX Attribute Manager -->

<body onLoad="initialize()">
<!--  onload="goOnLoad();"> -->
<!-- AJAX Attribute Manager end -->
 
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>

<div class="container-fluid">
  <div class="row-fluid">

    <section id="bodyContent" class="col-xs-12">

<!--        <div id="bodyContent" class="col-xs-12 <?php echo (tep_session_is_registered('admin') ? 'col-sm-12 col-md-12 equal' : 'col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3'); ?> content-canvas"> -->
        		
<?php
  if ($messageStack->size > 0) {
	echo '<div id="dialog" title="Basic dialog">';
    echo $messageStack->output();
	echo '</div>';
  }
?>