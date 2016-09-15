<?php
/*
  $Id: supertracker.php, v3.3
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  Created by Mark Stephens, http://www.phpworks.co.uk
  Added keywords filters by Monika MathÃ©, http://www.monikamathe.com
*/

// ********** PAY PER CLICK CONFIGURATION SECTION ************
// Pay per click referral URLs used - to make this work you have to set up your pay-per-click
// URLs like this : http://www.yoursite.com/catalog/index.php?ref=xxx&keyw=yyy
// where xxx is a code representing the PPC service and yyy is the keyword being used
// to generate that referral. Here's an example :
// http://www.yoursite.com/catalog/index.php?ref=googled&keyw=gameboy
// which might be used for the keyword "gameboy" in a google adwords campaign.
// The keyword part is optional - if you don't use it in a particular campaign, you
// Just set up the $ppc array like that in the example for googlead below

$ppc = array ('googlead' => array ('title' => 'Google Adwords', 'keywords' => 'shortcode1:friendlyterm1,shortcode1:friendlyterm2'));

//Set the following to true to enable the PPC referrer report
//Eventually, this will probably be moved into the configuration menu
//in admin, where it really should be!

define ('SUPERTRACKER_USE_PPC', false);
// ********** PAY PER CLICK CONFIGURATION SECTION EOF ************

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/supertracker.php');

  if (isset($HTTP_GET_VARS['action'])) $action = $HTTP_GET_VARS['action'];
  if (isset($HTTP_GET_VARS['page']))   $page   = $HTTP_GET_VARS['page'];  

  if (tep_not_null($action)) {
    switch ($action) {
      case 'del_rows':	 
        $rows_to_delete = $_POST['num_rows'];
        $del_query  = "DELETE from supertracker ORDER by tracking_id ASC LIMIT " . $rows_to_delete;
        $del_result = tep_db_query ($del_query);
		tep_redirect(tep_href_link(FILENAME_SUPERTRACKER));
	  break;
    }
  }
  include ('includes/classes/currencies.php');
  $currency = new currencies();

  function draw_geo_graph($geo_hits,$country_names,$total_hits) {
//          echo '<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr class="dataTableRow"><td class="dataTableContent"><table cellpadding=2 cellspacing=0 border=0>';
        $return_string = '' ;
        $max_pixels = 600;
        arsort($geo_hits);
        foreach ($geo_hits as $country_code=>$num_hits) {
           $country_name = $country_names[$country_code];
           $bar_length = ($num_hits/$total_hits) * $max_pixels;
           $percent_hits = round(($num_hits/$total_hits) * 100,2);
           //Create a random colour for each bar
           srand((double)microtime()*1000000);
           $r = dechex(rand (0, 255));
           $g = dechex(rand (0, 255));
           $b = dechex(rand (0, 255));

           $return_string .= '<tr><td >' . $country_name . ': </td><td><div style="display:justify;background:#' . $r . $g . $b . '; border:1px solid #000; height:10px; width:' . $bar_length . '"></div></td><td>' . $percent_hits . '%</td></tr>';
        }
//          echo '</table></td></tr></table>';
        return $return_string ;
  }//end function
  
  function draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) {  // compiles the table for the diffrent reports  
  
        $tracker_query = tep_db_query($tracker_query_raw);  
		
        $return_content_report .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

        $return_content_report .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
        $return_content_report .= '                 <thead>' . PHP_EOL ;
        $return_content_report .= '                   <tr class="heading-row">' . PHP_EOL ;
		foreach ($headings as $h) {
            $return_content_report .= '                      <th>' . $h . '</th>';
        }
    			   
        $return_content_report .= '                   </tr>' . PHP_EOL ;
        $return_content_report .= '                 </thead>' . PHP_EOL ;
        $return_content_report .= '                 <tbody>' . PHP_EOL ;
		
        $counter = 0;
        while ($tracker = tep_db_fetch_array($tracker_query)) {
            $counter++;
            $return_content_report .= '<tr>' . PHP_EOL ;
            $return_content_report .= '  <td>' . $counter . '</td>'. PHP_EOL ;			
            foreach ($row_data as $r) {
                if (strlen($tracker[$r]) > 100) $tracker[$r] = substr($tracker[$r],0,100);
                            $return_content_report .= '  <td>' . $tracker[$r] . '</td>';
            }
            $return_content_report .= '<tr>' . PHP_EOL ;
        } 		    		  
        $return_content_report .= '                 </tbody>' . PHP_EOL ;
        $return_content_report .= '   </table>' . PHP_EOL ;		   
		
        return $return_content_report ;
  } // end function draw_table_reports
  
  require(DIR_WS_INCLUDES . 'template_top.php'); 		
?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-md-5 col-xs-12"><?php echo HEADING_TITLE ; ?></h1>  
			
               <div class="col-md-7 col-xs-12">		  
