<?php
/*
  $Id: googlesitemap.php admin page,v 2.0 2/03/2006 developer@eurobigstore.com
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
   
  	function GenerateSubmitURL(){
		$url = urlencode(HTTP_SERVER . DIR_WS_CATALOG . 'sitemapindex.xml');
		return htmlspecialchars(utf8_encode('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $url));
	} # end function

// controllo delle lingue	
        $controllo = $languages_id;
		$query = "SELECT 
							languages_id,
							code
					FROM
							" . TABLE_LANGUAGES . "
					WHERE
							languages_id = $controllo";
    	
		$result = tep_db_query($query);
    	
		while ($row = tep_db_fetch_array($result))
				{ 
					$codice = $row[code]; 
							    };
	
	$file = 'usu5_sitemaps/index.php?language=';
	$url = $file . $codice;
  require('includes/template_top.php');
// Fine	
?>

<table   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo TITLE_GOOGLE_SITEMAPS; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
		  
		  <div class="panel panel-primary">
		    <div class="panel-body">
			
			  <div class="col-xs-12 col-md-6">

			     <ul class="list-group"> 
			       <li class="list-group-item"> 	 
                       <p><strong><?php echo OVERVIEW_TITLE_GOOGLE_SITEMAPS; ?></strong></p>					   
					   <p><?php echo OVERVIEW_GOOGLE_SITEMAPS; ?></p>
                   </li>		   
			       <li class="list-group-item"> 	
                      <p class="text-info"><strong><?php echo INSTRUCTIONS_STEP1_GOOGLE_SITEMAPS; ?></strong></p>
					  <p><?php echo  tep_draw_bs_button(INSTRUCTIONS_CLICK_GOOGLE_SITEMAPS . ' ' . INSTRUCTIONS_END1_GOOGLE_SITEMAPS, 'disk', tep_catalog_href_link( $url . '&return=' . basename($PHP_SELF) . '&admin_id=' . $HTTP_GET_VARS['osCAdminID'] ), null, null, 'btn-primary ') ; ?></p>
					  <p><?php echo INSTRUCTIONS_NOTE_GOOGLE_SITEMAPS ; ?> 
                   </li>					   
			       <li class="list-group-item"> 	
                      <p class="text-info"><strong><?php echo INSTRUCTIONS_STEP2_GOOGLE_SITEMAPS; ?></strong></p>
					  <p><?php echo  tep_draw_bs_button(INSTRUCTIONS_CLICK_GOOGLE_SITEMAPS . ' ' . INSTRUCTIONS_END2_GOOGLE_SITEMAPS, 'disk', GenerateSubmitURL(), null, null, 'btn-primary ') ; ?></p>
                   </li>				   
                 </ul>
			  
			  </div>
			  
			  <div class="col-xs-12 col-md-6">

			     <ul class="list-group"> 
			       <li class="list-group-item"> 
                       <p><?php echo tep_image( "images/google-sitemaps.gif" ) ;?></p>				   
			           <p><?php echo WHATIS_TEXT_GOOGLE_SITEMAPS ; ?></p>
					   <p><?php echo  tep_draw_bs_button( WHATIS_REGISTER_GOOGLE_SITEMAPS, 'login', "http://www.google.nl/webmasters/#utm_medium=et&utm_source=bizsols&utm_campaign=sitemaps", 'target="_blank"', null, 'btn-primary ') ; ?></p>					   
                   </li>
                 </ul>
			  
			  </div>			  
			
			</div>
		 </div>
		  
</table>		  

<?php 
require(DIR_WS_INCLUDES . 'template_bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>