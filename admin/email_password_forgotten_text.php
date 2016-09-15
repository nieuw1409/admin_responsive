<?php


  require('includes/application_top.php');

//   $sql=tep_db_query("SELECT * FROM configuration where configuration_key = 'EMAIL_USE_HTML'");

//   $configuration_group=tep_db_fetch_array($sql);
//   $configuration_group = $configuration_group["configuration_group_id"];          

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

    if (($action == 'update') && isset($_POST))  {

    	$languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $languages_id=$languages[$i]['id'];

     	  $admin_password=$_POST['admin_password'][$languages_id];
		  $admin_password_title=$_POST['admin_password_title'][$languages_id];

	      if (EMAIL_USE_HTML == 'true'){	

		     $admin_password = str_replace("&lt;-", "<-", $admin_password);
		     $admin_password = str_replace("-&gt;", "->", $admin_password);
		     $admin_password = preg_replace('/\r\n|\r|\n/', ' ', $admin_password);

 		     if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $admin_password . "', eorder_title = '" . $admin_password_title . "'  where eorder_text_id = '" . _SYS_EMAIL_HTML_PASSWORDFORGOTTEN . "' and language_id='" . $languages_id . "' and stores_id='" . $multi_stores_id . "'")) {
               $messageStack->add(SUCCESS_EMAIL_ORDER_TEXT, 'success');
             } else {
   			   $messageStack->add(ERROR_EMAIL_ORDER_TEXT, 'error'); 
    	     } 
          } else {
 		     if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $admin_password . "', eorder_title = '" . $admin_password_title . "'  where eorder_text_id = '" . _SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN . "' and language_id='" . $languages_id . "' and stores_id='" . $multi_stores_id . "'")) {
               $messageStack->add(SUCCESS_EMAIL_ORDER_TEXT, 'success');
             } else {
   			   $messageStack->add(ERROR_EMAIL_ORDER_TEXT, 'error'); 
    	     }     	
  	      }  
        }
    } 

  require('includes/template_top.php');
?>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
		  <div class="col-md-6 col-xs-12">
              <ul class="list-group">
                  <li class="list-group-item"><-SYS_STORE_NAME->                  <?php echo ' :  ' . POPUP_STORENAME ?></li>
                  <li class="list-group-item"><-SYS_RESET_PASSWORD_URL->          <?php echo ' :  ' . POPUP_SECURE_LINK ?></li>		    		  
              </ul> 
          </div> 					
		  <div class="col-md-6 col-xs-12">
              <ul class="list-group">
                  <li class="list-group-item"><-SYS_CUSTOMER_FIRSTNAME->          <?php echo ' :  ' . POPUP_CUSTOMER_FIRST ?></li>				   
                  <li class="list-group-item"><-SYS_CUSTOMER_LASTNAME->           <?php echo ' :  ' . POPUP_CUSTOMER_LAST ?></li>	   			   	  
              </ul> 
          </div> 
          <div class="clearfix"></div>		  		  
		  <hr>

            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                </tr>
              </thead>
              <tbody>				
