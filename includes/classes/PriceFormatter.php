<?php
/*
  $Id: PriceFormatter.php,v 1.10 2008/08/28 Exp $
  adapted for QPBPP for SPPC version 2.0 2008/11/08
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com   Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  PriceFormatter.php - module to support quantity pricing
  Created 2003, Beezle Software based on some code mods by WasaLab Oy (Thanks!)
  Refactored 2008, Moved pricebreak data into dedicated table
*/
class PriceFormatter {

  function PriceFormatter() {
    $this->thePrice = -1;
    $this->NettoPrice = -1;	
    $this->taxClass = -1;
    $this->qtyBlocks = 1;
    $this->products_min_order_qty = 1; // min order quantity added in QPBPP for SPPC v2.0
    $this->price_breaks = array();
    $this->hasQuantityPrice = false;  
    $this->hiPrice = -1;
    $this->lowPrice = -1;
    $this->hasSpecialPrice = false; //tep_not_null($this->specialPrice);
    $this->specialPrice = NULL; //$prices['specials_new_products_price'];
    $this->DiscountPercentage = NULL; // tep_get_discount_b2b($products_id)
    $this->hasDiscountPercentage = false; // tep_get_discount_b2b($products_id)	
  }

  function loadProduct($product_id, $language_id = 1, $listing = NULL, $price_breaks_from_listing = NULL) {
    global $pfs;
	
//	$old_group_id = $this->cg_id ; // old group id 
    $this->cg_id = $this->get_customer_group_id();

    $product_id = tep_get_prid($product_id); // only use integers here
	
	// if customer group is changed get new price
//	if ( $old_group_id == $this->cg_id ) {
    // returns NULL if the price break information is not yet stored
    $stored_price_formatter_data = $pfs->getPriceFormatterData($product_id);
    if (tep_not_null($stored_price_formatter_data)) {
      //Use data from the cache with some conversions
      $price_formatter_data = $stored_price_formatter_data;
      unset($stored_price_formatter_data);
    }
//	}

  if (!isset($price_formatter_data)) {
    if ($listing == NULL) {
      //Collect required data
       $sql = "select pd.products_name, p.products_model, p.products_image, p.products_id," .
   " p.manufacturers_id, p.products_price, p.products_weight, p.products_quantity," .
   " p.products_qty_blocks as qtyBlocks, p.products_min_order_qty, p.products_tax_class_id," .
   " NULL as specials_new_products_price," .
   " ptdc.discount_categories_id from " . TABLE_PRODUCTS . " p left join (select products_id, discount_categories_id from " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . "" .
   " where products_id = '" . (int)$product_id . "' and customers_group_id = '" . $this->cg_id . "') as ptdc on " .
   " p.products_id = ptdc.products_id, " .
   " " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1'" .
   " and pd.products_id = p.products_id " .
   " and p.products_id = '" . (int)$product_id . "'" .
   " and pd.language_id = '". (int)$language_id ."'";


      $product_info_query = tep_db_query($sql);
      $product_info = tep_db_fetch_array($product_info_query);
      
  if ($this->cg_id != '0') {
  // re-set qty blocks and min order qty to 1: do not use values for retail customers
      $product_info['qtyBlocks'] = 1;
      $product_info['products_min_order_qty'] = 1;

      $customer_group_price_query = tep_db_query("select customers_group_price, products_qty_blocks as qtyBlocks, products_min_order_qty from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$product_id . "' and customers_group_id =  '" . $this->cg_id . "'");
      
      if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
// customer group price can be null when qty blocks or min order qty is used
        if (tep_not_null($customer_group_price['customers_group_price'])) {
        $product_info['products_price'] = $customer_group_price['customers_group_price'];
        }
        $product_info['qtyBlocks'] = $customer_group_price['qtyBlocks'];
        $product_info['products_min_order_qty'] = $customer_group_price['products_min_order_qty'];
      }
  } // end if ($this->cg_id != '0')
  // now get the specials price for this customer_group and add it to product_info array
  $special_price_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = " . (int)$product_id . " and status = '1' and customers_group_id = '" . $this->cg_id . "'");
  if ($specials_price = tep_db_fetch_array($special_price_query)) {
	  $product_info['specials_new_products_price'] = $specials_price['specials_new_products_price'];
  }
      //Price-breaks

        $price_breaks_array = array();
        $price_breaks_query = tep_db_query("select products_price, products_qty from " . TABLE_PRODUCTS_PRICE_BREAK . " where products_id = '" . (int)$product_id . "' and customers_group_id = '" . $this->cg_id . "' order by products_qty");
        while ($price_break = tep_db_fetch_array($price_breaks_query)) {
          $price_breaks_array[] = $price_break;
        }
    
      //Compose cachable structure
      $price_formatter_data = array(
        'products_name' => $product_info['products_name'],
        'products_model' => $product_info['products_model'],
        'products_image' => $product_info['products_image'],
        'products_id' =>  $product_info['products_id'],
        'manufacturers_id' =>  $product_info['manufacturers_id'],
        'products_price' => $product_info['products_price'],
        'specials_new_products_price' => $product_info['specials_new_products_price'],
        'products_tax_class_id' => $product_info['products_tax_class_id'],
        'discount_categories_id' => $product_info['discount_categories_id'],
        'products_weight' => $product_info['products_weight'],
        'products_quantity' => $product_info['products_quantity'],
        'price_breaks' => $price_breaks_array,
        'qtyBlocks' => $product_info['qtyBlocks'],
        'products_min_order_qty' => $product_info['products_min_order_qty'],
		'customer_discount' => tep_get_discount_b2b($product_info['products_id']));

      //Add to cache
      $pfs->addPriceFormatterData($product_id, $price_formatter_data);
    } else { // data from product listing
      //Compose cachable structure
      $price_formatter_data = array(
        'products_name' => $listing['products_name'],
        'products_model' => $listing['products_model'],
        'products_image' => $listing['products_image'],
        'products_id' =>  $listing['products_id'],
        'manufacturers_id' =>  $listing['manufacturers_id'],
        'products_price' => $listing['products_price'],
        'specials_new_products_price' => $listing['specials_new_products_price'],
        'products_tax_class_id' => $listing['products_tax_class_id'],
        'discount_categories_id' => $listing['discount_categories_id'],
        'products_weight' => $listing['products_weight'],
        'products_quantity' => $listing['products_quantity'],
        'price_breaks' => $price_breaks_from_listing,
        'qtyBlocks' => $listing['qtyBlocks'],
        'products_min_order_qty' => $listing['products_min_order_qty'],
		'customer_discount' => tep_get_discount_b2b($listing['products_id']));
      //Add to cache
      $pfs->addPriceFormatterData($product_id, $price_formatter_data);
    }
  } // end if (!isset($price_formatter_data))
    
    //Assign members
    $this->product_id = $product_id; // needed for adjustQty
    $this->thePrice = $price_formatter_data['products_price'];
    $this->BrutoPrice = $price_formatter_data['products_price'];	
    $this->taxClass = $price_formatter_data['products_tax_class_id'];
    $this->qtyBlocks = $price_formatter_data['qtyBlocks'];
    $this->products_min_order_qty = $price_formatter_data['products_min_order_qty'];
    $this->discount_categories_id = $price_formatter_data['discount_categories_id'];
    $this->price_breaks = $price_formatter_data['price_breaks'];
    $this->specialPrice = $price_formatter_data['specials_new_products_price'];
    $this->hasSpecialPrice = tep_not_null($this->specialPrice);
	$this->DiscountPercentage =  tep_get_discount_b2b( $product_id ) ;
	//$price_formatter_data['customer_discount'];	
	$this->hasDiscountPercentage = ( $this->DiscountPercentage != 0 );
// totalb2b	begin
    if (true == $this->hasDiscountPercentage) {          
       if ($this->DiscountPercentage >= 0) {
            $this->thePrice = $this->BrutoPrice + $this->BrutoPrice * abs($this->DiscountPercentage) / 100;
       } else {
            $this->thePrice = $this->BrutoPrice - $this->BrutoPrice * abs($this->DiscountPercentage) / 100;
       }
	}
// totalb2b end

    //Custom      
    $this->hasQuantityPrice = false;
    $this->hiPrice = $this->thePrice;
    $this->lowPrice = $this->thePrice;
    if (count($this->price_breaks) > 0) {
      $this->hasQuantityPrice = true;
      foreach($this->price_breaks as $price_break) {
        $this->hiPrice = max($this->hiPrice, $price_break['products_price']);
        $this->lowPrice = min($this->lowPrice, $price_break['products_price']);
      }
    }

    /*
    Change support special prices
    If any price level has a price greater than the special
    price lower it to the special price
    If product is in the shopping_cart $this->price_breaks can be empty
    */
    if (true == $this->hasSpecialPrice && is_array($this->price_breaks)) {
      foreach($this->price_breaks as $key => $price_break) {
        $this->price_breaks[$key]['products_price'] = min($price_break['products_price'], $this->specialPrice);
      }
    }
    //end changes to support special prices
  }
  
