<?php
/*
  $Id: option_types.php 2009-06-01 $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 AvanOsch for http://shop.crystalcopy.nl

  Released under the GNU General Public License
*/

 // - Zappo - Option Types v2 - Use some easy shorter names for products_options_name values
	$Default = false;  // Set this value to true if current option is Default (drowpdown) (see below)
	$ProdOpt_ID = $products_options_name['products_options_id'];
    $ProdOpt_Name = $products_options_name['products_options_name'];
    $ProdOpt_Comment = $products_options_name['products_options_comment'];
    $ProdOpt_Length = $products_options_name['products_options_length'];
// BOF SPPC attributes mod  
    $products_attribs_query = tep_db_query("select distinct products_attributes_id, options_values_id, options_values_price, price_prefix from " . 
	                                        TABLE_PRODUCTS_ATTRIBUTES . " where 
											    products_id='" . (int)tep_db_input($product_info['products_id']) . "' and 
												options_id = '" . $ProdOpt_ID . "' and 
												find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0
												order by products_options_sort_order");
																					  
      $products_options_array = tep_db_fetch_array($products_attribs_query) ; 

      if ( $customer_group_id != '0') { 
	    $pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . 
		                              TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " 
									  where products_id = '" . (int)tep_db_input($product_info['products_id']) . "' and 
									        products_attributes_id = '" . $products_options_array['products_attributes_id']    . "'  and 
											customers_group_id = '" . $customer_group_id . "'");																
        $cg_attr_prices = tep_db_fetch_array($pag_query) ;
		if (tep_not_null($cg_attr_prices)) {
		   $products_options_array['price_prefix'] = $cg_attr_prices['price_prefix'];
		   $products_options_array['options_values_price'] = $cg_attr_prices['options_values_price'];		 		 
		}
      }
// EOF SPPC attributes mod
	// Get Price for Option Values (Except for Multi-Options (Like Dropdown and Radio))
    if ($products_options_array['options_values_price'] != '0') {
      $tmp_html_price = 	'(' . $products_options_array['price_prefix'] . $currencies->display_price($products_options_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
    } else {
	  $tmp_html_price = '';
	}
	switch ($products_options_name['products_options_type']) {
    case OPTIONS_TYPE_TEXT:
      $tmp_html = '<input type="text" name="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" id="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" size="' . $ProdOpt_Length .'" maxlength="' . $ProdOpt_Length . '"
                             value="' . $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID] .'"';
      if (OPTIONS_TYPE_PROGRESS == 'Text' || OPTIONS_TYPE_PROGRESS == 'Both') {
        $tmp_html .= 'onKeyDown="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                               onKeyUp="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                               onFocus="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"> &nbsp; ' . $ProdOpt_Comment . $tmp_html_price .
                              '<div id="counterbar_'. $ProdOpt_ID . '" class="progress"><div id="progressbar_'. $ProdOpt_ID . '" class="progress-bar" role="progressbar"></div></div>
                               <script>textCounter(document.getElementById("id[' . TEXT_PREFIX . $ProdOpt_ID . ']"),"progressbar_' . $ProdOpt_ID . '",' . $ProdOpt_Length . ',"counterbar_'. $ProdOpt_ID . '")</script>';
      } else {
        $tmp_html .= '>' . $ProdOpt_Comment . $tmp_html_price ;
      } 
 

      $prod_attributes .= '      <div class="form-group">' . PHP_EOL ;
      $prod_attributes .= '	       <label for="id[' . TEXT_PREFIX  . $ProdOpt_ID . ']" class="label label-info col-xs-3"><h6>' . $ProdOpt_Name . ' :' . '</h6></label>' . PHP_EOL ;
      $prod_attributes .= '		   <div class="col-ss-9">' . PHP_EOL ;
      $prod_attributes .=             $tmp_html  ;
      $prod_attributes .= '        </div>' . PHP_EOL ;
      $prod_attributes .= '      </div> ' . PHP_EOL ;
      $prod_attributes .= '	     <br />' . PHP_EOL ;

    break;

    case OPTIONS_TYPE_TEXTAREA:
      $tmp_html = '<textarea wrap="soft" rows="5" COLS="70" name="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" 
                             id="id[' . TEXT_PREFIX . $ProdOpt_ID . ']"';
      if (OPTIONS_TYPE_PROGRESS == 'TextArea' || OPTIONS_TYPE_PROGRESS == 'Both') {
        $tmp_html .= 'onKeyDown="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                                onKeyUp="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                                onFocus="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')">' . 
                                $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID] . '</textarea>
                                <div id="counterbar_'. $ProdOpt_ID . '" class="progress"><div id="progressbar_'. $ProdOpt_ID . '" class="progress-bar" role="progressbar"></div></div>
                                <script>textCounter(document.getElementById("id[' . TEXT_PREFIX . $ProdOpt_ID . ']"),"progressbar_' . $ProdOpt_ID . '",' . $ProdOpt_Length . ',"counterbar_'. $ProdOpt_ID . '")</script>';
      } else {
        $tmp_html .= '>' . $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID] . '</textarea>';
      } 

      $prod_attributes .= '      <div class="form-group text-left ">'. PHP_EOL ;
      $prod_attributes .= '	        <label for="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" class="col-xs-3  label label-info"><h6>' . $ProdOpt_Name . ' :<br></h6>' . $ProdOpt_Comment . ' ' . $tmp_html_price . '</label>' . PHP_EOL ;
      $prod_attributes .= '		    <div class="col-xs-9">'. PHP_EOL ;
      $prod_attributes .=              $tmp_html ;
      $prod_attributes .= '         </div>'. PHP_EOL ;
      $prod_attributes .= '       </div> 	  '. PHP_EOL ;
      $prod_attributes .= '	      <br />'. PHP_EOL ;

    break;

    case OPTIONS_TYPE_RADIO:
      $tmp_html = '';
      $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_id 
	       from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . 
		            TABLE_PRODUCTS_OPTIONS_VALUES . " pov 
		   where pa.products_id = '" . (int)$product_info['products_id'] . "' 
		     and pa.options_id = '" . $ProdOpt_ID . "' 
			 and pa.options_values_id = pov.products_options_values_id 
			 and pov.language_id = '" . $languages_id . "' 
			 and find_in_set('".$customer_group_id."', pa.attributes_hide_from_groups) = 0 order by pa.products_options_sort_order");
			 
      $tmp_html .= '<div class="form-group">' . PHP_EOL ;
	  $tmp_html .= '<div class="col-xs-10">' . PHP_EOL ;			 
      while ($products_options_array = tep_db_fetch_array($products_options_query)) {
        if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID]) && ($products_options_array['products_options_values_id'] == $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID])) {
          $checked = true;
        } else {
          $checked = false;
        }

        $tmp_html .= tep_bootstrap_radio_field('id[' . $ProdOpt_ID . ']', 'id[' . $ProdOpt_ID . ']', $products_options_array['products_options_values_id'], $products_options_array['products_options_values_name'], $checked ) ;


        if ( $customer_group_id != '0') { 
	      $pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " 
		                                 . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id = '" . $products_options_array['products_attributes_id'] . "' and customers_group_id = '" . $customer_group_id . "'");										 
		  $pag_array = tep_db_fetch_array($pag_query) ;
		  if ( $pag_array['options_values_price'] != 0 ) {
            $tmp_html .= ' (' . $pag_array['price_prefix'] . $currencies->display_price($pag_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';			
          } else {
            $tmp_html .= '' ;
          }
		} else {
          if ($products_options_array['options_values_price'] != '0') {
            $tmp_html .= ' (' . $products_options_array['price_prefix'] . $currencies->display_price($products_options_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
          }		
		}
// EOF sppc attribute mod		
      }
      $tmp_html .= '</div>' . PHP_EOL ;
	  $tmp_html .= '</div>' . PHP_EOL ;	  

      $prod_attributes .= '      <div class="form-group">' . PHP_EOL ;
      $prod_attributes .= '	        <label for="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" class="col-xs-3  label label-info"><h6>'. $ProdOpt_Name . ' :</h6><br />' . $ProdOpt_Comment . ' ' . $tmp_html_price . '</label>' . PHP_EOL ;
      $prod_attributes .= '		    <div class="col-xs-9">' . PHP_EOL ;
      $prod_attributes .=              $tmp_html  ;
      $prod_attributes .= '         </div>' . PHP_EOL ;
      $prod_attributes .= '      </div> 		  ' . PHP_EOL ;
      $prod_attributes .= '	     <br />' . PHP_EOL ;

    break;

    case OPTIONS_TYPE_CHECKBOX:
      if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID])) {
        $checked = true;
      } else {
        $checked = false;
      }
      $tmp_html = tep_bootstrap_checkbox_field('id[' . $ProdOpt_ID . ']', 'id[' . $ProdOpt_ID . ']', $products_attribs_array['options_values_id'], $ProdOpt_Comment, $checked ) . ' &nbsp; ' . PHP_EOL;
      $tmp_html .= $tmp_html_price . PHP_EOL; 

      $prod_attributes .= '      <div class="form-group">' . PHP_EOL ;
      $prod_attributes .= '	        <label for="id['. TEXT_PREFIX . $ProdOpt_ID . ']" class="col-xs-3  label label-info">' . $ProdOpt_Name . ' : ' . '</label>' . PHP_EOL ;
      $prod_attributes .= '		    <div class="col-xs-9">' . PHP_EOL ;
      $prod_attributes .=              $tmp_html  ; 
      $prod_attributes .= '         </div>' . PHP_EOL ;
      $prod_attributes .= '       </div> 	' . PHP_EOL ;
      $prod_attributes .= '       <br /><br />' . PHP_EOL ;

    break;

    case OPTIONS_TYPE_FILE:
      $number_of_uploads++;
  		//BOF - Zappo - Option Types v2 - Added dropdown with previously uploaded files
			if ($old_uploads == true) unset($uploaded_array);
      $uploaded_array[] = array('id' => '', 'text' => TEXT_NONE);
      $uploaded_files_query = tep_db_query("select files_uploaded_name from " . TABLE_FILES_UPLOADED . " where sesskey = '" . tep_session_id() . "' or customers_id = '" . (int)$customer_id . "'");
      while ($uploaded_files = tep_db_fetch_array($uploaded_files_query)) {
        $uploaded_array[] = array('id' => $uploaded_files['files_uploaded_name'], 'text' => $uploaded_files['files_uploaded_name'] . ($tmp_html_price ? ' - ' . $tmp_html_price : ''));
				$old_uploads = true;
			}
      $tmp_html = '<input type="file" name="id[' . TEXT_PREFIX . $ProdOpt_ID . ']"  id="id[' . TEXT_PREFIX . $ProdOpt_ID . ']">' .         // File field with new upload
      tep_draw_hidden_field(UPLOAD_PREFIX . $number_of_uploads, $ProdOpt_ID). PHP_EOL ;    // Hidden field with number of this upload (for this product)
			$tmp_html .= $tmp_html_price . PHP_EOL ;
			if	($old_uploads == true) $tmp_html .= '<br />' . tep_bootstrap_pull_down_menu(TEXT_PREFIX . UPLOAD_PREFIX . $number_of_uploads, $uploaded_array, TEXT_PREFIX . UPLOAD_PREFIX . $number_of_uploads, $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID] ). PHP_EOL  ;
			//tep_draw_pull_down_menu(TEXT_PREFIX . UPLOAD_PREFIX . $number_of_uploads, $uploaded_array, $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID]);
			
	    //EOF - Zappo - Option Types v2 - Added dropdown with previously uploaded files 

      $prod_attributes .= '      <div class="form-group">' . PHP_EOL ;
      $prod_attributes .= '          <label for="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" class="col-xs-3 label label-info"><h6>' . $ProdOpt_Name . ' :' . (($old_uploads == true) ? '</h6><br />' . TEXT_PREV_UPLOADS . ': ' : '') . '</label>' . PHP_EOL ;
      $prod_attributes .= '		     <div class="col-xs-9">' . PHP_EOL ;
      $prod_attributes .=               $tmp_html  ; 
      $prod_attributes .= '          </div>' . PHP_EOL ;
      $prod_attributes .= '      </div>' . PHP_EOL ;
      $prod_attributes .= '	     <br />' . PHP_EOL ;

    break;

