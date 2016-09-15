<?php
/*
  Manufacturer Discount
  by hOZONE, hozone@tiscali.it, http://www.hozone.it

  derived by:
  Discount_Groups_v1.1, by Enrico Drusiani, 2003/5/22
  
  for:
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  
  Copyright (c) 2003 osCommerce
  
  Released under the GNU General Public License 
*/

  require('includes/application_top.php');
  
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

      case 'update':
          $error = false;
          $catediscount_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
          $catediscount_name = tep_db_prepare_input($HTTP_POST_VARS['catediscount_name']);
          $catediscount_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['catediscount_discount_sign']);
          $catediscount_discount = tep_db_prepare_input($HTTP_POST_VARS['catediscount_discount']);
          $catediscount_categories_id = tep_db_prepare_input($HTTP_POST_VARS['catediscount_categories_id']);
          $checkbox_customers_groups = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers_groups']);
          $catediscount_groups_id = tep_db_prepare_input($HTTP_POST_VARS['catediscount_groups_id']);
          if ($checkbox_customers_groups == false) $catediscount_groups_id = 0;
          $checkbox_customers = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers']);
          $catediscount_customers_id = tep_db_prepare_input($HTTP_POST_VARS['catediscount_customers_id']);
          if ($checkbox_customers == false) $catediscount_customers_id = 0;
          if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CATEDISCOUNT . " WHERE catediscount_groups_id='" . $catediscount_groups_id . "' and catediscount_customers_id='" . $catediscount_customers_id . "' and catediscount_categories_id='" . $catediscount_categories_id . "' and catediscount_id != " . tep_db_input($catediscount_id))) > 0) {
            $messageStack->add_session(ERROR_CATEDISCOUNT_DUPLICATE, 'error');
          } else {
            tep_db_query("update " . TABLE_CATEDISCOUNT . " set catediscount_name='" . $catediscount_name . "', catediscount_discount='" . $catediscount_discount_sign . $catediscount_discount . "', catediscount_groups_id='" . $catediscount_groups_id . "', catediscount_customers_id='" . $catediscount_customers_id . "', catediscount_categories_id='" . $catediscount_categories_id . "' where catediscount_id = " . tep_db_input($catediscount_id) );
          }

          tep_redirect(tep_href_link(FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $catediscount_id));
		    break;
        
      case 'deleteconfirm':
          tep_db_query("delete from " . TABLE_CATEDISCOUNT . " where catediscount_id= " . tep_db_prepare_input($HTTP_GET_VARS['cID'])); 
          tep_redirect(tep_href_link(FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('cID', 'action')))); 
        break;
        
      case 'newconfirm' :
          $catediscount_name = tep_db_prepare_input($HTTP_POST_VARS['catediscount_name']);
          $catediscount_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['catediscount_discount_sign']);
          $catediscount_discount = tep_db_prepare_input($HTTP_POST_VARS['catediscount_discount']);
          $catediscount_categories_id = tep_db_prepare_input($HTTP_POST_VARS['catediscount_categories_id']);
          $checkbox_customers_groups = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers_groups']);
          $catediscount_groups_id = tep_db_prepare_input($HTTP_POST_VARS['catediscount_groups_id']);
          if ($checkbox_customers_groups == false) $catediscount_groups_id = 0;
          $checkbox_customers = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers']);
          $catediscount_customers_id = tep_db_prepare_input($HTTP_POST_VARS['catediscount_customers_id']);
          if ($checkbox_customers == false) $catediscount_customers_id = 0;
          if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CATEDISCOUNT . " WHERE catediscount_groups_id='" . $catediscount_groups_id . "' and catediscount_customers_id='" . $catediscount_customers_id . "' and catediscount_categories_id='" . $catediscount_categories_id . "'")) > 0) {
            $messageStack->add_session(ERROR_CATEDISCOUNT_DUPLICATE, 'error');
          } else {
            tep_db_query("insert into " . TABLE_CATEDISCOUNT . " set catediscount_name = '" . $catediscount_name . "', catediscount_discount = '" . $catediscount_discount_sign . $catediscount_discount . "', catediscount_groups_id = '" . $catediscount_groups_id . "', catediscount_customers_id = '" . $catediscount_customers_id . "', catediscount_categories_id = '" . $catediscount_categories_id . "'");
          }
          tep_redirect(tep_href_link(FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('action'))));
        break;
    }
  }

  //purge entry with wrong bind
  $catediscount_query = tep_db_query("SELECT * FROM " . TABLE_CATEDISCOUNT);
  while ($catediscount = tep_db_fetch_array($catediscount_query)) {
    if($catediscount['catediscount_customers_id'] != 0) {
      if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CUSTOMERS . " WHERE customers_id = ".$catediscount['catediscount_customers_id'])) <= 0) {
        tep_db_query("DELETE FROM " . TABLE_CATEDISCOUNT . " WHERE catediscount_id = ".$catediscount['catediscount_id']);
      }
    }
    if($catediscount['catediscount_groups_id'] != 0) {
      if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE customers_group_id = ".$catediscount['catediscount_groups_id'])) <= 0) {
        tep_db_query("DELETE FROM " . TABLE_CATEDISCOUNT . " WHERE catediscount_id = ".$catediscount['catediscount_id']);
      }
    }
    if($catediscount['catediscount_categories_id'] != 0) {
      if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CATEGORIES . " WHERE categories_id = ".$catediscount['catediscount_categories_id'])) <= 0) {
        tep_db_query("DELETE FROM " . TABLE_CATEDISCOUNT . " WHERE catediscount_id = ".$catediscount['catediscount_id']);
      }
    }
  }
  require(DIR_WS_INCLUDES . 'template_top.php');
  
  switch ($listing) {
              case "id-asc":
              $order = "g.catediscount_id";
              break;
              case "group":
              $order = "g.catediscount_name";
              break;
              case "group-desc":
              $order = "g.catediscount_name DESC";
              break;
              case "discount":
              $order = "g.catediscount_discount";
              break;
              case "discount-desc":
              $order = "g.catediscount_discount DESC";
              break;
              default:
              $order = "g.catediscount_id ASC";										   
  }
  
  $array_discount_sign = array(array('id' => '-', 'text' => 'minus'),
	       			           array('id' => '+', 'text' => 'plus'));	

  $customers_query = tep_db_query("select distinct customers_firstname, customers_lastname, customers_id from " . TABLE_CUSTOMERS . " order by customers_lastname, customers_firstname");
  $input_customers=array();
  $all_customers=array();
			  
  while ($existing_customers = tep_db_fetch_array($customers_query)) {
    $input_customers[]=array("id"=>$existing_customers['customers_id'],
                             "text"=>$existing_customers['customers_lastname'] . " " . $existing_customers['customers_firstname'] );
  }	  
	
  $customers_group_query = tep_db_query("select distinct customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_name");
  $input_groups=array();
  $all_groups=array();
  $sde=0;
  while ($existing_groups = tep_db_fetch_array($customers_group_query)) {
      $input_groups[$sde++]=array("id"=>$existing_groups['customers_group_id'],
	                              "text"=>$existing_groups['customers_group_name']);
  }  
  
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                    <?php echo TABLE_HEADING_NAME; ?></th>
                   <th class="text-center"><?php echo TABLE_HEADING_DISCOUNT; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_CUSTOMERS; ?></th>				   
                   <th class="text-center"><?php echo TABLE_HEADING_GROUPS; ?></th>		
                   <th class="text-center"><?php echo TABLE_HEADING_CATEGORIES; ?></th>						  
                   <th class="text-left" > <?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php			  
				$search = '';
				if ( ($HTTP_GET_VARS['search']) && (tep_not_null($HTTP_GET_VARS['search'])) ) {
				  $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
				  $search = "and g.catediscount_name like '%" . $keywords . "%'";
				}

				$catediscount_query_raw = "select g.catediscount_id, g.catediscount_name, g.catediscount_discount, cd.categories_name as catediscount_categories_name, g.catediscount_customers_id, g.catediscount_groups_id from " . TABLE_CATEDISCOUNT . " g, " .  TABLE_CATEGORIES . " c , " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and g.catediscount_categories_id = c.categories_id " . $search . " order by " . $order;
				$catediscount_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $catediscount_query_raw, $catediscount_query_numrows);
				$catediscount_query = tep_db_query($catediscount_query_raw);

				while ($catediscount = tep_db_fetch_array($catediscount_query)) {

				  if (((!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['cID'] == $catediscount['catediscount_id'])) && (!$cInfo)) {
					$cInfo = new objectInfo($catediscount);
				  }
				  
				  $catediscount_customers_name = '';
				  $catediscount_groups_name    = '';				  
				  
				  if ($catediscount['catediscount_customers_id'] != 0) {
					$customers_query = tep_db_query("select distinct customers_firstname, customers_lastname, customers_id from " . TABLE_CUSTOMERS . " where customers_id = '" . $catediscount['catediscount_customers_id'] . "'");
					$customers = tep_db_fetch_array($customers_query);
					$catediscount_customers_name = $customers['customers_lastname'] . " " . $customers['customers_firstname'];
				//	$catediscount_groups_name = '';
				  } 
				  if ($catediscount['catediscount_groups_id'] !=0) {
					$customers_group_query = tep_db_query("select distinct customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .$catediscount['catediscount_groups_id'] . "'");
					$customers_groups = tep_db_fetch_array($customers_group_query);
					$catediscount_groups_name =  $customers_groups['customers_group_name'];
					//$catediscount_customers_name =  '';
 
				  }
				  if ( (is_object($cInfo)) && ($catediscount['catediscount_id'] == $cInfo->catediscount_id) ) {
					echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->catediscount_id . '&action=edit') . '\'">' . PHP_EOL;
				  } else {
					echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('cID')) . 'cID=' . $catediscount['catediscount_id']) . '\'">' . PHP_EOL;
					
				  }		