  function computePrice($qty, $nof_other_items_in_cart_same_cat = 0)
  {
    $qty = $this->adjustQty($qty);

    // Add the number of other items in the cart from the same category to see if a price break is reached
    $qty += $nof_other_items_in_cart_same_cat;

    // Compute base price, taking into account the possibility of a special
    $price = (true == $this->hasSpecialPrice) ? $this->specialPrice : $this->thePrice;

    if (is_array($this->price_breaks) && count($this->price_breaks) > 0) {
      foreach($this->price_breaks as $price_break) {
        if ($qty >= $price_break['products_qty']) {
          $price = $price_break['products_price'];
        }
      }
    } // end if (is_array($this->price_breaks) && count($this->price_breaks) > 0)

    return $price;
  }

  function adjustQty($qty, $qtyBlocks = NULL) {
    // Force QTY_BLOCKS granularity
    if (!tep_not_null($qtyBlocks)) {
      $qtyBlocks = $this->getQtyBlocks();
    }
    $minimum_order_quantity = $this->getMinOrderQty();
     if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ((int)$qty > MAX_QTY_IN_CART)) {
        $qty = MAX_QTY_IN_CART;
      }
    
    if ($qty < 1) {
      $qty = 1;
      }

    if ($qtyBlocks >= 1) {
      $remove_session_min_order_qty_not_met = 0;
      if ($qty < $minimum_order_quantity) {
        $qty = $minimum_order_quantity; // make sure quantity is minimum order quantity first
        $_SESSION['min_order_qty_not_met'][] = $this->product_id;
      }
      if ($qty < $qtyBlocks) {
        $qty = $qtyBlocks;
        $_SESSION['qty_blocks_not_met'][] = $this->product_id;
        $remove_session_min_order_qty_not_met = 1;
      }
      if (($qty % $qtyBlocks) != 0) {
        $qty += ($qtyBlocks - ($qty % $qtyBlocks));
        if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ($qty > MAX_QTY_IN_CART)) {
          $qty -= $qtyBlocks;
        }

        $_SESSION['qty_blocks_not_met'][] = $this->product_id;
        $remove_session_min_order_qty_not_met = 1;
      }
      // no two different warnings for the same product
      if ($remove_session_min_order_qty_not_met == 1 && isset($_SESSION['min_order_qty_not_met'])) {
        foreach ($_SESSION['min_order_qty_not_met'] as $moq_key => $moq_pid) {
          if ($moq_pid == $this->product_id) {
            unset($_SESSION['min_order_qty_not_met'][$moq_key]);
          }
        }
      } // end if ($remove_session_min_order_qty_not_met == 1 && isset(...
    }
    return $qty;
  }
  
  function getQtyBlocks() {
    return $this->qtyBlocks;
  }

  function getMinOrderQty() {
    return $this->products_min_order_qty;
  }

  function get_discount_category() {
    return $this->discount_categories_id;
  }

  function getPrice() {
    return $this->thePrice;
  }
  function BrutoPrice() {
    return $this->BrutoPrice;
  }  
 
  function SpecialPrice() {
    return $this->specialPrice;
  }  

  function getLowPrice() {
    return $this->lowPrice;
  }

  function getHiPrice() {
    return $this->hiPrice;
  }

  function hasSpecialPrice() {
    return $this->hasSpecialPrice;
  }

  function hasQuantityPrice() {
    return $this->hasQuantityPrice;
  }

  function DiscountPercentage() {
    return $this->DiscountPercentage;
  }  
  function hasDiscountPercentage() {
    return $this->hasDiscountPercentage;
  }  

  function getDiscountSaving($original_price, $discount_price) {
    $difference = $original_price - $discount_price;
    $percentage = (($difference / $original_price) * 100);
    if ($percentage == '0') {
    return '- ';
    } else {
      return round ($percentage) . '%';
    }
  }  
  function VATpercentage() {
    return $this->taxClass;
  }    
  function getPriceString( $standard='', $highlight='', $instock_or_not = '') {
    global $currencies, $currency;

    // If you want to change the format of the price/quantity table
    // displayed on the product information page, here is where you do it.
    $styling = 'padding-left: 10px;padding-right:10px;';
    $no_of_price_breaks = count($this->price_breaks);
    $qtyBlocks = $this->getQtyBlocks();

    if (true == $this->hasQuantityPrice) {
// if number of price breaks exceeds a number (set in Admin_>Configuration->Price breaks)
// a dropdown with price breaks followed by "from Low Price" is shown instead of table.
    if ($no_of_price_breaks >= (defined('NOF_PRICE_BREAKS_FOR_DROPDOWN') ? NOF_PRICE_BREAKS_FOR_DROPDOWN : 10)) {
      $lc_text = $this->getPriceDropDown( 'false');
      return $lc_text;
    }

      $lc_text = '<div class="panel panel-info">
	               <div class="panel-heading"></div>
	                <div class="table-responsive">
	                 <table class="table table-bordered">
		              <thead>
			           <tr class="primary">';
//      $lc_text .= '       <tr valign="top">' . PHP_EOL ;

	  if (true == $this->hasDiscountPercentage) {
          $lc_text .= '<th> ' . TEXT_STD_PRICE .'</th>' . PHP_EOL ;
      }	 	  
      $lc_text .= '<th>' . TEXT_ENTER_QUANTITY .'</th>' . PHP_EOL ;
      if ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty) {
        $lc_text .= '<th>' . $this->products_min_order_qty ;
        if (($this->price_breaks[0]['products_qty'] - $this->products_min_order_qty) > $qtyBlocks) {
          $lc_text .= '-' . ($this->price_breaks[0]['products_qty'] - $qtyBlocks);
        }
        $lc_text .= '</th>' . PHP_EOL ;
      } // end if ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty)
      
      foreach($this->price_breaks as $key => $price_break) {
          $lc_text .= '<th>'
            . $price_break['products_qty'];
          if ($key == $no_of_price_breaks -1) {
            $lc_text .= '+&nbsp;</th>' . PHP_EOL ;
          } else {
            if (($this->price_breaks[$key + 1]['products_qty'] - $this->price_breaks[$key]['products_qty']) > $qtyBlocks) {
            $lc_text .= '-' . ($this->price_breaks[$key+1]['products_qty'] - $qtyBlocks) . '</th>' . PHP_EOL ;
            }
          } 
      } // end foreach($this->price_breaks as $key => $price_break)
      $lc_text .= '    </tr>' . PHP_EOL ;
      $lc_text .= '   </thead>' ;	  
// end header for the price breaks

      $lc_text .= '<tbody>' ;
      $lc_text .= '   <tr class="success">' ;	  
	  if (true == $this->hasDiscountPercentage) {
          $lc_text .= '<td>' .  $currencies->display_price($this->BrutoPrice, tep_get_tax_rate($this->taxClass)) .'</td>' . PHP_EOL ;
      }	
	  
      $lc_text .= '<td>' . TEXT_PRICE_PER_PIECE . '</td>' . PHP_EOL ;

  
      if (true == $this->hasSpecialPrice && ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty)) {
	    // google microdata
        $lc_text .= '<td>';
        $lc_text .= '<del>' . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</del>' 
          . '        <mark>' .  $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass)) . '</mark>'
          .'</td>' . PHP_EOL ; // productSpecialPrice
		  
		  
      } elseif ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty) {
       $lc_text .= '<td>';		  
       $lc_text .= ' <mark>' .  $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</mark>'
                  .'</td>' . PHP_EOL ; // productSpecialPrice		  
//        $lc_text .= '<td align="center" >'
//        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
//        . '</td>' . PHP_EOL ;
      }
  
      $number_price_breaks = count( $this->price_breaks ) ;
	  $count               = 1 ;
      foreach($this->price_breaks as $price_break) {
		 if ( $count == $number_price_breaks ) {
// bof micro data added 	itemprop="price"	  
			$lc_text .= '<td itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . PHP_EOL ;
			$lc_text .= ' <meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' 
			  . '        <span itemprop="price">' .  $currencies->display_price($price_break['products_price'], tep_get_tax_rate($this->taxClass)) . '</span>'
			  . 	     $instock_or_not . PHP_EOL ;
	//            . $currencies->display_price($price_break['products_price'], tep_get_tax_rate($this->taxClass))
			$lc_text .= '</td>' . PHP_EOL ;			 
		 } else {
           $lc_text .= '<td>' 
              . '     ' .  $currencies->display_price($price_break['products_price'], tep_get_tax_rate($this->taxClass)) . PHP_EOL ;				 
		   $lc_text .= '</td>' . PHP_EOL ;				  
		 }
	     $count++ ;
      }
      $lc_text .= '   </tr>' . PHP_EOL ;
