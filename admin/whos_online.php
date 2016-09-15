<?php
/* 
  $Id: whos_online.php,v 3.5 2008/08/21 SteveDallas Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2015 osCommerce
  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  require(DIR_WS_INCLUDES . 'template_top.php');
// Version number text
  $wo_version = '3.5.4';
/*
  Configuration Values
    Set these to easily personalize your Whos Online
*/

// Display format for "last refresh time" (12 or 24 hour clock)
  $time_format = 24;
// Seconds that a visitor is considered "active"
  $active_time = 300;
// Seconds before visitor is removed from display
  $track_time = 900;

 // # mjc mike challis wordwrap referrer url
  $referrer_wordwrap_chars = 500; // <= set to number of characters to wrap to

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

  $show_type = array();
  $show_type[] = array('id' => '', 'text' => TEXT_NONE_);
  $show_type[] = array('id' => 'all', 'text' => TEXT_ALL);
  $show_type[] = array('id' => 'bots', 'text' => TEXT_BOTS);
  $show_type[] = array('id' => 'cust', 'text' => TEXT_CUSTOMERS);

// Images used for status lights
  $status_active_cart = 'icon_status_cart_top.gif'; // replace word cart with green if you dont want the new icon.
  $status_active_cart_top = 'icon_status_cart_top.gif';
  $status_inactive_cart = 'icon_status_cart_red.gif';
  $status_active_nocart = 'summary_customers.gif';
  $status_inactive_nocart = 'summary_customers_red.gif';
  $status_active_bot = 'icon_status_green_border_light.gif';
  $status_inactive_bot = 'icon_status_red_border_light.gif';

// Text color used for table entries - different colored text for different users
//   Named colors and Hex values should work fine here
  $fg_color_bot = 'maroon';
  $fg_color_admin = '#0000AC';
  $fg_color_guest = 'green';
  $fg_color_account = 'blue'; // '#000000'; // Black