?>				  
 
							<td ><?php echo $catediscount['catediscount_name']; ?></td>
							<td  class="text-center"><?php echo $catediscount['catediscount_discount']; ?>%</td>
							<td  class="text-center"><?php echo $catediscount_customers_name; ?></td>
							<td  class="text-center"><?php echo $catediscount_groups_name; ?></td>
							<td  class="text-center"><?php echo $catediscount['catediscount_categories_name']; ?></td>
                            <td class="text-left">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_CATEDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $catediscount['catediscount_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_CATEDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $catediscount['catediscount_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_CATEDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $catediscount['catediscount_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				            </td>												
                          </tr>
<?php
                  if (isset($cInfo) && is_object($cInfo) && ($catediscount['catediscount_id'] == $cInfo->catediscount_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CATEDISCOUNT . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('catediscount', FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->catediscount_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $catediscount['catediscount_name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->catediscount_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_CATEDISCOUNT . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('edit_discount_category', FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();" class="form-horizontal" role="form"', 'id_edit_discount_category') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('catediscount_name',       $cInfo->catediscount_name,        ENTRY_CATEDISCOUNT_NAME,       'id_input_zones_name' ,        'col-xs-3', 'col-xs-9', 'left', ENTRY_CATEDISCOUNT_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	

									   $contents            .= '                       <div class="form-group">' . PHP_EOL ;

                                       if ( $cInfo->catediscount_discount > 0 ) {
	                                     $discount_sign = '+' ;
	                                     $discount_amount = $cInfo->catediscount_discount ;
                                       } else {
	                                     $discount_sign   = '-' ;	
                                         $discount_amount = substr($cInfo->catediscount_discount,1,strlen($cInfo->catediscount_discount))	 ;
                                       }
							  
								       $contents            .= '                   ' .      tep_draw_bs_pull_down_menu('catediscount_discount_sign', $array_discount_sign, $discount_sign, ENTRY_DEFAULT_DISCOUNT, 'input_category_Discount_Sign', 'col-xs-2', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents            .= '                   ' .      tep_draw_bs_input_field(   'catediscount_discount', $discount_amount,  null, 'inputcategory_discount' ,  null, 'col-xs-7', null, ENTRY_DEFAULT_DISCOUNT, '' ) . PHP_EOL;	
									   $contents            .= '                       </div>' . PHP_EOL ;

									   $contents            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents            .=                             ENTRY_CUSTOMERS_NAME . PHP_EOL ;
									   $contents            .= '                       </label>' . PHP_EOL ;
									   
									   $contents            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents            .=                            tep_draw_bs_pull_down_menu('catediscount_customers_id', $input_customers, $cInfo->catediscount_customers_id, null, 'input_customer_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents            .=                            tep_bs_checkbox_field('checkbox_customers', false, null, 'id_use_customer', (($cInfo->catediscount_customers_id != 0) ?  true : false), ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents            .= '                       </div>' . PHP_EOL ;	
									   
									   $contents            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents            .=                             ENTRY_GROUPS_NAME . PHP_EOL ;
									   $contents            .= '                       </label>' . PHP_EOL ;
									   
									   $contents            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents            .=                            tep_draw_bs_pull_down_menu('catediscount_groups_id', $input_groups, $cInfo->catediscount_groups_id, null, 'input_customer_group_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents            .=                            tep_bs_checkbox_field('checkbox_customers_groups', false, null, 'id_use_customer_group', (($cInfo->catediscount_groups_id != 0) ?  true : false), ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents            .= '                       </div>' . PHP_EOL ;										   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('catediscount_categories_id', tep_get_category_tree(0,'',0), $cInfo->catediscount_categories_id, ENTRY_CATEGORIES_NAME, 'id_input_category', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->catediscount_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
/*		                            default: 
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $cInfo->countries_name . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_NAME . '<br />' . $cInfo->zone_name . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_ZONES_CODE . '  ' . $cInfo->zone_code . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;					
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_COUNTRY_NAME . '  ' . tep_get_country_name( $cInfo->countries_id ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                          
 			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_ZONES ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
*/							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row ' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets	
                }
?>
 
			  </tbody>
		</table>
	 </div>
  </table>
     <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $catediscount_split->display_count($catediscount_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_DISCOUNT); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $catediscount_split->display_links($catediscount_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_DISCOUNT, 'plus', null,'data-toggle="modal" data-target="#new_discount_category"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  

       <div class="modal fade"  id="new_discount_category" role="dialog" aria-labelledby="new_discount_category" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php //echo tep_draw_form('new_customers', FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('action')) . 'action=newconfirm') ; 
				echo tep_draw_bs_form('new_discount_category', FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('action')) . 'action=newconfirm', 'post', 'onSubmit="return check_form();" class="form-horizontal" role="form"', 'id_new_discount_category')
				?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_CATEDISCOUNT; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_new            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_CATEDISCOUNT . '</div>' . PHP_EOL;
			                           $contents_new            .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_new            .= '                           ' . tep_draw_bs_input_field('catediscount_name',       null,        ENTRY_CATEDISCOUNT_NAME,       'id_input_zones_name' ,        'col-xs-3', 'col-xs-9', 'left', ENTRY_CATEDISCOUNT_NAME,       '', true ) . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;	

									   $contents_new            .= '                       <div class="form-group">' . PHP_EOL ; 
	                                   $discount_sign   = '-' ;	
                                       $discount_amount = 0	 ;                                       
							  
								       $contents_new            .= '                   ' .      tep_draw_bs_pull_down_menu('catediscount_discount_sign', $array_discount_sign, $discount_sign, ENTRY_DEFAULT_DISCOUNT, 'input_category_Discount_Sign', 'col-xs-2', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_new            .= '                   ' .      tep_draw_bs_input_field(   'catediscount_discount', $discount_amount,  null, 'inputcategory_discount' ,  null, 'col-xs-7', null, ENTRY_DEFAULT_DISCOUNT, '' ) . PHP_EOL;	
									   $contents_new            .= '                       </div>' . PHP_EOL ;

									   $contents_new            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents_new            .=                             ENTRY_CUSTOMERS_NAME . PHP_EOL ;
									   $contents_new            .= '                       </label>' . PHP_EOL ;
									   
									   $contents_new            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents_new            .=                            tep_draw_bs_pull_down_menu('catediscount_customers_id', $input_customers,  null, null, 'input_customer_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents_new            .=                            tep_bs_checkbox_field('checkbox_customers', false, false, 'id_use_customer', null, ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents_new            .= '                       </div>' . PHP_EOL ;	
									   
									   $contents_new            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents_new            .=                             ENTRY_GROUPS_NAME . PHP_EOL ;
									   $contents_new            .= '                       </label>' . PHP_EOL ;
									   
									   $contents_new            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents_new            .=                            tep_draw_bs_pull_down_menu('catediscount_groups_id', $input_groups, null, null, 'input_customer_group_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents_new            .=                            tep_bs_checkbox_field('checkbox_customers_groups', false, false, 'id_use_customer_group', null, ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents_new            .= '                       </div>' . PHP_EOL ;										   
									   
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new            .= '                           ' . tep_draw_bs_pull_down_menu('catediscount_categories_id', tep_get_category_tree(0,'',0), null, ENTRY_CATEGORIES_NAME, 'id_input_category', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;									   
									   $contents_new            .= '                       <br />' . PHP_EOL;	
                                       
		                               $contents_new_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_news_footer .= '           </div>' . PHP_EOL; // end div 	panel	
?>
                    <div class="full-iframe" width="100%"> 
                      <?php echo $contents_new . $contents_new_footer ; ?>
                    </div> 
   
                   
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEDISCOUNT, 'page=' . $HTTP_GET_VARS['page'])); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_discount_category --> 
  
<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>