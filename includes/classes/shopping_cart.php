<?php
/*
  $Id: shopping_cart.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class shoppingCart {
    var $contents, $total, $weight, $cartID, $content_type;

    function shoppingCart() {
      $this->reset();
    }

    function restore_contents() {
      global $customer_id;
	  global $languages_id; // languages_id needed for PriceFormatter - QPBPP

      if (!tep_session_is_registered('customer_id')) return false;

// insert current cart contents in database
      if (is_array($this->contents)) {
        reset($this->contents);
// BOF SPPC attribute hide/invalid check: loop through the shopping cart and check the attributes if they
// are hidden for the now logged-in customer
      $this->cg_id = $this->get_customer_group_id();
        while (list($products_id, ) = each($this->contents)) {
					// only check attributes if they are set for the product in the cart
				   if (isset($this->contents[$products_id]['attributes'])) {
				$check_attributes_query = tep_db_query("select options_id, options_values_id, IF(find_in_set('" . $this->cg_id . "', attributes_hide_from_groups) = 0, '0', '1') as hide_attr_status from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . tep_get_prid($products_id) . "'");
				while ($_check_attributes = tep_db_fetch_array($check_attributes_query)) {
					$check_attributes[] = $_check_attributes;
				} // end while ($_check_attributes = tep_db_fetch_array($check_attributes_query))
				$no_of_check_attributes = count($check_attributes);
				$change_products_id = '0';

				foreach($this->contents[$products_id]['attributes'] as $attr_option => $attr_option_value) {
					$valid_option = '0';
					for ($x = 0; $x < $no_of_check_attributes ; $x++) {
						if ($attr_option == $check_attributes[$x]['options_id'] && $attr_option_value == $check_attributes[$x]['options_values_id']) {
							$valid_option = '1';
							if ($check_attributes[$x]['hide_attr_status'] == '1') {
							// delete hidden attributes from array attributes, change products_id accordingly later
							$change_products_id = '1';
							unset($this->contents[$products_id]['attributes'][$attr_option]);
							}
						} // end if ($attr_option == $check_attributes[$x]['options_id']....
					} // end for ($x = 0; $x < $no_of_check_attributes ; $x++)
					if ($valid_option == '0') {
						// after having gone through the options for this product and not having found a matching one
						// we can conclude that apparently this is not a valid option for this product so remove it
						unset($this->contents[$products_id]['attributes'][$attr_option]);
						// change products_id accordingly later
						$change_products_id = '1';
					}
				} // end foreach($this->contents[$products_id]['attributes'] as $attr_option => $attr_option_value)

          if ($change_products_id == '1') {
	           $original_products_id = $products_id;
	           $products_id = tep_get_prid($original_products_id);
	           $products_id = tep_get_uprid($products_id, $this->contents[$original_products_id]['attributes']);
						 // add the product without the hidden attributes to the cart
	           $this->contents[$products_id] = $this->contents[$original_products_id];
				     // delete the originally added product with the hidden attributes
	           unset($this->contents[$original_products_id]);
            }
				  } // end if (isset($this->contents[$products_id]['attributes']))
				} // end while (list($products_id, ) = each($this->contents))
       reset($this->contents); // reset the array otherwise the cart will be emptied
// EOF SPPC attribute hide/invalid check
		
        while (list($products_id, ) = each($this->contents)) {
          $qty = $this->contents[$products_id]['qty'];
// BOF QPBPP for SPPC adjust quantity blocks and min_order_qty for this customer group
// warnings about this are raised in PriceFormatter
      $pf = new PriceFormatter;
      $pf->loadProduct(tep_get_prid($products_id), $languages_id);
      $qty = $pf->adjustQty($qty);
// EOF QPBPP for SPPC		  
          $product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          if (!tep_db_num_rows($product_query)) {
            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . tep_db_input($qty) . "', '" . date('Ymd') . "')");
            if (isset($this->contents[$products_id]['attributes'])) {
			  ksort($this->contents[$products_id]['attributes']);
// bof 231 option type
	          $uploads_query = tep_db_query("select files_uploaded_name from " . TABLE_FILES_UPLOADED . " where sesskey = '" . tep_session_id() . "'");
              while ($uploads_array = tep_db_fetch_array($uploads_query)) {
                if (file_exists(TMP_DIR . $uploads_array['files_uploaded_name'])) { // Customer upload found in TMP dir --> Copy to Upload Dir
                  @rename(TMP_DIR . $uploads_array['files_uploaded_name'], UPL_DIR . $uploads_array['files_uploaded_name']);
                  // Set Customer_ID for the files that are found
                  tep_db_query("update " . TABLE_FILES_UPLOADED . " set customers_id = '" . (int)$customer_id . "' where sesskey = '" . tep_session_id() . "' and files_uploaded_name = '" . $uploads_array['files_uploaded_name'] . "'");
                }
              }
			  ksort($this->contents[$products_id]['attributes']);
              reset($this->contents[$products_id]['attributes']);
              while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
                $attr_value = $this->contents[$products_id]['attributes_values'][$option];
                tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $option . "', '" . (int)$value . "', '" . tep_db_input($attr_value) . "')");
              }			  
                // OTF contrib ends
// eof 231 option type
              
            }
          } else {
            tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . tep_db_input($qty) . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
          }
        }
      }

// reset per-session cart contents, but not the database contents
      $this->reset(false);


// BOF QPBPP for SPPC
      $products_query = tep_db_query("select cb.products_id, ptdc.discount_categories_id, customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " cb left join (select products_id, discount_categories_id from " . TABLE_PRODUCTS_TO_DISCOUNT_CATEGORIES . " where customers_group_id = '" . $this->cg_id . "') as ptdc on cb.products_id = ptdc.products_id where customers_id = '" . (int)$customer_id . "'");
      while ($products = tep_db_fetch_array($products_query)) {
          $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity'], 'discount_categories_id' => $products['discount_categories_id']);
// EOF QPBPP for SPPC
// attributes        
          $attributes_query = tep_db_query("select products_options_id, products_options_value_id, products_options_value_text from " 
		                                        . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " 
												      where customers_id = '" . (int)$customer_id . "' 
													    and products_id = '" . tep_db_input($products['products_id']) . "' order by products_options_id");
        // OTF contrib ends
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
                  //CLR 020606 if text attribute, then set additional information

          // OTF contrib begins
          if ($attributes['products_options_value_id'] == OPTIONS_VALUE_TEXT_ID) {
            $this->contents[$products['products_id']]['attributes_values'][$attributes['products_options_id']] = $attributes['products_options_value_text'];
          }      
          // OTF contrib ends        
		}
      }
      $this->cleanup();
    }

    function reset($reset_database = false) {
      global $customer_id;

      $this->contents = array();
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;

      if (tep_session_is_registered('customer_id') && ($reset_database == true)) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "'");
      }

      unset($this->cartID);
      if (tep_session_is_registered('cartID')) tep_session_unregister('cartID');
    }

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {
      global $new_products_id_in_cart, $customer_id;
// BOF Separate Pricing Per Customer 
      $this->cg_id = $this->get_customer_group_id();
// EOF Separate Pricing Per Customer	  
// BOF qpbpp
      $pf = new PriceFormatter;
      $pf->loadProduct($products_id);
      $qty = $pf->adjustQty($qty);
      $discount_category_id = $pf->get_discount_category();
// EOF qpbpp
      if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ((int)$qty > MAX_QTY_IN_CART)) {
        $qty = MAX_QTY_IN_CART;
      }
// BOF QPBPP for SPPC
//      $pf = new PriceFormatter;
//      $pf->loadProduct($products_id);
//      $qty = $pf->adjustQty($qty);
//      $discount_category = $pf->get_discount_category();
// EOF QPBPP for SPPC
      //if (is_numeric($products_id) && is_numeric($qty) && ($attributes_pass_check == true)) {
      $products_id = tep_get_uprid($products_id, $attributes);
      // OTF contrib ends
        $check_product_query = tep_db_query("select products_status from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
        $check_product = tep_db_fetch_array($check_product_query);

        if (($check_product !== false) && ($check_product['products_status'] == '1')) {
          if ($notify == true) {
            $new_products_id_in_cart = $products_id;
            tep_session_register('new_products_id_in_cart');
          }

          // OTF contrib begins
          if ($this->in_cart($products_id)) {          
            $this->update_quantity($products_id, $qty, $attributes, $discount_category_id);
          } else {
            $this->contents[$products_id] = array('qty' => (int)$qty, 'discount_categories_id' => $discount_category_id);
			
//            $this->update_quantity($products_id, $qty, $attributes, $discount_category_id);
//          } else {
//            $this->contents[] = array($products_id);
//            $this->contents[$products_id] = array('qty' => (int)$qty);
          // OTF contrib ends

// insert into database
            // OTF contrib begins
            if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");
            // OTF contrib ends
            if (is_array($attributes)) {
              reset($attributes);
              while (list($option, $value) = each($attributes)) {

            // OTF contrib begins
            //$this->contents[$products_id_string]['attributes'][$option] = $value;
            $attr_value = NULL;
            $blank_value = FALSE;
            if (strstr($option, TEXT_PREFIX)) {
              if (trim($value) == NULL)
              {
                $blank_value = TRUE;
              } else {
                $option = substr($option, strlen(TEXT_PREFIX));
                $attr_value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
                $value = OPTIONS_VALUE_TEXT_ID;
                $this->contents[$products_id]['attributes_values'][$option] = $attr_value;
              }
            }
            if (!$blank_value)
            {
              $this->contents[$products_id]['attributes'][$option] = $value;
            // OTF contrib ends
// insert into database
                // OTF contrib begins
                  if (tep_session_is_registered('customer_id')) tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int)$customer_id . "', '" . tep_db_input($products_id) . "', '" . (int)$option . "', '" . (int)$value . "', '" . tep_db_input($attr_value) . "')");
                }
                // OTF contrib ends
              }
            }
          }

          $this->cleanup();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
    // OTF contrib begins 
        $this->cartID = $this->generate_cart_id();
      }
    }

//    function update_quantity($products_id, $quantity = '', $attributes = '') {
// BOF qpbpp
    function update_quantity($products_id, $quantity = '', $attributes = '', $discount_categories_id = NULL) {
// EOF qpbpp	
      global $customer_id;
	  
// bof qpbpp	  
      $products_id_string = tep_get_uprid($products_id, $attributes);

      if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ((int)$quantity > MAX_QTY_IN_CART)) {
        $quantity = MAX_QTY_IN_CART;
      }	 
// eof qpbpp
$this->contents[$products_id_string]['discount_categories_id'] = $discount_categories_id;
$this->contents[$products_id_string]['qty'] = $quantity;
// update database
if (tep_session_is_registered('customer_id')) tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id_string) . "'");
// 2.3.3
// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
  $this->cartID = $this->generate_cart_id();
// eof 2.3.3
  
  if (is_array($attributes)) {
    reset($attributes);
    while (list($option, $value) = each($attributes)) {
        // BOM - Options Catagories
        $attr_value = NULL;
        if ( !is_array($value) ) {
           $this->contents[$products_id]['attributes'][$option] = $value;
           $attr_value = $value;
           if (tep_session_is_registered('customer_id')) {
		       tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id_string) . "' and products_options_id = '" . (int)$option . "'");
           } elseif ( isset($attributes[$option]['t']) && !empty($attributes[$option]['t']) ) {
              $this->contents[$products_id_string]['attributes'][$option] = $value;
              $attr_value = htmlspecialchars(stripslashes($attributes[$option]['t']), ENT_QUOTES);
              if (tep_session_is_registered('customer_id')) {
			     tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id_string) . "' and products_options_id = '" . (int)$option . "'");
              } elseif ( isset($attributes[$option]['c']) ) {
                 $this->contents[$products_id_string]['attributes'][$option] = $value;
                 foreach ($value as $v) {
                   $attr_value = $v;
                   if (tep_session_is_registered('customer_id')) {
				      tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int)$value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id_string) . "' and products_options_id = '" . (int)$option . "'");
					  
                   }					  
                 }
              }
              // EOM - Options Catagories
		    }
        }
    }
  }
}

    function cleanup() {
      global $customer_id;

      reset($this->contents);
      while (list($key,) = each($this->contents)) {
        if ($this->contents[$key]['qty'] < 1) {
          unset($this->contents[$key]);
// remove from database
          if (tep_session_is_registered('customer_id')) {
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($key) . "'");
          }
        }
      }
    }

    function count_contents() {  // get total number of items in cart 
      $total_items = 0;
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $total_items += $this->get_quantity($products_id);
        }
      }

      return $total_items;
    }

    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }

    function in_cart($products_id) {
      if (isset($this->contents[$products_id])) {
        return true;
      } else {
        return false;
      }
    }

    function remove($products_id) {
      global $customer_id;

      // OTF contrib begins
      $products_id = tep_get_uprid($products_id, $attributes);
      // OTF contrib ends

      unset($this->contents[$products_id]);
// remove from database
      if (tep_session_is_registered('customer_id')) {
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
      }

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function remove_all() {
      $this->reset( $this->contents );
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          $product_id_list .= ', ' . $products_id;
        }
      }

      return substr($product_id_list, 2);
    }

    function calculate() {
//      global $currencies;
     global $currencies, $languages_id, $pfs; // for qpbpp added: $languages_id, $pfs

      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) return 0;
	  
// BOF Separate Pricing Per Customer
// global variable (session) $sppc_customer_group_id -> class variable cg_id
      $this->cg_id = $this->get_customer_group_id();
// EOF Separate Pricing Per Customer
	  
	  
// BOF qpbpp
        $discount_category_quantity = array(); // calculates no of items per discount category in shopping basket
      foreach ($this->contents as $products_id => $contents_array) {
	      if(tep_not_null($contents_array['discount_categories_id'])) {
	        if (!isset($discount_category_quantity[$contents_array['discount_categories_id']])) {
		        $discount_category_quantity[$contents_array['discount_categories_id']] = $contents_array['qty'];
	        } else {
		        $discount_category_quantity[$contents_array['discount_categories_id']] += $contents_array['qty'];
	        }
	      }
      } // end foreach

   $pf = new PriceFormatter;
// EOF qpbpp 

      reset($this->contents);
      while (list($products_id, $attr_value,  ) = each($this->contents)) { // eric
        $qty = $this->contents[$products_id]['qty'];
// BOF qpbpp        
      if (tep_not_null($this->contents[$products_id]['discount_categories_id'])) {
        $nof_items_in_cart_same_cat = $discount_category_quantity[$this->contents[$products_id]['discount_categories_id']];
        $nof_other_items_in_cart_same_cat = $nof_items_in_cart_same_cat - $qty;
      } else {
          $nof_other_items_in_cart_same_cat = 0;
      }
// EOF qpbpp		

// products price

       $pf->loadProduct($products_id, $languages_id);
        if ($product = $pfs->getPriceFormatterData($products_id)) {
          $prid = $product['products_id'];
          $products_tax = tep_get_tax_rate($product['products_tax_class_id']);
          $products_price = $pf->computePrice($qty ) ;
		  //$nof_other_items_in_cart_same_cat); // eric
// EOF qpbpp

          $products_weight = $product['products_weight'];
// bof products price cost
	      $products_cost = $product['products_cost'];		  
// eof products price cost
          $this->total += $currencies->calculate_price($products_price, $products_tax, $qty);
          $this->weight += ($qty * $products_weight);
        }

// attributes price
// BOF SPPC attributes mod
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
       $where = " AND ((";
        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
         $where .= "options_id = '" . (int)$option . "' AND options_values_id = '" . (int)$value . "') OR (";
       }
       $where=substr($where, 0, -5) . ')';
    
       $attribute_price_query = tep_db_query("SELECT products_attributes_id, options_values_price, price_prefix FROM " . TABLE_PRODUCTS_ATTRIBUTES . " WHERE products_id = '" . (int)$products_id . "'" . $where ."");

       if (tep_db_num_rows($attribute_price_query)) { 
	       $list_of_prdcts_attributes_id = '';
				 // empty array $attribute_price
				 $attribute_price = array();
	       while ($attributes_price_array = tep_db_fetch_array($attribute_price_query)) { 
		   $attribute_price[] =  $attributes_price_array;
		   $list_of_prdcts_attributes_id .= $attributes_price_array['products_attributes_id'].",";
            }
	       if (tep_not_null($list_of_prdcts_attributes_id) && $this->cg_id != '0') { 
         $select_list_of_prdcts_attributes_ids = "(" . substr($list_of_prdcts_attributes_id, 0 , -1) . ")";
	 $pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id IN " . $select_list_of_prdcts_attributes_ids . " AND customers_group_id = '" . $this->cg_id . "'");
	 while ($pag_array = tep_db_fetch_array($pag_query)) {
		 $cg_attr_prices[] = $pag_array;
	 }

	 // substitute options_values_price and prefix for those for the customer group (if available)
	 if ($customer_group_id != '0' && tep_not_null($cg_attr_prices)) {
	    for ($n = 0 ; $n < count($attribute_price); $n++) {
		 for ($i = 0; $i < count($cg_attr_prices) ; $i++) {
			 if ($cg_attr_prices[$i]['products_attributes_id'] == $attribute_price[$n]['products_attributes_id']) {
				$attribute_price[$n]['price_prefix'] = $cg_attr_prices[$i]['price_prefix'];
				$attribute_price[$n]['options_values_price'] = $cg_attr_prices[$i]['options_values_price'];
			 }
		 } // end for ($i = 0; $i < count($cg_att_prices) ; $i++)
          }
        } // end if ($customer_group_id != '0' && (tep_not_null($cg_attr_prices))
      } // end if (tep_not_null($list_of_prdcts_attributes_id) && $customer_group_id != '0')
// now loop through array $attribute_price to add up/substract attribute prices

   for ($n = 0 ; $n < count($attribute_price); $n++) {
            if ($attribute_price[$n]['price_prefix'] == '+') {
              $this->total += $currencies->calculate_price($attribute_price[$n]['options_values_price'], $products_tax, $qty);
            } else {
              $this->total -= $currencies->calculate_price($attribute_price[$n]['options_values_price'], $products_tax, $qty);
        }
   } // end for ($n = 0 ; $n < count($attribute_price); $n++)
          } // end if (tep_db_num_rows($attribute_price_query))
        } // end if (isset($this->contents[$products_id]['attributes'])) 
      }
    }
// EOF SPPC attributes mod

// function attributes_price changed partially according to FalseDawn's post
// http://forums.oscommerce.com/index.php?showtopic=139587
// changed completely for Separate Pricing Per Customer, attributes mod
    function attributes_price($products_id) {
// global variable (session) $sppc_customer_group_id -> class variable cg_id
    $this->cg_id = $this->get_customer_group_id();
		
      if (isset($this->contents[$products_id]['attributes'])) {
        reset($this->contents[$products_id]['attributes']);
       $where = " AND ((";
        while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
         $where .= "options_id = '" . (int)$option . "' AND options_values_id = '" . (int)$value . "') OR (";
       }
       $where=substr($where, 0, -5) . ')';
    
       $attribute_price_query = tep_db_query("SELECT products_attributes_id, options_values_price, price_prefix FROM " . TABLE_PRODUCTS_ATTRIBUTES . " WHERE products_id = '" . (int)$products_id . "'" . $where ."");
 			 
      if (tep_db_num_rows($attribute_price_query)) {
	       $list_of_prdcts_attributes_id = '';
	       while ($attributes_price_array = tep_db_fetch_array($attribute_price_query)) { 
		   $attribute_price[] =  $attributes_price_array;
		   $list_of_prdcts_attributes_id .= $attributes_price_array['products_attributes_id'].",";
          }

	       if (tep_not_null($list_of_prdcts_attributes_id) && $this->cg_id != '0') { 
         $select_list_of_prdcts_attributes_ids = "(" . substr($list_of_prdcts_attributes_id, 0 , -1) . ")";
	 $pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id IN " . $select_list_of_prdcts_attributes_ids . " AND customers_group_id = '" . $this->cg_id . "'");
	 while ($pag_array = tep_db_fetch_array($pag_query)) {
		 $cg_attr_prices[] = $pag_array;
	 }

	 // substitute options_values_price and prefix for those for the customer group (if available)
	 if ($customer_group_id != '0' && tep_not_null($cg_attr_prices)) {
	    for ($n = 0 ; $n < count($attribute_price); $n++) {
		 for ($i = 0; $i < count($cg_attr_prices) ; $i++) {
			 if ($cg_attr_prices[$i]['products_attributes_id'] == $attribute_price[$n]['products_attributes_id']) {
				$attribute_price[$n]['price_prefix'] = $cg_attr_prices[$i]['price_prefix'];
				$attribute_price[$n]['options_values_price'] = $cg_attr_prices[$i]['options_values_price'];
        }
		 } // end for ($i = 0; $i < count($cg_att_prices) ; $i++)
      }
        } // end if ($customer_group_id != '0' && (tep_not_null($cg_attr_prices))
      } // end if (tep_not_null($list_of_prdcts_attributes_id) && $customer_group_id != '0')
// now loop through array $attribute_price to add up/substract attribute prices

   for ($n = 0 ; $n < count($attribute_price); $n++) {
            if ($attribute_price[$n]['price_prefix'] == '+') {
              $attributes_price += $attribute_price[$n]['options_values_price'];
            } else {
              $attributes_price -= $attribute_price[$n]['options_values_price'];
            }
   } // end for ($n = 0 ; $n < count($attribute_price); $n++)
      return $attributes_price;
       } else { // end if (tep_db_num_rows($attribute_price_query))
         return 0;
       } 
     }  else { // end if (isset($this->contents[$products_id]['attributes']))
       return 0;
    }
   } // end of function attributes_price, modified for SPPC with attributes

    function get_products() {
//      global $languages_id;
      global $languages_id, $pfs;

// BOF Separate Pricing Per Customer
      $this->cg_id = $this->get_customer_group_id();
// EOF Separate Pricing Per Customer	  

      if (!is_array($this->contents)) return false;

	  // BOF qpbpp
      $discount_category_quantity = array();
      foreach ($this->contents as $products_id => $contents_array) {
	      if(tep_not_null($contents_array['discount_categories_id'])) {
	        if (!isset($discount_category_quantity[$contents_array['discount_categories_id']])) {
		        $discount_category_quantity[$contents_array['discount_categories_id']] = $contents_array['qty'];
	        } else {
		        $discount_category_quantity[$contents_array['discount_categories_id']] += $contents_array['qty'];
	        }
	      }
      } // end foreach
      
      $pf = new PriceFormatter;
      // EOF qpbpp

      $products_array = array();
      reset($this->contents);
      while (list($products_id, $attr_value, ) = each($this->contents)) { // eric
      $pf->loadProduct($products_id, $languages_id); // does query if necessary and adds to 
      // PriceFormatterStore or gets info from it next
	  if ($products = $pfs->getPriceFormatterData($products_id)) {
       if (tep_not_null($this->contents[$products_id]['discount_categories_id'])) {
          $nof_items_in_cart_same_cat =  $discount_category_quantity[$this->contents[$products_id]['discount_categories_id']];
          $nof_other_items_in_cart_same_cat = $nof_items_in_cart_same_cat - $this->contents[$products_id]['qty'];
        } else {
          $nof_other_items_in_cart_same_cat = 0;
        }
          $products_price = $pf->computePrice($this->contents[$products_id]['qty']) ; // eric
		  //, $nof_other_items_in_cart_same_cat);
// EOF qpbpp
          $products_array[] = array('id' => $products_id,
                                    'name' => $products['products_name'],
                                    'model' => $products['products_model'],
                                    'image' => $products['products_image'],
// BOF qpbpp
                                    'discount_categories_id' => $this->contents[$products_id]['discount_categories_id'],
// EOF qpbpp					
// bof product cost price
                                    'cost' => $products['products_cost'],
// 	eof product cost price			
                                    'price' => $products_price,
                                    'quantity' => $this->contents[$products_id]['qty'],
                                    'weight' => $products['products_weight'],
                                    'final_price' => ($products_price + $this->attributes_price($products_id)),
                                    'tax_class_id' => $products['products_tax_class_id'],
                                    // OTF contrib begins
                                    //'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''));
                                    'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''),
                                    'attributes_values' => (isset($this->contents[$products_id]['attributes_values']) ? $this->contents[$products_id]['attributes_values'] : ''));
                                    // OTF contrib ends
        }
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }

    function show_weight() {
      $this->calculate();

      return $this->weight;
    }

    function generate_cart_id($length = 5) {
      return tep_create_random_value($length, 'digits');
    }

    function get_content_type() {
      $this->content_type = false;

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          if (isset($this->contents[$products_id]['attributes'])) {
            reset($this->contents[$products_id]['attributes']);
            while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
              $virtual_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$products_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
              $virtual_check = tep_db_fetch_array($virtual_check_query);

              if ($virtual_check['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';

                return $this->content_type;
                break;
              default:
                $this->content_type = 'physical';
                break;
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }
 // added for Separate Pricing Per Customer, returns customer_group_id
    function get_customer_group_id() {
// bof multi stores 	
      if (isset($_SESSION['sppc_customer_group_id']) ) { // && $_SESSION['sppc_customer_group_id'] != '0'
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