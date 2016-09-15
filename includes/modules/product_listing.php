<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2010 osCommerce
  Released under the GNU General Public License
*/
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ($messageStack->size('product_action') > 0) {
    echo $messageStack->output('product_action');
  }
?>
  <div class="contentText">
<?php
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<div class="row equal">
  <div class="col-sm-6 pagenumber hidden-xs">
    <?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>
  </div>
  <div class="col-sm-6">
    <div class="pull-right pagenav"><ul class="pagination"><?php echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul></div>
    <span class="pull-right"><?php echo TEXT_RESULT_PAGE; ?></span>
  </div>
</div>
<?php
  }

  if ($listing_split->number_of_rows > 0) { ?>
   
    <div class="well well-sm">
    <?php if (MODULE_JAVA_CSS_GRID_LIST_VIEW_STATUS == 'True') {
    ?>
      <strong><?php echo TEXT_VIEW; ?></strong>
      <div class="btn-group"><a href="#" id="list" class="btn btn-default btn-sm"><span class="<?php echo glyphicon_icon_to_fontawesome( "th-list" ) ; ?>"></span><?php echo TEXT_VIEW_LIST; ?></a> 
	                         <a href="#" id="grid" class="btn btn-default btn-sm"><span class="<?php echo glyphicon_icon_to_fontawesome( "th" ) ; ?>"></span><?php echo TEXT_VIEW_GRID; ?></a></div>
      <?php } ?>
      
      <div class="btn-group btn-group-sm pull-right">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?php echo TEXT_SORT_BY; ?><span class="caret"></span></button>
        
          <ul class="dropdown-menu text-left">
          
          <?php
          $lc_show_model = false;
          $lc_show_manu = false;
          $lc_show_qty = false;
          $lc_show_lbs = false;
		  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
               switch ($column_list[$col]) {
                  case 'PRODUCT_LIST_MODEL':
                    $lc_text = TABLE_HEADING_MODEL;
		            $lc_show_model = true;
                    break;
                  case 'PRODUCT_LIST_NAME':
                    $lc_text = TABLE_HEADING_PRODUCTS;
                    break;
                case 'PRODUCT_LIST_MANUFACTURER':
					$lc_text = TABLE_HEADING_MANUFACTURER;
					$lc_show_manu = true;
					break;
				case 'PRODUCT_LIST_PRICE':
					$lc_text = TABLE_HEADING_PRICE;
					break;
				case 'PRODUCT_LIST_QUANTITY':
					$lc_text = TABLE_HEADING_QUANTITY;
					$lc_show_qty = true;
					break;
				case 'PRODUCT_LIST_WEIGHT':
					$lc_text = TABLE_HEADING_WEIGHT;
					$lc_show_lbs = true;
					break;
				case 'PRODUCT_SORT_ORDER':
					$lc_text = TABLE_HEADING_SORT_ORDER;
					$lc_show_sort_order = true;
					break;					
				case 'PRODUCT_LIST_IMAGE':
					$lc_text = TABLE_HEADING_IMAGE;
					break;
				case 'PRODUCT_LIST_BUY_NOW':
					$lc_text = TABLE_HEADING_BUY_NOW;
					break;
				case 'PRODUCT_LIST_ID':
					$lc_text = TABLE_HEADING_LATEST_ADDED;
					break;
				}

                if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
                        $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
	                    echo '        <li>' . $lc_text . '</li>';
                }
    
         }	
		  ?>
         </ul>  
       </div><!-- button-group dropdown -->
       <div class="clearfix"></div>
    </div>
    <?php
    $listing_query = tep_db_query($listing_split->sql_query);
    
    $prod_list_contents = NULL;

    $prod_list_contents .= '<div id="product-listing">' . PHP_EOL ;
	$prod_list_contents .= '  <ul class="inline-span">' . PHP_EOL ;

	
    while ($listing = tep_db_fetch_array($listing_query)) {
		
      $prod_list_contents .= '    <li class="listingContainer equal-height">' . PHP_EOL ;
      $prod_list_contents .= '      <span itemscope itemtype="http://schema.org/Product">' . PHP_EOL ;	  
	  
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
		     $prod_list_contents .= '<span class="label label-info">' . TABLE_HEADING_MODEL . '</span>&nbsp;<span itemprop="mpn" class="productModel">' . $listing['products_model'] . '</span>&nbsp;';	
	         $prod_list_contents .= '      <div class="clearfix"></div>';				 
          break ;
		  
          case 'PRODUCT_LIST_NAME':
	         $prod_list_contents .= '      <hr>';		  
             $prod_list_contents .= '      <div class="caption">';		  
	         $prod_list_contents .= '        ';
             if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
                $prod_list_contents .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '"><span itemprop="name">' . $listing['products_name'] . '</span></a>';
             } else {
                $prod_list_contents .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '"><span itemprop="name">' . $listing['products_name'] . '</span></a>';
             }
             $prod_list_contents .= '       ';				 
	         $prod_list_contents .= '      </div> <!--caption-->';	
		 
	         $prod_list_contents .= '      <div class="clearfix"></div>';
	         $prod_list_contents .= '      <hr>';			 
          break ;
	      case 'PRODUCT_LIST_MANUFACTURER':
	         if (($lc_show_manu == true) && ($listing['manufacturers_id'] !=  0))    
		         $prod_list_contents .= '<div><span class="label label-info">' . TABLE_HEADING_MANUFACTURER . '</span>&nbsp;<span class="productManu"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a></span>&nbsp;</div>';
             $prod_list_contents .= '<span itemprop="manufacturer" itemscope itemtype="http://schema.org/Organization"><meta itemprop="name" content="' . $listing['manufacturers_name'] . '" /></span>';		  
	         $prod_list_contents .= '      <div class="clearfix"></div>';			 
          break ;
          case 'PRODUCT_LIST_PRICE':
             $prod_list_contents .= '      <hr><div align="center" >';
