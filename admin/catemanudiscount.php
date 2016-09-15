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
          $catemanudiscount_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
          $catemanudiscount_name = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_name']);
          $catemanudiscount_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_discount_sign']);
          $catemanudiscount_discount = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_discount']);
          $catemanudiscount_categories_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_categories_id']);
          $catemanudiscount_manufacturers_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_manufacturers_id']);
          $checkbox_customers_groups = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers_groups']);
          $catemanudiscount_groups_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_groups_id']);
          if ($checkbox_customers_groups == false) $catemanudiscount_groups_id = 0;
          $checkbox_customers = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers']);
          $catemanudiscount_customers_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_customers_id']);
          if ($checkbox_customers == false) $catemanudiscount_customers_id = 0;
          if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CATEMANUDISCOUNT . " WHERE catemanudiscount_groups_id='" . $catemanudiscount_groups_id . "' and catemanudiscount_customers_id='" . $catemanudiscount_customers_id . "' and catemanudiscount_categories_id='" . $catemanudiscount_categories_id . "' and catemanudiscount_manufacturers_id='" . $catemanudiscount_manufacturers_id . "' and catemanudiscount_id != " . tep_db_input($catemanudiscount_id))) > 0) {
            $messageStack->add_session(ERROR_CATEMANUDISCOUNT_DUPLICATE, 'error');
          } else {
            tep_db_query("update " . TABLE_CATEMANUDISCOUNT . " set catemanudiscount_name='" . $catemanudiscount_name . "', catemanudiscount_discount='" . $catemanudiscount_discount_sign . $catemanudiscount_discount . "', catemanudiscount_groups_id='" . $catemanudiscount_groups_id . "', catemanudiscount_customers_id='" . $catemanudiscount_customers_id . "', catemanudiscount_categories_id='" . $catemanudiscount_categories_id . "', catemanudiscount_manufacturers_id='" . $catemanudiscount_manufacturers_id . "' where catemanudiscount_id = " . tep_db_input($catemanudiscount_id) );
          }

          tep_redirect(tep_href_link(FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $catemanudiscount_id));
		    break;
        
      case 'deleteconfirm':
          tep_db_query("delete from " . TABLE_CATEMANUDISCOUNT . " where catemanudiscount_id= " . tep_db_prepare_input($HTTP_GET_VARS['cID'])); 
          tep_redirect(tep_href_link(FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('cID', 'action')))); 
        break;
        
      case 'newconfirm' :
          $catemanudiscount_name = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_name']);
          $catemanudiscount_discount_sign = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_discount_sign']);
          $catemanudiscount_discount = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_discount']);
          $catemanudiscount_categories_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_categories_id']);
          $catemanudiscount_manufacturers_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_manufacturers_id']);
          $checkbox_customers_groups = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers_groups']);
          $catemanudiscount_groups_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_groups_id']);
          if ($checkbox_customers_groups == false) $catemanudiscount_groups_id = 0;
          $checkbox_customers = tep_db_prepare_input($HTTP_POST_VARS['checkbox_customers']);
          $catemanudiscount_customers_id = tep_db_prepare_input($HTTP_POST_VARS['catemanudiscount_customers_id']);
          if ($checkbox_customers == false) $catemanudiscount_customers_id = 0;
          if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CATEMANUDISCOUNT . " WHERE catemanudiscount_groups_id='" . $catemanudiscount_groups_id . "' and catemanudiscount_customers_id='" . $catemanudiscount_customers_id . "' and catemanudiscount_categories_id='" . $catemanudiscount_categories_id . "'  and catemanudiscount_manufacturers_id='" . $catemanudiscount_manufacturers_id . "'")) > 0) {
            $messageStack->add_session(ERROR_CATEMANUDISCOUNT_DUPLICATE, 'error');
          } else {
            tep_db_query("insert into " . TABLE_CATEMANUDISCOUNT . " set catemanudiscount_name = '" . $catemanudiscount_name . "', catemanudiscount_discount = '" . $catemanudiscount_discount_sign . $catemanudiscount_discount . "', catemanudiscount_groups_id = '" . $catemanudiscount_groups_id . "', catemanudiscount_customers_id = '" . $catemanudiscount_customers_id . "', catemanudiscount_categories_id = '" . $catemanudiscount_categories_id . "', catemanudiscount_manufacturers_id='" . $catemanudiscount_manufacturers_id . "'");
          }
          tep_redirect(tep_href_link(FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('action'))));
        break;
    }
  }

  //purge entry with wrong bind
  $catemanudiscount_query = tep_db_query("SELECT * FROM " . TABLE_CATEMANUDISCOUNT);
  while ($catemanudiscount = tep_db_fetch_array($catemanudiscount_query)) {
    if($catemanudiscount['catemanudiscount_customers_id'] != 0) {
      if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CUSTOMERS . " WHERE customers_id = ".$catemanudiscount['catemanudiscount_customers_id'])) <= 0) {
        tep_db_query("DELETE FROM " . TABLE_CATEMANUDISCOUNT . " WHERE catemanudiscount_id = ".$catemanudiscount['catemanudiscount_id']);
      }
    }
    if($catemanudiscount['catemanudiscount_groups_id'] != 0) {
      if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE customers_group_id = ".$catemanudiscount['catemanudiscount_groups_id'])) <= 0) {
        tep_db_query("DELETE FROM " . TABLE_CATEMANUDISCOUNT . " WHERE catemanudiscount_id = ".$catemanudiscount['catemanudiscount_id']);
      }
    }
    if($catemanudiscount['catemanudiscount_categories_id'] != 0) {
      if(tep_db_num_rows(tep_db_query("SELECT * FROM " . TABLE_CATEGORIES . " WHERE categories_id = ".$catemanudiscount['catemanudiscount_categories_id'])) <= 0) {
        tep_db_query("DELETE FROM " . TABLE_CATEMANUDISCOUNT . " WHERE catemanudiscount_id = ".$catemanudiscount['catemanudiscount_id']);
      }
    }
    if($catemanudiscount['catemanudiscount_categories_id'] != 0) {
      if(tep_db_num_rows(tep_db_query("SELECT * FROM " .  TABLE_MANUFACTURERS . " WHERE manufacturers_id = ".$catemanudiscount['catemanudiscount_manufacturers_id'])) <= 0) {
        tep_db_query("DELETE FROM " . TABLE_CATEMANUDISCOUNT . " WHERE catemanudiscount_id = ".$catemanudiscount['catemanudiscount_id']);
      }
    }
  }
  
  
  require(DIR_WS_INCLUDES . 'template_top.php');
  
  switch ($listing) {
              case "id-asc":
              $order = "g.catemanudiscount_id";
              break;
              case "group":
              $order = "g.catemanudiscount_name";
              break;
              case "group-desc":
              $order = "g.catemanudiscount_name DESC";
              break;
              case "discount":
              $order = "g.catemanudiscount_discount";
              break;
              case "discount-desc":
              $order = "g.catemanudiscount_discount DESC";
              break;
              default:
              $order = "g.catemanudiscount_id ASC";
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
  $sde=0;
  while ($existing_groups = tep_db_fetch_array($customers_group_query)) {
      $input_groups[$sde++]=array("id"=>$existing_groups['customers_group_id'],
	                              "text"=>$existing_groups['customers_group_name']);
  }  
  
  
  $manufacturer_query = tep_db_query("select distinct manufacturers_name, manufacturers_id from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  $input_manufacturer=array();
  $sde=0;
  while ($manufacturer = tep_db_fetch_array($manufacturer_query)) {
      $input_manufacturer[$sde++]=array("id"  =>$manufacturer['manufacturers_id'],
	                                    "text"=>$manufacturer['manufacturers_name']);
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
                   <th class="text-center"><?php echo TABLE_HEADING_MANUFACTURERS; ?></th>					   
                   <th class="text-left" > <?php echo TABLE_HEADING_ACTION; ?></th>	   
                </tr>
              </thead>
              <tbody>
<?php			  
				$search = '';
				if ( ($HTTP_GET_VARS['search']) && (tep_not_null($HTTP_GET_VARS['search'])) ) {
				  $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
				  $search = "and g.catemanudiscount_name like '%" . $keywords . "%'";
				}

				$catemanudiscount_query_raw = "select g.catemanudiscount_id, g.catemanudiscount_name, g.catemanudiscount_discount, cd.categories_name as catemanudiscount_categories_name, gm.manufacturers_name as catemanudiscount_manufacturers_name, g.catemanudiscount_customers_id, g.catemanudiscount_groups_id, g.catemanudiscount_manufacturers_id, g.catemanudiscount_categories_id from " . TABLE_CATEMANUDISCOUNT . " g, " .  TABLE_CATEGORIES . " c , " . TABLE_CATEGORIES_DESCRIPTION . " cd, " .  TABLE_MANUFACTURERS . " gm where g.catemanudiscount_manufacturers_id = gm.manufacturers_id and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and g.catemanudiscount_categories_id = c.categories_id " . $search . " order by " . $order;
				$catemanudiscount_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $catemanudiscount_query_raw, $catemanudiscount_query_numrows);
				$catemanudiscount_query = tep_db_query($catemanudiscount_query_raw);

				while ($catemanudiscount = tep_db_fetch_array($catemanudiscount_query)) {

				  if (((!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['cID'] == $catemanudiscount['catemanudiscount_id'])) && (!$cInfo)) {
					$cInfo = new objectInfo($catemanudiscount);
				  }

				  $catemanudiscount_customers_name =  '';
				  $catemanudiscount_groups_name    =  '';				  
				  if ($catemanudiscount['catemanudiscount_customers_id'] != 0) {
					$customers_query = tep_db_query("select distinct customers_firstname, customers_lastname, customers_id from " . TABLE_CUSTOMERS . " where customers_id = '" . $catemanudiscount['catemanudiscount_customers_id'] . "'");
					$customers = tep_db_fetch_array($customers_query);
					$catemanudiscount_customers_name = $customers['customers_lastname'] . " " . $customers['customers_firstname'];
					//$catemanudiscount_groups_name = "&nbsp;";
				  } 
				  if ($catemanudiscount['catemanudiscount_groups_id'] !=0) {
					$customers_groups_query = tep_db_query("select distinct customers_group_name, customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .$catemanudiscount['catemanudiscount_groups_id'] . "'");
					$customers_groups = tep_db_fetch_array($customers_groups_query);
					$catemanudiscount_groups_name =  $customers_groups['customers_group_name'];
					//$catemanudiscount_customers_name =  "&nbsp;";
				  }
				  
				  if ( (is_object($cInfo)) && ($catemanudiscount_id['catemanudiscount_id_id'] == $cInfo->catemanudiscount_id_id) ) {
					echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->catemanudiscount_id . '&action=edit') . '\'">' . PHP_EOL;
				  } else {
					echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('cID')) . 'cID=' . $catemanudiscount['catemanudiscount_id']) . '\'">' . PHP_EOL;
					
				  }		
?>				  
 
							<td>                     <?php echo $catemanudiscount['catemanudiscount_name']; ?></td>
							<td  class="text-center"><?php echo $catemanudiscount['catemanudiscount_discount']; ?>%</td>
							<td  class="text-center"><?php echo $catemanudiscount_customers_name; ?></td>
							<td  class="text-center"><?php echo $catemanudiscount_groups_name; ?></td>
							<td  class="text-center"><?php echo $catemanudiscount['catemanudiscount_categories_name']; ?></td>
                            <td  class="text-center"><?php echo $catemanudiscount['catemanudiscount_manufacturers_name']; ?></td>							
                            <td class="text-left">								 
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_CATEMANUDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $catemanudiscount['catemanudiscount_id'] . '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_CATEMANUDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $catemanudiscount['catemanudiscount_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_CATEMANUDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $catemanudiscount['catemanudiscount_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				            </td>												
                          </tr>
<?php
                  if (isset($cInfo) && is_object($cInfo) && ($catemanudiscount['catemanudiscount_id'] == $cInfo->catemanudiscount_id) && isset($HTTP_GET_VARS['action'])) { 
	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CATEMANUDISCOUNT . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('catemanudiscount_id', FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->catemanudiscount_id . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_DELETE_INTRO . '<br />' . $catemanudiscount['catemanudiscount_name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->catemanudiscount_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_EDIT_MANUCATEDISCOUNT . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('edit_discount_category', FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('action')) . 'cID=' . $cInfo->catemanudiscount_id  . 'action=update', 'post', 'onSubmit="return check_form();" class="form-horizontal" role="form"', 'id_edit_discount_category') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('catemanudiscount_name',       $cInfo->catemanudiscount_name,        ENTRY_CATEMANUDISCOUNT_NAME,       'id_input_manu_name' ,        'col-xs-3', 'col-xs-9', 'left', ENTRY_CATEMANUDISCOUNT_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	

									   $contents            .= '                       <div class="form-group">' . PHP_EOL ; 
									   
                                      if ( $cInfo->catemanudiscount_discount > 0 ) {
	                                     $discount_sign = '+' ;
	                                     $discount_amount = $cInfo->catemanudiscount_discount ;
                                       } else {
	                                     $discount_sign   = '-' ;	
                                         $discount_amount = substr($cInfo->catemanudiscount_discount,1,strlen($cInfo->catemanudiscount_discount))	 ;
                                       }                                 
							  
								       $contents            .= '                   ' .      tep_draw_bs_pull_down_menu('catemanudiscount_discount_sign', $array_discount_sign, $discount_sign, ENTRY_DEFAULT_DISCOUNT, 'input_manu_category_Discount_Sign', 'col-xs-2', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents            .= '                   ' .      tep_draw_bs_input_field(   'catemanudiscount_discount', $discount_amount,  null, 'input_manu_category_discount' ,  null, 'col-xs-7', null, ENTRY_DEFAULT_DISCOUNT, '' ) . PHP_EOL;	
									   $contents            .= '                       </div>' . PHP_EOL ;
									   
									   $contents            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents            .=                             ENTRY_CUSTOMERS_NAME . PHP_EOL ;
									   $contents            .= '                       </label>' . PHP_EOL ;
									   
									   $contents            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents            .=                            tep_draw_bs_pull_down_menu('catemanudiscount_customers_id', $input_customers,  $cInfo->catemanudiscount_customers_id, null, 'input_customer_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents            .=                            tep_bs_checkbox_field('checkbox_customers', false, false, 'id_use_customer', (($cInfo->catemanudiscount_customers_id != 0) ?  true : false), ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents            .= '                       </div>' . PHP_EOL ;	
									   
									   $contents            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents            .=                             ENTRY_GROUPS_NAME . PHP_EOL ;
									   $contents            .= '                       </label>' . PHP_EOL ;
									   
									   $contents            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents            .=                            tep_draw_bs_pull_down_menu('catemanudiscount_groups_id', $input_groups, $cInfo->catemanudiscount_groups_id, null, 'input_customer_group_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents            .=                            tep_bs_checkbox_field('checkbox_customers_groups', false, false, 'id_use_customer_group', (($cInfo->catemanudiscount_groups_id != 0) ?  true : false), ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents            .= '                       </div>' . PHP_EOL ;										   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('catemanudiscount_categories_id', tep_get_category_tree(0,'',0), $cInfo->catemanudiscount_categories_id, ENTRY_CATEGORIES_NAME, 'id_input_category', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_pull_down_menu('catemanudiscount_manufacturers_id', $input_manufacturer, $cInfo->catemanudiscount_manufacturers_id, ENTRY_MANUFACTURERS_NAME, 'id_input_manufacturer', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
									   
									   $contents            .= '                       <br />' . PHP_EOL;	
                                       
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEMANUDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->catemanudiscount_id), null, null, 'btn-default text-danger') . PHP_EOL;			
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
                                      '                  <td colspan="7">' . PHP_EOL .
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
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $catemanudiscount_split->display_count($catemanudiscount_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_DISCOUNT); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $catemanudiscount_split->display_links($catemanudiscount_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></div>		   
	     </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_DISCOUNT, 'plus', null,'data-toggle="modal" data-target="#new_manu_discount_category"') ; ?>
 			 </div>
            </div>
<?php
          }
?>		  

       <div class="modal fade"  id="new_manu_discount_category" role="dialog" aria-labelledby="new_manu_discount_category" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php //echo tep_draw_form('new_customers', FILENAME_CATEDISCOUNT, tep_get_all_get_params(array('action')) . 'action=newconfirm') ; 
				echo tep_draw_bs_form('new_manu_discount_category', FILENAME_CATEMANUDISCOUNT, tep_get_all_get_params(array('action')) . 'action=newconfirm', 'post', 'onSubmit="return check_form();" class="form-horizontal" role="form"', 'id_new_discount_category')
				?>                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_MANUCATEDISCOUNT; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
			                           $contents_new            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents_new            .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_NEW_MANUCATEDISCOUNT . '</div>' . PHP_EOL;
			                           $contents_new            .= '          <div class="panel-body">' . PHP_EOL;			
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents_new            .= '                           ' . tep_draw_bs_input_field('catemanudiscount_name',       null,        ENTRY_CATEMANUDISCOUNT_NAME,       'id_input_manu_name' ,        'col-xs-3', 'col-xs-9', 'left', ENTRY_CATEMANUDISCOUNT_NAME,       '', true ) . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;	

									   $contents_new            .= '                       <div class="form-group">' . PHP_EOL ; 
	                                   $discount_sign   = '-' ;	
                                       $discount_amount = 0	 ;                                       
							  
								       $contents_new            .= '                   ' .      tep_draw_bs_pull_down_menu('catemanudiscount_discount_sign', $array_discount_sign, $discount_sign, ENTRY_DEFAULT_DISCOUNT, 'input_manu_category_Discount_Sign', 'col-xs-2', ' selectpicker show-tick ', 'col-xs-3', 'left')  . PHP_EOL;	
									   $contents_new            .= '                   ' .      tep_draw_bs_input_field(   'catemanudiscount_discount', $discount_amount,  null, 'input_manu_category_discount' ,  null, 'col-xs-7', null, ENTRY_DEFAULT_DISCOUNT, '' ) . PHP_EOL;	
									   $contents_new            .= '                       </div>' . PHP_EOL ;

									   $contents_new            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents_new            .=                             ENTRY_CUSTOMERS_NAME . PHP_EOL ;
									   $contents_new            .= '                       </label>' . PHP_EOL ;
									   
									   $contents_new            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents_new            .=                            tep_draw_bs_pull_down_menu('catemanudiscount_customers_id', $input_customers,  null, null, 'input_customer_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents_new            .=                            tep_bs_checkbox_field('checkbox_customers', false, false, 'id_use_customer', null, ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents_new            .= '                       </div>' . PHP_EOL ;	
									   
									   $contents_new            .= '                       <label class="col-xs-3">' . PHP_EOL ;									   
									   $contents_new            .=                             ENTRY_GROUPS_NAME . PHP_EOL ;
									   $contents_new            .= '                       </label>' . PHP_EOL ;
									   
									   $contents_new            .= '                       <div class="form-inline">' . PHP_EOL ;											   
									   $contents_new            .=                            tep_draw_bs_pull_down_menu('catemanudiscount_groups_id', $input_groups, null, null, 'input_customer_group_id', 'col-xs-4', ' selectpicker show-tick ', null, 'left', null, null, true)  . PHP_EOL;	
									   $contents_new            .=                            tep_bs_checkbox_field('checkbox_customers_groups', false, false, 'id_use_customer_group', null, ' checkbox checkbox-success ', 'col-xs-1', '', 'right') ;
									   
									   $contents_new            .= '                       </div>' . PHP_EOL ;										   
									   
                                       $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new            .= '                           ' . tep_draw_bs_pull_down_menu('catemanudiscount_categories_id', tep_get_category_tree(0,'',0), null, ENTRY_CATEGORIES_NAME, 'id_input_category', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;	
                                       $contents_new            .= '                       </div>' . PHP_EOL ;									   
									   
                                      $contents_new            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents_new            .= '                           ' . tep_draw_bs_pull_down_menu('catemanudiscount_manufacturers_id', $input_manufacturer, null, ENTRY_MANUFACTURERS_NAME, 'id_input_manufacturer', 'col-xs-9', ' selectpicker show-tick ', 'col-xs-3', 'left', null, null, true)  . PHP_EOL;	
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
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CATEMANUDISCOUNT, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->catemanudiscount_id)); ?>
                  </div> <!-- end div modal-footer -->              
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_manu_discount_category --> 


<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>