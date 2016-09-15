<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

 require ('includes/languages/' . $language . '/' . basename($_SERVER['PHP_SELF']));
?>

<script>
<!--

  var dbServer;
  var dbUsername;
  var dbPassword;
  var dbName;

  var formSubmited = false;
  var formSuccess = false;

  function prepareDB() {
    if (formSubmited == true) {
      return false;
    }

    formSubmited = true;

    $('#mBox').show();

    $('#mBoxContents').html('<p><img src="images/progress.gif" align="right" hspace="5" vspace="5" /><?php echo CONNECT_TESTING; ?></p>');

    dbServer = $('#DB_SERVER').val();
    dbUsername = $('#DB_SERVER_USERNAME').val();
    dbPassword = $('#DB_SERVER_PASSWORD').val();
    dbName = $('#DB_DATABASE').val();

    $.get('rpc.php?action=dbCheck&server=' + encodeURIComponent(dbServer) + '&username=' + encodeURIComponent(dbUsername) + '&password=' + encodeURIComponent(dbPassword) + '&name=' + encodeURIComponent(dbName), function (response) {
      var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(response);
      result.shift();

      if (result[0] == '1') {
        $('#mBoxContents').html('<p><img src="images/progress.gif" align="right" hspace="5" vspace="5" /><?php echo CONNECT_SUCCESSFULLY_MS; ?></p>');

        formSuccess = true;
        setTimeout(function() {
            $('#installForm').submit();
        }, 2000);			
      } else {
        var result_error = result[1].replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

        $('#mBoxContents').html('<p><img src="images/failed.gif" align="right" hspace="5" vspace="5" /><?php echo CONNECT_PROBLEM; ?></p>'.replace('%s', result_error));

        formSubmited = false;
      }
    }).fail(function() {
      formSubmited = false;
    });
  }

  $(function() {
    $('#installForm').submit(function(e) {
      if ( formSuccess == false ) {
        e.preventDefault();

        prepareDB();
      }
    });
  });

//-->
</script>
<div class="row">
  <div class="col-sm-9">
    <div class="alert alert-info">
      <h1><?php echo PAGE_TITLE_INSTALLATION_MULTI_STORE ; ?></h1>

      <p><?php echo TEXT_DESCRIPTION ; ?></p>
      <p></p>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="panel panel-default">
      <div class="panel-body">
        <ol>
          <li class="text-success"><strong><?php echo TEXT_BOX_DB ; ?></strong></li>
          <li class="text-muted"><?php echo TEXT_BOX_WS ; ?></li>
          <li class="text-muted"><?php echo TEXT_BOX_ON ; ?></li>
          <li class="text-muted"><?php echo TEXT_BOX_FINISH ; ?>!</li>
        </ol>
      </div>
    </div>
    <div class="progress">
      <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">25%</div>
    </div>
  </div>
</div>
  
<div class="clearfix"></div>

<div class="row">
  <div class="col-xs-12 col-sm-push-3 col-sm-9">

    <div id="mBox">
      <div class="alert alert-danger">
        <div id="mBoxContents"></div>
      </div>
    </div>
    
    <div class="page-header">
      <p class="inputRequirement pull-right text-right"><span class="glyphicon glyphicon-asterisk inputRequirement"></span> <?php echo TEXT_INPUT_REQUIRED ; ?></p>
      <h2><?php echo TEXT_BOX_DB ; ?></h2>
    </div>
    
    <form name="install" id="installForm" action="install.php?step=22" method="post" class="form-horizontal" role="form">
    
      <div class="form-group has-feedback">
        <label for="dbServer" class="control-label col-xs-3"><?php echo TEXT_BOX_DB ; ?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('DB_SERVER', NULL, 'required aria-required="true" id="dbServer" placeholder="localhost"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_DATABASE_TEXT ; ?></span>
        </div>
      </div>
    
      <div class="form-group has-feedback">
        <label for="userName" class="control-label col-xs-3"><?php echo TEXT_DATABASE_USERNAME ; ?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('DB_SERVER_USERNAME', NULL, 'required aria-required="true" id="userName" placeholder="Username"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_DATABASE_USERNAME_TEXT ; ?></span>
        </div>
      </div>
    
      <div class="form-group has-feedback">
        <label for="passWord" class="control-label col-xs-3"><?php echo TEXT_DATABASE_PASSWORD ; ?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_password_field('DB_SERVER_PASSWORD', NULL, 'required aria-required="true" id="passWord"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php echo TEXT_DATABASE_PASSWORD_TEXT ; ?></span>
        </div>
      </div>
    
      <div class="form-group has-feedback">
        <label for="dbName" class="control-label col-xs-3"><?php echo TEXT_DATABASE_NAME ; ?></label>
        <div class="col-xs-9">
          <?php echo osc_draw_input_field('DB_DATABASE', NULL, 'required aria-required="true" id="dbName" placeholder="Database"'); ?>
          <span class="glyphicon glyphicon-asterisk form-control-feedback inputRequirement"></span>
          <span class="help-block"><?php  echo TEXT_DATABASE_NAME_TEXT ; ?></span>
        </div>
      </div>

      <p><?php echo osc_draw_button(TEXT_NEXT_STEP2, 'triangle-1-e', null, 'primary', null, 'btn-success btn-block'); ?></p>

    </form>
    
  </div>
  <div class="col-xs-12 col-sm-pull-9 col-sm-3">
    <div class="panel panel-success">
      <div class="panel-heading">
        <?php echo TEXT_STEP1 ; ?>
      </div>
      <div class="panel-body">
        <p><?php echo TEXT_STEP1_TEXT1 ; ?></p>
        <p><?php echo TEXT_STEP1_TEXT2 ; ?></p>
      </div>
    </div>
  </div>
  
</div>