<?php
                  $maint_query = "select tracking_id, time_arrived from supertracker order by tracking_id ASC";
                  $maint_result = tep_db_query($maint_query);
                  $num_rows = tep_db_num_rows($maint_result);
                  $maint_row = tep_db_fetch_array($maint_result);		  
				  
                  echo '' . tep_draw_form('del_rows', FILENAME_SUPERTRACKER, 'action=del_rows', 'post', 'role="form"'). PHP_EOL ;
				  echo '    <p>'.  sprintf(TEXT_TABLE_DATABASE, $num_rows, tep_date_short($maint_row['time_arrived'])) . '</p>' . PHP_EOL ;
				  echo '    <div class="control-label"><p>'.  TEXT_TABLE_DELETE . '</p></div>' . PHP_EOL ;				  
                  echo '    <div class="form-group">' . PHP_EOL;				  
                  echo          tep_draw_bs_input_field('num_rows', $number_rows,  null, 'id_delete_num_rows' , null, 'col-xs-6', 'left'	).  PHP_EOL; 
                  echo          tep_draw_bs_button(TEXT_BUTTON_ERASE, 'trash', null) ;				  
 				  echo '    </div>' . PHP_EOL;	

	              echo '    </form>' . PHP_EOL ; 		  
?>			   </div>	  
               <div class="clearfix"></div>            	
          </div><!-- page-header-->		
	      <div>
<?php
           $selections[] = array('id' => '',
                               'text' => TABLE_TEXT_MENU_TEXT);		  
           $selections[] = array('id' => 'special_last_ten',
                               'text' => TEXT_LAST_TEN_VISITORS);									   
           $selections[] = array('id' => 'special_keywords',
                               'text' => TEXT_SEARCH_KEYWORDS);								   
           $selections[] = array('id' => 'special_keywords_last24',
                               'text' => TEXT_SEARCH_KEYWORDS_24);								   
           $selections[] = array('id' => 'special_keywords_last72',
                               'text' => TEXT_SEARCH_KEYWORDS_3);									   
           $selections[] = array('id' => 'special_keywords_lastweek',
                               'text' => TEXT_SEARCH_KEYWORDS_7);								   
           $selections[] = array('id' => 'special_keywords_lastmonth',
                               'text' => TEXT_SEARCH_KEYWORDS_30);	

           if (SUPERTRACKER_USE_PPC) {
             $selections[] = array('id' => 'special_ppc_summary',
                                 'text' => TEXT_PPC_REFERRAL);				   
           }		   
           $selections[] = array('id' => 'special_geo',
                               'text' => TEXT_VISITORS);		   
           $selections[] = array('id' => 'special_state',
                               'text' => TEXT_VISITORS_STATE);	
           $selections[] = array('id' => 'special_city',
                               'text' => TEXT_VISITORS_CITY);
							   
           $selections[] = array('id' => 'report_refer',
                               'text' => TEXT_TOP_REFERRERS);							   
           $selections[] = array('id' => 'report_success_refer',
                               'text' => TEXT_TOP_SALES);	
           $selections[] = array('id' => 'report_ave_clicks',
                               'text' => TEXT_AVERAGE_CLICKS);	
           $selections[] = array('id' => 'report_ave_time',
                               'text' => TEXT_AVERAGE_TIME_SPENT);	
           $selections[] = array('id' => 'report_exit',
                               'text' => TEXT_TOP_EXIT_PAGES);	
           $selections[] = array('id' => 'report_exit_added',
                               'text' => TEXT_TOP_EXIT_PAGES_NO_SALE);	
           $selections[] = array('id' => 'report_browser',
                               'text' => TEXT_BROWSER);	
								   
							   
           echo '' . tep_draw_bs_form('status', FILENAME_SUPERTRACKER, tep_get_all_get_params(array('report_selected', 'report_selector','offset')), 'get', '"role=form"' ). PHP_EOL ;
           echo '    <div class="form-group">' . PHP_EOL;
		   echo          tep_draw_bs_pull_down_menu( 'report_selector', $selections, $HTTP_GET_VARS['report_selected'], TABLE_TEXT_MENU_DESC_TEXT, 'id_select_rapport', 'col-xs-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left', 'onchange="this.form.submit();"' ) . PHP_EOL;
 		   echo '    </div>' . PHP_EOL;				  
		   echo          tep_hide_session_id() ;
	       echo '    </form>' . PHP_EOL ; 
?>
          </div>		  
		  
  </table>
  