// bof sppc prices	  
             $pf->loadProduct( (int)$listing['products_id'],(int)$languages_id);
             $prod_list_contents .= '        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			      '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			       $pf->getPriceStringShort( null, null, null )
				   . '</font></span><br />' . PHP_EOL ;	
      
             $prod_list_contents .= '      </div> <!--pricewrap-->';
	  
       	     $prod_list_contents .= '      <div class="clearfix"></div>';		  
          break ;		  
         case 'PRODUCT_LIST_QUANTITY':
	         if (($lc_show_qty == true) && (tep_get_products_stock($listing['products_id'])!= 0) )
		          $prod_list_contents .= '<div><span class="label label-info">' . TABLE_HEADING_QUANTITY . '</span>&nbsp;<span class="productQty">' . tep_get_products_stock($listing['products_id']) . '</span>&nbsp;</div>';		  
	         $prod_list_contents .= '      <div class="clearfix"></div>';				  
          break ;
		  
          case 'PRODUCT_LIST_WEIGHT':
	         if (($lc_show_lbs == true) && ($listing['products_weight'] != 0))
		        $prod_list_contents .= '<div><span class="label label-info">' . TABLE_HEADING_WEIGHT . '</span>&nbsp;<span class="productWeight">' . $listing['products_weight'] . '</span>&nbsp;</div>';		  
	         $prod_list_contents .= '      <div class="clearfix"></div>';				
          break ;
		  
          case 'PRODUCT_LIST_DESCRIPTION':	  
	         $prod_list_contents .= '      <hr>';	  
/*** BOF End Header Tags SEO 327 */
             $hts_listing_query = tep_db_query("select products_head_listing_text, products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = " . (int)$listing['products_id'] . " and language_id = " . (int)$languages_id);
             if (tep_db_num_rows($hts_listing_query) > 0) { 
                $hts_listing = tep_db_fetch_array($hts_listing_query);
  	            if ( PRODUCT_LIST_DESCRIPTION_TEXT == 'true') {	 
                   $text = '';				  
				   if (tep_not_null($hts_listing['products_description'])) {
                     $text = sprintf("%s...%s",'        ' . strip_tags(substr($hts_listing['products_description'], 0, (int)PRODUCT_LIST_DESCRIPTION_MAX_LENGTH ), '<br>') . '',
					                           '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . (int)$listing['products_id']) .
 									               '" itemprop="url"><span class="mark">' . sprintf(TEXT_SEE_MORE, $listing['products_name']) . '</span></a>'
					 );
				   } 
  			       if (tep_not_null($hts_listing['products_head_listing_text'])) {
                        $text = sprintf("%s...%s", '        ' . strip_tags(substr($hts_listing['products_head_listing_text'], 0, (int)PRODUCT_LIST_DESCRIPTION_MAX_LENGTH ), '<br>') . '',
                                                   '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . (int)$listing['products_id']) . 
												     '" itemprop="url"><span class="mark">' . sprintf(TEXT_SEE_MORE, $listing['products_name']) . '</span></a>'
						);				   
                   }						
                   if (tep_not_null($text)) {					
                       $prod_list_contents .= '<div class="caption_long" itemprop="description"><small>' . $text . '</small></div>'  . PHP_EOL ;
                   }					   
			  }
             } 