//BOF - Zappo - Added Image Selector Option
    case OPTIONS_TYPE_IMAGE:
      $Image_Opticount_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " 
	             where products_id='" . (int)$product_info['products_id'] . "' 
				 and options_id ='" . (int)$ProdOpt_ID . "' 
				 and find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0  ");
      $Image_Opticount = tep_db_fetch_array($Image_Opticount_query);
      $Image_displayed = 0;
      $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_id 
	                  from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov 
					  where pa.products_id = '" . (int)$product_info['products_id'] . "' 
					      and pa.options_id = '" . (int)$ProdOpt_ID . "' 
						  and pa.options_values_id = pov.products_options_values_id 
						  and pov.language_id = '" . (int)$languages_id . "' 
						  and find_in_set('".$customer_group_id."', pa.attributes_hide_from_groups) = 0 order by pa.products_options_sort_order");
						  
      while ($products_options = tep_db_fetch_array($products_options_query)) {
        $pOptValName = $products_options['products_options_values_name'];
        $Image_displayed++;
        if ($products_options['options_values_price'] != '0') {
          $option_price = ' (' . $products_options['price_prefix'] . ' ' . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
        } else {
          $option_price = '';
        }
// BOF sppc attribute mod
        if ( $customer_group_id != '0') { 
	      $pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " 
		                                 . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id = '" . $products_options['products_attributes_id'] . "' and customers_group_id = '" . $customer_group_id . "'");										 
		  $pag_array = tep_db_fetch_array($pag_query) ;
		  if ( $pag_array['options_values_price'] != 0 ) {
            $option_price = ' (' . $pag_array['price_prefix'] . ' ' . $currencies->display_price($pag_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          } else {
            $option_price = '' ;
          }
		}
// EOF sppc attribute mod		
        $Image_Dropdown_ID = 'id[' . $ProdOpt_ID . ']';
        $Image_Name = (OPTIONS_TYPE_IMAGENAME == 'Name') ? $products_options['products_options_values_name'] : $products_options['products_options_values_id'];
        $Real_Image_Name = OPTIONS_TYPE_IMAGEPREFIX . $Image_Name . ((OPTIONS_TYPE_IMAGELANG == 'Yes') ? '_'.$languages_id : '') . '.jpg';
        if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID]) && ($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID] == $products_options['products_options_values_id'])) {
          $Image_Dropdown[$product_info['products_id']] .= '<option value="' . $products_options['products_options_values_id'] . '" SELECTED>' . $pOptValName . $option_price . '</option>';
          $First_ImageText[$product_info['products_id']] = '<img src="' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '" alt="'.$pOptValName.'" title="'.$pOptValName.'">';
          $ImageText[$product_info['products_id']] .= '"<img src=\"' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '\" alt=\"'.$pOptValName.'\" title=\"'.$pOptValName.'\">"';
        } else {
          $Image_Dropdown[$product_info['products_id']] .= '<option value="' . $products_options['products_options_values_id'] . '">' . $pOptValName . $option_price . '</option>';
          $ImageText[$product_info['products_id']] .= '"<img src=\"' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '\" alt=\"'.$pOptValName.'\" title=\"'.$pOptValName.'\">"';
          if ($First_ImageText[$product_info['products_id']] == '' && $Image_displayed == 1) $First_ImageText[$product_info['products_id']] = '<img src="' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '" alt="'.$pOptValName.'" title="'.$pOptValName.'">';
        }
        // BOF - Zappo - PreLoad the Images
        if ($Image_displayed == 1) echo '<div id="ImagePreload">';		
        echo '<span style="display:none; visibility:hidden;"><img src="' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '" alt="'.$pOptValName.'" title="'.$pOptValName.'"></span>';
        if ($Image_displayed != $Image_Opticount['total']) {
          $ImageText[$product_info['products_id']] .= ',';
        } else { // - Zappo - PreLoad the Images - Close Div...
		  echo '</div>'; 
		}
		// EOF - Zappo - PreLoad the Images
      }
      $ImageSelector_Name = $ProdOpt_Name . ': <script language="JavaScript" type="text/JavaScript">var ImageText'.$product_info['products_id'] . ' = new Array(' . $ImageText[$product_info['products_id']] . ')</script>';
      $ImageSelector_Dropdown = '<select class="form-control" id="' . $Image_Dropdown_ID . '" name="' . $Image_Dropdown_ID . '" onChange="document.getElementById(\'ImageSelect' . $product_info['products_id'] . '\').innerHTML=ImageText'.$product_info['products_id'].'[this.selectedIndex];">' . $Image_Dropdown[$product_info['products_id']] . '</select> ' . $ProdOpt_Comment;

      $prod_attributes .= '      <div class="form-group">' . PHP_EOL ;
      $prod_attributes .= '          <label for="' . $Image_Dropdown_ID . '" class="col-xs-3 label label-info"><h6>' . $ImageSelector_Name . '</h6></label>'. PHP_EOL ;
      $prod_attributes .= '		     <div class="col-xs-9">'. PHP_EOL ;
      $prod_attributes .=                $ImageSelector_Dropdown ; 
      $prod_attributes .= '		         <p><div id="ImageSelect' . $product_info['products_id'] . '">' . $First_ImageText[$product_info['products_id']] . '</div></p>' . PHP_EOL ;
      $prod_attributes .= '           </div>'. PHP_EOL ;
      $prod_attributes .= '      </div>'. PHP_EOL ;
      $prod_attributes .= '	     <br /> <br />'. PHP_EOL ;


    break;
//EOF - Zappo - Added Image Selector Option

    default:
    $Default = true;  // Set this value to check if current option is Default (drowpdown)
    // - Zappo - Option Types v2 - Default action is (standard) dropdown list. If something is not correctly set, we should always fall back to the standard.
  }
?>