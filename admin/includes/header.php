<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
  //BOF Down for Maintenance Mod
if (DOWN_FOR_MAINTENANCE == 'true') {
    $messageStack->add(TEXT_ADMIN_DOWN_FOR_MAINTENANCE, 'warning');
  }
 //EOF Down for Maintenance Mod

  if ($messageStack->size > 0) {
//START THEME ADMIN SWITCHER
//	echo '<div id="dialog" title="Basic dialog">';
//    echo $messageStack->output();
//	echo '</div>';
//START THEME ADMIN SWITCHER	
  }
/*
  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
*/  
  $avail_stores = tep_get_multi_stores();
  $multi_stores_array = array();
  $multi_stores_selected = $multi_stores_id  ;
  for ($i = 0, $n = sizeof($avail_stores); $i < $n; $i++) {
    $multi_stores_array[] = array('id' => $avail_stores[$i]['id'],
                                  'text' => $avail_stores[$i]['name']);
    if ($avail_stores[$i]['id'] == $multi_stores_id) {
      $multi_stores_selected = $avail_stores[$i]['id'];
    }
  } 
  
?> 
<!-- Modal stores-->
<div class="modal fade" id="myStores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo HEADER_TITLE_STORES ; ?></h4>
      </div>
      <div class="modal-body">
        <?php echo tep_draw_form('adminstores', FILENAME_DEFAULT, '', 'get', 'class="form-inline" role="form"  ') . tep_draw_pull_down_menu('multi_stores', $multi_stores_array, $multi_stores_selected, 'onchange="this.form.submit();"') . tep_hide_session_id() . '</form>'; ?>
      </div>
    </div>
  </div>
</div>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fullwidth">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class=" hidden-xs hidden-sm hidden-md"><a class="brand" href="<?php echo tep_href_link(FILENAME_DEFAULT) ; ?>"><img src="../images/<?php echo STORE_LOGO ; ?>" alt="osCommerce Online Merchant" width="202" height="30" ></a></li>
        <li class="                     hidden-lg"><a class="brand" href="<?php echo tep_href_link(FILENAME_DEFAULT) ; ?>"><?php echo tep_glyphicon( 'home') ; ?></a></li>		
<?php

  if ( isset($_SESSION['admin']) ) {
//    include(DIR_WS_INCLUDES . 'app_menu.php');
  }
?>
        <li><?php echo '<a href="' . tep_catalog_href_link() . '" target="_blank">' . HEADER_TITLE_ONLINE_CATALOG . '</a>'; ?></li>		
        <li class="dropdown hidden-xs hidden-sm">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Help<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="http://www.oscommerce.com" target="_blank"><?php echo HEADER_TITLE_SUPPORT_SITE; ?></a></li>
          </ul>
        </li>		
      </ul>

<?php
  if ( isset($_SESSION['admin']) ) {
?>
      <ul class="nav navbar-nav navbar-right"> 
	    <li ><a href="<?php echo tep_href_link( FILENAME_DEFAULT,        '', 'NONSSL' ) ; ?>"><span class="label label-success"><?php echo TEXT_MULTI_STORE_NAME . $multi_stores  ; ?></span></a></li> 	  
		<li> 		
<!-- bof buttons -->
        <li class="hidden-xs hidden-sm"><a href="<?php echo tep_href_link( FILENAME_CATEGORIES,        '', 'NONSSL' ); ?>"><span class="label label-primary"><?php echo HEADER_NAV_TEXT_PRODUCTS; ?></span></a></li>		
        <li class="hidden-xs hidden-sm"><a href="<?php echo tep_href_link( FILENAME_ORDERS,            '', 'NONSSL' ); ?>"><span class="label label-warning"><?php echo HEADER_NAV_TEXT_ORDERS; ?></span></a></li>		
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo HEADER_NAV_TEXT_DIRECT_ACCESS ; ?><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo tep_href_link( FILENAME_CATEGORIES,        '', 'NONSSL' ); ?>"><?php echo tep_glyphicon( 'pencil') .        HEADER_NAV_TEXT_PRODUCTS; ?></a></li>
            <li><a href="<?php echo tep_href_link( FILENAME_ORDERS,            '', 'NONSSL' ); ?>"><?php echo tep_glyphicon( 'shopping-cart') . HEADER_NAV_TEXT_ORDERS; ?></a></li>
            <li><a href="<?php echo tep_href_link( FILENAME_CUSTOMERS,         '', 'NONSSL' ); ?>"><?php echo tep_glyphicon( 'user') .          HEADER_NAV_TEXT_CUSTOMERS; ?></a></li>		    	   
            <li><a href="<?php echo tep_href_link( FILENAME_CREATE_ACCOUNT,    '', 'NONSSL' ); ?>"><?php echo tep_glyphicon( 'plus') .          HEADER_NAV_TEXT_ADD_CUSTOMER; ?></a></li>
            <li><a href="<?php echo tep_href_link( FILENAME_CREATE_ORDER,      '', 'NONSSL' ); ?>"><?php echo tep_glyphicon( 'plus') .          HEADER_NAV_TEXT_ADD_ORDERS; ?></a></li>
            <li><a href="<?php echo tep_href_link( FILENAME_WHOS_ONLINE,       '', 'NONSSL' ); ?>"><?php echo tep_glyphicon( 'question') .      HEADER_NAV_TEXT_ACTIVE_CUST; ?></a></li>				
          </ul>
        </li>
<!-- eof buttons -->
<!--// bof dropdown stores -->
        <li class="dropdown"> 
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo HEADER_TITLE_STORES  ; ?><b class="caret"></b></a> 
          <ul class="dropdown-menu"> 
<?php
 
               if (count($multi_stores_array) > 0) {
                  reset($multi_stores_array);
                  while (list($key, $value) = each($multi_stores_array)) {				
                     echo '<li><a href="' .  tep_href_link(FILENAME_DEFAULT, 'multi_stores=' . $value[ 'id'] ) . '">' . $value[ 'text'] . '</a></li>' ;
                  }
               }
	         
?>			 
          </ul> 
       </li>    
<!--// eof dropdown stores -->		
<!--// bof dropdown lang -->
        <li class="dropdown"> 
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo HEADER_TITLE_LANGUAGES  ; ?><b class="caret"></b></a> 
          <ul class="dropdown-menu"> 
<?php

// languages
               if (!isset($lng) || (isset($lng) && !is_object($lng))) {
                  include(DIR_WS_CLASSES . 'language.php');
                  $lng = new language;
               }
               if (count($lng->catalog_languages) > 1) {
                  reset($lng->catalog_languages);
                  while (list($key, $value) = each($lng->catalog_languages)) {				
                     echo '<li><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . 
					                            tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name'], null, null, null, false) . ' ' . rtrim( $value['name'] ) . '</a></li>';
                  }
               }
	         
?>			 
          </ul> 
       </li>    
<!--// eof dropdown lang -->
        <li class="dropdown hidden-xs hidden-sm hidden-md">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo tep_output_string_protected($_SESSION['admin']['username']); ?><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo tep_href_link(FILENAME_LOGIN, 'action=logoff'); ?>"><?php echo tep_glyphicon( 'user-times') . ' ' . HEADER_TITLE_LOGOFF; ?></a></li>				
          </ul>
        </li>
		<li class="                     hidden-lg"><a class="brand" href="<?php echo tep_href_link(FILENAME_LOGIN, 'action=logoff') ; ?>"><?php echo tep_glyphicon( 'user-times') ; ?></a></li>
	  </ul>
<?php
  }