//      $lc_text .= '</tbody>' . PHP_EOL ;	  
  
// Begin saving calculation
//      $lc_text .= '<tbody>' . PHP_EOL ;
      $base_price = $this->thePrice;
      // if you have a min order quantity set, this might be the first entry
      // in the price break table so let's check for that
      if ($this->products_min_order_qty > 1 && $this->products_min_order_qty == $this->price_breaks[0]['products_qty']) {
        $base_price = $this->price_breaks[0]['products_price'];
      }
      // in case of a special price the "Savings" are calculated against the normal price
      // apart from the first column which calculates against the special price
      $lc_text .= '<tr class="info">';
	  if (true == $this->hasDiscountPercentage) {
          $lc_text .= '<td>' . abs($this->DiscountPercentage) .'%</td>' . PHP_EOL ;
      }	
      $lc_text .= '<td>' . TEXT_SAVINGS . '</td>' . PHP_EOL ;
	  
      if (true == $this->hasSpecialPrice && ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty)) {
        $lc_text .= '<td >'
        . $this->getDiscountSaving($base_price, $this->specialPrice)
        .'</td>' . PHP_EOL ;
      } elseif ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty) {
        $lc_text .= '<td>- </td>' . PHP_EOL ;
      }

      foreach($this->price_breaks as $price_break) {
        if ($price_break['products_qty'] > $this->qtyBlocks) {
          $lc_text .= '<td>'
          . $this->getDiscountSaving($base_price, $price_break['products_price'])
          .'</td>' . PHP_EOL ;
        } else {
          $lc_text .= '<td>- </td>' . PHP_EOL ;
        }
      }
	  
	  
	  $lc_text .= '</tr>
	                 
					    </tbody>
						   </table>
						      </div>
							    </div>';

    } else {
      if (true == $this->hasSpecialPrice) {
       $lc_text  =  ' <div class="panel panel-info">'. PHP_EOL ;
        $lc_text .=  '   <div class="panel-heading">' . TEXT_PRICE_PER_PIECE . '</div>'. PHP_EOL ;
        $lc_text .=  '   <div class="panel-body">'. PHP_EOL ;
		
		
//        $lc_text .=  '<font size="'.PRODUCT_PRICE_SIZE.'">&nbsp;<del>' 
//		                      . $standard  . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))     . '</span></del>&nbsp;&nbsp;' . 
//		                        $highlight . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass)) . '</span>&nbsp;' .
//					 '</font>'; 	
	    $lc_text .=  '      <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			         '          <font size="'.PRODUCT_PRICE_SIZE.'">' . 
			         '              <del><class="'. $standard . '">' .                    $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</' . $standard . '></del>'  .					 
			         '              <meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
					 
			         '              <mark class="'. $highlight . '" itemprop="price">' .  $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass)) . '</mark>'  .
				     '          </font>' . PHP_EOL ;		
		// stock from cm_pi_price_box
	    $lc_text .=	 $instock_or_not ; 
        $lc_text .=  '      </span>'. PHP_EOL ;		
					 
        $lc_text .=  '   </div>'. PHP_EOL ;
        $lc_text .=  ' </div>'. PHP_EOL ;