// Determines status and cart of visitor and displays appropriate icon.
  // mjc mike challis modified next line, added $the_ip for count active guests and customers feature
  function tep_check_cart($customer_id, $session_id, $the_ip) {
    global $status_active_cart, $status_active_cart_top, $status_inactive_cart, $status_active_nocart, $status_inactive_nocart, $status_inactive_bot, $status_active_bot, $active_time;
    // mjc added next line for count active guests and customers without duplicates
    global $ip_addrs_active;

    // Pull Session data from the correct source.

    if (STORE_SESSIONS == 'mysql') {
      $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $session_id . "'");
      $session_data = tep_db_fetch_array($session_data);
      $session_data = trim($session_data['value']);
    } else {
      if ((file_exists(tep_session_save_path() . '/sess_' . $session_id)) && (filesize(tep_session_save_path() . '/sess_' . $session_id) > 0)) {
        $session_data = file(tep_session_save_path() . '/sess_' . $session_id);
        $session_data = trim(implode('', $session_data));
      }
    }
    // mjc mike challis bof added to fix shopping cart indicator bug
    # the bug was .. When one of the visitors has an item in their cart,
    # every "customer" has the Active with Cart or Inactive with Cart icon blinking.

    $products =0;
    if ($length = strlen($session_data)) {
      #contents";a:0: <= no products in cart
      #contents";a:5: <= 5 products in cart
      preg_match('|contents";a:(\d+):|i',$session_data, $find);
      $products = $find[1];
    }
    // mjc mike challis eof added to fix shopping cart indicator bug

    $which_query = $session_data;
    $who_data =   tep_db_query("select time_entry, time_last_click
                                 from " . TABLE_WHOS_ONLINE . "
                                 where session_id='" . $session_id . "'");
    $who_query = tep_db_fetch_array($who_data);

    // Determine if visitor active/inactive
    $xx_mins_ago_long = (time() - $active_time);

    if($customer_id < 0) {
    // inactive
      if ($who_query['time_last_click'] < $xx_mins_ago_long) {
		return tep_glyphicon( 'remove-circle', 'danger' ) ;  //. TEXT_STATUS_INACTIVE_BOT ;
//        return tep_image(DIR_WS_IMAGES . $status_inactive_bot, TEXT_STATUS_INACTIVE_BOT);
    // active
      } else {
		return tep_glyphicon( 'remove-circle', 'success' ) ; // .  TEXT_STATUS_ACTIVE_BOT ;
//        return tep_image(DIR_WS_IMAGES . $status_active_bot, TEXT_STATUS_ACTIVE_BOT);
      }
    }

    // Determine active/inactive and cart/no cart status
    // no cart
    // mjc mike challis modified the next line to fix shopping cart indicator bug
    if ($products == 0 ) {
      // inactive
      if ($who_query['time_last_click'] < $xx_mins_ago_long) {
		return tep_glyphicon( 'user', 'danger' ) ; // .         TEXT_STATUS_INACTIVE_NOCART ;
//        return tep_image(DIR_WS_IMAGES . $status_inactive_nocart, TEXT_STATUS_INACTIVE_NOCART);
      // active
      } else {
            // mjc mike challis added next 3 lines for count active guests and customers without duplicates
            if (!in_array($the_ip,$ip_addrs_active)) {
             $the_ip != $_SERVER["REMOTE_ADDR"] and $ip_addrs_active[]=$the_ip;
            }
        return tep_glyphicon( 'user', 'success' ) ; //           TEXT_STATUS_ACTIVE_NOCART ;			
//        return tep_image(DIR_WS_IMAGES . $status_active_nocart, TEXT_STATUS_ACTIVE_NOCART);
      }
    // cart
    } else {
      // inactive
      if ($who_query['time_last_click'] < $xx_mins_ago_long) {
		return tep_glyphicon( 'shopping-cart', 'danger' ) ; // TEXT_STATUS_INACTIVE_CART ;
//        return tep_image(DIR_WS_IMAGES . $status_inactive_cart, TEXT_STATUS_INACTIVE_CART);
      // active
      } else {
        // mjc mike challis added next 3 lines for count active guests and customers without duplicates
            if (!in_array($the_ip,$ip_addrs_active)) {
             $the_ip != $_SERVER["REMOTE_ADDR"] and $ip_addrs_active[]=$the_ip;
            }
	    return tep_glyphicon( 'shopping-cart', 'success' ) ;  // . TEXT_STATUS_ACTIVE_CART ;
//        return tep_image(DIR_WS_IMAGES . $status_active_cart, TEXT_STATUS_ACTIVE_CART); 
      }
    }
  }
 
  /* Display the details about a visitor */
  function display_details() {
    global $whos_online, $is_bot, $is_admin, $is_guest, $is_account;
    // mjc mike challis added next line for wordwrap
    global $referrer_wordwrap_chars;
	
	$extra_info  = '<div class="col-xs-12">'. PHP_EOL ;
	$extra_info .= '  <div class="col-xs-6">'. PHP_EOL ;
    $extra_info .= '                        <ul class="list-group">' . PHP_EOL;
    $extra_info .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$extra_info .= '                              ' . TABLE_HEADING_FULL_NAME . ':</b> ' . $whos_online['full_name'] . PHP_EOL;
	$extra_info .= '                          </li>' . PHP_EOL;	
    if ( !$is_bot ){	
	   $extra_info .= '                       <li class="list-group-item">' . PHP_EOL; 		
	   $extra_info .= '                           ' . TABLE_HEADING_CUSTOMER_ID . ':</b> ' . $whos_online['customer_id'] . PHP_EOL;
	   $extra_info .= '                       </li>' . PHP_EOL;			   
    }	   
	$extra_info .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$extra_info .= '                              ' . TABLE_HEADING_IP_ADDRESS . ':</b> ' . "<a href='http://www.dnsstuff.com/tools/whois.ch?ip=$whos_online[ip_address]' target='_new'>" . $whos_online['ip_address'] . "</a>" . PHP_EOL;
	$extra_info .= '                          </li>' . PHP_EOL;			   
	$extra_info .= '                        </ul>' . PHP_EOL;

	$extra_info .= '  </div>'. PHP_EOL ;
	$extra_info .= '  <div class="col-xs-6">'. PHP_EOL ;	

    $extra_info .= '                        <ul class="list-group">' . PHP_EOL;
	$extra_info .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$extra_info .= '                              ' . TEXT_USER_AGENT . ':</b> ' . $whos_online['user_agent'] . PHP_EOL;
	$extra_info .= '                          </li>' . PHP_EOL;	
    if ( $whos_online['session_id'] != $whos_online['ip_address'] ) {	
	   $extra_info .= '                          <li class="list-group-item">' . PHP_EOL; 		
	   $extra_info .= '                              ' . TEXT_OSCID . ':</b> ' . $whos_online['session_id'] . PHP_EOL;
	   $extra_info .= '                          </li>' . PHP_EOL;		
    }	   
    if($whos_online['http_referer'] != "" ) {
	   $extra_info .= '                          <li class="list-group-item">' . PHP_EOL; 		
	   $extra_info .= '                              ' . TABLE_HEADING_HTTP_REFERER . ':</b> ' . wordwrap(htmlspecialchars($whos_online['http_referer']), $referrer_wordwrap_chars, "<br />", true) . PHP_EOL;
	   $extra_info .= '                          </li>' . PHP_EOL;				   
    }	   
	$extra_info .= '                        </ul>' . PHP_EOL;
	$extra_info .= '  </div>'. PHP_EOL ;
	$extra_info .= '</div>'. PHP_EOL ;	
	
	echo $extra_info ;

  }

  // Time to remove old entries
  $xx_mins_ago = (time() - $track_time);

  // remove entries that have expired
  tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");


  // WOL 1.6 - Cleaned up refresh
  // mjc mike challis bof - more standard use of get vars on refresh
  if(  isset($HTTP_GET_VARS['refresh'])&& is_numeric($HTTP_GET_VARS['refresh'])  ){
    echo '<meta http-equiv="refresh" content="' . htmlspecialchars($HTTP_GET_VARS['refresh']) . ';URL=' . FILENAME_WHOS_ONLINE . '?' . htmlspecialchars($_SERVER["QUERY_STRING"]) . '">';
  }
  // mjc mike challis eof - more standard use of get vars on refresh
  // WOL 1.6 EOF
