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

  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
        $error = false;

        $store_logo = new upload('store_logo');
        $store_logo->set_extensions(array('png', 'gif', 'jpg'));
        $store_logo->set_destination(DIR_FS_CATALOG_IMAGES);

        if ($store_logo->parse()) {
//          $store_logo->set_filename('store_logo.png');

          if ($store_logo->save()) {
            $messageStack->add_session(SUCCESS_LOGO_UPDATED, 'success');
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = '" . tep_db_input($store_logo->filename) . "', last_modified = now() where configuration_value = '" . STORE_LOGO . "'");
            // Configuration Cache modification start
             require('includes/configuration_cache.php');
             // Configuration Cache modification end	            			
          } else {
            $error = true;
          }
		  
        } else {
          $error = true;
        }

        if ($error == false) {
          tep_redirect(tep_href_link(FILENAME_STORE_LOGO));
        }
		
        break;
    }
  }

  if (!tep_is_writable(DIR_FS_CATALOG_IMAGES)) {
    $messageStack->add(sprintf(ERROR_IMAGES_DIRECTORY_NOT_WRITEABLE, tep_href_link(FILENAME_SEC_DIR_PERMISSIONS)), 'error');
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
     <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1>
            <h2 class="col-xs-12 col-md-6"><?php echo TEXT_MULTI_STORE_NAME . $multi_stores ; ?></h2>			
		    <div class="clearfix"></div>
      </div><!-- page-header-->
      <div class="table-responsive">
        <table class="table table-condensed table-striped"> 
         <tbody>
		       <div class="col-xs-12 col-md-06"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . STORE_LOGO); ?><br /></div>
              <?php echo tep_draw_form('logo', FILENAME_STORE_LOGO, 'action=save', 'post', 'class="form-inline" role="form" enctype="multipart/form-data"'); ?>	
                 <div class="form-group"> 
                    <?php echo tep_draw_bs_file_field('store_logo', '', TEXT_LOGO_IMAGE, 'input_logo_name'  ); ?>
				 </div>
				 <div class="col-xs-12"> 
				   <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null, 'primary') ; ?>
                 </div>				   
				 <br />
				 <br />
              </form>	
              <div class="well">
                 <p> <?php echo TEXT_FORMAT_AND_LOCATION; ?> <br /><br />  <?php echo DIR_FS_CATALOG_IMAGES . STORE_LOGO; ?> </p>
              </div>			  
         </tbody>
       </table>		 
	 </div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>