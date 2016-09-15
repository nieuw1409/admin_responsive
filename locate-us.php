<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce
  Portions Copyright 2010 oscommerce-solution.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EASYMAP);
  
  $mapType = '';
  $MAX_ZOOM_LEVEL = 22;
  $zoom_level = 9999;
  $roadmapType = array();
  
  function _get_lan_lng_google(  $address = '' ) { // Google HQ
    $prepAddr = str_replace(' ','+',$address);
 
    $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
 
    $output= json_decode($geocode);
	
	return array( $output->results[0]->geometry->location->lat , $output->results[0]->geometry->location->lng );
  }	



  

  /******************** SET THE ZOOM LEVEL *********************/ 
  if (isset($_POST['action']) && $_POST['action'] == 'process_options') {
      if (isset($_POST['zoom_level'])) {
          if ((int)$_POST['zoom_level'] < $MAX_ZOOM_LEVEL) {
              $zoom_level = (int)$_POST['zoom_level'];
          }
      }    
  }


/*  get all locations for this store */  
//  $location_query = tep_db_query("select distinct locationmap_id, locationmap_name, locationmap_image, locationmap_address, locationmap_zoom from " . TABLE_LOCATIONMAP . " 
//		                               where find_in_set('" . SYS_STORES_ID . "', locationmap_to_stores) != 0  order by locationmap_name");
									   
  $location_query = tep_db_query( "select l.locationmap_id, l.locationmap_name, l.locationmap_image, l.locationmap_address, l.locationmap_zoom, l.locationmap_to_stores,  
                                         li.location_text_map, li.location_text_marker  
                           from " . TABLE_LOCATIONMAP . " l LEFT JOIN " .  TABLE_LOCATIONMAP_INFO . " li on l.locationmap_id = li.locationmap_id 
						   where find_in_set('" . SYS_STORES_ID . "', l.locationmap_to_stores) != 0 and li.languages_id = '".$languages_id ."' order by l.locationmap_name" );
									   
  $locations_id         = 0 ;
  $locations_lat        = '' ;  
  $locations_lng        = '' ;
  $loc_map_zoom         = '' ;
  $loc_map_text_marker  = '"' ;  
  
  $locations_string = 
  '<div class="container-fluid">'  . PHP_EOL .
  '  <div class="row">'  . PHP_EOL .
  '     <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">'  . PHP_EOL ;
									   
  if ($number_of_rows = tep_db_num_rows($location_query)) {	
    while ($locations = tep_db_fetch_array($location_query)) {
	
    $locations_string .= '             <div class="row">' . PHP_EOL ;
    $locations_string .= '                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' . PHP_EOL  ;
    $locations_string .= '                    <div class="panel panel-info" onclick="return init_map(' . $locations_id . ');">' . PHP_EOL  ;
    $locations_string .= '                       <div class="panel-body">' . PHP_EOL  ;	
    $locations_string .= '                        <div class="row">' . PHP_EOL  ;
    $locations_string .= '                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' . PHP_EOL  ;
    $locations_string .= '                                <span></span>' . PHP_EOL  ;
    $locations_string .= '                                ' . tep_image(DIR_WS_IMAGES . $locations['locationmap_image'], '', null, null, 'itemprop="image"') . '<br />' . PHP_EOL  ;
    $locations_string .= '                            </div>' . PHP_EOL  ;
    $locations_string .= '                        </div>' . PHP_EOL  ;	
    $locations_string .= '                        <div class="row">' . PHP_EOL  ;	
    $locations_string .= '                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' . PHP_EOL  ;
    $locations_string .= '                                <div class="clearfix">' . PHP_EOL  ;
    $locations_string .= '                                    <div class="well well-info">' . PHP_EOL  ;
    $locations_string .= '                                        <p>' . $locations['location_text_map']  . '</p>'. PHP_EOL  ;
    $locations_string .= '                                    </div>'  . PHP_EOL ;                                 
    $locations_string .= '                                </div>' . PHP_EOL  ;
    $locations_string .= '                            </div>' . PHP_EOL  ;
    $locations_string .= '                        </div>' . PHP_EOL  ;
    $locations_string .= '                      </div>' . PHP_EOL  ;	
    $locations_string .= '                    </div>' . PHP_EOL  ;
    $locations_string .= '                </div>' . PHP_EOL  ;
    $locations_string .= '            </div>	' . PHP_EOL  ;
	
	/* get lat and lng from google */
	$array_locationl_lan_lng = _get_lan_lng_google(  $locations['locationmap_address' ] ) ;
	$locations_lat          .= $array_locationl_lan_lng[0] . ',';  
    $locations_lng          .= $array_locationl_lan_lng[1] . ',' ;  
    $loc_map_zoom           .= $locations['locationmap_zoom' ] . ',' ; 
    $loc_map_text_marker    .= $locations['location_text_marker' ] . '","' ; 
 	
	
	 $locations_id++ ;
	
    }   // end while
  }  // end if ($number_of_
  
  $locations_string .= 
  '      </div> <!-- end class="col-xs-12 col-sm-12 col-md-6 col-lg-6 " -->'  . PHP_EOL ;
  
  /* add the google map */
  $locations_string .= '      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">'  . PHP_EOL ;
  $locations_string .= '          <div class="row padbig">'  . PHP_EOL ;
  $locations_string .= '              <div id="map" class="map">'  . PHP_EOL ;
  $locations_string .= '              </div>'  . PHP_EOL ;
  $locations_string .= '          </div>'  . PHP_EOL ;
  $locations_string .= '      </div>'  . PHP_EOL ;
  
  
  $locations_string .= 		
  '   </div>     <!-- end class="row"   -->'  . PHP_EOL ;
  
  
  $locations_string .= 	  
  ' </div>      <!-- end class="container" -->'  . PHP_EOL ;
  
  
  
  $locations_lat       = rtrim($locations_lat, ",") . '' ;  
  $locations_lng       = rtrim($locations_lng, ",") . '' ;  
  $loc_map_text_marker = strip_tags( rtrim($loc_map_text_marker, ',"') . '"' );    
   
  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_EASYMAP));
  require(DIR_WS_INCLUDES . 'template_top.php');
  