?>
   <h3><?php echo HEADING_TITLE . " " . $wo_version; ?></h3>
  <div class="row">
	<div class="col-xs-5">
<?php
        // select refresh time plus more info and show bots
	 	   $contents .= tep_draw_bs_form('who_online_update', FILENAME_WHOS_ONLINE, '', 'get', "role=form", "id_who_online_time");

		      $contents .= '      <div class="form-group">' . PHP_EOL ;
              if (isset($HTTP_GET_VARS['info'])) {
		         $contents .=        tep_draw_hidden_field('info', $HTTP_GET_VARS['info']);
		      }
		      $contents .=           tep_draw_hidden_field(tep_session_name(), tep_session_id());		
		      $contents .= '      </div>' . PHP_EOL ;	
			  
		      $contents .= '      <div class="form-group">' . PHP_EOL ;			  
		      $contents .=           tep_draw_bs_pull_down_menu( 'refresh', $refresh_values, $HTTP_GET_VARS['refresh'], TEXT_SET_REFRESH_RATE, "id_refresh_time", "col-xs-9", ' selectpicker show-tick ', "col-xs-3", 'left', 'onChange="this.form.submit();"' ) . PHP_EOL ;
		      $contents .= '      </div>' . PHP_EOL ;				  

		      $contents .= '      <div class="clearfix"></div> <br />' . PHP_EOL ;			  
			  
		      $contents .= '      <div class="form-group">' . PHP_EOL ;			  
		      $contents .=           tep_draw_bs_pull_down_menu( 'show', $show_type, $HTTP_GET_VARS['show'], TEXT_PROFILE_DISPLAY, "id_refresh_time", "col-xs-9", ' selectpicker show-tick ', "col-xs-3", 'left', 'onChange="this.form.submit();"' ) . PHP_EOL ;
		      $contents .= '      </div>' . PHP_EOL ;				  

		      $contents .= '      <div class="clearfix"></div> <br />' . PHP_EOL ;				   			  
			  
		      $contents .= '      <div class="form-group">' . PHP_EOL ;	
			  $contents .= '        <div class="checkbox checkbox-success">'. PHP_EOL  ;			  
		      $contents .=             tep_bs_checkbox_field( 'bots', 'show', TEXT_SHOW_BOTS, "id_show_bots", ($HTTP_GET_VARS['bots'] == 'show' ? true : false ), 'checkbox checkbox-success', 'col-xs-6', null, 'right', 'onChange="this.form.submit();"' ) . PHP_EOL ;
		      $contents .= '        </div>' . PHP_EOL ;			  
		      $contents .= '      </div>' . PHP_EOL ;	

		      $contents .= '      <div class="clearfix"></div> <br />' . PHP_EOL ;				  

		
		   $contents .= '</form>' . PHP_EOL ;
		  
		  echo $contents ;

?>		   

	</div>
	
	<div class="col-xs-7">
	  <div class="col-xs-6">	
 
<?php 

           $contents_info_1 .= '                        <ul class="list-group">' . PHP_EOL;
		   $contents_info_1 .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_info_1 .= '                              ' . tep_glyphicon( 'shopping-cart', 'success' ) . TEXT_STATUS_ACTIVE_CART . PHP_EOL;
		   $contents_info_1 .= '                          </li>' . PHP_EOL;										
		   $contents_info_1 .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_info_1 .= '                              ' . tep_glyphicon( 'user', 'success' ) .           TEXT_STATUS_ACTIVE_NOCART . PHP_EOL;
		   $contents_info_1 .= '                          </li>' . PHP_EOL;			   
		   $contents_info_1 .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_info_1 .= '                              ' . tep_glyphicon( 'remove-circle', 'success' ) .  TEXT_STATUS_ACTIVE_BOT . PHP_EOL;
		   $contents_info_1 .= '                          </li>' . PHP_EOL;			   
		   $contents_info_1 .= '                        </ul>' . PHP_EOL;
		   
		   echo $contents_info_1 ;
