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
		  $create_cust_admin_title=$_POST['create_cust_admin_title'][$languages_id];

     	  $create_cust_admin=$_POST['create_cust_admin'][$languages_id];

	      if (EMAIL_USE_HTML == 'true'){	

		     $create_cust_admin = str_replace("&lt;-", "<-", $create_cust_admin);
		     $create_cust_admin = str_replace("-&gt;", "->", $create_cust_admin);
		     $create_cust_admin = preg_replace('/\r\n|\r|\n/', ' ', $create_cust_admin);

 		     if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $create_cust_admin . "', eorder_title = '" . $create_cust_admin_title . "' 
			     where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN . "' and language_id='" . $languages_id . "' and stores_id='" . $multi_stores_id . "'")) {
               $messageStack->add(SUCCESS_EMAIL_ORDER_TEXT, 'success');
             } else {
   			   $messageStack->add(ERROR_EMAIL_ORDER_TEXT, 'error'); 
    	     } 
          } else {
 		     if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $create_cust_admin . "', eorder_title = '" . $create_cust_admin_title . "' 
			      where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN . "' and language_id='" . $languages_id . "' and stores_id='" . $multi_stores_id . "'")) {
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
                  <li class="list-group-item"><-SYS_STORE_NAME->              <?php echo ' :  ' . POPUP_CA_STORENAME ?></li>
                  <li class="list-group-item"><-SYS_CREATE_ACC_ID->           <?php echo ' :  ' . POPUP_CA_ID ?></li>
                  <li class="list-group-item"><-SYS_CREATE_ACC_GENDER->       <?php echo ' :  ' . POPUP_CA_GENDER ?></li>				   
                  <li class="list-group-item"><-SYS_CREATE_ACC_FIRSTNAME->    <?php echo ' :  ' . POPUP_CA_FIRSTNAME ?></li>
                  <li class="list-group-item"><-SYS_CREATE_ACC_LASTNAME->     <?php echo ' :  ' . POPUP_CA_LASTNAME ?></li>				  
                  <li class="list-group-item"><-SYS_CREATE_ACC_STREET->       <?php echo ' :  ' . POPUP_CA_STREET ?></li>	
                  <li class="list-group-item"><-SYS_CREATE_ACC_POSTCODE->     <?php echo ' :  ' . POPUP_CA_POSTCODE ?></li>	
                  <li class="list-group-item"><-SYS_CREATE_ACC_CITY->         <?php echo ' :  ' . POPUP_CA_CITY ?></li>					  
                  <li class="list-group-item"><-SYS_CUSTOMER_COUNTRY->        <?php echo ' :  ' . POPUP_CA_COUNTRY ?></li>			  
              </ul> 
          </div> 					
		  <div class="col-md-6 col-xs-12">
              <ul class="list-group">
                  <li class="list-group-item"><-SYS_CREATE_ACC_SUBURB->       <?php echo ' :  ' . POPUP_CA_SUBURB ?></li>				  
                  <li class="list-group-item"><-SYS_CREATE_ACC_STATE->        <?php echo ' :  ' . POPUP_CA_STATE ?></li>
                  <li class="list-group-item"><-SYS_CREATE_ACC_BIRTHDATE->    <?php echo ' :  ' . POPUP_CA_BIRTHDATE ?></li>				  
                  <li class="list-group-item"><-SYS_CREATE_ACC_COMPANY->      <?php echo ' :  ' . POPUP_CA_COMPANY ?></li>	
                  <li class="list-group-item"><-SYS_CREATE_ACC_NEWSLETTER->   <?php echo ' :  ' . POPUP_CA_NEWSLETTER ?></li>						  
                  <li class="list-group-item"><-SYS_CREATE_ACC_EMAILADDRESS-> <?php echo ' :  ' . POPUP_CA_EMAILADDRESS ?></li>	
                  <li class="list-group-item"><-SYS_CREATE_ACC_TELEPHONE->    <?php echo ' :  ' . POPUP_CA_TELEPHONE ?></li>	
                  <li class="list-group-item"><-SYS_CREATE_ACC_FAX->          <?php echo ' :  ' . POPUP_CA_FAX ?></li> 			  
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
    			$contents_edit_new_customer_admin       .=  tep_draw_bs_form('create_cust_adminform', FILENAME_ADMIN_EMAIL_CREATE_CUST_TEXT,'action=update', 'post', '', 'id_form_new_cust_admin_description')  ;				 
				$contents_edit_new_customer_admin       .=  PHP_EOL . '  <!-- Nav tabs new customer text admin edit email  -->' . PHP_EOL ;						
				$contents_edit_new_customer_admin       .=  '<div role="tabpanel" id="tab_edit_new_customer_admin_text_description">'. PHP_EOL  ;			
				
				$contents_edit_new_customer_admin_tab_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_new_customer_admin_text_description">'. PHP_EOL  ;

				$contents_edit_new_customer_admin_tab_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
				$contents_edit_new_customer_admin_tab_tabs .=  '  <div class="tab-content" id="tab_edit_new_customer_admin_text_description">'. PHP_EOL  ;	
				
				$active_tab = 'active' ;
				
		        $languages = tep_get_languages();				
				for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
				
				   $languages_id=$languages[$i]['id'];	
		
                   if (EMAIL_USE_HTML == 'true'){
                      $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN . "' AND language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");
                   } else {    
                      $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN . "' AND language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");
                   }   
				   $row=tep_db_fetch_array($sql);
				   $text  = $row['eorder_text_one'];	
				   $title = $row['eorder_title'];			
				
                 			
				   $contents_edit_new_customer_admin_tab_links   .= '    <li class="'. $active_tab . '"><a href="#new_cust_admin_text_edit_description' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_edit_new_customer_admin_text_description">' .
															 tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

                   $contents_edit_new_customer_admin_tab_content  = '                 <div class="form-group">' . PHP_EOL;	  
                   $contents_edit_new_customer_admin_tab_content .=                        tep_draw_bs_input_field('create_cust_admin_title[' . $languages[$i]['id'] . ']', $title, TEXT_EMAIL_SUBJECT, 'id_input_cu_title' , 'col-sm-2', 'col-sm-10', 'left' )	;
	               $contents_edit_new_customer_admin_tab_content .= '                 </div>' . PHP_EOL;				   			   
	               $contents_edit_new_customer_admin_tab_content .= '                 <div class="clearfix"></div>' . PHP_EOL;				   
	               $contents_edit_new_customer_admin_tab_content .= '                 <br />' . PHP_EOL;					   
 
                   if (EMAIL_USE_HTML == 'true'){																	 
				      $contents_edit_new_customer_admin_tab_content .= '              <div class="form-group">' . PHP_EOL .										 
													   '                                 <div class="col-xs-12">' . tep_draw_textarea_ckeditor('create_cust_admin[' . $languages[$i]['id'] . ']', 'soft', '140', '40', $text, 'id = "products_description[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
											           '                                 </div>' . PHP_EOL .
											           '                              </div>' . PHP_EOL;			
                   } else {
				      $contents_edit_new_customer_admin_tab_content .= '              <div class="form-group">' . PHP_EOL .										 
													   '                                 <div class="col-xs-12">' . tep_draw_textarea_field('create_cust_admin[' . $languages[$i]['id'] . ']', 'soft', '140', '40', $text, 'id = "products_description[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
											           '                                 </div>' . PHP_EOL .
											           '                              </div>' . PHP_EOL;		
                   }				   
											 
				   $contents_edit_new_customer_admin_tab_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="new_cust_admin_text_edit_description' . $languages[$i]['name'] . '">' . $contents_edit_new_customer_admin_tab_content .'</div>'. PHP_EOL  ;					  
				   $active_tab = '' ;			  
				}		

	 
				
				$contents_edit_new_customer_admin_tab_links .=  '  </ul>'. PHP_EOL  ;	
				$contents_edit_new_customer_admin_tab_tabs  .=  '  </div>'. PHP_EOL  ; 			
	 

//				$contents_edit_new_customer_admin       .=	 '<p>' . TEXT_PRODUCTS_DESCRIPTION . '</p>'. PHP_EOL ;
				$contents_edit_new_customer_admin       .=	 			$contents_edit_new_customer_admin_tab_links . PHP_EOL ;
				$contents_edit_new_customer_admin       .=			    $contents_edit_new_customer_admin_tab_tabs  . PHP_EOL ;
				$contents_edit_new_customer_admin       .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
				
				
	            $contents_edit_new_customer_admin       .= '<div class="clearfix"></div>' . PHP_EOL;				   
	            $contents_edit_new_customer_admin       .= '<br />' . PHP_EOL;	
				
				$contents_edit_new_customer_admin       .=   tep_draw_bs_button(IMAGE_SAVE, 'ok', null) ;
				
				$contents_edit_new_customer_admin       .=  '</form>'. PHP_EOL  ;					
				$contents_edit_new_customer_admin       .=  '<!-- end nav new customer text admin edit email  -->'. PHP_EOL  ;			
                echo $contents_edit_new_customer_admin ;
?>			
			  </tbody>
			</table>  
   </table>

<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>