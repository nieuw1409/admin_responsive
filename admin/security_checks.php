<?php
/*
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2015 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  function tep_sort_secmodules($a, $b) {
    return strcasecmp($a['title'], $b['title']);
  }
  $types = array('fa fa-info-circle fa-lg icon-blue', 'fa fa-exclamation-triangle fa-lg', 'fa fa-times fa-lg icon-red');
  $modules = array();
  if ($secdir = @dir(DIR_FS_ADMIN . 'includes/modules/security_check/')) {
    while ($file = $secdir->read()) {
      if (!is_dir(DIR_FS_ADMIN . 'includes/modules/security_check/' . $file)) {
        if (substr($file, strrpos($file, '.')) == '.php') {
          $class = 'securityCheck_' . substr($file, 0, strrpos($file, '.'));
          include(DIR_FS_ADMIN . 'includes/modules/security_check/' . $file);
          $$class = new $class();
          $modules[] = array('title' => isset($$class->title) ? $$class->title : substr($file, 0, strrpos($file, '.')),
                             'class' => $class,
                             'code' => substr($file, 0, strrpos($file, '.')));
        }
      }
    }
    $secdir->close();
  }
  if ($extdir = @dir(DIR_FS_ADMIN . 'includes/modules/security_check/extended/')) {
    while ($file = $extdir->read()) {
      if (!is_dir(DIR_FS_ADMIN . 'includes/modules/security_check/extended/' . $file)) {
        if (substr($file, strrpos($file, '.')) == '.php') {
          $class = 'securityCheckExtended_' . substr($file, 0, strrpos($file, '.'));
          include(DIR_FS_ADMIN . 'includes/modules/security_check/extended/' . $file);
          $$class = new $class();
          $modules[] = array('title' => isset($$class->title) ? $$class->title : substr($file, 0, strrpos($file, '.')),
                             'class' => $class,
                             'code' => substr($file, 0, strrpos($file, '.')));
        }
      }
    }
    $extdir->close();
  }
  usort($modules, 'tep_sort_secmodules');
  require(DIR_WS_INCLUDES . 'template_top.php');
?>

    <div class="col-xs-12">
    <div class="page-header">
       <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
       <div class="clearfix"></div>
    </div><!-- page-header-->
		<div class="col-xs-12 col-md-6 text-right">
			<a class="btn btn-default" id="btnStartReload" href="<?php echo tep_href_link('security_checks.php'); ?>" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Reloading..." role="button"><?php echo tep_glyphicon('refresh', 'danger') . Reload; ?></a>
	    </div>
	</div>
<script>
// button state demo
$("#btnStartReload").click(function(){
$("#btnStartReload").button('loading');
});
</script>

<div class="row">    
  <div class="col-xs-12">  
   <div class="table-responsive">  
    <table class="table table-hover table-condensed table-responsive table-striped">
		<thead>     
			<tr>
				<th>&nbsp;</th>
				<th><?php echo TABLE_HEADING_TITLE; ?></th>
				<th><?php echo TABLE_HEADING_MODULE; ?></th>
				<th><?php echo TABLE_HEADING_INFO; ?></th>
				<th class="text-right">&nbsp;</th>
			</tr>
		</thead>
		<tbody>	  

<?php
  foreach ($modules as $module) {
    $secCheck = $$module['class'];
    if ( !in_array($secCheck->type, $types) ) {
      $secCheck->type = tep_glyphicon('exclamation-triangle', 'danger') ;  //'fa fa-exclamation-triangle fa-lg';
    }
    $output = '';
    if ( $secCheck->pass() ) {
      $secCheck->type = tep_glyphicon('ok-sign', 'success');
    } else {
      $output = $secCheck->getMessage();
    }
    echo '  <tr>' .
       //  '    <td class="text-center">' . tep_image(DIR_WS_IMAGES . 'ms_' . $secCheck->type . '.png', '', 16, 16) . '</td>' .
         '    <td class="text-center">' . $secCheck->type . '</td>' .
         '    <td style="white-space: nowrap;">' . tep_output_string_protected($module['title']) . '</td>' .
         '    <td>' . tep_output_string_protected($module['code']) . '</td>' .
         '    <td>' . $output . '</td>' .
         '    <td class="text-center">' . ((isset($secCheck->has_doc) && $secCheck->has_doc) ? '<a href="http://library.oscommerce.com/Wiki&oscom_2_3&security_checks&' . $module['code'] . '" target="_blank">' . tep_glyphicon('eye-open', 'info') . '</a>' : '') . '</td>' .
         '  </tr>';
  }
?>

	  </tbody>
	</table>
   </div>
  </div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>