?>		 
 
	  </div>
	  <div class="col-xs-6">

<?php 
           $contents_info_2 .= '                        <ul class="list-group">' . PHP_EOL;
		   $contents_info_2 .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_info_2 .= '                              ' . tep_glyphicon( 'shopping-cart', 'danger' ) . TEXT_STATUS_INACTIVE_CART . PHP_EOL;
		   $contents_info_2 .= '                          </li>' . PHP_EOL;										
		   $contents_info_2 .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_info_2 .= '                              ' . tep_glyphicon( 'user', 'danger' ) .          TEXT_STATUS_INACTIVE_NOCART . PHP_EOL;
		   $contents_info_2 .= '                          </li>' . PHP_EOL;		
		   $contents_info_2 .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_info_2 .= '                              ' . tep_glyphicon( 'remove-circle', 'danger' ) . TEXT_STATUS_INACTIVE_BOT . PHP_EOL;
		   $contents_info_2 .= '                          </li>' . PHP_EOL;				   
		   $contents_info_2 .= '                        </ul>' . PHP_EOL;
		   
		   echo $contents_info_2 ;
?>
	  </div>	 
    </div>
   </div>
	
   <div colspan="2" class="panel panel-info" align="center">
      <div class="panel-body">
         <script type="text/javascript">
            <!-- Begin
            Stamp = new Date();
            document.write('<?php echo TEXT_LAST_REFRESH. '&nbsp;'; ?>');
            var Hours;
            var Mins;
            var Time;
            Hours = Stamp.getHours();
            if (<?php echo $time_format; ?> == 12) {
               if (Hours >= 12) {
                  Time = " pm";
                  Hours -= 12;
               } else {
                  Time = " am";
               }
               if (Hours == 0) {
                  Hours = 12;
               }
            } else {
                 Time = "";
            }
            Mins = Stamp.getMinutes();
            if (Mins < 10) {
                 Mins = "0" + Mins;
            }
            document.write('&nbsp;' + Hours + ":" + Mins + Time );
            // End -->
         </script>
      </div>
   </div>
	

<div class="row">
	<div class="col-md-9 col-xs-12">
	  <div class="table-responsive">
		<table class="table table-hover table-condensed table-responsive table-striped">
			<thead>     
				<tr>
					<th colspan="2" class="text-center">  <?php echo TABLE_HEADING_ONLINE; ?></th>
					<th class="text-center">              <?php echo TABLE_HEADING_FULL_NAME; ?></th>
					<th class="text-center">              <?php echo TABLE_HEADING_IP_ADDRESS; ?></th>
					<th class="text-center hidden-xs">    <?php echo TABLE_HEADING_ENTRY_TIME; ?></th>
					<th class="text-center">              <?php echo TABLE_HEADING_LAST_CLICK; ?></th>
					<th class="text-center">              <?php echo TABLE_HEADING_LAST_PAGE_URL; ?></th>
					<th class="text-center">              <?php echo TABLE_HEADING_USER_SESSION; ?></th>
					<th class="text-center hidden-xs">    <?php echo TABLE_HEADING_HTTP_REFERER		; ?></th>
				  </tr>
			</thead>
			<tbody>	
