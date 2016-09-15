<?php
  require('includes/application_top.php');
   $term = $HTTP_POST_VARS['query'] ;

   $autoComplete =  tep_db_query("SELECT p.products_id,p.products_image,pd.products_name from " . TABLE_PRODUCTS . " p WHERE  p.products_name  like '%".$term ."%'");
   $list = tep_db_num_rows($autoComplete);
//   if($list > 0) {
     while($value = tep_db_fetch_array($autoComplete)){
//		echo tep_image(DIR_WS_IMAGES . $value['products_image'], $value['products_name'], "20", "20").','.$value['products_name'].','.$value['products_name']."\n";
        $results[] = $value['products_name'] ;
	 }
//  }

 echo json_encode($results);  
// return json_encode($results);  
// return json_encode( '{"Ashok","Rai","Vinod"}' );
?>