//       $lc_text = '&nbsp;<s>' . $standard 
//        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
//        . '</span></s>&nbsp;&nbsp;' . $highlight
//        . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
//        . '</span>&nbsp;'; //productSpecialPrice		
      } else {

        $lc_text  =  ' <div class="panel panel-info">'. PHP_EOL ;
        $lc_text .=  '   <div class="panel-heading">' . TEXT_PRICE_PER_PIECE . '</div>'. PHP_EOL ;
        $lc_text .=  '   <div class="panel-body">'. PHP_EOL ;
		
	    $lc_text .=  '      <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			         '          <font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			         '              <mark class="' . $highlight . '" itemprop="price">' .  $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</mark>'  .
				     '          </font>' . PHP_EOL ;	
	    
		$lc_text .=	 $instock_or_not ; 					 
        $lc_text .=  '      </span>'. PHP_EOL ;					 
//        $lc_text .=  '<font size="'.PRODUCT_PRICE_SIZE.'">&nbsp;' . $highlight . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</span>&nbsp;</font>'. PHP_EOL ;
        $lc_text .=  '   </div>'. PHP_EOL ;
        $lc_text .=  ' </div>'. PHP_EOL ;  

      }
    }
    return $lc_text;
  }
  
  function getPriceStringShort( $std = '', $high = '', $instock_or_not = '') {
    global $currencies;

    if (true == $this->hasQuantityPrice) {
	    $lc_text .=  '      ' . 
			         '          <font size="'.PRODUCT_PRICE_SIZE.'">' 
					 .              TEXT_PRICE_BREAKS . 
					 '              <meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			         '              <mark class="' . $highlight . '" itemprop="price">' .  $currencies->display_price($this->lowPrice, tep_get_tax_rate($this->taxClass)) . '</mark>'  .
				     '          </font>' . PHP_EOL ;	
	    
		$lc_text .=	 $instock_or_not ; 		
        $lc_text .=  '       <br />'. PHP_EOL ;			
//      $lc_text = '<font size="'.PRODUCT_PRICE_SIZE.'">&nbsp;' . $highlight . TEXT_PRICE_BREAKS . ' '
 //     . $currencies->display_price($this->lowPrice, tep_get_tax_rate($this->taxClass))
 //     . '</span></font>&nbsp;<br />';
    } elseif (true == $this->hasSpecialPrice) {
//        $lc_text = '<font size="'.PRODUCT_PRICE_SIZE.'">&nbsp;<del>' . $standard 
//        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
//        . '</span></del>&nbsp;&nbsp; ' . $highlight  
//        . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
//        . '</span></font>&nbsp;<br />';	  
	    $lc_text .=    
			         '          <font size="'.PRODUCT_PRICE_SIZE.'">' . 
			         '              <del><class="'. $standard . '">' .                    $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</' . $standard . '></del>'  .					 
			         '              <meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
					 
			         '              <mark class="'. $highlight . '" itemprop="price">' .  $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass)) . '</mark>'  .
				     '          </font>' . PHP_EOL ;		
		// stock from cm_pi_price_box
	    $lc_text .=	 $instock_or_not ; 
        $lc_text .=  '       <br />'. PHP_EOL ;		
    } else {
		
	    $lc_text .=  '      ' . 
			         '          <font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			         '              <mark class="' . $highlight . '" itemprop="price">' .  $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</mark>'  .
				     '          </font>' . PHP_EOL ;	
	    
		$lc_text .=	 $instock_or_not ; 					 
        $lc_text .=  '      <br />'. PHP_EOL ;	
		
//        $lc_text = '<font size="'.PRODUCT_PRICE_SIZE.'">&nbsp;' . $highlight 
//        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
//        . '&nbsp;</span></font><br />';
    }
    return $lc_text;
  }
  
  function getMobilePriceString($style='productPriceInBox') {
    global $currencies;

    // If you want to change the format of the price/quantity table
    // displayed on the product information page, here is where you do it.
    $styling = 'padding-left: 10px;padding-right:10px;';
    $no_of_price_breaks = count($this->price_breaks);
    $qtyBlocks = $this->getQtyBlocks();

    if (true == $this->hasQuantityPrice) {
// if number of price breaks exceeds a number (set in Admin_>Configuration->Price breaks)
// a dropdown with price breaks followed by "from Low Price" is shown instead of table.
    if ($no_of_price_breaks >= (defined('NOF_PRICE_BREAKS_FOR_DROPDOWN') ? NOF_PRICE_BREAKS_FOR_DROPDOWN : 10 )) {
      $lc_text = $this->getPriceDropDown();
      return $lc_text;
    }

      $lc_text = '<div id="users-contain" class="ui-body-'. SYS_MOBILE_THEME .' ui-corner-all ">
	        <table id="users" class="ui-corner-all">
		      <thead align="center">
			    <tr class="ui-body-'. SYS_MOBILE_THEME .' ui-corner-all">' ;
//	         '<table border="0" cellspacing="0" cellpadding="0" class="ui-widget-header ui-corner-all" align="right">
//              <tr valign="top">
//              <td>
//              <table border="0" cellspacing="1" cellpadding="4" class="ui-widget-content ui-corner-all">' . PHP_EOL ;
      $lc_text .= '<tr valign="top">' . PHP_EOL ;
	  
	  if (true == $this->hasDiscountPercentage) {
          $lc_text .= '<td style="' . $styling . '" class="ui-bar-'. SYS_MOBILE_THEME .' ui-corner-all">' . TEXT_STD_PRICE .'</td>' . PHP_EOL ;
      }	 	  
      $lc_text .= '<td style="' . $styling . '" class="ui-bar-'. SYS_MOBILE_THEME .' ui-corner-all">' . TEXT_ENTER_QUANTITY .'</td>' . PHP_EOL ;
      if ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty) {
        $lc_text .= '<td align="center" class="ui-bar-'. SYS_MOBILE_THEME .' ui-corner-all">' . $this->products_min_order_qty;
        if (($this->price_breaks[0]['products_qty'] - $this->products_min_order_qty) > $qtyBlocks) {
          $lc_text .= '-' . ($this->price_breaks[0]['products_qty'] - $qtyBlocks);
        }
        $lc_text .= '</td>' . PHP_EOL ;
      } // end if ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty)
      
      foreach($this->price_breaks as $key => $price_break) {
          $lc_text .= '<td align="center" style="' . $styling . '" class="ui-bar-'. SYS_MOBILE_THEME .' ui-corner-all">'
            . $price_break['products_qty'];
          if ($key == $no_of_price_breaks -1) {
            $lc_text .= '+&nbsp;</td>' . PHP_EOL ;
          } else {
            if (($this->price_breaks[$key + 1]['products_qty'] - $this->price_breaks[$key]['products_qty']) > $qtyBlocks) {
            $lc_text .= '-' . ($this->price_breaks[$key+1]['products_qty'] - $qtyBlocks) . '</td>' . PHP_EOL ;
            }
          } 
      } // end foreach($this->price_breaks as $key => $price_break)
      $lc_text .= '</tr>' . PHP_EOL ;
      $lc_text .= '<tr valign="top">' ;
	  if (true == $this->hasDiscountPercentage) {
          $lc_text .= '<td style="' . $styling . '" class="ui-btn-hover-'. SYS_MOBILE_THEME .' ui-corner-all">' . $currencies->display_price($this->BrutoPrice, tep_get_tax_rate($this->taxClass)) .'</td>' . PHP_EOL ;
      }	
	  
      $lc_text .= '<td style="' . $styling . '" class="ui-body-'. SYS_MOBILE_THEME .' ui-corner-all">' . TEXT_PRICE_PER_PIECE . '</td>' . PHP_EOL ;
      if (true == $this->hasSpecialPrice && ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty)) {
        $lc_text .= '<td align="center" style="' . $styling . '" class="ui-btn-hover-'. SYS_MOBILE_THEME .'  ui-corner-all">';
        $lc_text .= '<s>' 
		  .  $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
          . '</s>&nbsp;&nbsp;<span class="ui-btn-active ui-corner-all">'  
          . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
          . '</span>&nbsp;'
          .'</td>' . PHP_EOL ; // productSpecialPrice
      } elseif ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty) {
        $lc_text .= '<td align="center" style="' . $styling . '" class="ui-btn-active  ui-corner-all">'
        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
        . '</td>' . PHP_EOL ;
      }
      
      foreach($this->price_breaks as $price_break) {
          $lc_text .= '<td align="center" style="' . $styling . '" class="ui-btn-active  ui-corner-all">'
            . $currencies->display_price($price_break['products_price'], tep_get_tax_rate($this->taxClass))
            .'</td>' . PHP_EOL ;
      }
      $lc_text .= '</tr>' . PHP_EOL ;
  
      // Begin saving calculation
      $base_price = $this->thePrice;
      // if you have a min order quantity set, this might be the first entry
      // in the price break table so let's check for that
      if ($this->products_min_order_qty > 1 && $this->products_min_order_qty == $this->price_breaks[0]['products_qty']) {
        $base_price = $this->price_breaks[0]['products_price'];
      }
      // in case of a special price the "Savings" are calculated against the normal price
      // apart from the first column which calculates against the special price
      $lc_text .= '<tr valign="top">';
	  if (true == $this->hasDiscountPercentage) {
          $lc_text .= '<td align="center" class="ui-btn-active ui-corner-all">' . abs($this->DiscountPercentage) .'%</td>' . PHP_EOL ;
      }	
      $lc_text .= '<td style="' . $styling . '" class="ui-body-'. SYS_MOBILE_THEME .' ui-corner-all">' . TEXT_SAVINGS . '</td>' . PHP_EOL ;
	  
      if (true == $this->hasSpecialPrice && ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty)) {
        $lc_text .= '<td align="center" class="ui-btn-active ui-corner-all">'
        . $this->getDiscountSaving($base_price, $this->specialPrice)
        .'</td>' . PHP_EOL ;
      } elseif ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty) {
        $lc_text .= '<td align="center" class="ui-btn-active ui-corner-all">- </td>' . PHP_EOL ;
      }

      foreach($this->price_breaks as $price_break) {
        if ($price_break['products_qty'] > $this->qtyBlocks) {
          $lc_text .= '<td align="center" style="' . $styling . '" class="ui-btn-active ui-corner-all">'
          . $this->getDiscountSaving($base_price, $price_break['products_price'])
          .'</td>' . PHP_EOL ;
        } else {
          $lc_text .= '<td align="center" class="ui-btn-active ui-corner-all">- </td>' . PHP_EOL ;
        }
      }
      $lc_text .= '</tr></tr></thead></table></div>';
    } else {
	  $lc_text = '<div id="users-contain" class="ui-body-'. SYS_MOBILE_THEME .' ui-corner-all ">' ;
      if (true == $this->hasSpecialPrice) {	    
        $lc_text .= '&nbsp;</span class="ui-bar-'. SYS_MOBILE_THEME .' ui-corner-all"><s>'
        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
        . '</s></span>&nbsp;&nbsp;<span class="ui-btn-active ui-corner-all">'
        . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
        . '</span>&nbsp;'; //productSpecialPrice
      } else {
        $lc_text .= '&nbsp;&nbsp;<span class="ui-btn-active ui-corner-all">'
        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
		. '</span>'
        . '&nbsp;';
      }
	  $lc_text .= '</div>' ;
    }
    return $lc_text;
  }

  function getPriceDropDown( $mobile_website = 'false' ) {
    global $currencies;
    $no_of_price_breaks = count($this->price_breaks);
    $qtyBlocks = $this->getQtyBlocks();

      $dropdown_price_breaks = array();
      $i = 0;
      $pb_text = '';
      if (true == $this->hasSpecialPrice) {
        $dropdown = '&nbsp;<s>' 
        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
        . '</s>&nbsp;&nbsp; ' 
        . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
        . '&nbsp;<br />';	  
      } else {
        $dropdown = '&nbsp;' 
        . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
        . '&nbsp;<br />';
      }	  

      if ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty) {
        $pb_text = PB_DROPDOWN_BEFORE . $this->products_min_order_qty;
        if (($this->price_breaks[0]['products_qty'] - $this->products_min_order_qty) > $qtyBlocks) {
          $pb_text .= '-' . ($this->price_breaks[0]['products_qty'] - $qtyBlocks);
        }
        $pb_text .= PB_DROPDOWN_BETWEEN . $currencies->display_price($this->hiPrice, tep_get_tax_rate($this->taxClass)) . PB_DROPDOWN_AFTER;
        $dropdown_price_breaks[] = array('id' => $i, 'text' => $pb_text);
        $i++;
      } // end if ($this->price_breaks[0]['products_qty'] > $this->products_min_order_qty)

     for ($z = 0; $z < $no_of_price_breaks; $z++) {
       $pb_text = PB_DROPDOWN_BEFORE . $this->price_breaks[$z]['products_qty']; // start again
       if ($z == $no_of_price_breaks -1) {
         // last one
         $pb_text .= '+';
       } else {
         if (($this->price_breaks[$z + 1]['products_qty'] - $this->price_breaks[$z]['products_qty']) > $qtyBlocks) {
           $pb_text .= '-' . ($this->price_breaks[$z + 1]['products_qty'] - $qtyBlocks);
         }
       }
       $pb_text .= PB_DROPDOWN_BETWEEN . $currencies->display_price($this->price_breaks[$z]['products_price'], tep_get_tax_rate($this->taxClass)) . PB_DROPDOWN_AFTER;
       $dropdown_price_breaks[] = array('id' => $i, 'text' => $pb_text);
       $i++;
     } // end for ($z = 0; $z < $no_of_price_breaks; $z++)
