<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $compat_register_globals = true;

  if (function_exists('ini_get') && (PHP_VERSION < 4.3) && ((int)ini_get('register_globals') == 0)) {
    $compat_register_globals = false;
  }
  
   $languages_array = array(array('id' => 'english', 'text' => 'English'),
                            array('id' => 'dutch', 'text' => 'Nederlands')
                            );  

  require ('includes/languages/' . $language . '/' . basename($_SERVER['PHP_SELF']));  
?>
<div class="col-xs-12 col-sm-12 col-md-12 page_header">
  <form action="index.php" method="get">

       <?php echo TEXT_CHOOSE_LANGUAGE; ?><?php echo osc_draw_select_menu('language', $languages_array, $language, 'onChange="this.form.submit();"'); ?>

       <br /><br />

  </form>
</div>  

<div class="alert alert-info">
  <h1><?php echo PAGE_TITLE_WELCOME; ?><?php echo osc_get_version(); ?>!</h1>
  <p><?php  echo TEXT_INDEX ; ?></p>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-push-3 col-sm-9">
    <div class="page-header">
      <h2><?php echo TEXT_NEW_INSTALLATION ; ?></h2>
    </div>

<?php
    $configfile_array = array();

    if (file_exists(osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php') && !osc_is_writable(osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php')) {
      @chmod(osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php', 0777);
    }

    if (file_exists(osc_realpath(dirname(__FILE__) . '/../../../admin/includes') . '/configure.php') && !osc_is_writable(osc_realpath(dirname(__FILE__) . '/../../../admin/includes') . '/configure.php')) {
      @chmod(osc_realpath(dirname(__FILE__) . '/../../../admin/includes') . '/configure.php', 0777);
    }

    if (file_exists(osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php') && !osc_is_writable(osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php')) {
      $configfile_array[] = osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php';
    }

    if (file_exists(osc_realpath(dirname(__FILE__) . '/../../../admin/includes') . '/configure.php') && !osc_is_writable(osc_realpath(dirname(__FILE__) . '/../../../admin/includes') . '/configure.php')) {
      $configfile_array[] = osc_realpath(dirname(__FILE__) . '/../../../admin/includes') . '/configure.php';
    }

    $warning_array = array();

    if (function_exists('ini_get')) {
      if ($compat_register_globals == false) {
        $warning_array['register_globals'] = TEXT_COMPAT_PHP43 ;
      }
    }

    if (!extension_loaded('mysql')) {
      $warning_array['mysql'] = TEXT_MYSQL_EXT ;
    }

    if ((sizeof($configfile_array) > 0) || (sizeof($warning_array) > 0)) {
?>

      <div class="noticeBox">

<?php
      if (sizeof($warning_array) > 0) {
?>

        <table class="table table-condensed">

<?php
        reset($warning_array);
        while (list($key, $value) = each($warning_array)) {
          echo '        <tr>' . "\n" .
               '          <td valign="top"><strong>' . $key . '</strong></td>' . "\n" .
               '          <td valign="top">' . $value . '</td>' . "\n" .
               '        </tr>' . "\n";
        }
?>

        </table>
<?php
      }

      if (sizeof($configfile_array) > 0) {
?>

        <div class="alert alert-danger">
          <p><?php echo TEXT_PARAMETERS ; ?></p>
          <p><?php echo TEXT_CHMOD ; ?></p>
          <p>

<?php
          for ($i=0, $n=sizeof($configfile_array); $i<$n; $i++) {
            echo $configfile_array[$i];

            if (isset($configfile_array[$i+1])) {
              echo '<br />';
            }
          }
?>

          </p>
        </div>

<?php
      }
?>

      </div>

<?php
    }

    if ((sizeof($configfile_array) > 0) || (sizeof($warning_array) > 0)) {
?>

      <div class="alert alert-danger"><?php echo TEXT_CORRECT ; ?></div>

<?php
      if (sizeof($warning_array) > 0) {
        echo '    <div class="alert alert-info"><i>' . TEXT_CHANGING . '</i></div>' . "\n";
      }
?>

      <p><a href="index.php" class="btn btn-danger btn-block" role="button"><?php echo IMAGE_RETRY ; ?></a></p>

<?php
    } else {
?>

      <div class="alert alert-success"><?php echo TEXT_VERIFIED ; ?></div>

      <div class="alert alert-info"><?php echo TEXT_INSTALL_PROCEDURE ; ?></div>	  

      <div id="jsOn" style="display: none;">
        <p><a href="install.php" class="btn btn-success btn-block" role="button"><?php echo TEXT_STANDARD_INSTALL ; ?></a></p>
      </div>

      <div id="jsOn_Ms" style="display: none;">
        <p><a href="install.php?step=20" class="btn btn-primary btn-block" role="button"><?php echo TEXT_MULTISHOP_INSTALL ; ?></a></p>
      </div>	  

      <div id="jsOff">
        <p class="text-danger"><?php TEXT_ENABLE_JAVA ; ?></p>
        <p><a href="index.php" class="btn btn-danger btn-block" role="button"><?php echo IMAGE_RETRY ; ?></a></p>
      </div>

<script>
$(function() {
  $('#jsOff').hide();
  $('#jsOn').show();
  $('#jsOn_Ms').show();  
});
</script>

<?php
  }
?>
  </div>
  <div class="col-xs-12 col-sm-pull-9 col-sm-3">
    <div class="panel panel-success">
      <div class="panel-heading">
        <?php echo TEXT_SERVEER_CAPABILITIES ; ?>
      </div>
      <div class="panel-body">
        <table class="table table-condensed">
          <tr>
            <td><strong><?php echo TEXT_PHP_VERSION ; ?></strong></td>
            <td align="right"><?php echo PHP_VERSION; ?></td>
            <td align="right" width="25"><img src="images/<?php echo ((PHP_VERSION >= 4) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
        </table>

<?php
  if (function_exists('ini_get')) {
?>

        <br />

        <table class="table table-condensed">
          <tr>
            <td colspan="3"><strong><?php echo TEXT_PHP_VERSION ; ?></strong></td>
          </tr>
          <tr>
            <td><?php echo TEXT_R_G ; ?></td>
            <td align="right"><?php echo (((int)ini_get('register_globals') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><img src="images/<?php echo (($compat_register_globals == true) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
          <tr>
            <td><?php echo TEXT_M_Q ; ?></td>
            <td align="right"><?php echo (((int)ini_get('magic_quotes') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><img src="images/<?php echo (((int)ini_get('magic_quotes') == 0) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
          <tr>
            <td><?php echo TEXT_F_U ; ?></td>
            <td align="right"><?php echo (((int)ini_get('file_uploads') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><img src="images/<?php echo (((int)ini_get('file_uploads') == 1) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
          <tr>
            <td><?php echo TEXT_SA_S ; ?></td>
            <td align="right"><?php echo (((int)ini_get('session.auto_start') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><img src="images/<?php echo (((int)ini_get('session.auto_start') == 0) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
          <tr>
            <td><?php echo TEXT_SU_T_S ; ?></td>
            <td align="right"><?php echo (((int)ini_get('session.use_trans_sid') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right"><img src="images/<?php echo (((int)ini_get('session.use_trans_sid') == 0) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
        </table>

        <br />

        <table class="table table-condensed">
          <tr>
            <td colspan="2"><strong><?php echo TEXT_PHP_EXTENSIONS ; ?></strong></td>
          </tr>
          <tr>
            <td><?php echo TEXT_M_S ; ?></td>
            <td align="right"><img src="images/<?php echo (extension_loaded('mysql') || extension_loaded('mysqli') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
        </table>

        <br />

        <table class="table table-condensed">
          <tr>
            <td colspan="2"><strong><?php echo TEXT_OPT_PHP_EXT ; ?></strong></td>
          </tr>
          <tr>
            <td><?php echo TEXT_GD ; ?></td>
            <td align="right"><img src="images/<?php echo (extension_loaded('gd') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
          <tr>
            <td><?php echo TEXT_CURL ; ?></td>
            <td align="right"><img src="images/<?php echo (extension_loaded('curl') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
          <tr>
            <td><?php echo TEXT_OPENSSL ; ?></td>
            <td align="right"><img src="images/<?php echo (extension_loaded('openssl') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16" /></td>
          </tr>
        </table>

<?php
  }
?>
      </div>
    </div>
  </div>
</div>
