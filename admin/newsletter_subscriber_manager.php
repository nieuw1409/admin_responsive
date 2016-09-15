<?php
/*
  $Id: newsletter_subscriber_manager.php.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
 

  if (tep_not_null($action)) {
    switch ($action) {
     case 'update':
      $subscription_id = tep_db_prepare_input($HTTP_GET_VARS['aID']);
      $subscription_email_address = tep_db_prepare_input($HTTP_POST_VARS['subscription_address_email']);
      $subscription_name = tep_db_prepare_input($HTTP_POST_VARS['subscription_name']);	  
      $subscription_newsletter = tep_db_prepare_input($HTTP_POST_VARS['subscription_newsletter']);
      $subscription_group_id = tep_db_prepare_input($HTTP_POST_VARS['subscription_customers_group_id']);
      $subscription_stores_id = tep_db_prepare_input($HTTP_POST_VARS['subscription_stores_id']);	  

      $sql_data_array = array('subscription_email_address' => $subscription_email_address,
                              'subscription_name' => $subscription_name,	  
                              'subscription_newsletter' => $subscription_newsletter,
							  'subscription_customers_group_id' =>  $subscription_group_id,
							  'subscription_stores_id' =>  $subscription_stores_id);

        tep_db_perform(TABLE_NEWSLETTER_SUBSCRIPTION, $sql_data_array, 'update', "subscription_id = '" . tep_db_input($subscription_id) . "'");

//        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$subscription_newsletter . "' where customers_email_address = '" . $subscription_email_address . "'");

        tep_redirect(tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&aID=' . $subscription_id . '&search=' . $HTTP_GET_VARS['search'] ));
        break;

     case 'new':
      $subscription_id = tep_db_prepare_input($HTTP_GET_VARS['aID']);
      $subscription_email_address = tep_db_prepare_input($HTTP_POST_VARS['subscription_address_email']);
      $subscription_name = tep_db_prepare_input($HTTP_POST_VARS['subscription_name']);	  
      $subscription_newsletter = tep_db_prepare_input($HTTP_POST_VARS['subscription_newsletter']);
      $subscription_group_id = tep_db_prepare_input($HTTP_POST_VARS['subscription_customers_group_id']);
      $subscription_stores_id = tep_db_prepare_input($HTTP_POST_VARS['subscription_stores_id']);	  

      $sql_data_array = array('subscription_email_address' => $subscription_email_address,
                              'subscription_name' => $subscription_name,	  
                              'subscription_newsletter' => $subscription_newsletter,
							  'subscription_customers_group_id' =>  $subscription_group_id,
							  'subscription_stores_id' =>  $subscription_stores_id,
							  'subscription_date_creation' => 'now()');
      
      tep_db_perform(TABLE_NEWSLETTER_SUBSCRIPTION, $sql_data_array);

      $subscription_id = tep_db_insert_id();

      tep_redirect(tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&aID=' . $subscription_id . '&search=' . $HTTP_GET_VARS['search'] ));
      break;
		
      case 'deleteconfirm':
        $subscription_id = tep_db_prepare_input($HTTP_GET_VARS['aID']);
        $subscription_email_address = tep_db_prepare_input($HTTP_GET_VARS['Aemail']);

        tep_db_query("delete from " . TABLE_NEWSLETTER_SUBSCRIPTION . " where subscription_id = '" . $subscription_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '0' where customers_email_address = '" . $subscription_email_address . "'");

        tep_redirect(tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID', 'action', 'page')))); 
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');

?>
  <div class="table-responsive">
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE ; ?></h1>
            <div class="col-md-6">
              <div class="row">              
			    <div class="col-md-10 col-xs-8">
<?php
                  echo '' . tep_draw_bs_form('search', FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, '', 'get', 'role="form" class="form-horizontal"'). PHP_EOL .
//                       '      <label class="col-xs-3" for="search">' . HEADING_TITLE_SEARCH . '</label>' . PHP_EOL .  
//                       '      '. tep_draw_input_field('search','', 'placeholder="' . HEADING_TITLE_SEARCH . '"') . tep_hide_session_id() . PHP_EOL .
                       '      '. tep_draw_bs_input_field('search', $HTTP_GET_VARS['search'],HEADING_TITLE_SEARCH, 'id_input_search' , 'col-xs-3', 'col-xs-9', 'left', HEADING_TITLE_SEARCH ) . PHP_EOL .
                       '      '. tep_hide_session_id() . PHP_EOL .					   
	                   '    </form>' . PHP_EOL ; 
?>
                </div>  					   
			    <div class="col-md-2 col-xs-4"> 
<?php				
                   if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {

		             echo tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER)); 
                   }		   				   
?>
                </div> 
              </div>
            </div>
            <div class="clearfix"></div>
          </div><!-- page-header-->
<?php	
          switch ($HTTP_GET_VARS['listing']) {
              case "id-asc":
              $order = " subscription_id";
	          break;
	          case "cg_name":
              $order = " subscription_customers_group_id, subscription_name";
	          break;
              case "cg_name-desc":
              $order = "subscription_customers_group_id DESC, subscription_name";
              break;
              case "name":
              $order = " subscription_name";
              break;
              case "name-desc":
              $order = " subscription_name DESC";
              break;
              case "active":
              $order = " subscription_newsletter";
              break;
              case "active-desc":
              $order = " subscription_newsletter DESC";
              break;			  
              case "store_name":
              $order = " subscription_stores_id";
              break;
              case "store_name-desc":
              $order = " subscription_stores_id DESC";
              break;	
              default:
              $order = " subscription_id DESC";
          }	
?>	  
            <div class="table-responsive">
             <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=email'  );   ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo TABLE_HEADING_EMAIL;                  ?><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=email-desc');     ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>
                   <th><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=name' );     ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo TABLE_HEADING_NAME;                   ?><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=name-desc');      ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>
                   <th class="hidden-xs"><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=cg_name'  ); ?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo TABLE_HEADING_CUSTOMERS_GROUPS;       ?><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=cg_name-desc');   ?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>
                   <th class="hidden-xs"><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=store_name');?>"><?php echo tep_glyphicon('sort-by-alphabet' ); ?></a><?php echo TABLE_HEADING_STORES;                 ?><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=store_name-desc');?>"><?php echo tep_glyphicon('sort-by-alphabet-alt' ); ?></a></th>				   
                   <th class="hidden-xs"><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=id-asc'   ); ?>"><?php echo tep_glyphicon('sort-by-order' );    ?></a><?php echo TABLE_HEADING_ACCOUNT_CREATED;        ?><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=id-desc');        ?>"><?php echo tep_glyphicon('sort-by-order-alt' ); ?></a></th>
                   <th><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=active'   ); ?>"><?php echo tep_glyphicon('triangle-top' );     ?></a><?php echo TABLE_HEADING_ACTIVE;                 ?><a href="<?php echo tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('listing','action', 'cID')) . 'listing=active-desc');    ?>"><?php echo tep_glyphicon('triangle-bottom' ); ?></a></th>
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>
                </tr>
              </thead>
              <tbody>
<?php			  
                $search = '';
                 if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
                    $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
                    $search = "where subscription_name like '%" . $keywords . "%' or subscription_email_address like '%" . $keywords . "%'";	  
                 }
// BOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer
				 $newsletter_query_raw = "select  ns.subscription_id, ns.subscription_email_address, ns.subscription_name, ns.subscription_date_creation, ns.subscription_newsletter, ns.subscription_customers_group_id, ns.subscription_stores_id, cg.customers_group_name, st.stores_name 
				                                       from " . TABLE_NEWSLETTER_SUBSCRIPTION . " ns left join " . TABLE_CUSTOMERS_GROUPS . " cg on ns.subscription_customers_group_id = cg.customers_group_id left join " . TABLE_STORES . " st on ns.subscription_stores_id = st.stores_id " . $search . " order by" . $order ;

				$newsletter_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsletter_query_raw, $newsletter_query_numrows);
				$newsletter_query = tep_db_query($newsletter_query_raw);
				while ($newsletter_values = tep_db_fetch_array($newsletter_query)) {

				  if ((!isset($HTTP_GET_VARS['aID']) || (isset($HTTP_GET_VARS['aID']) && ($HTTP_GET_VARS['aID'] == $newsletter_values['subscription_id']))) && !isset($cInfo)) {
					$cInfo_array = $newsletter_values;
					$cInfo = new objectInfo($cInfo_array);
				  }

				  if (isset($cInfo) && is_object($cInfo) && ($newsletter_values['subscription_id'] == $cInfo->subscription_id)) {
					echo '          <tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID', 'action')) . 'aID=' . $cInfo->subscription_id . '&action=edit') . '\'">' . "\n";
				  } else {
					echo '          <tr                onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID')) . 'aID=' . $newsletter_values['subscription_id']) . '\'">' . "\n";
				  }	
?>				  
										 <td><?php echo $newsletter_values['subscription_email_address'] ;                   ?></td>
										 <td><?php echo $newsletter_values['subscription_name'] ;              ?></td>								 
										 <td class="hidden-xs"><?php echo $newsletter_values['customers_group_name'] ;             ?></td>							 
										 <td class="hidden-xs"><?php echo $newsletter_values['stores_name'] ;             ?></td>											 
										 <td class="hidden-xs"><?php echo tep_date_short($newsletter_values['subscription_date_creation']) ; ?></td>								  										 
										 <td>
<?php								 
                                           if ($newsletter_values['subscription_newsletter'] == '1') {
                                                echo tep_glyphicon('ok-sign', 'success')  ;
                                           } else {
                                                echo  tep_glyphicon('remove-sign', 'danger');
                                           }
?>
										 </td>
										 <td class="text-right">
											 <div class="btn-toolbar" role="toolbar">                  
<?php
				   echo '                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, 'aID=' . $newsletter_values['subscription_id']. '&action=info'),                                                      null, 'info')    . '</div>' . PHP_EOL .
						'                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID', 'action')) . 'aID=' . $newsletter_values['subscription_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
						'                      <div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID', 'action')) . 'aID=' . $newsletter_values['subscription_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ; 
?>
											 </div> 
										 </td>									 
							 
				              </tr>
<?php							  
                              if (isset($cInfo) && is_object($cInfo) && ($newsletter_values['subscription_id'] == $cInfo->subscription_id) && isset($HTTP_GET_VARS['action'])) { 
 	                             $alertClass = '';
                                 switch ($action) {
									 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_SUBSCRIBER . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('delete_subscription', FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID', 'action')) . 'aID=' . $cInfo->subscription_id . '&email=' . $cInfo->subscription_email_address . '&action=deleteconfirm') . PHP_EOL ;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
			                           $contents .= '                          <h4>' . TEXT_INFO_HEADING_DELETE . ': ' . '</h4>' . PHP_EOL;
                                       $contents .= '                          <p>' . $cInfo->subscription_name  .  '<br />' . $cInfo->subscription_email_address . '</p>' . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID', 'action', 'page')) . 'aID=' . $cInfo->subscription_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
                                      break;

		                            case 'edit':
									
									   $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
														         array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));						
 			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
//			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
//			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('edit_subscription', FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&action=update&aID=' . $cInfo->subscription_id . '&search=' . $HTTP_GET_VARS['search'], 'post', 'class="form-horizontal" role="form"',  'id_edit_subscriber') . PHP_EOL;	
									   
			                           $contents            .= '          <div class="panel panel-primary">' . PHP_EOL;
			                           $contents            .= ' 		     <div class="panel-heading">' . CATEGORY_PERSONAL . '</div>' . PHP_EOL;
			                           $contents            .= ' 			 <div class="panel-body">' . PHP_EOL;

                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('subscription_address_email', $cInfo->subscription_email_address,  TEXT_INFO_EMAIL, 'inputEmail_Adress' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_EMAIL, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('subscription_name', $cInfo->subscription_name,  ENTRY_SUBSCRIPTION_NAME, 'inputName' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_SUBSCRIPTION_NAME, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;									   
									   
									   $contents            .= '                 <div class="form-group has-feedback">' . PHP_EOL ;	
								       $contents            .= '                   ' . tep_draw_bs_pull_down_menu('subscription_newsletter', $newsletter_array, (($cInfo->subscription_newsletter == '1') ? '1' : '0'), TEXT_NEWSLETTER, 'inputNewsletter', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents            .= '                 </div>' . PHP_EOL ;										   

                                       $index = 0;
	                                   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
                                       while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
                                            $existing_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
                                            ++$index;
                                       }				 
									   
									   $contents            .= '                 <div class="form-group ">' . PHP_EOL ;
								       $contents            .= '                   ' . tep_draw_bs_pull_down_menu('subscription_customers_group_id', $existing_array, $cInfo->subscription_customers_group_id, ENTRY_CUSTOMERS_GROUP_NAME, 'inputCust_Group', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents            .= '                 </div>' . PHP_EOL ;
				 
//									   $contents            .= '                 <div class="form-group ">' . PHP_EOL ; 
                                       $index = 0;
	                                   $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
                                       while ($stores_customers =  tep_db_fetch_array($stores_customers_query)) {
                                         $stores_array[] = array("id" => $stores_customers['stores_id'], "text" => "&#160;".$stores_customers['stores_name']."&#160;");
                                         ++$index;
                                       }
									   $contents            .= '                 <div class="form-group ">' . PHP_EOL ;
								       $contents            .= '                   ' . tep_draw_bs_pull_down_menu('subscription_stores_id', $stores_array, $cInfo->subscription_stores_id, ENTRY_CUSTOMERS_STORES_NAME, 'inputSubscription_Store', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents            .= '                 </div>' . PHP_EOL ;										   
									   
									   $contents            .= '			 </div>' . PHP_EOL ; //  <!-- end panel body category personal -->
									   $contents            .= '		  </div>' . PHP_EOL ; //    <!-- end panel category personal -->		   
					   

                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove',  tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&aID=' . $cInfo->subscription_id . '&search=' . $HTTP_GET_VARS['search']  ), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents .= '                      </form>' . PHP_EOL;
//		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;															  
          		
		                            default: 
// bof multi stores		
	                                    $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " where stores_id= '" . $cInfo->subscription_stores_id . "'");
                                        $stores_customers =  tep_db_fetch_array($stores_customers_query) ;
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
//			                            $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 			
			                            $contents .= '                              ' . TEXT_DATE_ACCOUNT_CREATED . ' ' . $info['date_account_created'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($info['date_account_last_modified']) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 	
			                            $contents .= '                              ' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($info['date_last_logon']) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;										
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $info['number_of_logons'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER ), null, null, 'btn-default text-danger') . PHP_EOL;										
                                    break;
                                 }
	
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="7">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
                              }							  
				} // end while ($customers = tep_db_fetch_array($cus							  
 							  
				
?>
			  </tbody>
		  </table>
		 </div> <!-- end class table-responsive -->
	</table>
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText hidden-xs mark" valign="top"><?php echo $newsletter_split->display_count($newsletter_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SUBSCRIBERS); ?></td>
            <td class="smallText mark" style="text-align: right;"><?php echo $newsletter_split->display_links($newsletter_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))) ?></td>	   
		  </tr>

    </table>
  </table>	
 </div> <!-- endi div class="table-responsive" -->
<?php
    if (!isset($HTTP_GET_VARS['search'])) {		
       $customers_groups_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
       while ($existing_customers_groups =  tep_db_fetch_array($customers_groups_query)) {
           $existing_customers_groups_array[] = array("id" => $existing_customers_groups['customers_group_id'], "text" => $existing_customers_groups['customers_group_name']);
      }
      $count_groups_query = tep_db_query("select subscription_customers_group_id, count(*) as count from " . TABLE_NEWSLETTER_SUBSCRIPTION . " group by subscription_customers_group_id order by count desc");
      while ($count_groups = tep_db_fetch_array($count_groups_query)) {
	    for ($n = 0; $n < sizeof($existing_customers_groups_array); $n++) {
		  if ($count_groups['subscription_customers_group_id'] == $existing_customers_groups_array[$n]['id']) {
			  $count_groups['customers_group_name'] = $existing_customers_groups_array[$n]['text'];
		  }
	    } // end for ($n = 0; $n < sizeof($existing_customers_groups_array); $n++)
	    $count_groups_array[] = array("id" => $count_groups['customers_group_id'], "number_in_group" => $count_groups['count'], "name" => $count_groups['customers_group_name']);
      }
?>
    <div class="table-responsive">
      <div class="col-md-6 col-xs-12 ">
	    <table class="table table-bordered" >
		   <thead>
		    <th><?php echo TABLE_HEADING_CUSTOMERS_GROUPS ?></td>
		    <th align="right"><?php echo TABLE_HEADING_CUSTOMERS_GROUPS_QNT ?></th>
		 </tr>
<?php $c = '0'; // variable used for background coloring of rows
   for ($z = 0; $z < sizeof($count_groups_array); $z++) {	  
?>
	<tr>
	  <td><?php echo $count_groups_array[$z]['name']; ?></td>
	  <td class="text-right"><?php echo $count_groups_array[$z]['number_in_group'] ?></td>
	</tr>
<?php
   } // end for ($z = 0; $z < sizeof($count_groups_array); $z++)
?>		 </table>
		 </td>
              <tr>
<?php
  } // end if (!isset($HTTP_GET_VARS['search']))
?>	
     </div>
	</div>
<?php	 
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_SUBSCRIBER, 'plus', null,'data-toggle="modal" data-target="#new_subscriber"') ; ?>
 			 </div>
            </div>
<?php
          }
?>  
  
        <div class="modal fade"  id="new_subscriber" role="dialog" aria-labelledby="new_subscriber" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('new_subscription', FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('action')) . 'action=new&page=' . $HTTP_GET_VARS['page'], 'post', 'class="form-horizontal" role="form"',  'id_new_subscriber') ; ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_SUBSCRIBER; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 

			                           $contents_new_subscriber            .= '          <div class="panel panel-primary">' . PHP_EOL;
			                           $contents_new_subscriber            .= ' 		     <div class="panel-heading">' . CATEGORY_PERSONAL . '</div>' . PHP_EOL;
			                           $contents_new_subscriber            .= ' 			 <div class="panel-body">' . PHP_EOL;

                                       $contents_new_subscriber            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new_subscriber            .= '                           ' . tep_draw_bs_input_field('subscription_address_email', null,  TEXT_INFO_EMAIL, 'inputEmail_Adress' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_EMAIL, '', true ) . PHP_EOL;	
                                       $contents_new_subscriber            .= '                       </div>' . PHP_EOL ;									   
									   $contents_new_subscriber            .= '                           <br />' . PHP_EOL;
									   
                                       $contents_new_subscriber            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new_subscriber            .= '                           ' . tep_draw_bs_input_field('subscription_name', null,  ENTRY_SUBSCRIPTION_NAME, 'inputName' ,  'col-xs-3', 'col-xs-9', 'left', ENTRY_SUBSCRIPTION_NAME, '', true ) . PHP_EOL;	
                                       $contents_new_subscriber            .= '                       </div>' . PHP_EOL ;									   
									   $contents_new_subscriber            .= '                           <br />' . PHP_EOL;									   
									   
									   $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
														         array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));	
																 
									   $contents_new_subscriber            .= '                 <div class="form-group has-feedback">' . PHP_EOL ;	
								       $contents_new_subscriber            .= '                   ' . tep_draw_bs_pull_down_menu('subscription_newsletter', $newsletter_array, true, TEXT_NEWSLETTER, 'inputNewsletter', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_new_subscriber            .= '                 </div>' . PHP_EOL ;										   

                                       $index = 0;
	                                   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
                                       while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
                                            $existing_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
                                            ++$index;
                                       }				 
									   
									   $contents_new_subscriber            .= '                 <div class="form-group ">' . PHP_EOL ;
								       $contents_new_subscriber            .= '                   ' . tep_draw_bs_pull_down_menu('subscription_customers_group_id', $existing_array, null, ENTRY_CUSTOMERS_GROUP_NAME, 'inputCust_Group', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_new_subscriber            .= '                 </div>' . PHP_EOL ;
				 
//									   $contents            .= '                 <div class="form-group ">' . PHP_EOL ; 
                                       $index = 0;
	                                   $stores_customers_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id ");
                                       while ($stores_customers =  tep_db_fetch_array($stores_customers_query)) {
                                         $stores_array[] = array("id" => $stores_customers['stores_id'], "text" => "&#160;".$stores_customers['stores_name']."&#160;");
                                         ++$index;
                                       }
									   $contents_new_subscriber            .= '                 <div class="form-group ">' . PHP_EOL ;
								       $contents_new_subscriber            .= '                   ' . tep_draw_bs_pull_down_menu('subscription_stores_id', $stores_array, null, ENTRY_CUSTOMERS_STORES_NAME, 'inputSubscription_Store', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_new_subscriber            .= '                 </div>' . PHP_EOL ;										   
									   
//									   $contents_new_subscriber            .= '			 </div>' . PHP_EOL ; //  <!-- end panel body category personal -->
//									   $contents_new_subscriber            .= '		  </div>' . PHP_EOL ; //    <!-- end panel category personal -->	

									
		              $contents_new_subscriber_footer .= '</div>' . PHP_EOL; // end div 	panel body
		              $contents_new_subscriber_footer .= '           </div>' . PHP_EOL; // end div 	panel		
?> 
                      <div class="full-iframe" width="100%"> 
                          <?php echo $contents_new_subscriber . $contents_new_subscriber_footer ; ?>
                      </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . 
				             tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_NEWSLETTER_SUBSCRIBER_MANAGER, tep_get_all_get_params(array('aID', 'page')) )); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_subscriber -->  
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>