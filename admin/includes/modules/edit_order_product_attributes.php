<?php
	  $contents_edit_orders_order_products .= '<div class="table-responsive">'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '<div  class="panel panel-primary">'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '    <div class="panel-heading">' .  TEXT_PRODUCTS_TABLE . '</div>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '        <table class="table table-bordered">'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '            <thead>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '                <tr>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '                    <th style="width:2%">' . TABLE_HEADING_DELETE . '</th>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '                    <th >' . TABLE_HEADING_QUANTITY . '</th>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '                    <th >' . TABLE_HEADING_PRODUCTS . '</th>'. PHP_EOL;	
//	  $contents_edit_orders_order_products .= '                    <th>' . TABLE_HEADING_PRODUCTS_MODEL . '</th>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '                    <th>' . TABLE_HEADING_TAX . '</th>'. PHP_EOL;		  
	  
	  $contents_edit_orders_order_products .= '                    <th>' . TABLE_HEADING_BASE_PRICE . '</th>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '                    <th>' . TABLE_HEADING_UNIT_PRICE . '</th>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '                    <th>' . TABLE_HEADING_UNIT_PRICE_TAXED . '</th>'. PHP_EOL;		  
	  $contents_edit_orders_order_products .= '                    <th>' . TABLE_HEADING_TOTAL_PRICE . '</th>'. PHP_EOL;		  
	  $contents_edit_orders_order_products .= '                    <th>' . TABLE_HEADING_TOTAL_PRICE_TAXED . '</th>'. PHP_EOL;		  
	  $contents_edit_orders_order_products .= '                </tr>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '            </thead>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '            <tbody>'. PHP_EOL;	
	  
      if (sizeof($order->products)) {
        for ($i=0; $i<sizeof($order->products); $i++) {
           $orders_products_id = $order->products[$i]['orders_products_id'];  

           $update_string =  'onKeyUp="updatePrices( %s, %s)"' ; 
		   
	       $contents_edit_orders_order_products .= '                <tr>'. PHP_EOL;	
	  
 	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .=  '                     <div class="form-group">' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=	                                          tep_bs_checkbox_field('update_products[' . $orders_products_id . '][delete]',  null, null, 'do_not_delete'.$orders_products_id, false, 'checkbox checkbox-success', '', '', 'right') . PHP_EOL;	
	       $contents_edit_orders_order_products  .=  '                     </div>' . PHP_EOL;
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;

	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][qty]', $order->products[$i]['qty'], null,    'update_products[' . $orders_products_id . '][qty]' , '', 'col-xs-12', 'left', '', sprintf($update_string, "'qty'", (string)$orders_products_id ), 'onKeyUp="updatePrices((string)qty,' . (string)$orders_products_id . ')"'	) . PHP_EOL; //'onKeyUp="updatePrices((string)qty,' . (string)$orders_products_id . ')"'
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;	  
	  
           $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][name]', oe_html_quotes($order->products[$i]['name']), null,    'update_products_'.$orders_products_id , '', 'col-xs-12', 'left' 	) . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][model]', $order->products[$i]['model'], null,    'update_products[' . $orders_products_id . '][model]' , '', 'col-xs-12', 'left'	) . PHP_EOL;
		   
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;
    
	       // Has Attributes?
           if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
             for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
                $orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];

	            $contents_edit_orders_order_products  .= '		          <div class="clearfix"></div><hr>' . PHP_EOL;
	            $contents_edit_orders_order_products  .= '	              <div class="form-goup">' . PHP_EOL;
				
	            $contents_edit_orders_order_products  .=                       tep_draw_bs_input_field('update_products[' . $orders_products_id . '][attributes][' . $orders_products_attributes_id . '][option]',  oe_html_quotes($order->products[$i]['attributes'][$j]['option']) , null,    'update_products_attr_option'.$orders_products_attributes_id ,                       null, 'col-xs-12' 	) . PHP_EOL;
	            $contents_edit_orders_order_products  .= '	              </div>' . PHP_EOL;

	            $contents_edit_orders_order_products  .= '	              <div class="form-goup">' . PHP_EOL;				
	            $contents_edit_orders_order_products  .=                       tep_draw_bs_input_field('update_products[' . $orders_products_id . '][attributes][' . $orders_products_attributes_id . '][value]',   oe_html_quotes($order->products[$i]['attributes'][$j]['value']) ,  null,    'update_products_attr_value'.$orders_products_attributes_id ,                        null, 'col-xs-12' 	) . PHP_EOL;
	            $contents_edit_orders_order_products  .= '	              </div>' . PHP_EOL;

				$price_prefix = ( tep_not_null($order->products[$i]['attributes'][$j]['prefix'] ) ? $order->products[$i]['attributes'][$j]['prefix'] : '+' ) ;
	            $contents_edit_orders_order_products  .= '	              <div class="form-goup">' . PHP_EOL;			
	            $contents_edit_orders_order_products  .=                       tep_draw_bs_input_field(   'update_products[' . $orders_products_id . '][attributes][' . $orders_products_attributes_id . '][price]',   $order->products[$i]['attributes'][$j]['price'] ,                  null,    'p' . (string)$orders_products_id . 'a' . (string)$orders_products_attributes_id ,                   null, 'col-xs-7', null, '', sprintf($update_string, "'att_price'", (string)$orders_products_id ), 'onKeyUp="updatePrices("att_price",' . $orders_products_id . ')"' 	) . PHP_EOL;	// 'onKeyUp="updatePrices("att_price",' . $orders_products_id . ')"'		
	            $contents_edit_orders_order_products  .=                       tep_draw_bs_input_field(   'update_products[' . $orders_products_id . '][attributes][' . $orders_products_attributes_id . '][prefix]',  $order->products[$i]['attributes'][$j]['prefix'] ,                 null,    'p' . (string)$orders_products_id . '_' . (string)$orders_products_attributes_id . '_prefix'  ,      null, 'col-xs-5', null, '', sprintf($update_string, "'att_price'", (string)$orders_products_id ), 'onKeyUp="updatePrices("att_price",' . $orders_products_id . ')"' 	) . PHP_EOL;	// 'onKeyUp="updatePrices("att_price",' . $orders_products_id . ')"'
