<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $login_request = true;

  require('includes/application_top.php');
  require('includes/functions/password_funcs.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

// prepare to logout an active administrator if the login page is accessed again
  if (tep_session_is_registered('admin')) {
    $action = 'logoff';
  }

  if (tep_not_null($action)) {
    switch ($action) {
      case 'process':
        if (tep_session_is_registered('redirect_origin') && isset($redirect_origin['auth_user']) && !isset($_POST['username'])) {
          $username = tep_db_prepare_input($redirect_origin['auth_user']);
          $password = tep_db_prepare_input($redirect_origin['auth_pw']);
        } else {
          $username = tep_db_prepare_input($_POST['username']);
          $password = tep_db_prepare_input($_POST['password']);
        }

        $actionRecorder = new actionRecorderAdmin('ar_admin_login', null, $username);

        if ($actionRecorder->canPerform()) {
          $check_query = tep_db_query("select id, user_name, user_password from " . TABLE_ADMINISTRATORS . " where user_name = '" . tep_db_input($username) . "'");

          if (tep_db_num_rows($check_query) == 1) {
            $check = tep_db_fetch_array($check_query);

            if (tep_validate_password($password, $check['user_password'])) {
// migrate old hashed password to new phpass password
              if (tep_password_type($check['user_password']) != 'phpass') {
                tep_db_query("update " . TABLE_ADMINISTRATORS . " set user_password = '" . tep_encrypt_password($password) . "' where id = '" . (int)$check['id'] . "'");
              }

              tep_session_register('admin');

              $admin = array('id' => $check['id'],
                             'username' => $check['user_name']);

              $actionRecorder->_user_id = $admin['id'];
              $actionRecorder->record();

              if (tep_session_is_registered('redirect_origin')) {
                $page = $redirect_origin['page'];
                $get_string = '';

                if (function_exists('http_build_query')) {
                  $get_string = http_build_query($redirect_origin['get']);
                }

                tep_session_unregister('redirect_origin');

                tep_redirect(tep_href_link($page, $get_string));
              } else {
                tep_redirect(tep_href_link(FILENAME_DEFAULT));
              }
            }
          }

          if (isset($_POST['username'])) {
            $messageStack->add(ERROR_INVALID_ADMINISTRATOR, 'error');
          }
        } else {
          $messageStack->add(sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES') ? (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES : 5)));
        }

        if (isset($_POST['username'])) {
          $actionRecorder->record(false);
        }

        break;

      case 'logoff':
        tep_session_unregister('admin');

        if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {
          tep_session_register('auth_ignore');
          $auth_ignore = true;
        }

        tep_redirect(tep_href_link(FILENAME_DEFAULT));

        break;

      case 'create':
        $check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " limit 1");

        if (tep_db_num_rows($check_query) == 0) {
          $username = tep_db_prepare_input($_POST['username']);
          $password = tep_db_prepare_input($_POST['password']);

          if ( !empty($username) ) {
            tep_db_query("insert into " . TABLE_ADMINISTRATORS . " (user_name, user_password) values ('" . tep_db_input($username) . "', '" . tep_db_input(tep_encrypt_password($password)) . "')");
          }
        }

        tep_redirect(tep_href_link(FILENAME_LOGIN));

        break;
    }
  }

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

  $admins_check_query = tep_db_query("select id from " . TABLE_ADMINISTRATORS . " limit 1");
  if (tep_db_num_rows($admins_check_query) < 1) {
    $messageStack->add(TEXT_CREATE_FIRST_ADMINISTRATOR, 'warning');
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
          <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4" style="margin-top:8%; margin-bottom: 4%;">
           <div class="page-header">
              <h1 class="col-md-8" style="padding-top:0"><?php echo HEADING_TITLE; ?></h1>

<?php
  if (sizeof($languages_array) > 1) {
?>

<?php 
 /*       echo tep_draw_bs_form('adminlanguage', FILENAME_DEFAULT, 'post', 'get', 'role="form"',  'id_login_language') . PHP_EOL  ;

        echo '  <div class="form-group">'  . PHP_EOL  ;		
//		echo        tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onchange="this.form.submit();"')  . PHP_EOL  ;
        echo        tep_draw_bs_pull_down_menu('language', $languages_array, $languages_selected, '', 'id_sel_lang_login', null, ' selectpicker show-tick ', null, null, 'onchange="this.form.submit();"')	 ;	
        echo '  </div>' . PHP_EOL  ;		
        echo '  <div class="form-group">'  . PHP_EOL  ;			
		echo        tep_hide_session_id() . PHP_EOL  ;
        echo '  </div>' . PHP_EOL  ;			
        echo '</form>'  . PHP_EOL  ;
*/			                           $contents            .= '               ' . tep_draw_bs_form('adminlanguage', FILENAME_DEFAULT, '', 'get', 'class="form-inlinehorizontal" role="form"',  'id_login_language') . PHP_EOL;	
		
								       $contents            .= '                       <div class="form-group">' . PHP_EOL;	// tep_draw_pull_down_menu('options_id', $options_array, $attributes_values['options_id'])
								       $contents            .= '                         ' . tep_draw_bs_pull_down_menu('language', $languages_array,  $languages_selected, null, 'inputCountry_Name', 'col-xs-12', ' selectpicker show-tick ', null, 'left', 'onchange="this.form.submit();"', false, false)  . PHP_EOL;	
								       $contents            .= '                       </div>' . PHP_EOL;
		                               $contents            .= '                   </form>' . PHP_EOL;									   
echo $contents ; 			
	
?>

<?php
  }
?>
              <div class="clearfix"></div>
            </div>		  
          <div id="login" class="well">
 
<?php
  echo '            '. tep_draw_form('login', FILENAME_LOGIN, ((tep_db_num_rows($admins_check_query) > 0) ? 'action=process' : 'action=create')); ?>

              <div class="form-group">
                <label class="sr-only" for="username">TEXT_USERNAME</label>
                <div class="input-group">
                  <div class="input-group-addon"><?php echo tep_glyphicon('user') ; ?></div> <!-- glyphicon glyphicon-user -->
                  <?php echo tep_draw_input_field('username', NULL, 'autofocus="autofocus" placeholder="' . TEXT_USERNAME . '"'); ?>
                </div>
              </div>
              <div class="form-group">
                <label class="sr-only" for="password">TEXT_PASSWORD</label>
                <div class="input-group">
                  <div class="input-group-addon"><?php echo tep_glyphicon('lock') ; ?></div> <!-- <i class="glyphicon glyphicon-lock"></i> -->
                  <?php echo tep_draw_password_field('password', NULL, 'placeholder="' . TEXT_PASSWORD . '"'); ?>
                </div>
              </div>
              <div class="form-group">
                <?php echo tep_draw_bs_button(((tep_db_num_rows($admins_check_query) > 0) ? BUTTON_LOGIN : BUTTON_CREATE_ADMINISTRATOR), 'log-in', Null, Null, Null, 'btn-primary pull-right'); ?>
              </div>
              <div class="clearfix"></div>

            </form>
            </div>
          </div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
