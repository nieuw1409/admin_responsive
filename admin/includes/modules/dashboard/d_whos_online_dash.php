<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce
  Copyright (c) 2013 Robin Ellis

  Released under the GNU General Public License
*/


  class d_whos_online_dash {
    var $code = 'd_whos_online_dash';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function d_whos_online_dash() {
      $this->title = MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_TITLE;
      $this->description = MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_DESCRIPTION;

      if ( defined('MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_STATUS == 'True');
      }
    }

  function getOutput() {
	  
	global $HTTP_GET_VARS, $_SERVER ;

	$xx_mins_ago = (time() - 900);
	// remove entries that have expired
	tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");

	// Automatic refresh times in seconds and display names
	//   Time and Display Text order must match between the arrays
	//   "None" is handled separately in the code
	$refresh_time = array(  15,   30,    60,     120,     300,    600 );
	$refresh_display = array('0:15', '0:30', '1:00', '2:00', '5:00', '10:00' );
	$refresh_values = array();
	$refresh_values[] = array('id' => 'none', 'text' => TEXT_NONE_);
	$refresh_values[] = array('id' => '15', 'text' => '0:15');
	$refresh_values[] = array('id' => '30', 'text' => '0:30');
	$refresh_values[] = array('id' => '60', 'text' => '1:00');
	$refresh_values[] = array('id' => '120', 'text' => '2:00');
	$refresh_values[] = array('id' => '300', 'text' => '5:00');
	$refresh_values[] = array('id' => '600', 'text' => '10:00');

	if(  isset($HTTP_GET_VARS['refresh'])&& is_numeric($HTTP_GET_VARS['refresh'])  ){
	    $output .= '<meta http-equiv="refresh" content="' . htmlspecialchars($HTTP_GET_VARS['refresh']) . ';URL=' . FILENAME_DASH_WHOS_ONLINE . '?' . htmlspecialchars($_SERVER["QUERY_STRING"]) . '">';
	}
	
    $output .= '<div class="panel panel-default">' . PHP_EOL ;	
    $output .= ' <div class="panel-body">' . PHP_EOL ;		
    $output .=  tep_draw_bs_form('update', FILENAME_DASH_WHOS_ONLINE, '', 'get', 'role="form"', 'id_dash_who_online');

    if (isset($HTTP_GET_VARS['info'])) {
		$output .=  tep_draw_hidden_field('info', $HTTP_GET_VARS['info']);
	}

	$output .= '<div class="form-group">' . PHP_EOL ;   	
	$output .=     tep_draw_hidden_field(tep_session_name(), tep_session_id());
	$output .= '</div>' . PHP_EOL ;
	$output .= '<div class="form-group">' . PHP_EOL ;	
	$output .=      tep_draw_bs_pull_down_menu( 'refresh', $refresh_values, $HTTP_GET_VARS['refresh'], TABLE_HEADING_REFRESH_RATE, 'id_select_refresh_who_online', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
	//tep_draw_pull_down_menu('refresh', $refresh_values, $_GET['refresh'], 'onChange="this.form.submit();"') . '</td></tr>';
	$output .= '</div>' . PHP_EOL ;	
	  
	$output .= '	<table class="table table-hover table-condensed table-responsive table-striped">'. PHP_EOL ;
	$output .= '			<thead>'. PHP_EOL ;
	$output .= '				<tr>'. PHP_EOL ;
	$output .= '	                <th class="text-center">'             . TABLE_HEADING_ONLINE       . '</th>'. PHP_EOL ;
	$output .= '	                <th class="text-center">            ' . TABLE_HEADING_FULL_NAME     . '</th>'. PHP_EOL ;
	$output .= '	                <th class="text-center">            ' . TABLE_HEADING_IP_ADDRESS    . '</th>'. PHP_EOL ;
	$output .= '	                <th class="text-center hidden-xs">  ' . TABLE_HEADING_ENTRY_TIME    . '</th>'. PHP_EOL ;
	$output .= '	                <th class="text-center">            ' . TABLE_HEADING_LAST_CLICK    . '</th>'. PHP_EOL ;
	$output .= '	                <th class="text-center">            ' . TABLE_HEADING_LAST_PAGE_URL . '</th>'. PHP_EOL ;
	$output .= '	              </tr>'. PHP_EOL ;
	$output .= '			</thead>'. PHP_EOL ;
	$output .= '			<tbody>	'. PHP_EOL ;
	
	$whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, session_id from " . TABLE_WHOS_ONLINE);
  
	while ($whos_online = tep_db_fetch_array($whos_online_query)) {
		$time_online = (time() - $whos_online['time_entry']);
		$output .= '<tr>' . PHP_EOL ;
		$output .= '  <td class="text-center">' . gmdate('H:i:s', $time_online) . '</td>' . PHP_EOL ; 
		$output .= '  <td class="text-center">' . $whos_online['full_name'] . '</td>' .  PHP_EOL ; 
		$output .= '  <td class="text-center">' . $whos_online['ip_address'] . '</td>' .  PHP_EOL ; 
		$output .= '  <td  class="text-center hidden-xs">' . date('H:i:s', $whos_online['time_entry']) . '</td>' .  PHP_EOL ; 
		$output .= '  <td class="text-center">' . date('H:i:s', $whos_online['time_last_click']) . '</td>' .  PHP_EOL ; 
		$output .= '  <td class="text-center">';

		if (preg_match('/^(.*)' . tep_session_name() . '=[a-f,0-9]+[&]*(.*)/i', $whos_online['last_page_url'], $array)) { 
			$output .= $array[1] . $array[2];
		} else { 
			$output .= $whos_online['last_page_url']; 
		} 
		$output .= '  </td>';		
		$output .= '</tr>'. PHP_EOL ;
	}
	
	$output .= '	        </tbody>'. PHP_EOL ;
	$output .= '	</table>'. PHP_EOL ;
//	$output .= '		  

	$output .=  tep_draw_bs_button(sprintf(TEXT_NUMBER_OF_CUSTOMERS, tep_db_num_rows($whos_online_query)), 'question', tep_href_link(FILENAME_WHOS_ONLINE), null, null, 'btn-primary') . PHP_EOL ;

    $output .= ' </div>' . PHP_EOL ;		
    $output .= '</div>' . PHP_EOL ;	
	//'<tr class="dataTableRow"><td class="smallText" colspan="7">' .
	//	   '<a href="' . FILENAME_WHOS_ONLINE . '" target="_self">' . sprintf(TEXT_NUMBER_OF_CUSTOMERS, tep_db_num_rows($whos_online_query)) . 
	//	   '</a>&nbsp&nbsp</td></tr></table>';

      return $output;
     }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Whos Online Dash Module', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_STATUS', 'True', 'Do you want to show Whos Online on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_STATUS', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DASH_SORT_ORDER');
    }
  }
?>