?>
    </div>
    </div>
</div>
<?php
  $templateModules['footer'][] = <<<EOD
<!-- navbar jQuery menu script -->
<script>
$(function () {
  $('a.submenu').on("click", function (e) {
    e.preventDefault();
  });
});
</script>
EOD;
?>
 
<?php 
if ( isset($_SESSION['admin']) ) {
?>	
 
  <div class="clearfix"></div>
  <hr> 
  <br />
<?php
//++++ QT Pro: Begin added code

  $qtpro_sick_count = qtpro_sick_product_count();
  if ( $PHP_SELF != 'qtprodoctor.php' ) {
     if($qtpro_sick_count != 0){
?>
 
         <div class="well well-danger"><?php echo TEXT_SHORT_DESCRIPTION_006 . $qtpro_sick_count . TEXT_SHORT_DESCRIPTION_007 . ' <a href="' . tep_href_link(FILENAME_QTPRODOCTOR) . '" class="headerLink">' . TEXT_SHORT_DESCRIPTION_008 . '</a>.'; ?></div><br />
 	 
<?php
	 }
  }

  if ($dir = @dir(DIR_FS_ADMIN . 'includes/boxes')) {
      $files = array();
      while ($file = $dir->read()) {
        if (!is_dir($dir->path . '/' . $file)) {
          if (substr($file, strrpos($file, '.')) == '.php') {
            $files[] = $file;
          }
        }
      }
      $dir->close();
      natcasesort($files);
      foreach ( $files as $file ) {
        if ( file_exists(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file) ) {
          include(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/boxes/' . $file);
        }
        include($dir->path . '/' . $file);
      }
    }

    function tep_sort_admin_boxes($a, $b) {
      return strcasecmp($a['heading'], $b['heading']);
    }
    usort($cl_box_groups, 'tep_sort_admin_boxes');

    function tep_sort_admin_boxes_links($a, $b) {
      return strcasecmp($a['title'], $b['title']);
    }
    foreach ( $cl_box_groups as &$group ) {
      usort($group['apps'], 'tep_sort_admin_boxes_links');
    }
?>
    <div class="navbar navbar-default navbar-static-top">
        <div class="container-fullwidth">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".main-nav">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse main-nav">
             <ul class="nav navbar-nav">	   
<?php
                  foreach ($cl_box_groups as $groups) {
                     $counter++;				
?>					 
                     <li class="dropdown">	  
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $groups['icon'] . $groups['heading']  ; ?><span class="caret"></span></a>
                           <ul class="dropdown-menu" role="menu">						
<?php
                     foreach ($groups['apps'] as $app) {
?>							
                               <li><a href="<?php echo $app['link'] ; ?>"><?php echo $app["title"] ; ?></a></li>
<?php
                     }
?>					 
                          </ul>
                     </li>
<?php
				  }
?>				  
            </ul>	
            </div>
        </div>
    </div>
 
<?php
 }
?>