<?php

/*
  Google Delivery Maps V1.0.2
  
  This program is free software; you can redistribute it and/or modify it under the terms
  of the GNU General Public License as published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
  See the GNU General Public License for more details.
  
  This program requires interface with the Google Maps API and your usage of this program must 
  comply with the terms of the Google API.
*/

require('includes/application_top.php');

// get orderID
$order = $HTTP_GET_VARS['oID']; 

// get delivery information
		$order_query = tep_db_query("select delivery_name, delivery_company, delivery_street_address, delivery_city, delivery_state, delivery_postcode from orders where orders_id = '$order'");
		$order_info = tep_db_fetch_array($order_query);

// build name/company and full address
$deliveryName = $order_info[delivery_name] . ", " . $order_info[delivery_company];
$deliveryAddress = $order_info[delivery_street_address] . ", " . $order_info[delivery_postcode] . ", " . $order_info[delivery_city] . ", " . $order_info[delivery_state];
//$deliveryAddress = $order_info[delivery_street_address] . ", " . $order_info[delivery_city] . ", " . $order_info[delivery_state] . ", " . $order_info[delivery_postcode];

// hardcoded start address (couldn't pull from configuration)
$storeAddress =  STORE_ADDRESS ;
//GOOGLE_STORE_STREET_ADDRESS . ' ' . GOOGLE_STORE_CITY . ' ' . GOOGLE_STORE_STATE . ' ' . GOOGLE_STORE_POSTCODE . ' ' . GOOGLE_STORE_COUNTRY;
				 $address = stripslashes($order_info[delivery_street_address]) . ',' . 
				 		    stripslashes( $order_info[delivery_postcode])       . ',' . 
						    stripslashes($order_info[delivery_city] )           ;
						   
				 $address = str_replace(" ", "+", $address);
				 $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . $address  . "&sensor=false" );
				 $response = json_decode($json);
				 				$lat = $response->results[0]->geometry->location->lat;
				$long = $response->results[0]->geometry->location->lng;
require('includes/template_top.php');
?>
 <style type="text/css">

  #directionsPanel {
    background: #FFFFFF;
    width: 800px;
    padding: 20px;
    margin: 0 auto;
    box-shadow: 0px 0px 6px #999;
    border-radius: 10px;
    font-size: 20px;
  }
  .adp-directions {
    width: 100%; 
  }
  form label {
    display: block;
    padding: 4px 0px;
  }
  </style>
  
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var directionDisplay;
var directionsService = new google.maps.DirectionsService();
function initialize() {
  var latlng = new google.maps.LatLng(<?php echo $lat . ',' . $long ; ?>);
  // set direction render options
  var rendererOptions = { draggable: true };
  directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
  var myOptions = {
    zoom: 14,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControl: false
  };
  // add the map to the map placeholder
  var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
  directionsDisplay.setMap(map);
  directionsDisplay.setPanel(document.getElementById("directionsPanel"));
  // Add a marker to the map for the end-point of the directions.
  var marker = new google.maps.Marker({
    position: latlng,
    map: map,
    title:"<?php echo STORE_NAME ; ?>"
  });
}
function calcRoute() {
  // get the travelmode, startpoint and via point from the form  
  var travelMode = $('input[name="travelMode"]:checked').val();
  var start = $("#routeStart").val();
  var via = $("#routeVia").val();
 
  if (travelMode == 'TRANSIT') {
    via = ''; // if the travel mode is transit, don't use the via waypoint because that will not work
  }
  var end = "51.764696,5.526042"; // endpoint is a geolocation
  var waypoints = []; // init an empty waypoints array
  if (via != '') {
    // if waypoints (via) are set, add them to the waypoints array
    waypoints.push({
      location: via,
      stopover: true
    });
  }
  var request = {
    origin: start,
    destination: end,
    waypoints: waypoints,
    unitSystem: google.maps.UnitSystem.IMPERIAL,
    travelMode: google.maps.DirectionsTravelMode[travelMode]
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      $('#directionsPanel').empty(); // clear the directions panel before adding new directions
      directionsDisplay.setDirections(response);
    } else {
      // alert an error message when the route could nog be calculated.
      if (status == 'ZERO_RESULTS') {
        alert('No route could be found between the origin and destination.');
      } else if (status == 'UNKNOWN_ERROR') {
        alert('A directions request could not be processed due to a server error. The request may succeed if you try again.');
      } else if (status == 'REQUEST_DENIED') {
        alert('This webpage is not allowed to use the directions service.');
      } else if (status == 'OVER_QUERY_LIMIT') {
        alert('The webpage has gone over the requests limit in too short a period of time.');
      } else if (status == 'NOT_FOUND') {
        alert('At least one of the origin, destination, or waypoints could not be geocoded.');
      } else if (status == 'INVALID_REQUEST') {
        alert('The DirectionsRequest provided was invalid.');        
      } else {
        alert("There was an unknown error in your request. Requeststatus: nn"+status);
      }
    }
  });
}
</script>
 

	
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE  . $lat . $long ; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div id="map_canvas" style="width:100%; height:300px"></div>  

         <form action="/routebeschrijving" onSubmit="calcRoute();return false;" id="routeForm">
         <div style="overflow: hidden; width: 500px; margin: 0 auto;">
            <div style="width: 350px; float: left; text-align: left;">
        <label>
          From: <br />
          <input type="text" id="routeStart" value="<?php echo $storeAddress ; ?>">
        </label>
        <label>
          Via: (optional)<br />
          <input type="text" id="routeVia" value="<?php echo $deliveryAddress ; ?>">
        </label>
      </div>
      <div style="width: 150px; float: left; text-align: left;">
        <label>Travel mode:</label>
        <label><input type="radio" name="travelMode" value="DRIVING" checked /> Driving</label>
        <label><input type="radio" name="travelMode" value="BICYCLING" /> Bicylcing</label>
        <label><input type="radio" name="travelMode" value="TRANSIT" /> Public transport</label>
        <label><input type="radio" name="travelMode" value="WALKING" /> Walking</label>
      </div>
    </div>
    <input type="submit" value="Calculate route">
  </form>
?>   
   </table>	 
<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>