<?php		
			  // Order by is on Last Click. Also initialize total_bots and total_admin counts
			  $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, hostname, time_entry, time_last_click, last_page_url, http_referer, user_agent, session_id from " . TABLE_WHOS_ONLINE . ' order by time_last_click DESC');
			  $total_bots = 0;
			  $total_admin = 0;
			  $total_guests = 0;
			  $total_loggedon = 0;
			  $total_dupes = 0;
			  // mjc added next line for count active guests and customers feature
			  $ip_addrs_active = array();
			  $ip_addrs = array();
			  // mjc added next line to force info from the get var
			  isset($HTTP_GET_VARS['info']) and $info = $HTTP_GET_VARS['info'];
			  while ($whos_online = tep_db_fetch_array($whos_online_query)) {
				$time_online = ($whos_online['time_last_click'] - $whos_online['time_entry']);
    
				
				if ((!isset($HTTP_GET_VARS['info']) || (isset($HTTP_GET_VARS['info']) && ($HTTP_GET_VARS['info'] == $whos_online['session_id']))) && !isset($info)) {
				  $info = $whos_online['session_id'];
				}

				$hostname = $whos_online['hostname'];

				//Check for duplicates
				if (in_array($whos_online['ip_address'],$ip_addrs)) {$total_dupes++;};
				$ip_addrs[] = $whos_online['ip_address'];

				// Display Status
				//   Check who it is and set values
				$is_bot = $is_admin = $is_guest = $is_account = false;

				if ($whos_online['customer_id'] < 0) {
				  $total_bots++;
				  $fg_color = $fg_color_bot;
				  $is_bot = true;

				  // Admin detection
				} elseif ($whos_online['ip_address'] == $_SERVER["REMOTE_ADDR"]) {
				  $total_admin++;
				  $fg_color = $fg_color_admin;
				  $is_admin = true;
				// Guest detection (may include Bots not detected by Prevent Spider Sessions/spiders.txt)
				} elseif ($whos_online['customer_id'] == 0) {
				  $fg_color = $fg_color_guest;
				  $is_guest = true;
				  $total_guests++;
				// Everyone else (should only be account holders)
				} else {
				  $fg_color = $fg_color_account;
				  $is_account = true;
				  $total_loggedon++;
				}

				if (!($is_bot && !isset($HTTP_GET_VARS['bots']))) {

					if ($whos_online['session_id'] == $info) {
					   if($whos_online['http_referer'] != "")
					   {
						$http_referer_url = $whos_online['http_referer'];
					   }
					  // mjc added "onclick" to allow refresh by clicking on selected row
					  echo '<tr class="active"   onclick="document.location.href=\'' . tep_href_link(FILENAME_WHOS_ONLINE, tep_get_all_get_params(array('info', 'action')) . 'info=' . $whos_online['session_id'], 'NONSSL') . '\'">' . PHP_EOL ;
					} else {
					  echo '<tr                  onclick="document.location.href=\'' . tep_href_link(FILENAME_WHOS_ONLINE, tep_get_all_get_params(array('info', 'action')) . 'info=' . $whos_online['session_id'], 'NONSSL') . '\'">' . PHP_EOL ;
					}
?>
                              <td class="text-center"><?php echo '&nbsp;' . tep_check_cart($whos_online['customer_id'], $whos_online['session_id'], $whos_online['ip_address']); ?></td>	
                              
							  <!-- Time Online -->
                              <td class="text-center text-info"><?php echo gmdate('H:i:s', $time_online); ?></td>		

                         <!-- Name -->
<?php
							  $text_name = '<td class="text-center text-info">';

							  // WOL 1.6 Restructured to Check for Guest or Admin
							  if ( $is_guest || $is_admin ){
								$text_name .= $whos_online['full_name'] . '&nbsp;';
							  // Check for Bot
							  } elseif ( $is_bot ) {
								// Tokenize UserAgent and try to find Bots name
								$tok = strtok($whos_online['full_name']," ();/");
								while ($tok !== false) {  // edited from forum perfectpassion
								  if ( strlen(strtolower($tok)) > 3 )
									if ( !strstr(strtolower($tok), "mozilla") &&
										 !strstr(strtolower($tok), "compatible") &&
										 !strstr(strtolower($tok), "msie") &&
										 !strstr(strtolower($tok), "windows")
									   ) {
									  $text_name .= $tok ;
									  break;
									}
								  $tok = strtok(" ();/");
								}
							  // Check for Account
							  } elseif ( $is_account ) {
//								$text_name .= '<a HREF="customers.php?cID=' . $whos_online['customer_id'] . '&action=edit" . tep_adminid>';
//								$text_name .= '<p class="bg-success">' . $whos_online['full_name'] . '</p></a>';
								$text_name .= tep_draw_bs_button($whos_online['full_name'], 'pencil', tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $whos_online['customer_id'] . '&action=edit'), null, null, 'btn-default text-warning') ;
							  } else {
								$text_name .= TEXT_ERROR;
							  }
							  $text_name .= '</td>';
							  
							  echo $text_name ;
	?>							  
								  
							  <!-- IP Address -->
							  <td class="text-center text-info" valign="top">
								<?php
								// Show 'Admin' instead of IP for Admin
								if ( $is_admin ) {
								  echo '<p class="bg-warning">' . TEXT_ADMIN . '</p>' . PHP_EOL ;
								} elseif ( $hostname == 'unknown' ) {
								  echo '<p class="bg-danger">' . $hostname . '</p>' . PHP_EOL ;
								} else {
								  echo '<a href="http://www.infobyip.com/ip-' . $whos_online['ip_address'] . '.html" target="_blank">';						  
								  echo '<p class="bg-info">' . $hostname . '</font></a>' . PHP_EOL ;
								}
								?>
							  </td>							  
							  
<?php
							  if ($time_format == 12) {
								$format_string = "h:i:s&\\nb\sp;a";
							  } else {
								$format_string = "H:i:s";
							  }
?>

                              <!-- Time Entry -->
                              <td class="text-center text-info  hidden-xs"><?php echo date($format_string, $whos_online['time_entry']); ?></td>

                              <!-- Last Click -->
                              <td class="text-center text-info"><?php echo date($format_string, $whos_online['time_last_click']); ?></td>							  
							  
							  <!-- Last URL -->
							  <td class="text-center text-info"><?php
								$temp_url_link = $whos_online['last_page_url'];
								if (preg_match('/^(.*)' . tep_session_name() . '=[a-f,0-9]+[&]*(.*)/i', $whos_online['last_page_url'], $array)) {
								  $temp_url_display =  $array[1] . $array[2];
								} else {
								  $temp_url_display = $whos_online['last_page_url'];
								}

								// WOL 1.6 - Removes osCsid from the Last Click URL and the link
								if ( $osCsid_position = strpos($temp_url_display, "osCsid") )
								  $temp_url_display = substr_replace($temp_url_display, "", $osCsid_position - 1 );
								if ( $osCsid_position = strpos($temp_url_link, "osCsid") )
								  $temp_url_link = substr_replace($temp_url_link, "", $osCsid_position - 1 );

								// escape any special characters to conform to HTML DTD
								$temp_url_display = htmlspecialchars($temp_url_display);

								// alteration for last url product name  bof
								if (strpos($temp_url_link,'product_info.php')) {
								  if (strpos($temp_url_link,'products_id=')) {
									//Standard osC install using parameters
									$temp = strstr($temp_url_link,'?');
									$temp=str_replace('?','',$temp);
									$parameters= preg_split("/&/",$temp);

									$i=0;
									while($i < count($parameters)) {
									  $a= preg_split("/=/",$parameters[$i]);
									  if ($a[0]=="products_id") { $products_id=$a[1]; }
									  $i++;
									}
								  } elseif (strpos($temp_url_link,'products_id/')) {
									//osC search-engine safe URL
									$temp = strstr($temp_url_link,'products_id');
									$temparr= preg_split("/\//",$temp);
									$products_id=$temparr[1];
								  } else {
									//couldn't figure it out
									$products_id = '';
								  }
								  if ( tep_not_null($products_id) && is_numeric($products_id) ) {
									$product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION. " where products_id='" . (int)$products_id . "' and language_id = '" . (int)$languages_id . "'");
									$product = tep_db_fetch_array($product_query);
									$display_link = $product['products_name'].' <i>(Product)</i>';
								  } else {
									$display_link = $temp_url_display;
								  }
								} elseif (strpos($temp_url_link,'cPath')) {
								  if (strpos($temp_url_link,'cPath=')) {
									//Standard osC install using parameters
									$temp = strstr($temp_url_link,'?');
									$temp=str_replace('?','',$temp);
									$parameters= preg_split("/&/",$temp);

									$i=0;
									while($i < count($parameters)) {
									  $a= preg_split("/=/",$parameters[$i]);
									  if ($a[0]=="cPath") { $cat=$a[1]; }
									  $i++;
									}
								  } elseif (strpos($temp_url_link,'cPath/')) {
									//osC search-engine safe URL
									$temp = strstr($temp_url_link,'cPath');
									$temparr= preg_split("/\//",$temp);
									$cat=$temparr[1];
								  } else {
									//couldn't figure it out
									$cat = '';
								  }

								  $parameters= preg_split("/_/",$cat);

								  $i=0;
								  while($i < count($parameters)) {
									$category_query=tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id='" . $parameters[$i] . "' and language_id = '" . (int)$languages_id . "'");
									$category = tep_db_fetch_array($category_query);
									if ($i>0) { $cat_list.=' / ' . $category['categories_name']; } else { $cat_list=$category['categories_name']; }

									$i++;
								  }
								  $display_link = $cat_list.' <i>(Category)</i>';
								} else {
								  $display_link = $temp_url_display;
								}

								// alteration for last url product name  eof

								// Get product and category from Ultimate SEO URLs bof
								if ( preg_match('/^(.*)-p-(.*).html/', $temp_url_link, $matches) && is_numeric($matches[2]) ) {
								  $products_id = $matches[2];
								  $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id='" . (int)$products_id . "' and language_id = '" . (int)$languages_id . "'");
								  $product = tep_db_fetch_array($product_query);

								  $display_link = $product['products_name'].' <i>(Product)</i>';
								} elseif ( preg_match('/^(.*)-c-(.*).html/',$temp_url_link,$matches) ) {
								  $cat=$matches[2];
								  $parameters= preg_split("/_/",$cat);

								  $i=0;
								  while($i < count($parameters)) {
									$category_query=tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id='" . $parameters[$i] . "' and language_id = '" . (int)$languages_id . "'");
									$category = tep_db_fetch_array($category_query);
									if ($i>0) { $cat_list.=' / '.$category['categories_name']; } else { $cat_list=$category['categories_name']; }

									$i++;
								  }
								  $display_link = $cat_list.' <i>(Category)</i>';
								}
								// Get product and category from Ultimate SEO URLs eof

