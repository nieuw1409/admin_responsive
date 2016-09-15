<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  $www_location = 'http://' . $_SERVER['HTTP_HOST'];

  if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
    $www_location .= $_SERVER['REQUEST_URI'];
  } else {
    $www_location .= $_SERVER['SCRIPT_FILENAME'];
  }

  $www_location = substr($www_location, 0, strpos($www_location, 'install'));

  $dir_fs_www_root = osc_realpath(dirname(__FILE__) . '/../../../') . '/';
  
  require ('includes/languages/' . $language . '/' . basename($_SERVER['PHP_SELF']));  
?>


<div class="row">
  <div class="col-sm-9">
    <div class="alert alert-info">
      <h1><?php echo PAGE_TITLE_INSTALLATION ; ?></h1>

      <p><?php echo TEXT_DESCRIPTION ; ?></p>
      <p></p>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="panel panel-default">
      <div class="panel-body">
        <ol>
          <li class="text-muted"><?php echo TEXT_BOX_DB ; ?></li>
          <li class="text-success"><strong><?php echo TEXT_BOX_WS ; ?></strong></li>
          <li class="text-muted"><?php echo TEXT_BOX_ON ; ?></li>
          <li class="text-muted"><?php echo TEXT_BOX_FINISH ; ?></li>
        </ol>
      </div>
    </div>
    <div class="progress">
      <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">50%</div>
    </div>
  </div>
</div>

<div class="clearfix"></div>

<div class="row">
  <div class="col-xs-12 col-sm-push-3 col-sm-9">

    <div class="page-header">
      <p class="inputRequirement pull-right text-right"><span class="glyphicon glyphicon-asterisk inputRequirement"></span><?php echo TEXT_INPUT_REQUIRED ; ?></p>
      <h2><?php echo TEXT_BOX_WS ; ?></h2>
    </div>

    <form name="install" id="installForm" action="install.php?step=3" method="post" class="form-horizontal" role="form">

      <div class="form-group has-feedback">
        <label for="wwwAddress" class="control-label col-xs-3"><?php echo TEXT_WWW_ADDRESS ; ?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('HTTP_WWW_ADDRESS', $www_location, 'required aria-required="true" id="wwwAddress" placeholder="http://"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_WWW_ADDRESS_TEXT ; ?></span>
        </div>
      </div>
    
      <div class="form-group has-feedback">
        <label for="webRoot" class="control-label col-xs-3"><?php echo TEXT_WWW_ROOT_DIRECTORY ; ?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('DIR_FS_DOCUMENT_ROOT', $dir_fs_www_root, 'required aria-required="true" id="webRoot"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_WWW_ROOT_DIRECTORY_TEXT ; ?></span>
        </div>
      </div>

      <p><?php echo osc_draw_button(TEXT_NEXT_STEP3, 'triangle-1-e', null, 'primary', null, 'btn-success btn-block'); ?></p>

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
        <?php echo TEXT_STEP2 ; ?>
      </div>
      <div class="panel-body">
        <p><?php echo TEXT_STEP2_TEXT ; ?></p>
      </div>
    </div>
  </div>
  
</div>
