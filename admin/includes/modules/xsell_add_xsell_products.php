<?php	
	$products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
	$products_name = tep_db_fetch_array($products_name_query);
	
	if ($products_name['products_model'] == NULL) {
	   $products_model = TEXT_NONE;
	} else {
	   $products_model = $products_name['products_model'];
	} // end if ($products_name['products_model'] == NULL)
?>
    <div class="panel panel-info">
	  <div class="panel-heading"><?php echo TEXT_SETTING_SELLS . ' : <span class="bg-primary">' . $products_name['products_name'] ; ?></span></div>
	  <div class="panel-body">
	  
<?php
                  echo '   <div class="col-xs-12">' . PHP_EOL ;
                  echo '' .   tep_draw_bs_form('search_add_xsell', FILENAME_XSELL_PRODUCTS_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&add_related_product_ID=' . $HTTP_GET_VARS['add_related_product_ID'], 'get', 'role="form"'  ). PHP_EOL ;				
                  echo '         <div class="form-group">' . PHP_EOL;				  
                  echo              tep_draw_bs_input_field('search_add_xsell', '',  TEXT_SEARCH_IN_RESULTS, 'xsell_input_search' , 'control-label col-xs-3', 'col-xs-8', 'left', TEXT_SEARCH_IN_RESULTS	).  PHP_EOL; 
 				  echo '         </div>' . PHP_EOL;					  
				  
				  

                  if (isset($HTTP_GET_VARS['search_add_xsell']) && tep_not_null($HTTP_GET_VARS['search_add_xsell'])) {
                     echo '      <div class="col-xs-1">'. PHP_EOL ;
		             echo            tep_draw_bs_button(IMAGE_RESET, 'refresh', tep_href_link(FILENAME_XSELL_PRODUCTS_PRODUCTS, 'page=' . $HTTP_GET_VARS['page'] . '&add_related_product_ID=' . $_GET['add_related_product_ID'] )); 
                     echo '      </div>'. PHP_EOL ;
                  }		   				   
			      echo            tep_hide_session_id() ;
	              echo '      </form>' . PHP_EOL ; 			  
 				  echo '   </div>' . PHP_EOL ;	
				  
				  
                  echo '' . tep_draw_bs_form('update_cross', FILENAME_XSELL_PRODUCTS_PRODUCTS, 'action=update_cross', 'get', 'role="form"'  ). PHP_EOL ;

                  echo '         <div class="form-group">' . PHP_EOL;				  
                  echo              tep_draw_hidden_field('add_related_product_ID', $HTTP_GET_VARS['add_related_product_ID']) . PHP_EOL ;			  
 				  echo '         </div>' . PHP_EOL;		
				  
                  echo  '    <div class="col-xs-3">' . PHP_EOL ;
                  echo          tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products_name['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . PHP_EOL ;
                  echo  '    </div>' . PHP_EOL ;				  
				  
//                  echo  '    <div class="col-xs-3">' . PHP_EOL ;				  
//                  echo  '       <li class="list-group-item">' . PHP_EOL ;
//                  echo  '         <div class="row">' . PHP_EOL ;	
                  echo  '             <div class="col-xs-2 list-group-item">'. $products_name['products_name'] . '</div>' . PHP_EOL ;	
                  echo  '             <div class="col-xs-2 list-group-item hidden-xs hidden-sm">' . TEXT_MODEL.': ' . $products_model . '</div>' . PHP_EOL ;	
                  echo  '             <div class="col-xs-1 list-group-item">' . TEXT_PRODUCT_ID.': '. $_GET['add_related_product_ID'] . '</div> ' . PHP_EOL ;	
//                  echo  '         </div>' . PHP_EOL ;	  
//                  echo  '       </li>' . PHP_EOL ;					  
 
                  echo  '    <div class="col-xs-4">' . PHP_EOL ; 
 	              echo          tep_draw_bs_button(IMAGE_SAVE,   'ok' ); 
 	              echo          tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_XSELL_PRODUCTS_PRODUCTS), 'page=' . $HTTP_GET_VARS['page']); 				  
                  echo '     </div>'. PHP_EOL ;
                  	   				   	  
			      echo        tep_hide_session_id() ;