<?php			                  
    			$contents_edit_password_forgotten       .=  tep_draw_bs_form('password_forgottenform', FILENAME_EMAIL_PASSWORD_FORGOTTEN_TEXT,'action=update', 'post', '', 'id_form_password_forgotten_admin_description')  ;				 
				$contents_edit_password_forgotten       .=  PHP_EOL . '  <!-- Nav tabs password forgotten admin edit email  -->' . PHP_EOL ;						
				$contents_edit_password_forgotten       .=  '<div role="tabpanel" id="tab_edit_password_forgotten_text_description">'. PHP_EOL  ;			
				
				$contents_edit_password_forgotten_tab_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_password_forgotten_text_description">'. PHP_EOL  ;

				$contents_edit_password_forgotten_tab_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
				$contents_edit_password_forgotten_tab_tabs .=  '  <div class="tab-content" id="tab_edit_password_forgotten_text_description">'. PHP_EOL  ;	
				
				$active_tab = 'active' ;
				
		        $languages = tep_get_languages();				
				for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
				
				   $languages_id=$languages[$i]['id'];	
		
                   if (EMAIL_USE_HTML == 'true'){
                      $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_PASSWORDFORGOTTEN . "'  AND language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");
                   } else {    
                      $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN . "'  AND language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");
                   }  
				   $row=tep_db_fetch_array($sql);
				   $text  = $row['eorder_text_one'];	
				   $title = $row['eorder_title'];			
				
                 			
				   $contents_edit_password_forgotten_tab_links   .= '    <li class="'. $active_tab . '"><a href="#update_password_forgotten_text_edit_description' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_edit_password_forgotten_text_description">' .
															 tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

                   $contents_edit_password_forgotten_tab_content  = '                 <div class="form-group">' . PHP_EOL;	  
                   $contents_edit_password_forgotten_tab_content .=                        tep_draw_bs_input_field('admin_password_title[' . $languages[$i]['id'] . ']', $title, TEXT_EMAIL_SUBJECT, 'id_input_cu_title' , 'col-sm-2', 'col-sm-10', 'left' )	;
	               $contents_edit_password_forgotten_tab_content .= '                 </div>' . PHP_EOL;				   			   
	               $contents_edit_password_forgotten_tab_content .= '                 <div class="clearfix"></div>' . PHP_EOL;				   
	               $contents_edit_password_forgotten_tab_content .= '                 <br />' . PHP_EOL;					   
 
                   if (EMAIL_USE_HTML == 'true'){																	 
				      $contents_edit_password_forgotten_tab_content .= '              <div class="form-group">' . PHP_EOL .										 
													   '                                 <div class="col-xs-12">' . tep_draw_textarea_ckeditor('admin_password[' . $languages[$i]['id'] . ']', 'soft', '140', '40', $text, 'id = "products_description[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
											           '                                 </div>' . PHP_EOL .
											           '                              </div>' . PHP_EOL;			
                   } else {
				      $contents_edit_password_forgotten_tab_content .= '              <div class="form-group">' . PHP_EOL .										 
													   '                                 <div class="col-xs-12">' . tep_draw_textarea_field('admin_password[' . $languages[$i]['id'] . ']', 'soft', '140', '40', $text, 'id = "products_description[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
											           '                                 </div>' . PHP_EOL .
											           '                              </div>' . PHP_EOL;		
                   }				   
											 
				   $contents_edit_password_forgotten_tab_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="update_password_forgotten_text_edit_description' . $languages[$i]['name'] . '">' . $contents_edit_password_forgotten_tab_content .'</div>'. PHP_EOL  ;					  
				   $active_tab = '' ;			  
				}		

	 
				
				$contents_edit_password_forgotten_tab_links .=  '  </ul>'. PHP_EOL  ;	
				$contents_edit_password_forgotten_tab_tabs  .=  '  </div>'. PHP_EOL  ; 			
	 

//				$contents_edit_password_forgotten       .=	 '<p>' . TEXT_PRODUCTS_DESCRIPTION . '</p>'. PHP_EOL ;
				$contents_edit_password_forgotten       .=	 			$contents_edit_password_forgotten_tab_links . PHP_EOL ;
				$contents_edit_password_forgotten       .=			    $contents_edit_password_forgotten_tab_tabs  . PHP_EOL ;
				$contents_edit_password_forgotten       .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
				
				
	            $contents_edit_password_forgotten       .= '<div class="clearfix"></div>' . PHP_EOL;				   
	            $contents_edit_password_forgotten       .= '<br />' . PHP_EOL;	
				
				$contents_edit_password_forgotten       .=   tep_draw_bs_button(IMAGE_SAVE, 'ok', null) ;
				
				$contents_edit_password_forgotten       .=  '</form>'. PHP_EOL  ;					
				$contents_edit_password_forgotten       .=  '<!-- end nav tabs password forgotten admin edit email -->'. PHP_EOL  ;			
                echo $contents_edit_password_forgotten ;
?>			
			  </tbody>
			</table>  
   </table>

<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>