//				$contents_edit_orders_order_products .=                        tep_draw_bs_pull_down_menu('update_products[' . $orders_products_id . '][attributes][' . $orders_products_attributes_id . '][prefix]',               $price_prefix_array, $order->products[$i]['attributes'][$j]['prefix'] , null, 'p' . (string)$orders_products_id . '_' . (string)$orders_products_attributes_id . '_prefix', 'col-xs-6', ' selectpicker show-tick ', '', '', 'onchange="updatePrices("att_price",' . $orders_products_id . ')"')  . PHP_EOL;	
	            $contents_edit_orders_order_products  .= '	              </div>' . PHP_EOL;				
			}  //end for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
		
			 //Has downloads?
  
            if (DOWNLOAD_ENABLED == 'true') {
               $downloads_count = 1;
               $d_index = 0;
               $download_query_raw ="SELECT orders_products_download_id, orders_products_filename, download_maxdays, download_count
                         FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "                               
						 WHERE orders_products_id='" . $orders_products_id . "'
						 AND orders_id='" . (int)$oID . "'
						 ORDER BY orders_products_download_id";
  
		       $download_query = tep_db_query($download_query_raw);
		
		       if (isset($downloads->products)) unset($downloads->products);
		           if (tep_db_num_rows($download_query) > 0) {
                      while ($download = tep_db_fetch_array($download_query)) {
		
 		                $downloads->products[$d_index] = array( 'id' => $download['orders_products_download_id'],
		                                                        'filename' => $download['orders_products_filename'],
                                                                'maxdays' => $download['download_maxdays'],
                                                                'maxcount' => $download['download_count']);
		
		                $d_index++; 
		
		             } 
                   } 
        
                  if (isset($downloads->products) && (sizeof($downloads->products) > 0)) {
                     for ($mm=0; $mm<sizeof($downloads->products); $mm++) {  
                        $id =  $downloads->products[$mm]['id'];
                        echo '<br><small>';
                        echo '<nobr>' . ENTRY_DOWNLOAD_COUNT . $downloads_count . "";
                        echo ' </nobr><br>' . "\n";
  
                        echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_FILENAME . ": <input name='update_downloads[" . $id . "][filename]' size='12' value='" . $downloads->products[$mm]['filename'] . "'>";
                        echo ' </nobr><br>' . "\n";
                        echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXDAYS . ": <input name='update_downloads[" . $id . "][maxdays]' size='6' value='" . $downloads->products[$mm]['maxdays'] . "'>";
                        echo ' </nobr><br>' . "\n";
                        echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXCOUNT . ": <input name='update_downloads[" . $id . "][maxcount]' size='6' value='" . $downloads->products[$mm]['maxcount'] . "'>";
     
                        echo ' </nobr>' . "\n";
                        echo '<br></small>';
                        $downloads_count++;
                   } //end  for ($mm=0; $mm<sizeof($download_query); $mm++) {
              }
             } //end download
           } //end if (sizeof($order->products[$i]['attributes']) > 0) {

		   
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;	 

