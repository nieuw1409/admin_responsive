<?php
/*
      QT Pro Version 4.0
  
      stats_low_stock_attrib.php
  
      Contribution extension to:
        osCommerce, Open Source E-Commerce Solutions
        http://www.oscommerce.com
     
      Copyright (c) 2004 Ralph Day
      Released under the GNU General Public License
  
      Based on prior works released under the GNU General Public License:
        QT Pro prior versions
          Ralph Day, October 2004
          Tom Wojcik aka TomThumb 2004/07/03 based on work by Michael Coffman aka coffman
          FREEZEHELL - 08/11/2003 freezehell@hotmail.com Copyright (c) 2003 IBWO
          Joseph Shain, January 2003
        osCommerce MS2
          Copyright (c) 2003 osCommerce
          
      Modifications made:
        11/2004 - Clean up to not replicate for all languages
                  Handle multiple attributes per product
                  Ignore attributes that stock isn't tracked for
                  Remove unused code
        
*******************************************************************************************
  
      QT Pro Low Stock Report
  
      This report lists all products and products attributes that have stock less than
      the reorder level configured in the osCommerce admin site

      
*******************************************************************************************

  $Id: stats_products.php,v 1.22 2002/03/07 20:30:00 harley_vb Exp $
  (v 1.3 by Tom Wojcik aka TomThumb 2004/07/03)
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

    require('includes/application_top.php');
   require(DIR_WS_INCLUDES . 'template_top.php');
   
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1  class="col-xs-12 col-md-6"><?php echo HEADING_TITLE ; ?></h1> 
            <div class="col-xs-12 col-md-6 ">
                <h2 class="text-center"><?php echo strftime(DATE_FORMAT_LONG); ?></h2>			
			</div>  
             <div class="clearfix"></div>
            </div><!-- page-header-->
            <div class="table-responsive">
             <table  id="stock_products" class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th colspan=2>          <?php echo TABLE_HEADING_PRODUCTS; ?></th>
                   <th class="text-left">  <?php echo TABLE_HEADING_MODEL; ?></th>				    			   
                   <th class="text-center"><?php echo TABLE_HEADING_QUANTITY; ?></th>				   
                   <th class="text-left">  <?php echo TABLE_HEADING_PRICE; ?></th>	   		   
                </tr>
              </thead>
              <tbody>
<?php			  
				  $products_query_raw = "select p.products_id, pd.products_name, p.products_model, p.products_quantity,p.products_price, l.name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_LANGUAGES . " l where p.products_id = pd.products_id and p.products_id = pd.products_id and l.languages_id = pd.language_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name ASC";
                  $products_low_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
 				  
				  $products_query = tep_db_query($products_query_raw);
				  while ($products = tep_db_fetch_array($products_query)) {
					    $products_id = $products['products_id'];

					  // check for product or attributes below reorder level
					    $products_stock_query=tep_db_query("SELECT products_stock_attributes, products_stock_quantity 
														  FROM " . TABLE_PRODUCTS_STOCK . " 
														  WHERE products_id=" . $products['products_id'] ." 
														  ORDER BY products_stock_attributes");
					    $products_stock_rows=tep_db_num_rows($products_stock_query);
					    // Highlight products with low stock
					    if ($products['products_quantity'] > STOCK_REORDER_LEVEL){
						   $trclass="";
					    } else {  
						   $trclass="bg-danger text-danger";
					    } 
										 
						$products_quantity= $products['products_quantity'];
						$products_price=($products_stock_rows > 0) ? '' : $currencies->format($products['products_price']);			  
?>
						<tr>
                            <td colspan=2>          <?php echo '<a href="' . tep_href_link(FILENAME_STOCK, 'product_id=' . $products['products_id']) . '">' . $products['products_name'] .'</a>'; ?>&nbsp;</td>
                            <td class="text-left">  <?php echo $products['products_model']; ?></td>
                            <td class="text-center <?php echo $trclass; ?>">  <?php echo $products_quantity; ?></td>
                            <td class="text-left">  <?php echo $products_price; ?>&nbsp;</td>						
						</tr> 
<?php						
                        if ($products_stock_rows > 0) {
                           $products_options_name_query = tep_db_query("SELECT distinct popt.products_options_id, popt.products_options_name 
                                                       FROM " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib 
                                                       WHERE patrib.products_id='" . $products['products_id'] . "' 
                                                       AND patrib.options_id = popt.products_options_id 
                                                       AND popt.products_options_track_stock = '1' 
                                                       AND popt.language_id = '" . (int)$languages_id . "' 
                                                       ORDER BY popt.products_options_id");		
						  
?>	
                           <tr class="row">
 
							  <thead>
								<tr class="heading-row">
<?php
                                   while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
                                       echo '<th>' . $products_options_name['products_options_name'] . '</th>' ;
                                   }								
                                   echo     '<th class="text-center">' . TABLE_HEADING_QUANTITY . '</th>' ;								   
                                   echo     '<th>' . TABLE_HEADING_PRICE . '</th>' ;
?>								     		   
								</tr>
							  </thead>
							  <tbody>
<?php
                                 // build array of attributes price delta
                                 $attributes_price = array();
                                 $products_attributes_query = tep_db_query("SELECT pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix 
                                                     FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                                                     WHERE pa.products_id = '" . $products['products_id'] . "'"); 
           
		                         while ($products_attributes_values = tep_db_fetch_array($products_attributes_query)) {
                                 $option_price = $products_attributes_values['options_values_price'];
                                 if ($products_attributes_values['price_prefix'] == "-") $option_price= -1*$option_price;
                                    $attributes_price[$products_attributes_values['options_id']][$products_attributes_values['options_values_id']] = $option_price;
                                 }
								 
                                 while($products_stock_values=tep_db_fetch_array($products_stock_query)) {
                                        $attributes=explode(",",$products_stock_values['products_stock_attributes']);
                                        echo '<tr>' . PHP_EOL ; 
			                            $total_price=$products['products_price'];
			
			                            // Highlight products out of stock
	  			                        if (($products_stock_values['products_stock_quantity']) > STOCK_REORDER_LEVEL){
					                       $trclassstock="bg-success text-success";
					                    } else {
						                   $trclassstock="bg-danger text-danger";
					 	                }						
						
                                        foreach($attributes as $attribute) {
                                           $attr=explode("-",$attribute);
                                           echo '<td class=' . $trclassstock . '" >' . tep_values_name($attr[1]) . '</td>' . PHP_EOL;
                                           $total_price+=$attributes_price[$attr[0]][$attr[1]];
                                        }
			                            $total_price=$currencies->format($total_price);
										
                                        echo '<td class="text-center ' . $trclassstock . '">' . $products_stock_values['products_stock_quantity'] . '</td>'. PHP_EOL ;						
                                        echo '<td class="' . $trclassstock . '">' . $total_price .                                      '</td>'. PHP_EOL ;											
                                        echo '</tr>' . PHP_EOL ;
                                 }			  
?>							  
                              </tbody>
   
						   </tr>
						 
<?php
                        }
				  }
?>						   
              </tbody>
            </table>
          </div> <!-- div end table-responsive -->			
   </table>
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $products_low_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $products_low_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div>
    </table>  
	
				
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>