//	 if ( $mobile_website = 'true' ) { 
//	     $dropdown = tep_mobile_pull_down_menu('price_breaks', $dropdown_price_breaks, '0', '', '', STOCK_LIST_IN_PI_TEXT_PRICE,'price_breaks', SYS_MOBILE_THEME,                                            // $datatheme = 'a'
//									                                                                                                            MOBILE_SYS_USE_SELECT_MENU_NATIVE_EFFECT ) ;                        // $use_native_menu = 'false' ) ;
//     } else {		 
		 $dropdown = tep_draw_pull_down_menu('price_breaks', $dropdown_price_breaks, '0', 'style="font-weight: normal"');         
//     }		 
	 $dropdown .= '&nbsp;<span class="smalltext">' . PB_FROM . '</span>&nbsp;' . $currencies->display_price($this->lowPrice, tep_get_tax_rate($this->taxClass)) . PHP_EOL ;

     return $dropdown;
  }
// added for Separate Pricing Per Customer, returns customer_group_id
    function get_customer_group_id() {
// bof multi stores 	
      if (isset($_SESSION['sppc_customer_group_id']) ) {  // && $_SESSION['sppc_customer_group_id'] != '0'
        $_cg_id = $_SESSION['sppc_customer_group_id'];
      } else {
//         $_cg_id = 0;
         $_cg_id =  _SYS_STORE_CUSTOMER_GROUP ;		 
// eof multi stores		 
      }
      return $_cg_id;
    }
}
?>