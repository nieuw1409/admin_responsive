<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php'); 

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  
 // check if there more then 1 store active
  $stores_query = tep_db_query( "select stores_id from " . TABLE_STORES ) ;
  if ( tep_db_num_rows( $stores_query ) > 1 ) $stores_multi_present = 'true' ;    

// bof multi stores
  $currencies_to_stores = '@,';
  if ( $HTTP_POST_VARS['stores_currencies'] ) { // if any of the checkboxes are checked
    $_stores = array_keys( $HTTP_POST_VARS['stores_currencies'] ) ;  
    foreach($_stores as $val) {
        $currencies_to_stores .= tep_db_prepare_input($val).','; 
		$test .= $val.',';
    } // end foreach
  }
  $currencies_to_stores = substr($currencies_to_stores,0,strlen($currencies_to_stores)-1); // remove last comma
  if ( $stores_multi_present != 'true' ) $currencies_to_stores = '@,1' ; // 1 store automatic activ   
// eof multi stores	  
  
  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['cID'])) $currency_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $title = tep_db_prepare_input($HTTP_POST_VARS['title'] );
        $code = tep_db_prepare_input($HTTP_POST_VARS['code']);
        $symbol_left = tep_db_prepare_input($HTTP_POST_VARS['symbol_left']);
        $symbol_right = tep_db_prepare_input($HTTP_POST_VARS['symbol_right']);
        $decimal_point = tep_db_prepare_input($HTTP_POST_VARS['decimal_point']);
        $thousands_point = tep_db_prepare_input($HTTP_POST_VARS['thousands_point']);
        $decimal_places = tep_db_prepare_input($HTTP_POST_VARS['decimal_places']);
        $value = tep_db_prepare_input($HTTP_POST_VARS['value']);

        $sql_data_array = array('title' => $title,
                                'code' => $code,
                                'symbol_left' => $symbol_left,
                                'symbol_right' => $symbol_right,
                                'decimal_point' => $decimal_point,
                                'thousands_point' => $thousands_point,
                                'decimal_places' => $decimal_places,
                                'value' => $value, 
								'currencies_to_stores' =>  $currencies_to_stores);

        if ($action == 'insert') {
          tep_db_perform(TABLE_CURRENCIES, $sql_data_array);
          $currency_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          tep_db_perform(TABLE_CURRENCIES, $sql_data_array, 'update', "currencies_id = '" . (int)$currency_id . "'");
        }

        if (isset($HTTP_POST_VARS['default']) && ($HTTP_POST_VARS['default'] == 'on')) {
          tep_db_query("update " . $multi_stores_config . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_CURRENCY'");
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end				  
        }

        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currency_id));
        break;
      case 'deleteconfirm':
        $currencies_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        $currency_query = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . DEFAULT_CURRENCY . "'");
        $currency = tep_db_fetch_array($currency_query);

        if ($currency['currencies_id'] == $currencies_id) {
          tep_db_query("update " . $multi_stores_config . " set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end				  
        }

        tep_db_query("delete from " . TABLE_CURRENCIES . " where currencies_id = '" . (int)$currencies_id . "'");

        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'update':
        $server_used = CURRENCY_SERVER_PRIMARY;

        $currency_query = tep_db_query("select currencies_id, code, title from " . TABLE_CURRENCIES);
        while ($currency = tep_db_fetch_array($currency_query)) {
          $quote_function = 'quote_' . CURRENCY_SERVER_PRIMARY . '_currency';
          $rate = $quote_function($currency['code']);

          if (empty($rate) && (tep_not_null(CURRENCY_SERVER_BACKUP))) {
            $messageStack->add_session(sprintf(WARNING_PRIMARY_SERVER_FAILED, CURRENCY_SERVER_PRIMARY, $currency['title'], $currency['code']), 'warning');

            $quote_function = 'quote_' . CURRENCY_SERVER_BACKUP . '_currency';
            $rate = $quote_function($currency['code']);

            $server_used = CURRENCY_SERVER_BACKUP;
          }

          if (tep_not_null($rate)) {
            tep_db_query("update " . TABLE_CURRENCIES . " set value = '" . $rate . "', last_updated = now() where currencies_id = '" . (int)$currency['currencies_id'] . "'");

            $messageStack->add_session(sprintf(TEXT_INFO_CURRENCY_UPDATED, $currency['title'], $currency['code'], $server_used), 'success');
          } else {
            $messageStack->add_session(sprintf(ERROR_CURRENCY_INVALID, $currency['title'], $currency['code'], $server_used), 'error');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $HTTP_GET_VARS['cID']));
        break;
      case 'delete':
        $currencies_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        $currency_query = tep_db_query("select code from " . TABLE_CURRENCIES . " where currencies_id = '" . (int)$currencies_id . "'");
        $currency = tep_db_fetch_array($currency_query);

        $remove_currency = true;
        if ($currency['code'] == DEFAULT_CURRENCY) {
          $remove_currency = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_CURRENCY, 'error');
        }
        break;
    }
  }

  $currency_select = array('USD' => array('title' => 'U.S. Dollar', 'code' => 'USD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'EUR' => array('title' => 'Euro', 'code' => 'EUR', 'symbol_left' => '', 'symbol_right' => '€', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'JPY' => array('title' => 'Japanese Yen', 'code' => 'JPY', 'symbol_left' => '¥', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'GBP' => array('title' => 'Pounds Sterling', 'code' => 'GBP', 'symbol_left' => '£', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'CHF' => array('title' => 'Swiss Franc', 'code' => 'CHF', 'symbol_left' => '', 'symbol_right' => 'CHF', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'AUD' => array('title' => 'Australian Dollar', 'code' => 'AUD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'CAD' => array('title' => 'Canadian Dollar', 'code' => 'CAD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'SEK' => array('title' => 'Swedish Krona', 'code' => 'SEK', 'symbol_left' => '', 'symbol_right' => 'kr', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'HKD' => array('title' => 'Hong Kong Dollar', 'code' => 'HKD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'NOK' => array('title' => 'Norwegian Krone', 'code' => 'NOK', 'symbol_left' => 'kr', 'symbol_right' => '', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'NZD' => array('title' => 'New Zealand Dollar', 'code' => 'NZD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'MXN' => array('title' => 'Mexican Peso', 'code' => 'MXN', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'SGD' => array('title' => 'Singapore Dollar', 'code' => 'SGD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'BRL' => array('title' => 'Brazilian Real', 'code' => 'BRL', 'symbol_left' => 'R$', 'symbol_right' => '', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'CNY' => array('title' => 'Chinese RMB', 'code' => 'CNY', 'symbol_left' => '￥', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'CZK' => array('title' => 'Czech Koruna', 'code' => 'CZK', 'symbol_left' => '', 'symbol_right' => 'Kč', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'DKK' => array('title' => 'Danish Krone', 'code' => 'DKK', 'symbol_left' => '', 'symbol_right' => 'kr', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'HUF' => array('title' => 'Hungarian Forint', 'code' => 'HUF', 'symbol_left' => '', 'symbol_right' => 'Ft', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'ILS' => array('title' => 'Israeli New Shekel', 'code' => 'ILS', 'symbol_left' => '₪', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'INR' => array('title' => 'Indian Rupee', 'code' => 'INR', 'symbol_left' => 'Rs.', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'MYR' => array('title' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol_left' => 'RM', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'PHP' => array('title' => 'Philippine Peso', 'code' => 'PHP', 'symbol_left' => 'Php', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'PLN' => array('title' => 'Polish Zloty', 'code' => 'PLN', 'symbol_left' => '', 'symbol_right' => 'zł', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'THB' => array('title' => 'Thai Baht', 'code' => 'THB', 'symbol_left' => '', 'symbol_right' => '฿', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'TWD' => array('title' => 'Taiwan New Dollar', 'code' => 'TWD', 'symbol_left' => 'NT$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'));

  $currency_select_array = array(array('id' => '', 'text' => TEXT_INFO_COMMON_CURRENCIES));
  foreach ($currency_select as $cs) {
    if (!isset($currencies->currencies[$cs['code']])) {
      $currency_select_array[] = array('id' => $cs['code'], 'text' => '[' . $cs['code'] . '] ' . $cs['title']);
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

<script type="text/javascript">
var currency_select = new Array();
<?php
  foreach ($currency_select_array as $cs) {
    if (!empty($cs['id'])) {
      echo 'currency_select["' . $cs['id'] . '"] = new Array("' . $currency_select[$cs['id']]['title'] . '", "' . $currency_select[$cs['id']]['symbol_left'] . '", "' . $currency_select[$cs['id']]['symbol_right'] . '", "' . $currency_select[$cs['id']]['decimal_point'] . '", "' . $currency_select[$cs['id']]['thousands_point'] . '", "' . $currency_select[$cs['id']]['decimal_places'] . '");' . "\n";
    }
  }
?>

function updateForm() {
  var cs = document.forms["currencies"].cs[document.forms["currencies"].cs.selectedIndex].value;

  document.forms["currencies"].title.value = currency_select[cs][0];
  document.forms["currencies"].code.value = cs;
  document.forms["currencies"].symbol_left.value = currency_select[cs][1];
  document.forms["currencies"].symbol_right.value = currency_select[cs][2];
  document.forms["currencies"].decimal_point.value = currency_select[cs][3];
  document.forms["currencies"].thousands_point.value = currency_select[cs][4];
  document.forms["currencies"].decimal_places.value = currency_select[cs][5];
  document.forms["currencies"].value.value = 1;
}
</script>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE . $what; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th><?php echo TABLE_HEADING_CURRENCY_NAME; ?></th>
                   <th><?php echo TABLE_HEADING_CURRENCY_CODES; ?></th>				   
                   <th class="text-right"><?php echo TABLE_HEADING_CURRENCY_VALUE; ?></th>				   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>				   

                </tr>
              </thead>
              <tbody>
<?php
// multi stores
                $currency_query_raw = "select currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, last_updated, value, currencies_to_stores from " . TABLE_CURRENCIES . " order by title";
                $currency_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $currency_query_raw, $currency_query_numrows);
                $currency_query = tep_db_query($currency_query_raw);
                while ($currency = tep_db_fetch_array($currency_query)) {
                    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $currency['currencies_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                       $cInfo = new objectInfo($currency);
                    }

                    if (isset($cInfo) && is_object($cInfo) && ($currency['currencies_id'] == $cInfo->currencies_id) ) {
                       echo '<tr class="active" onclick="document.location.href=\'' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=edit') . '\'">' . "\n";
                    } else {
                       echo '<tr onclick="document.location.href=\'' . tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currency['currencies_id']) . '\'">' . "\n";
                    }

                    if (DEFAULT_CURRENCY == $currency['code']) {
                        echo '   <td class="text-warning small_text"><strong>' . $currency['title'] . ' (' . TEXT_DEFAULT . ')</strong></td>' . "\n";
                    } else {
                        echo '   <td>' . $currency['title'] . '</td>' . "\n";
                    }
?>
                    <td ><?php echo $currency['code']; ?></td>
                    <td class="text-right"><?php echo number_format($currency['value'], 8); ?></td>
                    <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currency['currencies_id']. '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currency['currencies_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $currency['currencies_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL ;
?>
                                   </div> 
				    </td>	              
				</tr>
<?php							  
              if (isset($cInfo) && is_object($cInfo) && ($currency['currencies_id'] == $cInfo->currencies_id) && isset($HTTP_GET_VARS['action'])) { 
// BOF multi stores
    $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
    while ($stores_stores = tep_db_fetch_array($stores_query)) {
      $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
    }	
    $currenc_to_stores_array = explode(',', $cInfo->currencies_to_stores); // multi stores
    $currenc_to_stores_array = array_slice($currenc_to_stores_array, 1); // remove "@" from the array	 // multi stores	
// EOF multi stores	
		  
 	                             $alertClass = '';
                                 switch ($action) {
 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_CURRENCY . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_bs_form('status', FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=deleteconfirm') ;
									  
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $currency['title']  . '</p>' . PHP_EOL;
 									
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
                                      break;									 									
 	
		                            case 'edit':
								
 			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('currencies', FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=save', 'post', 'class="form-horizontal" role="form"',  'id_edit_currencies') . PHP_EOL;	
									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('title', $cInfo->title,  TEXT_INFO_CURRENCY_TITLE, 'id_input_cur_title' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_TITLE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	

									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('code', $cInfo->code,  TEXT_INFO_CURRENCY_CODE, 'id_input_cur_code' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_CODE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('symbol_left', tep_decode_specialchars( $cInfo->symbol_left ),  TEXT_INFO_CURRENCY_SYMBOL_LEFT, 'id_input_cur_sym_lft' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_SYMBOL_LEFT, '' ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	

									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('symbol_right', tep_decode_specialchars( $cInfo->symbol_right ),  TEXT_INFO_CURRENCY_SYMBOL_RIGHT, 'id_input_cur_sym_rght' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_SYMBOL_RIGHT, ''  ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('decimal_point', $cInfo->decimal_point,  TEXT_INFO_CURRENCY_DECIMAL_POINT, 'id_input_cur_dec_poi' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_DECIMAL_POINT, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('thousands_point', $cInfo->thousands_point,  TEXT_INFO_CURRENCY_THOUSANDS_POINT, 'id_input_cur_thou_point' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_THOUSANDS_POINT, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('decimal_places', $cInfo->decimal_places,  TEXT_INFO_CURRENCY_DECIMAL_PLACES, 'id_input_cur_dec_place' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_DECIMAL_PLACES, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('value', $cInfo->value,  TEXT_INFO_CURRENCY_VALUE, 'id_input_cur_value' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_VALUE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
									   if (DEFAULT_CURRENCY != $cInfo->code) {
			                              $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents         .= '                         <div class="panel-heading">' . TEXT_SET_DEFAULT . '</div>' . PHP_EOL;
			                              $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
                                          $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										  $contents         .=                                 tep_bs_checkbox_field('default', null, TEXT_INFO_SET_AS_DEFAULT, 'id_set_standard', null, 'checkbox checkbox-success' ) . ' '  ;									   
                                          $contents         .= '                           </div>' . PHP_EOL ;										  
		                                  $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
		                                  $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  
									   }
 									   
// BOF multi stores
									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
			                              $contents            .= '                        <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents            .= '                           <div class="panel-heading">' . TEXT_CURRENCY_TO_STORES . '</div>' . PHP_EOL;
			                              $contents            .= '                           <div class="panel-body">' . PHP_EOL;										       
                                          for ($i = 0; $i < count($stores_array); $i++) {
	                                         $contents .= '                                        <div class="form-group">' . PHP_EOL ;
                                             $contents .= '                                              ' .  tep_bs_checkbox_field('stores_currencies[' . $stores_array[$i]['id'] . ']',  $stores_array[$i]['id'], $stores_array[$i]['text'], 'input_stores_'.$stores_array[$i]['id'], ((in_array($stores_array[$i]['id'], $currenc_to_stores_array)) ? 1: 0) ,  'checkbox checkbox-success') . PHP_EOL ;
                                             $contents .= '                                        </div>'. PHP_EOL  ;					 	
                                          } 
		                                  $contents .= '                                      </div>' . PHP_EOL; // end div stores	panel body
		                                  $contents .= '                                   </div>' . PHP_EOL; // end div stores 	panel										  
									  }										  
// EOF multi stores
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove',  tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;	
									
                                    default :
// bof multi stores		
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . $cInfo->title . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-4 col-md-4">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 			
			                            $contents .= '                              ' . TEXT_INFO_CURRENCY_TITLE . ' '   . $cInfo->title . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_CURRENCY_CODE . ' ' . $cInfo->code . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;	 									
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_CURRENCY_SYMBOL_LEFT . ' : ' . tep_decode_specialchars( $cInfo->symbol_left ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;									
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_CURRENCY_SYMBOL_RIGHT . ' : ' . tep_decode_specialchars( $cInfo->symbol_right )  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;									
										
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
			                            $contents .= '                              ' . TEXT_INFO_CURRENCY_VALUE . ' : ' . number_format($cInfo->value, 8) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
																				
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
 										
			                            $contents .= '                      <div class="col-xs-12 col-sm-4 col-md-4">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;

										$contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_CURRENCY_DECIMAL_POINT . ' ' . $cInfo->decimal_point. PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;								
										
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_INFO_CURRENCY_THOUSANDS_POINT . ' ' . $cInfo->thousands_point . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
	                                    $contents .= '                              ' . TEXT_INFO_CURRENCY_DECIMAL_PLACES . ' ' . $cInfo->decimal_places . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                                    
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
	                                    $contents .= '                              ' . TEXT_INFO_CURRENCY_LAST_UPDATED . ' ' . tep_date_short($cInfo->last_updated) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;						                                    
										
                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
	                                    $contents .= '                              ' . TEXT_INFO_CURRENCY_EXAMPLE . ' ' . tep_decode_specialchars( $currencies->format('30', false, DEFAULT_CURRENCY) ) . ' = ' . 
										                                                                                   tep_decode_specialchars( $currencies->format('30', true, $cInfo->code) ) . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;											
						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
										
			                            $contents .= '                      <div class="col-xs-12 col-sm-4 col-md-4">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
										
                                       for ($i = 0; $i < count($stores_array); $i++) {
                                           if (in_array($stores_array[$i]['id'], $currenc_to_stores_array)) {  
                                              $currency_to_stores .= $stores_array[$i]['text'] . '<br />'; 
                                           }
                                        } // end for ($i = 0; $i < count($stores_array); $i++)
											
									    if ( !tep_not_null( $currency_to_stores ) ) $currency_to_stores = TEXT_STORES_NONE ;					

										$contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_CURRENCY_TO_STORES . '<br /> ' . $currency_to_stores  . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;								
										
							
						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;										
                                         
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_CURRENCIES ), null, null, 'btn-default text-danger') . PHP_EOL;										
									
                                      break	 ;							

                                 }
	
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel body
		                         $contents_footer .= '                      </div>' . PHP_EOL; // end div 	panel
		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
              }	 // end if (isset($cInfo) && is_object($cInfo) && ($custo   
  }
?>  		  
			  </tbody>
		    </table>
		</div>	
    </table>
	
   <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
          <div class="row">
            <div class="smallText hidden-xs mark"><?php echo $currency_split->display_count($currency_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CURRENCIES); ?></div>
            <div class="smallText mark text-right"><?php echo $currency_split->display_links($currency_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>	   
		  </div>
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">	  
<?php 
                if (CURRENCY_SERVER_PRIMARY) { 
				     echo tep_draw_bs_button(IMAGE_UPDATE_CURRENCIES, 'refresh', tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=update')); 
			    } 
                echo tep_draw_bs_button(IMAGE_NEW_CURRENCY, 'plus', null,'data-toggle="modal" data-target="#new_currency"')
				//tep_draw_button(IMAGE_NEW_CURRENCY, 'plus', tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=new')); 
?>
            </div>
 
<?php
          }
?>			  

    </table>		  
       <div class="modal fade"  id="new_currency" role="dialog" aria-labelledby="new_currency" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_bs_form('currencies', FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert', 'post', 'role="form"',  'id_new_currencies') ;
				?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_CURRENCY; ?></h4>
                  </div>
                  <div class="modal-body">
<?php	
// BOF multi stores
                                       $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
                                       while ($stores_stores = tep_db_fetch_array($stores_query)) {
                                           $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
                                       }	 
// EOF multi stores 
	
 			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
//			                           $contents            .= '               ' . tep_draw_bs_form('currencies', FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->currencies_id . '&action=save', 'post', 'class="form-horizontal" role="form"',  'id_new_currencies') . PHP_EOL;	
									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('title',null,  TEXT_INFO_CURRENCY_TITLE, 'id_input_cur_title' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_TITLE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	

									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('code', null,  TEXT_INFO_CURRENCY_CODE, 'id_input_cur_code' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_CODE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('symbol_left', null,  TEXT_INFO_CURRENCY_SYMBOL_LEFT, 'id_input_cur_sym_lft' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_SYMBOL_LEFT, '' ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	

									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('symbol_right', null,  TEXT_INFO_CURRENCY_SYMBOL_RIGHT, 'id_input_cur_sym_rght' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_SYMBOL_RIGHT, ''  ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('decimal_point', null,  TEXT_INFO_CURRENCY_DECIMAL_POINT, 'id_input_cur_dec_poi' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_DECIMAL_POINT, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('thousands_point', null,  TEXT_INFO_CURRENCY_THOUSANDS_POINT, 'id_input_cur_thou_point' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_THOUSANDS_POINT, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('decimal_places', null,  TEXT_INFO_CURRENCY_DECIMAL_PLACES, 'id_input_cur_dec_place' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_DECIMAL_PLACES, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('value', null,  TEXT_INFO_CURRENCY_VALUE, 'id_input_cur_value' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_CURRENCY_VALUE, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
									   $contents            .= '                           <br />' . PHP_EOL;	
									   
									   $contents            .= '   	                   <div class="clearfix"></div>' . PHP_EOL;	
				//					   if (DEFAULT_CURRENCY != $cInfo->code) {
			                              $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents         .= '                         <div class="panel-heading">' . TEXT_SET_DEFAULT . '</div>' . PHP_EOL;
			                              $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
                                          $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										  $contents         .=                                 tep_bs_checkbox_field('default', null, TEXT_INFO_SET_AS_DEFAULT, 'id_set_standard', null, 'checkbox checkbox-success' ) . ' '  ;									   
                                          $contents         .= '                           </div>' . PHP_EOL ;										  
		                                  $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
		                                  $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  
//									   }
 									   
// BOF multi stores
									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
			                              $contents            .= '                        <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents            .= '                           <div class="panel-heading">' . TEXT_CURRENCY_TO_STORES . '</div>' . PHP_EOL;
			                              $contents            .= '                           <div class="panel-body">' . PHP_EOL;										       
                                          for ($i = 0; $i < count($stores_array); $i++) {
	                                         $contents .= '                                        <div class="form-group">' . PHP_EOL ;
                                             $contents .= '                                              ' .  tep_bs_checkbox_field('stores_currencies[' . $stores_array[$i]['id'] . ']',  null, $stores_array[$i]['text'], 'input_stores_'.$stores_array[$i]['id'], null,  'checkbox checkbox-success') . PHP_EOL ;
                                             $contents .= '                                        </div>'. PHP_EOL  ;					 	
                                          } 
		                                  $contents .= '                                      </div>' . PHP_EOL; // end div stores	panel body
		                                  $contents .= '                                   </div>' . PHP_EOL; // end div stores 	panel										  
									  }
// EOF multi stores
?>									   
                         <div class="full-iframe" width="100%"> 
                             <?php echo $contents  ; ?>
                         </div> 
   
                     </form>             
                  </div> <!-- end div modal-body -->
                  <div class="modal-footer">
                  <?php echo tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_CURRENCIES, 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $HTTP_GET_VARS['cID'])); ?>
				  
                  </div> <!-- end div modal-footer -->              

              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_currency -->				  
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