<?php
 if (isset($HTTP_GET_VARS['report_selector'])) {
	   $action_report_selector = $HTTP_GET_VARS['report_selector'] ;
	   
	   $headings=array();  // used in report refer etc
       $row_data=array(); // used in report refer etc
	   
	   switch ($action_report_selector) {
		   case 'special_keywords': 
              $keywords_used = array();
              $keyword_query = "select * from supertracker";
              $keyword_result = tep_db_query ($keyword_query);
              while ($keywords = tep_db_fetch_array($keyword_result)) {
                   $key_array = explode ('&', $keywords[referrer_query_string]);
                         for ($i=0; $i<sizeof($key_array); $i++) {
                          if (substr($key_array[$i], 0,2) == 'q=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (substr($key_array[$i], 0,2) == 'p=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (strstr($key_array[$i], 'query=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
                                }
                          if (strstr($key_array[$i], 'keyword=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
                                }
                          if (strstr($key_array[$i], 'keywords=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
                                }
                   }
              }
              //Need a function to sort $keywords_used into order of no. of hits at some stage!
			  $content_searches == '' ;
              arsort($keywords_used);
              foreach ($keywords_used as $kw=>$hits) {
                $content_searches .= '<tr><td>' . $kw . '</td><td class="text-center">' . $hits . '</td></tr>';
              }
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TEXT_SEARCH_KEYWORDS   . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS    . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;
			 
              // end Keywords Report last 24h	
              break;
			  
		   case 'special_keywords_last24': 
              $keywords_used = array();
              $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 1 DAY) >= now() ";
              $keyword_result = tep_db_query ($keyword_query);
              while ($keywords = tep_db_fetch_array($keyword_result)) {
                   $key_array = explode ('&', $keywords[referrer_query_string]);
                         for ($i=0; $i<sizeof($key_array); $i++) {
                          if (substr($key_array[$i], 0,2) == 'q=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (substr($key_array[$i], 0,2) == 'p=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (strstr($key_array[$i], 'query=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
                                }
                          if (strstr($key_array[$i], 'keyword=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
                                }
                          if (strstr($key_array[$i], 'keywords=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
                                }
                   }
              }
              //Need a function to sort $keywords_used into order of no. of hits at some stage!
			  $content_searches == '' ;
              arsort($keywords_used);
              foreach ($keywords_used as $kw=>$hits) {
                $content_searches .= '<tr><td>' . $kw . '</td><td class="text-center">' . $hits . '</td></tr>';
              }
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TEXT_SEARCH_KEYWORDS_24 . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS    . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;
			 
              // end Keywords Report last 24h	
              break;			  
		   case 'special_keywords_last72':
              $keywords_used = array();
              $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 3 DAY) >= now() ";
              $keyword_result = tep_db_query ($keyword_query);
              while ($keywords = tep_db_fetch_array($keyword_result)) {
                   $key_array = explode ('&', $keywords[referrer_query_string]);
                         for ($i=0; $i<sizeof($key_array); $i++) {
                          if (substr($key_array[$i], 0,2) == 'q=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (substr($key_array[$i], 0,2) == 'p=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (strstr($key_array[$i], 'query=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
                                }
                          if (strstr($key_array[$i], 'keyword=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
                                }
                          if (strstr($key_array[$i], 'keywords=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
                                }
                   }
              }
              //Need a function to sort $keywords_used into order of no. of hits at some stage!
			  $content_searches == '' ;
              arsort($keywords_used);
              foreach ($keywords_used as $kw=>$hits) {
                $content_searches .= '<tr><td>' . $kw . '</td><td class="text-center">' . $hits . '</td></tr>';
              }
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TEXT_SEARCH_KEYWORDS_3 . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS    . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;		   
		   
		      break;
		   case 'special_keywords_lastweek':
              $keywords_used = array();
              $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 7 DAY) >= now() ";
              $keyword_result = tep_db_query ($keyword_query);
              while ($keywords = tep_db_fetch_array($keyword_result)) {
                   $key_array = explode ('&', $keywords[referrer_query_string]);
                         for ($i=0; $i<sizeof($key_array); $i++) {
                          if (substr($key_array[$i], 0,2) == 'q=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (substr($key_array[$i], 0,2) == 'p=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (strstr($key_array[$i], 'query=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
                                }
                          if (strstr($key_array[$i], 'keyword=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
                                }
                          if (strstr($key_array[$i], 'keywords=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
                                }
                   }
              }
              //Need a function to sort $keywords_used into order of no. of hits at some stage!
			  $content_searches == '' ;
              arsort($keywords_used);
              foreach ($keywords_used as $kw=>$hits) {
                $content_searches .= '<tr><td>' . $kw . '</td><td class="text-center">' . $hits . '</td></tr>';
              }
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TEXT_SEARCH_KEYWORDS_7 . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS    . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;		   
		   
		      break;			  
			  
		   case 'special_keywords_lastmonth':
              $keywords_used = array();
              $keyword_query = "select * from supertracker where DATE_ADD(time_arrived, INTERVAL 30 DAY) >= now() ";
              $keyword_result = tep_db_query ($keyword_query);
              while ($keywords = tep_db_fetch_array($keyword_result)) {
                   $key_array = explode ('&', $keywords[referrer_query_string]);
                         for ($i=0; $i<sizeof($key_array); $i++) {
                          if (substr($key_array[$i], 0,2) == 'q=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (substr($key_array[$i], 0,2) == 'p=') {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],2, strlen($key_array[$i])-2))] +=1;
                                }
                          if (strstr($key_array[$i], 'query=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],6, strlen($key_array[$i])-6))] +=1;
                                }
                          if (strstr($key_array[$i], 'keyword=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],8, strlen($key_array[$i])-8))] +=1;
                                }
                          if (strstr($key_array[$i], 'keywords=')) {
                                  $keywords_used[str_replace('+', ' ', substr($key_array[$i],9, strlen($key_array[$i])-9))] +=1;
                                }
                   }
              }
              //Need a function to sort $keywords_used into order of no. of hits at some stage!
			  $content_searches == '' ;
              arsort($keywords_used);
              foreach ($keywords_used as $kw=>$hits) {
                $content_searches .= '<tr><td>' . $kw . '</td><td class="text-center">' . $hits . '</td></tr>';
              }
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TEXT_SEARCH_KEYWORDS_30 . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS     . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;		   
		   
		      break;				  
			  
		   case 'special_ppc_summary':
              foreach ($ppc as $ref_code => $details) {
                $scheme_name = $details['title'];
                $keywords = $details['keywords'];

                $ppc_q = "SELECT * from supertracker where landing_page like '%ref=" . $ref_code . "%'";
                $ppc_result = tep_db_query ($ppc_q);
                $ppc_num_refs = tep_db_num_rows($ppc_result);
                $content_searches .= '<tr><td class="text-warning">' . $scheme_name . ' - Total Referrals ' . $ppc_num_refs . '</td></tr>';

                if ($keywords != '') {
                    $keyword_array = explode(',',$keywords);
                    foreach ($keyword_array as $key => $val) {
                        $colon_pos = strpos ($val, ':');
                        $keyword_code = substr($val,0,$colon_pos);
                        $keyword_friendly_name = substr($val,$colon_pos+1,strlen($val)-$colon_pos);
                        $ppc_key_q = "SELECT *, count(*) as count, AVG(num_clicks) as ave_clicks, AVG(UNIX_TIMESTAMP(last_click) - UNIX_TIMESTAMP(time_arrived))/60 as ave_time from supertracker where landing_page like '%ref=" . $ref_code . "&keyw=" . $keyword_code . "%' group by landing_page";
                        $ppc_key_result = tep_db_query($ppc_key_q);
                        $ppc_row = tep_db_fetch_array($ppc_key_result);
                        $ppc_key_refs = $ppc_row['count'];
                        $content_searches .=  '<tr><td>' . $keyword_friendly_name . ' : ' . $ppc_key_refs . TABLE_TEXT_AVERAGE_TIME  . $ppc_row['ave_time'] . TABLE_TEXT_MINS_AVERAGE_CLICKS  . $ppc_row['ave_clicks'] . '</td></tr>';
                    }
                }
              }

              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TEXT_SEARCH_KEYWORDS_30 . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS     . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;		   
		   
		      break;
		   case 'special_geo':
              $geo_query = "select count(*) as count, country_code, country_name from supertracker GROUP by country_code";
              $geo_result = tep_db_query($geo_query);
              $geo_hits = array();
              $country_names = array();
              $total_hits = 0;
              while ($geo_row = tep_db_fetch_array($geo_result)) {
                   $total_hits += $geo_row['count'];
                   $country_code = strtolower($geo_row['country_code']);
                   $geo_hits[$country_code] = $geo_row['count'];
                   $country_names[$country_code] = tep_image(DIR_WS_IMAGES . 'flags/' . $country_code . '.gif') . ' ' . $geo_row['country_name'];
              }
              $content_searches .= draw_geo_graph($geo_hits,$country_names,$total_hits);
			  
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TABLE_TEXT_COUNTRY . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS     . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;		   
		   
		      break;				  

		   case 'special_state':
              $state_query = "select count(*) as count, country_region from supertracker GROUP by country_region";
              $state_result = tep_db_query($state_query);
              $state_hits = array();
              $country_names = array();
              $total_hits = 0;
              while ($state_row = tep_db_fetch_array($state_result)) {
                   $total_hits += $state_row['count'];
                   $country_code = strtolower($state_row['country_region']);
                   $state_hits[$country_code] = $state_row['count'];
                   $country_names[$country_code] = $state_row['country_region'];
              }
              $content_searches .= draw_geo_graph($state_hits,$country_names,$total_hits);
				 	  
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TABLE_TEXT_REGION . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS     . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;		   
		   
		      break;					  
			  
		   case 'special_city':
              $city_query = "select count(*) as count, country_city from supertracker GROUP by country_city";
              $city_result = tep_db_query($city_query);
              $city_hits = array();
              $country_names = array();
              $total_hits = 0;
              while ($city_row = tep_db_fetch_array($city_result)) {
                   $total_hits += $city_row['count'];
                   $country_code = strtolower($city_row['country_city']);
                   $city_hits[$country_code] = $city_row['count'];
                   $country_names[$country_code] = $city_row['country_city'];
              }
              $content_searches .= draw_geo_graph($city_hits,$country_names,$total_hits);
				 	  
              $content_report_selector .= '   <table border="0" width="100%" cellspacing="0" cellpadding="2">' . PHP_EOL ;

              $content_report_selector .= '               <table class="table table-condensed table-striped">' . PHP_EOL ;
              $content_report_selector .= '                 <thead>' . PHP_EOL ;
              $content_report_selector .= '                   <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '                      <th>'                    . TABLE_TEXT_CITY . '</th>' . PHP_EOL ;
              $content_report_selector .= '                      <th class="text-center">'. TEXT_NUMBER_OF_HITS     . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '                   </tr>' . PHP_EOL ;
              $content_report_selector .= '                 </thead>' . PHP_EOL ;
              $content_report_selector .= '                 <tbody>' . PHP_EOL ;
              $content_report_selector .= '                   ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '                 </tbody>' . PHP_EOL ;
              $content_report_selector .= '   </table>' . PHP_EOL ;		   
		   
		      break;				  
			  
		   case 'report_refer':
              $title = TEXT_TOP_REFERRERS;
              $headings[]=TEXT_RANKING;
              $headings[]=TEXT_REFERRING_URL;
              $headings[]=TEXT_NUMBER_OF_HITS;

              $row_data[]='referrer';
              $row_data[]='total';
              $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker GROUP BY referrer order by total DESC';
              $content_report_selector = draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) ;  // compiles the table for the diffrent reports
		      break;	
			  
		   case 'report_success_refer':
              $title = TEXT_TOP_SALES;
              $headings[]=TEXT_RANKING;
              $headings[]=TEXT_REFERRING_URL;
              $headings[]=TEXT_NUMBER_OF_SALES;

              $row_data[]='referrer';
              $row_data[]='total';
              $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker WHERE completed_purchase = "true" group by referrer order by total DESC';
              $content_report_selector = draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) ;  // compiles the table for the diffrent reports
		      break;				  

		   case 'report_exit':
              $title =TEXT_TOP_EXIT_PAGES;
              $headings[]=TEXT_RANKING;
              $headings[]=TEXT_EXIT_PAGE;
              $headings[]=TEXT_NUMBER_OF_OCCURRENCES;

              $row_data[]='exit_page';
              $row_data[]='total';
              $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker where completed_purchase="false" group by exit_page order by total DESC';
              $content_report_selector = draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) ;  // compiles the table for the diffrent reports
		      break;	

		   case 'report_exit_added':
              $title = TEXT_TOP_EXIT_PAGES_NO_SALE;
              $headings[]=TEXT_RANKING;
              $headings[]=TEXT_EXIT_PAGE;
              $headings[]=TEXT_NUMBER_OF_OCCURRENCES;

              $row_data[]='exit_page';
              $row_data[]='total';
              $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker where completed_purchase="false" and added_cart="true" group by exit_page order by total DESC';
              $content_report_selector = draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) ;  // compiles the table for the diffrent reports
		      break;	

		   case 'report_ave_clicks':
              $title = TEXT_AVERAGE_CLICKS;
              $headings[]=TEXT_RANKING;
              $headings[]=TEXT_REFERRING_URL;
              $headings[]=TEXT_NUMBER_OF_CLICKS;

              $row_data[]='referrer';
              $row_data[]='ave_clicks';
              $tracker_query_raw='SELECT *, AVG(num_clicks) as ave_clicks FROM supertracker group by referrer order by ave_clicks DESC';
              $content_report_selector = draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) ;  // compiles the table for the diffrent reports
		      break;	

		   case 'report_ave_time':
              $title = TEXT_AVERAGE_TIME_SPENT;
              $headings[]=TEXT_RANKING;
              $headings[]=TEXT_REFERRING_URL;
              $headings[]=TEXT_AVERAGE_LENGTH_OF_TIME;

              $row_data[]='referrer';
              $row_data[]='ave_time';
              $tracker_query_raw='SELECT *, AVG(UNIX_TIMESTAMP(last_click) - UNIX_TIMESTAMP(time_arrived))/60 as ave_time FROM supertracker group by referrer order by ave_time DESC';
              $content_report_selector = draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) ;  // compiles the table for the diffrent reports
		      break;				  

		   case 'report_browser':
              $title = TEXT_BROWSER;
              $headings[]=TEXT_RANKING;
              $headings[]='Browser';
              $headings[]=TEXT_NUMBER_OF_OCCURRENCES;

              $row_data[]='browser';
              $row_data[]='total';
              $tracker_query_raw='SELECT *, COUNT(*) as total FROM supertracker group by browser order by total DESC';
              $content_report_selector = draw_table_reports( $title, $headings, $row_data, $tracker_query_raw ) ;  // compiles the table for the diffrent reports
		      break;					   

		   case 'special_last_ten':
/*		   
		      if (isset($HTTP_GET_VARS['offset'])) {
				 $offset = $HTTP_GET_VARS['offset'];
              } else {
				 $offset = 0;
			  }
*/
		      $search_refer_match = '' ;
              if (isset($HTTP_POST_VARS['refer_match'])) {
                $match_refer_string = " and referrer like '%" . $HTTP_POST_VARS['refer_match'] . "%'";
                $refer_match = $HTTP_POST_VARS['refer_match'];
				$search_refer_match .= '&refer_match=' . $HTTP_POST_VARS['refer_match'] ;				
              } else {
                $match_refer_string = '';
                $refer_match = '';
              }


              if (isset($HTTP_POST_VARS['filter'])) {
                $filter = $HTTP_POST_VARS['filter'];
				$search_refer_match .= '&filter=' . $HTTP_POST_VARS['filter'] ;				
	          } else {
		        $filter = 'all';
				$search_refer_match .= '&filter=all' ;					
			  }
              
			  switch ($filter) {
				  case 'all' :

                      if ($refer_match == '') {
						  $lt_query = "select * from supertracker ORDER by last_click DESC " ; //LIMIT " . $offset . ",10";
					  } else {
						  $lt_query = "select * from supertracker where referrer like '%" . $refer_match . "%' ORDER by last_click DESC " ; //LIMIT " . $offset . ",10";
					  }
                      break;

                  case 'bailed' :
                      $lt_query = "select * from supertracker where added_cart = 'true' and completed_purchase = 'false' " . $match_refer_string . " ORDER by last_click DESC " ; //LIMIT " . $offset . ",10";
                      break;

                  case 'completed' :
                      $lt_query = "select * from supertracker where completed_purchase = 'true'  " . $match_refer_string . " ORDER by last_click DESC " ; //LIMIT " . $offset . ",10";
                      break;

              } // end switch
			  


              $last_ten_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $lt_query, $last_ten_query_numrows);
              $lt_result= tep_db_query ($lt_query);
			  
              while ($lt_row        = tep_db_fetch_array($lt_result)) {
                $customer_ip        = $lt_row['ip_address'];
                $country_code       = $lt_row['country_code'];
                $country_name       = $lt_row['country_name'];
                $region_name        = $lt_row['country_region'];
                $city_name          = $lt_row['country_city'];

                $customer_id = $lt_row['customer_id'];
                if ($customer_id != 0) {
                    $cust_query     = "select * from customers where customers_id ='" . $customer_id . "'";
                    $cust_result    = tep_db_query ($cust_query);
                    $cust_row       = tep_db_fetch_array($cust_result);
                    $customer_name  = $cust_row['customers_firstname'] . ' ' . $cust_row['customers_lastname'];
                } else {
					$customer_name  = "Guest";
				}
                $referrer = $lt_row['referrer'] . '?' . $lt_row['referrer_query_string'];
                if ($referrer == '?') $referrer = 'Direct Access / Bookmark';
				
                $landing_page       = $lt_row['landing_page'];
                $last_page_viewed   = $lt_row['exit_page'];
                $time_arrived       = $lt_row['time_arrived'];
                $last_click         = $lt_row['last_click'];
                $num_clicks         = $lt_row['num_clicks'];
                $added_cart         = $lt_row['added_cart'];
                $completed_purchase = $lt_row['completed_purchase'];
                $browser_string     = $lt_row['browser_string'];
				$browser            = $lt_row['browser'];
				
                $products_viewed = '';
                if ($lt_row['products_viewed'] != '') {
                    $products_viewed = $lt_row['products_viewed'];
                    $prod_view_array = explode ('*',$products_viewed);
//                } else {
//					$products_viewed = '';
			    }
				
                if($country_code==''){
                  $country_code='pixel_trans';
                }
				

                $content_searches .= '          <tr>' . PHP_EOL ;
                $content_searches .= '             <td  rowspan="4"class="hidden-sm hidden-xs">' . '<a href="http://www.infobyip.com/ip-' . $customer_ip . '.html" target="_blank">' . trim( $customer_ip ) . '</a><br/>
				                                                                                    (' . $country_name . ')' . tep_image(DIR_WS_IMAGES . 'geo_flags/flags/' . $country_code . '.gif', null, null, null, null, false) . '<br />' . 
																									gethostbyaddr($customer_ip)  .  '</td>' . PHP_EOL ;
																									
                $content_searches .= '             <td  rowspan="4"class="hidden-lg hidden-md">' . $customer_ip . '<br />' . gethostbyaddr($customer_ip) .                                                                                                        '</td>' . PHP_EOL ;
				
                $content_searches .= '             <td class="hidden-sm hidden-xs hidden-md">'   . $region_name . '/' . $city_name .                                                                                                    '</td>' . PHP_EOL ;				
				
                $content_searches .= '             <td class="hidden-sm hidden-xs">'             . $browser . ' : ' . $browser_string .                                                                                                 '</td>' . PHP_EOL ;	
				
                $content_searches .= '             <td>'                                         . '<a href="' . $referrer . '" target="_blank">' . $referrer .      '</a>' .                                                           '</td>' . PHP_EOL ;	
                
				$content_searches .= '             <td>'                                         . $customer_name .                                                                                                                     '</td>' . PHP_EOL ;	
								
                $content_searches .= '             <td class="hidden-sm hidden-xs">'             . $landing_page .                                                                                                                      '</td>' . PHP_EOL ;	
				
                $content_searches .= '             <td>'                                         . $last_page_viewed  .                                                                                                                 '</td>' . PHP_EOL ;	
				
                $content_searches .= '             <td class="hidden-sm hidden-xs">'             . tep_datetime_short($time_arrived) .                                                                                                  '</td>' . PHP_EOL ;	
				
                $content_searches .= '             <td class="hidden-sm hidden-xs">'             . tep_datetime_short($last_click) .                                                                                                    '</td>' . PHP_EOL ;					
				
                $time_on_site = strtotime($last_click) - strtotime($time_arrived);
                $hours_on_site = floor($time_on_site /3600);
                $minutes_on_site = floor( ($time_on_site - ($hours_on_site*3600))  / 60);
                $seconds_on_site = $time_on_site - ($hours_on_site *3600) - ($minutes_on_site * 60);
                $time_on_site = $hours_on_site . 'hrs ' . $minutes_on_site . 'mins ' . $seconds_on_site . ' seconds';				
				
                $content_searches .= '             <td class="hidden-sm hidden-xs">' . $time_on_site .                                                                                                                      '</td>' . PHP_EOL ;	
                $content_searches .= '             <td class="hidden-sm hidden-xs hidden-md">' . $num_clicks  .                                                                                                                       '</td>' . PHP_EOL ;	
                $content_searches .= '             <td class="hidden-sm hidden-xs hidden-md">' . $added_cart .                                                                                                                        '</td>' . PHP_EOL ;	
				
				$order_total = 0 ;				
                if ($completed_purchase == 'true') {
                   $order_q = "select ot.text as order_total from orders as o, orders_total as ot where o.orders_id=ot.orders_id and o.orders_id = '" . $lt_row['order_id'] . "' and ot.class='ot_total'";
                   $order_result = tep_db_query($order_q);
                   if (tep_db_num_rows($order_result)>0) {
                      $order_row = tep_db_fetch_array($order_result);
				      $order_total = $order_row['order_total'] ;
                   }
                }				
                $content_searches .= '             <td class="hidden-sm hidden-xs">'            . $order_total                 .                                                                                                             '</td>' . PHP_EOL ;		
				
                $content_searches .= '          </tr>' . PHP_EOL ;				
                $content_searches .= '          <tr>' . PHP_EOL ;				
					
                $categories_viewed = unserialize($lt_row['categories_viewed']);
                if (!empty($categories_viewed)) {
                    $content_searches .= '         <td class="hidden-xs hidden-sm"><strong>' . TABLE_TEXT_CATEGORIES . ' </strong></td>';					
                   $cat_string = '';
                   foreach ($categories_viewed as $cat_id=>$val) {
                      $cat_query = "select * from categories as c, categories_description as cd where c.categories_id=cd.categories_id and c.categories_id='" . $cat_id . "'";
                      $cat_result = tep_db_query($cat_query);
                      $cat_row = tep_db_fetch_array($cat_result);
                      $cat_string .= $cat_row['categories_name'] . ',';
                   }
                   $cat_string = rtrim($cat_string, ',');
                   $content_searches .= '<td  class="hidden-xs hidden-sm info" colspan="4">' . $cat_string . '</td>';
                }
                $content_searches .= '          </tr>' . PHP_EOL ;				
				
                $content_searches .= '          <tr>' . PHP_EOL ;								
                if ($products_viewed != '') {
                    $content_searches .= '         <td class="hidden-xs hidden-sm"><strong>' . TABLE_TEXT_PRODUCTS . ' </strong></td>';
                    $content_searches .= '         <td class="hidden-xs hidden-sm success" colspan="4">' ;	
                    $content_searches .= '             <div class="well text-center">' . PHP_EOL ;
                    $content_searches .= '                 <div class="row">' . PHP_EOL ;
                    
					$counter = 0 ;
					foreach ($prod_view_array as $key=>$product_id) {	
                      $product_id = rtrim($product_id, '?');
                      if ($product_id != '') {
                          $prod_query = "select * from products as p, products_description as pd where p.products_id=pd.products_id and p.products_id='" . $product_id . "'";
                          $prod_result = tep_db_query($prod_query);
                          $prod_row = tep_db_fetch_array($prod_result);
                          if (tep_not_null($prod_row['products_image'])) {					
					         if ( $counter <= 12  ) {
                                $content_searches .= '          <div class="col-xs-1">' . PHP_EOL ;
                                $content_searches .=               tep_image(DIR_WS_CATALOG_IMAGES . $prod_row['products_image'], $prod_row['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . PHP_EOL ;
                                $content_searches .= '          </div>' . PHP_EOL ;
							 }
						  }
					  }
					  $counter += 1 ;
                    }					
                    $content_searches .= '               	</div>' . PHP_EOL ;
                    $content_searches .= '             </div>					' . PHP_EOL ;
                    $content_searches .= '         </td>' ;                        
                }				
                $content_searches .= '          </tr>' . PHP_EOL ;				
                $content_searches .= '          <tr>' . PHP_EOL ;	
                
				$cart_contents = unserialize($lt_row['cart_contents']);				
                if (!empty($cart_contents)) {
                    $content_searches .= '         <td class="hidden-xs hidden-sm"><strong>' . TABLE_TEXT_CUSTOMERS_CART . '(' . TABLE_TEXT_CART_VALUE . $currency->format($lt_row['cart_total']) . ') </strong></td>';
                    $content_searches .= '         <td class="warning hidden-xs hidden-sm" colspan="4">' ;	
                    $content_searches .= '             <div class="well text-center">' . PHP_EOL ;
                    $content_searches .= '                 <div class="row">' . PHP_EOL ;
                    
					$counter = 0 ;
                    foreach ($cart_contents as $product_id => $qty_array) {
                      $prod_query = "select * from products as p, products_description as pd where p.products_id=pd.products_id and p.products_id='" . $product_id . "'";
                      $prod_result = tep_db_query($prod_query);
                      $prod_row = tep_db_fetch_array($prod_result);
                      if (tep_not_null($prod_row['products_image'])) {				
					      if ( $counter <= 12  ) {
                                $content_searches .= '          <div class="col-xs-1">' . PHP_EOL ;
                                $content_searches .=               tep_image(DIR_WS_CATALOG_IMAGES . $prod_row['products_image'], $prod_row['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . PHP_EOL ;
                                $content_searches .= '          </div>' . PHP_EOL ;
						  }						  
					  }
					  $counter += 1 ;
                    }					
                    $content_searches .= '               	</div>' . PHP_EOL ;
                    $content_searches .= '             </div>					' . PHP_EOL ;
                    $content_searches .= '         </td>' ;                         
                }				
                $content_searches .= '          </tr>' . PHP_EOL ;				

			  }


              $content_report_selector .=           tep_draw_bs_form('filter_select', FILENAME_SUPERTRACKER, 'report_selected=special_last_ten&report_selector=special_last_ten&filter=' . $filter . '&refer_match' . $refer_match, 'post', 'role="form"', 'ID_SELECT_FILTER'  ) . PHP_EOL ;

              $select_options = array(array('id' => 'all', 'text' => TEXT_SHOW_ALL),
                                      array('id' => 'bailed', 'text' => TEXT_BAILED_CARTS),
                                      array('id' => 'completed', 'text' => TEXT_SUCCESSFUL_CHECKOUTS)									  
									  );	  
              $content_report_selector .= '            <div class="input-group">'. PHP_EOL ;
			  $content_report_selector .=                  tep_draw_bs_pull_down_menu('filter', $select_options, $filter, null, 'id_input_filter_last_ten', 'col-xs-12', ' selectpicker show-tick ', null, 'left', 'onchange="this.form.submit();"')  . PHP_EOL;	
              $content_report_selector .= '            </div>'. PHP_EOL ;		  
/* 
//			  $content_report_selector .= '            <div class="form-group">' . PHP_EOL;			  
			  $content_report_selector .=                  tep_draw_bs_input_field('refer_match', $refer_match, TEXT_REFERRER_STRING, 'id_input_refer_match' , 'col-xs-4', 'col-xs-4',  null, TEXT_REFERRER_STRING ) . PHP_EOL; 
			  $content_report_selector .=			       tep_draw_bs_button(IMAGE_UPDATE, 'refresh') ;
			  $content_report_selector .= '            </div>' . PHP_EOL;	
*/		  
 
			  $content_report_selector .=              tep_hide_session_id() ;

			  $content_report_selector .= '         </form> <!-- end formfilter -->' . PHP_EOL ;	  

              $content_report_selector .= '<div class="container-fluid">'. PHP_EOL ;  
//              $content_report_selector .= ' <div class="table-responsive">'. PHP_EOL ; 
              $content_report_selector .= '   <table class="table table-condensed">' . PHP_EOL ; 
              $content_report_selector .= '       <thead>' . PHP_EOL ;
              $content_report_selector .= '         <tr class="heading-row">' . PHP_EOL ;
              $content_report_selector .= '            <th >'                                                    . TABLE_TEXT_IP                              . '</th>' . PHP_EOL ;
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs hidden-md">'     . TABLE_TEXT_REGION . '/' .  TABLE_TEXT_CITY . '</th>' . PHP_EOL ;    			   
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs">'               . TABLE_TEXT_CUSTOMER_BROWSER                . '</th>' . PHP_EOL ;  
              $content_report_selector .= '            <th class="text-left">'                                   . TABLE_TEXT_REFFERED_BY                     . '</th>' . PHP_EOL ;  
              $content_report_selector .= '            <th class="text-left">'                                   . TABLE_TEXT_NAME                            . '</th>' . PHP_EOL ;  			  
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs">'               . TABLE_TEXT_LANDING_PAGE                    . '</th>' . PHP_EOL ;  
              $content_report_selector .= '            <th class="text-left">'                                   . TABLE_TEXT_LAST_PAGE_VIEWED                . '</th>' . PHP_EOL ;			  
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs">'               . TABLE_TEXT_TIME_ARRIVED                    . '</th>' . PHP_EOL ;  
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs">'               . TABLE_TEXT_LAST_CLICK                      . '</th>' . PHP_EOL ;  			  
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs">'               . TABLE_TEXT_TIME_ON_SITE                    . '</th>' . PHP_EOL ;  
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs">'               . TABLE_TEXT_NUMBER_OF_CLICKS                . '</th>' . PHP_EOL ;  
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs hidden-md">'     . TABLE_TEXT_ADDED_CART                      . '</th>' . PHP_EOL ;  
              $content_report_selector .= '            <th class="text-left hidden-sm hidden-xs">'               . TABLE_TEXT_COMPLETED_PURCHASE              . '</th>' . PHP_EOL ;  			  
              $content_report_selector .= '         </tr>' . PHP_EOL ;
              $content_report_selector .= '       </thead>' . PHP_EOL ;			  
              $content_report_selector .= '       <tbody>' . PHP_EOL ;
              $content_report_selector .= '            ' . $content_searches . PHP_EOL ;			    		  
              $content_report_selector .= '       </tbody>' . PHP_EOL ; 			  
              $content_report_selector .= '   </table>' . PHP_EOL ;		

              $content_report_selector .= '       <table class="table" style="width: 100%"> <!-- osCommerce table foot -->' . PHP_EOL ;
              $content_report_selector .= '   	     <hr>' . PHP_EOL ;
              $content_report_selector .= '            <div class="row">' . PHP_EOL ;
			  
              $content_report_selector .= '                  <div class="mark text-left  col-xs-12 col-md-6">' . $last_ten_split->display_count($last_ten_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS) . '</div>' . PHP_EOL ;
              $content_report_selector .= '                  <div class="mark text-right col-xs-12 col-md-6">' . $last_ten_split->display_links($last_ten_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], 'report_selected=special_last_ten&report_selector=special_last_ten' . $search_refer_match ) . '</div>' . PHP_EOL ;  
              $content_report_selector .= '   	     </div>' . PHP_EOL ;
              $content_report_selector .= '       </table>' . PHP_EOL ;
			  
//              $content_report_selector .= ' </div>' . PHP_EOL ;  // end table-responsive
              $content_report_selector .= '</div>' . PHP_EOL ;	// end container
//			  $content_report_selector .=   tep_draw_bs_button(TABLE_TEXT_NEXT_TEN_RESULTS, 'chevron-right', tep_href_link(FILENAME_SUPERTRACKER, 'report_selector=special_last_ten&offset=' . ( $offset + 10 ). '&filter='. $filter . '&refer_match=' . $refer_match))  ;

		      break;  // end special_last_ten
			  
    }//End  switch ($action_report_selector) { 
		
    echo  $content_report_selector ;
	 
 } // end if if (isset($HTTP_GET_VARS['report_se 
?>

                </td>
 
  </tr>
</table>

<?php 
 require(DIR_WS_INCLUDES . 'template_bottom.php');
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>