//								echo '<a href="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . htmlspecialchars($temp_url_link) . '" target="_blank"><p class="bg-info">' . $display_link . '</p></a></td>';
								echo '<a href="' .  htmlspecialchars($temp_url_link) . '" target="_blank"><p class="bg-info">' . $display_link . '</p></a></td>';
								
?>							  
							  <!-- osCsid? -->
							  <td  class="text-center text-info"><?php echo (($whos_online['session_id'] != $whos_online['ip_address']) ? TEXT_IN_SESSION : TEXT_NO_SESSION);?></td>

							  <!-- Referer? -->
							  <td class="text-center text-info hidden-xs"><?php echo (($whos_online['http_referer'] == "") ? TEXT_HTTP_REFERER_NOT_FOUND : TEXT_HTTP_REFERER_FOUND);?></td>
						   </tr>

<?php
					// mjc mchallis modified next line for more standard use of query get vars
					if (($HTTP_GET_VARS['show'] == 'all') || (($HTTP_GET_VARS['show'] == 'bots') && $is_bot) || (($HTTP_GET_VARS['show'] == 'cust') && ( $is_guest || $is_account || $is_admin )) ) {
?>
							<tr> 
							  <td class="text-center text-info  hidden-xs" colspan="9"><?php display_details(); ?></td>
							</tr>
<?php
					}
                } // closes "if $isbot statement
              } // closes "while" statement
     
	          //Display HTTP referer, if any
              // mjc mike challis added wordwrap to referrer url
              if(isset($http_referer_url)) {
?>
                  <tr>
                     <td class="mark text-left" colspan="9"><?php echo '<span class="bg-info">' . TEXT_HTTP_REFERER_URL . '</span>:<span class="text-info"><a href="' . htmlspecialchars($http_referer_url) . '" target="_blank">' . wordwrap(htmlspecialchars($http_referer_url), $referrer_wordwrap_chars, "<br />", true) . '</a></span>'; ?></td>
                  </tr>
<?php
              }			  