?>

<div class="page-header">
  <h1>
     <?php echo HEADING_TITLE ; ?>
  </h1>
</div>
<?php
  echo $locations_string ;
?>  

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?></span>
  </div>


<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
    var latitudes   = [<?php echo $locations_lat ; ?> ];
    var longitudes  = [<?php echo $locations_lng ; ?>];
    var zoom_levels = [<?php echo $loc_map_zoom  ; ?>];	
    var marker_text = [<?php echo $loc_map_text_marker  ; ?>];		


    function init_map(index) {
        var myLocation = new google.maps.LatLng(latitudes[index], longitudes[index]);
        var mapOptions = {
            center: myLocation,
            zoom: zoom_levels[index]
        }; 

        var contentString = $('<div class="marker-info-win">'+
        '<div class="marker-inner-win"><span class="info-content">'+        
        marker_text[index]+
        '</span>'+
        '</div></div>');		
	  
        var infowindow = new google.maps.InfoWindow({
          content: contentString[0]
        });
		
        var marker = new google.maps.Marker({
            position: myLocation,
            title: "<?php echo STORE_NAME ; ?>"		
			
        });
        var map = new google.maps.Map(document.getElementById("map"),
            mapOptions);
        marker.setMap(map);
        
		google.maps.event.addListener(marker, 'click', function() {
           infowindow.open(map,marker);
        });		
    }	
	google.maps.event.addDomListener(window, 'load', init_map(0));
 
</script>
<style>

    .panel:hover {
        background-color: rgb(237, 245, 252);
    }

    .map {
  width: 100%;
  height: 135px;
  margin-bottom: 15px;
  border: 2px solid #fff;
    }

    img {
        max-width: 90%;
        height: auto;
    }

    .clearfix {
        clear: both;
    }

    .rowcolor {
        background-color: #CCCCCC;
    }

    .padall {
        padding: 10px;
    }

    .padbig {
        padding: 20px;
    }

    .icon {
        font-size: 23px;
        color: #197BB5;
    }
</style>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>