/*** End Header Tags SEO 327 ***/	  
	         $prod_list_contents .= '      <div class="clearfix"></div>';
			 $prod_list_contents .= '      <hr>';
		  break ;
          case 'PRODUCT_LIST_IMAGE':
            $prod_list_contents .= '      <div class="text-center">';
      
	        if (isset($HTTP_GET_VARS['manufacturers_id'])  && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
              $prod_list_contents .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'itemprop="image"') . '</a>';
            } else {
              $prod_list_contents .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'itemprop="image"') . '</a>';
            }
	        $prod_list_contents .= '      </div> <!--placeholder-->';
	  
	        $prod_list_contents .= '      <div class="clearfix"></div>';
          break;	
          case 'PRODUCT_LIST_BUY_NOW':
            $prod_list_contents .= '      <div class="text-center"> ' ;  	  
            $prod_list_contents .= '        <div class="btn-group"> ' ; 

            if ( LISTING_BUTTON == 'details' or LISTING_BUTTON == 'both' )  {	  

                $prod_list_contents .= '      ' . tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 
	                                                                     'circle-arrow-right', 
																		 tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $listing['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-default btn-sm');	
            }																		 

            if ( $listing['products_purchase'] == '1' ) {
			  if ( LISTING_BUTTON == 'buy now' or LISTING_BUTTON == 'both' )   { 		  
                  $prod_list_contents .= '      ' . tep_draw_button(IMAGE_BUTTON_BUY_NOW, 
	                                                                     'shopping-cart', 
																		 tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']), 
																		 NULL, 
																		 NULL, 
																		 'btn-success btn-sm');
		      }
	       }
																 
           $prod_list_contents .= '         </div><!--btn-group-->' . PHP_EOL;	  
           $prod_list_contents .= '      </div><!--text-center-->' . PHP_EOL;
		   $prod_list_contents .= '      <div class="clearfix"></div>';
          break;		
		  case 'PRODUCT_SORT_ORDER';  
	        if (($lc_show_sort_order == true) && ($listing['products_sort_order'] != 0))
		         $prod_list_contents .= '<div><span class="label label-info">' . TABLE_HEADING_SORT_ORDER . '</span>&nbsp;<span class="productWeight">' . $listing['products_sort_order'] . '</span>&nbsp;</div>';
	         $prod_list_contents .= '      <div class="clearfix"></div>';	  

		  break ;		  
		  
		  
	    }	  
	  }
      $prod_list_contents .= '      </span>'. PHP_EOL ; 
      $prod_list_contents .= '    </li>';
	  
    }
    $prod_list_contents .= '  </ul>';
    $prod_list_contents .= '</div>';

    echo $prod_list_contents;
	
//BOF: Search Tag Cloud v2.4 by darkamex
    if (($_GET['keywords'] != '')) {
    $search_count = tep_db_query("select search, freq from customers_searches where search = '". $_GET['keywords'] . "' and language_id='". $languages_id . "'");
      if (!tep_db_num_rows($search_count)) {
      tep_db_query("insert into customers_searches (search, freq, language_id) values ('". tep_db_input($_GET['keywords']) ."',1," .$languages_id." )");
       } else {
      $search_val = tep_db_fetch_array($search_count);
      tep_db_query("update customers_searches set freq = " . ($search_val['freq']+1) . " where search = '". tep_db_input($_GET['keywords']) . "' and language_id='". $languages_id . "'");
      }
    }
//EOF: Search Tag Cloud v2.3 by faaliyet		
	
  } else {
?>
    <div class="alert alert-info"><?php echo TEXT_NO_PRODUCTS; ?></div>
<?php
  }

  if ( ($listing_split->number_of_rows > MAX_DISPLAY_PAGE_LINKS ) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<div class="row">
  <div class="col-sm-6 pagenumber hidden-xs">
    <?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>
  </div>
  <div class="col-sm-6">
    <div class="pull-right pagenav"><ul class="pagination"><?php echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul></div>
    <span class="pull-right"><?php echo TEXT_RESULT_PAGE; ?></span>
  </div>
</div>
<?php
  }
?>
</div>

