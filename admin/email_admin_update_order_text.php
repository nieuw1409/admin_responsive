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

     	  $admin_update_order=$_POST['admin_update_order'][$languages_id];
		  $admin_update_title=$_POST['admin_update_title'][$languages_id];

	      if (EMAIL_USE_HTML == 'true'){	

		     $admin_update_order = str_replace("&lt;-", "<-", $admin_update_order);
		     $admin_update_order = str_replace("-&gt;", "->", $admin_update_order);
		     //$admin_update_order = preg_replace('/\r\n|\r|\n/', ' ', $admin_update_order);

 		     if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $admin_update_order . "', eorder_title = '" . $admin_update_title . "' where eorder_text_id = '" . _SYS_EMAIL_HTML_ORDER_UPDATE . "' and language_id='" . $languages_id . "' and stores_id='" . $multi_stores_id . "'")) {
               $messageStack->add(SUCCESS_EMAIL_ORDER_TEXT, 'success');
             } else {
   			   $messageStack->add(ERROR_EMAIL_ORDER_TEXT, 'error'); 
    	     } 
          } else {
 		     if (tep_db_query("update " . TABLE_EMAIL_ORDER_TEXT . " set eorder_text_one = '" . $admin_update_order . "', eorder_title = '" . $admin_update_title . "' where eorder_text_id = '" . _SYS_EMAIL_NORMAL_ORDER_UPDATE . "' and language_id='" . $languages_id . "' and stores_id='" . $multi_stores_id . "'")) {
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
                  <li class="list-group-item"><-SYS_UPDATE_STATUS->               <?php echo ' :  ' . POPUP_UPDATE_STATUS ?></li>
                  <li class="list-group-item"><-SYS_UPDATE_CUSTOMER_NAME->        <?php echo ' :  ' . POPUP_UPDATE_CUST_NAME ?></li>	
                  <li class="list-group-item"><-SYS_UPDATE_CUSTOMER_EMAIL->       <?php echo ' :  ' . POPUP_UPDATE_CUST_EMAIL ?></li>					  
                  <li class="list-group-item"><-SYS_UPDATE_ORDER_PURCHASED->      <?php echo ' :  ' . POPUP_UPDATE_DATE_ORDER_BUY ?></li>				    		  
              </ul> 
          </div> 					
		  <div class="col-md-6 col-xs-12">
              <ul class="list-group">
                  <li class="list-group-item"><-SYS_UPDATE_COMMENTS->             <?php echo ' :  ' . POPUP_UPDATE_COMMENTS ?></li>				   
                  <li class="list-group-item"><-SYS_UPDATE_INVOICE_ID->           <?php echo ' :  ' . POPUP_INVOICE_ID ?></li>
				  
                  <li class="list-group-item"><-SYS_UPDATE_TRATRA_NR->            <?php echo ' :  ' . POPUP_UPDATE_TRATRA_NR ?></li>	
                  <li class="list-group-item"><-SYS_UPDATE_TRATRA_URL->           <?php echo ' :  ' . POPUP_UPDATE_TRATRA_URL ?></li>					  
                  <li class="list-group-item"><-SYS_UPDATE_TRATRA_PCODE->         <?php echo ' :  ' . POPUP_UPDATE_TRATRA_PCODE ?></li>				   			   	  
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
    			$contents_edit_new_order_admin       .=  tep_draw_bs_form('admin_update_order_status_form', FILENAME_ADMIN_EMAIL_UPD_ORDER_TEXT,'action=update', 'post', '', 'id_form_updata_order_status_admin_description')  ;				 
				$contents_edit_new_order_admin       .=  PHP_EOL . '  <!-- Nav tabs update  order  status admin text admin edit email  -->' . PHP_EOL ;						
				$contents_edit_new_order_admin       .=  '<div role="tabpanel" id="tab_edit_update_order_status_admin_text_description">'. PHP_EOL  ;			
				
				$contents_edit_new_order_admin_tab_links .=  '  <ul class="nav nav-tabs" role="tablist" id="tab_edit_update_order_status_admin_text_description">'. PHP_EOL  ;

				$contents_edit_new_order_admin_tab_tabs .=  '  <!-- Tab panes -->'. PHP_EOL  ;
				$contents_edit_new_order_admin_tab_tabs .=  '  <div class="tab-content" id="tab_edit_update_order_status_admin_text_description">'. PHP_EOL  ;	
				
				$active_tab = 'active' ;
				
		        $languages = tep_get_languages();				
				for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { 
				
				   $languages_id=$languages[$i]['id'];	
		
                   if (EMAIL_USE_HTML == 'true'){
                      $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_HTML_ORDER_UPDATE . "' AND language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");
                   } else {    
                      $sql=tep_db_query("SELECT * FROM " . TABLE_EMAIL_ORDER_TEXT . " where eorder_text_id = '" . _SYS_EMAIL_NORMAL_ORDER_UPDATE . "' AND language_id = '" . $languages_id . "' and stores_id='" . $multi_stores_id . "'");
                   }   
				   $row=tep_db_fetch_array($sql);
				   $text  = $row['eorder_text_one'];	
				   $title = $row['eorder_title'];			
				
                 			
				   $contents_edit_new_order_admin_tab_links   .= '    <li class="'. $active_tab . '"><a href="#update_order_status_admin_update_text_edit_description' . $languages[$i]['name'] . '" aria-controls="' . $languages[$i]['name'] . '" role="tab" data-toggle="tab" id="tab_edit_update_order_status_admin_text_description">' .
															 tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], '', 'SSL'), $languages[$i]['name'], NULL, NULL,NULL, false ) .  "  " . $languages[$i]['name'] . '</a></li>' . PHP_EOL ;

                   $contents_edit_new_order_admin_tab_content  = '                 <div class="form-group">' . PHP_EOL;	  
                   $contents_edit_new_order_admin_tab_content .=                        tep_draw_bs_input_field('admin_update_title[' . $languages[$i]['id'] . ']', $title, TEXT_EMAIL_SUBJECT, 'id_input_cu_title' , 'col-sm-2', 'col-sm-10', 'left' )	;
	               $contents_edit_new_order_admin_tab_content .= '                 </div>' . PHP_EOL;				   			   
	               $contents_edit_new_order_admin_tab_content .= '                 <div class="clearfix"></div>' . PHP_EOL;				   
	               $contents_edit_new_order_admin_tab_content .= '                 <br />' . PHP_EOL;					   
 
                   if (EMAIL_USE_HTML == 'true'){																	 
				      $contents_edit_new_order_admin_tab_content .= '              <div class="form-group">' . PHP_EOL .										 
													   '                                 <div class="col-xs-12">' . tep_draw_textarea_ckeditor('admin_update_order[' . $languages[$i]['id'] . ']', 'soft', '140', '40', $text, 'id = "products_description[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
											           '                                 </div>' . PHP_EOL .
											           '                              </div>' . PHP_EOL;			
                   } else {
				      $contents_edit_new_order_admin_tab_content .= '              <div class="form-group">' . PHP_EOL .										 
													   '                                 <div class="col-xs-12">' . tep_draw_textarea_field('admin_update_order[' . $languages[$i]['id'] . ']', 'soft', '140', '40', $text, 'id = "products_description[' . $languages[$i]['id'] . ']" class="ckeditor"') . PHP_EOL .
											           '                                 </div>' . PHP_EOL .
											           '                              </div>' . PHP_EOL;		
                   }				   
											 
				   $contents_edit_new_order_admin_tab_tabs      .=  '    <div  role="tabpanel" class="tab-pane ' . $active_tab . '" id="update_order_status_admin_update_text_edit_description' . $languages[$i]['name'] . '">' . $contents_edit_new_order_admin_tab_content .'</div>'. PHP_EOL  ;					  
				   $active_tab = '' ;			  
				}		

	 
				
				$contents_edit_new_order_admin_tab_links .=  '  </ul>'. PHP_EOL  ;	
				$contents_edit_new_order_admin_tab_tabs  .=  '  </div>'. PHP_EOL  ; 			
	 

//				$contents_edit_new_order_admin       .=	 '<p>' . TEXT_PRODUCTS_DESCRIPTION . '</p>'. PHP_EOL ;
				$contents_edit_new_order_admin       .=	 			$contents_edit_new_order_admin_tab_links . PHP_EOL ;
				$contents_edit_new_order_admin       .=			    $contents_edit_new_order_admin_tab_tabs  . PHP_EOL ;
				$contents_edit_new_order_admin       .=  '</div>'. PHP_EOL  ;		// end div <div role="tabpanel" id="tab_prod_edit_des	
				
				
	            $contents_edit_new_order_admin       .= '<div class="clearfix"></div>' . PHP_EOL;				   
	            $contents_edit_new_order_admin       .= '<br />' . PHP_EOL;	
				
				$contents_edit_new_order_admin       .=   tep_draw_bs_button(IMAGE_SAVE, 'ok', null) ;
				
				$contents_edit_new_order_admin       .=  '</form>'. PHP_EOL  ;					
				$contents_edit_new_order_admin       .=  '<!-- end nav update  order status admin text admin edit email  -->'. PHP_EOL  ;			
                echo $contents_edit_new_order_admin ;
?>			
			  </tbody>
			</table>  
   </table>

<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>