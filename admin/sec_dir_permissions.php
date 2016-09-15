<?php
/*
  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2015 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');

  $whitelist_array = array();
  $whitelist_query = tep_db_query("select directory from " . TABLE_SEC_DIRECTORY_WHITELIST);
  while ($whitelist = tep_db_fetch_array($whitelist_query)) {
    $whitelist_array[] = $whitelist['directory'];
  }
  $admin_dir = basename(DIR_FS_ADMIN);
  if ($admin_dir != 'admin') {
    for ($i=0, $n=sizeof($whitelist_array); $i<$n; $i++) {
      if (substr($whitelist_array[$i], 0, 6) == 'admin/') {
        $whitelist_array[$i] = $admin_dir . substr($whitelist_array[$i], 5);
      }
    }
  }
  require(DIR_WS_INCLUDES . 'template_top.php');
?>
	<div class="row">
		<div class="col-xs-12">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
        <table class="table table-hover table-condensed table-responsive table-striped">
			<thead>     
				<tr>
					<th><?php echo TABLE_HEADING_DIRECTORIES; ?></th>
					<th class="text-center"><?php echo TABLE_HEADING_WRITABLE; ?></th>
					<th class="text-center"><?php echo TABLE_HEADING_RECOMMENDED; ?></th>
				</tr>
			</thead>
			<tbody>	
<?php
  foreach (tep_opendir(DIR_FS_CATALOG) as $file) {
    if ($file['is_dir']) {
?>
              <tr>
                <td><?php echo substr($file['name'], strlen(DIR_FS_CATALOG)); ?></td>
                <td class="text-center"><?php echo (($file['writable'] == true) ? tep_glyphicon('ok-sign', 'success')  : tep_glyphicon('remove-sign', 'danger') ); ?></td>
                <td class="text-center"><?php echo (in_array(substr($file['name'], strlen(DIR_FS_CATALOG)), $whitelist_array) ? tep_glyphicon('ok-sign', 'success'): tep_glyphicon('remove-sign', 'danger') ); ?></td>
              </tr>
<?php
    }
  }
?>
          </tbody>
        </table>
                <?php echo TEXT_DIRECTORY . ' ' . DIR_FS_CATALOG; ?>
	  </div>
	</div>	
</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>