//	              echo '   </form>' . PHP_EOL ; 			  
?>		  
	  
	  </div> <!-- end panel info body -->
	</div> <!-- end panel info -->	
    <table class="table table-condensed table-striped table-responsive">
        <thead>
           <tr class="heading-row">
             <th>                                 <?php echo TABLE_HEADING_PRODUCT_ID; ?></th>
             <th class="hidden-xs hidden-sm">     <?php echo TABLE_HEADING_PRODUCT_MODEL; ?></th>
             <th class="text-center">             <?php echo TABLE_HEADING_PRODUCT_IMAGE; ?></th>
             <th class="text-center">             <?php echo TABLE_HEADING_PRODUCT_NAME; ?></th>
             <th class="text-center">             <?php echo TABLE_HEADING_PRODUCT_PRICE; ?></th>			        			 
             <th class="text-left">               <?php echo TABLE_HEADING_ACTION; ?></th>				  
           </tr>
        </thead>
        <tbody>
<?php		
// Create a list of products to cross sell
			$xsell_array = array(); 
            $xsell_query = tep_db_query('select x.xsell_id from ' . TABLE_PRODUCTS . ' p, ' . TABLE_PRODUCTS_XSELL . ' x where x.products_id = "'.$_GET['add_related_product_ID'].'"');
			while ($xsell = tep_db_fetch_array($xsell_query)) {
			  $xsell_array[] = $xsell['xsell_id'];
		    } // end while ($xsell = tep_db_fetch_array($xsell_query))
            $num_xsell = tep_db_num_rows($xsell_query);
		    if (isset($_GET['search_add_xsell'])) {
		      $products_query_raw = 'select distinct p.products_id, p.products_image, p.products_model, p.products_price, pd.products_name from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and ((pd.products_name like "%' . tep_db_prepare_input(addslashes($_GET['search_add_xsell'])) . '%") or (p.products_model like "%' . tep_db_prepare_input(addslashes($_GET['search_add_xsell'])) . '%")) and pd.language_id = "'.(int)$languages_id.'" group by p.products_id order by pd.products_name asc';
		    } else {
		      $products_query_raw = 'select distinct p.products_id, p.products_image, p.products_model, pd.products_name, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" group by p.products_id order by pd.products_name asc';
		    } // end if (isset($_GET['search']))
		    $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
		  
		    $products_query = tep_db_query($products_query_raw);
			while ($products = tep_db_fetch_array($products_query)) {
			  if (!in_array($products['products_id'], $xsell_array)) {
			    $xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');
			    $xsold_query_reciprocal = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$products['products_id'].'" and xsell_id = "'.$_GET['add_related_product_ID'].'"');
				
				if ($products['products_model'] == NULL) {
			      $products_model = TEXT_NONE;
		        } else {
			      $products_model = $products['products_model'];
		        } // end if ($products['products_model'] == NULL)
?>		
	             <tr>  
				 
		            <td>                                <?php echo $products['products_id'];?></td>
		            <td class="hidden-xs hidden-sm">    <?php echo $products_model;?></td>
		            <td class="text-center">            <?php echo tep_image(DIR_WS_CATALOG_IMAGES  . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>
					
		            <td class="text-center">            <?php echo $products['products_name'];?></td>
		            <td class="text-center">            <?php echo $currencies->format($products['products_price']);?></td>
					<td>
                       <div class="form-group">
<?php 
                          echo    tep_draw_hidden_field('product[]', $products['products_id']) . PHP_EOL ;
 	                      echo '  <div class="checkbox checkbox-success">' . PHP_EOL ;						  
						  echo      tep_bs_checkbox_field('cross[]', $products['products_id'],                 TEXT_CROSS_SELL,      'id_check_box_cross_sell',      ((tep_db_num_rows($xsold_query) > 0) ? true : false),             ' checkbox checkbox-success ', '', '', 'right' ) . PHP_EOL ;
 	                      echo '  </div>' . PHP_EOL ;						  
						  
	                      echo '  <div class="checkbox checkbox-success">' . PHP_EOL ;						  
						  echo      tep_bs_checkbox_field('reciprocal_link_cross[]', $products['products_id'], TEXT_RECIPROCAL_LINK, 'id_check_box_reciprocal_sell', ((tep_db_num_rows($xsold_query_reciprocal) > 0) ? true : false), ' checkbox checkbox-success ', '', '', 'right' ) . PHP_EOL ;						  
 	                      echo '  </div>' . PHP_EOL ;
?>						  
                       </div>
					</td>
								
				 </tr>
<?php
		      } // end if (!in_array($products['products_id'], $xsell_array))
	        } // end while ($products = tep_db_fetch_array($products_query))		 
?>	  
		</tbody>
	</table>
<?php	
	echo            tep_hide_session_id() ;
?>	
	</form> <!-- checkboxes relatex and recipical links -->
	
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></div>		   
	     </div>
	</table>	