?>			  
		</tbody>
	</table>
  </div>
<?php	
    $total_sess = tep_db_num_rows($whos_online_query);
    // Subtract Bots and Me from Real Customers.  Only subtract me once as Dupes will remove others
    $total_cust = $total_sess - $total_dupes - $total_bots - ($total_admin > 1? 1 : $total_admin);
    $contents_info_total .= '                        <ul class="list-group">' . PHP_EOL;
	$contents_info_total .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$contents_info_total .= '                              ' . sprintf(TEXT_NUMBER_OF_CUSTOMERS, $total_sess) . PHP_EOL;
	$contents_info_total .= '                          </li>' . PHP_EOL;										
	$contents_info_total .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$contents_info_total .= '                              ' . TEXT_DUPLICATE_IP . ' : ' . $total_dupes . PHP_EOL;
	$contents_info_total .= '                          </li>' . PHP_EOL;		
	$contents_info_total .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$contents_info_total .= '                              ' . TEXT_BOTS . ' : ' . $total_bots . PHP_EOL;
	$contents_info_total .= '                          </li>' . PHP_EOL;				   
	$contents_info_total .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$contents_info_total .= '                              ' . TEXT_ME . ' : ' . $total_admin . PHP_EOL;
	$contents_info_total .= '                          </li>' . PHP_EOL;	
	$contents_info_total .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$contents_info_total .= '                              ' . TEXT_REAL_CUSTOMERS . ' : ' . count($ip_addrs_active) .  '  ' . sprintf(TEXT_ACTIVE_CUSTOMERS, $total_cust) . PHP_EOL;
//	$contents_info_total .= '                              ' . TEXT_REAL_CUSTOMERS; if(count($ip_addrs_active) > 0) echo ', <font color="' . $fg_color_guest . '">' . count($ip_addrs_active) . TEXT_ACTIVE_CUSTOMERS . '</font>' . ' : ' . $total_cust . PHP_EOL;

	$contents_info_total .= '                          </li>' . PHP_EOL;		
	$contents_info_total .= '                          <li class="list-group-item">' . PHP_EOL; 		
	$contents_info_total .= '                              ' . TEXT_MY_IP_ADDRESS . " : " . $_SERVER["REMOTE_ADDR"] . '<br />' .  TEXT_NOT_AVAILABLE . PHP_EOL;
	$contents_info_total .= '                          </li>' . PHP_EOL;			
	$contents_info_total .= '                        </ul>' . PHP_EOL;
	
    echo $contents_info_total ;
