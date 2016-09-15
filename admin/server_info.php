<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  switch ($action) {
    case 'export':
      $info = tep_get_system_information();
    break;

    case 'submit':
      $target_host = 'usage.oscommerce.com';
      $target_path = '/submit.php';

      $encoded = base64_encode(serialize(tep_get_system_information()));

      $response = false;

      if (function_exists('curl_init')) {
        $data = array('info' => $encoded);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://' . $target_host . $target_path);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = trim(curl_exec($ch));
        curl_close($ch);
      } else {
        if ($fp = @fsockopen($target_host, 80, $errno, $errstr, 30)) {
          $data = 'info=' . $encoded;

          fputs($fp, "POST " . $target_path . " HTTP/1.1\r\n");
          fputs($fp, "Host: " . $target_host . "\r\n");
          fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
          fputs($fp, "Content-length: " . strlen($data) . "\r\n");
          fputs($fp, "Connection: close\r\n\r\n");
          fputs($fp, $data."\r\n\r\n");

          $response = '';

          while (!feof($fp)) {
            $response .= fgets($fp, 4096);
          }

          fclose($fp);

          $response = trim(substr($response, strrpos($response, "\r\n\r\n")));
        }
      }

      if ($response != 'OK') {
        $messageStack->add_session(ERROR_INFO_SUBMIT, 'error');
      } else {
        $messageStack->add_session(SUCCESS_INFO_SUBMIT, 'success');
      }

      tep_redirect(tep_href_link(FILENAME_SERVER_INFO));
    break;

    case 'save':
      $info = tep_get_system_information();
      $info_file = 'server_info-' . date('YmdHis') . '.txt';
      header('Content-type: text/plain');
      header('Content-disposition: attachment; filename=' . $info_file);
      echo tep_format_system_info_array($info);
      exit;

    break;

    default:
      $info = tep_get_system_information();
      break;
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<style type="text/css">
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold;}
.h {background-color: #9999cc; font-weight: bold;}
.v {background-color: #cccccc;}
i {color: #666666;} 
</style>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
<?php

  if ($action == 'export') {
?>
		<p><?php echo TEXT_EXPORT_INTRO; ?></p>
        <p>  <?php echo tep_draw_textarea_field('server configuration', 'soft', '100', '10', tep_format_system_info_array($info)); ?></p>
        <p class="text-center"> <?php echo tep_draw_bs_button(IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_SERVER_INFO                  ), 'primary', null, 'btn-primary') . '&nbsp;' . 
										   tep_draw_bs_button(IMAGE_SEND, 'paper-plane-o', tep_href_link(FILENAME_SERVER_INFO, 'action=submit'), 'primary', null, 'btn-primary') . '&nbsp;' . 
										   tep_draw_bs_button(IMAGE_SAVE, 'floppy-o', tep_href_link(FILENAME_SERVER_INFO, 'action=save'),        'primary', null, 'btn-success');?></p>

<?php
  } else {
?>

		  <div class="panel panel-primary">
		     <div class="panel-heading"><?php echo HEADING_SERVER_INFO ; ?></div>
			 <div class="panel-body">
                   <label><?php echo TITLE_SERVER_HOST; ?></label>
                   <?php echo $server['host'] . ' (' . gethostbyname($server['host']) . ')'; ?>
				   <br />
                   <label><?php echo TITLE_DATABASE_HOST; ?></label>
				   <?php echo DB_SERVER . ' (' . gethostbyname(DB_SERVER) . ')'; ?>
                   <br />
                   <label><?php echo TITLE_SERVER_OS; ?></label>
				   <?php echo $info['system']['os'] . ' ' . $info['system']['kernel']; ?>
                   <br />
				   <label><?php echo TITLE_DATABASE; ?></label>
                   <?php echo 'MySQL ' . $info['mysql']['version']; ?>
                   <br />
                   <label><?php echo TITLE_SERVER_DATE; ?></label>
				   <?php echo $info['system']['date']; ?>
                   <br />
				   <label><?php echo TITLE_DATABASE_DATE; ?></label>
				   <?php echo $info['mysql']['date']; ?>
                   <br />
                   <label><?php echo TITLE_SERVER_UP_TIME; ?></label>
				   <?php echo $info['system']['uptime']; ?>
                   <br />  
                   <label><?php echo TITLE_HTTP_SERVER; ?></label>
                   <?php echo $info['system']['http_server']; ?>
                   <br />
                   <label><?php echo TITLE_PHP_VERSION; ?></label>
                   <?php echo $info['php']['version'] . ' (' . TITLE_ZEND_VERSION . ' ' . $info['php']['zend'] . ')'; ?>
                   <br />
                   <?php echo tep_draw_bs_button(IMAGE_EXPORT, 'disk', tep_href_link(FILENAME_SERVER_INFO, 'action=export'));?>		 
			 
			 </div>
		  </div>
<?php
          if (function_exists('ob_start')) {

               ob_start();
               phpinfo();
               $phpinfo = ob_get_contents();
               ob_end_clean();
               $phpinfo = str_replace('border: 1px', '', $phpinfo);
               preg_match('/<body>(.*)<\/body>/is', $phpinfo, $regs);
               echo '<table class="table">' .
                  '  <tr><td><a href="http://www.oscommerce.com"><img border="0" src="images/oscommerce.png" title="osCommerce Online Merchant v' . tep_get_version() . '" /></a><h1 class="p">osCommerce Online Merchant v' . tep_get_version() . '</h1></td>' .
                  '  </tr>' .
                  '</table>';
               echo $regs[1];
          } else {
            phpinfo();
          }

  }
?>
  </table>
  
<?php  

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>