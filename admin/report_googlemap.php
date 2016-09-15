<?php
/*
  A script to visualize the customer database of
  osCommerce 2.2-MS2. (not tested otherwise)
  It is not localized and only supposed for Shops
  located in Germany.

  Released under the GNU General Public License
  Please refer to the included file 'gpl.txt'.

  The following copyright announcement is in compliance
  to section 2c of the GNU General Public License, and
  thus can not be removed, or can only be modified
  appropriately.

  Please leave this comment intact together with the
  following copyright announcement.

  Copyright (c) 2006 by
  J. Lingott (www.deweblop.de) and
  J. Hoppe   (www.footbag-shop.de)

  The authors provide no warranty and distribute this
  script under the GNU General Public License.
*/


  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  require(DIR_WS_INCLUDES . 'template_top.php');
  
?>
    <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<?php
	$customers_query_raw = "select orders_id, lat, lng from orders_to_latlng";
	$customers_query = tep_db_query($customers_query_raw);

	while ($customers = tep_db_fetch_array($customers_query)) 
	{
		$orders_total_query_raw = "select value from orders_total WHERE orders_id=" . $customers['orders_id'] . " AND class LIKE 'ot_subtotal'";
		$orders_total_query = tep_db_query($orders_total_query_raw);
		$order_total = tep_db_fetch_array($orders_total_query);
		
		$orders_query_raw = "select * from ". TABLE_ORDERS . " WHERE orders_id=" . $customers['orders_id'] ;
		$orders_query = tep_db_query($orders_query_raw);
		$order = tep_db_fetch_array($orders_query);	

        if ( tep_not_null(  $order['customers_name'] ) ) {
		  $content_name =  '"' . $order['customers_name']            . '<br />' .
                                 $order['customers_street_address']	 . '<br />' .
                                 $order['customers_city']            . '<br />' .
								 $order['customers_email_address']   . '<br />' .
						   '"' ;
		  $content_id   =  '"' . $order['orders_id'] . '"' ;		  
		} else {
		  $content_name =  '""' ;			
		  $content_id   =  '""' ;		  
		}		
		
		$string_orders .= '['.$customers['lat'].','.$customers['lng'].','.$content_name.','.$content_id.']' . ',' ;
 
	}

	$string_orders = substr($string_orders,0,strlen($string_orders)-1); // remove last comma
?>

   <div class="container-fluid">
      <div class="row">
         <div id="map" style="width: 100%; height: <?php echo GOOGLE_MAP_HEIGHT; ?>px;"><?php echo GOOGLE_MAP_LOADING_TXT; ?></div> 
      </div> 
   </div> 
  
<script type="text/javascript">
    var locations = [
       <?php echo $string_orders ; ?>
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: <?php echo GOOGLE_MAP_CENTER_ZOOM; ?>,
      center: new google.maps.LatLng(<?php echo GOOGLE_MAP_CENTER_LAT; ?>, <?php echo GOOGLE_MAP_CENTER_LNG; ?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i, html;

    for (i = 0; i < locations.length; i++) { 
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][0], locations[i][1]),
        map: map
      });
	  


      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
	  html = locations[i][2] + "<br /><a href=\"orders.php?oID=" + locations[i][3] + "&action=info&osCAdminID=<?php echo $HTTP_GET_VARS['osCAdminID'] ; ?>\" target=\"_blank\"><?php echo MAP_DETAIL_TXT; ?> #" + locations[i][3] + "</a><br />" ;
		
          infowindow.setContent(html);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
</script>

<?php 
      require(DIR_WS_INCLUDES . 'template_bottom.php'); 
      require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>