?>	
	</div> <!-- end <div class="col-md-9 col-xs-12"> -->	
	<div class="col-md-3 col-xs-12">
<?php
 
  if (  isset($_GET['info']) and $info = $_GET['info'] ) {	
    if (STORE_SESSIONS == 'mysql') {
      $session_data = tep_db_query("select value from " . TABLE_SESSIONS . " WHERE sesskey = '" . $info . "'");
      $session_data = tep_db_fetch_array($session_data);
      $session_data = trim($session_data['value']);
    } else {
      if ( (file_exists(tep_session_save_path() . '/sess_' . $info)) && (filesize(tep_session_save_path() . '/sess_' . $info) > 0) ) {
        $session_data = file(tep_session_save_path() . '/sess_' . $info);
        $session_data = trim(implode('', $session_data));
      }
    }

	$contents_cart = '' ; 
    if ($length = strlen($session_data)) {

      if (PHP_VERSION < 4) {
        $start_cart = strpos($session_data, 'cart[==]o');
        $start_currency = strpos($session_data, 'currency[==]s');
      } else {
        $start_cart = strpos($session_data, 'cart|O');
        $start_currency = strpos($session_data, 'currency|s');
      }

      // if we found the 'cart' tag in the session data
      // workaround for timeout when suhosin session data encryption is in effect
      if ($start_cart !== false) {
        for ($i=$start_cart; $i<$length; $i++) {
          if ($session_data[$i] == '{') {
            if (isset($tag)) {
              $tag++;
            } else {
              $tag = 1;
            }
          } elseif ($session_data[$i] == '}') {
            $tag--;
          } elseif ( (isset($tag)) && ($tag < 1) ) {
            break;
          }
        }

        $session_data_cart = substr($session_data, $start_cart, $i);
        $session_data_currency = substr($session_data, $start_currency, (strpos($session_data, ';', $start_currency) - $start_currency + 1));

        session_decode($session_data_cart);
        session_decode($session_data_currency);

        if (PHP_VERSION < 4) {
          $broken_cart = $cart;
          $cart = new shoppingCart;
          $cart->unserialize($broken_cart);
        } else {
          $cart = $_SESSION['cart'];
          $currency = $_SESSION['currency'];
        }
        $contents_cart .= '<div class="panel panel-info">' . PHP_EOL;
        $contents_cart .= '	  <div class="panel-heading">' . TABLE_HEADING_SHOPPING_CART . '</div>' . PHP_EOL ;
        $contents_cart .= '   <div class="panel-body">';		  
        $contents_cart .= '        <ul class="list-group">' . PHP_EOL;
		
        if (is_object($cart)) {
          $products = $cart->get_products();
          for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
		   $contents_cart .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_cart .= '                              ' . $products[$i]['quantity'] . ' x ' . $products[$i]['name'] . PHP_EOL;
		   $contents_cart .= '                          </li>' . PHP_EOL;				  
 //           $contents[] = array('text' => $products[$i]['quantity'] . ' x ' . $products[$i]['name']);
          }
          if (sizeof($products) > 0) {
//            $contents[] = array('text' => tep_draw_separator('pixel_black.gif', '100%', '1'));
		   $contents_cart .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_cart .= '                              ' . TEXT_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($cart->show_total(), true, $currency) . PHP_EOL;
		   $contents_cart .= '                          </li>' . PHP_EOL;			
//            $contents[] = array('align' => 'right', 'text' => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($cart->show_total(), true, $currency));
          } else {
		   $contents_cart .= '                          <li class="list-group-item">' . PHP_EOL; 		
		   $contents_cart .= '                              ' . TEXT_EMPTY . PHP_EOL;
		   $contents_cart .= '                          </li>' . PHP_EOL;						  
//            $contents[] = array('text' => '<i>' . TEXT_EMPTY . '</i>');
          }
        }
	    $contents_cart .= '          </ul>' . PHP_EOL;	
        $contents_cart .= '   </div>' . PHP_EOL;  //  end div panel body
        $contents_cart .= '</div>';			// end div panel
      } // end if ($start_cart !== false) 
    } // end if ($length = strlen($session_
	echo $contents_cart;	
  } // end if (  isset($_GET['info']) and $info 
?>	
	
    </div> <!-- end 	<div class="col-md-3 col-xs-12"> -->	
  </div> <!-- end div class="row" -->	

<?php  require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
