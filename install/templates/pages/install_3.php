<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/
  
  require ('includes/languages/' . $language . '/' . basename($_SERVER['PHP_SELF']));

  $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '\\') && (substr($dir_fs_document_root, -1) != '/')) {
    if (strrpos($dir_fs_document_root, '\\') !== false) {
      $dir_fs_document_root .= '\\';
    } else {
      $dir_fs_document_root .= '/';
    }
  }
?>

<div class="row">
  <div class="col-sm-9">
    <div class="alert alert-info">
      <h1><?php echo PAGE_TITLE_INSTALLATION ; ?></h1>

      <p></p>
      <p><?php echo TEXT_DESCRIPTION ; ?></p>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="panel panel-default">
      <div class="panel-body">
        <ol>
          <li class="text-muted"><?php echo TEXT_BOX_DB ; ?></li>
          <li class="text-muted"><?php echo TEXT_BOX_WS ; ?></li>
          <li class="text-success"><strong><?php echo TEXT_BOX_ON ; ?></strong></li>
          <li class="text-muted"><?php echo TEXT_BOX_FINISH ; ?></li>
        </ol>
      </div>
    </div>
    <div class="progress">
      <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
    </div>
  </div>
</div>

<div class="clearfix"></div>

<div class="row">
  <div class="col-xs-12 col-sm-push-3 col-sm-9">

    <div class="page-header">
      <p class="inputRequirement pull-right text-right"><span class="glyphicon glyphicon-asterisk inputRequirement"></span><?php echo TEXT_INPUT_REQUIRED ; ?></p>
      <h2><?php echo TEXT_BOX_ON ; ?></h2>
    </div>

    <form name="install" id="installForm" action="install.php?step=4" method="post" class="form-horizontal" role="form">

      <div class="form-group has-feedback">
        <label for="storeName" class="control-label col-xs-3"><?php echo TEXT_STORE_NAME ;?> </label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('CFG_STORE_NAME', NULL, 'required aria-required="true" id="storeName" placeholder="Your Store Name"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_STORE_NAME_TEXT ; ?></span>
        </div>
      </div>
      

      <div class="form-group has-feedback">
        <label for="ownerName" class="control-label col-xs-3"><?php echo TEXT_OWNER_NAME ;?> </label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('CFG_STORE_OWNER_NAME', NULL, 'required aria-required="true" id="ownerName" placeholder="Your Name"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_OWNER_NAME_TEXT ; ?></span>
        </div>
      </div>
      
      <div class="form-group has-feedback">
        <label for="ownerEmail" class="control-label col-xs-3"><?php echo TEXT_OWNER_MAIL ;?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('CFG_STORE_OWNER_EMAIL_ADDRESS', NULL, 'required aria-required="true" id="ownerEmail" placeholder="you@yours.com"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_OWNER_MAIL_TEXT ; ?></span>
        </div>
      </div>
      
      <div class="form-group has-feedback">
        <label for="adminUsername" class="control-label col-xs-3"><?php echo TEXT_AU ;?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('CFG_ADMINISTRATOR_USERNAME', NULL, 'required aria-required="true" id="adminUsername" placeholder="Username"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_AU_TEXT ; ?></span>
        </div>
      </div>
      
      <div class="form-group has-feedback">
        <label for="adminPassword" class="control-label col-xs-3"><?php echo TEXT_AP_TEXT ;?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('CFG_ADMINISTRATOR_PASSWORD', NULL, 'required aria-required="true" id="adminPassword"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_AP_TEXT ; ?></span>
        </div>
      </div>
      
      
      
      <?php
  if (osc_is_writable($dir_fs_document_root) && osc_is_writable($dir_fs_document_root . 'admin')) {
?>
      <div class="form-group has-feedback">
        <label for="adminDir" class="control-label col-xs-3"><?php echo TEXT_ADMIN_DN ;?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('CFG_ADMIN_DIRECTORY', 'admin', 'required aria-required="true" id="adminDir"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_ADMIN_DN_TEXT ; ?>.</span>
        </div>
      </div>
<?php
  }

  if (PHP_VERSION >= '5.2') {
?>
      <div class="form-group has-feedback">
        <label for="Zulu" class="control-label col-xs-3"><?php echo TEXT_CFG_TIME_ZONE ; ?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_time_zone_select_menu('CFG_TIME_ZONE'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_CFG_TIME_ZONE_TEXT ; ?></span>
        </div>
      </div>
<?php
  }
?>

      <p><?php echo osc_draw_button(TEXT_NEXT_STEP4, 'triangle-1-e', null, 'primary', null, 'btn-success btn-block'); ?></p>

      <?php
      foreach ( $_POST as $key => $value ) {
        if (($key != 'x') && ($key != 'y')) {
          echo osc_draw_hidden_field($key, $value);
        }
      }
      ?>

    </form>

  </div>
  <div class="col-xs-12 col-sm-pull-9 col-sm-3">
    <div class="panel panel-success">
      <div class="panel-heading">
        <?php echo TEXT_STEP3 ; ?>
      </div>
      <div class="panel-body">
        <p></p>
        <p><?php echo TEXT_STEP3_TEXT ; ?></p>
      </div>
    </div>
  </div>
  
</div>