//	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
//	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
//	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][model]', $order->products[$i]['model'], null,    'update_products[' . $orders_products_id . '][model]' , '', 'col-xs-12', 'left'	) . PHP_EOL;
//	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
//	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;	 		   
		   
	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][tax]', $order->products[$i]['tax'], '%',    'update_products[' . $orders_products_id . '][tax]' , 'col-xs-3', 'col-xs-9', 'right', '', sprintf($update_string, "'tax'", (string)$orders_products_id ), 'onKeyUp="updatePrices("tax",' . $orders_products_id . ')"'	) . PHP_EOL;//'onKeyUp="updatePrices("tax",' . $orders_products_id . ')"'
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;	 	
		   
	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][price]', $order->products[$i]['price'], null,    'update_products[' . $orders_products_id . '][price]' , '', 'col-xs-12', 'left', '', sprintf($update_string, "'price'", (string)$orders_products_id ), 'onKeyUp="updatePrices("price",' . $orders_products_id . ')"'	) . PHP_EOL; //'onKeyUp="updatePrices("price",' . $orders_products_id . ')"'
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;	 		   
		   
	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][final_price]', $order->products[$i]['final_price'], null,    'update_products[' . $orders_products_id . '][final_price]' , '', 'col-xs-12', 'left', '', sprintf($update_string, "'final_price'", (string)$orders_products_id ),'onKeyUp="updatePrices("final_price", $orders_products_id )"'	) . PHP_EOL; //'onKeyUp="updatePrices("final_price", $orders_products_id )"'
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;			   
		   
	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][price_incl]', number_format(($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1)), 4, '.', ''), null,    'update_products[' . $orders_products_id . '][price_incl]' , '', 'col-xs-12', 'left', '', sprintf($update_string, "'price_incl'", (string)$orders_products_id ), 'onKeyUp="updatePrices("price_incl",' . $orders_products_id . ')"'	) . PHP_EOL; //'onKeyUp="updatePrices("price_incl",' . $orders_products_id . ')"'
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;			   
		   
	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][total_excl]', number_format($order->products[$i]['final_price'] * $order->products[$i]['qty'], 4, '.', ''), null,    'update_products[' . $orders_products_id . '][total_excl]' , '', 'col-xs-12', 'left', '', sprintf($update_string, "'total_excl'", (string)$orders_products_id ), 'onKeyUp="updatePrices("total_excl",' . $orders_products_id . ')"'	) . PHP_EOL; //'onKeyUp="updatePrices("total_excl",' . $orders_products_id . ')"'
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;			   
		   
	       $contents_edit_orders_order_products  .=  '                  <td>' . PHP_EOL;
	       $contents_edit_orders_order_products  .= '	                  <div class="form-group">' . PHP_EOL;
	       $contents_edit_orders_order_products  .=                           tep_draw_bs_input_field('update_products[' . $orders_products_id . '][total_incl]', number_format((($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1))) * $order->products[$i]['qty'], 4, '.', ''), null,    'update_products[' . $orders_products_id . '][total_incl]' , '', 'col-xs-12', 'left', '', sprintf($update_string, "'total_incl'", (string)$orders_products_id ), 'onKeyUp="updatePrices("total_incl",' . $orders_products_id . ')"'	) . PHP_EOL; //'onKeyUp="updatePrices("total_incl",' . $orders_products_id . ')"'
	       $contents_edit_orders_order_products  .= '	                  </div>' . PHP_EOL;	  
	       $contents_edit_orders_order_products  .=  '                  </td>' . PHP_EOL;			   

 			   
	    } // end for ($i=0; $i<sizeof($order-
	  } // if (sizeof($order->pr
	
	  $contents_edit_orders_order_products .= '                </tr>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '            </tbody>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '        </table>'. PHP_EOL;	
	  $contents_edit_orders_order_products .= '    </div>'. PHP_EOL;  // END <div  class="panel panel-prima
	  $contents_edit_orders_order_products .= '</div>'. PHP_EOL;	  // end '<div class="table-responsive


       echo '      <!-- begin order edit products -->           ' . PHP_EOL .
             '                   ' . PHP_EOL .
             '                     <div class="row ' . $alertClass . '">' . PHP_EOL .
                                     $contents_edit_orders_order_products . 
             '                    </div>' . PHP_EOL .
             '                  ' . PHP_EOL .
             '                ' . PHP_EOL .
			 '     <!-- end order edit products --> ' . PHP_EOL ;	
?>